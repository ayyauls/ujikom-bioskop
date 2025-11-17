@extends('layouts.admin')

@section('title', 'Film Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Film Management</h1>
                <a href="{{ route('admin.films.create') }}" class="btn btn-primary">Add New Film</a>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Poster</th>
                                    <th>Title</th>
                                    <th>Genre</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Studio</th>
                                    <th>Showtimes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($films as $film)
                                <tr>
                                    <td>
                                        @if($film->poster)
                                        <img src="{{ asset($film->poster) }}" alt="{{ $film->title }}" width="50" height="70" class="img-thumbnail">
                                        @else
                                        <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $film->title }}</td>
                                    <td>{{ $film->genre }}</td>
                                    <td>{{ $film->duration }} min</td>
                                    <td>
                                        <span class="badge bg-{{ $film->status === 'now_playing' ? 'success' : 'warning' }}">
                                            {{ $film->status === 'now_playing' ? 'Now Playing' : 'Coming Soon' }}
                                        </span>
                                    </td>
                                    <td>{{ $film->studio ? $film->studio->name : 'N/A' }}</td>
                                    <td>
                                        @if($film->showtimes)
                                        {{ implode(', ', $film->showtimes) }}
                                        @else
                                        <span class="text-muted">No showtimes</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.films.edit', $film->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.films.delete', $film->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No films found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $films->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
