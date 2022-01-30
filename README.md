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

An extra step is required for setting up stripe. You will need to set both of the keys and a webhook within stripe. This webhook should receive the `checkout.session.completed` event<br />
You will need to point the webhook to `{url}/api/stripe/webhooks`, for example, `https://octopustravelmatrix.com/api/stripe/webhooks`.

<hr />

## Importing Data

You can import certain data into the application using `php artisan import:{class} {file}`. The file should be a CSV file without a header row.<br />

Fields will need to be formatted in a specific way:<br />
- Dates should be formatted as dd/mm/yyyy
- Dates with time should be 24 hours and formatted as dd/MM/yyyy hh:mm:ss
- Booleans (true/false, yes/no) should be either `YES` or `NO`, with capitals

Listed below are the available imports, and the structure required.<br />

### Accommodation (`import:accommodation`)

`name,description,audit_date,address_line_1,address_line_2,town,region,country,postcode,currency,notes`

### Accommodation Inventory (`import:accommodation-inventory`)

Hotel Name should be the same as it is on the accommodation import

`hotel_name,room_type_name,room_type_maximum_occupancy,board_type,check_in,check_out,FIT_selectable,stock,purchase_price,sales_price,notes`

### Activity (`import:activity`)

`name,description,activity_type,location_type,address_line_1,address_line_2,town,region,country,postcode,currency,notes`

### Activity Inventory (`import:activity-inventory`)

Activity Name should be the same as on the activity import

`activity_name,ticket_type,starts_at,ends_at,FIT_selectable,stock,purchase_price,sales_price,notes`

### Airport (`import:airport`)

`name,iata_code,address_line_1,address_line_2,town,region,country,postcode`

### Flights and Inventory (`import:flight-inventory`)

`airline_name,departure_airport_name,arrival_airport_name,is_domestic,currency,flight_notes,flight_number,travel_class,check_in,departs_at,arrives_at,FIT_selectable,stock,purchase_price,sales_price,inventory_notes`

### Customer (`import:customer`)

`email_address,title,first_name,middle_name,last_name,date_of_birth,mobile_number,other_number,gender,home_address_line_1,home_address_line_2,home_town,home_region,home_country,home_postcode,billing_address_line_1,billing_address_line_2,billing_town,billing_region,billing_country,billing_postcode,emergency_contact_name,emergency_contact_number,emergency_contact_relationship,passport_first_name,passport_middle_name,passport_last_name,passport_number,passport_expiry_date,passport_country_of_issue`

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
