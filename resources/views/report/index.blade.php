@extends('layouts.app', ['activePage' => $status, 'titlePage' => __('Manajemen Laporan Harian')])

@section('content')
    <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
    <div class="content">
        <div class="container-fluid">
            <div class="row ">

                <div class="col-md-12">


                    @include('validation_error')
                    {{ Form::open(['url'=>'report'])}}

                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Tambah Laporan Harian</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                {{ Form::select('unit_id',$unit,null,['class'=>'form-control','placeholder'=>'Pilih unit','id'=>'unit_id'])}}
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="500">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                {{ Form::select('task_id',[],null,['class'=>'form-control','placeholder'=>'Pilih task','id'=>'task_id'])}}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @csrf
                            </table>
                            <br>
                            <br>
                            {{ Form::submit('Tambahkan',['class'=>'btn btn-primary'])}}
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Laporan Harian Aktif</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body">
                            <button id="buttonSubmit" name="buttonSubmit">Submit Masal</button>
                            <button id="buttonDelete" name="buttonDelete">Hapus Masal</button>
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
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('js')

        <script>


         var table = $('#table_task').DataTable({
            "columnDefs": [

                {
                    "width": "500px",
                    "targets": 4
                },

            ],
            "order": [],
            fixedColumns: true,
            processing: true,
            serverSide: false,
            ajax: {
                "url": '/report/json',
                "data":{
              "status_id":"1"
                }
            },

              select: {
            style: 'multi'
        },

            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'unit_name',
                    name: 'unit_name'
                },
                {
                    data: 'task_name',
                    name: 'task_name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action'
                }

            ],
        });




        $('#buttonSubmit').click(function() {
             var data= table.rows({selected:true}).data()
              if (confirm('Apakah Anda Yakin Ingin Submit Masal?')) {
             var newarray=[];
        for (var i=0; i < data.length ;i++){
           newarray.push(data[i]);
                         }
         var sData = newarray.join();


             $.ajax({
              url: "/report/mass_update",
              type: "PATCH",
              data: {
                  _token: $("#csrf").val(),
                  reports:newarray,
                  status_id:2

              },
              cache: false,
              success: function(dataResult){

                  var dataResult = JSON.parse(JSON.stringify(dataResult));

                  if(dataResult.statusCode==200){
                     table.ajax.reload();
                       alert(dataResult.messege);
                  }
                  else if(dataResult.statusCode==201){
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

          $('#buttonDelete').click(function() {
             var data= table.rows({selected:true}).data()
              if (confirm('Apakah Anda Yakin Ingin Hapus Masal?')) {
             var newarray=[];
        for (var i=0; i < data.length ;i++){
           newarray.push(data[i]);
                         }
         var sData = newarray.join();


             $.ajax({
              url: "/report/mass_delete",
              type: "DELETE",
              data: {
                  _token: $("#csrf").val(),
                  reports:newarray,

              },
              cache: false,
              success: function(dataResult){

                  var dataResult = JSON.parse(JSON.stringify(dataResult));

                  if(dataResult.statusCode==200){
                     table.ajax.reload();
                       alert(dataResult.messege);
                  }
                  else if(dataResult.statusCode==201){
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


        $(document).ready(function() {



            $('#unit_id').on('change', function(e) {
                table.ajax.reload();


                var d = e.target.value;
                $.ajax({
                    url: "{{ route('filter.taskQuery') }}",
                    type: "GET",
                    data: {
                        unit_id: d
                    },
                    success: function(data) {
                        $('#task_id').empty();
                        $.each(data.task, function(index, subcategory) {
                            $('#task_id').append('<option value="' + subcategory.id + '">' + subcategory.task_name + '</option>');
                        })
                    }
                })
            });

        });







        </script>
    @endpush

@endsection

