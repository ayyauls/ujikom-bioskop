@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Admin Dashboard</h1>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <h2>{{ $data['total_users'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Films</h5>
                            <h2>{{ $data['total_films'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Studios</h5>
                            <h2>{{ $data['total_studios'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Seats</h5>
                            <h2>{{ $data['total_seats'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Users</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @forelse($data['recent_users'] as $user)
                                <div class="list-group-item">
                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                @empty
                                <p class="text-muted">No users found</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Films -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Films</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @forelse($data['films'] as $film)
                                <div class="list-group-item">
                                    <h6 class="mb-1">{{ $film->title }}</h6>
                                    <small class="text-muted">{{ $film->genre }}</small>
                                </div>
                                @empty
                                <p class="text-muted">No films found</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
