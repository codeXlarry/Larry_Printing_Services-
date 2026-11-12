document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");

    const username = document.getElementById("username");
    const password = document.getElementById("password");

    const usernameError =
        document.getElementById("usernameError");

    const passwordError =
        document.getElementById("passwordError");

    const passwordToggle =
        document.getElementById("passwordToggle");

    const loginButton =
        document.getElementById("loginButton");


    /*
    |--------------------------------------------------------------------------
    | Show / Hide Password
    |--------------------------------------------------------------------------
    */

    passwordToggle.addEventListener("click", () => {
        const hidden = password.type === "password";

        password.type = hidden
            ? "text"
            : "password";

        passwordToggle.textContent = hidden
            ? "Hide"
            : "Show";

        passwordToggle.setAttribute(
            "aria-label",
            hidden
                ? "Hide password"
                : "Show password"
        );
    });


    /*
    |--------------------------------------------------------------------------
    | Client-side Validation
    |--------------------------------------------------------------------------
    */

    form.addEventListener("submit", (event) => {
        let valid = true;

        usernameError.textContent = "";
        passwordError.textContent = "";

        username.classList.remove("input-error");
        password.classList.remove("input-error");


        if (username.value.trim() === "") {

            usernameError.textContent =
                "Please enter your username.";

            username.classList.add("input-error");

            valid = false;
        }


        if (password.value === "") {

            passwordError.textContent =
                "Please enter your password.";

            password.classList.add("input-error");

            valid = false;
        }


        if (!valid) {
            event.preventDefault();
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Double Submission
        |--------------------------------------------------------------------------
        */

        loginButton.classList.add("loading");
        loginButton.disabled = true;
    });


    /*
    |--------------------------------------------------------------------------
    | Remove Error State As User Types
    |--------------------------------------------------------------------------
    */

    username.addEventListener("input", () => {
        usernameError.textContent = "";
        username.classList.remove("input-error");
    });


    password.addEventListener("input", () => {
        passwordError.textContent = "";
        password.classList.remove("input-error");
    });
});