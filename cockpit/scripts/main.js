$(document).ready(function() {

// Mask input fields
    $(".time").inputmask({
        "mask": "99:99"
    });

// custom scrollbar
    $("#sidebar").niceScroll({styler:"fb",cursorcolor:"#e60000", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});

    // $("html").niceScroll({styler:"fb",cursorcolor:"#e60000", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', spacebarenabled:false,  cursorborder: '', zindex: '1000'});


	 $('a').each(function() {
	    if ($(this).prop('href') === window.location.href.split("&")[0]) {
	      $(this).addClass('active');
	    }
	  });


	// Scroll To Top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });

    $('.scrollup').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

	$('[data-toggle="tooltip"]').tooltip({
		delay: { "show": 10, "hide": 20 },
		container :'body'
	});

	$('#menu').treeview({
		animated: 'fast',
		collapsed: false,
		persist: 'cookie'
	});

    $('.datepicker').datepicker({
        'dateFormat' : 'dd-mm-yy'
    });

    if($('table.datatable').length > 0)
    {
        $('table.datatable').each(function() {

            var $this = $(this);

            $this.dataTable({
                'bPaginate': true,
                'bLengthChange': true,
                'bFilter': true,
                'bSort': true,
                'bInfo': true,
                'bAutoWidth': false,
                /* Disable initial sort */
                'aaSorting': [],
                'aoColumnDefs' : [
                    { 'sType': 'nldate', 'aTargets': [ 'nldate' ] },
                    { 'bSortable': false, aTargets: [ 'no_sort' ] }
                ],
                'oLanguage': {
                    'sProcessing': 'Bezig...',
                    'sLengthMenu': '_MENU_ resultaten weergeven',
                    'sZeroRecords': 'Geen resultaten gevonden',
                    'sInfo': '_START_ tot _END_ van _TOTAL_ resultaten',
                    'sInfoEmpty': 'Geen resultaten om weer te geven',
                    'sInfoFiltered': ' (gefilterd uit _MAX_ resultaten)',
                    'sInfoPostFix': '',
                    'sSearch': 'Zoeken:',
                    'sEmptyTable': 'Geen resultaten aanwezig in de tabel',
                    'sInfoThousands': '.',
                    'sLoadingRecords': 'Een moment geduld aub - bezig met laden...',
                    'oPaginate': {
                        'sFirst': 'Eerste',
                        'sLast': 'Laatste',
                        'sNext': 'Volgende',
                        'sPrevious': 'Vorige'
                    }
                }
            });
        });
    }
});
