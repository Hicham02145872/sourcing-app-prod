<?php
$ar = json_decode(file_get_contents(__DIR__ . '/lang/ar.json'), true);
$keys = file(__DIR__ . '/temp_client_i18n_keys.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$missing = [];
foreach ($keys as $k) {
    if (!array_key_exists($k, $ar)) {
        $missing[] = $k;
    }
}
file_put_contents(__DIR__ . '/temp_client_i18n_missing.txt', implode(PHP_EOL, $missing));
echo 'Missing count: ' . count($missing) . PHP_EOL;
