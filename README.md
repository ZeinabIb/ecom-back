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

PUSHER_APP_ID=1793309
PUSHER_APP_KEY=a2e0226e8071fa644e1a
PUSHER_APP_SECRET=5db59ccdfa46aad1b9ed
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=ap2

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

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


STRIPE_KEY=pk_test_51NRHMtALKyGb52CAJZEZ2etcwJI04zEoKzRdoUhfS4Mzp6UqWGabNqxDd3F5z1PMogXR5mkT9NyXr20YS5a3BBuA00ZRkTnhIT
STRIPE_SECRET=sk_test_51NRHMtALKyGb52CApX1sTxO1e1tk5VDt2idasg9CMByhsGq4JPkY2NLj0rfc3xtW8V0Q7HUVkQpQ1tKQg0zG4WbR00VxY5WbAL
