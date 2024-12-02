{{-- Manager Event Manager --}}

@extends('admin.dashboard.home.layout')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Management Event Manager</h3>
        </div>
        <div class="form-inline pull-left  w-100">
            <form action="{{ route('admin.eventManager') }}" method="GET" class="form-inline ">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search..."
                        value="{{ request('search') }}">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </span>
                </div>
            </form>
            <a href="{{ route('admin.eventManager.create') }}" class="add btn btn-success ">Create</a>
        </div>

        <div class="box-body">
            @if ($allUserDetails->isEmpty())
                <p class="text-center text-danger">Empty List Event Manager</p>
            @else
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allUserDetails as $index => $detail)
                            <tr>
                                <td>{{ $allUserDetails->firstItem() + $index }}</td>
                                <td>{{ $detail->full_name }}</td>
                                <td>{{ $detail->address }}</td>
                                <td>{{ $detail->user->email }}</td>
                                <td>{{ $detail->phone }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.eventManager.edit', $detail->user->id) }}"
                                            class="btn btn-warning btn-sm">Update</a>
                                        <form action="{{ route('admin.eventManager.delete', $detail->user->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="box-footer clearfix">
                    {{ $allUserDetails->links('vendor.pagination.bootstrap-3') }}
                </div>
            @endif
        </div>
    </div>
@endsection
