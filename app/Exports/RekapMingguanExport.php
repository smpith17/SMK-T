<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RekapMingguanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    // Menerima kiriman data dari Controller
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Mengembalikan kumpulan data untuk diisi ke excel
    public function collection()
    {
        return $this->data;
    }

    // Mengatur judul kolom paling atas di excel
    public function headings(): array
    {
        return [
            'No. Kartu',
            'Nama Nasabah',
            'Lokasi ATM',
            'Tanggal Masuk',
            'Tanggal Selesai',
            'Status Akhir'
        ];
    }

    // Memetakan field database agar masuk ke kolom yang tepat
    public function map($row): array
    {
        return [
            $row->nomor_kartu,
            $row->nama_nasabah,
            $row->lokasi_atm,
            $row->tanggal_masuk,
            $row->tanggal_selesai ?? '-',
            $row->status_akhir ?? $row->status
        ];
    }
}