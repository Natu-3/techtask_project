<?php

require_once __DIR__ . "/../app/support/logger.php";

$dbPath = __DIR__ . '/../database/database.sqlite';

writeLog("info", 'raw-posts.php Started');

if (!file_exists($dbPath)) {
    writeLog('error', 'SQLite 파일이 존재하지 않습니다: ' . $dbPath);
    exit('SQLite 파일이 존재하지 않습니다.');
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    //writeLog("info", "DB 연결 성공");
    writeLog("info", "게시글 목록 조회 시작");

    $sql = "SELECT id, title, content, user_id, created_at FROM posts ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $posts = $stmt->fetchAll();

    writeLog('info', '게시글 목록 조회 성공  total : '. count($posts));

} catch (PDOException $e) {
    writeLog('게시글 목록 조회 실패 :', $e->getMessage());
    exit('오류 발생: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>순수 PHP 게시글 목록</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
    </style>
</head>
<body>
    <a href="/posts" class="back-button"> Main Menu </a>
    <h1>게시글 목록</h1>

<?php if (empty($posts)): ?>
    <p class="empty">게시글이 없습니다.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>제목</th>
            <th>내용</th>
            <th>작성자 ID</th>
            <th>작성일</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= htmlspecialchars($post['id']) ?></td>
                <td><?= htmlspecialchars($post['title']) ?></td>
                <td><?= nl2br(htmlspecialchars($post['content'])) ?></td>
                <td><?= htmlspecialchars($post['user_id']) ?></td>
                <td><?= htmlspecialchars($post['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</body>
</html>
