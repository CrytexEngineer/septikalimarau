@extends('layouts.app', ['activePage' => 'table', 'titlePage' => __('Ceklist Laporan Harian')])
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content">

        <h2 class="text-center my-5">Tutorial Laravel #30 : Membuat Upload File Dengan Laravel</h2>

        <div class="col-lg-8 mx-auto my-5">

            @if(count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }} <br/>
                    @endforeach
                </div>
            @endif

            <form action="/upload/proses" method="POST" enctype="multipart/form-data">


                    <b>File Gambar</b><br/>
                    <input type="file" name="file">

                <div class="form-group">
                    <b>Keterangan</b>
                    <textarea class="form-control" name="keterangan"></textarea>
                </div>

                <input type="submit" value="Upload" class="btn btn-primary">
            </form>
        </div>

        <table class="compact align-self-center">
            <thead>
            <tr>
                <th width="1%">Gambar</th>
                <th width="1%">Keterangan</th>
                <th width="1%">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($gambar as $g)
                <tr>
                    <td><img width="150px" src="{{ url('/gambar_harian/'.$g->image_path) }}"></td>
                    <td>{{$g->keterangan}}</td>
                    <td><a class="btn btn-danger" href="/upload/hapus/{{ $g->id }}">HAPUS</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


</div>
</body>
</html>
