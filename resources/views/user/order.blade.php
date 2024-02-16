@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="row col-md-10">
                                <div class="col-sm-auto">
                                    <label for="kategori" class="col-form-label">{{ __('Pilih Kategori Obat :') }}</label>
                                </div>
                                <div class="col-sm-auto">
                                    <select id="kategori" class="form-control" name="kategori">
                                        <option value="All">Tampilkan Semua</option>
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-auto">
                                    <label for="search" class="col-form-label">{{ __('Cari Obat :') }}</label>
                                </div>
                                <div class="col-sm-auto">
                                    <input type="text" id="search" class="form-control" placeholder="Cari Obat">
                                </div>
                            </div>
                            <div class="col-sm-auto mt-2">
                                <a href="{{ route('keranjang') }}" class="btn position-relative">
                                    <button class="btn btn-primary">Keranjang</button>
                                    <span
                                        class="position-absolute top-0 start-90 translate-middle badge rounded-pill bg-danger">
                                        {{ $keranjang }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-md-3 g-3" id="obat-container">
                            @foreach ($obats as $obat)
                                <div class="col" data-kategori="{{ $obat->id_kategori }}">
                                    <div class="card shadow-sm">
                                        <img src="{{ asset('storage/' . $obat->image) }}"
                                            style="max-height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $obat->nama_obat }}</h5>
                                            <p class="card-text">
                                                Kategori Obat : <strong>{{ $obat->kategori->nama_kategori }} 
                                                    @if ($obat->kategori->status_obat == 'aman')
                                                        <span style="color: green">(Aman Dikonsumsi)</span>
                                                    @elseif ($obat->kategori->status_obat == 'tidakaman')
                                                        <span style="color: red">(Tidak Untuk Dikonsumsi)</span>
                                                    @endif
                                                    <br></strong>
                                                Harga : Rp{{ number_format($obat->harga, 0, ',', '.') }}<br>
                                                Stok : {{ $obat->stok }}<br>
                                                Keterangan : {{ $obat->keterangan }}
                                            </p>
                                            @if ($obat->stok != 0)
                                                <button class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#pesanModal{{ $obat->id }}">
                                                    Beli
                                                </button>
                                            @else
                                                <button class="btn btn-secondary" disabled>Pesan</button>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Modal Pesan Obat -->
                                    <div class="modal fade" id="pesanModal{{ $obat->id }}" tabindex="-1"
                                        aria-labelledby="pesanModalLabel{{ $obat->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="pesanModalLabel{{ $obat->id }}">Pesan
                                                        {{ $obat->nama_obat }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form enctype="multipart/form-data" method="POST"
                                                        action="{{ route('store.order') }}">
                                                        @csrf

                                                        <div class="form-group">
                                                            <label for="qty">Jumlah Obat</label>
                                                            <input name="qty" type="number"
                                                                class="form-control qty-input" id="qty"
                                                                placeholder="Masukkan jumlah obat yang ingin anda beli"
                                                                max="{{ $obat->stok }}">
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label for="totalHarga">Total Harga</label>
                                                            <input type="text" class="form-control totalHarga"
                                                                id="totalHarga" readonly>
                                                            <input name="totalharga" type="hidden"
                                                                class="form-control totalHarga2" id="totalHarga2" readonly>
                                                        </div>

                                                        <input name="idobat" type="hidden" value="{{ $obat->id }}">
                                                        <button type="submit" class="btn btn-primary mt-3">Pesan</button>
                                                    </form>
                                                    <input class="hargaobat" type="hidden" value="{{ $obat->harga }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div id="no-match-message" class="col" style="display: none;">
                                <p><strong>Tidak Ada Obat Yang Cocok</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#kategori').on('change', function() {
                filterMenu();
            });

            $('#search').on('input', function() {
                filterMenu();
            });

            $('.qty-input').on('input', function() {
                updateTotalHarga($(this));
            });

            function filterMenu() {
                var selectedValue = $('#kategori').val();
                var searchValue = $('#search').val().toLowerCase();
                var matchedElements;

                if (selectedValue === 'All') {
                    matchedElements = $('#obat-container .col');
                } else {
                    matchedElements = $('#obat-container .col[data-kategori="' + selectedValue + '"]');
                }

                if (searchValue) {
                    matchedElements = matchedElements.filter(function() {
                        return $(this).find('.card-title').text().toLowerCase().includes(searchValue);
                    });
                }

                if (selectedValue === 'All' && searchValue === '' && matchedElements.length === 0) {
                    $('#no-match-message').show();
                } else {
                    $('#no-match-message').hide();
                }

                $('#obat-container .col').hide();
                matchedElements.show();

                if (matchedElements.length === 0) {
                    $('#no-match-message').show();
                } else {
                    $('#no-match-message').hide();
                }
            }

            function updateTotalHarga(input) {
                var qty = input.val();
                var harga = input.closest('.modal').find('.hargaobat').val();
                var totalHarga = qty * harga;

                var formattedTotalHarga = 'Rp' + totalHarga.toFixed(0).replace(/\d(?=(\d{3})+$)/g, '$&.');

                input.closest('.modal').find('.totalHarga').val(formattedTotalHarga);
                input.closest('.modal').find('.totalHarga2').val(totalHarga);
            }
        });
    </script>
@endsection
