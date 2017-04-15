<?php
 

namespace vsapp;

/**
 *
 * @author vench
 */
interface IObserver {

    function fire(Event $event);
}
