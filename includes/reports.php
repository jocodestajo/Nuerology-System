<style>
    .tabs-Rep {
        display: flex;
        background: var(--beige-color);
    }

    .tabRep {
        padding: 5px 20px;
        color: black;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        flex: 1;
    }

    .tabRep:hover {
        background: var(--blue-color);
        color: var(--white-color);
    }

    .tabRep.active {
        background: var(--blue-color);
        color: var(--white-color);
        font-weight: bold;
    }

    .tab-content {
        display: none;
        padding: 20px;
    }

    .tab-content.active {
        display: block;
    }

    .border-b {
        color: #2c3e50;
        border-bottom: 2px solid var(--blue-color);
        padding-bottom: 10px;
        margin-top: 0;
    }


    tr:hover {
        background-color: var(--beige-color);
    }

    .chart-container {
        width: 100%;
        height: 400px;
        margin: 20px 0;
    }

    .summary-cards {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .card {
        flex: 1;
        background: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin: 0 10px;
        text-align: center;
        border-top: 4px solid var(--blue-color);
    }

    .card h3 {
        color: #2c3e50;
        margin-top: 0;
    }

    .card .value {
        font-size: 24px;
        font-weight: bold;
        color: var(--blue-color);
        margin: 10px 0;
    }

    .filters {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }
</style>
<!-- REPORTS TABS -->
<div class="tabs-Rep">
    <div class="tabRep" onclick="openTab('patient-reports')">Patient Reports</div>
    <div class="tabRep active" onclick="openTab('medication-consumption')">Medication Consumption</div>
    <div class="tabRep" onclick="openTab('case-load')">Case Load</div>
</div>

<!-- Patient Reports Tab -->
<div id="patient-reports" class="tab-content">
    <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
        <button class="btn btn-green" onclick="printReport('patient-reports')">Print Report</button>
    </div>
    <!-- <h2 class="border-b">Patient Reports</h2> -->
    
    <div class="filters">
        <div class="filter-group">
            <label for="patient-type">Patient Type:</label>
            <select id="patient-type" name="patientType" class="width-100">
                <option>All Patients</option>
                <option>Pending</option>
                <option>Face to Face</option>
                <option>Teleconsultion</option>
                <option>Processed</option>
                <option>Cancelled</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="date-from">From:</label>
            <input type="date" id="date-from" name="dateFrom" class="width-100" />
        </div>
        <div class="filter-group">
            <label for="date-to">To:</label>
            <input type="date" id="date-to" name="dateTo" class="width-100" />
        </div>
        
        <div class="filter-group" style="align-self: flex-end;">
            <button class="btn btn-blue">Apply Filters</button>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="th-check">#</th>
                <th>HRN</th>
                <th>Name</th>
                <th>Date Schedule</th>
                <th>Date Processed</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Medication Consumption Tab -->
<div id="medication-consumption" class="tab-content active">
    <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
        <button class="btn btn-green" onclick="printReport('medication-consumption')">Print Report</button>
    </div>
    <!-- <h2 class="border-b">Medication Consumption</h2> -->
    
    <div class="filters">
        <div class="filter-group">
            <label for="med-month-filter">Month:</label>
            <select id="med-month-filter">
                <option value="">All Months</option>
                <option value="0">January</option>
                <option value="1">February</option>
                <option value="2">March</option>
                <option value="3">April</option>
                <option value="4">May</option>
                <option value="5">June</option>
                <option value="6">July</option>
                <option value="7">August</option>
                <option value="8">September</option>
                <option value="9">October</option>
                <option value="10">November</option>
                <option value="11">December</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="med-sort">Sort By:</label>
            <select id="med-sort">
                <option>Highest Consumption</option>
                <option>Lowest Consumption</option>
                <option>Alphabetical</option>
            </select>
        </div>
        
        <div class="filter-group" style="align-self: flex-end; display: flex; gap: 10px;">
            <button id="apply-med-filters" class="btn-blue">Apply Filters</button>
            <button id="clear-med-filters" type="button" class="btn-red">Clear Filters</button>
        </div>
    </div>
    
    <div class="summary-cards">
        <div class="card">
            <h3>Total Medications</h3>
            <div class="value" id="total-medications-value">0</div>
            <div>Different medications</div>
        </div>
        
        <div class="card">
            <h3>Most Prescribed</h3>
            <div class="value" id="most-prescribed-name">N/A</div>
            <div id="most-prescribed-doses">0 doses</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th class="th-check">#</th>
                <th>Name</th>
                <th>Qty Prescribed</th>
                <th>Total Patients</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated by JavaScript -->
        </tbody>
    </table>
</div>

<!-- Case Load Tab -->
<div id="case-load" class="tab-content">
    <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
        <button class="btn btn-green" onclick="printReport('case-load')">Print Report</button>
    </div>
    <div class="filters">
        <div class="filter-group">
            <label for="case-month-filter">Month:</label>
            <select id="case-month-filter">
                <option value="">All Months</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="case-sort">Sort By:</label>
            <select id="case-sort">
                <option>Highest Case Load</option>
                <option>Lowest Case Load</option>
                <option>Alphabetical</option>
            </select>
        </div>
        <div class="filter-group" style="align-self: flex-end;">
            <button id="apply-case-filters" class="btn-blue">Apply Filters</button>
            <button id="clear-case-filters" type="button" class="btn-red">Clear Filters</button>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th class="th-check">#</th>
                <th>Case Load / Classification</th>
                <th>Number of Cases</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $query = "
                    SELECT cl.name as classification_name,
                        COUNT(c.id) as case_count
                    FROM neurology_classifications cl 
                    LEFT JOIN neurology_consultations c ON cl.id = c.classification
                    WHERE c.classification IS NOT NULL AND c.classification != ''
                    GROUP BY cl.name
                    ORDER BY case_count DESC
                ";

                $query_run = mysqli_query($conn, $query);

                $total_cases = 0;
                if(mysqli_num_rows($query_run) > 0)
                {
                    $index = 0;
                    foreach($query_run as $records)
                        {
                            $is_new_client = ($records['case_count'] == 1);
                            $row_class = $is_new_client ? 'new-client' : '';
                            $index++;
                            $total_cases += (int)$records['case_count'];
                        ?>
                            <tr id="case_<?=$records['classification_name'];?>" class="<?= $row_class ?> th-check">
                                <td><?= $index; ?></td>
                                <td><?= $records['classification_name']; ?></td>
                                <td><?= $records['case_count']; ?></td>
                            </tr>
                        <?php
                    }

                }
            ?>
            
            <!-- Data will be populated by JavaScript -->
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align: right;">Total:</th>
                <th id="case-load-total"><?php echo $total_cases; ?></th>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    let medicationDataCache = [];
    let caseLoadDataCache = [];

    function openTab(tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tabRep");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        event.currentTarget.className += " active";

        if (tabName === 'case-load') {
            fetchCaseLoadData();
        }
    }

    function fetchMedicationData(month = '') {
        let url = `api/get/fetch-reports.php?reportType=medication`;
        if (month !== '') {
            url += `&month=${month}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                medicationDataCache = data;
                applySortingAndRender();
            })
            .catch(error => {
                console.error('Error fetching medication data:', error);
                const tableBody = document.querySelector('#medication-consumption tbody');
                tableBody.innerHTML = '<tr><td colspan="4">Error loading data.</td></tr>';
            });
    }

    function applyMedicationFilters() {
        const selectedMonth = document.getElementById('med-month-filter').value;
        fetchMedicationData(selectedMonth);
    }

    function clearMedicationFilters() {
        document.getElementById('med-month-filter').value = '';
        document.getElementById('med-sort').selectedIndex = 0;
        fetchMedicationData();
    }

    function applySortingAndRender() {
        let data = [...medicationDataCache];
        const sortBy = document.getElementById('med-sort').value;

        // Sort
        if (sortBy === 'Highest Consumption') {
            data.sort((a, b) => parseInt(b.quantity_used) - parseInt(a.quantity_used));
        } else if (sortBy === 'Lowest Consumption') {
            data.sort((a, b) => parseInt(a.quantity_used) - parseInt(b.quantity_used));
        } else if (sortBy === 'Alphabetical') {
            data.sort((a, b) => a.name.localeCompare(b.name));
        }

        renderMedicationTable(data);
    }

    function renderMedicationTable(data) {
        const tableBody = document.querySelector('#medication-consumption tbody');
        tableBody.innerHTML = '';

        // Update summary card for total medications
        const totalMedicationsValue = document.getElementById('total-medications-value');
        if (totalMedicationsValue) {
            totalMedicationsValue.textContent = data.length;
        }

        // Find and display most prescribed medication
        const mostPrescribedNameEl = document.getElementById('most-prescribed-name');
        const mostPrescribedDosesEl = document.getElementById('most-prescribed-doses');

        if (data.length > 0) {
            // Recalculate most prescribed based on potentially sorted data
            const mostPrescribed = [...data].sort((a, b) => parseInt(b.quantity_used) - parseInt(a.quantity_used))[0];
            if (mostPrescribedNameEl) {
                mostPrescribedNameEl.textContent = mostPrescribed.name;
            }
            if (mostPrescribedDosesEl) {
                mostPrescribedDosesEl.textContent = `${mostPrescribed.quantity_used} doses`;
            }
        } else {
            if (mostPrescribedNameEl) {
                mostPrescribedNameEl.textContent = 'N/A';
            }
            if (mostPrescribedDosesEl) {
                mostPrescribedDosesEl.textContent = '0 doses';
            }
        }

        if (data.length > 0) {
            data.forEach((medicine, index) => {
                const row = `
                    <tr>
                        <td class="th-check">${index + 1}</td>
                        <td>${medicine.name}</td>
                        <td>${medicine.quantity_used}</td>
                        <td>${medicine.total_users}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="4">No medication data found.</td></tr>';
        }
    }

    function fetchCaseLoadData(month = '') {
        let url = `api/get/fetch-reports.php?reportType=case-load`;
        if (month !== '') {
            url += `&month=${month}`;
        }
        fetch(url)
            .then(response => response.json())
            .then(data => {
                caseLoadDataCache = data;
                applyCaseLoadSortingAndRender();
            })
            .catch(error => {
                console.error('Error fetching case load data:', error);
                const tableBody = document.querySelector('#case-load tbody');
                tableBody.innerHTML = '<tr><td colspan="3">Error loading data.</td></tr>';
            });
    }

    function applyCaseLoadFilters() {
        const selectedMonth = document.getElementById('case-month-filter').value;
        fetchCaseLoadData(selectedMonth);
    }

    function clearCaseLoadFilters() {
        document.getElementById('case-month-filter').value = '';
        document.getElementById('case-sort').selectedIndex = 0;
        fetchCaseLoadData();
    }

    function applyCaseLoadSortingAndRender() {
        let data = [...caseLoadDataCache];
        const sortBy = document.getElementById('case-sort').value;
        if (sortBy === 'Highest Case Load') {
            data.sort((a, b) => parseInt(b.case_count) - parseInt(a.case_count));
        } else if (sortBy === 'Lowest Case Load') {
            data.sort((a, b) => parseInt(a.case_count) - parseInt(b.case_count));
        } else if (sortBy === 'Alphabetical') {
            data.sort((a, b) => a.classification_name.localeCompare(b.classification_name));
        }
        renderCaseLoadTable(data);
    }

    function renderCaseLoadTable(data) {
        const tableBody = document.querySelector('#case-load tbody');
        tableBody.innerHTML = '';
        let totalCases = 0;
        if (data.length > 0) {
            data.forEach((item, index) => {
                totalCases += parseInt(item.case_count);
                const row = `
                    <tr>
                        <td class="th-check">${index + 1}</td>
                        <td>${item.classification_name}</td>
                        <td>${item.case_count}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="3">No case load data found.</td></tr>';
        }
        document.getElementById('case-load-total').textContent = totalCases;
    }

    function printReport(tabId) {
        const tabContent = document.getElementById(tabId);
        if (!tabContent) return;
        // Clone tab content to manipulate for printing
        const clone = tabContent.cloneNode(true);
        // Remove filter section
        const filterDiv = clone.querySelector('.filters');
        if (filterDiv) filterDiv.remove();
        // Create header based on filters
        let headerText = '';
        if (tabId === 'patient-reports') {
            const patientType = document.getElementById('patient-type').value || document.getElementById('patient-type').options[document.getElementById('patient-type').selectedIndex].text;
            const dateFrom = document.getElementById('date-from').value;
            const dateTo = document.getElementById('date-to').value;
            let dateRangeText = '';
            if (dateFrom && dateTo) {
                dateRangeText = `Date Range: ${dateFrom} to ${dateTo}`;
            } else if (dateFrom) {
                dateRangeText = `From: ${dateFrom}`;
            } else if (dateTo) {
                dateRangeText = `To: ${dateTo}`;
            } else {
                dateRangeText = 'Date Range: All Dates';
            }
            headerText = `Patient Reports - Patient Type: ${patientType}, ${dateRangeText}`;
        } else if (tabId === 'medication-consumption') {
            const month = document.getElementById('med-month-filter').value;
            const monthText = month === '' ? 'All Months' : document.getElementById('med-month-filter').options[parseInt(month) + 1].text;
            const sort = document.getElementById('med-sort').value;
            headerText = `Medication Consumption - Month: ${monthText}, Sort By: ${sort}`;
        } else if (tabId === 'case-load') {
            const month = document.getElementById('case-month-filter').value;
            const monthText = month === '' ? 'All Months' : document.getElementById('case-month-filter').options[parseInt(month)].text;
            const sort = document.getElementById('case-sort').value;
            headerText = `Case Load - Month: ${monthText}, Sort By: ${sort}`;
        }
        // Insert header
        const header = document.createElement('h2');
        header.textContent = headerText;
        header.style.fontSize = '1.5rem';
        header.style.fontWeight = 'bold';
        header.style.marginBottom = '20px';
        clone.insertBefore(header, clone.firstChild);
        // Remove print button from clone
        const printBtn = clone.querySelector('button.btn-green');
        if (printBtn) printBtn.remove();
        // Print
        const printWindow = window.open('', '', 'height=700,width=900');
        printWindow.document.write('<html><head><title>Print Report</title>');
        // Copy styles
        const styles = Array.from(document.querySelectorAll('style, link[rel="stylesheet"]'));
        styles.forEach(style => {
            printWindow.document.write(style.outerHTML);
        });
        printWindow.document.write('</head><body>');
        printWindow.document.write(clone.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }

    function fetchPatientData() {
        const patientType = document.getElementById('patient-type').value;
        const dateFrom = document.getElementById('date-from').value;
        const dateTo = document.getElementById('date-to').value;
        let url = `api/get/fetch-reports.php?reportType=patient&patientType=${encodeURIComponent(patientType)}`;
        if (dateFrom) url += `&dateFrom=${encodeURIComponent(dateFrom)}`;
        if (dateTo) url += `&dateTo=${encodeURIComponent(dateTo)}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                renderPatientTable(data);
            })
            .catch(error => {
                console.error('Error fetching patient data:', error);
                const tableBody = document.querySelector('#patient-reports tbody');
                tableBody.innerHTML = '<tr><td colspan="6">Error loading data.</td></tr>';
            });
    }

    function renderPatientTable(data) {
        const tableBody = document.querySelector('#patient-reports tbody');
        tableBody.innerHTML = '';
        const totalPatientsValue = document.getElementById('total-patients-value');
        if (totalPatientsValue) {
            totalPatientsValue.textContent = data.length;
        }
        if (data.length > 0) {
            data.forEach((patient, index) => {
                const row = `
                    <tr>
                        <td class="th-check">${index + 1}</td>
                        <td>${patient.hrn}</td>
                        <td>${patient.name}</td>
                        <td>${patient.date_sched}</td>
                        <td>${patient.date_process}</td>
                        <td>${patient.status}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="6">No patient data found.</td></tr>';
        }
    }

    function applyPatientFilters() {
        fetchPatientData();
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchMedicationData(); // Show all data by default
        document.getElementById('apply-med-filters').addEventListener('click', applyMedicationFilters);
        document.getElementById('clear-med-filters').addEventListener('click', clearMedicationFilters);
        document.getElementById('med-sort').addEventListener('change', applySortingAndRender);

        // Case Load event listeners
        document.getElementById('apply-case-filters').addEventListener('click', applyCaseLoadFilters);
        document.getElementById('clear-case-filters').addEventListener('click', clearCaseLoadFilters);
        document.getElementById('case-sort').addEventListener('change', applyCaseLoadSortingAndRender);

        document.querySelector('#patient-reports .btn-blue').addEventListener('click', applyPatientFilters);
        fetchPatientData(); // Load initial data
    });
</script>

