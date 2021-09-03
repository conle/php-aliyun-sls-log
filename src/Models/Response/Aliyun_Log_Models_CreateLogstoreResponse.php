<?php
namespace AliSlsLog\Models\Response;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */


/**
 * The response of the CreateLogstore API from log service.
 *
 * @author log service dev
 */
class Aliyun_Log_Models_CreateLogstoreResponse extends Aliyun_Log_Models_Response {
    
    /**
     * Aliyun_Log_Models_CreateLogstoreResponse constructor
     *
     * @param array $resp
     *            CreateLogstore HTTP response body
     * @param array $header
     *            CreateLogstore HTTP response header
     */
    public function __construct($resp, $header) {
        parent::__construct ( $header );
    }
    
}
