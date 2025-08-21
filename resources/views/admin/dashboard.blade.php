@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold">Welcome to Admin Dashboard</h1>
            <div class="text-muted">Ringkasan metrik dan pintasan manajemen.</div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-primary"><i class="bi bi-calendar-event fs-3"></i></div>
                    <div>
                        <div class="small text-muted">Total Events</div>
                        <div class="h5 mb-0">{{ $eventCount ?? '0' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-success"><i class="bi bi-people fs-3"></i></div>
                    <div>
                        <div class="small text-muted">Total Users</div>
                        <div class="h5 mb-0">{{ $userCount ?? '0' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-warning"><i class="bi bi-ui-checks fs-3"></i></div>
                    <div>
                        <div class="small text-muted">Registrations</div>
                        <div class="h5 mb-0">{{ $registrationCount ?? '0' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3 text-danger"><i class="bi bi-currency-dollar fs-3"></i></div>
                    <div>
                        <div class="small text-muted">Revenue (Success)</div>
                        <div class="h5 mb-0">Rp {{ isset($paymentSum) ? number_format($paymentSum, 0, ',', '.') : '0' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // JS khusus dashboard.
    </script>
@endpush
