<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ManagerController extends Controller
{
    public function index()
    {
        $managers = User::whereHas('roles', function ($query) {
            $query->where('name', 'hotel_manager')->orWhere('name', 'temple_manager');
        })->with(['hotel', 'temple'])->latest()->paginate(10);

        return view('admin.managers.index', compact('managers'));
    }

    public function show(User $manager)
    {
        // Load relationships to make sure we have all the data
        $manager->load(['hotel', 'temple', 'roles']);
        
        // Return the new view, passing the manager data to it
        return view('admin.managers.show', compact('manager'));
    }
    public function create()
    {
        $hotels = Hotel::whereNull('manager_id')->orderBy('name')->get();
        $temples = Temple::whereNull('manager_id')->orderBy('name')->get();
        return view('admin.managers.create', compact('hotels', 'temples'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:hotel_manager,temple_manager'],
            'hotel_id' => ['nullable', 'exists:hotels,id'],
            'temple_id' => ['nullable', 'exists:temples,id'],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // Max 1MB
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo_path'] = $path;
        }

        $user = User::create($userData);

        $user->role = $request->role;
        $user->save();

        $user->syncRoles([$request->role]);

        if ($request->role === 'hotel_manager' && $request->hotel_id) {
            Hotel::find($request->hotel_id)->update(['manager_id' => $user->id]);
        }
        if ($request->role === 'temple_manager' && $request->temple_id) {
            Temple::find($request->temple_id)->update(['manager_id' => $user->id]);
        }

        return redirect()->route('admin.managers.index')->with('success', 'Manager created successfully.');
    }

    public function edit(User $manager)
    {
        $hotels = Hotel::whereNull('manager_id')->orWhere('manager_id', $manager->id)->orderBy('name')->get();
        $temples = Temple::whereNull('manager_id')->orWhere('manager_id', $manager->id)->orderBy('name')->get();
        return view('admin.managers.edit', compact('manager', 'hotels', 'temples'));
    }

    public function update(Request $request, User $manager)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $manager->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:hotel_manager,temple_manager'],
            'hotel_id' => ['nullable', 'exists:hotels,id'],
            'temple_id' => ['nullable', 'exists:temples,id'],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // Max 1MB
        ]);

        $updateData = $request->only('name', 'email', 'role');
        
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($manager->profile_photo_path) {
                Storage::disk('public')->delete($manager->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $updateData['profile_photo_path'] = $path;
        }
        
        $manager->update($updateData);

        if ($request->filled('password')) {
            $manager->update(['password' => Hash::make($request->password)]);
        }

        $manager->syncRoles([$request->role]);

        if ($manager->hotel) $manager->hotel->update(['manager_id' => null]);
        if ($manager->temple) $manager->temple->update(['manager_id' => null]);

        if ($request->role === 'hotel_manager' && $request->hotel_id) {
            Hotel::find($request->hotel_id)->update(['manager_id' => $manager->id]);
        }
        if ($request->role === 'temple_manager' && $request->temple_id) {
            Temple::find($request->temple_id)->update(['manager_id' => $manager->id]);
        }

        return redirect()->route('admin.managers.index')->with('success', 'Manager updated successfully.');
    }

    public function destroy(User $manager)
    {
        if ($manager->hotel) $manager->hotel->update(['manager_id' => null]);
        if ($manager->temple) $manager->temple->update(['manager_id' => null]);

        $manager->delete();

        return redirect()->route('admin.managers.index')->with('success', 'Manager deleted successfully.');
    }
}
