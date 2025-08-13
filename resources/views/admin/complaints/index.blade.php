@extends('layouts.admin') 

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .action-buttons .btn {
            margin-right: 5px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Submitted Complaints</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date Submitted</th>
                                <th>Status</th>
                                <th style="width: 150px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($complaints as $complaint)
                                {{-- Main row for each complaint --}}
                                <tr class="complaint-row" data-id="{{ $complaint->id }}">
                                    <td>{{ $complaint->id }}</td>
                                    <td>{{ $complaint->name }}</td>
                                    <td>{{ $complaint->email }}</td>
                                    <td>{{ $complaint->subject }}</td>
                                    <td>{{ $complaint->created_at->format('d M Y') }}</td>
                                    <td>
                                        <select class="form-control form-control-sm status-dropdown" data-id="{{ $complaint->id }}">
                                            <option value="Pending" {{ $complaint->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="In Progress" {{ $complaint->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Resolved" {{ $complaint->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                            <option value="Rejected" {{ $complaint->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-info view-details-btn">View</button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $complaint->id }}">Delete</button>
                                    </td>
                                </tr>
                                {{-- Hidden details row, toggled by the "View" button --}}
                                <tr class="details-row" id="details-{{ $complaint->id }}" style="display: none;">
                                    <td colspan="7">
                                        <div class="p-3">
                                            <strong>Issue Type:</strong> {{ ucwords(str_replace('_', ' ', $complaint->issue_type)) }}<br>
                                            <hr>
                                            <strong>Full Message:</strong>
                                            <p style="white-space: pre-wrap; margin-top: 5px;">{{ $complaint->message }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No complaints have been submitted yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Make sure jQuery is loaded in your admin layout. Then add Toastr JS. --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function() {
    // Configure Toastr notifications
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
    };

    // Handle the "View" button click to show/hide details
    $('.view-details-btn').on('click', function() {
        var row = $(this).closest('tr');
        var complaintId = row.data('id');
        $('#details-' + complaintId).toggle(); // Toggles the visibility of the details row
    });

    // Handle the status dropdown change
    $('.status-dropdown').on('change', function() {
        var complaintId = $(this).data('id');
        var newStatus = $(this).val();

        // Use AJAX to send the update to the server
        $.ajax({
            url: '/admin/complaints/' + complaintId + '/status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                if(response.success) {
                    toastr.success('Status updated successfully!');
                }
            },
            error: function() {
                toastr.error('Error: Could not update status.');
            }
        });
    });

    // Handle the delete button click
    $('.delete-btn').on('click', function() {
        var complaintId = $(this).data('id');
        
        // Use a confirmation dialog before deleting
        if (confirm('Are you sure you want to delete this complaint? This action cannot be undone.')) {
            $.ajax({
                url: '/admin/complaints/' + complaintId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        // Remove the row from the table on success
                        $('tr[data-id="' + complaintId + '"]').remove();
                        $('#details-' + complaintId).remove();
                        toastr.success(response.message || 'Complaint deleted successfully!');
                    }
                },
                error: function() {
                    toastr.error('Error: Could not delete the complaint.');
                }
            });
        }
    });
});
</script>
@endpush
