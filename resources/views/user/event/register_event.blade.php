@extends('user.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Register for Event: {{ $event->name }}</h3>
        </div>

        <div class="box-body">
            <form id="event-registration-form">
                @csrf
                <div class="form-group">
                    <label for="ticket_id">Choose Ticket Type</label>
                    <div class="d-flex justify-content-start">
                        @foreach ($tickets as $ticket)
                            <div class="mr-3">
                                <input type="radio" id="ticket_{{ $ticket->id }}" name="ticket_id"
                                    value="{{ $ticket->id }}">
                                <label for="ticket_{{ $ticket->id }}" class="ml-1">
                                    {{ ucfirst($ticket->ticket_type) }} - Price: ${{ $ticket->price }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Register</button>
                </div>
            </form>

            <div id="response-message" class="mt-3"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#event-registration-form').on('submit', function(event) {
                event.preventDefault();

                var ticketId = $("input[name='ticket_id']:checked").val();

                if (!ticketId) {
                    toastr.error('Please select a ticket type.');
                    return;
                }

                $.ajax({
                    url: '{{ route('user.event.processRegistration', $event->id) }}',
                    method: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        ticket_id: ticketId
                    },
                    success: function(response) {
                        toastr.success(response.message || "Register event successfully!");
                          window.location.href =
                           "{{ route('user.event') }}";
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                            'An error occurred. Please try again.';

                        toastr.error(errorMessage);
                    }
                });
            });
        });
    </script>
@endsection
