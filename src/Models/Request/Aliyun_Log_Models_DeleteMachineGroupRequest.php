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
class Aliyun_Log_Models_DeleteMachineGroupRequest extends Aliyun_Log_Models_Request {


    private $groupName; 
    /**
     * Aliyun_Log_Models_DeleteMachineGroupRequest Constructor
     *
     */
    public function __construct($groupName) {
        $this->groupName = $groupName;
    }

    public function getGroupName(){
        return $this->groupName;
    }

    public function setGroupName($groupName){
        $this->groupName = $groupName;
    }
    
}
