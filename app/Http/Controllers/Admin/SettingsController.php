<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $settings = SiteSetting::orderBy('group')->orderBy('sort_order')->get()->groupBy('group');
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $values = $request->input('settings', []);
        foreach ($values as $key => $value) {
            $row = SiteSetting::where('key', $key)->first();
            if (! $row) {
                continue;
            }
            $row->value = is_array($value) ? json_encode($value) : (string) $value;
            $row->save();
        }
        SiteSetting::flushCache();
        return back()->with('success', 'Settings saved.');
    }
}
