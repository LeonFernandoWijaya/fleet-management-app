//============================================================//
//==================== GLOBAL FUNCTION ==================//
const baseUrl = document
    .querySelector('meta[name="base-url"]')
    .getAttribute("content");

function showFlowBytesModal(id) {
    let modal = FlowbiteInstances.getInstance("Modal", id);
    if (modal == null) {
        modal = new Modal(document.getElementById(id), {
            placement: "center",
            backdrop: "static",
            closable: false,
        });
    }
    modal.show();
}
function hideFlowBytesModal(id) {
    let modal = FlowbiteInstances.getInstance("Modal", id);
    if (modal == null) {
        modal = new Modal(document.getElementById(id), { placement: "center" });
    }
    modal.hide();
}
function buttonPagination(selector, numberOfPage, currentPage, link) {
    $(selector).empty();
    let currentPageButton = `<li>
    <button aria-current="page"
    onclick="${link}({{page}})"
        class="flex items-center justify-center mx-1 cursor-pointer rounded-lg px-3 h-8 text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700">{{page}}</button>

    </li>`;
    // Other Page
    let otherPage = `<li>
    <a onclick="${link}({{page}})"
        class="flex items-center justify-center me-1 px-3 h-8 leading-tight text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 cursor-pointer rounded-lg">{{page}}</a>
    </li>`;
    let button = `<li>
    <button type="button" onclick="${link}({{page}})"
        class="flex items-center justify-center cursor-pointer px-3 h-8 leading-tight text-gray-500 bg-white rounded-lg hover:bg-gray-100 hover:text-gray-700">{{button-text}}</button>
    </li>`;
    let dots = `<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>`;
    let previousButton = button.replace("{{button-text}}", "<");
    if (currentPage != 1) {
        previousButton = previousButton.replace("{{page}}", currentPage - 1);
    }
    let buildedPage = previousButton;
    const TOTAL_SIDE_BUTTON = 3;
    if (currentPage > TOTAL_SIDE_BUTTON + 2) {
        buildedPage += otherPage.replaceAll("{{page}}", 1);
        buildedPage += otherPage.replaceAll("{{page}}", 2);
        buildedPage += dots;
    }

    let startingMiddlePage = currentPage - TOTAL_SIDE_BUTTON;
    if (startingMiddlePage < 1 || startingMiddlePage == 2) {
        startingMiddlePage = 1;
    }

    let endMiddlePage = currentPage + TOTAL_SIDE_BUTTON;
    if (endMiddlePage > numberOfPage || endMiddlePage == numberOfPage - 1) {
        endMiddlePage = numberOfPage;
    }

    for (let i = startingMiddlePage; i <= endMiddlePage; i++) {
        if (i === currentPage) {
            buildedPage += currentPageButton.replaceAll("{{page}}", i);
        } else {
            buildedPage += otherPage.replaceAll("{{page}}", i);
        }
    }
    if (currentPage < numberOfPage - TOTAL_SIDE_BUTTON - 1) {
        buildedPage += dots;
        buildedPage += otherPage.replaceAll("{{page}}", numberOfPage - 1);
        buildedPage += otherPage.replaceAll("{{page}}", numberOfPage);
    }

    let nextButton = button.replaceAll("{{button-text}}", ">");
    if (currentPage != numberOfPage) {
        nextButton = nextButton.replace("{{page}}", currentPage + 1);
    }
    buildedPage += nextButton;
    $(selector).append(buildedPage);
}
function showAlertModal(status, message) {
    showFlowBytesModal("alert-modal");
    if (status == 1) {
        $("#success-svg").removeClass("hidden");
        $("#failed-svg").addClass("hidden");
        $("#alert-message").text(message);
        $("#alert-button").addClass("green-button").removeClass("red-button");
    } else {
        $("#alert-message").text(message);
        $("#success-svg").addClass("hidden");
        $("#failed-svg").removeClass("hidden");
        $("#alert-button").removeClass("green-button").addClass("red-button");
    }
}
function showDeleteModal(link, id) {
    showFlowBytesModal("confirmation-delete-modal");
    $("#confirmation-delete-submit-button").attr(
        "onclick",
        link +
            "(" +
            id +
            ");" +
            "hideFlowBytesModal('confirmation-delete-modal')"
    );
}
function showUpdateModal(link, id) {
    showFlowBytesModal("confirmation-update-modal");
    $("#confirmation-update-submit-button").attr(
        "onclick",
        link +
            "(" +
            id +
            ");" +
            "hideFlowBytesModal('confirmation-update-modal')"
    );
}

