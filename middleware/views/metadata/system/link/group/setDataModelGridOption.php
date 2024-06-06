<div id="fz-grid-option" class="freeze-overflow-xy-auto" style="height: 512px;">
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="bold"><?php echo $this->lang->line('META_00075'); ?></th>
                <th class="bold"><?php echo $this->lang->line('META_00007'); ?></th>
                <th class="bold">Утга</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="middle">detaultViewer</td>
                <td class="middle">Detault view type</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'DETAULTVIEWER');
                    $savedOpt = Arr::get($this->gridOption, 'DETAULTVIEWER');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[detaultViewer]', 
                            'class' => 'form-control form-control-sm',
                            'data' => $this->defaultViewerArr,
                            'data-name' => $defaultOpt,
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">hideLayoutViewer</td>
                <td class="middle">Харагдацын төрлийг нуух эсэх</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'HIDELAYOUTVIEWER');
                    $savedOpt = Arr::get($this->gridOption, 'HIDELAYOUTVIEWER');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[hideLayoutViewer]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt,
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">viewTheme</td>
                <td class="middle">View theme</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'VIEWTHEME');
                    $savedOpt = Arr::get($this->gridOption, 'VIEWTHEME');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[viewTheme]',
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'jeasyuiTheme1',
                                    'name' => 'Theme 1'
                                ), 
                                array(
                                    'code' => 'jeasyuiTheme2',
                                    'name' => 'Theme 2'
                                ), 
                                array(
                                    'code' => 'jeasyuiTheme3',
                                    'name' => 'Theme 3'
                                ), 
                                array(
                                    'code' => 'jeasyuiTheme4',
                                    'name' => 'Theme 4'
                                ), 
                                array(
                                    'code' => 'jeasyuiTheme5',
                                    'name' => 'Theme 5'
                                ), 
                                array(
                                    'code' => 'jeasyuiTheme6',
                                    'name' => 'Theme 6'
                                ),
                                array(
                                    'code' => 'jeasyuiTheme7',
                                    'name' => 'Theme 7'
                                ),
                                array(
                                    'code' => 'jeasyuiTheme8',
                                    'name' => 'Theme 8 /document/'
                                ),
                                array(
                                    'code' => 'jeasyuiTheme9',
                                    'name' => 'Theme 9'
                                ),
                                array(
                                    'code' => 'jeasyuiTheme10',
                                    'name' => 'Theme 10'
                                ), 
                                array(
                                    'code' => 'jeasyuiTheme11',
                                    'name' => 'Theme 11'
                                ), 
                                array(
                                    'code' => 'jeasyuiTheme12',
                                    'name' => 'Theme 12 /The Ecommerce like Theme3/'
                                ), 
                                array(
                                    'code' => 'jeasyuiTheme13',
                                    'name' => 'Theme 13'
                                ),
                            ),
                            'data-name' => $defaultOpt,
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">mobileTheme</td>
                <td class="middle">Mobile theme</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'MOBILETHEME');
                    $savedOpt = Arr::get($this->gridOption, 'MOBILETHEME');
                    $mobileTheme = array();
                    
                    for ($m = 1; $m <= 300; $m++) {
                        $mobileTheme[] = array('code' => 'DV_theme'.$m, 'name' => 'DV theme'.$m);
                    }
                            
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[mobileTheme]',
                            'class' => 'form-control form-control-sm',
                            'data' => $mobileTheme,
                            'data-name' => $defaultOpt,
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">mobileTheme2</td>
                <td class="middle">Mobile theme2</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'MOBILETHEME2');
                    $savedOpt = Arr::get($this->gridOption, 'MOBILETHEME2');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[mobileTheme2]',
                            'class' => 'form-control form-control-sm',
                            'data' => $mobileTheme,
                            'data-name' => $defaultOpt,
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">resizeHandle</td>
                <td class="middle">Resizing column position, Available value are: 'left','right','both'. When 'right', users can resize columns by dragging the right edge of column headers, etc.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'RESIZEHANDLE');
                    $savedOpt = Arr::get($this->gridOption, 'RESIZEHANDLE');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[resizeHandle]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'left', 
                                    'name' => 'Left'
                                ), 
                                array(
                                    'code' => 'right', 
                                    'name' => 'Right'
                                ), 
                                array(
                                    'code' => 'both', 
                                    'name' => 'Both'
                                )
                            ),
                            'data-name' => $defaultOpt,
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">fitColumns</td>
                <td class="middle">True to auto expand/contract the size of the columns to fit the grid width and prevent horizontal scrolling.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'FITCOLUMNS');
                    $savedOpt = Arr::get($this->gridOption, 'FITCOLUMNS');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[fitColumns]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name', 
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">autoRowHeight</td>
                <td class="middle">True to auto expand/contract the size of the columns to fit the grid width and prevent horizontal scrolling.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'AUTOROWHEIGHT');
                    $savedOpt = Arr::get($this->gridOption, 'AUTOROWHEIGHT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[autoRowHeight]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">striped</td>
                <td class="middle">True to stripe the rows.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'STRIPED');
                    $savedOpt = Arr::get($this->gridOption, 'STRIPED');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[striped]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">method</td>
                <td class="middle">The method type to request remote data.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'METHOD');
                    $savedOpt = Arr::get($this->gridOption, 'METHOD');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[method]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'post', 
                                    'name' => 'Post'
                                ), 
                                array(
                                    'code' => 'get', 
                                    'name' => 'Get'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">nowrap</td>
                <td class="middle">True to display data in one line. Set to true can improve loading performance.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'NOWRAP');
                    $savedOpt = Arr::get($this->gridOption, 'NOWRAP');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[nowrap]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">loadMsg</td>
                <td class="middle">When loading data from remote site, show a prompt message.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'LOADMSG');
                    $savedOpt = Arr::get($this->gridOption, 'LOADMSG');
                    echo Form::text(
                        array(
                            'name' => 'gridProperties[loadMsg]', 
                            'class' => 'form-control form-control-sm',
                            'data-name' => $defaultOpt, 
                            'value' => $savedOpt
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">Message no record found</td>
                <td class="middle">Үр дүн хоосон ирсэн үед харуулах мессеж.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'MSGNORECORDFOUND');
                    $savedOpt = Arr::get($this->gridOption, 'MSGNORECORDFOUND');
                    echo Form::text(
                        array(
                            'name' => 'gridProperties[msgNoRecordFound]', 
                            'class' => 'form-control form-control-sm',
                            'data-name' => $defaultOpt, 
                            'value' => $savedOpt
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">pagination</td>
                <td class="middle">True to show a pagination toolbar on datagrid bottom.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'PAGINATION');
                    $savedOpt = Arr::get($this->gridOption, 'PAGINATION');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[pagination]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'text' => 'notext',
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">rownumbers</td>
                <td class="middle">True to show a row number column.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'ROWNUMBERS');
                    $savedOpt = Arr::get($this->gridOption, 'ROWNUMBERS');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[rownumbers]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'text' => 'notext',
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">singleSelect</td>
                <td class="middle">True to allow selecting only one row.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SINGLESELECT');
                    $savedOpt = Arr::get($this->gridOption, 'SINGLESELECT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[singleSelect]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'text' => 'notext',
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">ctrlSelect</td>
                <td class="middle">True to only allow multi-selection when ctrl+click is used.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'CTRLSELECT');
                    $savedOpt = Arr::get($this->gridOption, 'CTRLSELECT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[ctrlSelect]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'text' => 'notext',
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">checkOnSelect</td>
                <td class="middle">If true, the checkbox is checked/unchecked when the user clicks on a row. If false, the checkbox is only checked/unchecked when the user clicks exactly on the checkbox.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'CHECKONSELECT');
                    $savedOpt = Arr::get($this->gridOption, 'CHECKONSELECT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[checkOnSelect]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'text' => 'notext',
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">selectOnCheck</td>
                <td class="middle">If set to true, clicking a checkbox will always select the row. If false, selecting a row will not check the checkbox.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SELECTONCHECK');
                    $savedOpt = Arr::get($this->gridOption, 'SELECTONCHECK');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[selectOnCheck]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'text' => 'notext',
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">showCheckbox</td>
                <td class="middle">Show/Hide checkbox</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SHOWCHECKBOX');
                    $savedOpt = Arr::get($this->gridOption, 'SHOWCHECKBOX');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[showCheckbox]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">enableFilter</td>
                <td class="middle">Create and enable filter functionality. The 'filters' parameter is an array of filter configuration.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'ENABLEFILTER');
                    $savedOpt = Arr::get($this->gridOption, 'ENABLEFILTER');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[enableFilter]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                   
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">filterAutoComplete</td>
                <td class="middle">Filter autoComplet search.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'FILTERAUTOCOMPLETE');
                    $savedOpt = Arr::get($this->gridOption, 'FILTERAUTOCOMPLETE');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[filterAutoComplete]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                   
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">pagePosition</td>
                <td class="middle">Defines position of the pager bar.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'PAGEPOSITION');
                    $savedOpt = Arr::get($this->gridOption, 'PAGEPOSITION');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[pagePosition]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'top', 
                                    'name' => 'Top'
                                ), 
                                array(
                                    'code' => 'bottom', 
                                    'name' => 'Bottom'
                                ), 
                                array(
                                    'code' => 'both', 
                                    'name' => 'Both'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">pageNumber</td>
                <td class="middle">When set pagination property, initialize the page number.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'PAGENUMBER');
                    $savedOpt = Arr::get($this->gridOption, 'PAGENUMBER');
                    echo Form::text(
                        array(
                            'name' => 'gridProperties[pageNumber]', 
                            'class' => 'form-control form-control-sm longInit',
                            'data-name' => $defaultOpt, 
                            'value' => $savedOpt
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">pageSize</td>
                <td class="middle">When set pagination property, initialize the page size.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'PAGESIZE');
                    $savedOpt = Arr::get($this->gridOption, 'PAGESIZE');
                    echo Form::text(
                        array(
                            'name' => 'gridProperties[pageSize]', 
                            'class' => 'form-control form-control-sm longInit',
                            'data-name' => $defaultOpt, 
                            'value' => $savedOpt
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">pageList</td>
                <td class="middle">When set pagination property, initialize the page size selecting list.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'PAGELIST');
                    $savedOpt = Arr::get($this->gridOption, 'PAGELIST');
                    echo Form::text(
                        array(
                            'name' => 'gridProperties[pageList]', 
                            'class' => 'form-control form-control-sm',
                            'data-name' => $defaultOpt, 
                            'value' => $savedOpt
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">pageStyle</td>
                <td class="middle">Pagination view style.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'PAGESTYLE');
                    $savedOpt = Arr::get($this->gridOption, 'PAGESTYLE');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[pageStyle]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'manual', 
                                    'name' => 'Manual'
                                ), 
                                array(
                                    'code' => 'links', 
                                    'name' => 'Link'
                                ), 
                                array(
                                    'code' => 'lazy_load', 
                                    'name' => 'Lazy Load'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">sortName</td>
                <td class="middle">Defines which column can be sorted.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SORTNAME');
                    $savedOpt = Arr::get($this->gridOption, 'SORTNAME');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[sortName]', 
                            'class' => 'form-control form-control-sm',
                            'data' => $this->groupChildDatas,
                            'data-name' => $defaultOpt, 
                            'op_value' => 'META_DATA_CODE',
                            'op_text' => 'META_DATA_CODE',
                            'value' => $savedOpt
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">sortOrder</td>
                <td class="middle">Defines the column sort order, can only be 'asc' or 'desc'.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SORTORDER');
                    $savedOpt = Arr::get($this->gridOption, 'SORTORDER');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[sortOrder]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'asc', 
                                    'name' => 'Ascending'
                                ), 
                                array(
                                    'code' => 'desc', 
                                    'name' => 'Descending'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">multiSort</td>
                <td class="middle">Defines if to enable multiple column sorting.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'MULTISORT');
                    $savedOpt = Arr::get($this->gridOption, 'MULTISORT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[multiSort]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">remoteSort</td>
                <td class="middle">Defines if to sort data from server.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'REMOTESORT');
                    $savedOpt = Arr::get($this->gridOption, 'REMOTESORT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[remoteSort]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">showHeader</td>
                <td class="middle">Defines if to show row header.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SHOWHEADER');
                    $savedOpt = Arr::get($this->gridOption, 'SHOWHEADER');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[showHeader]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">showFooter</td>
                <td class="middle">Defines if to show row footer.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SHOWFOOTER');
                    $savedOpt = Arr::get($this->gridOption, 'SHOWFOOTER');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[showFooter]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">scrollbarSize</td>
                <td class="middle">The scrollbar width(when scrollbar is vertical) or height(when scrollbar is horizontal).</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SCROLLBARSIZE');
                    $savedOpt = Arr::get($this->gridOption, 'SCROLLBARSIZE');
                    echo Form::text(
                        array(
                            'name' => 'gridProperties[scrollbarSize]', 
                            'class' => 'form-control form-control-sm longInit',
                            'data-name' => $defaultOpt, 
                            'value' => $savedOpt
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">mergeCells</td>
                <td class="middle">Synonyms cells merge</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'MERGECELLS');
                    $savedOpt = Arr::get($this->gridOption, 'MERGECELLS');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[mergeCells]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">mergeCellsKeyField</td>
                <td class="middle">mergeCells тохиргоо True үед энд бичигдсэн жагсаалтын нэг Патын утгаар бүлэглэлт хийж merge хийнэ.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'MERGECELLSKEYFIELD');
                    $savedOpt = Arr::get($this->gridOption, 'MERGECELLSKEYFIELD');
                    echo Form::text(
                        array(
                            'name' => 'gridProperties[mergeCellsKeyField]', 
                            'class' => 'form-control form-control-sm',
                            'data-name' => $defaultOpt, 
                            'value' => $savedOpt
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">Child icon</td>
                <td class="middle">File зураг Харагдах/Харагдахгүй</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SHOWFILEICON');
                    $savedOpt = Arr::get($this->gridOption, 'SHOWFILEICON');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[showFileicon]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">Toolbar</td>
                <td class="middle">Dataview action buttons</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SHOWTOOLBAR');
                    $savedOpt = Arr::get($this->gridOption, 'SHOWTOOLBAR');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[showToolbar]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => '1', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => '0', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">Toolbar Right</td>
                <td class="middle">Dataview action right buttons</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SHOWTOOLBARRIGHT');
                    $savedOpt = Arr::get($this->gridOption, 'SHOWTOOLBARRIGHT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[showToolbarRight]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => '1', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => '0', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">First row select</td>
                <td class="middle">Эхний мөрийг сонгох</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'FIRSTROWSELECT');
                    $savedOpt = Arr::get($this->gridOption, 'FIRSTROWSELECT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[firstRowSelect]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">Subgrid Excel Export</td>
                <td class="middle">Subgrid дээр эксель рүү хөрвүүлэх товч харуулах</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'SUBGRIDEXCEL');
                    $savedOpt = Arr::get($this->gridOption, 'SUBGRIDEXCEL');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[subgridExcel]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">Inline Edit</td>
                <td class="middle">Dataview CRUD</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'INLINEEDIT');
                    $savedOpt = Arr::get($this->gridOption, 'INLINEEDIT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[inlineEdit]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">toggleSelect</td>
                <td class="middle">True to only allow single select.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'TOGGLESELECT');
                    $savedOpt = Arr::get($this->gridOption, 'TOGGLESELECT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[toggleSelect]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">loadAfterUncheck</td>
                <td class="middle">Load after uncheck</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'LOADAFTERUNCHECK');
                    $savedOpt = Arr::get($this->gridOption, 'LOADAFTERUNCHECK');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[loadAfterUncheck]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">autoHeight</td>
                <td class="middle">Auto height</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'AUTOHEIGHT');
                    $savedOpt = Arr::get($this->gridOption, 'AUTOHEIGHT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[autoHeight]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">defaultAllSelect</td>
                <td class="middle">Default all rows selection</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'DEFAULTALLSELECT');
                    $savedOpt = Arr::get($this->gridOption, 'DEFAULTALLSELECT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[defaultAllSelect]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">Drag order</td>
                <td class="middle">Drag order</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'DRAGSORT');
                    $savedOpt = Arr::get($this->gridOption, 'DRAGSORT');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[dragsort]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">Drill double click row</td>
                <td class="middle">Drill double click row</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'DRILLDBLCLICKROW');
                    $savedOpt = Arr::get($this->gridOption, 'DRILLDBLCLICKROW');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[drillDblClickRow]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name', 
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">groupField</td>
                <td class="middle">Group rows via specified column.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'GROUPFIELD');
                    $savedOpt = Arr::get($this->gridOption, 'GROUPFIELD');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[groupField]', 
                            'class' => 'form-control form-control-sm',
                            'data' => $this->groupChildDatas,
                            'data-name' => $defaultOpt, 
                            'op_value' => 'META_DATA_CODE',
                            'op_text' => 'META_DATA_CODE',
                            'value' => $savedOpt
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">groupFormatter</td>
                <td class="middle">Бүлэглэсэн мөрүүдийн дээр гарах гарчигын формат <i class="fa fa-info-circle" title="[value] = groupField-ийн утга &#xA;[length] = тухайн бүлгийн мөрийн тоо &#xA;Жишээ нь: [value] - [length] Item(s)"></i></td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'GROUPFORMATTER');
                    $savedOpt = Arr::get($this->gridOption, 'GROUPFORMATTER');
                    echo Form::text(
                        array(
                            'name' => 'gridProperties[groupFormatter]', 
                            'class' => 'form-control form-control-sm',
                            'data-name' => $defaultOpt, 
                            'value' => $savedOpt
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">groupField expand</td>
                <td class="middle">Бүлэглэсэн мөрүүдийг нээлттэй харуулах эсэх</td>
                <td class="middle">
                    <?php 
                    $savedOpt = Arr::get($this->gridOption, 'GROUPFIELDEXPAND');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[groupFieldExpand]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">group sum</td>
                <td class="middle">Бүлэглэсэн мөрүүдийн нийлбэр дүн</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'GROUPSUM');
                    $savedOpt = Arr::get($this->gridOption, 'GROUPSUM');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[groupSum]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">group field user</td>
                <td class="middle">Эцсийн хэрэглэгч өөрөө багануудаас сонгох эсэх</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'GROUPFIELDUSER');
                    $savedOpt = Arr::get($this->gridOption, 'GROUPFIELDUSER');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[groupFieldUser]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">is group freeze</td>
                <td class="middle">Бүлэглэсэн мөрийн утгыг скролл гүйлгэхэд freeze болгоно</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'GROUPTITLEFREEZE');
                    $savedOpt = Arr::get($this->gridOption, 'GROUPTITLEFREEZE');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[groupTitleFreeze]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">Keyup clientside search</td>
                <td class="middle">Харагдаж байгаа мөрүүдээс бичихэд шууд хайна. Enter товч дарахад serverside хайлт хийнэ.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'KEYUPCLIENTSEARCH');
                    $savedOpt = Arr::get($this->gridOption, 'KEYUPCLIENTSEARCH');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[keyupClientSearch]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">Keyup serverside search</td>
                <td class="middle">Хадгалагдсан бүх мөрүүдээс бичихэд шууд хайна</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'KEYUPSERVERSIDESEARCH');
                    $savedOpt = Arr::get($this->gridOption, 'KEYUPSERVERSIDESEARCH');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[keyupServerSideSearch]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?> 
                </td>
            </tr>
            <tr>
                <td class="middle">Contextmenu workflow status</td>
                <td class="middle">Contextmenu буюу mouse 2 дээр тухайн мөрийн дараагийн төлвийг харуулах</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'CONTEXTMENUWFMSTATUS');
                    $savedOpt = Arr::get($this->gridOption, 'CONTEXTMENUWFMSTATUS');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[contextMenuWfmStatus]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">Pivot query эсэх</td>
                <td class="middle">pivot query эсэх.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'ISPIVOTQUERY');
                    $savedOpt = Arr::get($this->gridOption, 'ISPIVOTQUERY');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[isPivotQuery]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">Header menu type</td>
                <td class="middle">Header menu харуулах төрөл.</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'HEADERMENUTYPE');
                    $savedOpt = Arr::get($this->gridOption, 'HEADERMENUTYPE');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[headerMenuType]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'work5day', 
                                    'name' => 'Ажлын 5 өдөр'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">onResizeColumn</td>
                <td class="middle">onResizeColumn ажиллах /Хэрэглэгч тус бүрээр өргөн тохируулах эсэх/</td>
                <td class="middle">
                    <?php 
                    $defaultOpt = Arr::get($this->defaultGridOption, 'ONRESIZECOLUMN');
                    $savedOpt = Arr::get($this->gridOption, 'ONRESIZECOLUMN');
                    echo Form::select(
                        array(
                            'name' => 'gridProperties[onResizeColumn]', 
                            'class' => 'form-control form-control-sm',
                            'data' => array(
                                array(
                                    'code' => 'true', 
                                    'name' => 'True'
                                ), 
                                array(
                                    'code' => 'false', 
                                    'name' => 'False'
                                )
                            ),
                            'data-name' => $defaultOpt, 
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'value' => $savedOpt,
                            'style' => 'width: 110px;'
                        )
                    ); 
                    ?>
                </td>
            </tr>
            <tr>
                <td class="middle">JSON config</td>
                <td class="middle">JSON тохиргоо</td>
                <td class="middle">
                    <button type="button" class="btn btn-sm purple-plum" onclick="gridOptionJsonConfig(this);">...</button>
                    <?php 
                    $savedOpt = Arr::get($this->gridOption, 'JSON_CONFIG');
                    ?>
                    <input type="hidden" name="gridProperties[json_config]" value="<?php echo $savedOpt ?>">
                </td>
            </tr>
        </tbody>
    </table>    
