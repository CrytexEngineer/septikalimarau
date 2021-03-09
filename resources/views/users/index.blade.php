@extends('layouts.app', ['activePage' => 'manajemenUser', 'titlePage' => __('Manajemen User')])

@section('content')

    <div class="content">


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Daftar User</h4>
                        <p class="card-category"></p>

                    </div>
                    <div class="card-body">
                        @can('admin')
                            <a href="/user/create" class="btn btn-md btn-primary"><i class="fas fa-plus"></i>Tambah
                                Pengguna
                                <div class="ripple-container"></div>
                            </a>
                        @endcan

                        <a class="btn btn-md btn-primary" href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();"> <i
                                class="fas fa-user"></i> Logout </a>

                        <div class="ripple-container"></div>
                        </a>
                        <div class="table-responsive">
                            <table class="display dt" id="table_user">
                                <thead class=" text-primary">
                                <th>
                                    NIP
                                </th>
                                <th>
                                    Nama Pegawai
                                </th>
                                <th>
                                    Unit
                                </th>
                                <th>
                                    roles
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
            $(document).ready(function () {

                $('#table_user').DataTable({

                    processing: true,
                    serverSide: false,
                    ajax: '/user/json',
                    columns: [
                        {data: 'nip', name: 'nip'},
                        {data: 'name', name: 'name'},
                        {data: 'unit_name', name: 'unit_name'},
                        {data: 'role_name', name: 'role_name'},
                        {data: 'action', name: 'action'},

                    ],

                });

            });


        </script>
    @endpush

@endsection

