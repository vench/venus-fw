<?php

namespace app\controller\rest;

/**
 * Description of UserController
 *
 * @author vench
 */
class UserController implements  \vsapp\proxy\AnnotationInterface {

    
    /**
     * 
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult 
     * 
     */
    public function actionAll() {
        return $this->getUsers();
    }
    
    /**
     * 
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult 
     * @param int $id Description
     */
    public function actionOne($id) {
        $data =  $this->getUsers();
        if(!isset($data[$id])) {
            throw new \Exception("Model User {$id} not found!", 404);
        }
        return $data[$id];
    }
    
    /**
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult 
     * @proxy_exec \vsapp\proxy\filters\controller\RequestMethod {"types": ["POST"]}
     */
    public function actionAdd() {
         $this->getUsers();
         //add by post array data
         
         return ["status"=> "OK"];
    }
    
    /**
     * 
     * @param type $id
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult 
     * @proxy_exec \vsapp\proxy\filters\controller\RequestMethod {"types": ["PUT"]}
     */
    public function actionUpdate($id) {
        $data =  $this->getUsers();
        if(!isset($data[$id])) {
            throw new \Exception("Model User {$id} not found!", 404);
        }
        //TODO update by post data
        return $data[$id];
    }
    
    /**
     * 
     * @param type $id
     * @return array
     * @throws \Exception
     * @proxy_exec \vsapp\proxy\filters\controller\JSONResult 
     * @proxy_exec \vsapp\proxy\filters\controller\RequestMethod {"types": ["DELETE"]}
     */
    public function actionDelete($id) {
        $data =  $this->getUsers();
        if(!isset($data[$id])) {
            throw new \Exception("Model User {$id} not found!", 404);
        }
        unset($data[$id]);
        return ["status"=> "OK"];
    }
    
        
    /**
     * 
     * @return array
     */
    private function getUsers() {
        return [
            '1' => [
                'pk'    => 1,
                'name'  => 'Ben',
                'age'   => 23,
                'male'  => 'm',
            ],
            '2' => [
                'pk'    => 2,
                'name'  => 'Iban',
                'age'   => 25,
                'male'  => 'm',
            ],
            '3' => [
                'pk'    => 3,
                'name'  => 'Olga',
                'age'   => 27,
                'male'  => 'f',
            ],
            '4' => [
                'pk'    => 4,
                'name'  => 'Mary',
                'age'   => 21,
                'male'  => 'f',
            ],
        ];
    }
    
     
    
}
