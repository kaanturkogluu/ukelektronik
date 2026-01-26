<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Models\Project;
use App\Models\Faq;
use App\Models\Slider;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'services' => Service::count(),
            'projects' => Project::count(),
            'faqs' => Faq::count(),
            'sliders' => Slider::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

