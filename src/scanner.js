let scannedData = "";
let sendTimeout = null;

document.addEventListener("keydown", (e) => {
  if (e.key === "Enter") {
    const cleanedData = scannedData.replace(/Shift/g, "");
    showLoader();

    if (sendTimeout) clearTimeout(sendTimeout); // Clear previous timeout

    sendTimeout = setTimeout(() => {
      showLoader(); // Show loader before sending
      sendDataToServer(cleanedData);
      handleScannedData(cleanedData);
      scannedData = "";
    }, 3000);
  } else {
    scannedData += e.key;
  }
});

function handleScannedData(data) {
  console.log(data);
}

function sendDataToServer(data) {
  fetch("storeCode.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `scannedData=${encodeURIComponent(data)}`,
  })
    .then((response) => response.text())
    .then((result) => {
      console.log(result.trim());
      if (result.trim() === "Error") {
        showAlert("The QR Code is scanned already or server ERROR", "error");
      } else {
        showAlert(result.trim(), "success");
        location.reload();
      }
    })
    .catch((error) => {
      console.error("Error storing data:", error);
      showAlert(
        "An error occurred while storing data. Please try again.",
        "error"
      );
    })
    .finally(() => {
      hideLoader(); // Hide loader after request
    });
}

// Show Loader
function showLoader() {
  document.querySelector(".loading").style.display = "flex";
}

// Hide Loader
function hideLoader() {
  document.querySelector(".loading").style.display = "none";
}

function searchTable(tableId, inputId) {
  const searchTerm = document.getElementById(inputId).value.toLowerCase();

  const table = document.getElementById(tableId);
  const rows = table.getElementsByTagName("tr");

  for (let i = 1; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName("td");
    let matchFound = false;

    for (let j = 0; j < cells.length; j++) {
      const cellText = cells[j].textContent || cells[j].innerText;

      if (cellText.toLowerCase().includes(searchTerm)) {
        matchFound = true;
        break;
      }
    }

    if (matchFound) {
      rows[i].classList.remove("hidden");
    } else {
      rows[i].classList.add("hidden");
    }
  }
}

function setupTableFilter(filterId, tableId, columnClass) {
  const filterElement = document.getElementById(`${filterId}`);
  const tableRows = document.querySelectorAll(`#${tableId} tbody tr`);

  filterElement.addEventListener("change", function () {
    const filterValue = this.value.toLowerCase();

    console.log(filterElement);

    tableRows.forEach((row) => {
      const cellValue = row
        .querySelector(`.${columnClass}`)
        .textContent.trim()
        .toLowerCase();

      if (filterValue === "all" || cellValue === filterValue) {
        row.style.display = ""; // Show row
      } else {
        console.log(cellValue);
        row.style.display = "none"; // Hide row
      }
    });
  });
}

function setupClassFilter(filterId, tableId, columnClass) {
  const filterElement = document.getElementById(`${filterId}`);
  const tableRows = document.querySelectorAll(`#${tableId} tbody tr`);

  filterElement.addEventListener("change", function () {
    const filterValue = this.value.toLowerCase();

    console.log(filterElement);

    tableRows.forEach((row) => {
      const cellValue = row
        .querySelector(`.${columnClass}`)
        .textContent.trim()
        .toLowerCase();

      if (filterValue === "all" || cellValue === filterValue) {
        row.style.display = ""; // Show row
      } else {
        console.log(cellValue);
        row.style.display = "none"; // Hide row
      }
    });
  });
}

function setupDateFilter(dateInputId, tableId, columnClass) {
  const dateInputElement = document.getElementById(dateInputId);
  const tableRows = document.querySelectorAll(`#${tableId} tbody tr`);

  dateInputElement.addEventListener("change", function () {
    const selectedDate = this.value; // Use the input value directly (YYYY-MM-DD format)

    tableRows.forEach((row) => {
      // Parse the date from the table cell
      const cellDateText = row
        .querySelector(`.${columnClass}`)
        .textContent.trim();
      const cellDate = new Date(cellDateText);

      // Format the cell date to YYYY-MM-DD in Philippine Time
      const cellDateFormatted = cellDate.toLocaleDateString("en-CA", {
        timeZone: "Asia/Manila",
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
      });

      if (this.value === "" || cellDateFormatted === selectedDate) {
        row.style.display = ""; // Show row
      } else {
        row.style.display = "none"; // Hide row
      }
    });
  });
}

function setupDateRangeFilter(startInputId, endInputId, tableId, columnClass) {
  const startInputElement = document.getElementById(startInputId);
  const endInputElement = document.getElementById(endInputId);
  const tableRows = document.querySelectorAll(`#${tableId} tbody tr`);

  function filterRows() {
    const startDate = startInputElement.value; // YYYY-MM-DD
    const endDate = endInputElement.value; // YYYY-MM-DD

    tableRows.forEach((row) => {
      const cellDateText = row
        .querySelector(`.${columnClass}`)
        .textContent.trim();

      // Attempt to parse the cell date
      let cellDate;
      try {
        cellDate = new Date(cellDateText).toISOString().split("T")[0]; // Convert to YYYY-MM-DD
      } catch (e) {
        console.error("Invalid cell date:", cellDateText);
        row.style.display = "none"; // Hide invalid rows
        return;
      }

      // Debugging: log parsed dates
      console.log({ startDate, endDate, cellDate });

      if (
        (!startDate || cellDate >= startDate) &&
        (!endDate || cellDate <= endDate)
      ) {
        row.style.display = ""; // Show row
      } else {
        row.style.display = "none"; // Hide row
      }
    });
  }

  startInputElement.addEventListener("change", filterRows);
  endInputElement.addEventListener("change", filterRows);
}
