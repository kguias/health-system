<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Health Office - Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Login Screen -->
    <div id="loginScreen" class="login-container">
        <div class="logo">🏥</div>
        <h2>City Health Office</h2>
        <div id="loginAlert"></div>
        <form id="loginForm">
            <div class="form-group">
                <label>Username</label>
                <input type="text" id="loginUsername" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="loginPassword" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select id="loginRole" required>
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="doctor">Doctor</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p style="text-align: center; margin-top: 15px; color: #6c757d; font-size: 12px;">
            Demo: admin/admin123, doctor/doctor123, staff/staff123
        </p>
    </div>

    <!-- Main Application -->
    <div id="mainApp" class="main-app hidden">
        <!-- Header -->
        <header>
            <h1>🏥 City Health Office - Management System</h1>
            <div class="user-info">
                <span id="currentUser"></span>
                <button class="btn btn-small btn-secondary" onclick="logout()">Logout</button>
            </div>
        </header>

        <div class="app-container">
            <!-- Sidebar Navigation -->
            <aside class="sidebar">
                <nav>
                    <button class="nav-item active" onclick="showSection('dashboard')">
                        <span class="icon">📊</span>
                        <span>Dashboard</span>
                    </button>
                    <button class="nav-item" onclick="showSection('patients')">
                        <span class="icon">👥</span>
                        <span>Patients</span>
                    </button>
                    <button class="nav-item" onclick="showSection('appointments')">
                        <span class="icon">📅</span>
                        <span>Appointments</span>
                    </button>
                    <button class="nav-item" onclick="showSection('emr')">
                        <span class="icon">📋</span>
                        <span>Medical Records</span>
                    </button>
                    <button class="nav-item" onclick="showSection('prescriptions')">
                        <span class="icon">💊</span>
                        <span>Prescriptions</span>
                    </button>
                    <button class="nav-item" onclick="showSection('lab')">
                        <span class="icon">🔬</span>
                        <span>Lab Results</span>
                    </button>
                    <button class="nav-item" onclick="showSection('billing')">
                        <span class="icon">💰</span>
                        <span>Billing</span>
                    </button>
                    <button class="nav-item" onclick="showSection('reports')">
                        <span class="icon">📈</span>
                        <span>Reports</span>
                    </button>
                    <button class="nav-item" onclick="showSection('backup')">
                        <span class="icon">🔒</span>
                        <span>Backup & Security</span>
                    </button>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <main class="main-content">
                <!-- Dashboard Section -->
                <div id="dashboard" class="section active">
                    <h2 class="page-title">Dashboard Overview</h2>
                    <div class="dashboard-grid">
                        <div class="stat-card">
                            <h3>Total Patients</h3>
                            <div class="number" id="totalPatients">0</div>
                        </div>
                        <div class="stat-card">
                            <h3>Today's Appointments</h3>
                            <div class="number" id="todayAppointments">0</div>
                        </div>
                        <div class="stat-card">
                            <h3>Pending Queue</h3>
                            <div class="number" id="queueCount">0</div>
                        </div>
                        <div class="stat-card">
                            <h3>Active Prescriptions</h3>
                            <div class="number" id="activePrescriptions">0</div>
                        </div>
                    </div>

                    <div class="card">
                        <h3>Recent Activities (Audit Trail)</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody id="auditTrail"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Patient Management Section -->
                <div id="patients" class="section">
                    <h2 class="page-title">Patient Management</h2>
                    <button class="btn btn-success btn-small" onclick="openModal('addPatientModal')">+ Add New Patient</button>
                    
                    <div class="search-bar" style="margin-top: 20px;">
                        <input type="text" id="patientSearch" placeholder="Search patients by name, ID, or phone..." oninput="searchPatients()">
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th>Last Visit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="patientsList"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Appointments Section -->
                <div id="appointments" class="section">
                    <h2 class="page-title">Appointment Scheduling & Queue</h2>
                    <button class="btn btn-success btn-small" onclick="openModal('addAppointmentModal')">+ Schedule Appointment</button>

                    <div class="card" style="margin-top: 20px;">
                        <h3>Current Queue</h3>
                        <div id="queueList"></div>
                    </div>

                    <div class="card">
                        <h3>All Appointments</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th>Queue #</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="appointmentsList"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- EMR Section -->
                <div id="emr" class="section">
                    <h2 class="page-title">Electronic Medical Records (EMR)</h2>
                    <button class="btn btn-success btn-small" onclick="openModal('addRecordModal')">+ Create New Record</button>

                    <div class="search-bar" style="margin-top: 20px;">
                        <input type="text" id="recordSearch" placeholder="Search by patient name or record ID..." oninput="searchRecords()">
                    </div>

                    <div id="recordsList"></div>
                </div>

                <!-- Prescriptions Section -->
                <div id="prescriptions" class="section">
                    <h2 class="page-title">Prescription & Medication Tracking</h2>
                    <button class="btn btn-success btn-small" onclick="openModal('addPrescriptionModal')">+ New Prescription</button>

                    <div class="table-container" style="margin-top: 20px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Medications</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="prescriptionsList"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Lab Results Section -->
                <div id="lab" class="section">
                    <h2 class="page-title">Laboratory Test Results</h2>
                    <button class="btn btn-success btn-small" onclick="openModal('addLabModal')">+ Add Lab Result</button>

                    <div class="table-container" style="margin-top: 20px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Test Type</th>
                                    <th>Result</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="labResultsList"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Billing Section -->
                <div id="billing" class="section">
                    <h2 class="page-title">Billing & Payment Recording</h2>
                    <button class="btn btn-success btn-small" onclick="openModal('addBillingModal')">+ New Billing Entry</button>

                    <div class="table-container" style="margin-top: 20px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Service</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="billingList"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Reports Section -->
                <div id="reports" class="section">
                    <h2 class="page-title">Standard Reports</h2>
                    
                    <div class="form-grid">
                        <div class="card">
                            <h3>Daily Patient List</h3>
                            <div class="form-group">
                                <label>Select Date</label>
                                <input type="date" id="reportDate">
                            </div>
                            <button class="btn btn-small" onclick="generateDailyReport()">Generate Report</button>
                        </div>

                        <div class="card">
                            <h3>Visit History Report</h3>
                            <div class="form-group">
                                <label>Select Patient</label>
                                <select id="reportPatient"></select>
                            </div>
                            <button class="btn btn-small" onclick="generateVisitHistory()">Generate Report</button>
                        </div>
                    </div>

                    <div class="card">
                        <h3>Generated Reports</h3>
                        <div id="generatedReports"></div>
                    </div>
                </div>

                <!-- Backup & Security Section -->
                <div id="backup" class="section">
                    <h2 class="page-title">Data Backup & Security</h2>
                    
                    <div class="card">
                        <h3>Database Backup</h3>
                        <p style="margin-bottom: 15px;">Last backup: <strong id="lastBackup">Never</strong></p>
                        <button class="btn btn-small btn-success" onclick="performBackup()">Backup Now</button>
                        <button class="btn btn-small btn-secondary" onclick="restoreBackup()">Restore from Backup</button>
                    </div>

                    <div class="card">
                        <h3>Data Privacy & Security</h3>
                        <div class="info-row">
                            <span class="info-label">Encryption Status</span>
                            <span style="color: #28a745; font-weight: 600;">✓ Enabled</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">SSL Certificate</span>
                            <span style="color: #28a745; font-weight: 600;">✓ Valid</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Access Control</span>
                            <span style="color: #28a745; font-weight: 600;">✓ Role-based Active</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Audit Logging</span>
                            <span style="color: #28a745; font-weight: 600;">✓ Enabled</span>
                        </div>
                    </div>

                    <div class="card">
                        <h3>System Information</h3>
                        <div class="info-row">
                            <span class="info-label">Total Records</span>
                            <span id="totalRecords">0</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Database Size</span>
                            <span id="dbSize">calculating...</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Active Users</span>
                            <span id="activeUsers">1</span>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Patient Modal -->
    <div id="addPatientModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Patient</h3>
                <button class="close-btn" onclick="closeModal('addPatientModal')">&times;</button>
            </div>
            <form id="addPatientForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" name="lastName" required>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Date of Birth *</label>
                        <input type="date" name="dob" required>
                    </div>
                    <div class="form-group">
                        <label>Gender *</label>
                        <select name="gender" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Phone Number *</label>
                    <input type="tel" name="phone" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Blood Type</label>
                    <select name="bloodType">
                        <option value="">Select</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Allergies</label>
                    <textarea name="allergies" rows="2" placeholder="List any known allergies"></textarea>
                </div>
                <button type="submit" class="btn">Add Patient</button>
            </form>
        </div>
    </div>

    <!-- Add Appointment Modal -->
    <div id="addAppointmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Schedule Appointment</h3>
                <button class="close-btn" onclick="closeModal('addAppointmentModal')">&times;</button>
            </div>
            <form id="addAppointmentForm">
                <div class="form-group">
                    <label>Patient *</label>
                    <select name="patientId" id="appointmentPatient" required></select>
                </div>
                <div class="form-group">
                    <label>Doctor *</label>
                    <select name="doctor" required>
                        <option value="">Select Doctor</option>
                        <option value="Dr. Smith">Dr. Smith</option>
                        <option value="Dr. Johnson">Dr. Johnson</option>
                        <option value="Dr. Williams">Dr. Williams</option>
                    </select>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label>Time *</label>
                        <input type="time" name="time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Reason for Visit</label>
                    <textarea name="reason" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">Schedule Appointment</button>
            </form>
        </div>
    </div>

    <!-- Add Medical Record Modal -->
    <div id="addRecordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create Medical Record</h3>
                <button class="close-btn" onclick="closeModal('addRecordModal')">&times;</button>
            </div>
            <form id="addRecordForm">
                <div class="form-group">
                    <label>Patient *</label>
                    <select name="patientId" id="recordPatient" required></select>
                </div>
                <div class="form-group">
                    <label>Chief Complaint *</label>
                    <input type="text" name="complaint" required>
                </div>
                <div class="form-group">
                    <label>Diagnosis *</label>
                    <textarea name="diagnosis" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Treatment Notes *</label>
                    <textarea name="treatment" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label>Vital Signs</label>
                    <input type="text" name="vitals" placeholder="BP, Temp, HR, etc.">
                </div>
                <button type="submit" class="btn">Create Record</button>
            </form>
        </div>
    </div>

    <!-- Add Prescription Modal -->
    <div id="addPrescriptionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>New Prescription</h3>
                <button class="close-btn" onclick="closeModal('addPrescriptionModal')">&times;</button>
            </div>
            <form id="addPrescriptionForm">
                <div class="form-group">
                    <label>Patient *</label>
                    <select name="patientId" id="prescriptionPatient" required></select>
                </div>
                <div class="form-group">
                    <label>Doctor *</label>
                    <input type="text" name="doctor" required>
                </div>
                <div class="form-group">
                    <label>Medications *</label>
                    <textarea name="medications" rows="4" placeholder="List medications with dosage and frequency" required></textarea>
                </div>
                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" name="duration" placeholder="e.g., 7 days, 2 weeks">
                </div>
                <div class="form-group">
                    <label>Instructions</label>
                    <textarea name="instructions" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">Create Prescription</button>
            </form>
        </div>
    </div>

    <!-- Add Lab Result Modal -->
    <div id="addLabModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Lab Result</h3>
                <button class="close-btn" onclick="closeModal('addLabModal')">&times;</button>
            </div>
            <form id="addLabForm">
                <div class="form-group">
                    <label>Patient *</label>
                    <select name="patientId" id="labPatient" required></select>
                </div>
                <div class="form-group">
                    <label>Test Type *</label>
                    <select name="testType" required>
                        <option value="">Select Test</option>
                        <option value="Complete Blood Count">Complete Blood Count</option>
                        <option value="Urinalysis">Urinalysis</option>
                        <option value="Blood Sugar">Blood Sugar</option>
                        <option value="Cholesterol">Cholesterol</option>
                        <option value="X-Ray">X-Ray</option>
                        <option value="ECG">ECG</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Result *</label>
                    <textarea name="result" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="Normal">Normal</option>
                        <option value="Abnormal">Abnormal</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" rows="2"></textarea>
                </div>
                <button type="submit" class="btn">Add Result</button>
            </form>
        </div>
    </div>

    <!-- Add Billing Modal -->
    <div id="addBillingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>New Billing Entry</h3>
                <button class="close-btn" onclick="closeModal('addBillingModal')">&times;</button>
            </div>
            <form id="addBillingForm">
                <div class="form-group">
                    <label>Patient *</label>
                    <select name="patientId" id="billingPatient" required></select>
                </div>
                <div class="form-group">
                    <label>Service *</label>
                    <input type="text" name="service" required>
                </div>
                <div class="form-group">
                    <label>Amount *</label>
                    <input type="number" name="amount" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Payment Status *</label>
                    <select name="paymentStatus" required>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                        <option value="Partial">Partial</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="paymentMethod">
                        <option value="Cash">Cash</option>
                        <option value="Card">Card</option>
                        <option value="Insurance">Insurance</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <button type="submit" class="btn">Add Billing Entry</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>