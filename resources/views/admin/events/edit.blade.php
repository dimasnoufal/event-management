@extends('layouts.app')

@section('title', 'Edit Event')

@push('styles')
    <style>
        .card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(20, 33, 61, .06);
        }

        .section-title {
            font-weight: 700;
        }

        .hint {
            color: #6b7280;
            font-size: .9rem;
        }

        .badge-status {
            font-size: .8rem;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">Edit Event</h1>
            <div class="text-muted">Perbarui detail untuk <span class="fw-semibold">{{ $event->title }}</span></div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>    
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.events.update', $event) }}" method="POST" class="card p-4">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <label class="form-label section-title">Judul Event <span class="text-danger">*</span></label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}"
                    class="form-control @error('title') is-invalid @enderror" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror>
            </div>

            <div class="col-12 col-lg-4">
                <label class="form-label section-title">Status <span class="text-danger">*</span></label>
                @php $opt = old('status', $event->status); @endphp
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="scheduled" {{ $opt === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="ongoing" {{ $opt === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ $opt === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $opt === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="mt-2">
                    @switch($opt)
                        @case('scheduled')
                            <span class="badge rounded-pill text-bg-primary-subtle badge-status">Scheduled</span>
                        @break

                        @case('ongoing')
                            <span class="badge rounded-pill text-bg-warning-subtle badge-status">Ongoing</span>
                        @break

                        @case('completed')
                            <span class="badge rounded-pill text-bg-success-subtle badge-status">Completed</span>
                        @break

                        @case('cancelled')
                            <span class="badge rounded-pill text-bg-danger-subtle badge-status">Cancelled</span>
                        @break

                        @default
                            <span class="badge rounded-pill text-bg-secondary-subtle badge-status">{{ ucfirst($opt) }}</span>
                    @endswitch
                </div>
            </div>

            <div class="col-12">
                <label class="form-label section-title">Deskripsi</label>
                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                    placeholder="Detail event, rundown, catatan khusus...">{{ old('description', $event->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label section-title">Tanggal & Waktu <span class="text-danger">*</span></label>
                <input type="datetime-local" name="date"
                    value="{{ old('date', optional($event->date)->format('Y-m-d\TH:i')) }}"
                    class="form-control @error('date') is-invalid @enderror" required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="hint mt-1">Format: YYYY-MM-DD HH:MM</div>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label section-title">Lokasi</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}"
                    class="form-control @error('location') is-invalid @enderror" placeholder="Gedung / alamat / venue">
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label section-title">Penyelenggara <span class="text-danger">*</span></label>
                <input type="text" name="organizer" value="{{ old('organizer', $event->organizer) }}"
                    class="form-control @error('organizer') is-invalid @enderror" required>
                @error('organizer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label section-title">Harga (Rp) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" step="0.01" min="0" name="price"
                        value="{{ old('price', $event->price) }}" class="form-control @error('price') is-invalid @enderror"
                        required>
                </div>
                @error('price')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="hint mt-1">Gunakan 0 jika gratis.</div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2-circle me-1"></i> Update
            </button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection
