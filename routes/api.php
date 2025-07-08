<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Route::post('/whatsapp/enviar', [WhatsAppController::class, 'sendMessage']);
Route::get('/whatsapp/qr', [WhatsAppController::class, 'qr']);
Route::post('/whatsapp/send', [WhatsAppController::class, 'sendMessage']);
Route::post('/whatsapp/send-bulk', [WhatsAppController::class, 'sendBulkMessages']);
Route::post('/whatsapp/send-media', [WhatsAppController::class, 'sendMedia']);
Route::post('/whatsapp/send-bulk-media', [WhatsAppController::class, 'sendBulkMedia']);
Route::post('/whatsapp/send-status', [WhatsAppController::class, 'sendWithStatus']);
Route::post('/whatsapp/mark-read', [WhatsAppController::class, 'markAsRead']);
Route::post('/whatsapp/received-message', [WhatsAppController::class, 'storeIncoming']);
Route::get('/whatsapp/incoming', [WhatsAppController::class, 'incomingMessages']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
