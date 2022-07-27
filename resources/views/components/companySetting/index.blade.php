@extends('layouts.user')
@section('content')
<div class="container-fluid">
    <form class="" action="{{route('setting.settingSubmition')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                    <div>
                                        <table>
                                            <tr>
                                                <td style="padding: 0 2px;">
                                                    @if ($company->logo)
                                                    <img src="{{ Storage::url($company->logo) }}" style="width: 100px;">
                                                    @else
                                                        <img src="{{ public_path('pdf-logo.png') }}" style="width: 130px;">
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                            <br>
                                        </div>
                                </div> 
                            </div>
                        </div>    
                    </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label custom_form_label">Logo</label>
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" id="inputGroupFile02" name="logo">
                                    <label class="input-group-text btn-info" for="inputGroupFile02">Upload</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label custom_form_label">Company name</label>
                                <div class="mb-3" id="workerSalary">
                                    <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $company->name) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label custom_form_label" >Phone country</label>
                                <div class="mb-3">
                                    <select class="form-select" name="phone_country">
                                        <option data-countryCode="DK" value="45" @if (old('phone_country', $user->phone_country) == 45) selected @endif >Denmark (+45)</option>
                                        <option data-countryCode="DE" value="49" @if (old('phone_country', $user->phone_country) == 49) selected @endif >Germany (+49)</option>
                                        <option data-countryCode="LT" value="370" @if (old('phone_country', $user->phone_country) == 370) selected @endif>Lithuania (+370)</option>
                                        <option data-countryCode="NO" value="47" @if (old('phone_country', $user->phone_country) == 47) selected @endif>Norway (+47)</option>
                                        <option data-countryCode="PL" value="48" @if (old('phone_country', $user->phone_country) == 48) selected @endif >Poland (+48)</option>
                                        <option data-countryCode="SE" value="46" @if (old('phone_country', $user->phone_country) == 46) selected @endif >Sweden (+46)</option>
                                        <option data-countryCode="GB" value="44" @if (old('phone_country', $user->phone_country) == 44) selected @endif >UK (+44)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label custom_form_label">Phone number</label>
                                <div class="mb-3" id="">
                                    <input class="form-control" type="number" name="phone_no" id="phone_no" value="{{ old('name', $user->phone_no) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label custom_form_label">Company registration</label>
                                <div class="mb-3" id="workerSalary">
                                    <input class="form-control" type="text" name="company_registration" id="company_registration" value="{{ old('name', $company->company_registration_number) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label custom_form_label">Company address</label>
                                <div class="mb-3" id="workerSalary">
                                    <input class="form-control" type="text" name="company_address" id="company_address" value="{{ old('name', $company->company_address) }}">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="company_id" value="{{$company->id}}">
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                    </div>
                </div>    
            </div>
        </div>  
        <button class="btn btn-info mt-3 js-disable">Save</button>
    </form>
</div>
@endsection
