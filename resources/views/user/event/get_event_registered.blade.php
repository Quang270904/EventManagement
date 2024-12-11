@extends('user.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">My Event</h3>
        </div>

        <div class="form-inline pull-left w-100">
            <div class="input-group">
                <input id="search" type="text" name="search" class="form-control" placeholder="Search...">
            </div>
        </div>

        <div class="box-body">
            <div id="eventContainer">
                {{-- AJAX sẽ load sự kiện ở đây --}}
            </div>
            <div class="box-footer clearfix">
                {{-- Phần phân trang sẽ được AJAX cập nhật --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            loadEvents();

            // Tìm kiếm sự kiện theo từ khóa
            $('#search').on('keyup', function() {
                let value = $(this).val();
                loadEvents(value);
            });

            // Phân trang
            $(document).on('click', '.pagination-link', function(event) {
                event.preventDefault();
                let page = $(this).data('page');
                loadEvents($('#search').val(), page);
            });
        });

        function loadEvents(search = '', page = 1) {
            $.ajax({
                type: 'GET',
                url: '{{ route('user.event.getEventRegistered') }}',
                data: {
                    search: search,
                    page: page
                },
                success: function(data) {
                    updateEventTable(data.events, page);
                    updatePagination(data.pagination);
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }

        // Cập nhật danh sách sự kiện
        function updateEventTable(events, page = 1) {
            let eventContainer = $('#eventContainer');
            eventContainer.empty();

            if (events.length > 0) {
                events.forEach(function(event) {
                    // Truy cập các thuộc tính của sự kiện thông qua event.event
                    let startTimeFormatted = moment(event.event.start_time).format('DD-MM-YYYY HH:mm:ss');
                    let endTimeFormatted = moment(event.event.end_time).format('DD-MM-YYYY HH:mm:ss');
                    let imageUrl = `{{ asset('storage') }}/${event.event.image}`;

                    let card = `<div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="card" style="width: 18rem;">
                    <img src="${imageUrl}" class="card-img-top" alt="${event.event.name}">
                    <div class="card-body">
                        <h5 class="card-title">${event.event.name}</h5>
                        <p class="card-text">${event.event.description}</p>
                        <p class="text-muted"><small>Location: ${event.event.location}</small></p>
                        <p class="text-muted"><small>Start: ${startTimeFormatted}</small></p>
                        <p class="text-muted"><small>End: ${endTimeFormatted}</small></p>
                        <a href="/user/event/${event.event.id}/detail" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>`;
                    eventContainer.append(card);
                });
            } else {
                eventContainer.html("<div class='col-12'><p class='text-center text-danger'>No events found</p></div>");
            }
        }


        // Cập nhật phân trang
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

        $(document).on('click', '.btnCancel', function(event) {
            event.preventDefault();

            let eventId = $(this).data('event-id');
            let button = $(this);

            $.ajax({
                type: 'POST',
                url: `/user/event/${eventId}/cancel`,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        loadEvents($('#search').val());
                    }
                },
                error: function(xhr, status, error) {
                    let errMessage = xhr.responseJSON ? xhr.responseJSON.message :
                        'Something went wrong.';
                    toastr.error(errMessage);
                }
            });
        });
    </script>
@endsection
