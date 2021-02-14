@extends('layouts.app', ['activePage' => 'manajemenUser', 'titlePage' => __('Manajemen User')])

@section('content')

    <div class="content">
        @can('admin')
        <div class="card justify-content-center ">
            <div class="card-header">@yield('title')</div>

            <div class="card-body ml-0">
                @include('validation_error')

                {{ Form::model($user,['url'=>'user/'.$user->nip,'method'=>'PUT'])}}

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
                            <input type="password" name="password" id="password" class="form-control"
                                   placeholder="{{ __('Password...') }}"
                                   {{--                               value="{{ !$errors->has('password') ? "secret" : "" }}"--}}
                                   required>

                        </div>
                    </div>


                    <div class="form-group row">

                        <div class="col-md-6">
                            <label for="roles">Roles</label>

                            @foreach($roles as $role)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="role_id"
                                               @if($role->id==$user->role_id) checked @endif
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
        @endcan

            @if(Auth::user()->hasAnyRoles(['Petugas','Kanit']))
                <div class="card justify-content-center ">
                    <div class="card-header">@yield('title')</div>

                    <div class="card-body ml-0">
                        @include('validation_error')

                        {{ Form::model($user,['url'=>'user/'.$user->nip,'method'=>'PUT'])}}

                        @csrf

                        <div class="form-group row">

                            <div class="col-md-6">
                                {{ Form::text('nip', null, ['class'=>'form-control', 'placeholder'=> 'NIP Pegawai','readonly']) }}
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-6">
                                {{ Form::text('name', null, ['class'=>'form-control', 'placeholder'=> 'Nama Pegawai','readonly']) }}
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
                                <input type="password" name="password" id="password" class="form-control"
                                       placeholder="{{ __('Password...') }}"
                                       {{--                               value="{{ !$errors->has('password') ? "secret" : "" }}"--}}
                                       required>

                            </div>
                        </div>


                        <div class="form-group row">

                            <div class="col-md-6" hidden>
                                <label for="roles">Roles</label>

                                @foreach($roles as $role)
                                    <div class="form-check">
                                        <label class="form-check-label" >
                                            <input class="form-check-input" type="checkbox" name="role_id"
                                                   @if($role->id==$user->role_id) checked @endif
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
                                {{Form::submit('Simpank Data',['class'=>'btn btn-primary'])}}
                                <a href="/user" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
           @endif
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
