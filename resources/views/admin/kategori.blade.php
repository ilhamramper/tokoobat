@extends('layouts.app')

@section('content')
    <div class="container" style="margin-bottom: 4%">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ __('Data Kategori Obat') }}</span>
                            <a href="{{ route('create.kategori') }}" class="btn btn-success">+ Buat Kategori</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="kategori" class="table table-bordered table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Kategori</th>
                                    <th class="text-center">Status Obat</th>
                                    <th class="text-center">
                                        Pilih Semua
                                        <span style="padding-left: 10px;">
                                            <input type="checkbox" id="checkAll">
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategoris as $index => $kategori)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $kategori->nama_kategori }}</td>
                                        <td>
                                            @if ($kategori->status_obat == 'aman')
                                                Aman Dikonsumsi
                                            @elseif ($kategori->status_obat == 'tidakaman')
                                                Tidak Untuk Dikonsumsi
                                            @endif
                                        </td>
                                        <td>
                                            <input type="checkbox" class="kategori-checkbox" name="kategori_ids[]"
                                                value="{{ $kategori->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <input type="hidden" id="selectedKategoriIds" name="selectedKategoriIds" value="">
                        <button id="deleteButton" class="btn btn-secondary" disabled>Hapus Data</button>
                        <button id="editButton" class="btn btn-secondary" disabled>Edit Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#kategori').DataTable();

            $('#checkAll').change(function() {
                $('.kategori-checkbox').prop('checked', $(this).prop('checked'));

                updateSelectedKategoriIds();
                updateButtonStates();
            });

            $('.kategori-checkbox').change(function() {
                if (!$(this).prop('checked')) {
                    $('#checkAll').prop('checked', false);
                }

                var allChecked = $('.kategori-checkbox:checked').length === $('.kategori-checkbox').length;

                $('#checkAll').prop('checked', allChecked);

                updateSelectedKategoriIds();
                updateButtonStates();
            });

            function updateSelectedKategoriIds() {
                var selectedIds = $('.kategori-checkbox:checked').map(function() {
                    return $(this).val();
                }).get().join(',');

                $('#selectedKategoriIds').val(selectedIds);
            }

            function updateButtonStates() {
                var selectedCount = $('.kategori-checkbox:checked').length;
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
                var selectedKategoriIds = $('#selectedKategoriIds').val();

                if (selectedKategoriIds) {
                    var selectedCount = selectedKategoriIds.split(',').length;

                    var confirmMessage = selectedCount > 1 ?
                        'Apakah anda yakin ingin menghapus ' + selectedCount + ' kategori?' :
                        'Apakah anda yakin ingin menghapus kategori?';

                    if (confirm(confirmMessage)) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('delete.kategori') }}',
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'kategori_ids': selectedKategoriIds
                            },
                            success: function(response) {
                                console.log(response);
                                alert('Kategori berhasil dihapus');
                                location.reload();
                            },
                            error: function(error) {
                                console.error(error);
                                alert('Error menghapus kategori');
                            }
                        });
                    }
                } else {
                    alert('Pilih salah satu kategori untuk dihapus');
                }
            });

            $('#editButton').click(function() {
                var selectedKategoriIds = $('#selectedKategoriIds').val();

                if (selectedKategoriIds) {
                    var selectedCount = selectedKategoriIds.split(',').length;

                    if (selectedCount === 1) {
                        var editUrl = '{{ route('edit.kategori', ':id') }}';
                        editUrl = editUrl.replace(':id', selectedKategoriIds);
                        window.location.href = editUrl;
                    } else {
                        alert('Piih hanya satu kategori untuk diedit');
                    }
                } else {
                    alert('Pilih salah satu kategori untuk diedit');
                }
            });
        });
    </script>
@endsection
