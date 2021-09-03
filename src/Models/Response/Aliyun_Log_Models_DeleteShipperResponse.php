<?php
namespace AliSlsLog\Models\Response;
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */


class Aliyun_Log_Models_DeleteShipperResponse extends Aliyun_Log_Models_Response {

    /**
     * Aliyun_Log_Models_DeleteShipperResponse constructor
     *
     * @param array $resp
     *            GetLogs HTTP response body
     * @param array $header
     *            GetLogs HTTP response header
     */
    public function __construct($resp, $header) {
        parent::__construct ( $header );
    }
}