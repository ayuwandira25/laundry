<?php
// Tampilkan semua error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = 'Username dan password harus diisi.';
    } else {
        $found_user = getUserForLogin($username);

        if ($found_user && password_verify($password, $found_user['password'])) {

            $_SESSION['user_id'] = $found_user['id'];
            $_SESSION['user_name'] = $found_user['nama'];
            $_SESSION['user_role'] = $found_user['role'];

            insertLoginHistory(
                $found_user['id'],
                $found_user['email'],
                $found_user['nama'],
                $found_user['role']
            );

            header('Location: dashboard.php');
            exit;

        } else {
            $message = 'Username atau password salah.';
        }
    }
}

if (isset($_GET['success'])) {
    $message = 'Registrasi berhasil! Silakan login.';
}

?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        background: #f4f6f9;
    }

    .login-page-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-container-new {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        padding: 40px 35px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
    }

    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .laundry-icon {
        width: 85px;
        height: 85px;
        background: #eff6ff;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 15px;
    }

    .login-header h1 {
        font-size: 28px;
        color: #1e293b;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .login-subtitle {
        color: #64748b;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
    }

    .input-wrapper {
        position: relative;
    }

    .form-input {
        width: 100%;
        padding: 14px 45px 14px 15px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        background: #f9fafb;
        font-size: 14px;
        transition: 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #2563eb;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }

    .input-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        color: #94a3b8;
    }

    .btn-login {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 12px;
        background: #1e293b;
        color: white;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
    }

    .btn-login:hover {
        background: #0f172a;
        transform: translateY(-2px);
    }

    .alert {
        padding: 14px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        text-align: center;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .login-footer {
        margin-top: 25px;
        text-align: center;
        font-size: 14px;
        color: #64748b;
    }

    .login-footer a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .login-container-new {
            padding: 30px 25px;
        }
    }
</style>

<div class="login-page-wrapper">

    <div class="login-container-new">

        <!-- Header -->
        <div class="login-header">

            <div class="laundry-icon">
                <svg viewBox="0 0 100 100" width="60" height="60" fill="none"
                     xmlns="http://www.w3.org/2000/svg">

                    <circle cx="50" cy="50" r="40"
                            stroke="#1e40af" stroke-width="2"/>

                    <circle cx="50" cy="50" r="35"
                            fill="none"
                            stroke="#0369a1"
                            stroke-width="1"
                            opacity="0.5"/>

                    <circle cx="50" cy="50" r="25"
                            fill="none"
                            stroke="#1e40af"
                            stroke-width="2"/>

                    <path d="M 40 45 Q 38 50 40 55"
                          fill="none"
                          stroke="#0369a1"
                          stroke-width="1.5"/>

                    <path d="M 50 40 Q 48 48 50 56"
                          fill="none"
                          stroke="#0369a1"
                          stroke-width="1.5"/>

                    <path d="M 60 45 Q 58 50 60 55"
                          fill="none"
                          stroke="#0369a1"
                          stroke-width="1.5"/>
                </svg>
            </div>

            <h1>Kyka Laundry</h1>
            <p class="login-subtitle">
                Sistem Manajemen Laundry
            </p>

        </div>

        <!-- Form Login -->
        <form method="POST" action="" class="login-form">

            <?php if ($message): ?>
                <div class="alert <?php echo strpos($message, 'berhasil') !== false ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Username -->
            <div class="form-group">

                <label for="username">
                    Username atau Email
                </label>

                <div class="input-wrapper">

                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-input"
                        placeholder="Masukkan username atau email"
                        required
                        autocomplete="username"
                    >

                    <svg class="input-icon"
                         viewBox="0 0 24 24"
                         fill="currentColor"
                         xmlns="http://www.w3.org/2000/svg">

                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>

                    </svg>

                </div>

            </div>

            <!-- Password -->
            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <div class="input-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password"
                    >

                    <svg class="input-icon"
                         viewBox="0 0 24 24"
                         fill="currentColor"
                         xmlns="http://www.w3.org/2000/svg">

                        <rect x="3" y="11"
                              width="18"
                              height="11"
                              rx="2"
                              ry="2"/>

                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>

                    </svg>

                </div>

            </div>

            <!-- Button -->
            <button type="submit" class="btn-login">
                Masuk
            </button>

        </form>

        <!-- Footer -->
        <div class="login-footer">
            <p>
                Belum punya akun?
                <a href="register.php">
                    Daftar di sini
                </a>
            </p>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>