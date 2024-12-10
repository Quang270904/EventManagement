@extends('event_managers.dashboard.home.layout')

@section('contents')
    <div class="">
        <div class="">
            <h3>Edit Event</h3>

            <form id="update-form" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="image">Event Image</label>
                    <input type="file" class="form-control" name="image">
                    @error('image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    @if ($event->image)
                        <div class="mt-2">
                            <p>Current Image:</p>
                            <img src="{{ Storage::url($event->image) }}" class="event-image" alt="Event Image">
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="name">Event Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $event->name) }}"
                        required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" required>{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" name="location"
                        value="{{ old('location', $event->location) }}" required>
                    @error('location')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="datetime-local" class="form-control" name="start_time"
                        value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i')) }}"
                        required>
                    @error('start_time')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="datetime-local" class="form-control" name="end_time"
                        value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i')) }}"
                        required>
                    @error('end_time')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btnSubmit btn btn-primary">Update Event</button>
                <a href="{{ route('event_manager.event') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#update-form").submit(function(event) {
                event.preventDefault();

                var form = $("#update-form")[0];
                var data = new FormData(form);

                $("#btnSubmit").prop("disable", true);

                $.ajax({
                    type: "POST",
                    url: "{{ route('event_manager.event.update', ['id' => $event->id]) }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        window.location.href =
                            "{{ route('event_manager.event') }}";
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });
        });
    </script>
@endpush
