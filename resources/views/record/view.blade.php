@extends('layouts.app', ['activePage' => $status, 'titlePage' => __('Ceklist Laporan Harian')])
@section('content')

    <div class="content">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            @include('validation_error')
            {{ Form::model($records,['url'=>'record'])}}
            {{--                {{dd($record->all())}}--}}

            <div class="card">
                <div class="card-header card-header-primary">

                    <h4 class="card-title ">Ceklist Laporan Harian
                        <div class="row" style="margin: 8px">
                            <h4><span class="badge badge-warning"
                                      style="margin-right: 8px">{{$task->get()->first()['task_name']}}</span></h4>
                            <h4><span class="badge badge-warning"
                                      style="margin-right: 8px">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $report->first()['created_at'])->isoFormat('dddd, D MMMM Y')}}</span>
                            </h4>
                            <h4><span class="badge badge-warning" style="margin-right: 8px">
                        Pagi:{{date("H:i", strtotime($report->first()['created_at']))}}</span></h4>
                            <h4><span class="badge badge-warning" style="margin-right: 8px">
                        Siang:{{date("H:i", strtotime($report->first()['updated_at']))}}</span></h4>

                        </div>
                    </h4>
                    <p class="card-category"></p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table_form" class="table-striped">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Uraian</th>
                                <th>Kondisi Pagi</th>
                                <th>Kondisi Siang</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!$records)
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{$loop->index}}</td>
                                        <input type="hidden" id="report_id[]" name="report_id[]"
                                               value={{$report->first()->id }}>
                                        <input type="hidden" id="item_id[]" name="item_id[]" value={{$item->id }}>
                                        <td><input type="hidden" id="item_name[]" name="item_name[]"
                                                   value="{{$item->item_name }}"
                                                   readonly>
                                            {{$item->item_name }}
                                        </td>

                                        <td>
                                            {{ Form::select('kondisi_pagi[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), null, array(
                                                'class' => 'form-control','disabled'
                                                ))}}
                                        </td>

                                        <td>
                                            {{ Form::select('kondisi_siang[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), null, array(
                                                'class' => 'form-control','disabled',
                                                ))}}
                                        </td>

                                    </tr>
                                @endforeach
                            @endif

                            @if($records)
                                @foreach($records as $record)
                                    <tr>
                                        <td>{{$loop->index}}</td>
                                        <input type="hidden" id="report_id[]" name="report_id[]" d
                                               value={{$report->first()->id }}>
                                        <input type="hidden" id="item_id[]" name="item_id[]"
                                               value={{$record->item_id }}>
                                        <td><input type="hidden" id="item_name[]" name="item_name[]"
                                                   value="{{$record->item_name }}"
                                                   readonly>
                                           {{$record->item_name }}
                                        </td>

                                        <td>
                                                {{ Form::select('kondisi_pagi[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), $record->kondisi_pagi, array(
                                                    'class' => 'form-control', 'disabled'
                                                    ))}}
                                        </td>
                                        <td>
                                            {{ Form::select('kondisi_siang[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), $record->kondisi_siang, array(
                                                'class' => 'form-control', 'disabled'
                                                ))}}
                                        </td>

                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>


                    <div class="row">
                        <div class="col-sm-3">
                            <label>Petugas Pagi</label>
                            {{ Form::select('petugas_pagi_id',$petugas,$selectedPetugasPagi?$selectedPetugasPagi->id:null,['class'=>'form-control','placeholder'=>'Pilih Petugas','id'=>'id'])}}
                        </div>
                        <div class="col-sm-3">
                            <label>Petugas Siang</label>
                            {{ Form::select('petugas_siang_id',$petugas,$selectedPetugasSiang?$selectedPetugasSiang->id:null,['class'=>'form-control','placeholder'=>'Pilih Petugas','id'=>'id'])}}
                        </div>
                            <div class="col-sm-3">
                            <label>Kanit</label>{{ Form::select('kanit_id',$kanit,$selectedKanit?$selectedKanit->id:null,['class'=>'form-control','placeholder'=>$selectedKanit,'id'=>'id','disabled'])}}
                        </div>
                        <div class="col-sm-3">
                            <label>Kasi</label>{{ Form::select('kasi_id',$kasi,$selectedKasi?$selectedKasi->id:null,['class'=>'form-control','placeholder'=>$selectedKasi,'id'=>'id','disabled'])}}
                        </div>
                    </div>

                    <div class="form-outline">
                        <label for="comment">Keterangan:</label>
                        <textarea class="form-control" rows="5" id="keterangan"  disabled
                                  name="keterangan">{{$report->first()->keterangan}}</textarea>
                    </div>

{{--                    @if($report->first()->status_id!=5)--}}
{{--                        {{Form::submit('Simpan Data',['class'=>'btn btn-primary'])}}--}}
{{--                    @endif--}}

                    {{Form::close()}}
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                        <div class="table-responsive">
                    <table id="table_picture" class="">
                        <thead>
                        <tr>
                            <th >Gambar</th>
                            <th >Keterangan</th>
{{--                                <th>Aksi</th>--}}

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($gambar as $g)
                            @csrf
                            <tr>
                                <td><img width="150px" src="{{ url('/gambar_harian/'.$g->image_path) }}"></td>
                                <td>{{$g->keterangan}}</td>
{{--                                <td><a class="btn btn-danger" href="/upload/hapus/{{ $g->id }}">HAPUS</a></td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        </div>
                </div>
            </div>

        @if($status=='Submitted')
            <a href="/report/review">
                <button class="btn btn-info">Kembali</button>
            </a>
        @elseif($status=='Approved')
            <a href="/report/archive">
                <button class="btn btn-info">Kembali</button>
            </a>
        @endif
    </div>




    @push('js')
        <script>
$(document).ready(function() {
  $('#table_form').DataTable();
      $('#table_picture').DataTable();

{{--    $('button').click( function() {--}}
            {{--        var data = table.$('input, select').serialize();--}}
            {{--        console.log(data);--}}
            {{--        $.ajaxSetup({--}}
            {{--          headers: {--}}
            {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--          }--}}
            {{--        });--}}
            {{--       $.ajax({--}}
            {{--        url: '/record',--}}
            {{--        type: 'POST',--}}
            {{--        data: data,--}}
            {{--        dataType: 'json'--}}
            {{--    });--}}
            {{--    } );--}}
            } );

















        </script>
    @endpush
@endsection

