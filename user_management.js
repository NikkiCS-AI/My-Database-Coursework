let users = [];
let currentsort=null;
let sortorder='asc';

function renderTable() {
  const tbody = document.querySelector('#userTable tbody');
  tbody.innerHTML = '';

  users.forEach((user, i) => {
    const row = `<tr id="user-${user.user_id}">
    <td>${user.User_id}</td>
    <td>${user.Name}</td>
    <td>${user.Email}</td>
    <td>${user.Phone_no}</td>
      <td>
        <button class="updatebutton" onclick="showForm('update', ${i})">Update</button>
        <button class="deletebutton" data-id="${user.User_id}">Delete</button>
      </td>
    </tr>`;
    tbody.innerHTML += row;
  });
}

function showForm(mode, index = null) {
  document.getElementById('userForm').style.display = 'block';
  document.getElementById('formTitle').innerText = mode === 'add' ? 'Add User' : 'Update User';

  if (mode === 'update') {
    const user = users[index];
    document.getElementById('editIndex').value = index;
    document.getElementById('name').value = user.Name;
    document.getElementById('email').value = user.Email;
    document.getElementById('phone').value = user.Phone_no;
  } else {
    document.querySelector('#userForm form').reset();
    document.getElementById('editIndex').value = '';
  }
}

function hideForm() {
  document.getElementById('userForm').style.display = 'none';
}

function deleteCustomer(userId) {
  if (confirm('Are you sure you want to delete this user?')) {
    fetch('user_management.php', {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ userid: userId }).toString()
    }).then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
    });
  }
}

function filterUsers() {
  const query = document.getElementById('searchInput').value.toLowerCase();
  const tbody = document.querySelector('#userTable tbody');
  tbody.innerHTML = '';

  users.forEach((user, i) => {
    if (user.Name.toLowerCase().includes(query)) {
      const row = `<tr id="user-${user.User_id}">
        <td>${user.User_id}</td>
        <td>${user.Name}</td>
        <td>${user.Email}</td>
        <td>${user.Phone_no}</td>
        <td>
          <button class="updatebutton" onclick="showForm('update', ${i})">Update</button>
          <button class="deletebutton" data-id="${user.User_id}">Delete</button>
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

  const data = {
    userid: formData.get('userid'),
    name: formData.get('name'),
    email: formData.get('email'),
    phone: formData.get('phone'),
  };

  if (index === '') {
    // Add new user
    fetch('user_management.php', {
      method: 'POST',
      body: formData
    }).then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
    });
  } else {
    // Update existing
    data.userid = users[index].User_id;

    fetch('user_management.php', {
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
  console.log("Fetching users...");

  document.querySelector('#userform').addEventListener('submit', handleForm);

  fetch('user_management.php')
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        users = data.data;
        renderTable();
      } else {
        console.error('Failed to load users:', data.message);
      }
    });

  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('deletebutton')) {
      const id = e.target.getAttribute('data-id');
      deleteCustomer(id);
    }
  });
};
