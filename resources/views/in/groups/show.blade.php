@extends('layouts.app')
@section('title', 'Groups')
@section('page-title', 'Group Details')

@section('content')
<style>
    /* Styling for the Print View */
    @media print {
        .no-print {
            display: none !important;
        }
        .container-fluid {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .card {
            border: 1px solid #ccc !important;
            page-break-inside: avoid;
            margin-bottom: 1rem;
        }
        .card-header {
            background-color: #f0f0f0 !important;
            color: #333 !important;
        }
    }
</style>

<div class="container-fluid py-4">
    {{-- ================================================================= --}}
    {{-- HEADER & ACTIONS BAR --}}
    {{-- ================================================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2 class="h3 mb-0 text-dark">
            <i class="bi bi-people-fill text-primary me-2"></i> Group: <span class="text-primary">{{ $group->group_name }}</span>
        </h2>
        <div class="btn-group shadow-sm" role="group">
            <a href="{{ route('groups.index') }}" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-warning d-flex align-items-center">
                <i class="bi bi-pencil-square me-1"></i> Edit Group
            </a>
            <button onclick="exportGroupData({{ $group->id }})" class="btn btn-success d-flex align-items-center">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Data
            </button>
            <button onclick="window.print()" class="btn btn-info d-flex align-items-center">
                <i class="bi bi-printer me-1"></i> Print
            </button>
        </div>
    </div>

    <div class="row">
        
        {{-- ================================================================= --}}
        {{-- GROUP DETAILS CARD --}}
        {{-- ================================================================= --}}
        <div class="col-lg-5 col-md-12 mb-4">
            <div class="card shadow-sm h-100 border-start border-primary border-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> General Group Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Group Code:</strong> <span class="fw-bold text-dark">{{ $group->group_code }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Group Type:</strong> {{ $group->group_type ?? '—' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Credit Officer:</strong> {{ $group->creditOfficer->first_name ?? '—' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Registration Date:</strong> {{ $group->registration_date ?? '—' }}
                        </div>
                        <div class="col-12">
                            <strong>Location:</strong> {{ $group->location ?? '—' }}
                        </div>
                        <div class="col-12">
                            <strong>Status:</strong>
                            @if($group->is_active)
                                <span class="badge bg-success fs-6">Active</span>
                            @else
                                <span class="badge bg-danger fs-6">Inactive</span>
                            @endif
                        </div>
                        <div class="col-12">
                            <strong>Description:</strong> 
                            <p class="text-muted mt-2 mb-0">{{ $group->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- GROUP MEMBERS CARD --}}
        {{-- ================================================================= --}}
        <div class="col-lg-7 col-md-12 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-secondary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i> Group Members ({{ $group->members->count() }})</h5>
                    <a href="{{ route('group_members.create', $group->id) }}" class="btn btn-sm btn-light">
                        <i class="bi bi-plus-circle me-1"></i> Add Member
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($group->members->count())
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member Code</th>
                                        <th>Full Name</th>
                                        <th>Role</th>
                                        <th class="no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group->members as $member)
                                        <tr>
                                            <td><span class="fw-medium">{{ $member->member_code }}</span></td>
                                            <td>{{ $member->employee->first_name }} {{ $member->employee->last_name }}</td>
                                            <td><span class="badge bg-info-subtle text-dark">{{ $member->role_in_group ?? 'General Member' }}</span></td>
                                            <td class="no-print">
                                                <form action="{{ route('group_members.destroy', $member->id) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to remove this member?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Member">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-person-slash fs-4 d-block mb-2"></i>
                            No members have been assigned to this group yet.
                        </div>
                    @endif
                </div>
                
            </div>
        </div>
    </div>
    
</div>

<script>
    /**
     * Placeholder function for exporting a single group's data (Group details + Members list).
     * In a real application, this would make an AJAX request to a backend route
     * that generates and downloads the CSV or PDF file.
     */
    function exportGroupData(groupId) {
        // NOTE: You must define a route like: 
        // Route::get('/groups/{group}/export/single', [GroupController::class, 'exportSingle'])->name('groups.export.single');
        const exportUrl = `/groups/${groupId}/export/single`; 
        
        // Simulating the download
        alert(`Exporting Group ID ${groupId} data, including member details, to CSV. A download should start if the backend route is configured.`);
        
        // Uncomment the line below in a live environment where the route exists:
        // window.location.href = exportUrl; 
    }
</script>
@endsection
