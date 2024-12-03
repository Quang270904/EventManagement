@extends('admin.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Create Ticket</h3>
        </div>
        <form action="{{ route('admin.ticket.submit') }}" method="POST">
            @csrf
            <div class="box-body">
                <div class="form-group">
                    <label for="event_id">Select Event</label>
                    <select name="event_id" id="event_id" class="form-control">
                        <option value="">-- Select Event --</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                        @endforeach
                    </select>
                    @error('event_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ticket_type">Ticket Type</label>
                    <select name="ticket_type" id="ticket_type" class="form-control" required>
                        <option value="">-- Select Ticket Type --</option>
                        <option value="regular">Regular</option>
                        <option value="vip">VIP</option>
                        <option value="discounted">Discounted</option>
                    </select>
                    @error('ticket_type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" class="form-control"
                        placeholder="Enter ticket price" required>
                    @error('price')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-success">Create Ticket</button>
            </div>
        </form>
    </div>
@endsection
