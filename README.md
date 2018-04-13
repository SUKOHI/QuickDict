# QuickDict
A Laravel package for create migration and seeder from key-value data.
(This is maintained under Laravel 5.6)

# Installation

Execute composer command.

    composer require sukohi/quick-dict:1.*

And publish a configuration file.

    php artisan vendor:publish --provider="Sukohi\QuickDict\QuickDictServiceProvider"
    
Now you should have `quick-dict.php` in `/config`.
    
# Usage

Please add key-value data in `quick-dict.php` like so.

    return [
        'months' => [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ]
    ]
    
Then run the next command to add the data into DB.

    php artisan dict
    
This means that QuickDict creates migration and seeder then run them.

# Add new key-value data

When you'd like to add new key-value data like so, also run `php artisan dict`. 
Existing table will be ignored.

    return [
        'months' => [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ],
        'weekday_names' => [    // New
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ]
    ]

# Recommendation

After running `php artisan dict` command, I recommend you to add created Seeder(s) into `database/seeds/DatabaseSeeder` if you'd like to manage your key-value data through seeder commnd like `php artisan migrate:fresh --seed`.

# License

This package is licensed under the MIT License.

Copyright 2018 Sukohi Kuhoh