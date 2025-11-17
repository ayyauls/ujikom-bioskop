@extends('layouts.admin')

@section('title', 'Seat Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Seat Management</h1>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @foreach($studios as $studio)
            <div class="card mb-4">
                <div class="card-header">
                    <h5>{{ $studio->name }} (Capacity: {{ $studio->capacity }})</h5>
                </div>
                <div class="card-body">
                    <div class="seat-layout">
                        @php
                        $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                        @endphp

                        @foreach($rows as $row)
                        <div class="seat-row mb-2">
                            <span class="row-label me-3">{{ $row }}</span>
                            @for($i = 1; $i <= 12; $i++)
                            @php
                            $seat = $studio->seats->where('row_letter', $row)->where('seat_position', $i)->first();
                            @endphp
                            @if($seat)
                            <button class="btn btn-sm seat-btn {{ $seat->is_available ? 'btn-success' : 'btn-danger' }}"
                                    onclick="toggleSeat({{ $seat->id }}, {{ $seat->is_available ? 0 : 1 }})"
                                    title="{{ $seat->seat_number }} - {{ $seat->type }} - Rp {{ number_format($seat->price, 0, ',', '.') }}">
                                {{ $i }}
                            </button>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled title="Seat not found">{{ $i }}</button>
                            @endif
                            @endfor
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Legend:</strong>
                            <span class="badge bg-success me-2">Available</span>
                            <span class="badge bg-danger">Unavailable</span>
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function toggleSeat(seatId, newStatus) {
    if (confirm('Are you sure you want to ' + (newStatus ? 'enable' : 'disable') + ' this seat?')) {
        fetch('{{ route("admin.seats.update-availability") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                seat_id: seatId,
                is_available: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to update seat status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}
</script>

<style>
.seat-layout {
    font-family: monospace;
}

.seat-row {
    display: flex;
    align-items: center;
}

.seat-btn {
    width: 35px;
    height: 35px;
    margin: 2px;
    padding: 0;
    font-size: 12px;
    line-height: 1;
}

.row-label {
    font-weight: bold;
    width: 20px;
    text-align: center;
}
</style>
@endsection
