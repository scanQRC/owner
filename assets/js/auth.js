/* =====================================================
   SCANME Authentication Engine v2.2
   CSRF Protected Login
   Auto Token Fetch
===================================================== */

document.addEventListener("DOMContentLoaded", async () => {

    const loginBtn = document.getElementById("loginBtn");
    const loginModal = document.getElementById("loginModal");
    const closeBtn = document.getElementById("closeLoginModal");

    const loginForm = document.getElementById("loginForm");
    const loginInput = document.getElementById("login");
    const passwordInput = document.getElementById("password");

    const loginMessage = document.getElementById("loginMessage");
    const submitBtn = document.getElementById("loginSubmit");


    if (!loginForm) return;


    let csrfToken = "";


    /*
    |--------------------------------------------------------------------------
    | Load CSRF Token
    |--------------------------------------------------------------------------
    */

    async function loadCSRFToken() {

        try {

            const response = await fetch(
                "api/auth/csrf-token.php",
                {
                    method: "GET",
                    credentials: "same-origin"
                }
            );


            const result = await response.json();


            if (result.success) {

                csrfToken = result.token;

            }


        } catch (error) {

            console.error(
                "CSRF Token Error:",
                error
            );

        }

    }


    await loadCSRFToken();



    function showMessage(message, success = false) {

        loginMessage.innerHTML =
            `<span style="color:${success ? "green" : "red"}">${message}</span>`;

    }



    function openModal() {

        loginModal.style.display = "flex";

        loginMessage.innerHTML = "";

        loginForm.reset();

        setTimeout(() => loginInput.focus(), 100);

    }



    function closeModal() {

        loginModal.style.display = "none";

        loginMessage.innerHTML = "";

        loginForm.reset();

    }



    if (loginBtn) {

        loginBtn.addEventListener(
            "click",
            openModal
        );

    }



    if (closeBtn) {

        closeBtn.addEventListener(
            "click",
            closeModal
        );

    }



    window.addEventListener(
        "click",
        (e) => {

            if (e.target === loginModal) {

                closeModal();

            }

        }
    );



    document.addEventListener(
        "keydown",
        (e) => {

            if (e.key === "Escape") {

                closeModal();

            }

        }
    );



    loginForm.addEventListener(
        "submit",
        async (e) => {


        e.preventDefault();


        loginMessage.innerHTML = "";


        const login = loginInput.value.trim();

        const password = passwordInput.value;



        if (!login || !password) {

            showMessage(
                "Please enter Mobile/Email and Password."
            );

            return;

        }



        if (!csrfToken) {

            await loadCSRFToken();

        }



        if (!csrfToken) {

            showMessage(
                "Security token missing. Refresh page and try again."
            );

            return;

        }



        submitBtn.disabled = true;

        submitBtn.textContent = "Please wait...";



        try {


            const response = await fetch(
                "api/auth/login.php",
                {

                method: "POST",

                headers: {

                    "Content-Type": "application/json",

                    "X-CSRF-TOKEN": csrfToken

                },


                credentials: "same-origin",


                body: JSON.stringify({

                    login,

                    password

                })

            });



            if (!response.ok) {

                throw new Error(
                    "Server error."
                );

            }



            const result = await response.json();



            if (result.success) {


                showMessage(
                    result.message,
                    true
                );


                setTimeout(
                    () => {

                    window.location.href =
                        result.redirect;

                    },
                    500
                );


            } else {


                showMessage(
                    result.message
                );


            }



        } catch (error) {


            console.error(
                "Login Error:",
                error
            );


            showMessage(
                "Unable to connect to server."
            );


        }



        submitBtn.disabled = false;

        submitBtn.textContent = "Login";


    });


});
