<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Log Error Alert</title>
</head>
<body style="margin:0; padding:0; background:#f8fafc; font-family: Arial, Helvetica, sans-serif; color:#0f172a;">
    <div style="max-width:720px; margin:0 auto; padding:24px;">
        <div style="background:#0f172a; color:#fff; padding:20px 24px; border-radius:14px 14px 0 0;">
            <div style="font-size:12px; text-transform:uppercase; letter-spacing:1.6px; color:#94a3b8; font-weight:700;">{{ $appName }}</div>
            <h1 style="margin:8px 0 0; font-size:24px; line-height:1.2;">Laravel log error detected</h1>
        </div>

        <div style="background:#ffffff; border:1px solid #e2e8f0; border-top:none; border-radius:0 0 14px 14px; padding:24px;">
            <p style="margin:0 0 12px; font-size:15px; line-height:1.6;">Hello,</p>
            <p style="margin:0 0 16px; font-size:15px; line-height:1.6;">An error was detected in the <strong>{{ $logSource }}</strong> logs.</p>

            <div style="display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:12px; margin:20px 0;">
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:14px;">
                    <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#1d4ed8; font-weight:700;">Environment</div>
                    <div style="font-size:16px; font-weight:700; margin-top:4px;">{{ $environment }}</div>
                </div>
                <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:14px;">
                    <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#b91c1c; font-weight:700;">Error Count</div>
                    <div style="font-size:16px; font-weight:700; margin-top:4px;">{{ $count }}</div>
                </div>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:14px;">
                    <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#475569; font-weight:700;">Source</div>
                    <div style="font-size:16px; font-weight:700; margin-top:4px;">{{ $logSource }}</div>
                </div>
            </div>

            <div style="margin:22px 0;">
                <h2 style="font-size:16px; margin:0 0 12px;">Recent error lines</h2>
                <div style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                    @foreach($errors as $error)
                        <div style="padding:12px 14px; border-bottom:1px solid #e2e8f0; background:{{ $loop->odd ? '#ffffff' : '#f8fafc' }};">
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                                <span style="display:inline-block; padding:3px 8px; border-radius:999px; background:#fee2e2; color:#b91c1c; font-size:11px; font-weight:700; text-transform:uppercase;">{{ strtoupper($error['level'] ?? 'error') }}</span>
                                @if(!empty($error['request_id']))
                                    <span style="font-size:12px; color:#64748b; font-family:monospace;">request_id: {{ $error['request_id'] }}</span>
                                @endif
                            </div>
                            <div style="font-family:monospace; font-size:12px; line-height:1.6; white-space:pre-wrap; word-break:break-word; color:#0f172a;">{{ $error['text'] ?? 'Unknown error' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="margin-top:24px; text-align:center;">
                <a href="{{ $dashboardUrl }}" style="display:inline-block; background:#0f172a; color:#ffffff; text-decoration:none; padding:12px 18px; border-radius:10px; font-weight:700;">Open Dev Dashboard</a>
            </div>

            <p style="margin:22px 0 0; font-size:12px; color:#64748b; line-height:1.6; text-align:center;">
                This alert is sent only when Laravel logs contain error-level entries and the dashboard toggle is enabled.
            </p>
        </div>
    </div>
</body>
</html>