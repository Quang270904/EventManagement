
{{-- Edit Manager --}}

@extends('admin.dashboard.home.layout')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Edit EventManger</h3>
        </div>

        <div class="box-body">
            <form action="{{ route('admin.eventManager.update', $user->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" name="full_name" placeholder="Full Name"
                        value="{{ old('full_name', $userDetail->full_name) }}">
                    @error('full_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="email" placeholder="Email"
                        value="{{ old('email', $user->email) }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="phone" placeholder="Phone Number"
                        value="{{ old('phone', $userDetail->phone) }}">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="address" placeholder="Address"
                        value="{{ old('address', $userDetail->address) }}">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="gender" placeholder="Gender"
                        value="{{ old('gender', $userDetail->gender) }}">
                </div>

                <div class="form-group">
                    <input type="date" class="form-control" name="dob" value="{{ old('dob', $userDetail->dob) }}">
                </div>

                <button type="submit" class="btn btn-primary">Update EventManger</button>
                <a href="{{ route('admin.eventManager') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
