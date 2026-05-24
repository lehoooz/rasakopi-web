<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LandingController extends Controller
{
    public function index()
    {
        // Jika staff (admin/kasir) sudah login, langsung arahkan ke panel masing-masing
        if (Auth::check()) {
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect('/admin');
            }

            if ($role === 'kasir') {
                return redirect('/kasir/pos');
            }
        }

        $products = Product::where('status', 'available')->get();
        return view('landing', compact('products'));
    }

    public function aiRecommendation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $name = $request->name;
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json(['error' => 'API Key Gemini tidak terdeteksi di berkas .env Anda.'], 500);
        }

        // Prompt murni alami dan umum untuk segala jenis menu (makanan maupun minuman)
        $prompt = "You are an expert culinary and beverage sommelier. Analyze this menu item: '{$name}' (which could be a coffee, tea, beverage, pastry, cake, main dish, or snack).
Generate a detailed, premium, and professional description in Indonesian.
Your response MUST be a valid JSON object with the following exact keys:
{
  \"description\": \"A beautiful, premium 2-3 sentence culinary/sommelier description in Indonesian describing the menu item's ingredients, taste profile, aroma, and overall sensory experience.\"
}
Return only the JSON object.";

        try {
            // Memaksa DNS resolve menggunakan IPv4 untuk mengatasi bug timeout cURL pada Windows/Laragon
            $response = Http::withOptions([
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
                ]
            ])->timeout(15)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($text) {
                    $jsonData = json_decode($text, true);
                    if ($jsonData) {
                        return response()->json($jsonData);
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Koneksi internet lambat atau terputus. Silakan coba beberapa saat lagi.'], 500);
        }

        return response()->json(['error' => 'Gagal mendapatkan analisis yang valid dari Gemini API.'], 500);
    }
}
