<?php

foreach (['fr.json', 'en.json'] as $filename) {
    $path = 'lang/'.$filename;
    if (file_exists($path)) {
        $data = json_decode(file_get_contents($path), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            ksort($data);
            file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            echo "Deduplicated and sorted $filename\n";
        } else {
            echo "Error decoding $filename: ".json_last_error_msg()."\n";
        }
    }
}
