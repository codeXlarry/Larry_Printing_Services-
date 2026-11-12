<?php
declare(strict_types=1);

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/db_config.php';


/*
|--------------------------------------------------------------------------
| POST Only
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: login.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| CSRF Check
|--------------------------------------------------------------------------
*/

$csrfToken =
    (string) ($_POST['csrf_token'] ?? '');

if (
    empty($csrfToken)
    ||
    empty($_SESSION['csrf_token'])
    ||
    !hash_equals(
        $_SESSION['csrf_token'],
        $csrfToken
    )
) {

    $_SESSION['login_error'] =
        'Your session has expired. Please try again.';

    header('Location: login.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Login Details
|--------------------------------------------------------------------------
*/

$username =
    trim((string) ($_POST['username'] ?? ''));

$password =
    (string) ($_POST['password'] ?? '');


$_SESSION['old_username'] =
    $username;


if (
    $username === ''
    ||
    $password === ''
) {

    $_SESSION['login_error'] =
        'Please enter your username and password.';

    header('Location: login.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Find User
|--------------------------------------------------------------------------
*/

try {

    $stmt =
        $pdo->prepare(
            "
            SELECT
                user_id,
                full_name,
                username,
                password_hash,
                role,
                contact_number,
                email,
                status
            FROM users
            WHERE username = :username
            LIMIT 1
            "
        );


    $stmt->execute([
        ':username' => $username
    ]);


    $user =
        $stmt->fetch();


} catch (PDOException $e) {

    error_log(
        'Login query error: '
        . $e->getMessage()
    );


    $_SESSION['login_error'] =
        'Unable to sign in right now. Please try again.';


    header('Location: login.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Username / Password Verification
|--------------------------------------------------------------------------
*/

if (
    !$user
    ||
    !password_verify(
        $password,
        $user['password_hash']
    )
) {

    $_SESSION['login_error'] =
        'Invalid username or password.';

    header('Location: login.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Account Status
|--------------------------------------------------------------------------
*/

if (
    strcasecmp(
        trim($user['status']),
        'Active'
    ) !== 0
) {

    $_SESSION['login_error'] =
        'Your account is inactive. Please contact the administrator.';

    header('Location: login.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Role Validation
|--------------------------------------------------------------------------
*/

$allowedRoles = [
    'Owner',
    'Admin',
    'Cashier',
    'Staff',
    'Client'
];


$role =
    trim((string) $user['role']);


if (
    !in_array(
        $role,
        $allowedRoles,
        true
    )
) {

    $_SESSION['login_error'] =
        'Your account does not have a valid system role.';

    header('Location: login.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Successful Authentication
|--------------------------------------------------------------------------
*/

session_regenerate_id(true);


$_SESSION['logged_in'] =
    true;

$_SESSION['user_id'] =
    (int) $user['user_id'];

$_SESSION['full_name'] =
    $user['full_name'];

$_SESSION['username'] =
    $user['username'];

$_SESSION['role'] =
    $user['role'];

$_SESSION['email'] =
    $user['email'];

$_SESSION['contact_number'] =
    $user['contact_number'];

$_SESSION['login_time'] =
    time();


unset(
    $_SESSION['login_error'],
    $_SESSION['old_username']
);


/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: dashboard.php');
exit;