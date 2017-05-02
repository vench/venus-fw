<?php

 

namespace test_;

/**
 * Description of TimeExec
 *
 * @author vench
 */
class TimeExec {
    
    
    private $lastExecTime = 0;



    public function __invoke($object, $type, $result = null) {
        $time = microtime(true);
        echo "<br>", $type, " ", $time, "<br>";
        if($type == 1) {
            echo ($time - $this->lastExecTime), "<br>";
        } else {
            $this->lastExecTime = $time;
        }
    }
}
