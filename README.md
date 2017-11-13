# **Takeaway restaurant API**

> An api for restaurants of takeaway.com/thuisbezorgd.nl. This api depends on cron jobs because their restaurant dashboard don't save any orders. 

> This code is in no way affiliated with, authorized, maintained, sponsored or endorsed by takeaway.com/thuisbezorgd.nl or any of its affiliates or subsidiaries. This is an independent and unofficial API


### **Installation**
``` bash
# Install composer
composer install

```

### **Configuration**
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


