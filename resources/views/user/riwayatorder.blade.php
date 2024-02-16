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
                        <table id="riwayatorder" class="table table-bordered table-striped text-center">
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
                                @foreach ($riwayatOrders as $riwayatOrder)
                                    <tr>
                                        <td>ORD{{ $riwayatOrder->id_penjualan }}</td>
                                        <td>Rp{{ number_format($riwayatOrder->total_sum, 0, ',', '.') }}</td>
                                        <td>{{ $riwayatOrder->pembayaran->nama_pembayaran }}</td>
                                        <td>{{ $riwayatOrder->tanggal }}</td>
                                        <td>
                                            @if ($riwayatOrder->id_user == null)
                                                <button class="btn btn-success" disabled>Cetak Struk</button>
                                            @else
                                                <a href="{{ route('cetak-struk', ['id_penjualan' => $riwayatOrder->id_penjualan]) }}"
                                                    target="_blank" class="btn btn-success">Cetak Struk</a>
                                            @endif
                                        </td>
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
            $('#riwayatorder').DataTable();
        });
    </script>
@endsection
