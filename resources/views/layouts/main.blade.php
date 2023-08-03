@include('layouts.header')

<body>
<div class="container-xxl position-relative bg-white d-flex p-0">
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
