@extends('adminlte::page')

@section('title', 'Users')

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="box-title">Users list</h3>
        </div>
        <div class="card-body">
            <table id="categories-table" class="table" style="width:100%">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Platform id</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->platform_id }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->status}}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td>
                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-xs btn-warning" data-toggle="tooltip" data-placement="top" data-title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button type="submit" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-{{ $user->id }}" data-placement="top" data-title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                            <form method="post" action="{{ route('users.destroy', ['user' => $user->id]) }}" enctype="multipart/form-data">
                                @method('delete')
                                @csrf
                                <div class="modal fade show" id="modal-{{ $user->id }}" style="display: none; padding-right: 17px;" aria-modal="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content bg-default">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Delete user?</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are u sure?</p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Yes, delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a class="btn btn-success" href="{{ route('users.create') }}">New</a>
        </div>
    </div>
@stop
