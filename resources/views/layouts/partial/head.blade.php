<link href="{{ env('PUBLIC_PATH') }}/skote/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- DataTables -->
<link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />     
<link href="{{ env('PUBLIC_PATH') }}/skote/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{ env('PUBLIC_PATH') }}/skote/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />

<!-- Icons Css -->
<link href="/skote/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->

<!-- bx Icons -->
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
<link href="{{ env('PUBLIC_PATH') }}/skote/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
<link rel="apple-touch-icon" href="{{ env('PUBLIC_PATH') }}/custom_icon.png"/>

<script src="//d2wy8f7a9ursnm.cloudfront.net/v7/bugsnag.min.js"></script>
<script>Bugsnag.start({ apiKey: '<?php echo env('BUGSNAG_API_KEY') ?>' })</script>

<style>
    
    @media (max-width: 576px) {
        .page-content {
            padding-left: 0;
            padding-right: 0;
        }
    }

    .btn-primary {
        background-color: #653FF4;
        border-color: #5c39de;
    }
    .btn-primary:hover {
        background-color: #5232cb;
        border-color: #4c2eba;
    }
</style>

<script>
    window.csrfToken = '<?php echo csrf_token(); ?>';
</script>
