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

    writeLog("info", "게시글 목록 조회 시작");

    $searchType = $_GET['search_type'] ?? 'all';
    $keyword = trim($_GET['keyword'] ?? '');

    $perPage = 5;
    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    if ($currentPage < 1) {
        $currentPage = 1;
    }

    $whereSql = "";
    $params = [];

    if ($keyword !== '') {
        if ($searchType === 'title') {
            $whereSql = " WHERE posts.title LIKE :keyword ";
        } elseif ($searchType === 'writer') {
            $whereSql = " WHERE users.name LIKE :keyword ";
        } else {
            $whereSql = " WHERE posts.title LIKE :keyword OR users.name LIKE :keyword ";
        }

        $params[':keyword'] = '%' . $keyword . '%';
    }

    // 전체 개수 조회 (검색 반영)
    $countSql = "
        SELECT COUNT(*)
        FROM posts
        JOIN users ON posts.user_id = users.id
        $whereSql
    ";

    $countStmt = $pdo->prepare($countSql);

    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $countStmt->execute();
    $totalCount = (int) $countStmt->fetchColumn();
    $totalPages = (int) ceil($totalCount / $perPage);

    if ($totalPages > 0 && $currentPage > $totalPages) {
        $currentPage = $totalPages;
    }

    $offset = ($currentPage - 1) * $perPage;

    // 게시글 목록 조회
    $sql = "
        SELECT
            posts.id,
            posts.title,
            posts.content,
            posts.created_at,
            users.name AS user_name
        FROM posts
        JOIN users ON posts.user_id = users.id
        $whereSql
        ORDER BY posts.created_at DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $posts = $stmt->fetchAll();

    writeLog('info', '게시글 목록 조회 성공 total: ' . count($posts));

} catch (PDOException $e) {
    writeLog('error', '게시글 목록 조회 실패: ' . $e->getMessage());
    exit('오류 발생: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
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

        .pagination {
            margin-top: 20px;
        }

        .pagination a,
        .pagination strong {
            margin-right: 8px;
        }
    </style>
</head>
<body>
<a href="/posts" class="back-button">Main Menu</a>
<h1>게시글 목록</h1>

<form method="GET" action="raw-posts.php" class="search-form">
    <select name="search_type">
        <option value="all" <?= $searchType === 'all' ? 'selected' : '' ?>>전체</option>
        <option value="title" <?= $searchType === 'title' ? 'selected' : '' ?>>제목</option>
        <option value="writer" <?= $searchType === 'writer' ? 'selected' : '' ?>>작성자</option>
    </select>

    <input
        type="text"
        name="keyword"
        value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>"
        placeholder="검색어를 입력하세요"
    >

    <button type="submit">검색</button>
</form>

<?php if (empty($posts)): ?>
    <p class="empty">게시글이 없습니다.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= htmlspecialchars((string) $post['id'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="/raw-post-show.php?id=<?= urlencode((string) $post['id']) ?>">
                        <?= htmlspecialchars($post['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <td><?= htmlspecialchars($post['user_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($post['created_at'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?>&search_type=<?= urlencode($searchType) ?>&keyword=<?= urlencode($keyword) ?>">이전</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i === $currentPage): ?>
                <strong><?= $i ?></strong>
            <?php else: ?>
                <a href="?page=<?= $i ?>&search_type=<?= urlencode($searchType) ?>&keyword=<?= urlencode($keyword) ?>">
                    <?= $i ?>
                </a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?= $currentPage + 1 ?>&search_type=<?= urlencode($searchType) ?>&keyword=<?= urlencode($keyword) ?>">다음</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
</body>
</html>
