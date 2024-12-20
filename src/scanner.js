let scannedData = '';

document.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        const cleanedData = scannedData.replace(/Shift/g, '');
        sendDataToServer(cleanedData);
        handleScannedData(cleanedData);
        scannedData = '';
    } else {
        scannedData += e.key;
    }
});

function handleScannedData(data) {
    console.log(data);
}

function sendDataToServer(data) {
    fetch('storeCode.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `scannedData=${encodeURIComponent(data)}`
    })
    .then(response => response.text())
    .then(result => {
        if (result.trim() === "True") {
            showAlert('Scanned successfully!', 'success');
        } else {
            showAlert('The QR Code is scanned already or server ERROR', 'error');
        }
    })
    .catch(error => {
        console.error('Error storing data:', error);
        showAlert('An error occurred while storing data. Please try again.', 'error');
    });
}

function searchTable(tableId, inputId) {
    const searchTerm = document.getElementById(inputId).value.toLowerCase();
  
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let matchFound = false;
        
        for (let j = 0; j < cells.length; j++) {
            const cellText = cells[j].textContent || cells[j].innerText;
            
            if (cellText.toLowerCase().includes(searchTerm)) {
                matchFound = true;
                break;
            }
        }
        
        if (matchFound) {
            rows[i].classList.remove('hidden');
        } else {
            rows[i].classList.add('hidden');
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
            const cellValue = row.querySelector(`.${columnClass}`).textContent.trim().toLowerCase();


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
            const cellDateText = row.querySelector(`.${columnClass}`).textContent.trim();
            const cellDate = new Date(cellDateText);

            // Format the cell date to YYYY-MM-DD in Philippine Time
            const cellDateFormatted = cellDate.toLocaleDateString('en-CA', {
                timeZone: 'Asia/Manila',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            });

            if (this.value === "" || cellDateFormatted === selectedDate) {
                row.style.display = ""; // Show row
            } else {
                row.style.display = "none"; // Hide row
            }
        });
    });
}




