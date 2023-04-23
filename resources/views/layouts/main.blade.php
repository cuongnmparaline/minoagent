@include('layouts.header')

<body>
<div class="container-xxl position-relative bg-white d-flex p-0">
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    @include('layouts.sidebar')
    <div class="content">

        @include('layouts.nav')

        @yield('content')
    </div>
</div>

<!-- JavaScript Libraries -->
@include('layouts.load_js')
</body>

</html>
