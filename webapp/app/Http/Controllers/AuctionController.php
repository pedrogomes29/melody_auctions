<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Auction;
use Illuminate\Support\Facades\DB;
use PDOException;
use Carbon\Carbon;	
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    private function search_auctions_helper(Request $request){
        try{
            $search = $request->search;
            $categoryId = $request->categoryId;
    
            $useSearch = !is_null($search);
            $useCategory = $categoryId !=='-1' && !is_null($categoryId);
            $useOnGoing = $request->ongoing!=='-1' && !is_null($request->ongoing);
            $onGoing = $request->ongoing==1;
            $offset = (int)$request->offset??0;
            DB::beginTransaction();
            DB::statement('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ READ ONLY');

            $auctionsAfterFilter = Auction::when($useSearch,
                                    function($query1) use($search,$categoryId,$onGoing,$useCategory,$useOnGoing){
                                        $query1->when($useCategory,
                                        function($query2) use ($search,$categoryId,$onGoing,$useOnGoing){
                                            $query2->when($useOnGoing,
                                            function($query3) use($search,$categoryId,$onGoing){
                                                    $query3->when($onGoing,
                                                    function($query) use($search,$categoryId){
                                                        $query->whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?) AND category_id=? AND enddate>CURRENT_TIMESTAMP',
                                                        [$search,$categoryId]);
                                                    },
                                                    function($query) use($search,$categoryId){
                                                        $query->whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?) AND category_id=? AND enddate<CURRENT_TIMESTAMP',
                                                        [$search,$categoryId]);
                                                    });
                                            },
                                            function($query) use($search,$categoryId){
                                                $query->whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?) AND category_id=?',
                                                        [$search,$categoryId]);
                                            });
                                        },
                                        function($query2) use($search,$onGoing,$useOnGoing){
                                            $query2->when($useOnGoing,
                                            function($query3) use($search,$onGoing){
                                                    $query3->when($onGoing,
                                                    function($query) use($search){
                                                        $query->whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?) AND enddate>CURRENT_TIMESTAMP',
                                                        [$search]);
                                                    },
                                                    function($query) use($search){
                                                        $query->whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?) AND enddate<CURRENT_TIMESTAMP',
                                                        [$search]);
                                                    });
                                            },
                                            function($query) use($search){
                                                $query->whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)',
                                                        [$search]);
                                            });
                                        });
                                    },
                                    function($query1) use($categoryId,$onGoing,$useCategory,$useOnGoing){
                                        $query1->when($useCategory,
                                        function($query2) use($categoryId,$onGoing,$useOnGoing){
                                            $query2->when($useOnGoing,
                                            function($query3) use($categoryId,$onGoing){
                                                    $query3->when($onGoing,
                                                    function($query) use($categoryId){
                                                        $query->whereRaw('category_id=? AND enddate>CURRENT_TIMESTAMP',
                                                        [$categoryId]);
                                                    },
                                                    function($query) use($categoryId){
                                                        $query->whereRaw('category_id=? AND enddate<CURRENT_TIMESTAMP',
                                                        [$categoryId]);
                                                    });
                                            },
                                            function($query) use($categoryId){
                                                $query->whereRaw('category_id=?',
                                                [$categoryId]);
                                            });
                                        },
                                        function($query2) use($onGoing,$useOnGoing){
                                            $query2->when($useOnGoing,
                                            function($query3) use($onGoing){
                                                    $query3->when($onGoing,
                                                    function($query){
                                                        $query->whereRaw('enddate>CURRENT_TIMESTAMP');
                                                    },
                                                    function($query){
                                                        $query->whereRaw('enddate<CURRENT_TIMESTAMP');
                                                    });
                                            });
                                        });
                                    }

                                );

            $nrAuctions = $auctionsAfterFilter->count();
            
            $auctions = $auctionsAfterFilter->selectRaw('id,
                                                        enddate,
                                                        CASE 
                                                        WHEN CURRENT_TIMESTAMP < enddate
                                                            THEN 1
                                                            ELSE 0
                                                            END
                                                        AS active,
                                                        name AS productName,
                                                        currentprice + minbidsdif AS minBid,
                                                        photo')
                                            ->when($useSearch,function($query,$search){
                                                $query->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC,name', [$search]);
                                            },function($query){
                                                $query->orderBy('name');
                                            }
                                            )
                                            ->offset($offset*10)
                                            ->limit(10)
                                            ->get();
            DB::commit();
        }
        catch(PDOException $e){
            DB::rollBack();
        }
        return [$auctions,$nrAuctions];
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
                                        ->whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                                        ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                                        ->take(10)
                                        ->get());
    }

    public function list(Request $request){
        [$username,$profile_pic_path] = AuthenticatedUserController::get_username_pfp();
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

        $onGoingText = $request->ongoing==='1'?'Active':($request->ongoing==='0'?'Closed':'None selected');
        return view('pages.auctions')
            ->with('nrAuctions',$nrAuctions)
            ->with('auctions',$auctions)
            ->with('search',$search)
            ->with('onGoing',$request->ongoing)
            ->with('onGoingText',$onGoingText)
            ->with('categoryId',$request->categoryId)
            ->with('categoryName',$categoryName)
            ->with('categories',$categories)
            ->with('offset',$offset)
            ->with('profile_pic_path',$profile_pic_path)
            ->with('username',$username);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */

    public function ownerUpdate(Request $request, $auctionId)
    {
        if (Auth::check()){
            $user_id = Auth::id();
            $auction = Auction::find($auctionId);
            if ($auction->owner_id == $user_id){
                $success = true;
                $auction = Auction::find($auctionId);

                $validated = $request->validate([
                    'name' => 'required|max:50',
                    'description' => 'required',
                    'minBidDif' => 'required|numeric|min:0.01',
                    'startDate' => 'required|date',
                    'endDate' => 'required|date',
                ]);

                $auction->name = $request->input('name');
                $auction->description = $request->input('description');
                $auction->minbidsdif = $request->input('minBidDif');

                // Start date
                $inputStartDate = Carbon::parse($request->input('startDate'))->format('Y-m-d H:i:s.u'); // get the start date from the form
                $inputStartDate = substr($inputStartDate, 0, -3); // remove last 3 digits to be according to the dates saved in the database
                $nowDate = Carbon::now()->format('Y-m-d H:i:s.u'); // get current date
                $nowDate = substr($nowDate, 0, -3); // remove last 3 digits to be according to the dates saved in the database
            
                // check if the input date and the auction start date is in the future //check if input date is the current one stored in the database
                if ((Carbon::parse($inputStartDate)->gt(Carbon::now()) && Carbon::parse($auction->startdate)->gt(Carbon::now())) || $auction->startdate == $inputStartDate) { 
                    $auction->startdate = $inputStartDate; 
                } else {
                    $success = false;
                }

                // End date
                $inputEndDate = Carbon::parse($request->input('endDate'))->format('Y-m-d H:i:s.u'); // get the end date from the form
                $inputEndDate = substr($inputEndDate, 0, -3); // remove last 3 digits to be according to the dates saved in the database

                // check if the input date and the auction start date is in the future //check if input date is the current one stored in the database
                if ((Carbon::parse($inputEndDate)->gt(Carbon::now()) && Carbon::parse($auction->startdate)->gt(Carbon::now())) || $auction->enddate == $inputEndDate) { 
                    $auction->enddate = $inputEndDate; 
                } else {
                    $success = false;
                }

                
                if ($success){
                    $auction->save();
                }
                return view('pages.auction_edit', ['auction' => $auction, 'success' => $success]);
            }
        }
        return redirect()->route('home');
    }
    public function ownerDelete($auctionId)
    {
        if (Auth::check()){
            $user_id = Auth::id();
            $auction = Auction::find($auctionId);
            if ($auction->owner_id == $user_id){
                $auction->delete();
                return redirect('/');
            }
        }
        return redirect()->route('home');
    }

    public function store(Request $request,$auctionId)
    {
        if (Auth::check()){
            $user_id = Auth::id();
            $auction = Auction::find($auctionId);
            if ($auction->owner_id == $user_id){
                $validated = $request->validate([
                    'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $image_path = $request->file('photo')->store('auctions','public');
                $auction->photo = $image_path;
                $auction->save();
                return redirect()->route('auction.edit',$auctionId);
            }
        }
        return redirect()->route('home');
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
        $auction->delete();
        return redirect('admin/auctions');
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

}

