<?php

abstract class Webformat_Profiler_Abstract
{
    protected $times = null;


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