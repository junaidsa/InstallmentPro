var colors = [
    "#f56954",
    "#4285F4",
    "#34A853",
    "#DB4437",
    "#FF6D01",
    "#46BDC6",
    "#AB47BC",
    "#8E24AA",
    "#EA12E3",
    "#F93DA0",
    "#969C0C",
];
var colorMap = {};
var colorIndex = 0;
var Calendar = FullCalendar.Calendar;
var calendarEl = document.getElementById("calendar");
var events = [];

var calendar = new Calendar(calendarEl, {
    plugins: ["bootstrap", "interaction", "dayGrid", "timeGrid"],
    header: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    themeSystem: "bootstrap",
    editable: false,
    droppable: false,
    dayMaxEvents: true,
    height: "auto",
    fixedWeekCount: false,
    displayEventTime: false,
    views: {
        dayGridMonth: {
            dayMaxEventRows: 30,
        },
    },

    events: function (fetchInfo, successCallback, failureCallback) {
        $.ajax({
            url: "/installments/calendarData",
            type: "GET",
            data: {
                start: fetchInfo.startStr,
                end: fetchInfo.endStr,
            },
            success: function (response) {
                response.forEach((event) => {
                    var statusColors = {
                        "Full pay": "#34A853",
                        Pending: "#67c034",
                    };

                    var color = statusColors[event.status] || "#67c034";
                    if (event.is_late) {
                        color = "#DB4437";
                    } else if (event.is_near_due) {
                        color = "#FFA500";
                    }

                    event.backgroundColor = color;
                    event.borderColor = color;
                    event.textColor = "#fff";
                });
                successCallback(response);
            },
            error: function () {
                failureCallback();
            },
        });
    },

    dateClick: function (info) {
        var selectedDate = info.dateStr;
        $.ajax({
            url: "/installments/calendarData",
            type: "GET",
            data: { date: selectedDate },
            success: function (response) {
                $("#modalDate").text(selectedDate);
                var installmentList = $("#installmentList");
                installmentList.empty();

                if (response.length > 0) {
                    response.forEach((installment) => {
                        let badge = "";

                        // Add color indicators in the modal too
                        if (installment.is_late) {
                            badge = `<span class="badge bg-danger">Late</span>`;
                        } else if (installment.is_near_due) {
                            badge = `<span class="badge bg-warning text-dark">Near Due</span>`;
                        } else if (installment.status === "Full pay") {
                            badge = `<span class="badge bg-success">Paid</span>`;
                        }

                        installmentList.append(`
                            <li class="list-group-item">
                                <strong>${installment.customer_name}</strong> ${badge}<br>
                                Amount: ${installment.amount} <br>
                                Due: ${installment.due_date_formatted} <br>
                                Status: ${installment.status}
                            </li>
                        `);
                    });
                } else {
                    installmentList.append(
                        `<li class="list-group-item text-muted">No installments due on this date.</li>`
                    );
                }

                $("#installmentModal").modal("show");
            },
        });
    },
});

calendar.render();
