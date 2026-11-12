<?php
declare(strict_types=1);

require_once __DIR__ . '/config/session.php';


/*
|--------------------------------------------------------------------------
| Authentication Guard
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['logged_in'])) {

    $_SESSION['login_error'] =
        'Please sign in first.';

    header('Location: login.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Current User
|--------------------------------------------------------------------------
*/

$fullName = htmlspecialchars(
    (string) ($_SESSION['full_name'] ?? 'User'),
    ENT_QUOTES,
    'UTF-8'
);


$username = htmlspecialchars(
    (string) ($_SESSION['username'] ?? ''),
    ENT_QUOTES,
    'UTF-8'
);


$role = htmlspecialchars(
    (string) ($_SESSION['role'] ?? ''),
    ENT_QUOTES,
    'UTF-8'
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
        Dashboard | Larry Clothing & Printing
    </title>


    <!-- Main Website Design -->

    <link
        rel="stylesheet"
        href="assets/css/style.css?v=1.0"
    >

</head>


<body class="dashboard-page">


<!-- =========================================================
     GLOBAL REUSABLE HEADER
========================================================= -->

<?php
require_once __DIR__ . '/header.php';
?>


<!-- =========================================================
     DASHBOARD
========================================================= -->

<main class="dashboard-content">

    <!-- WELCOME -->

    <section class="welcome-card">


        <span class="dashboard-label">
            Dashboard
        </span>


        <h1>
            Welcome, <?= $fullName; ?>!
        </h1>


        <p>
            You are successfully logged in to
            Larry Clothing & Printing.
        </p>


        <!-- ACCOUNT INFORMATION -->

        <div class="account-summary">


            <div class="summary-item">

                <span>
                    Username
                </span>

                <strong>
                    <?= $username; ?>
                </strong>

            </div>


            <div class="summary-item">

                <span>
                    Account Role
                </span>

                <strong>
                    <?= $role; ?>
                </strong>

            </div>


            <div class="summary-item">

                <span>
                    Account Status
                </span>

                <strong class="active-text">
                    Active
                </strong>

            </div>


        </div>

    </section>


    <!-- =====================================================
         QUICK START AREA
    ====================================================== -->

    <section class="dashboard-main-card">


        <div class="dashboard-main-icon">
            LC
        </div>


        <h2>
            Larry Clothing & Printing
        </h2>


        <p>
            Your account dashboard is ready.
            You can access products, printing services,
            orders, and other features as they become
            available.
        </p>


        <div class="dashboard-actions">


            <a
                href="products.php"
                class="dashboard-action-primary"
            >
                View Products
            </a>


            <a
                href="#"
                class="dashboard-action-secondary"
            >
                Printing Services
            </a>


        </div>


    </section>


</main>

<?php require_once __DIR__ . '/footer.php'; ?>


</body>

</html>