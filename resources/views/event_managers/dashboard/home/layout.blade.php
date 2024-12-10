<!DOCTYPE html>
<html>

<head>
    @include('event_managers.dashboard.components.header')

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <a href="index2.html" class="logo">
                <span class="logo-mini"><b>A</b>LT</span>
                <span class="logo-lg"><b>Admin</b>LTE</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            @include('event_managers.dashboard.components.navbar')

        </header>

        @include('event_managers.dashboard.components.sidebar')

        <div class="content-wrapper">

            <section class="content">
                @yield('contents')
            </section>

        </div>
        @include('event_managers.dashboard.components.footer')

        immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>

    @include('event_managers.dashboard.components.script')
    @yield('scripts')

</body>

</html>
