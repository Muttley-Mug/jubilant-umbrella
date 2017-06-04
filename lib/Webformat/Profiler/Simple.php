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
            if(!array_key_exists($k1,$res))
                $res[$k1] = array($k2 => array(0,0));
            if(!array_key_exists($k2,$res[$k1]))
                $res[$k1][$k2] = array(0,0);
        }
        file_put_contents($filename,$file);
    }

}