<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{asset('js/perfect-scrollbar.jquery.min.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{asset('js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{asset('js/sidebarmenu.js')}}"></script>
    <!--stickey kit -->

    <script src="{{asset('js/raphael-min.js')}}"></script>
    <script src="{{asset('js/morris.min.js')}}"></script>


    <script src="{{asset('js/sticky-kit.min.js')}}"></script>
    <script src="{{asset('js/jquery.sparkline.min.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{asset('js/custom.min.js')}}"></script>
    <!-- This is data table -->
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.responsive.min.js')}}"></script>
    {{-- <script src="{{asset('js/dashboard1.js')}}"></script> --}}
    <script type="text/javascript" src="{{asset('js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/daterangepicker.js')}}"></script>
    @if(isset($scriptfile))
        <script src="{{asset($scriptfile)}}"></script>
    @endif
    <!-- start - This is for export functionality only -->
    <script src="{{asset('js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('js/buttons.flash.min.js')}}"></script>
    <script src="{{asset('js/jszip.min.js')}}"></script>
    <script src="{{asset('js/pdfmake.min.js')}}"></script>
    <script src="{{asset('js/vfs_fonts.js')}}"></script>
    <script src="{{asset('js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('js/buttons.print.min.js')}}"></script>
    <script src="{{asset('js/notify.min.js')}}"></script>
    <script src="{{asset('js/jquery.tagsinput.min.js')}}"></script>
    <script src="{{asset('js/dropdowntree.js')}}"></script>
    <script src="{{asset('js/multi.min.js')}}"></script>
    <script src="{{asset('js/messagebox.min.js')}}"></script>
    <script>
        // $(document).ready(function(){
        //     $('.mini-sidebar li').on({
        //             mouseenter: function () {
        //                 $('.mini-sidebar .sidebar-nav a').attr('style','font-size: 1.09375rem');
        //                 console.log('hover');
        //             },
        //             mouseleave: function () {
        //                 $('.mini-sidebar .sidebar-nav a').attr('style','font-size: 0px');
        //                 console.log('leave');
        //             }
        //         });
        // });

    </script>
