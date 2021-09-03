<?php
namespace AliSlsLog;
/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 */

/**
 * Class Aliyun_Log_LoggerFactory
 * Factory for creating logger instance, with $client, $project, $logstore, $topic configurable.
 * Will flush current logger when the factory instance was recycled.
 */
class aliyun_log_loggerfactory{

    private static $loggermap = array();

    /**
     * get logger instance
     * @param $client valid log client
     * @param $project which could be created in aliyun logger server configuration page
     * @param $logstore which could be created in aliyun logger server configuration page
     * @param null $topic used to specified the log by topic field
     * @return mixed return logger instance
     * @throws exception if the input parameter is invalid, throw exception
     */
    public static function getlogger($client, $project, $logstore, $topic = null){
        if($project === null || $project == ''){
            throw new exception('project name is blank!');
        }
        if($logstore === null || $logstore == ''){
            throw new exception('logstore name is blank!');
        }
        if($topic === null){
            $topic = '';
        }
        $loggerkey = $project.'#'.$logstore.'#'.$topic;
        if (!array_key_exists($loggerkey, static::$loggermap))
        {
            $instancesimplelogger = new aliyun_log_simplelogger($client,$project,$logstore,$topic);
            static::$loggermap[$loggerkey] = $instancesimplelogger;
        }
        return static::$loggermap[$loggerkey];
    }

    /**
     * set modifier to protected for singleton pattern
     * aliyun_log_loggerfactory constructor.
     */
    protected function __construct()
    {

    }

    /**
     * set clone function to private for singleton pattern
     */
    private function __clone()
    {}

    /**
     * flush current logger in destruct function
     */
    function __destruct() {
        if(static::$loggermap != null){
            foreach (static::$loggermap as $innerlogger){
                $innerlogger->logflush();
            }
        }
    }
}
