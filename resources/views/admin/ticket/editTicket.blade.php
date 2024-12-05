@extends('admin.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Update Ticket</h3>
        </div>

        <form id="update-form">
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
                <button type="submit" class="btnSubmit btn btn-primary">Update Ticket</button>
                <a href="{{ route('admin.ticket') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
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
                    url: "{{ route('admin.ticket.update', ['id' => $ticket->id]) }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        window.location.href =
                            "{{ route('admin.ticket') }}";
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            });
        });
    </script>
@endsection
