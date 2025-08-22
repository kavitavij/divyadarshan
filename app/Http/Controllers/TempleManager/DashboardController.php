<?php
namespace App\Http\Controllers\TempleManager;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

        class DashboardController extends Controller
        {
            /**
            * Show the temple manager dashboard.
            */
            public function index()
            {
                $manager = Auth::user();
                $temple = $manager->temple; // Get the temple managed by this user

                if (!$temple) {
                    // Handle case where the manager is not assigned to a temple yet
                    return view('temple-manager.dashboard', ['error' => 'You are not yet assigned to a temple. Please contact the site administrator.']);
                }

                return view('temple-manager.dashboard', compact('temple'));
            }
        }
