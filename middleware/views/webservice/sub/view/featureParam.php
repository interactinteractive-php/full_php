<div class="bp-view-process-feature">
    <div class="table-scrollable table-scrollable-borderless bp-header-param">
        <?php
        if ($param = Mdwebservice::getFeatureCellIndex($this->paramData, '8')) {
            if (strtolower($param['META_TYPE_CODE']) === 'file') { 
        ?>
        <table class="table table-sm table-no-bordered bp-header-param float-left" style="width: 5% !important;">
            <tbody>
                <tr>
                <?php
                    $filePath = Mdwebservice::findRowKeyValFillData($this->fillParamData, $param['META_DATA_CODE']);
                    $filePath = file_exists($filePath) ? $filePath : 'assets/core/global/img/user.png';
                    echo '<td>';
                    echo '<img src="'.$filePath.'" class="rounded-circle" height="120" width="120">';
                    echo '</td>';
                ?>
                </tr>
            </tbody>
        </table>
        <?php
            }
        }
        ?>
        <table class="table table-sm table-no-bordered bp-header-param" style="width: 84% !important; height: 130px;">
            <tbody>         
                <tr>
                    <?php                               
                    if ($param = Mdwebservice::getFeatureCellIndex($this->paramData, '1')) { ?>
                        <td class="text-right middle" style="width: 14%">
                            <?php
                            $labelAttr = array(
                                'text' => $this->lang->line($param['META_DATA_NAME']),
                                'for' => "param[" . $param['META_DATA_CODE'] . "]",
                                'data-label-path' => $param['META_DATA_CODE']
                            );
                            echo Form::label($labelAttr);
                            ?>
                        </td>
                        <td class="middle" style="width: 27%">
                            <?php
                            echo Mdwebservice::renderViewParamControl($this->methodId, $param, "param[" . $param['META_DATA_CODE'] . "]", $param['META_DATA_CODE'], $this->fillParamData);
                            ?>                                
                        </td>
                        <?php
                    }
                    if ($param = Mdwebservice::getFeatureCellIndex($this->paramData, '2')) { ?>
                        <td class="text-right middle" style="width: 14%">
                            <?php
                            $labelAttr = array(
                                'text' => $this->lang->line($param['META_DATA_NAME']),
                                'for' => "param[" . $param['META_DATA_CODE'] . "]",
                                'data-label-path' => $param['META_DATA_CODE']
                            );
                            echo Form::label($labelAttr);
                            ?>
                        </td>
                        <td class="middle" style="width: 27%">
                            <?php
                            echo Mdwebservice::renderViewParamControl($this->methodId, $param, "param[" . $param['META_DATA_CODE'] . "]", $param['META_DATA_CODE'], $this->fillParamData);
                            ?>                                
                        </td>
                        <?php
                    }                    
                    ?>
                </tr>
                <tr>
                    <?php
                    if ($param = Mdwebservice::getFeatureCellIndex($this->paramData, '3')) { ?>
                        <td class="text-right middle" style="width: 14%">
                            <?php
                            $labelAttr = array(
                                'text' => $this->lang->line($param['META_DATA_NAME']),
                                'for' => "param[" . $param['META_DATA_CODE'] . "]",
                                'data-label-path' => $param['META_DATA_CODE']
                            );
                            echo Form::label($labelAttr);
                            ?>
                        </td>
                        <td class="middle" style="width: 27%">
                            <?php
                            echo Mdwebservice::renderViewParamControl($this->methodId, $param, "param[" . $param['META_DATA_CODE'] . "]", $param['META_DATA_CODE'], $this->fillParamData);
                            ?>                                
                        </td>
                        <?php
                    }
                    if ($param = Mdwebservice::getFeatureCellIndex($this->paramData, '4')) { ?>
                        <td class="text-right middle" style="width: 14%">
                            <?php
                            $labelAttr = array(
                                'text' => $this->lang->line($param['META_DATA_NAME']),
                                'for' => "param[" . $param['META_DATA_CODE'] . "]",
                                'data-label-path' => $param['META_DATA_CODE']
                            );
                            echo Form::label($labelAttr);
                            ?>
                        </td>
                        <td class="middle" style="width: 27%">
                            <?php
                            echo Mdwebservice::renderViewParamControl($this->methodId, $param, "param[" . $param['META_DATA_CODE'] . "]", $param['META_DATA_CODE'], $this->fillParamData);
                            ?>                                
                        </td>
                        <?php
                    }
                    ?>                        
                </tr>
                <tr>
                    <?php
                    if ($param = Mdwebservice::getFeatureCellIndex($this->paramData, '5')) { ?>
                        <td class="text-right middle" style="width: 14%">
                            <?php
                            $labelAttr = array(
                                'text' => $this->lang->line($param['META_DATA_NAME']),
                                'for' => "param[" . $param['META_DATA_CODE'] . "]",
                                'data-label-path' => $param['META_DATA_CODE']
                            );
                            echo Form::label($labelAttr);
                            ?>
                        </td>
                        <td class="middle" style="width: 27%">
                            <?php
                            echo Mdwebservice::renderViewParamControl($this->methodId, $param, "param[" . $param['META_DATA_CODE'] . "]", $param['META_DATA_CODE'], $this->fillParamData);
                            ?>                                
                        </td>
                        <?php
                    }
                    if ($param = Mdwebservice::getFeatureCellIndex($this->paramData, '6')) { ?>
                        <td class="text-right middle" style="width: 14%">
                            <?php
                            $labelAttr = array(
                                'text' => $this->lang->line($param['META_DATA_NAME']),
                                'for' => "param[" . $param['META_DATA_CODE'] . "]",
                                'data-label-path' => $param['META_DATA_CODE']
                            );
                            echo Form::label($labelAttr);
                            ?>
                        </td>
                        <td class="middle" style="width: 27%">
                            <?php
                            echo Mdwebservice::renderViewParamControl($this->methodId, $param, "param[" . $param['META_DATA_CODE'] . "]", $param['META_DATA_CODE'], $this->fillParamData);
                            ?>                                
                        </td>
                        <?php
                    }                    
                    ?>                        
                </tr>
                <tr>
                    <?php
                    if ($param = Mdwebservice::getFeatureCellIndex($this->paramData, '7')) { ?>
                        <td class="text-right middle" style="width: 14%">
                            <?php
                            $labelAttr = array(
                                'text' => $this->lang->line($param['META_DATA_NAME']),
                                'for' => "param[" . $param['META_DATA_CODE'] . "]",
                                'data-label-path' => $param['META_DATA_CODE']
                            );
                            echo Form::label($labelAttr);
                            ?>
                        </td>
                        <td class="middle" style="width: 27%">
                            <?php
                            echo Mdwebservice::renderViewParamControl($this->methodId, $param, "param[" . $param['META_DATA_CODE'] . "]", $param['META_DATA_CODE'], $this->fillParamData);
                            ?>                                
                        </td>
                        <?php
                    }        
                    ?>
                </tr>
            </tbody>
        </table> 
    </div>
</div>
<div class="clearfix w-100"></div>