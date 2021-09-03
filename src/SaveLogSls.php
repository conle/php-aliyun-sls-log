<?php

namespace AliSlsLog;


use AliSlsLog\Models\Request\Aliyun_Log_Models_BatchGetLogsRequest;
use AliSlsLog\Models\Request\Aliyun_Log_Models_GetCursorRequest;
use AliSlsLog\Models\Request\Aliyun_Log_Models_GetHistogramsRequest;
use AliSlsLog\Models\Request\Aliyun_Log_Models_GetLogsRequest;
use AliSlsLog\Models\Request\Aliyun_Log_Models_GetProjectLogsRequest;
use AliSlsLog\Models\Request\Aliyun_Log_Models_ListShardsRequest;
use AliSlsLog\Models\Request\Aliyun_Log_Models_ListTopicsRequest;
use AliSlsLog\Models\Request\Aliyun_Log_Models_MergeShardsRequest;
use AliSlsLog\Models\Request\Aliyun_Log_Models_SplitShardRequest;
use Exception;
use AliSlsLog\Models\Aliyun_Log_Models_LogItem;
use AliSlsLog\Models\Request\Aliyun_Log_Models_ListLogstoresRequest;
use AliSlsLog\Models\Request\Aliyun_Log_Models_PutLogsRequest;

class SaveLogSls
{

    protected $client;
    protected $logstore;
    protected $project;

    public function __construct(array $config, string $logstore, string $token = "")
    {
        $endpoint = $config['end_point'];
        $accessKeyId = $config['access_key'];
        $accessKey = $config['access_key_secret'];
        $this->project = $config['project'];
        $this->logstore = $logstore;

        $this->client = new Aliyun_Log_Client($endpoint, $accessKeyId, $accessKey, $token);
    }

    /**
     * 拼接成功返回值
     * @param $request_id
     * @param array $data
     * @param bool $status
     * @param string $message
     * @return array
     */
    public function logSuccess($request_id, array $data = [], bool $status = true, string $message = 'success')
    {
        return ['request_id' => $request_id, 'data' => $data, 'status' => $status, 'message' => $message];
    }

    /**
     * 拼接失败的返回值
     * @param $request_id
     * @param string $message
     * @param $code
     * @param bool $status
     * @return array
     */
    public function logError(string $message = 'success', $code = 0, $request_id = 0, bool $status = false)
    {
        return ['request_id' => $request_id, 'status' => $status, 'message' => $message];
    }

