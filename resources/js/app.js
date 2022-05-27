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
                    if (info.event._def.extendedProps.owner == null) trash.classList.remove('hidden');
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

            if (info.event._def.extendedProps.todo != null) {
                showTodoGroup.innerHTML = '';

                info.event._def.extendedProps.todo.forEach(task => {
                    showTodoGroup.appendChild(window.getTask(task, info.event._def.extendedProps.asPower));
                });
                document.getElementById("event-show-toDo-form-todo_id").value = info.event._def.extendedProps.todo_id;
                showTodo.classList.remove('hidden');
                if (info.event._def.extendedProps.asPower == false) {
                    showTodoForm.classList.add('hidden');
                } else {
                    showTodoForm.classList.remove('hidden');
                }
            }
            if (info.event._def.extendedProps.asPower == false) {
                showTodo.classList.add('hidden');
                showTodoForm.classList.add('hidden');
                console.log(showTodo);
            } else {
                showTodo.classList.remove('hidden');
                showTodoForm.classList.remove('hidden');
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
    <div class="flex items-center mt-2 rounded-md hover:bg-gray-100" id="task-${task.id}" has-power="${hasPower}" task-id="${task.id}">
  
        <div class="justify-start w-full flex items-center ml-4 my-2" onclick="checkTask(${task.id})">
            <input type="hidden" id="task-checked-${task.id}" value="${task.complete == 1 ? true : false}">
            <svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="1.25" y="1.25" width="17.5" height="17.5" rx="2.75" stroke="#000" stroke-width="1.5" />
                <path fill="#000" id="task-checkbox-show-${task.id}" class="${task.complete == 1 ? "" : "hidden"}" d="M3.55939 3.55247C3.81128 3.35094 4.17367 3.36793 4.40561 3.59213L10 9L15.4406 3.55941C15.7389 3.26113 16.2303 3.28868 16.4934 3.61844C16.7178 3.89981 16.6951 4.3049 16.4406 4.55941L11 10L16.4036 15.3807C16.6844 15.6604 16.6849 16.115 16.4046 16.3953C16.1248 16.6752 15.671 16.6752 15.3911 16.3953L9.99581 11L4.55065 16.4452C4.30059 16.6952 3.89517 16.6952 3.64511 16.4452C3.39805 16.1981 3.39466 15.7986 3.63751 15.5474L9 10L3.50656 4.50656C3.23633 4.23633 3.26098 3.79121 3.55939 3.55247Z" />
            </svg>

            <div id="task-text-${task.id}" class="w-2/3 ${task.complete == 1 ? "line-through" : ""} mx-4 font-medium bg-transparent">
                ${task.description} 
            </div>
        </div>
        <div class="justify-end mr-4 my-2">
            <div class="${hasPower ? "" : "hidden"} items-center text-sm font-medium text-center text-black rounded-lg focus:ring-4" onclick="destroyTask(${task.id})">
                <svg width="22" height="22" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26.6664 3.98301H20.4004C20.1792 1.75005 18.2903 0 16 0C13.7098 0 11.8209 1.75005 11.5997 3.98301H5.33373C3.45365 3.98301 1.92407 5.51258 1.92407 7.39266C1.92407 9.216 3.36271 10.7095 5.16448 10.7981V27.8822C5.16448 30.1528 7.01169 32 9.28226 32H22.7179C24.9885 32 26.8357 30.1528 26.8357 27.8822V10.7981C28.6375 10.7096 30.0761 9.21607 30.0761 7.39273C30.0761 5.51258 28.5465 3.98301 26.6664 3.98301ZM16 2.09378C17.1337 2.09378 18.0803 2.90848 18.2864 3.98301H13.7136C13.9197 2.90848 14.8663 2.09378 16 2.09378ZM24.7419 27.8823C24.7419 28.9984 23.8339 29.9063 22.7179 29.9063H9.28219C8.16613 29.9063 7.2582 28.9984 7.2582 27.8823V10.8024C8.11274 10.8024 24.0735 10.8024 24.7419 10.8024V27.8823ZM26.6664 8.70861C26.4482 8.70861 5.51931 8.70861 5.33373 8.70861C4.60816 8.70861 4.01786 8.1183 4.01786 7.39273C4.01786 6.66717 4.60816 6.07686 5.33373 6.07686H26.6664C27.392 6.07686 27.9823 6.66717 27.9823 7.39273C27.9823 8.1183 27.392 8.70861 26.6664 8.70861Z" fill="#2D2438" />
                    <path d="M16 27.3813C16.5782 27.3813 17.0469 26.9126 17.0469 26.3344V14.2111C17.0469 13.6329 16.5783 13.1642 16 13.1642C15.4219 13.1642 14.9531 13.6329 14.9531 14.2111V26.3344C14.9531 26.9126 15.4219 27.3813 16 27.3813Z" fill="#2D2438" />
                    <path d="M21.4036 27.3813C21.9817 27.3813 22.4504 26.9126 22.4504 26.3344V14.2111C22.4504 13.6329 21.9817 13.1642 21.4036 13.1642C20.8254 13.1642 20.3567 13.6329 20.3567 14.2111V26.3344C20.3567 26.9126 20.8254 27.3813 21.4036 27.3813Z" fill="#2D2438" />
                    <path d="M10.5967 27.3813C11.1749 27.3813 11.6436 26.9126 11.6436 26.3344V14.2111C11.6436 13.6329 11.1749 13.1642 10.5967 13.1642C10.0185 13.1642 9.5498 13.6329 9.5498 14.2111V26.3344C9.5498 26.9126 10.0185 27.3813 10.5967 27.3813Z" fill="#2D2438" />
                </svg>
            </div>
        </div>
    </div>`
    return li;
}

window.getOptionTask = function (description, id) {
    let li = document.createElement('li');
    li.setAttribute('id', 'li-task-show-' + id);
    li.innerHTML = `
    <div class="flex items-center px-4 py-2 mt-2 rounded-md hover:bg-gray-100">
        <div class="justify-start w-full mx-1 font-medium bg-transparent">
            ${description} 
        </div>
        <div class="justify-end" onclick="removeTask(${id})">
            <svg width="22" height="22" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26.6664 3.98301H20.4004C20.1792 1.75005 18.2903 0 16 0C13.7098 0 11.8209 1.75005 11.5997 3.98301H5.33373C3.45365 3.98301 1.92407 5.51258 1.92407 7.39266C1.92407 9.216 3.36271 10.7095 5.16448 10.7981V27.8822C5.16448 30.1528 7.01169 32 9.28226 32H22.7179C24.9885 32 26.8357 30.1528 26.8357 27.8822V10.7981C28.6375 10.7096 30.0761 9.21607 30.0761 7.39273C30.0761 5.51258 28.5465 3.98301 26.6664 3.98301ZM16 2.09378C17.1337 2.09378 18.0803 2.90848 18.2864 3.98301H13.7136C13.9197 2.90848 14.8663 2.09378 16 2.09378ZM24.7419 27.8823C24.7419 28.9984 23.8339 29.9063 22.7179 29.9063H9.28219C8.16613 29.9063 7.2582 28.9984 7.2582 27.8823V10.8024C8.11274 10.8024 24.0735 10.8024 24.7419 10.8024V27.8823ZM26.6664 8.70861C26.4482 8.70861 5.51931 8.70861 5.33373 8.70861C4.60816 8.70861 4.01786 8.1183 4.01786 7.39273C4.01786 6.66717 4.60816 6.07686 5.33373 6.07686H26.6664C27.392 6.07686 27.9823 6.66717 27.9823 7.39273C27.9823 8.1183 27.392 8.70861 26.6664 8.70861Z" fill="#2D2438" />
                <path d="M16 27.3813C16.5782 27.3813 17.0469 26.9126 17.0469 26.3344V14.2111C17.0469 13.6329 16.5783 13.1642 16 13.1642C15.4219 13.1642 14.9531 13.6329 14.9531 14.2111V26.3344C14.9531 26.9126 15.4219 27.3813 16 27.3813Z" fill="#2D2438" />
                <path d="M21.4036 27.3813C21.9817 27.3813 22.4504 26.9126 22.4504 26.3344V14.2111C22.4504 13.6329 21.9817 13.1642 21.4036 13.1642C20.8254 13.1642 20.3567 13.6329 20.3567 14.2111V26.3344C20.3567 26.9126 20.8254 27.3813 21.4036 27.3813Z" fill="#2D2438" />
                <path d="M10.5967 27.3813C11.1749 27.3813 11.6436 26.9126 11.6436 26.3344V14.2111C11.6436 13.6329 11.1749 13.1642 10.5967 13.1642C10.0185 13.1642 9.5498 13.6329 9.5498 14.2111V26.3344C9.5498 26.9126 10.0185 27.3813 10.5967 27.3813Z" fill="#2D2438" />
            </svg>
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
