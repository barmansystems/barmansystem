<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <!-- LOGO -->
    <div class="logo-box">
        <a href="javascript:void(0)" class="logo logo-dark text-center">
            <span class="logo-sm">
                <img src="/assets/images/img/logo-sm-barman-white.png" alt="" height="24">
                <!-- <span class="logo-lg-text-light">Minton</span> -->
            </span>
            <span class="logo-lg">
                <img src="/assets/images/img/logo-barman.png" alt="" height="20">
                <!-- <span class="logo-lg-text-light">M</span> -->
            </span>
        </a>
        <a href="javascript:void(0)" class="logo logo-light text-center">
            <span class="logo-sm">
                <img src="/assets/images/img/logo-sm-barman-white.png" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="/assets/images/img/logo-barman.png" alt="" height="54">
            </span>
        </a>
    </div>

    <div class="h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            <img src="/assets/images/users/avatar.png" alt="user-img" title="Mat Helme"
                 class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="#" class="text-reset dropdown-toggle h5 mt-2 mb-1 d-block"
                   data-bs-toggle="dropdown">Nik Patel</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-reset">Admin Head</p>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li class="menu-title">پنل مدیریت</li>
                {{-- Dashboard --}}
                @canany(['users-list','roles-list','tasks-list','notes-list','leaves-list','reports-list','file-manager'])
                    @php $active_side = active_sidebar(['panel','users','users/create','users/{user}/edit','roles','roles/create','roles/{role}/edit', 'tasks','tasks/create','tasks/{task}/edit', 'tasks/{task}', 'notes','notes/create','notes/{note}/edit','leaves','leaves/create','leaves/{leave}/edit','reports','reports/create','reports/{report}/edit','file-manager']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#dashboard" data-bs-toggle="collapse" aria-expanded="false" aria-controls="dashboard">
                            <i class="ri-dashboard-line"></i>
                            <span> داشبورد </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="dashboard">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="/panel">پنل</a>
                                </li>
                                @can('users-list')
                                    @php $active_item = active_sidebar(['users','users/create','users/{user}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('users.index') }}" class="{{ $active_item ? 'active' : '' }}">کاربران</a>
                                    </li>
                                @endcan
                                @can('roles-list')
                                    @php $active_item = active_sidebar(['roles','roles/create','roles/{role}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('roles.index') }}" {{ $active_item ? 'active' : '' }}>نقش
                                            ها</a>
                                    </li>
                                @endcan
                                @can('tasks-list')
                                    @php $active_item = active_sidebar(['tasks','tasks/create','tasks/{task}/edit', 'tasks/{task}']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('tasks.index') }}" {{ $active_item ? 'active' : '' }}>وظایف</a>
                                    </li>
                                @endcan
                                @can('notes-list')
                                    <li>
                                        <a href="{{ route('notes.index') }}">یادداشت ها</a>
                                    </li>
                                @endcan
                                @can('leaves-list')
                                    @php $active_item = active_sidebar(['leaves','leaves/create','leaves/{leave}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('leaves.index') }}" {{ $active_item ? 'active' : '' }}>درخواست
                                            مرخصی</a>
                                    </li>
                                @endcan
                                @can('reports-list')
                                    @php $active_item = active_sidebar(['reports','reports/create','reports/{report}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('reports.index') }}" {{ $active_item ? 'active' : '' }}>گزارشات
                                            روزانه</a>
                                    </li>
                                @endcan
                                @can('file-manager')
                                    @php $active_item = active_sidebar(['file-manager']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('file-manager.index') }}" {{ $active_item ? 'active' : '' }}>مدیریت
                                            فایل</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- Products --}}
                @canany(['products-list','price-history','coupons-list'])
                    @php $active_side = active_sidebar(['products','products/create','products/{product}/edit','search/products','coupons','coupons/create','coupons/{coupon}/edit','price-history','categories','categories/create','categories/{category}/edit']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#products" data-bs-toggle="collapse" aria-expanded="false" aria-controls="products">
                            <i class="ri-list-unordered"></i>
                            <span> محصولات </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="products">
                            <ul class="nav-second-level">
                                @can('products-list')
                                    @php $active_item = active_sidebar(['products','products/create','products/{product}/edit','search/products']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('products.index') }}" {{ $active_item ? 'active' : '' }}>لیست
                                            محصولات</a>
                                    </li>
                                @endcan
                                @can('categories-list')
                                    @php $active_item = active_sidebar(['categories','categories/create','categories/{category}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('categories.index') }}" {{ $active_item ? 'active' : '' }}>دسته بندی ها</a>
                                    </li>
                                @endcan
                                @can('parso-products')
                                    @php $active_item = active_sidebar(['parso-products']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('parso.index') }}" {{ $active_item ? 'active' : '' }}>محصولات
                                            بارمان سیستم</a>
                                    </li>
                                @endcan
                                @can('price-history')
                                    <li>
                                        <a href="{{ route('price-history') }}">تاریخچه قیمت</a>
                                    </li>
                                @endcan
                                @can('coupons-list')
                                    @php $active_item = active_sidebar(['coupons','coupons/create','coupons/{coupon}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('coupons.index') }}" {{ $active_item ? 'active' : '' }}>کد
                                            تخفیف</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- Orders --}}
                @canany(['invoices-list','buy-orders-list','sale-reports-list','price-requests-list'])
                    @php $active_side = active_sidebar(['invoices','invoices/create','invoices/{invoice}/edit','search/invoices','sale-reports','sale-reports/create','sale-reports/{sale_report}/edit','search/sale-reports','invoice-action/{invoice}','orders-status/{invoice}','price-requests','price-requests/create','price-requests/{price_request}/edit','price-requests/{price_request}','buy-orders','buy-orders/create','buy-orders/{buy_order}/edit','buy-orders/{buy_order}','search/buy-orders']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#orders" data-bs-toggle="collapse" aria-expanded="false" aria-controls="orders">
                            <i class="ri-shopping-cart-line"></i>
                            <span> سفارشات </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="orders">
                            <ul class="nav-second-level">
                                @can('invoices-list')
                                    @php $active_item = active_sidebar(['invoices','invoices/create','invoices/{invoice}/edit','search/invoices','invoice-action/{invoice}','orders-status/{invoice}']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('invoices.index') }}" {{ $active_item ? 'active' : '' }}>سفارشات
                                            فروش</a>
                                    </li>
                                @endcan
                                @can('buy-orders-list')
                                    @php $active_item = active_sidebar(['buy-orders','buy-orders/create','buy-orders/{buy_order}/edit','buy-orders/{buy_order}','search/buy-orders']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('buy-orders.index') }}" {{ $active_item ? 'active' : '' }}>سفارشات
                                            خرید</a>
                                    </li>
                                @endcan
                                @can('sale-reports-list')
                                    @php $active_item = active_sidebar(['sale-reports','sale-reports/create','sale-reports/{sale_report}/edit','search/sale-reports']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('sale-reports.index') }}" {{ $active_item ? 'active' : '' }}>گزارشات
                                            فروش</a>
                                    </li>
                                @endcan
                                @can('price-requests-list')
                                    @php $active_item = active_sidebar(['price-requests','price-requests/create','price-requests/{price_request}/edit','price-requests/{price_request}']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('price-requests.index') }}" {{ $active_item ? 'active' : '' }}>درخواست
                                            قیمت</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- Packets --}}
                @can('packets-list')
                    @php $active_side = active_sidebar(['packets','packets/create','packets/{packet}/edit','search/packets']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#packets" data-bs-toggle="collapse" aria-expanded="false" aria-controls="packets">
                            <i class="ri-truck-line"></i>
                            <span> بسته های ارسالی </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="packets">
                            <ul class="nav-second-level">
                                @php $active_item = active_sidebar(['packets','packets/create','packets/{packet}/edit','search/packets']); @endphp
                                <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('packets.index') }}" {{ $active_item ? 'active' : '' }}>لیست بسته
                                        ها</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                {{-- Customers --}}
                @can('customers-list')
                    @php $active_side = active_sidebar(['customers','customers/create','customers/{customer}/edit','search/customers']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#customers" data-bs-toggle="collapse" aria-expanded="false" aria-controls="customers">
                            <i class="ri-group-line"></i>
                            <span> مشتریان </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="customers">
                            <ul class="nav-second-level">
                                @php $active_item = active_sidebar(['customers','customers/create','customers/{customer}/edit','search/customers']); @endphp
                                <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('customers.index') }}" {{ $active_item ? 'active' : '' }}>لیست
                                        مشتریان</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                {{-- Shops --}}
                @can('shops')
                    @php $active_side = active_sidebar(['off-site-products/{website}','off-site-product/{off_site_product}','off-site-product-create/{website}','off-site-products/{off_site_product}/edit']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#shops" data-bs-toggle="collapse" aria-expanded="false" aria-controls="shops">
                            <i class="ri-store-3-line"></i>
                            <span> فروشگاه ها </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="shops">
                            @php $active_item = active_sidebar(['off-site-products/{website}','off-site-product/{off_site_product}','off-site-product-create/{website}','off-site-products/{off_site_product}/edit']); @endphp
                            <ul class="nav-second-level">
                                <li class="{{ $active_item && request()->website == 'torob' ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('off-site-products.index', 'torob') }}" {{ $active_item && request()->website == 'torob' ? 'active' : '' }}>ترب</a>
                                </li>
                                <li class="{{ $active_item && request()->website == 'emalls' ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('off-site-products.index', 'emalls') }}" {{ $active_item && request()->website == 'emalls' ? 'active' : '' }}>ایمالز</a>
                                </li>
                                <li class="{{ $active_item && request()->website == 'digikala' ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('off-site-products.index', 'digikala') }}" {{ $active_item && request()->website == 'digikala' ? 'active' : '' }}>دیجیکالا</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                {{-- Warehouse --}}
                @canany(['guarantees-list','warehouses-list'])
                    @php $active_side = active_sidebar(['inventory','inventory/create','inventory/{inventory}/edit','search/inventory','inventory-reports','inventory-reports/create','inventory-reports/{inventory_report}/edit','warehouses','warehouses/create','warehouses/{warehouse}/edit','search/inventory-reports','guarantees','guarantees/create','guarantees/{guarantee}/edit','categories','categories/create','categories/{category}/edit']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#warehouse" data-bs-toggle="collapse" aria-expanded="false" aria-controls="warehouse">
                            <i class="ri-home-5-line"></i>
                            <span> انبار </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="warehouse">
                            <ul class="nav-second-level">
                                @can('guarantees-list')
                                    @php $active_item = active_sidebar(['guarantees','guarantees/create','guarantees/{guarantee}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('guarantees.index') }}" {{ $active_item ? 'active' : '' }}>گارانتی
                                            ها</a>
                                    </li>
                                @endcan
                                @can('warehouses-list')
                                    @php $active_item = active_sidebar(['warehouses','warehouses/create','warehouses/{warehouse}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('warehouses.index') }}" {{ $active_item ? 'active' : '' }}>انبار
                                            ها</a>
                                    </li>
                                @endcan
                                @can('categories-list')
                                    @php $active_item = active_sidebar(['categories','categories/create','categories/{category}/edit']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('categories.index') }}" {{ $active_item ? 'active' : '' }}>دسته بندی ها</a>
                                    </li>
                                @endcan
                                @if(request()->warehouse_id)
                                    @can('inventory-list')
                                        @php $active_item = active_sidebar(['inventory','inventory/create','inventory/{inventory}/edit','search/inventory']); @endphp
                                        <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                            <a href="{{ route('inventory.index', ['warehouse_id' => request()->warehouse_id]) }}" {{ $active_item ? 'active' : '' }}>کالاها</a>
                                        </li>
                                    @endcan
                                    @can('input-reports-list')
                                        @php $active_item = active_sidebar(['inventory-reports','inventory-reports/create','inventory-reports/{inventory_report}/edit','search/inventory-reports']) && request()->type == 'input'; @endphp
                                        <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                            <a href="{{ route('inventory-reports.index', ['type' => 'input', 'warehouse_id' => request()->warehouse_id]) }}" {{ $active_item ? 'active' : '' }}>ورود</a>
                                        </li>
                                    @endcan
                                    @can('output-reports-list')
                                        @php $active_item = active_sidebar(['inventory-reports','inventory-reports/create','inventory-reports/{inventory_report}/edit','search/inventory-reports']) && request()->type == 'output'; @endphp
                                        <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                            <a href="{{ route('inventory-reports.index', ['type' => 'output', 'warehouse_id' => request()->warehouse_id]) }}" {{ $active_item ? 'active' : '' }}>خروج</a>
                                        </li>
                                    @endcan
                                @endif
                            </ul>
                        </div>
                    </li>
                @endcanany

                {{-- Tickets & Supports --}}
                @canany(['tickets-list','sms-histories'])
                    @php $active_side = active_sidebar(['tickets','tickets/create','tickets/{ticket}/edit','search/tickets','sms-histories','sms-histories/{sms_history}']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#tickets" data-bs-toggle="collapse" aria-expanded="false" aria-controls="tickets">
                            <i class="ri-message-2-line"></i>
                            <span> پشتیبانی و تیکت </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="tickets">
                            <ul class="nav-second-level">
                                @can('tickets-list')
                                    @php $active_item = active_sidebar(['tickets','tickets/create','tickets/{ticket}/edit','search/tickets']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('tickets.index') }}" {{ $active_item ? 'active' : '' }}>تیکت
                                            ها</a>
                                    </li>
                                @endcan
                                @can('sms-histories')
                                    @php $active_item = active_sidebar(['sms-histories','sms-histories/{sms_history}']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('sms-histories.index') }}" {{ $active_item ? 'active' : '' }}>پیام
                                            های ارسال شده</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['indicator'])
                    @php $active_side = active_sidebar(['indicator','indicator/create','indicator/{indicator}/edit']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#indicators" data-bs-toggle="collapse" aria-expanded="false" aria-controls="tickets">
                            <i class="ri-mail-line"></i>
                            <span> نامه نگاری </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="indicators">
                            <ul class="nav-second-level">
                                @can('indicator')
                                    @php $active_item = active_sidebar(['/indicator/inbox']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('indicator.inbox') }}" {{ $active_item ? 'active' : '' }}>
                                            صندوق نامه ها</a>
                                    </li>

                                    @php $active_item = active_sidebar(['indicator','/indicator/{indicator}/edit','/indicator/create']); @endphp
                                    <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                        <a href="{{ route('indicator.index') }}" {{ $active_item ? 'active' : '' }}>نامه
                                            های ایجاد شده</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['order-payment-list'])
                    @php $active_side = active_sidebar(['payments_order','payments_order/create','payments_order/{payments_order}/edit']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#payments_order" data-bs-toggle="collapse" aria-expanded="false"
                           aria-controls="tickets">
                            <i class="ri-calculator-line"></i>
                            <span> امور مالی </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="payments_order">
                            <ul class="nav-second-level">
                                {{--                            @can('indicator')--}}
                                @php $active_item = active_sidebar(['payments_order','payments_order/{payments_order}/edit','payments_order/create']); @endphp
                                <li class="{{ $active_item && request()->type=='payments' ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('payments_order.index',['type'=>'payments']) }}" {{ $active_item  && request()->type=='payments' ? 'active' : '' }}>
                                        دستور پرداخت
                                    </a>
                                </li>
                                <li class="{{ $active_item && request()->type=='receive' ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('payments_order.index',['type'=>'receive']) }}" {{ $active_item && request()->type=='receive' ? 'active' : '' }}>
                                        دستور دریافت
                                    </a>
                                </li>
                                {{--                            @endcan--}}
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['purchase-engineering'])
                    @php $active_side = active_sidebar(['purchase-engineering','purchases/status/{id}']); @endphp
                    <li class="{{ $active_side ? 'menuitem-active' : '' }}">
                        <a href="#purchases" data-bs-toggle="collapse" aria-expanded="false"
                           aria-controls="tickets">
                            <i class="ri-shopping-cart-fill"></i>
                            <span> مهندسی خرید </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ $active_side ? 'show' : '' }}" id="purchases">
                            <ul class="nav-second-level">
                                {{--                            @can('indicator')--}}
                                @php $active_item = active_sidebar(['purchases','purchases/status/{id}']); @endphp
                                <li class="{{ $active_item ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('purchase.index') }}" {{ $active_item  ? 'active' : '' }}>
                                        لیست نیاز ها
                                    </a>
                                </li>

                                {{--                            @endcan--}}
                            </ul>
                        </div>
                    </li>
                @endcanany
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