</div>    

<script type="text/javascript">
$(function() {
    var $gridOptionTbl = $('table', 'div#fz-grid-option');
    $gridOptionTbl.tableHeadFixer({'head': true}); 
    
    $gridOptionTbl.find('input, select').each(function() {
        var $this = $(this), defaultOpt = $this.attr('data-name'), savedOpt = $this.val();
        
        if (defaultOpt == undefined) {
            defaultOpt = '';
        }
        
        if (defaultOpt != savedOpt) {
            $this.addClass('bg-info');
        }
    });
    
    $gridOptionTbl.on('change', 'input, select', function() {
        var $this = $(this), defaultOpt = $this.attr('data-name'), savedOpt = $this.val();
        
        if (defaultOpt == undefined) {
            defaultOpt = '';
        }

        if (defaultOpt != savedOpt) {
            $this.addClass('bg-info');
        } else {
            $this.removeClass('bg-info');
        }
    });
});    

var formatExpOpts = {
    indent_size: 4,
    indent_char: ' ',
    max_preserve_newlines: 5,
    preserve_newlines: true,
    keep_array_indentation: false,
    break_chained_methods: false,
    indent_scripts: 'normal',
    brace_style: 'collapse',
    space_before_conditional: true, 
    unescape_strings: false, 
    jslint_happy: false,
    end_with_newline: false,
    wrap_line_length: 0,
    indent_inner_html: false,
    comma_first: false,
    e4x: false,
    indent_empty_lines: false
};

