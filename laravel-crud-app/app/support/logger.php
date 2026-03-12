<?php

function writeLog(string $level, string $message): void
{
    $logDir = __DIR__ . '../logs';
    $logFile = $logDir . '/app.log';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    $date = date('Y-m-d H:i:s');
    $formattedMessage = "[{$date}] " . strtoupper($level) . ": {$message}" . PHP_EOL;

    file_put_contents($logFile, $formattedMessage, FILE_APPEND | LOCK_EX);
}
