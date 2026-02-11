<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MailSentListener
{
    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        try {
            $directory = storage_path('app/mails');
            if (! File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $message = $event->sent->getOriginalMessage();
            $id = time().'_'.uniqid();
            
            $data = [
                'id' => $id,
                'date' => now()->toDateTimeString(),
                'subject' => $message->getSubject(),
                'to' => collect($message->getTo())->map(fn($t) => $t->getAddress())->implode(', '),
                'from' => collect($message->getFrom())->map(fn($f) => $f->getAddress())->implode(', '),
                'body' => $event->sent->getSymfonySentMessage()->getMessage()->getHtmlBody() ?: $event->sent->getSymfonySentMessage()->getMessage()->getTextBody(),
            ];

            File::put($directory . '/' . $id . '.json', json_encode($data));
            
            // Keep only last 50 emails
            $files = File::glob($directory . '/*.json');
            if (count($files) > 50) {
                usort($files, fn($a, $b) => filemtime($a) - filemtime($b));
                File::delete(array_slice($files, 0, count($files) - 50));
            }
        } catch (\Exception $e) {
            Log::error('MailSentListener Error: ' . $e->getMessage());
        }
    }
}
