@extends('layouts.app', ['activePage' => 'table', 'titlePage' => __('Manajemen Item Pelaporan')])

@section('content')

    <div class="content">
        <div class="row ">

            <div class="col-md-12">



                @include('validation_error')
                {{ Form::open(['url'=>'item'])}}
                <h1 class="card p-md-2 mb-md-5" >
                    {{ Form::select('task_id',$task,null,['class'=>'form-control','selected'=>''.$task->first().'','id'=>'id'])}} </h1>





                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Tambah Tugas Pelaporan</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">


                        <table>

                            <tr>
                                <td width="300">Nama Item</td>
                                <td>
                                    {{ Form::text('item_name',null,['class'=>'form-control','placeholder'=>'Masukan Item'])}}
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
                        <h4 class="card-title ">Item Pelaporan</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display  compact" id="table_task">
                                <thead class=" text-primary">
                                <th>
                                    ID
                                </th>
                                <th>
                                    Nama Item
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
                "url": '/item/json',
                "data": function ( d ) {
                    d.id = $('#id').val();
                    console.log(d);
                }},
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'item_name', name: 'item_name'},
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

