const date = new Date();

const renderCalendar = () => {
    date.setDate(1);
    const firstDayIndex = date.getDay();
    const lastDayIndex = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDay();
    const monthDays = document.querySelector(".date");
    const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    const prevLastDay = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
    const nextDays = 7 - lastDayIndex - 1;
    console.log(lastDayIndex);
    const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];

    document.querySelector(".current-month").innerHTML = months[date.getMonth()];
    document.querySelector(".current-year").innerHTML = date.getFullYear();

    let days = "";

    for (let i = firstDayIndex; i > 0; i--) {
        days += `<div>${prevLastDay - i + 1}</div>`;
    }

    for (let i = 1; i <= lastDay; i++) {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();

        if (i === currentDate.getDate() && date.getMonth() === currentDate.getMonth() && date.getFullYear() === currentYear) {
            days += `<div class="today">${i}</div>`;
        } else {
            days += `<div>${i}</div>`;
        }
    }


    for (let i = 1; i <= nextDays; i++) {
        days += `<div>${i}</div>`;
    }

    monthDays.innerHTML = days;
};

document.querySelector('.prev-month').addEventListener('click', () => {
    date.setMonth(date.getMonth() - 1);
    renderCalendar();
});

document.querySelector('.next-month').addEventListener('click', () => {
    date.setMonth(date.getMonth() + 1);
    renderCalendar();
});

function openRegistrationModal() {
    document.getElementById("registrationModal").style.display = "block";
}

function closeRegistrationModal() {
    document.getElementById("registrationModal").style.display = "none";
}

function openAddEventForm() {
    document.getElementById("addEventForm").style.display = "block";
}

function closeAddEventForm() {
    document.getElementById("addEventForm").style.display = "none";
}

function openEventDetailsModal(eventId) {
    // Make an AJAX request to get event details and creator information
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const eventData = JSON.parse(xhr.responseText);

            // Display the event details and creator information in the modal
            const modalContent = document.getElementById('eventDetailsContent');
            modalContent.innerHTML = `
                <p>Title: ${eventData.title}</p>
                <p>Description: ${eventData.description}</p>
                <p>Category: ${eventData.category}</p>
                <p>Location: ${eventData.location}</p>
                <p>Date: ${eventData.date}</p>
                <p>Time: ${eventData.time}</p>
                <p>Created by: ${eventData.creator_username}</p>
                <label for="rsvpStatus">RSVP:</label>
                <select id="rsvpStatus">
            <option value="RSVP">RSVP</option>
            <option value="Attending">Attending</option>
            <option value="Not Attending">Not Attending</option>
        </select>
        <button onclick="updateRSVPStatus(${eventId})">Submit</button>
                <!-- Add more details as needed -->
            `;

            // Show the event details modal
            document.getElementById('eventDetailsModal').style.display = 'block';
        }
    };

    // Open a GET request to the server endpoint that fetches event details
    xhr.open('GET', 'getEventDetails.php?eventId=' + eventId, true);
    xhr.send();
}

function updateRSVPStatus(eventId) {
    // Get the selected RSVP status from the dropdown
    const rsvpStatus = document.getElementById('rsvpStatus').value;

    // Make an AJAX request to update the user's RSVP status
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Display the server response (RSVP success or error)
            alert(xhr.responseText);
            
            // If the update was successful, close the modal
            if (xhr.responseText.includes("successful")) {
                closeEventDetailsModal();
            }
        }
    };

    // Open a GET request to the server endpoint that updates RSVP status
    xhr.open('GET', `updateEventStatus.php?eventId=${eventId}&status=${rsvpStatus}`, true);
    xhr.send();
}

function rsvpToEvent(eventId) {
    // Make an AJAX request to update the user's RSVP status
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Display the server response (RSVP success or error)
            alert(xhr.responseText);
        }
    };

    // Open a GET request to the server endpoint that updates RSVP status
    xhr.open('GET', 'updateEventStatus.php?eventId=' + eventId, true);
    xhr.send();
}


// JavaScript function to close the event details modal
function closeEventDetailsModal() {
    document.getElementById('eventDetailsModal').style.display = 'none';
}
renderCalendar();