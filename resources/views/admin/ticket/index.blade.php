{{-- Manager Event --}}

@extends('admin.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Management Ticket</h3>
        </div>
        <div class="form-inline pull-left  w-100">
            <div class="input-group">
                <input id="search" type="text" name="search" class="form-control" placeholder="Search...">
            </div>
            <a href="{{ route('admin.ticket.create') }}" class="add btn btn-success ">Create</a>
        </div>

        <div class="box-body">
            @if ($tickets->isEmpty())
                <p class="text-center text-danger">Empty List Ticket</p>
            @else
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Prices</th>
                            <th>Ticket_type</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                    <tbody id="ticketTableBody">

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
        // search Ticket
        $(document).ready(function() {
            loadTickets();

            $('#search').on('keyup', function() {
                let value = $(this).val();
                loadTickets(value);
            });

            $(document).on('click', '.pagination-link', function(event) {
                event.preventDefault();
                let page = $(this).data('page');
                loadTickets($('#search').val(), page);
            });
        });

        //get all User
        function loadTickets(search = '', page = 1) {
            $.ajax({
                type: 'GET',
                url: '{{ route('admin.ticket.search') }}',
                data: {
                    search: search,
                    page: page
                },
                success: function(data) {
                    updateTicketTable(data.tickets, page);
                    updatePagination(data.pagination);
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }

        function updateTicketTable(tickets, page = 1) {
            let tableBody = $('#ticketTableBody');
            tableBody.empty();
            const offset = (page - 1) * 10;

            if (tickets.length > 0) {
                tickets.forEach(function(ticket, index) {
                    let createdAt = moment(ticket.created_at).format('DD-MM-YYYY HH:mm:ss');
                    let updatedAt = moment(ticket.updated_at).format('DD-MM-YYYY HH:mm:ss');
                    let row = `<tr>
                <td>${offset + index + 1}</td> 
                <td>${ticket.event.name}</td>
                <td>${ticket.price}</td>
                <td>${ticket.ticket_type}</td>
                <td>${createdAt}</td>
                <td>${updatedAt}</td>
                <td>
                    <a href="/admin/ticket/${ticket.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                    <a href="#" data-id="${ticket.id}" class="deleteData btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>`;
                    tableBody.append(row);
                });
            } else {
                tableBody.html("<tr><td colspan='7' class='text-center text-danger'>No tickets found</td></tr>");
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

        // delete user
        $(document).on('click', '.deleteData', function(event) {
            event.preventDefault();

            var ticketId = $(this).data('id');

            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    type: "POST",
                    url: "/admin/ticket/" + ticketId + "/delete",
                    data: {
                        _token: $("meta[name='csrf-token']").attr("content"),
                    },
                    success: function(response) {
                        $('a[data-id="' + ticketId + '"]').closest('tr').remove();
                        toastr.success("Ticket deleted successfully!");
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
