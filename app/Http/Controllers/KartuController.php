<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KartuTertelan;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapMingguanExport;
use Maatwebsite\Excel\Facades\Excel;

class KartuController extends Controller
{
    public function index(Request $request)
    {
        $kartu = KartuTertelan::with('user_input')->get();

        foreach ($kartu as $k) {
            $k->sisa_hari = (int) Carbon::now()->diffInDays(Carbon::parse($k->deadline), false);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Daftar semua kartu tertelan berhasil diambil!',
                'data'    => $kartu
            ], 200);
        }

        return response()->json(['data' => $kartu]);
    }

    public function dashboard()
    {
        // Hitung total kritis langsung dari database agar tidak terpengaruh pagination
        $kritisCount = KartuTertelan::whereNotIn('status', ['Diambil', 'Dimusnahkan'])
            ->whereDate('deadline', '<=', Carbon::now()->addDays(3))
            ->count();

        // Pagination: Menampilkan 10 data per halaman pada dashboard
        $kartu = KartuTertelan::with('user_input')
            ->whereNotIn('status', ['Diambil', 'Dimusnahkan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        foreach ($kartu as $k) {
            $k->sisa_hari = (int) Carbon::now()->diffInDays(Carbon::parse($k->deadline), false);
        }

        $selesaiBulanIni = KartuTertelan::whereIn('status', ['Diambil', 'Dimusnahkan'])
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->count();

        return view('kartu.dashboard', compact('kartu', 'kritisCount', 'selesaiBulanIni'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nomor_kartu'   => 'required|digits:4',
            'nama_nasabah'  => 'required|min:2',
            'lokasi_atm'    => 'required|min:2',
            'lokasi_simpan' => 'required',
        ]);

        $kartu = KartuTertelan::create([
            'id'            => (string) Str::uuid(),
            'nomor_kartu'   => $request->nomor_kartu,
            'nama_nasabah'  => $request->nama_nasabah,
            'lokasi_atm'    => $request->lokasi_atm,
            'lokasi_simpan' => $request->lokasi_simpan,
            'tanggal_masuk' => Carbon::now(),
            'deadline'      => Carbon::now()->addDays(7),
            'status'        => 'Disimpan',
            'input_oleh'    => Auth::id(), 
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data kartu tertelan berhasil disimpan via API!',
                'data'    => $kartu
            ], 201);
        }

        return redirect('/input')->with('success', 'Data kartu tertelan berhasil disimpan!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $kartu = KartuTertelan::findOrFail($id);
        $kartu->status = $request->status;
        $kartu->save(); 

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Status kartu berhasil diperbarui via API!',
                'data'    => $kartu
            ], 200);
        }

        return redirect()->back()->with('success', 'Status kartu berhasil diperbarui!');
    }

    public function arsip()
    {
        // Pagination: Menampilkan 15 data per halaman pada arsip
        $arsip = KartuTertelan::with('user_input')
            ->whereIn('status', ['Diambil', 'Dimusnahkan'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        foreach ($arsip as $a) {
            $a->tanggal_selesai = Carbon::parse($a->updated_at)->format('d M Y');
            $a->tanggal_masuk   = Carbon::parse($a->tanggal_masuk)->format('d M Y');
            $a->status_akhir    = $a->status;

            $dbLogs = DB::table('log_audit')
                ->join('users', 'log_audit.user_id', '=', 'users.id')
                ->where('log_audit.kartu_id', $a->id)
                ->select('log_audit.*', 'users.username')
                ->orderBy('log_audit.created_at', 'asc')
                ->get();

            $formattedLogs = [];

            foreach ($dbLogs as $log) {
                $formattedLogs[] = [
                    'status' => $log->new_status ?? $log->action,
                    'tanggal' => Carbon::parse($log->created_at)->format('d M Y'),
                    'petugas' => $log->username ?? 'Petugas'
                ];
            }

            $a->custom_logs = $formattedLogs;
        }

        return view('kartu.arsip', compact('arsip'));
    }

    public function rekap()
    {
        $stats = (object)[
            'masuk'       => KartuTertelan::count(),
            'diambil'     => KartuTertelan::where('status', 'Diambil')->count(),
            'dimusnahkan' => KartuTertelan::where('status', 'Dimusnahkan')->count(),
        ];

        $grafikAtm = [
            (object)['nama' => 'ATM Center', 'jumlah' => 2, 'persentase' => 80, 'warna' => 'var(--red)'],
        ];

        $waktu = (object)['rata_rata' => 1, 'tercepat' => 1, 'terlama' => 1];

        return view('kartu.rekap', compact('stats', 'grafikAtm', 'waktu'));
    }

    public function exportExcel()
    {
        $data = \App\Models\KartuTertelan::all(); 
        return Excel::download(new RekapMingguanExport($data), 'Rekap_Mingguan_SMKT.xlsx');
    }

    public function destroy($id)
    {
        $kartu = KartuTertelan::findOrFail($id);
        
        DB::table('log_audit')->where('kartu_id', $id)->delete();
        $kartu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kartu tertelan beserta history audit log-nya berhasil dihapus permanen!'
        ], 200);
    }
}