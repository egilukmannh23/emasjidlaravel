<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use Carbon\Carbon;

class KasController extends Controller
{
    public function index()
    {
        $kas = Kas::UserMasjid()->latest()->paginate(50);
        $saldoAkhir = Kas::SaldoAkhir();
        return view('kas_index', compact('kas', 'saldoAkhir'));
    }

    public function create()
    {
        // Form untuk membuat data baru
        $kas = new kas();
        $saldoAkhir = Kas::SaldoAkhir();
        $disable = [];
        return view('kas_form', compact('kas', 'saldoAkhir', 'disable'));
    }

    public function store(Request $request)
    {
        // Validasi input
      $requestData =  $request->validate([
            // 'masjid_id' => 'required',
            'tanggal' => 'required|date',
            'kategori' => 'nullable',
            'keterangan' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required',
            // 'saldo_akhir' => 'required',
            // 'created_by' => 'required',
        ]);
        $tanggalTransaksi = Carbon::parse($requestData['tanggal']);
        $tahunBulanTransaksi = $tanggalTransaksi->format('Ym');
        $tahunBulanSekarang = Carbon::now()->format('Ym');
        if($tahunBulanTransaksi != $tahunBulanSekarang){
            flash('Data gagal di tambahkan. Transaksi hanya bisa dilakukan untuk tanggal dan bulan saat ini!!')->error();
            return back();
        }

        $requestData['jumlah'] = str_replace('.', '', $requestData['jumlah']);
        $saldoAkhir = Kas::SaldoAkhir();
            if($requestData['jenis'] == 'masuk'){
                $saldoAkhir += $requestData['jumlah'];
            } else {
                $saldoAkhir -= $requestData['jumlah'];
            }


        if($saldoAkhir <= -1){
            flash('Data kas gagal di tambahkan. Saldo akhir di akhir transaksi tidak boleh kurang dari 0.')->error();
            return back();
        }
        
        // ddd($requestData);
        $kas = new Kas();
        $kas->fill($requestData);
        $kas->masjid_id = auth()->user()->masjid_id;
        $kas->created_by = auth()->user()->id;
        $kas->save();
        auth()->user()->masjid->update(['saldo_akhir' => $saldoAkhir]);

        return redirect()->route('kas.index')->with('success', 'Data Berhasil Di Tambahkan');
    }

    public function edit($id)
    {
        $kas = Kas::findOrFail($id);
        $saldoAkhir = Kas::SaldoAkhir();
        $disable = ['disabled'];
        return view('kas_form', compact('kas', 'saldoAkhir', 'disable'));
    }
    
    public function update(Request $request, $id)
    {
        // Validasi input
       $requestData =  $request->validate([
            'kategori' => 'nullable',
            'keterangan' => 'required',
            'jumlah' => 'required',
        ]);

      $jumlah =str_replace('.', '', $requestData['jumlah']);
        $saldoAkhir = Kas::SaldoAkhir();
        $kas = Kas::findOrFail($id);
        if ($kas->jenis == 'masuk'){
            $saldoAkhir -= $kas->jumlah;
        }
        if ($kas->jenis == 'keluar'){
            $saldoAkhir += $kas->jumlah;
        }
        $saldoAkhir = $saldoAkhir + $jumlah;
        $requestData['jumlah'] = $jumlah;
        $kas->fill($requestData);
        $kas->save();
        auth()->user()->masjid->update(['saldo_akhir' => $saldoAkhir]);
        flash('Data Sudah Di perbarui');
        return redirect()->route('kas.index');
    }
    

    public function destroy($id)
    {
// Hapus data dari database
$kas = Kas::findOrFail($id);
$saldoAkhir = Kas::saldoAkhir();
if($kas->jenis =='masuk'){
    $saldoAkhir -= $kas-> jumlah;
}
if($kas->jenis =='keluar'){
    $saldoAkhir += $kas-> jumlah;
}

$kas->delete();
auth()->user()->masjid->update(['saldo_akhir' => $saldoAkhir]);
flash('data berhasil disimpan');
return redirect()->route('kas.index');
    }

    // private function calculateSaldoAkhir($tanggal)
    // {
    //     $transactions = Kas::where('tanggal', '<=', $tanggal)
    //     ->orderBy('tanggal')
    //     ->orderBy('id')
    //     ->get();

    //     $saldo = 0;

    //     foreach ($transactions as $transaction ){
    //         if($transaction->jenis == 'masuk'){
    //             $saldo += $transaction->jumlah;
    //         } else {
    //             $saldo -= $transaction->jumlah;
    //         }
    //     }

    //     return $saldo;
    // }
}
