    <!-- js placed at the end of the document so the pages load faster -->
    <script class="include" type="text/javascript" src="/cockpit/scripts/jquery.dcjqaccordion.2.7.js"></script>
    <script src="/cockpit/scripts/jquery.nicescroll.js" type="text/javascript"></script>

    <!-- Spinnerz scripting -->
    <script src="/cockpit/scripts/jquery.treeview.js" type="text/javascript"></script>
    <script src="/cockpit/scripts/jquery.cookie.js" type="text/javascript"></script>
    <script src="/cockpit/scripts/jquery.imgareaselect.min.js" type="text/javascript"></script>
    <script src="/cockpit/scripts/jquery.datatables.min.js" type="text/javascript"></script>
    <script src="/cockpit/scripts/main.js" type="text/javascript"></script>
    <script type="text/javascript" src="/cockpit/scripts/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="/cockpit/node_modules/inputmask/dist/jquery.inputmask.min.js"></script>



    <!--common script for all pages-->
    <script src="/cockpit/scripts/common-scripts.js"></script>

    <!--script for this page-->

    <script type="application/javascript">
        $(document).ready(function() {
            $("#date-popover").popover({
                html: true,
                trigger: "manual"
            });
            $("#date-popover").hide();
            $("#date-popover").click(function(e) {
                $(this).hide();
            });

        });


        function myNavFunction(id) {
            $("#date-popover").hide();
            var nav = $("#" + id).data("navigation");
            var to = $("#" + id).data("to");
            console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
        }
    </script>