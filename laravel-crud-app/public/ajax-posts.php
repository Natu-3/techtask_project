<?php
require_once __DIR__ . '/../app/support/logger.php';
writeLog('info', 'ajax-posts.php started');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>AJAX 게시글 목록</title>
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

        .search-form {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-form input,
        .search-form select,
        .search-form button {
            padding: 8px 10px;
            font-size: 14px;
        }

        .message {
            margin-bottom: 16px;
            color: #444;
        }

        .error {
            color: #c00;
        }

        .loading {
            color: #666;
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f5f5f5;
        }

        .empty {
            color: #666;
            padding: 20px 0;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination button,
        .pagination strong {
            margin-right: 8px;
        }

        .pagination button {
            padding: 6px 10px;
            cursor: pointer;
        }

        .post-link {
            color: #0066cc;
            text-decoration: none;
        }

        .post-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="top-links">
    <a href="/posts">Main Menu</a>
    <a href="/raw-posts.php">레거시 목록</a>
    <button type="button" id="loginButton">로그인</button>
    <button type="button" id="logoutButton">로그아웃</button>
    <span id="authStatus">확인중.. </span>
</div>

<h1>AJAX 게시글 목록</h1>

<form id="searchForm" class="search-form">
    <select name="search_type" id="searchType">
        <option value="all">전체</option>
        <option value="title">제목</option>
        <option value="writer">작성자</option>
    </select>

    <input
        type="text"
        name="keyword"
        id="keyword"
        placeholder="검색어를 입력하세요"
    >

    <button type="submit">검색</button>
</form>

<div id="loading" class="loading" style="display:none;">불러오는 중...</div>
<div id="message" class="message"></div>
<div id="tableContainer"></div>
<div id="pagination" class="pagination"></div>

<script src="/assets/js/ajax-posts.js"></script>
</body>
</html>
