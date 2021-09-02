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


    function putLogs(array $contents, string $topic = 'test')
    {

        $logItem = new Aliyun_Log_Models_LogItem();
        $logItem->setTime(time());
        $logItem->setContents($contents);
        $logitems = array($logItem);
        $request = new Aliyun_Log_Models_PutLogsRequest($this->project, $this->logstore,
            $topic, null, $logitems);

        try {
            $response = $this->client->putLogs($request);
            $this->logVarDump($response);
        } catch (Aliyun_Log_Exception $ex) {
            $this->logVarDump($ex);
        } catch (Exception $ex) {
            $this->logVarDump($ex);
        }
    }

    function listLogstores()
    {
        try {
            $request = new Aliyun_Log_Models_ListLogstoresRequest($this->project);
            $response = $this->client->listLogstores($request);
            $this->logVarDump($response);
        } catch
        (Aliyun_Log_Exception $ex) {
            $this->logVarDump($ex);
        } catch (Exception $ex) {
            $this->logVarDump($ex);
        }
    }


    function listTopics()
    {
        $request = new Aliyun_Log_Models_ListTopicsRequest($this->project, $this->logstore);

        try {
            $response = $this->client->listTopics($request);
            $this->logVarDump($response);
        } catch (Aliyun_Log_Exception $ex) {
            $this->logVarDump($ex);
        } catch (Exception $ex) {
            $this->logVarDump($ex);
        }
    }

    function getLogs()
    {
        $topic = 'TestTopic';
        $from = time() - 3600;
        $to = time();
        $request = new Aliyun_Log_Models_GetLogsRequest($this->project, $this->logstore, $from, $to, $topic, '', 100, 0, False);

        try {
            $response = $this->client->getLogs($request);
            foreach ($response->getLogs() as $log) {
                print $log->getTime() . "\t";
                foreach ($log->getContents() as $key => $value) {
                    print $key . ":" . $value . "\t";
                }

                print "\n";
            }

        } catch
        (Aliyun_Log_Exception $ex) {
            $this->logVarDump($ex);
        } catch (Exception $ex) {
            $this->logVarDump($ex);
        }
    }

    function getProjectLogsWithPowerSql()
    {
        $query = " select count(method) from sls_operation_log where __time__ > to_unixtime(now()) - 300 and __time__ < to_unixtime(now())";
        $request = new Aliyun_Log_Models_GetProjectLogsRequest($this->project, $query, True);

        try {
            $response = $this->client->getProjectLogs($request);
            #$response = $this->client->getProjectLogs($request);
            foreach ($response->getLogs() as $log) {
                print $log->getTime() . "\t";
                foreach ($log->getContents() as $key => $value) {
                    print $key . ":" . $value . "\t";
                }

                print "\n";
            }
            print "proccesedRows:" . $response->getProcessedRows() . "\n";
            print "elapsedMilli:" . $response->getElapsedMilli() . "\n";
            print "cpuSec:" . $response->getCpuSec() . "\n";
            print "cpuCores:" . $response->getCpuCores() . "\n";
            print "requestId:" . $response->getRequestId() . "\n";

        } catch
        (Aliyun_Log_Exception $ex) {
            $this->logVarDump($ex);
        } catch (Exception $ex) {
            $this->logVarDump($ex);
        }
    }

    function crudSqlInstance()
    {
        $res = $this->client->createSqlInstance($this->project, 1000);
        $this->logVarDump($res);
        $res = $this->client->updateSqlInstance($this->project, 999);
        $this->logVarDump($res);
        $res = $this->client->listSqlInstance($this->project);
        $this->logVarDump($res);
    }

    function getHistograms()
    {
        $topic = 'TestTopic';
        $from = time() - 3600;
        $to = time();
        $request = new Aliyun_Log_Models_GetHistogramsRequest($this->project, $this->logstore, $from, $to, $topic, '');

        try {
            $response = $this->client->getHistograms($request);
            $this->logVarDump($response);
        } catch
        (Aliyun_Log_Exception $ex) {
            $this->logVarDump($ex);
        } catch (Exception $ex) {
            $this->logVarDump($ex);
        }
    }

    function listShard()
    {
        $request = new Aliyun_Log_Models_ListShardsRequest($this->project, $this->logstore);
        try {
            $response = $this->client->listShards($request);
            $this->logVarDump($response);
        } catch
        (Aliyun_Log_Exception $ex) {
            $this->logVarDump($ex);
        } catch (Exception $ex) {
            $this->logVarDump($ex);
        }
    }

    function batchGetLogs()
    {
        $listShardRequest = new Aliyun_Log_Models_ListShardsRequest($this->project, $this->logstore);
        $listShardResponse = $this->client->listShards($listShardRequest);
        foreach ($listShardResponse->getShardIds() as $shardId) {
            $getCursorRequest = new Aliyun_Log_Models_GetCursorRequest($this->project, $this->logstore, $shardId, null, time() - 60);
            $response = $this->client->getCursor($getCursorRequest);
            $cursor = $response->getCursor();
            $count = 100;
            while (true) {
                $batchGetDataRequest = new Aliyun_Log_Models_BatchGetLogsRequest($this->project, $this->logstore, $shardId, $count, $cursor);
                $this->logVarDump($batchGetDataRequest);
                $response = $this->client->batchGetLogs($batchGetDataRequest);
                if ($cursor == $response->getNextCursor()) {
                    break;
                }

                $logGroupList = $response->getLogGroupList();
                foreach ($logGroupList as $logGroup) {
                    print ($logGroup->getCategory());

                    foreach ($logGroup->getLogsArray() as $log) {
                        foreach ($log->getContentsArray() as $content) {
                            print($content->getKey() . ":" . $content->getValue() . "\t");
                        }
                        print("\n");
                    }
                }
                $cursor = $response->getNextCursor();
            }
        }
    }

    function batchGetLogsWithRange()
    {
        $listShardRequest = new Aliyun_Log_Models_ListShardsRequest($this->project, $this->logstore);
        $listShardResponse = $this->client->listShards($listShardRequest);
        foreach ($listShardResponse->getShardIds() as $shardId) {
            //pull data which reached server at time range [now - 60s, now) for every shard
            $curTime = time();
            $beginCursorResponse = $this->client->getCursor(new Aliyun_Log_Models_GetCursorRequest($this->project, $this->logstore, $shardId, null, $curTime - 60));
            $beginCursor = $beginCursorResponse->getCursor();
            $endCursorResponse = $this->client->getCursor(new Aliyun_Log_Models_GetCursorRequest($this->project, $this->logstore, $shardId, null, $curTime));
            $endCursor = $endCursorResponse->getCursor();
            $cursor = $beginCursor;
            print("-----------------------------------------\nbatchGetLogs for shard: " . $shardId . ", cursor range: [" . $beginCursor . ", " . $endCursor . ")\n");
            $count = 100;
            while (true) {
                $batchGetDataRequest = new Aliyun_Log_Models_BatchGetLogsRequest($this->project, $this->logstore, $shardId, $count, $cursor, $endCursor);
                $response = $this->client->batchGetLogs($batchGetDataRequest);
                $logGroupList = $response->getLogGroupList();
                $logGroupCount = 0;
                $logCount = 0;
                foreach ($logGroupList as $logGroup) {
                    $logGroupCount += 1;
                    foreach ($logGroup->getLogsArray() as $log) {
                        $logCount += 1;
                        foreach ($log->getContentsArray() as $content) {
                            print($content->getKey() . ":" . $content->getValue() . "\t");
                        }

                        print("\n");
                    }
                }
                $nextCursor = $response->getNextCursor();
                print("batchGetLogs once, cursor: " . $cursor . ", nextCursor: " . nextCursor . ", logGroups: " . $logGroupCount . ", logs: " . $logCount . "\n");
                if ($cursor == $nextCursor) {
                    //read data finished
                    break;
                }
                $cursor = $nextCursor;
            }
        }
    }

    function mergeShard()
    {
        $request = new Aliyun_Log_Models_MergeShardsRequest($this->project, $this->logstore, $shardId);
        try {
            $response = $this->client->mergeShards($request);
            $this->logVarDump($response);
        } catch
        (Aliyun_Log_Exception $ex) {
            $this->logVarDump($ex);
        } catch (Exception $ex) {
            $this->logVarDump($ex);
        }
    }

    function splitShard()
    {
        $request = new Aliyun_Log_Models_SplitShardRequest($this->project, $this->logstore, $shardId, $midHash);
        try {
            $response = $this->client->splitShard($request);
            $this->logVarDump($response);
        } catch
        (Aliyun_Log_Exception $ex) {
            $this->logVarDump($ex);
        } catch (Exception $ex) {
            $this->logVarDump($ex);
        }
    }

    function logVarDump($expression)
    {
        print "<br>loginfo begin = " . get_class($expression) . "<br>";
        var_dump($expression);
        print "<br>loginfo end<br>";
    }
}
