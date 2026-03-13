<?php
require_once __DIR__ . "/../app/support/logger.php";

$dbPath = __DIR__ . '/../database/database.sqlite';

writeLog("info", 'raw-post-show.php Started');

if (!file_exists($dbPath)) {
    writeLog('error', 'SQLite 파일이 존재하지 않습니다: ' . $dbPath);
    exit('SQLite 파일이 존재하지 않습니다.');
}

$post = null;
$postId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

writeLog('info', '요청된 게시글 id=' . $postId);

if ($postId < 1) {
    writeLog('error', '잘못된 게시글 id 요청');
    exit('잘못된 요청입니다.');
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $sql = "
        SELECT
            posts.id,
            posts.title,
            posts.content,
            posts.created_at,
            users.name AS user_name
        FROM posts
        JOIN users ON posts.user_id = users.id
        WHERE posts.id = :id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $postId, PDO::PARAM_INT);
    $stmt->execute();

    $post = $stmt->fetch();

    if ($post === false) {
        writeLog('error', '게시글 없음 id=' . $postId);
        exit('해당 게시글을 찾을 수 없습니다.');
    }

    writeLog('info', '게시글 상세 조회 성공 id=' . $postId);

} catch (PDOException $e) {
    writeLog('error', '게시글 상세 조회 실패: ' . $e->getMessage());
    exit('오류 발생: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시글 상세보기</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
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

        .actions a {
            margin-right: 12px;
        }
    </style>
</head>
<body>
<h1>게시글 상세보기</h1>

<div class="card">
    <div class="row">
        <span class="label">ID</span>
        <span><?= htmlspecialchars((string) $post['id'], ENT_QUOTES, 'UTF-8') ?></span>
    </div>

    <div class="row">
        <span class="label">제목</span>
        <span><?= htmlspecialchars($post['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
    </div>

    <div class="row">
        <span class="label">작성자</span>
        <span><?= htmlspecialchars($post['user_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
    </div>

    <div class="row">
        <span class="label">작성일</span>
        <span><?= htmlspecialchars($post['created_at'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
    </div>

    <div class="row">
        <div class="label">내용</div>
        <div class="content-box"><?= htmlspecialchars($post['content'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
    </div>

    <div class="actions">
        <a href="raw-posts.php">목록으로</a>
    </div>
</div>
</body>
</html>











