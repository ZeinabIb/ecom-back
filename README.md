# Welcome, Inhabitants of Earth!

Greetings, fellow Earth dwellers! We're thrilled to embark on this digital journey with you. 

### Prerequisites

Hey squad double-check XAMPP's on your rig, yeah? No XAMPP, no party—it's that simple!

### Step 1: Power Up Your XAMPP

power up xamp run the Appache and MySql


### Step 2: Install Dependencies

composer install

### Step 3: Database Migration

php artisan migrate


Congratulations, Earthling! You've successfully set up your environment for our project. 

Happy coding yoooooooooo fellow earthers ,,


w ahamma chi make your .env file 
you can do that using this command:

cp .env.example .env

and set ur db config after that ...

note: after you create the db, go to mysql and alter the user table, the token should be varchar(1500) instead of varchar(255) cz microsoft token is long


when u create ur env add this bel akhir  bil .env file



GOOGLE_CLIENT_ID=738705865835-h5al4kkvqvijm7peo8ec0n469l37s0pu.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback

MICROSOFT_CLIENT_ID=2208ad6d-0e62-4d17-8562-3d3cc42217d7
MICROSOFT_CLIENT_SECRET=XIC8Q~lHIToaXrXacIUU6kAef1UQmuWFIMQLVa5G
MICROSOFT_REDIRECT_URI=http://localhost:8000/auth/microsoft/callback

The mailer stuff already exists so just replace them with this:

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ralphdaher6@gmail.com
MAIL_PASSWORD=nyyhzsnhjektdbct
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ralphdaher6@gmail.com
MAIL_FROM_NAME="Ecommerce Mail"
