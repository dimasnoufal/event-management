@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Welcome to Admin Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Manage Events</div>
                    <div class="card-body">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-primary">View Events</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Manage Users</div>
                    <div class="card-body">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">View Users</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Manage Registrations</div>
                    <div class="card-body">
                        <a href="{{ route('admin.registrations.index') }}" class="btn btn-primary">View Registrations</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Manage Payments</div>
                    <div class="card-body">
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-primary">View Payments</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
