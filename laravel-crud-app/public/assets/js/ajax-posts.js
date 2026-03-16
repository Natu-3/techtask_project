const searchForm = document.getElementById('searchForm');
const searchTypeEl = document.getElementById('searchType');
const keywordEl = document.getElementById('keyword');
const loadingEl = document.getElementById('loading');
const messageEl = document.getElementById('message');
const tableContainerEl = document.getElementById('tableContainer');
const paginationEl = document.getElementById('pagination');
const logoutButton = document.getElementById('logoutButton');
const loginButton = document.getElementById('loginButton');
const authStatusEl = document.getElementById('authStatus');

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

function updateAuthUi(isLoggedIn, user = null) {
    if (loginButton) {
        loginButton.disabled = isLoggedIn;
    }

    if (logoutButton) {
        logoutButton.disabled = !isLoggedIn;
    }

    if (authStatusEl) {
        authStatusEl.textContent = isLoggedIn
            ? (user?.name ? `${user.name}님 로그인됨` : '로그인됨')
            : '비로그인 상태';
    }
}

async function fetchMe() {
    const response = await fetch('/api/me.php', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    });

    const rawText = await response.text();
    console.log('me raw response:', rawText);

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

async function loadAuthStatus() {
    try {
        const { ok, status, result } = await fetchMe();

        if (ok && result.success === true) {
            updateAuthUi(true, result.data.user);
            return;
        }

        if (status === 401) {
            updateAuthUi(false);
            return;
        }

        updateAuthUi(false);
    } catch (error) {
        console.error(error);
        updateAuthUi(false);
    }
}

function renderTable(items) {
    if (!Array.isArray(items) || items.length === 0) {
        tableContainerEl.innerHTML = '<p class="empty">게시글이 없습니다.</p>';
        return;
    }

    const rows = items.map((post) => {
        const postId = escapeHtml(post.id);
        const title = escapeHtml(post.title ?? '');
        const userName = escapeHtml(post.user_name ?? '');
        const createdAt = escapeHtml(post.created_at ?? '');

        return `
            <tr>
                <td>${postId}</td>
                <td>
                    <a class="post-link" href="/ajax-post-show.php?id=${encodeURIComponent(post.id)}">
                        ${title}
                    </a>
                </td>
                <td>${userName}</td>
                <td>${createdAt}</td>
            </tr>
        `;
    }).join('');

    tableContainerEl.innerHTML = `
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
                ${rows}
            </tbody>
        </table>
    `;
}

function renderPagination(pagination) {
    paginationEl.innerHTML = '';

    if (!pagination || pagination.total_pages <= 1) {
        return;
    }

    const fragment = document.createDocumentFragment();

    if (pagination.current_page > 1) {
        const prevButton = document.createElement('button');
        prevButton.type = 'button';
        prevButton.textContent = '이전';
        prevButton.addEventListener('click', () => {
            loadPosts(pagination.current_page - 1);
        });
        fragment.appendChild(prevButton);
    }

    for (let i = 1; i <= pagination.total_pages; i += 1) {
        if (i === pagination.current_page) {
            const strong = document.createElement('strong');
            strong.textContent = String(i);
            fragment.appendChild(strong);
        } else {
            const pageButton = document.createElement('button');
            pageButton.type = 'button';
            pageButton.textContent = String(i);
            pageButton.addEventListener('click', () => {
                loadPosts(i);
            });
            fragment.appendChild(pageButton);
        }
    }

    if (pagination.current_page < pagination.total_pages) {
        const nextButton = document.createElement('button');
        nextButton.type = 'button';
        nextButton.textContent = '다음';
        nextButton.addEventListener('click', () => {
            loadPosts(pagination.current_page + 1);
        });
        fragment.appendChild(nextButton);
    }

    paginationEl.appendChild(fragment);
}

async function loadPosts(page = 1) {
    const searchType = searchTypeEl.value;
    const keyword = keywordEl.value.trim();

    const params = new URLSearchParams({
        search_type: searchType,
        keyword,
        page: String(page),
    });

    setLoading(true);
    setMessage('');
    tableContainerEl.innerHTML = '';
    paginationEl.innerHTML = '';

    try {
        const response = await fetch(`/api/posts/index.php?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        });

        const rawText = await response.text();
        console.log('posts raw response:', rawText);

        let result;

        try {
            result = JSON.parse(rawText);
        } catch (error) {
            throw new Error(`JSON 응답 파싱 실패: ${rawText}`);
        }

        if (!response.ok || result.success !== true) {
            throw new Error(result.message || '목록 조회에 실패했습니다.');
        }

        renderTable(result.data.items);
        renderPagination(result.data.pagination);

        const totalCount = result.data.pagination?.total_count ?? 0;
        setMessage(`총 ${totalCount}개의 게시글이 조회되었습니다.`);
    } catch (error) {
        console.error(error);
        setMessage(error.message || '서버 요청 중 오류가 발생했습니다.', true);
    } finally {
        setLoading(false);
    }
}

async function logout() {
    const response = await fetch('/api/logout.php', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    });

    const rawText = await response.text();
    console.log('logout raw response:', rawText);

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

searchForm.addEventListener('submit', (event) => {
    event.preventDefault();
    loadPosts(1);
});


if (loginButton) {
    loginButton.addEventListener('click', () => {
        window.location.href = '/ajax-login.php';
    });
}

if (logoutButton) {
    logoutButton.addEventListener('click', async () => {
        try {
            const { ok, result } = await logout();

            if (!ok || result.success !== true) {
                throw new Error(result.message || '로그아웃에 실패했습니다.');
            }

            updateAuthUi(false);
            window.location.href = '/ajax-login.php';
        } catch (error) {
            console.error(error);
            alert(error.message || '로그아웃 중 오류가 발생했습니다.');
        }
    });
}

window.addEventListener('DOMContentLoaded', async () => {
    await loadAuthStatus();
    await loadPosts(1);
});
