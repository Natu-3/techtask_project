const loadingEl = document.getElementById('loading');
const messageEl = document.getElementById('message');
const detailContainerEl = document.getElementById('detailContainer');

const currentUserId = window.AJAX_POST_SHOW?.currentUserId ?? null;

let currentPost = null;

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

function getPostId() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id');
}

function renderView(post) {
    const isOwner =
        currentUserId !== null &&
        Number(currentUserId) === Number(post.user_id);

    console.log('currentUserId =', currentUserId);
    console.log('post.user_id =', post.user_id);
    console.log('isOwner =', isOwner);
    console.log('post =', post);

    detailContainerEl.innerHTML = `
        <div class="card">
            <div class="row">
                <span class="label">제목</span>
                <span>${escapeHtml(post.title ?? '')}</span>
            </div>

            <div class="row">
                <span class="label">작성자</span>
                <span>${escapeHtml(post.user_name ?? '작성자 없음')}</span>
            </div>

            <div class="row">
                <span class="label">작성일</span>
                <span>${escapeHtml(post.created_at ?? '')}</span>
            </div>

            <div class="row">
                <span class="label">내용</span>
                <div class="content-box">${escapeHtml(post.content ?? '')}</div>
            </div>

            <div class="actions">
                <a href="/ajax-posts.php">목록으로</a>
                ${isOwner ? '<button type="button" id="editButton">수정</button>' : ''}
                ${isOwner ? '<button type="button" id="deleteButton">삭제</button>' : ''}
            </div>
        </div>
    `;

    if (isOwner) {
        const editButton = document.getElementById('editButton');
        const deleteButton = document.getElementById('deleteButton');

        if (editButton) {
            editButton.addEventListener('click', () => {
                renderEditForm(currentPost);
            });
        }

        if (deleteButton) {
            deleteButton.addEventListener('click', async () => {
                await deletePost();
            });
        }
    }
}

function renderEditForm(post) {
    detailContainerEl.innerHTML = `
        <div class="card">
            <div class="row">
                <label class="label" for="editTitle">제목</label>
                <input type="text" id="editTitle" class="form-input" value="${escapeHtml(post.title ?? '')}">
            </div>

            <div class="row">
                <label class="label" for="editContent">내용</label>
                <textarea id="editContent" class="form-textarea">${escapeHtml(post.content ?? '')}</textarea>
            </div>

            <div class="actions">
                <button type="button" id="saveButton">저장</button>
                <button type="button" id="cancelButton">취소</button>
            </div>
        </div>
    `;

    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');

    if (saveButton) {
        saveButton.addEventListener('click', async () => {
            await updatePost();
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', () => {
            renderView(currentPost);
        });
    }
}

async function fetchPostDetail() {
    const postId = getPostId();

    if (!postId) {
        setMessage('게시글 ID가 없습니다.', true);
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
            credentials: 'same-origin',
        });

        const rawText = await response.text();
        console.log('post detail raw response:', rawText);

        let result;

        try {
            result = JSON.parse(rawText);
        } catch (error) {
            throw new Error(`JSON 응답 파싱 실패: ${rawText}`);
        }

        if (!response.ok || result.success !== true) {
            throw new Error(result.message || '게시글 조회에 실패했습니다.');
        }

        currentPost = result.data.item;
        renderView(currentPost);
    } catch (error) {
        console.error(error);
        setMessage(error.message || '서버 요청 중 오류가 발생했습니다.', true);
    } finally {
        setLoading(false);
    }
}

async function updatePost() {
    const id = currentPost?.id;
    const title = document.getElementById('editTitle')?.value.trim() ?? '';
    const content = document.getElementById('editContent')?.value.trim() ?? '';

    setLoading(true);
    setMessage('');

    try {
        const response = await fetch('/api/posts/update.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                id,
                title,
                content,
            }),
        });

        const rawText = await response.text();
        console.log('post update raw response:', rawText);

        let result;

        try {
            result = JSON.parse(rawText);
        } catch (error) {
            throw new Error(`JSON 응답 파싱 실패: ${rawText}`);
        }

        if (!response.ok || result.success !== true) {
            throw new Error(result.message || '게시글 수정에 실패했습니다.');
        }

        currentPost = {
            ...currentPost,
            ...result.data.item,
        };

        renderView(currentPost);
        setMessage(result.message || '게시글이 수정되었습니다.');
    } catch (error) {
        console.error(error);
        setMessage(error.message || '서버 요청 중 오류가 발생했습니다.', true);
    } finally {
        setLoading(false);
    }
}

async function deletePost() {
    const id = currentPost?.id;

    if (!id) {
        setMessage('삭제할 게시글 ID가 없습니다.', true);
        return;
    }

    const confirmed = window.confirm('정말 삭제하시겠습니까?');

    if (!confirmed) {
        return;
    }

    setLoading(true);
    setMessage('');

    try {
        const response = await fetch('/api/posts/delete.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify({ id }),
        });

        const rawText = await response.text();
        console.log('post delete raw response:', rawText);

        let result;

        try {
            result = JSON.parse(rawText);
        } catch (error) {
            throw new Error(`JSON 응답 파싱 실패: ${rawText}`);
        }

        if (!response.ok || result.success !== true) {
            throw new Error(result.message || '게시글 삭제에 실패했습니다.');
        }

        alert(result.message || '게시글이 삭제되었습니다.');
        window.location.href = '/ajax-posts.php';
    } catch (error) {
        console.error(error);
        setMessage(error.message || '서버 요청 중 오류가 발생했습니다.', true);
    } finally {
        setLoading(false);
    }
}

window.addEventListener('DOMContentLoaded', async () => {
    await fetchPostDetail();
});
