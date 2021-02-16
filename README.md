## OTM System

Octopus Travel Matrix development docs

You can document your branch by adding a section.  

## Booking Form

This is the frontend booking-form.  It is accessed via 

site.octopustravelmatrix.com/booking

It uses VueJS to render the booking form and to connect it to backend services via the Booking API.

## Booking API

The Booking API are requests made for records from the backend services

/api/booking/tour/{id}
/api/booking/tour/{id}/flights
/api/booking/tour/{id}/accommodation
/api/booking/tour/{id}/accomodation/{type}
/api/booking/tour/{id}/activities
/api/booking/tour/{id}/activities/{type}
/api/booking/accomodation
/api/booking/flights
/api/booking/activities
/api/booking/transport
/api/booking/tour/{id}/lead
/api/booking/tour/{id}/group

## Payment Schedule

The payment schedule comprises two tables that manage payment schedules and installment plans.  The basic idea 

schedule label -< installments

e.g.
monthly-acclerated -< deposit 30%, month1 20%, month2 30%, month3 50%
