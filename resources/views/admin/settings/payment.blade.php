@extends('layouts.admin')

@section('title', 'Payment Gateway Settings')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Payment Gateway Settings</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('admin.settings.payment.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Razorpay Settings --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Razorpay</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="razorpay_active"
                                name="razorpay[is_active]" value="1" {{ $razorpay->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="razorpay_active">Enable Razorpay</label>
                        </div>
                        <div class="mb-3">
                            <label for="razorpay_key" class="form-label">Razorpay Key</label>
                            <input type="text" class="form-control" id="razorpay_key" name="razorpay[key]"
                                value="{{ $razorpay->key }}" placeholder="rzp_test_...">
                        </div>
                        <div class="mb-3">
                            <label for="razorpay_secret" class="form-label">Razorpay Secret</label>
                            <input type="password" class="form-control" id="razorpay_secret" name="razorpay[secret]"
                                value="{{ $razorpay->secret }}" placeholder="•••••••••••••••">
                            <small class="form-text">Leave secret blank to keep it unchanged.</small>
                        </div>
                    </div>
                </div>

                {{-- Stripe Settings --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Stripe</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="stripe_active"
                                name="stripe[is_active]" value="1" {{ $stripe->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="stripe_active">Enable Stripe</label>
                        </div>
                        <div class="mb-3">
                            <label for="stripe_key" class="form-label">Stripe Publishable Key</label>
                            <input type="text" class="form-control" id="stripe_key" name="stripe[key]"
                                value="{{ $stripe->key }}" placeholder="pk_test_...">
                        </div>
                        <div class="mb-3">
                            <label for="stripe_secret" class="form-label">Stripe Secret Key</label>
                            <input type="password" class="form-control" id="stripe_secret" name="stripe[secret]"
                                value="{{ $stripe->secret }}" placeholder="•••••••••••••••">
                            <small class="form-text">Leave secret blank to keep it unchanged.</small>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
@endsection
