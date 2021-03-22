<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Sempoa ERP</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Sempoa ERP" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @include('layouts.backend.metrica.header-script')

    </head>
    <body>
        @include('layouts.backend.metrica.sideleft-navbar')

        @include('layouts.backend.metrica.header-navbar')

        <div class="page-wrapper">
            <div class="page-content-tab">

                @yield('content')

                <footer class="footer text-center text-sm-left">
                    &copy; 2020 Sempoa ERP <span class="text-muted d-none d-sm-inline-block float-right">Developers</span>
                </footer><!--end footer-->
            </div>
        </div>

        @include('layouts.backend.metrica.footer-script')
    </body>
</html>
