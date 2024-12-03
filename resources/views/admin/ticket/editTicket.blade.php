@extends('admin.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Update Ticket</h3>
        </div>

        <form action="{{ route('admin.ticket.update', $ticket->id) }}" method="POST">
            @csrf

            <div class="box-body">
                <div class="form-group">
                    <label for="ticket_type">Ticket Type</label>
                    <select name="ticket_type" id="ticket_type" class="form-control" required>
                        <option value="regular" {{ $ticket->ticket_type == 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="vip" {{ $ticket->ticket_type == 'vip' ? 'selected' : '' }}>VIP</option>
                        <option value="discounted" {{ $ticket->ticket_type == 'discounted' ? 'selected' : '' }}>Discounted
                        </option>
                    </select>
                    @error('ticket_type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" class="form-control" placeholder="Enter price"
                        value="{{ old('price', $ticket->price) }}" required>
                    @error('price')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Update Ticket</button>
            </div>
        </form>
    </div>
@endsection
