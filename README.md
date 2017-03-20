# Psr3Decorator
This package allows for adding functionality to any existing PSR3 implementation.

After extending the Psr3Decorator class in your own custom logging class, you can 
add functionality by using any of the traits that come with this package.

For example:
```php
use Syndicate\Psr3Decorator\Psr3Decorator;

class MyLogger Extends Psr3Decorator {
    use \Syndicate\Psr3Decorator\Traits\Psr3Tagging;
    use \Syndicate\Psr3Decorator\Traits\Psr3Redaction;
    use \Syndicate\Psr3Decorator\Traits\Psr3Buffer;
}
```

After you have own logging class defined, you can instantiate it by passing in an instance of any PSR3 
implementation.

An example using Monolog:
```php
$monolog = new Logger('PSR3DECORATOR');
$handler = new StreamHandler("php://stdout", Logger::INFO);
$monolog->pushHandler($handler);

$logger = new MyLogger($monolog);
```

The ```$logger``` class will now have all of the methods related to the tagging, redaction, and buffer traits.

### Traits included in this package

#### Psr3Tagging
The tagging trait allows you to add/remove tags for all messsages or contexts passed to the logger.

Message tags will be converted to uppercase and added to the beginning of each message as a JSON encoded array:

```
$logger->addMessageTag("Tag1");
$logger->addMessageTag("AnotherTag");
$logger->warning("Something is wrong");

// will log something like this:
// PSR3DECORATOR.WARNING: (TAGS:[TAG1,ANOTHERTAG]) Something is wrong [] []
```

You can also add context tags, which will be added to each context passed to the logger:
```
$logger->addContextTag("user_id", 418);
$logger->info("User logged in");
$logger->info("User logged out");

// will log something like this:
// PSR3DECORATOR.INFO: User logged in {"user_id":418} []
// PSR3DECORATOR.INFO: User logged out {"user_id":418} []
```

#### Psr3Redaction
The redaction trait allows you to watch for sensitive information, and remove it from anything sent to the logger.

```
$user = array(
    "id"        =>  418,
    "password   =>  "super_secret"
);

$logger->redact($user['password']);

$logger->info("User logged in", $user);
$logger->info("Password is " . $user['password']);

// will log something like this:
// PSR3DECORATOR.INFO: User logged in {"user_id":418,"password":"*** REDACTED ***"} []
// PSR3DECORATOR.INFO: Password is *** REDACTED *** [] []

```

#### Psr3Buffer
The buffer trait allows you to start/stop buffers to capture everything sent to the logger.

```
$logger->startBuffer("user_actions");

$logger->info("User did this");
$logger->info("User did that");

$logger->stopBuffer("user_actions");

$logger->info("more stuff happens");

$actions = $logger->getBuffer("user_actions");

```

#### Psr3MessageContextInterpolation
This trait adds functionality for interpolating data from the context array into the message.

```
$user = array(
    "id"    =>  418,
    "name"  =>  "shannon"
);

$this->info("User {name} has logged out", $user);
 
// will log something like:
// PSR3DECORATOR.INFO: User shannon has logged out {"id":418,"name":"shannon"} [] 
```

In order to use this interpolation function, you will need to add an additional step after importing the trait.
You'll need to over-ride the ```init()``` method in your logging class and enable message interpolation:

``` 
class MyLogger extends Psr3Decorator
{
    use \Syndicate\Psr3Decorator\Traits\Psr3MessageContextInterpolation;

    public function init()
    {
        $this->setMessageInterpolation(true);
    } 
} 
```


### Adding your own functionality

#### Filters
The additional functionality provided by the included traits work by registering filter functions.  Any time a 
logging method is called, the Psr3Decorator loops through the registered filters before passing the filtered message and context on to the underlying Psr3 
implementation.

Two types of filter functions can be registered: *message*, and *context*.
When called, both types will be passed two arguments, ```$message``` and ```$context``` - in that order.
All message filter functions should return the filtered message, and all context filters should return the filtered 
context array.

When registering a filter function, you can optionally supply a priority number.  Filters are ran in order of highest
priority to lowest priority.  If two filters are registered with the same priority number, then they will be ran in the 
order that they were added.  If no priority number is supplied, then a priority of 0 is given to the filter.

For example:
```php
$this->addMessageFilter($filter1, 500);     // will run second
$this->addMessageFilter($filter2, 999);     // will run first
$this->addMessageFilter($filter3);          // will run third
$this->addMessageFilter($filter4, -999);    // will run fifth (last)
$this->addMessageFilter($filter5);          // will run fourth
```

You can also remove filters at any time by passing the filter to the appropriate remove method:
```
$this->addMessageFilter($msg_filter1);
$this->addMessageFilter($msg_filter2);
$this->addContextFilter($ctx_filter1);

$this->removeMessageFilter($msg_filter1);
$this->removeContextFilter($ctx_filter1);
```

### The init method
The constructor method of Psr3Decorator is final, and cannot be over-ridden by your custom logging class.
In order to provide some means of allowing your logger class to do any type of bootstrapping work, there is an
```init()``` method that is over-ridable, and will be called from the constructor.  An example of this can be seen 
above in the Psr3MessageContextInterpolation section.












