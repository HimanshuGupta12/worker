@extends('layouts.user')

@section('head')

<link href="{{ env('PUBLIC_PATH') }}/css/worker-forms.css" rel="stylesheet" type="text/css" />
<style>
    .card-title {
        margin-bottom: 30px;
        display: inline-flex;
        align-items: center;
    }
    span.number {
        background: #404040;
        color: white;
        padding: 8px 13px;
        border-radius: 40px;
        margin-right: 10px;
    } 
    .worker-client {
        margin-left: 0;
    }
    .worker-client label{
        padding: 0;
    }

    .sub_head_title {
        font-size: 11px;
        color: #667685;
        display: block;
        margin-bottom: 10px;
    }
    .mb-3.row.worker-client>.form-check.col-md-2 {
        border: 1px solid #F2F3F4;
        vertical-align: middle;
        padding: 1px 25px;
        margin: 3.35px;
        border-radius: 5px;
    }

    .col-md-6.client_data .client_sub {
        color: #667685;
        font-size: 13px;
    }

    form.add_edit_clientform input, form.add_edit_clientform textarea {
        background-color: #F9F9F9;
        border-color: #F9F9F9;
        border-radius: 5px;
    }
    .add_edit_clientform .select2-selection {
        background-color: #F9F9F9 !important;
        border-color: #F9F9F9;
    }

    .add_edit_clientform select {
        background-color: #F9F9F9;
        border-color: #F9F9F9;
    }
    .w-card_title {
        font-size: 20px;
        color: #001B34;
        line-height: 25.4px;
        margin-bottom: 31px;
    }
   .form-label.custom_form_label {
        margin-bottom: 6px;
        color: #001B34;
        font-size: 13px;
    }

    form.add_edit_clientform input::placeholder, form.add_edit_clientform textarea::placeholder {
        color: #667685;
    }
    .worker-client .form-check.col-md-2 .form-check-label {
        color: #667685;
        font-weight: 400;
        font-size: 13px;
    }
    .proj_enc_tabs .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        background-color: #2697FF;
        border-radius: 5px;
    }
    .proj_enc_tabs .nav-pills>li>a, .nav-tabs>li>a {
        border: 1px solid #F2F3F4;
        margin-right: 8px;
        color: #2F45C5;
    }
    .col-md-6.proj_enc_tabs .tab-content.p-3.text-muted {
        padding-left: 0px !important;
    }
    .add_edit_clientform .form-check-input:checked {
        background-color: #2F45C5;
        border-color: #2F45C5;
    }
      @media only screen and (min-width: 280px) and (max-width: 768px)
        {
.save-mb {
    font-size: 17px;
}
}
</style>


@endsection

@section('content')

