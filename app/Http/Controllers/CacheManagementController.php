<?php

namespace App\Http\Controllers;

use Artisan;

class CacheManagementController extends Controller
{
    public function index()
    {
        return view('cache-management');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');

        return redirect()->route('cache.management')->with('success', 'Cache Cleared Successfully.');
    }

    public function clearViewCache()
    {
        Artisan::call('view:clear');

        return redirect()->route('cache.management')->with('success', 'View Cache Cleared Successfully.');
    }

    public function clearConfigCache()
    {
        Artisan::call('config:clear');

        return redirect()->route('cache.management')->with('success', 'Config Cache Cleared Successfully.');
    }

    public function dumpAutoload()
    {
        exec('composer dump-autoload');

        return redirect()->route('cache.management')->with('success', 'Autoload Dumped Successfully.');
    }
}
