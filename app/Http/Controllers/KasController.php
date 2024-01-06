<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;

class KasController extends Controller
{
    public function index()
    {
        $kas = Kas::UserMasjid()->latest()->paginate(50);
        return view('kas_index', compact('kas'));
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
        $kas->saldo_akhir = $saldoAkhir;
        $kas->save();

        return redirect()->route('kas.index')->with('success', 'Data Berhasil Di Tambahkan');
    }
<<<<<<< HEAD
    public function edit($id){
        $kas = Kas::findOrfail($id);
        $saldoAkhir =Kas::saldoAkhir();
        return view('kas_form',compact('kas','saldoAkhir'));
=======

    public function edit($id)
    {
        $kas = Kas::findOrFail($id);
        $saldoAkhir = Kas::SaldoAkhir();
        $disable = ['disabled'];
        return view('kas_form', compact('kas', 'saldoAkhir', 'disable'));
>>>>>>> 84ccb807e1fb91b845f90be428f4506383966830
    }
    
    public function update(Request $request, $id)
    {
        // Validasi input
       $requestData =  $request->validate([
            'kategori' => 'nullable',
            'keterangan' => 'required',
        ]);

        $kas = Kas::findOrFail($id);
        $kas->fill($requestData);
        $kas->save();
        flash('Data Sudah Di perbarui');
        return redirect()->route('kas.index');
    }
    

    public function destroy($id)
    {
// Hapus data dari database
$kas = Kas::findOrFail($id);
$kas->keterangan = 'dihapus oleh' . auth()->user()->name;
$kas->save();

$kasBaru = $kas->replicate();
$kasBaru->keterangan = 'perbaikan data id ke' . $kas->id;
$saldoAkhir = Kas::saldoAkhir();
if($kas->jenis =='masuk'){
    $saldoAkhir -= $kas-> jumlah;
}
if($kas->jenis =='keluar'){
    $saldoAkhir += $kas-> jumlah;
}
$kasBaru->saldo_akhir = $saldoAkhir;
$kasBaru->save();
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
