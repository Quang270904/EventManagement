@extends('admin.dashboard.home.layout')

@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">User Detail: {{ $users->userDetail->full_name }}</h3>
        </div>
        <div class="box-body">
            <table class="table">
                <tr>
                    <th>Full Name</th>
                    <td>{{ $users->userDetail->full_name }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $users->userDetail->address }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $users->email }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $users->userDetail->phone }}</td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td>{{ $users->role->role_name }}</td>
                </tr>
            </table>

            <a href="{{ route('admin.user') }}" class="btn btn-primary">Back to Users List</a>
        </div>
    </div>
@endsection
