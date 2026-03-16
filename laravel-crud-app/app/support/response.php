<?php
# JSON 응답형식 선언부
# HTTP status code 설정 및 성공/실패분기 응답 선언

declare(strict_types=1);

function  jsonResponse(array $body, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=UTF-8');

    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function jsonSuccess(string $message, $data = null, int $status = 200): void
{
    $response = [
        'success' => true,
        'message' => $message,
    ];

    if ($data !== null) {
        $response['data'] = $data;
    }
    jsonResponse($response, $status);
}

function jsonError(string $message, int $status = 400, array $errors = []): void
{
    $response = [
        'success' => false,
        'message' => $message,
    ];

    if (!empty($errors)) {
        $response['errors'] = $errors;
    }

    jsonResponse($response, $status);
}

function requireMethod(string $method): void
{
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if (strtoupper($requestMethod) !== strtoupper($method)) {
        jsonError('허용되지 않은 메서드입니다.', 405);
    }
}
