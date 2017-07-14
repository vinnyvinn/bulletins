
</div><!-- /.content-wrapper -->


<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; {{ date('Y') }} {{ config('app.name') }}.</strong> All rights reserved.
</footer>


<div class="control-sidebar-bg"></div>

</div><!-- ./wrapper -->

    <!-- main app js -->
    {{--<script src="{{ asset('js/app.js') }}"></script>--}}

    <!-- jQuery 2.1.4 -->
    <script src="{{ asset('assets/admin/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ asset('assets/admin/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('assets/admin/') }}/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/admin/') }}/dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="{{ asset('assets/admin/') }}/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="{{ asset('assets/admin/') }}/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="{{ asset('assets/admin/') }}/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="{{ asset('assets/admin/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>



    @yield('page-js')



    <script>
       $('#alert-head').delay(5000).slideUp();
       new Clipboard('.clipboard');
    </script>
  </body>
</html>
