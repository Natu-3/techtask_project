<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX 게시판 조회</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 15px;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        .post-item {
            border: 1px solid #ddd;
            padding: 12px;
            margin-bottom: 12px;
            max-width: 700px;
        }

        .post-item h3 {
            margin: 0 0 8px;
        }

        .post-meta {
            color: #666;
            font-size: 13px;
            margin-top: 8px;
        }
    </style>
</head>
<body>
<h1>AJAX 게시판 조회</h1>

<div id="messageBox" class="message"></div>

<button type="button" id="reloadButton">새로 불러오기</button>

<hr>

<div id="postList">불러오는 중...</div>

<script src="storage/assets/js/ajax-posts.js"></script>
</body>
</html>
