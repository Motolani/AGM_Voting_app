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
            <a href="{{ route('create.shareholders') }}" class="btn btn-dark">Create Shareholder</a>
            <br>
            <br>
            <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Shares</th>
                            <th scope="col">Created</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @if (isset($shareholders))
                        @foreach ($shareholders as $user)
                            <tr>
                                <th>{{$user->name}}</th>
                                <td>{{$user->email}}</td>
                                <td>{{$user->shares}}</td>
                                <td>{{$user->created_at}}</td>
                                <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal"  onclick="modalFun({{ $user->id }}, `{{ $user->name }}`)">
                                    Drop Shareholder
                                    </button>
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
                        Are you sure you want to delete <span id="modal_shareholder_name"></span>?
                    </div>
                    <div class="modal-footer">
                        <form action="{{url('dropShareholder')}}" method="post">
                            @method('DELETE')
                            @csrf
                            <input type="hidden" name="id" id="shareholderId" placeholder="Password">
                            
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button id="modal_confirm_delete" type="submit" class="btn btn-primary">Delete</button>
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
        
        document.getElementById("modal_shareholder_name").innerHTML = name;
        document.getElementById("shareholderId").value = id;
        
    }
</script>
{{-- @section('script')
<script>
    function loadDeleteModal(id, name) {
        $('#modal-shareholder_name').html(name);
        $('#modal-confirm_delete').attr('onclick', `confirmDelete(${id})`);
        $('#exampleModal').modal('show');
    }

    function confirmDelete(id) {
        $.ajax({
            url: '{{ url('categories') }}/' + id,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                '_method': 'delete',
            },
            success: function (data) {
                // Success logic goes here..!

            $('#deleteShareholder').modal('hide');
            },
            error: function (error) {
                // Error logic goes here..!
            }
        });
    }
</script>
@endsection --}}