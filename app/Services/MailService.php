<?php
namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * Send order confirmation emails to customer and admin(s)
     */
    public function sendOrderEmails($user, $order, $orderItems)
    {
        $orderDate = \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i:s');
        $subject = 'Order Number #' . $order->order_no . ' ' . $orderDate;
        $data = compact('user', 'order', 'orderItems');

        // 📨 Send to customer
        $this->sendViaSendinblue(
            to: [['email' => $user->email, 'name' => $user->name]],
            subject: $subject,
            view: 'emails.users.order_placed',
            data: $data
        );

        // 🛠 Send to admin(s)
        $adminRecipients = $this->getAdminRecipients();
        if (!empty($adminRecipients)) {
            $this->sendViaSendinblue(
                to: $adminRecipients,
                subject: $subject,
                view: 'emails.admin.order_placed',
                data: $data
            );
        }
    }

    /**
     * Send profile update confirmation to user and notification to admin(s)
     */
    public function sendProfileUpdateEmails($user)
    {
        $date = date('d/m/Y H:i:s');
        
        // 📨 Send to user (confirmation)
        $this->sendViaSendinblue(
            to: [['email' => $user->email, 'name' => $user->name]],
            subject: 'Profile Updated Successfully - ' . $date,
            view: 'emails.users.profile_updated',
            data: compact('user')
        );

        // 🛠 Send to admin(s) (notification)
        $adminRecipients = $this->getAdminRecipients();
        if (!empty($adminRecipients)) {
            $this->sendViaSendinblue(
                to: $adminRecipients,
                subject: 'User Profile Updated - ' . $user->name . ' (' . $user->email . ')',
                view: 'emails.admin.profile_updated',
                data: compact('user')
            );
        }
    }

    /**
     * Get admin email recipients from Settings or fallback to env
     * 
     * @return array Array of recipient arrays with 'email' and 'name' keys
     */
    protected function getAdminRecipients(): array
    {
        $adminEmails = Settings::get('admin_emails', '');
        
        if (!empty($adminEmails)) {
            $emails = array_map('trim', explode(',', $adminEmails));
            $emails = array_filter($emails, fn($email) => !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL));
            
            if (!empty($emails)) {
                return array_map(fn($email) => ['email' => $email, 'name' => 'Admin'], $emails);
            }
        }
        
        // Fallback to env ADMIN_EMAIL
        $envEmail = config('mail.from.address', env('ADMIN_EMAIL'));
        if ($envEmail) {
            return [['email' => $envEmail, 'name' => 'Admin']];
        }
        
        return [];
    }

    /**
     * Send email via Sendinblue API or Laravel Mail (for testing)
     */
    protected function sendViaSendinblue(array $to, string $subject, string $view, array $data): void
    {
        // For testing: Use Laravel Mail with MailHog
        if ($this->isTestingMode()) {
            $this->sendViaLaravelMail($to, $subject, $view, $data);
            return;
        }

        // Production: Use Sendinblue API
        $this->sendViaSendinblueAPI($to, $subject, $view, $data);
    }

    /**
     * Check if email testing mode is enabled
     */
    protected function isTestingMode(): bool
    {
        return filter_var(env('TESTING_MAIL', false), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Send email via Sendinblue API
     */
    protected function sendViaSendinblueAPI(array $to, string $subject, string $view, array $data): void
    {
        try {
            $htmlContent = View::make($view, $data)->render();
            $apiKey = env('SENDINBLUE_API_KEY');
            $senderEmail = config('mail.from.address', env('ADMIN_EMAIL'));
            
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'api-key' => $apiKey,
                'content-type' => 'application/json',
            ])->post('https://api.sendinblue.com/v3/smtp/email', [
                'sender' => [
                    'name' => config('mail.from.name', 'Prestige India'),
                    'email' => $senderEmail,
                ],
                'to' => $to,
                'subject' => $subject,
                'htmlContent' => $htmlContent,
            ]);

            if ($response->successful()) {
                Log::info("Email sent via Sendinblue API", ['to' => $to, 'subject' => $subject]);
            } else {
                Log::error("Sendinblue Email Failed", [
                    'view' => $view,
                    'response' => $response->body(),
                    'status' => $response->status()
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Sendinblue Email Exception", [
                'view' => $view,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Send email via Laravel Mail (for MailHog testing)
     */
    protected function sendViaLaravelMail(array $to, string $subject, string $view, array $data): void
    {
        try {
            $fromEmail = config('mail.from.address', env('ADMIN_EMAIL', 'test@example.com'));
            $fromName = config('mail.from.name', 'Prestige India');
            
            foreach ($to as $recipient) {
                Mail::send($view, $data, function ($message) use ($recipient, $subject, $fromEmail, $fromName) {
                    $message->to($recipient['email'], $recipient['name'] ?? '')
                            ->subject($subject)
                            ->from($fromEmail, $fromName);
                });
            }
            
            Log::info("Email sent via Laravel Mail (MailHog)", ['to' => $to, 'subject' => $subject]);
        } catch (\Exception $e) {
            Log::error("Laravel Mail Failed", [
                'view' => $view,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
