<?php

namespace App\Livewire\Admin;

use App\Models\SourcingOrder;
use App\Models\TrackingLog;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DevDashboard extends Component
{
    // Debug System
    public $debugQueries = [];

    public $debugRequests = [];

    public $serverStats = [];

    public $activeTab = 'sync';

    public $syncErrors = [];

    public $trackingLogs = [];

    public $users = [];

    public $logs = '';

    public $queueSize = 0;

    public $failedJobs = 0;

    public $mediaFiles = [];

    public $statusAudit = [];

    // Tracking Monitor
    public $trackingOrders = [];

    public $testTrackingNumber = '';

    public $testTrackingCarrier = '';

    public $trackingTestResult = null;

    public $trackingTestError = null;

    // Cache Monitor
    public $cacheStats = [];

    // Notification Tester
    public $testUserId = '';

    public $testNotificationTitle = '';

    public $hasPendingUpdates = false;

    public $envContent = '';

    public $capturedMails = [];

    public $selectedMail = null;

    public $backups = [];

    public $scheduledTasks = [];

    public $testNotificationBody = '';

    public $notificationTestResult = null;

    public $notificationTestError = null;

    public $recentNotifications = [];

    // Email Preview
    public $selectedMailable = 'PaymentReminderMail';
    public $testMailOrderId = '';
    public $emailPreviewHtml = '';
    public $testEmailRecipient = '';
    public $emailTestResult = null;
    public $emailTestError = null;
    public $testMailOrders = [];

    // Database Explorer
    public $tables = [];

    public $tableSearch = '';

    public $tableCounts = [];

    public $query = '';

    public $queryResult = null;

    public $queryError = null;

    public $queryHistory = [];

    public $editingCell = null;

    // New Features
    public $activeSessions = [];
    public $availableSeeders = [];
    public $healthStatus = [];
    public $queueJobs = [];
    public $seederLoading = null;
    public $trackingStats = [];

    public function boot()
    {
        DB::listen(function ($query) {
            $this->debugQueries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ];
        });
    }

    public function mount()
    {
        $this->loadData();
        $this->queryHistory = session()->get('dev_query_history', []);
        $this->captureServerStats();
        $this->fetchLogs();
        $this->fetchFailedJobs();
        $this->loadCacheStats();
        $this->loadRecentNotifications();
        $this->loadEmailOrders();
    }

    public function captureServerStats()
    {
        $this->serverStats = [
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
            'memory' => round(memory_get_usage() / 1024 / 1024, 2).' MB',
            'os' => PHP_OS,
            'db_connection' => config('database.default'),
            'env' => app()->environment(),
        ];
    }

    public function loadData()
    {
        $this->syncErrors = SourcingOrder::whereNotNull('sheet_sync_error')
            ->with('user', 'quotation.sourcingRequest')
            ->latest()
            ->get();

        $this->trackingLogs = TrackingLog::latest()->take(10)->get();
        $this->users = User::orderBy('name')->get();
        $this->failedJobs = DB::table('failed_jobs')->count();
        $this->queueSize = DB::table('jobs')->count();

        $this->statusAudit = SourcingOrder::latest()->take(10)->get();
        $this->loadTrackingOrders();

        $rawTables = collect(DB::select('SHOW TABLES'))->map(fn ($t) => (array) $t)->flatten();
        $this->tables = $rawTables->toArray();

        foreach ($this->tables as $table) {
            $this->tableCounts[$table] = DB::table($table)->count();
        }

        $this->fetchLogs();
        $this->fetchFailedJobs();
        $this->loadTrackingOrders();
        $this->loadEnvPreview();
        $this->loadCapturedMails();
        $this->loadBackups();
        $this->loadScheduledTasks();
        $this->loadSessions();
        $this->loadSeeders();
        $this->checkHealth();
        $this->loadQueueJobs();
        $this->loadTrackingStats();
    }

    public function getFilteredTablesProperty()
    {
        if (empty($this->tableSearch)) {
            return $this->tables;
        }

        return array_filter($this->tables, function ($table) {
            return str_contains(strtolower($table), strtolower($this->tableSearch));
        });
    }

    public function getFcmCountProperty()
    {
        return $this->users->filter(fn ($u) => ! empty($u->fcm_token))->count();
    }

    public function forceSyncAll()
    {
        $orders = SourcingOrder::whereNotNull('sheet_sync_error')->get();
        $count = 0;
        foreach ($orders as $order) {
            try {
                $service = new \App\Services\GoogleSheetService;
                $service->upsertRow($order->toGoogleSheetArray(), $order->id);
                $order->update(['sheet_sync_error' => null]);
                $count++;
            } catch (\Exception $e) {
                Log::error("Manual sync failed for Order #{$order->id}: ".$e->getMessage());
            }
        }
        $this->dispatch('show-success-toast', message: "$count orders synced successfully.");
        $this->loadData();
    }

    public function impersonate($userId)
    {
        $user = User::find($userId);
        if ($user) {
            Auth::login($user);

            return redirect()->route('dashboard');
        }
    }

    public function generateTestData($type, $count = 5)
    {
        try {
            if ($type === 'orders') {
                SourcingOrder::factory()->count($count)->create();
            } elseif ($type === 'requests') {
                \App\Models\SourcingRequest::factory()->count($count)->create();
            }
            $this->dispatch('show-success-toast', message: "Generated $count $type.");
            $this->loadData();
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Error: '.$e->getMessage());
        }
    }

    public function loadEnvPreview()
    {
        $path = base_path('.env');
        if (! File::exists($path)) {
            $this->envContent = 'File .env not found.';

            return;
        }

        $lines = explode("\n", File::get($path));
        $maskedLines = [];
        $sensitiveKeys = ['PASSWORD', 'SECRET', 'KEY', 'TOKEN', 'DATABASE', 'USERNAME', 'CLIENT_ID'];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {
                $maskedLines[] = $line;
                continue;
            }

            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $shouldMask = false;
                foreach ($sensitiveKeys as $sensitive) {
                    if (stripos($key, $sensitive) !== false) {
                        $shouldMask = true;
                        break;
                    }
                }

                if ($shouldMask && ! empty($value)) {
                    $maskedValue = substr($value, 0, 3).str_repeat('*', 8).substr($value, -3);
                    $maskedLines[] = "{$key}={$maskedValue}";
                } else {
                    $maskedLines[] = $line;
                }
            } else {
                $maskedLines[] = $line;
            }
        }

        $this->envContent = implode("\n", $maskedLines);
    }

    public function loadCapturedMails()
    {
        $directory = storage_path('app/mails');
        if (! File::exists($directory)) {
            $this->capturedMails = [];

            return;
        }

        $files = File::glob($directory.'/*.json');
        $this->capturedMails = collect($files)
            ->map(function ($file) {
                return json_decode(File::get($file), true);
            })
            ->sortByDesc('date')
            ->values()
            ->toArray();
    }

    public function selectMail($id)
    {
        $this->selectedMail = collect($this->capturedMails)->firstWhere('id', $id);
    }

    public function deleteMail($id)
    {
        $path = storage_path("app/mails/{$id}.json");
        if (File::exists($path)) {
            File::delete($path);
        }
        $this->selectedMail = null;
        $this->loadCapturedMails();
        $this->dispatch('show-success-toast', message: 'Mail deleted.');
    }

    public function clearAllMails()
    {
        $directory = storage_path('app/mails');
        if (File::exists($directory)) {
            File::cleanDirectory($directory);
        }
        $this->capturedMails = [];
        $this->selectedMail = null;
        $this->dispatch('show-success-toast', message: 'All mails cleared.');
    }

    public function loadBackups()
    {
        $this->backups = (new \App\Services\BackupService())->listBackups();
    }

    public function createDatabaseBackup()
    {
        try {
            (new \App\Services\BackupService())->createDatabaseBackup();
            $this->loadBackups();
            $this->dispatch('show-success-toast', message: 'Database backup created.');
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Backup failed: '.$e->getMessage());
        }
    }

    public function createFilesBackup()
    {
        try {
            (new \App\Services\BackupService())->createFilesBackup();
            $this->loadBackups();
            $this->dispatch('show-success-toast', message: 'Files backup created.');
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Backup failed: '.$e->getMessage());
        }
    }

    public function deleteBackup($name)
    {
        $path = storage_path("app/backups/{$name}");
        if (File::exists($path)) {
            File::delete($path);
        }
        $this->loadBackups();
        $this->dispatch('show-success-toast', message: 'Backup deleted.');
    }

    public function downloadBackup($name)
    {
        $path = storage_path("app/backups/{$name}");
        if (File::exists($path)) {
            return response()->download($path);
        }
    }

    public function loadScheduledTasks()
    {
        $scheduler = app(\Illuminate\Console\Scheduling\Schedule::class);
        $this->scheduledTasks = collect($scheduler->events())
            ->map(function ($event) {
                return [
                    'command' => $event->command ?? $event->description,
                    'expression' => $event->expression,
                    'next_run' => $event->nextRunDate()->format('Y-m-d H:i:s'),
                    'description' => $event->description,
                ];
            })
            ->toArray();
    }

    public function runScheduledTask($command)
    {
        try {
            // Cleanup command string if it starts with 'php artisan'
            $artisanCmd = str_replace('\'php\' \'artisan\' ', '', $command);
            $artisanCmd = trim($artisanCmd, '\'');
            
            \Illuminate\Support\Facades\Artisan::call($artisanCmd);
            $this->dispatch('show-success-toast', message: "Task '{$artisanCmd}' executed.");
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Execution failed: '.$e->getMessage());
        }
    }

    public function runArtisan($command)
    {
        try {
            Artisan::call($command);
            $this->dispatch('show-success-toast', message: "Command '$command' executed.");
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Error: '.$e->getMessage());
        }
    }

    public function loadSessions()
    {
        $this->activeSessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.email as user_email', 'users.name as user_name')
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                return (array) $session;
            })->toArray();
    }

    public function deleteSession($id)
    {
        DB::table('sessions')->where('id', $id)->delete();
        $this->loadSessions();
        $this->dispatch('show-success-toast', message: 'Session terminated.');
    }

    public function loadSeeders()
    {
        $directory = database_path('seeders');
        $files = File::files($directory);
        $this->availableSeeders = collect($files)
            ->map(fn($file) => $file->getFilenameWithoutExtension())
            ->filter(fn($name) => $name !== 'DatabaseSeeder')
            ->values()
            ->toArray();
    }

    public function runSeeder($seederClass)
    {
        $this->seederLoading = $seederClass;
        try {
            Artisan::call('db:seed', ['--class' => $seederClass, '--no-interaction' => true]);
            $this->dispatch('show-success-toast', message: "Seeder {$seederClass} executed.");
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Seeder failed: ' . $e->getMessage());
        }
        $this->seederLoading = null;
        $this->loadData();
    }

    public function checkHealth()
    {
        $status = [];

        // Database
        try {
            DB::connection()->getPdo();
            $status['Database'] = ['ok' => true, 'message' => 'Connected'];
        } catch (\Throwable $e) {
            $status['Database'] = ['ok' => false, 'message' => $e->getMessage()];
        }

        // Redis
        try {
            if (!class_exists('Redis') && config('database.redis.client') === 'phpredis') {
                throw new \Exception('PHP Redis extension not installed');
            }
            $redis = \Illuminate\Support\Facades\Redis::connection();
            $redis->ping();
            $status['Redis'] = ['ok' => true, 'message' => 'PONG'];
        } catch (\Throwable $e) {
            $status['Redis'] = ['ok' => false, 'message' => $e->getMessage()];
        }

        // SMTP
        try {
            $transport = app('mailer')->getSymfonyTransport();
            if ($transport instanceof \Symfony\Component\Mailer\Transport\Smtp\SmtpTransport) {
                $transport->stop();
                $transport->start();
                $status['SMTP'] = ['ok' => true, 'message' => 'Handshake successful'];
            } else {
                $status['SMTP'] = ['ok' => true, 'message' => 'Using non-SMTP transport'];
            }
        } catch (\Throwable $e) {
            $status['SMTP'] = ['ok' => false, 'message' => $e->getMessage()];
        }

        // 17Track API
        try {
            $apiKey = config('services.17track.key') ?? env('SEVENTEEN_TRACK_API_KEY');
            if ($apiKey) {
                $client = new \GuzzleHttp\Client();
                $response = $client->get('https://api.17track.net/track/v2.2/getcarrier', [
                    'headers' => ['17token' => $apiKey],
                    'timeout' => 5
                ]);
                if ($response->getStatusCode() === 200) {
                    $status['17Track API'] = ['ok' => true, 'message' => 'API Responsive'];
                } else {
                    $status['17Track API'] = ['ok' => false, 'message' => 'HTTP ' . $response->getStatusCode()];
                }
            } else {
                $status['17Track API'] = ['ok' => false, 'message' => 'API Key missing'];
            }
        } catch (\Throwable $e) {
            $status['17Track API'] = ['ok' => false, 'message' => $e->getMessage()];
        }

        $this->healthStatus = $status;
    }

    public function loadQueueJobs()
    {
        $this->queueJobs = DB::table('jobs')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($job) {
                $payload = json_decode($job->payload, true);
                $displayName = $payload['displayName'] ?? ($payload['data']['commandName'] ?? 'Unknown');
                return [
                    'id' => $job->id,
                    'queue' => $job->queue,
                    'name' => $displayName,
                    'attempts' => $job->attempts,
                    'reserved_at' => $job->reserved_at,
                    'available_at' => date('Y-m-d H:i:s', $job->available_at),
                    'created_at' => date('Y-m-d H:i:s', $job->created_at),
                ];
            })->toArray();
    }

    public function deleteQueueJob($id)
    {
        DB::table('jobs')->where('id', $id)->delete();
        $this->loadQueueJobs();
        $this->dispatch('show-success-toast', message: 'Job removed from queue.');
    }



    public $parsedLogs = [];

    public $failedJobsList = [];

    public function fetchLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (! File::exists($logPath)) {
            $this->parsedLogs = [['text' => 'Log file not found.', 'level' => 'info']];

            return;
        }

        $content = File::get($logPath);
        $lines = array_slice(explode("\n", trim($content)), -200);
        $this->parsedLogs = [];

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $level = 'info';
            if (stripos($line, '.ERROR') !== false || stripos($line, '.CRITICAL') !== false || stripos($line, '.ALERT') !== false) {
                $level = 'error';
            } elseif (stripos($line, '.WARNING') !== false) {
                $level = 'warning';
            }

            $this->parsedLogs[] = [
                'text' => $line,
                'level' => $level,
            ];
        }
    }

    public function fetchFailedJobs()
    {
        $this->failedJobsList = DB::table('failed_jobs')
            ->latest('failed_at')
            ->take(50)
            ->get()
            ->map(function ($job) {
                return (array) $job;
            })->toArray();
    }

    public function retryJob($id)
    {
        try {
            Artisan::call("queue:retry $id");
            $this->dispatch('show-success-toast', message: "Job #$id pushed back to queue.");
            $this->fetchFailedJobs();
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Retry failed: '.$e->getMessage());
        }
    }

    public function runSqlQuery($customQuery = null)
    {
        $this->query = $customQuery ?: $this->query;
        $this->queryError = null;
        $this->queryResult = null;

        if (empty($this->query)) {
            return;
        }

        try {
            // Check if it's a query that returns results
            $trimmedQuery = trim($this->query);
            $isSelect = stripos($trimmedQuery, 'SELECT') === 0;
            $isSchema = stripos($trimmedQuery, 'SHOW') === 0 || stripos($trimmedQuery, 'DESCRIBE') === 0;

            if ($isSelect || $isSchema) {
                // Protective Limit for UI performance - only for SELECT queries as DESCRIBE/SHOW don't always support it
                $limitedQuery = $this->query;
                if ($isSelect && ! stripos($this->query, 'LIMIT')) {
                    $limitedQuery = rtrim($this->query, ';').' LIMIT 200';
                }

                $result = DB::select($limitedQuery);
                $this->queryResult = json_decode(json_encode($result), true);

                if (count($this->queryResult) >= 200 && ! stripos($this->query, 'LIMIT')) {
                    $this->dispatch('show-info-toast', message: 'Results limited to 200 rows for performance.');
                }
            } else {
                $affected = DB::statement($this->query);
                $this->queryResult = [['Status' => 'Success', 'Affected Rows' => $affected]];
            }

            // Record History
            if (! in_array($this->query, $this->queryHistory)) {
                array_unshift($this->queryHistory, $this->query);
                $this->queryHistory = array_slice($this->queryHistory, 0, 10);
                session()->put('dev_query_history', $this->queryHistory);
            }

        } catch (\Exception $e) {
            $this->queryError = $e->getMessage();
        }
    }

    public function startEditing($table, $id, $column, $value)
    {
        $this->editingCell = [
            'table' => $table,
            'id' => $id,
            'column' => $column,
            'value' => $value,
        ];
    }

    public function updateCell()
    {
        if (! $this->editingCell) {
            return;
        }

        try {
            DB::table($this->editingCell['table'])
                ->where('id', $this->editingCell['id'])
                ->update([$this->editingCell['column'] => $this->editingCell['value']]);

            $this->dispatch('show-success-toast', message: 'Cell updated successfully.');
            $this->editingCell = null;
            $this->runSqlQuery(); // Refresh results
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Update failed: '.$e->getMessage());
        }
    }

    public function cancelEditing()
    {
        $this->editingCell = null;
    }


    public function setQuery($sql)
    {
        $this->query = $sql;
    }

    public function loadTrackingOrders()
    {
        $this->trackingOrders = SourcingOrder::whereNotNull('tracking_number')
            ->with(['user', 'quotation.sourcingRequest', 'shippingCompany'])
            ->latest('updated_at')
            ->take(20)
            ->get();

        // Check if any tracking is currently being processed in background
        $this->hasPendingUpdates = false;
        foreach ($this->trackingOrders as $order) {
            if (\Illuminate\Support\Facades\Cache::has("tracking_pending:{$order->tracking_number}")) {
                $this->hasPendingUpdates = true;
                break;
            }
        }

        // Also check the specific test tracking number if it's being tested
        if (! $this->hasPendingUpdates && ! empty($this->testTrackingNumber)) {
            if (\Illuminate\Support\Facades\Cache::has("tracking_pending:{$this->testTrackingNumber}")) {
                $this->hasPendingUpdates = true;
            }
        }
    }

    public function loadTrackingStats()
    {
        $last24h = now()->subHours(24);

        $stats = TrackingLog::where('created_at', '>=', $last24h)
            ->select('provider', 'status', DB::raw('count(*) as count'))
            ->groupBy('provider', 'status')
            ->get();

        $grouped = [];
        foreach ($stats as $stat) {
            $provider = $stat->provider ?: 'Unknown';
            if (! isset($grouped[$provider])) {
                $grouped[$provider] = ['success' => 0, 'failed' => 0, 'total' => 0];
            }

            $isSuccess = ($stat->status !== 'Failed' && $stat->status !== 'Numéro introuvable' && $stat->status !== 'Error' && !str_contains(strtolower($stat->status), 'error'));
            
            if ($isSuccess) {
                $grouped[$provider]['success'] += $stat->count;
            } else {
                $grouped[$provider]['failed'] += $stat->count;
            }
            $grouped[$provider]['total'] += $stat->count;
        }

        foreach ($grouped as $provider => &$data) {
            $data['rate'] = $data['total'] > 0 ? round(($data['success'] / $data['total']) * 100, 1) : 0;
        }

        $this->trackingStats = $grouped;
    }

    public function testTracking()
    {
        $this->trackingTestResult = null;
        $this->trackingTestError = null;

        if (empty($this->testTrackingNumber)) {
            $this->trackingTestError = 'Please enter a tracking number';

            return;
        }

        try {
            $service = app(\App\Services\Tracking\UnifiedTrackingService::class);
            $result = $service->track($this->testTrackingNumber, $this->testTrackingCarrier);

            if ($result['success'] ?? false) {
                $this->trackingTestResult = $result;
                $this->dispatch('show-success-toast', message: 'Tracking info retrieved successfully.');
            } else {
                $this->trackingTestError = $result['error'] ?? 'Unknown error';
                $this->dispatch('show-error-toast', message: $this->trackingTestError);
            }
        } catch (\Exception $e) {
            $this->trackingTestError = $e->getMessage();
            $this->dispatch('show-error-toast', message: 'Error: '.$e->getMessage());
        }
    }

    public function refreshTracking($trackingNumber)
    {
        try {
            $service = app(\App\Services\Tracking\UnifiedTrackingService::class);
            $result = $service->track($trackingNumber, null);

            if ($result['status'] ?? '' === 'pending') {
                $this->dispatch('show-info-toast', message: $result['error'] ?? 'Tracking refresh initiated in background.');
            } else {
                $this->dispatch('show-success-toast', message: 'Tracking refreshed successfully.');
            }
            $this->loadTrackingOrders();
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Error: '.$e->getMessage());
        }
    }

    public function loadCacheStats()
    {
        $this->cacheStats = [
            'driver' => config('cache.default'),
            'prefix' => config('cache.prefix'),
        ];
    }

    public function clearCache($type = 'all')
    {
        try {
            switch ($type) {
                case 'all':
                    Artisan::call('cache:clear');
                    $message = 'All cache cleared successfully';
                    break;
                case 'config':
                    Artisan::call('config:clear');
                    $message = 'Config cache cleared successfully';
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    $message = 'Route cache cleared successfully';
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    $message = 'View cache cleared successfully';
                    break;
                default:
                    $message = 'Unknown cache type';
            }

            $this->dispatch('show-success-toast', message: $message);
            $this->loadCacheStats();
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Error: '.$e->getMessage());
        }
    }

    public function loadRecentNotifications()
    {
        $this->recentNotifications = DB::table('notifications')
            ->latest('created_at')
            ->take(20)
            ->get()
            ->map(function ($notification) {
                return (array) $notification;
            })->toArray();
    }

    public function testFcmNotification()
    {
        $this->notificationTestResult = null;
        $this->notificationTestError = null;

        if (empty($this->testUserId)) {
            $this->notificationTestError = 'Please select a user';

            return;
        }

        try {
            $user = User::find($this->testUserId);

            if (! $user) {
                $this->notificationTestError = 'User not found';

                return;
            }

            if (! $user->fcm_token) {
                $this->notificationTestError = 'User has no FCM token registered';

                return;
            }

            // Send test notification
            $title = $this->testNotificationTitle ?: 'Test Notification';
            $body = $this->testNotificationBody ?: 'This is a test notification from Dev Dashboard';

            $user->notify(new \App\Notifications\AlternativeSourcingNotification(
                $title,
                $body,
                ['test' => true, 'sent_from' => 'dev_dashboard']
            ));

            $this->notificationTestResult = [
                'user' => $user->name,
                'email' => $user->email,
                'fcm_token' => substr($user->fcm_token, 0, 20).'...',
                'title' => $title,
                'body' => $body,
            ];

            $this->dispatch('show-success-toast', message: 'Test notification sent successfully.');
            $this->loadRecentNotifications();
        } catch (\Exception $e) {
            $this->notificationTestError = $e->getMessage();
            $this->dispatch('show-error-toast', message: 'Error: '.$e->getMessage());
        }
    }

    public function previewEmail()
    {
        $this->emailPreviewHtml = '';
        $this->emailTestError = null;

        if (empty($this->testMailOrderId)) {
            $this->emailTestError = 'Please select an order for preview';
            return;
        }

        try {
            $order = SourcingOrder::find($this->testMailOrderId);
            if (!$order) {
                $this->emailTestError = 'Order not found';
                return;
            }

            $mailable = null;
            if ($this->selectedMailable === 'PaymentReminderMail') {
                $mailable = new \App\Mail\PaymentReminderMail($order);
            } else if ($this->selectedMailable === 'ProformaInvoiceMail') {
                $mailable = new \App\Mail\ProformaInvoiceMail($order);
            }

            if ($mailable) {
                $this->emailPreviewHtml = $mailable->render();
            }
        } catch (\Exception $e) {
            $this->emailTestError = $e->getMessage();
        }
    }

    public function sendTestEmail()
    {
        $this->emailTestResult = null;
        $this->emailTestError = null;

        if (empty($this->testMailOrderId)) {
            $this->emailTestError = 'Please select an order';
            return;
        }

        if (empty($this->testEmailRecipient)) {
            $this->emailTestError = 'Please enter a recipient email';
            return;
        }

        try {
            $order = SourcingOrder::find($this->testMailOrderId);
            if (!$order) {
                $this->emailTestError = 'Order not found';
                return;
            }

            $mailable = null;
            if ($this->selectedMailable === 'PaymentReminderMail') {
                $mailable = new \App\Mail\PaymentReminderMail($order);
            } else if ($this->selectedMailable === 'ProformaInvoiceMail') {
                $mailable = new \App\Mail\ProformaInvoiceMail($order);
            }

            if ($mailable) {
                \Illuminate\Support\Facades\Mail::to($this->testEmailRecipient)->send($mailable);
                $this->emailTestResult = "Email sent to {$this->testEmailRecipient}";
                $this->dispatch('show-success-toast', message: 'Test email sent successfully.');
            }
        } catch (\Exception $e) {
            $this->emailTestError = $e->getMessage();
            $this->dispatch('show-error-toast', message: 'Error: ' . $e->getMessage());
        }
    }

    public function loadEmailOrders()
    {
        $this->testMailOrders = SourcingOrder::latest()->take(20)->get();
    }

    public function exportToCsv()
    {
        if (empty($this->queryResult)) {
            $this->dispatch('show-error-toast', message: 'No data to export.');
            return;
        }

        try {
            $filename = 'export_'.now()->format('Y-m-d_H-i-s').'.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () {
                $file = fopen('php://output', 'w');
                
                // Header row
                if (count($this->queryResult) > 0) {
                    $firstRow = (array) $this->queryResult[0];
                    fputcsv($file, array_keys($firstRow));
                }

                // Data rows
                foreach ($this->queryResult as $row) {
                    fputcsv($file, (array) $row);
                }
                
                fclose($file);
            };

            return response()->streamDownload($callback, $filename, $headers);
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Export failed: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.dev-dashboard')->layout('layouts.dev');
    }
}
