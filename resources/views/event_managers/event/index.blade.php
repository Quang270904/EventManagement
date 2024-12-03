{{-- Manager Event --}}
@extends('event_managers.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Management Event</h3>
        </div>
        <div class="form-inline pull-left  w-100">
            <form action="{{ route('event_manager.event') }}" method="GET" class="form-inline ">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search..."
                        value="{{ request('search') }}">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </span>
                </div>
            </form>
            <a href="{{ route('event_manager.event.create') }}" class="add btn btn-success ">Create</a>
        </div>

        <div class="box-body">
            @if ($events->isEmpty())
                <p class="text-center text-danger">Empty List Event</p>
            @else
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $index => $event)
                            <tr>
                                <td>{{ $events->firstItem() + $index }}</td>
                                <td>
                                    @if ($event->image)
                                        <div>
                                            <img class="image_path" src="{{ Storage::url($event->image) }}"
                                                alt="Event Image">
                                        </div>
                                    @else
                                        <p>No image available</p>
                                    @endif
                                </td>
                                <td>{{ $event->name }}</td>
                                <td>{{ $event->description }}</td>
                                <td>{{ $event->location }}</td>
                                <td>{{ $event->start_time->format('Y-m-d H:i') }}</td>
                                <td>{{ $event->end_time->format('Y-m-d H:i') }}</td>
                                <td>{{ ucfirst($event->status) }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('event_manager.event.show', $event->id) }}"
                                            class="btn btn-info btn-sm">Show Details</a>
                                        <a href="{{ route('event_manager.event.edit', $event->id) }}"
                                            class="btn btn-warning btn-sm">Update</a>
                                        <form action="{{ route('event_manager.event.delete', $event->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this event?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="box-footer clearfix">
                    {{ $events->links('admin.vendor.pagination.bootstrap-3') }}
                </div>
            @endif
        </div>
    </div>
@endsection
