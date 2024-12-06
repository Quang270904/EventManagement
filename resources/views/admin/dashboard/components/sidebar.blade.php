<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i
                            class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="active treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="active">
                        <a href="{{ route('admin.user') }}"><i class="fa fa-circle-o"></i>
                           {{__('sidebar.user')}}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.event') }}"><i class="fa fa-circle-o"></i>
                            {{__('sidebar.event')}}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ticket') }}"><i class="fa fa-circle-o"></i>
                            {{__('sidebar.ticket')}}
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
</aside>
