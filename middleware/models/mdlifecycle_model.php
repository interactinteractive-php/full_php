<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdlifecycle_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }

    // <editor-fold defaultstate="collapsed" desc="lifecycle">

    public function getLifeCycleNameModel($mainLifeCycleId) {

        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $mainLifeCycleId
                ),
        ));

        $param = array(
            'systemMetaGroupId' => '1478589104111',
            'showQuery' => 0,
            'ignorePermission' => 1,
            'criteria' => $criteria
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success') {
            if (isset($data['result'])) {
                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);

                if (isset($data['result'][0])) {
                    return $data['result'][0]['name'];
                }
            }
        } else {
            return false;
        }
    }

    public function getlifeCycleTreeListModel($selectiveId, $param = array()) {

        $criteria = array_merge($param,
            array(
            'selectiveid' => array(
                array(
                    'operator' => '=',
                    'operand' => $selectiveId
                ),
            ))
        );

        $dataViewId = '1484300539192092';
        $param = array(
            'systemMetaGroupId' => $dataViewId,
            'showQuery' => 0,
            'ignorePermission' => 1,
            'criteria' => $criteria
        );

        $this->load->model('mdobject', 'middleware/models/');
        $dataGridOptionData = $this->model->getDVGridOptionsModel($dataViewId);

        if (isset($dataGridOptionData['SORTNAME']) && $dataGridOptionData['SORTNAME'] != '') {
            $param['paging']['sortColumnNames'] = array(
                $dataGridOptionData['SORTNAME'] => array(
                    'sortType' => $dataGridOptionData['SORTORDER']
                )
            );
        }

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success') {
            if (isset($data['result'])) {

                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);

                if (isset($data['result'][0]['wfmstatusname'])) {
                    array_walk($data['result'], function(&$value) {
                        $value['wfmstatusname'] = Lang::line($value['wfmstatusname']);
                    });
                }

                return $data['result'];
            }
        }

        return array();
    }

    public function getlifeCycleTreeListModel_v1($lifecycleId, $recordId, $param = array(), $lifecycletaskId = null, $treeDvId = null) {

        $criteria = array_merge($param,
            array(
                'lifecycleid' => array(
                    array(
                        'operator' => '=',
                        'operand' => $lifecycleId
                    ),
                ),
                'recordid' => array(
                    array(
                        'operator' => '=',
                        'operand' => $recordId
                    )
                )
            )
        );

        if ($lifecycletaskId) {
            $criteria = array_merge($criteria, array(
                'lifecycletaskId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $lifecycletaskId
                    )
                )
            ));
        }

        $dataViewId = ($treeDvId) ? $treeDvId : '1525850830437866';
        
        $param = array(
            'systemMetaGroupId' => $dataViewId,
            'showQuery' => 0,
            'ignorePermission' => 1,
            'criteria' => $criteria
        );

        $this->load->model('mdobject', 'middleware/models/');
        $dataGridOptionData = $this->model->getDVGridOptionsModel($dataViewId);

        if (isset($dataGridOptionData['SORTNAME']) && $dataGridOptionData['SORTNAME'] != '') {
            $param['paging']['sortColumnNames'] = array(
                $dataGridOptionData['SORTNAME'] => array(
                    'sortType' => $dataGridOptionData['SORTORDER']
                )
            );
        }

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        $this->load->model('mdlifecycle', 'middleware/models/');
        
        if ($data['status'] == 'success' && isset($data['result'])) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            if (isset($data['result'][0]['wfmstatusname'])) {
                array_walk($data['result'], function(&$value) {
                    $value['wfmstatusname'] = Lang::line($value['wfmstatusname']);
                });
            }

            return $data['result'];
        }

        return array();
    }

    public function countAttachedFilesModel($id, $recordId) {
        $cnt = $this->db->GetOne("SELECT COUNT(1) FROM ECM_CONTENT_MAP WHERE REF_STRUCTURE_ID = $id AND RECORD_ID = $recordId");
        return $cnt;
    }

    // </editor-fold>
}