<?php

namespace App\Livewire\Admin;

use App\Models\RefundRequest;
use App\Models\SourcingOrder;
use App\Models\TrackingLog;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\WebhookEvent;
use App\Models\DevQueryLog;
use App\Notifications\DevPingNotification;
use App\Services\BackupService;
use App\Services\Dev\ArtisanWhitelist;
use App\Services\Dev\AuditLogger;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
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

    public $userSearch = '';

    public $passwordInputs = [];

    public $passwordConfirmations = [];

    public $currentAdminPassword = '';

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
    public string $selectedMailableClass = \App\Mail\PaymentReminderMail::class;

    public $testMailOrderId = '';

    public $emailPreviewHtml = '';

    public $testEmailRecipient = '';

    public $emailTestResult = null;

    public $emailTestError = null;

    public $testMailOrders = [];

    public $featureSearch = '';

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

    /** Auto-refresh (secondes). 0 = désactivé. */
    public int $devPollSeconds = 0;

    public function updatedDevPollSeconds($value): void
    {
        $this->devPollSeconds = max(0, (int) $value);
    }

    public bool $showCommandPalette = false;

    public string $commandPaletteQuery = '';

    /** SQL : refuser UPDATE/DELETE sauf si true + mot de passe. */
    public bool $dbAllowWrite = false;

    public string $devSensitivePassword = '';

    public string $errorsLogSearch = '';

    /** Lignes parsées du fichier errors-*.log */
    public array $errorsLogLines = [];

    public array $auditLogsPage = [];

    public array $webhookEventsList = [];

    public string $webhookTestSource = 'manual';

    public string $webhookTestPayload = '{"test": true}';

    public string $trackingDeepFsb = '';

    public ?SourcingOrder $trackingDeepOrder = null;

    public array $trackingTimeline = [];

    public array $trackingProviderP95 = [];

    public array $availableMailables = [];

    /** Canaux pour DevPing : mail, database, fcm */
    public array $devPingChannels = ['database', 'fcm'];

    public string $backupRestoreName = '';

    public string $backupRestoreConfirmPhrase = '';

    public array $backupRestorePreview = [];

    public string $fcmUserSearch = '';

    /** Lignes filtrées pour affichage (Errors tab) */
    public array $errorsLogFiltered = [];

    public array $explainResult = [];

    public ?string $dbSchemaFocusTable = null;

    public array $dbSchemaColumns = [];

    public array $dbSchemaIndexes = [];

    public array $rateLimiterOverview = [];

    public array $performanceSlowQueries = [];

    /** @var array<string, int> */
    public array $performanceN1Hints = [];

    public array $redisInfoSnippet = [];

    public function boot()
    {
        DB::listen(function ($query) {
            $this->debugQueries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ];
            if (count($this->debugQueries) > 400) {
                array_shift($this->debugQueries);
            }
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
        $this->loadFeatureFlags();
        $this->loadAvailableMailables();
    }

    public function updatedActiveTab(string $value): void
    {
        match ($value) {
            'errors' => $this->loadErrorsDailyLog(),
            'actionlog' => $this->loadAuditLogsPage(),
            'webhooks' => $this->loadWebhookEventsList(),
            'tracking_deep' => $this->loadTrackingDeep(),
            'performance' => $this->refreshPerformanceTab(),
            'rate_limits' => $this->loadRateLimiterOverview(),
            default => null,
        };
    }

    public function captureServerStats(): void
    {
        $diskStorage = @disk_free_space(storage_path());
        $diskBase = @disk_free_space(base_path());

        $dbVersion = null;
        $dbSizeMb = null;
        $topTables = [];
        try {
            $dbVersion = DB::selectOne('SELECT VERSION() as v')->v ?? null;
            $dbName = DB::getDatabaseName();
            $dbSizeMb = round((float) (DB::selectOne(
                'SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS s FROM information_schema.tables WHERE table_schema = ?',
                [$dbName]
            )->s ?? 0), 2);
            $topTables = DB::select(
                'SELECT table_name AS name, ROUND((data_length + index_length)/1024/1024,2) AS mb FROM information_schema.tables WHERE table_schema = ? ORDER BY (data_length+index_length) DESC LIMIT 10',
                [$dbName]
            );
        } catch (\Throwable) {
        }

        $redisMemory = null;
        try {
            if (config('database.redis.client') === 'phpredis') {
                $info = Redis::connection()->info('memory');
                $redisMemory = is_array($info)
                    ? ($info['used_memory_human'] ?? json_encode($info))
                    : (string) $info;
            } else {
                $raw = (string) Redis::connection()->executeRaw(['INFO', 'memory']);
                if (preg_match('/used_memory_human:([^\r\n]+)/', $raw, $m)) {
                    $redisMemory = trim($m[1]);
                }
            }
        } catch (\Throwable $e) {
            $redisMemory = 'n/a ('.$e->getMessage().')';
        }

        $uptimeOs = null;
        if (PHP_OS_FAMILY === 'Linux' && is_readable('/proc/uptime')) {
            $uptimeOs = trim((string) file_get_contents('/proc/uptime'));
        } elseif (PHP_OS_FAMILY === 'Windows') {
            $uptimeOs = 'Windows (voir Get-Uptime / TaskMgr)';
        }

        $this->serverStats = [
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
            'memory' => round(memory_get_usage() / 1024 / 1024, 2).' MB',
            'memory_peak' => round(memory_get_peak_usage() / 1024 / 1024, 2).' MB',
            'os' => PHP_OS,
            'db_connection' => config('database.default'),
            'db_version' => $dbVersion ?? 'n/a',
            'db_size_mb' => $dbSizeMb !== null ? (string) $dbSizeMb.' MB' : 'n/a',
            'disk_storage_free' => $diskStorage !== false ? round($diskStorage / 1024 / 1024 / 1024, 2).' GB' : 'n/a',
            'disk_base_free' => $diskBase !== false ? round($diskBase / 1024 / 1024 / 1024, 2).' GB' : 'n/a',
            'redis_memory' => $redisMemory ?? 'n/a',
            'host_uptime' => $uptimeOs ?? 'n/a',
            'env' => app()->environment(),
        ];

        $this->serverStats['db_top_tables'] = collect($topTables ?? [])
            ->map(fn ($r) => ($r->name ?? '').' '.($r->mb ?? '').' MB')
            ->implode(' | ');
    }

    public function pollDevMonitors(): void
    {
        $this->captureServerStats();
        match ($this->activeTab) {
            'health' => $this->refreshHealthPollChunk(),
            'queue' => $this->loadQueueJobs(),
            'tracking' => $this->loadTrackingOrders(),
            'sessions' => $this->loadSessions(),
            'sync' => $this->loadSyncErrorsLight(),
            'performance' => $this->refreshPerformanceTab(),
            default => null,
        };
    }

    protected function refreshHealthPollChunk(): void
    {
        $this->fetchLogs();
        $this->fetchFailedJobs();
        $this->checkHealth();
    }

    protected function loadSyncErrorsLight(): void
    {
        $this->syncErrors = SourcingOrder::whereNotNull('sheet_sync_error')
            ->with('user', 'quotation.sourcingRequest')
            ->latest()
            ->get();
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
        $this->loadEnvPreview();
        $this->loadCapturedMails();
        $this->loadBackups();
        $this->loadScheduledTasks();
        $this->loadSessions();
        $this->loadSeeders();
        $this->checkHealth();
        $this->loadQueueJobs();
        $this->loadTrackingStats();
        $this->loadFeatureFlags();
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
        if (! $this->devGate(requirePassword: true)) {
            return;
        }

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
        AuditLogger::log('force_sync_all', null, null, ['orders_synced' => $count]);
        $this->dispatch('show-success-toast', message: "$count orders synced successfully.");
        $this->loadData();
    }

    public function impersonate($userId)
    {
        $user = User::find($userId);
        if (! $user) {
            return;
        }

        if (! session()->has('dev_impersonator_id')) {
            session()->put('dev_impersonator_id', Auth::id());
        }

        AuditLogger::log('impersonate', User::class, (int) $user->id, [
            'target_email' => $user->email,
            'target_role' => $user->role,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function getFilteredUsersProperty()
    {
        $users = $this->users instanceof \Illuminate\Support\Collection
            ? $this->users
            : collect($this->users);

        if (empty($this->userSearch)) {
            return $users;
        }

        $search = strtolower(trim($this->userSearch));

        return $users->filter(function ($user) use ($search) {
            return str_contains(strtolower((string) $user->name), $search)
                || str_contains(strtolower((string) $user->email), $search)
                || str_contains(strtolower((string) $user->role), $search);
        });
    }

    public function updateUserPassword($userId)
    {
        $actor = Auth::user();

        if (! $actor) {
            $this->dispatch('show-error-toast', message: 'Unauthorized action.');

            return;
        }

        $validator = Validator::make(
            [
                'current_admin_password' => $this->currentAdminPassword,
                'password' => $this->passwordInputs[$userId] ?? '',
                'password_confirmation' => $this->passwordConfirmations[$userId] ?? '',
            ],
            [
                'current_admin_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('show-error-toast', message: $validator->errors()->first());

            return;
        }

        if (! Hash::check($this->currentAdminPassword, (string) $actor->password)) {
            $this->dispatch('show-error-toast', message: 'Current password is incorrect.');

            return;
        }

        $user = User::find($userId);

        if (! $user) {
            $this->dispatch('show-error-toast', message: 'User not found.');

            return;
        }

        if ((int) $user->id === (int) $actor->id) {
            $this->dispatch('show-error-toast', message: 'You cannot reset your own password from this panel.');

            return;
        }

        if (in_array((string) $user->role, ['super_admin', 'developer'], true)) {
            $this->dispatch('show-error-toast', message: 'Password reset is blocked for protected roles.');

            return;
        }

        $user->password = $this->passwordInputs[$userId];
        $user->save();

        AuditLogger::log('update_user_password', User::class, (int) $user->id, [
            'target_email' => $user->email,
        ]);

        $this->currentAdminPassword = '';
        $this->passwordInputs[$userId] = '';
        $this->passwordConfirmations[$userId] = '';

        $this->dispatch('show-success-toast', message: "Password updated for {$user->name}.");
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
        if (! $this->devGate(requirePassword: true)) {
            return;
        }
        $directory = storage_path('app/mails');
        if (File::exists($directory)) {
            File::cleanDirectory($directory);
        }
        $this->capturedMails = [];
        $this->selectedMail = null;
        AuditLogger::log('clear_all_mails', null, null, []);
        $this->dispatch('show-success-toast', message: 'All mails cleared.');
    }

    public function loadBackups()
    {
        $this->backups = (new \App\Services\BackupService)->listBackups();
    }

    public function createDatabaseBackup()
    {
        try {
            (new \App\Services\BackupService)->createDatabaseBackup();
            $this->loadBackups();
            $this->dispatch('show-success-toast', message: 'Database backup created.');
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Backup failed: '.$e->getMessage());
        }
    }

    public function createFilesBackup()
    {
        try {
            (new \App\Services\BackupService)->createFilesBackup();
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
        if (! $this->devGate(requirePassword: true)) {
            return;
        }
        try {
            // Cleanup command string if it starts with 'php artisan'
            $artisanCmd = str_replace('\'php\' \'artisan\' ', '', $command);
            $artisanCmd = trim($artisanCmd, '\'');

            \Illuminate\Support\Facades\Artisan::call($artisanCmd);
            AuditLogger::log('run_scheduled_task', null, null, ['command' => $artisanCmd]);
            $this->dispatch('show-success-toast', message: "Task '{$artisanCmd}' executed.");
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Execution failed: '.$e->getMessage());
        }
    }

    public function runArtisan($command)
    {
        if (! $this->devGate(requirePassword: true)) {
            return;
        }
        try {
            app(ArtisanWhitelist::class)->assertAllowed((string) $command);
            Artisan::call((string) $command);
            AuditLogger::log('run_artisan', null, null, ['command' => $command]);
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
        $row = DB::table('sessions')->where('id', $id)->first();
        if ($row && $row->user_id) {
            $u = User::find($row->user_id);
            if ($u && in_array((string) $u->role, ['super_admin', 'developer'], true)) {
                if (! $this->devGate(requirePassword: true)) {
                    return;
                }
            }
        }

        DB::table('sessions')->where('id', $id)->delete();
        AuditLogger::log('delete_session', null, null, ['session_id' => $id]);
        $this->loadSessions();
        $this->dispatch('show-success-toast', message: 'Session terminated.');
    }

    public function loadSeeders()
    {
        $directory = database_path('seeders');
        $files = File::files($directory);
        $this->availableSeeders = collect($files)
            ->map(fn ($file) => $file->getFilenameWithoutExtension())
            ->filter(fn ($name) => $name !== 'DatabaseSeeder')
            ->values()
            ->toArray();
    }

    public function runSeeder($seederClass)
    {
        if (! $this->devGate(requirePassword: true)) {
            return;
        }
        $this->seederLoading = $seederClass;
        try {
            Artisan::call('db:seed', ['--class' => $seederClass, '--no-interaction' => true]);
            AuditLogger::log('run_seeder', null, null, ['class' => $seederClass]);
            $this->dispatch('show-success-toast', message: "Seeder {$seederClass} executed.");
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Seeder failed: '.$e->getMessage());
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
            if (! class_exists('Redis') && config('database.redis.client') === 'phpredis') {
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
                $client = new \GuzzleHttp\Client;
                $response = $client->get('https://api.17track.net/track/v2.2/getcarrier', [
                    'headers' => ['17token' => $apiKey],
                    'timeout' => 5,
                ]);
                if ($response->getStatusCode() === 200) {
                    $status['17Track API'] = ['ok' => true, 'message' => 'API Responsive'];
                } else {
                    $status['17Track API'] = ['ok' => false, 'message' => 'HTTP '.$response->getStatusCode()];
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
        if (! $this->devGate(requirePassword: true)) {
            return;
        }
        try {
            $cmd = 'queue:retry '.(int) $id;
            app(ArtisanWhitelist::class)->assertAllowed($cmd);
            Artisan::call($cmd);
            AuditLogger::log('queue_retry', null, null, ['failed_job_id' => (int) $id]);
            $this->dispatch('show-success-toast', message: "Job #{$id} pushed back to queue.");
            $this->fetchFailedJobs();
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Retry failed: '.$e->getMessage());
        }
    }

    public function runSqlQuery($customQuery = null)
    {
        $this->query = $customQuery !== null ? $customQuery : $this->query;
        $this->queryError = null;
        $this->queryResult = null;
        $this->explainResult = [];

        if (empty(trim((string) $this->query))) {
            return;
        }

        try {
            $trimmedQuery = trim((string) $this->query);
            $upper = strtoupper($trimmedQuery);
            $isSelect = str_starts_with($upper, 'SELECT');
            $isSchema = str_starts_with($upper, 'SHOW') || str_starts_with($upper, 'DESCRIBE');
            $isExplain = str_starts_with($upper, 'EXPLAIN');
            $isWrite = $this->sqlLooksDestructive($trimmedQuery);

            if ($isWrite) {
                if (! $this->dbAllowWrite) {
                    $this->queryError = 'Écriture SQL refusée : activez le mode écriture et fournissez votre mot de passe.';

                    return;
                }
                if (! $this->devGate(requirePassword: true)) {
                    return;
                }
                DB::statement($trimmedQuery);
                DevQueryLog::create([
                    'user_id' => Auth::id(),
                    'query' => $trimmedQuery,
                    'is_write' => true,
                    'rows_affected' => null,
                    'ip' => request()?->ip(),
                    'request_id' => request()?->attributes->get('request_id'),
                ]);
                AuditLogger::log('run_sql_write', null, null, ['query_preview' => \Illuminate\Support\Str::limit($trimmedQuery, 500)]);
                $this->queryResult = [['Status' => 'OK', 'Message' => 'Requête exécutée (écriture).']];
            } elseif ($isSelect || $isSchema || $isExplain) {
                $limitedQuery = $trimmedQuery;
                if ($isSelect && ! preg_match('/\bLIMIT\b/i', $limitedQuery)) {
                    $limitedQuery = rtrim($limitedQuery, ';').' LIMIT 200';
                }

                $result = DB::select($limitedQuery);
                $this->queryResult = json_decode(json_encode($result), true);

                if ($isSelect && count($this->queryResult) >= 200 && ! preg_match('/\bLIMIT\b/i', $trimmedQuery)) {
                    $this->dispatch('show-info-toast', message: 'Results limited to 200 rows for performance.');
                }
            } else {
                $this->queryError = 'Seules les requêtes SELECT / SHOW / DESCRIBE / EXPLAIN ou les écritures explicites (mode écriture) sont autorisées.';

                return;
            }

            if (! in_array($trimmedQuery, $this->queryHistory, true)) {
                array_unshift($this->queryHistory, $trimmedQuery);
                $this->queryHistory = array_slice($this->queryHistory, 0, 10);
                session()->put('dev_query_history', $this->queryHistory);
            }
        } catch (\Exception $e) {
            $this->queryError = $e->getMessage();
        }
    }

    public function runSqlExplain(): void
    {
        $this->explainResult = [];
        $this->queryError = null;
        $q = trim((string) $this->query);
        if ($q === '' || ! preg_match('/^SELECT\b/i', $q)) {
            $this->queryError = 'EXPLAIN disponible uniquement pour un SELECT.';

            return;
        }
        try {
            $rows = DB::select('EXPLAIN '.$q);
            $this->explainResult = json_decode(json_encode($rows), true);
        } catch (\Exception $e) {
            $this->queryError = $e->getMessage();
        }
    }

    protected function sqlLooksDestructive(string $q): bool
    {
        return (bool) preg_match('/^\s*(INSERT|UPDATE|DELETE|DROP|ALTER|TRUNCATE|CREATE|REPLACE|GRANT|REVOKE)\b/i', ltrim($q));
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

        if (! $this->dbAllowWrite || ! $this->devGate(requirePassword: true)) {
            $this->dispatch('show-error-toast', message: 'Activez l’écriture SQL + mot de passe pour modifier une cellule.');

            return;
        }

        try {
            DB::table($this->editingCell['table'])
                ->where('id', $this->editingCell['id'])
                ->update([$this->editingCell['column'] => $this->editingCell['value']]);

            DevQueryLog::create([
                'user_id' => Auth::id(),
                'query' => 'UPDATE '.$this->editingCell['table'].' SET '.$this->editingCell['column'].' WHERE id='.$this->editingCell['id'],
                'is_write' => true,
                'ip' => request()?->ip(),
                'request_id' => request()?->attributes->get('request_id'),
            ]);
            AuditLogger::log('db_cell_update', null, null, ['table' => $this->editingCell['table'], 'id' => $this->editingCell['id']]);

            $this->dispatch('show-success-toast', message: 'Cell updated successfully.');
            $this->editingCell = null;
            $this->runSqlQuery();
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

            $isSuccess = ($stat->status !== 'Failed' && $stat->status !== 'Numéro introuvable' && $stat->status !== 'Error' && ! str_contains(strtolower($stat->status), 'error'));

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
        if ($type === 'all' && ! $this->devGate(requirePassword: true)) {
            return;
        }
        try {
            switch ($type) {
                case 'all':
                    Artisan::call('cache:clear');
                    $message = 'All cache cleared successfully';
                    AuditLogger::log('clear_cache', null, null, ['scope' => 'all']);
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

            $channels = array_values(array_unique($this->devPingChannels));

            if ($channels === []) {
                $this->notificationTestError = 'Sélectionnez au moins un canal (mail / database / fcm).';

                return;
            }

            if (in_array('fcm', $channels, true) && empty($user->fcm_token)) {
                $this->notificationTestError = 'FCM sélectionné mais aucun jeton pour cet utilisateur';

                return;
            }

            $title = $this->testNotificationTitle ?: 'Test Notification';
            $body = $this->testNotificationBody ?: 'This is a test notification from Dev Dashboard';

            $user->notify(new DevPingNotification(
                $title,
                $body,
                ['sent_from' => 'dev_dashboard', 'test' => true],
                $channels
            ));

            $fcmPreview = [
                'title' => $title,
                'body' => $body,
                'data' => ['type' => 'dev_ping', 'sent_from' => 'dev_dashboard'],
            ];

            $this->notificationTestResult = [
                'user' => $user->name,
                'email' => $user->email,
                'fcm_token' => $user->fcm_token ? substr((string) $user->fcm_token, 0, 20).'...' : null,
                'title' => $title,
                'body' => $body,
                'channels' => $channels,
                'fcm_payload_preview' => $fcmPreview,
            ];

            $this->dispatch('show-success-toast', message: 'Notification de test envoyée.');
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

        try {
            $mailable = $this->buildMailableForDev();
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

        if (empty($this->testEmailRecipient)) {
            $this->emailTestError = 'Please enter a recipient email';

            return;
        }

        try {
            $mailable = $this->buildMailableForDev();
            if ($mailable) {
                \Illuminate\Support\Facades\Mail::to($this->testEmailRecipient)->send($mailable);
                $this->emailTestResult = "Email sent to {$this->testEmailRecipient}";
                $this->dispatch('show-success-toast', message: 'Test email sent successfully.');
            }
        } catch (\Exception $e) {
            $this->emailTestError = $e->getMessage();
            $this->dispatch('show-error-toast', message: 'Error: '.$e->getMessage());
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

    // Feature Flags
    public $featureFlags = [];

    public $newFeatureKey = '';

    public $newFeatureName = '';

    public function loadFeatureFlags()
    {
        $query = \App\Models\FeatureFlag::query();

        if ($this->featureSearch) {
            $query->where(function ($q) {
                $q->where('key', 'like', '%'.$this->featureSearch.'%')
                    ->orWhere('name', 'like', '%'.$this->featureSearch.'%');
            });
        }

        $this->featureFlags = $query->get()->toArray();
    }

    public function updatedFeatureSearch()
    {
        $this->loadFeatureFlags();
    }

    public function toggleFeatureStatus($id, $status)
    {
        $flag = \App\Models\FeatureFlag::find($id);
        if ($flag) {
            $flag->update(['status' => $status]);
            app(\App\Services\FeatureFlagService::class)->clearCache($flag->key);
            $this->loadFeatureFlags();
            $this->dispatch('show-success-toast', message: "Feature '{$flag->name}' status updated to {$status}.");
        }
    }

    public function updateFeatureRoles($id, $roles)
    {
        $flag = \App\Models\FeatureFlag::find($id);
        if ($flag) {
            $flag->update(['roles' => $roles]);
            app(\App\Services\FeatureFlagService::class)->clearCache($flag->key);
            $this->loadFeatureFlags();
            $this->dispatch('show-success-toast', message: "Feature '{$flag->name}' roles updated.");
        }
    }

    public function addFeatureFlag()
    {
        $this->validate([
            'newFeatureKey' => 'required|unique:feature_flags,key',
            'newFeatureName' => 'required',
        ]);

        \App\Models\FeatureFlag::create([
            'key' => $this->newFeatureKey,
            'name' => $this->newFeatureName,
            'status' => 'visible',
            'roles' => [],
        ]);

        $this->newFeatureKey = '';
        $this->newFeatureName = '';
        $this->loadFeatureFlags();
        $this->dispatch('show-success-toast', message: 'Feature flag added.');
    }

    public function initializeFeatureFlags()
    {
        try {
            Artisan::call('db:seed', ['--class' => 'FeatureFlagSeeder', '--no-interaction' => true]);
            $this->loadFeatureFlags();
            $this->dispatch('show-success-toast', message: 'Feature flags initialized from defaults.');
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: 'Initialization failed: '.$e->getMessage());
        }
    }

    public function deleteFeatureFlag($id)
    {
        if (! $this->devGate(requirePassword: true)) {
            return;
        }
        $flag = \App\Models\FeatureFlag::find($id);
        if ($flag) {
            app(\App\Services\FeatureFlagService::class)->clearCache($flag->key);
            AuditLogger::log('delete_feature_flag', \App\Models\FeatureFlag::class, (int) $id, ['key' => $flag->key]);
            $flag->delete();
            $this->loadFeatureFlags();
            $this->dispatch('show-success-toast', message: 'Feature flag deleted.');
        }
    }

    protected function devGate(bool $requirePassword): bool
    {
        $key = 'dev-dash-ops:'.Auth::id();
        if (! RateLimiter::attempt($key, 12, fn () => true, 60)) {
            $this->dispatch('show-error-toast', message: 'Limite de débit (12 actions/min). Réessayez dans une minute.');

            return false;
        }
        if ($requirePassword && ! $this->verifyDevSensitivePassword()) {
            return false;
        }
        if ($requirePassword) {
            $this->devSensitivePassword = '';
        }

        return true;
    }

    protected function verifyDevSensitivePassword(): bool
    {
        $actor = Auth::user();
        if (! $actor || ! Hash::check($this->devSensitivePassword, (string) $actor->password)) {
            $this->dispatch('show-error-toast', message: 'Mot de passe de confirmation invalide.');

            return false;
        }

        return true;
    }

    public function loadAvailableMailables(): void
    {
        $dir = app_path('Mail');
        $classes = [];
        foreach (glob($dir.DIRECTORY_SEPARATOR.'*.php') ?: [] as $file) {
            $short = basename($file, '.php');
            $fqcn = 'App\\Mail\\'.$short;
            if (! class_exists($fqcn)) {
                continue;
            }
            try {
                $ref = new \ReflectionClass($fqcn);
            } catch (\Throwable) {
                continue;
            }
            if ($ref->isSubclassOf(\Illuminate\Mail\Mailable::class) && ! $ref->isAbstract() && $ref->isInstantiable()) {
                $classes[] = $fqcn;
            }
        }
        sort($classes);
        $this->availableMailables = $classes;
        if ($classes !== [] && ! in_array($this->selectedMailableClass, $classes, true)) {
            $this->selectedMailableClass = $classes[0];
        }
    }

    protected function buildMailableForDev(): ?\Illuminate\Mail\Mailable
    {
        if (! class_exists($this->selectedMailableClass)) {
            return null;
        }
        $ref = new \ReflectionClass($this->selectedMailableClass);
        if (! $ref->isSubclassOf(\Illuminate\Mail\Mailable::class) || ! $ref->isInstantiable()) {
            return null;
        }
        $ctor = $ref->getConstructor();
        if (! $ctor) {
            return $ref->newInstance();
        }
        $args = [];
        foreach ($ctor->getParameters() as $param) {
            $args[] = $this->resolveDevMailConstructorArg($param);
        }

        return $ref->newInstanceArgs($args);
    }

    protected function resolveDevMailConstructorArg(\ReflectionParameter $param): object
    {
        $type = $param->getType();
        if (! $type instanceof \ReflectionNamedType || $type->isBuiltin()) {
            throw new \InvalidArgumentException('Paramètre '.$param->getName().' : type objet attendu.');
        }
        $className = $type->getName();

        return match ($className) {
            SourcingOrder::class => $this->testMailOrderId
                ? SourcingOrder::findOrFail($this->testMailOrderId)
                : throw new \InvalidArgumentException('Choisissez une commande pour ce mailable.'),
            RefundRequest::class => RefundRequest::query()->latest()->firstOrFail(),
            User::class => User::query()->where('role', 'client')->firstOrFail(),
            default => throw new \InvalidArgumentException('Type non supporté : '.$className),
        };
    }

    public function loadErrorsDailyLog(): void
    {
        $date = now()->format('Y-m-d');
        $path = storage_path('logs/errors-'.$date.'.log');
        if (! File::exists($path)) {
            $path = storage_path('logs/errors.log');
        }
        if (! File::exists($path)) {
            $this->errorsLogLines = [];
            $this->errorsLogFiltered = [];

            return;
        }
        $lines = array_slice(explode("\n", File::get($path)), -800);
        $parsed = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $level = 'info';
            if (preg_match('/\.(ERROR|CRITICAL|ALERT)/i', $line)) {
                $level = 'error';
            } elseif (preg_match('/\.WARNING/i', $line)) {
                $level = 'warning';
            }
            $reqId = null;
            if (preg_match('/request_id[\"\'\s:]+([a-zA-Z0-9_-]{8,})/i', $line, $m)) {
                $reqId = $m[1];
            }
            $parsed[] = ['text' => $line, 'level' => $level, 'request_id' => $reqId];
        }
        $this->errorsLogLines = $parsed;
        $this->applyErrorsLogFilter();
    }

    public function updatedErrorsLogSearch(): void
    {
        $this->applyErrorsLogFilter();
    }

    protected function applyErrorsLogFilter(): void
    {
        $q = strtolower(trim($this->errorsLogSearch));
        $this->errorsLogFiltered = array_values(array_filter($this->errorsLogLines, function ($row) use ($q) {
            if ($q === '') {
                return true;
            }
            if (! empty($row['request_id']) && str_contains(strtolower((string) $row['request_id']), $q)) {
                return true;
            }

            return str_contains(strtolower((string) $row['text']), $q);
        }));
    }

    public function loadAuditLogsPage(): void
    {
        if (! Schema::hasTable('audit_logs')) {
            $this->auditLogsPage = [];

            return;
        }
        $this->auditLogsPage = AuditLog::query()
            ->with('user:id,name,email')
            ->latest()
            ->limit(150)
            ->get()
            ->map(fn (AuditLog $l) => [
                'id' => $l->id,
                'action' => $l->action,
                'user' => $l->user?->email,
                'target_type' => $l->target_type,
                'target_id' => $l->target_id,
                'request_id' => $l->request_id,
                'ip' => $l->ip,
                'created_at' => $l->created_at?->format('Y-m-d H:i:s'),
                'payload' => $l->payload,
            ])
            ->toArray();
    }

    public function exportAuditLogsCsv()
    {
        if (! Schema::hasTable('audit_logs')) {
            $this->dispatch('show-error-toast', message: 'Table audit_logs absente.');

            return;
        }
        $this->loadAuditLogsPage();
        $rows = $this->auditLogsPage;
        $filename = 'audit_logs_'.now()->format('Y-m-d_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'action', 'user', 'target_type', 'target_id', 'request_id', 'ip', 'created_at', 'payload']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r['id'],
                    $r['action'],
                    $r['user'],
                    $r['target_type'],
                    $r['target_id'],
                    $r['request_id'],
                    $r['ip'],
                    $r['created_at'],
                    json_encode($r['payload'] ?? []),
                ]);
            }
            fclose($out);
        }, $filename, $headers);
    }

    public function loadWebhookEventsList(): void
    {
        if (! Schema::hasTable('webhook_events')) {
            $this->webhookEventsList = [];

            return;
        }
        $this->webhookEventsList = WebhookEvent::query()
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'source' => $e->source,
                'event_type' => $e->event_type,
                'status' => $e->status,
                'error' => $e->error,
                'processed_at' => $e->processed_at?->format('Y-m-d H:i:s'),
                'created_at' => $e->created_at?->format('Y-m-d H:i:s'),
                'payload' => $e->payload,
            ])
            ->toArray();
    }

    public function storeTestWebhook(): void
    {
        if (! Schema::hasTable('webhook_events')) {
            $this->dispatch('show-error-toast', message: 'Migration webhook_events requise.');

            return;
        }
        try {
            $json = json_decode($this->webhookTestPayload, true);
            if ($json === null && trim($this->webhookTestPayload) !== '') {
                $json = ['raw' => $this->webhookTestPayload];
            }
            WebhookEvent::create([
                'source' => $this->webhookTestSource ?: 'manual',
                'event_type' => 'dev_test',
                'payload' => $json ?? [],
                'status' => 'received',
            ]);
            AuditLogger::log('webhook_test_ingest', null, null, ['source' => $this->webhookTestSource]);
            $this->loadWebhookEventsList();
            $this->dispatch('show-success-toast', message: 'Webhook enregistré.');
        } catch (\Throwable $e) {
            $this->dispatch('show-error-toast', message: $e->getMessage());
        }
    }

    public function replayWebhookEvent(int $id): void
    {
        if (! Schema::hasTable('webhook_events')) {
            return;
        }
        $event = WebhookEvent::find($id);
        if (! $event) {
            return;
        }
        WebhookEvent::create([
            'source' => $event->source,
            'event_type' => ($event->event_type ?? 'replay').'_replay',
            'payload' => $event->payload,
            'status' => 'received',
            'processed_at' => null,
            'error' => null,
        ]);
        AuditLogger::log('webhook_replay', WebhookEvent::class, $id);
        $this->loadWebhookEventsList();
        $this->dispatch('show-success-toast', message: 'Replay créé (entrée dupliquée).');
    }

    public function loadTrackingDeep(): void
    {
        $fsb = trim($this->trackingDeepFsb);
        if ($fsb === '') {
            $this->trackingDeepOrder = null;
            $this->trackingTimeline = [];
            $this->trackingProviderP95 = [];

            return;
        }
        $this->trackingDeepOrder = SourcingOrder::resolveFsbNumberToOrder($fsb)
            ?? SourcingOrder::where('tracking_number', $fsb)->first();
        $this->trackingTimeline = TrackingLog::where('tracking_number', $fsb)
            ->orderBy('created_at')
            ->get()
            ->map(fn (TrackingLog $l) => [
                'at' => $l->created_at?->format('Y-m-d H:i:s'),
                'provider' => $l->provider,
                'status' => $l->status,
                'location' => $l->location,
                'payload_excerpt' => \Illuminate\Support\Str::limit(json_encode($l->payload ?? []), 320),
            ])
            ->toArray();

        $logs = TrackingLog::where('created_at', '>=', now()->subDays(7))->get();
        $latByProvider = [];
        foreach ($logs as $l) {
            $p = $l->provider ?: 'unknown';
            $ms = (int) data_get($l->payload, 'duration_ms', data_get($l->payload, 'elapsed_ms', 0));
            if ($ms <= 0) {
                continue;
            }
            $latByProvider[$p][] = $ms;
        }
        $p95 = [];
        foreach ($latByProvider as $provider => $arr) {
            sort($arr);
            $n = count($arr);
            $idx = (int) floor(max(0, ($n * 0.95) - 1));
            $p95[$provider] = [
                'p95_ms' => $arr[$idx] ?? null,
                'samples' => $n,
            ];
        }
        $this->trackingProviderP95 = $p95;
    }

    public function replayTrackingDeepRefresh(): void
    {
        $tn = trim($this->trackingDeepFsb);
        if ($tn === '') {
            return;
        }
        Cache::forget("tracking:{$tn}");
        Cache::forget("tracking_pending:{$tn}");
        Cache::forget("tracking_blocked:{$tn}");
        Cache::forget("tracking_failures:{$tn}");
        $this->refreshTracking($tn);
        $this->loadTrackingDeep();
    }

    public function refreshPerformanceTab(): void
    {
        $sorted = collect($this->debugQueries)->sortByDesc('time')->take(25)->values()->all();
        $this->performanceSlowQueries = $sorted;

        $signatures = collect($this->debugQueries)->map(function ($q) {
            $sql = preg_replace('/\b\d+\b/', '?', (string) $q['sql']) ?? '';

            return preg_replace('/\s+/', ' ', trim($sql));
        });
        $counts = $signatures->countBy()->sortDesc();
        $this->performanceN1Hints = $counts->filter(fn ($c) => $c >= 40)->take(15)->toArray();

        $this->redisInfoSnippet = [];
        try {
            foreach (['memory', 'stats'] as $section) {
                $info = Redis::connection()->info($section);
                if (is_array($info)) {
                    $this->redisInfoSnippet[$section] = array_slice($info, 0, 12);
                }
            }
        } catch (\Throwable $e) {
            $this->redisInfoSnippet = ['error' => $e->getMessage()];
        }
    }

    public function loadRateLimiterOverview(): void
    {
        $this->rateLimiterOverview = [
            [
                'name' => 'google-sheets (jobs)',
                'limit' => '50 / minute',
                'source' => 'AppServiceProvider::RateLimiter::for(google-sheets)',
            ],
            [
                'name' => 'login (Fortify / auth)',
                'limit' => '5 tentatives / minute (route login)',
                'source' => 'routes/auth.php throttle:5,1',
            ],
            [
                'name' => 'tracking data client',
                'limit' => '10 req / minute',
                'source' => 'routes/web.php tracking routes throttle:10,1',
            ],
            [
                'name' => 'Dev dashboard sensible',
                'limit' => '12 actions / minute + mot de passe requis',
                'source' => 'DevDashboard::devGate',
            ],
        ];
    }

    public function getFilteredFcmUsersProperty()
    {
        $users = $this->users instanceof \Illuminate\Support\Collection
            ? $this->users
            : collect($this->users);

        return $users->filter(function ($u) {
            if (empty($u->fcm_token)) {
                return false;
            }
            if ($this->fcmUserSearch === '') {
                return true;
            }
            $s = strtolower($this->fcmUserSearch);

            return str_contains(strtolower((string) $u->name), $s)
                || str_contains(strtolower((string) $u->email), $s);
        });
    }

    public function revokeUserFcm(int $userId): void
    {
        if (! $this->devGate(requirePassword: true)) {
            return;
        }
        $user = User::find($userId);
        if ($user) {
            $user->update(['fcm_token' => null]);
            AuditLogger::log('fcm_token_revoke', User::class, $userId);
            $this->users = User::orderBy('name')->get();
            $this->dispatch('show-success-toast', message: 'Jeton FCM révoqué.');
        }
    }

    public function previewBackupRestore(string $name): void
    {
        if (! $this->devGate(requirePassword: false)) {
            return;
        }
        $this->backupRestoreName = $name;
        $this->backupRestoreConfirmPhrase = '';
        try {
            $path = storage_path('app/backups/'.$name);
            $this->backupRestorePreview = (new BackupService)->previewSqlFile($path);
        } catch (\Throwable $e) {
            $this->backupRestorePreview = ['error' => $e->getMessage()];
        }
    }

    public function executeBackupRestore(): void
    {
        if (trim($this->backupRestoreConfirmPhrase) !== 'RESTORE') {
            $this->dispatch('show-error-toast', message: 'Tapez RESTORE pour confirmer.');

            return;
        }
        if (! $this->devGate(requirePassword: true)) {
            return;
        }
        $path = storage_path('app/backups/'.$this->backupRestoreName);
        try {
            (new BackupService)->restoreDatabaseFromSql($path);
            AuditLogger::log('restore_backup', null, null, ['file' => $this->backupRestoreName]);
            $this->backupRestoreConfirmPhrase = '';
            $this->dispatch('show-success-toast', message: 'Restauration lancée (vérifiez les données).');
        } catch (\Throwable $e) {
            $this->dispatch('show-error-toast', message: $e->getMessage());
        }
    }

    public function loadDbSchemaDetail(string $table): void
    {
        $this->dbSchemaFocusTable = $table;
        try {
            $db = DB::getDatabaseName();
            $this->dbSchemaColumns = json_decode(json_encode(DB::select(
                'SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, COLUMN_DEFAULT, EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION',
                [$db, $table]
            )), true) ?? [];
            $this->dbSchemaIndexes = json_decode(json_encode(DB::select(
                'SELECT INDEX_NAME, COLUMN_NAME, NON_UNIQUE, SEQ_IN_INDEX FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY INDEX_NAME, SEQ_IN_INDEX',
                [$db, $table]
            )), true) ?? [];
        } catch (\Throwable $e) {
            $this->dbSchemaColumns = [];
            $this->dbSchemaIndexes = [];
            $this->dispatch('show-error-toast', message: $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.dev-dashboard')->layout('layouts.dev');
    }
}
