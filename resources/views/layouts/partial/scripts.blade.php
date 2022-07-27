{{--<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>--}}
{{--<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>--}}
{{--<script src="/packages/bootstrap-select/bootstrap-select.min.js"></script>--}}
{{--<script src="/packages/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>--}}
{{--@yield('scripts')--}}
{{--<script>--}}
{{--    $(function () {--}}
{{--        $('select').selectpicker({--}}
{{--            'liveSearch': true,--}}
{{--        });--}}
{{--        $('.js-date').datepicker({--}}
{{--            format: 'yyyy-mm-dd',--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}

<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/jquery/jquery.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/simplebar/simplebar.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/node-waves/waves.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/select2/js/select2.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<!-- Responsive examples -->
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/js/app.js"></script>

<!-- Required datatable js -->
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/jszip/jszip.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

<!-- Datatable init js -->
<script src="{{ env('PUBLIC_PATH') }}/skote/assets/js/pages/datatables.init.js"></script>
        
<script>
    $(".form-select").select2();
    
    $(".form-select-without-search").select2({
         minimumResultsForSearch: -1,
        placeholder: 'Select option',
    });

    $(".form-select-inventorization").select2({
        minimumResultsForSearch: -1,
        placeholder: 'Inventorization status',
    });
    
    $(".form-select-invoice").select2({
        minimumResultsForSearch: -1,
        placeholder: 'Invoice status',
    });
</script>

<script>
    /*
     <a href="posts/2" data-method="delete"> <---- We want to send an HTTP DELETE request
     - Or, request confirmation in the process -
     <a href="posts/2" data-method="delete" data-confirm="Are you sure?">

     Add this to your view:
     <script>
     window.csrfToken = '<?php echo csrf_token(); ?>';
*/

    (function() {

        var laravel = {
            initialize: function() {
                this.registerEvents();
            },

            registerEvents: function() {
                $('body').on('click', 'a[data-method]', this.handleMethod);
            },

            handleMethod: function(e) {
                e.preventDefault();
                e.stopPropagation();
                var link = $(this);
// link.prop('disabled', true);
                var httpMethod = link.data('method').toUpperCase();
                var form;

                // If the data-method attribute is not PUT or DELETE,
                // then we don't know what to do. Just ignore.
                if ( $.inArray(httpMethod, ['POST', 'PUT', 'DELETE']) === - 1 ) {
                    return;
                }

                // Allow user to optionally provide data-confirm="Are you sure?"
                if ( link.data('confirm') ) {
                    if ( ! laravel.verifyConfirm(link) ) {
                        return false;
                    }
                }

                form = laravel.createForm(link, httpMethod);
                form.submit();
            },

            verifyConfirm: function(link) {
                return confirm(link.data('confirm'));
            },

            createForm: function(link, httpMethod) {
                var form =
                    $('<form>', {
                        'method': 'POST',
                        'action': link.attr('href')
                    });

                var token =
                    $('<input>', {
                        'name': '_token',
                        'type': 'hidden',
                        'value': window.csrfToken
                    });

                var hiddenInput;
                if (httpMethod != 'POST') {
                    hiddenInput =
                        $('<input>', {
                            'name': '_method',
                            'type': 'hidden',
                            'value': link.data('method')
                        });
                }


                return form.append(token, hiddenInput)
                    .appendTo('body');
            }
        };

        laravel.initialize();

    })();
</script>

<script>
    $(function () {
        $('.js-disable').click(function () {
            let str = '<div class="spinner-border text-primary m-1" role="status"><span class="sr-only"></span></div>';
            $(this).hide();
            $(str).insertAfter(this);
            setTimeout(function(){
                this.disabled = true;
                $(this).closest('form').submit();
                $(".spinner-border").remove();
                $('.js-disable').show();
            }, 1000);
        });
        var $radios = $('input[name=type]').change(function () {
            var value = $radios.filter(':checked').val();
            if (value == 'private') {
                $('.company_data').hide();
            }else if(value == 'business') {
                $('.company_data').show();
            }
        });
        
        $("#tool-settings").on("click", function(){
            localStorage.removeItem('activeTab');// Show first tab by default if user clicks on tool settings. Other wise show different if user reloads the same pag.
        });
    });
    function showMessage(message, type) {
        var color = (type == 'success') ? '#34c38f' : '#d9534f';
        var msgHtml = '<div class="alert alert-success showMessage" style="position: absolute; top: 3px; left: 44%; background-color: ' + color + '; color: #fff; text-align: center; border: 0; z-index: 99999; border-right: 10px; min-height: 30px; display: flex; justify-content: center; align-content: center; flex-direction: column; width: 20%;"> ' + message + ' </div>';
        $(".page-content").prepend(msgHtml);
        setTimeout(function() { $('.showMessage').remove(); }, 5000);
    }
</script>

<script src="//code.tidio.co/oj9oclz7qlhcn1rmkxncojvcne8nfl4g.js" async></script>
