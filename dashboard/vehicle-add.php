<?php

declare(strict_types=1);

session_start();

require_once '../config/config.php';
require_once '../includes/functions.php';
require_once '../includes/session.php';

if (empty($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit;
}

$pageTitle = 'Add Vehicle';

include_once '../includes/header.php';
?>

<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-sm">

                <div class="card-header">
                    <h3>Add New Vehicle</h3>
                </div>

                <div class="card-body">

                    <form id="vehicleForm">

                        <div class="mb-3">
                            <label class="form-label">Vehicle Number</label>
                            <input
                                type="text"
                                name="vehicle_number"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Vehicle Type</label>

                            <select
                                name="vehicle_type"
                                class="form-select"
                                required>

                                <option value="">Select</option>
                                <option>Car</option>
                                <option>Bike</option>
                                <option>Scooter</option>
                                <option>Truck</option>
                                <option>Bus</option>
                                <option>Taxi</option>
                                <option>Other</option>

                            </select>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <input
                                type="text"
                                name="brand"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Model</label>
                            <input
                                type="text"
                                name="model"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input
                                type="text"
                                name="color"
                                class="form-control"
                                required>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary">
                            Save Vehicle
                        </button>

                        <a
                            href="vehicles.php"
                            class="btn btn-secondary">
                            Cancel
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include_once '../includes/footer.php'; ?>
