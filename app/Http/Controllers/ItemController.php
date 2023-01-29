<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Meeting;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class ItemController extends Controller
{
    public function __construct()
    {
       $this->middleware('is_admin', ['except' => ['index', 'show', 'voting', 'meetingIndex', 'viewItems']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Item::with(['votes'])->get();
        // dd($items);
        
        $shareholder =Auth::user();
        $votingPower = $shareholder->shares;
        $usedVote = 0;
        
            
        return view('admin/items/index', compact('items', 'votingPower', 'shareholder', 'usedVote'));
    }
    
    public function viewItems($id)
    {
        //
        $meetingId = $id;
        $items = Item::where('meeting_id', $id)->with(['votes'])->get();
        // dd($items);
        
        $shareholder =Auth::user();
        $votingPower = $shareholder->shares;
        $usedVote = 0;
        
            
        return view('admin/items/index', compact('items', 'votingPower', 'shareholder', 'usedVote', 'meetingId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin/items/create');
    }
    
    public function newCreate($id)
    {
        //
        $meetingId = $id;
        return view('admin/items/create', compact('meetingId'));
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $item = new Item();
        $item->name = $request->name;
        $item->description = $request->description;
        $item->meeting_id = $request->meeting_id;
        $saved = $item->save();
        
        if($saved){  
            return redirect()->away('viewItems/'.$item->meeting_id)->with('message', 'Item successfully created');
        }else{
            return redirect()->back()->with('error', 'failed to create Item');
        }
        
    }
    
    // public function voteView($id)
    // {
    //     # code...
    //     $item = Item::where('id', $id)->first();
    //     $powerCount = [];
    //     $shareholder =Auth::user();
        
    //     $voteSearch = Vote::where('user_id', $shareholder->id)->where('item_id',$item->id);
    //     if($voteSearch->exists()){
    //         $vote = $voteSearch->latest();
            
    //         $votingPower = $vote->user_remaining_votes;
    //         for ($x = 1; $x <= $votingPower; $x++) {
    //             array_push($powerCount, $x);
    //         }
    //     }else{
    //         $votingPower = $shareholder->shares;
    //         for ($x = 1; $x <= $votingPower; $x++) {
    //             array_push($powerCount, $x);
    //         }
    //     }
        
    //     Log::info($powerCount);
    //     return view('admin/items/vote/index', compact('powerCount', 'item'));
    // }
    
    public function voting(Request $request, $id)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'voteCount' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $shareholder = Auth::user();
        $userVote = $request->voteCount;
        // $item = Item::where('id', $id)->first();
        // dd($item);
        
        $voteSearch = Vote::where('user_id', $shareholder->id)->where('item_id',$id);
        if($voteSearch->exists()){
            $vote = $voteSearch->latest();
            $voteRow = $vote->first();
            
            $votingPower = $voteRow->user_remaining_votes;
            $currentUsedVotes  = $voteRow->used_votes;
            $usedVotes = $currentUsedVotes + $userVote;
            
            if($votingPower > $userVote){
                $updateRemainingVote = $votingPower - $userVote;
                
            }else{
                $updateRemainingVote = $userVote - $votingPower;
            }
            
            $vote->update([
                'user_remaining_votes' => $updateRemainingVote,
                'used_votes' => $usedVotes,
            ]);
            
        }else{      
            $remainingVote = $shareholder->shares - $userVote;
            
            $vote = new Vote();
            $vote->user_id = $shareholder->id;
            $vote->votes = $userVote;
            $vote->item_id = $id;
            $vote->user_remaining_votes = $remainingVote;
            $vote->used_votes = $userVote;
            $vote->total_user_votes = $shareholder->shares;
            $vote->save();
        }
        return redirect()->back()->with('success', 'Successfully Voted');
    }
    
    public function closeVote(Request $request)
    {
        # code...
        $id = $request->id;
        // dd($id);
        
        //uncomment when done
        $closeVote = Item::where('id', $id)
        ->update([
                    'status' => 1,
                     'closed_at' => Carbon::now(),
            ]);
            
        if($closeVote){
            $item = Item::where('id', $id);
            $itemWithVotes = $item->with(['votes']);
            
            $totalItemVotes = Vote::where('item_id', $id)->sum('used_votes');
             
            $votes = Vote::where('item_id', $id);
            $totalAvaliableVotes = $votes->sum('total_user_votes');
            
            $item->update([
                'total_votes' => $totalItemVotes,
                'total_available_votes' => $totalAvaliableVotes,
            ]);
        } 
        
        return redirect()->back()->with('success', 'Voting Successfully Closed');   
    }
    
    public function meeting()
    {
        # code...
        return view('admin/createMeeting');
    }
    
    public function meetingPost(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'date' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        if($request->date < now()){
            return redirect()->back()->with('error', 'invalid date');
        }else{
            $meeting = new Meeting();
            $meeting->title = $request->title;
            $meeting->date = $request->date;
            $meeting->save();
            
            return redirect()->away('meetingIndex')->with('success', 'meeting successfully created');
        }
        
    }
    public function meetingIndex()
    {
        # code...
        $meetings = Meeting::all();
        return view('admin/meetingIndex', compact('meetings'));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }
    
    
}
