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
        if (i === new Date().getDate() && date.getMonth() === new Date().getMonth()) {
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

renderCalendar();