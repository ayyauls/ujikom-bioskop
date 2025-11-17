@extends('layouts.admin')

@section('title', 'Schedule Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Schedule Management</h1>
                <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Schedule
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($schedules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Film</th>
                                    <th>Studio</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->film->title }}</td>
                                    <td>{{ $schedule->studio->name }}</td>
                                    <td>{{ $schedule->show_date->format('d M Y') }}</td>
                                    <td>{{ $schedule->show_time->format('H:i') }}</td>
                                    <td>
                                        @if($schedule->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.schedules.delete', $schedule->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $schedules->links() }}
                    @else
                    <div class="text-center py-5">
                        <h5>No schedules found</h5>
                        <p class="text-muted">Start by adding a new schedule</p>
                        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add First Schedule
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection