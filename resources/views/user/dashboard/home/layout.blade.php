<!DOCTYPE html>
<html>

<head>
    @include('user.dashboard.components.header')

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <a href="index2.html" class="logo">
                <span class="logo-mini"><b>A</b>LT</span>
                <span class="logo-lg"><b>Admin</b>LTE</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            @include('user.dashboard.components.navbar')

        </header>

        @include('user.dashboard.components.sidebar')

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content-header')
            </section>


            <section class="content">
                @yield('contents')
            </section>

        </div>
        @include('user.dashboard.components.footer')

        immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>

    @include('user.dashboard.components.script')
    @yield('scripts')

</body>

</html>
