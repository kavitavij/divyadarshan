<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
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
        ]);

        $manager->update($request->only('name', 'email', 'role'));
        
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