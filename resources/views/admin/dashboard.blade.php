@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-3">Dashboard Overview</h4>
        <p class="text-muted">This is your central admin dashboard. Use the quick actions below to manage different sections
            of your site.</p>

        <div class="row">
            <!-- Manage Temples -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Temples</h5>
                        <p class="text-muted">Add, edit, or remove temple details.</p>
                        <a href="{{ route('admin.temples.index') }}" class="btn btn-primary">Manage Temples</a>
                    </div>
                </div>
            </div>

            <!-- Manage Ebooks -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">E-Books</h5>
                        <p class="text-muted">Upload and manage e-book resources.</p>
                        <a href="{{ route('admin.ebooks.index') }}" class="btn btn-success">Manage E-Books</a>
                    </div>
                </div>
            </div>

            <!-- Latest Updates -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Latest Updates</h5>
                        <p class="text-muted">Share announcements and news.</p>
                        <a href="{{ route('admin.latest_updates.index') }}" class="btn btn-warning">Latest Updates</a>
                    </div>
                </div>
            </div>

            <!-- Complaints -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Complaints</h5>
                        <p class="text-muted">View and resolve user complaints.</p>
                        <a href="{{ route('admin.complaints.index') }}" class="btn btn-danger">Manage Complaints</a>
                    </div>
                </div>
            </div>

            <!-- Bookings -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Bookings</h5>
                        <p class="text-muted">Darshan & Sevas booking management.</p>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-info">Manage Bookings</a>
                    </div>
                </div>
            </div>
            <!-- Cancel/return -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Cancel Request</h5>
                        <p class="text-muted">Cancel & Return process</p>
                        <a href="{{ route('admin.booking-cancel.index') }}" class="btn btn-info">Refund Return</a>
                    </div>
                </div>
            </div>
            {{-- Donations --}}
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Donations</h5>
                        <p class="text-muted">View all Donations.</p>
                        <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">Donations</a>
                    </div>
                </div>
            </div>
            <!-- Hotels -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Hotels</h5>
                        <p class="text-muted">Add and manage hotels/accommodations.</p>
                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">Manage Hotels</a>
                    </div>
                </div>
            </div>

            <!-- Contact Messages -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Contact Messages</h5>
                        <p class="text-muted">Read and respond to user messages.</p>
                        <a href="{{ route('admin.contact-submissions.index') }}" class="btn btn-dark">View Messages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