// ============================ VEHICLES =======================//
function loadVehicleData(page = 1) {
    $.ajax({
        url: "/vehicles-data?page=" + page,
        type: "GET",
        data: {
            search: $("#vehicleSearch").val(),
            filter: $('input[name="vehicleStatusFilter"]:checked').val(),
        },
        success: function (response) {
            $("#vehicle-tbody").empty();
            response.data.forEach((vehicle) => {
                $("#vehicle-tbody").append(`
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${vehicle.plate_number}
                    </th>
                    <td class="px-6 py-4">
                        ${vehicle.brand}
                    </td>
                    <td class="px-6 py-4">
                        ${vehicle.model}
                    </td>
                    <td class="px-6 py-4">
                        ${vehicle.vehicle_type.name}
                    </td>
                    <td class="px-6 py-4">
                        ${vehicle.vehicle_status.name}
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button class="blue-button" type="button" onclick='openEditVehicleModal("${vehicle.id}", "${vehicle.vehicle_type_id}", "${vehicle.plate_number}", "${vehicle.brand}", "${vehicle.model}", "${vehicle.capacity_ton}", "${vehicle.vehicle_status_id}", "${vehicle.reservice_level}")'>Edit</button>
                        <button class="red-button" type="button" onclick="showDeleteModal('deleteVehicle', '${vehicle.id}')">Delete</button>
                    </td>
                </tr>
                `);
            });
            buttonPagination(
                "#vehicle-pagination",
                response.last_page,
                response.current_page,
                "loadVehicleData"
            );
        },
    });
}

function openCreateVehicleModal() {
    $("#vehicle-form-data")[0].reset();
    $("#vehicle-form-title").text("Create New Vehicle");
    showFlowBytesModal("vehicle-form-modal");
    $("#vehicle-form-submit").attr("onclick", "storeVehicle()");
}

