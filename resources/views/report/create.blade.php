@extends('layouts.app', ['activePage' =>'Active' , 'titlePage' => __('Tambah task')])
@section('content')

    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('validation_error')
                        {{ Form::open(['url'=>'report'])}}

                        <table >

                            <tr>
                                <td width="300">Unit</td>
                                <td>
                                    {{ Form::select('unit_id',$unit,null,['class'=>'form-control','placeholder'=>'Pilih unit','id'=>'id'])}}
                                </td>
                            </tr>

                            <tr>
                                <td width="300">Task</td>
                                <td>
                                    {{ Form::select('task_id',$task,null,['class'=>'form-control','placeholder'=>'Pilih task','id'=>'id'])}}
                                </td>
                            </tr>
                            @csrf
                        </table>
                        <br>
                        <br>




                        {{ Form::submit('Simpan Data',['class'=>'btn btn-primary'])}}
                        <a href="/report" class="btn btn-primary">Kembali</a>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

