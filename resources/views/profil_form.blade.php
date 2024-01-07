@extends('layouts.app_adminkit')

@section('content')
    <div class="container">
        <h1>Profil Masjid {{ auth()->user()->masjid->nama }}</h1>

        {!! Form::model(
            $profil, [
                'route' => isset($profil->id) ? ['profil.update', $profil->id] : 'profil.store',
                'method' => isset($profil->id) ? 'PUT' : 'POST',
                ]
        ) !!}





        <div class="form-group">
            {!! Form::label('kategori', 'Kategori') !!}
            {!! Form::text('kategori', null, ['class' => 'form-control']) !!}
            <span class="text-danger">{{ $errors->first('kategori') }}</span>
        </div>

        <div class="form-group">
            {!! Form::label('keterangan', 'Keterangan') !!}
            {!! Form::text('keterangan', null, ['class' => 'form-control']) !!}
            <span class="text-danger">{{ $errors->first('keterangan') }}</span>
        </div>

                <div class="form-check">
                    {!! Form::radio('jenis', 'masuk', null, ['id' => 'jenis_masuk', 'class' => 'form-check-input' ]) !!}
                    {!! Form::label('jenis_masuk', 'pemasukan', ['class' => 'form-check-label']) !!}
                    <span class="text-danger">{{ $errors->first('jenis') }}</span>
                </div>
                <div class="form-check">
                    {!! Form::radio('jenis', 'keluar', null, ['id' => 'jenis_keluar', 'class' => 'form-check-input' ]) !!}
                    {!! Form::label('jenis_keluar', 'pengeluaran', ['class' => 'form-check-label']) !!}
                    <span class="text-danger">{{ $errors->first('jenis') }}</span>
                </div>


        <div class="form-group mb-3">
            {!! Form::label('jumlah', 'Jumlah Transaksi') !!}
            {!! Form::text('jumlah', null, ['class' => 'form-control rupiah']) !!}
            <span class="text-danger">{{ $errors->first('jumlah') }}</span>
        </div>

        <div class="form-group">
            {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('kas.index') }}" class="btn btn-secondary">Batal</a>
        </div>

        {!! Form::close() !!}
    </div>
@endsection
