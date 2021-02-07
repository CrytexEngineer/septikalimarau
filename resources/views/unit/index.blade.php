@extends('layouts.app', ['activePage' => 'manajemenUnit', 'titlePage' => __('Manajemen Unit')])

@section('content')

    <div class="content">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Tambah Unit</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">
                        @include('validation_error')
                        {{ Form::open(['url'=>'unit'])}}

                        <table>

{{--                            <tr>--}}
{{--                                <td width="300">Unit</td>--}}
{{--                                <td>--}}
{{--                                    {{ Form::select('KL_IDSemester',['1'=>'Bagunan dan Landasan','2'=>'Listrik Bandara'],null,['class'=>'form-control','placeholder'=>'Pilih unit'])}}--}}
{{--                                </td>--}}
{{--                            </tr>--}}

                            <tr>
                                <td width="300">Nama Unit</td>
                                <td>
                                    {{ Form::text('unit_name',null,['class'=>'form-control','placeholder'=>'Masukan Nama Unit'])}}
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
                        <h4 class="card-title ">Daftar Unit</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display dt-responsive" id="table_task">
                                <thead class=" text-primary">
                                <th>
                                    ID
                                </th>
                                <th>
                                    Nama Unit
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
        $(document).ready(function() {

    $('#table_task').DataTable({
    "columnDefs": [
    { "width": "20%" },
  ],   fixedColumns: true,
   processing: true,
                serverSide: false,
                ajax: '/unit/json',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'unit_name', name: 'task_name'},
                {data: 'action', name: 'action'}

            ],

});

} );




        </script>
    @endpush

@endsection

