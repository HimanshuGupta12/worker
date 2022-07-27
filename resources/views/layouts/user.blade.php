<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.partial.head')
    <title>@yield('title', 'Worker')</title>
    @yield('head')
    @stack('headStack')
</head>
<body data-sidebar="dark">


<!-- Add Quick Worker -->
@if(request()->url() != route('workers.create') && request()->route()->getName() != 'workers.edit' )
    <div class="modal fade" id="addQuickWorker" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" style="max-width: 1200px !important">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myExtraLargeModalLabel2">Add worker</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $e = new \Illuminate\Support\ViewErrorBag();
                        if(session()->has('errors')){
                            $e = $errors ? $errors->getBag('workerQuickModalError') : new \Illuminate\Support\ViewErrorBag() ;
                        }

                    @endphp
                    @include('workers.the_form', [
                        'workerInfoSubmitUrl' => route('workers.store'),
                        'quickMode' => true,
                        'errorMessages' => $e->getMessages(),
                        'worker' => new \App\Models\Worker(),
                        'projects' => $projectsForQuickMode,
                    ])
                </div>
            </div>
        </div>
    </div>
@endif


@if(request()->url() != route('projects.create') && request()->route()->getName() != 'projects.edit')
    <!-- Add Quick Project -->
    <div class="modal fade" id="addQuickProject" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" style="max-width: 1200px !important">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myExtraLargeModalLabel2">Add project</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $e = new \Illuminate\Support\ViewErrorBag();
                        if(session()->has('errors')){
                            $e = $errors ? $errors->getBag('workerQuickModalError') : new \Illuminate\Support\ViewErrorBag() ;
                        }

                    @endphp
                    @include('projects.the_form', [
                        'workerInfoSubmitUrl' => route('workers.store'),
                        'project' => new \App\Models\Project(),
                        'errorMessages' => $e->getMessages(),
                        'day_duration' => null, 'worked_duration' =>null,
                        'client' => new \App\Models\Client(),
                        'url' => route('projects.store'),
                        'page' => 'create',
                        'quickMode' => true,
                        'workers' => $workersForQuickMode,
                        'clients' => $clientsForQuickMode

                    ])
                </div>
            </div>
        </div>
    </div>
@endif

@if($subscription_alert && !in_array(request()->route()->getName(), ['subscription.show', 'subscription.invoice', 'workers.index', 'workers.create', 'workers.edit']))
    <div class="modal fade" role="dialog" data-bs-backdrop="static" id="subscriptionAlertModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="myExtraLargeModalLabel2">Subscribe plan</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        You are not subscribed.&nbsp;
                        Please subscribe to a plan.
                    </p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('subscription.show') }}">
                        <button type="button" class="btn btn-info waves-effect waves-light">Subscribe</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<div id="layout-wrapper">
    @if (Auth::check())
        @include('layouts.partial.user_top_menu')
        @include('layouts.partial.user_left_menu')
    @endif
    <div class="main-content">
        <div class="page-content">
            @include('partial.messages')
            @yield('content')
        </div>
    </div>
</div>




@include('layouts.partial.scripts')
@yield('scripts')
@stack('scriptsStack')
<script type="text/javascript">
    $(document).ready(function() {
        $("#subscriptionAlertModal").modal('show');
    });
    if ('caches' in window) {
        caches.keys()
          .then(function(keyList) {
              return Promise.all(keyList.map(function(key) {
                  return caches.delete(key);
              }));
          })
      } 
</script>
</body>
</html>
