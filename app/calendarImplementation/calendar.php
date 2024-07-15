<?php

function boot()
{
    Event::listen(function (MicrosoftGraphCallbackReceived $event) {
        session()->put('microsoftgraph-access-data', $event->accessData);
    });
}

$calendarClass = new Calendar();

$calendars = $calendarClass->getCalendars();

$events = $calendarClass->getCalendarEvents($calendar);

$calendarClass->saveEvent($calendar, $event);
