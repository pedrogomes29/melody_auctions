<?php

namespace App\Http\Controllers;

use App\Events\AuctionEnded;
use App\Events\AuctionEnding;
use App\Models\Follow;
use App\Models\Auction;
use App\Models\Category;
use App\Models\Manufactor;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDOException;
use Carbon\Carbon;	
use App\Events\AuctionCancelled;

class AuctionController extends Controller
{
    private function search_auctions_helper(Request $request){
        try{
            $search = $request->search;
            $categoryId = $request->categoryId;
            $lower_price = isset($request->minPrice) ? floatval($request->minPrice) : 0;
            $highest_price = isset($request->maxPrice) ? floatval($request->maxPrice) : Auction::maxPrice();
            
            $order = $request->order ?? 0;
            
            $useSearch = !is_null($search);
            $useCategory = !is_null($categoryId);
            $useType = !is_null($request->type);
            $offset = (int)$request->offset??0;
            DB::beginTransaction();
            DB::statement('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ READ ONLY');
            
            $auctionsAfterFilter = Auction::where('cancelled','<>',1);

            if ($useSearch)
                $auctionsAfterFilter = $auctionsAfterFilter->whereRaw('tsvectors @@ to_tsquery(\'english\', ?)', $search.':*');

            if($useCategory)
                $auctionsAfterFilter->where('category_id',$categoryId);
            
            if($useType){
                if($request->type==="uninitiated")
                    $auctionsAfterFilter->where('startdate','>',now());
                elseif($request->type==="active")
                    $auctionsAfterFilter->where('enddate','>',now())
                                        ->where('startdate','<',now());
                elseif($request->type==="closed")
                    $auctionsAfterFilter->where('enddate','<',now());
            }
            
            $auctionsAfterFilter->where(function($query) use($lower_price, $highest_price){
                $query->whereBetween('currentprice', [$lower_price, $highest_price])
                        ->orWhere(function($query) use($lower_price, $highest_price) {
                            $query->whereBetween('startprice', [$lower_price, $highest_price])
                                    ->whereNull('currentprice');
                        });
            });
            
            
            $nrAuctions = $auctionsAfterFilter->count();
            
            $auctions = $auctionsAfterFilter->selectRaw('id,
                                                        CASE 
                                                            WHEN CURRENT_TIMESTAMP < startdate THEN startdate
                                                            ELSE enddate
                                                        END
                                                        as date,
                                                        name AS productName,
                                                        CASE WHEN currentPrice IS NULL
                                                            THEN startPrice
                                                            ELSE currentprice
                                                        END AS minBid,
                                                        currentprice,
                                                        startPrice,
                                                        photo,
                                                        CASE WHEN CURRENT_TIMESTAMP < enddate
                                                            THEN 1
                                                            ELSE 0
                                                        END AS active,
                                                        CASE WHEN CURRENT_TIMESTAMP < startdate
                                                            THEN 1
                                                            ELSE 0
                                                        END AS uninitiated,                                
                                                        (Select avg(rating) from reviews where reviewed_id = auctions.owner_id) as rating');
            if($order === '1')
                // order by price low to high
                $auctions->orderByRaw('CASE WHEN currentPrice IS NULL
                                            THEN startPrice
                                            ELSE currentprice+minbidsdif
                                        END');
            elseif($order === '2')
                // order by price high to low
                $auctions->orderByRaw('CASE WHEN currentPrice IS NULL
                                            THEN startPrice
                                            ELSE currentprice+minbidsdif
                                        END DESC');
            elseif($order === '3')
                // order by date
                $auctions->orderBy('enddate', 'DESC');
            elseif($order === '4')
                $auctions->orderBy('rating'); 
            else if( $order === '0' && $useSearch )
                $auctions->orderByRaw('ts_rank(tsvectors, to_tsquery(\'english\', ?)) DESC,name', [$search.':*']);
            else if( $order === '0')
                $auctions->orderBy('name');
            else
                $auctions->orderBy('enddate', 'DESC');
            

            $auctions = $auctions->offset($offset*10)->limit(10)->get();


            DB::commit();
            return [$auctions,$nrAuctions];
        }
        catch(PDOException $e){
            error_log($e->getMessage());
            DB::rollBack();
        }
        return [[],0];
    }
    public function search_results_html(Request $request){
        [$auctions,$nrAuctions]=$this->search_auctions_helper($request);
        $offset = ((int)$request->offset) ?? 0;
        

        return  view('partials.auctions')
                ->with('auctions',$auctions)
                ->with('nrAuctions',$nrAuctions)
                ->with('offset',$offset);
               
                
    }

    public function search_results_json(Request $request){
        $query = $request->search;
        return  response()->json(Auction::select('name','description','id')
                                        ->whereRaw('tsvectors @@ to_tsquery(\'english\', ?)', [$query.':*'])
                                        ->orderByRaw('ts_rank(tsvectors, to_tsquery(\'english\', ?)) DESC', [$query.':*'])
                                        ->take(10)
                                        ->get());
    }

    public function list(Request $request){
        $search = $request->search;
        $offset = ((int)$request->offset) ?? 0;
        [$auctions,$nrAuctions]=$this->search_auctions_helper($request);

        $categories = Category::select('name','id')
                                ->orderBy('name')
                                ->get();

        $categoryName = Category::select('name')
                                ->where('id','=',$request->categoryId)
                                ->get();
        
                                
        if(sizeof($categoryName)<=0)
            $categoryName = "Any category";
        else{
            $categoryName = $categoryName[0]->name;
        }

        $typeText = ucfirst($request->type??"Any type");

        $lower_price = isset($request->minPrice) ? floatval($request->minPrice) : 0;
        $highest_price = isset($request->maxPrice) ? floatval($request->maxPrice) : Auction::maxPrice();

        $order = $request->order ?? 0;
        if($order !== '0' && $order !== '1' && $order !== '2' && $order !== '3' && $order !== '4')
            $order = '0';

        return view('pages.auctions')
            ->with('nrAuctions',$nrAuctions)
            ->with('auctions',$auctions)
            ->with('search',$search)
            ->with('lower_price',$lower_price)
            ->with('highest_price',$highest_price)
            ->with('order',$order)
            ->with('type',$request->type)
            ->with('typeText',$typeText)
            ->with('categoryId',$request->categoryId)
            ->with('categoryName',$categoryName)
            ->with('categories',$categories)
            ->with('offset',$offset);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function edit($auctionId)
    {
        if (Auth::check()){
            $user_id = Auth::id();
            $auction = Auction::find($auctionId);
            if ($auction->owner_id == $user_id){
                return view('pages.auction_edit', ['auction' => $auction]);
            }
        }
        return redirect()->route('home');
    }


    /**
     *  the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */

    public function ownerUpdate(Request $request, $auctionId)
    {
        error_log("ownerUpdate");
        $auction = Auction::find($auctionId);
        $this->authorize('update', $auction);

        $success = true;
        $auction = Auction::find($auctionId);

        if ($request->input('name')==''){
            $errors[] = 'Auction name is required';
            $success = false;
        }

        if ($request->input('description')==''){
            $errors[] = 'Auction description is required';
            $success = false;
        }

        $validated = $request->validate([
            'name' => 'required|max:50',
            'description' => 'required',
            'minBidDif' => 'required|numeric|min:0.01',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ]);
        $errors = [];
        if ($auction->cancelled == 1){
            $errors[] = 'Auction has been cancelled';
            $success = false;
        }



        $auction->name = $request->input('name');
        $auction->description = $request->input('description');
        $auction->minbidsdif = $request->input('minBidDif');

        // Start date
        $inputStartDate = Carbon::parse($request->input('startDate'))->format('Y-m-d H:i:s.u'); // get the start date from the form
        $inputStartDate = substr($inputStartDate, 0, -3); // remove last 3 digits to be according to the dates saved in the database
        $nowDate = Carbon::now()->format('Y-m-d H:i:s.u'); // get current date
        $nowDate = substr($nowDate, 0, -3); // remove last 3 digits to be according to the dates saved in the database
    
        // check if the input date and the auction start date is in the future //check if input date is the current one stored in the database
        if ($auction->startdate == $inputStartDate){
            $auction->startdate = $inputStartDate;
        }
        else if (Carbon::parse($auction->startdate)->lt(Carbon::now())){
            $errors[] = 'The auction has already started';
            $success = false;
        }
        else if (Carbon::parse($inputStartDate)->lt(Carbon::now())) { 
            $errors[] = 'The start date must be in the future';
            $success = false; 
        }   else {
            $auction->startdate = $inputStartDate;
        }

        // End date
        $inputEndDate = Carbon::parse($request->input('endDate'))->format('Y-m-d H:i:s.u'); // get the end date from the form
        $inputEndDate = substr($inputEndDate, 0, -3); // remove last 3 digits to be according to the dates saved in the database

        // check if the input date and the auction start date is in the future //check if input date is the current one stored in the database
        if ($auction->enddate == $inputEndDate){
            $auction->enddate = $inputEndDate; 
        } else if (Carbon::parse($auction->startdate)->lt(Carbon::now())){
            $errors[] = 'The auction has already started';
            $success = false;
        } else if (Carbon::parse($inputEndDate)->lt(Carbon::now())) {
            $errors[] = 'The end date must be in the future';
            $success = false; 
        } else {
            $auction->enddate = $inputEndDate; 
        }


        if ($auction->startdate > $auction->enddate){
            $errors[] = 'The end date must be after the start date';
            $success = false;
        }

        
        
        if ($success){
            $auction->save();
            return response('Updated', 200);
        }


        return response()->json(['error' => $errors], 400);
    }
    public function ownerDelete($auctionId)
    {
        $auction = Auction::find($auctionId);
        $this->authorize('delete', $auction);
        if ($auction->cancelled){
            return response()->json(['error' => 'Cannot delete an auction that has been cancelled'], 400);
        }
        if ($auction->notStarted()){
            $auction->cancelled = 1;
            $auction->save();
            AuctionCancelled::dispatch(now(),$auction);
            return response('Deleted', 200);
        } else {
            return response()->json(['error' => 'Cannot delete an auction that has already started'], 400);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    public function adminUpdate(Request $request, $auctionId)
    {
        if (Auth::guard('admin')->user()){
            $validated = $request->validate([
                'name' => 'required|max:50',
                'description' => 'required',
            ]);

            $auction = Auction::find($auctionId);
            $auction->name = $request->input('name');
            $auction->description = $request->input('description');
            $auction->save();
            return response('Updated', 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    public function adminDelete(Request $request, $auctionId)
    {
        if (Auth::guard('admin')->user()){
            $auction = Auction::find($auctionId);
            $auction->cancelled = 1;
            $auction->save();
            return response('Deleted', 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    public function updatePhoto(Request $request,$auctionId)
    {

        error_log("updatePhoto");
        $auction = Auction::find($auctionId);
        $this->authorize('update', $auction);

        error_log("AUTHORIZE");
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        
        if ($auction->cancelled) {
            return response()->json(['error' => 'Auction has been cancelled'], 400);
        }

        if ($auction->notStarted()){
            $image_path = AuctionController::uploadImage($request);
            
            $auction->photo = $image_path;
            $auction->save();
            return response('Updated Photo', 200);
        } 

        return response()->json(['error' => 'Cannot update an auction that has already started'], 400);
    
        
    }

    public function defaultImage(Request $request,$auctionId)
    {
        $auction = Auction::find($auctionId);
        if (! Auth::guard('admin')->user()){
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $auction = Auction::find($auctionId);

        $auction->photo = '';
        $auction->save();
        
        
        return response('Updated Photo', 200);
    }


    public static function update(Request $request, $id)
    {
        $auction = Auction::find($id);
        $auction->name = $request->input('name');
        $auction->description = $request->input('description');
        $auction->save();
        return redirect('admin/auctions');
    }

    public static function destroy($id)
    {
        $auction = Auction::find($id);
        $auction->cancelled = 1;
        $auction->save();
        AuctionCancelled::dispatch(now(),$auction);
        return redirect('admin/');
    }
  
    public static function find($id)
    {
        $auction = Auction::find($id);
        return $auction;
    }

    public static function findAll()
    {
        $auctions = Auction::all();
        return $auctions;
    }
    
    public static function uploadImage($request ){

        $file = $request->file('photo')->store('image', 'public');
        error_log($file);

        return $file;
        
    }

    /**
     * Show the form for creating a new auction.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ( ! Auth::check() || !Auth::user()->can('create', Auction::class)){
            return redirect()->route('login');
        }

        return view('pages.create_auction', ['categories' => Category::all(), 'manufactors' => Manufactor::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if ( ! Auth::check() || !Auth::user()->can('create', Auction::class)){
            return redirect()->route('login');
        }

        $this->validate($request, [            
            'photo' => 'required|image|mimes:jpg,png,jpeg',
            'name' => 'required',
            'description' => 'required',
            'manufactor' => 'required',
            'category' => 'required|integer|min:0',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'startvalue' => 'required|numeric',
            'minbiddiff' => 'required|numeric',
        ]);

        $image_path = "";
        try{
            $errors = [];

            $auction = new Auction();

            $auction->name = $request->input('name');
            $auction->description = $request->input('description');
            $auction->startprice = floatval($request->input('startvalue'));
            $auction->minbidsdif = floatval($request->input('minbiddiff'));


            $auction->startdate = $request->input('startDate');
            $auction->enddate = $request->input('endDate');


            $auction->owner_id = Auth::id();
            
            $now = date('Y-m-d\TH:i:s');

            
            DB::beginTransaction();
            DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');

            if($auction->startdate < $now){
                $errors['startdate_error'] = 'Start date must be after now!';
                throw new PDOException('startdate error!');
            }

            if($auction->enddate < $auction->startdate){
                $errors['enddate_error'] = 'End date must be after Star Date!';
                throw new PDOException('enddate error!');
            }

            if($auction->startprice < 0.01){
                $errors['start_value'] = 'Start value must be at least 0.01!';
                throw new PDOException('start_value error!');
            }
                
            if($auction->minbidsdif < 0.01){
                $errors['minbiddiff'] = 'Min Bid difference must be at least 0.01!';
                throw new PDOException('minbiddiff error!');
            }


            if(!Category::find(intval($request->input('category')))->exists()){
                $errors['category_error'] = 'Category does not exists!';
                throw new PDOException('category error!');
            }

            $auction->category_id = intval($request->input('category'));

            $manufactor_name = ucfirst(strtolower($request->input('manufactor')));
            $manufactor = Manufactor::where('name', $manufactor_name)->first();
            if($manufactor === null){
                $manufactor = Manufactor::firstOrCreate(['name' => $manufactor_name, 'id' => Manufactor::max('id')+1]);
            }

            $auction->manufactor_id = $manufactor->id;
            
            $auction->photo = AuctionController::uploadImage($request);
            $auction->id = Auction::max('id') + 1;


            $auction->save();
            
            DB::commit();
        }catch(PDOException $e){

            if($auction->photo!==""){
                //TODO apagar imagem  
            }

            DB::rollBack();
            return redirect('auction/create')->withErrors($errors);
        }

        return redirect('auction/'.$auction->id);


    }

    /**
     * Display the auction page.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $auction = Auction::find($id);
        $followed = Follow::where('auction_id', $id)->get()->contains('authenticated_user_id', Auth::id());
        $messages = $auction->messages;
        if($auction){
            return view('pages.auction', ['auction' => $auction, 'followed' => $followed, 'messages'=>$messages]);
        }else{
            abort(404);
        }
    }

    public function bids(Request $request, $id){

        $offset =$request->offset;
        if($offset!==null){
            // Validate if a string is a valid number

            if(! preg_match('/^[0-9]+$/', $offset)){
                abort(404);
            }   
        }

        $auction = Auction::find($id);
        if($auction==null){
            abort(404);
        }

        $bids = [];
        if($offset!==null){
            $bids = $auction->bids_offset(intval($offset));
        }else{
            $bids =$auction->bids;
        }
        
        return view('partials.bids', ['bids' => $bids]);
    }


    public static function notifyEndingFunctions(){

        $endingAuctions = Auction::where('enddate', '<=', date("Y-m-d H:i:s", strtotime('+30 minutes')))
                                 ->where('already_notified_ending',false)
                                 ->get();

        foreach ($endingAuctions as $auction) {
            $auction->already_notified_ending = true;
            $auction->save();
            AuctionEnding::dispatch(now(), $auction);
        }

    }
    public static function notifyEndedFunctions(){
        
        $endedAuctions = Auction::where('enddate','<=',now())
                                ->where('already_notified_ended',false)->get();

        foreach ($endedAuctions as $auction) {
            $bidder = $auction->getLastBidder();
            if($bidder)
                $auction->winner_id = $bidder->id;
            $auction->already_notified_ended = true;
            $auction->save();
            AuctionEnded::dispatch(now(), $auction);
        }
    }
}
