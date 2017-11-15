# **Takeaway restaurant API**

> An api for restaurants of takeaway.com/thuisbezorgd.nl. This api depends on cron jobs because their restaurant dashboard don't save any orders. 

> This code is in no way affiliated with, authorized, maintained, sponsored or endorsed by takeaway.com/thuisbezorgd.nl or any of its affiliates or subsidiaries. This is an independent and unofficial API


## **Installation**
``` bash
# Install composer
composer install

```

## **Configuration**
Rename the ``.env.example`` file to ``.env`` in the root of the api folder

Generate app key
``` bash 
php artisan key:generate

```

#### **Environment**
Insert your database credentials.
``` bash
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
Insert your restaurant credentials. You can find it in every invoice mail you received from takeaway.com/thuisbezorgd.nl
``` bash
TAKEAWAY_USERNAME=
TAKEAWAY_PASSWORD=
```

#### **Database**

``` bash
#Run migrations
php artisan migrate
```

## **Task Scheduling**
This api depends on cron jobs because their restaurant dashboard don't save any orders. Create a new cron entry to run the scraper on a cron job.

``` bash
crontab -e
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

#### **Schedule Frequency Options**
You can find the schedules at ``App\Console\Kernel``. Currently it is running everyday between **16:00 - 21:00**, feel free to put your own schedule preference 

``` bash
$schedule->call(function(){
    OrdersController::orders();
})->daily()
  ->everyMinute()
  ->timezone('Europe/Amsterdam')
  ->between('16:00', '21:00');   
```



