<?php
 

namespace vsapp\auth;


use \vsapp\ApplyAppableInterface;
use \vsapp\AppContextInterface;

/**
 * Description of WebClient
 *
 * @author vench
 */
class WebClient implements IIdentifier, ApplyAppableInterface {
    
    
    const USER_IDENTIFIER = 'WebClient.identifier';


        
    /**
     *
     * @var ISession
     */
    private $session = null;  

    /**
     *
     * @var IAccess
     */
    private $access = null;

    /**
     * 
     * @return string
     */
    public function getIdentifier() {
        return $this->session->get(self::USER_IDENTIFIER);
    }

    /**
     * 
     * @param string $identifier
     */
    public function setIdentifier($identifier = null) {
        $this->session->set(self::USER_IDENTIFIER, $identifier);
    }
    
    
    /**
     * 
     * @param string $id
     * @return boolean
     */
    public function checkAccess($id) {
        return $this->access->checkAccess($this->getIdentifier(), $id);
    }
    
    
    /**
     * 
     * @param AppContextInterface $app
     */
    public function appInit(AppContextInterface $app) {
        $this->session = $app->get('session'); 
        $this->access  = $app->get('access');
    }

}
