@extends('admin.dashboard.home.layout')



@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">User Details</h3>
        </div>
        <div class="box-body">
            <!-- Thông tin chi tiết người dùng -->
            <div id="userDetailsContainer">
                <p><strong>Full Name:</strong> <span id="fullName">{{ $users->user_detail->full_name ?? 'N/A' }}</span></p>
                <p><strong>Email:</strong> <span id="email">{{ $user->email ?? 'N/A' }}</span></p>
                <p><strong>Phone:</strong> <span id="phone">{{ $user->user_detail->phone ?? 'N/A' }}</span></p>
                <p><strong>Address:</strong> <span id="address">{{ $user->user_detail->address ?? 'N/A' }}</span></p>
                <p><strong>Role:</strong> <span id="role">{{ $user->role->role_name ?? 'N/A' }}</span></p>
            </div>

            <!-- Nút quay lại danh sách người dùng -->
            <a href="{{ route('admin.user') }}" class="btn btn-primary">Back to List</a>
        </div>
    </div>
@endsection
