<?php

if (!function_exists('logger')) {
    /**
     * @param string $message
     * 
     * @return \Wilkques\Log\Log
     */
    function logger($message = null, $channel = null)
    {
        $log = Wilkques\Log\Log::make();

        if ($channel) {
            $log->channel($channel);
        }

        if ($message) {
            return $log->info($message);
        }

        return $log;
    }
}
