<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); 
    $percentHeight = 0;
?>

<div class="col-md-12 mt10 pl0 pr0 dashboard-render">
    <div id="dashboard-container-<?php echo $this->metaDataId; ?>" class="dashboard-container custom-table" processMetaDataId="<?php echo isset($this->processMetaDataId) ? $this->processMetaDataId : '0'; ?>">        
        <div class="card light bordered mb0 pb5 mddashboard-card" style="  border: none !important; box-shadow: none;">
            <?php if( $this->diagram['IS_SHOW_TITLE'] == '1') { ?>
            <div class="card-title mddashboard-card-title" id="card-title-<?php echo $this->metaDataId; ?>">
                <div class="caption mddashboard-caption">
                    <span class="caption-subject font-weight-bold mddashboard-title" title="" id="dashboard-title-<?php echo $this->metaDataId; ?>"><?php echo $this->diagram['TITLE']; ?></span>
                    <span class="caption-helper mddashboard-helper" id="dashboard-helper-<?php echo $this->metaDataId; ?>"></span>
                </div>
            </div>
            <?php } ?>
            <div class="card-body dashboard-content-<?php echo $this->metaDataId; ?>">        
                <div id="dashboard-<?php echo $this->metaDataId; ?>" <?php echo !empty($this->diagram['HEIGHT']) ? 'style="height:'.$this->diagram['HEIGHT'].'; overflow-y: auto"' : ''; ?>>

                    <?php if($this->data['status'] === 'success' && array_key_exists(0, $this->data['result'])) {
                        $getGroupName = $this->data['result'][0][$this->diagram['XAXIS']];
                        unset($this->data['result']['aggregatecolumns']);
                        unset($this->data['result']['paging']);
                        echo '<table class="table table-bordered" style="width: 100%; background: #1b8dc2;color: #fff;font-size: 15px;">';
                        echo ' <tr><th rowspan="2"   style="width:140px;" class="text-center ta-c"> Эрсдэл </th> 
                                    <th colspan="4" class="text-center ta-c">Аюулын түвшин</th>
                                </tr>';
                        echo '<tr>
                                <td></td>
                                <td>Бага</td>
                                <td>Дунд</td>
                                <td>Өндөр</td>
                            </tr>';

                        $i = 2;

                        foreach ($this->data['result'] as $rowKey => $row) {
                            $i ++;

                            if($rowKey === 0) {
                                echo ' <tr><td rowspan="'. $i .'"  style="width:50px;"><p style="transform: rotate(90deg); text-align: center;">Магадлал</p></td>  <td>' . $row['probabilityname'] . '</td>
                                <td style="background-color:' . $row['lowcolcolor'] . '"><a  style=" padding:0 15px; color: #000;font-size: 15px;" href="javascript:;" onclick="cusomdrillV1(this, '.$row['lowdataview'].' )">' . $row['low'] .'</a> </td>
                                <td style="background-color:' . $row['midcolcolor'] . '"><a  style=" padding:0 15px; color: #000;font-size: 15px;" href="javascript:;" onclick="cusomdrillV1(this, '.$row['middataview'].' )">' .$row['mid'].'</a> </td>
                                <td style="background-color:' . $row['highcolcolor'] . '"><a  style=" padding:0 15px; color: #000;font-size: 15px;" href="javascript:;" onclick="cusomdrillV1(this, '.$row['highdataview'].' )">' .$row['high'].'</a></td></tr>';
                            }else{
                                echo '<tr>  <td>' . $row['probabilityname'] . '</td>
                                            <td style="background-color:' . $row['lowcolcolor'] . '"><a  style=" padding:0 15px; color: #000;font-size: 15px;" href="javascript:;" onclick="cusomdrillV1(this, '.$row['lowdataview'].' )">' . $row['low'] .'</a> </td>
                                            <td style="background-color:' . $row['midcolcolor'] . '"><a  style=" padding:0 15px; color: #000;font-size: 15px;" href="javascript:;" onclick="cusomdrillV1(this, '.$row['middataview'].' )">' .$row['mid'].'</a> </td>
                                            <td style="background-color:' . $row['highcolcolor'] . '"><a  style=" padding:0 15px; color: #000;font-size: 15px;" href="javascript:;" onclick="cusomdrillV1(this, '.$row['highdataview'].' )">' .$row['high'].'</a></td></tr>';
                                } 
                        }

                        echo '</table>';

                    } ?>                    
                </div>


            </div>
        </div>
    </div>
    
</div>

<script>
  function cusomdrillV1(elem, id) {
      
    var $dialogName = 'dialog-talon-dataview';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
            
    $.ajax({
        type: 'post',
        url: 'mdobject/dataValueViewer',
        data: {
            metaDataId: id, 
            viewType: 'detail', 
            dataGridDefaultHeight: 510, 
            ignorePermission: 1
        },
        beforeSend: function () {
            Core.blockUI({
                animate: true
            });
        },
        success: function (dataHtml) {
            
            $dialog.empty().append('<div class="row" id="object-value-list-1569898078688459">' + dataHtml + '</div>');
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Харах', 
                width: 1000,
                height: 600,
                modal: false,
                open: function () {
                    $dialog.find('.top-sidebar-content:eq(0)').attr('style', 'padding-left: 15px !important');
                    $dialog.find('.div-accordionToggler, .remove-type-1567753457397, .clearfix.w-100, .row.w-100').remove();
                    $dialog.find('.card-collapse').empty();
                    $dialog.find('.col-md-12.text-right.pr0').removeClass('col-md-12').addClass('float-right col-md-3').css('margin-top', '-25px');
                    $dialog.find('.mb5.pb5').removeClass('mb5 pb5');
                    $dialog.find('.xs-form.top-sidebar-content').css('padding-left', '').removeClass('mb10');
                    
                    setTimeout(function(){
                        $dialog.find('input[type="text"]:eq(0)').focus();
                    }, 100);
                }, 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                }
            }).dialogExtend({
                "closable": true,
                "maximizable": false, 
                "minimizable": true,
                "collapsable": true,
                "minimizeLocation": "left",
                "icons": {
                    "close": "ui-icon-circle-close",
                    "maximize": "ui-icon-extlink",
                    "minimize": "ui-icon-minus",
                    "collapse": "ui-icon-triangle-1-s",
                    "restore": "ui-icon-newwin"
                }
            });
            
            $dialog.dialog('open');

            Core.unblockUI();
        },
        error: function () {
            alert('Error');
        }
    });
}
   
</script>