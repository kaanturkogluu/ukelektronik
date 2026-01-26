<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $faqs = DB::table('faqs')->get()->toArray();
        
        Schema::table('faqs', function (Blueprint $table) {
            $table->json('question_json')->nullable()->after('question');
            $table->json('answer_json')->nullable()->after('answer');
        });
        
        foreach ($faqs as $faq) {
            DB::table('faqs')
                ->where('id', $faq->id)
                ->update([
                    'question_json' => json_encode(['tr' => $faq->question ?? '', 'en' => $faq->question ?? '']),
                    'answer_json' => json_encode(['tr' => $faq->answer ?? '', 'en' => $faq->answer ?? '']),
                ]);
        }
        
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['question', 'answer']);
        });
        
        Schema::table('faqs', function (Blueprint $table) {
            $table->json('question')->after('id');
            $table->json('answer')->after('question');
        });
        
        $faqsWithJson = DB::table('faqs')->get();
        foreach ($faqsWithJson as $faq) {
            DB::table('faqs')
                ->where('id', $faq->id)
                ->update([
                    'question' => $faq->question_json,
                    'answer' => $faq->answer_json,
                ]);
        }
        
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['question_json', 'answer_json']);
        });
    }

    public function down(): void
    {
        $faqs = DB::table('faqs')->get()->toArray();
        
        Schema::table('faqs', function (Blueprint $table) {
            $table->text('question_temp')->after('id');
            $table->longText('answer_temp')->after('question_temp');
        });
        
        foreach ($faqs as $faq) {
            $question = is_string($faq->question) ? json_decode($faq->question, true) : ($faq->question ?? []);
            $answer = is_string($faq->answer) ? json_decode($faq->answer, true) : ($faq->answer ?? []);
            
            DB::table('faqs')
                ->where('id', $faq->id)
                ->update([
                    'question_temp' => is_array($question) ? ($question['tr'] ?? '') : '',
                    'answer_temp' => is_array($answer) ? ($answer['tr'] ?? '') : '',
                ]);
        }
        
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['question', 'answer']);
            $table->text('question')->after('id');
            $table->longText('answer')->after('question');
        });
        
        foreach ($faqs as $faq) {
            $question = is_string($faq->question) ? json_decode($faq->question, true) : ($faq->question ?? []);
            $answer = is_string($faq->answer) ? json_decode($faq->answer, true) : ($faq->answer ?? []);
            
            DB::table('faqs')
                ->where('id', $faq->id)
                ->update([
                    'question' => is_array($question) ? ($question['tr'] ?? '') : '',
                    'answer' => is_array($answer) ? ($answer['tr'] ?? '') : '',
                ]);
        }
        
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['question_temp', 'answer_temp']);
        });
    }
};
