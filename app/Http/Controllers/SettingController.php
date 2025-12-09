<?php

namespace App\Http\Controllers;

use App\Models\SmsProvider;
use App\Models\SmsTemplate;
use App\Models\SmsTrigger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        $triggers = SmsTrigger::allTriggers();
        $smstems = SmsTemplate::where('group_id', $user->group_id)->get();
        if ($user->hasRole(Config('rolesConstant.ADMIN'))) {
            return view('setting.index', compact('user', 'triggers', 'smstems'));
        } else {
            return view('/restricted')->with('error', 'Direct URL access is restricted.');
        }
    }


    public function store(Request $request)
    {
        try {
            $user = Session::get('user');

            $request->validate([
                'name' => 'required',
                'base_url' => 'required',
                'method' => 'required|in:GET,POST',
            ]);
            $urlParts = parse_url($request->base_url);
            parse_str($urlParts['query'] ?? '', $queryParams);
            $params = [];
            foreach ($queryParams as $key => $value) {
                if (str_contains(strtolower($key), 'phone')) {
                    $params[$key] = '{{to}}';
                } elseif (str_contains(strtolower($key), 'message') || str_contains(strtolower($key), 'sms')) {
                    $params[$key] = '{{message}}';
                } else {
                    $params[$key] = $value;
                }
            }
            $cleanBaseUrl = strtok($request->base_url, '?');

            SmsProvider::create([
                'group_id' => $user->group_id,
                'name' => $request->name,
                'base_url' => $cleanBaseUrl,
                'method' => $request->method,
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'headers' => null,
                'active' => $request->has('active'),
            ]);

            return redirect()->back()->with('success', 'SMS Provider saved successfully!');
        } catch (\Exception $e) {
            Log::error('Error SMS Provider saved: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function storeSmsTemplete(Request $request)
    {
        try {
            $user = Session::get('user');
            $request->validate([
                'name' => 'required',
                'message' => 'required',
            ]);

            SmsTemplate::create([
                'group_id' => $user->group_id,
                'name' => $request->name,
                'message' => $request->message,
            ]);
            return redirect()->back()->with('success', 'SMS Templete saved successfully!');
        } catch (\Exception $e) {
            Log::error('Error SMS Provider saved: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function getTemplete($id)
    {
        $smstem = SmsTemplate::find($id);
        return response()->json($smstem);
    }


    public function assignTemplate(Request $request)
    {
        try {
            $user = Session::get('user');
            $request->validate([
                'trigger_name' => 'required|string|max:255',
                'sms_template_id' => 'required|exists:sms_templates,id',
                'message' => 'required',
            ]);
            SmsTrigger::create([
                'group_id' => $user->group_id,
                'trigger_name' => $request->trigger_name,
                'sms_template_id' => $request->sms_template_id,
            ]);

            $smstemplete = SmsTemplate::find($request->sms_template_id);

            if ($smstemplete) {
                $smstemplete->message = $request->message;
                $smstemplete->save();
            }
            return redirect()->back()->with('success', 'SMS Template assigned successfully!');
        } catch (\Exception $e) {
            Log::error('Error assigning SMS Template: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Something went wrong while saving the SMS Template.');
        }
    }
}
