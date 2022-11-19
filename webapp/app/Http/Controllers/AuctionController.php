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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCreate()
    {
        //TODO verificar se está logado
        return view('pages.create_auction', ['categories' => Category::all(), 'manufactors' => Manufactor::all()]);
    }
    
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
        error_log($request->input('name'));
        error_log($request->input('description'));
        error_log($request->input('manufactor'));
        error_log($request->input('category'));
        error_log($request->input('startdate'));
        error_log($request->input('enddate'));
        error_log($request->input('startvalue'));
        error_log($request->input('minbiddiff'));

        try{
            error_log("sss");
            $auction = new Auction();
            DB::beginTransaction();
            DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');

            error_log("NAME");
            $auction->name = $request->input('name');
            error_log("description");
            $auction->description = $request->input('description');
            error_log("enddate");
            $auction->enddate = $request->input('enddate');
            error_log("startprice");
            $auction->startprice = floatval($request->input('startprice'));
            error_log("minbiddiff");
            $auction->minbidsdif = floatval($request->input('minbiddiff'));
            error_log("startdate");
            $auction->startdate = $request->input('startdate');

            $auction->owner_id = 1; //TODO: mudar para o id do user logado
            error_log("category");
            $auction->category_id = intval($request->input('category'));

            error_log("manufactor");
            $manufactor_name = ucfirst(strtolower($request->input('manufactor')));
            $auction->manufactor_id = Manufactor::where('name', $manufactor_name)->firstOrCreate(['name' => $manufactor_name])->id;
            
            error_log("uploadImage");
            AuctionController::uploadImage($request);
            $auction->id = Auction::max('id') + 1;
            error_log("id");
            $auction->save();
            error_log("save");
            
            DB::commit();
        }catch(PDOException $e){

            error_log($e->getMessage());

            DB::rollBack();
        }

        return redirect('auction/'.$auction->id);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
