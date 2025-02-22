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
                let vehicleDue =
                    vehicle.needs_service == 1
                        ? "<span class='text-red-500 font-bold'>Due</span>"
                        : "";
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
                            sparepart.low_stock == 1
                                ? "<span class='text-red-500 font-bold'>Low</span>"
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

// ============================ DOCUMENT =======================//
function loadVehicleForDocumentData(page = 1) {
    $.ajax({
        url: "/vehicles-data-for-document?page=" + page,
        type: "GET",
        data: {
            search: $("#vehicleSearch").val(),
        },
        success: function (response) {
            $("#vehicle-tbody").empty();
            response.data.forEach((vehicle) => {
                let expiryColor =
                    vehicle.total_expiry > 0
                        ? "text-red-500"
                        : "text-green-500";
                $("#vehicle-tbody").append(`
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td class="px-2 py-4">
                        ${
                            vehicle.total_expiry > 0
                                ? "<span class='text-red-500 font-bold'>Exp</span>"
                                : ""
                        }
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${vehicle.plate_number}
                    </th>
                    <td class="px-6 py-4">
                        ${vehicle.total_documents}
                    </td>
                    <td class="px-6 py-4 ${expiryColor}">
                        ${vehicle.total_expiry}
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button class="blue-button" type="button" onclick='openDocumentDataModal("${
                            vehicle.id
                        }")'>View</button>
                    </td>
                </tr>
                `);
            });
            buttonPagination(
                "#vehicle-pagination",
                response.last_page,
                response.current_page,
                "loadVehicleForDocumentData"
            );
        },
    });
}

function openDocumentDataModal(vehicleId) {
    $("#document-id").val(vehicleId);
    loadDocumentData();
    showFlowBytesModal("list-modal");
}

