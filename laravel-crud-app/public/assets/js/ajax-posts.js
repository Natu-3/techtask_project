const searchForm = document.getElementById('searchForm');
const searchTypeEl = document.getElementById('searchType');
const keywordEl = document.getElementById('keyword');
const loadingEl = document.getElementById('loading');
const messageEl = document.getElementById('message');
const tableContainerEl = document.getElementById('tableContainer');
const paginationEl = document.getElementById('pagination');

let currentPage = 1;

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
    currentPage = page;

    const searchType = searchTypeEl.value;
    const keyword = keywordEl.value.trim();

    const params = new URLSearchParams({
        search_type: searchType,
        keyword: keyword,
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
        });

        const result = await response.json();

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

searchForm.addEventListener('submit', (event) => {
    event.preventDefault();
    loadPosts(1);
});

window.addEventListener('DOMContentLoaded', () => {
    loadPosts(1);
});
