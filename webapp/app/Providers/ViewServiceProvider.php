<?php
 
namespace App\Providers;
 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use PDOException;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
 
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Using closure based composers...
        View::composer('layouts.app', function ($view) {
            $loggedIn = Auth::check()|| ((bool)Auth::guard('admin')->user());
            if(!$loggedIn)
                $view->with('loggedIn',false);
            else{
                $isAdmin = (bool) Auth::guard('admin')->user();
                if($isAdmin){
                    $profilePic = Auth::guard('admin')->user()->photo != "" ?:
                        'default_images/default.jpg';
                    $identificator = Auth::guard('admin')->user()->id;
                    $view->with('loggedIn',true)
                    ->with('isAdmin',true)
                    ->with('profilePic',$profilePic)
                    ->with('identificator',$identificator);
                }
                else{
                    $profilePic = Auth::user()->photo !=""?:
                        'default_images/default.jpg';
                    try{
                        DB::beginTransaction();
                        DB::statement('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ READ ONLY');
                        $numUnreadNotifications = Auth::user()->notifications()->where('beenread',false)->count();
                        $notifications = Auth::user()->notifications()->orderBy('date','DESC')->get();
                        DB::commit();
                    }
                    catch(PDOException $e){
                        error_log($e->getMessage());
                        DB::rollBack();
                    }
                    $userId = auth()->user()->id;
                    $identificator = Auth::user()->username;

                    $view->with('loggedIn',true)
                    ->with('isAdmin',false)
                    ->with('profilePic',$profilePic)
                    ->with('notifications',$notifications)
                    ->with('numUnreadNotifications',$numUnreadNotifications)
                    ->with('userId',$userId)
                    ->with('identificator',$identificator);
                }
            }
        });
    }
}