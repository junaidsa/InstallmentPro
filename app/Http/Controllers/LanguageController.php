<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLanguage(Request $request)
    {
        $locale = $request->input('locale');

        Session::put('locale', $locale);

        if (Auth::check()) {
            Auth::user()->update(['lang' => $locale]);
        }

        return redirect()->back();
    }
}
