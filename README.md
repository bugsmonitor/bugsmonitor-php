# BugSender PHP library

## Installation

In your `composer.json` add code:
```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/bugsmonitor/bugsmonitor-php"
    }
],
"require": {
    "bugsmonitor/bugsmonitor-php": "dev"
}
```



## Set user
Sometimes you may need add user to bug report, but usually
```php
$bugSender = \Bugsender\Bugsender::getInstance();
$bugSender->setUser([
    'id' => 123,
    'name' => 'Joe Doe',
    'email' => 'joe.doe@example.com',
]);
``` 
