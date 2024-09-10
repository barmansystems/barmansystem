@include('panel.layouts.head')

<div id="wrapper">
    @includeWhen(!isset($topbar), 'panel.layouts.topbar')
    @includeWhen(!isset($left_sidebar), 'panel.layouts.left-sidebar')
    <div class="content-page">
        @yield('content')
        @includeWhen(!isset($footer), 'panel.layouts.footer')
    </div>
</div>
{{--@includeWhen(!isset($right_sidebar), 'panel.layouts.right-sidebar')--}}

@include('panel.layouts.scripts')
@include('sweet::alert')
