{{-- Manager Event --}}
@extends('admin.dashboard.home.layout')


@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Management Event</h3>
        </div>
        <div class="form-inline pull-left  w-100">
            <div class="input-group">
                <input id="search" type="text" name="search" class="form-control" placeholder="Search...">

            </div>
            <a href="{{ route('admin.event.create') }}" class="add btn btn-success">Create</a>

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
                    <tbody id="eventTableBody">

                    </tbody>
                </table>
                <div class="box-footer clearfix">
                    {{-- pagination --}}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // search event
        // search event
        $(document).ready(function() {
            loadEvents();

            $('#search').on('keyup', function() {
                let value = $(this).val();
                loadEvents(value);
            });

            $(document).on('click', '.pagination-link', function(event) {
                event.preventDefault();
                let page = $(this).data('page');
                loadEvents($('#search').val(), page);
            });
        });

        //get all Event
        function loadEvents(search = '', page = 1) {
            $.ajax({
                type: 'GET',
                url: '{{ route('admin.event.search') }}',
                data: {
                    search: search,
                    page: page
                },
                success: function(data) {
                    console.log(data); // In response ra console

                    updateEventTable(data.events, page);
                    updatePagination(data.pagination);
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }

        function updateEventTable(events, page = 1) {

            let startTimeFormatted = moment(event.start_time).format('DD-MM-YYYY HH:mm:ss');
            let endTimeFormatted = moment(event.end_time).format('DD-MM-YYYY HH:mm:ss');
            let tableBody = $('#eventTableBody');
            tableBody.empty();
            const offset = (page - 1) * 10;


            if (events.length > 0) {
                events.forEach(function(event, index) {
                    let imageUrl = `{{ asset('storage/') }}/${event.image}`; // Đảm bảo đúng đường dẫn
                    let row = `<tr>
                <td>${offset + index + 1}</td>
                <td><img src="${imageUrl}" alt="${event.name}" class="event-image"></td>
                <td>${event.name}</td>
                <td>${event.description}</td>
                <td>${event.location}</td>
                <td>${startTimeFormatted}</td>
                <td>${endTimeFormatted}</td>
                <td>${event.status}</td>
                <td>
                    <a href="/admin/event/${event.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                    <a href="#" data-id="${event.id}" class="deleteEvent btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>`;
                    tableBody.append(row);
                });
            } else {
                tableBody.html("<tr><td colspan='8' class='text-center text-danger'>No events found</td></tr>");
            }
        }

        //pagination
        function updatePagination(pagination) {
            let paginationContainer = $('.box-footer.clearfix');
            paginationContainer.empty();

            if (pagination.total > 0) {
                let prevPage = pagination.current_page > 1 ? pagination.current_page - 1 : 1;
                let nextPage = pagination.current_page < pagination.last_page ? pagination.current_page + 1 : pagination
                    .last_page;

                let paginationHTML = `<ul class="pagination">
            <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link pagination-link" href="#" data-page="${prevPage}">Previous</a>
            </li>`;

                for (let i = 1; i <= pagination.last_page; i++) {
                    paginationHTML += `<li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                <a class="page-link pagination-link" href="#" data-page="${i}">${i}</a>
            </li>`;
                }

                paginationHTML += `<li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
            <a class="page-link pagination-link" href="#" data-page="${nextPage}">Next</a>
        </li></ul>`;

                paginationContainer.append(paginationHTML);
            }
        }

        $(document).on('click', '.deleteEvent', function(event) {
            event.preventDefault();

            var eventId = $(this).data('id');

            if (confirm("Are you sure you want to delete this event?")) {
                $.ajax({
                    type: "POST",
                    url: "/admin/event/" + eventId + "/delete",
                    data: {
                        _token: $("meta[name='csrf-token']").attr("content"),
                    },
                    success: function(response) {
                        $('a[data-id="' + eventId + '"]').closest('tr').remove();
                        toastr.success("Event deleted successfully!");
                    },
                    error: function(e) {
                        console.log(e.responseText);
                        alert("Error deleting the user.");
                    }
                });
            }
        });
    </script>
@endsection
