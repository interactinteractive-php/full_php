<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px;" class="left-padding">Render type:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'renderType',
                            'id' => 'renderType',
                            'data' => array(
                                array(
                                    'id' => 'tab',
                                    'name' => 'Tab'
                                ),
                                array(
                                    'id' => 'onepage',
                                    'name' => 'Onepage'
                                ), 
                                array(
                                    'id' => 'column',
                                    'name' => 'Column'
                                ),
                                array(
                                    'id' => 'leftside',
                                    'name' => 'Left side'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'class' => 'form-control select2'
                        )
                    );
                    ?>      
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isIgnoreMainTitle">
                        <?php echo $this->lang->line('metadata_notitle'); ?>
                    </label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isIgnoreMainTitle',
                                'id' => 'isIgnoreMainTitle',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Mobile theme:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'mobileTheme',
                            'id' => 'mobileTheme',
                            'data' => array(
                                array(
                                    'id' => 'PC_theme1',
                                    'name' => 'PC theme1'
                                ),
                                array(
                                    'id' => 'PC_theme2',
                                    'name' => 'PC theme2'
                                ),
                                array(
                                    'id' => 'PC_theme3',
                                    'name' => 'PC theme3'
                                )
                            ),
                            'op_value' => 'id',
                            'op_text' => 'name',
                            'class' => 'form-control select2'
                        )
                    );
                    ?>      
                </td>
            </tr>
            <tr id="">
                <td class="left-padding">Tab background color:</td>
                <td>
                    <div class="input-group color chart-colorpicker-default" data-color="">
                        <input type="text" name="tabBackgroundColor" id="tabBackgroundColor" class="form-control" value="">
                        <span class="input-group-btn">
                            <button class="btn default colorpicker-input-addon px-1" type="button"><i style=""></i>&nbsp;</button>
                        </span>
                    </div>
                </td>
            </tr>       
            <tr>
                <td class="left-padding">
                    <label for="isPermission">
                        Is permission
                    </label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isPermission',
                                'id' => 'isPermission',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isIgnorePackageTitle">
                        <?php echo $this->lang->line('package_notitle'); ?>
                    </label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isIgnorePackageTitle',
                                'id' => 'isIgnorePackageTitle',
                                'value' => '1'
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        $('.chart-colorpicker-default').colorpicker({
            format: 'hex'
        });        
    });
</script>