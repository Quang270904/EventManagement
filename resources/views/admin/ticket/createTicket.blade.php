@extends('admin.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Create Ticket</h3>
        </div>
        <form id="myForm">
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
                <button type="submit" class="btnSubmit btn btn-success">Create Ticket</button>
                <a href="{{ route('admin.ticket') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
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
                    url: "{{ route('admin.ticket.submit') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log('ticket', data);
                        // window.location.href =
                        //     "{{ route('admin.user') }}";
                        toastr.success("Ticket created successfully!");
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
