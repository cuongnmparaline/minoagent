
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary">CALIN AGENT</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="{{ asset('img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">
                    @if(\Illuminate\Support\Facades\Auth::guard('customer')->check())
                        {{ \Illuminate\Support\Facades\Auth::guard('customer')->user()->nick_name }}
                    @else
                        {{ \Illuminate\Support\Facades\Auth::guard('admin')->user()->name }}
                    @endif
                </h6>
                <span>@if(\Illuminate\Support\Facades\Auth::guard('customer')->check())
                        {{ \Illuminate\Support\Facades\Auth::guard('customer')->user()->nick_name }}
                    @else
                        {{ \Illuminate\Support\Facades\Auth::guard('admin')->user()->name }}
                    @endif</span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            @if(\Illuminate\Support\Facades\Auth::guard('admin')->check())
            <a href="{{ route('management.home') }}" class="nav-item nav-link {{ request()->is('management') ? 'active' : '' }}"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <a href="{{ route('management.account') }}" class="nav-item nav-link {{ request()->is('management/account*') ? 'active' : '' }}"><i class="fa fa-user me-2"></i>Account</a>
            <a href="{{ route('management.report') }}" class="nav-item nav-link {{ (request()->is('management/report*') && !request()->is('management/report/import')) ? 'active' : '' }}"><i class="fa fa-file me-2"></i>Report</a>
            <a href="{{ route('management.customer') }}" class="nav-item nav-link {{ request()->is('management/customer*') ? 'active' : '' }}"><i class="fa fa-business-time me-2"></i>Customer</a>
            <a href="{{ route('management.group') }}" class="nav-item nav-link {{ request()->is('management/group*') ? 'active' : '' }}"><i class="fa fa-users me-2"></i>Group</a>
            <a href="{{ route('management.history') }}" class="nav-item nav-link {{ request()->is('management/history*') ? 'active' : '' }}"><i class="fa fa-book me-2"></i>History Top Up</a>
            <a href="{{ route('management.report.import') }}" class="nav-item nav-link {{ request()->is('management/report/import') ? 'active' : '' }}"><i class="fa fa-file-excel me-2"></i>Import Excel</a>
            @endif
            @if(\Illuminate\Support\Facades\Auth::guard('customer')->check())
                    <a href="{{ route('customer.home') }}" class="nav-item nav-link {{ request()->is('customer') ? 'active' : '' }}"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                <a href="{{ route('customer.account') }}" class="nav-item nav-link {{ request()->is('customer/account*') ? 'active' : '' }}"><i class="fa fa-user me-2"></i>Account</a>
                    <a href="{{ route('customer.group') }}" class="nav-item nav-link {{ request()->is('customer/group*') ? 'active' : '' }}"><i class="fa fa-users me-2"></i>Group</a>
                    <a href="{{ route('customer.history') }}" class="nav-item nav-link {{ request()->is('customer/history*') ? 'active' : '' }}"><i class="fa fa-book me-2"></i>History Top Up</a>

            @endif
        </div>
    </nav>
</div>
