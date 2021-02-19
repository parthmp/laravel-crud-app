
## How to setup

Run the given commands below and follow instructions.

- clone git repository
- composer install
- npm install
- npm run prod
- place provided .env file inside the project & change database settings
- php artisan migrate
- php artisan serve
- "php artisan api:insert" this command (without quotes) will fetch all data, Also will bring in all new data from API, to update existing data, run "php artisan api:update" (without quotes) command. "php artisan api:insert" and "php artisan api:update" commands should run in background on the server as their job is to add and update data from the API constantly.
- Visit your localhost in any browser on your computer, most of times it is 127.0.0.1:8000 for serve command.
- Register an account and login with it, You will see the interface.
- Make sure you have enabled GD library in your php.ini file.


## What more could have been done with more time?

- The API's data is not good as it should have been, County, Country and Town could have their seperate tables, It becomes complicated to search through tables, find and put in seperate tables as API does not care for that as for now.
- Could have added more unit tests.
- Could have made dynamic links for images for properties.
- Could have been tested more to make it more robust.
- Could have made better UI
