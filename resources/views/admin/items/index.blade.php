@extends('layouts.app')
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if(count($errors) > 0)
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
            @endif
            <br>
            <a href="{{ route('meetingIndex') }}" class="btn btn-dark">Back</a>
            
            @if(Auth::user()->is_admin == 1)
                <a href="{{ url('newCreate/'.$meetingId) }}" class="btn btn-dark">Create Item</a>
            @endif
            <br>
            <br>
            <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            
                            <th scope="col">Description</th>
                            
                            @if(Auth::user()->is_admin == 1)
                                <th scope="col">Total Votes</th>
                            @endif
                            
                            @if(Auth::user()->is_admin == 1)
                                <th scope="col">Total Available Votes</th>
                            @endif
                            
                            <th scope="col">Status</th>
                            
                            @if(Auth::user()->is_admin != 1)
                                <th scope="col">Cast Votes</th>
                            @endif
                            
                            @if(Auth::user()->is_admin != 1)
                                <th scope="col">Used Votes</th>
                            @endif
                            
                            @if(Auth::user()->is_admin != 1)
                                <th scope="col">Remaining Votes</th>
                            @endif
                            
                            
                            
                            <th scope="col">Created</th>
                            
                            <th scope="col">Closed Date</th>
                            
                            @if(Auth::user()->is_admin == 1)
                                <th scope="col">Action</th>
                            @endif
                            @if(Auth::user()->is_admin != 1)
                                <th scope="col">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                    @if (isset($items))
                        @foreach ($items as $item)
                            <tr>
                                <th>{{$item->name}}</th>
                                
                                <td>{{$item->description}}</td>
                                
                                @if(Auth::user()->is_admin == 1)
                                    <td>{{$item->total_votes}}</td>
                                @endif
                                
                                @if(Auth::user()->is_admin == 1)
                                    <td>
                                        {{$item->total_available_votes}} 
                                    </td>
                                @endif
                                
                                @if ($item->status == 0)
                                    <td><a class="btn btn-success" >Open</a></td>
                                @else
                                    <td><a class="btn btn-danger">Closed</a></td>
                                @endif
                                @if(Auth::user()->is_admin != 1)
                                    @if ($item->status == 0 )
                                        {{-- <td><a href="{{url('voteView/'.$item->id)}}" class="btn btn-primary">Vote</a></td> --}}
                                        
                                        <td>
                                            <form class="" method="post" action="{{url('votingPost/' .$item->id)}}">
                                                @csrf
                                                <div class="row">
                                                    {{-- <input name="item" type="hidden" value={{$item->id}}> --}}
                                                    @php
                                                        $powerCount = [];
                                                        $voteSearch = App\Models\Vote::where('user_id', $shareholder->id)->where('item_id',$item->id);
                                                        
                                                        if($voteSearch->exists()){
                                                            $vote = $voteSearch->latest()->first();
                                                            // dd($vote)
                                                            
                                                            $votingPower = $vote->user_remaining_votes;
                                                            $usedVote = $shareholder->shares - $votingPower;
                                                            
                                                            for ($x = 1; $x <= $votingPower; $x++) {
                                                                array_push($powerCount, $x);
                                                            }
                                                        }else{
                                                            $usedVote = 0;
                                                            $votingPower = Auth::user()->shares;
                                                            for ($x = 1; $x <= $votingPower; $x++) {
                                                                array_push($powerCount, $x);
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="col-7">
                                                        <select class="form-select" name="voteCount" size="2" aria-label="size 3 select example" >
                                                            <option selected value="0">Select from avaliable votes</option>
                                                            @foreach ($powerCount as $power)
                                                                <option value="{{$power}}">{{$power}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-4">
                                                        
                                                        <button type="submit" class="btn btn-info">Vote</button>
                                                    </div>
                                                </div>        
                                            </form>
                                        </td>
                                    @else
                                        <td>Vote closed</td>
                                    @endif
                                @endif
                                
                                @if(Auth::user()->is_admin != 1)
                                    @if($item->status == 0)
                                        <td>
                                            <span class="badge bg-danger xl">{{$usedVote}} </span>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                @endif
                                
                                @if(Auth::user()->is_admin != 1)
                                    @if($item->status == 0)
                                        <td>
                                            <span class="badge bg-success">{{$votingPower}} </span>
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                @endif
                                
                                <td>{{$item->closed_at}}</td>
                                
                                <td>{{$item->created_at}}</td>
                                @if(Auth::user()->is_admin == 1)
                                    @if($item->status == 0 )
                                        <td>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal"  onclick="modalFun({{ $item->id }}, `{{ $item->name }}`)">
                                            close vote
                                            </button>
                                        </td>
                                    @else
                                        <td>
                                            {{-- <a href="{{url('items/'.$item->id)}}" class="btn btn-secondary">
                                                View Details
                                            </a> --}}
                                            <a href="{{url('analytics/'.$item->id)}}" class="btn btn-info">
                                                View Analytics
                                            </a>
                                        </td>
                                    @endif
                                @endif
                                @if(Auth::user()->is_admin != 1)
                                <td>
                                    <a href="{{url('analytics/'.$item->id)}}" class="btn btn-info">
                                        View Result
                                    </a>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <th></th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif
                        
                    </tbody>
            </table>
        </div>
        
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        You are about to close vote titled, <strong><span id="modal_close_vote_name"></span></strong>
                    </div>
                    <div class="modal-footer">
                        <form action="{{url('closeVote')}}" method="post">
                            @csrf
                            <input type="hidden" name="id" id="voteId">
                            
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abort</button>
                            <button id="modal_confirm_delete" type="submit" class="btn btn-danger">Close Vote</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
<script>
    function modalFun(id, name){
        console.log(id);
        console.log(name);
        
        document.getElementById("modal_close_vote_name").innerHTML = name;
        document.getElementById("voteId").value = id;
        
    }
</script>
