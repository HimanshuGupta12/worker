<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>worker.nu</title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:600,400' rel='stylesheet' type='text/css'>
</head>

<body style="margin: 0; padding: 0;">

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: 'Open Sans', sans-serif, Arial; color: #6d727b; font-size: 16px; background-color: #F6F7F7;">
    <tr>
        <td></td>


        {{-- container --}}
        <td align="center">

            <table border="0" cellpadding="0" cellspacing="0" style="max-width: 780px; width: 100%;">
                <tr>
                    <td style="padding: 30px 0 100px 0;">

                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: transparent; padding-bottom: 20px;">
                            <tr>
                                <td style="text-align: center;">
                                    <a href="{{ URL::to('/') }}">
                                        <img border="0" src="{{ URL::to('/img/back-logo.png') }}" alt="logo" style="width: 60px;;">
                                    </a>
                                </td>
                            </tr>
                        </table>

                        {{-- content --}}
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#fcfcfd">
                            <tr>
                                <td style="padding: 30px 30px 30px 30px;">

                                    @yield('content')

                                    {{--@if (isset($style))--}}
                                        {{----}}
                                    {{--@else--}}
                                        {{--@yield('content')--}}
                                    {{--@endif--}}

                                </td>
                            </tr>
                        </table>

                        {{-- footer --}}
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#212734" style="padding: 20px 30px 30px 30px; color: #c7c7c7;">
                            <tr>
                                <td>
                                    <?php $email_footer = $__env->yieldContent('footer'); ?>
                                    @if (!$email_footer) {{-- default footer --}}

                                        @section('footer')
                                            <p style="">
                                                Sincerely,<br>
                                                The worker.nu team
                                            </p>
                                        @stop

                                    @endif

                                    @yield('footer')
                                </td>
                            </tr>
                        </table>

                        @if (isset($unsubscribe))
                            <p style="text-align:center;"><a style="font-size:11px;color:#f6a022;" href="{{ route('unsubscribe') }}">{{ __("emails/template.unsubscribe") }}</a></p>
                            <div style="height: 20px;"></div>
                        @endif

                    </td>
                </tr>
            </table>


        </td>
        <td></td>
    </tr>
</table>
</body>
</html>
