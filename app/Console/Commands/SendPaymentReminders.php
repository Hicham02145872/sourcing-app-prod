<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SourcingOrder;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReminderMail;
use Carbon\Carbon;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-payment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for pending payments on sourcing orders created more than 3 days ago.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Searching for orders needing a payment reminder...');

        $limitDate = Carbon::now()->subDays(3);

        $pendingOrders = SourcingOrder::where('status', 'pending_payment')
            ->where('created_at', '<= ', $limitDate)
            ->with('user') // Eager load the user relationship
            ->get();

        if ($pendingOrders->isEmpty()) {
            $this->info('No orders require a payment reminder.');
            return;
        }

        $this->info($pendingOrders->count() . ' order(s) found. Sending reminders...');

        foreach ($pendingOrders as $order) {
            try {
                Mail::to($order->user->email)->send(new PaymentReminderMail($order));
                $this->info('Reminder sent for order #' . $order->id . ' to ' . $order->user->email);
            } catch (\Exception $e) {
                $this->error('Failed to send reminder for order #' . $order->id . '. Error: ' . $e->getMessage());
            }
        }

        $this->info('All payment reminders have been sent.');
    }
}