function storeVehicle() {
    let formData = new FormData(document.getElementById("vehicle-form-data"));
    $.ajax({
        url: "/vehicles",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("vehicle-form-modal");
            showAlertModal(1, response.message);
            loadVehicleData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function openEditVehicleModal(
    id,
    type,
    plate,
    brand,
    model,
    capacityTon,
    status,
    reserviceLevel
) {
    $("#vehicle-form-title").text("Edit Vehicle");
    $("#vehicle-type").val(type);
    let vehiclePlateSeparated = plate.split("-");
    $("#vehicle-plate-1").val(vehiclePlateSeparated[0]);
    $("#vehicle-plate-2").val(vehiclePlateSeparated[1]);
    $("#vehicle-plate-3").val(vehiclePlateSeparated[2]);
    $("#vehicle-brand").val(brand);
    $("#vehicle-model").val(model);
    $("#vehicle-capacity-ton").val(capacityTon);
    $("#vehicle-status").val(status);
    $("#vehicle-reservice-level").val(reserviceLevel);
    showFlowBytesModal("vehicle-form-modal");
    $("#vehicle-form-submit").attr("onclick", "updateVehicle(" + id + ")");
}

function updateVehicle(id) {
    let formData = new FormData(document.getElementById("vehicle-form-data"));
    $.ajax({
        url: "/vehicles/" + id,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("vehicle-form-modal");
            showAlertModal(1, response.message);
            loadVehicleData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function deleteVehicle(id) {
    $.ajax({
        url: "/vehicles/" + id,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            showAlertModal(1, response.message);
            loadVehicleData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

// ============================ END VEHICLE =======================//

// ============================ MAINTENANCE =======================//
function loadVehicleDataForMaintenance(page = 1) {
    $.ajax({
        url: "/vehicles-data-for-maintenance/",
        type: "GET",
        data: {
            search: $("#vehicleSearch").val(),
            filter: $('input[name="vehicleStatusFilter"]:checked').val(),
        },
        success: function (response) {
            $("#vehicle-tbody").empty();
            response.data.forEach((vehicle) => {
                let vehicleDue = ``;
                if (vehicle.maintenance_date != null) {
                    let maintenanceDate = new Date(vehicle.maintenance_date);
                    let currentDate = new Date();
                    let diffTime = Math.abs(maintenanceDate - currentDate);
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays > vehicle.reservice_level) {
                        vehicleDue = `<span class="text-red-500 font-bold">Due</span>`;
                    }
                }
                let vehiclestatus = vehicle.vehicle_status.name;
                let actionButton =
                    "<button class='dark-button opacity-50 cursor-not-allowed' disabled>In use</button>";
                if (vehiclestatus == "Available") {
                    actionButton = `<button class="green-button" onclick="showUpdateModal('changeVehicleStatus', '${vehicle.id}')">Set on service</button>`;
                } else if (vehiclestatus == "On Service") {
                    actionButton = `<button class="blue-button" onclick="showUpdateModal('changeVehicleStatus', '${vehicle.id}')">Set available</button>`;
                }

                $("#vehicle-tbody").append(`
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                     <td class="px-2 py-4">
                        ${vehicleDue}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${vehicle.plate_number}
                    </th>
                    <td class="px-6 py-4">
                        ${
                            vehicle.maintenance_date
                                ? vehicle.maintenance_date
                                : "New Added"
                        }
                    </td>
                     <td class="px-6 py-4">
                        ${vehicle.reservice_level}
                    </td>
                    <td class="px-6 py-4">
                        ${vehicle.vehicle_type.name}
                    </td>
                    <td class="px-6 py-4">
                        ${vehicle.vehicle_status.name}
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        ${actionButton}
                        <button class="alternative-button" type="button" onclick="openMaintenanceHistoryModal('${
                            vehicle.id
                        }')">History</button>
                    </td>
                </tr>
                `);
            });
            buttonPagination(
                "#vehicle-pagination",
                response.last_page,
                response.current_page,
                "loadVehicleData"
            );
        },
    });
}

function changeVehicleStatus(id) {
    $.ajax({
        url: "/change-vehicle-status/" + id,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            showAlertModal(1, response.message);
            loadVehicleDataForMaintenance();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function openMaintenanceHistoryModal(vehicleId) {
    showFlowBytesModal("history-modal");
    $("#history-id").val(vehicleId);
    loadMaintenanceData();
}

function loadMaintenanceData(page = 1) {
    $.ajax({
        url: "/maintenances-data?page=" + page,
        type: "GET",
        data: {
            vehicle_id: $("#history-id").val(),
        },
        success: function (response) {
            $("#history-tbody").empty();
            response.data.forEach((maintenance) => {
                $("#history-tbody").append(`
                <div class="flex items-center justify-between gap-4 p-2 rounded-xl border border-gray-300 dark:border-gray-700">
                    <div class="flex flex-col gap-2 text-xs text-gray-900 dark:text-white">
                        <div>
                            Date: ${maintenance.date}
                        </div>
                        <div>
                            Details : ${maintenance.details}
                        </div>
                        <div>
                            Cost : ${maintenance.cost}
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" onclick="openEditMaintenanceModal('${maintenance.id}', '${maintenance.date}', '${maintenance.details}', '${maintenance.cost}')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="showDeleteModal('deleteMaintenance', '${maintenance.id}')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                `);
            });
            buttonPagination(
                "#history-pagination",
                response.last_page,
                response.current_page,
                "loadMaintenanceData"
            );
        },
    });
}

function openCreateMaintenanceModal() {
    $("#maintenance-form-data")[0].reset();
    $("#maintenance-form-title").text("Create New Maintenance");
    showFlowBytesModal("maintenance-form-modal");
    $("#maintenance-form-submit").attr("onclick", "storeMaintenance()");
}

function storeMaintenance() {
    let formData = new FormData(
        document.getElementById("maintenance-form-data")
    );
    formData.append("vehicle_id", $("#history-id").val());
    $.ajax({
        url: "/maintenances",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("maintenance-form-modal");
            showAlertModal(1, response.message);
            loadMaintenanceData();
            loadVehicleDataForMaintenance();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function openEditMaintenanceModal(id, date, details, cost) {
    $("#maintenance-form-title").text("Edit Maintenance");
    $("#maintenance-date").val(date);
    $("#maintenance-details").val(details);
    $("#maintenance-cost").val(cost);
    showFlowBytesModal("maintenance-form-modal");
    $("#maintenance-form-submit").attr(
        "onclick",
        "updateMaintenance(" + id + ")"
    );
}

function updateMaintenance(id) {
    let formData = new FormData(
        document.getElementById("maintenance-form-data")
    );
    $.ajax({
        url: "/maintenances/" + id,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("maintenance-form-modal");
            showAlertModal(1, response.message);
            loadMaintenanceData();
            loadVehicleDataForMaintenance();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function deleteMaintenance(id) {
    $.ajax({
        url: "/maintenances/" + id,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            showAlertModal(1, response.message);
            loadMaintenanceData();
            loadVehicleDataForMaintenance();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

// ============================ END MAINTENANCE =======================//

// ============================ SPARE PART =======================//
function openCreateSparepartModal() {
    $("#sparepart-form-data")[0].reset();
    $("#sparepart-form-title").text("Create New Sparepart");
    showFlowBytesModal("sparepart-form-modal");
    loadSupplierData();
    $("#sparepart-form-submit").attr("onclick", "storeSparepart()");
}

function loadSupplierData(value = null) {
    $.ajax({
        url: "/suppliers-for-dropdown",
        type: "GET",
        success: async function (response) {
            $("#sparepart-supplier").empty();
            response.forEach((supplier) => {
                $("#sparepart-supplier").append(`
                <option value="${supplier.id}">${supplier.name} - ${supplier.contact_number}</option>
                `);
            });
            $("#sparepart-supplier").select2({
                placeholder: "Select Supplier",
                theme: "classic",
                width: "100%",
            });
            var firstValue =
                value ?? $("#sparepart-supplier option:first").val();
            $("#sparepart-supplier").val(firstValue).trigger("change");
        },
    });
}

function storeSparepart() {
    let formData = new FormData(document.getElementById("sparepart-form-data"));
    $.ajax({
        url: "/spareparts",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("sparepart-form-modal");
            showAlertModal(1, response.message);
            loadSparepartData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function loadSparepartData(page = 1) {
    $.ajax({
        url: "/spareparts-data?page=" + page,
        type: "GET",
        data: {
            search: $("#sparepartSearch").val(),
        },
        success: function (response) {
            $("#sparepart-tbody").empty();
            response.data.forEach((sparepart) => {
                $("#sparepart-tbody").append(`
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td class="px-2 py-4">
                        ${
                            sparepart.stock <= sparepart.reorder_level
                                ? `<span class="text-red-500 font-bold">Low</span>`
                                : ""
                        }
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${sparepart.name}
                    </th>
                    <td class="px-6 py-4">
                        ${sparepart.stock}
                    </td>
                    <td class="px-6 py-4">
                        ${sparepart.reorder_level}
                    </td>
                       <td class="px-6 py-4">
                        ${sparepart.supplier.name} - ${
                    sparepart.supplier.contact_number
                }
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button class="blue-button" type="button" onclick='openEditSparepartModal("${
                            sparepart.id
                        }", "${sparepart.name}", "${sparepart.stock}", "${
                    sparepart.reorder_level
                }", "${sparepart.supplier_id}")'>Edit</button>
                        <button class="red-button" type="button" onclick="showDeleteModal('deleteSparepart', '${
                            sparepart.id
                        }')">Delete</button>
                    </td>
                </tr>
                `);
            });
            buttonPagination(
                "#sparepart-pagination",
                response.last_page,
                response.current_page,
                "loadSparepartData"
            );
        },
    });
}

function openEditSparepartModal(id, name, stock, reorderLevel, supplierId) {
    $("#sparepart-form-title").text("Edit Sparepart");
    $("#sparepart-name").val(name);
    $("#sparepart-stock").val(stock);
    $("#sparepart-reorder-level").val(reorderLevel);
    loadSupplierData(supplierId);
    showFlowBytesModal("sparepart-form-modal");
    $("#sparepart-form-submit").attr("onclick", "updateSparepart(" + id + ")");
}

function updateSparepart(id) {
    let formData = new FormData(document.getElementById("sparepart-form-data"));
    $.ajax({
        url: "/spareparts/" + id,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("sparepart-form-modal");
            showAlertModal(1, response.message);
            loadSparepartData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function deleteSparepart(id) {
    $.ajax({
        url: "/spareparts/" + id,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            showAlertModal(1, response.message);
            loadSparepartData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

// ============================ END SPARE PART =======================//

// ============================ SUPPLIER =======================//
function loadSuppliersData(page = 1) {
    $.ajax({
        url: "/suppliers-data?page=" + page,
        type: "GET",
        data: {
            search: $("#supplierSearch").val(),
        },
        success: function (response) {
            $("#supplier-tbody").empty();
            response.data.forEach((supplier) => {
                $("#supplier-tbody").append(`
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${supplier.name}
                    </th>
                    <td class="px-6 py-4">
                        ${supplier.contact_number}
                    </td>
                    <td class="px-6 py-4">
                        ${supplier.address}
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button class="blue-button" type="button" onclick='openEditSupplierModal("${supplier.id}", "${supplier.name}", "${supplier.contact_number}", "${supplier.address}")'>Edit</button>
                        <button class="red-button" type="button" onclick="showDeleteModal('deleteSupplier', '${supplier.id}')">Delete</button>
                    </td>
                </tr>
                `);
            });
            buttonPagination(
                "#supplier-pagination",
                response.last_page,
                response.current_page,
                "loadSuppliersData"
            );
        },
    });
}

function openCreateSupplierModal(type) {
    $("#supplier-form-data")[0].reset();
    $("#supplier-form-title").text("Create New Supplier");
    showFlowBytesModal("supplier-form-modal");
    $("#supplier-form-submit").attr("onclick", `storeSupplier('${type}')`);
}

function storeSupplier(type) {
    let formData = new FormData(document.getElementById("supplier-form-data"));
    $.ajax({
        url: "/suppliers",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("supplier-form-modal");
            showAlertModal(1, response.message);
            if (type == "sparepart") {
                loadSupplierData();
            } else if (type == "supplier") {
                loadSuppliersData();
            }
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function openEditSupplierModal(id, name, contactNumber, address) {
    $("#supplier-form-title").text("Edit Supplier");
    $("#supplier-name").val(name);
    $("#supplier-contact-number").val(contactNumber);
    $("#supplier-address").val(address);
    showFlowBytesModal("supplier-form-modal");
    $("#supplier-form-submit").attr("onclick", "updateSupplier(" + id + ")");
}

function updateSupplier(id) {
    let formData = new FormData(document.getElementById("supplier-form-data"));
    $.ajax({
        url: "/suppliers/" + id,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("supplier-form-modal");
            showAlertModal(1, response.message);
            loadSuppliersData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function deleteSupplier(id) {
    $.ajax({
        url: "/suppliers/" + id,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            showAlertModal(1, response.message);
            loadSuppliersData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}
// ============================ END SUPPLIER =======================//
