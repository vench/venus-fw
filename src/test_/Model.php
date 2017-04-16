<?php
 

namespace test_;

/**
 * Description of Model
 *
 * @author vench
 */
class Model implements \vsapp\BindingInterface {

    public $app;


    public function getBindMap() {
        return [
            'app' => '\vsapp\App',
        ];
    }

}
