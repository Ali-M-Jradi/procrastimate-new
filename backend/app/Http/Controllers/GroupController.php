<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function showGroupCreationForm()
    {
        return view('group.create_group');
    }

    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:groups',
            'description' => 'nullable|string|max:1000',
        ]);
        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        auth()->user()->groups()->attach($group->id);
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Group created successfully!');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard')->with('success', 'Group created successfully!');
        }
        return redirect()->back()->with('success', 'Group created successfully!');
    }

    public function showGroupUpdateForm($id)
    {
        $user = auth()->user();
        
        // Allow admins and coaches to edit any group
        if ($user->role === 'admin' || $user->role === 'coach') {
            $group = Group::findOrFail($id);
        } else {
            // Regular users can only edit their own groups
            $group = auth()->user()->groups()->findOrFail($id);
        }
        
        return view('group.edit', compact('group'));
    }

    public function updateGroup(Request $request, $id)
    {
        $user = auth()->user();
        
        // Allow admins and coaches to update any group
        if ($user->role === 'admin' || $user->role === 'coach') {
            $group = Group::findOrFail($id);
        } else {
            // Regular users can only update their own groups
            $group = auth()->user()->groups()->findOrFail($id);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);
        
        $group->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Group updated successfully!');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard')->with('success', 'Group updated successfully!');
        }
        
        return redirect()->back()->with('success', 'Group updated successfully!');
    }

    public function deleteGroup($id)
    {
        $group = auth()->user()->groups()->findOrFail($id);
        $group->delete();
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Group deleted successfully!');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard')->with('success', 'Group deleted successfully!');
        }
        return redirect()->back()->with('success', 'Group deleted successfully!');
    }

    public function joinGroup(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);
        $group = Group::findOrFail($request->group_id);
        auth()->user()->groups()->attach($group);
        return redirect()->route('userDashboard')->with('success', 'Joined group successfully!');
    }

    public function leaveGroup(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);
        $group = Group::findOrFail($request->group_id);
        auth()->user()->groups()->detach($group);
        return redirect()->route('userDashboard')->with('success', 'Left group successfully!');
    }

    public function showGroupJoinForm()
    {
        $groups = Group::all();
        return view('group.index', compact('groups'));
    }

    public function viewGroup($id)
    {
        $group = auth()->user()->groups()->findOrFail($id);
        // Pass as a collection to maintain compatibility with view_groups.blade.php
        $groups = collect([$group]);
        return view('group.view_groups', compact('groups', 'group'));
    }
    public function listGroups()
    {
        $groups = Group::all();
        return view('group.view_groups', compact('groups'));
    }
}
