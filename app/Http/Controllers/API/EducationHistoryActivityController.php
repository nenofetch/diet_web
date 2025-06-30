<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\EducationHistoryActivity;
use Illuminate\Support\Facades\Auth;

class EducationHistoryActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $tglInput = $request->tgl_input;
        $educationName = $request->education_name;

        $educationHistoryActivity = EducationHistoryActivity::create([
            'user_id' => Auth::user()->id,
            'education_name' => $educationName,
            'tgl_input' => $tglInput,
        ]);


        return ResponseFormatter::success($educationHistoryActivity, 'Data berhasil disimpan!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
