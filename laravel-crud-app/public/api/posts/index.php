<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/support/db.php';
require_once __DIR__ . '/../../../app/support/response.php';
require_once __DIR__ . '/../../../app/support/logger.php';

writeLog('info', 'API posts/index.php started');

requireMethod('GET');

try {
    $pdo = getPdo();

    $searchType = $_GET['search_type'] ?? 'all';
    $keyword = trim($_GET['keyword'] ?? '');
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

    if ($page < 1) {
        $page = 1;
    }

    $allowedSearchTypes = ['all', 'title', 'writer'];
    if (!in_array($searchType, $allowedSearchTypes, true)) {
        jsonError('허용되지 않은 search_type 입니다.', 400);
    }

    $perPage = 5;
    $whereSql = '';
    $params = [];

    if ($keyword !== '') {
        if ($searchType === 'title') {
            $whereSql = ' WHERE posts.title LIKE :keyword ';
        } elseif ($searchType === 'writer') {
            $whereSql = ' WHERE users.name LIKE :keyword ';
        } else {
            $whereSql = ' WHERE posts.title LIKE :keyword OR users.name LIKE :keyword ';
        }

        $params[':keyword'] = '%' . $keyword . '%';
    }

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

    if ($totalPages > 0 && $page > $totalPages) {
        $page = $totalPages;
    }

    $offset = ($page - 1) * $perPage;

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

    writeLog('info', '게시글 목록 API 조회 성공 total=' . count($posts));

    jsonSuccess('게시글 목록 조회 성공', [
        'items' => $posts,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $perPage,
            'total_count' => $totalCount,
            'total_pages' => $totalPages,
        ],
        'search' => [
            'search_type' => $searchType,
            'keyword' => $keyword,
        ],
    ], 200);

} catch (Throwable $e) {
    writeLog('error', '게시글 목록 API 조회 실패: ' . $e->getMessage());
    jsonError('서버 내부 오류가 발생했습니다.', 500);
}
