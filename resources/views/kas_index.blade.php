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
                    <th>Masjid ID</th>
                    <th>Tanggal</th>
                    <th>kategori</th>
                    <th>Keterangan</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Saldo Akhir</th>
                    <th>Created By</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kas as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->masjid_id }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->jenis }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->saldo_akhir }}</td>
                        <td>{{ $item->created_by }}</td>
                        <td>
                            <a href="{{ route('kas.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('kas.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
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
