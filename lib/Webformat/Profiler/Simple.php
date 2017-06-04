<?php

class Webformat_Profiler_Simple extends Webformat_Profiler_Abstract
{
    protected function enableKeys(){}
    protected function disableKeys(){}

    public function report($filename)
    {
        $res = array();
        list($k1,$t1) = array_shift($this->times);
        $keys = array($k1);
        foreach($this->times as list($k2,$t2)){
        }
        file_put_contents($filename,$file);
    }

}