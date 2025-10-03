<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\WhatsAppLog;
use App\Models\IncomingMessage;
use App\Models\WhatsappEnvio;
use App\Models\WhatsappEnvioLog;
use App\Models\WhatsappEstados;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;


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
    public function reportes(Request $request){
        $query = WhatsAppLog::query();
        // Filtros de fecha si llegan
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
        $logs = $query->get();
        // Mensajes por día
        $messagesByDay = $query->clone()->selectRaw('DATE(created_at) as date, COUNT(*) as total')->groupBy('date')->orderBy('date', 'asc')->pluck('total', 'date');
        // Distribución por tipo
        $messagesByType = $query->clone()->selectRaw('type, COUNT(*) as total')->groupBy('type')->pluck('total', 'type');
        // Top 5 teléfonos
        $topPhones = $query->clone()->selectRaw('phone, COUNT(*) as total')->groupBy('phone')->orderByDesc('total')->limit(5)->pluck('total', 'phone');
        return view('pages.messagewhatsapp', compact('logs', 'messagesByDay', 'messagesByType', 'topPhones'));
    }
    public function dashboardWhatsappmensajesenviados(){
        $totalMessages = WhatsAppLog::count();
        $todayMessages = WhatsAppLog::whereDate('created_at', now()->toDateString())->count();
        $uniqueTypes = WhatsAppLog::select('type')->distinct()->count();
        $topPhone = WhatsAppLog::select('phone', DB::raw('COUNT(*) as total'))->groupBy('phone')->orderByDesc('total')->first();
        $messagesByDay = WhatsAppLog::selectRaw('DATE(created_at) as date, COUNT(*) as total')->groupBy('date')->orderBy('date')->pluck('total', 'date');
        $messagesByType = WhatsAppLog::selectRaw('type, COUNT(*) as total')->groupBy('type')->pluck('total', 'type');
        $topPhones = WhatsAppLog::selectRaw('phone, COUNT(*) as total')->groupBy('phone')->orderByDesc('total')->limit(5)->pluck('total','phone');
        return view('pages.dashboardmensajesenviados', compact('totalMessages','todayMessages','uniqueTypes','topPhone','messagesByDay','messagesByType','topPhones'));
    }

    public function WhatsappEstados(){
        $estados = WhatsappEstados::get();
        $estadisticas = [
            'pendientes' => $estados->where('ack', 0)->count(),
            'enviados'   => $estados->where('ack', 1)->count(),
            'entregados' => $estados->where('ack', 2)->count(),
            'leidos'     => $estados->where('ack', 3)->count(),
        ];
        // mensajes agrupados por día
        $porDia = $estados->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
        })->map->count();
        // top 5 números
        $topNumeros = $estados->groupBy('phone')->map->count()->sortDesc()->take(5);        
        return view('pages.estadoswhatsapp', compact('estados','estadisticas','porDia','topNumeros'));
    }
    public function mensajesRespondidos(){
        $estados = IncomingMessage::get();
        // 1. Mensajes por día
        $messagesByDay = IncomingMessage::selectRaw('DATE(received_at) as date, COUNT(*) as total')->groupBy('date')->orderBy('date', 'asc')->pluck('total', 'date');
        // 2. Top 5 números
        $topSenders = IncomingMessage::selectRaw('"from", COUNT(*) as total')->groupBy('from')->orderByDesc('total')->limit(5)->pluck('total', 'from');
        // 3. Mensajes por hora
        $messagesByHour = IncomingMessage::selectRaw('EXTRACT(HOUR FROM received_at) as hour, COUNT(*) as total')->groupBy('hour')->orderBy('hour')->pluck('total', 'hour');
        return view('pages.mensajesrespondidos', compact('estados', 'messagesByDay', 'topSenders', 'messagesByHour'));
    }
    public function estadoswhatsappreporte(){
        $estados = WhatsappEstados::get();
        return view('pages.estadoswhatsappreporte', compact('estados'));
    }
    public function enviarMasivoConImagen(Request $request)
    {
        try {
            $request->validate([
                'phones' => 'required|string',
                'caption' => 'required|string',
                'link' => 'required|string',
                'file' => 'required|file|max:51200' // 50 MB
            ]);
            $phones = json_decode($request->phones, true);
            if (!is_array($phones)) {
                return response()->json(['error' => 'El campo phones debe ser un arreglo JSON válido.'], 422);
            }
            $response = Http::timeout(420)->attach(
                'file',
                file_get_contents($request->file('file')),
                $request->file('file')->getClientOriginalName()
            )->post("http://127.0.0.1:3000/send-bulk-messages-link-img", [
                'phones' => json_encode($phones),
                'message' => $request->caption,
                'link' => $request->link
            ]);

            $filename = $request->file('file')->getClientOriginalName();
            foreach ($phones as $index => $phone) {
                WhatsappEnvio::create([
                    'phone' => $phone,
                    'caption' => $request->caption,
                    'link' => $request->link,
                    'media_filename' => $filename,
                    'status' => $response->ok() && ($response['results'][$index]['success'] ?? false) ? 'enviado' : 'error',
                    'response' => json_encode($response->json())
                ]);
            }

            return response()->json($response->json(), $response->status());
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function registrarResultado(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'status' => 'required|string',
            'caption' => 'nullable|string',
            'link' => 'nullable|string',
            'media_filename' => 'nullable|string',
            'message_id' => 'nullable|string',
            'response' => 'nullable|array'
        ]);
        WhatsappEnvioLog::create([
            'phone' => $request->phone,
            'status' => $request->status,
            'caption' => $request->caption,
            'link' => $request->link,
            'media_filename' => $request->media_filename,
            'message_id' => $request->message_id,
            'response' => json_encode($request->response),
        ]);
        return response()->json(['success' => true]);
    }

    public function registrarEstado(Request $request)
    {
        $request->validate([
            'message_id' => 'string',
            'phone' => 'required|string',
            'ack' => 'required|integer|min:0|max:3',
            'timestamp' => 'nullable|integer'
        ]);

        WhatsappEstados::updateOrCreate(
            ['message_id' => $request->message_id],
            [
                'phone' => $request->phone,
                'ack' => $request->ack,
                'estado_at' => $request->timestamp ? \Carbon\Carbon::createFromTimestampMs($request->timestamp) : now()
            ]
        );

        return response()->json(['success' => true]);
    }
}
