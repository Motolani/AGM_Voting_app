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
            @if(Auth::user()->is_admin == 1)
                <a href="{{ route('createMeeting') }}" class="btn btn-dark">Create Meeting</a>
            @endif
            <br>
            <br>
            <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            
                            <th scope="col">Date</th>
                            
                            <th scope="col">Action</th>
                            
                        </tr>
                    </thead>

                    <tbody>
                    @if (isset($meetings))
                        @foreach ($meetings as $meeting)
                            <tr>
                                <th>{{$meeting->title}}</th>
                                
                                <td>{{$meeting->date}}</td>
                                
                                <td>
                                    <a href="{{url('viewItems/'.$meeting->item_id)}}" class="btn btn-info">
                                        View Items
                                    </a>
                                </td>
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
