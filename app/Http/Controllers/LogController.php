<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index(){
        $data = Activity::latest()->paginate(10);
        $actor = [];
        foreach($data as $item){
            $subject = User::find($item->causer_id); 
            // dd($subject->nama);
            $actor[] = $subject->nama;
        }
        // dd($actor);
        return view('pages.activities.index',compact('data','actor'));
    }
}