function gridOptionJsonConfig(elem) {

    var $dialogName = 'dialog-jsonConfigExpcriteria';

    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.css"/>');

        $("#" + $dialogName).empty().html(
            '<div class="row">'+
                '<div class="col-md-12">'+
                '<?php
                echo Form::textArea(
                    array(
                        'name' => 'jsonConfigRowExpressionString_set',
                        'id' => 'jsonConfigRowExpressionString_set',
                        'class' => 'form-control ace-textarea',
                        'value' => '',
                        'spellcheck' => 'false',
                        'style' => 'width: 100%;'
                    )
                );
                ?>'+
                '</div>'+
            '</div>'
        );

        $("#" + $dialogName).find('#jsonConfigRowExpressionString_set').val($(elem).closest('tr').find('input[name="gridProperties[json_config]"]').val());

        $("#" + $dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'JSON CONFIG /Alt+T/',
            width: 900,
            minWidth: 900,
            height: "auto",
            modal: false,
            position: {my:'top', at:'top+50'},
            create: function() {
                $("#" + $dialogName).parent('.ui-dialog').css('zIndex', 10001);
            },            
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green', click: function() {
                    jsonConfigExpressionRowEditor.save();
                    
                    $(elem).closest('tr').find('input[name="gridProperties[json_config]"]').val($('#jsonConfigRowExpressionString_set').val());
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                    $("#" + $dialogName).dialog('close');
                }}
            ]
        }).dialogExtend({
            "closable": true,
            "maximizable": true,
            "minimizable": true,
            "collapsable": true,
            "dblclick": "maximize",
            "minimizeLocation": "left",
            "icons": {
                "close": "ui-icon-circle-close",
                "maximize": "ui-icon-extlink",
                "minimize": "ui-icon-minus",
                "collapse": "ui-icon-triangle-1-s",
                "restore": "ui-icon-newwin"
            }
        });

        var jsonConfigExpressionRowEditor = CodeMirror.fromTextArea(document.getElementById("jsonConfigRowExpressionString_set"), {
            mode: 'application/json',
            styleActiveLine: true,
            lineNumbers: true,
            lineWrapping: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            indentUnit: 4,
            theme: 'material', 
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"], 
            extraKeys: {
                "Alt-T": function(cm){ 
                    var formattedExpression = js_beautify(cm.getValue(), formatExpOpts);
                    cm.setValue(formattedExpression);
                },                 
                "F11": function(cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                },
                "Esc": function(cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
            }
        });
        setTimeout(function() {
            jsonConfigExpressionRowEditor.refresh();
        }, 1);        

        $("#" + $dialogName).dialog('open');
    });
}
</script>