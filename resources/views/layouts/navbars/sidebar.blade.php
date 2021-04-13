<div class="sidebar" data-color="orange" data-background-color="white"
     data-image="{{ asset('material') }}/img/sidebar-1.jpg">
    <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
    <div class="logo">
        <div class="container ml-4 mb-0">
            <data-image><img class="center" style="width:150px " src="{{ asset('material') }}/img/logo-dishub.png">
            </data-image>
        </div>

        <a href=# class="simple-text logo-normal">
            {{ __('Teknik Operasi') }}
        </a>


    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item {{ ($activePage == 'laporanAktif' || $activePage == 'laporanDitinjau'|| $activePage == 'laporanArsip') ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#Laporan" aria-expanded="true">
                    <i class="material-icons">dashboard</i>
                    <p>{{ __('Laporan') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="Laporan">
                    <ul class="nav">
                        <li class="nav-item{{ $activePage == 'Active' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('report.index') }}">
                                <span class="sidebar-mini">LA</span>
                                <span class="sidebar-normal">{{ __('Laporan Aktif') }} </span>
                            </a>
                        </li>
                        <li class="nav-item{{ $activePage == 'Rejected' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('report.reject') }}">
                                <span class="sidebar-mini">LT</span>
                                <span class="sidebar-normal">{{ __('Laporan Ditolak') }} </span>
                            </a>
                        </li>
                        <li class="nav-item{{ $activePage == 'Submitted' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('report.review') }}">
                                <span class="sidebar-mini">LD</span>
                                <span class="sidebar-normal"> {{ __('Laporan Ditinjau') }} </span>
                            </a>
                        </li>
                        <li class="nav-item{{ $activePage == 'Approved' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('report.archive') }}">
                                <span class="sidebar-mini">LA</span>
                                <span class="sidebar-normal"> {{ __('Arsip') }} </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @can('management')
                <li class="nav-item {{ ($activePage == 'manajemenUnit' || $activePage == 'manajemenTugas') ? ' active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#manajemenData" aria-expanded="true">
                        <i class="fas fa-globe-asia"></i>
                        <p>{{ __('Manajemen Data') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse show" id="manajemenData">
                        <ul class="nav">
                            @can('admin')
                                <li class="nav-item{{ $activePage == 'manajemenUnit' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('unit.index') }}">
                                        <span class="sidebar-mini"> MU </span>
                                        <span class="sidebar-normal">{{ __('Manajemen  Unit') }} </span>
                                    </a>
                                </li>
                            @endcan

                            <li class="nav-item{{ $activePage == 'manajemenTugas' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('task.index') }}">
                                    <span class="sidebar-mini"> MT </span>
                                    <span class="sidebar-normal"> {{ __('Manajemen  Tugas') }} </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

                <li class="nav-item{{ $activePage == 'manajemenUser' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('user.index') }}">
                        <i class="material-icons">account_circle</i>
                        <p>{{ __('Manajemen User') }}</p>
                    </a>
                </li>

            {{--      <li class="nav-item{{ $activePage == 'typography' ? ' active' : '' }}">--}}
            {{--        <a class="nav-link" href="{{ route('typography') }}">--}}
            {{--          <i class="material-icons">library_books</i>--}}
            {{--            <p>{{ __('Typography') }}</p>--}}
            {{--        </a>--}}
            {{--      </li>--}}
            {{--      <li class="nav-item{{ $activePage == 'icons' ? ' active' : '' }}">--}}
            {{--        <a class="nav-link" href="{{ route('icons') }}">--}}
            {{--          <i class="material-icons">bubble_chart</i>--}}
            {{--          <p>{{ __('Icons') }}</p>--}}
            {{--        </a>--}}
            {{--      </li>--}}
            {{--      <li class="nav-item{{ $activePage == 'map' ? ' active' : '' }}">--}}
            {{--        <a class="nav-link" href="{{ route('map') }}">--}}
            {{--          <i class="material-icons">location_ons</i>--}}
            {{--            <p>{{ __('Maps') }}</p>--}}
            {{--        </a>--}}
            {{--      </li>--}}
            {{--      <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">--}}
            {{--        <a class="nav-link" href="{{ route('notifications') }}">--}}
            {{--          <i class="material-icons">notifications</i>--}}
            {{--          <p>{{ __('Notifications') }}</p>--}}
            {{--        </a>--}}
            {{--      </li>--}}
            {{--      <li class="nav-item{{ $activePage == 'language' ? ' active' : '' }}">--}}
            {{--        <a class="nav-link" href="{{ route('language') }}">--}}
            {{--          <i class="material-icons">language</i>--}}
            {{--          <p>{{ __('RTL Support') }}</p>--}}
            {{--        </a>--}}
            {{--      </li>--}}
            {{--      <li class="nav-item active-pro{{ $activePage == 'upgrade' ? ' active' : '' }}">--}}
            {{--        <a class="nav-link text-white bg-danger" href="{{ route('upgrade') }}">--}}
            {{--          <i class="material-icons text-white">unarchive</i>--}}
            {{--          <p>{{ __('Upgrade to PRO') }}</p>--}}
            {{--        </a>--}}
            {{--      </li>--}}
        </ul>
    </div>
</div>
