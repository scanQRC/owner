// SCANME App Logic

function searchVehicle() {

    const vehicleId = document.getElementById("vehicleId").value.trim();

    if (!vehicleId) {
        alert("Please enter Vehicle ID");
        return;
    }

    const data = localStorage.getItem(vehicleId);

    if (!data) {
        alert("No vehicle found");
        return;
    }

    const vehicle = JSON.parse(data);

    alert(
        "Vehicle Found!\n\n" +
        "Owner: " + vehicle.ownerName + "\n" +
        "Vehicle: " + vehicle.vehicleNumber + "\n" +
        "Type: " + vehicle.vehicleType
    );
}


// Registration Form

document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("vehicleForm");

    if (form) {

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const ownerName = document.getElementById("ownerName").value;
            const vehicleNumber = document.getElementById("vehicleNumber").value;
            const vehicleType = document.getElementById("vehicleType").value;
            const mobile = document.getElementById("mobile").value;
            const email = document.getElementById("email").value;

            // Generate Vehicle ID
            const vehicleId = "SCAN" + Math.floor(Math.random() * 1000000);

            const vehicleData = {
                ownerName,
                vehicleNumber,
                vehicleType,
                mobile,
                email,
                vehicleId
            };

            // Save to localStorage
            localStorage.setItem(vehicleId, JSON.stringify(vehicleData));

            document.getElementById("result").innerText =
                "Vehicle Registered! Your ID: " + vehicleId;

            form.reset();
        });

    }

});
