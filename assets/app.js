// ============================
// SCANME Website JavaScript
// ============================


// Search Vehicle Function

function searchVehicle() {


    const vehicleId = document.getElementById("vehicleId").value.trim();



    if (vehicleId === "") {

        alert("Please enter Vehicle ID");

        return;

    }



    // Temporary response
    // Future: Connect with database/API


    alert(
        "Searching vehicle: " + vehicleId +
        "\n\nSCANME secure lookup system will display vehicle details here."
    );


}






// Register Button Handling

document.addEventListener("DOMContentLoaded", function(){


    const registerButtons = document.querySelectorAll(".primary-btn");


    registerButtons.forEach(function(button){


        button.addEventListener("click", function(){


            console.log("SCANME Registration Started");


        });


    });



});






// Future QR Scanner Placeholder


function scanVehicleQR(){


    alert(
        "QR Scanner feature will be activated soon."
    );


}
