<?php

namespace App\Http\Controllers\Backend;

use App\Exports\HistoryExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;

class HistoryController extends Controller
{
    public function bmi()
    {
        $data['bmi'] = History::where('category', 'BMI')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.bmi', $data);
    }

    public function bmiDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function bmr()
    {
        $data['bmr'] = History::where('category', 'BMR')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.bmr', $data);
    }

    public function bmrDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function breakfast()
    {
        $data['breakfast'] = History::where('category', 'Makan Pagi')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.breakfast', $data);
    }

    public function breakfastDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function lunch()
    {
        $data['lunch'] = History::where('category', 'Makan Siang')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.lunch', $data);
    }

    public function lunchDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function dinner()
    {
        $data['dinner'] = History::where('category', 'Makan Malam')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.dinner', $data);
    }

    public function dinnerDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function snack()
    {
        $data['snack'] = History::where('category', 'Cemilan')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.snack', $data);
    }

    public function snackDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function drink()
    {
        $data['drink'] = History::where('category', 'Minuman')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.drink', $data);
    }

    public function drinkDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function sport()
    {
        $data['sport'] = History::where('category', 'Olahraga')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.sport', $data);
    }

    public function sportDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function export()
    {
        $filename = (new DateTime('now'))->format('Y-m-d_H-i-s') . '-riwayat-laporan.xlsx';
        return Excel::download(new HistoryExport, $filename);
    }
}
