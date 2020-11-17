<!DOCTYPE html>
<html lang="en">
<head>
    @include('layout.head')
    @yield('head')
</head>
<body class="skin-default-dark fixed-layout">
    <div class="preloader" style="display: none;">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Elite admin</p>
        </div>
    </div>
    {{-- preload  --}}
    <div id="main-wrapper">
        @include('layout.header')
        @include('layout.aside')
        <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @yield('main')
                </div>
            </div>
        </div>
        </div>
        <!-- footer -->
        <footer class="footer">
            © 2020 admin panel by Hi-Face
        </footer>
        <!-- footer -->
    </div>
    {{-- @include('layout.script') --}}
    @yield('script')
</body>
</html>
