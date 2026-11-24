<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class JokeController extends Controller
{
    /**
     * Show the joke generator page
     */
    public function index()
    {
        return view('joke');
    }

    /**
     * Fetch a random joke from the external API
     */
    public function fetchJoke(Request $request)
    {
        try {
            // Fetch from JokeAPI
            $response = Http::timeout(10)
                ->get('https://official-joke-api.appspot.com/random_joke');

            if ($response->successful()) {
                $joke = $response->json();

                return response()->json([
                    'success' => true,
                    'setup' => $joke['setup'] ?? 'Why did the programmer?',
                    'punchline' => $joke['punchline'] ?? 'Because they wanted to solve the problem!',
                    'type' => $joke['type'] ?? 'general',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch joke',
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
