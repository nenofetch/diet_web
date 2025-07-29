<?php

namespace App\Http\Controllers\Backend;

use App\Exports\HistoryExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    private $perPage = 50; // Number of records per page

    public function bmi(Request $request)
    {
        $query = History::with(['user:id,name,email'])
            ->where('category', 'BMI')
            ->orderBy('tgl_input', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(1000); // Limit to prevent memory issues

        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data['bmi'] = $query->get();

        return view('backend.histories.bmi.index', $data);
    }

    public function bmiEdit($id)
    {
        $data['bmi'] = History::with('user:id,name,email')->findOrFail($id);
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

        $bmi = History::findOrFail($id);
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
        History::findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function bmr(Request $request)
    {
        $query = History::with(['user:id,name,email'])
            ->where('category', 'BMR')
            ->orderBy('created_at', 'desc')
            ->limit(1000); // Limit to prevent memory issues

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data['bmr'] = $query->get();

        return view('backend.histories.bmr.index', $data);
    }

    public function bmrEdit($id)
    {
        $data['bmr'] = History::with('user:id,name,email')->findOrFail($id);
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

        $bmr = History::findOrFail($id);
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
        History::findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function breakfast(Request $request)
    {
        $query = History::with(['user:id,name,email'])
            ->where('category', 'Makan Pagi')
            ->orderBy('created_at', 'desc')
            ->limit(1000); // Limit to prevent memory issues

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data['breakfast'] = $query->get();

        return view('backend.histories.breakfast.index', $data);
    }

    public function breakfastEdit($id)
    {
        $data['breakfast'] = History::with('user:id,name,email')->findOrFail($id);
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

        $breakfast = History::findOrFail($id);
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
        History::findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function lunch(Request $request)
    {
        $query = History::with(['user:id,name,email'])
            ->where('category', 'Makan Siang')
            ->orderBy('created_at', 'desc')
            ->limit(1000); // Limit to prevent memory issues

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data['lunch'] = $query->get();

        return view('backend.histories.lunch.index', $data);
    }

    public function lunchEdit($id)
    {
        $data['lunch'] = History::with('user:id,name,email')->findOrFail($id);
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

        $lunch = History::findOrFail($id);
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
        History::findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function dinner(Request $request)
    {
        $query = History::with(['user:id,name,email'])
            ->where('category', 'Makan Malam')
            ->orderBy('created_at', 'desc')
            ->limit(1000); // Limit to prevent memory issues

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data['dinner'] = $query->get();

        return view('backend.histories.dinner.index', $data);
    }

    public function dinnerEdit($id)
    {
        $data['dinner'] = History::with('user:id,name,email')->findOrFail($id);
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

        $dinner = History::findOrFail($id);
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
        History::findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function snack(Request $request)
    {
        $query = History::with(['user:id,name,email'])
            ->where('category', 'Cemilan')
            ->orderBy('created_at', 'desc')
            ->limit(1000); // Limit to prevent memory issues

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data['snack'] = $query->get();

        return view('backend.histories.snack.index', $data);
    }

    public function snackEdit($id)
    {
        $data['snack'] = History::with('user:id,name,email')->findOrFail($id);
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

        $snack = History::findOrFail($id);
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
        History::findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function drink(Request $request)
    {
        $query = History::with(['user:id,name,email'])
            ->where('category', 'Minuman')
            ->orderBy('created_at', 'desc')
            ->limit(1000); // Limit to prevent memory issues

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data['drink'] = $query->get();

        return view('backend.histories.drink.index', $data);
    }

    public function drinkEdit($id)
    {
        $data['drink'] = History::with('user:id,name,email')->findOrFail($id);
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

        $drink = History::findOrFail($id);
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
        History::findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function sport(Request $request)
    {
        $query = History::with(['user:id,name,email'])
            ->where('category', 'Olahraga')
            ->orderBy('created_at', 'desc')
            ->limit(1000); // Limit to prevent memory issues

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data['sport'] = $query->get();

        return view('backend.histories.sport.index', $data);
    }

    public function sportEdit($id)
    {
        $data['sport'] = History::with('user:id,name,email')->findOrFail($id);
        return view('backend.histories.sport.edit', $data);
    }

    public function sportUpdate(Request $request, $id)
    {
        // Validation
        $data = $request->validate([
            'name' => 'required|max:255',
            'tgl_input' => 'required',
            'duration' => 'required|numeric',
            'calories' => 'required|numeric',
        ]);

        $sport = History::findOrFail($id);
        $sport->update([
            'name' => $request->name,
            'tgl_input' => $request->tgl_input,
            'duration' => $request->duration,
            'calories' => $request->calories,
        ]);

        return redirect()->route('histories.sport')->with('message', 'Riwayat Olahraga berhasil diubah!');
    }

    public function sportDestroy($id)
    {
        History::findOrFail($id)->delete();
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }

    public function export()
    {
        try {
            // Set memory limit for export
            ini_set('memory_limit', '512M');
            set_time_limit(300); // 5 minutes timeout

            $date = now()->format('Y-m-d_H-i-s');
            return Excel::download(new HistoryExport, "laporan_{$date}.xlsx");
        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_records' => History::count(),
            'by_category' => History::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
            'recent_records' => History::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get()
        ];

        return response()->json($stats);
    }
}
