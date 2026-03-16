<?php
require_once __DIR__ . '/../app/support/logger.php';
writeLog('info', 'ajax-login.php started');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>AJAX 로그인</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .top-links {
            margin-bottom: 16px;
        }

        .top-links a {
            margin-right: 12px;
        }

        .login-box {
            max-width: 420px;
            border: 1px solid #ccc;
            padding: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            font-size: 14px;
        }

        button {
            padding: 10px 14px;
            font-size: 14px;
            cursor: pointer;
        }

        .message {
            margin-bottom: 16px;
            color: #444;
        }

        .error {
            color: #c00;
        }

        .success {
            color: #0a7a28;
        }

        .loading {
            color: #666;
            margin-bottom: 16px;
        }

        .field-error {
            margin-top: 6px;
            color: #c00;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="top-links">
    <a href="/posts">Main Menu</a>
    <a href="/ajax-posts.php">AJAX 게시글 목록</a>
</div>

<h1>AJAX 로그인</h1>

<div class="login-box">
    <div id="loading" class="loading" style="display:none;">처리 중...</div>
    <div id="message" class="message"></div>

    <form id="loginForm">
        <div class="form-group">
            <label for="email">이메일</label>
            <input type="email" id="email" name="email" placeholder="이메일을 입력하세요">
            <div id="emailError" class="field-error"></div>
        </div>

        <div class="form-group">
            <label for="password">비밀번호</label>
            <input type="password" id="password" name="password" placeholder="비밀번호를 입력하세요">
            <div id="passwordError" class="field-error"></div>
        </div>

        <button type="submit">로그인</button>
    </form>
</div>

<script src="/assets/js/ajax-auth.js"></script>
</body>
</html>
