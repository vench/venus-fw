<?php
 

namespace app\controller;

use vsapp\util\View;

/**
 * Description of SiteController
 *
 * @author vench
 */
class SiteController {

    public function actionIndex() {
        View::renderPhp('site/index', [ ]);
    }
}
