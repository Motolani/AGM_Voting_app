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
            <div class="card" style="width: 18rem;">
                <div class="card-header">
                  Featured
                </div>
                <div>
                    <form class="" method="post" action="{{route('votingPost')}}">
                        @csrf
                        <div class="row">
                            <input name="item" type="hidden" value={{$item->id}}>
                            <div class="col-7">
                                <select class="form-select" name="voteCount" size="2" aria-label="size 3 select example" >
                                    <option selected>Select from avaliable votes</option>
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
                </div>
              </div>
        </div>

@endsection