<div class="container-fluid">
    <form class="add_edit_clientform" action="{{ $url }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="w-card_title">Client Details</h4>
                        <div class="row">
                            <div class="col-12"><h5 >Type</h5></div>
                            <div class="col-sm-auto">
                                <div class="mb-3">
                                    <div class="form-check mb-3" style="float: left;">
                                        <input class="form-check-input" type="radio" name="type" value="private" class="client_type" <?php echo (!isset($client->type) || (isset($client->type) && $client->type=='private')) ? 'checked' : '' ?>>
                                        <label class="form-check-label">
                                            Private
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="mb-3">
                                    <div class="form-check" style="float: left;">
                                        <input class="form-check-input" type="radio" value="business" name="type" class="client_type" <?php echo (isset($client->type) && $client->type=='business') ? 'checked' : '' ?>>
                                        <label class="form-check-label">
                                            Business
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="row">

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label custom_form_label">Client Name*</label>
                                   <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $client->name) }}" placeholder="Enter client name" />
                                @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">Email:*</label>
                                    <input class="form-control" type="text" name="email" value="{{ old('email', $client->email) }}" placeholder="Enter email address">
                                </div>
                            </div>
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label custom_form_label">Client phone*</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        
                                        <select class="form-select" name="phone_country">
{{--                                            <option data-countryCode="DZ" value="213" @if (old('phone_country', $client->phone_country) == 213) selected @endif>Algeria (+213)</option>--}}
{{--                                            <option data-countryCode="AD" value="376" @if (old('phone_country', $client->phone_country) == 376) selected @endif>Andorra (+376)</option>--}}
{{--                                            <option data-countryCode="AO" value="244" @if (old('phone_country', $client->phone_country) == 244) selected @endif>Angola (+244)</option>--}}
{{--                                            <option data-countryCode="AI" value="1264" @if (old('phone_country', $client->phone_country) == 1264) selected @endif>Anguilla (+1264)</option>--}}
{{--                                            <option data-countryCode="AG" value="1268" @if (old('phone_country', $client->phone_country) == 1268) selected @endif>Antigua &amp; Barbuda (+1268)</option>--}}
{{--                                            <option data-countryCode="AR" value="54" @if (old('phone_country', $client->phone_country) == 54) selected @endif>Argentina (+54)</option>--}}
{{--                                            <option data-countryCode="AM" value="374" @if (old('phone_country', $client->phone_country) == 374) selected @endif>Armenia (+374)</option>--}}
{{--                                            <option data-countryCode="AW" value="297" @if (old('phone_country', $client->phone_country) == 297) selected @endif>Aruba (+297)</option>--}}
{{--                                            <option data-countryCode="AU" value="61" @if (old('phone_country', $client->phone_country) == 61) selected @endif>Australia (+61)</option>--}}
{{--                                            <option data-countryCode="AT" value="43" @if (old('phone_country', $client->phone_country) == 43) selected @endif>Austria (+43)</option>--}}
{{--                                            <option data-countryCode="AZ" value="994" @if (old('phone_country', $client->phone_country) == 994) selected @endif>Azerbaijan (+994)</option>--}}
{{--                                            <option data-countryCode="BS" value="1242" @if (old('phone_country', $client->phone_country) == 1242) selected @endif>Bahamas (+1242)</option>--}}
{{--                                            <option data-countryCode="BH" value="973" @if (old('phone_country', $client->phone_country) == 973) selected @endif>Bahrain (+973)</option>--}}
{{--                                            <option data-countryCode="BD" value="880" @if (old('phone_country', $client->phone_country) == 880) selected @endif>Bangladesh (+880)</option>--}}
{{--                                            <option data-countryCode="BB" value="1246" @if (old('phone_country', $client->phone_country) == 1246) selected @endif>Barbados (+1246)</option>--}}
{{--                                            <option data-countryCode="BY" value="375" @if (old('phone_country', $client->phone_country) == 375) selected @endif>Belarus (+375)</option>--}}
{{--                                            <option data-countryCode="BE" value="32" @if (old('phone_country', $client->phone_country) == 32) selected @endif>Belgium (+32)</option>--}}
{{--                                            <option data-countryCode="BZ" value="501" @if (old('phone_country', $client->phone_country) == 501) selected @endif>Belize (+501)</option>--}}
{{--                                            <option data-countryCode="BJ" value="229" @if (old('phone_country', $client->phone_country) == 229) selected @endif>Benin (+229)</option>--}}
{{--                                            <option data-countryCode="BM" value="1441" @if (old('phone_country', $client->phone_country) == 1441) selected @endif>Bermuda (+1441)</option>--}}
{{--                                            <option data-countryCode="BT" value="975" @if (old('phone_country', $client->phone_country) == 975) selected @endif>Bhutan (+975)</option>--}}
{{--                                            <option data-countryCode="BO" value="591" @if (old('phone_country', $client->phone_country) == 591) selected @endif>Bolivia (+591)</option>--}}
{{--                                            <option data-countryCode="BA" value="387" @if (old('phone_country', $client->phone_country) == 387) selected @endif>Bosnia Herzegovina (+387)</option>--}}
{{--                                            <option data-countryCode="BW" value="267" @if (old('phone_country', $client->phone_country) == 267) selected @endif>Botswana (+267)</option>--}}
{{--                                            <option data-countryCode="BR" value="55" @if (old('phone_country', $client->phone_country) == 55) selected @endif>Brazil (+55)</option>--}}
{{--                                            <option data-countryCode="BN" value="673" @if (old('phone_country', $client->phone_country) == 673) selected @endif>Brunei (+673)</option>--}}
{{--                                            <option data-countryCode="BG" value="359" @if (old('phone_country', $client->phone_country) == 359) selected @endif>Bulgaria (+359)</option>--}}
{{--                                            <option data-countryCode="BF" value="226" @if (old('phone_country', $client->phone_country) == 226) selected @endif>Burkina Faso (+226)</option>--}}
{{--                                            <option data-countryCode="BI" value="257" @if (old('phone_country', $client->phone_country) == 257) selected @endif>Burundi (+257)</option>--}}
{{--                                            <option data-countryCode="KH" value="855" @if (old('phone_country', $client->phone_country) == 855) selected @endif>Cambodia (+855)</option>--}}
{{--                                            <option data-countryCode="CM" value="237" @if (old('phone_country', $client->phone_country) == 237) selected @endif>Cameroon (+237)</option>--}}
{{--                                            <option data-countryCode="CA" value="1" @if (old('phone_country', $client->phone_country) == 1) selected @endif>Canada (+1)</option>--}}
{{--                                            <option data-countryCode="CV" value="238" @if (old('phone_country', $client->phone_country) == 238) selected @endif>Cape Verde Islands (+238)</option>--}}
{{--                                            <option data-countryCode="KY" value="1345" @if (old('phone_country', $client->phone_country) == 1345) selected @endif>Cayman Islands (+1345)</option>--}}
{{--                                            <option data-countryCode="CF" value="236" @if (old('phone_country', $client->phone_country) == 236) selected @endif>Central African Republic (+236)</option>--}}
{{--                                            <option data-countryCode="CL" value="56" @if (old('phone_country', $client->phone_country) == 56) selected @endif>Chile (+56)</option>--}}
{{--                                            <option data-countryCode="CN" value="86" @if (old('phone_country', $client->phone_country) == 86) selected @endif>China (+86)</option>--}}
{{--                                            <option data-countryCode="CO" value="57" @if (old('phone_country', $client->phone_country) == 57) selected @endif>Colombia (+57)</option>--}}
{{--                                            <option data-countryCode="KM" value="269" @if (old('phone_country', $client->phone_country) == 269) selected @endif>Comoros (+269)</option>--}}
{{--                                            <option data-countryCode="CG" value="242" @if (old('phone_country', $client->phone_country) == 242) selected @endif>Congo (+242)</option>--}}
{{--                                            <option data-countryCode="CK" value="682" @if (old('phone_country', $client->phone_country) == 682) selected @endif>Cook Islands (+682)</option>--}}
{{--                                            <option data-countryCode="CR" value="506" @if (old('phone_country', $client->phone_country) == 506) selected @endif>Costa Rica (+506)</option>--}}
{{--                                            <option data-countryCode="HR" value="385" @if (old('phone_country', $client->phone_country) == 385) selected @endif>Croatia (+385)</option>--}}
{{--                                            <option data-countryCode="CU" value="53" @if (old('phone_country', $client->phone_country) == 53) selected @endif>Cuba (+53)</option>--}}
{{--                                            <option data-countryCode="CY" value="90392" @if (old('phone_country', $client->phone_country) == 90392) selected @endif>Cyprus North (+90392)</option>--}}
{{--                                            <option data-countryCode="CY" value="357" @if (old('phone_country', $client->phone_country) == 357) selected @endif>Cyprus South (+357)</option>--}}
{{--                                            <option data-countryCode="CZ" value="42" @if (old('phone_country', $client->phone_country) == 42) selected @endif>Czech Republic (+42)</option>--}}
                                    <option data-countryCode="DK" value="45" @if (old('phone_country', $client->phone_country) == 45) selected @endif>Denmark (+45)</option>
{{--                                            <option data-countryCode="DJ" value="253" @if (old('phone_country', $client->phone_country) == 253) selected @endif>Djibouti (+253)</option>--}}
{{--                                            <option data-countryCode="DM" value="1809" @if (old('phone_country', $client->phone_country) == 1809) selected @endif>Dominica (+1809)</option>--}}
{{--                                            <option data-countryCode="DO" value="1809" @if (old('phone_country', $client->phone_country) == 1809) selected @endif>Dominican Republic (+1809)</option>--}}
{{--                                            <option data-countryCode="EC" value="593" @if (old('phone_country', $client->phone_country) == 593) selected @endif>Ecuador (+593)</option>--}}
{{--                                            <option data-countryCode="EG" value="20" @if (old('phone_country', $client->phone_country) == 20) selected @endif>Egypt (+20)</option>--}}
{{--                                            <option data-countryCode="SV" value="503" @if (old('phone_country', $client->phone_country) == 503) selected @endif>El Salvador (+503)</option>--}}
{{--                                            <option data-countryCode="GQ" value="240" @if (old('phone_country', $client->phone_country) == 240) selected @endif>Equatorial Guinea (+240)</option>--}}
{{--                                            <option data-countryCode="ER" value="291" @if (old('phone_country', $client->phone_country) == 291) selected @endif>Eritrea (+291)</option>--}}
{{--                                            <option data-countryCode="EE" value="372" @if (old('phone_country', $client->phone_country) == 372) selected @endif>Estonia (+372)</option>--}}
{{--                                            <option data-countryCode="ET" value="251" @if (old('phone_country', $client->phone_country) == 251) selected @endif>Ethiopia (+251)</option>--}}
{{--                                            <option data-countryCode="FK" value="500" @if (old('phone_country', $client->phone_country) == 500) selected @endif>Falkland Islands (+500)</option>--}}
{{--                                            <option data-countryCode="FO" value="298" @if (old('phone_country', $client->phone_country) == 298) selected @endif>Faroe Islands (+298)</option>--}}
{{--                                            <option data-countryCode="FJ" value="679" @if (old('phone_country', $client->phone_country) == 679) selected @endif>Fiji (+679)</option>--}}
{{--                                            <option data-countryCode="FI" value="358" @if (old('phone_country', $client->phone_country) == 358) selected @endif>Finland (+358)</option>--}}
{{--                                            <option data-countryCode="FR" value="33" @if (old('phone_country', $client->phone_country) == 33) selected @endif>France (+33)</option>--}}
{{--                                            <option data-countryCode="GF" value="594" @if (old('phone_country', $client->phone_country) == 594) selected @endif>French Guiana (+594)</option>--}}
{{--                                            <option data-countryCode="PF" value="689" @if (old('phone_country', $client->phone_country) == 689) selected @endif>French Polynesia (+689)</option>--}}
{{--                                            <option data-countryCode="GA" value="241" @if (old('phone_country', $client->phone_country) == 241) selected @endif>Gabon (+241)</option>--}}
{{--                                            <option data-countryCode="GM" value="220" @if (old('phone_country', $client->phone_country) == 220) selected @endif>Gambia (+220)</option>--}}
{{--                                            <option data-countryCode="GE" value="7880" @if (old('phone_country', $client->phone_country) == 7880) selected @endif>Georgia (+7880)</option>--}}
                                    <option data-countryCode="DE" value="49" @if (old('phone_country', $client->phone_country) == 49) selected @endif>Germany (+49)</option>
{{--                                            <option data-countryCode="GH" value="233" @if (old('phone_country', $client->phone_country) == 233) selected @endif>Ghana (+233)</option>--}}
{{--                                            <option data-countryCode="GI" value="350" @if (old('phone_country', $client->phone_country) == 350) selected @endif>Gibraltar (+350)</option>--}}
{{--                                            <option data-countryCode="GR" value="30" @if (old('phone_country', $client->phone_country) == 30) selected @endif>Greece (+30)</option>--}}
{{--                                            <option data-countryCode="GL" value="299" @if (old('phone_country', $client->phone_country) == 299) selected @endif>Greenland (+299)</option>--}}
{{--                                            <option data-countryCode="GD" value="1473" @if (old('phone_country', $client->phone_country) == 1473) selected @endif>Grenada (+1473)</option>--}}
{{--                                            <option data-countryCode="GP" value="590" @if (old('phone_country', $client->phone_country) == 590) selected @endif>Guadeloupe (+590)</option>--}}
{{--                                            <option data-countryCode="GU" value="671" @if (old('phone_country', $client->phone_country) == 671) selected @endif>Guam (+671)</option>--}}
{{--                                            <option data-countryCode="GT" value="502" @if (old('phone_country', $client->phone_country) == 502) selected @endif>Guatemala (+502)</option>--}}
{{--                                            <option data-countryCode="GN" value="224" @if (old('phone_country', $client->phone_country) == 224) selected @endif>Guinea (+224)</option>--}}
{{--                                            <option data-countryCode="GW" value="245" @if (old('phone_country', $client->phone_country) == 245) selected @endif>Guinea - Bissau (+245)</option>--}}
{{--                                            <option data-countryCode="GY" value="592" @if (old('phone_country', $client->phone_country) == 592) selected @endif>Guyana (+592)</option>--}}
{{--                                            <option data-countryCode="HT" value="509" @if (old('phone_country', $client->phone_country) == 509) selected @endif>Haiti (+509)</option>--}}
{{--                                            <option data-countryCode="HN" value="504" @if (old('phone_country', $client->phone_country) == 504) selected @endif>Honduras (+504)</option>--}}
{{--                                            <option data-countryCode="HK" value="852" @if (old('phone_country', $client->phone_country) == 852) selected @endif>Hong Kong (+852)</option>--}}
{{--                                            <option data-countryCode="HU" value="36" @if (old('phone_country', $client->phone_country) == 36) selected @endif>Hungary (+36)</option>--}}
{{--                                            <option data-countryCode="IS" value="354" @if (old('phone_country', $client->phone_country) == 354) selected @endif>Iceland (+354)</option>--}}
{{--                                            <option data-countryCode="IN" value="91" @if (old('phone_country', $client->phone_country) == 91) selected @endif>India (+91)</option>--}}
{{--                                            <option data-countryCode="ID" value="62" @if (old('phone_country', $client->phone_country) == 62) selected @endif>Indonesia (+62)</option>--}}
{{--                                            <option data-countryCode="IR" value="98" @if (old('phone_country', $client->phone_country) == 98) selected @endif>Iran (+98)</option>--}}
{{--                                            <option data-countryCode="IQ" value="964" @if (old('phone_country', $client->phone_country) == 964) selected @endif>Iraq (+964)</option>--}}
{{--                                            <option data-countryCode="IE" value="353" @if (old('phone_country', $client->phone_country) == 353) selected @endif>Ireland (+353)</option>--}}
{{--                                            <option data-countryCode="IL" value="972" @if (old('phone_country', $client->phone_country) == 972) selected @endif>Israel (+972)</option>--}}
{{--                                            <option data-countryCode="IT" value="39" @if (old('phone_country', $client->phone_country) == 39) selected @endif>Italy (+39)</option>--}}
{{--                                            <option data-countryCode="JM" value="1876" @if (old('phone_country', $client->phone_country) == 1876) selected @endif>Jamaica (+1876)</option>--}}
{{--                                            <option data-countryCode="JP" value="81" @if (old('phone_country', $client->phone_country) == 81) selected @endif>Japan (+81)</option>--}}
{{--                                            <option data-countryCode="JO" value="962" @if (old('phone_country', $client->phone_country) == 962) selected @endif>Jordan (+962)</option>--}}
{{--                                            <option data-countryCode="KZ" value="7" @if (old('phone_country', $client->phone_country) == 7) selected @endif>Kazakhstan (+7)</option>--}}
{{--                                            <option data-countryCode="KE" value="254" @if (old('phone_country', $client->phone_country) == 254) selected @endif>Kenya (+254)</option>--}}
{{--                                            <option data-countryCode="KI" value="686" @if (old('phone_country', $client->phone_country) == 686) selected @endif>Kiribati (+686)</option>--}}
{{--                                            <option data-countryCode="KP" value="850" @if (old('phone_country', $client->phone_country) == 850) selected @endif>Korea North (+850)</option>--}}
{{--                                            <option data-countryCode="KR" value="82" @if (old('phone_country', $client->phone_country) == 82) selected @endif>Korea South (+82)</option>--}}
{{--                                            <option data-countryCode="KW" value="965" @if (old('phone_country', $client->phone_country) == 965) selected @endif>Kuwait (+965)</option>--}}
{{--                                            <option data-countryCode="KG" value="996" @if (old('phone_country', $client->phone_country) == 996) selected @endif>Kyrgyzstan (+996)</option>--}}
{{--                                            <option data-countryCode="LA" value="856" @if (old('phone_country', $client->phone_country) == 856) selected @endif>Laos (+856)</option>--}}
                                    <option data-countryCode="LV" value="371" @if (old('phone_country', $client->phone_country) == 371) selected @endif>Latvia (+371)</option>
{{--                                            <option data-countryCode="LB" value="961" @if (old('phone_country', $client->phone_country) == 961) selected @endif>Lebanon (+961)</option>--}}
{{--                                            <option data-countryCode="LS" value="266" @if (old('phone_country', $client->phone_country) == 266) selected @endif>Lesotho (+266)</option>--}}
{{--                                            <option data-countryCode="LR" value="231" @if (old('phone_country', $client->phone_country) == 231) selected @endif>Liberia (+231)</option>--}}
{{--                                            <option data-countryCode="LY" value="218" @if (old('phone_country', $client->phone_country) == 218) selected @endif>Libya (+218)</option>--}}
{{--                                            <option data-countryCode="LI" value="417" @if (old('phone_country', $client->phone_country) == 417) selected @endif>Liechtenstein (+417)</option>--}}
                                    <option data-countryCode="LT" value="370" @if (old('phone_country', $client->phone_country) == 370) selected @endif>Lithuania (+370)</option>
{{--                                            <option data-countryCode="LU" value="352" @if (old('phone_country', $client->phone_country) == 352) selected @endif>Luxembourg (+352)</option>--}}
{{--                                            <option data-countryCode="MO" value="853" @if (old('phone_country', $client->phone_country) == 853) selected @endif>Macao (+853)</option>--}}
{{--                                            <option data-countryCode="MK" value="389" @if (old('phone_country', $client->phone_country) == 389) selected @endif>Macedonia (+389)</option>--}}
{{--                                            <option data-countryCode="MG" value="261" @if (old('phone_country', $client->phone_country) == 261) selected @endif>Madagascar (+261)</option>--}}
{{--                                            <option data-countryCode="MW" value="265" @if (old('phone_country', $client->phone_country) == 265) selected @endif>Malawi (+265)</option>--}}
{{--                                            <option data-countryCode="MY" value="60" @if (old('phone_country', $client->phone_country) == 60) selected @endif>Malaysia (+60)</option>--}}
{{--                                            <option data-countryCode="MV" value="960" @if (old('phone_country', $client->phone_country) == 960) selected @endif>Maldives (+960)</option>--}}
{{--                                            <option data-countryCode="ML" value="223" @if (old('phone_country', $client->phone_country) == 223) selected @endif>Mali (+223)</option>--}}
{{--                                            <option data-countryCode="MT" value="356" @if (old('phone_country', $client->phone_country) == 356) selected @endif>Malta (+356)</option>--}}
{{--                                            <option data-countryCode="MH" value="692" @if (old('phone_country', $client->phone_country) == 692) selected @endif>Marshall Islands (+692)</option>--}}
{{--                                            <option data-countryCode="MQ" value="596" @if (old('phone_country', $client->phone_country) == 596) selected @endif>Martinique (+596)</option>--}}
{{--                                            <option data-countryCode="MR" value="222" @if (old('phone_country', $client->phone_country) == 222) selected @endif>Mauritania (+222)</option>--}}
{{--                                            <option data-countryCode="YT" value="269" @if (old('phone_country', $client->phone_country) == 269) selected @endif>Mayotte (+269)</option>--}}
{{--                                            <option data-countryCode="MX" value="52" @if (old('phone_country', $client->phone_country) == 52) selected @endif>Mexico (+52)</option>--}}
{{--                                            <option data-countryCode="FM" value="691" @if (old('phone_country', $client->phone_country) == 691) selected @endif>Micronesia (+691)</option>--}}
{{--                                            <option data-countryCode="MD" value="373" @if (old('phone_country', $client->phone_country) == 373) selected @endif>Moldova (+373)</option>--}}
{{--                                            <option data-countryCode="MC" value="377" @if (old('phone_country', $client->phone_country) == 377) selected @endif>Monaco (+377)</option>--}}
{{--                                            <option data-countryCode="MN" value="976" @if (old('phone_country', $client->phone_country) == 976) selected @endif>Mongolia (+976)</option>--}}
{{--                                            <option data-countryCode="MS" value="1664" @if (old('phone_country', $client->phone_country) == 1664) selected @endif>Montserrat (+1664)</option>--}}
{{--                                            <option data-countryCode="MA" value="212" @if (old('phone_country', $client->phone_country) == 212) selected @endif>Morocco (+212)</option>--}}
{{--                                            <option data-countryCode="MZ" value="258" @if (old('phone_country', $client->phone_country) == 258) selected @endif>Mozambique (+258)</option>--}}
{{--                                            <option data-countryCode="MN" value="95" @if (old('phone_country', $client->phone_country) == 95) selected @endif>Myanmar (+95)</option>--}}
{{--                                            <option data-countryCode="NA" value="264" @if (old('phone_country', $client->phone_country) == 264) selected @endif>Namibia (+264)</option>--}}
{{--                                            <option data-countryCode="NR" value="674" @if (old('phone_country', $client->phone_country) == 674) selected @endif>Nauru (+674)</option>--}}
{{--                                            <option data-countryCode="NP" value="977" @if (old('phone_country', $client->phone_country) == 977) selected @endif>Nepal (+977)</option>--}}
{{--                                            <option data-countryCode="NL" value="31" @if (old('phone_country', $client->phone_country) == 31) selected @endif>Netherlands (+31)</option>--}}
{{--                                            <option data-countryCode="NC" value="687" @if (old('phone_country', $client->phone_country) == 687) selected @endif>New Caledonia (+687)</option>--}}
{{--                                            <option data-countryCode="NZ" value="64" @if (old('phone_country', $client->phone_country) == 64) selected @endif>New Zealand (+64)</option>--}}
{{--                                            <option data-countryCode="NI" value="505" @if (old('phone_country', $client->phone_country) == 505) selected @endif>Nicaragua (+505)</option>--}}
{{--                                            <option data-countryCode="NE" value="227" @if (old('phone_country', $client->phone_country) == 227) selected @endif>Niger (+227)</option>--}}
{{--                                            <option data-countryCode="NG" value="234" @if (old('phone_country', $client->phone_country) == 234) selected @endif>Nigeria (+234)</option>--}}
{{--                                            <option data-countryCode="NU" value="683" @if (old('phone_country', $client->phone_country) == 683) selected @endif>Niue (+683)</option>--}}
{{--                                            <option data-countryCode="NF" value="672" @if (old('phone_country', $client->phone_country) == 672) selected @endif>Norfolk Islands (+672)</option>--}}
{{--                                            <option data-countryCode="NP" value="670" @if (old('phone_country', $client->phone_country) == 670) selected @endif>Northern Marianas (+670)</option>--}}
                                    <option data-countryCode="NO" value="47" @if (old('phone_country', $client->phone_country) == 47) selected @endif>Norway (+47)</option>
{{--                                            <option data-countryCode="OM" value="968" @if (old('phone_country', $client->phone_country) == 968) selected @endif>Oman (+968)</option>--}}
{{--                                            <option data-countryCode="PW" value="680" @if (old('phone_country', $client->phone_country) == 680) selected @endif>Palau (+680)</option>--}}
{{--                                            <option data-countryCode="PA" value="507" @if (old('phone_country', $client->phone_country) == 507) selected @endif>Panama (+507)</option>--}}
{{--                                            <option data-countryCode="PG" value="675" @if (old('phone_country', $client->phone_country) == 675) selected @endif>Papua New Guinea (+675)</option>--}}
{{--                                            <option data-countryCode="PY" value="595" @if (old('phone_country', $client->phone_country) == 595) selected @endif>Paraguay (+595)</option>--}}
{{--                                            <option data-countryCode="PE" value="51" @if (old('phone_country', $client->phone_country) == 51) selected @endif>Peru (+51)</option>--}}
{{--                                            <option data-countryCode="PH" value="63" @if (old('phone_country', $client->phone_country) == 63) selected @endif>Philippines (+63)</option>--}}
                                    <option data-countryCode="PL" value="48" @if (old('phone_country', $client->phone_country) == 48) selected @endif>Poland (+48)</option>
{{--                                            <option data-countryCode="PT" value="351" @if (old('phone_country', $client->phone_country) == 351) selected @endif>Portugal (+351)</option>--}}
{{--                                            <option data-countryCode="PR" value="1787" @if (old('phone_country', $client->phone_country) == 1787) selected @endif>Puerto Rico (+1787)</option>--}}
{{--                                            <option data-countryCode="QA" value="974" @if (old('phone_country', $client->phone_country) == 974) selected @endif>Qatar (+974)</option>--}}
{{--                                            <option data-countryCode="RE" value="262" @if (old('phone_country', $client->phone_country) == 262) selected @endif>Reunion (+262)</option>--}}
{{--                                            <option data-countryCode="RO" value="40" @if (old('phone_country', $client->phone_country) == 40) selected @endif>Romania (+40)</option>--}}
{{--                                            <option data-countryCode="RU" value="7" @if (old('phone_country', $client->phone_country) == 7) selected @endif>Russia (+7)</option>--}}
{{--                                            <option data-countryCode="RW" value="250" @if (old('phone_country', $client->phone_country) == 250) selected @endif>Rwanda (+250)</option>--}}
{{--                                            <option data-countryCode="SM" value="378" @if (old('phone_country', $client->phone_country) == 378) selected @endif>San Marino (+378)</option>--}}
{{--                                            <option data-countryCode="ST" value="239" @if (old('phone_country', $client->phone_country) == 239) selected @endif>Sao Tome &amp; Principe (+239)</option>--}}
{{--                                            <option data-countryCode="SA" value="966" @if (old('phone_country', $client->phone_country) == 966) selected @endif>Saudi Arabia (+966)</option>--}}
{{--                                            <option data-countryCode="SN" value="221" @if (old('phone_country', $client->phone_country) == 221) selected @endif>Senegal (+221)</option>--}}
{{--                                            <option data-countryCode="CS" value="381" @if (old('phone_country', $client->phone_country) == 381) selected @endif>Serbia (+381)</option>--}}
{{--                                            <option data-countryCode="SC" value="248" @if (old('phone_country', $client->phone_country) == 248) selected @endif>Seychelles (+248)</option>--}}
{{--                                            <option data-countryCode="SL" value="232" @if (old('phone_country', $client->phone_country) == 232) selected @endif>Sierra Leone (+232)</option>--}}
{{--                                            <option data-countryCode="SG" value="65" @if (old('phone_country', $client->phone_country) == 65) selected @endif>Singapore (+65)</option>--}}
{{--                                            <option data-countryCode="SK" value="421" @if (old('phone_country', $client->phone_country) == 421) selected @endif>Slovak Republic (+421)</option>--}}
{{--                                            <option data-countryCode="SI" value="386" @if (old('phone_country', $client->phone_country) == 386) selected @endif>Slovenia (+386)</option>--}}
{{--                                            <option data-countryCode="SB" value="677" @if (old('phone_country', $client->phone_country) == 677) selected @endif>Solomon Islands (+677)</option>--}}
{{--                                            <option data-countryCode="SO" value="252" @if (old('phone_country', $client->phone_country) == 252) selected @endif>Somalia (+252)</option>--}}
{{--                                            <option data-countryCode="ZA" value="27" @if (old('phone_country', $client->phone_country) == 27) selected @endif>South Africa (+27)</option>--}}
{{--                                            <option data-countryCode="ES" value="34" @if (old('phone_country', $client->phone_country) == 34) selected @endif>Spain (+34)</option>--}}
{{--                                            <option data-countryCode="LK" value="94" @if (old('phone_country', $client->phone_country) == 94) selected @endif>Sri Lanka (+94)</option>--}}
{{--                                            <option data-countryCode="SH" value="290" @if (old('phone_country', $client->phone_country) == 290) selected @endif>St. Helena (+290)</option>--}}
{{--                                            <option data-countryCode="KN" value="1869" @if (old('phone_country', $client->phone_country) == 1869) selected @endif>St. Kitts (+1869)</option>--}}
{{--                                            <option data-countryCode="SC" value="1758" @if (old('phone_country', $client->phone_country) == 1758) selected @endif>St. Lucia (+1758)</option>--}}
{{--                                            <option data-countryCode="SD" value="249" @if (old('phone_country', $client->phone_country) == 249) selected @endif>Sudan (+249)</option>--}}
{{--                                            <option data-countryCode="SR" value="597" @if (old('phone_country', $client->phone_country) == 597) selected @endif>Suriname (+597)</option>--}}
{{--                                            <option data-countryCode="SZ" value="268" @if (old('phone_country', $client->phone_country) == 268) selected @endif>Swaziland (+268)</option>--}}
                                    <option data-countryCode="SE" value="46" @if (old('phone_country', $client->phone_country) == 46) selected @endif>Sweden (+46)</option>
{{--                                            <option data-countryCode="CH" value="41" @if (old('phone_country', $client->phone_country) == 41) selected @endif>Switzerland (+41)</option>--}}
{{--                                            <option data-countryCode="SI" value="963" @if (old('phone_country', $client->phone_country) == 963) selected @endif>Syria (+963)</option>--}}
{{--                                            <option data-countryCode="TW" value="886" @if (old('phone_country', $client->phone_country) == 886) selected @endif>Taiwan (+886)</option>--}}
{{--                                            <option data-countryCode="TJ" value="7" @if (old('phone_country', $client->phone_country) == 7) selected @endif>Tajikstan (+7)</option>--}}
{{--                                            <option data-countryCode="TH" value="66" @if (old('phone_country', $client->phone_country) == 66) selected @endif>Thailand (+66)</option>--}}
{{--                                            <option data-countryCode="TG" value="228" @if (old('phone_country', $client->phone_country) == 228) selected @endif>Togo (+228)</option>--}}
{{--                                            <option data-countryCode="TO" value="676" @if (old('phone_country', $client->phone_country) == 676) selected @endif>Tonga (+676)</option>--}}
{{--                                            <option data-countryCode="TT" value="1868" @if (old('phone_country', $client->phone_country) == 1868) selected @endif>Trinidad &amp; Tobago (+1868)</option>--}}
{{--                                            <option data-countryCode="TN" value="216" @if (old('phone_country', $client->phone_country) == 216) selected @endif>Tunisia (+216)</option>--}}
{{--                                            <option data-countryCode="TR" value="90" @if (old('phone_country', $client->phone_country) == 90) selected @endif>Turkey (+90)</option>--}}
{{--                                            <option data-countryCode="TM" value="7" @if (old('phone_country', $client->phone_country) == 7) selected @endif>Turkmenistan (+7)</option>--}}
{{--                                            <option data-countryCode="TM" value="993" @if (old('phone_country', $client->phone_country) == 993) selected @endif>Turkmenistan (+993)</option>--}}
{{--                                            <option data-countryCode="TC" value="1649" @if (old('phone_country', $client->phone_country) == 1649) selected @endif>Turks &amp; Caicos Islands (+1649)</option>--}}
{{--                                            <option data-countryCode="TV" value="688" @if (old('phone_country', $client->phone_country) == 688) selected @endif>Tuvalu (+688)</option>--}}
{{--                                            <option data-countryCode="UG" value="256" @if (old('phone_country', $client->phone_country) == 256) selected @endif>Uganda (+256)</option>--}}
                                    <option data-countryCode="GB" value="44" @if (old('phone_country', $client->phone_country) == 44) selected @endif>UK (+44)</option>
                                    <option data-countryCode="UA" value="380" @if (old('phone_country', $client->phone_country) == 380) selected @endif>Ukraine (+380)</option>
{{--                                            <option data-countryCode="AE" value="971" @if (old('phone_country', $client->phone_country) == 971) selected @endif>United Arab Emirates (+971)</option>--}}
{{--                                            <option data-countryCode="UY" value="598" @if (old('phone_country', $client->phone_country) == 598) selected @endif>Uruguay (+598)</option>--}}
{{--                                            <option data-countryCode="US" value="1" @if (old('phone_country', $client->phone_country) == 1) selected @endif>USA (+1)</option>--}}
{{--                                            <option data-countryCode="UZ" value="7" @if (old('phone_country', $client->phone_country) == 7) selected @endif>Uzbekistan (+7)</option>--}}
{{--                                            <option data-countryCode="VU" value="678" @if (old('phone_country', $client->phone_country) == 678) selected @endif>Vanuatu (+678)</option>--}}
{{--                                            <option data-countryCode="VA" value="379" @if (old('phone_country', $client->phone_country) == 379) selected @endif>Vatican City (+379)</option>--}}
{{--                                            <option data-countryCode="VE" value="58" @if (old('phone_country', $client->phone_country) == 58) selected @endif>Venezuela (+58)</option>--}}
{{--                                            <option data-countryCode="VN" value="84" @if (old('phone_country', $client->phone_country) == 84) selected @endif>Vietnam (+84)</option>--}}
{{--                                            <option data-countryCode="VG" value="84" @if (old('phone_country', $client->phone_country) == 84) selected @endif>Virgin Islands - British (+1284)</option>--}}
{{--                                            <option data-countryCode="VI" value="84" @if (old('phone_country', $client->phone_country) == 84) selected @endif>Virgin Islands - US (+1340)</option>--}}
{{--                                            <option data-countryCode="WF" value="681" @if (old('phone_country', $client->phone_country) == 681) selected @endif>Wallis &amp; Futuna (+681)</option>--}}
{{--                                            <option data-countryCode="YE" value="969" @if (old('phone_country', $client->phone_country) == 969) selected @endif>Yemen (North)(+969)</option>--}}
{{--                                            <option data-countryCode="YE" value="967" @if (old('phone_country', $client->phone_country) == 967) selected @endif>Yemen (South)(+967)</option>--}}
{{--                                            <option data-countryCode="ZM" value="260" @if (old('phone_country', $client->phone_country) == 260) selected @endif>Zambia (+260)</option>--}}
{{--                                            <option data-countryCode="ZW" value="263" @if (old('phone_country', $client->phone_country) == 263) selected @endif>Zimbabwe (+263)</option>--}}
                                </select>

                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="phone_number" value="{{ old('phone_number', $client->phone_number) }}" placeholder="66123456">
                                    </div>
                                </div>
                            </div>
                        </div>




                    </div>

         
                            <div class="company_data row" style="display: <?php echo (isset($client->type) && $client->type == 'business') ? 'block' : 'none' ?>">

                                <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4" style="float: left; margin-bottom: 15px; ">
                                    <label for="company_name">Company name:*</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company name">
                                </div>

                                <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4" style="float: left; margin-bottom: 15px;">
                                    <label for="company_org_no">Company Org. No.:*</label>
                                    <input type="text" class="form-control" id="company_org_no" name="company_org_no" placeholder="Company Org. No.">
                                </div>

                            </div>

                            <div class="row"> 
                                <div class="col-md-4">
                                    <label class="form-label custom_form_label">Client location and address*</label>
                                    <input class="form-control" type="text" name="street" value="{{ old('street', $client->street) }}" placeholder="Enter street address">
                                 </div>
                                <div class="col-md-3">
                                    <div class="mb-3 ">
                                        <label for="formrow-inputCity" class="form-label custom_form_label">City</label>
                                        <input class="form-control" type="text" name="city" value="{{ old('city', $client->city) }}" placeholder="City">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label custom_form_label">Post code</label>
                                        <input type="text" class="form-control" name="postcode" value="{{ old('postcode', $client->postcode) }}" placeholder="Enter post code">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                      <label class="form-label custom_form_label">Additional notes about the client</label>
                                        <textarea class="form-control" name="additional_note" style="height: 100px;" placeholder="Write a note">{{ old('additional_note', $client->additional_note) }}</textarea>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="mb-5">
            <button class="btn btn-info mt-3 js-disable save-mb">Save</button>
        </div>
    </form>
</div>

@endsection
