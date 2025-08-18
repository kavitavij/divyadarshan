    <?php

    namespace App\Http\Controllers\HotelManager;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class DashboardController extends Controller
    {
        /**
         * Show the hotel manager dashboard.
         */
        public function index()
        {
            $manager = Auth::user();
            $hotel = $manager->hotel; // Get the hotel managed by this user

            if (!$hotel) {
                // Handle case where the manager is not assigned to a hotel yet
                return view('hotel-manager.dashboard', ['error' => 'You are not yet assigned to a hotel. Please contact the site administrator.']);
            }

            return view('hotel-manager.dashboard', compact('hotel'));
        }
    }
    