/* =====================================================
   SCANME Authentication Engine v1.0
===================================================== */

document.addEventListener("DOMContentLoaded", () => {

    const loginBtn = document.getElementById("loginBtn");
    const loginModal = document.getElementById("loginModal");
    const closeBtn = document.getElementById("closeLoginModal");

    const loginForm = document.getElementById("loginForm");
    const loginMessage = document.getElementById("loginMessage");
    const submitBtn = document.getElementById("loginSubmit");

    /* -----------------------------------
       Open Login Modal
    ----------------------------------- */

    if (loginBtn) {

        loginBtn.addEventListener("click", () => {

            loginModal.style.display = "flex";

        });

    }

    /* -----------------------------------
       Close Button
    ----------------------------------- */

    if (closeBtn) {

        closeBtn.addEventListener("click", () => {

            loginModal.style.display = "none";

        });

    }

    /* -----------------------------------
       Click Outside
    ----------------------------------- */

    window.addEventListener("click", (e) => {

        if (e.target === loginModal) {

            loginModal.style.display = "none";

        }

    });

    /* -----------------------------------
       ESC Close
    ----------------------------------- */

    document.addEventListener("keydown", (e) => {

        if (e.key === "Escape") {

            loginModal.style.display = "none";

        }

    });

    /* -----------------------------------
       Login
    ----------------------------------- */

    if (loginForm) {

        loginForm.addEventListener("submit", async (e) => {

            e.preventDefault();

            loginMessage.innerHTML = "";

            submitBtn.disabled = true;
            submitBtn.innerHTML = "Please wait...";

            try {

                const response = await fetch("api/auth/login.php", {

                    method: "POST",

                    headers: {
                        "Content-Type": "application/json"
                    },

                    body: JSON.stringify({

                        login: document.getElementById("login").value.trim(),

                        password: document.getElementById("password").value

                    })

                });

                const result = await response.json();

                if (result.success) {

                    loginMessage.innerHTML =
                        "<span style='color:green'>Login Successful...</span>";

                    setTimeout(() => {

                        window.location.href = result.redirect;

                    }, 500);

                                } else {

                    loginMessage.innerHTML =
                        "<span style='color:red'>" +
                        result.message +
                        "</span>";

                }

            } catch (error) {

                console.error("LOGIN ERROR:", error);

                alert(error.message);

                loginMessage.innerHTML =
                    "<span style='color:red'>" +
                    error.message +
                    "</span>";

            }

            submitBtn.disabled = false;
            submitBtn.innerHTML = "Login";

        });

    }

});
