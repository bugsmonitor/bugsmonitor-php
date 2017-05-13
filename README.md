# BugSender PHP library

## Installation

### Composer (recommended)
```bash
composer require bugsmonitor/bugsmonitor-php
```

### Manually
1. Download [package](https://github.com/bugsmonitor/bugsmonitor-php) from Github or clone repository
```bash
git clone git@github.com:bugsmonitor/bugsmonitor-php.git
```
2. Add Autoload.php file.
```php
require __DIR__ . '/PATH/TO/BUGSMONITOR/LIBRARY/bugsmonitor-php/src/Autoload.php';
```
## Usage

### Set error handlers
```php
$bugsMonitor = \Bugsmonitor\Bugsmonitor::getInstance();
$bugsMonitor->init([
    'projectKey' => 'YOUR_PROJECT_KEY',
    'apiKey' => 'YOUR_API_KEY',
]);
$bugsMonitor->setHandlers();
```

## Set user

Sometimes you may need add user to bug report, but usually
```php
$bugSender = \Bugsender\Bugsender::getInstance();

# set authorized user
$bugSender->setUser([
    'id' => 123,
    'name' => 'Joe Doe',
    'email' => 'joe.doe@example.com',
]);
``` 

## Prevent from send sensitive data
```php
// default not send keys
'pass',
'password',
'confirm_password',
'password_confirm',
'password_confirmation',

// you can overwrite this with
$bugsMonitor->setStopKeys(Array);

// or add other keys
$bugsMonitor->addStopKeys(Array|String);
```