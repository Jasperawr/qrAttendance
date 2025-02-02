<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Viewer</title>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-4">
    <h1 class="text-2xl font-bold mb-4">Excel Viewer</h1>
    <input type="file" id="excelFile" accept=".xls,.xlsx" class="mb-4 p-2 bg-white border border-gray-300 rounded" />
    <div id="viewer"></div>

    <script>
        document.getElementById('excelFile').addEventListener('change', handleFile, false);

        function handleFile(event) {
            const file = event.target.files[0];
            if (!file) {
                alert('Please select an Excel file!');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });
                const viewer = document.getElementById('viewer');
                viewer.innerHTML = ''; // Clear previous content

                workbook.SheetNames.forEach((sheetName) => {
                    const worksheet = workbook.Sheets[sheetName];
                    const jsonData = XLSX.utils.sheet_to_json(worksheet, {
                        header: 1,
                        range: 35
                    });

                    const headerRow = jsonData[0];
                    const studentNoIndex = headerRow.indexOf('Student No');
                    const fullNameIndex = headerRow.indexOf('Full Name');

                    if (studentNoIndex === -1 || fullNameIndex === -1) {
                        const error = document.createElement('p');
                        error.textContent = `Headers "Student No" and "Full Name" not found in sheet "${sheetName}".`;
                        error.className = 'text-red-600 font-semibold';
                        viewer.appendChild(error);
                        return;
                    }

                    // Remove empty or rows with just whitespace
                    const filteredData = jsonData.slice(1).filter(row => {
                        const studentNo = row[studentNoIndex];
                        const fullName = row[fullNameIndex];
                        return studentNo && fullName && studentNo.trim() !== '' && fullName.trim() !== ''; // Filter out rows where Student No or Full Name is empty
                    });

                    // Create table for filtered data
                    const page = document.createElement('div');
                    page.className = 'page mb-8 p-6 bg-white rounded-lg shadow-lg';

                    const title = document.createElement('h2');
                    title.textContent = `Sheet: ${sheetName}`;
                    title.className = 'text-xl font-semibold mb-4';
                    page.appendChild(title);

                    const table = document.createElement('table');
                    table.className = 'min-w-full table-auto border-collapse';

                    const thead = document.createElement('thead');
                    const headerRowElement = document.createElement('tr');
                    ['Student No', 'Full Name'].forEach((header) => {
                        const th = document.createElement('th');
                        th.textContent = header;
                        th.className = 'border p-2 text-left bg-gray-200';
                        headerRowElement.appendChild(th);
                    });
                    thead.appendChild(headerRowElement);
                    table.appendChild(thead);

                    const tbody = document.createElement('tbody');
                    filteredData.forEach((row) => {
                        const studentNo = row[studentNoIndex] || '';
                        const fullName = row[fullNameIndex] || '';

                        const tr = document.createElement('tr');
                        [studentNo, fullName].forEach((cell) => {
                            const td = document.createElement('td');
                            td.textContent = cell;
                            td.className = 'border p-2 text-left';
                            tr.appendChild(td);
                        });
                        tbody.appendChild(tr);
                    });
                    table.appendChild(tbody);

                    page.appendChild(table);
                    viewer.appendChild(page);
                });
            };

            reader.readAsArrayBuffer(file);
        }
    </script>
</body>

</html>