@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('Buat Kategori Obat') }}</span>
                        <span><a href="{{ route('home') }}" class="btn btn-sm btn-danger">X</a></span>
                    </div>

                    <div class="card-body">
                        <form enctype="multipart/form-data" method="POST" action="{{ route('store.kategori') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="nama_kategori"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Kategori Obat') }}</label>

                                <div class="col-md-6">
                                    <input id="nama_kategori" type="text"
                                        class="form-control @error('nama_kategori') is-invalid @enderror"
                                        name="nama_kategori" value="{{ old('nama_kategori') }}" required>

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
                                        <option value="aman">Aman Dikonsumsi</option>
                                        <option value="tidakaman">Tidak Untuk Dikonsumsi</option>
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
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Buat Kategori') }}
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
