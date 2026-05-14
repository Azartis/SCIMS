<?php

namespace App\Http\Controllers;

use App\Models\SavedFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedFilterController extends Controller
{
    /**
     * Store a new saved filter preset for the current user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'context' => ['required', 'string', 'max:150'],
            'filters' => ['required', 'array'],
        ]);

        SavedFilter::create([
            'user_id' => Auth::id(),
            'context' => $data['context'],
            'name' => $data['name'],
            'filters' => $data['filters'],
        ]);

        return back()->with('success', 'Filter preset saved.');
    }

    /**
     * Delete a saved filter belonging to the current user.
     */
    public function destroy(SavedFilter $savedFilter)
    {
        abort_unless($savedFilter->user_id === Auth::id(), 403);

        $savedFilter->delete();

        return back()->with('success', 'Filter preset removed.');
    }
}

