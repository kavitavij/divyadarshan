@extends('layouts.temple-manager')
@section('title', 'Temple Manager Dashboard')

@section('content')
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }

        h1 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .welcome-text {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 30px;
        }

        .alert {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            position: relative;
        }

        .alert button {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #3c763d;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 40px;
        }

        .col {
            flex: 1;
            min-width: 250px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .card h5 {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .btn {
            margin-top: 15px;
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            text-align: center;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .card-header {
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
        }

        ul li a {
            text-decoration: none;
            color: #3498db;
            font-weight: 500;
        }

        ul li a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="container">

        <h1>Temple Manager Dashboard</h1>
        <p class="welcome-text">Welcome, <strong>{{ Auth::user()->name }}</strong>! Use this dashboard to manage temples,
            bookings, and services efficiently.</p>

        @if (session('success'))
            <div class="alert">
                {{ session('success') }}
                <button onclick="this.parentElement.style.display='none';">&times;</button>
            </div>
        @endif

        {{-- Quick Stats --}}
        <div class="row">
            @php
                $stats = [
                    [
                        'title' => 'Temples',
                        'count' => \App\Models\Temple::count(),
                        'route' => route('admin.temples.index'),
                    ],
                    ['title' => 'Bookings', 'count' => \App\Models\Booking::count() ?? 0, 'route' => '#'],
                    ['title' => 'Sevas', 'count' => \App\Models\Seva::count() ?? 0, 'route' => '#'],
                    ['title' => 'Accommodation', 'count' => '--', 'route' => '#'],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="col">
                    <div class="card">
                        <div>
                            <h5>{{ $stat['title'] }}</h5>
                            <p>{{ $stat['count'] }}</p>
                        </div>
                        <a href="{{ $stat['route'] }}" class="btn">Manage</a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Quick Links --}}
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Temple Management</div>
                    <ul>
                        <li><a href="#">➕ Add New Temple</a></li>
                        <li><a href="#">📋 View / Edit Temples</a></li>
                        <li><a href="#gallery">🖼 Manage Gallery</a></li>
                        <li><a href="#map">🗺 Update Maps</a></li>
                    </ul>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Reports & Analytics</div>
                    <ul>
                        <li><a href="#">📊 Booking Reports</a></li>
                        <li><a href="#">📈 Seva Trends</a></li>
                        <li><a href="#">🏨 Accommodation Reports</a></li>
                        <li><a href="#">💰 Finance Summary</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection
