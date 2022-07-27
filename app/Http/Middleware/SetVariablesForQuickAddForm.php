<?php

namespace App\Http\Middleware;

//use App\Models\WorkerPosition;
use App\Models\WorkerPosition;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SetVariablesForQuickAddForm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(user()){
            $customWorkerPositions = user()->company->customWorkerPosition()->get();
            $defaultWorkerPositions = WorkerPosition::where('company_id', null)->get();

            View::share([
                'projectsForQuickMode' => user()->company->projects()->where('status', 'active')->orderBy('created_at','DESC')->get(),
                'workersForQuickMode' => user()->company->workers()->orderDefault()->get(),
                'clientsForQuickMode' => user()->company->clients()->orderDefault()->get(),
                'customWorkerPositions' => $customWorkerPositions,
                'defaultWorkerPositions' => $defaultWorkerPositions,
            ]);
        }
        return $next($request);
    }
}
