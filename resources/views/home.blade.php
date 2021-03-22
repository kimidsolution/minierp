@extends('layouts.backend.metrica.master')

@section('css')
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
        <!-- end col-->
    </div>
    <!--end row-->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-0">Revenue</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="media my-3">
                                <img src="{{ URL::asset('metrica/assets/images/widgets/dollar.png') }}" alt="" class="thumb-md rounded-circle" />
                                <div class="media-body align-self-center text-truncate ml-3">
                                    <h4 class="mt-0 mb-1 font-weight-semibold text-dark font-24">$36154.00</h4>
                                    <p class="text-muted text-uppercase mb-0 font-12">Total Revenue Of This Month</p>
                                </div>
                                <!--end media-body-->
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-md-8">
                            <ul class="nav-border nav nav-pills" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link font-weight-semibold" data-toggle="tab" href="#Today" role="tab">Today</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-semibold" data-toggle="tab" href="#This_Week" role="tab">This Week</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active font-weight-semibold" data-toggle="tab" href="#This_Month" role="tab">This Month</a>
                                </li>
                            </ul>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                    <div class="tab-content">
                        <div class="tab-pane pt-3" id="Today" role="tabpanel">
                            <div id="eco_dash" class="apex-charts"></div>
                        </div>
                        <!-- Tab panes -->
                        <div class="tab-pane pt-3" id="This_Week" role="tabpanel">
                            <div id="Top_Week" class="apex-charts"></div>
                        </div>
                        <!-- Tab panes -->
                        <div class="tab-pane active pt-3" id="This_Month" role="tabpanel">
                            <canvas id="bar" class="drop-shadow w-100" height="350"></canvas>
                        </div>
                        <!-- Tab panes -->
                    </div>
                    <!-- Tab content -->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->

        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 align-self-center text-center">
                                    <i data-feather="users" class="align-self-center icon-lg icon-dual-pink"></i>
                                </div>
                                <!--end col-->
                                <div class="col-8">
                                    <h3 class="mt-0 mb-1 font-weight-semibold">24k</h3>
                                    <p class="mb-0 font-12 text-muted text-uppercase font-weight-semibold-alt">Visits</p>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end  card-->
                </div>
                <!--end col-->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body justify-content-center">
                            <div class="row">
                                <div class="col-4 align-self-center text-center">
                                    <i data-feather="shopping-cart" class="align-self-center icon-lg icon-dual-secondary"></i>
                                </div>
                                <!--end col-->
                                <div class="col-8">
                                    <h3 class="mt-0 mb-1 font-weight-semibold">10k</h3>
                                    <p class="mb-0 font-12 text-muted text-uppercase font-weight-semibold-alt">New Orders</p>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end  card-->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 align-self-center text-center">
                                    <i data-feather="repeat" class="align-self-center icon-lg icon-dual-purple"></i>
                                </div>
                                <!--end col-->
                                <div class="col-8">
                                    <h3 class="mt-0 mb-1 font-weight-semibold">1.5k</h3>
                                    <p class="mb-0 font-12 text-uppercase font-weight-semibold-alt text-muted">Return Orders</p>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end  card-->
                </div>
                <!--end col-->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body justify-content-center">
                            <div class="row">
                                <div class="col-4 align-self-center text-center">
                                    <i data-feather="layers" class="align-self-center icon-lg icon-dual-warning"></i>
                                </div>
                                <!--end col-->
                                <div class="col-8">
                                    <h3 class="mt-0 mb-1 font-weight-semibold">+22.98%</h3>
                                    <p class="mb-0 font-12 text-uppercase font-weight-semibold-alt text-muted">Growth</p>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end  card-->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Monthly Trends</h4>
                    <div class="row">
                        <div class="col-6">
                            <div id="eco_categories" class="apex-charts mb-n3"></div>
                        </div>
                        <!--end col-->
                        <div class="col-6 align-self-center">
                            <ul class="list-unstyled">
                                <li class="list-item mb-2 font-weight-semibold-alt"><i class="fas fa-play text-primary mr-2"></i>Electronic</li>
                                <li class="list-item mb-2 font-weight-semibold-alt"><i class="fas fa-play text-success mr-2"></i>Footwear</li>
                                <li class="list-item font-weight-semibold-alt"><i class="fas fa-play text-pink mr-2"></i>Clothes</li>
                            </ul>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-round dual-btn-icon">View Details <i class="mdi mdi-arrow-right"></i></button>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end  card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-7 align-self-center">
                            <div class="timer-data">
                                <div class="icon-info mt-1 mb-4">
                                    <i class="mdi mdi-bullseye bg-soft-success"></i>
                                </div>
                                <h3 class="mt-0 text-dark">45k <span class="font-14">of 70k</span></h3>
                                <h4 class="mt-0 header-title text-truncate mb-1">Monthly Goal</h4>
                                <p class="text-muted mb-0 text-truncate">It is a long established fact that a reader.</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-5 align-self-center">
                            <div class="mt-4">
                                <span class="text-info">Complate</span>
                                <small class="float-right text-muted ml-3 font-14">62%</small>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 62%; border-radius: 5px;" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-7 align-self-center">
                            <div class="timer-data">
                                <div class="icon-info mt-1 mb-4">
                                    <i class="mdi mdi-bullseye-arrow bg-soft-pink"></i>
                                </div>
                                <h3 class="mt-0 text-dark">26m <span class="font-14">of 30m</span></h3>
                                <h4 class="mt-0 header-title text-truncate mb-1">Yearly Goal</h4>
                                <p class="text-muted mb-0 text-truncate">It is a long established fact that a reader.</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-5 align-self-center">
                            <div class="mt-4">
                                <span class="text-info">Complate</span>
                                <small class="float-right text-muted ml-3 font-14">81%</small>
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-pink" role="progressbar" style="width: 81%; border-radius: 5px;" aria-valuenow="81" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body dash-info-carousel">
                    <h4 class="mt-0 header-title mb-0">Top 3 Best Saler</h4>
                    <div id="carousel_1" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item">
                                <div class="media mb-2 mt-3">
                                    <div class="user-img-box">
                                        <img src="{{ URL::asset('metrica/assets/images/users/user-5.jpg') }}" alt="" class="rounded-circle" />
                                        <img src="{{ URL::asset('metrica/assets/images/flags/french_flag.jpg') }}" alt="" class="flag" />
                                    </div>
                                    <div class="media-body align-self-center text-truncate ml-3">
                                        <h4 class="mt-0 font-weight-semibold text-dark font-24">Matt Rosales</h4>
                                        <p class="text-muted text-uppercase mb-0 font-12">Total Revenue Of This Month</p>
                                        <h4 class="font-18 text-success font-weight-semibold">$42,874.00</h4>
                                    </div>
                                    <!--end media-body-->
                                </div>
                                <!--end media-->
                            </div>
                            <!--end carousel-item-->
                            <div class="carousel-item active">
                                <div class="media mb-2 mt-3">
                                    <div class="user-img-box">
                                        <img src="{{ URL::asset('metrica/assets/images/users/user-7.jpg') }}" alt="" class="rounded-circle" />
                                        <img src="{{ URL::asset('metrica/assets/images/flags/us_flag.jpg') }}" alt="" class="flag" />
                                    </div>
                                    <div class="media-body align-self-center text-truncate ml-3">
                                        <h4 class="mt-0 font-weight-semibold text-dark font-24">Rosa Dodson</h4>
                                        <p class="text-muted text-uppercase mb-0 font-12">Total Revenue Of This Month</p>
                                        <h4 class="font-18 text-success font-weight-semibold">$30,125.00</h4>
                                    </div>
                                    <!--end media-body-->
                                </div>
                                <!--end media-->
                            </div>
                            <!--end carousel-item-->
                            <div class="carousel-item">
                                <div class="media mb-2 mt-3">
                                    <div class="user-img-box">
                                        <img src="{{ URL::asset('metrica/assets/images/users/user-6.jpg') }}" alt="" class="rounded-circle" />
                                        <img src="{{ URL::asset('metrica/assets/images/flags/spain_flag.jpg') }}" alt="" class="flag" />
                                    </div>
                                    <div class="media-body align-self-center text-truncate ml-3">
                                        <h4 class="mt-0 font-weight-semibold text-dark font-24">Helen White</h4>
                                        <p class="text-muted text-uppercase mb-0 font-12">Total Revenue Of This Month</p>
                                        <h4 class="font-18 text-success font-weight-semibold">$51,541.00</h4>
                                    </div>
                                    <!--end media-body-->
                                </div>
                                <!--end media-->
                            </div>
                            <!--end carousel-item-->
                        </div>
                        <!--end carousel-inner-->
                        <a class="carousel-control-prev" href="#carousel_1" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel_1" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <!--end carousel-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
</div>
<!-- container -->


@endsection

@section('script')
<script src="{{ URL::asset('metrica/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('metrica/plugins/chartjs/chart.min.js') }}"></script>
<script src="{{ URL::asset('metrica/plugins/chartjs/roundedBar.min.js') }}"></script>
<script src="{{ URL::asset('metrica/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ URL::asset('metrica/plugins/jvectormap/jquery-jvectormap-us-aea-en.js') }}"></script>
<script src="{{ URL::asset('metrica/assets/pages/jquery.ecommerce_dashboard.init.js') }}"></script> 
@endsection
