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
      <td>${lead.User_id}</td>
      <td>${lead.Name}</td>
      <td>${lead.status}</td>
      <td>${lead.follow_up_date}</td>
      <td>${lead.lead_notes}</td>
    </tr>`;
    tbody.innerHTML += row;
  });
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
        <td>${lead.User_id}</td>
        <td>${lead.Name}</td>
        <td>${lead.status}</td>
        <td>${lead.follow_up_date}</td>
        <td>${lead.lead_notes}</td>
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


window.onload = function () {
  console.log("Loading leads...");
  fetch('admin_leads.php', {
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
};
