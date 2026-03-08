// Data Storage (in-memory arrays)
let currentUser = null;
let patients = [];
let appointments = [];
let medicalRecords = [];
let prescriptions = [];
let labResults = [];
let billingRecords = [];
let auditLog = [];

// Users database (demo fallback, mostly for frontend testing)
const users = {
    'admin': { password: 'admin123', role: 'admin', name: 'Admin User' },
    'doctor': { password: 'doctor123', role: 'doctor', name: 'Dr. Smith' },
    'staff': { password: 'staff123', role: 'staff', name: 'Staff Member' }
};

/*
// OLD LOCAL LOGIN
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const username = document.getElementById('loginUsername').value;
    const password = document.getElementById('loginPassword').value;
    const role = document.getElementById('loginRole').value;

    if (users[username] && users[username].password === password && users[username].role === role) {
        currentUser = { username, role, name: users[username].name };
        document.getElementById('loginScreen').classList.add('hidden');
        document.getElementById('mainApp').classList.remove('hidden');
        document.getElementById('currentUser').textContent = `${currentUser.name} (${currentUser.role})`;
        loadData();
        updateDashboard();
        addAuditLog('Login', `User ${currentUser.name} logged in`);
    } else {
        document.getElementById('loginAlert').innerHTML = '<div class="alert alert-error">Invalid credentials</div>';
    }
});
*/

// LOGIN (fetch backend)
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            currentUser = data.user; // set current user from backend
            document.getElementById('loginScreen').classList.add('hidden');
            document.getElementById('mainApp').classList.remove('hidden');
            document.getElementById('currentUser').textContent = `${currentUser.name} (${currentUser.role})`;
            // Load all backend data on login
            loadPatients();
            loadAppointments();
            loadRecords();
            loadPrescriptions();
            loadLabResults();
            loadBilling();
            updateDashboard();
            addAuditLog('Login', `User ${currentUser.name} logged in`);
        } else {
            alert("Invalid credentials");
        }
    });
});

// LOGOUT
function logout() {
    if(currentUser) addAuditLog('Logout', `User ${currentUser.name} logged out`);
    currentUser = null;
    document.getElementById('loginScreen').classList.remove('hidden');
    document.getElementById('mainApp').classList.add('hidden');
    document.getElementById('loginForm').reset();
    document.getElementById('loginAlert').innerHTML = '';
}

// Navigation
function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(section => section.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(btn => btn.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
    event.target.classList.add('active');
}

// Modals
function openModal(modalId) { document.getElementById(modalId).classList.add('active'); }
function closeModal(modalId) { document.getElementById(modalId).classList.remove('active'); }

// PATIENTS

// Add Patient (fetch backend)
document.getElementById('addPatientForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);

    fetch("add_patient.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === "success"){
                patients.push(data.patient); // push new patient returned from backend
                renderPatients();
                updatePatientSelects();
                updateDashboard();
                addAuditLog('Create', `Patient ${data.patient.firstName} ${data.patient.lastName} added`);
                alert("Patient added!");
            }
        });
});

// Render Patients
function renderPatients() {
    const tbody = document.getElementById('patientsList');
    tbody.innerHTML = patients.map(p => {
        const age = calculateAge(p.dob);
        return `<tr>
            <td>${p.id}</td>
            <td>${p.firstName} ${p.lastName}</td>
            <td>${age}</td>
            <td>${p.gender}</td>
            <td>${p.phone}</td>
            <td>${p.lastVisit ? new Date(p.lastVisit).toLocaleDateString() : 'N/A'}</td>
            <td>
                <button class="btn btn-small" onclick="viewPatient('${p.id}')">View</button>
                <button class="btn btn-small btn-danger" onclick="deletePatient('${p.id}')">Delete</button>
            </td>
        </tr>`;
    }).join('');
}

function calculateAge(dob) {
    const today = new Date(), birth = new Date(dob);
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();
    if(monthDiff < 0 || (monthDiff===0 && today.getDate() < birth.getDate())) age--;
    return age;
}

