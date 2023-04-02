// Get today's date in YYYY-MM-DD format
var today = new Date().toISOString().split('T')[0];
var pickUpDate;

// Set the minimum date to today's date for the pick-up date input field
document.getElementById("pickupdate").setAttribute("min", today);

// Add event listener to check for changes in the pick-up date input field
document.getElementById("pickupdate").addEventListener("change", function () {
    // Get the value of the pick-up date input field
    pickUpDate = this.value;

    // Check if the selected date is before today's date
    if (pickUpDate < today) {
        alert("Pick-up date must only be today or future dates only.");
        this.value = "";
    }
});


function validateDates() {
    const date1 = new Date(document.getElementById("pickupdate").value);
    const date2 = new Date(document.getElementById("dropoffdate").value);
  
    var today = new Date().toISOString().split('T')[0];

    // Check if date1 is today or a future date
    if (date1 < today) {
      alert("Pick up date must be today or a future date.");
      return false;
    }
  
    // Check if date2 is greater than date1
    if (date2 < date1) {
      alert("Drop off date must be greater than pick up date.");
      return false;
    }
  
    // Dates are valid
    return true;
  }