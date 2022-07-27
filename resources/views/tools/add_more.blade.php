@extends(Auth::check() ? 'layouts.user' : 'layouts.worker')
@section('head')
    @parent
    <link href="/skote/assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <div class="container">
        <div class="row g-3">
            <div class="col-md-6 col-sm-12" style="text-align: center; margin-top: 30px">
                <a href="{{ route('scan', ['redirect' => route('tools.create')]) }}" class="align-center">
                    <button type="button" class="btn btn-primary" style="color: #fff;">{{__('Add more tools')}}</button>
                </a>
            </div>
            @if ($worker->add_tool || Auth::check())
                <div class="col-md-6 col-sm-12" style="text-align: center; margin-top: 30px">
                    <a href="{{ route('tools.index') }}" class=" align-center">
                        <button type="button" class="btn btn-secondary" style="background-color: #74788d; color: #fff;">{{__('See all tools')}}</button>
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
