@extends('layouts.app', ['activePage' => 'table', 'titlePage' => __('Edit task')])
@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('validation_error')
                        {{ Form::model($task,['url'=>'task/'.$task->id,'method'=>'PUT'])}}

                        <div class="form-group row">

                            <div class="col-md-6">
                                <td>
                                    {{ Form::text('task_name',null,['class'=>'form-control','placeholder'=>'Pilih unit','id'=>'id'])}}
                                </td>
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-6">
                                <td>
                                  <input name="id" id="id" type="hidden" value="{{$task->id}}" >
                                </td>
                            </div>
                        </div>
                        <br>
                        <br>
                        {{ Form::submit('Simpan Data',['class'=>'btn btn-primary'])}}
                        <a href="/task/" class="btn btn-primary">Kembali</a>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
