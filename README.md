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

## Usage
```php
$bugsMonitor = \Bugsmonitor\Bugsmonitor::getInstance();
$bugsMonitor->init([
    'apiKey' => '5118d47e4ac894f0b59ef18f7cff3f033da49227078b14fafd8d7f9e70c73502',
]);
$bugsMonitor->setHandlers();
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
