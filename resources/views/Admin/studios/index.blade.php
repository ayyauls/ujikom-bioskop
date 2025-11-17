@extends('layouts.admin')

@section('title', 'Studio Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Studio Management</h1>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Capacity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studios as $studio)
                                <tr>
                                    <td>{{ $studio->id }}</td>
                                    <td>{{ $studio->name }}</td>
                                    <td>{{ $studio->capacity }}</td>
                                    <td>
                                        <span class="badge bg-{{ $studio->is_active ? 'success' : 'danger' }}">
                                            {{ $studio->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.studios.update', $studio->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')

                                            <div class="input-group input-group-sm" style="width: 200px;">
                                                <input type="text" class="form-control" name="name" value="{{ $studio->name }}" required>
                                                <input type="number" class="form-control" name="capacity" value="{{ $studio->capacity }}" min="1" required>
                                                <div class="input-group-text">
                                                    <input type="checkbox" name="is_active" value="1" {{ $studio->is_active ? 'checked' : '' }}>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No studios found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $studios->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
