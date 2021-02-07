@extends('layouts.app', ['activePage' => 'table', 'titlePage' => __('Tambah task')])
@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('validation_error')
                        {{ Form::model($unit,['url'=>'unit/'.$unit->id,'method'=>'PUT'])}}

                        <table >
                            @csrf
                            <tr>
                                <td width="300">Nama Unit</td>
                                <td>
                                    {{ Form::text('unit_name',null,['class'=>'form-control','placeholder'=>'Masukan Nama Unit'])}}
                                </td>
                            </tr>



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
