const date = new Date();
date.setDate(1);
const firstDayIndex = date.getDay();
const lastDayIndex = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDay();
const monthDays = document.querySelector(".date");
const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
const prevLastDay = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
const nextDays = 7 + lastDayIndex - 1;
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

for (let x = firstDayIndex; x > 0; x--) {
    days += `<div>${prevLastDay - x + 1}</div>`;
}

for (let i = 1; i <= lastDay; i++) {
    days += `<div>${i}</div>`;
}

monthDays.innerHTML = days;