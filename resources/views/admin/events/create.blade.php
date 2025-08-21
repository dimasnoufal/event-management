@extends('layouts.app')

@section('title', 'Create Event')

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
    </style>
@endpush

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">Create New Event</h1>
            <div class="text-muted">Isi detail event baru yang ingin ditambahkan.</div>
        </div>
        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
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

    <form action="{{ route('admin.events.store') }}" method="POST" class="card p-4">
        @csrf

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <label class="form-label section-title">Judul Event <span class="text-danger">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}"
                    class="form-control @error('title') is-invalid @enderror" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-lg-4">
                <label class="form-label section-title">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label section-title">Deskripsi</label>
                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                    placeholder="Detail event, rundown, catatan khusus...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label section-title">Tanggal & Waktu <span class="text-danger">*</span></label>
                <input type="datetime-local" name="date" value="{{ old('date') }}"
                    class="form-control @error('date') is-invalid @enderror" required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="hint mt-1">Format: YYYY-MM-DD HH:MM</div>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label section-title">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}"
                    class="form-control @error('location') is-invalid @enderror" placeholder="Gedung / alamat / venue">
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label section-title">Penyelenggara <span class="text-danger">*</span></label>
                <input type="text" name="organizer" value="{{ old('organizer') }}"
                    class="form-control @error('organizer') is-invalid @enderror" required>
                @error('organizer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label section-title">Harga (Rp) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}"
                        class="form-control @error('price') is-invalid @enderror" required>
                </div>
                @error('price')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="hint mt-1">Gunakan 0 jika gratis.</div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Create Event
            </button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection
