<?php
declare(strict_types=1);

require_once __DIR__ . '/config/session.php';

$isLoggedIn = !empty($_SESSION['logged_in']);

$fullName = trim(
    (string) ($_SESSION['full_name'] ?? '')
);

$role = trim(
    (string) ($_SESSION['role'] ?? '')
);

$initial = 'U';

if ($fullName !== '') {
    $initial = strtoupper(
        mb_substr($fullName, 0, 1)
    );
}
?>

<!-- =========================================================
     APPLY SAVED THEME
========================================================= -->

<script>
(function () {

    const savedTheme =
        localStorage.getItem('larryTheme');

    if (savedTheme === 'dark') {

        document.documentElement
            .classList
            .add('dark-theme');

    }

})();
</script>


<!-- =========================================================
     HEADER + THEME CSS
========================================================= -->

<link
    rel="stylesheet"
    href="assets/css/header.css?v=3.0"
>

<link
    rel="stylesheet"
    href="assets/css/theme.css?v=3.0"
>


<header class="site-header">

    <nav class="site-navbar">


        <!-- BRAND -->

        <a
            href="dashboard.php"
            class="site-brand"
        >

            <span class="site-logo">
                L
            </span>

            <span class="site-brand-text">
                Larry
            </span>

        </a>


        <!-- DESKTOP MENU -->

        <div class="desktop-nav">

            <a
                href="dashboard.php"
                class="nav-link"
            >
                Home
            </a>

            <a
                href="products.php"
                class="nav-link"
            >
                Products
            </a>

            <a
                href="#"
                class="nav-link"
            >
                Printing
            </a>

            <a
                href="#"
                class="nav-link"
            >
                Services
            </a>


            <?php if ($isLoggedIn): ?>

                <a
                    href="#"
                    class="nav-link"
                >
                    Orders
                </a>

            <?php endif; ?>


            <a
                href="#"
                class="nav-link"
            >
                About
            </a>

        </div>


        <!-- RIGHT SIDE -->

        <div class="nav-right">


            <?php if ($isLoggedIn): ?>


                <!-- USER ACCOUNT -->

                <div class="user-menu">

                    <button
                        type="button"
                        class="user-menu-button"
                        id="userMenuButton"
                        aria-label="Open account menu"
                        aria-expanded="false"
                    >

                        <span class="user-avatar">

                            <?= htmlspecialchars(
                                $initial,
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        </span>

                    </button>


                    <!-- DROPDOWN -->

                    <div
                        class="user-dropdown"
                        id="userDropdown"
                    >

                        <div class="user-dropdown-header">

                            <strong>

                                <?= htmlspecialchars(
                                    $fullName !== ''
                                        ? $fullName
                                        : 'User',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </strong>

                            <span>

                                <?= htmlspecialchars(
                                    $role,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </span>

                        </div>


                        <div class="dropdown-divider"></div>


                        <a
                            href="dashboard.php"
                            class="dropdown-item"
                        >
                            My Account
                        </a>


                        <form
                            action="logout.php"
                            method="POST"
                            class="dropdown-logout-form"
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

                            <button
                                type="submit"
                                class="dropdown-item dropdown-logout"
                            >
                                Sign Out
                            </button>

                        </form>

                    </div>

                </div>


            <?php else: ?>


                <a
                    href="login.php"
                    class="signin-link"
                >
                    Sign In
                </a>


            <?php endif; ?>


            <!-- =================================================
                 LIGHT / DARK MODE
            ================================================== -->

            <button
                type="button"
                class="header-theme-toggle"
                id="themeToggle"
                aria-label="Switch to dark mode"
                title="Switch theme"
            >

                <!-- MOON -->

                <svg
                    class="theme-svg moon-icon"
                    id="moonIcon"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        d="M21 12.79A9 9 0 1 1 11.21 3
                           7 7 0 0 0 21 12.79Z"
                    />
                </svg>


                <!-- SUN -->

                <svg
                    class="theme-svg sun-icon"
                    id="sunIcon"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >

                    <circle
                        cx="12"
                        cy="12"
                        r="4"
                    />

                    <line
                        x1="12"
                        y1="2"
                        x2="12"
                        y2="4"
                    />

                    <line
                        x1="12"
                        y1="20"
                        x2="12"
                        y2="22"
                    />

                    <line
                        x1="4.93"
                        y1="4.93"
                        x2="6.34"
                        y2="6.34"
                    />

                    <line
                        x1="17.66"
                        y1="17.66"
                        x2="19.07"
                        y2="19.07"
                    />

                    <line
                        x1="2"
                        y1="12"
                        x2="4"
                        y2="12"
                    />

                    <line
                        x1="20"
                        y1="12"
                        x2="22"
                        y2="12"
                    />

                    <line
                        x1="6.34"
                        y1="17.66"
                        x2="4.93"
                        y2="19.07"
                    />

                    <line
                        x1="19.07"
                        y1="4.93"
                        x2="17.66"
                        y2="6.34"
                    />

                </svg>

            </button>


            <!-- MOBILE MENU -->

            <button
                type="button"
                class="mobile-menu-button"
                id="mobileMenuButton"
                aria-label="Open navigation"
                aria-expanded="false"
            >

                <span></span>
                <span></span>

            </button>

        </div>

    </nav>


    <!-- =========================================================
         MOBILE NAVIGATION
    ========================================================== -->

    <div
        class="mobile-nav"
        id="mobileNav"
    >

        <div class="mobile-nav-inner">

            <a href="dashboard.php">
                Home
            </a>

            <a href="#">
                Products
            </a>

            <a href="#">
                Printing
            </a>

            <a href="#">
                Services
            </a>


            <?php if ($isLoggedIn): ?>

                <a href="#">
                    Orders
                </a>

            <?php endif; ?>


            <a href="#">
                About
            </a>


            <?php if ($isLoggedIn): ?>

                <div class="mobile-nav-divider"></div>


                <div class="mobile-user">

                    <span class="mobile-user-avatar">

                        <?= htmlspecialchars(
                            $initial,
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </span>


                    <div>

                        <strong>

                            <?= htmlspecialchars(
                                $fullName !== ''
                                    ? $fullName
                                    : 'User',
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        </strong>

                        <small>

                            <?= htmlspecialchars(
                                $role,
                                ENT_QUOTES,
                                'UTF-8'
                            ); ?>

                        </small>

                    </div>

                </div>


                <form
                    action="logout.php"
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

                    <button
                        type="submit"
                        class="mobile-signout"
                    >
                        Sign Out
                    </button>

                </form>


            <?php else: ?>

                <div class="mobile-nav-divider"></div>

                <a href="login.php">
                    Sign In
                </a>

                <a href="register.php">
                    Create Account
                </a>

            <?php endif; ?>

        </div>

    </div>

</header>


<!-- =========================================================
     GLOBAL HEADER SCRIPT
========================================================= -->

<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | Elements
        |--------------------------------------------------------------------------
        */

        const themeToggle =
            document.getElementById(
                'themeToggle'
            );

        const userMenuButton =
            document.getElementById(
                'userMenuButton'
            );

        const userDropdown =
            document.getElementById(
                'userDropdown'
            );

        const mobileButton =
            document.getElementById(
                'mobileMenuButton'
            );

        const mobileNav =
            document.getElementById(
                'mobileNav'
            );


        /*
        |--------------------------------------------------------------------------
        | Theme
        |--------------------------------------------------------------------------
        */

        function updateThemeButton() {

            if (!themeToggle) {
                return;
            }

            const darkMode =
                document.documentElement
                    .classList
                    .contains(
                        'dark-theme'
                    );


            themeToggle.setAttribute(
                'aria-label',
                darkMode
                    ? 'Switch to light mode'
                    : 'Switch to dark mode'
            );


            themeToggle.setAttribute(
                'title',
                darkMode
                    ? 'Light mode'
                    : 'Dark mode'
            );

        }


        updateThemeButton();


        if (themeToggle) {

            themeToggle.addEventListener(
                'click',
                function () {

                    document.documentElement
                        .classList
                        .toggle(
                            'dark-theme'
                        );


                    const darkMode =
                        document.documentElement
                            .classList
                            .contains(
                                'dark-theme'
                            );


                    localStorage.setItem(
                        'larryTheme',
                        darkMode
                            ? 'dark'
                            : 'light'
                    );


                    updateThemeButton();

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | User Dropdown
        |--------------------------------------------------------------------------
        */

        if (
            userMenuButton &&
            userDropdown
        ) {

            userMenuButton.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();


                    userDropdown.classList.toggle(
                        'active'
                    );


                    userMenuButton.setAttribute(
                        'aria-expanded',
                        userDropdown.classList.contains(
                            'active'
                        )
                            ? 'true'
                            : 'false'
                    );

                }
            );


            document.addEventListener(
                'click',
                function (event) {

                    if (
                        !userDropdown.contains(
                            event.target
                        )
                        &&
                        !userMenuButton.contains(
                            event.target
                        )
                    ) {

                        userDropdown.classList.remove(
                            'active'
                        );

                        userMenuButton.setAttribute(
                            'aria-expanded',
                            'false'
                        );

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Mobile Navigation
        |--------------------------------------------------------------------------
        */

        if (
            mobileButton &&
            mobileNav
        ) {

            mobileButton.addEventListener(
                'click',
                function () {

                    mobileButton.classList.toggle(
                        'active'
                    );

                    mobileNav.classList.toggle(
                        'active'
                    );


                    mobileButton.setAttribute(
                        'aria-expanded',
                        mobileNav.classList.contains(
                            'active'
                        )
                            ? 'true'
                            : 'false'
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Window Resize
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'resize',
            function () {

                if (
                    window.innerWidth > 820
                    &&
                    mobileButton
                    &&
                    mobileNav
                ) {

                    mobileButton.classList.remove(
                        'active'
                    );

                    mobileNav.classList.remove(
                        'active'
                    );

                    mobileButton.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                }

            }
        );

    }
);
</script>