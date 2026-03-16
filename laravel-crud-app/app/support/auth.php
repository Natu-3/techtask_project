<?php

declare(strict_types=1);

function startSessionIfNeeded(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function loginUser(int $userId, string $userName): void
{
    startSessionIfNeeded();

    $_SESSION['user_id'] = $userId;
    $_SESSION['user_name'] = $userName;

    session_write_close();
}

function logoutUser(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        return;
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function currentUserId(): ?int
{
    startSessionIfNeeded();

    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function currentUserName(): ?string
{
    startSessionIfNeeded();

    return isset($_SESSION['user_name']) ? (string) $_SESSION['user_name'] : null;
}

function isLoggedIn(): bool
{
    return currentUserId() !== null;
}

function requireLogin(): int
{
    $userId = currentUserId();

    if ($userId === null) {
        jsonError('로그인이 필요합니다.', 401);
    }

    return $userId;
}
