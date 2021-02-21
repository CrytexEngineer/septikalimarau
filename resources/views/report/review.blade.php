@extends('layouts.app', ['activePage' => $status, 'titlePage' => __('Manajemen Laporan Harian')])

@section('content')
    <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Laporan Harian Dalam Peninjauan</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">
                        @can('admin')
                        <button id="buttonApprove" name="buttonApprove">Arsip Masal</button>
                        <button id="buttonReject" name="buttonReject">Tolak Masal</button>
                        @endcan
                        <div class="table-responsive">
                            <table class="display  compact" id="table_task">
                                <thead class=" text-primary">
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
          var table = $("#table_task").DataTable({
          order: [],
          columnDefs: [{ width: "20%" }],
          fixedColumns: true,
          processing: true,
          serverSide: false,
          ajax: {
            url: "/report/json",
            data: function (d) {
              d.status_id = "2";
              console.log(d);
            },
          },
          select: {
            style: "multi",
          },
          columns: [
            { data: "id", name: "id" },
            { data: "unit_name", name: "unit_name" },
            { data: "task_name", name: "task_name" },
            { data: "created_at", name: "created_at" },
            { data: "updated_at", name: "updated_at" },
            { data: "action", name: "action" },
          ],
        });

        $(document).ready(function () {
          $("#id").on("change", function (e) {
            table.ajax.reload();
          });
        });

        $("#buttonApprove").click(function () {
          var data = table.rows({ selected: true }).data();
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
          var data = table.rows({ selected: true }).data();
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
                status_id: 1,
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


        </script>
    @endpush

@endsection

