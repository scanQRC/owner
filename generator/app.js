document.addEventListener("DOMContentLoaded", () => {

    const generateBtn = document.getElementById("generateBtn");
    const qrData = document.getElementById("qrData");
    const preview = document.getElementById("preview");

    generateBtn.addEventListener("click", () => {

        const value = qrData.value.trim();

        if (value === "") {
            preview.innerHTML = "<p>Please enter some data.</p>";
            return;
        }

        preview.innerHTML = `
            <div style="text-align:center;">
                <h3>QR Preview</h3>
                <p><strong>Data:</strong></p>
                <p>${value}</p>

                <div style="
                    margin:20px auto;
                    width:220px;
                    height:220px;
                    border:3px dashed #0066ff;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    border-radius:15px;
                    background:#fff;">
                    QR Coming Soon
                </div>

                <p style="color:#777;">
                    Next step: Real QR Generator
                </p>
            </div>
        `;

    });

});
