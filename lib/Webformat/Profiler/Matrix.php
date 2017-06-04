<?php

class Webformat_Profiler_Simple extends Webformat_Profiler_Abstract
{

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
            list($c1,$c2) = $res[$k1][$k2];
            $res[$k1][$k2] = array($c1+1,$c2 + $t2 - $t1);
            $keys[] = $k2;
            $k1 = $k2 ; $t1 = $t2;
        }
        $keys = array_flip(array_flip($keys));
        array_walk($res,function(&$a){array_walk($a,function(&$i){$i = $i[1] * 1000 / $i[0];});});
        $file=','.implode(',',$keys)."\n";
        foreach($keys as $k){
            $file .= "$k,".implode(',',array_merge(array_fill_keys($keys,''),$res[$k])) . "\n";
        }
        file_put_contents($filename,$file);
    }

}