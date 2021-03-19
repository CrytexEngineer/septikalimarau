@extends('layouts.app', ['activePage' => 'manajemenTugas', 'titlePage' => __('Manajemen Tugas Pelaporan')])

@section('content')

    <div class="content">
        <div class="row ">

            <div class="col-md-12">



                        @include('validation_error')
                        {{ Form::open(['url'=>'task'])}}
                        <h1 class="card p-md-2 mb-md-5" >
                            {{ Form::select('unit_id',$unit,null,['class'=>'form-control','placeholder'=>'Pilih unit','id'=>'id'])}} </h1>





                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Tambah Tugas Pelaporan</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">


                        <div class="form-group row">
                            <div class="col-md-6">
                                <td width="300">Nama Tugas</td>
                                <td>
                                    {{ Form::text('task_name',null,['class'=>'form-control','placeholder'=>'Masukan task'])}}
                                </td>
                            </div>
                        </div>

                            @csrf
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
                        <h4 class="card-title ">Tugas Pelaporan</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">
                        <div class="table">
                            <table class="display  compact" id="table_task">
                                <thead class=" text-primary">
                                <th>
                                    ID
                                </th>
                                <th>
                                    Nama Task
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
      var table= $('#table_task').DataTable({
    "columnDefs": [
    { "width": "20%"},
  ],
     fixedColumns: true,
   processing: true,
                serverSide: false,
                  ajax: {
                "url": '/task/json',
                "data": function ( d ) {
                    d.id = $('#id').val();
                    console.log(d);
                }},
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'task_name', name: 'task_name'},
                {data: 'action', name: 'action'}

            ],

});



   $(document).ready(function () {
                $('#id').on('change',function(e) {
               table.ajax.reload();


                });
        });






        </script>
    @endpush

@endsection

