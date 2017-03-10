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


## Project Resources

* [Project Homepage](https://musingsz.github.io/yii2-audit/)
* [Live Demo](https://yii2-audit.herokuapp.com/)
* [GitHub Project](https://github.com/musingsz/yii2-audit)
* [Yii2 Extension](http://www.yiiframework.com/extension/yii2-audit)
* [Packagist Package](https://packagist.org/packages/musingsz/yii2-audit)
* [Travis CI Testing](https://travis-ci.org/musingsz/yii2-audit)
* [Scrutinizer CI Code Quality](https://scrutinizer-ci.com/g/musingsz/yii2-audit)

## License

BSD-3 - Please refer to the [license](https://github.com/musingsz/yii2-audit/blob/master/LICENSE.md).
![Analytics](https://ga-beacon.appspot.com/UA-65104334-3/yii2-audit/README.md?pixel)