<!DOCTYPE html>
<html>

<head>
    @include('event_managers.dashboard.components.header')

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>


    <script>
        Pusher.logToConsole = true;
        var pusher = new Pusher('c9e4562f8a9e0439bde1', {
            cluster: 'ap2'
        });


        var channel = pusher.subscribe('my-channel');
        channel.bind('form-summitted', function(data) {
            if (data && data.message && data.event_name && data.registration_time) {
                toastr.success(
                    data.message, {
                        timeOut: 0,
                        extendedTimeOut: 0,
                    }
                );
            } else {
                console.error('Invalid data structure received:', data);
            }
        });
    </script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <a href="index2.html" class="logo">
                <span class="logo-mini"><b>A</b>LT</span>
                <span class="logo-lg"><b>Admin</b>LTE</span>
            </a>
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
    @stack('scripts')

</body>

</html>
