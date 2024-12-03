@extends('admin.dashboard.home.layout')

@section('contents')
    <div class="">
        <div class="">
            <h3 class="">Create New User</h3>
        </div>

        <div class="">
            <form action="{{ route('admin.user.submit') }}" method="POST">
                @csrf

                <div class="form-group">
                    <input type="text" class="form-control" name="full_name" placeholder="Full name"
                        value="{{ old('full_name') }}">
                    @error('full_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="email" placeholder="Email"
                        value="{{ old('email') }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="phone" placeholder="Phone Number"
                        value="{{ old('phone') }}">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="address" placeholder="Address"
                        value="{{ old('address') }}">
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="gender" placeholder="Gender"
                        value="{{ old('gender') }}">
                    @error('gender')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="date" class="form-control" name="dob" value="{{ old('dob') }}">
                    @error('dob')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role_id"><strong>Role</strong></label>
                    <select name="role_id" id="role_id" class="form-control">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->role_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="{{ route('admin.user') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
