function validateForm() {
    // Get the values of the password and confirm password fields
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;
    
    // Check if the passwords match
    if (password != confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }
    
    // Check if the password is at least 8 characters long and contains both letters and numbers
    var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
    if (!passwordRegex.test(password)) {
        alert("Password must be at least 8 characters long and contain both letters and numbers.");
        return false;
    }
    
    return true;
}