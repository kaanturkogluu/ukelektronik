<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = [
            'phones' => ['group' => 'contact', 'type' => 'json'],
            'emails' => ['group' => 'contact', 'type' => 'json'],
            'display_phone' => ['group' => 'contact', 'type' => 'text'],
            'display_email' => ['group' => 'contact', 'type' => 'text'],
            'address' => ['group' => 'contact', 'type' => 'text'],
            'product_whatsapp' => ['group' => 'contact', 'type' => 'text'],
            'map_iframe' => ['group' => 'contact', 'type' => 'textarea'],
            'facebook' => ['group' => 'social', 'type' => 'url'],
            'twitter' => ['group' => 'social', 'type' => 'url'],
            'linkedin' => ['group' => 'social', 'type' => 'url'],
            'instagram' => ['group' => 'social', 'type' => 'url'],
            'site_title' => ['group' => 'general', 'type' => 'text'],
            'site_description' => ['group' => 'general', 'type' => 'textarea'],
        ];

        // Handle phones
        if ($request->has('phones_json')) {
            $phonesJson = $request->input('phones_json');
            $phones = json_decode($phonesJson, true);
            if (is_array($phones)) {
                Setting::set('phones', json_encode($phones), 'json', 'contact');
            }
        } elseif ($request->has('phone_numbers') && $request->has('phone_types')) {
            // New format: phone_numbers[] and phone_types[] arrays
            $phoneNumbers = $request->input('phone_numbers', []);
            $phoneTypes = $request->input('phone_types', []);
            $phones = [];
            
            foreach ($phoneNumbers as $index => $number) {
                $number = trim($number);
                if (!empty($number)) {
                    $type = isset($phoneTypes[$index]) ? $phoneTypes[$index] : 'phone';
                    $phones[] = [
                        'number' => $number,
                        'type' => $type
                    ];
                }
            }
            
            Setting::set('phones', json_encode($phones), 'json', 'contact');
        } elseif ($request->has('phones')) {
            // Old format: simple array of phone numbers
            $phones = $request->input('phones', []);
            $phones = array_filter(array_map('trim', $phones));
            // Convert to new format
            $phonesArray = [];
            foreach ($phones as $phone) {
                $phonesArray[] = [
                    'number' => $phone,
                    'type' => 'phone'
                ];
            }
            Setting::set('phones', json_encode($phonesArray), 'json', 'contact');
        }

        // Handle emails
        if ($request->has('emails_json')) {
            $emailsJson = $request->input('emails_json');
            $emails = json_decode($emailsJson, true);
            if (is_array($emails)) {
                Setting::set('emails', json_encode($emails), 'json', 'contact');
            }
        } elseif ($request->has('emails')) {
            $emails = $request->input('emails', []);
            $emails = array_filter(array_map('trim', $emails));
            Setting::set('emails', json_encode(array_values($emails)), 'json', 'contact');
        }

        // Handle display_phone
        if ($request->has('display_phone')) {
            Setting::set('display_phone', $request->input('display_phone'), 'text', 'contact');
        }

        // Handle display_email
        if ($request->has('display_email')) {
            Setting::set('display_email', $request->input('display_email'), 'text', 'contact');
        }

        // Handle other settings
        foreach ($request->except(['_token', '_method', 'phones', 'emails', 'phones_json', 'emails_json', 'phone_numbers', 'phone_types']) as $key => $value) {
            if (isset($settings[$key])) {
                Setting::set($key, $value, $settings[$key]['type'], $settings[$key]['group']);
            } else {
                Setting::set($key, $value);
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}

