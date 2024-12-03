{{-- Manager Event --}}

@extends('admin.dashboard.home.layout')


<?php
// dd($tickets);
?>
@section('contents')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Management Ticket</h3>
        </div>
        <div class="form-inline pull-left  w-100">
            <form action="{{ route('admin.ticket') }}" method="GET" class="form-inline ">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search..."
                        value="{{ request('search') }}">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </span>
                </div>
            </form>
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
                    <tbody>
                        @foreach ($tickets as $index => $ticket)
                            <tr>
                                <td>{{ $tickets->firstItem() + $index }}</td>
                                <td>{{ $ticket->event->name ?? 'No Event' }}</td>
                                <td>{{ $ticket->price }}</td>
                                <td>{{ $ticket->ticket_type }}</td>
                                <td>{{ $ticket->created_at }}</td>
                                <td>{{ $ticket->updated_at }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.ticket.edit', $ticket->id) }}"
                                            class="btn btn-warning btn-sm">Update</a>
                                        <form action="{{ route('admin.ticket.delete', $ticket->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this event?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="box-footer clearfix">
                    {{ $events->links('admin.vendor.pagination.bootstrap-3') }}
                </div> --}}
            @endif
        </div>

    </div>
@endsection
