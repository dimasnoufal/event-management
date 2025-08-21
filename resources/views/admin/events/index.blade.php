@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Manage Events</h1>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary mb-3">Create Event</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->date ? $event->date->format('Y-m-d H:i:s') : '-' }}</td>
                        <td>{{ number_format($event->price, 2) }}</td>
                        <td>{{ $event->status }}</td>
                        <td>
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this event?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $events->links() }}
    </div>
@endsection
