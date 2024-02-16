@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                {{ __('Data Obat') }}
                                <a href="{{ route('exp.obat') }}" class="btn btn-warning ms-2">Data Obat Expired</a>
                            </span>
                            <a href="{{ route('create.obat') }}" class="btn btn-success">+ Buat Obat</a>
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
                                @foreach ($obats as $index => $obat)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if ($obat->image)
                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#imageModal{{ $obat->id }}">
                                                    <img src="{{ asset('storage/' . $obat->image) }}"
                                                        style="max-width: 100px;">
                                                </a>
                                                <!-- Modal Image Masakan -->
                                                <div class="modal fade" id="imageModal{{ $obat->id }}" tabindex="-1"
                                                    aria-labelledby="imageModalLabel{{ $obat->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="imageModalLabel{{ $obat->id }}">Gambar
                                                                    {{ $obat->nama_obat }}
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="{{ asset('storage/' . $obat->image) }}"
                                                                    style="width: 100%;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                Gambar Tidak Ada
                                            @endif
                                        </td>
                                        <td>{{ $obat->kategori->nama_kategori }}</td>
                                        <td>{{ $obat->nama_obat }}</td>
                                        <td>Rp{{ number_format($obat->harga, 0, ',', '.') }}</td>
                                        <td>{{ $obat->keterangan }}</td>
                                        <td>{{ $obat->stok }}</td>
                                        <td>{{ $obat->exp }}</td>
                                        <td>
                                            <input type="checkbox" class="obat-checkbox" name="obat_ids[]"
                                                value="{{ $obat->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <input type="hidden" id="selectedObatIds" name="selectedObatIds" value="">
                        <button id="deleteButton" class="btn btn-secondary" disabled>Hapus Obat</button>
                        <button id="editButton" class="btn btn-secondary" disabled>Edit Obat</button>
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
                var editButton = $('#editButton');

                if (selectedCount === 1) {
                    editButton.prop('disabled', false);
                    editButton.removeClass('btn-secondary').addClass('btn-warning');
                } else {
                    editButton.prop('disabled', true);
                    editButton.removeClass('btn-warning').addClass('btn-secondary');
                }

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

            $('#editButton').click(function() {
                var selectedObatIds = $('#selectedObatIds').val();

                if (selectedObatIds) {
                    var selectedCount = selectedObatIds.split(',').length;

                    if (selectedCount === 1) {
                        var editUrl = '{{ route('edit.obat', ':id') }}';
                        editUrl = editUrl.replace(':id', selectedObatIds);
                        window.location.href = editUrl;
                    } else {
                        alert('Pilih hanya satu data obat untuk diedit');
                    }
                } else {
                    alert('Pilih data obat yang mau diedit');
                }
            });
        });
    </script>
@endsection
