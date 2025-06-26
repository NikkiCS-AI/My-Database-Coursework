let leads = [];
let currentsort=null;
let sortorder='asc';


function renderTable() {
  const tbody = document.querySelector('#leadTable tbody');
  tbody.innerHTML = '';

  leads.forEach((lead, i) => {
    const row = `<tr id="lead-${lead.Lead_id}">
      <td>${lead.Lead_id}</td>
      <td>${lead.Customer_id}</td>
      <td>${lead.status}</td>
      <td>${lead.follow_up_date}</td>
      <td>${lead.lead_notes}</td>
      <td>
        <button class="updatebutton" onclick="showForm('update', ${i})">Update</button>
        <button class="deletebutton" data-id="${lead.Lead_id}">Delete</button>
      </td>
    </tr>`;
    tbody.innerHTML += row;
  });
}


function showForm(mode, index = null) {
  document.getElementById('leadForm').style.display = 'block';
  document.getElementById('formTitle').innerText = mode === 'add' ? 'Add Lead' : 'Update Lead';

  if (mode === 'update') {
    const lead = leads[index];
    document.getElementById('editIndex').value = index;
    document.getElementById('customerid').value = lead.Customer_id;
    document.getElementById('status').value = lead.status;
    document.getElementById('followup').value = lead.follow_up_date;
    document.getElementById('notes').value = lead.lead_notes;
  } else {
    document.getElementById('leadform').reset();
    document.getElementById('editIndex').value = '';
  }
}

function hideForm() {
  document.getElementById('leadForm').style.display = 'none';
}

function deleteLead(leadId) {
  if (confirm('Are you sure you want to delete this lead?')) {
    fetch('leads.php', {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ Lead_id: leadId }).toString()
    })
    .then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
    });
  }
}

function filterLeads() {
  const query = document.getElementById('searchInput').value;
  const tbody = document.querySelector('#leadTable tbody');
  tbody.innerHTML = '';

  leads.forEach((lead, i) => {
    if (lead.Lead_id.includes(query)) {
      const row = `<tr id="lead-${lead.Lead_id}">
        <td>${lead.Lead_id}</td>
        <td>${lead.Customer_id}</td>
        <td>${lead.status}</td>
        <td>${lead.follow_up_date}</td>
        <td>${lead.lead_notes}</td>
        <td>
          <button class="updatebutton" onclick="showForm('update', ${i})">Update</button>
          <button class="deletebutton" data-id="${lead.Lead_id}">Delete</button>
        </td>
      </tr>`;
      tbody.innerHTML += row;
    }
  });
}
//sorting function
function sortTable(field){
    if (currentsort===field){
        sortorder= sortorder ==='asc'? 'desc':'asc';

    }else{
        currentsort=field;
        sortorder='asc';
    }

    leads.sort((a,b)=>{
        let valA=a[field];
        let valB=b[field];

        if (field === 'follow_up_date'){
            valA= new Date(valA);
            valB=new Date(valB);
        }

        if (valA < valB) return sortorder === 'asc' ? -1 : 1;
        if (valA > valB) return sortorder === 'asc' ? 1 : -1;
        return 0;
      });
    
      renderTable();
}

function handleForm(event) {
  event.preventDefault();

  const index = document.getElementById('editIndex').value;
  const formData = new FormData(event.target);

  const status = formData.get('status');

  if (index === '') {
    // Add new lead
    fetch('leads.php', {
      method: 'POST',
      body: new URLSearchParams({
        status: status,
        followup: formData.get('followup'),
        notes: formData.get('notes')
      }).toString(),
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    }).then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
    });
  } else {
    // Update existing lead
    const data = {
      leadId: leads[index].Lead_id,
      status: status,
      followup: formData.get('followup'),
      notes: formData.get('notes')
    };

    fetch('leads.php', {
      method: 'PUT',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(data).toString()
    }).then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
    });
  }
}


window.onload = function () {
  console.log("Loading leads...");

  document.getElementById('leadform').addEventListener('submit', handleForm);

  fetch('leads.php', {
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        leads = data.data;
        renderTable();
      } else {
        console.error('Failed to load leads:', data.message);
      }
    });

  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('deletebutton')) {
      const id = e.target.getAttribute('data-id');
      deleteLead(id);
    }
  });
};
