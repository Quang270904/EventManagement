@extends('admin.dashboard.home.layout')

@section('contents')
    <div class="">
        <div class="">
            <h3>Create New Event</h3>

            <form id="myForm" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="image">Event Image</label>
                    <input type="file" class="form-control" name="image">
                    @error('image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Event Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Event name"
                        value="{{ old('name') }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" placeholder="Event description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" name="location" placeholder="Location"
                        value="{{ old('location') }}">
                    @error('location')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="datetime-local" class="form-control" name="start_time" value="{{ old('start_time') }}">
                    @error('start_time')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="datetime-local" class="form-control" name="end_time" value="{{ old('end_time') }}">
                    @error('end_time')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" name="status">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btnSubmit btn btn-primary">Create Event</button>
                <a href="{{ route('admin.event') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#myForm").submit(function(event) {
                event.preventDefault();

                var form = $(this)[0];
                var data = new FormData(form);

                $("#btnSubmit").prop("disabled", true);

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.event.submit') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        // window.location.href =
                        //     "{{ route('admin.user') }}";
                        toastr.success("Event created successfully!");
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
