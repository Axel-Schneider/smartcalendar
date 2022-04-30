require('./bootstrap');

import Alpine from 'alpinejs';
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

window.initCalendar = function (Events) {
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
        firstDay: 1,
        editable: false,
        dayMaxEvents: true, // allow "more" link when too many events
        navLinks: true,
        events: '/api/events',

        eventContent: function (info) {
            let element = info.el;

            if (info.event.extendedProps && info.event.extendedProps.description) {
                if (element.hasClass("fc-day-grid-event")) {
                    element.data("content", info.event.extendedProps.description);
                    element.data("placement", "top");
                    KTApp.initPopover(element);
                } else if (element.hasClass("fc-time-grid-event")) {
                    element.find(".fc-title").append("<div class=" +
                        fc - description + ">" + info.event.extendedProps.description + "</div>");
                } else if (element.find(".fc-list-item-title").lenght !== 0) {
                    element.find(".fc-list-item-title").append("<div test=\"test\" class=" +
                        fc - description + ">" + info.event.extendedProps.description + "</div>");
                }
            }
        },
        dateClick: function (info) {
            const newEventModal = document.getElementById('new-event-modal');
            const modalOpacity = document.getElementById("modal-opacity");
            newEventModal.classList.remove("hidden");
            modalOpacity.addEventListener('click', () => { newEventModal.classList.add('hidden'); });
            document.querySelector('[data-modal-toggle]').addEventListener('click', () => { newEventModal.classList.add('hidden'); });
        
            // document.getElementById('startDate').value = info.date.toISOString().slice(0, 16);
            document.getElementById('startDate').value = info.dateStr + "T00:00";
            console.log(info.dateStr);
        }
    });
    calendar.render();
}
