<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switch(Request $request, $locale)
    {
        // Validate locale
        if (!in_array($locale, ['en', 'si'])) {
            return redirect()->back()->with('error', 'Invalid language selected.');
        }
        
        // Store locale in session
        Session::put('locale', $locale);
        
        // Set application locale
        App::setLocale($locale);
        
        // Debug: Log the language switch
        \Log::info('Language switched to: ' . $locale);
        
        return redirect()->back()->with('success', 'Language switched successfully.');
    }
    
    /**
     * Get current language
     */
    public function current()
    {
        return response()->json([
            'locale' => App::getLocale(),
            'name' => $this->getLanguageName(App::getLocale())
        ]);
    }
    
    /**
     * Get available languages
     */
    public function available()
    {
        return response()->json([
            'languages' => [
                'en' => [
                    'code' => 'en',
                    'name' => 'English',
                    'native' => 'English'
                ],
                'si' => [
                    'code' => 'si',
                    'name' => 'Sinhala',
                    'native' => 'සිංහල'
                ]
            ]
        ]);
    }
    
    /**
     * Get language name by code
     */
    private function getLanguageName($code)
    {
        $languages = [
            'en' => 'English',
            'si' => 'Sinhala'
        ];
        
        return $languages[$code] ?? 'English';
    }
}