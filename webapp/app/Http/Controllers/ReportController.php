<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, $username){
        $this->authorize('create', Report::class);
        $this->validate($request, [
            'reportstext' => 'required',
        ]);
        $reported_id = AuthenticatedUser::where('username', $username)->firstOrFail()->id;
        $reporter_id = Auth::user()->id;
        if (Report::where('reported_id', $reported_id)->where('reporter_id', $reporter_id)->exists()){
            $errors = [];
            $errors['report']  = 'You have already reported this user';
            return redirect()->route('user', ['username' => $username])->withErrors($errors);
        }
        $report = new Report();
        $id = Report::max('id') + 1;
        $report->id = $id;
        $report->reportstext = $request->input('reportstext');
        $report->reporter_id = $reporter_id;
        $report->reported_id = $reported_id;
        $report->reportsdate = date('Y-m-d H:i:s');
        $report->reports_state_id = 1;
        $report->save();
        return redirect()->route('user', ['username' => $username]);
    }
}
