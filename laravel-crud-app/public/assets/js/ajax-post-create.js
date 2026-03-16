const createPostForm = document.getElementById('createPostForm');
const titleEl = document.getElementById('title');
const contentEl = document.getElementById('content');

const loadingEl = document.getElementById('loading');
const messageEl = document.getElementById('message');

const titleErrorEl = document.getElementById('titleError');
const contentErrorEl = document.getElementById('contentError');

function setLoading(isLoading) {
    loadingEl.style.display = isLoading ? 'block' : 'none';
}

function setMessage(text = '', type = '') {
    messageEl.textContent = text;
    messageEl.className = 'message';

    if (type === 'error') {
        messageEl.classList.add('error');
    }

    if (type === 'success') {
        messageEl.classList.add('success');
    }
}

function clearFieldErrors() {
    titleErrorEl.textContent = '';
    contentErrorEl.textContent = '';
}

function renderFieldErrors(errors = {}) {
    if (errors.title && Array.isArray(errors.title)) {
        titleErrorEl.textContent = errors.title[0] ?? '';
    }

    if (errors.content && Array.isArray(errors.content)) {
        contentErrorEl.textContent = errors.content[0] ?? '';
    }
}

async function createPost(title, content) {
    const body = new URLSearchParams({
        title,
        content,
    });

    const response = await fetch('/api/posts/store.php', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: body.toString(),
        credentials: 'same-origin',
    });

    const rawText = await response.text();
    console.log('create post raw response:', rawText);

    let result;

    try {
        result = JSON.parse(rawText);
    } catch (error) {
        throw new Error(`JSON 응답 파싱 실패: ${rawText}`);
    }

    return {
        ok: response.ok,
        status: response.status,
        result,
    };
}

createPostForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    clearFieldErrors();
    setMessage('');

    const title = titleEl.value.trim();
    const content = contentEl.value.trim();

    setLoading(true);

    try {
        const { ok, status, result } = await createPost(title, content);

        if (!ok || result.success !== true) {
            if (status === 401) {
                alert(result.message || '로그인이 필요합니다.');
                window.location.href = '/ajax-login.php';
                return;
            }

            if (result.errors) {
                renderFieldErrors(result.errors);
            }

            setMessage(result.message || '게시글 등록에 실패했습니다.', 'error');
            return;
        }

        setMessage(result.message || '게시글이 등록되었습니다.', 'success');
        window.location.href = `/ajax-post-show.php?id=${encodeURIComponent(result.data.id)}`;
    } catch (error) {
        console.error(error);
        setMessage('서버 요청 중 오류가 발생했습니다.', 'error');
    } finally {
        setLoading(false);
    }
});
