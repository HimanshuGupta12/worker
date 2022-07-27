<div class="row">
    <div class="col-md-12">
        @if(!empty($subscription))
            <div class="table-responsive">
                <table class="table table-nowrap mb-0">
                    <tbody>
                        <tr>
                            <th scope="row">Plan :</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $subscription_name)) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Status :</th>
                            <td>{{ strtoupper($subscription->status) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Started At :</th>
                            <td>{{ date(dateTimeFormat(), $subscription->created) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Ended At :</th>
                            <td>{{ (!empty($subscription->ended_at)) ? date(dateTimeFormat(), $subscription->ended_at) : '' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Period :</th>
                            <td>{{ date(dateTimeFormat(), $subscription->current_period_start) . ' to ' . date(dateTimeFormat(), $subscription->current_period_end) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Max Usage :</th>
                            <td>{{ $total_usage }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Pricing Tiers</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
        <div class="col-md-12">
            <!-- <h5 style="margin-top: 0.5rem;">Pricing Tiers</h5> -->
            <div class="table-responsive">
                <table class="table table-nowrap table-hover mb-0">
                    <thead>
                        <tr>
                            <th>QUANTITY</th>
                            <th>PRICE PER UNIT</th>
                            <th>FLAT AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($subscription->plan->tiers))
                            @foreach($subscription->plan->tiers as $t => $tier)
                                <tr>
                                    <td>
                                        @if(!empty($tier->up_to))
                                            {{ 'Upto ' . $tier->up_to }}
                                        @else
                                            {{ 'More' }}
                                        @endif
                                    </td>
                                    <td>{{ '€' . ($tier->unit_amount / 100) }}</td>
                                    <td>{{ '€' . ($tier->flat_amount / 100) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    <div>
</div>