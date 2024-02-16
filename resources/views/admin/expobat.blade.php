@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ __('Data Obat Expired') }}</span>
                            <a href="{{ route('obat') }}" class="btn btn-danger">X</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="obat" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Gambar</th>
                                    <th class="text-center">Kategori Obat</th>
                                    <th class="text-center">Nama Obat</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Exp</th>
                                    <th class="text-center">
                                        Pilih Semua
                                        <span style="padding-left: 10px;">
                                            <input type="checkbox" id="checkAll">
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expObats as $index => $expObat)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if ($expObat->image)
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#imageModal{{ $expObat->id }}">
                                                    <img src="{{ asset('storage/' . $expObat->image) }}"
                                                        style="max-width: 100px;">
                                                </a>
                                                <!-- Modal Image Masakan -->
                                                <div class="modal fade" id="imageModal{{ $expObat->id }}" tabindex="-1"
                                                    aria-labelledby="imageModalLabel{{ $expObat->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="imageModalLabel{{ $expObat->id }}">Gambar
                                                                    {{ $expObat->nama_obat }}
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="{{ asset('storage/' . $expObat->image) }}"
                                                                    style="width: 100%;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                Gambar Tidak Ada
                                            @endif
                                        </td>
                                        <td>{{ $expObat->kategori->nama_kategori }}</td>
                                        <td>{{ $expObat->nama_obat }}</td>
                                        <td>Rp{{ number_format($expObat->harga, 0, ',', '.') }}</td>
                                        <td>{{ $expObat->keterangan }}</td>
                                        <td>{{ $expObat->stok }}</td>
                                        <td style="color: red;"><strong>{{ $expObat->exp }}</strong></td>
                                        <td>
                                            <input type="checkbox" class="obat-checkbox" name="obat_ids[]"
                                                value="{{ $expObat->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <input type="hidden" id="selectedObatIds" name="selectedObatIds" value="">
                        <button id="deleteButton" class="btn btn-secondary" disabled>Hapus Obat</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#obat').DataTable();

            $('#checkAll').change(function() {
                $('.obat-checkbox').prop('checked', $(this).prop('checked'));

                updateSelectedObatIds();
                updateButtonStates();
            });

            $('.obat-checkbox').change(function() {
                if (!$(this).prop('checked')) {
                    $('#checkAll').prop('checked', false);
                }

                var allChecked = $('.obat-checkbox:checked').length === $('.obat-checkbox').length;

                $('#checkAll').prop('checked', allChecked);

                updateSelectedObatIds();
                updateButtonStates();
            });

            function updateSelectedObatIds() {
                var selectedIds = $('.obat-checkbox:checked').map(function() {
                    return $(this).val();
                }).get().join(',');

                $('#selectedObatIds').val(selectedIds);
            }

            function updateButtonStates() {
                var selectedCount = $('.obat-checkbox:checked').length;
                var deleteButton = $('#deleteButton');

                if (selectedCount > 0) {
                    deleteButton.prop('disabled', false);
                    deleteButton.removeClass('btn-secondary').addClass('btn-danger');
                } else {
                    deleteButton.prop('disabled', true);
                    deleteButton.removeClass('btn-danger').addClass('btn-secondary');
                }
            }

            $('#deleteButton').click(function() {
                var selectedObatIds = $('#selectedObatIds').val();

                if (selectedObatIds) {
                    var selectedCount = selectedObatIds.split(',').length;

                    var confirmMessage = selectedCount > 1 ?
                        'Apakah anda yakin ingin menghapus ' + selectedCount + ' obat?' :
                        'Apakah anda yakin ingin menghapus obat?';

                    if (confirm(confirmMessage)) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('delete.obat') }}',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'obat_ids': selectedObatIds
                            },
                            success: function(response) {
                                console.log(response);
                                alert('Data obat berhasil dihapus');
                                location.reload();
                            },
                            error: function(error) {
                                console.error(error);
                                alert('Gagal menghapus data obat');
                            }
                        });
                    }
                } else {
                    alert('Pilih satu data obat untuk dihapus');
                }
            });
        });
    </script>
@endsection
