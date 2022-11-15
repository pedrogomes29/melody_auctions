<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Auction;
use Illuminate\Support\Facades\DB;
use PDOException;
class AuctionController extends Controller
{
    public function search_results_html(Request $request){
        try{
            $search = $request->search;
            $categoryId = $request->categoryId;
    
            $useSearch = !is_null($search);
            $useCategory = $categoryId !=='-1';
            $useOnGoing = $request->ongoing!=='-1';
            $onGoing = $request->ongoing==1;
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
            
            $auctions = $auctionsAfterFilter->selectRaw('CASE 
                                                            WHEN CURRENT_TIMESTAMP < enddate
                                                            THEN enddate-CURRENT_TIMESTAMP
                                                            ELSE (0 * interval \'1 minute\')
                                                            END
                                                        AS timeleft,
                                                        name AS productName,
                                                        currentprice + minbidsdif AS minBid,
                                                        photo')
                                            ->when($useSearch,function($query,$search){
                                                $query->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC,name', [$search]);
                                            },function($query){
                                                $query->orderBy('name');
                                            }
                                            )
                                            ->take(10)
                                            ->get();
            DB::commit();
        }
        catch(PDOException $e){
            DB::rollBack();
        }
        
        

        return  strval($nrAuctions).'-'.view('partials.auctions')
                                        ->with('auctions',$auctions);
               
                
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
        try{
            DB::beginTransaction();
            DB::statement('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ READ ONLY');

            $nrAuctions = Auction::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$search])
                                  ->count();

            $auctions = Auction::selectRaw('CASE 
                                                WHEN CURRENT_TIMESTAMP < enddate
                                                THEN enddate-CURRENT_TIMESTAMP
                                                ELSE (0 * interval \'1 minute\')
                                                END
                                            AS timeleft,
                                            name AS productName,
                                            currentprice + minbidsdif AS minBid,
                                            photo')
                                ->whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$search])
                                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$search])
                                ->take(10)
                                ->get();
            
            $categories = DB::select('Select * FROM categorys ORDER BY name');
            /*
            $categories = Category::select('name')
                                    ->orderBy('name')
                                    ->get();                ~
            */  
            DB::commit();
        }
        catch(PDOException $e){
            DB::rollBack();
        }
        return view('pages.auctions')
            ->with('nrAuctions',$nrAuctions)
            ->with('auctions',$auctions)
            ->with('search',$search)
            ->with('categories',$categories);
    }
}
