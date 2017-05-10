<?php
 
namespace vsapp\auth;


use \vsapp\ApplyAppableInterface;
use \vsapp\AppContextInterface;

/**
 * Description of Access
 *
 * @author vench
 */
class Access implements IAccess , ApplyAppableInterface  {
    
    /**
     *
     * @var array [ 'clientId' =>  [ 'resource list'  ] ] 
     */
    private $accessMap;


    /**
     * 
     * @param string $idClient
     * @param string $id
     * @return boolaen
     */
    public function checkAccess($idClient, $id) {        
         return isset($this->accessMap[$idClient]) && in_array($id, $this->accessMap[$idClient]);
    }
    
   /**
    * 
    * @param array $accessMap  [ 'clientId' =>  [ 'resource list'  ] ] 
    */
    final public function setAccessMap($accessMap) {
        $this->accessMap = $accessMap;
    }

    /**
     * 
     * @param AppContextInterface $app
     */
    public function appInit(AppContextInterface $app) {
        $config = $app->get('config');
        $this->setAccessMap( $config->getValue('accessMap', []) );
    }

}
