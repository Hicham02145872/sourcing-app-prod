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
        // Mail archiving is useful for local debugging, but should be disabled by default in production.
        if (! config('mail_archive.enabled', false)) {
            return;
        }

        try {
            $directory = storage_path('app/mails');
            if (! File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            if (! is_writable($directory)) {
                Log::warning('MailSentListener skipped: mails directory is not writable.', [
                    'directory' => $directory,
                ]);

                return;
            }

            // In Laravel 11+, MessageSent wraps a Symfony RawMessage/Email.
            // We need to safely extract HTML or text body depending on the concrete type.
            $symfonyMessage = $event->sent->getSymfonySentMessage()->getOriginalMessage();

            $bodyHtml = null;
            $bodyText = null;

            if ($symfonyMessage instanceof \Symfony\Component\Mime\Email) {
                $bodyHtml = $symfonyMessage->getHtmlBody();
                $bodyText = $symfonyMessage->getTextBody();
            } elseif (method_exists($symfonyMessage, 'getBody')) {
                // Fallback for other message types
                $bodyText = $symfonyMessage->getBody();
            }

            $message = $symfonyMessage;
            $id = time().'_'.uniqid();
            
            $data = [
                'id' => $id,
                'date' => now()->toDateTimeString(),
                'subject' => $message->getSubject(),
                'to' => collect($message->getTo())->map(fn($t) => $t->getAddress())->implode(', '),
                'from' => collect($message->getFrom())->map(fn($f) => $f->getAddress())->implode(', '),
                'body' => $bodyHtml ?: $bodyText ?: '',
            ];

            File::put($directory.'/'.$id.'.json', json_encode($data));
            
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
