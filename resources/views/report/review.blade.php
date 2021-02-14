@extends('layouts.app', ['activePage' => $status, 'titlePage' => __('Manajemen Laporan Harian')])

@section('content')

    <div class="content">


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Laporan Harian Dalam Peninjauan</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display  compact" id="table_task">
                                <thead class=" text-primary">
                                <th>
                                    ID
                                </th>
                                <th>
                                    Unit
                                </th>
                                <th>
                                    Tugas Pelaporan
                                </th>
                                <th>
                                    Dibuat
                                </th>
                                <th>
                                    Diperbaharui
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
           var table= $('#table_task').DataTable({
           "order": [],
           columnDefs: [
            { width: '20%' }],
                fixedColumns: true,
                processing: true,
                serverSide: false,
                  ajax: {
                "url": '/report/json',
                "data": function ( d ) {
                    d.status_id = "2";
                    console.log(d);
                }},
                columns: [
                    {data: 'id', name: 'id'},
                     {data: 'unit_name', name: 'unit_name'},
                    {data: 'task_name', name: 'task_name'},
                     {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action'}

            ],
});



   $(document).ready(function () {
                $('#id').on('change',function(e) {
               table.ajax.reload();
                });

{{--                 $('#id').on('change',function(e) {--}}

            {{--                 console.log("JAKARTA"+$('id').value);--}}
            {{--                 var unit_id = e.target.value;--}}
            {{--                               $.ajax({--}}
            {{--                       url:"{{ route('subjectQuery') }}",--}}
            {{--                       type:"GET",--}}
            {{--                       data: {--}}
            {{--                           unit_id: id--}}
            {{--                        },--}}
            {{--                       success:function (data) {--}}
            {{--                          $('#MK_ID').empty();--}}
            {{--                          $.each(data.subject,function(index,subcategory){--}}
            {{--                          $('#MK_ID').append('<option value="'+subcategory.KE_KR_MK_ID+'">'+subcategory.MK_Mata_Kuliah +'</option>');--}}
            {{--                        })--}}
            {{--                       }--}}
            {{--                   })--}}
            {{--                });--}}
            });


</script>
    @endpush

@endsection

