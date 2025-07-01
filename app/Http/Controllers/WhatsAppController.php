<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    public function enviar(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|string',
            'mensaje' => 'required|string',
        ]);

        $response = Http::post('http://localhost:3000/send-message', [
            'number' => $validated['numero'],
            'message' => $validated['mensaje'],
        ]);

        return response()->json($response->json(), $response->status());
    }
}
