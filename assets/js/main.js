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

        const href = this.getAttribute("href");

        // Ignore empty anchors like "#"
        if (!href || href === "#") {
            return;
        }

        const target = document.querySelector(href);

        if (target) {

            e.preventDefault();

            target.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });

        }

    });

});
   
/* =====================================================
   SCANME MODULE POPUP
===================================================== */

const moduleData = {

    vehicle: {
        logo: "assets/images/modules/vehicle.png",
        title: "SCANME Vehicle",
        message: "Vehicle Registration is now available.",
        button: "Register Now"
    },

    school: {
        logo: "assets/images/modules/school.png",
        title: "SCANME School",
        message: "School Module is coming soon.",
        button: "Coming Soon"
    },

    hotel: {
        logo: "assets/images/modules/hotel.png",
        title: "SCANME Hotel & Restaurant",
        message: "Hotel & Restaurant Module is coming soon.",
        button: "Coming Soon"
    },

    business: {
        logo: "assets/images/modules/business.png",
        title: "SCANME Business",
        message: "Business Module is coming soon.",
        button: "Coming Soon"
    },

    mobile: {
        logo: "assets/images/modules/mobile.png",
        title: "SCANME Mobile",
        message: "Mobile Security Module is coming soon.",
        button: "Coming Soon"
    },

    personal: {
        logo: "assets/images/modules/personal.png",
        title: "SCANME Personal ID",
        message: "Personal ID Module is coming soon.",
        button: "Coming Soon"
    },

    child_safety: {
        logo: "assets/images/modules/child_safety.png",
        title: "SCANME Child Safety",
        message: "Child Safety Module is coming soon.",
        button: "Coming Soon"
    },

    pet: {
        logo: "assets/images/modules/pet.png",
        title: "SCANME Pet",
        message: "Pet Module is coming soon.",
        button: "Coming Soon"
    },

    lost_found: {
        logo: "assets/images/modules/lost_found.png",
        title: "SCANME Lost & Found",
        message: "Lost & Found Module is coming soon.",
        button: "Coming Soon"
    }

};

const popup = document.getElementById("modulePopup");

if (popup) {

    const popupLogo = document.getElementById("popupLogo");
    const popupTitle = document.getElementById("popupTitle");
    const popupMessage = document.getElementById("popupMessage");
    const popupButton = document.getElementById("popupButton");
    const popupClose = document.querySelector(".popup-close");

    document.querySelectorAll(".module-card").forEach(card => {

        card.addEventListener("click", () => {

            const key = card.dataset.module;
            const module = moduleData[key];

            if (!module) return;

            popupLogo.src = module.logo;
            popupTitle.textContent = module.title;
            popupMessage.textContent = module.message;
            popupButton.textContent = module.button;

            popup.style.display = "flex";

        });

    });

    popupClose.addEventListener("click", () => {
        popup.style.display = "none";
    });

    popup.addEventListener("click", (e) => {
        if (e.target === popup) {
            popup.style.display = "none";
        }
    });

}
});
