@extends('layouts.admin')

@section('title', 'Price Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h1>Price Management</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('admin.prices.update') }}" method="POST">
                        @csrf

                        <!-- Weekday Price -->
                        <div class="mb-3">
                            <label for="price_weekday" class="form-label">
                                Weekday Price (Senin - Jumat)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('weekday_price') is-invalid @enderror"
                                       id="price_weekday"
                                       name="weekday_price"
                                       value="{{ old('weekday_price', $prices->where('day_type', 'weekday')->first()->price ?? 40000) }}"
                                       min="0" 
                                       step="1000"
                                       required>
                                @error('weekday_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Harga default: Rp 40.000</small>
                        </div>

                        <!-- Weekend Price -->
                        <div class="mb-3">
                            <label for="price_weekend" class="form-label">
                                Weekend Price (Sabtu - Minggu)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('weekend_price') is-invalid @enderror"
                                       id="price_weekend"
                                       name="weekend_price"
                                       value="{{ old('weekend_price', $prices->where('day_type', 'weekend')->first()->price ?? 50000) }}"
                                       min="0" 
                                       step="1000"
                                       required>
                                @error('weekend_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Harga default: Rp 50.000</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Prices
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection