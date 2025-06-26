let customers = [];
let currentsort=null;
let sortorder='asc';

function renderTable() {
  const tbody = document.querySelector('#customerTable tbody');
  tbody.innerHTML = '';

  customers.forEach((cust, i) => {
    const row = `<tr id="customer-${cust.User_id}">
      <td>${cust.User_id}</td>
      <td>${cust.customer_id}</td>
      <td>${cust.Name}</td>
      <td>${cust.Company}</td>
      <td>${cust.Email}</td>
      <td>${cust.Phone_no}</td>
      <td>${cust.Address}</td>
      <td>
        <button class="updatebutton" onclick="showForm('update', ${i})">Update</button>
        <button class="deletebutton" data-id="${cust.customer_id}">Delete</button>
      </td>
    </tr>`;
    tbody.innerHTML += row;
  });
}

function showForm(mode, index = null) {
  document.getElementById('customerForm').style.display = 'block';
  document.getElementById('formTitle').innerText = mode === 'add' ? 'Add Customer' : 'Update Customer';

  if (mode === 'update') {
    const customer = customers[index];
    document.getElementById('editIndex').value = index;
    document.getElementById('userid').value = customer.User_id;
    document.getElementById('name').value = customer.Name;
    document.getElementById('company').value = customer.Company;
    document.getElementById('email').value = customer.Email;
    document.getElementById('phone').value = customer.Phone_no;
    document.getElementById('address').value = customer.Address;
  } else {
    document.querySelector('#customerForm form').reset();
    document.getElementById('editIndex').value = '';
  }
}

function hideForm() {
  document.getElementById('customerForm').style.display = 'none';
}

function deleteCustomer(customerId) {
  if (confirm('Are you sure you want to delete this customer?')) {
    fetch('admin_customers.php', {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id: customerId }).toString()
    }).then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
    });
  }
}

function filterCustomers() {
  const query = document.getElementById('searchInput').value.toLowerCase();
  const tbody = document.querySelector('#customerTable tbody');
  tbody.innerHTML = '';

  customers.forEach((cust, i) => {
    if (cust.Name.toLowerCase().includes(query)) {
      const row = `<tr id="customer-${cust.User_id}">
        <td>${cust.User_id}</td>
        <td>${cust.customer_id}</td>  
        <td>${cust.Name}</td>
        <td>${cust.Company}</td>
        <td>${cust.Email}</td>
        <td>${cust.Phone_no}</td>
        <td>${cust.Address}</td>
        <td>
        <button class="updatebutton" onclick="showForm('update', ${i})">Update</button>
          <button class="deletebutton" data-id="${cust.customer_id}">Delete</button>
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

  customers.sort((a,b)=>{
      let valA=a[field];
      let valB=b[field];


      if (valA < valB) return sortorder === 'asc' ? -1 : 1;
      if (valA > valB) return sortorder === 'asc' ? 1 : -1;
      return 0;
    });
}

function handleForm(event) {
  event.preventDefault();

  const index = document.getElementById('editIndex').value;
  const formData = new FormData(event.target);

  const data = {
    userid: formData.get('userid'),
    name: formData.get('name'),
    company: formData.get('company'),
    email: formData.get('email'),
    phone: formData.get('phone'),
    address: formData.get('address')
  };

  if (index === '') {
    // Add new customer
    fetch('admin_customers.php', {
      method: 'POST',
      body: formData
    }).then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
    });
  } else {
    // Update existing customer
    data.id = customers[index].customer_id;

    fetch('admin_customers.php', {
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
  console.log("Fetching customers...");

  document.querySelector('#customerFormElement').addEventListener('submit', handleForm);

  fetch('admin_customers.php')
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
