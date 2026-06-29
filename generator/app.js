document.addEventListener("DOMContentLoaded", () => {

    const generateBtn = document.getElementById("generateBtn");
    const downloadBtn = document.getElementById("downloadBtn");
    const qrData = document.getElementById("qrData");
    const preview = document.getElementById("preview");

    let qrCode;

    generateBtn.addEventListener("click", generateQR);

    function generateQR() {

        const data = qrData.value.trim();

        if (!data) {
            alert("Please enter data.");
            return;
        }

        preview.innerHTML = "";

        qrCode = new QRCodeStyling({
            width: 260,
            height: 260,
            data: data,
            image: "../logo.png",

            dotsOptions: {
                color: "#005bea",
                type: "rounded"
            },

            backgroundOptions: {
                color: "#ffffff"
            },

            cornersSquareOptions: {
                color: "#005bea",
                type: "extra-rounded"
            },

            cornersDotOptions: {
                color: "#005bea"
            },

            imageOptions: {
                crossOrigin: "anonymous",
                margin: 6,
                imageSize: 0.28
            }
        });

        qrCode.append(preview);

    }

    downloadBtn.addEventListener("click", () => {

        if (!qrCode) {
            alert("Generate QR first.");
            return;
        }

        qrCode.download({
            name: "SCANQRC",
            extension: "png"
        });

    });

});
