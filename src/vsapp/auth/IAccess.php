<?php

 
namespace vsapp\auth;

/**
 *
 * @author vench
 */
interface IAccess {

    /**
     * @param string $idClient Id client
     * @param string $id Id resource
     * @return boolean Description
     */
    function checkAccess($idClient, $id);
}
