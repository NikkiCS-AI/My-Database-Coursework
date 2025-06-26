let customers = [];
let currentSort = null;
let sortOrder = 'asc';

// Render the customer table
function renderTable() {
  const tbody = document.querySelector('#customerTable tbody');
  tbody.innerHTML = '';

  customers.forEach((cust, i) => {
    const row = `<tr id="customer-${cust.customer_id}">
      <td>${cust.customer_id}</td>
      <td>${cust.Name}</td>
      <td>${cust.Company}</td>
      <td>${cust.Email}</td>
      <td>${cust.Phone_no}</td>
      <td>${cust.Address}</td>
      <td>${cust.interaction || 'No history'}</td>
      <td>
        <button class="updatebutton" onclick="showForm('update', ${i})">Update</button>
        <button class="deletebutton" data-id="${cust.customer_id}">Delete</button>
      </td>
    </tr>`;
    tbody.innerHTML += row;
  });
}

//Fetching leads to be added as customers in drop down
function fetchLeads() {
  fetch('customers.php?fetchLeads=1')
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        const leadSelect = document.getElementById('lead_id');
        leadSelect.innerHTML = '<option value="">None</option>';

        data.leads.forEach(lead => {
          const option = document.createElement('option');
          option.value = lead.Lead_id;
          option.textContent = `Lead ID: ${lead.Lead_id}`;
          leadSelect.appendChild(option);
        });
      } else {
        console.error('Failed to fetch leads:', data.message);
      }
    });
}

function showForm(mode, index = null) {
  document.getElementById('customerForm').style.display = 'block';
  document.getElementById('formTitle').innerText = mode === 'add' ? 'Add Customer' : 'Update Customer';

  if (mode === 'update') {
    const customer = customers[index];
    document.getElementById('editIndex').value = index;
    document.getElementById('name').value = customer.Name;
    document.getElementById('company').value = customer.Company;
    document.getElementById('email').value = customer.Email;
    document.getElementById('phone').value = customer.Phone_no;
    document.getElementById('address').value = customer.Address;
    document.getElementById('lead_id').value = customer.Lead_id || ''; // pre-fill
  } else {
    document.getElementById('customerFormElement').reset();
    document.getElementById('editIndex').value = '';
    fetchLeads(); 
  }
}


// Hide the form
function hideForm() {
  document.getElementById('customerForm').style.display = 'none';
}


function deleteCustomer(customerId) {
  if (confirm('Are you sure you want to delete this customer?')) {
    fetch('customers.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        deleteCustomer: true,
        Customer_id: customerId
      }).toString()
    })
    .then(res => res.json())
    .then(response => {
      alert(response.message);
      location.reload();
    })
    .catch(error => {
      console.error('Error deleting customer:', error);
      alert('Failed to delete customer. Please try again.');
    });
  }
}


function filterCustomers() {
  const query = document.getElementById('searchInput').value.toLowerCase();
  const tbody = document.querySelector('#customerTable tbody');
  tbody.innerHTML = '';

  customers.forEach((cust, i) => {
    if (cust.Name.toLowerCase().includes(query)) {
      const row = `<tr id="customer-${cust.customer_id}">
        <td>${cust.customer_id}</td>
        <td>${cust.Name}</td>
        <td>${cust.Company}</td>
        <td>${cust.Email}</td>
        <td>${cust.Phone_no}</td>
        <td>${cust.Address}</td>
        <td>${cust.interaction || 'No history'}</td>
        <td>
          <button class="updatebutton" onclick="showForm('update', ${i})">Update</button>
          <button class="deletebutton" data-id="${cust.customer_id}">Delete</button>
        </td>
      </tr>`;
      tbody.innerHTML += row;
    }
  });
}

// Sort the customer table
function sortTable(field) {
  if (currentSort === field) {
    sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
  } else {
    currentSort = field;
    sortOrder = 'asc';
  }

  customers.sort((a, b) => {
    let valA = a[field];
    let valB = b[field];

    // If numbers (customer_id, phone), compare numerically
    if (field === 'customer_id' || field === 'Phone_no') {
      valA = Number(valA);
      valB = Number(valB);
    } else {
      // Otherwise, compare as lowercase strings
      valA = String(valA).toLowerCase();
      valB = String(valB).toLowerCase();
  }


    if (valA < valB) return sortOrder === 'asc' ? -1 : 1;
    if (valA > valB) return sortOrder === 'asc' ? 1 : -1;
    return 0;
  });

  renderTable();
}

// Handle form submission for adding or updating
function handleForm(event) {
  event.preventDefault();

  const index = document.getElementById('editIndex').value;
  const formData = new FormData(event.target);

  const data = {
    name: formData.get('name'),
    company: formData.get('company'),
    email: formData.get('email'),
    phone: formData.get('phone'),
    address: formData.get('address')
  };

  if (index === '') {
    // Add new customer
    fetch('customers.php', {
      method: 'POST',
      body: formData
    }).then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
    });
  } else {
    // Update existing customer
    data.id = customers[index].customer_id;

    fetch('customers.php', {
      method: 'PUT',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(data).toString()
    }).then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
    });
  }
}

// Main startup
window.onload = function () {
  console.log("Fetching customers...");

  document.getElementById('customerFormElement').addEventListener('submit', handleForm);

  fetch('customers.php')
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        customers = data.data;
        renderTable();
      } else {
        console.error('Failed to load customers:', data.message);
      }
    });

  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('deletebutton')) {
      const id = e.target.getAttribute('data-id');
      deleteCustomer(id);
    }
  });
};
