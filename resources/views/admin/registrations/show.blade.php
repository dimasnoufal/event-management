@extends('layouts.app')

@section('title', 'Registration Detail')

@section('content')
<div class="container my-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 fw-bold">Registration Detail</h1>
        <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            Registration Info
        </div>
        <div class="card-body">
            <p><strong>User:</strong> {{ $registration->user->name }} ({{ $registration->user->email }})</p>
            <p><strong>Event:</strong> {{ $registration->event->title }}</p>
            <p><strong>Registration Date:</strong> {{ $registration->registration_date->format('d M Y H:i') }}</p>
            <p>
                <strong>Payment Status:</strong> 
                @if($registration->payment_status === 'paid')
                    <span class="badge bg-success">Paid</span>
                @elseif($registration->payment_status === 'pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                @else
                    <span class="badge bg-danger">Failed</span>
                @endif
            </p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white fw-bold">
            Event Info
        </div>
        <div class="card-body">
            <p><strong>Title:</strong> {{ $registration->event->title }}</p>
            <p><strong>Date:</strong> {{ $registration->event->date->format('d M Y H:i') }}</p>
            <p><strong>Location:</strong> {{ $registration->event->location ?? '-' }}</p>
            <p><strong>Organizer:</strong> {{ $registration->event->organizer }}</p>
            <p><strong>Price:</strong> Rp {{ number_format($registration->event->price, 0, ',', '.') }}</p>
            <p><strong>Status:</strong> 
                <span class="badge 
                    @if($registration->event->status === 'scheduled') bg-primary 
                    @elseif($registration->event->status === 'ongoing') bg-success
                    @elseif($registration->event->status === 'completed') bg-secondary
                    @else bg-danger @endif">
                    {{ ucfirst($registration->event->status) }}
                </span>
            </p>
        </div>
    </div>

    {{-- Payment Info --}}
    @if($registration->payment)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white fw-bold">
                Payment Info
            </div>
            <div class="card-body">
                <p><strong>Order ID:</strong> {{ $registration->payment->midtrans_order_id ?? '-' }}</p>
                <p><strong>Amount:</strong> Rp {{ number_format($registration->payment->amount, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($registration->payment->payment_status) }}</p>
                <p><strong>Created At:</strong> {{ $registration->payment->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    @endif

    {{-- Action --}}
    <div class="d-flex gap-2">
        <a href="{{ route('admin.registrations.edit', $registration) }}" class="btn btn-warning">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
        <form action="{{ route('admin.registrations.destroy', $registration) }}" method="POST" onsubmit="return confirm('Delete this registration?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">
                <i class="bi bi-trash me-1"></i> Delete
            </button>
        </form>
    </div>
</div>
@endsection
