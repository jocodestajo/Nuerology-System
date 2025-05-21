<style>
    .tabs-Rep {
        display: flex;
        background: var(--beige-color);
    }

    .tabRep {
        padding: 10px 20px;
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

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #2c3e50;
    }
</style>

<div class="tabs-Rep">
    <div class="tabRep active" onclick="openTab('patient-reports')">Patient Reports</div>
    <div class="tabRep" onclick="openTab('medication-consumption')">Medication Consumption</div>
    <div class="tabRep" onclick="openTab('case-load')">Case Load</div>
</div>

<!-- Patient Reports Tab -->
<div id="patient-reports" class="tab-content active">
    <!-- <h2 class="border-b">Patient Reports</h2> -->
    
    <div class="filters">
        <div class="filter-group">
            <label for="patient-type">Patient Type:</label>
            <select id="patient-type" class="width-100">
                <option>All Patients</option>
                <option>Pending</option>
                <option>Face to Face</option>
                <option>Teleconsultion</option>
                <option>Processed</option>
                <option>Cancelled</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="timeframe">Timeframe:</label>
            <select id="timeframe" class="width-100">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>Last 90 days</option>
                <option>Last year</option>
                <option>Custom range</option>
            </select>
        </div>
        
        <div class="filter-group" style="align-self: flex-end;">
            <button class="btn btn-blue">Apply Filters</button>
        </div>
    </div>
    
    <!-- <div class="summary-cards">
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
    </div> -->
    
    <table>
        <thead>
            <tr>
                <th>#</th>
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
<div id="medication-consumption" class="tab-content">
    <h2 class="border-b">Medication Consumption</h2>
    
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
    <h2 class="border-b">Case Load Distribution</h2>
    
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

