<?php
namespace AliSlsLog\Models\Request;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */


/**
 * 
 *
 * @author log service dev
 */
class Aliyun_Log_Models_CreateACLRequest extends Aliyun_Log_Models_Request {

    private $acl;
    /**
     * Aliyun_Log_Models_CreateACLRequest Constructor
     *
     */
    public function __construct($acl=null) {
        $this->acl = $acl;
    }

    public function getAcl(){
        return $this->acl;
    }
    public function setAcl($acl){
        $this->acl = $acl;
    }
    
}
