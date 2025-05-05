<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container {
            width: 95%;
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .tabs {
            display: flex;
            background: #2c3e50;
        }
        
        .tab {
            padding: 15px 25px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            flex: 1;
        }
        
        .tab:hover {
            background: #34495e;
        }
        
        .tab.active {
            background: #3498db;
            font-weight: bold;
        }
        
        .tab-content {
            display: none;
            padding: 20px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-top: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #3498db;
            color: white;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        tr:hover {
            background-color: #e6f7ff;
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
            border-top: 4px solid #3498db;
        }
        
        .card h3 {
            color: #2c3e50;
            margin-top: 0;
        }
        
        .card .value {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
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
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        select, input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="tabs">
            <div class="tab active" onclick="openTab('patient-reports')">Patient Reports</div>
            <div class="tab" onclick="openTab('medication-consumption')">Medication Consumption</div>
            <div class="tab" onclick="openTab('case-load')">Case Load</div>
        </div>
        
        <!-- Patient Reports Tab -->
        <div id="patient-reports" class="tab-content active">
            <h2>Patient Reports</h2>
            
            <div class="filters">
                <div class="filter-group">
                    <label for="timeframe">Timeframe:</label>
                    <select id="timeframe">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                        <option>Last year</option>
                        <option>Custom range</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="department">Department:</label>
                    <select id="department">
                        <option>All Departments</option>
                        <option>Cardiology</option>
                        <option>Neurology</option>
                        <option>Oncology</option>
                        <option>Pediatrics</option>
                        <option>General Medicine</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="patient-type">Patient Type:</label>
                    <select id="patient-type">
                        <option>All Patients</option>
                        <option>Inpatient</option>
                        <option>Outpatient</option>
                        <option>Emergency</option>
                    </select>
                </div>
                
                <div class="filter-group" style="align-self: flex-end;">
                    <button>Apply Filters</button>
                </div>
            </div>
            
            <div class="summary-cards">
                <div class="card">
                    <h3>Total Patients</h3>
                    <div class="value">1,248</div>
                    <div>+12% from last period</div>
                </div>
                
                <div class="card">
                    <h3>Average Stay</h3>
                    <div class="value">3.2 days</div>
                    <div>-0.5 days from last period</div>
                </div>
                
                <div class="card">
                    <h3>Readmission Rate</h3>
                    <div class="value">8.5%</div>
                    <div>-1.2% from last period</div>
                </div>
                
                <div class="card">
                    <h3>Satisfaction Score</h3>
                    <div class="value">4.6/5</div>
                    <div>+0.2 from last period</div>
                </div>
            </div>
            
            <div class="chart-container">
                <!-- Chart would be rendered here with a library like Chart.js -->
                <img src="https://via.placeholder.com/1200x400?text=Patient+Admissions+Trend+Chart" alt="Patient Admissions Trend Chart" style="width:100%; height:100%; object-fit: cover;">
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Admission Date</th>
                        <th>Discharge Date</th>
                        <th>Department</th>
                        <th>Diagnosis</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PT-1001</td>
                        <td>John Smith</td>
                        <td>2023-05-15</td>
                        <td>2023-05-18</td>
                        <td>Cardiology</td>
                        <td>Hypertension</td>
                        <td>Discharged</td>
                    </tr>
                    <tr>
                        <td>PT-1002</td>
                        <td>Sarah Johnson</td>
                        <td>2023-05-16</td>
                        <td>-</td>
                        <td>Neurology</td>
                        <td>Migraine</td>
                        <td>In Treatment</td>
                    </tr>
                    <tr>
                        <td>PT-1003</td>
                        <td>Michael Brown</td>
                        <td>2023-05-10</td>
                        <td>2023-05-12</td>
                        <td>General Medicine</td>
                        <td>Influenza</td>
                        <td>Discharged</td>
                    </tr>
                    <tr>
                        <td>PT-1004</td>
                        <td>Emily Davis</td>
                        <td>2023-05-17</td>
                        <td>-</td>
                        <td>Pediatrics</td>
                        <td>Asthma</td>
                        <td>In Treatment</td>
                    </tr>
                    <tr>
                        <td>PT-1005</td>
                        <td>Robert Wilson</td>
                        <td>2023-05-05</td>
                        <td>2023-05-09</td>
                        <td>Oncology</td>
                        <td>Follow-up</td>
                        <td>Discharged</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Medication Consumption Tab -->
        <div id="medication-consumption" class="tab-content">
            <h2>Medication Consumption</h2>
            
            <div class="filters">
                <div class="filter-group">
                    <label for="med-timeframe">Timeframe:</label>
                    <select id="med-timeframe">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option selected>Last 90 days</option>
                        <option>Last year</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="med-category">Medication Category:</label>
                    <select id="med-category">
                        <option>All Categories</option>
                        <option>Analgesics</option>
                        <option>Antibiotics</option>
                        <option>Antihypertensives</option>
                        <option>Psychotropics</option>
                        <option>Other</option>
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
                
                <div class="filter-group" style="align-self: flex-end;">
                    <button>Apply Filters</button>
                </div>
            </div>
            
            <div class="summary-cards">
                <div class="card">
                    <h3>Total Medications</h3>
                    <div class="value">87</div>
                    <div>Different medications used</div>
                </div>
                
                <div class="card">
                    <h3>Most Prescribed</h3>
                    <div class="value">Paracetamol</div>
                    <div>1,245 doses</div>
                </div>
                
                <div class="card">
                    <h3>Average Daily Use</h3>
                    <div class="value">428</div>
                    <div>Doses per day</div>
                </div>
                
                <div class="card">
                    <h3>Controlled Substances</h3>
                    <div class="value">12%</div>
                    <div>Of total medications</div>
                </div>
            </div>
            
            <div class="chart-container">
                <!-- Chart would be rendered here with a library like Chart.js -->
                <img src="https://via.placeholder.com/1200x400?text=Medication+Consumption+Trend+Chart" alt="Medication Consumption Trend Chart" style="width:100%; height:100%; object-fit: cover;">
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Medication ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Quantity Used</th>
                        <th>Average Daily Use</th>
                        <th>Stock Level</th>
                        <th>Reorder Needed</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>MED-5001</td>
                        <td>Paracetamol 500mg</td>
                        <td>Analgesic</td>
                        <td>1,245</td>
                        <td>13.8</td>
                        <td>2,100</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>MED-5002</td>
                        <td>Amoxicillin 250mg</td>
                        <td>Antibiotic</td>
                        <td>876</td>
                        <td>9.7</td>
                        <td>1,200</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>MED-5003</td>
                        <td>Lisinopril 10mg</td>
                        <td>Antihypertensive</td>
                        <td>654</td>
                        <td>7.3</td>
                        <td>800</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>MED-5004</td>
                        <td>Diazepam 5mg</td>
                        <td>Psychotropic</td>
                        <td>321</td>
                        <td>3.6</td>
                        <td>450</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>MED-5005</td>
                        <td>Ibuprofen 400mg</td>
                        <td>Analgesic</td>
                        <td>1,098</td>
                        <td>12.2</td>
                        <td>1,800</td>
                        <td>No</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Case Load Tab -->
        <div id="case-load" class="tab-content">
            <h2>Case Load Distribution</h2>
            
            <div class="filters">
                <div class="filter-group">
                    <label for="case-timeframe">Timeframe:</label>
                    <select id="case-timeframe">
                        <option>Current</option>
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option selected>Last 90 days</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="staff-role">Staff Role:</label>
                    <select id="staff-role">
                        <option>All Staff</option>
                        <option>Doctors</option>
                        <option>Nurses</option>
                        <option>Specialists</option>
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
                    <button>Apply Filters</button>
                </div>
            </div>
            
            <div class="summary-cards">
                <div class="card">
                    <h3>Total Staff</h3>
                    <div class="value">48</div>
                    <div>Active medical staff</div>
                </div>
                
                <div class="card">
                    <h3>Average Case Load</h3>
                    <div class="value">26</div>
                    <div>Cases per staff</div>
                </div>
                
                <div class="card">
                    <h3>Highest Case Load</h3>
                    <div class="value">Dr. Johnson</div>
                    <div>42 cases</div>
                </div>
                
                <div class="card">
                    <h3>Staff Satisfaction</h3>
                    <div class="value">4.2/5</div>
                    <div>Workload rating</div>
                </div>
            </div>
            
            <div class="chart-container">
                <!-- Chart would be rendered here with a library like Chart.js -->
                <img src="https://via.placeholder.com/1200x400?text=Case+Load+Distribution+Chart" alt="Case Load Distribution Chart" style="width:100%; height:100%; object-fit: cover;">
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Active Cases</th>
                        <th>Completed Cases</th>
                        <th>Total Cases</th>
                        <th>Workload</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ST-2001</td>
                        <td>Dr. Johnson</td>
                        <td>Cardiologist</td>
                        <td>Cardiology</td>
                        <td>18</td>
                        <td>24</td>
                        <td>42</td>
                        <td>High</td>
                    </tr>
                    <tr>
                        <td>ST-2002</td>
                        <td>Dr. Williams</td>
                        <td>Neurologist</td>
                        <td>Neurology</td>
                        <td>12</td>
                        <td>22</td>
                        <td>34</td>
                        <td>Medium</td>
                    </tr>
                    <tr>
                        <td>ST-2003</td>
                        <td>Nurse Peterson</td>
                        <td>RN</td>
                        <td>General Medicine</td>
                        <td>8</td>
                        <td>28</td>
                        <td>36</td>
                        <td>Medium</td>
                    </tr>
                    <tr>
                        <td>ST-2004</td>
                        <td>Dr. Lee</td>
                        <td>Pediatrician</td>
                        <td>Pediatrics</td>
                        <td>15</td>
                        <td>15</td>
                        <td>30</td>
                        <td>Medium</td>
                    </tr>
                    <tr>
                        <td>ST-2005</td>
                        <td>Nurse Garcia</td>
                        <td>RN</td>
                        <td>Oncology</td>
                        <td>5</td>
                        <td>20</td>
                        <td>25</td>
                        <td>Low</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        function openTab(tabName) {
            // Hide all tab contents
            var tabContents = document.getElementsByClassName('tab-content');
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }
            
            // Remove active class from all tabs
            var tabs = document.getElementsByClassName('tab');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            
            // Show the current tab and add active class
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>