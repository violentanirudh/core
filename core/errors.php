<?php

function initErrorHandler() {
    if (!file_exists('logs')) {
        mkdir('logs', 0777, true);
    }
    
    set_error_handler('logError');
    set_exception_handler('logException');
}

function logError($errno, $errstr, $errfile, $errline) {
    $errorTypes = [
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'PARSE',
        E_NOTICE => 'NOTICE',
        E_USER_ERROR => 'USER_ERROR',
        E_USER_WARNING => 'USER_WARNING',
        E_USER_NOTICE => 'USER_NOTICE'
    ];
    
    $type = $errorTypes[$errno] ?? 'UNKNOWN';
    $date = date('Y-m-d H:i:s');
    
    $logMessage = sprintf(
        "[%s] %s:\n  Message: %s\n  File: %s\n  Line: %d\n\n\n",
        $date,
        $type,
        $errstr,
        $errfile,
        $errline
    );
    
    error_log($logMessage, 3, 'logs/error.log');
    displayErrorPage($type, $errstr, $errfile, $errline);
    
    return true;
}

function logException($exception) {
    $date = date('Y-m-d H:i:s');
    
    $logMessage = sprintf(
        "[%s] EXCEPTION:\n  Message: %s\n  File: %s\n  Line: %d\n  Stack Trace:\n    %s\n\n\n",
        $date,
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        str_replace("\n", "\n    ", $exception->getTraceAsString())
    );
    
    error_log($logMessage, 3, 'logs/error.log');
    displayErrorPage('EXCEPTION', $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
}

function displayErrorPage($type, $message, $file, $line, $trace = '') {
    if (!headers_sent()) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: text/html; charset=utf-8');
    }
    
    $output = sprintf(
        "%s\n  Message: %s\n  File: %s\n  Line: %d",
        $type,
        $message,
        $file,
        $line
    );
    
    if ($trace) {
        $output .= "\n\nStack Trace:\n" . $trace;
    }


$htmlTemplate = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Error Occurred - {$type}</title>
    <style>
        body {
            background: #f0f0f0;
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: auto;
        }
        h1 {
            color: #d63031;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        pre {
            background: #2d3436;
            color: #dfe6e9;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>An Error Has Occurred</h1>
        <pre>{output}</pre>
    </div>
</body>
</html>
HTML;

    echo str_replace('{output}', htmlspecialchars($output), $htmlTemplate);
    exit;

}
