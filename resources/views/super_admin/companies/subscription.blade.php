@extends('layouts.super_admin')

@section('head')
    <meta name="_token" content="{{ csrf_token() }}">
    @parent
    <link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="{{ env('PUBLIC_PATH') }}/css/worker-reports.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .rep_heading.Active {
            color: #19BB43;
            font-weight: 300;
        }
        .rep_heading.Completed {
            color: #E92727;
            font-weight: 300;
        }
        .select2{
            width: 100% !important;
        }
        input , textarea , select {
            width: 100% !important;
        }
        .form-switch-md .form-check-input {
            width: 40px !important;
        }
        .custom_form_label {
            margin-bottom: 6px;
            color: #667685;
            font-size: 13px;
            display: block;
        }
        .sub_head_title {
            font-size: 11px;
            color: #667685;
            display: block;
            margin-bottom: 10px;
        }
        div#datatable_filter {
            display: none;
        }
    </style>
@endsection

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-xl-4">

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Company/User Details</h4>
                    <div class="table-responsive">
                        <table class="table table-nowrap mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">Name :</th>
                                    <td>{{ $company->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email :</th>
                                    <td>{{ (!empty($company->user->email)) ? $company->user->email : '' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Active Plan :</th>
                                    <td>{{ $active_plan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        Coupon Details
                        @php
                            if(empty($stripeCustomer->discount->coupon)) {
                                $couponBtnText = 'Apply Coupon';
                                $couponBtnClr = 'primary';
                            } else {
                                $couponBtnText = 'Edit/Remove Coupon';
                                $couponBtnClr = 'warning';
                            }
                        @endphp
                        @if(!empty($stripeCustomer))
                            <button type="button" id="applyCoupon" class="btn btn-{{ $couponBtnClr }} waves-effect waves-light" style="float: right;">{{ $couponBtnText }}</button>
                        @endif
                    </h4>
                    @if(!empty($stripeCustomer->discount->coupon))
                        <div class="table-responsive">
                            <table class="table table-nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Name :</th>
                                        <td>{{ $stripeCustomer->discount->coupon->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Duration :</th>
                                        <td>{{ ucwords($stripeCustomer->discount->coupon->duration) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Type :</th>
                                        <td>
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
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Billing Details</h4>
                    <div class="table-responsive">
                        <table class="table table-nowrap mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">Company name :</th>
                                    <td>{{ ($user_billing_detail->company_name) ? $user_billing_detail->company_name : $user->company->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Billing email :</th>
                                    <td>{{ ($user_billing_detail->email) ? $user_billing_detail->email : $user->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Phone number :</th>
                                    <td>{{ ($user_billing_detail->phone_number) ? $user_billing_detail->phone_number : $user->phone_no }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">VAT number :</th>
                                    <td>
                                        {{ $user_billing_detail->vat_number }}
                                        @if(!empty($taxIdInfo->verification->status))
                                            &nbsp;({{ ucfirst($taxIdInfo->verification->status) }})
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Address line :</th>
                                    <td>{{ $user_billing_detail->address_line }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">City :</th>
                                    <td>{{ $user_billing_detail->city }}</td>
                                </tr>
                                <!-- <tr>
                                    <th scope="row">State :</th>
                                    <td>{{ $user_billing_detail->state }}</td>
                                </tr> -->
                                <tr>
                                    <th scope="row">Country :</th>
                                    <td>{{ $user_billing_detail->country }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Postal code :</th>
                                    <td>{{ $user_billing_detail->postal_code }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>         
        
        <div class="col-xl-8">

            <!-- <div class="row">
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-2">Completed Projects</p>
                                    <h4 class="mb-0">125</h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                        <span class="avatar-title">
                                            <i class="bx bx-check-circle font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-2">Pending Projects</p>
                                    <h4 class="mb-0">12</h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm mini-stat-icon rounded-circle bg-primary">
                                        <span class="avatar-title">
                                            <i class="bx bx-hourglass font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-2">Total Revenue</p>
                                    <h4 class="mb-0">$36,524</h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm mini-stat-icon rounded-circle bg-primary">
                                        <span class="avatar-title">
                                            <i class="bx bx-package font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @if(!empty($active_plan))
                            <button type="button" id="cancelSubscription" class="btn btn-danger waves-effect waves-light">Cancel Subscription</button>
                        @else
                            <button type="button" id="freeTrial" class="btn btn-warning waves-effect waves-light">
                                Free Trial 
                                @if(!empty($user->trial_ends_at))
                                    ({{ datetimeConversionTZ($user->trial_ends_at, 'user', dateTimeFormat()) }})
                                @endif
                            </button>
                        @endif
                        @if(empty($stripeCustomer))
                            <button type="button" id="createStripeCustomer" class="btn btn-primary waves-effect waves-light">Create Stripe Customer</button>
                        @endif

                        <div class="float-end">
                            <div class="mb-3 form-check form-switch form-switch-md">
                                <label for="disable_subscription" class="form-check-label">Disable Subscription</label>
                                <input class="form-check-input" type="checkbox" id="disable_subscription" value="1" {{ ($company->disable_subscription) ? 'checked' : '' }}>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Subscriptions History</h4>
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Plan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Started At</th>
                                    <th scope="col">Ended At</th>
                                    <th scope="col" class="text-center" width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($subscriptions))
                                    @foreach($subscriptions as $s => $subscription)
                                        <tr>
                                            <td>{{ ucfirst(str_replace('_', ' ', $subscription->name)) }}</td>
                                            <td>{{ strtoupper($subscription->stripe_status) }}</td>
                                            <td>{{ $subscription->created_at->format(dateTimeFormat()) }}</td>
                                            <td>{{ (!empty($subscription->ends_at)) ? $subscription->ends_at->format(dateTimeFormat()) : '' }}</td>
                                            <td class="text-center">
                                                <button type="button" id="viewSubscriptionDetails" data-subscriptionID="{{ $subscription->stripe_id }}" data-subscriptionItemID="{{ $subscription->items[0]->stripe_id }}" data-subscriptionName="{{ $subscription->name }}" class="btn btn-outline-info btn-sm" title="View Details">
                                                    <i class="bx bx-show-alt bxicon"></i>
                                                    <i class="bx bx-loader bx-spin font-size-16 align-middle bxloader" style="display: none;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Invoices</h4>
                    <div class="table-responsive">
                        <table class="table table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Billing reason</th>
                                    <th scope="col" class="text-center" width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($invoices))
                                    @foreach($invoices as $s => $invoice)
                                        <tr>
                                            <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                                            <td>{{ $invoice->total() }}</td>
                                            <td>{{ strtoupper($invoice->status) }}</td>
                                            <td>{{ ucwords(str_replace('_', ' ', $invoice->billing_reason)) }}</td>
                                            <td class="text-center">
                                                <a target="_blank" href="{{ $invoice->hosted_invoice_url }}" class="btn btn-outline-info btn-sm" title="View Details">
                                                    <i class="bx bx-show-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end row -->
    
</div>

<div class="modal fade" id="freeTrialModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Free Trial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="freeTrialForm">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label custom_form_label">Trial ends at</label>
                                <input type="datetime-local" name='trial_ends_at' value="{{ (!empty($user->trial_ends_at)) ? datetimeConversionTZ($user->trial_ends_at, 'user', 'Y-m-d H:i') : '' }}" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="applyCouponModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Coupon Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="applyCouponForm">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label custom_form_label">Coupon ID</label>
                                <input type="text" name='coupon_id' value="{{ (!empty($stripeCustomer->discount->coupon->id)) ? $stripeCustomer->discount->coupon->id : '' }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewSubscriptionDetailsModal" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subscription Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="subscriptionDetailsDiv"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
    @parent

    <script type="text/javascript">
        $(document).on('click', '#freeTrial', function(e) {
            e.preventDefault();
            $('#freeTrialModal').modal('show');
        });
        $(document).on('submit', '#freeTrialForm', function(e) {
            e.preventDefault();
            var formData = $(this).serializeArray();
            $.ajax({
                url: "{{ route('sa.companies.freeTrial') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                },
            }).done(function(response) {
                if(response.status == 'success') {
                    $("#freeTrialModal").modal('hide');
                    setTimeout(function() { location.reload(); }, 1000);
                }
                showMessage(response.message, response.status);
            }).fail(function(jqXHR, textStatus) {
                var errMsg = $.parseJSON(jqXHR.responseText);
                errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                showMessage(errMsg, 'danger');
            });
        });
        $(document).on('click', '#cancelSubscription', function(e) {
            e.preventDefault();
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: "{{ route('sa.companies.cancelSubscription') }}",
                    type: 'POST',
                    data: { "_token": "{{ csrf_token() }}", "user_id": "{{ $user->id }}" },
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                    },
                }).done(function(response) {
                    if(response.status == 'success') {
                        setTimeout(function() { location.reload();  }, 1000);
                    }
                    showMessage(response.message, response.status);
                }).fail(function(jqXHR, textStatus) {
                    var errMsg = $.parseJSON(jqXHR.responseText);
                    errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                    showMessage(errMsg, 'danger');
                });
            }
        });
        $(document).on('click', '#applyCoupon', function(e) {
            e.preventDefault();
            $('#applyCouponModal').modal('show');
        });
        $(document).on('submit', '#applyCouponForm', function(e) {
            e.preventDefault();
            var formData = $(this).serializeArray();
            $.ajax({
                url: "{{ route('sa.companies.applyCoupon') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                },
            }).done(function(response) {
                if(response.status == 'success') {
                    $("#applyCouponModal").modal('hide');
                    setTimeout(function() { location.reload(); }, 1000);
                }
                showMessage(response.message, response.status);
            }).fail(function(jqXHR, textStatus) {
                var errMsg = $.parseJSON(jqXHR.responseText);
                errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                showMessage(errMsg, 'danger');
            });
        });
        $(document).on('click', '#createStripeCustomer', function(e) {
            e.preventDefault();
            if (confirm('Are you sure?')) {
                $.ajax({
                    url: "{{ route('sa.companies.createStripeCustomer') }}",
                    type: 'POST',
                    data: { "_token": "{{ csrf_token() }}", "user_id": "{{ $user->id }}" },
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                    },
                }).done(function(response) {
                    if(response.status == 'success') {
                        setTimeout(function() { location.reload();  }, 1000);
                    }
                    showMessage(response.message, response.status);
                }).fail(function(jqXHR, textStatus) {
                    var errMsg = $.parseJSON(jqXHR.responseText);
                    errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                    showMessage(errMsg, 'danger');
                });
            }
        });
        $(document).on('click', '#viewSubscriptionDetails', function(e) {
            e.preventDefault();
            var _this = $(this);
            var subscriptionID = $(this).attr('data-subscriptionID');
            var subscriptionName = $(this).attr('data-subscriptionName');
            var subscriptionItemID = $(this).attr('data-subscriptionItemID');
            if(subscriptionID) {
                _this.find('.bxicon').hide();
                _this.find('.bxloader').show();
                $.ajax({
                    url: "{{ route('sa.companies.stripeSubscriptionDetails') }}",
                    type: 'POST',
                    data: { subscription_id: subscriptionID, subscription_name: subscriptionName, subscription_item_id: subscriptionItemID },
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                    },
                }).done(function(response) {
                    if(response.status == 'success') {
                        $("#subscriptionDetailsDiv").html(response.data);
                        $('#viewSubscriptionDetailsModal').modal('show');
                    }
                    _this.find('.bxicon').show();
                    _this.find('.bxloader').hide();
                }).fail(function(jqXHR, textStatus) {
                    var errMsg = $.parseJSON(jqXHR.responseText);
                    errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                    showMessage(errMsg, 'danger');
                    _this.find('.bxicon').show();
                    _this.find('.bxloader').hide();
                });
            }
        });
        $('#viewSubscriptionDetailsModal').on('hidden.bs.modal', function () {
            $("#subscriptionDetailsDiv").html('');
        });
        $(document).on('change', '#disable_subscription', function(e) {
            e.preventDefault();
            if (confirm('Are you sure?')) {
                var disable_subscription = ($('#disable_subscription').is(':checked')) ? 1 : 0;
                $.ajax({
                    url: "{{ route('sa.companies.setSubscriptionStatus') }}",
                    type: 'POST',
                    data: { "_token": "{{ csrf_token() }}", disable_subscription: disable_subscription, company_id: '{{ $company->id }}' },
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content'),
                    },
                }).done(function(response) {
                    showMessage(response.message, response.status);
                }).fail(function(jqXHR, textStatus) {
                    var errMsg = $.parseJSON(jqXHR.responseText);
                    errMsg = (errMsg.message) ? errMsg.message : jqXHR.statusText;
                    showMessage(errMsg, 'danger');
                });
            } else {
                if($('#disable_subscription').is(':checked')) {
                    $('#disable_subscription').prop('checked', false);
                } else {
                    $('#disable_subscription').prop('checked', true);
                }
            }
        });
    </script>
@endsection