<?php

abstract class Webformat_Profiler_Abstract
{
    protected $times = null;

    protected function defaultKey()
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,2);
        return $bt[1]['file'].'+'.$bt[1]['line'];
    }

    protected function doNotProfile($key){return false;}

    public function profile($key = null)
    {
        if(!$this->enabled) return;
        if(is_null($key)){
            $key = $this->defaultKey();
        }
        if($this->doNotProfile($key)) return;
        if(is_null($this->times))
            $this->times=array(array($key,microtime(true)));
        else
            $this->times[] = array($key,microtime(true));
    }

}