<?php

if (!function_exists('logger')) {
    /**
     * @param string $message
     * 
     * @return \Wilkques\Log\Log
     */
    function logger($message = null, $channel = 'system')
    {
        $log = Wilkques\Log\Log::make();

        $log->channel($channel);

        if ($message) {
            return $log->info($message);
        }

        return $log;
    }
}
