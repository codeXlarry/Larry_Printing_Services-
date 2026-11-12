<?php
declare(strict_types=1);

require_once __DIR__ . '/config/session.php';


if (!empty($_SESSION['logged_in'])) {

    header('Location: dashboard.php');
    exit;

}


$error =
    $_SESSION['login_error'] ?? '';

$success =
    $_SESSION['login_success'] ?? '';

$oldUsername =
    $_SESSION['old_username'] ?? '';


unset(
    $_SESSION['login_error'],
    $_SESSION['login_success'],
    $_SESSION['old_username']
);


$loggedOut =
    isset($_GET['logged_out']);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Login | Larry Clothing & Printing
    </title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>

<body class="login-page">

<div class="login-wrapper">


    <section class="brand-panel">

        <div class="brand-content">

            <div class="brand-logo">
                L
            </div>

            <p class="brand-eyebrow">
                Clothing • Printing • Customisation
            </p>

            <h1>
                Larry Clothing
                <span>& Printing</span>
            </h1>

            <p class="brand-description">
                Sign in to manage your account,
                orders, products, and printing services.
            </p>


            <div class="brand-features">

                <div class="feature-item">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <div>
                        <strong>
                            Secure Access
                        </strong>

                        <small>
                            Secure password authentication.
                        </small>
                    </div>

                </div>


                <div class="feature-item">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <div>
                        <strong>
                            Order Management
                        </strong>

                        <small>
                            Track and manage transactions.
                        </small>
                    </div>

                </div>


                <div class="feature-item">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <div>
                        <strong>
                            Printing Services
                        </strong>

                        <small>
                            Manage customised printing requests.
                        </small>
                    </div>

                </div>

            </div>

        </div>


        <div class="brand-footer">

            © <?= date('Y'); ?>
            Larry Clothing & Printing

        </div>

    </section>


    <main class="login-panel">

        <div class="login-card">


            <div class="mobile-brand">

                <div class="mobile-logo">
                    L
                </div>

                <span>
                    Larry Clothing & Printing
                </span>

            </div>


            <div class="login-heading">

                <span class="welcome-label">
                    Welcome Back
                </span>

                <h2>
                    Sign in to your account
                </h2>

                <p>
                    Enter your username and password
                    to continue.
                </p>

            </div>


            <?php if ($error !== ''): ?>

                <div
                    class="alert alert-error"
                    role="alert"
                >

                    <span class="alert-icon">
                        !
                    </span>

                    <span>

                        <?= htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </span>

                </div>

            <?php endif; ?>


            <?php if ($success !== ''): ?>

                <div
                    class="alert alert-success"
                    role="alert"
                >

                    <span class="alert-icon">
                        ✓
                    </span>

                    <span>

                        <?= htmlspecialchars(
                            $success,
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </span>

                </div>

            <?php endif; ?>


            <?php if ($loggedOut): ?>

                <div
                    class="alert alert-success"
                >

                    <span class="alert-icon">
                        ✓
                    </span>

                    <span>
                        You have been signed out successfully.
                    </span>

                </div>

            <?php endif; ?>


            <form
                action="authenticate.php"
                method="POST"
            >


                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(
                        $_SESSION['csrf_token'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>"
                >


                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            @
                        </span>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="<?= htmlspecialchars(
                                $oldUsername,
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            placeholder="Enter your username"
                            maxlength="50"
                            autocomplete="username"
                            required
                            autofocus
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            •
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword()"
                        >
                            Show
                        </button>

                    </div>

                </div>


                <button
                    type="submit"
                    class="login-button"
                >
                    Sign In
                </button>

            </form>


            <div class="account-divider">

                <span>
                    Don't have an account?
                </span>

            </div>


            <a
                href="register.php"
                class="create-account-button"
            >
                Create an Account
            </a>


            <div class="login-help">

                <p>
                    Having trouble signing in?
                    Please contact the system administrator.
                </p>

            </div>

        </div>

    </main>

</div>


<script>

function togglePassword() {

    const password =
        document.getElementById('password');

    const button =
        document.querySelector('.password-toggle');


    if (password.type === 'password') {

        password.type = 'text';
        button.textContent = 'Hide';

    } else {

        password.type = 'password';
        button.textContent = 'Show';

    }

}

</script>

</body>
</html>