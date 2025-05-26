document.addEventListener('DOMContentLoaded', function () {
    fetchNotifications();

    const serviceCards = document.querySelectorAll(".service-card");
    const serviceSection = document.querySelector(".service-section");
    const contractorSection = document.getElementById("contractor-section");
    const appointmentSection = document.getElementById("appointment-section");
    const profileDetails = document.getElementById("profile-details");
    const bookingsSection = document.getElementById("bookings-section");
    const notificationsSection = document.getElementById("notifications-section");

    // Event listener for each service card
    serviceCards.forEach(card => {
        card.addEventListener("click", () => {
            const serviceType = card.getAttribute('data-service-type');
            fadeOut(serviceSection, function () {
                showContractors(serviceType); // Fetch contractors based on the service type
                fadeIn(contractorSection);
            });
        });
    });

    // Back button to return to service section
document.getElementById('back-to-services-btn').addEventListener("click", () => {
    // Fade out contractor and appointment sections
    contractorSection.style.opacity = 1;
    appointmentSection.style.opacity = 1;

    const fadeOutInterval1 = setInterval(() => {
        contractorSection.style.opacity -= 0.1;
        appointmentSection.style.opacity -= 0.1;

        if (contractorSection.style.opacity <= 0 && appointmentSection.style.opacity <= 0) {
            clearInterval(fadeOutInterval1);
            contractorSection.style.display = 'none';
            appointmentSection.style.display = 'none';
            fadeIn(serviceSection);
        }
    }, 50);
});

// Back button to return to contractor section from appointment
document.getElementById('back-to-contractors-btn').addEventListener("click", () => {
    // Fade out appointment section
    appointmentSection.style.opacity = 1;

    const fadeOutInterval2 = setInterval(() => {
        appointmentSection.style.opacity -= 0.1;

        if (appointmentSection.style.opacity <= 0) {
            clearInterval(fadeOutInterval2);
            appointmentSection.style.display = 'none';
            fadeIn(contractorSection);
        }
    }, 50);
});

// Transition to profile details section
document.getElementById('profile-btn').addEventListener("click", () => {
    // Fade out service, appointment, and contractor sections
	profileDetails.style.opacity = 1;
    serviceSection.style.opacity = 1;
    appointmentSection.style.opacity = 1;
    contractorSection.style.opacity = 1;

    const fadeOutInterval3 = setInterval(() => {
		profileDetails.style.opacity -= 0.1;
        serviceSection.style.opacity -= 0.1;
        appointmentSection.style.opacity -= 0.1;
        contractorSection.style.opacity -= 0.1;

        if (serviceSection.style.opacity <= 0 && appointmentSection.style.opacity <= 0 && contractorSection.style.opacity <= 0) {
            clearInterval(fadeOutInterval3);
			profileDetails.style.display ='none';
            serviceSection.style.display = 'none';
			notificationsSection.style.display = 'none';
			bookingsSection.style.display = 'none';
            appointmentSection.style.display = 'none';
            contractorSection.style.display = 'none';
            fadeIn(profileDetails);
        }
    }, 50);
});

// Transition to bookings section
document.getElementById('bookings-btn').addEventListener("click", () => {
    // Fade out service, contractor, appointment, profile, and notifications sections
    serviceSection.style.opacity = 1;
    contractorSection.style.opacity = 1;
    appointmentSection.style.opacity = 1;
    profileDetails.style.opacity = 1;
    notificationsSection.style.opacity = 1;

    const fadeOutInterval4 = setInterval(() => {
        serviceSection.style.opacity -= 0.1;
        contractorSection.style.opacity -= 0.1;
        appointmentSection.style.opacity -= 0.1;
        profileDetails.style.opacity -= 0.1;
        notificationsSection.style.opacity -= 0.1;

        if (serviceSection.style.opacity <= 0 && contractorSection.style.opacity <= 0 &&
            appointmentSection.style.opacity <= 0 && profileDetails.style.opacity <= 0 &&
            notificationsSection.style.opacity <= 0) {
            clearInterval(fadeOutInterval4);
            serviceSection.style.display = 'none';
            contractorSection.style.display = 'none';
            appointmentSection.style.display = 'none';
            profileDetails.style.display = 'none';
            notificationsSection.style.display = 'none';
            fadeIn(bookingsSection);
        }
    }, 50);
});


// Transition to notifications section
document.getElementById('notifications-btn').addEventListener("click", () => {
    // Fade out service, contractor, appointment, profile, and bookings sections
    serviceSection.style.opacity = 1;
    contractorSection.style.opacity = 1;
    appointmentSection.style.opacity = 1;
    profileDetails.style.opacity = 1;
    bookingsSection.style.opacity = 1;

    const fadeOutInterval5 = setInterval(() => {
        serviceSection.style.opacity -= 0.1;
        contractorSection.style.opacity -= 0.1;
        appointmentSection.style.opacity -= 0.1;
        profileDetails.style.opacity -= 0.1;
        bookingsSection.style.opacity -= 0.1;

        if (serviceSection.style.opacity <= 0 && contractorSection.style.opacity <= 0 &&
            appointmentSection.style.opacity <= 0 && profileDetails.style.opacity <= 0 &&
            bookingsSection.style.opacity <= 0) {
            clearInterval(fadeOutInterval5);
            serviceSection.style.display = 'none';
            contractorSection.style.display = 'none';
            appointmentSection.style.display = 'none';
            profileDetails.style.display = 'none';
            bookingsSection.style.display = 'none';
            fadeIn(notificationsSection);
        }
    }, 50);
});

// Back to dashboard from profile
document.getElementById('back-to-dashboard-btn').addEventListener("click", () => {
    // Fade out profile details section
    profileDetails.style.opacity = 1;

    const fadeOutInterval6 = setInterval(() => {
        profileDetails.style.opacity -= 0.1;

        if (profileDetails.style.opacity <= 0) {
            clearInterval(fadeOutInterval6);
            profileDetails.style.display = 'none';
            fadeIn(serviceSection);
        }
    }, 50);
});

// Back to dashboard from bookings
document.getElementById('back-to-dashboard-btn-bookings').addEventListener("click", () => {
    // Fade out bookings section
    bookingsSection.style.opacity = 1;

    const fadeOutInterval7 = setInterval(() => {
        bookingsSection.style.opacity -= 0.1;

        if (bookingsSection.style.opacity <= 0) {
            clearInterval(fadeOutInterval7);
            bookingsSection.style.display = 'none';
            fadeIn(serviceSection);
        }
    }, 50);
});


function showContractors(serviceType) {
    const contractorTitle = document.getElementById('contractor-title');
    const contractorContainer = document.getElementById('contractor-container');

    // Clear previous classes and apply new one
    contractorContainer.className = 'contractor-grid'; // Reset class first

    // Update the title based on the selected service
    if (serviceType === "Plumbing") {
        contractorTitle.textContent = "Select a Plumber";
        contractorContainer.classList.add('contractor-grid-plumbing'); // Add plumbing class
    } else if (serviceType === "Electrical") {
        contractorTitle.textContent = "Select an Electrician";
        contractorContainer.classList.add('contractor-grid-electrical'); // Add electrical class
    } else if (serviceType === "Carpentry") {
        contractorTitle.textContent = "Select a Carpenter";
        contractorContainer.classList.add('contractor-grid-carpentry'); // Add carpentry class
    } else if (serviceType === "Painting") {
        contractorTitle.textContent = "Select a Painter";
        contractorContainer.classList.add('contractor-grid-painting'); // Add painting class
    } else {
        contractorTitle.textContent = "Select a Contractor"; // Default case for no matching service
    }

    fetch(`fetch_contractors.php?serviceType=${serviceType}`)
        .then(response => response.json())
        .then(data => {
            contractorContainer.innerHTML = ''; // Clear existing contractor cards

            if (data.length === 0) {
                const message = document.createElement('p');
                message.textContent = 'No contractors available for this service';
                contractorContainer.appendChild(message);
                return; // Exit the function early
            }

            // Populate the contractor grid with contractor details
            data.forEach(contractor => {
                const contractorDiv = document.createElement('div');
                contractorDiv.classList.add('contractor-card'); // Ensure this class is applied

                let profilePic = contractor.profile_picture 
                    ? contractor.profile_picture 
                    : 'http://localhost/default_profile.png'; // Default profile picture URL

                contractorDiv.innerHTML = `
    <img src="${profilePic}" alt="Profile Picture" class="contractor-profile-pic">
    <h3>${contractor.contractor_name} ${contractor.contractor_surname}</h3>
    <p>Profession: ${contractor.contractor_profession}</p>
    <p>Contractor ID: ${contractor.contractor_id}</p> <!-- Displaying Contractor ID -->
    <p>Rating: ${contractor.contractor_rating} ⭐</p>
    <p>Experience: ${contractor.contractor_experience} years</p>
`;

// Add an event listener for selecting a contractor
contractorDiv.addEventListener('click', () => {
    selectContractor(contractor.contractor_id, contractor.contractor_name, contractor.contractor_surname, contractor.contractor_profession); // Pass contractor ID and profession too
});
                contractorContainer.appendChild(contractorDiv); // Add the card to the container
            });
        })
        .catch(error => console.error('Error fetching contractors:', error));
}

// Function to select contractor
function selectContractor(contractor_id, contractor_name, contractor_surname, contractor_profession) {
    const appointmentSection = document.getElementById("appointment-section");

    // Store the contractor details for later use
    window.contractor_id = contractor_id; 
    window.contractor_name = contractor_name;
    window.contractor_surname = contractor_surname;
    window.contractor_profession = contractor_profession;

    fadeOut(contractorSection, function () {
        fadeIn(appointmentSection);
    });
}

// Event listener for appointment form submission
// Event listener for appointment form submission
document.getElementById('appointment-form').addEventListener('submit', function (event) {
    event.preventDefault();

    // Check if contractor details are set
    if (!window.contractor_id || !window.contractor_name || !window.contractor_surname) {
        alert("Please select a contractor first.");
        return;
    }

    const formData = new FormData(this);
    formData.append('contractor_id', window.contractor_id);
    formData.append('contractor_name', window.contractor_name);
    formData.append('contractor_surname', window.contractor_surname);
    formData.append('contractor_profession', window.contractor_profession);

    fetch('submit_appointments.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.status === "success") {
            alert(data.message);
            displayAppointmentDetails(formData);
            fadeOut(appointmentSection, function () {
                fadeIn(bookingsSection);  // Transition smoothly to bookings section
            });
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error submitting appointment:', error);
        alert("There was an error submitting your request.");
    });
});


function displayAppointmentDetails(formData) {
    const bookingsSection = document.getElementById("bookings-section");

    // Clear previous bookings before adding new appointment
    bookingsSection.innerHTML = '';

    // Create appointment details
    const appointment = {
        id: Date.now(),
        user_id: formData.get('user_id'),  // This gets user_id from the form data
        location: formData.get('location'),
        contractor_name: formData.get('contractor_name'),
        contractor_surname: formData.get('contractor_surname'),
        contractor_profession: formData.get('contractor_profession') || 'Profession',
        appointment_date: formData.get('appointment-date'),
        appointment_time: formData.get('appointment-time'),
        details: formData.get('details') || 'No additional details provided',
        status: 'pending' // Default status
    };

    // Check if user_id exists and display it
    console.log("User ID:", appointment.user_id);  // Debugging step

    const statusClass = appointment.status === 'accepted' ? 'accepted-status' :
                        appointment.status === 'pending' ? 'pending-status' :
                        'rejected-status';

    const appointmentDetails = `
        <div class="appointment-box">
            <h3>New appointment created:</h3>
            <p><strong>Appointment Number:</strong> ${appointment.id}</p>
            <p><strong>User ID:</strong> ${appointment.user_id || 'N/A'}</p>
            <p><strong>Location:</strong> ${appointment.location}</p>
            <p><strong>Contractor:</strong> ${appointment.contractor_name} ${appointment.contractor_surname}</p>
            <p><strong>Service:</strong> ${appointment.contractor_profession}</p>
            <p><strong>Date:</strong> ${appointment.appointment_date}</p>
            <p><strong>Time:</strong> ${appointment.appointment_time}</p>
            <p><strong>Details:</strong> ${appointment.details}</p>
            <p class="${statusClass}">
                <strong>Status:</strong> ${appointment.status}
            </p>
        </div>
    `;

    // Insert new appointment details at the top of the bookings section
    bookingsSection.insertAdjacentHTML('afterbegin', appointmentDetails);
    bookingsSection.style.display = 'block'; // Make sure the bookings section is visible
}



// Fade out effect
function fadeOut(element, callback) {
    element.style.opacity = 1;
    const fadeOutInterval = setInterval(() => {
        element.style.opacity -= 0.1;
        if (element.style.opacity <= 0) {
            clearInterval(fadeOutInterval);
            element.style.display = 'none';
            if (callback) callback();
        }
    }, 50);
}

// Fade in effect
function fadeIn(element) {
    element.style.display = 'block';
    element.style.opacity = 0;
    const fadeInInterval = setInterval(() => {
        element.style.opacity = parseFloat(element.style.opacity) + 0.1;
        if (element.style.opacity >= 1) {
            clearInterval(fadeInInterval);
        }
    }, 50);
}


function updateAppointmentsList() {
    var appointmentsList = document.getElementById('booking-details');
    
    // Clear current appointments
    appointmentsList.innerHTML = '';

    // Sort appointments by date (assuming appointment_date is in 'YYYY-MM-DD' format)
    appointments.sort((a, b) => new Date(b.appointment_date) - new Date(a.appointment_date));

    // Loop through each appointment and create HTML content
    appointments.forEach(function(appointment) {
        var appointmentDiv = document.createElement('div');
        appointmentDiv.className = 'appointment-box'; // Use appointment-box class
        
        // Determine status class based on the appointment status
        var statusClass = '';
        if (appointment.status === 'accepted') {
            statusClass = 'accepted-status';
        } else if (appointment.status === 'pending') {
            statusClass = 'pending-status';
        } else if (appointment.status === 'rejected') {
            statusClass = 'rejected-status';
        }

        // Populate the appointment div with information
        appointmentDiv.innerHTML = `
            <p><strong>Appointment Number:</strong> ${appointment.id}</p>
            <p><strong>User ID:</strong> ${appointment.user_id}</p>
            <p><strong>Location:</strong> ${appointment.location}</p>
            <p><strong>Contractor:</strong> ${appointment.contractor_name} ${appointment.contractor_surname}</p>
            <p><strong>Service:</strong> ${appointment.contractor_profession}</p>
            <p><strong>Date:</strong> ${appointment.appointment_date}</p>
            <p><strong>Time:</strong> ${appointment.appointment_time}</p>
            <p><strong>Details:</strong> ${appointment.details}</p>
            <p class="${statusClass}"><strong>Status:</strong> ${appointment.status}</p>
        `;
        
        // Append the appointment div to the list
        appointmentsList.appendChild(appointmentDiv);
    });
}

// Call the function when new appointments are created
updateAppointmentsList();



function notifications() {
    var notificationsList = document.getElementById('notifications-section');

    // Clear current notifications
    notificationsList.innerHTML = '';

    // Add a heading and informational text
    notificationsList.innerHTML += `
        <h2>Notification</h2>
        <p>Your appointment details have been sent to the contractor.</p>
    `;

    // Filter and sort appointments to show only accepted ones
    var acceptedAppointments = appointments.filter(appointment => appointment.status === 'accepted');
    acceptedAppointments.sort((a, b) => new Date(b.appointment_date) - new Date(a.appointment_date));

    acceptedAppointments.forEach(function(appointment) {
        var appointmentDiv = document.createElement('div');
        appointmentDiv.className = 'appointment-box';

        // Check if contractor details are available and display the appointment details
        appointmentDiv.innerHTML = `
            <p><strong>Appointment Number:</strong> ${appointment.id}</p>
            <p><strong>User ID:</strong> ${appointment.user_id}</p>
            <p><strong>Location:</strong> ${appointment.location}</p>
            <p><strong>Contractor:</strong> ${appointment.contractor_name} ${appointment.contractor_surname}</p>
            <p><strong>Service:</strong> ${appointment.contractor_profession}</p>
            <p><strong>Date:</strong> ${appointment.appointment_date}</p>
            <p><strong>Time:</strong> ${appointment.appointment_time}</p>
            <p><strong>Details:</strong> ${appointment.details}</p>
            <p><strong>Payment Status:</strong> ${appointment.payment_status === 'paid' ? 'Paid' : 'Unpaid'}</p>
            ${appointment.payment_status === 'unpaid' 
                ? '<a href="Payment/PaymentWindow.php" class="payment-button">Proceed with payment</a>' 
                : '<a href="#" class="payment-button">Paid</a>'}
        `;

        // Append the appointment div to the notifications list
        notificationsList.appendChild(appointmentDiv);
    });
}

// Call the function to update notifications dynamically
notifications();



    // Dummy function to simulate fetching notifications
    function fetchNotifications() {
        console.log("Fetching notifications...");
    }
});