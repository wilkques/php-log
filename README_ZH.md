# Log for PHP

[![Latest Stable Version](https://poser.pugx.org/wilkques/log/v/stable)](https://packagist.org/packages/wilkques/log)
[![License](https://poser.pugx.org/wilkques/log/license)](https://packagist.org/packages/wilkques/log)

````
composer require wilkques/log
````

## 使用方式
```php
$log = \Wilkques\Log\Log::make();

// 或

$log = logger();

$log->logName('<change log name>'); // 預設 system.log

$log->setDirectory('<change log path>'); // 預設 ./storage/logs

$log->info(123);

$log->debug(123);

$log->warning(123);

$log->error(123);

$log->critical(123);

$log->error(new \Exception(123));

$log->critical(new \Exception(456));

// 或

Wilkques\Log\Log::info('123');
Wilkques\Log\Log::debug('123');
Wilkques\Log\Log::warning('123');
Wilkques\Log\Log::error('123');
Wilkques\Log\Log::critical('123');
Wilkques\Log\Log::error(new \Exception(123));
Wilkques\Log\Log::critical(new \Exception(456));
```

輸出

```log
[2025-05-14 11:29:14] [INFO] 123
[2025-05-14 11:29:14] [DEBUG] 123
[2025-05-14 11:29:14] [WARNING] 123
[2025-05-14 11:29:14] [ERROR] 123
[2025-05-14 11:29:14] [CRITICAL] 123
[2025-05-14 11:29:14] [ERROR] exception 'Exception' with message '123' in C:\works\projects\packages\54\test.php:70
Stack trace:
#0 {main}
[2025-05-14 11:29:14] [CRITICAL] exception 'Exception' with message '456' in C:\works\projects\packages\54\test.php:71
Stack trace:
#0 {main}
```
