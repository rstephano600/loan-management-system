<!-- ================================================================ -->
<!-- FILE: resources/views/employees/index.blade.php -->
<!-- ================================================================ -->
@extends('layouts.app')

@section('title', 'Wafanyakazi')
@section('page-title', 'Orodha ya Wafanyakazi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashibodi</a></li>
    <li class="breadcrumb-item active">Wafanyakazi</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="mb-0">
                <i class="bi bi-person-badge text-primary"></i>
                Orodha ya Wafanyakazi
            </h3>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Ongeza Mfanyakazi
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('employees.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tafuta</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Jina, Namba, NIDA, Email..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Idara</label>
                    <select name="department" class="form-select">
                        <option value="">Zote</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hali</label>
                    <select name="status" class="form-select">
                        <option value="">Zote</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            Hai
                        </option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            Hayupo Kazini
                        </option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tafuta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Idara</h6>
                    <h2>{{ $departments->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Picha</th>
                            <th>Namba ya Mfanyakazi</th>
                            <th>Jina Kamili</th>
                            <th>Email/Simu</th>
                            <th>Nafasi</th>
                            <th>Idara</th>
                            <th>Tarehe ya Kuajiriwa</th>
                            <th>Hali</th>
                            <th>Vitendo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td>{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                            <td>
                                <img src="{{ $employee->profile_picture_url }}" 
                                     alt="{{ $employee->full_name }}" 
                                     class="rounded-circle" 
                                     width="40" height="40"
                                     style="object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $employee->employee_id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $employee->full_name }}</strong><br>
                                    <small class="text-muted">{{ $employee->gender }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="bi bi-envelope"></i> {{ $employee->user->email }}<br>
                                    <i class="bi bi-phone"></i> {{ $employee->user->phone ?? 'N/A' }}
                                </div>
                            </td>
                            <td>{{ $employee->position }}</td>
                            <td>
                                <span class="badge bg-info">{{ $employee->department }}</span>
                            </td>
                            <td>{{ $employee->date_of_hire->format('d/m/Y') }}</td>
                            <td>
                                @if($employee->is_active)
                                    <span class="badge bg-success">Hai</span>
                                @else
                                    <span class="badge bg-secondary">Hayupo Kazini</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('employees.show', $employee) }}" 
                                       class="btn btn-info" 
                                       title="Tazama">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $employee) }}" 
                                       class="btn btn-warning" 
                                       title="Hariri">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('employees.toggle-status', $employee) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-{{ $employee->is_active ? 'secondary' : 'success' }}" 
                                                title="{{ $employee->is_active ? 'Simamisha' : 'Anzisha' }}">
                                            <i class="bi bi-toggle-{{ $employee->is_active ? 'off' : 'on' }}"></i>
                                        </button>
                                    </form>
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $employee->id }}"
                                            title="Futa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $employee->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Thibitisha Kufuta</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Je, una uhakika unataka kumfuta {{ $employee->full_name }}?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Ghairi
                                                </button>
                                                <form action="{{ route('employees.destroy', $employee) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Futa</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted">Hakuna wafanyakazi waliopatikana</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

