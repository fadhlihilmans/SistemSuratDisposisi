<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class PrintPdfController extends Controller
{
    public function cetakLaporan(Request $request)
    {
        $validated = $request->validate([
            'tipe_surat' => 'required|in:surat_masuk,surat_keluar',
            'status' => 'nullable|string',
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        if ($request->tipe_surat === 'surat_masuk') {
            $query = SuratMasuk::with(['kodeSurat', 'disposisi']);
            
            if ($request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->tanggal_awal && $request->tanggal_akhir) {
                $query->whereBetween('tanggal_masuk', [$request->tanggal_awal, $request->tanggal_akhir]);
            }
            
            $data = $query->orderBy('tanggal_masuk', 'desc')->get();
            
            $tipeSurat = 'Surat Masuk';
            $statusLabel = $this->getStatusLabel('surat_masuk', $request->status);
            
        } else {
            $query = SuratKeluar::with(['kodeSurat', 'pegawai', 'persetujuan']);
            
            if ($request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->tanggal_awal && $request->tanggal_akhir) {
                $query->whereBetween('tanggal_pengajuan', [$request->tanggal_awal, $request->tanggal_akhir]);
            }
            
            $data = $query->orderBy('tanggal_pengajuan', 'desc')->get();
            
            $tipeSurat = 'Surat Keluar';
            $statusLabel = $this->getStatusLabel('surat_keluar', $request->status);
        }
        
        $periode = Carbon::parse($request->tanggal_awal)->locale('id')->translatedFormat('d F Y') . ' - ' . 
                   Carbon::parse($request->tanggal_akhir)->locale('id')->translatedFormat('d F Y');
        
        $viewData = [
            'tipeSurat' => $tipeSurat,
            'statusLabel' => $statusLabel,
            'periode' => $periode,
            'tanggal_cetak' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
            'data' => $data,
        ];
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $html = view('admin.laporan.pdf', $viewData)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'Laporan_' . str_replace(' ', '_', $tipeSurat) . '_' . date('d-m-Y') . '.pdf';
        
        return $dompdf->stream($filename);
    }
    
    private function getStatusLabel($type, $status)
    {
        if (empty($status)) {
            return 'Semua Status';
        }
        
        if ($type === 'surat_masuk') {
            return $status === 'disposisi' ? 'Disposisi' : 'Belum Disposisi';
        } else {
            switch ($status) {
                case 'disetujui': return 'Disetujui';
                case 'ditolak': return 'Ditolak';
                case 'menunggu': return 'Menunggu';
                default: return 'Semua Status';
            }
        }
    }
}
