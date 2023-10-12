# OTM System

## System Requirements 
We have a docker-compose setup ready for use, if you wish to use that. However, if you wish to run it locally, you will need, at a bare minimum:

- MySQL (Due to optimizations, the system is currently incompatible with Sqlite or Postgres)
- PHP 8.1 (Requires the following extensions be enabled)
  - php8.1-intl
  - php8.1-json
  - php8.1-pdo
  - php8.1-zip
  - php8.1-gd
- NPM and NodeJS (latest)
- PDFTK (Optional, required for working with ATOL certificates)

You will also need some form of headless chromium to get puppeteer working for PDF generation. This is, however, not a requirement.

## Initial Project Setup
### Installing Dependencies

Once you have all the requirements, you should clone down the repository to your local files. Once you have downloaded the files, you should use the following command to install all PHP dependencies:

`composer install`

And then use the following command to install the NodeJS dependencies:

`npm install`

### Project Configuration
You will need to copy .env.example to .env, and configure the database variables. If you intend to use docker-compose, you can set them to any values, and they will be used when generating the container, however, if you wish to use standalone, you will need to use the correct information for your database.<br />
Mailing is set to use the minimal mailer, which just logs all email recipients and subjects to a file for viewing, however, you can configure this to use SMTP if you need to test emails.<br />
If you wish to test the payment gateways, you will need to setup the stripe keys.<br />

Once you have completed that, you’ll finally need to perform two commands:

`php artisan key:generate`
`php artisan storage:link`

The first will generate the private key for the database to use, required by laravel, and the second will link the storage to the public directory.

### Setting up a User Account

You can set up a new user account to login with by using the following command:

`php artisan user:create --otm`

This will create a new superuser account, which can access the logs from the web viewer. If you omit the flag, it will create a regular administrator, who does not have access to the log viewer.

## Running the Website

To run the website, you can either point nginx/apache towards the public/ directory, or you may use the following command to spin up a temporary webserver:

`php artisan serve --port=8000`

The port flag is optional, and will require administrator privileges if you wish to set it to 80.

## Generating Resources

During initial setup, and whenever changes are made to the views/resources, you will need to run the following command:

`npm run dev`

This will regenerate all the CSS/JS files, and make the website ready for viewing.

## Code Styling Guidelines

We have no hard code styling guidelines at the moment, however, we try to avoid heavy nesting (use guard clauses instead). Variables should also be named to be readable and understandable as to their purpose.

## Running the Test Suite

To run the unit tests, you may use the following command:

`php artisan test --parralel --processes=4`

You can change 4 to be any number less than the number of threads your processor has.
If you are using PHPStorm, you may also right-click and run the php unit file.

## Tech Stack and Packages

### Back-end

- Laravel 9.x (To be updated to laravel 10 in future)
- HasManyDeep (Used to generate deeper and more complex relationships)
- CascadeSoftDeletes (Will be deprecated in future when better tooling is in place)
- Excel (Used for exporting and importing data from/to CSV/XLSX files)
- PDFTK (Used to input data into the pregenerated ATOL certificates)
- Bouncer (Manages permissions)
- Laravel IDE Helper (Generates helper code for IDEs, and model definitions)

### Livewire

- Livewire 2 (Will update to Livewire 3 with Laravel 10)
- Livewire Datatables

### Front-end

- Webpack + Mix
- jQuery
- SCSS + PostCSS
- Bootstrap (To be deprecated in future. Certain features are non-functional)
- TailwindCSS
- Typescript (Optional, but preferred)
- FontAwesome Free (Icons)
- AlpineJS (Available, used by livewire)
- ChartJS (Used for generating graphs)
- Browsershot + Puppeteer (Used for converting pages into PDF files)
- Select2

<hr />

## Importing Data

You can import certain data into the application using `php artisan import:{class} {file}`. The file should be a CSV file without a header row.<br />

Fields will need to be formatted in a specific way:<br />
- Dates should be formatted as dd/mm/yyyy
- Dates with time should be 24 hours and formatted as dd/MM/yyyy hh:mm:ss
- Booleans (true/false, yes/no) should be either `YES` or `NO`, with capitals

Listed below are the available imports. Templates can be found in the public/import directory. Headers must remain the same, but can be in any order <br />

### Accommodation (`import:accommodation`)

### Accommodation Inventory (`import:accommodation-inventory`)

Hotel Name should be the same as it is on the accommodation import

### Activity (`import:activity`)

### Activity Inventory (`import:activity-inventory`)

Activity Name should be the same as on the activity import

### Airport (`import:airport`)

`name,iata_code,address_line_1,address_line_2,town,region,country,postcode`

### Flights and Inventory (`import:flight-inventory`)

`airline_name,departure_airport_name,arrival_airport_name,is_domestic,currency,flight_notes,flight_number,travel_class,check_in,departs_at,arrives_at,FIT_selectable,stock,purchase_price,sales_price,inventory_notes`

### Customer (`import:customer`)
