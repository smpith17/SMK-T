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
    /**
     * BARU: Menampilkan daftar semua kartu tertelan khusus untuk Jalur API (Postman)
     */
    public function index(Request $request)
    {
        // Mengambil semua data kartu beserta informasi petugas yang menginputnya
        $kartu = KartuTertelan::with('user_input')->get();

        // Hitung sisa hari secara dinamis untuk setiap kartu
        foreach ($kartu as $k) {
            $k->sisa_hari = (int) Carbon::now()->diffInDays(Carbon::parse($k->deadline), false);
        }

        // Jika request datang dari Postman / API, kirim response JSON
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
        $kartu = KartuTertelan::with('user_input')
            ->whereNotIn('status', ['Diambil', 'Dimusnahkan'])->get();

        foreach ($kartu as $k) {
            $k->sisa_hari = (int) Carbon::now()->diffInDays(Carbon::parse($k->deadline), false);
        }

        $kritisCount = $kartu->where('sisa_hari', '<=', 3)->count();

        $selesaiBulanIni = KartuTertelan::whereIn('status', ['Diambil', 'Dimusnahkan'])
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->count();

        return view('kartu.dashboard', compact('kartu', 'kritisCount', 'selesaiBulanIni'));
    }

    /**
     * HYBRID: Menyimpan data kartu baru (Mendukung Web Form & Postman API)
     */
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

        // Kondisional: Jika mendeteksi request API, return JSON murni status 210/201
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data kartu tertelan berhasil disimpan via API!',
                'data'    => $kartu
            ], 201);
        }

        // Jika dari form website biasa, lakukan redirect
        return redirect('/input')->with('success', 'Data kartu tertelan berhasil disimpan!');
    }

    /**
     * HYBRID: Mengubah status kartu (Mendukung Web Action & Postman API)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $kartu = KartuTertelan::findOrFail($id);
        $kartu->status = $request->status;
        $kartu->save(); 

        // Kondisional: Jika mendeteksi request API, return JSON murni status 200 OK
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Status kartu berhasil diperbarui via API!',
                'data'    => $kartu
            ], 200);
        }

        // Jika dari tombol di website, kembalikan ke halaman sebelumnya
        return redirect()->back()->with('success', 'Status kartu berhasil diperbarui!');
    }

    public function arsip()
    {
        $arsip = KartuTertelan::with('user_input')
            ->whereIn('status', ['Diambil', 'Dimusnahkan'])
            ->get();

        foreach ($arsip as $a) {
            $a->tanggal_selesai = Carbon::parse($a->updated_at)->format('d M Y');
            $a->tanggal_masuk   = Carbon::parse($a->tanggal_masuk)->format('d M Y');
            $a->status_akhir    = $a->status;

            // Mengambil log asli dari tabel log_audit bawaan sistem dikombinasikan dengan tabel users
            $dbLogs = DB::table('log_audit')
                ->join('users', 'log_audit.user_id', '=', 'users.id')
                ->where('log_audit.kartu_id', $a->id)
                ->select('log_audit.*', 'users.username')
                ->orderBy('log_audit.created_at', 'asc')
                ->get();

            $formattedLogs = [];

            // Melacak seluruh log perubahan dari tabel log_audit secara dinamis (Termasuk log input pertama)
            foreach ($dbLogs as $log) {
                $formattedLogs[] = [
                    'status' => $log->new_status ?? $log->action,
                    'tanggal' => Carbon::parse($log->created_at)->format('d M Y'),
                    'petugas' => $log->username ?? 'Petugas'
                ];
            }

            // Menyisipkan hasil format ke properti dinamis model agar dibaca di Blade
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

    /**
     * BARU: Menghapus data kartu tertelan secara permanen via API (Menyelesaikan Error 405 Method Not Allowed)
     */
    public function destroy($id)
    {
        $kartu = KartuTertelan::findOrFail($id);
        $kartu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kartu tertelan berhasil dihapus secara permanen via API!'
        ], 200);
    }
}