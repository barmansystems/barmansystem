<!-- Topbar Start -->
<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-end mb-0">
            <li class="d-none d-md-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" id="light-dark-mode" href="#">
                    <i class="fe-moon noti-icon"></i>
                </a>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#"
                   role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fe-bell noti-icon"></i>
                    <span
                        class="badge text-danger bg-light rounded-circle noti-icon-badge {{ auth()->user()->unreadNotifications()->count() ? '' : 'd-none' }}"
                        id="notif_count">{{ auth()->user()->unreadNotifications()->count() }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-lg">

                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0">
                            <span class="float-end">
                                <a href="{{ route('notifications.read') }}" class="text-dark">
                                    <small>خواندن همه</small>
                                </a>
                            </span>اعلانات
                        </h5>
                    </div>

                    <div class="noti-scroll" data-simplebar id="notif_sec">
                        @foreach(auth()->user()->unreadNotifications as $notification)
                            <!-- item-->
                            <a href="{{ route('notifications.read', $notification->id) }}"
                               class="dropdown-item notify-item active" style="white-space: nowrap">
                                <div class="notify-icon bg-soft-primary text-primary">
                                    <i class="mdi mdi-comment-account-outline"></i>
                                </div>
                                <p class="notify-details"
                                   style="overflow: unset; text-overflow: unset; white-space: wrap">{{ $notification->data['message'] }}
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->ago() }}</small>
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown"
                   href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img
                        src="{{auth()->user()->gender == 'male'?'/assets/images/users/avatar.png':'/assets/images/users/girl.png'}}"
                        alt="user-image" class="rounded-circle">
                    <span class="pro-user-name ms-1">
                                    {{ auth()->user()->fullName() }}
                        <i class="mdi mdi-chevron-down"></i>
                                </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">خوش آمدید !</h6>
                    </div>

                    <!-- item-->
                    <a href="{{ route('users.edit', auth()->id()) }}" class="dropdown-item notify-item">
                        <i class="ri-account-circle-line"></i>
                        <span>ویرایش پروفایل</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <button class="dropdown-item notify-item" form="logout_form">
                        <i class="ri-logout-box-line"></i>
                        <span>خروج</span>
                        <form action="{{ route('logout') }}" method="post" id="logout_form">
                            @csrf
                        </form>
                    </button>

                </div>
            </li>

            {{--            <li class="dropdown notification-list">--}}
            {{--                <a class="nav-link waves-effect waves-light" data-bs-toggle="offcanvas" href="#theme-settings-offcanvas" >--}}
            {{--                    <i class="fe-settings noti-icon"></i>--}}
            {{--                </a>--}}
            {{--            </li>--}}

        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="index.html" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <img src="/assets/images/logo-sm-dark.png" alt="" height="24">
                    <!-- <span class="logo-lg-text-light">Minton</span> -->
                </span>
                <span class="logo-lg">
                    <img src="/assets/images/logo-dark.png" alt="" height="20">
                    <!-- <span class="logo-lg-text-light">M</span> -->
                </span>
            </a>
            <a href="index.html" class="logo logo-light text-center">
                <span class="logo-sm">
                    <img src="/assets/images/logo-sm.png" alt="" height="24">
                </span>
                <span class="logo-lg">
                    <img src="/assets/images/logo-light.png" alt="" height="20">
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Topbar End -->
