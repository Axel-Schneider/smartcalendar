require('./bootstrap');

import Alpine from 'alpinejs';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import moment from 'moment';


window.Alpine = Alpine;
window.moment = moment;

Alpine.start();

window.initCalendar = function (Events) {
    console.log('initCalendar')
    const element = document.getElementById("calendar");

    var todayDate = moment().startOf("day");
    var YM = todayDate.format("YYYY-MM");
    var YESTERDAY = todayDate.clone().subtract(1, "day").format("YYYY-MM-DD");
    var TODAY = todayDate.format("YYYY-MM-DD");
    var TOMORROW = todayDate.clone().add(1, "day").format("YYYY-MM-DD");

    var calendarEl = document.getElementById("calendar");
    var calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
        },

        height: 800,
        contentHeight: 780,
        aspectRatio: 3, // see: https://fullcalendar.io/docs/aspectRatio

        nowIndicator: true,

        views: {
            dayGridMonth: {
                buttonText: "month"
            },
            timeGridWeek: {
                buttonText: "week"
            },
            timeGridDay: {
                buttonText: "day"
            }
        },

        initialView: "dayGridMonth",
        initialDate: TODAY,

        editable: false,
        dayMaxEvents: true, // allow "more" link when too many events
        navLinks: true,
        events: [
            {
                id: 'a',
                title: 'my event',
                start: '2022-04-22T10:30:00'
            },
            {
                id: 'a',
                title: 'my event',
                start: '2022-04-23'
            },
            {
                id: 'a',
                title: 'my event',
                start: '2022-04-22'
            },
            {
                id: 'a',
                title: 'my event',
                start: '2022-04-22'
            },
            {
                id: 'a',
                title: 'my event',
                start: '2022-04-22'
            },
            {
                id: 'a',
                title: 'my event',
                start: '2022-04-22'
            },
            {
                id: 'a',
                title: 'my event',
                start: '2022-04-22'
            },
            {
                id: 'a',
                title: '<p>my event</p><div>yop</div>',

                start: '2022-04-25'
            }
        ],

        eventContent: function (info) {
            var element = info.el;

            console.log("ok");
            console.log(info);

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
        }
    });

    calendar.render();
}