<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SpiritualHelpRequest;
use Illuminate\Http\Request;

class SpiritualHelpController extends Controller
{
    public function index()
    {
        $requests = SpiritualHelpRequest::with('temple')->latest()->paginate(15);
        return view('admin.spiritual-help.index', compact('requests'));
    }

    public function destroy(SpiritualHelpRequest $spiritualHelpRequest)
    {
        $spiritualHelpRequest->delete();
        return redirect()->back()->with('success', 'Request marked as resolved and deleted.');
    }
}
