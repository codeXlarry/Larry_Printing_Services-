document.addEventListener("DOMContentLoaded", () => {

    const form =
        document.getElementById("registerForm");

    const username =
        document.getElementById("username");

    const password =
        document.getElementById("password");

    const confirmPassword =
        document.getElementById("confirm_password");


    const usernameError =
        document.getElementById("usernameError");

    const passwordError =
        document.getElementById("passwordError");

    const confirmPasswordError =
        document.getElementById("confirmPasswordError");


    const passwordToggle =
        document.getElementById("passwordToggle");

    const confirmPasswordToggle =
        document.getElementById(
            "confirmPasswordToggle"
        );

    const registerButton =
        document.getElementById(
            "registerButton"
        );


    /*
    |--------------------------------------------------------------------------
    | Password Toggle
    |--------------------------------------------------------------------------
    */

    passwordToggle.addEventListener(
        "click",
        () => {

            const isHidden =
                password.type === "password";

            password.type =
                isHidden
                    ? "text"
                    : "password";

            passwordToggle.textContent =
                isHidden
                    ? "Hide"
                    : "Show";
        }
    );


    confirmPasswordToggle.addEventListener(
        "click",
        () => {

            const isHidden =
                confirmPassword.type
                    === "password";

            confirmPassword.type =
                isHidden
                    ? "text"
                    : "password";

            confirmPasswordToggle.textContent =
                isHidden
                    ? "Hide"
                    : "Show";
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Form Validation
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        "submit",
        (event) => {

            let valid = true;


            usernameError.textContent = "";
            passwordError.textContent = "";
            confirmPasswordError.textContent = "";


            username.classList.remove(
                "input-error"
            );

            password.classList.remove(
                "input-error"
            );

            confirmPassword.classList.remove(
                "input-error"
            );


            const usernameValue =
                username.value.trim();


            /*
            |--------------------------------------------------------------------------
            | Username
            |--------------------------------------------------------------------------
            */

            if (usernameValue === "") {

                usernameError.textContent =
                    "Please enter a username.";

                username.classList.add(
                    "input-error"
                );

                valid = false;

            } else if (
                usernameValue.length < 4
            ) {

                usernameError.textContent =
                    "Username must contain at least 4 characters.";

                username.classList.add(
                    "input-error"
                );

                valid = false;

            } else if (
                !/^[A-Za-z0-9._]+$/.test(
                    usernameValue
                )
            ) {

                usernameError.textContent =
                    "Use only letters, numbers, periods, and underscores.";

                username.classList.add(
                    "input-error"
                );

                valid = false;

            }


            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            */

            if (password.value === "") {

                passwordError.textContent =
                    "Please create a password.";

                password.classList.add(
                    "input-error"
                );

                valid = false;

            } else if (
                password.value.length < 8
            ) {

                passwordError.textContent =
                    "Password must contain at least 8 characters.";

                password.classList.add(
                    "input-error"
                );

                valid = false;

            }


            /*
            |--------------------------------------------------------------------------
            | Confirm Password
            |--------------------------------------------------------------------------
            */

            if (
                confirmPassword.value === ""
            ) {

                confirmPasswordError.textContent =
                    "Please confirm your password.";

                confirmPassword.classList.add(
                    "input-error"
                );

                valid = false;

            } else if (
                password.value !==
                confirmPassword.value
            ) {

                confirmPasswordError.textContent =
                    "Passwords do not match.";

                confirmPassword.classList.add(
                    "input-error"
                );

                valid = false;

            }


            if (!valid) {

                event.preventDefault();
                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Loading State
            |--------------------------------------------------------------------------
            */

            registerButton.classList.add(
                "loading"
            );

            registerButton.disabled = true;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Remove Errors When Typing
    |--------------------------------------------------------------------------
    */

    username.addEventListener(
        "input",
        () => {

            usernameError.textContent = "";

            username.classList.remove(
                "input-error"
            );

        }
    );


    password.addEventListener(
        "input",
        () => {

            passwordError.textContent = "";

            password.classList.remove(
                "input-error"
            );

        }
    );


    confirmPassword.addEventListener(
        "input",
        () => {

            confirmPasswordError.textContent = "";

            confirmPassword.classList.remove(
                "input-error"
            );

        }
    );

});