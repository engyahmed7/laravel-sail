<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;

class ChatGPTController extends Controller
{
    //
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    /**
     * Handle the request to generate text using ChatGPT.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $prompt = $request->input('prompt');
        $responseText = $this->openAIService->generateText($prompt);

        return response()->json([
            'response' => $responseText,
        ]);
    }
}
