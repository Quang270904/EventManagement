<nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

            <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell-o"></i>
                    <span id="notification-count" class="label label-warning">0</span>
                    <!-- Hiển thị số lượng thông báo -->
                </a>
                <ul id="notifications-menu" class="dropdown-menu">
                    <li class="header">You have 0 notifications</li>
                    <li>
                        <ul class="menu">
                            <!-- Các thông báo sẽ được thêm vào đây -->
                        </ul>
                    </li>
                    <li class="footer"><a href="#">View all</a></li>
                </ul>
            </li>

            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="hidden-xs">{{ $role->role_name }}</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="user-header">
                        <p>{{ $userDetail->full_name }}</p>
                    </li>
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div class="pull-right">
                            <a href={{ route('auth.logout') }} class="btn btn-default btn-flat">Sign out</a>
                        </div>
                    </li>
                </ul>
            </li>

            <li class="dropdown language-dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                    aria-expanded="false">
                    Language <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach (config('localization.locales') as $locale)
                        <li><a href="{{ route('localization', $locale) }}">{{ __($locale) }}</a></li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>
</nav>

@push('scripts')
<script>
    $(document).ready(function() {
        console.log("Document loaded, making AJAX request...");

        $.ajax({
            url: '{{ route('event_manager.notification') }}',
            method: 'GET',
            success: function(response) {
                console.log("AJAX Success:", response); 
                var notifications = response.notifications;
                var notificationsMenu = $('#notifications-menu ul');
                var notificationCount = notifications.length;

                $('#notification-count').text(notificationCount);

                var headerText = 'You have ' + notificationCount + ' notifications';
                $('#notifications-menu .header').text(headerText);

                notificationsMenu.empty();

                if (notificationCount === 0) {
                    notificationsMenu.append('<li><a href="#">No new notifications</a></li>');
                }

                notifications.forEach(function(notification) {
                    var date = new Date(notification.created_at);
                    var formattedDate = date.toLocaleDateString('en-GB'); 

                    notificationsMenu.append(`
                        <li>
                            <a href="#">
                                <i class="fa fa-bell text-aqua"></i> 
                                ${notification.message}
                                <span class="notification-time">${formattedDate}</span> <!-- Thời gian ở góc phải -->
                            </a>
                        </li>
                    `);
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error); 
            }
        });
    });
</script>
@endpush

