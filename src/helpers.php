<?php

if (!function_exists('logger')) {
    /**
     * @param string $message
     * 
     * @return \Wilkques\Log\Log
     */
    function logger($message = null)
    {
        /** @var \Wilkques\Log\Log */
        $log = new Wilkques\Log\Log;

        if ($message) {
            return $log->info($message);
        }

        return $log;
    }
}
