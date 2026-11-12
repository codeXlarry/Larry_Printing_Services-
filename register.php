<?php
declare(strict_types=1);

require_once __DIR__ . '/config/session.php';

if (!empty($_SESSION['logged_in'])) {

    header('Location: dashboard.php');
    exit;

}


$error =
    $_SESSION['register_error'] ?? '';

$oldFullName =
    $_SESSION['register_full_name'] ?? '';

$oldUsername =
    $_SESSION['register_username'] ?? '';

$oldEmail =
    $_SESSION['register_email'] ?? '';

$oldContact =
    $_SESSION['register_contact'] ?? '';


unset(
    $_SESSION['register_error'],
    $_SESSION['register_full_name'],
    $_SESSION['register_username'],
    $_SESSION['register_email'],
    $_SESSION['register_contact']
);
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
        Create Account | Larry Clothing & Printing
    </title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>

<body class="login-page">

<div class="login-wrapper">


    <!-- LEFT SIDE -->

    <section class="brand-panel">

        <div class="brand-content">

            <div class="brand-logo">
                L
            </div>

            <p class="brand-eyebrow">
                Larry Clothing & Printing
            </p>

            <h1>
                Create your
                <span>Client Account</span>
            </h1>

            <p class="brand-description">
                Register to manage your account,
                printing requests, purchases,
                and future orders.
            </p>


            <div class="brand-features">

                <div class="feature-item">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <div>
                        <strong>
                            Secure Registration
                        </strong>

                        <small>
                            Your password is securely encrypted.
                        </small>
                    </div>

                </div>


                <div class="feature-item">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <div>
                        <strong>
                            Client Access
                        </strong>

                        <small>
                            Access your orders and transactions.
                        </small>
                    </div>

                </div>


                <div class="feature-item">

                    <div class="feature-icon">
                        ✓
                    </div>

                    <div>
                        <strong>
                            Easy Management
                        </strong>

                        <small>
                            Manage your requests in one place.
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


    <!-- REGISTRATION -->

    <main class="login-panel">

        <div class="login-card register-card">


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
                    Client Registration
                </span>

                <h2>
                    Create an account
                </h2>

                <p>
                    Fill in your information below.
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


            <form
                action="register_process.php"
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


                <!-- FULL NAME -->

                <div class="form-group">

                    <label for="full_name">
                        Full Name
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            U
                        </span>

                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            placeholder="Enter your full name"
                            value="<?= htmlspecialchars(
                                $oldFullName,
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            maxlength="150"
                            required
                        >

                    </div>

                </div>


                <!-- USERNAME -->

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
                            placeholder="Create a username"
                            value="<?= htmlspecialchars(
                                $oldUsername,
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            maxlength="50"
                            autocomplete="username"
                            required
                        >

                    </div>

                </div>


                <!-- EMAIL -->

                <div class="form-group">

                    <label for="email">
                        Email
                        <span class="optional-label">
                            (Optional)
                        </span>
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            @
                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            value="<?= htmlspecialchars(
                                $oldEmail,
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            maxlength="150"
                        >

                    </div>

                </div>


                <!-- CONTACT -->

                <div class="form-group">

                    <label for="contact_number">
                        Contact Number
                        <span class="optional-label">
                            (Optional)
                        </span>
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            #
                        </span>

                        <input
                            type="text"
                            id="contact_number"
                            name="contact_number"
                            placeholder="09XXXXXXXXX"
                            value="<?= htmlspecialchars(
                                $oldContact,
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>"
                            maxlength="20"
                        >

                    </div>

                </div>


                <!-- PASSWORD -->

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
                            placeholder="Create a password"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword(
                                'password',
                                this
                            )"
                        >
                            Show
                        </button>

                    </div>

                    <small class="password-requirement">
                        At least 8 characters.
                    </small>

                </div>


                <!-- CONFIRM PASSWORD -->

                <div class="form-group">

                    <label for="confirm_password">
                        Confirm Password
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            •
                        </span>

                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="Re-enter your password"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword(
                                'confirm_password',
                                this
                            )"
                        >
                            Show
                        </button>

                    </div>

                </div>


                <button
                    type="submit"
                    class="login-button"
                >
                    Create Account
                </button>

            </form>


            <div class="account-divider">

                <span>
                    Already registered?
                </span>

            </div>


            <a
                href="login.php"
                class="create-account-button"
            >
                Back to Sign In
            </a>

        </div>

    </main>

</div>


<script>

function togglePassword(inputId, button) {

    const input =
        document.getElementById(inputId);

    if (input.type === 'password') {

        input.type = 'text';
        button.textContent = 'Hide';

    } else {

        input.type = 'password';
        button.textContent = 'Show';

    }

}

</script>

</body>
</html>