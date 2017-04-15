<?php
 

namespace vsapp\log;

/**
 * Description of LogFile
 *
 * @author vench
 */
class LogFile implements \vsapp\IObserver {

    public function init() {
        \vsapp\Trunk::getInstance()->addObserver($this, \vsapp\Trunk::TYPE_LOG);
    }

    public function fire(\vsapp\Event $event) {
        var_dump($event);
    }

}
