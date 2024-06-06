<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'report-archive-form', 'method' => 'post')); ?>

<div class="col-md-12 xs-form bp-template-mode">
    <!--<center><h3>Дотоод товъёог</h3></center>-->
<!--    <br />
    <br />-->
    <center>         
        <?php 
        echo Lang::lineDefault('ecmDirectory', 'Хадгаламжийн нэгжийн №');
        echo Form::select(
            array(
                'name' => 'directoryId', 
                'class' => 'form-control select2 form-control-sm',
                'id' => 'directoryId', 
                'data' => $this->directoryList,
                'op_value' => 'ID', 
                'op_text' => 'NAME'
            )
        ); 
        ?>
    </center>
<!--    <br />
    <br />-->
<!--    <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" data-table-path="sdmdv048">
        <thead>
            <tr>
                <th class="rowNumber" style="width:30px;">№</th>
                <th class="bp-head-sort" style="width: 130px">Огноо</th>
                <th class="bp-head-sort">Бүртгэлийн дугаар</th>
                <th class="bp-head-sort">Атал</th>
                <th class="bp-head-sort">Бтал</th>
                <th class="bp-head-sort">Агуулга</th>
                <th class="bp-head-sort">Хуудасны дугаар</th>
            </tr>    
        </thead>
        <tbody>
            <tr>
                <td class="text-center middle">
                    <span>1</span>
                </td>
                <td class="stretchInput middle text-center">
                    <div class="dateElement input-group">
                        <input class="form-control form-control-sm dateInit" type="text" value="<?php echo Date::currentDate('Y-m-d'); ?>">
                        <span class="input-group-btn">
                            <button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </td>
                <td class="stretchInput middle text-center">
                    <input class="form-control form-control-sm stringInit" type="text">
                </td>
                <td class="stretchInput middle text-center">
                    <input class="form-control form-control-sm stringInit" type="text">
                </td>
                <td class="stretchInput middle text-center">
                    <input class="form-control form-control-sm stringInit" type="text">
                </td>
                <td class="stretchInput middle text-center">
                    <input class="form-control form-control-sm stringInit" type="text">
                </td>
                <td class="stretchInput middle text-center">
                    <input class="form-control form-control-sm stringInit" type="text">
                </td>
            </tr>
        </tbody>    
    </table>-->
<!--    <table style="width: 100%">
        <tbody>
            <tr>
                <td style="border-bottom: 1px #666 solid"></td>
            </tr>
        </tbody>
    </table>-->
    <!--<br /><br />-->
<!--    <table style="width: 100%">
        <tbody>
            <tr>
                <td style="width: 20px; padding-left: 0">Бүгд</td>
                <td style="border-bottom: 1px #666 solid; text-align: center;">3</td>
            </tr>
        </tbody>
    </table>-->
<!--    Баримт бичиг бүртгэв.
    <br /><br />
    <center>/тоо, үсгээр/</center>
    <br /><br />-->
<!--    Товъёог үйлдсэн: <u>&nbsp;&nbsp;<strong><?php echo Ue::getSessionPersonName(); ?></strong>&nbsp;&nbsp;</u> дүүргийн тойргийн нотариатч
    <br /><br /><br />
    <table style="width: 100%">
        <tbody>
            <tr>
                <td style="border-top: 1px #666 solid; text-align: center">/Гарын үсэг, гарын үсгийн тайлал/</td>
                <td style="width: 80px;"></td>
            </tr>
        </tbody>
    </table>-->
</div>

<br/>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Нэр', 'for' => 'contentName', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
        <div class="col-md-8">
            <?php echo Form::text(array('name' => 'contentName', 'value' => $this->defaultName, 'id' => 'contentName', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>