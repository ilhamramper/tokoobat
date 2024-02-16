@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($struks)
            <h1 class="text-center">Struk Pembayaran</h1>

            <p class="mt-3">ID Penjualan : ORD{{ $struks->id_penjualan }}</p>
            <p>Nama Petugas : {{ $struks->user->nama_user }}</p>
            <p>Nama Pelanggan : {{ $struks->pelanggan->nama_pelanggan }}</p>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualans as $penjualan)
                        <tr>
                            <td>{{ $penjualan->obat->nama_obat }}</td>
                            <td>{{ $penjualan->jumlah }}</td>
                            <td>Rp{{ number_format($penjualan->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Total Pembayaran: <span style="color: green">Rp{{ number_format($penjualans->sum('total'), 0, ',', '.') }}</span></p>
        @else
            <p class="text-center">Penjualan tidak ditemukan</p>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
@endsection
