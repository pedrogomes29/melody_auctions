<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use App\Models\Manufactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException; 


class AuctionController extends Controller
{

    
    private function uploadImage($request ){

        $image_path = $request->file('photo')->store('image', 'public');
        /*
        // resize image
        $image = Image::make(public_path("storage/{$image_path}"))->fit(1200, 1200); 


        error_log("Original");
        error_log($image_path);

        
        $width = imagesx($original);     // width of the original image
        $height = imagesy($original);    // height of the original image
        $square = min($width, $height);  // size length of the maximum square

        // Create and save a small square thumbnail
        $small = imagecreatetruecolor(200, 200);
        imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
        $image_path = $small->store('image', 'public');
        error_log("Small");
        error_log($image_path);


        // Calculate width and height of medium sized image (max width: 400)
        $mediumwidth = $width;
        $mediumheight = $height;
        if ($mediumwidth > 400) {
            $mediumwidth = 400;
            $mediumheight = $mediumheight * ( $mediumwidth / $width );
        }

        // Create and save a medium image
        error_log("Medium");
        $medium = imagecreatetruecolor($mediumwidth, $mediumheight);
        imagecopyresized($medium, $original, 0, 0, 0, 0, $mediumwidth, $mediumheight, $width, $height);
        $image_path = $medium->store('image', 'public');
        error_log($image_path);*/
        return $image_path;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //$this->authorize('create', Auction::class);
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
        $this->validate($request, [            
            'photo' => 'required|image|mimes:jpg,png,jpeg',
            'name' => 'required',
            'description' => 'required',
            'manufactor' => 'required',
            'category' => 'required|integer|min:0',
            'startdate' => 'required|date',
            'enddate' => 'required|date',
            'startvalue' => 'required|numeric',
            'minbiddiff' => 'required|numeric',
        ]);

        $image_path = "";
        try{
            $errors = [];

            error_log("sss");
            $auction = new Auction();

            $auction->name = $request->input('name');
            $auction->description = $request->input('description');
            $auction->startprice = floatval($request->input('startvalue'));
            $auction->minbidsdif = floatval($request->input('minbiddiff'));


            $auction->startdate = $request->input('startdate');
            $auction->enddate = $request->input('enddate');

            $auction->owner_id = 1; //TODO: mudar para o id do user logado

            //$this->authorize('store', $auction);

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
            
            $image_path = AuctionController::uploadImage($request);
            $auction->id = Auction::max('id') + 1;


            $auction->save();
            
            DB::commit();
        }catch(PDOException $e){

            error_log($e->getMessage());
            if($image_path!==""){
                // apagar imagem  
            }

            DB::rollBack();
            return redirect('auction/create')->withErrors($errors);
        }

        return redirect('auction/'.$auction->id);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $auction = Auction::find($id);
        if($auction){
            //$this->authorize('show', $auction);
            return view('pages.auction', ['auction' => $auction]);
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
        
        error_log($bids);
        return view('partials.bids', ['bids' => $bids]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function edit(Auction $auction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auction $auction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auction $auction)
    {
        //
    }
}
