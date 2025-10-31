@extends('layouts.admin')

@push('styles')
    <style>
        /* Enhanced Responsive table styles */
        @media (max-width: 767.98px) {
            .table-responsive-stack thead {
                display: none;
            }

            .table-responsive-stack tbody,
            .table-responsive-stack tr,
            .table-responsive-stack td {
                display: block;
                width: 100%;
            }

            .table-responsive-stack tr {
                margin-bottom: 1rem;
            }

            .table-responsive-stack td {
                padding: 0.75rem;
                border: none;
                border-bottom: 1px solid #e9ecef;
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: right;
            }

            /* Align mobile data-label content to the left */
            .table-responsive-stack td .data-content {
                text-align: right;
            }

            .table-responsive-stack tr:first-child td {
                border-top: 1px solid #e9ecef;
            }

            .table-responsive-stack td:last-child {
                border-bottom: 0;
            }

            .table-responsive-stack td::before {
                content: attr(data-label);
                font-weight: 600;
                width: 40%;
                text-align: left;
                padding-right: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Managers</h1>
        <a href="{{ route('admin.managers.create') }}" class="btn btn-primary">Add New Manager</a>
    </div>

    {{-- Session Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-end">
            <div class="w-100 w-md-50 w-lg-25">
                <input type="text" id="managerSearch" class="form-control" placeholder="Search managers...">
            </div>
        </div>

        <div class="card-body" style="min-height: 60vh;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle table-responsive-stack" id="managers-table">
                    <thead class="table-light">
                        <tr>
                            <th>Manager</th> {{-- UPDATED: From "Name" to "Manager" --}}
                            <th>Email</th>
                            <th>Role</th>
                            <th>Manages</th>
                            <th width="180px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($managers as $manager)
                            <tr>
                                {{-- UPDATED: Added Profile Photo --}}
                                <td data-label="Manager">
                                    <div class="d-flex align-items-center data-content">
                                        <img src="{{ $manager->profile_photo_url }}" alt="{{ $manager->name }}"
                                            class="rounded-circle me-2" width="40" height="40"
                                            style="object-fit: cover;">
                                        <span class="fw-bold">{{ $manager->name }}</span>
                                    </div>
                                </td>
                                <td data-label="Email">
                                    <div class="data-content text-muted">{{ $manager->email }}</div>
                                </td>
                                <td data-label="Role">
                                    <div class="data-content">
                                        @if ($manager->roles->isNotEmpty())
                                            <span
                                                class="badge bg-info">{{ Str::title(str_replace('_', ' ', $manager->roles->first()->name)) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Manages">
                                    <div class="data-content">
                                        @if ($manager->hotel)
                                            {{ $manager->hotel->name }} (Hotel)
                                        @elseif ($manager->temple)
                                            {{ $manager->temple->name }} (Temple)
                                        @else
                                            <span class="text-muted">Not Assigned</span>
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Actions">
                                    <div class="dropdown dropend data-content">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                            id="action-menu-{{ $manager->id }}" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="action-menu-{{ $manager->id }}">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('admin.managers.show', $manager->id) }}">
                                                    <i class="fa-solid fa-eye me-2"></i>View
                                                </a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('admin.managers.edit', $manager->id) }}">
                                                    <i class="fa-solid fa-edit me-2"></i>Edit
                                                </a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.managers.destroy', $manager->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete? Deactivating is safer if they have bookings.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fa-solid fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No managers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $managers->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('managerSearch');
            const table = document.getElementById('managers-table');
            const tableRows = table.querySelectorAll('tbody tr');

            searchInput.addEventListener('keyup', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                tableRows.forEach(row => {
                    // Check if it's not the "empty" row
                    if (row.querySelectorAll('td').length > 1) {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>
@endpush