function searchPatients() {
    const searchTerm = document.getElementById('patientSearch').value.toLowerCase();
    const filtered = patients.filter(p => 
        p.firstName.toLowerCase().includes(searchTerm) ||
        p.lastName.toLowerCase().includes(searchTerm) ||
        p.id.toLowerCase().includes(searchTerm) ||
        p.phone.includes(searchTerm)
    );
    const tbody = document.getElementById('patientsList');
    tbody.innerHTML = filtered.map(p => {
        const age = calculateAge(p.dob);
        return `<tr>
            <td>${p.id}</td>
            <td>${p.firstName} ${p.lastName}</td>
            <td>${age}</td>
            <td>${p.gender}</td>
            <td>${p.phone}</td>
            <td>${p.lastVisit ? new Date(p.lastVisit).toLocaleDateString() : 'N/A'}</td>
            <td>
                <button class="btn btn-small" onclick="viewPatient('${p.id}')">View</button>
                <button class="btn btn-small btn-danger" onclick="deletePatient('${p.id}')">Delete</button>
            </td>
        </tr>`;
    }).join('');
}

function deletePatient(id){
    if(!confirm('Are you sure you want to delete this patient?')) return;
    const patient = patients.find(p => p.id===id);
    patients = patients.filter(p => p.id!==id);
    addAuditLog('Delete', `Patient ${patient.firstName} ${patient.lastName} deleted`);
    renderPatients();
    updateDashboard();
}

// APPOINTMENTS

// Add Appointment (fetch backend)
document.getElementById('addAppointmentForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch("add_appointment.php", { method: "POST", body: formData })
        .then(res=>res.json())
        .then(data=>{
            if(data.status==="success"){
                appointments.push(data.appointment);
                renderAppointments();
                renderQueue();
                updateDashboard();
                addAuditLog('Create', `Appointment ${data.appointment.id} added`);
                alert("Appointment scheduled!");
            }
        });
});

// Render Appointments
function renderAppointments(){
    const tbody = document.getElementById('appointmentsList');
    tbody.innerHTML = appointments.map(a=>{
        const patient = patients.find(p=>p.id===a.patientId);
        return `<tr>
            <td>${new Date(a.date).toLocaleDateString()}</td>
            <td>${a.time}</td>
            <td>${patient ? patient.firstName+' '+patient.lastName : 'Unknown'}</td>
            <td>${a.doctor}</td>
            <td><span style="color:${a.status==='Completed'?'#28a745':'#667eea'}">${a.status}</span></td>
            <td>${a.queueNumber}</td>
            <td>
                <button class="btn btn-small btn-success" onclick="completeAppointment('${a.id}')">Complete</button>
                <button class="btn btn-small btn-danger" onclick="cancelAppointment('${a.id}')">Cancel</button>
            </td>
        </tr>`;
    }).join('');
}

function renderQueue(){
    const today = new Date().toISOString().split('T')[0];
    const todayAppointments = appointments.filter(a=>a.date===today && a.status==='Scheduled');
    const queueDiv = document.getElementById('queueList');
    if(todayAppointments.length===0){ queueDiv.innerHTML='<p>No patients in queue today</p>'; return; }
    queueDiv.innerHTML = todayAppointments.map(a=>{
        const patient = patients.find(p=>p.id===a.patientId);
        return `<div class="queue-item">
            <div>
                <div class="queue-number">#${a.queueNumber}</div>
                <div><strong>${patient ? patient.firstName+' '+patient.lastName : 'Unknown'}</strong></div>
                <div style="color:#6c757d;font-size:14px;">${a.time} - ${a.doctor}</div>
            </div>
            <button class="btn btn-small btn-success" onclick="completeAppointment('${a.id}')">Call In</button>
        </div>`;
    }).join('');
}

function completeAppointment(id){
    const appt = appointments.find(a=>a.id===id);
    appt.status='Completed';
    const patient = patients.find(p=>p.id===appt.patientId);
    if(patient) patient.lastVisit=new Date().toISOString();
    addAuditLog('Update', `Appointment ${id} completed`);
    renderAppointments(); renderQueue(); updateDashboard();
}

