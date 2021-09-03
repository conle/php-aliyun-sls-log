<?php
namespace AliSlsLog\Models;
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */


class Aliyun_Log_Models_Machine_Status{
      public $binaryCurVersion;
      public $binaryDeployVersion;
      
      public function __construct($binaryCurVersion=null,$binaryDeployVersion=null){
          $this->binaryCurVersion = $binaryCurVersion;
          $this->binaryDeployVersion = $binaryDeployVersion;
      }

      public function toArray(){
          $resArr = array();
          if($this->binaryCurVersion!==null)
            $resArr['binaryCurVersion'] = $this->binaryCurVersion;
          if($this->binaryDeployVersion!==null)
            $resArr['binaryDeployVersion'] = $this->binaryDeployVersion;
          return $resArr;
      }
}

