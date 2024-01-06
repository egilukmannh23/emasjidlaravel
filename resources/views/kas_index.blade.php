@extends('layouts.app_adminkit')

@section('content')



        <h2>Data Kas</h2>
        <a href="{{ route('kas.create') }}" class="btn btn-success mb-2 ">Tambah Data</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

    <div class="card">
        <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>kategori</th>
                    <th>Keterangan</th>
                    <th>Pemasukan</th>
                    <th>Pengeluaran</th>
                    <th>Saldo Akhir</th>
                    <th>Di input oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kas as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->tanggal->translatedFormat('d-m-Y') }}</td>
                        <td>{{ $item->kategori ?? 'umum'}}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            {{ $item->jenis == 'masuk' ? formatRupiah($item->jumlah) : '-' }}
                        </td>
                        <td>
                            {{ $item->jenis == 'keluar' ? formatRupiah($item->jumlah) : '-' }}
                        </td>
                        <td>{{ formatRupiah($item->saldo_akhir, true) }}</td>
                        <td>{{ $item->createdBy->name }}</td>
                        <td>
                            <a href="{{ route('kas.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            {!! Form::open([
                                'method'=>'DELETE' ,
                                'route'=>['kas.destroy',$item->id],
                                'style'=>'display:inline'   
                                ]) !!}
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Data kas tidak tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $kas->links() }}
        </div>
    </div>

@endsection
