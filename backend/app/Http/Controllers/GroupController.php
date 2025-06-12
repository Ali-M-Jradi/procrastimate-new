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
        $user = auth()->user();
        
        // Allow admins and coaches to delete any group
        if ($user->role === 'admin' || $user->role === 'coach') {
            $group = Group::findOrFail($id);
        } else {
            // Regular users can only delete their own groups
            $group = auth()->user()->groups()->findOrFail($id);
        }
        
        $group->delete();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Group deleted successfully!');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard')->with('success', 'Group deleted successfully!');
        }
        return redirect()->back()->with('success', 'Group deleted successfully!');
    }

    public function joinGroup(Request $request)
    {
        try {
            $request->validate([
                'group_id' => 'required|exists:groups,id',
            ]);
            
            $user = auth()->user();
            $groupId = $request->group_id;
            
            // Check if user is already a member of this group
            if ($user->groups()->where('group_id', $groupId)->exists()) {
                return redirect()->route('userDashboard')->with('error', 'You are already a member of this group.');
            }
            
            // Attach the user to the group using just the ID
            $user->groups()->attach($groupId);
            
            \Log::info("User {$user->id} joined group {$groupId} successfully");
            
            return redirect()->route('userDashboard')->with('success', 'Joined group successfully!');
        } catch (\Exception $e) {
            \Log::error("Error joining group: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('userDashboard')->with('error', 'Failed to join group. Please try again. Error: ' . $e->getMessage());
        }
    }

    public function leaveGroup(Request $request)
    {
        try {
            $request->validate([
                'group_id' => 'required|exists:groups,id',
            ]);
            
            $groupId = $request->group_id;
            $user = auth()->user();
            
            // Check if user is a member of this group
            if (!$user->groups()->where('group_id', $groupId)->exists()) {
                return redirect()->route('userDashboard')->with('error', 'You are not a member of this group.');
            }
            
            // Detach the user from the group using just the ID
            $user->groups()->detach($groupId);
            
            \Log::info("User {$user->id} left group {$groupId} successfully");
            
            return redirect()->route('userDashboard')->with('success', 'Left group successfully!');
        } catch (\Exception $e) {
            \Log::error("Error leaving group: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('userDashboard')->with('error', 'Failed to leave group. Please try again.');
        }
    }

    public function showGroupJoinForm()
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'You must be logged in to join a group.');
            }
            
            // Get all groups
            $allGroups = Group::all();
            
            // Get the groups the current user is already a member of
            $userGroups = auth()->user()->groups()->pluck('groups.id')->toArray();
            
            // Filter out groups the user is already a member of
            $groups = $allGroups->reject(function($group) use ($userGroups) {
                return in_array($group->id, $userGroups);
            });
            
            return view('group.join_groups', compact('groups'));
        } catch (\Exception $e) {
            \Log::error('Error in showGroupJoinForm: ' . $e->getMessage());
            return redirect()->route('userDashboard')
                ->with('error', 'There was a problem accessing the join group form. Please try again.');
        }
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
