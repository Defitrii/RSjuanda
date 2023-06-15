<?php

namespace App\Http\Middleware;

use App\Models\ChatPayment;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChatPaymentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $chatpayment = Chatpayment::with('user')->where('user_id', $user->id)->latest()->first();
        if ($user->type == 'Pasien') {
            if (!$chatpayment || $chatpayment->status == '1' || Carbon::now()->greaterThanOrEqualTo($chatpayment->waktu_selesai)) {
                if ($chatpayment) {
                    $chatpayment->status = '1';
                    $chatpayment->save();
                }
                abort(403, 'Perbarui status untuk akses chat berbayar.');
            }
        }
        return $next($request);
    }
}
