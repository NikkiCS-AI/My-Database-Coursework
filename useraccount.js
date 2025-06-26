window.addEventListener("DOMContentLoaded", () => {
    fetch("useraccount.php")
      .then(res => res.json())
      .then(data => {
        console.log(data);

        if (data.error) {
          alert("Not logged in!");
            window.location.href = "login.html";
        } else {
            document.getElementById("name").textContent = data.Name;
            document.getElementById("userId").textContent = data.User_id;
            document.getElementById("email").value = data.Email;
            document.getElementById("phone").value = data.Phone_no;
            document.getElementById("password").value = data.Password;

            if(data.Password ==="000"){
                document.getElementById("passwordMessage").textContent="Please set your password.";
            }
            else{
                document.getElementById("passwordMessage").textContent="";
            }
        
        }
      });
  
    document.getElementById("updateForm").addEventListener("submit", e => {
      e.preventDefault();
      const formData = new FormData(e.target);
  
      fetch("useraccount.php", {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(result => {
        document.getElementById("message").textContent = result.message;
      });
    });
  });
  