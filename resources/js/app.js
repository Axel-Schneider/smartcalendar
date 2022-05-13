require('./bootstrap');

import Alpine from 'alpinejs';
import 'flowbite';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import allLocales from '@fullcalendar/core/locales-all';
import Interaction from '@fullcalendar/interaction';
import moment from 'moment';
import { locale } from 'moment';


window.Alpine = Alpine;
window.moment = moment;

Alpine.start();

window.initCalendar = function () {
    const element = document.getElementById("calendar");

    const todayDate = moment().startOf("day");
    const YM = todayDate.format("YYYY-MM");
    const YESTERDAY = todayDate.clone().subtract(1, "day").format("YYYY-MM-DD");
    const TODAY = todayDate.format("YYYY-MM-DD");
    const TOMORROW = todayDate.clone().add(1, "day").format("YYYY-MM-DD");

    const calendarEl = document.getElementById("calendar");
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, Interaction],
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
        },

        height: 800,
        contentHeight: 780,
        aspectRatio: 3, // see: https://fullcalendar.io/docs/aspectRatio

        nowIndicator: true,
        locales: allLocales,
        locale: window.navigator.userLanguage || window.navigator.language,
        initialView: "dayGridMonth",
        initialDate: TODAY,
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            hour12: false,
        },
        eventColor: '#2C3E50',
        firstDay: 1,
        editable: false,
        dayMaxEvents: true, // allow "more" link when too many events
        navLinks: true,
        events: '/api/events',

        eventContent: function (info) {
            // let element = info.el;

            // if (info.event.extendedProps && info.event.extendedProps.description) {
            //     if (element.hasClass("fc-day-grid-event")) {
            //         element.data("content", info.event.extendedProps.description);
            //         element.data("placement", "top");
            //         KTApp.initPopover(element);
            //     } else if (element.hasClass("fc-time-grid-event")) {
            //         element.find(".fc-title").append("<div class=" +
            //             fc - description + ">" + info.event.extendedProps.description + "</div>");
            //     } else if (element.find(".fc-list-item-title").lenght !== 0) {
            //         element.find(".fc-list-item-title").append("<div test=\"test\" class=" +
            //             fc - description + ">" + info.event.extendedProps.description + "</div>");
            //     }
            // }
        },
        dateClick: function (info) {
            document.getElementById('startDate').value = info.dateStr + "T00:00";
            document.getElementById('endDate').value = info.dateStr + "T23:59";
            const newEventModal = document.getElementById('new-event-modal');
            newEventModal.classList.remove("hidden");
        },
        eventClick: function (info) {
            const isSameDate = (firstDate, secondDate) => {
                return firstDate.getDate() == secondDate.getDate() &&
                    firstDate.getMonth() == secondDate.getMonth() &&
                    firstDate.getFullYear() == secondDate.getFullYear()
            }
            const showTitle = document.getElementById('event-show-title');
            const showDate = document.getElementById('event-show-date');
            const showDescription = document.getElementById('event-show-description');
            const popup = document.getElementById('show-event-modal');
            const trash = document.getElementById('eventId');

            let date = "";

            showTitle.innerText = info.event._def.title;
            showDescription.innerText = info.event._def.extendedProps.description;

            if (info.event._def.allDay) {
                date = info.event.start.toLocaleDateString(undefined, {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
                date = date.charAt(0).toUpperCase() + date.slice(1);
            } else if (info.event.end == null) {
                date = info.event.start.toLocaleDateString(undefined, {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
                let startTime = info.event.start.toLocaleTimeString(undefined, {
                    hour: 'numeric',
                    minute: 'numeric',
                });
                date = date.charAt(0).toUpperCase() + date.slice(1) + " " + startTime;

            }
            else if (isSameDate(info.event.start, info.event.end)) {
                date = info.event.start.toLocaleDateString(undefined, {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
                let startTime = info.event.start.toLocaleTimeString(undefined, {
                    hour: 'numeric',
                    minute: 'numeric',
                });
                let endTime = info.event.end.toLocaleTimeString(undefined, {
                    hour: 'numeric',
                    minute: 'numeric',
                });
                date = date.charAt(0).toUpperCase() + date.slice(1) + " " + startTime + " - " + endTime;
            } else {
                let startDate = info.event.start.toLocaleDateString(undefined, {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
                let endDate = info.event.end.toLocaleDateString(undefined, {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
                date = startDate + " - " + endDate;
            }

            showDate.innerText = date;
            trash.setAttribute('value', info.event.id.toString());
            popup.classList.remove("hidden");
        }
    });
    calendar.render();
    window.calendar = calendar;
}

window.formatDateForInput = function(date){
    return `${date.getFullYear()}-${to2Digit(date.getMonth() + 1)}-${to2Digit(date.getDate())}T${to2Digit(date.getHours())}:${to2Digit(date.getMinutes())}`
}

function to2Digit(number){
    return ("0" + (number)).slice(-2);
}
