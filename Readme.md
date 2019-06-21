# Command To Url

This module allow you to execute command from url

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is CommandToUrl.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/command-to-url-module ~1.0.0
```

## Usage

#### Configuration
By default all commands are disabled (for security reason).  
You must enable them in module configuration page.

For each command you can (and its really encouraged) add two different security :
- A token : you can specify a token who will be needed in url as parameter like this `&token=MY_SECRET_TOKEN`
- Ips : you can specify some trusted ips separated by comma, only theses ips will be able to call this command by url

The two security check must be valid (if they are configured) to execute command

#### Execute command

The url to call commands look like this :
`/command?command=MY_COMMAND&token=MY_TOKEN&arguments[MY_ARGUMENT]=MY_ARGUMENT_VALUE&options[MY_OPTION]=MY_OPTION_VALUE`

Some example with default Thelia commands:
- `/command?command=module:deactivate&token=e16sqdf46er8t4&arguments[module]=Carousel` To deactivate carousel module
- `/command?command=cache:clear&token=e16sqdf46er8t4&options[--env]=prod` To clear prod cache

