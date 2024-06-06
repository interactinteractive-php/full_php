<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

class Mdintranet_Model extends Model {

    private $glServiceAddress = GF_SERVICE_ADDRESS;
    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }

    public function getIntranedSidebarChildModel() {
        $param = array(
            'systemMetaGroupId' => '1565319206141945',
            'showQuery' => '0',
            'ignorePermission' => 1
        );

        $data = $this->ws->run('serialize', Mddatamodel::$getDataViewCommand, $param, self::$gfServiceAddress);

        if (isset($data['result']) && $data['result']) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        }
    }

    public function getLeftSidebarModel($dataviewId, $categoryid = null) {
        $param = array(
            'systemMetaGroupId' => $dataviewId,
            'showQuery' => '0',
            'ignorePermission' => 1,
            'criteria' => array()
        );

        if ($categoryid) {
            $param['criteria'] = array(
                'parentid' => array(
                    array(
                        'operator' => '=',
                        'operand' => $categoryid
                    )
                )
            );
        } else {
            $param['criteria'] = array(
                'parentid' => array(
                    array(
                        'operator' => '=',
                        'operand' => '0'
                    )
                )
            );
        }

        $data = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && $data['result']) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        }
    }

    public function getSidebarContentModel($dataviewId, $filterParam = array()) {
        (Array) $criteria = array();

        $param = array(
            'systemMetaGroupId' => $dataviewId,
            'showQuery' => '0',
            'ignorePermission' => 1,
            'criteria' => $criteria,
        );

        if ($filterParam) {
            if (isset($filterParam['param']) && $filterParam['param']) {
                foreach ($filterParam['param'] as $key => $row) {
                    $criteria[$key][] = array(
                        'operator' => '=',
                        'operand' => $row
                    );
                }
            }
        }

        $param['criteria'] = $criteria;

        $data = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && $data['result']) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        }
    }

    public function getProcessCodeResult($processId, $params) {

        $processData = $this->db->GetRow("SELECT * FROM META_DATA WHERE META_DATA_ID = " . $processId);
        if (isset($processData['META_DATA_CODE']) && $processData['META_DATA_CODE']) {

            $result = $this->ws->caller('WSDL-DE', GF_SERVICE_ADDRESS, $processData['META_DATA_CODE'], 'return', $params, 'serialize');

            if (isset($result['result']) && $result['result']) {
                unset($result['result']['aggregatecolumns']);
                return $result['result'];
            }
        }

        return array();
    }
    

}
