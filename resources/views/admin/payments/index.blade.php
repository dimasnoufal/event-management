@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Manage Payments</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Event</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->registration->user->name }}</td>
                        <td>{{ $payment->registration->event->title }}</td>
                        <td>{{ $payment->amount }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ $payment->payment_status }}</td>
                        <td>
                            <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $payments->links() }}
    </div>
@endsection
