@extends('layouts.user')

@section('head')
    <style>
        .StripeElement {
            background-color: white;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid transparent;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }
        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }
        .StripeElement--invalid {
            border-color: #fa755a;
        }
        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
        .stripe-error {
            color: #f46a6a;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('subscription.process') }}" method="POST" id="subscribe-form">
                @csrf
                <input type="hidden" name="plan_name" id="plan_name">
                <input type="hidden" name="prev_plan_name" id="prev_plan_name">
                <input type="hidden" name="prev_vat_number" value="{{ old('vat_number', $user_billing_detail->vat_number) }}">
                <input type="hidden" name="vat_number_stripe_id" value="{{ old('vat_number_stripe_id', $user_billing_detail->vat_number_stripe_id) }}">
                <input type="hidden" name="stripe_active_plan_name" id="stripe_active_plan_name" />
                <input type="hidden" name="prev_company_number" value="{{ old('company_number', $user_billing_detail->company_number) }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="card-title col-md-8">
                                Plans
                            </div>
                            <div class="col-md-4">
                                @if(!empty($plan_name))
                                    <button type="button" id="cancelSubscription" class="btn btn-danger w-md float-end">Cancel Subscription</button>
                                @elseif(!$user->onTrial() && empty($user->trial_ends_at))
                                    <!-- <button type="button" id="startFreeTrial" class="btn btn-info w-md float-end">Start 7 days free trial</button> -->
                                @endif
                            </div>
                        </div>
                        <div class="row plans-div">
                            @php
                                $activePlan = [];
                            @endphp
                            @if(!empty($plans))
                                @foreach($plans as $plan)
                                    @if(!empty($plan->product->metadata->custom_plan_name))
                                        @php
                                            if(empty($activePlan) && $user->subscribedToProduct($plan->product->id, $plan->product->metadata->custom_plan_name)) {
                                                $activePlan = $plan;
                                            }
                                        @endphp
                                        <div class="col-md-4">
                                            <div class="subscription-option">
                                                <input class="form-check-input planTypes @error('name') is-invalid @enderror" type="radio" id="plan-worker-{{ $plan->id }}" name="plan" value='{{ $plan->id }}' data-planTitle="{{ $plan->product->name }}" data-planName="{{ $plan->product->metadata->custom_plan_name }}" {{ ($user->subscribedToProduct($plan->product->id, $plan->product->metadata->custom_plan_name)) ? 'checked' : '' }}>
                                                <label for="plan-worker-{{ $plan->id }}">
                                                    <span class="plan-name">{{ $plan->product->name }}</span>
                                                    <br />
                                                    <span class="plan-price"><small>{{ $plan->product->description }}</small></span>
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-title mb-4">
                            Billing Settings
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">
                                        Company name&nbsp;
                                        <i class="bx bxs-info-circle text-info" title="Company name to be used in invoices."></i>
                                    </label>
                                    <input class="form-control  @error('company_name') is-invalid @enderror" type="text" name="company_name" value="{{ old('company_name', $user_billing_detail->company_name) }}" placeholder="{{ $user->company->name }}">
                                    @error('company_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">
                                        Billing email&nbsp;
                                        <i class="bx bxs-info-circle text-info" title="Email address to be used to send invoice emails."></i>
                                    </label>
                                    <input class="form-control  @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $user_billing_detail->email) }}" placeholder="Email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">Address line*</label>
                                    <input class="form-control  @error('address_line') is-invalid @enderror" type="text" name="address_line" value="{{ old('address_line', $user_billing_detail->address_line) }}" placeholder="Address line">
                                    @error('address_line')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label" >Postal code*</label>
                                    <input class="form-control @error('postal_code') is-invalid @enderror" type="text" name="postal_code" value="{{ old('postal_code', $user_billing_detail->postal_code) }}" placeholder="Postal code">
                                    @error('postal_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">State</label>
                                    <input class="form-control  @error('state') is-invalid @enderror" type="text" name="state" value="{{ old('state', $user_billing_detail->state) }}" placeholder="State">
                                    @error('state')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label" >City*</label>
                                    <input class="form-control @error('city') is-invalid @enderror" type="text" name="city" value="{{ old('city', $user_billing_detail->city) }}" placeholder="City">
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label" >Country*</label>
                                    <select class="form-select" name="country" id="country" {{ (!empty($user_billing_detail->country)) ? 'disabled' : '' }}>
                                        <option value="DK" @if (old('country', $user_billing_detail->country) == 'DK') selected @endif>Denmark</option>
                                        <option value="DE" @if (old('country', $user_billing_detail->country) == 'DE') selected @endif>Germany</option>
                                        <option value="LV" @if (old('country', $user_billing_detail->country) == 'LV') selected @endif>Latvia</option>
                                        <option value="LT" @if (old('country', $user_billing_detail->country) == 'LT') selected @endif>Lithuania</option>
                                        <option value="NO" @if (old('country', $user_billing_detail->country) == 'NO') selected @endif>Norway</option>
                                        <option value="PL" @if (old('country', $user_billing_detail->country) == 'PL') selected @endif>Poland</option>
                                        <option value="SE" @if (old('country', $user_billing_detail->country) == 'SE') selected @endif>Sweden</option>
                                        <option value="GB" @if (old('country', $user_billing_detail->country) == 'GB') selected @endif>UK</option>
                                    </select>
                                    @error('country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label custom_form_label">Phone country</label>
                                <div class="mb-3">
                                    <select class="form-select" name="phone_country">
                                        <option data-countryCode="DK" value="45" @if (old('phone_country', $user_billing_detail->phone_country) == 45) selected @endif >Denmark (+45)</option>
                                        <option data-countryCode="DE" value="49" @if (old('phone_country', $user_billing_detail->phone_country) == 49) selected @endif >Germany (+49)</option>
                                        <option data-countryCode="LT" value="370" @if (old('phone_country', $user_billing_detail->phone_country) == 370) selected @endif>Lithuania (+370)</option>
                                        <option data-countryCode="NO" value="47" @if (old('phone_country', $user_billing_detail->phone_country) == 47) selected @endif>Norway (+47)</option>
                                        <option data-countryCode="PL" value="48" @if (old('phone_country', $user_billing_detail->phone_country) == 48) selected @endif >Poland (+48)</option>
                                        <option data-countryCode="SE" value="46" @if (old('phone_country', $user_billing_detail->phone_country) == 46) selected @endif >Sweden (+46)</option>
                                        <option data-countryCode="GB" value="44" @if (old('phone_country', $user_billing_detail->phone_country) == 44) selected @endif >UK (+44)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">Phone number</label>
                                    <input class="form-control  @error('phone_number') is-invalid @enderror" type="text" name="phone_number" value="{{ old('phone_number', $user_billing_detail->phone_number) }}" placeholder="66123456">
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6" id="companyNumberField" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">VAT number</label>
                                    <input class="form-control  @error('company_number') is-invalid @enderror" type="text" name="company_number" id="company_number" value="{{ old('company_number', $user_billing_detail->company_number) }}" placeholder="VAT number">
                                    @error('company_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6" id="vatNumberField">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">
                                        VAT number
                                        @if(!empty($taxIdInfo->verification->status))
                                            @php
                                                $taxStatus = $taxIdInfo->verification->status;
                                                $clr = $title = '';
                                                $cls = 'bx-error-circle';
                                                if($taxStatus == 'pending') {
                                                    $clr = 'text-secondary';
                                                    $title = 'Pending Verification';
                                                } else if($taxStatus == 'verified') {
                                                    $cls = 'bx-check-circle';
                                                    $clr = 'text-success';
                                                    $title = 'Verified';
                                                } else if(in_array($taxStatus, ['unverified', 'unavailable'])) {
                                                    $clr = 'text-danger';
                                                    $title = ucfirst($taxStatus);
                                                }
                                            @endphp
                                            <i class="bx {{ $cls }} {{ $clr }}" title="{{ $title }}"></i>
                                        @endif
                                    </label>
                                    <input class="form-control  @error('vat_number') is-invalid @enderror" type="text" name="vat_number" id="vat_number" value="{{ old('vat_number', $user_billing_detail->vat_number) }}" placeholder="VAT number">
                                    @error('vat_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label custom_form_label">Coupon code</label>
                                    <input class="form-control" type="text" name="coupon_code" value="{{ (!empty($stripeCustomer->discount->coupon->id)) ? $stripeCustomer->discount->coupon->id : '' }}" placeholder="Coupon code">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="card-title col-md-8">
                                Payment Settings
                            </div>
                            <div class="col-md-4">
                                @if($user->hasPaymentMethod())
                                    <div class="mb-3 form-check form-switch form-switch-md float-end">
                                        <input class="form-check-input" type="checkbox" name="edit_pm_details" id="edit_pm_details" value="1">
                                        <label for="edit_pm_details" class="form-check-label">Change card</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($user->hasPaymentMethod())
                            <i class="bx bxl-{{ $user->pm_type }} me-1"></i>
                            {{ ucwords($user->pm_type) . ' (' . $user->pm_last_four . ')' }}
                        @endif
                        <div class="row" id="paymentSettingsDiv">
                            <div class="col-md-6">
                                <label class="form-label custom_form_label" for="card-holder-name">Card Holder Name</label>
                                <input id="card-holder-name" type="text" class="form-control">
                                <span class="stripe-error" id="card-name-errors" role="alert"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="card-element">Credit or debit card</label>
                                <div id="card-element" class="form-control"></div>
                                <!-- Used to display form errors. -->
                                <span class="stripe-error" id="card-errors" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stripe-errors"></div>

                <div class="row">

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Billable worker this month</h4>
                                <div class="table-responsive">
                                    <table class="table table-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Active workers :</th>
                                                <td align="right">
                                                    {{ (!empty($upcomingInvoice['lines']['data'][0]['quantity'])) ? $upcomingInvoice['lines']['data'][0]['quantity'] : $user->activeWorkersCount() }}
                                                </td>
                                            </tr>
                                            @if(!empty($upcomingInvoice))
                                                <tr>
                                                    <th scope="row">Active plan :</th>
                                                    <td align="right">{{ (!empty($activePlan->product->name)) ? $activePlan->product->name : '' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Next payment :</th>
                                                    <td align="right">
                                                        {{ formatStripeAmount($upcomingInvoice['total']) . ' on ' . date(dateFormat(), $upcomingInvoice['period_end']) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Subtotal :</th>
                                                    <td align="right">{{ formatStripeAmount($upcomingInvoice['subtotal']) }}</td>
                                                </tr>
                                                @if(!empty($upcomingInvoice['total_discount_amounts']))
                                                    @php
                                                        $discount = 0;
                                                        foreach($upcomingInvoice['total_discount_amounts'] as $t => $d_amt) {
                                                            $discount += $d_amt['amount'];
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <th scope="row">Discount :</th>
                                                        <td align="right">{{ '-' . formatStripeAmount($discount) }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th scope="row">VAT :</th>
                                                    <td align="right">{{ formatStripeAmount($upcomingInvoice['tax']) }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Total :</th>
                                                    <td align="right">{{ formatStripeAmount($upcomingInvoice['total']) }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!empty($stripeCustomer->discount->coupon))
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">
                                        Discount Coupon Details
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-nowrap mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Name :</th>
                                                    <td align="right">{{ $stripeCustomer->discount->coupon->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Duration :</th>
                                                    <td align="right">{{ ucwords($stripeCustomer->discount->coupon->duration) }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Type :</th>
                                                    <td align="right">
                                                        @if(!empty($stripeCustomer->discount->coupon->amount_off))
                                                            Amount off ({{ $stripeCustomer->discount->coupon->amount_off . ' ' . $stripeCustomer->discount->coupon->currency }})
                                                        @else
                                                            Percent off ({{ $stripeCustomer->discount->coupon->percent_off . '%' }})
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-info mt-3 js-disable">
                        <span id="btnText">Save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    @parent
    
    <script type="text/javascript" src="{{ env('PUBLIC_PATH') }}/js/libs/jquery.validate.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">

        var hasPM;
        $(document).ready(function() {
            hasPM = '{{ $user->hasPaymentMethod() }}';
            if(hasPM) {
                $('#paymentSettingsDiv').hide();
            } else {
                $('#paymentSettingsDiv').show();
            }
            $('#plan_name, #prev_plan_name').val($('.planTypes:checked').attr('data-planName'));
            $('#stripe_active_plan_name').val($('.planTypes:checked').attr('data-planTitle'));
            $('#country').trigger('change');
            changeBtnText();
            removeQueryString();
        });
        $(document).on('change', '#edit_pm_details', function(e) {
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#paymentSettingsDiv').show();
            } else {
                $('#paymentSettingsDiv').hide();
            }
            changeBtnText();
        });
        $(document).on('change', '.planTypes', function(e) {
            e.preventDefault();
            var planName = $('.planTypes:checked').attr('data-planName');
            var planTitle = $('.planTypes:checked').attr('data-planTitle');
            $('#plan_name').val(planName);
            $('#stripe_active_plan_name').val(planTitle);
            changeBtnText();
        });
        $(document).on('change', '#country', function(e) {
            e.preventDefault();
            var country = $(this).val();
            if(country == 'NO') {
                $('#companyNumberField').show();
                $('#vatNumberField').hide();
                $('#vat_number').val('');
            } else {
                $('#companyNumberField').hide();
                $('#vatNumberField').show();
                $('#company_number').val('');
            }
        });
        $(document).on('click', '#startFreeTrial', function(e) {
            e.preventDefault();
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: "{{ route('subscription.startFreeTrial') }}",
                    type: 'POST',
                    data: { "_token": "{{ csrf_token() }}" },
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                    },
                }).done(function(response) {
                    if(response.status == 'success') {
                        showMessage(response.message, response.status);
                        setTimeout(function(){
                            location.reload(); 
                        }, 1000);
                    }
                }).fail(function(jqXHR, textStatus) {
                    var errMsg = $.parseJSON(jqXHR.responseText);
                    errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                    showMessage(errMsg, 'danger');
                });
            }
        });
        $(document).on('click', '#cancelSubscription', function(e) {
            e.preventDefault();
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: "{{ route('subscription.cancelSubscription') }}",
                    type: 'POST',
                    data: { "_token": "{{ csrf_token() }}" },
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                    },
                }).done(function(response) {
                    if(response.status == 'success') {
                        showMessage(response.message, response.status);
                        setTimeout(function(){
                            location.reload(); 
                        }, 1000);
                    }
                }).fail(function(jqXHR, textStatus) {
                    var errMsg = $.parseJSON(jqXHR.responseText);
                    errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                    showMessage(errMsg, 'danger');
                });
            }
        });

        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        var card = elements.create('card', {hidePostalCode: true, style: style});
        card.mount('#card-element');
        const cardHolderName = document.getElementById('card-holder-name');
        const clientSecret = "{{ $intent->client_secret }}";

        $('#subscribe-form').submit(function(e) {
            e.preventDefault();
        }).validate({
            rules: {
                plan: "required",
                email: { email: true },
                address_line: "required",
                postal_code: "required",
                city: "required",
                phone_number: { digits: true }
            },
            messages: {
                plan: "Please select a plan.",
                email: { email: "Please enter valid email address." },
                address_line: "Please enter address line.",
                postal_code: "Please enter postal code.",
                city: "Please enter city.",
                phone_number: { digits: "Please enter valid phone number." }
    
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
                if (element.attr("name") == "plan" ) {
                    error.insertAfter('.plans-div');
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: async function(form) {
                var payment_method;
                if($('#edit_pm_details').is(':checked') || !hasPM) {
                    console.log("attempting");
                    const { setupIntent, error } = await stripe.confirmCardSetup(
                        clientSecret, {
                            payment_method: {
                                card: card,
                                billing_details: { name: cardHolderName.value }
                            }
                        }
                    );
                    var isCardDetailsValid = true;
                    if(cardHolderName.value == '') {
                        $('#card-holder-name').focus();
                        $('#card-name-errors').text('Please enter card holder name.');
                        isCardDetailsValid = false;
                    } else {
                        $('#card-name-errors').text('');
                    }
                    if (error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = error.message;
                        isCardDetailsValid = false;
                    } else {
                        $('#card-errors').text('');
                    }
                    if (!isCardDetailsValid) {
                        return false;
                    } else {
                        payment_method = setupIntent.payment_method;
                    }
                } else {
                    payment_method = '';
                }

                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method');
                hiddenInput.setAttribute('value', payment_method);
                form.appendChild(hiddenInput);
                
                form.submit();
            }
        });

        function changeBtnText() {
            var planName = $('.planTypes:checked').attr('data-planName');
            if($('#prev_plan_name').val() != planName || $('#edit_pm_details').is(':checked')) {
                $('#btnText').text('Pay');
            } else {
                $('#btnText').text('Save');
            }
        }

        function removeQueryString() {
            var uri = window.location.toString();
            if (uri.indexOf("?") > 0) {
                var clean_uri = uri.substring(0, uri.indexOf("?"));
                window.history.replaceState({}, document.title, clean_uri);
            }
        }
    </script>
@endsection
