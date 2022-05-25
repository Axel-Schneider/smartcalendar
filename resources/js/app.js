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
            const showShared = document.getElementById('event-show-shareds');
            const showOwner = document.getElementById('event-show-owner');
            const showCommons = document.getElementById('event-show-commons');
            const showDescription = document.getElementById('event-show-description');
            const showTodo = document.getElementById('event-show-todo');
            const showTodoGroup = document.getElementById('event-show-toDo-group');
            const showTodoForm = document.getElementById('event-show-toDo-add-form');
            const popup = document.getElementById('show-event-modal');
            const inputId = document.getElementById('eventId');
            const trash = document.getElementById('event-show-trash');
            const modify = document.getElementById('event-show-modify');

            let date = "";

            showTitle.innerText = info.event._def.title;
            showDescription.innerText = info.event._def.extendedProps.description;
            if (info.event._def.extendedProps.sharedWith != null && Object.values(info.event._def.extendedProps.sharedWith).length > 0) {
                showShared.innerText = Object.values(info.event._def.extendedProps.sharedWith).join(', ');

                document.getElementById('event-show-shareds-div').classList.remove('hidden');
            } else {
                showShared.innerText = "";
                document.getElementById('event-show-shareds-div').classList.add('hidden');
            }

            console.log(Object.values(info.event._def.extendedProps.sharedWith));
            console.log(window.user);
            if (info.event._def.extendedProps.owner != null) {
                showOwner.innerText = info.event._def.extendedProps.owner;
                document.getElementById('event-show-owner-div').classList.remove('hidden');
                if (info.event._def.extendedProps.asPower) {
                    if(info.event._def.extendedProps.owner == null) trash.classList.remove('hidden');
                    else trash.classList.add('hidden');
                    modify.classList.remove('hidden');
                } else {
                    trash.classList.add('hidden');
                    modify.classList.add('hidden');
                }
            } else {
                console.log('not owner');
                showOwner.innerText = "";
                document.getElementById('event-show-owner-div').classList.add('hidden');
                trash.classList.remove('hidden');
                modify.classList.remove('hidden');
            }

            if (info.event._def.extendedProps.commonWith != null && Object.values(info.event._def.extendedProps.commonWith).length > 0) {
                showCommons.innerText = Object.values(info.event._def.extendedProps.commonWith).join(', ');
                document.getElementById('event-show-commons-div').classList.remove('hidden');
            } else {
                showCommons.innerText = "";
                document.getElementById('event-show-commons-div').classList.add('hidden');
            }

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

            if(info.event._def.extendedProps.todo == null) showTodo.classList.add('hidden');
            else {
                showTodoGroup.innerHTML = '';

                info.event._def.extendedProps.todo.forEach(task => {
                    showTodoGroup.appendChild(window.getTask(task, info.event._def.extendedProps.asPower));
                });
                document.getElementById("event-show-toDo-form-todo_id").value = info.event._def.extendedProps.todo_id;
                showTodo.classList.remove('hidden');
                if(info.event._def.extendedProps.asPower == false) {
                    showTodoForm.classList.add('hidden');
                } else {
                    showTodoForm.classList.remove('hidden');
                }
            }

            inputId.setAttribute('value', info.event.id.toString());
            popup.classList.remove("hidden");
        }
    });
    calendar.render();
    window.calendar = calendar;
}

window.getTask = function (task, hasPower) {
    let li = document.createElement('li');
    li.innerHTML = `
    <div class="flex items-center px-4 py-2 mt-2 rounded-md hover:bg-gray-100" id="task-${task.id}" has-power="${hasPower}" task-id="${task.id}" onclick="checkTask()">
        <input type="hidden" id="task-checked-${task.id}" value="${task.complete == 1 ? true : false}">
        <svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="1.25" y="1.25" width="17.5" height="17.5" rx="2.75" stroke="#000" stroke-width="1.5" />
            <path fill="#000" id="task-checkbox-show-${task.id}" class="${task.complete == 1 ? "" : "hidden"}" d="M3.55939 3.55247C3.81128 3.35094 4.17367 3.36793 4.40561 3.59213L10 9L15.4406 3.55941C15.7389 3.26113 16.2303 3.28868 16.4934 3.61844C16.7178 3.89981 16.6951 4.3049 16.4406 4.55941L11 10L16.4036 15.3807C16.6844 15.6604 16.6849 16.115 16.4046 16.3953C16.1248 16.6752 15.671 16.6752 15.3911 16.3953L9.99581 11L4.55065 16.4452C4.30059 16.6952 3.89517 16.6952 3.64511 16.4452C3.39805 16.1981 3.39466 15.7986 3.63751 15.5474L9 10L3.50656 4.50656C3.23633 4.23633 3.26098 3.79121 3.55939 3.55247Z" />
        </svg>

        <div id="task-text-${task.id}" class="${task.complete == 1 ? "line-through" : ""} mx-4 font-medium bg-transparent">
            ${task.description} 
        </div>
    </div>`
    return li;
}

window.getOptionTask = function (description){
    let li = document.createElement('li');
    li.innerHTML = `
    <div class="flex items-center px-4 py-2 mt-2 rounded-md hover:bg-gray-100">
        <div class="mx-4 font-medium bg-transparent">
            ${description} 
        </div>
    </div>`;
    return li;
}

window.formatDateForInput = function (date) {
    return `${date.getFullYear()}-${to2Digit(date.getMonth() + 1)}-${to2Digit(date.getDate())}T${to2Digit(date.getHours())}:${to2Digit(date.getMinutes())}`
}
window.setShow = function (input_id) {
    const INPUT_ID = input_id || event.target.getAttribute('input-id');
    const select = document.getElementById('select-input-select-' + INPUT_ID);
    var result = [];
    var options = select && select.options;
    var opt;

    for (var i = 0, iLen = options.length; i < iLen; i++) {
        opt = options[i];

        if (opt.hasAttribute('selected')) {
            result.push(opt.text);
        }
    }
    const SHOW_DIV = document.getElementById('select-input-show-' + INPUT_ID);
    SHOW_DIV.innerHTML = (result.length > 0) ? result.join(', ') : SHOW_DIV.getAttribute('default-text');
}

window.AddOption = function () {
    const INPUT_OPTION_ID = event.target.getAttribute('input-option-id');
    const INPUT_ID = event.target.getAttribute('input-id');
    const USER_INPUT = document.getElementById("select-input-option-" + INPUT_ID + "-" + INPUT_OPTION_ID);


    if (USER_INPUT.hasAttribute('selected')) {
        console.log('selected');
        USER_INPUT.removeAttribute("selected");
        event.target.classList.remove("bg-gray-600");
        event.target.classList.remove("text-white");
        event.target.classList.remove("hover:bg-gray-700");
        event.target.classList.add("hover:bg-gray-100");
    } else {
        console.log('not selected');
        USER_INPUT.setAttribute("selected", "selected");
        event.target.classList.add("bg-gray-600");
        event.target.classList.add("text-white");
        event.target.classList.add("hover:bg-gray-700");
        event.target.classList.remove("hover:bg-gray-100");
    }
    console.log(INPUT_ID);
    setShow(INPUT_ID);
}

function to2Digit(number) {
    return ("0" + (number)).slice(-2);
}
