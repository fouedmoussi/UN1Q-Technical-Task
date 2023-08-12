<h1 align="center">
  UN1Q Technical Task
</h1>

<p align="center">
    <a href="https://laravel.com/"><img src="https://img.shields.io/badge/Laravel-10-FF2D20.svg?style=flat&logo=laravel" alt="Laravel 10"/></a>
    <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.1-777BB4.svg?style=flat&logo=php" alt="PHP 8.1"/></a>
</p>

This repository contains the implementation of a Calendar Event Management System based on the provided technical assessment. The system allows users to add, update, list, and delete events using REST APIs. The design follows Domain Driven Design (DDD) principles and is inspired by the work of <a href="https://github.com/Orphail">Orphail</a> and <a href="https://github.com/MohammadMehrabani">MohammadMehrabani</a> in <a href="https://github.com/Orphail/laravel-ddd">this repository</a>.

# Table of Contents
[Overview](#Overview)<br/>
[Installation](#Installation)<br/>
[Technical Details](#TechDetails)<br/>
[Available Endpoints](#AvailableEndpoints)<br/>
[Features Implemented](#FeaturesImplemented)<br/>
[Assumptions](#Assumptions)<br/>

<a name="Overview"></a>
## Overview
The Calendar Event Management System is a RESTful API application that allows users to manage calendar events. Users can create, update, list, and delete events. The system supports recurring events and validates against event overlaps. No authentication or authorization is required for accessing the APIs.

<a name="Installation"></a>
## Installation
To run the Calendar Event Management System locally, follow these steps:
1. Clone this repository ```git clone <repository-url>```
2. Navigate to the project directory ```cd UN1Q-Technical-Task```
3. ```composer install```
4. ```cp .env.example .env```
5. ```php artisan key:generate```
6. Set database connection in the ```.env``` variables that start with ```DB_*``` and run ```php artisan migrate```
7. ```php artisan test```

<a name="TechDetails"></a>
## Technical Details
[Structure particularities](https://github.com/Orphail/laravel-ddd#-structure-particularities)<br/>
[What's inside each layer?](https://github.com/Orphail/laravel-ddd#-whats-inside-each-layer)

<a name="AvailableEndpoints"></a>
## Available Endpoints
| HTTP Method | Endpoint                   | Description                                |
|-------------|----------------------------|--------------------------------------------|
| POST        | /api/events/new         | Create a new event                        |
| PUT         | /api/events/{id}        | Update an existing event                  |
| GET         | /api/events             | List events, allowing filtering by date-time range |
| DELETE      | /api/events/{id}        | Delete an event  

<a name="FeaturesImplemented"></a>
## Features Implemented
- Create new events, supporting recurring patterns.
- Update individual event instances.
- List events within a specific date-time range.
- Delete individual event instances.

<a name="Assumptions"></a>
## Assumptions
- Events are limited to a single day (no multi-day events).
- Recurring events generate occurrences within the specified time range.
- Recurring patterns include daily, weekly, monthly, and yearly.
- All events can be updated and deleted regardless of past or future dates.

Please refer to the code for more detailed information on the implementation. If you have any questions or need further assistance, feel free to reach out. 
