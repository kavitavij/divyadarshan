@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Dashboard Overview</h4>
    <p class="text-muted">
        This is your central admin dashboard. Use the quick actions below to manage different sections
        of your site.
    </p>

    {{-- General Website Section --}}
    <h5 class="mt-4 mb-3 text-primary">General Website</h5>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Managers</h5>
                    <p class="text-muted">Add and view all managers.</p>
                    <a href="{{ route('admin.managers.index') }}" class="btn btn-primary">Manage Managers</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Revenue</h5>
                    <p class="text-muted">View Revenue of website</p>
                    <a href="{{ route('admin.revenue.index') }}" class="btn btn-primary">Website Revenue</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Page Content</h5>
                    <p class="text-muted">View and add page content.</p>
                    <a href="{{ route('admin.settings.edit') }}" class="btn btn-primary">Manage Page Contents</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Contact Messages</h5>
                    <p class="text-muted">Read and respond to user messages.</p>
                    <a href="{{ route('admin.contact-submissions.index') }}" class="btn btn-success">View Messages</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Latest Updates</h5>
                    <p class="text-muted">Share announcements and news.</p>
                    <a href="{{ route('admin.latest_updates.index') }}" class="btn btn-warning text-white">Latest Updates</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Complaints</h5>
                    <p class="text-muted">View and resolve user complaints.</p>
                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-danger">Manage Complaints</a>
                </div>
            </div>
        </div>
        
    </div>
    {{-- Temple Management Section --}}
    <h5 class="mt-4 mb-3 text-success">Temple Management</h5>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Temples</h5>
                    <p class="text-muted">Add, edit, or remove temple details.</p>
                    <a href="{{ route('admin.temples.index') }}" class="btn btn-primary">Manage Temples</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">E-Books</h5>
                    <p class="text-muted">Upload and manage e-book resources.</p>
                    <a href="{{ route('admin.ebooks.index') }}" class="btn btn-success">Manage E-Books</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Spiritual Help</h5>
                    <p class="text-muted">Review and manage spiritual help forms.</p>
                    <a href="{{ route('admin.spiritual-help.index') }}" class="btn btn-primary">Spiritual Help</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Darshan & Sevas Booking</h5>
                    <p class="text-muted">Manage temple darshan and seva bookings.</p>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-info">Manage Bookings</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Temple Donations</h5>
                    <p class="text-muted">View and manage temple donations.</p>
                    <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">Donations</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Slots</h5>
                    <p class="text-muted">Add or edit temple darshan & seva time slots.</p>
                    <a href="{{ route('admin.slots.index') }}" class="btn btn-primary">Manage Slots</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Hotel Section --}}
    <h5 class="mt-4 mb-3 text-info">Hotel Management</h5>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Hotels</h5>
                    <p class="text-muted">Add and manage hotels/accommodations.</p>
                    <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">Manage Hotels</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Amenities</h5>
                    <p class="text-muted">Add or edit hotel amenities.</p>
                    <a href="{{ route('admin.amenities.index') }}" class="btn btn-primary">Manage Amenities</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Accommodation Bookings</h5>
                    <p class="text-muted">Manage hotel room/accommodation bookings.</p>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-info">Manage Bookings</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Cancel Requests</h5>
                    <p class="text-muted">Process booking cancellations and refunds.</p>
                    <a href="{{ route('admin.booking-cancel.index') }}" class="btn btn-info">Refund & Return</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
