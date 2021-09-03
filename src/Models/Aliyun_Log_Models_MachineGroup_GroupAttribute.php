<?php
namespace AliSlsLog\Models;
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */

class Aliyun_Log_Models_MachineGroup_GroupAttribute {
    public $externalName;
    public $groupTopic;
    public function __construct($externalName=null,$groupTopic=null){
      $this->externalName = $externalName;
      $this->groupTopic = $groupTopic;
    }
    public function toArray(){
      $resArray = array();
      if($this->externalName!==null)
        $resArray['externalName'] = $this->externalName;
      if($this->groupTopic!==null)
        $resArray['groupTopic'] = $this->groupTopic;
      return $resArray;
    }
}

