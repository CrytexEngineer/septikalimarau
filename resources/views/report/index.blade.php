@extends('layouts.app', ['activePage' => $status, 'titlePage' => __('Manajemen Laporan Harian')])

@section('content')

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
                            <button id="button">Row count</button>
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
    var table= $('#table_task').DataTable({
 "columnDefs": [

      { "width": "500px", "targets": 4 },

    ],
            "order": [],
        fixedColumns: true,
                processing: true,
                serverSide: false,
                  ajax: {
                "url": '/report/json',
                "data": function ( d ) {
                    d.status_id = "1";
                    console.log(d);
                }},

                columns: [
                    {data: 'id', name: 'id'},
                     {data: 'unit_name', name: 'unit_name'},
                    {data: 'task_name', name: 'task_name'},
                    {data: 'created_at', name: 'created_at'},
                     {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action'}

            ],
});



          $(document).ready(function () {

 $('#button').click( function () {
        console.log( table.row().data());
    } );

                  $('#table_task tbody').on( 'click', 'tr', function () {
                             $(this).toggleClass('selected');
                  } );


                 $('#unit_id').on('change',function(e) {
                    table.ajax.reload();


                 var d = e.target.value;
                        $.ajax({
                       url:"{{ route('filter.taskQuery') }}",
                       type:"GET",
                       data: {
                           unit_id: d
                        },
                       success:function (data) {
                          $('#task_id').empty();
                          $.each(data.task,function(index,subcategory){
                          $('#task_id').append('<option value="'+subcategory.id+'">'+subcategory.task_name +'</option>');
                             })
                       }
                   })
                });

            });












        </script>
    @endpush

@endsection

