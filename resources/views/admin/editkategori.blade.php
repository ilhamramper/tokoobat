@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Edit Kategori') }}</span>
                        <span><a href="{{ route('home') }}" class="btn btn-sm btn-danger">X</a></span>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('update.kategori') }}">
                            @csrf
                            <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">

                            <div class="row mb-3">
                                <label for="nama_kategori"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Nama Kategori') }}</label>

                                <div class="col-md-6">
                                    <input id="nama_kategori" type="text"
                                        class="form-control @error('nama_kategori') is-invalid @enderror"
                                        name="nama_kategori"
                                        value="{{ old('nama_kategori', isset($kategori) ? $kategori->nama_kategori : '') }}"
                                        required autofocus>

                                    @error('nama_kategori')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="status_obat"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Status Obat') }}</label>

                                <div class="col-md-6">
                                    <select id="status_obat" class="form-control" name="status_obat">
                                        <option value="aman"
                                            {{ old('status_obat', isset($kategori) ? $kategori->status_obat : '') == 'aman' ? 'selected' : '' }}>
                                            Aman Dikonsumsi</option>
                                        <option value="tidakaman"
                                            {{ old('status_obat', isset($kategori) ? $kategori->status_obat : '') == 'tidakaman' ? 'selected' : '' }}>
                                            Tidak Aman Dikonsumsi</option>
                                    </select>

                                    @error('status_obat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary"
                                        onclick="return confirm('Apakah anda yakin ingin mengedit kategori obat?');">
                                        {{ __('Edit Kategori') }}
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
