@if ($rejectedReports)


    <div class="row" style="margin: 8px">
            <span class="alert  col-sm-1 d-flex justify-content-center d-flex align-items-center"
                  style="color: white;padding:8px;
                            font-size: 2rem;
                        background-color: orange">
                 <i class="fas fa-exclamation-triangle"></i>
            </span>
        <span class="alert col-sm-11 d-flex align-items-center">
                    Perhatian, Terdapat {{$rejectedReports}} Laporan Ditolak
            </span>
    </div>

@endif
