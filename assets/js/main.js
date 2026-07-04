/* =====================================================
   SCANME Enterprise Core Engine v2.0
===================================================== */

document.addEventListener("DOMContentLoaded", () => {

    console.log("SCANME Platform Initialized");

    /* -----------------------------
       PRELOADER
    ----------------------------- */

    const preloader = document.getElementById("preloader");

    window.addEventListener("load", () => {

        if (preloader) {

            preloader.style.opacity = "0";

            setTimeout(() => {

                preloader.style.display = "none";

            }, 300);

        }

    });

    /* -----------------------------
       MOBILE MENU
    ----------------------------- */

    const menuBtn = document.querySelector(".menu-toggle");
    const nav = document.querySelector(".nav-links");

    if (menuBtn && nav) {

        menuBtn.addEventListener("click", () => {

            nav.classList.toggle("active");
            document.body.classList.toggle("menu-open");

        });

        nav.querySelectorAll("a").forEach(link => {

            link.addEventListener("click", () => {

                nav.classList.remove("active");
                document.body.classList.remove("menu-open");

            });

        });

    }

    /* -----------------------------
       SMOOTH SCROLL
    ----------------------------- */

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {

        anchor.addEventListener("click", function(e){

            const target = document.querySelector(this.getAttribute("href"));

            if(target){

                e.preventDefault();

                target.scrollIntoView({

                    behavior:"smooth",
                    block:"start"

                });

            }

        });

    });

});
