<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Auction;
use Illuminate\Support\Facades\DB;
use PDOException;
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
            ->with('offset',$offset);
    }

    public function index(Request $request){
        $active_auctions = Auction::selectRaw(' id,
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
                                    ->where('enddate','>',now())
                                    ->take(10)
                                    ->get();

        $closed_auctions = Auction::selectRaw(' id,
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
                                    ->where('enddate','<',now())
                                    ->take(10)
                                    ->get();


        $categories = Category::select('name','id')
                                ->orderBy('name')
                                ->get();

        return view('pages.index')
                ->with('active_auctions',$active_auctions)
                ->with('closed_auctions',$closed_auctions)
                ->with('categories',$categories);
    }
}
