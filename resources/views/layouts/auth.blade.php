<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DivyaDarshan Authentication')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Page Fade-In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Blob Animation */
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        /* Floating Element Animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* Orb Pulse Animation */
        @keyframes pulse {

            0%,
            100% {
                opacity: 0.2;
            }

            50% {
                opacity: 0.3;
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-3000 {
            animation-delay: 3s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Subtle Grid Pattern */
        .bg-grid-pattern {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* Main Gradient for Left Panel */
        /* Adjusted gradient to match DivyaDarshan's blue theme better */
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            /* Blue gradient */
        }

        /* Card Shadow */
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Input Focus Style */
        .input-focus:focus {
            outline: none;
            border-color: #3b82f6;
            /* Blue focus */
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            /* Lighter blue shadow */
        }

        /* Primary Button Gradient & Hover Effect */
        .btn-primary {
            background: linear-gradient(to right, #1e40af, #2563eb);
            /* Darker blue gradient */
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #1e3a8a, #1d4ed8);
            /* Slightly darker blue on hover */
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(30, 64, 175, 0.5);
            /* Blue shadow */
        }

        /* Alert Styles */
        .alert {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-success {
            background-color: #d1fae5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        /* Glass Effect for Cards/Elements */
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Ensure specific DivyaDarshan yellow is available */
        .text-divya-yellow {
            color: #FACC15;
        }

        /* Tailwind yellow-400 */
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body class="antialiased">

    @yield('content')

</body>

</html>
