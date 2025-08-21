@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Manage Registrations</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Event</th>
                    <th>Registration Date</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                    <tr>
                        <td>{{ $reg->user->name }}</td>
                        <td>{{ $reg->event->title }}</td>
                        <td>{{ $reg->registration_date }}</td>
                        <td>{{ $reg->payment_status }}</td>
                        <td>
                            <a href="{{ route('admin.registrations.show', $reg) }}" class="btn btn-sm btn-info">View</a>
                            <form action="{{ route('admin.registrations.destroy', $reg) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this registration?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $registrations->links() }}
    </div>
@endsection
