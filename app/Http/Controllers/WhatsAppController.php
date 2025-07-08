<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\WhatsAppLog;
use App\Models\IncomingMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class WhatsAppController extends Controller
{
    private $apiUrl = 'http://localhost:3000'; // URL del servicio Node.js

    public function qr()
    {
        $response = Http::get("{$this->apiUrl}/qr");
        return $response->json();
    }

    public function sendMessage(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $response = Http::post("{$this->apiUrl}/send-message", $data);
        
        WhatsAppLog::create([
            'phone' => $data['phone'] ,
            'message' => $data['message'] ,
            'type' => 'texto',
            'response' => json_encode($response->json())
        ]);
        
        return $response->json();
    }

    public function sendBulkMessages(Request $request)
    {
        $data = $request->validate([
            'phones' => 'required|array',
            'message' => 'required|string',
        ]);

        $response = Http::post("{$this->apiUrl}/send-bulk-messages", $data);
        WhatsAppLog::create([
            'phone' => 'bulk',
            'message' => $data['message'] ,
            'type' => 'masivo_texto',
            'response' => json_encode($response->json())
        ]);
        return $response->json();
    }

    public function sendMedia(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'caption' => 'nullable|string',
            'file' => 'required|file', // 50MB
        ]);

        $response = Http::attach(
            'file', file_get_contents($request->file('file')), $request->file('file')->getClientOriginalName()
        )->post("{$this->apiUrl}/send-proper-media", [
            'phone' => $request->input('phone'),
            'caption' => $request->input('caption', '')
        ]);

        WhatsAppLog::create([
            'phone' => $request->input('phone'),
            'message' => $request->input('caption', ''),
            'type' => 'multimedia',
            'response' => json_encode($response->json())
        ]);

        return $response->json();
    }

    public function sendBulkMedia(Request $request)
    {
        $request->validate([
            'phones' => 'required|array',
            'caption' => 'nullable|string',
            'file' => 'required|file|max:51200',
        ]);

        try {
            $response = Http::attach(
                'file',
                file_get_contents($request->file('file')),
                $request->file('file')->getClientOriginalName()
            )->post("{$this->apiUrl}/send-proper-phones-media", [
                'phones' => json_encode($request->input('phones')),
                'caption' => $request->input('caption', '')
            ]);

            $responseData = $response->json();

            // Guardar en log siempre, exitoso o no
            WhatsAppLog::create([
                'phone' => 'bulk',
                'message' => $request->input('caption', ''),
                'type' => 'masivo_multimedia',
                'response' => json_encode($responseData)
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al enviar mensajes masivos con multimedia.',
                    'details' => $responseData
                ], $response->status());
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error('Error en sendBulkMedia: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error crítico en el envío de multimedia masiva.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function sendWithStatus(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $response = Http::post("{$this->apiUrl}/send-with-status", $data);

        return response()->json($response->json(), $response->status());
    }

    public function markAsRead(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string',
        ]);

        $response = Http::post("{$this->apiUrl}/mark-as-read", [
            'number' => $data['phone']
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function storeIncoming(Request $request)
    {
        $request->validate([
            'from' => 'required|string',
            'body' => 'nullable|string',
            'timestamp' => 'nullable|integer',
            'media' => 'nullable|array',
            'media.data' => 'nullable|string',
            'media.mimetype' => 'nullable|string',
            'media.filename' => 'nullable|string',
        ]);
        $path = null;

        if ($request->has('media.data') && $request->media['data']) {
            $filename = $request->media['filename'] ?? 'archivo_' . time() . '.bin';
            $mimetype = $request->media['mimetype'] ?? 'application/octet-stream';
            // Guardar archivo
            $binary = base64_decode($request->media['data']);
            $path = 'whatsapp_media/' . uniqid() . '_' . $filename;
            Storage::put($path, $binary);
        
        }

        IncomingMessage::create([
            'from' => $request->from,
            'body' => $request->body,
            'received_at' => $request->timestamp ? Carbon::createFromTimestamp($request->timestamp) : now(),
            'media_filename' => $request->media['filename'] ?? null,
            'media_mimetype' => $request->media['mimetype'] ?? null,
            'media_path' => $path ? Storage::url($path) : null
        ]);

        return response()->json(['success' => true]);
    }

    public function incomingMessages()
    {
        return IncomingMessage::latest()->paginate(50);
    }

    public function reportes()
    {
        $logs = WhatsAppLog::latest()->paginate(50);
        return view('pages.icons', compact('logs'));
    }



}
