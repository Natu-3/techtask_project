<?php

require_once __DIR__ . '/../app/support/logger.php';
writeLog('info', 'ajax-post-show.php started');

session_start();
$currentUserId = $_SESSION['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>AJAX 게시글 상세보기</title>
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

        .loading {
            color: #666;
            margin-bottom: 16px;
        }

        .message {
            margin-bottom: 16px;
            color: #444;
        }

        .error {
            color: #c00;
        }

        .card {
            max-width: 800px;
            border: 1px solid #ccc;
            padding: 24px;
        }

        .row {
            margin-bottom: 16px;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            min-width: 80px;
        }

        .content-box {
            border: 1px solid #ddd;
            padding: 16px;
            min-height: 120px;
            background-color: #fafafa;
            white-space: pre-wrap;
        }

        .actions {
            margin-top: 24px;
        }

        .actions a,
        .actions button {
            margin-right: 12px;
        }

        .form-input,
        .form-textarea{
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-textarea {
            min-height: 160px;
            resize: vertical;
        }
    </style>
</head>
<body>
<div class="top-links">
    <a href="/posts">Main Menu</a>
    <a href="/ajax-posts.php">AJAX 목록</a>
</div>

<h1>AJAX 게시글 상세보기</h1>

<div id="loading" class="loading" style="display:none;">불러오는 중...</div>
<div id="message" class="message"></div>
<div id="detailContainer"></div>

<script>
    window.AJAX_POST_SHOW = {
        currentUserId: <?= $currentUserId !== null ? (int)$currentUserId : 'null' ?>
    };
</script>
<script src="/assets/js/ajax-post-show.js"></script>
</body>
</html>
