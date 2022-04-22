<?php

ini_set('display_errors', 'Off');

if ($_GET["action"] === 'enrich') {
    sleep(2);
    print file_get_contents('pages/enriched.html');
    exit;
}

if ($_GET["action"] === 'validate') {
    sleep(2);
    print file_get_contents('pages/validated.html');
    exit;
}

print file_get_contents('pages/start.html');