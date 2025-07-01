<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class MailService
{
    public function sendOrderEmails($user, $order, $orderItems)
    {
        // 📨 Send to customer
        $this->sendViaSendinblue(
            to: [
                ['email' => $user->email, 'name' => $user->name],
            ],
            subject: 'Order Number #'.$order->order_no.' '.date('d/m/Y H:i:s', strtotime($order->created_at)),
            view: 'emails.users.order_placed',
            data: compact('user', 'order', 'orderItems')
        );

        // 🛠 Send to admin
        $this->sendViaSendinblue(
            to: [
                ['email' => env('ADMIN_EMAIL'), 'name' => 'Admin'],
            ],
             subject: 'Order Number #'.$order->order_no.' '.date('d/m/Y H:i:s', strtotime($order->created_at)),
            view: 'emails.admin.order_placed',
            data: compact('user', 'order', 'orderItems')
        );
    }

    protected function sendViaSendinblue(array $to, string $subject, string $view, array $data)
    {
        
        $htmlContent = View::make($view, $data)->render();
        // return $htmlContent;
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'api-key' => env('SENDINBLUE_API_KEY'),
            'content-type' => 'application/json',
        ])->post('https://api.sendinblue.com/v3/smtp/email', [
            'sender' => [
                'name' => 'Prestige India',
                'email' => env('ADMIN_EMAIL'),
            ],
            'to' => $to,
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ]);

        if ($response->failed()) {
            Log::error("Sendinblue Email Failed", ['view' => $view, 'response' => $response->body()]);
        }
    }
}
