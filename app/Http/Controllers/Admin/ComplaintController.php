<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::latest()->get();
        return view('admin.complaints.index', ['complaints' => $complaints]);
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
        return response()->json(['success' => true, 'message' => 'Complaint deleted successfully!']);
    }
}