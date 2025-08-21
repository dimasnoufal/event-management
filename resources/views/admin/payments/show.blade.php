@extends('layouts.app')

@section('title', 'Payment Detail')

@section('content')
    <div class="container-xxl">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h4 mb-1 fw-bold">Payment Detail</h1>
                <div class="text-muted">Rincian transaksi & keterkaitan registrasi.</div>
            </div>
            <div>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Payments
                </a>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3"><i class="bi bi-receipt"></i></div>
                        <div>
                            <div class="small text-muted">Amount</div>
                            <div class="h5 mb-0">
                                Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3"><i class="bi bi-credit-card-2-front"></i></div>
                        <div>
                            <div class="small text-muted">Method</div>
                            <div class="h5 mb-0 text-capitalize">{{ $payment->payment_method ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                @php
                    $status = $payment->payment_status;
                    $badge =
                        [
                            'success' => 'success',
                            'pending' => 'warning',
                            'failed' => 'danger',
                        ][$status] ?? 'secondary';
                @endphp
                <div class="card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3"><i class="bi bi-activity"></i></div>
                        <div>
                            <div class="small text-muted">Status</div>
                            <span class="badge text-bg-{{ $badge }} px-3 py-2">{{ ucfirst($status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail sections --}}
        <div class="row g-4">
            {{-- Payment Info --}}
            <div class="col-12 col-lg-6">
                <div class="card card-lite p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="mb-0">Payment Info</h5>
                        @if (!empty($payment->redirect_url))
                            <a href="{{ $payment->redirect_url }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="bi bi-box-arrow-up-right"></i> Open Snap Page
                            </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-muted fw-normal" style="width: 40%">Payment ID</th>
                                    <td class="fw-semibold">#{{ $payment->id }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Order ID (Midtrans)</th>
                                    <td class="fw-semibold">{{ $payment->external_order_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Method</th>
                                    <td class="text-capitalize">{{ $payment->payment_method ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Amount</th>
                                    <td>Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Status</th>
                                    <td>
                                        <span class="badge text-bg-{{ $badge }}">{{ ucfirst($status) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Created At</th>
                                    <td>{{ optional($payment->created_at)->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">Updated At</th>
                                    <td>{{ optional($payment->updated_at)->format('d M Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Registration / User / Event --}}
            <div class="col-12 col-lg-6">
                <div class="card card-lite p-3 mb-3">
                    <h5 class="mb-3">User</h5>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="avatar rounded-circle d-inline-flex justify-content-center align-items-center"
                                style="width:44px;height:44px;background:#eef1ff;color:#2f6bff;font-weight:700;">
                                {{ strtoupper(substr($payment->registration->user->name ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $payment->registration->user->name ?? '-' }}</div>
                            <div class="text-muted small">
                                {{ $payment->registration->user->email ?? '-' }}
                                @if (!empty($payment->registration->user->phone))
                                    · {{ $payment->registration->user->phone }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-lite p-3">
                    <h5 class="mb-3">Event</h5>
                    <div class="mb-2 fw-semibold">
                        {{ $payment->registration->event->title ?? '-' }}
                    </div>
                    <div class="text-muted small">
                        <i class="bi bi-geo-alt me-1"></i>
                        {{ $payment->registration->event->venue ?? ($payment->registration->event->location ?? '-') }}
                        <br>
                        <i class="bi bi-calendar-event me-1"></i>
                        @php
                            $date =
                                $payment->registration->event->event_date ??
                                ($payment->registration->event->date ?? null);
                        @endphp
                        {{ $date ? \Illuminate\Support\Carbon::parse($date)->format('d M Y H:i') : '-' }}
                        <br>
                        <i class="bi bi-person-workspace me-1"></i>
                        Organizer: {{ $payment->registration->event->organizer ?? '-' }}
                        <br>
                        <i class="bi bi-cash-coin me-1"></i>
                        Price: Rp
                        {{ isset($payment->registration->event->price) ? number_format((float) $payment->registration->event->price, 0, ',', '.') : '0' }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
