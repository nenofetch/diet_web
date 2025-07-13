<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducationHistoryActivity;
use Illuminate\Support\Facades\Auth;

class EducationHistoryActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['educationHistoryActivity'] = EducationHistoryActivity::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('backend.histories.education.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $data['educationHistoryActivity'] = EducationHistoryActivity::find($id);
        return view('backend.histories.education.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $data = $request->validate([
            'education_name' => 'required|max:255',
            'tgl_input' => 'required',
        ]);

        $education = EducationHistoryActivity::find($id);
        $education->update([
            'education_name' => $request->education_name,
            'tgl_input' => $request->tgl_input,
        ]);

        return redirect()->route('histories.educations-history.index')->with('message', 'Riwayat pendidikan berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $education = EducationHistoryActivity::find($id);
        $education->delete();

        return redirect()->route('histories.educations-history.index')->with('message', 'Riwayat pendidikan berhasil dihapus!');
    }
}
