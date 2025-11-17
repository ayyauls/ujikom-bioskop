@extends('layouts.admin')

@section('title', 'Edit Film')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">
                    <h1>Edit Film</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.films.update', $film->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $film->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="genre" class="form-label">Genre</label>
                                    <input type="text" class="form-control @error('genre') is-invalid @enderror" id="genre" name="genre" value="{{ old('genre', $film->genre) }}" required>
                                    @error('genre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration (minutes)</label>
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $film->duration) }}" required>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating</label>
                                    <input type="text" class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating" value="{{ old('rating', $film->rating) }}">
                                    @error('rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="now_playing" {{ old('status', $film->status) === 'now_playing' ? 'selected' : '' }}>Now Playing</option>
                                        <option value="coming_soon" {{ old('status', $film->status) === 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="poster" class="form-label">Poster</label>
                                    @if($film->poster)
                                        <div class="mb-2">
                                            <img src="{{ asset($film->poster) }}" alt="Current Poster" width="100" height="140" class="img-thumbnail">
                                            <small class="text-muted d-block">Current poster</small>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('poster') is-invalid @enderror" id="poster" name="poster" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current poster</small>
                                    @error('poster')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $film->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Film</button>
                            <a href="{{ route('admin.films') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
