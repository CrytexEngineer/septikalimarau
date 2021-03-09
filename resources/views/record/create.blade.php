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
                                    <td>
                                        {{$item->item_name }}
                                        <input type="hidden" id="item_name[]" name="item_name[]"
                                               value="{{$item->item_name }}"
                                               readonly>
                                    </td>
                                    @if(!$isJamSiang)
                                        <td>
                                            {{ Form::select('kondisi_pagi[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), null, array(
                                                'class' => 'form-control'
                                                ))}}
                                        </td>
                                        <td>
                                            {{ Form::select('kondisi_siang[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), null, array(
                                             'class' => 'form-control', 'disabled'
                                             ))}}
                                            {{ Form::select('kondisi_siang[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), null, array(
                                                                                        'class' => 'form-control', 'hidden'))}}
                                        </td>
                                    @else
                                        <td>
                                            {{ Form::select('kondisi_pagi[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), null, array(
                                                'class' => 'form-control', 'disabled'
                                                ))}}
                                            {{ Form::select('kondisi_pagi[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), null, array(
                                              'class' => 'form-control', 'hidden'
                                              ))}}
                                        </td>
                                        <td>
                                            {{ Form::select('kondisi_siang[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), null, array(
                                                'class' => 'form-control'
                                                ))}}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif

                        @if($records)
                            @foreach($records as $record)
                                <tr>
                                    <td>{{$loop->index}}</td>
                                    <input type="hidden" id="report_id[]" name="report_id[]"
                                           value={{$report->first()->id }}>
                                    <input type="hidden" id="item_id[]" name="item_id[]"
                                           value={{$record->item_id }}>
                                    <td><input type="hidden" id="item_name[]" name="item_name[]"
                                               value="{{$record->item_name }}"
                                               readonly>
                                        {{$record->item_name}}
                                    </td>
                                    @if(!$isJamSiang)
                                        <td>
                                            {{ Form::select('kondisi_pagi[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), $record->kondisi_pagi, array(
                                                'class' => 'form-control'
                                                ))}}
                                        </td>
                                        <td>
                                            {{ Form::select('kondisi_siang[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), $record->kondisi_siang, array(
                                                'class' => 'form-control', 'disabled'
                                                ))}}
                                            {{ Form::select('kondisi_siang[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), $record->kondisi_siang, array(
                                              'class' => 'form-control', 'hidden'
                                              ))}}

                                        </td>

                                    @else
                                        <td>
                                            {{ Form::select('kondisi_pagi[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), $record->kondisi_pagi, array(
                                                'class' => 'form-control', 'disabled'
                                                ))}}
                                            {{ Form::select('kondisi_pagi[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), $record->kondisi_pagi, array(
                                             'class' => 'form-control', 'hidden'
                                             ))}}
                                        </td>

                                        <td>
                                            {{ Form::select('kondisi_siang[]', array('0'=>'Belum Di Cek','1'=>'Baik','2'=>'Kurang Baik','3'=>'Tidak Baik'), $record->kondisi_siang, array(
                                                'class' => 'form-control'
                                                ))}}
                                        </td>
                                    @endif
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
                        {{ Form::select('petugas_siang_id',$petugas,$selectedPetugasSiang?$selectedPetugasSiang->id:null,['class'=>'form-control','placeholder'=>'Pilih Kanit','id'=>'id'])}}
                    </div>
                    <div class="col-sm-3">
                        <label>Kanit</label>{{ Form::select('kanit_id',$kanit,$selectedKanit?$selectedKanit->id:null,['class'=>'form-control','placeholder'=>'Pilih Kanit','id'=>'id'])}}
                    </div>
                    <div class="col-sm-3">
                        <label>Kasi</label>{{ Form::select('kasi_id',$kasi,$selectedKasi?$selectedKasi->id:null,['class'=>'form-control','placeholder'=>'Pilih Kasi','id'=>'id'])}}
                    </div>
                </div>

                <div class="form-outline">
                    <label for="comment">Keterangan:</label>
                    <textarea class="form-control" rows="5" id="keterangan"
                              name="keterangan">{{$report->first()->keterangan}}</textarea>
                </div>

                @if($report->first()->status_id!=5)
                    {{Form::submit('Simpan ',['class'=>'btn btn-primary'])}}
                @endif

                {{Form::close()}}
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @if($report->first()->status_id!=5)
                    <form action="/lampiran/upload" method="POST" enctype="multipart/form-data">

                        @csrf
                        <input type="hidden" id="report_id" name="report_id" value={{$report->first()->id }}>
                        <b>Lampiran Gambar</b><br/>
                        <input type="file" name="file[]" class="form-control" multiple>
                        <input type="submit" value="Upload" class="btn btn-primary">

                    </form>

                @endif

                <form action="/lampiran/update" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="table-responsive">
                        <table id="table_picture" class="">
                            <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Keterangan</th>

                                @if($report->first()->status_id!=5)
                                    <th>Aksi</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($gambar as $g)
                                @csrf
                                <tr>
                                    <td><img width="150px" src="{{ url('/gambar_harian/'.$g->image_path) }}"></td>
                                    <input type="hidden" id="image_id" name="image_id[]"
                                           value={{$g->id }}>
                                    <td><textarea class="form-control" rows="5" id="keterangan"
                                                  name="keterangan[]">{{$g->keterangan}}</textarea></td>
                                    <td><a class="btn btn-danger" href="/lampiran/hapus/{{ $g->id }}">HAPUS</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <input type="submit" value="Simpan " class="btn btn-primary">
                </form>
                @if($status=='Active')
                    <a href="/report">
                        <button class="btn btn-info">Kembali</button>
                    </a>
                @elseif($status=='Rejected')
                    <a href="/report/reject">
                        <button class="btn btn-info">Kembali</button>
                    </a>
                @elseif($status=='Submitted')
                    <a href="/report/review">
                        <button class="btn btn-info">Kembali</button>
                    </a>
                @endif
            </div>
        </div>
    </div>





    @push('js')
        <script>
            $(document).ready(function () {
                $('#table_form').DataTable();
                $('#table_picture').DataTable();
            });
        </script>
    @endpush
@endsection

