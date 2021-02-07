@extends('layouts.app', ['activePage' => 'table', 'titlePage' => __('Tambah task')])
@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('validation_error')
                        {{ Form::open(['url'=>'kurikulum'])}}

                        <table >

                            <tr>
                                <td width="300">Unit</td>
                                <td>
                                    {{ Form::select('KL_IDSemester',['1'=>'Bagunan dan Landasan','2'=>'Listrik Bandara'],null,['class'=>'form-control','placeholder'=>'Pilih unit'])}}
                                </td>
                            </tr>

                            <tr>
                                <td width="300">Task</td>
                                <td>
                                    {{ Form::text('KL_Tahun_Kurikulum',null,['class'=>'form-control','placeholder'=>'Masukan task'])}}
                                </td>
                            </tr>

                            @csrf


                        </table>
                        <br>
                        <br>
                        {{ Form::submit('Simpan Data',['class'=>'btn btn-primary'])}}
                        <a href="/kurikulum" class="btn btn-primary">Kembali</a>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
