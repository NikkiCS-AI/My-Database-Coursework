
document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const hasError = urlParams.get("error") === "1";

  if (hasError) {
    const errorDiv = document.getElementById("error-message");
    if (errorDiv) {
      errorDiv.textContent = " Invalid email, password or role.";
      errorDiv.style.display = "block";
    }
  }
});
