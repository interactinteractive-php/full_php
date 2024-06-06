<?php
if (isset($this->widgetConfig) && $this->widgetConfig) {
?>
<div class="bp-template-table-cell-right">
    <div class="bp-template-table-cell-right-inside">
        
        <?php
        if (isset($this->widgetConfig['idcard'])) {
            $cardUniqId = getUID();
            echo '<div id="'.$cardUniqId.'"></div>';
            echo '<script type="text/javascript">renderIDCardPanel(\''.$cardUniqId.'\');</script>';
        }
        ?>
        
        <?php
        if (isset($this->widgetConfig['attach'])) {
            $bpAttachRender = (new Mddoc())->bpTemplateAttach($this->methodId, $this->bpTemplateId, $this->methodRow['REF_META_GROUP_ID'], $this->sourceId, $this->isEditMode);
            echo $bpAttachRender;
        }
        ?>
        
        <?php
        if (isset($this->widgetConfig['realestate'])) {
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
        if (isset($this->widgetConfig['billprint'])) {
        ?>
        <div class="card light bp-tmp-bill-part">
            <div class="card-header card-header-no-padding header-elements-inline">
                <div class="card-title">
                    <i class="fa fa-money"></i>
                    <span class="caption-subject font-weight-bold uppercase">Төлбөр төлөлт</span>
                </div>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row fom-row" style="margin-bottom: 0 !important">
                    <div class="row">
                        <label class="col-md-6 col-form-label mt5 pr0">Бүртгэлийн №:</label>
                        <div class="col-md-6">
                            <input class="form-control bp-bill-booknumber" type="text">
                        </div>
                    </div>
                </div>
                <div class="form-group row fom-row" style="margin-bottom: 0 !important">
                    <div class="row">
                        <label class="col-md-6 col-form-label mt5">Тасалбарын №:</label>
                        <div class="col-md-6">
                            <input class="form-control bp-bill-number" type="text">
                        </div>
                    </div>
                </div>
                <div class="form-group row fom-row" style="margin-bottom: 0 !important">
                    <div class="row">
                        <label class="col-md-6 col-form-label mt5">Үнэ:</label>
                        <div class="col-md-6">
                            <input class="form-control bigdecimalInit bp-bill-price" type="text">
                        </div>
                    </div>
                </div>
                <div class="form-group row fom-row" style="margin-bottom: 0 !important">
                    <div class="row">
                        <label class="col-md-6 col-form-label mt5">Төлсөн:</label>
                        <div class="col-md-6">
                            <input class="form-control bigdecimalInit bp-bill-paid" type="text">
                        </div>
                    </div>
                </div>
                <div class="form-group row fom-row" style="margin-bottom: 0 !important">
                    <div class="row">
                        <label class="col-md-6 col-form-label mt5">Хариулт:</label>
                        <div class="col-md-6">
                            <input class="form-control bigdecimalInit bp-bill-change" type="text">
                        </div>
                    </div>
                </div>
                <div class="clearfix w-100"></div>
                <div class="form-group row fom-row" style="margin-bottom: 0 !important; border-bottom: 0">
                    <div class="row">
                        <label class="col-md-6 col-form-label mt5"></label>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-circle btn-block blue btn-sm mt5" onclick="bpBillPrint(this);">Хэвлэх</button>
                        </div>
                    </div>
                </div>
                <div class="clearfix w-100"></div>
            </div>
        </div>    
        <script type="text/javascript">
        $(function(){
            $('.bp-bill-paid').off();
            $('.bp-bill-paid').on('keyup', function(){
                var billPrice = Number($('.bp-bill-price').autoNumeric('get'));
                var billPaid = Number($('.bp-bill-paid').autoNumeric('get'));
                
                $('.bp-bill-change').autoNumeric('set', (billPaid - billPrice));
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