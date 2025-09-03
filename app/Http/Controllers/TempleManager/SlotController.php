<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\DarshanSlot; // Make sure to create this model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DefaultDarshanSlot; // <-- Add
use App\Models\TempleDayStatus;
class SlotController extends Controller
{
    // Helper to get the authenticated manager's temple
    private function getManagerTemple()
    {
        // This assumes your User model has a 'temple' relationship. Adjust if needed.
        return Auth::user()->temple;
    }

    public function index()
    {
        $temple = Auth::user()->temple;
        $slots = DarshanSlot::where('temple_id', $temple->id)
                            ->where('slot_date', '>=', now()->toDateString())
                            ->orderBy('slot_date', 'asc')
                            ->orderBy('start_time', 'asc')
                            ->paginate(15);
        return view('temple-manager.slots.index', compact('slots', 'temple'));
    }

    public function create()
    {
        // You will need to create this view file
        return view('temple-manager.slots.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'slot_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'total_capacity' => 'required|integer|min:1',
        ]);

        $temple = $this->getManagerTemple();

        DarshanSlot::create([
            'temple_id' => $temple->id,
            'slot_date' => $request->slot_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_capacity' => $request->total_capacity,
            'booked_capacity' => 0, // Starts with zero bookings
        ]);

        return redirect()->route('temple-manager.slots.index')->with('success', 'Darshan slot created successfully.');
    }

    public function edit(DarshanSlot $slot)
    {
        if ($slot->temple_id !== $this->getManagerTemple()->id) {
            abort(403, 'Unauthorized action.');
        }
        // You will need to create this view file
        return view('temple-manager.slots.edit', compact('slot'));
    }

    public function update(Request $request, DarshanSlot $slot)
    {
        if ($slot->temple_id !== $this->getManagerTemple()->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'slot_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'total_capacity' => 'required|integer|min:' . $slot->booked_capacity,
        ]);

        $slot->update($request->only(['slot_date', 'start_time', 'end_time', 'total_capacity']));

        return redirect()->route('temple-manager.slots.index')->with('success', 'Slot updated successfully.');
    }

    public function destroy(DarshanSlot $slot)
    {
        if ($slot->temple_id !== $this->getManagerTemple()->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($slot->booked_capacity > 0) {
            return redirect()->back()->with('error', 'Cannot delete a slot that has active bookings.');
        }

        $slot->delete();
        return redirect()->route('temple-manager.slots.index')->with('success', 'Slot deleted successfully.');
    }
    private $defaultSlots = [
        ['start_time' => '09:00:00', 'end_time' => '11:00:00', 'label' => 'Morning (9 AM - 11 AM)'],
        ['start_time' => '11:00:00', 'end_time' => '13:00:00', 'label' => 'Late Morning (11 AM - 1 PM)'],
        ['start_time' => '15:00:00', 'end_time' => '17:00:00', 'label' => 'Afternoon (3 PM - 5 PM)'],
        ['start_time' => '17:00:00', 'end_time' => '19:00:00', 'label' => 'Evening (5 PM - 7 PM)'],
    ];

    // ... your existing index(), create(), store() etc. methods are fine ...
    public function deleteDayStatus($id)
{
    $status = TempleDayStatus::where('id', $id)
                ->where('temple_id', Auth::user()->temple->id)
                ->firstOrFail();

    $status->delete();

    return redirect()->back()->with('success', 'Day status entry deleted.');
}


     public function settings()
{
    $temple = Auth::user()->temple;

    $defaultSlots = DefaultDarshanSlot::where('temple_id', $temple->id)
                    ->orderBy('start_time')
                    ->get();

    // ✅ Get day statuses for this temple
    $dayStatuses = TempleDayStatus::where('temple_id', $temple->id)
                    ->orderBy('date', 'desc')
                    ->get();

    return view('temple-manager.slots.settings', compact('defaultSlots', 'temple', 'dayStatuses'));
}

    /**
     * Store the multiple default slots from the bulk create form.
     * NEW METHOD
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'slots' => 'required|array',
            'slots.*.start_time' => 'required',
            'slots.*.end_time' => 'required|after:slots.*.start_time',
            'slots.*.capacity' => 'required|integer|min:0',
        ]);

        foreach ($request->slots as $id => $slotData) {
            DefaultDarshanSlot::updateOrCreate(
                ['id' => $id, 'temple_id' => Auth::user()->temple->id],
                [
                    'start_time' => $slotData['start_time'],
                    'end_time' => $slotData['end_time'],
                    'capacity' => $slotData['capacity'],
                    'is_active' => isset($slotData['is_active']),
                ]
            );
        }
        return redirect()->back()->with('success', 'Default slot settings have been updated.');
    }
    public function updateDayStatus(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'action' => 'required|string|in:close,open',
            'reason' => 'nullable|string|max:255',
        ]);

        TempleDayStatus::updateOrCreate(
            ['temple_id' => Auth::user()->temple->id, 'date' => $request->date],
            [
                'is_closed' => $request->action === 'close',
                'reason' => $request->reason,
            ]
        );
        $message = $request->action === 'close' ? 'closed for bookings.' : 're-opened for bookings.';
        return redirect()->back()->with('success', "Date {$request->date} has been {$message}");
    }
}
