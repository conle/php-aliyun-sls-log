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
class Aliyun_Log_Models_GetConfigRequest extends Aliyun_Log_Models_Request {

    private $configName;

    /**
     * Aliyun_Log_Models_GetConfigRequest Constructor
     *
     */
    public function __construct($configName = null) {
        $this->configName = $configName;
    }

    public function getConfigName(){
      return $this->configName;
    }

    public function setConfigName($configName){
      $this->configName = $configName;
    }
    
}
