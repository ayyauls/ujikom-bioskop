@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>User Management</h1>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add New User</a>
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
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No users found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
