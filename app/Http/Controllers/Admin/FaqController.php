<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->paginate(15);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_tr' => 'required|string',
            'question_en' => 'required|string',
            'answer_tr' => 'required|string',
            'answer_en' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        // Prepare translations
        $validated['question'] = json_encode([
            'tr' => $validated['question_tr'],
            'en' => $validated['question_en']
        ]);
        $validated['answer'] = json_encode([
            'tr' => $validated['answer_tr'],
            'en' => $validated['answer_en']
        ]);
        
        unset($validated['question_tr'], $validated['question_en'], $validated['answer_tr'], $validated['answer_en']);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'SSS başarıyla oluşturuldu.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question_tr' => 'required|string',
            'question_en' => 'required|string',
            'answer_tr' => 'required|string',
            'answer_en' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        // Prepare translations
        $validated['question'] = json_encode([
            'tr' => $validated['question_tr'],
            'en' => $validated['question_en']
        ]);
        $validated['answer'] = json_encode([
            'tr' => $validated['answer_tr'],
            'en' => $validated['answer_en']
        ]);
        
        unset($validated['question_tr'], $validated['question_en'], $validated['answer_tr'], $validated['answer_en']);

        $validated['is_active'] = $request->has('is_active');

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'SSS başarıyla güncellendi.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'SSS başarıyla silindi.');
    }
}

