<?php
class Webformat_Logger
{
    private $file_handle = null;
    private $logLevel = 0;
    private $printCallbacks;

    public function __construct($filename)
    {
        $this->file_handle = fopen($filename,'a');
        $this->printCallbacks= array();
    }

    public function enable($level=10)
    {
        $this->logLevel = is_null($this->file_handle) ? 0 : (int) $level;
    }

    public function disable()
    {
        $this->logLevel = 0;
    }

    private function  printWithCallback($value,$name = null)
    {
        $prefix = is_string($name) ? "$name = " : '';
        $type = gettype($value);
        switch($type){
            case 'boolean': $prefix .= $value ? 'true' : 'false'; break;
            case 'string':
            case 'integer':
            case 'double':
                $prefix .= $value;
                break;
            case 'NULL':
                $prefix .= 'NULL';
                break;
            case 'object':
            case 'resource':
            case 'array':
                if(is_string($name) && array_key_exists($name,$this->printCallbacks) && is_callable($this->printCallbacks[$name])){
                    $prefix .= call_user_func($this->printCallbacks[$name],$value);
                    break;
                }
                if(is_object($value)){
                    $candidates = array_filter($this->printCallbacks,function($e) use ($value){return is_a($value,$e);},ARRAY_FILTER_USE_KEY);
                    if(!empty($candidates)){
                        uksort($candidates,function($class1,$class2){return is_subclass_of($class2,$class1) ? -1 : 1;});
                        $prefix .= call_user_func(reset($candidates),$value);
                        break;
                    }
                }
                $prefix .= print_r($value,true);
                break;
            default:
                $prefix .= '[Logging error: cannot determine type]';
        }
        if(is_object($value) || is_resource($value)){

        }
        fwrite($this->file_handle, "-------- $prefix \n");
    }

    public function __call($name, $args)
    {

        $level = preg_filter('/^log/', '', $name);
        if (is_null($level) || !is_numeric($level) || $level<1) return;

        if ($level > $this->logLevel) return $this;

        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $fname = $bt[1]['file'];
        $lnum = $bt[1]['line'];
        $time = date('[Y-m-d H:i]');
        fwrite($this->file_handle, "$time $fname $lnum \n");
        $var_list = array_shift($args);
        if(is_array($var_list))
            foreach ($var_list as $name => $value)
                $this-> printWithCallback($value,$name);
        foreach ($args as $arg)
            $this-> printWithCallback($arg);
        return $this;
    }

    public function installPrintCallback($key,$function){
        $this->printCallbacks[$key] = $function;
        return $this;
    }
    public function removePrintCallback($key){
        unset($this->printCallbacks[$key]);
        return $this;
    }
}