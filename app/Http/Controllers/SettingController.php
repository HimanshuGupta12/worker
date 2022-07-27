<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        $categories = user()->company->toolCategories()->orderDefault()->paginate(25);
        $toolstorages = user()->company->storages()->orderDefault()->withCount(['tools', 'toolsNeedInventorization'])->paginate(25);
        $user = user();
        $company = $user->company;

        $page = 'create';
        $month_day = '';
        if (user()->company->inventorization) {
            $page = 'edit';
            $month_day = user()->company->inventorization->month_day;
        }
        $workers = user()->company->workers()->orderDefault()->get();
        $storages = user()->company->storages()->orderDefault()->get();
        $sms_message = '';
        if (user()->company->inventorization && user()->company->inventorization->sms_message) {
            $sms_message = user()->company->inventorization->sms_message;
        }
        return view('settings.index', compact('categories','toolstorages','user','company','month_day', 'page', 'workers', 'storages', 'sms_message'));
    }

    /*
     * Return view settings
     */
    public function getViewSettings(Request $request) {
        $validator = Validator::make($request->all(), [
            'module_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'danger', 'message' => $validator->getMessageBag()->first()], 422);
        } else {
            try {
                $data = $request->all();
                $module_name = $data['module_name'];
                $settings = getCustomCookie($module_name . '_settings_wrkr');
                $view = view($module_name . '.view-settings', compact('module_name', 'settings'))->render();
                return response()->json(['status' => 'success', 'view' => $view], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'danger', 'message' => $e->getMessage()], 400);
            }
        }
    }

    /*
     * Save view settings
     */
    public function saveViewSettings(Request $request) {
        $validator = Validator::make($request->all(), [
            'module_name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('danger', $validator->getMessageBag()->first());
        } else {
            try {
                $data = $request->all();
                $module_name = $data['module_name'];
                unset($data['module_name']);
                unset($data['_token']);
                $settings = json_encode($data);
                return redirect()->back()->with('success', 'View settings updated successfully')->withCookie(cookie()->forever($module_name . '_settings_wrkr', $settings));
            } catch (\Exception $e) {
                return redirect()->back()->with('danger', $e->getMessage());
            }
        }
    }


    public function hoursSettings()
    {

        $company = user()->company;
        $settings = $company->settings ?? [];
        $late = $settings['late']?? [];
        return view('settings.hours',[
            'late' => $late
        ]);
    }


    public function lateSubmissionUpdate(Request $request){
        $request->validate([
            'disableNotifications' => 'in:on,off',
            'maxDay' => 'integer',
            'message' => 'string|nullable'
        ]);
        $company = user()->company;
        $settings = $company->settings ?? [];
        $late = $settings['late']?? [];

        $late = $request->except('_token');
        $settings['late'] = $late;
        $company->settings = $settings;
        $company->save();

        return redirect()->back();
    }

}
