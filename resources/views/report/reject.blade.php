@extends('layouts.app', ['activePage' => $status, 'titlePage' => __('Manajemen Laporan Harian')])
@section('content')
    <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title "> Laporan Ditolak</h4>
                        <p class="card-category"></p>
                        <div class="row">
                            @can('management')
                                <div class="col">
                                    <p class="card-category">Filter Unit</p>
                                    {{ Form::select('filter_unit_id',$unit,null,['class'=>'form-control','style'=>'background-color:white','placeholder'=>'Pilih Unit','id'=>'filter_unit_id'])}}
                                </div>
                            @endcan('management')
                            <div class="col">
                                <p class="card-category">Filter Tanggal</p>
                                {{ Form::select('filter_tanggal',$created_at,null,['class'=>'form-control','style'=>'background-color:white','id'=>'filter_tanggal'])}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <button id="buttonSubmit" name="buttonSubmit" class="btn btn-danger">Submit Masal</button>
                        <button id="buttonDelete" name="buttonDelete" class="btn btn-danger">Hapus Masal</button>
                        <div class="table-responsive">
                            <table class="display  compact" id="table_task">
                                <thead class=" text-primary">
                                <th>
                                    Detail
                                </th>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Unit
                                </th>
                                <th>
                                    Tugas Pelaporan
                                </th>
                                <th>
                                    Dibuat
                                </th>
                                <th>
                                    Diperbaharui
                                </th>
                                <th>
                                    Aksi
                                </th>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    @push('js')

        <script>


            var table = $('#table_task').DataTable({
                columnDefs: [
                    {width: '20%'}
                ],
                "order": [],
                fixedColumns: true,
                processing: true,
                serverSide: false,
                ajax: {
                    "url": '/report/json',
                    "data": function (d) {
                        d.status_id = "6";
                        d.unit_id = $('#filter_unit_id').val();
                        d.filter_tanggal = $('#filter_tanggal option:selected').text();
                    }
                },
                select: {
                    style: 'multi'
                },

                columns: [
                    {
                        "class": "details-control",
                        "orderable": false,
                        "data": null,
                        "defaultContent": ""
                    },
                    {data: 'id', name: 'id'},
                    {data: 'unit_name', name: 'unit_name'},
                    {data: 'task_name', name: 'task_name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action'}

                ],
            });

            $('#buttonSubmit').click(function () {
                var data = table.rows({selected: true}).data()
                if (confirm('Apakah Anda Yakin Ingin Submit Masal?')) {
                    var newarray = [];
                    for (var i = 0; i < data.length; i++) {
                        newarray.push(data[i]);
                    }
                    var sData = newarray.join();


                    $.ajax({
                        url: "/report/mass_update",
                        type: "PATCH",
                        data: {
                            _token: $("#csrf").val(),
                            reports: newarray,
                            status_id: 2

                        },
                        cache: false,
                        success: function (dataResult) {

                            var dataResult = JSON.parse(JSON.stringify(dataResult));

                            if (dataResult.statusCode == 200) {
                                table.ajax.reload();
                                alert(dataResult.messege);
                            } else if (dataResult.statusCode == 201) {
                                alert(dataResult.messege);
                            }

                        },
                        error: function (err, errCode, errMessage) {
                            alert(errMessage)
                            alert("Tidak Ada Data Dipilih");
                        }
                    });
                }
            });

            $('#buttonDelete').click(function () {
                var data = table.rows({selected: true}).data()
                if (confirm('Apakah Anda Yakin Ingin Hapus Masal?')) {
                    var newarray = [];
                    for (var i = 0; i < data.length; i++) {
                        newarray.push(data[i]);
                    }
                    var sData = newarray.join();


                    $.ajax({
                        url: "/report/mass_delete",
                        type: "DELETE",
                        data: {
                            _token: $("#csrf").val(),
                            reports: newarray,

                        },
                        cache: false,
                        success: function (dataResult) {

                            var dataResult = JSON.parse(JSON.stringify(dataResult));

                            if (dataResult.statusCode == 200) {
                                table.ajax.reload();
                                alert(dataResult.messege);
                            } else if (dataResult.statusCode == 201) {
                                alert(dataResult.messege);
                            }

                        },
                        error: function (err, errCode, errMessage) {
                            console.log("S");
                            alert("Tidak Ada Data Dipilih");
                        }
                    });
                }
            });


            $(document).ready(function () {
                // stores the open rows (detailed view)
                var openRows = new Array();
                $("#filter_unit_id").val($("#filter_unit_id option:last").val());

                /**
                 * Close all previously opened rows
                 *
                 * @param {object} table which is to be modified
                 * @param {object} selectedRow needs to determine,
                 * which other rows can be closed
                 * @returns {null}
                 */
                function closeOpenedRows(table, selectedRow) {
                    $.each(openRows, function (index, openRow) {
                        // not the selected row!
                        if ($.data(selectedRow) !== $.data(openRow)) {
                            var rowToCollapse = table.row(openRow);
                            rowToCollapse.child.hide();
                            openRow.removeClass('details');
                            // replace icon to expand
                            $(openRow).find('td.details-control').html('<span class="glyphicon glyphicon-plus"></span>');
                            // remove from list
                            var index = $.inArray(selectedRow, openRows);
                            openRows.splice(index, 1);
                        }
                    });
                }

                // Add event listener for opening and closing details
                $('#table_task tbody').on('click', 'tr td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);
                    var idx = $.inArray(tr.attr('id'), openRows);

                    if (row.child.isShown()) {
                        // This row is already open - change icon
                        $(this).html('<span class="glyphicon glyphicon-plus"></span>');
                        // close it
                        row.child.hide();
                        tr.removeClass('details');
                    } else {
                        // close all previously opened rows
                        closeOpenedRows(table, tr);

                        // This row should be opened - change icon
                        $(this).html('<span class="glyphicon glyphicon-minus"></span>');
                        // and open this row
                        $.ajax({
                            url: "/record/json",
                            type: "GET",
                            data: {
                                id: row.data().id
                            },
                            success: function (data) {
                                row.child(format(row.data(), data)).show();
                            }
                        });

                        tr.addClass('details');

                        // store current selection
                        openRows.push(tr);
                    }
                });

                table.on('draw', function () {
                    $.each(openRows, function (i, id) {
                        $('#' + id + ' td.details-control').trigger('click');
                    });
                });

                $('#unit_id').on('change', function (e) {
                    table.ajax.reload();


                    var d = e.target.value;
                    $.ajax({
                        url: "{{ route('filter.taskQuery') }}",
                        type: "GET",
                        data: {
                            unit_id: d
                        },
                        success: function (data) {
                            $('#task_id').empty();
                            $.each(data.task, function (index, subcategory) {
                                $('#task_id').append('<option value="' + subcategory.id + '">' + subcategory.task_name + '</option>');
                            })
                        }
                    })
                });

            })
            ;
            $('#filter_unit_id').on('change', function (e) {
                table.ajax.reload(function (result) {
                });
            });

            $('#filter_tanggal').on('change', function (e) {
                table.ajax.reload(function (result) {
                });
            });

            function format(d, data) {



                var petugas_pagi = (d.petugas_pagi) ? d.petugas_pagi : ' Tidak Diisi'
                var petugas_siang = (d.petugas_siang) ? d.petugas_siang : ' Tidak Diisi'
                var keterangan = (d.keterangan) ? d.keterangan : ' Tidak Diisi'
                var jumlahGambar = d.jumlahGambar


                var layoutHeader = '<table id="table_inner_header" class="display" style="width: 100%">' +
                    '  <thead>' +
                    '    <tr>' +
                    '      <th>Petugas Pagi</th>' +
                    '      <th>Petugas Siang</th>' +
                    '      <th>Jumlah Gambar</th>' +
                    '      <th>Keterangan</th>' +
                    '    </tr>' +
                    '  </thead>' +
                    '  <tbody>' +
                    '    <tr>' +
                    '      <td>' + petugas_pagi + '</td>' +
                    '      <td>' + petugas_siang + '</td>' +
                    '      <td>' + jumlahGambar + '</td>' +
                    '      <td>' + keterangan + '</td>' +
                    '    </tr>' +
                    '    <tr></tr>' +
                    '  </tbody>' +
                    '</table>' +
                    '</br>';

                var layoutItem = '<table id="table_inner_item_header" class="table-striped" style="width: 100%">' +
                    '  <thead>' +
                    '    <tr>' +
                    '      <th>No</th>' +
                    '      <th>Uraian</th>' +
                    '      <th>Kondisi Pagi</th>' +
                    '      <th>Kondisi Siang</th>' +
                    '    </tr>' +
                    '  </thead>';
                for (var a = 0; a < data.data.length; a++) {
                    kondisiPagi = "Belum Di Cek";
                    if (data.data[a].kondisi_pagi == 1) {
                        kondisiPagi = "Baik"
                    }
                    if (data.data[a].kondisi_pagi == 2) {
                        kondisiPagi = "Kurang Baik"
                    }
                    if (data.data[a].kondisi_pagi == 3) {
                        kondisiPagi = "Tidak Baik"
                    }

                    kondisiSiang = "Belum Di Cek";


                    if (data.data[a].kondisi_siang == 1) {
                        kondisiSiang = "Baik"
                    }
                    if (data.data[a].kondisi_siang == 2) {
                        kondisiSiang = "Kurang Baik"
                    }
                    if (data.data[a].kondisi_siang == 3) {
                        kondisiSiang = "Tidak Baik"
                    }


                    layoutItem = layoutItem + '    <tr>' +
                        '  <tbody>' +
                        '      <td>' + a + '</td>' +
                        '      <td>' + data.data[a].item_name + '</td>' +
                        '      <td>' + kondisiPagi + '</td>' +
                        '      <td>' + kondisiSiang + '</td>' +
                        '    </tr>'

                }


                layoutFooter = '  </tbody>' +
                    '</table>';
                return layoutHeader + layoutItem + layoutFooter;
            }


        </script>
    @endpush

@endsection

