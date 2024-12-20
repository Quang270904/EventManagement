@extends('admin.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Management User</h3>
        </div>
        <div class="form-inline pull-left w-100">
            <div class="input-group">
                <input id="search" type="text" name="search" class="form-control" placeholder="Search...">
            </div>
            <a href="{{ route('admin.user.create') }}" class="add btn btn-success">Create</a>
        </div>

        <div class="box-body">
            @if ($users->isEmpty())
                <p class="text-center text-danger">Empty List User</p>
            @else
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
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
        // search User
        $(document).ready(function() {
            loadUsers();

            $('#search').on('keyup', function() {
                let value = $(this).val();
                loadUsers(value);
            });

            $(document).on('click', '.pagination-link', function(event) {
                event.preventDefault();
                let page = $(this).data('page');
                loadUsers($('#search').val(), page);
            });
        });

        //get all User
        function loadUsers(search = '', page = 1) {
            $.ajax({
                type: 'GET',
                url: '{{ route('admin.user.search') }}',
                data: {
                    search: search,
                    page: page
                },
                success: function(data) {
                    updateUserTable(data.users, page);
                    updatePagination(data.pagination);
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }

        function updateUserTable(users, page = 1) {
            let tableBody = $('#userTableBody');
            tableBody.empty();
            const offset = (page - 1) * 10;

            if (users.length > 0) {
                users.forEach(function(user, index) {
                    let row = `<tr>
                <td>${offset + index + 1}</td> 
                <td>${user.user_detail.full_name}</td>
                <td>${user.user_detail.address}</td>
                <td>${user.email}</td>
                <td>${user.user_detail.phone}</td>
                <td>${user.role.role_name}</td>
                <td>
                    <a href="/admin/user/${user.id}/detail" class="btn btn-info btn-sm viewUserBtn" data-id="{{ $user->id }}">View</a>
                    <a href="/admin/user/${user.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                    <a href="#" data-id="${user.id}" class="deleteData btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>`;
                    tableBody.append(row);
                });
            } else {
                tableBody.html("<tr><td colspan='7' class='text-center text-danger'>No users found</td></tr>");
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

            var userId = $(this).data('id');

            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    type: "POST",
                    url: "/admin/user/" + userId + "/delete",
                    data: {
                        _token: $("meta[name='csrf-token']").attr("content"),
                    },
                    success: function(response) {
                        $('a[data-id="' + userId + '"]').closest('tr').remove();
                        toastr.success("User deleted successfully!");
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
