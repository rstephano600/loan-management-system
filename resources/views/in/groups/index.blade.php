@extends('layouts.app')
@section('title', 'Groups')
@section('page-title', 'Group Directory')

@section('content')
<div class="container-fluid py-4">

    {{-- ================================================================= --}}
    {{-- HEADER & ACTION BAR --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-people-fill text-primary me-2"></i> Group Directory
        </h2>
        <a href="{{ route('groups.create') }}" class="btn btn-primary shadow-sm d-flex align-items-center">
            <i class="bi bi-plus-circle me-1"></i> Add New Group
        </a>
    </div>

    {{-- ================================================================= --}}
    {{-- SEARCH, FILTER & EXPORT CARD --}}
    {{-- ================================================================= --}}
    <div class="card shadow-sm mb-4 no-print">
        <div class="card-body p-3">
            <form id="groupSearchForm" action="{{ route('groups.index') }}" method="GET" class="row g-2 align-items-center">
                
                {{-- Search Input --}}
                <div class="col-12 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" id="search_input" class="form-control" placeholder="Search group name or code..."
                               value="{{ $search }}">
                    </div>
                </div>
                
                {{-- Submit and Reset Buttons --}}
                <div class="col-6 col-md-3 col-lg-2">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bi bi-funnel me-1 d-md-none"></i> Filter
                    </button>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-1 d-md-none"></i> Reset
                    </a>
                </div>
                
                {{-- Export and Print Buttons (Dynamic) --}}
                <div class="col-12 col-md-6 col-lg-3 d-flex justify-content-end gap-2 mt-3 mt-lg-0">
                    <button type="button" onclick="exportGroups()" class="btn btn-success d-flex align-items-center w-100">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
                    </button>
                    <button type="button" onclick="window.print()" class="btn btn-info d-flex align-items-center w-100">
                        <i class="bi bi-printer me-1"></i> Print View
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- GROUPS TABLE --}}
    {{-- ================================================================= --}}
    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 15%;">Group Code</th>
                            <th style="width: 25%;">Group Name</th>
                            <th style="width: 10%;">Type</th>
                            <th style="width: 15%;">Officer</th>
                            <th style="width: 10%;">Registered</th>
                            <th class="text-center" style="width: 10%;">Status</th>
                            <th class="text-center" style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $key => $group)
                        <tr>
                            <td class="text-center">{{ $groups->firstItem() + $key }}</td>
                            <td class="fw-bold">{{ $group->group_code }}</td>
                            <td>{{ $group->group_name }}</td>
                            <td><span class="badge bg-secondary-subtle text-dark">{{ $group->group_type ?? '—' }}</span></td>
                            <td>{{ $group->creditOfficer->first_name ?? '—' }}</td>
                            <td>{{ $group->registration_date ? \Carbon\Carbon::parse($group->registration_date)->format('Y-m-d') : '—' }}</td>
                            <td class="text-center">
                                @if($group->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('groups.show', $group->id) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('groups.destroy', $group->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this group?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">No groups found matching your criteria.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination in Card Footer --}}
            @if($groups->hasPages())
                <div class="card-footer bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                        <div class="text-muted small mb-2 mb-sm-0">
                            Showing {{ $groups->firstItem() }} to {{ $groups->lastItem() }} of {{ $groups->total() }} results
                        </div>
                        {{ $groups->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>

    function exportGroups() {
        // 1. Get the current search parameter from the input field
        const search = document.getElementById('search_input').value;

        
        let exportUrl = baseUrl;
        
        // 3. Build the query string if a search term exists
        if (search) {
            exportUrl += `?search=${encodeURIComponent(search)}`;
        }

        // 4. Redirect the browser to the export URL to trigger the download
        console.log("Redirecting to export URL:", exportUrl);
        window.location.href = exportUrl;
    }
</script>
@endsection
