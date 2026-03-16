const loadingEl = document.getElementById('loading');
const messageEl = document.getElementById('message');
const detailContainerEl = document.getElementById('detailContainer');

function setLoading(isLoading) {
    loadingEl.style.display = isLoading ? 'block' : 'none';
}

function setMessage(text = '', isError = false) {
    messageEl.textContent = text;
    messageEl.className = isError ? 'message error' : 'message';
}

function escapeHtml(value) {
    if (value === null || value === undefined) {
        return '';
    }

    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function renderPost(post) {
    const id = escapeHtml(post.id);
    const title = escapeHtml(post.title ?? '');
    const userName = escapeHtml(post.user_name ?? '');
    const createdAt = escapeHtml(post.created_at ?? '');
    const content = escapeHtml(post.content ?? '');

    detailContainerEl.innerHTML = `
        <div class="card">
            <div class="row">
                <span class="label">ID</span>
                <span>${id}</span>
            </div>

            <div class="row">
                <span class="label">제목</span>
                <span>${title}</span>
            </div>

            <div class="row">
                <span class="label">작성자</span>
                <span>${userName}</span>
            </div>

            <div class="row">
                <span class="label">작성일</span>
                <span>${createdAt}</span>
            </div>

            <div class="row">
                <div class="label">내용</div>
                <div class="content-box">${content}</div>
            </div>

            <div class="actions">
                <a href="/ajax-posts.php">목록으로</a>
            </div>
        </div>
    `;
}

function getPostIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id');
}

async function loadPost() {
    const postId = getPostIdFromUrl();

    if (!postId || Number(postId) < 1) {
        setMessage('유효하지 않은 게시글 ID입니다.', true);
        return;
    }

    setLoading(true);
    setMessage('');
    detailContainerEl.innerHTML = '';

    try {
        const response = await fetch(`/api/posts/show.php?id=${encodeURIComponent(postId)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        });

        const result = await response.json();

        if (!response.ok || result.success !== true) {
            throw new Error(result.message || '게시글 상세 조회에 실패했습니다.');
        }

        renderPost(result.data.item);
    } catch (error) {
        console.error(error);
        setMessage(error.message || '서버 요청 중 오류가 발생했습니다.', true);
    } finally {
        setLoading(false);
    }
}

window.addEventListener('DOMContentLoaded', () => {
    loadPost();
});
