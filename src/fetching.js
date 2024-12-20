function loadData(url, targetElementId) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            document.getElementById(targetElementId).innerHTML = data;
        })
        .catch(error => console.error(`Error fetching data from ${url}:`, error));
}

// Load items on page load
window.addEventListener('DOMContentLoaded', () => {
    loadData('itemTableFaculty.php', 'items-table-body');
});