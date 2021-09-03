<?php
namespace AliSlsLog\Models\Response;
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */


/**
 * The response of the DeleteShard API from log service.
 *
 * @author log service dev
 */
class Aliyun_Log_Models_DeleteShardResponse extends Aliyun_Log_Models_Response {
    /**
     * Aliyun_Log_Models_DeleteShardResponse constructor
     *
     * @param array $header
     *            DeleteShard HTTP response header
     */
    public function __construct($headers) {
        parent::__construct ( $headers );
    }
}
