@extends('admin.dashboard.home.layout')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Create Ticket for Event: {{ $event->name }}</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('admin.ticket.submit', $event->id) }}" method="POST">
                @csrf
                
                <!-- Ticket Type -->
                <div class="form-group">
                    <label for="ticket_type"><strong>Ticket Type:</strong></label>
                    <select name="ticket_type" id="ticket_type" class="form-control" required>
                        <option value="regular">Regular</option>
                        <option value="vip">VIP</option>
                        <option value="discounted">Discounted</option>
                    </select>
                    @error('ticket_type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Price -->
                <div class="form-group">
                    <label for="price"><strong>Price:</strong></label>
                    <input type="number" name="price" id="price" class="form-control" step="0.01" required>
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hidden Event ID -->
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <button type="submit" class="btn btn-success">Create Ticket</button>
                <a href="{{ route('admin.event') }}" class="btn btn-secondary">Back to Events</a>
            </form>
        </div>
    </div>
@endsection