function loadDocumentData(page = 1) {
    $.ajax({
        url: "/documents-data?page=" + page,
        type: "GET",
        data: {
            vehicle_id: $("#document-id").val(),
        },
        success: function (response) {
            $("#document-tbody").empty();
            response.data.forEach((document) => {
                $("#document-tbody").append(`
                <div class="flex items-center justify-between gap-4 p-2 rounded-xl border border-gray-300 dark:border-gray-700">
                    <div class="flex flex-col gap-2 text-xs text-gray-900 dark:text-white">
                        <div>
                            Name : ${document.name}
                        </div>
                        <div>
                            Expired Date : <span class="${
                                document.is_expired == true
                                    ? "text-red-500"
                                    : ""
                            }">${document.expiry_date}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <a href="${baseUrl}/documents/download/${
                    document.path
                }" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-2.5 dark:focus:ring-yellow-900" onclick="">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </a>
                            <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" onclick="openEditDocumentModal('${
                                document.id
                            }', '${document.name}', '${document.expiry_date}')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="showDeleteModal('deleteDocument', '${
                                document.id
                            }')">
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
                "#document-pagination",
                response.last_page,
                response.current_page,
                "loadDocumentData"
            );
        },
    });
}

function openCreateDocumentModal() {
    $("#document-form-data")[0].reset();
    $("#document-form-title").text("Create New Document");
    showFlowBytesModal("document-form-modal");
    $("#document-form-submit").attr("onclick", "storeDocument()");
}

function storeDocument() {
    let formData = new FormData(document.getElementById("document-form-data"));
    formData.append("vehicle_id", $("#document-id").val());
    let file = $("#document-file")[0].files[0];
    formData.append("file", file);
    $.ajax({
        url: "/documents",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("document-form-modal");
            showAlertModal(1, response.message);
            loadDocumentData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function openEditDocumentModal(id, name, expiryDate) {
    $("#document-form-title").text("Edit Document");
    $("#document-name").val(name);
    $("#document-expiry-date").val(expiryDate);
    showFlowBytesModal("document-form-modal");
    $("#document-form-submit").attr("onclick", "updateDocument(" + id + ")");
}

function updateDocument(id) {
    let formData = new FormData(document.getElementById("document-form-data"));
    let file = $("#document-file")[0].files[0];
    formData.append("file", file);
    $.ajax({
        url: "/documents/" + id,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("document-form-modal");
            showAlertModal(1, response.message);
            loadDocumentData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function deleteDocument(id) {
    $.ajax({
        url: "/documents/" + id,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            showAlertModal(1, response.message);
            loadDocumentData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}
// ============================ END DOCUMENT =======================//

// ============================ USER =======================//
function loadUserData(page = 1) {
    $.ajax({
        url: "/users-data?page=" + page,
        type: "GET",
        data: {
            search: $("#userSearch").val(),
            status: $('input[name="userStatusFilter"]:checked').val(),
        },
        success: function (response) {
            $("#user-tbody").empty();
            response.data.forEach((user) => {
                $("#user-tbody").append(`
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${user.name}
                    </th>
                    <td class="px-6 py-4">
                        ${user.email}
                    </td>
                    <td class="px-6 py-4">
                        ${user.role.name}
                    </td>
                    <td class="px-6 py-4">
                        ${user.is_active == 1 ? "Active" : "Inactive"}
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button class="blue-button" type="button" onclick='openEditUserModal("${
                            user.id
                        }", "${user.name}", "${user.email}", "${
                    user.role_id
                }", "${user.is_active}")'>Edit</button>
                        <button class="red-button
                        " type="button" onclick="showDeleteModal('deleteUser', '${
                            user.id
                        }')">Delete</button>
                    </td>
                </tr>
                `);
            });
            buttonPagination(
                "#user-pagination",
                response.last_page,
                response.current_page,
                "loadUserData"
            );
        },
    });
}

function openCreateUserModal() {
    $("#user-form-data")[0].reset();
    $("#user-form-title").text("Create New User");
    showFlowBytesModal("user-form-modal");
    $("#user-form-submit").attr("onclick", "storeUser()");
}

function storeUser() {
    let formData = new FormData(document.getElementById("user-form-data"));
    $.ajax({
        url: "/users",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("user-form-modal");
            showAlertModal(1, response.message);
            loadUserData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function openEditUserModal(id, name, email, role, status) {
    $("#user-form-title").text("Edit User");
    $("#user-name").val(name);
    $("#user-email").val(email);
    $("#user-role").val(role);
    $("#user-status").val(status);
    showFlowBytesModal("user-form-modal");
    $("#user-form-submit").attr("onclick", "updateUser(" + id + ")");
}

function updateUser(id) {
    let formData = new FormData(document.getElementById("user-form-data"));
    $.ajax({
        url: "/users/" + id,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("user-form-modal");
            showAlertModal(1, response.message);
            loadUserData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function deleteUser(id) {
    $.ajax({
        url: "/users/" + id,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            showAlertModal(1, response.message);
            loadUserData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

// ================= END USER =======================//

// ============================ TRIP =======================//
function loadTripData(page = 1) {
    $.ajax({
        url: "/trips-data?page=" + page,
        type: "GET",
        data: {
            search: $("#vehicleSearch").val(),
            filter: $('input[name="tripStatusFilter"]:checked').val(),
        },
        success: function (response) {
            console.log(response);
            $("#trip-tbody").empty();
            response.data.forEach((trip) => {
                $("#trip-tbody").append(`
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${trip.vehicle.plate_number}
                    </th>
                    <td class="px-6 py-4">
                        ${trip.user.name}
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            ${trip.departure_time}
                        </div>
                        <div>
                            ${trip.arrival_time}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            ${trip.actual_departure_time ?? "Not Started"}
                        </div>
                        <div>
                            ${trip.actual_arrival_time ?? "Not Finished"}
                        </div>
                    </td>
                    <td class="px-6 py-4 ${
                        trip.trip_status.id == 3 ? "text-red-500" : ""
                    }">
                        ${trip.trip_status.name}
                    </td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button class="blue-button" onclick="openEditTripModal('${
                            trip.id
                        }', '${trip.user_id}', '${trip.vehicle_id}', '${
                    trip.departure_time
                }', '${trip.arrival_time}', '${trip.departure_location}', '${
                    trip.arrival_location
                }')" type="button">Edit</button>
                        <button class="red-button" onclick="showDeleteModal('deleteTrip', '${
                            trip.id
                        }')" type="button">Delete</button>
                    </td>
                </tr>
                `);
            });
            buttonPagination(
                "#trip-pagination",
                response.last_page,
                response.current_page,
                "loadTripData"
            );
        },
    });
}

function loadDriverDataForTrip(value = null) {
    $.ajax({
        url: "/drivers-for-dropdown",
        type: "GET",
        data: {
            value: value,
        },
        success: async function (response) {
            $("#trip-driver").empty();
            await response.forEach((driver) => {
                $("#trip-driver").append(`
                <option value="${driver.id}">${driver.id} - ${driver.name}</option>
                `);
            });
            $("#trip-driver").select2({
                placeholder: "Select Driver",
                theme: "classic",
                width: "100%",
            });
            var firstValue =
                (await value) ?? (await $("#trip-driver option:first").val());
            await $("#trip-driver").val(firstValue).trigger("change");
        },
    });
}

function loadVehicleDataForTrip(value = null) {
    $.ajax({
        url: "/vehicles-for-dropdown",
        type: "GET",
        data: {
            value: value,
        },
        success: async function (response) {
            $("#trip-vehicle").empty();
            await response.forEach((vehicle) => {
                $("#trip-vehicle").append(`
                <option value="${vehicle.id}">${vehicle.plate_number} - ${vehicle.vehicle_type.name}</option>
                `);
            });
            $("#trip-vehicle").select2({
                placeholder: "Select Vehicle",
                theme: "classic",
                width: "100%",
            });
            var firstValue =
                (await value) ?? (await $("#trip-vehicle option:first").val());
            await "#trip-vehicle".val(firstValue).trigger("change");
        },
    });
}

function openCreateTripModal() {
    loadDriverDataForTrip();
    loadVehicleDataForTrip();
    $("#trip-form-data")[0].reset();
    $("#trip-form-title").text("Create New Trip");
    showFlowBytesModal("trip-form-modal");
    $("#trip-form-submit").attr("onclick", "storeTrip()");
}

function storeTrip() {
    let formData = new FormData(document.getElementById("trip-form-data"));
    $.ajax({
        url: "/trips",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("trip-form-modal");
            showAlertModal(1, response.message);
            loadTripData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function openEditTripModal(
    id,
    driver,
    vehicle,
    departureTime,
    arrivalTime,
    departureLocation,
    arrivalLocation
) {
    loadDriverDataForTrip(driver);
    loadVehicleDataForTrip(vehicle);
    $("#trip-form-title").text("Edit Trip");
    $("#trip-departure-time").val(departureTime);
    $("#trip-arrival-time").val(arrivalTime);
    $("#trip-departure-location").val(departureLocation);
    $("#trip-arrival-location").val(arrivalLocation);
    showFlowBytesModal("trip-form-modal");
    $("#trip-form-submit").attr("onclick", "updateTrip(" + id + ")");
}

function updateTrip(id) {
    let formData = new FormData(document.getElementById("trip-form-data"));
    $.ajax({
        url: "/trips/" + id,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            hideFlowBytesModal("trip-form-modal");
            showAlertModal(1, response.message);
            loadTripData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

function deleteTrip(id) {
    $.ajax({
        url: "/trips/" + id,
        type: "DELETE",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            showAlertModal(1, response.message);
            loadTripData();
        },
        error: function (xhr, status, error) {
            const firstErrorMessage = xhr.responseJSON.errors;
            showAlertModal(0, firstErrorMessage);
        },
    });
}

// ============================ END TRIP =======================//
// ============================ TRACK =======================//
function mapMaker() {
    if (mymap != null) {
        mymap.remove();
    }
    mymap = L.map("mapid").setView([latitude, longitude], 20);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "© OpenStreetMap contributors",
    }).addTo(mymap);
    L.marker([latitude, longitude]).addTo(mymap);
    $.ajax({
        url: `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`,
        type: "GET",
        success: function (data) {
            $("#location").val(data.display_name);
        },
        error: function (error) {
            showAlertModal(0, "Failed to get location.");
        },
    });
}

function getLocation() {
    return new Promise((resolve, reject) => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    resolve();
                },
                () => {
                    showAlertModal(0, "Please allow location access.");
                    reject();
                }
            );
        } else {
            showAlertModal(0, "Geolocation is not supported by this browser.");
            reject();
        }
    });
}

async function startTracking() {
    await getLocation();
    if (latitude == null || longitude == null) {
        showAlertModal(0, "Please allow location access.");
        return;
    } else {
        console.log("Tracking started");
        mapMaker();
    }
}
