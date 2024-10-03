<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    /**
     * Send a request to the ChatGPT API and get a completion.
     *
     * @param string $prompt
     * @param string $model
     * @param int $maxTokens
     * @return string
     */
    public function generateText(string $prompt, string $model = 'gpt-3.5-turbo', int $maxTokens = 150): string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/completions', [
            'model' => $model,
            'prompt' => $prompt,
            'max_tokens' => $maxTokens,
        ]);

        if ($response->successful()) {
            return $response->json('choices.0.text');
        }

        return 'Error: ' . $response->body();
    }
}
