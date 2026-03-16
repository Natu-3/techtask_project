<?php

declare(strict_types=1);

require __DIR__ . '/response.php';

function startSessionIfNeeded(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function loginUser(int $userId, string $userName): void
{
    startSessionIfNeeded();

    $_SESSION['userId'] = $userId;
    $_SESSION['user_name'] = $userName;
}

function logoutUser(): void{
    startSessionIfNeeded();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
}


function currentUserId(): ?int
{
    StartSessionIfNeeded();

    if (!isset($_SESSION['userId'])) {
        return null;
    }

    return (int) $_SESSION['userId'];
}


function currentUserName(): ?string
{
    StartSessionIfNeeded();

    if (!isset($_SESSION['user_name'])) {
        return null;
    }

    return (string) $_SESSION['user_name'];
}

function isLoggedIn(): bool
{
    return currentUserId() !== null;
}

function requireLogin(): int
{
    $userId = currentUserId();

    if ($userId === null){
        jsonError('로그인이 필요합니다.',401);
    }

    return $userId;
}


