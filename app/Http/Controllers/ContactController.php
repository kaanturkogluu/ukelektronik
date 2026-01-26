<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        // Get phones from settings
        $phonesJson = Setting::get('phones', '[]');
        $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
        if (!is_array($phones)) $phones = [];
        
        // Convert old format to new format if needed
        if (!empty($phones) && is_string($phones[0] ?? null)) {
            $phones = array_map(function($phone) {
                return ['number' => $phone, 'type' => 'phone'];
            }, $phones);
        }
        
        // Get emails from settings
        $emailsJson = Setting::get('emails', '[]');
        $emails = is_string($emailsJson) ? json_decode($emailsJson, true) : [];
        if (!is_array($emails)) $emails = [];
        
        // Get address from settings
        $address = Setting::get('address', 'Dörtyol, Hatay, Türkiye');
        
        // Get map iframe from settings
        $mapIframe = Setting::get('map_iframe', '');
        
        return view('contact', compact('phones', 'emails', 'address', 'mapIframe'));
    }
}
