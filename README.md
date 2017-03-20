# Yii2 Audit



Yii2 Audit records and displays web/cli requests, database changes, php/js errors and associated data.

## Features

### Powerful, yet Simple

* Installs as a simple module so it can be added without any hassle.
* You can either track specific actions and nothing else or exclude specific routes from logging (wildcard supported).
* View your data. The module contains a nice viewer that is automatically made available when you add it to your configuration. It has configurable permissions to limit access to this functionality by IPs, roles or users.

### Minimal Footprint

Tracks minimal data in the base entry:

* `user_id` - User ID of the visitor (if any), based on `Yii::$app->user->id`.
* `ip` - IP Address of the visitor.
* `request_method` - The method used to generate the request, eg: `CLI` for console requests and `GET`, `POST`, `DELETE`, `PUT`, `PATCH`, `OPTIONS` or `HEAD` for web requests.
* `ajax` - If the page was requested using ajax.
* `route` - The controller and action of the request.
* `duration` - How long the request took to serve.
* `memory_max` - The peak memory usage during the request.
* `created` - The datetime the entry was created.



