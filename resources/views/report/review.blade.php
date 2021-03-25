@extends('layouts.app', ['activePage' => $status, 'titlePage' => __('Manajemen Laporan Harian')])
@section('content')
    <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                @include('validation_error')
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Laporan Harian Dalam Peninjauan</h4>
                        <p class="card-category"></p>

                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <div class="row">

                                @can('management')
                                    <div class="col">
                                        <p class="card-category">Filter Unit</p>
                                        {{ Form::select('filter_unit_id',$unit,null,['class'=>'form-control','placeholder'=>'Pilih Unit','id'=>'filter_unit_id'])}}
                                    </div>
                                @endcan('management')
                                <div class="col">
                                    <p class="card-category">Filter Tanggal</p>
                                    {{ Form::select('filter_tanggal',$created_at,null,['class'=>'form-control','id'=>'filter_tanggal'])}}
                                </div>
                            </div>
                            <br>
                            <br>
                            @can('admin')
                                <div class="row">
                                    <div class="col">
                                        <div class="d-grid gap-2 d-md-block">
                                            <button id="buttonApprove" name="buttonApprove" class="btn btn-primary">
                                                Arsip
                                                Masal
                                            </button>
                                            <button id="buttonReject" name="buttonReject"
                                                    class="btn btn-outline-danger">
                                                Tolak
                                                Masal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endcan

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
                                    @can('admin')
                                </th>
                                <th>
                                </th>
                                <th>
                                </th>
                                @endcan
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
            var table = $("#table_task").DataTable({
                order: [],
                columnDefs: [{width: "20%"}],
                fixedColumns: true,
                processing: true,
                serverSide: false,
                ajax: {
                    url: "/report/json",
                    data: function (d) {
                        d.status_id = "2";
                        d.unit_id = $('#filter_unit_id').val();
                        d.filter_tanggal = $('#filter_tanggal option:selected').text();
                    },
                },
                select: {
                    style: "multi",
                },
                columns: [
                    {
                        "class": "details-control",
                        "orderable": false,
                        "data": null,
                        "defaultContent": ""
                    },
                    {data: "id", name: "id"},
                    {data: "unit_name", name: "unit_name"},
                    {data: "task_name", name: "task_name"},
                    {data: "created_at", name: "created_at"},
                    {data: "updated_at", name: "updated_at"},
                    {
                        data: 'action',
                        className: "dt-center editor-edit",
                        orderable: false
                    },
                        @can('admin')
                    {
                        data: null,
                        className: "dt-center editor-approve",
                        defaultContent: "<button type='submit'class='btn btn-primary btn-block'><i class='fas fa-check'></i></button>",
                        orderable: false
                    },
                    {
                        data: null,
                        className: "dt-center editor-reject",
                        defaultContent: "<button type='submit'class='btn btn-outline-danger btn-block'><i class='fas fa-times-circle'></i></button>",
                        orderable: false
                    },
                    @endcan
                ],
            });


            $('#table_task').on('click', 'td.editor-approve', function (e) {
                e.preventDefault();
                var data = table.row($(this).closest('tr')).data()
                if (confirm('Apakah Anda Yakin Ingin Arsip?')) {
                    var newarray = [];
                    newarray.push(data);
                    $.ajax({
                        url: "/report/mass_update",
                        type: "PATCH",
                        data: {
                            _token: $("#csrf").val(),
                            reports: newarray,
                            status_id: 5

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

                        }
                    });
                }
            });

            $('#table_task').on('click', 'td.editor-reject', function (e) {
                e.preventDefault();
                var data = table.row($(this).closest('tr')).data()
                if (confirm('Apakah Anda Yakin Ingin Tolak ?')) {
                    var newarray = [];
                    newarray.push(data);
                }

                $.ajax({
                    url: "/report/mass_update",
                    type: "PATCH",
                    data: {
                        _token: $("#csrf").val(),
                        reports: newarray,
                        status_id: 6

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

                    }
                });
            });

            $("#buttonApprove").click(function () {
                var data = table.rows({selected: true}).data();
                if (confirm("Apakah Anda Yakin Ingin Arsip Masal?")) {
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
                            status_id: 5,
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
                        },
                    });
                }
            });

            $("#buttonReject").click(function () {
                var data = table.rows({selected: true}).data();
                if (confirm("Apakah Anda Yakin Ingin Tolak Masal?")) {
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
                            status_id: 6,
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
                            alert("Tidak Ada Data Dipilih");
                        },
                    });
                }
            });

            $(document).ready(function () {
                var openRows = [];
                $("#filter_unit_id").val($("#filter_unit_id option:last").val());

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

                $('#table_task tbody').on('click', 'tr td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);

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


                // On each draw, loop over the `detailRows` array and show any child rows
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

            });

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
                console.log(layoutItem)
                return layoutHeader + layoutItem + layoutFooter;
            }


        </script>
    @endpush

@endsection