    /**
     * 存入日志
     * @param array $contents
     * @param string $topic
     * @return Models\Response\Aliyun_Log_Models_PutLogsResponse|false
     */
    public function putLogs(array $contents, string $topic)
    {

        $logItem = new Aliyun_Log_Models_LogItem();
        $logItem->setTime(time());
        $logItem->setContents($contents);
        $logitems = array($logItem);
        $request = new Aliyun_Log_Models_PutLogsRequest($this->project, $this->logstore,
            $topic, null, $logitems);
        try {
            $response = $this->client->putLogs($request);
            return $this->logSuccess($response->getHeader('x-log-requestid'));
        } catch (Aliyun_Log_Exception $ex) {
            return $this->logError($ex->getErrorMessage(), $ex->getErrorCode(), $ex->getRequestId());
        } catch (Exception $ex) {
            return $this->logError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * 获取阿里logstores
     * @return array
     */
    public function listLogstores()
    {
        try {
            $request = new Aliyun_Log_Models_ListLogstoresRequest($this->project);
            $response = $this->client->listLogstores($request);
            return $this->logSuccess($response->getRequestId(), $response->getLogstores());
        } catch (Aliyun_Log_Exception $ex) {
            return $this->logError($ex->getErrorMessage(), $ex->getErrorCode(), $ex->getRequestId());
        } catch (Exception $ex) {
            return $this->logError($ex->getMessage(), $ex->getCode());
        }
    }


    /**
     * 获取topic
     * @return array
     */
    public function listTopics()
    {
        $request = new Aliyun_Log_Models_ListTopicsRequest($this->project, $this->logstore);
        try {
            $response = $this->client->listTopics($request);
            return $this->logSuccess($response->getRequestId(), $response->getTopics());
        } catch (Aliyun_Log_Exception $ex) {
            return $this->logError($ex->getErrorMessage(), $ex->getErrorCode(), $ex->getRequestId());
        } catch (Exception $ex) {
            return $this->logError($ex->getMessage(), $ex->getCode());
        }
    }


    /**
     * 获取日志 默认100条
     * @param string $topic
     * @param int $start_time
     * @param int $end_time
     * @return array
     */
    public function getLogs(string $topic, $start_time = 0, $end_time = 0)
    {
        $from = $start_time > 0 ? $start_time : time() - 3600;
        $to = $end_time > 0 ? $end_time : time();
        $request = new Aliyun_Log_Models_GetLogsRequest($this->project, $this->logstore, $from, $to, $topic, '', 100, 0, False);

        try {
            $response = $this->client->getLogs($request);
            $ret = [];
            foreach ($response->getLogs() as $k => $log) {
                $ret[$k]['time'] = $log->getTime();
                foreach ($log->getContents() as $key => $value) {
                    $ret[$k][$key] = $value;
                }
            }
            return $this->logSuccess($response->getRequestId(), $ret);
        } catch (Aliyun_Log_Exception $ex) {
            return $this->logError($ex->getErrorMessage(), $ex->getErrorCode(), $ex->getRequestId());
        } catch (Exception $ex) {
            return $this->logError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * sql查询日志
     * @param $query
     * @return array
     */
    public function getProjectLogsWithPowerSql($query)
    {
        //$query="select * from log where __time__ > to_unixtime(now()) - 43200 and __time__ < to_unixtime(now())";
        $request = new Aliyun_Log_Models_GetProjectLogsRequest($this->project, $query, True);

        try {
            $response = $this->client->getProjectLogs($request);
            #$response = $this->client->getProjectLogs($request);
            $ret = [];
            foreach ($response->getLogs() as $k => $log) {
                $ret[$k]['time'] = $log->getTime();
                foreach ($log->getContents() as $key => $value) {
                    $ret[$k][$key] = $value;
                }
            }
            $project = [
                'proccesedRows' => $response->getProcessedRows(),
                'elapsedMilli' => $response->getElapsedMilli(),
                'cpuSec' => $response->getCpuSec(),
                'cpuCores' => $response->getCpuCores(),
                'requestId' => $response->getRequestId(),
            ];
            return $this->logSuccess($response->getRequestId(), ['project' => $project, 'log' => $ret]);

        } catch (Aliyun_Log_Exception $ex) {
            return $this->logError($ex->getErrorMessage(), $ex->getErrorCode(), $ex->getRequestId());
        } catch (Exception $ex) {
            return $this->logError($ex->getMessage(), $ex->getCode());
        }
    }


    /**
     * 日志分布
     * @param $topic
     * @param int $start_time
     * @param int $end_time
     * @return array
     */
    public function getHistograms($topic, $start_time = 0, $end_time = 0)
    {
        $from = $start_time > 0 ? $start_time : time() - 3600;
        $to = $end_time > 0 ? $end_time : time();
        $request = new Aliyun_Log_Models_GetHistogramsRequest($this->project, $this->logstore, $from, $to, $topic, '');

        try {
            $response = $this->client->getHistograms($request);
            dd($response,$response->getHistograms());
            return $this->logSuccess($response->getRequestId(),$response->getHistograms());
        } catch (Aliyun_Log_Exception $ex) {
            return $this->logError($ex->getErrorMessage(), $ex->getErrorCode(), $ex->getRequestId());
        } catch (Exception $ex) {
            return $this->logError($ex->getMessage(), $ex->getCode());
        }
    }

}
