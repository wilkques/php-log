<?php

if (!function_exists('logger')) {
    /**
     * @param string $message
     * 
     * @return \Wilkques\Log\Log
     */
    function logger($message = null, $channel = 'file')
    {
        /** @var \Wilkques\Log\Log */
        $log = Wilkques\Log\Log::channel($channel);

        if ($message) {
            return $log->info($message);
        }

        return $log;
    }
}
