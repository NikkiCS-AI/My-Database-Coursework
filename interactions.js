let interactions = [];
let currentsort=null;
let sortorder='asc';


function renderTable() {
  const tbody = document.querySelector('#interactionTable tbody');
  tbody.innerHTML = '';

  interactions.forEach((interaction, i) => {
    const row = `<tr id="interaction-${interaction.Interaction_id}">
      <td>${interaction.Interaction_id}</td>
      <td>${interaction.Customer_id}</td>
      <td>${interaction.name}</td>
      <td>${interaction.date}</td>
      <td>${interaction.type}</td>
      <td>${interaction.description}</td>
      <td>
        <button class="updatebutton" onclick="showForm('update', ${i})">Update</button>
        <button class="deletebutton" data-id="${interaction.Interaction_id}">Delete</button>
      </td>
    </tr>`;
    tbody.innerHTML += row;
  });
}

function showForm(mode, index = null) {
  document.getElementById('interactionFormWrapper').style.display = 'block';
  document.getElementById('formTitle').innerText = mode === 'add' ? 'Add Interaction' : 'Update Interaction';

  if (mode === 'update') {
    const interaction = interactions[index];
    document.getElementById('editIndex').value = index;
    document.getElementById('customer-id').value = interaction.Customer_id;
    document.getElementById('customer-id').readOnly = true; 
    document.getElementById('interaction-type').value = interaction.type;
    document.getElementById('interaction-date').value = interaction.date;
    document.getElementById('description').value = interaction.description;

  } else {
    document.getElementById('interactionForm').reset();
    document.getElementById('editIndex').value = '';
    document.getElementById('customer-id').readOnly = false;
  }
}

function hideForm() {
  document.getElementById('interactionFormWrapper').style.display = 'none';
}
function filterinteractions() {
  const query = document.getElementById('searchInput').value;
  const tbody = document.querySelector('#interactionTable tbody');
  tbody.innerHTML = '';

  interactions.forEach((interaction, i) => {
    if (interaction.name.toLowerCase().includes(query)) {
      const row = `<tr id="interaction-${interaction.Interaction_id}">
        <td>${interaction.Interaction_id}</td>
        <td>${interaction.Customer_id}</td>
        <td>${interaction.name}</td>
        <td>${interaction.date}</td>
        <td>${interaction.type}</td>
        <td>${interaction.description}</td>
        <td>
          <button class="updatebutton" onclick="showForm('update', ${i})">Update</button>
          <button class="deletebutton" data-id="${interaction.Interaction_id}">Delete</button>
        </td>
      </tr>`;
      tbody.innerHTML += row;
    }
  });
}
function deleteInteraction(interactionId) {
  if (confirm('Are you sure you want to delete this interaction?')) {
    fetch('interactions.php', {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id: interactionId }).toString()
    })
    .then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
    });
  }
}


function handleForm(event) {
  event.preventDefault();

  const index = document.getElementById('editIndex').value;
  const formData = new FormData(event.target);

  
  const data = {
    customerid: formData.get('customer-id'),
    type: formData.get('type'),
    date: formData.get('date'),
    description: formData.get('description')

  };

  if (index === '') {
    // Add new interaction
    fetch('interactions.php', {
      method: 'POST',
      body: formData
    }).then(res => res.json()).then(response => {
      alert(response.message);
      location.reload();
      
    });
  } else {

    // Update existing interaction
    const data = {
      id: interactions[index].Interaction_id,
      customerid: formData.get('customer-id'),
      type: formData.get('type'),
      date: formData.get('date'),
      description: formData.get('description')
    };

    fetch('interactions.php', {
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
  console.log("Loading interactions...");

  document.getElementById('interactionForm').addEventListener('submit', handleForm);

  fetch('interactions.php', {
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        interactions = data.data;
        renderTable();
      } else {
        console.error('Failed to load interactions:', data.message);
      }
    });

  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('deletebutton')) {
      const id = e.target.getAttribute('data-id');
      deleteInteraction(id);
    }
  });
};
