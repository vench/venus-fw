<?php
 

namespace vsapp\log;

/**
 * Description of LogFile
 *
 * @author vench
 */
class LogFile implements \vsapp\IObserver {

    
    public function init() {
        \vsapp\Trunk::obs($this, \vsapp\Trunk::TYPE_LOG);
    }

    public function fire(\vsapp\Event $event) {
        $list = (array)$event->getContext();
        
        if(empty($list)) {
            return;
        }
        $message = '';
        
        foreach ($list as $item) {
            list($text, $level, $time) = $item;
            
            $message .= sprintf("Date: %s,\tlevel: %s,\t%s\n", 
                    date('Y-m-d H:i:s', $time), $level, $text);
        }
        
        $filename = 'test.log';
        $handle = fopen($filename, 'w+');
        fwrite($handle, $message);
        fclose($handle);
    }

}
