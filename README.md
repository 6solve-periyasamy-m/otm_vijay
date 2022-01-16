## OTM System

Octopus Travel Matrix development docs

You can document your branch by adding a section.

<hr />

## Installation 


### Basic Installation instructions

These are the basic steps for setting up an instance of the app. If you are setting up the app for development purposes, 
then these steps will be enough to get you up and running. If you need to test payment methods, then you will need to use
more of the additional setup below.

1) Copy `.env.example` to `.env` and fill in the values.
2) Run `composer install` and `nmp i && npm run dev` to install all dependencies and build the front-end.
3) Run `php artisan key:generate` to generate your app key.
4) Run `php artisan migrate:fresh --seed` to initialise the database. If `APP_DEBUG` is set to true, then some demo data will be loaded.

### Stripe Setup

An extra step is required for setting up stripe. You will need to set both of the keys and a webhook within stripe.<br />
You will need to point the webhook to `{url}/api/stripe/webhooks`, for example, `https://octopustravelmatrix.com/api/stripe/webhooks`.

<hr />

## Importing Data

You can import certain data into the application using `php artisan import:{class} {file}`. The file should be a CSV file without a header row.
Listed below are the available imports, and the structure required.

### Accommodation (`import:accommodation`)

`name,description,audit_date,address_line_1,address_line_2,town,region,country,postcode,currency`

### Accommodation Inventory (`import:accommodation-inventory`)

Hotel Name should be the same as it is on the accommodation import

`hotel_name,room_type_name,room_type_maximum_occupancy,board_type,check_in,check_out,FIT_selectable,stock,purchase_price,sales_price,notes`

<hr />

## Booking Form

This is the frontend booking-form.  It is accessed via

site.octopustravelmatrix.com/booking

It uses VueJS to render the booking form and to connect it to backend services via the Booking API.

## Booking API

The Booking API are requests made for records from the backend services

`/api/booking/tour/{id}`<br />
`/api/booking/tour/{id}/flights`<br />
`/api/booking/tour/{id}/accommodation`<br />
`/api/booking/tour/{id}/accomodation/{type}`<br />
`/api/booking/tour/{id}/activities`<br />
`/api/booking/tour/{id}/activities/{type}`<br />
`/api/booking/accomodation`<br />
`/api/booking/flights`<br />
`/api/booking/activities`<br />
`/api/booking/transport`<br />
`/api/booking/tour/{id}/lead`<br />
`/api/booking/tour/{id}/group`<br />

## Payment Schedule

The payment schedule comprises two tables that manage payment schedules and installment plans.  The basic idea

schedule label -< installments

e.g.
monthly-acclerated -< deposit 30%, month1 20%, month2 30%, month3 50%
