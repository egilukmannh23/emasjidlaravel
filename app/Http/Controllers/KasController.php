<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;

class KasController extends Controller
{
    public function index()
    {
        $kas = kas::UserMasjid()->latest()->paginate(50);
        return view('kas_index', compact('kas'));
    }

    public function create()
    {
        // Form untuk membuat data baru
        $kas = new kas();
        $saldoAkhir = Kas::SaldoAkhir();
        return view('kas_form', compact('kas', 'saldoAkhir'));
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
    public function edit($id){
        $kas = Kas::findOrfail($id);
        $saldoAkhir =Kas::saldoAkhir();
        return view('kas_form',compact('kas','saldoAkhir'));
    }
    
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'masjid_id' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'required',
            'jenis' => 'required',
            'jumlah' => 'required',
            'saldo_akhir' => 'required',
            'created_by' => 'required',
        ]);
    
        // Hitung saldo akhir berdasarkan jenis transaksi
        $saldoAkhir = $request->jenis == 'masuk' ? $request->saldo_akhir + $request->jumlah : $request->saldo_akhir - $request->jumlah;
    
        // Tambahkan nilai saldo_akhir ke dalam input request
        $request->merge(['saldo_akhir' => $saldoAkhir]);
    
        // Update data di database
        Kas::findOrFail($id)->update($request->all());
    
        return redirect()->route('kas.index')->with('success', 'Data kas berhasil diperbarui.');
    }
    

    public function destroy($id)
    {
        // Hapus data dari database
        Kas::findOrFail($id)->delete();

        return redirect()->route('kas.index')->with('success', 'Data kas berhasil dihapus.');
    }

    private function calculateSaldoAkhir($tanggal)
    {
        $transactions = Kas::where('tanggal', '<=', $tanggal)
        ->orderBy('tanggal')
        ->orderBy('id')
        ->get();

        $saldo = 0;

        foreach ($transactions as $transaction ){
            if($transaction->jenis == 'masuk'){
                $saldo += $transaction->jumlah;
            } else {
                $saldo -= $transaction->jumlah;
            }
        }

        return $saldo;
    }
}
