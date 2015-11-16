<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{!! Auth::user()->gravatar() !!}" class="img-circle" alt="{!! Auth::user()->name !!}"/>
            </div>
            <div class="pull-left info">
                <p>Hello, {!! Auth::user()->name !!}</p>

            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        {!! Menu::get('admin-menu') !!}
    </section>
    <!-- /.sidebar -->
</aside>
