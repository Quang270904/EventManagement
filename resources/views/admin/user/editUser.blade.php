@extends('admin.dashboard.home.layout')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Edit User</h3>
        </div>

        <div class="box-body">
            <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" name="full_name" placeholder="Full name" 
                        value="{{ old('full_name', $user->userDetail->full_name) }}">
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
                        value="{{ old('phone', $user->userDetail->phone) }}">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="address" placeholder="Address" 
                        value="{{ old('address', $user->userDetail->address) }}">
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="gender" placeholder="Gender" 
                        value="{{ old('gender', $user->userDetail->gender) }}">
                    @error('gender')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="date" class="form-control" name="dob" 
                        value="{{ old('dob', $user->userDetail->dob) }}">
                    @error('dob')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.user') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
