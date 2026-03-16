<?php
require_once __DIR__ . '/../app/support/auth.php';
require_once __DIR__ . '/../app/support/logger.php';

writeLog('info', 'ajax-post-create.php started');

if (!isLoggedIn()) {
    header('Location: /ajax-login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>AJAX 게시글 작성</title>
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

        .form-box {
            max-width: 800px;
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

        input, textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            font-size: 14px;
        }

        textarea {
            min-height: 220px;
            resize: vertical;
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

<h1>AJAX 게시글 작성</h1>

<div class="form-box">
    <div id="loading" class="loading" style="display:none;">저장 중...</div>
    <div id="message" class="message"></div>

    <form id="createPostForm">
        <div class="form-group">
            <label for="title">제목</label>
            <input type="text" id="title" name="title" placeholder="제목을 입력하세요">
            <div id="titleError" class="field-error"></div>
        </div>

        <div class="form-group">
            <label for="content">내용</label>
            <textarea id="content" name="content" placeholder="내용을 입력하세요"></textarea>
            <div id="contentError" class="field-error"></div>
        </div>

        <button type="submit">저장</button>
    </form>
</div>

<script src="/assets/js/ajax-post-create.js"></script>
</body>
</html>
