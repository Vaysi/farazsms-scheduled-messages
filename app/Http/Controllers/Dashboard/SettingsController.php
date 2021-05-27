<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        return view('Settings.index');
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $k => $v){
            option($k,$v);
        }
        alert()->success('تنظیمات با موفقیت ذخیره شد','موفق');
        return redirect(route('site.index'));
    }
}
