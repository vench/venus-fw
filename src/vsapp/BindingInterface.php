<?php
 

namespace vsapp;

/**
 *
 * @author vench
 */
interface BindingInterface {

    /**
     * @return array Map obkect field name => className instance 
     */
    function getBindMap();
}
