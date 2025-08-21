<?php

namespace App\Http\Controllers\Admin; // This namespace MUST be exactly correct

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    /**
     * Display a listing of the contact form submissions.
     */
    public function index()
    {
        $submissions = ContactSubmission::latest()->paginate(15);
        return view('admin.contact-submissions.index', compact('submissions'));
    }

    /**
     * Remove the specified submission from storage.
     */
    public function destroy(ContactSubmission $contactSubmission)
    {
        $contactSubmission->delete();
        return redirect()->route('admin.contact-submissions.index')->with('success', 'Submission deleted successfully.');
    }
}
