@extends('adminlte::page')

@section('title', 'New user')

@section('content')

    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="box-title">New user</h3>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('users.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">

                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="first_name" placeholder="First name" required value="{{ old('first_name') }}">
                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last_name" placeholder="Last name" required value="{{ old('last_name') }}">
                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone number</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" placeholder="Phone number" required value="{{ old('phone') }}">
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" required value="{{ old('email') }}">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="platform_id">Platform</label>
                        <select class="form-control" name="platform_id" id="platform_id">
                            <option value="1">Platform 1</option>
                            <option value="2">Platform 2</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="platform_id">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option>Status 1</option>
                            <option>Status 2</option>
                        </select>
                    </div>

                </div>
                <a href="{{ URL::previous() }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-success">Store</button>
            </form>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.min.js"></script>
    <script>

        $("#phone").inputmask({ mask: "+380 (99) 999 99 99", greedy: true});
    </script>
@stop
