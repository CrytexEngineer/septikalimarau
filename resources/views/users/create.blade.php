@extends('layouts.app', ['activePage' => 'manajemenUser', 'titlePage' => __('Manajemen User')])

@section('content')
    <div class="content">


        <div class="card justify-content-center ">
            <div class="card-header">@yield('title')</div>

            <div class="card-body ml-0">
                @include('validation_error')

                {{Form::open(['url'=>'user'])}}

                @csrf

                <div class="form-group row">

                    <div class="col-md-6">
                        {{ Form::text('nip', null, ['class'=>'form-control', 'placeholder'=> 'NIP Pegawai']) }}
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-6">
                        {{ Form::text('name', null, ['class'=>'form-control', 'placeholder'=> 'Nama Pegawai']) }}
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-6">
                        <td>
                            {{ Form::select('unit_id',$units,null,['class'=>'form-control','placeholder'=>'Pilih unit','id'=>'id'])}}
                        </td>
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-md-6">
                        {{ Form::text('email', null, ['class'=>'form-control', 'placeholder'=> 'E-Mail Pegawai']) }}
                    </div>
                </div>


                <div class="form-group row">

                    <div class="col-md-6">
                        {{ Form::password('password',['class'=>'form-control','placeholder'=>'Password'])}}
                    </div>
                </div>


                <div class="form-group row">

                    <div class="col-md-6">
                        <label for="roles">Roles</label>
                        @foreach($roles as $role)
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="role_id"
                                           value="{{ $role->id }}">
                                    <span class="form-check-sign">
                                <span class="check"></span>

                              </span>
                                    {{ $role->role_name }}
                                </label>
                            </div>
                        @endforeach


                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-3">
                        {{Form::submit('Simpan Data',['class'=>'btn btn-primary'])}}
                        <a href="/user" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection

@push('js')
    <script>
    $("input:checkbox").on('click', function() {
    console.log("fafsaf");
    // in the handler, 'this' refers to the box clicked on
    var $box = $(this);
    if ($box.is(":checked")) {
    // the name of the box is retrieved using the .attr() method
    // as it is assumed and expected to be immutable
    var group = "input:checkbox[name='" + $box.attr("name") + "']";
    // the checked state of the group/box on the other hand will change
    // and the current value is retrieved using .prop() method
    $(group).prop("checked", false);
    $box.prop("checked", true);
    } else {
    $box.prop("checked", false);
    }
    });


    </script>
@endpush
