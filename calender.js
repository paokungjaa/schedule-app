export function initCalender() {
    const monthCalenderElement = document.querySelector("[data-month-calender]");
    const allWeekCalenders = document.querySelectorAll("[data-week-calender]");

    document.addEventListener("view-change", (event) => {
        const selectedView = event.detail.view;
        const selectedDate = event.detail.date; // Expects format: 'YYYY-MM-DD'

        if (selectedView === "Month") {
            monthCalenderElement.style.display = "flex";
            allWeekCalenders.forEach(room => room.style.display = "none");
        } else if (selectedView === "Week" && selectedDate) {
            monthCalenderElement.style.display = "none";
            allWeekCalenders.forEach(room => {
                if (room.dataset.weekCalender === selectedDate) {
                    room.style.display = "flex";
                } else {
                    room.style.display = "none";
                }
            });
        } else {
            monthCalenderElement.style.display = "none";
            allWeekCalenders.forEach(room => room.style.display = "none");
        }
    });
}
