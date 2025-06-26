const menuButton = document.getElementById("menubutton");
const optionsBox = document.getElementById("optionsbox");

menuButton.addEventListener("click", () => {
  optionsBox.classList.toggle("hidden");
});

// Reminder Count Fetcher
window.addEventListener('load', function() {
  fetch('reminder_count.php')
    .then(response => response.text())
    .then(count => {
      const reminderCount = document.getElementById('reminderCount');
      if (parseInt(count) > 0) {
        reminderCount.textContent = count;
        reminderCount.style.display = 'inline-block';
      } else {
        reminderCount.style.display = 'none';
      }
    })
    .catch(error => console.error('Error fetching reminder count:', error));
});
