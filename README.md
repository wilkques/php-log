# Log for PHP

[![Latest Stable Version](https://poser.pugx.org/wilkques/log/v/stable)](https://packagist.org/packages/wilkques/log)
[![License](https://poser.pugx.org/wilkques/log/license)](https://packagist.org/packages/wilkques/log)

````
composer require wilkques/log
````

## How to use
```php
$log = new \Wilkques\Log\Log;

// or

$log = \Wilkques\Log\Log::channel();

// or

$log = logger();

$log->logName('<change log name>'); // default system.log

$log->path('<change log path>'); // default ./storage/logs

$log->info(123);

$log->debug(123);

$log->warning(123);

$log->error(123);

$log->critical(123);

$log->error(new \Exception(123, 400));

$log->critical(new \Exception(456, 500));

```

output

```log
[2025-05-08 17:31:49] [INFO] : 123 
[Information] 
#0 arguments: 
[]
[2025-05-08 17:31:49] [DEBUG] : 123 
[Information] 
#0 arguments: 
[]
[2025-05-08 17:31:49] [WARNING] : 123 
[Information] 
#0 arguments: 
[]
[2025-05-08 17:31:49] [ERROR] : 123 

[2025-05-08 17:31:49] [CRITICAL] : 123 

[2025-05-08 17:31:49] [ERROR] : 123
(code:400)
C:\works\projects\packages\54\test.php:(60)
[StackTrace]
#0 {main}

[2025-05-08 17:31:49] [CRITICAL] : 456
(code:500)
C:\works\projects\packages\54\test.php:(62)
[StackTrace]
#0 {main}
```