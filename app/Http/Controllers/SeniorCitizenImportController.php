<?php

namespace App\Http\Controllers;

use App\Imports\SeniorCitizenImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class SeniorCitizenImportController extends Controller
{
    /**
     * Show the import form (Admin only via route middleware).
     */
    public function create()
    {
        return view('imports.seniors');
    }

    /**
     * Handle CSV/Excel import (Admin only via route middleware).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx', 'max:5120'],
        ]);

        try {
            Excel::import(new SeniorCitizenImport, $data['file']);
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Import completed successfully.');
    }
}

