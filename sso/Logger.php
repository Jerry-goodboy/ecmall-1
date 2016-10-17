<?php
class Logger {
    
    private static function write($args, $type)
    {
        $msg = "";
        
        foreach ($args as $arg) {
            if($msg != "") $msg .= " ";
            if (!is_string($arg)) $arg = json_encode($arg);
            $msg .= $arg;
        }
        
        error_log(date('Y-m-d H:i:s') . " [$type]" . $msg . "\n", 3, APP_LOG);
    }
    
    public static function info($dummy)
    {
        Logger::write(func_get_args(), "INFO");
    }
    
    public static function debug($dummy)
    {
        Logger::write(func_get_args(), "DEBUG");
    }
    
    public static function warn($dummy)
    {
        Logger::write(func_get_args(), "WARN");
    }
    
    public static function error($dummy)
    {
        $args = func_get_args();
        //$trace = debug_backtrace();
        //Logger::write(array_merge($args, $trace), "ERROR");
        Logger::write($args, "ERROR");
    }
    
}