function cancelAppointment(id){
    if(!confirm('Cancel this appointment?')) return;
    appointments = appointments.filter(a=>a.id!==id);
    addAuditLog('Delete', `Appointment ${id} cancelled`);
    renderAppointments(); renderQueue(); updateDashboard();
}

// MEDICAL RECORDS

// Add Record (fetch backend)
document.getElementById('addRecordForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch("add_record.php",{method:'POST',body:formData})
        .then(res=>res.json())
        .then(data=>{
            if(data.status==='success'){
                medicalRecords.push(data.record);
                renderRecords();
                addAuditLog('Create', `Medical record ${data.record.id} added`);
                alert("Medical record added!");
            }
        });
});

// Render Records
function renderRecords(){
    const container = document.getElementById('recordsList');
    if(medicalRecords.length===0){ container.innerHTML='<p style="margin-top:20px;">No medical records found</p>'; return; }
    container.innerHTML = medicalRecords.map(r=>{
        const patient = patients.find(p=>p.id===r.patientId);
        return `<div class="patient-card">
            <h4>${r.id} - ${patient ? patient.firstName+' '+patient.lastName:'Unknown'}</h4>
            <div class="info-row"><span class="info-label">Date:</span><span>${new Date(r.createdAt).toLocaleString()}</span></div>
            <div class="info-row"><span class="info-label">Complaint:</span><span>${r.complaint}</span></div>
            <div class="info-row"><span class="info-label">Diagnosis:</span><span>${r.diagnosis}</span></div>
            <div class="info-row"><span class="info-label">Treatment:</span><span>${r.treatment}</span></div>
            <div class="info-row"><span class="info-label">Vitals:</span><span>${r.vitals||'N/A'}</span></div>
            <div class="info-row"><span class="info-label">Created By:</span><span>${r.createdBy}</span></div>
        </div>`;
    }).join('');
}

// PRESCRIPTIONS / LAB / BILLING

// Add Prescription
document.getElementById('addPrescriptionForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch("add_prescription.php",{method:'POST',body:formData})
        .then(res=>res.json())
        .then(data=>{
            if(data.status==='success'){
                prescriptions.push(data.prescription);
                renderPrescriptions();
                addAuditLog('Create', `Prescription ${data.prescription.id} added`);
                alert("Prescription added!");
            }
        });
});

function renderPrescriptions(){}

// Add Lab
document.getElementById('addLabForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch("add_lab.php",{method:'POST',body:formData})
        .then(res=>res.json())
        .then(data=>{
            if(data.status==='success'){
                labResults.push(data.lab);
                renderLabResults();
                addAuditLog('Create', `Lab result ${data.lab.id} added`);
                alert("Lab result added!");
            }
        });
});

function renderLabResults(){}

// Add Billing
document.getElementById('addBillingForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch("add_billing.php",{method:'POST',body:formData})
        .then(res=>res.json())
        .then(data=>{
            if(data.status==='success'){
                billingRecords.push(data.billing);
                renderBilling();
                addAuditLog('Create', `Billing ${data.billing.id} added`);
                alert("Billing record added!");
            }
        });
});

function renderBilling(){ /* similar to previous code, omitted for brevity */ }

// LOAD FUNCTIONS (fetch from backend)

function loadPatients(){ fetch("get_patients.php").then(r=>r.json()).then(d=>{patients=d; renderPatients(); updatePatientSelects();}); }
function loadAppointments(){ fetch("get_appointments.php").then(r=>r.json()).then(d=>{appointments=d; renderAppointments(); renderQueue();}); }
function loadRecords(){ fetch("get_records.php").then(r=>r.json()).then(d=>{medicalRecords=d; renderRecords();}); }
function loadPrescriptions(){ fetch("get_prescriptions.php").then(r=>r.json()).then(d=>{prescriptions=d; renderPrescriptions();}); }
function loadLabResults(){ fetch("get_lab.php").then(r=>r.json()).then(d=>{labResults=d; renderLabResults();}); }
function loadBilling(){ fetch("get_billing.php").then(r=>r.json()).then(d=>{billingRecords=d; renderBilling();}); }

// Call on page load
window.addEventListener('DOMContentLoaded', ()=>{
});
