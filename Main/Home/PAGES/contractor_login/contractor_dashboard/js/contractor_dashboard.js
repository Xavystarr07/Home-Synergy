document.addEventListener("DOMContentLoaded", function () {
    // Show the default active section
    const activeSection = document.querySelector('.section.active');
    if (activeSection) activeSection.style.display = 'block';

    // Click event listener for navigation links
    const links = document.querySelectorAll('.sidebar a');
    links.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const sectionId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
            const currentSection = document.querySelector('.section.active');
            const nextSection = document.getElementById(sectionId);

            if (currentSection !== nextSection) {
                currentSection.classList.remove('active');
                currentSection.classList.add('fade-out');

                setTimeout(() => {
                    currentSection.style.display = 'none';
                    currentSection.classList.remove('fade-out');

                    nextSection.style.display = 'block';
                    nextSection.classList.add('active');
                }, 500);
            }
        });
    });

    // Function to fade out and remove job card
    function fadeOutAndRemove(jobCard) {
        console.log(`Fading out job card: ${jobCard.id}`); // Debugging log
        jobCard.classList.add('fade-out'); // Add fade-out class to initiate fade
        setTimeout(() => {
            jobCard.remove(); // Remove job card from DOM after fade-out
        }, 500); // Match fade-out duration (500ms)
    }

    // Function to handle job status update through AJAX
    function handleJobStatus(event, jobId, status) {
        event.preventDefault(); // Prevent the form from submitting normally
        console.log(`Sending status update for jobId: ${jobId} to ${status}`); // Debugging log

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_job_status.php", true); // Update with actual backend endpoint
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log("Status updated: " + xhr.responseText); // Check response from server
                } else {
                    console.error("Error updating status: " + xhr.status); // Log any error
                }
            }
        };

        xhr.send(`id=${jobId}&status=${status}`); // Send job ID and new status
    }

    // Accept job function with confirmation prompt
    window.acceptJob = function (jobId) {
        const confirmation = confirm("Are you sure you want to accept this job?");
        if (confirmation) {
            console.log(`Accepted job: ${jobId}`); // Debugging log
            const jobCard = document.getElementById(`job_${jobId}`);
            if (jobCard) {
                fadeOutAndRemove(jobCard); // Fade out the job card
                handleJobStatus(event, jobId, 'accepted'); // Update status to accepted via AJAX
            } else {
                console.error(`Job card not found for jobId: ${jobId}`); // Debugging log
            }
        }
    };

    // Decline job function with confirmation prompt
    window.declineJob = function (jobId) {
        const confirmation = confirm("Are you sure you want to decline this job?");
        if (confirmation) {
            console.log(`Declined job: ${jobId}`); // Debugging log
            const jobCard = document.getElementById(`job_${jobId}`);
            if (jobCard) {
                fadeOutAndRemove(jobCard); // Fade out the job card
                handleJobStatus(event, jobId, 'rejected'); // Update status to rejected via AJAX
            } else {
                console.error(`Job card not found for jobId: ${jobId}`); // Debugging log
            }
        }
    };
});

// Logout function
function handleLogout() {
    window.location.href = '/Home_Synergy_Code/Main/Home/PAGES/contractor_login/contractor_dashboard/contractor_logout.php'; // Redirect to logout script
}

// Update password check with regex
function validatePassword() {
    var password = document.getElementById("password").value;
    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/; // At least 1 lowercase, 1 uppercase, 1 symbol, and minimum 8 characters

    // Test if the password matches the regex
    if (!passwordRegex.test(password)) {
        alert("Password must be at least 8 characters long, 1 lowercase letter, 1 uppercase letter, and 1 symbol.");
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}

document.addEventListener("DOMContentLoaded", function () {
    const notificationsList = document.getElementById('notifications-list');
    const notificationMessage = document.getElementById('notification-message');

    // Check if there is an appointment
    const hasAppointment = true; // Replace with actual logic to check for appointments

    if (hasAppointment) {
        notificationMessage.textContent = "Appointment has been confirmed, please check your appointments.";
    } else {
        notificationMessage.textContent = "No notifications available.";
    }
});
