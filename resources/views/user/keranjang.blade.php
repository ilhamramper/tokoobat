@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                {{ __('Keranjang') }}
                                <a href="{{ route('riwayat.order') }}" class="btn btn-warning ms-2">Riwayat Pesanan</a>
                            </span>
                            <a href="{{ route('order') }}" class="btn btn-danger">X</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="keranjang" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Obat</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keranjangs as $index => $keranjang)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $keranjang->obat->nama_obat }}</td>
                                        <td>{{ $keranjang->jumlah }}</td>
                                        <td>Rp{{ number_format($keranjang->obat->harga, 0, ',', '.') }}</td>
                                        <td>Rp{{ number_format($keranjang->total, 0, ',', '.') }}</td>
                                        <td>
                                            <button class="btn btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $keranjang->id }}">Edit</button>
                                            <form method="POST"
                                                action="{{ route('delete.order', ['id' => $keranjang->id]) }}"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus item keranjang ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal{{ $keranjang->id }}" tabindex="-1"
                                        aria-labelledby="editModalLabel{{ $keranjang->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $keranjang->id }}">Edit
                                                        Keranjang</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST"
                                                        action="{{ route('update.order', ['id' => $keranjang->id]) }}">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="form-group">
                                                            <label for="qty">Jumlah Obat</label>
                                                            <input name="qty" type="number" class="form-control"
                                                                id="qty{{ $keranjang->id }}"
                                                                value="{{ old('qty', $keranjang->jumlah) }}"
                                                                max="{{ $keranjang->obat->stok }}">
                                                        </div>

                                                        <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>

                        @if (count($keranjangs) > 0)
                            <div class="mt-3">
                                <span><strong>Total Harga Bayar : <span id="totalHargaBayar"
                                            style="color: green">0</span></strong></span>
                            </div>
                            <div class="mb-1 row">
                                <label for="role"
                                    class="col-auto col-form-label"><strong>{{ __('Pilih Metode Pembayaran :') }}</strong></label>

                                <div class="col-auto">
                                    <select id="id_pembayaran" class="form-control" name="id_pembayaran">
                                        @foreach ($pembayarans as $pembayaran)
                                            <option value="{{ $pembayaran->id }}">{{ $pembayaran->nama_pembayaran }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('id_pembayaran')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button id="bayarPesanan" class="btn btn-primary">Bayar Pesanan</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#keranjang').DataTable();

            function calculateTotalHargaBayar() {
                var totalHargaBayar = 0;
                $('#keranjang tbody tr').each(function(index, row) {
                    var jumlah = parseInt($(row).find('td:eq(2)')
                        .text());
                    var harga = parseFloat($(row).find('td:eq(3)').text().replace('Rp', '').replace('.', '')
                        .replace(',', '.'));
                    totalHargaBayar += jumlah * harga;
                });

                $('#totalHargaBayar').text(formatCurrency(totalHargaBayar));
            }

            function formatCurrency(value) {
                return 'Rp' + value.toFixed(0).replace(/\d(?=(\d{3})+$)/g, '$&.');
            }

            calculateTotalHargaBayar();

            $('#bayarPesanan').on('click', function() {
                var idPembayaran = $('#id_pembayaran').val();

                $.ajax({
                    url: '{{ route('bayar.pesanan') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id_pembayaran: idPembayaran,
                    },
                    success: function(response) {
                        if (response.success) {
                            window.loca
                            alert('Pesanan berhasil dibayar!');
                            window.location.href = '{{ route('order') }}';
                        } else {
                            alert('Gagal membayar pesanan!');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
@endsection
