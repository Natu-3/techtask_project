const loginForm = document.getElementById('loginForm');
const emailEl = document.getElementById('email');
const passwordEl = document.getElementById('password');

const loadingEl = document.getElementById('loading');
const messageEl = document.getElementById('message');

const emailErrorEl = document.getElementById('emailError');
const passwordErrorEl = document.getElementById('passwordError');

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
    emailErrorEl.textContent = '';
    passwordErrorEl.textContent = '';
}

function renderFieldErrors(errors = {}) {
    if (errors.email && Array.isArray(errors.email)) {
        emailErrorEl.textContent = errors.email[0] ?? '';
    }

    if (errors.password && Array.isArray(errors.password)) {
        passwordErrorEl.textContent = errors.password[0] ?? '';
    }
}

async function login(email, password) {
    const body = new URLSearchParams({
        email,
        password,
    });

    const response = await fetch('/api/login.php', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: body.toString(),
        credentials: 'same-origin',
    });

    const rawText = await response.text();
    console.log('login raw response:', rawText);

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

loginForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    clearFieldErrors();
    setMessage('');

    const email = emailEl.value.trim();
    const password = passwordEl.value;

    setLoading(true);

    try {
        const { ok, result } = await login(email, password);

        if (!ok || result.success !== true) {
            if (result.errors) {
                renderFieldErrors(result.errors);
            }

            setMessage(result.message || '로그인에 실패했습니다.', 'error');
            return;
        }

        setMessage(result.message || '로그인에 성공했습니다.', 'success');

        setTimeout(() => {
            window.location.href = '/ajax-posts.php';
        }, 700);
    } catch (error) {
        console.error(error);
        setMessage('서버 요청 중 오류가 발생했습니다.', 'error');
    } finally {
        setLoading(false);
    }
});
