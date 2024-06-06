<?php
if ($this->widgetConfig) {
?>
<div class="bp-template-table-cell-right">
    <div class="bp-template-table-cell-right-inside">
        
        <?php
        if ($this->paramList) {
            $replacedWidgetA = '';
            $replacedWidgetB = '';
            $replacedWidgetRstate = '';
            $replacedWidgetOrg = '';
            $replacedWidgetAuto = '';
            
            foreach ($this->paramList as $k => $row) {
                if ($row['type'] == 'detail') {
                    
                    if (isset($this->widgetConfig['widget_party_a'])) {
                        if(Arr::in_array_multi($row['code'], $this->widgetConfig['widget_party_a']['rows'], 'PATH')) {
                            foreach ($row['data'] as $ind => $val) {
                                foreach ($this->widgetConfig['widget_party_a']['rows'] as $kk => $vv) {
                                    $this->widgetConfig['widget_party_a']['rows'][$kk]['BODY'] = html_entity_decode(str_ireplace('#'.$val['META_DATA_CODE'].'#', Mdwebservice::renderParamControl($this->methodId, $val, $row['code'] . "." . $val['META_DATA_CODE'], $val['META_DATA_CODE'], null), $this->widgetConfig['widget_party_a']['rows'][$kk]['BODY']), ENT_QUOTES, 'UTF-8');
                                }
                            }
                            $replacedWidgetA = json_encode($this->widgetConfig['widget_party_a']['rows']);
                        }
                    }
                    
                    if (isset($this->widgetConfig['widget_party_b'])) {
                        if(Arr::in_array_multi($row['code'], $this->widgetConfig['widget_party_b']['rows'], 'PATH')) {
                            foreach ($row['data'] as $ind => $val) {
                                foreach ($this->widgetConfig['widget_party_b']['rows'] as $kk => $vv) {
                                    $this->widgetConfig['widget_party_b']['rows'][$kk]['BODY'] = html_entity_decode(str_ireplace('#'.$val['META_DATA_CODE'].'#', Mdwebservice::renderParamControl($this->methodId, $val, $row['code'] . "." . $val['META_DATA_CODE'], $val['META_DATA_CODE'], null), $this->widgetConfig['widget_party_b']['rows'][$kk]['BODY']), ENT_QUOTES, 'UTF-8');
                                }
                            }
                            $replacedWidgetB = json_encode($this->widgetConfig['widget_party_b']['rows']);
                        }
                    }
                    
                    if (isset($this->widgetConfig['widget_realestate'])) {
                        if(Arr::in_array_multi($row['code'], $this->widgetConfig['widget_realestate']['rows'], 'PATH')) {
                            foreach ($row['data'] as $ind => $val) {
                                foreach ($this->widgetConfig['widget_realestate']['rows'] as $kk => $vv) {
                                    $this->widgetConfig['widget_realestate']['rows'][$kk]['BODY'] = html_entity_decode(str_ireplace('#'.$val['META_DATA_CODE'].'#', Mdwebservice::renderParamControl($this->methodId, $val, $row['code'] . "." . $val['META_DATA_CODE'], $val['META_DATA_CODE'], null), $this->widgetConfig['widget_realestate']['rows'][$kk]['BODY']), ENT_QUOTES, 'UTF-8');
                                }
                            }
                            $replacedWidgetRstate = json_encode($this->widgetConfig['widget_realestate']['rows']);
                        }
                    }
                    
                    if (isset($this->widgetConfig['widget_organization'])) {
                        if(Arr::in_array_multi($row['code'], $this->widgetConfig['widget_organization']['rows'], 'PATH')) {
                            foreach ($row['data'] as $ind => $val) {
                                foreach ($this->widgetConfig['widget_organization']['rows'] as $kk => $vv) {
                                    $this->widgetConfig['widget_organization']['rows'][$kk]['BODY'] = html_entity_decode(str_ireplace('#'.$val['META_DATA_CODE'].'#', Mdwebservice::renderParamControl($this->methodId, $val, $row['code'] . "." . $val['META_DATA_CODE'], $val['META_DATA_CODE'], null), $this->widgetConfig['widget_organization']['rows'][$kk]['BODY']), ENT_QUOTES, 'UTF-8');
                                }
                            }
                            $replacedWidgetOrg = json_encode($this->widgetConfig['widget_organization']['rows']);
                        }
                    }
                    
                    if (isset($this->widgetConfig['widget_auto'])) {
                        if(Arr::in_array_multi($row['code'], $this->widgetConfig['widget_auto']['rows'], 'PATH')) {
                            foreach ($row['data'] as $ind => $val) {
                                foreach ($this->widgetConfig['widget_auto']['rows'] as $kk => $vv) {
                                    $this->widgetConfig['widget_auto']['rows'][$kk]['BODY'] = html_entity_decode(str_ireplace('#'.$val['META_DATA_CODE'].'#', Mdwebservice::renderParamControl($this->methodId, $val, $row['code'] . "." . $val['META_DATA_CODE'], $val['META_DATA_CODE'], null), $this->widgetConfig['widget_auto']['rows'][$kk]['BODY']), ENT_QUOTES, 'UTF-8');
                                }
                            }
                            $replacedWidgetAuto = json_encode($this->widgetConfig['widget_auto']['rows']);
                        }
                    }
                    
                }
            }
        }
        
        if (isset($this->widgetConfig['widget_party_a'])) {
            $cardUniqId = getUID();
            echo '<div id="'.$cardUniqId.'"></div>';
            echo '<script type="text/javascript">renderPartyPanel(\''.$cardUniqId.'\', JSON.stringify(' . $replacedWidgetA . '), JSON.stringify(' . json_encode($this->widgetExpression) . '));</script>';
        }
        if (isset($this->widgetConfig['widget_party_b'])) {
            $cardUniqId = getUID();
            echo '<div id="'.$cardUniqId.'"></div>';
            echo '<script type="text/javascript">renderPartyPanel(\''.$cardUniqId.'\', JSON.stringify(' . $replacedWidgetB . '), JSON.stringify(' . json_encode($this->widgetExpression) . '));</script>';
        }
        ?>
        
        <?php
        if (isset($this->widgetConfig['attach'])) {
            $bpAttachRender = (new Mddoc())->bpTemplateAttach($this->methodId, $this->bpTemplateId, $this->methodRow['REF_META_GROUP_ID'], $this->sourceId, $this->isEditMode);
            echo $bpAttachRender;
        }
        ?>
        
        <?php
        if (isset($this->widgetConfig['widget_realestate'])) {
            $cardUniqId = getUID();
            echo '<div id="'.$cardUniqId.'">';
            echo '<script type="text/javascript">appendTaxonamyBodyByTag(\''.$cardUniqId.'\', JSON.stringify(' . $replacedWidgetRstate . '));</script>';
        ?>
            <div class="card light bp-tmp-realestate-part">
                <div class="card-header card-header-no-padding header-elements-inline">
                    <div class="card-title">
                        <i class="fa fa-building"></i>
                        <span class="caption-subject font-weight-bold uppercase">Үл хөдлөх хөрөнгө</span>
                    </div>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row fom-row">
                        <div class="row">
                            <label class="col-md-5 col-form-label mt5">Улсын бүртгэлийн №:</label>
                            <div class="col-md-7 pl5">
                                <input class="form-control bp-tmp-realestate-input" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Гэрчилгээний №:</label>
                        <div class="col-md-7 pr0 pl5 bp-tmp-realestate-regnumber"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Үл хөдлөхийн хаяг:</label>
                        <div class="col-md-7 pr0 pl5 bp-tmp-realestate-address"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Үл хөдлөхийн талбай:</label>
                        <div class="col-md-7 pr0 pl5 bp-tmp-realestate-area"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Өрөөний тоо:</label>
                        <div class="col-md-7 pr0 pl5 bp-tmp-realestate-room"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="clearfix w-100"></div>
                </div>
            </div>    
        <?php echo '</div>'; ?>
        <script type="text/javascript">
        $(function(){
            $('.bp-tmp-realestate-input').on('keydown', function(e){
                var code = (e.keyCode ? e.keyCode : e.which);
                if (code === 13) {
                    var _this = $(this);
                    var _parent = _this.closest('.bp-tmp-realestate-part');
                    var certificateNumber = _this.val();
                    var city = 'Улаанбаатар';
                    var district = 'Хан-Уул дүүрэг';
                    var soum = '3 дугаар хороо';
                    var street = 'Чингисийн өргөн чөлөө';
                    var area = '80 m2';
                    var room = '5';
                    var regnumber = '9011441051';
                    
                    _parent.find('.bp-tmp-realestate-regnumber').text(regnumber);
                    _parent.find('.bp-tmp-realestate-address').text(city+', '+district+', '+soum+', '+street);
                    _parent.find('.bp-tmp-realestate-area').text(area);
                    _parent.find('.bp-tmp-realestate-room').text(room);
                    
                    var _parentForm = _this.closest('form');
                    
                    _parentForm.find("input[data-path='CERTIFICATE_NUMBER']").val(regnumber);
                    _parentForm.find("input[data-path='register']").val(certificateNumber);
                    _parentForm.find("input[data-path='CITY_NAME']").val(city);
                    _parentForm.find("input[data-path='DISTRICT_NAME']").val(district);
                    _parentForm.find("input[data-path='STREET_NAME']").val(soum);
                    _parentForm.find("input[data-path='addressLine2']").val(street);
                    _parentForm.find("input[data-path='ACTUAL_SIZE']").val(area);
                    _parentForm.find("input[data-path='roomCount']").val(room);
                }
            });
        });    
        </script>
        <?php
        }
        ?>
        
        <?php
        if (isset($this->widgetConfig['widget_organization'])) {
            $cardUniqId = getUID();
            echo '<div id="'.$cardUniqId.'">';
            echo '<script type="text/javascript">appendTaxonamyBodyByTag(\''.$cardUniqId.'\', JSON.stringify(' . $replacedWidgetOrg . '));</script>';
        ?>
            <div class="card light bp-tmp-realestate-part">
                <div class="card-header card-header-no-padding header-elements-inline">
                    <div class="card-title">
                        <i class="fa fa-building"></i>
                        <span class="caption-subject font-weight-bold uppercase">Хуулийн этгээд</span>
                    </div>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row fom-row">
                        <div class="row">
                            <label class="col-md-5 col-form-label mt5">Улсын бүртгэлийн №:</label>
                            <div class="col-md-7 pl5">
                                <input class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Нэр:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Хаяг:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">РД:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Гишүүдийн тоо:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Хариуцлагын хэлбэр:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Хувь нийлүүлсэн хөр.хэмжээ:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="clearfix w-100"></div>
                </div>
            </div>    
        <?php echo '</div>'; ?>
        <script type="text/javascript">
        $(function(){
            $('.bp-tmp-realestate-input').on('keydown', function(e){
                var code = (e.keyCode ? e.keyCode : e.which);
                if (code === 13) {
                    return;
                }
            });
        });    
        </script>
        <?php
        }
        ?>
        
        <?php
        if (isset($this->widgetConfig['widget_auto'])) {
            $cardUniqId = getUID();
            echo '<div id="'.$cardUniqId.'">';
            echo '<script type="text/javascript">appendTaxonamyBodyByTag(\''.$cardUniqId.'\', JSON.stringify(' . $replacedWidgetAuto . '));</script>';
        ?>
            <div class="card light bp-tmp-realestate-part">
                <div class="card-header card-header-no-padding header-elements-inline">
                    <div class="card-title">
                        <i class="fa fa-building"></i>
                        <span class="caption-subject font-weight-bold uppercase">Тээврийн хэрэгсэл</span>
                    </div>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row fom-row">
                        <div class="row">
                            <label class="col-md-5 col-form-label mt5">Улсын дугаар:</label>
                            <div class="col-md-7 pl5">
                                <input class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Марк:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Үйлдвэрлэсэн он:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Зориулалт:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Өнгө:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Аралын дугаар:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Гэрчилгээний дугаар:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Гэрчилгээ олгосон огноо:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="form-group row fom-row">
                        <label class="col-form-label col-md-5 pr0 pl0">Гаалийн мэдүүлгийн дугаар:</label>
                        <div class="col-md-7 pr0 pl5"></div>
                        <div class="clearfix w-100"></div>
                    </div>
                    <div class="clearfix w-100"></div>
                </div>
            </div>    
        <?php echo '</div>'; ?>
        <script type="text/javascript">
        $(function(){
            $('.bp-tmp-realestate-input').on('keydown', function(e){
                var code = (e.keyCode ? e.keyCode : e.which);
                if (code === 13) {
                    return;
                }
            });
        });    
        </script>
        <?php
        }
        ?>               
    </div>
</div>
<?php
}
?>