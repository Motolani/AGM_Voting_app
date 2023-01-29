<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnalyticController extends Controller
{
    //
    public function googleLineChart($id)
    {
        $votes = Item::where('id', $id)->select('total_votes', 'total_available_votes')
                        ->orderBy(DB::raw("YEAR(created_at)"))
                        ->get();
  
        $result[] = ['Total Votes','Unused Votes'];
        foreach ($votes as $key => $value) {
            (int) $unusedVotes = (int)$value->total_available_votes - (int)$value->total_votes;
            $result[++$key] = ["Total Votes", (int)$value->total_votes];
            $result[++$key] = ["Unused Votes", (int)$unusedVotes];
        }
       
        $item = Item::where('id', $id)->first();
        $meetingId = $item->meeting_id;
         
        
        $votes = Vote::where('item_id', $id)->select( "user_id", "used_votes")->get();
        
        $result2[] = ['Shareholder','Votes'];
        foreach ($votes as $key => $value) {
            $shareholder = User::where('id', $value->user_id)->first();
            
            $result2[++$key] = [$shareholder->name, (int)$value->used_votes];
        }
        return view('admin/items/analytics', compact('result', 'result2', 'meetingId'));
    }
}
