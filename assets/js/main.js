/* =====================================================
   SCANME Enterprise Core Engine v1.0
   ===================================================== */

document.addEventListener("DOMContentLoaded", () => {

    console.log("SCANME Platform Initialized");

    // Hide Preloader
    const preloader = document.getElementById("preloader");

    if (preloader) {
        window.addEventListener("load", () => {
            preloader.style.opacity = "0";
            setTimeout(() => {
                preloader.style.display = "none";
            }, 400);
        });
    }

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(link => {

        link.addEventListener("click", function (e) {

            const target = document.querySelector(this.getAttribute("href"));

            if (target) {

                e.preventDefault();

                target.scrollIntoView({
                    behavior: "smooth"
                });

            }

        });

    });

});
