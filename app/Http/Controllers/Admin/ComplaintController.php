<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::latest()->paginate(15);
        return view('admin.complaints.index', ['complaints' => $complaints]);
    }
    /**
     * NEW: Display the specified complaint.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load('user');
        return view('admin.complaints.show', compact('complaint'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $complaint->status = $request->input('status');
        $complaint->save();
        return response()->json(['success' => true]);
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return redirect()->route('admin.complaints.index')->with('success', 'Complaint deleted successfully.');
    }
}
