@extends('layouts.admin')

@section('title', 'Add Schedule')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Add New Schedule</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.schedules.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="film_id" class="form-label">Film</label>
                            <select class="form-select @error('film_id') is-invalid @enderror" id="film_id" name="film_id" required>
                                <option value="">Select Film</option>
                                @foreach($films as $film)
                                    <option value="{{ $film->id }}" {{ old('film_id') == $film->id ? 'selected' : '' }}>
                                        {{ $film->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('film_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="studio_id" class="form-label">Studio</label>
                            <select class="form-select @error('studio_id') is-invalid @enderror" id="studio_id" name="studio_id" required>
                                <option value="">Select Studio</option>
                                @foreach($studios as $studio)
                                    <option value="{{ $studio->id }}" {{ old('studio_id') == $studio->id ? 'selected' : '' }}>
                                        {{ $studio->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('studio_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="show_date" class="form-label">Show Date</label>
                            <input type="date" class="form-control @error('show_date') is-invalid @enderror" 
                                   id="show_date" name="show_date" value="{{ old('show_date') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('show_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Show Times</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_times[]" value="10:00" id="time1">
                                        <label class="form-check-label" for="time1">10:00</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_times[]" value="13:00" id="time2">
                                        <label class="form-check-label" for="time2">13:00</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_times[]" value="16:00" id="time3">
                                        <label class="form-check-label" for="time3">16:00</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_times[]" value="19:00" id="time4">
                                        <label class="form-check-label" for="time4">19:00</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_times[]" value="21:30" id="time5">
                                        <label class="form-check-label" for="time5">21:30</label>
                                    </div>
                                </div>
                            </div>
                            @error('show_times')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Add Schedule</button>
                            <a href="{{ route('admin.schedules') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection