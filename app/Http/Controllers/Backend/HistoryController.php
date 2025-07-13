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

        return view('backend.histories.bmi.index', $data);
    }

    public function bmiEdit($id)
    {
        $data['bmi'] = History::find($id);
        return view('backend.histories.bmi.edit', $data);
    }

    public function bmiUpdate(Request $request, $id)
    {
        // Validation
        $data = $request->validate([
            'name' => 'required|max:255',
            'tgl_input' => 'required',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'imt' => 'required|numeric',
            'result_bmi' => 'required|max:255',
        ]);

        $bmi = History::find($id);
        $bmi->update([
            'name' => $request->name,
            'tgl_input' => $request->tgl_input,
            'height' => $request->height,
            'weight' => $request->weight,
            'imt' => $request->imt,
            'result_bmi' => $request->result_bmi,
        ]);

        return redirect()->route('histories.bmi')->with('message', 'Riwayat BMI berhasil diubah!');
    }

    public function bmiDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function bmr()
    {
        $data['bmr'] = History::where('category', 'BMR')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.bmr.index', $data);
    }

    public function bmrEdit($id)
    {
        $data['bmr'] = History::find($id);
        return view('backend.histories.bmr.edit', $data);
    }

    public function bmrUpdate(Request $request, $id)
    {
        // Validation
        $data = $request->validate([
            'name' => 'required|max:255',
            'tgl_input' => 'required',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'result_bmr' => 'required|numeric',
        ]);

        $bmr = History::find($id);
        $bmr->update([
            'name' => $request->name,
            'tgl_input' => $request->tgl_input,
            'height' => $request->height,
            'weight' => $request->weight,
            'result_bmr' => $request->result_bmr,
        ]);

        return redirect()->route('histories.bmr')->with('message', 'Riwayat BMR berhasil diubah!');
    }

    public function bmrDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function breakfast()
    {
        $data['breakfast'] = History::where('category', 'Makan Pagi')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.breakfast.index', $data);
    }

    public function breakfastEdit($id)
    {
        $data['breakfast'] = History::find($id);
        return view('backend.histories.breakfast.edit', $data);
    }

    public function breakfastUpdate(Request $request, $id)
    {
        // Validation
        $data = $request->validate([
            'name' => 'required|max:255',
            'tgl_input' => 'required',
            'calories' => 'required|numeric',
            'carbohydrates' => 'required|numeric',
            'protein' => 'required|numeric',
            'fat' => 'required|numeric',
        ]);

        $breakfast = History::find($id);
        $breakfast->update([
            'name' => $request->name,
            'tgl_input' => $request->tgl_input,
            'calories' => $request->calories,
            'carbohydrates' => $request->carbohydrates,
            'protein' => $request->protein,
            'fat' => $request->fat,
        ]);

        return redirect()->route('histories.breakfast')->with('message', 'Riwayat Makan Pagi berhasil diubah!');
    }

    public function breakfastDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function lunch()
    {
        $data['lunch'] = History::where('category', 'Makan Siang')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.lunch.index', $data);
    }

    public function lunchEdit($id)
    {
        $data['lunch'] = History::find($id);
        return view('backend.histories.lunch.edit', $data);
    }

    public function lunchUpdate(Request $request, $id)
    {
        // Validation
        $data = $request->validate([
            'name' => 'required|max:255',
            'tgl_input' => 'required',
            'calories' => 'required|numeric',
            'carbohydrates' => 'required|numeric',
            'protein' => 'required|numeric',
            'fat' => 'required|numeric',
        ]);

        $lunch = History::find($id);
        $lunch->update([
            'name' => $request->name,
            'tgl_input' => $request->tgl_input,
            'calories' => $request->calories,
            'carbohydrates' => $request->carbohydrates,
            'protein' => $request->protein,
            'fat' => $request->fat,
        ]);

        return redirect()->route('histories.lunch')->with('message', 'Riwayat Makan Siang berhasil diubah!');
    }

    public function lunchDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function dinner()
    {
        $data['dinner'] = History::where('category', 'Makan Malam')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.dinner.index', $data);
    }

    public function dinnerEdit($id)
    {
        $data['dinner'] = History::find($id);
        return view('backend.histories.dinner.edit', $data);
    }

    public function dinnerUpdate(Request $request, $id)
    {
        // Validation
        $data = $request->validate([
            'name' => 'required|max:255',
            'tgl_input' => 'required',
            'calories' => 'required|numeric',
            'carbohydrates' => 'required|numeric',
            'protein' => 'required|numeric',
            'fat' => 'required|numeric',
        ]);

        $dinner = History::find($id);
        $dinner->update([
            'name' => $request->name,
            'tgl_input' => $request->tgl_input,
            'calories' => $request->calories,
            'carbohydrates' => $request->carbohydrates,
            'protein' => $request->protein,
            'fat' => $request->fat,
        ]);

        return redirect()->route('histories.dinner')->with('message', 'Riwayat Makan Malam berhasil diubah!');
    }

    public function dinnerDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function snack()
    {
        $data['snack'] = History::where('category', 'Cemilan')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.snack.index', $data);
    }

    public function snackEdit($id)
    {
        $data['snack'] = History::find($id);
        return view('backend.histories.snack.edit', $data);
    }

    public function snackUpdate(Request $request, $id)
    {
        // Validation
        $data = $request->validate([
            'name' => 'required|max:255',
            'tgl_input' => 'required',
            'calories' => 'required|numeric',
            'carbohydrates' => 'required|numeric',
            'protein' => 'required|numeric',
            'fat' => 'required|numeric',
        ]);

        $snack = History::find($id);
        $snack->update([
            'name' => $request->name,
            'tgl_input' => $request->tgl_input,
            'calories' => $request->calories,
            'carbohydrates' => $request->carbohydrates,
            'protein' => $request->protein,
            'fat' => $request->fat,
        ]);

        return redirect()->route('histories.snack')->with('message', 'Riwayat Cemilan berhasil diubah!');
    }

    public function snackDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function drink()
    {
        $data['drink'] = History::where('category', 'Minuman')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.drink.index', $data);
    }

    public function drinkEdit($id)
    {
        $data['drink'] = History::find($id);
        return view('backend.histories.drink.edit', $data);
    }

    public function drinkUpdate(Request $request, $id)
    {
        // Validation
        $data = $request->validate([
            'name' => 'required|max:255',
            'tgl_input' => 'required',
            'calories' => 'required|numeric',
            'carbohydrates' => 'required|numeric',
            'protein' => 'required|numeric',
            'fat' => 'required|numeric',
        ]);

        $drink = History::find($id);
        $drink->update([
            'name' => $request->name,
            'tgl_input' => $request->tgl_input,
            'calories' => $request->calories,
            'carbohydrates' => $request->carbohydrates,
            'protein' => $request->protein,
            'fat' => $request->fat,
        ]);

        return redirect()->route('histories.drink')->with('message', 'Riwayat Minuman berhasil diubah!');
    }

    public function drinkDestroy($id)
    {
        History::find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function sport()
    {
        $data['sport'] = History::where('category', 'Olahraga')->orderBy('created_at', 'desc')->get();

        return view('backend.histories.sport.index', $data);
    }

    public function sportEdit($id)
    {
        $data['sport'] = History::find($id);
        return view('backend.histories.sport.edit', $data);
    }

    public function sportUpdate(Request $request, $id)
    {
        // Validation
        $data = $request->validate([
            'name' => 'required|max:255',
            'tgl_input' => 'required',
            'duration' => 'required|numeric',
        ]);

        $sport = History::find($id);
        $sport->update([
            'name' => $request->name,
            'tgl_input' => $request->tgl_input,
            'duration' => $request->duration,
        ]);

        return redirect()->route('histories.sport')->with('message', 'Riwayat Olahraga berhasil diubah!');
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
