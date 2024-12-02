@extends('event_managers.dashboard.home.layout')


@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Event Details</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="form-group">
                    <a href="{{ route('admin.ticket.create', $event->id) }}" class="btn btn-primary">Create Ticket</a>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Event Name:</strong></label>
                        <p>{{ $event->name }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Description:</strong></label>
                        <p>{{ $event->description }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Location:</strong></label>
                        <p>{{ $event->location }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Start Time:</strong></label>
                        <p>
                            {{ $event->start_time ? $event->start_time->format('H:i') : 'Not set' }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label><strong>End Time:</strong></label>
                        <p>
                            {{ $event->end_time ? $event->end_time->format('H:i') : 'Not set' }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label><strong>Status:</strong></label>
                        <p>{{ ucfirst($event->status) }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Start Date:</strong></label>
                        <p>
                            {{ $event->start_time ? $event->start_time->format('Y-m-d') : 'Not set' }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label><strong>End Date:</strong></label>
                        <p>
                            {{ $event->end_time ? $event->end_time->format('Y-m-d') : 'Not set' }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label><strong>Event Image:</strong></label>
                        @if ($event->image)
                            <div>
                                <img src="{{ Storage::url($event->image) }}" alt="Event Image" class="img-fluid"
                                    style="width: 100%; height: auto;">
                            </div>
                        @else
                            <p>No image available</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <a href="{{ route('admin.event') }}" class="btn btn-secondary">Back to Events</a>
            </div>
        </div>
    </div>
@endsection
