@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Edit Obat') }}</span>
                        <span><a href="{{ route('obat') }}" class="btn btn-sm btn-danger">X</a></span>
                    </div>

                    <div class="card-body">
                        <form enctype="multipart/form-data" method="POST" action="{{ route('update.obat') }}">
                            @csrf
                            <input type="hidden" name="obat_id" value="{{ $obat->id }}">

                            <div class="row mb-3">
                                <label for="kategori"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Pilih Kategori Obat') }}</label>

                                <div class="col-md-6">
                                    <select id="kategori" class="form-control" name="kategori">
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}"
                                                {{ old('kategori', isset($obat) ? $obat->id_kategori : '') == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}</option>
                                        @endforeach
                                    </select>

                                    @error('kategori')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="image"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Gambar Obat') }}</label>

                                <div class="col-md-6">
                                    <input class="form-control" type="file" id="image" name="image">

                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama_obat"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Nama Obat') }}</label>

                                <div class="col-md-6">
                                    <input id="nama_obat" type="text"
                                        class="form-control @error('nama_obat') is-invalid @enderror" name="nama_obat"
                                        value="{{ old('nama_obat', isset($obat) ? $obat->nama_obat : '') }}" required>

                                    @error('nama_obat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="harga"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Harga Obat') }}</label>

                                <div class="col-md-6">
                                    <input id="harga" type="number"
                                        class="form-control @error('harga') is-invalid @enderror" name="harga"
                                        value="{{ old('harga', isset($obat) ? $obat->harga : '') }}" required>

                                    @error('harga')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="keterangan"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Keterangan Obat') }}</label>

                                <div class="col-md-6">
                                    <input id="keterangan" type="text"
                                        class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"
                                        value="{{ old('keterangan', isset($obat) ? $obat->keterangan : '') }}" required>

                                    @error('keterangan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="stok"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Stok Obat') }}</label>

                                <div class="col-md-6">
                                    <input id="stok" type="number"
                                        class="form-control @error('stok') is-invalid @enderror" name="stok"
                                        value="{{ old('stok', isset($obat) ? $obat->stok : '') }}" required>

                                    @error('stok')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="exp"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Exp Obat') }}</label>

                                <div class="col-md-6">
                                    <input id="exp" type="date"
                                        class="form-control @error('exp') is-invalid @enderror" name="exp"
                                        value="{{ old('exp', isset($obat) ? $obat->exp : '') }}" required
                                        min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">

                                    @error('exp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Edit Data Obat') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
