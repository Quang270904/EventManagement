@extends('admin.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Edit User</h3>
        </div>

        <div class="box-body">
            <form id="update-form">
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

                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.user') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#update-form").submit(function(event) {
                event.preventDefault();

                var form = $("#update-form")[0];
                var data = new FormData(form);

                $("#btnSubmit").prop("disable", true);

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.user.update', ['id' => $user->id]) }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        window.location.href =
                            "{{ route('admin.user') }}";
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            displayErrors(xhr.responseJSON.errors);
                        } else {
                            toastr.error("Something went wrong. Please try again.");
                        }
                        $("#btnSubmit").prop("disabled", false);
                    }
                });
            });

            function displayErrors(errors) {
                $('.text-danger').remove();
                $.each(errors, function(field, messages) {
                    var input = $('input[name="' + field + '"], select[name="' + field + '"]');
                    input.after('<span class="text-danger">' + messages[0] +
                        '</span>');
                });
            }
        });
    </script>
@endsection
