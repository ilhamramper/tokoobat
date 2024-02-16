@extends('layouts.app')

@section('content')
    <div class="container" style="margin-bottom: 4%">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ __('Riwayat Order') }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="transaksi" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">ID Penjualan</th>
                                    <th class="text-center">Total Bayar</th>
                                    <th class="text-center">Pembayaran</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center aksi-column">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksis as $transaksi)
                                    <tr>
                                        <td>ORD{{ $transaksi->id_penjualan }}</td>
                                        <td>Rp{{ number_format($transaksi->total_sum, 0, ',', '.') }}</td>
                                        <td>{{ $transaksi->pembayaran->nama_pembayaran }}</td>
                                        <td>{{ $transaksi->tanggal }}</td>
                                        @if ($transaksi->id_user == null)
                                            <td>
                                                <button class="btn btn-primary selesai"
                                                    data-transaksi-id="{{ $transaksi->id_penjualan }}"
                                                    onclick="handleSelesai(this)">Sudah Bayar</button>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route('cetak-struk', ['id_penjualan' => $transaksi->id_penjualan]) }}"
                                                    target="_blank" class="btn btn-success">Cetak Struk</a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#transaksi').DataTable();
        });

        function handleSelesai(button) {
            var confirmation = confirm('Apakah anda yakin pesanan sudah dibayar?');
            if (confirmation) {
                // Proceed with your logic for 'Pesanan Selesai'
                executeSelesai(button);
            }
        }

        function executeSelesai(button) {
            var transaksiId = button.dataset.transaksiId;

            // Execute your AJAX call for 'Pesanan Selesai'
            $.ajax({
                type: 'POST',
                url: '{{ route('selesai') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'transaksi_id': transaksiId
                },
                success: function(response) {
                    console.log(response);
                    alert('Berhasil');
                    location.reload();
                },
                error: function(error) {
                    console.error(error);
                    alert('Gagal');
                }
            });
        }
    </script>
@endsection
