@extends('event_managers.dashboard.home.layout')

@section('content')
    <div class="">
        <div class="">
            <h3>Create New Event</h3>

            <form action="{{ route('event_manager.event.submit') }}" method="POST" enctype="multipart/form-data">
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
                        value="{{ old('name') }}" >
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" placeholder="Event description" >{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" name="location" placeholder="Location"
                        value="{{ old('location') }}" >
                    @error('location')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="datetime-local" class="form-control" name="start_time" value="{{ old('start_time') }}"
                        >
                    @error('start_time')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="datetime-local" class="form-control" name="end_time" value="{{ old('end_time') }}"
                        >
                    @error('end_time')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Create Event</button>
                <a href="{{ route('event_manager.event') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
