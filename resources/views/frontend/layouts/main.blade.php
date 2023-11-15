<!DOCTYPE html>
<html lang="en">

<head>
    <!--====== Required meta tags ======-->
    @include('frontend.layouts.header')
</head>

<body>
    <section class="navbar-area navbar-nine">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="">
                            <img src="{{ asset('/images/white-logo.svg') }}" alt="Logo"/>
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNine"
                                aria-controls="navbarNine" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                            <span class="toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse sub-menu-bar" id="navbarNine">
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item">
                                    <a class="page-scroll active" href="#hero-area">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="#services">Services</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="#fees">Fees</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="#testimonial">Rating</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="#blog">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="#faq">FAQ</a>
                                </li>
                            </ul>
                        </div>

                        <div class="navbar-btn d-none d-lg-inline-block">
                            <a style="font-size: 18px;
        background: white;
        color: #FD8126;
        font-weight: bold;" class="menu-bar" href="{{ route('customer.login') }}">Login <i class="lni lni-backward"></i></a>
                        </div>
                    </nav>
                    <!-- navbar -->
                </div>
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </section>
@yield('content')


<!-- Start Footer Area -->
@include('frontend.layouts.footer')
<!--/ End Footer Area -->

<div class="made-in-ayroui mt-4">
    <a href="https://t.me/catherine1677" target="_blank" rel="nofollow">
        <img style="width: 220px" src="{{ asset('/images/telecalin.svg') }}">
    </a>
</div>

<a href="#" class="scroll-top btn-hover">
    <i class="lni lni-chevron-up"></i>
</a>

<!--====== js ======-->
@include('frontend.layouts.load_js')
</body>

</html>
