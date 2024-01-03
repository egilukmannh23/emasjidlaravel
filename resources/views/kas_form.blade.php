@extends('layouts.app_adminkit')

@section('content')
    <div class="container">
        <h2>{{ isset($kas) ? 'Edit' : 'Tambah' }} Data Kas</h2>

        {!! Form::model(
            $kas, [
                'route' => isset($kas->id) ? ['kas.update', $kas->id] : 'kas.store',
                'method' => isset($kas->id) ? 'PUT' : 'POST',
                ]
        ) !!}



        <div class="form-group">
            {!! Form::label('tanggal', 'Tanggal') !!}
            {!! Form::date('tanggal', null, ['class' => 'form-control']) !!}
            <span class="text-danger">{{ $errors->first('tanggal') }}</span>
        </div>

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

        <div class="form-group">
    {!! Form::label('jenis', 'Jenis Transaksi') !!}
    <br>
    <label>{!! Form::radio('jenis', 'masuk', true) !!} Masuk</label>
    <label>{!! Form::radio('jenis', 'keluar') !!} Keluar</label>
    <span class="text-danger">{{ $errors->first('jenis') }}</span>
</div>


        <div class="form-group mb-3">
            {!! Form::label('jumlah', 'Jumlah') !!}
            {!! Form::text('jumlah', null, ['class' => 'form-control']) !!}
            <span class="text-danger">{{ $errors->first('jumlah') }}</span>
        </div>

        <div class="form-group">
            {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
            <a href="{{ route('kas.index') }}" class="btn btn-secondary">Batal</a>
        </div>

        {!! Form::close() !!}
    </div>
@endsection
