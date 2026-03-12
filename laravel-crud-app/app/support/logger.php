<?php
date_default_timezone_set('Asia/Seoul');
if (!function_exists('writeLog')) {
    function writeLog(string $level, string $message): void
    {
        $projectRoot = dirname(__DIR__, 2);
        $logDir = $projectRoot . '/logs';
        $logFile = $logDir . '/app.log';

        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $date = date('Y-m-d H:i:s');
        $line = sprintf(
            "[%s] %s: %s%s",
            $date,
            strtoupper($level),
            $message,
            PHP_EOL
        );

        file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
    }
}
