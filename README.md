# LARAVEL HTML VALIDATOR
version: 1.0.0

## Description:
Library for laravel to validate HTML by URL.


## Install:
```
composer req idm/laravel-html-validator
```

## Usage:

In your PHP-class (controller / service) just:

**1. Use library** (at the top in usage stage)
```
use IDM\LaravelHtmlValidator\Services\HtmlValidator;
```

**2. Init it**
```
$htmlValidator = new HtmlValidator;
```

**3. Use it**
   
Set the URL of page, which you want to validate:   
```
$htmlValidator->setUrl($url);
```
Get link to validator:
```
$results = $htmlValidator->getLink();
```
Get validation results (array):
```
$results = $htmlValidator->validate();
```
Results:
```
[
  'isValid' => ..,  // true / false, 
  'erros' => ..,    // number of errors 
  'warnings' => .., // number of warnings
  'link' => ..,     // link to validator
  'html' => ..,     // html response from validator
]
```

## Config:
To publish library config to your project run:
```
php artisan vendor:publish
```
And select at list: 
**IDM\LaravelHtmlValidator\ServiceProvider**

Options in config:
```
[
  // Base link to validator service
  'validator_url' => 'https://validator.w3.org/unicorn/check',
  
  // Validator task (used in making full link to validator)
  'validator_task' => 'conformance',
  
  // Count number of warnings or not
  'ignore_warnings' => true,
  
  // Return in results HTML response from validator
  'return_html' => true
]
```

## Requirements:
* PHP
* CURL
* Laravel