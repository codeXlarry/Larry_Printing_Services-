<?php
declare(strict_types=1);

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/db_config.php';


/*
|--------------------------------------------------------------------------
| POST requests only
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: register.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| CSRF Protection
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

    $_SESSION['register_error'] =
        'Your session has expired. Please try again.';

    header('Location: register.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Receive Form Data
|--------------------------------------------------------------------------
*/

$fullName =
    trim((string) ($_POST['full_name'] ?? ''));

$username =
    trim((string) ($_POST['username'] ?? ''));

$email =
    trim((string) ($_POST['email'] ?? ''));

$contactNumber =
    trim((string) ($_POST['contact_number'] ?? ''));

$password =
    (string) ($_POST['password'] ?? '');

$confirmPassword =
    (string) ($_POST['confirm_password'] ?? '');


/*
|--------------------------------------------------------------------------
| Remember fields if validation fails
|--------------------------------------------------------------------------
*/

$_SESSION['register_full_name'] =
    $fullName;

$_SESSION['register_username'] =
    $username;

$_SESSION['register_email'] =
    $email;

$_SESSION['register_contact'] =
    $contactNumber;


/*
|--------------------------------------------------------------------------
| Required Fields
|--------------------------------------------------------------------------
*/

if (
    $fullName === ''
    ||
    $username === ''
    ||
    $password === ''
    ||
    $confirmPassword === ''
) {

    $_SESSION['register_error'] =
        'Please complete all required fields.';

    header('Location: register.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Full Name Validation
|--------------------------------------------------------------------------
*/

if (mb_strlen($fullName) > 150) {

    $_SESSION['register_error'] =
        'Full name is too long.';

    header('Location: register.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Username Validation
|--------------------------------------------------------------------------
*/

if (
    strlen($username) < 4
    ||
    strlen($username) > 50
) {

    $_SESSION['register_error'] =
        'Username must contain between 4 and 50 characters.';

    header('Location: register.php');
    exit;

}


if (
    !preg_match(
        '/^[A-Za-z0-9._]+$/',
        $username
    )
) {

    $_SESSION['register_error'] =
        'Username may only contain letters, numbers, periods, and underscores.';

    header('Location: register.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Email Validation
|--------------------------------------------------------------------------
*/

if (
    $email !== ''
    &&
    !filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )
) {

    $_SESSION['register_error'] =
        'Please enter a valid email address.';

    header('Location: register.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Password Validation
|--------------------------------------------------------------------------
*/

if (strlen($password) < 8) {

    $_SESSION['register_error'] =
        'Password must contain at least 8 characters.';

    header('Location: register.php');
    exit;

}


if ($password !== $confirmPassword) {

    $_SESSION['register_error'] =
        'Passwords do not match.';

    header('Location: register.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Check Existing Username
|--------------------------------------------------------------------------
*/

try {

    $checkUser =
        $pdo->prepare(
            "
            SELECT user_id
            FROM users
            WHERE username = :username
            LIMIT 1
            "
        );

    $checkUser->execute([
        ':username' => $username
    ]);


    if ($checkUser->fetch()) {

        $_SESSION['register_error'] =
            'That username is already taken.';

        header('Location: register.php');
        exit;

    }

} catch (PDOException $e) {

    error_log(
        'Username check error: '
        . $e->getMessage()
    );

    $_SESSION['register_error'] =
        'Unable to verify the username. Please try again.';

    header('Location: register.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Password Hash
|--------------------------------------------------------------------------
*/

$passwordHash =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );


if ($passwordHash === false) {

    $_SESSION['register_error'] =
        'Unable to secure your password.';

    header('Location: register.php');
    exit;

}


/*
|--------------------------------------------------------------------------
| Default Account Values
|--------------------------------------------------------------------------
|
| IMPORTANT:
| Users cannot select Owner/Admin/Cashier/Staff themselves.
|--------------------------------------------------------------------------
*/

$role = 'Client';
$status = 'Active';


/*
|--------------------------------------------------------------------------
| Convert Empty Optional Fields To NULL
|--------------------------------------------------------------------------
*/

$emailValue =
    $email !== ''
        ? $email
        : null;

$contactValue =
    $contactNumber !== ''
        ? $contactNumber
        : null;


/*
|--------------------------------------------------------------------------
| Create User
|--------------------------------------------------------------------------
*/

try {

    $sql = "
        INSERT INTO users
        (
            full_name,
            username,
            password_hash,
            role,
            contact_number,
            email,
            status
        )
        VALUES
        (
            :full_name,
            :username,
            :password_hash,
            :role,
            :contact_number,
            :email,
            :status
        )
    ";


    $stmt =
        $pdo->prepare($sql);


    $stmt->execute([

        ':full_name' =>
            $fullName,

        ':username' =>
            $username,

        ':password_hash' =>
            $passwordHash,

        ':role' =>
            $role,

        ':contact_number' =>
            $contactValue,

        ':email' =>
            $emailValue,

        ':status' =>
            $status

    ]);


    /*
    |--------------------------------------------------------------------------
    | Registration Successful
    |--------------------------------------------------------------------------
    */

    unset(
        $_SESSION['register_full_name'],
        $_SESSION['register_username'],
        $_SESSION['register_email'],
        $_SESSION['register_contact']
    );


    $_SESSION['login_success'] =
        'Your account was created successfully. You can now sign in.';


    $_SESSION['old_username'] =
        $username;


    header('Location: login.php');
    exit;


} catch (PDOException $e) {

    error_log(
        'Registration error: '
        . $e->getMessage()
    );


    /*
    |--------------------------------------------------------------------------
    | Duplicate Username
    |--------------------------------------------------------------------------
    */

    if ($e->getCode() === '23000') {

        $_SESSION['register_error'] =
            'That username is already registered.';

    } else {

        $_SESSION['register_error'] =
            'Account creation failed. Please try again.';

    }


    header('Location: register.php');
    exit;

}