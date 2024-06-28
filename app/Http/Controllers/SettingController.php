<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Show the form for editing the specified setting.
     */
    public function edit($setting)
    {
        $setting = Setting::findOrFail($setting);

        return inertia('Settings/Form', ['setting' => $setting]);
    }

    /**
     * Update the specified setting in storage.
     */
    public function update(SettingRequest $request, $setting)
    {
        $setting = Setting::findOrFail($setting);

        $valid = $request->validated();

        if ($request->hasFile('logo'))
        {
            if ($setting->getRawOriginal('logo') && Storage::exists($setting->getRawOriginal('logo')))
                Storage::delete($setting->getRawOriginal('logo'));

            $file = $request->file('logo');
            $path = $file->storePublicly('logos', 'public');

            $valid['logo'] = $path;
        }
        else
            $valid = Arr::except($valid, 'logo');

        if ($request->hasFile('favicon'))
        {
            if ($setting->getRawOriginal('favicon') && Storage::exists($setting->getRawOriginal('favicon')))
                Storage::delete($setting->getRawOriginal('favicon'));

            $file = $request->file('favicon');
            $path = $file->storePublicly('logos', 'public');

            $valid['favicon'] = $path;
        }
        else
            $valid = Arr::except($valid, 'favicon');

        if ($request->hasFile('footer_logo'))
        {
            if ($setting->getRawOriginal('footer_logo') && Storage::exists($setting->getRawOriginal('footer_logo')))
                Storage::delete($setting->getRawOriginal('footer_logo'));

            $file = $request->file('footer_logo');
            $path = $file->storePublicly('logos', 'public');

            $valid['footer_logo'] = $path;
        }
        else
            $valid = Arr::except($valid, 'footer_logo');

        if ($request->hasFile('portal_logo'))
        {
            if ($setting->getRawOriginal('portal_logo') && Storage::exists($setting->getRawOriginal('portal_logo')))
                Storage::delete($setting->getRawOriginal('portal_logo'));

            $file = $request->file('portal_logo');
            $path = $file->storePublicly('logos', 'public');

            $valid['portal_logo'] = $path;
        }
        else
            $valid = Arr::except($valid, 'portal_logo');

        $setting->update($valid);

        return to_route('settings.edit', $setting)->with('success', 'Updated Successfully');
    }
}
