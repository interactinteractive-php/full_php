<form id="changeWfmStatusForm_<?php echo $this->metaDataId ?>" method="post" enctype="multipart/form-data" class="">
    <div class="" style="background: #f1f4f7; ">
        <div class="main-action-meta bp-banner-container bp-window-<?php echo $this->uniqId ?>" data-meta-type="process" data-process-id="<?php echo $this->uniqId ?>" data-bp-uniq-id="<?php echo $this->uniqId ?>">
            <div class="col-md-12 center-sidebar">  
                <div class="table-scrollable table-scrollable-borderless bp-header-param">
                    <table class="table table-sm table-bordered bp-header-param customerInfo-table_<?php echo $this->uniqId ?>">
                        <tbody style=" ">

                            <?php if (isset($this->data['isprimary']) && $this->data['isprimary'] === '2') { ?>
                                <tr>
                                    <td colspan="4"><strong>1.1. Иргэн мэдээлэл</strong></td>
                                </tr>
                                <tr>                                            
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Эцэг (эхийн) нэр:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['lastname']) ? $this->data['lastname'] : ''; ?></td>
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Нэр:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['firstname']) ? $this->data['firstname'] : ''; ?></td>
                                </tr>
                                <tr>                                            
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Регистрийн дугаар:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['registernumber']) ? $this->data['registernumber'] : ''; ?></td>
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Иргэншил:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['nationality']) ? $this->data['nationality'] : '' ?></td>
                                </tr>

                            <?php } else { ?>

                                <tr>
                                    <td colspan="4"><strong>1.1. Байгууллагын мэдээлэл</strong></td>
                                </tr>
                                <tr>                                            
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Байгууллагын нэр:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['firstname']) ? $this->data['firstname'] : ''; ?></td>
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Улсын бүртгэлийн дугаар:</span>
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['countryregisternumber']) ? $this->data['countryregisternumber'] : ''; ?></td>
                                </tr>
                                <tr>                                            
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Регистрийн дугаар:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['registernumber']) ? $this->data['registernumber'] : ''; ?></td>
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Иргэншил:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['nationality']) ? $this->data['nationality'] : '' ?></td>
                                </tr>

                            <?php } ?>

                            <tr>                                              
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Үйл ажиллагаа явуулж эхэлсэн огноо:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['servicestartdate']) ? $this->data['servicestartdate'] : '' ?></td>                                         
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Өдрийн орлогын дундаж хэмжээ:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['dailyincome']) ? Number::formatMoney($this->data['dailyincome']) : ''; ?></td>
                            </tr>
                            <tr>    
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Өдөрт үйлчилүүлэгчдийн дундаж тоо:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['dailycustomer']) ? $this->data['dailycustomer'] : '' ?></td>                                         
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Кассын тоо:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['cashiercount']) ? $this->data['cashiercount'] : ''; ?></td>
                            </tr>
                            <tr>                                            
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Имэйл хаяг:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['email']) ? $this->data['email'] : '' ?></td>                                         
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Утасны дугаар 1:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['mobilephone']) ? $this->data['mobilephone'] : ''; ?></td>
                            </tr>
                            <tr>                            
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Мессеж илгээх утасны дугаар:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['messagephone']) ? $this->data['messagephone'] : '' ?></td>
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <?php echo (isset($this->levelType) && !empty($this->levelType)) ? '<span>Эзэмшигчийн зэрэглэл:</span>' : '' ?>
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php 

                                    $levelTypeArray = array(
                                        'name' => 'levelType',
                                        'id' => 'levelType',
                                        'class' => 'form-control select2',
                                        'data' => $this->levelType,
                                        'op_value' => 'id',
                                        'op_text' => 'name',
                                        'required' => 'required',
                                        'value' => isset($this->data['priorityid']) ? $this->data['priorityid'] : '',
                                    );

                                    if (isset($this->readonly) && $this->readonly) {
                                        $levelTypeArray['disabled'] = 'disabled';
                                    }

                                    echo Form::select($levelTypeArray);
                                ?></td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>1.2. Үндсэн хаяг</strong></td>
                            </tr>
                            <tr>                                            
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Аймаг, нийслэл:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['cityname']) ? $this->data['cityname'] : '' ?></td>
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Сум, дүүрэг:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['districtname']) ? $this->data['districtname'] : '' ?></td>

                            </tr>
                            <tr>                                            
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Баг, хороо:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['streetname']) ? $this->data['streetname'] : '' ?></td>
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Хаяг дэлгэрэнгүй:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($this->data['address']) ? $this->data['address'] : '' ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($this->data['posgetmerchantrequestlist']) && !empty($this->data['posgetmerchantrequestlist'])) {
        $index = 1;  
        ?>

        <div class="" style="background: #f1f4f7; ">
            <div class="main-action-meta bp-banner-container bp-window-<?php echo $this->uniqId ?>" data-meta-type="process" data-process-id="<?php echo $this->uniqId ?>" data-bp-uniq-id="1529976174445972">

                    <input type="hidden" name="customerInvoiceBookId" value="<?php echo $this->data['id'] ?>" />
                    <?php foreach ($this->data['posgetmerchantrequestlist'] as $key => $row) { ?>
                        <input type="hidden" name="kubInvoiceBookId[]" value="<?php echo $row['id'] ?>" />
                        <div class="col-md-12 center-sidebar">  
                            <div class="table-scrollable table-scrollable-borderless bp-header-param">
                                <table class="table table-sm table-bordered bp-header-param customerInfo-table_<?php echo $this->uniqId ?>">
                                    <tbody>

                                        <tr>
                                            <td colspan="4"><strong>2.<?php echo $index ?> КҮБ-ын мэдээлэл</strong></td>
                                        </tr>
                                        <tr>                                            
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span>Үйлчилгээний газрын нэр:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""><?php echo isset($row['merchantname']) ? $row['merchantname'] : '' ?></td>
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">Худалдааны төвийн нэр</td>
                                            <td class="middle" style="width: 27%" colspan=""><?php 
                                                $centerListSelection = array(
                                                    'name' => 'storeId[]',
                                                    'id' => 'storeId',
                                                    'class' => 'form-control select2',
                                                    'op_value' => 'id',
                                                    'op_text' => 'name',
                                                    'required' => 'required',
                                                    'value' => isset($row['storename']) ? $row['storename'] : ''
                                                );

                                                if (isset($this->centerList) && !empty($this->centerList)) {
                                                    $centerListSelection['data'] = $this->centerList;
                                                }

                                                if (isset($this->updateType) && $this->updateType === '3') {
                                                    $centerListSelection['disabled'] = 'disabled';
                                                }

                                                if (isset($this->readonly) && $this->readonly) {
                                                    $centerListSelection['disabled'] = 'disabled';
                                                }

                                                echo Form::select($centerListSelection);

                                            ?></td>
                                        </tr>
                                        <tr>                                            
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span>Борлуулалтын мэргэжилтэн:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""><?php 
                                                $posRepairmanSelection = array(
                                                                            'name' => 'salesManId[]',
                                                                            'id' => 'salesManId',
                                                                            'class' => 'form-control select2',
                                                                            'op_value' => 'id',
                                                                            'op_text' => 'username',
                                                                            'required' => 'required',
                                                                            'value' => isset($row['salesmanid']) ? $row['salesmanid'] : '',
                                                                        );

                                                if (isset($this->posRepairman) && !empty($this->posRepairman)) {
                                                    $posRepairmanSelection['data'] = $this->posRepairman;
                                                }

                                                if (isset($this->posRepairManReadonly) && $this->posRepairManReadonly) {
                                                    $posRepairmanSelection['disabled'] = 'disabled';
                                                }

                                                if (isset($this->readonly) && $this->readonly) {
                                                    $posRepairmanSelection['disabled'] = 'disabled';
                                                }
                                                
                                                echo Form::select($posRepairmanSelection);
                                            ?></td>
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;"></td>
                                            <td class="middle" style="width: 27%" colspan=""></td>
                                        </tr>
                                        <tr>                                            
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span>Дансны дугаар:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""><?php echo isset($row['accountnumber']) ? $row['accountnumber'] : '' ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="height: 39px"></td>
                                        </tr>
                                        <tr>                                            
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span>Аймаг, нийслэл:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""><?php echo isset($row['cityname']) ? $row['cityname'] : '' ?></td>
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span>Сум, дүүрэг:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""><?php echo isset($row['districtname']) ? $row['districtname'] : '' ?></td>

                                        </tr>
                                        <tr>                                            
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span>Баг, хороо:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""><?php echo isset($row['streetname']) ? $row['streetname'] : '' ?></td>
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span>Хаяг дэлгэрэнгүй:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""><?php echo isset($row['address']) ? $row['address'] : '' ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="height: 39px"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span>Хариуцсан ажилтаны нэр:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""><?php echo isset($row['contactname']) ? $row['contactname'] : '' ?></td>
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span>Албан тушаал:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""><?php echo isset($row['contactposition']) ? $row['contactposition'] : '' ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span>Утасны дугаар:</span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""><?php echo isset($row['contactphone']) ? $row['contactphone'] : '' ?></td>
                                            <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                                <span></span>                                            
                                            </td>
                                            <td class="middle" style="width: 27%" colspan=""></td>
                                        </tr>

                                    </tbody>
                                </table>
                                <table class="table table-sm table-bordered bp-header-param customerInfo-table_<?php echo $this->uniqId ?>">
                                    <tbody>
                                        <tr>
                                            <td colspan="4"><strong>2.<?php echo $index; ?>.1 Терминалын мэдээлэл</strong></td>
                                        </tr>
                                        <?php 
                                        if (isset($row['posgetterminalrequestlist']) && !empty($row['posgetterminalrequestlist'])) {  ?>

                                            <?php 
                                            $sindex = 1; ?>
                                            <tr style='background: #f8f9da'>
                                                <td colspan="2"><strong>Посын нэр</strong></td>
                                                <td colspan="2"><strong>MCC</strong></td>
                                                <td colspan="2"><strong>Агент</strong></td>
                                                <td class="hidden"><strong>Cashback</strong></td>
                                                <td class="hidden"><strong>Улирал</strong></td>
                                            </tr>
                                            <?php 

                                            foreach ($row['posgetterminalrequestlist'] as $skey => $sRow) { ?>
                                                <tr style='background: #f8f9da'>
                                                    <td colspan="2"><?php echo $sRow['posname']; ?></td>
                                                    <td colspan="2"><?php echo isset($sRow['industryname']) ? $sRow['industryname'] : ''; $sRow['industryname']; ?></td>
                                                    <td colspan="2"><?php echo isset($sRow['departmentname']) ? $sRow['departmentname'] : ''; ?></td>
                                                    <td class="hidden"><?php echo (isset($sRow['iscashback']) && $sRow['iscashback'] === '1') ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></td>
                                                    <td class="hidden"><?php echo (isset($sRow['isseason']) && $sRow['isseason'] === '1') ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></td>
                                                </tr>
                                            <?php } 

                                            foreach ($row['posgetterminalrequestlist'] as $skey => $sRow) {

                                                if (isset($sRow['statusData']) && !empty($sRow['statusData'])) {
                                                    if ($sindex === 1) { ?>
                                                    <tr>
                                                        <td><strong>Посын нэр</strong></td>
                                                        <td><strong>MCC</strong></td>
                                                        <td><strong>Агент</strong></td>
                                                        <td><strong>Төлөв</strong></td>
                                                        <td class="wfmstatus-path wfmstatus-path-<?php echo $row['id'] ?>"><strong>Татгалзсан шалтгаан</strong></td>
                                                        <td><strong>Тайлбар</strong></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td><?php 
                                                    
                                                            if ($sRow['isreadonly']) {
                                                                echo Form::hidden(array('name' => 'terminalId['. $row['id'] .'][]', 'value' => $sRow['id'], 'disabled' => 'disabled')); 
                                                                echo Form::hidden(array('name' => 'wfmstatusid['. $row['id'] .'][]', 'value' => $sRow['wfmstatusid'], 'disabled' => 'disabled')); 
                                                                echo Form::hidden(array('name' => 'dataviewid['. $row['id'] .'][]', 'value' => $sRow['dataviewid'], 'disabled' => 'disabled')); 
                                                                echo Form::text(array('id' => '', 'class' => 'form-control', 'disabled' => 'disabled', 'value' => $sRow['posname'], 'disabled' => 'disabled')); 
                                                            } else {
                                                                echo Form::hidden(array('name' => 'terminalId['. $row['id'] .'][]', 'value' => $sRow['id'])); 
                                                                echo Form::hidden(array('name' => 'wfmstatusid['. $row['id'] .'][]', 'value' => $sRow['wfmstatusid'])); 
                                                                echo Form::hidden(array('name' => 'dataviewid['. $row['id'] .'][]', 'value' => $sRow['dataviewid'])); 
                                                                echo Form::text(array('id' => '', 'class' => 'form-control', 'disabled' => 'disabled', 'value' => $sRow['posname'])); 
                                                            }
                                                            
                                                        ?></td>
                                                    <td><?php 
                                                        $industryArr = array(
                                                                            'name' => 'tempIndustryType['. $sRow['id'] .'][]',
                                                                            'class' => 'form-control select2 industryType',
                                                                            'op_value' => 'id',
                                                                            'op_text' => '(|industrycode|)| |industryname',
                                                                            'value' => $sRow['industryid'],
                                                                            'required' => 'required',
                                                                            'onchange' => 'callChange_'.$this->uniqId.'(this, \'industryType\')'
                                                                        );
                                                        
                                                        if (isset($this->industryType) && !empty($this->industryType)) { 
                                                            $industryArr['data'] = $this->industryType;
                                                        }

                                                        if ($sRow['isreadonly']) {
                                                            $industryArr['disabled'] = 'disabled';
                                                        }
                                                        
                                                        if (isset($this->readonly) && $this->readonly) {
                                                            $industryArr['disabled'] = 'disabled';
                                                        }
                                                        
                                                        echo Form::hidden(array('data-path' => 'industryType', 'name' => 'industryType['. $row['id'] .'][]', 'value' => $sRow['industryid'])); 
                                                        
                                                        /**/
                                                        echo Form::select($industryArr);
                                                    ?></td>
                                                    <td><?php

                                                        $agentList = array(
                                                                        'name' => 'tempAgentid['. $sRow['id'] .'][]',
                                                                        'class' => 'form-control select2 agentid',
                                                                        'op_value' => 'id',
                                                                        'op_text' => '(|departmentcode|)| |agentname',
                                                                        'value' => $sRow['departmentid'],
                                                                        'required' => 'required',
                                                                        'onchange' => 'callChange_'.$this->uniqId.'(this, \'agentid\')'
                                                                    );

                                                        if (isset($this->agentList) && !empty($this->agentList)) { 
                                                            $agentList['data'] = $this->agentList;
                                                        }
                                                        
                                                        if ($sRow['isreadonly']) {
                                                            $agentList['disabled'] = 'disabled';
                                                        }

                                                        if (isset($this->readonly) && $this->readonly) {
                                                            $agentList['disabled'] = 'disabled';
                                                        }
                                                        
                                                        echo Form::hidden(array('data-path' => 'agentid', 'name' => 'agentid['. $row['id'] .'][]', 'value' => $sRow['departmentid'])); 
                                                        echo Form::select($agentList);

                                                    ?></td>
                                                    <td><?php 
                                                        $wfmStatusArr = array(
                                                                'name' => 'tempwfmstatusid['. $sRow['id'] .'][]',
                                                                'class' => 'form-control select2 wfmstatusid',
                                                                'data' => $sRow['statusData'],
                                                                'op_value' => 'wfmstatusid',
                                                                'data-path' => 'wfmstatusid',
                                                                'op_text' => 'wfmstatusname',
                                                                'required' => 'required',
                                                                'data-in-param' => $row['id'],
                                                                'data-out-param' => 'rejectreason_' . $row['id']. '_' . $skey,
                                                                'onchange' => 'callChangeStatus_'.$this->uniqId.'(this)'
                                                            );

                                                        if ($sRow['isreadonly']) {
                                                            $wfmStatusArr['disabled'] = 'disabled';
                                                            echo Form::hidden(array('data-path' => 'newWfmStatusId', 'name' => 'newwfmstatusid['. $row['id'] .'][]', 'value' => '', 'disabled' => 'disabled')); 
                                                        } else {
                                                            echo Form::hidden(array('data-path' => 'newWfmStatusId', 'name' => 'newwfmstatusid['. $row['id'] .'][]', 'value' => '')); 
                                                        }
                                                        
                                                        echo Form::select($wfmStatusArr);
                                                    ?></td>
                                                    <td class="wfmstatus-path wfmstatus-path-<?php echo $row['id'] ?>"><div class="hidden rejectreason_<?php echo $row['id']. '_' . $skey; ?>"><?php 
                                                    if (isset($this->rejectReasonType) && !empty($this->rejectReasonType)) {

                                                        echo Form::select(
                                                            array(
                                                                'name' => 'rejectReasonTypeId['. $row['id'] .']['. $skey .'][]',
                                                                'class' => 'form-control select2',
                                                                'data' => $this->rejectReasonType,
                                                                'op_value' => 'name',
                                                                'op_text' => 'name',
                                                                'multiple' => 'multiple',
                                                                'text' => 'notext'
                                                            )
                                                        );
                                                    }
                                                    ?></div></td>
                                                    <td><textarea type="text" name="description[<?php echo $row['id'] ?>][]" id="description" class="form-control form-control-sm stringInit" <?php echo ($sRow['isreadonly']) ? 'disabled="disabled"' : '' ?>></textarea></td>
                                                </tr>
                                            <?php
                                                $sindex++;
                                                }

                                                if (isset($sRow['wfmlog']) && !empty($sRow['wfmlog'])) { ?>

                                                    <tr>
                                                        <td colspan="4"><strong>2.<?php echo $index; ?>.2 <?php echo $sRow['posname'].' - ны' ?> ҮАЭХорооны шийдвэр</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="text-right"><strong>Хэрэглэгчийн нэр</strong></td>
                                                        <td><strong>Төлөв</strong></td>
                                                        <td><strong>Тайлбар</strong></td>
                                                        <td><strong>Огноо</strong></td>
                                                    </tr>
                                                    <?php foreach ($sRow['wfmlog'] as $lRow) { ?>
                                                            <tr>
                                                                <td colspan="3" class="text-right"><?php echo $lRow['userfullname'] ?></td>
                                                                <td><?php echo $lRow['wfmstatusname'] ?></td>
                                                                <td><?php echo $lRow['wfmdescription'] ?></td>
                                                                <td><?php echo $lRow['createddate'] ?></td>
                                                            </tr>
                                                    <?php 
                                                    }  
                                                }

                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php  
                        $index++; 
                    } ?>

            </div>
        </div> 

    <?php  } ?>

    <?php
    $number = 3;
    if (isset($this->data['getmiddleposwithownerlist']) && !empty($this->data['getmiddleposwithownerlist'])) { 
        $getMiddle = $this->data['getmiddleposwithownerlist'];
        ?>
    <div class="" style="background: #f1f4f7; ">
        <div class="main-action-meta bp-banner-container bp-window-<?php echo $this->uniqId ?>" data-meta-type="process" data-process-id="<?php echo $this->uniqId ?>" data-bp-uniq-id="<?php echo $this->uniqId ?>">
            <div class="col-md-12 center-sidebar">  
                <div class="table-scrollable table-scrollable-borderless bp-header-param">
                    <table class="table table-sm table-bordered bp-header-param customerInfo-table_<?php echo $this->uniqId ?>">
                        <tbody style=" ">

                            <tr>
                                <td colspan="4"><strong><?php echo $number ?> Дундын пос нэмэх харилцагчийн мэдээлэл</strong></td>
                            </tr>

                            <?php if (isset($getMiddle['isprimary']) && $getMiddle['isprimary'] === '1') { ?>
                                <tr>
                                    <td colspan="4"><strong><?php echo $number ?>.1 Байгууллагын мэдээлэл</strong></td>
                                </tr>
                                <tr>                                            
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Байгууллагын нэр:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['firstname']) ? $getMiddle['firstname'] : ''; ?></td>
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Улсын бүртгэлийн дугаар:</span>
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['countryregisternumber']) ? $getMiddle['countryregisternumber'] : ''; ?></td>
                                </tr>
                                <tr>                                            
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Регистрийн дугаар:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['registernumber']) ? $getMiddle['registernumber'] : ''; ?></td>
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Иргэншил:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['countryname']) ? $getMiddle['countryname'] : '' ?></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4"><strong><?php echo $number ?>.1 Иргэн мэдээлэл</strong></td>
                                </tr>
                                <tr>                                            
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Эцэг (эхийн) нэр:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['lastname']) ? $getMiddle['lastname'] : ''; ?></td>
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Нэр:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['firstname']) ? $getMiddle['firstname'] : ''; ?></td>
                                </tr>
                                <tr>                                            
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Регистрийн дугаар:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['registernumber']) ? $getMiddle['registernumber'] : ''; ?></td>
                                    <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                        <span>Иргэншил:</span>                                            
                                    </td>
                                    <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['countryname']) ? $getMiddle['countryname'] : '' ?></td>
                                </tr>

                            <?php } ?>
                            <tr>                                              
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Үйл ажиллагаа явуулж эхэлсэн огноо:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['activitystartdate']) ? $getMiddle['activitystartdate'] : '' ?></td>                                         
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Өдрийн орлогын дундаж хэмжээ:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['dailyavgincome']) ? Number::formatMoney($getMiddle['dailyavgincome']) : ''; ?></td>
                            </tr>
                            <tr>    
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Өдөрт үйлчилүүлэгчдийн дундаж тоо:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['dailyavgcustomer']) ? $getMiddle['dailyavgcustomer'] : '' ?></td>                                         
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Кассын тоо:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['cashnumber']) ? $getMiddle['cashnumber'] : ''; ?></td>
                            </tr>
                            <tr>    
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Дансны дугаар:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['accountnumber']) ? $getMiddle['accountnumber'] : '' ?></td>                                         
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                </td>
                                <td class="middle" style="width: 27%" colspan=""></td>
                            </tr>

                            <tr>
                                <td colspan="4"><strong><?php echo $number ?>.2 Хариуцсан ажилтан</strong></td>
                            </tr>

                            <tr>
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Хариуцсан ажилтаны нэр:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['contactname']) ? $getMiddle['contactname'] : '' ?></td>
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Албан тушаал:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['contactposition']) ? $getMiddle['contactposition'] : '' ?></td>
                            </tr>
                            <tr>
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Утас:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['contactphone']) ? $getMiddle['contactphone'] : '' ?></td>
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                </td>
                                <td class="middle" style="width: 27%" colspan=""></td>
                            </tr>

                            <tr>
                                <td colspan="4"><strong><?php echo $number ?>.3 Үндсэн хаяг</strong></td>
                            </tr>
                            <tr>                                            
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Аймаг, нийслэл:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['cityname']) ? $getMiddle['cityname'] : '' ?></td>
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Сум, дүүрэг:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['districtname']) ? $getMiddle['districtname'] : '' ?></td>

                            </tr>
                            <tr>                                            
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Баг, хороо:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['streetname']) ? $getMiddle['streetname'] : '' ?></td>
                                <td class="text-right middle" style="width: 23%;  background: #fff7f7f2;">
                                    <span>Хаяг дэлгэрэнгүй:</span>                                            
                                </td>
                                <td class="middle" style="width: 27%" colspan=""><?php echo isset($getMiddle['address']) ? $getMiddle['address'] : '' ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php  

    $number = $number + 1;

    } ?>

    <div class="" style="background: #f1f4f7; ">
        <div class="xs-form main-action-meta bp-banner-container bp-window-<?php echo $this->uniqId ?>" data-meta-type="process" data-process-id="<?php echo $this->uniqId ?>" data-bp-uniq-id="1529976174445972">
            <div class="col-md-12 center-sidebar">  
                <div class="table-scrollable table-scrollable-borderless bp-header-param">
                    <table class="table table-sm table-bordered bp-header-param customerInfo-table_<?php echo $this->uniqId ?>">
                        <tbody>

                            <tr>
                                <td colspan="4"><strong><?php echo $number ?>. Хавсралт зураг</strong></td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width: 23%;  background: #fff7f7f2;">
                                    <span></span>                                            
                                </td>
                                <td class="middle" style="width: 77%" colspan="3">
                                    <div data-section-path="assetCode">
                                        <ul class="list-view-photo">
                                            <?php
                                            
                                            if ($this->data['file1']) {
                                                $bigIcon = "assets/core/global/img/meta/photo.png";
                                                $smallIcon = "assets/core/global/img/meta/photo-mini.png";

                                                if (file_exists($this->data['file1'])) {
                                                    $bigIcon = $this->data['file1'];
                                                    $smallIcon = $this->data['file1'];
                                                } else {
                                                    $bigIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                    $smallIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                } 
                                                
                                                $fileExtension = strtolower(substr($this->data['file1'], strrpos($this->data['file1'], '.') + 1));
                                                if ($fileExtension == 'pdf') {
                                                    $smallIcon = 'assets/core/global/img/filetype/64/'. $fileExtension .'.png';
                                                }   
                                                ?>
                                                <li class="shadow " data-attach-id="1" data-src-id="1" data-item="file1">
                                                    <a href="<?php echo $bigIcon; ?>" target="_blank" class="fancybox-button main" data-rel="fancybox-button" title="1">
                                                        <img src="<?php echo $smallIcon; ?>"/>
                                                    </a>
                                                </li>
                                                
                                                <?php
                                            }
                                            
                                            if ($this->data['file2']) {
                                                    $bigIcon = "assets/core/global/img/meta/photo.png";
                                                    $smallIcon = "assets/core/global/img/meta/photo-mini.png";

                                                    if (file_exists($this->data['file2'])) {
                                                        $bigIcon = $this->data['file2'];
                                                        $smallIcon = $this->data['file2'];
                                                    } else {
                                                        $bigIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                        $smallIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                    }
                                                    
                                                    $fileExtension = strtolower(substr($this->data['file2'], strrpos($this->data['file2'], '.') + 1));
                                                    if ($fileExtension == 'pdf') {
                                                        $smallIcon = 'assets/core/global/img/filetype/64/'. $fileExtension .'.png';
                                                    }   
                                                ?>
                                                <li class="shadow " data-attach-id="1" data-src-id="1" data-item="file2">
                                                    <a href="<?php echo $bigIcon; ?>" target="_blank" class="fancybox-button main" data-rel="fancybox-button" title="1">
                                                        <img src="<?php echo $smallIcon; ?>"/>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            
                                            if ($this->data['file3']) {
                                                    $bigIcon = "assets/core/global/img/meta/photo.png";
                                                    $smallIcon = "assets/core/global/img/meta/photo-mini.png";
                                                    
                                                    
                                                    if (file_exists($this->data['file3'])) {
                                                        $bigIcon = $this->data['file3'];
                                                        $smallIcon = $this->data['file3'];
                                                    } else {
                                                        $bigIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                        $smallIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                    }
                                                    
                                                    $fileExtension = strtolower(substr($this->data['file3'], strrpos($this->data['file3'], '.') + 1));
                                                    if ($fileExtension == 'pdf') {
                                                        $smallIcon = 'assets/core/global/img/filetype/64/'. $fileExtension .'.png';
                                                    }   
                                                    
                                                ?>
                                                <li class="shadow " data-attach-id="1" data-src-id="1"  data-item="file3">
                                                    <a href="<?php echo $bigIcon; ?>" target="_blank" class="fancybox-button main" data-rel="fancybox-button" title="1">
                                                        <img src="<?php echo $smallIcon; ?>"/>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            
                                            if ($this->data['file4']) {
                                                    $bigIcon = "assets/core/global/img/meta/photo.png";
                                                    $smallIcon = "assets/core/global/img/meta/photo-mini.png";

                                                    if (file_exists($this->data['file4'])) {
                                                        $bigIcon = $this->data['file4'];
                                                        $smallIcon = $this->data['file4'];
                                                    } else {
                                                        $bigIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                        $smallIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                    }
                                                    
                                                    $fileExtension = strtolower(substr($this->data['file4'], strrpos($this->data['file4'], '.') + 1));
                                                    if ($fileExtension == 'pdf') {
                                                        $smallIcon = 'assets/core/global/img/filetype/64/'. $fileExtension .'.png';
                                                    }   
                                                ?>
                                                <li class="shadow " data-attach-id="1" data-src-id="1" data-item="file4">
                                                    <a href="<?php echo $bigIcon; ?>" target="_blank" class="fancybox-button main" data-rel="fancybox-button" title="1">
                                                        <img src="<?php echo $smallIcon; ?>"/>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            
                                            if ($this->data['file5']) {
                                                    $bigIcon = "assets/core/global/img/meta/photo.png";
                                                    $smallIcon = "assets/core/global/img/meta/photo-mini.png";

                                                    if (file_exists($this->data['file5'])) {
                                                        $bigIcon = $this->data['file5'];
                                                        $smallIcon = $this->data['file5'];
                                                    } else {
                                                        $bigIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                        $smallIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                    }
                                                    
                                                    $fileExtension = strtolower(substr($this->data['file5'], strrpos($this->data['file5'], '.') + 1));
                                                    if ($fileExtension == 'pdf') {
                                                        $smallIcon = 'assets/core/global/img/filetype/64/'. $fileExtension .'.png';
                                                    }   
                                                ?>
                                                <li class="shadow " data-attach-id="1" data-src-id="1" data-item="file5">
                                                    <a href="<?php echo $bigIcon; ?>" target="_blank" class="fancybox-button main" data-rel="fancybox-button" title="1">
                                                        <img src="<?php echo $smallIcon; ?>"/>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            
                                            if ($this->data['file6']) {
                                                    $bigIcon = "assets/core/global/img/meta/photo.png";
                                                    $smallIcon = "assets/core/global/img/meta/photo-mini.png";

                                                    if (file_exists($this->data['file6'])) {
                                                        $bigIcon = $this->data['file6'];
                                                        $smallIcon = $this->data['file6'];
                                                    } else {
                                                        $bigIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                        $smallIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                    }
                                                    
                                                    $fileExtension = strtolower(substr($this->data['file6'], strrpos($this->data['file6'], '.') + 1));
                                                    if ($fileExtension == 'pdf') {
                                                        $smallIcon = 'assets/core/global/img/filetype/64/'. $fileExtension .'.png';
                                                    }   
                                                ?>
                                                <li class="shadow " data-attach-id="1" data-src-id="1" data-item="file6">
                                                    <a href="<?php echo $bigIcon; ?>" target="_blank" class="fancybox-button main" data-rel="fancybox-button" title="1">
                                                        <img src="<?php echo $smallIcon; ?>"/>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            
                                            if ($this->data['file7']) {
                                                    $bigIcon = "assets/core/global/img/meta/photo.png";
                                                    $smallIcon = "assets/core/global/img/meta/photo-mini.png";

                                                    if (file_exists($this->data['file7'])) {
                                                        $bigIcon = $this->data['file7'];
                                                        $smallIcon = $this->data['file7'];
                                                    } else {
                                                        $bigIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                        $smallIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                    }
                                                    
                                                    $fileExtension = strtolower(substr($this->data['file7'], strrpos($this->data['file7'], '.') + 1));
                                                    if ($fileExtension == 'pdf') {
                                                        $smallIcon = 'assets/core/global/img/filetype/64/'. $fileExtension .'.png';
                                                    }   
                                                ?>
                                                <li class="shadow " data-attach-id="1" data-src-id="1" data-item="file7">
                                                    <a href="<?php echo $bigIcon; ?>" target="_blank" class="fancybox-button main" data-rel="fancybox-button" title="1">
                                                        <img src="<?php echo $smallIcon; ?>"/>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            
                                            if ($this->data['file8']) {
                                                    $bigIcon = "assets/core/global/img/meta/photo.png";
                                                    $smallIcon = "assets/core/global/img/meta/photo-mini.png";

                                                    if (file_exists($this->data['file8'])) {
                                                        $bigIcon = $this->data['file8'];
                                                        $smallIcon = $this->data['file8'];
                                                    } else {
                                                        $bigIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                        $smallIcon = 'assets/core/global/img/filetype/64/jpg.png';
                                                    }
                                                    
                                                    $fileExtension = strtolower(substr($this->data['file8'], strrpos($this->data['file8'], '.') + 1));
                                                    if ($fileExtension == 'pdf') {
                                                        $smallIcon = 'assets/core/global/img/filetype/64/'. $fileExtension .'.png';
                                                        //$onclick = "dataViewFileViewer(this, '1', 'pdf', 'storage/uploads/process/201810/file_1539857828231888_15287848239311.pdf', 'https://epp.khanbank.com/emp/storage/uploads/process/201810/file_1539857828231888_15287848239311.pdf', 'undefined');"
                                                    }  
                                                ?>
                                                <li class="shadow " data-attach-id="1" data-src-id="1" data-item="file8">
                                                    <a href="<?php echo $bigIcon; ?>" target="_blank" class="fancybox-button main" data-rel="fancybox-button" title="1">
                                                        <img src="<?php echo $smallIcon; ?>"/>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            
                                            ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    
    $(function () {
        Core.initFancybox($('.list-view-photo'));
        $('body').on('click', '.data-filter > a', function(e) {
            var $this = $(this);
        
            $('.data-filter').find('a').removeClass('green-meadow').addClass('btn-secondary');
            $this.addClass('green-meadow').removeClass('btn-secondary');
            var filterType = $this.attr('data-filter').replace('.', ',');
            
            if (filterType === 'all') {
                $('ul.list-view-photo li.shadow').removeClass('hidden');
            } else {
                $('ul.list-view-photo li.shadow').addClass('hidden');
                $('ul.list-view-photo li[data-src-id*="'+filterType+'"]').removeClass('hidden');
            }
        });
    });
    
    $(function() {
        
        $('.list-view-photo').on('click' , '.dropdown-toggle', function(event){
            var self = $(this);
            var selfHeight = $(this).parent().height();
            var selfWidth = $(this).parent().width();
            var selfOffset = $(self).offset();
            var selfOffsetRigth = $(document).width() - selfOffset.left - selfWidth;
            var dropDown = self.parent().find('ul');
            $(dropDown).css({position:'fixed', top: selfOffset.top + selfHeight, left: 'auto', right: selfOffsetRigth, width: '160px'});
        });
        
        $('.bp-view-photo-action').on('click' , '.dropdown-toggle', function(event){
            var self = $(this);
            var selfHeight = $(this).parent().height();
            var selfWidth = $(this).parent().width();
            var selfOffset = $(self).offset();
            var selfOffsetLeft = $(document).width() - selfOffset.right - selfWidth;
            var dropDown = self.parent().find('ul');
            $(dropDown).css({position:'fixed', top: selfOffset.top + selfHeight, left: 'auto', right: selfOffsetLeft, width: '160px'});
        });
        
    });
    
</script>

<style type="text/css">
    
    .customerInfo-table_<?php echo $this->uniqId ?> tr {
        height: 32px;
    }
    
    .customerInfo-table_<?php echo $this->uniqId ?> ul.list-view-file > li, ul.list-view-photo > li {
        width: 110px !important;
    }
    
    .bp-window-<?php echo $this->uniqId ?> table.bp-header-param {
        table-layout: fixed;
        overflow: hidden;
    }
    
</style>

<script type="text/javascript">
    
    $(function () {
        $(".wfmstatus-path").hide();
    });
    
    
    function callChange_<?php echo $this->uniqId ?>(element, dataPath) {
        var $this = $(element);
        
        $(element).closest('td').find('input[data-path="'+ dataPath +'"]').val($this.val());
    }
    
    function callChangeStatus_<?php echo $this->uniqId ?>(element) {
        var $this = $(element);
        
        $(element).closest('td').find('input[data-path="newWfmStatusId"]').val($this.val());
        
        if (jQuery.inArray($this.val(), ['1530414661733960', '1529390462954467', '1530766341810732']) != -1) {
            /* '1528963418491658', '1530414729357243', '1530417172804921', '1533354517829913', */
            $(".wfmstatus-path-" + $this.attr('data-in-param')).show();
            $("." + $this.attr('data-out-param')).removeClass('hidden');
        } else {
            $(".wfmstatus-path-" + $this.attr('data-in-param')).hide();
            $("." + $this.attr('data-out-param')).addClass('hidden');
        }
    }
    
</script>