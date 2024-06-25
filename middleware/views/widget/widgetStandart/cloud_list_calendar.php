<!-- Support tickets -->
<?php 
$uid = getUID();
$renderAtom = new Mdwidget(); 
$currentDate = checkDefaultVal($this->filterDate, Date::currentDate('Y-m-d'));
$monday = strtotime("last monday");

$monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
$mon = date("Y-m-d", $monday);
$tue = date("Y-m-d", strtotime(date("Y-m-d", $monday) . " +1 days"));
$wed = date("Y-m-d", strtotime(date("Y-m-d", $monday) . " +2 days"));
$thu = date("Y-m-d", strtotime(date("Y-m-d", $monday) . " +3 days"));
$fri = date("Y-m-d", strtotime(date("Y-m-d", $monday) . " +4 days"));
$sat = date("Y-m-d", strtotime(date("Y-m-d", $monday) . " +5 days"));
$sun = date("Y-m-d", strtotime(date("Y-m-d", $monday) . " +6 days"));

?>
<div class="card no-border p-3 req_<?php echo $uid ?>">
    <div class="card-header header-elements-sm-inline">
        <h6 class="card-title">
            <div style="font-size:18px;color:#585858;margin-top:10px" class="font-bold">Миний хөгжүүлэх шаардлагууд</div>
            <p class="mb-3 text-left text-muted text-less"><?php echo sizeOf(issetParamArray($this->datasrc)); ?> шаардлага</p>
        </h6>
        <div class="header-elements">
            <ul class="pagination pagination-flat pagination-sm justify-content-around">
                <li class="page-item d-none"><a href="javascript:;" class="page-link">←</a></li>
                <li class="page-item">
                    <a href="javascript:;" class="page-link <?php echo ($currentDate == $mon ? 'active' : '') ?>" data-filterdate="<?php echo $mon; ?>">
                        <span class="mb-1 text-less"><?php echo Date::format('D', $mon) ?></span>
                        <span><?php echo Date::format('d', $mon) ?></span>
                    </a>
                </li>
                <li class="page-item">
                    <a href="javascript:;" class="page-link <?php echo ($currentDate == $tue ? 'active' : '') ?>" data-filterdate="<?php echo $tue; ?>">
                        <span class="mb-1 text-less"><?php echo Date::format('D', $tue) ?></span>
                        <span><?php echo Date::format('d', $tue) ?></span>
                    </a>
                </li>
                <li class="page-item">
                    <a href="javascript:;" class="page-link <?php echo ($currentDate == $wed ? 'active' : '') ?>" data-filterdate="<?php echo $wed; ?>">
                        <span class="mb-1 text-less"><?php echo Date::format('D', $wed) ?></span>
                        <span><?php echo Date::format('d', $wed) ?></span>
                    </a>
                </li>
                <li class="page-item">
                    <a href="javascript:;" class="page-link <?php echo ($currentDate == $thu ? 'active' : '') ?>" data-filterdate="<?php echo $thu; ?>">
                        <span class="mb-1 text-less"><?php echo Date::format('D', $thu) ?></span>
                        <span><?php echo Date::format('d', $thu) ?></span>
                    </a>
                </li>
                <li class="page-item">
                    <a href="javascript:;" class="page-link <?php echo ($currentDate == $fri ? 'active' : '') ?>" data-filterdate="<?php echo $fri; ?>">
                        <span class="mb-1 text-less"><?php echo Date::format('D', $fri) ?></span>
                        <span><?php echo Date::format('d', $fri) ?></span>
                    </a>
                </li>
                <li class="page-item">
                    <a href="javascript:;" class="page-link <?php echo ($currentDate == $sat ? 'active' : '') ?>" data-filterdate="<?php echo $sat; ?>">
                        <span class="mb-1 text-less"><?php echo Date::format('D', $sat) ?></span>
                        <span><?php echo Date::format('d', $sat) ?></span>
                    </a>
                </li>
                <li class="page-item">
                    <a href="javascript:;" class="page-link <?php echo ($currentDate == $sun ? 'active' : '') ?>" data-filterdate="<?php echo $sun; ?>">
                        <span class="mb-1 text-less"><?php echo Date::format('D', $sun) ?></span>
                        <span><?php echo Date::format('d', $sun) ?></span>
                    </a>
                </li>
                <li class="page-item d-none" ><a href="javascript:;" class="page-link">→</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body d-md-flex align-items-md-center justify-content-md-between flex-md-wrap">
        <div class="table-responsive datagrid-view">
            <table class="table not-datagrid">
                <tbody>
                    <?php 
                    if (issetParamArray($this->datasrc)) { ?>
                        <tr style="background-color: #FFF !important; min-height: 52px; height: 52px;">
                            <td>Шаардлага</td>
                            <td>Help desk URL</td>
                            <td colspan="4"></td>
                            <td class="text-right d-none">
                                <span class="badge bg-blue badge-pill">24</span>
                            </td>
                        </tr>
                        <?php
                        foreach($this->datasrc as $index => $row) {
                            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                            /* if ($index < 11) { */
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <a href="javascript:;" class="btn bg-info rounded-round btn-icon btn-sm text-center d-flex p-2">
                                                    <span class="<?php echo $renderAtom->renderAtom($row, "position0", $this->positionConfig, 'letter-icon'); ?>"></span>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="javascript:;" class="text-one-line text-default font-weight-semibold letter-icon-title cloud-callprocess-action" data-row="<?php echo $rowJson ?>" data-processid="<?php echo $renderAtom->renderAtomPath("position11", $this->positionConfig); ?>" data-tpath="<?php echo $renderAtom->renderAtomPath("position1", $this->positionConfig); ?>" title="<?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, 'Default value') ?>"><?php echo $renderAtom->renderAtom($row, "position1", $this->positionConfig, 'Default value') ?></a>
                                                <div class="text-one-line text-muted font-size-sm text-left text-less" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" title="<?php echo $renderAtom->renderAtom($row, "position2", $this->positionConfig, 'Default value') ?>"> <?php echo $renderAtom->renderAtom($row, "position2", $this->positionConfig, 'Default value') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-160px text-left">
                                        <a href="mdhelpdesk/login" target="_blank" class="text-one-line text-default font-weight-semibold letter-icon-title" data-tpath="<?php echo $renderAtom->renderAtomPath("position20", $this->positionConfig); ?>" title="<?php echo $renderAtom->renderAtom($row, "position20", $this->positionConfig, '') ?>"><?php echo $renderAtom->renderAtom($row, "position20", $this->positionConfig, '') ?></a>
                                    </td>
                                    <td class="w-120px text-center">
                                        <h6 class="mb-0 text-one-line" data-tpath="<?php echo $renderAtom->renderAtomPath("position3", $this->positionConfig); ?>" title="<?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig, '') ?>"><?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig, '') ?></h6>
                                        <div class="font-size-sm text-muted line-height-1 text-one-line" data-tpath="<?php echo $renderAtom->renderAtomPath("position4", $this->positionConfig); ?>" title="<?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig, 'Default value') ?>"><?php echo $renderAtom->renderAtom($row, "position4", $this->positionConfig, 'Default value') ?></div>
                                    </td>
                                    <td class="w-160px text-center">
                                        <span class="badge badge-flat border-primary text-primary-600 text-one-line hover-pointer"  data-rowdata="<?php echo $rowJson ?>"onclick="dataViewWfmStatusFlowViewer<?php echo $uid ?>(this, '<?php echo $renderAtom->renderAtom($row, 'position14', $this->positionConfig, ''); ?>', '<?php echo $renderAtom->renderAtom($row, 'position15', $this->positionConfig, ''); ?>', '<?php echo $renderAtom->renderAtom($row, 'position5', $this->positionConfig, ''); ?>', '<?php echo $renderAtom->renderAtom($row, 'position13', $this->positionConfig, ''); ?>', '<?php echo $renderAtom->renderAtom($row, 'position12', $this->positionConfig, ''); ?>', '<?php echo $renderAtom->renderAtom($row, 'position7', $this->positionConfig, ''); ?>');" style="background-color: <?php echo $renderAtom->renderAtom($row, "position7", $this->positionConfig, ''); ?>; color: <?php echo $renderAtom->renderAtom($row, "position8", $this->positionConfig, '#FFF !important;'); ?>; border: none" data-tpath="<?php echo $renderAtom->renderAtomPath("position5", $this->positionConfig); ?>" title="<?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, 'Default value') ?>"><?php echo $renderAtom->renderAtom($row, "position5", $this->positionConfig, 'Default value') ?></span>
                                    </td>
                                    <td class="w-120px text-left">
                                        <h6 class="mb-0" data-tpath="<?php echo $renderAtom->renderAtomPath("position6", $this->positionConfig); ?>" title="<?php echo $renderAtom->renderAtom($row, "position6", $this->positionConfig, 'Default value') ?>"><?php echo $renderAtom->renderAtom($row, "position6", $this->positionConfig, 'Default value') ?></h6>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-pink-400" style="width: <?php echo $renderAtom->renderAtom($row, "position6", $this->positionConfig, 'Default value') ?>">
                                                <span class="sr-only"><?php echo $renderAtom->renderAtom($row, "position6", $this->positionConfig, 'Default value') ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-140px text-right">
                                        <div class="list-icons">
                                            <a href="javascript:;" data-style="expand-right" data-spinner-color="#333" data-spinner-size="20" data-row="<?php echo $rowJson ?>" data-processid="<?php echo $renderAtom->renderAtomPath("position9", $this->positionConfig); ?>" title="Хөгжүүлэлт эхлүүлэх" class="cloud-callprocess-action btn btn-light btn-ladda ladda-button btn-ladda-spinner btn-icon mr-1"><i class="icon-play4"></i></a>
                                            <a href="javascript:;" data-style="expand-right" data-spinner-color="#333" data-spinner-size="20" data-row="<?php echo $rowJson ?>" data-processid="<?php echo $renderAtom->renderAtomPath("position10", $this->positionConfig); ?>" title="Хөгжүүлэлт дуусгах" class="cloud-callprocess-action btn btn-light btn-ladda ladda-button btn-ladda-spinner btn-icon"><i class="icon-stop2"></i></a>
                                            <a href="javascript:;" data-style="expand-right" data-spinner-color="#333" data-spinner-size="20" data-row="<?php echo $rowJson ?>" data-processid="<?php echo $renderAtom->renderAtomPath("position18", $this->positionConfig); ?>" title="Хөгжүүлэлт буцаах" class="cloud-callprocess-action btn btn-light btn-ladda ladda-button btn-ladda-spinner btn-icon"><i class="icon-cross2"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php /*  */
                        }
                    } else { ?>
                        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 w-full gap-4 " style="gap:0.3rem">
                            <img src="middleware/assets/img/icon/no-data.png" alt="no-data" class="w-auto mx-auto"/>              
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php if ($renderAtom->renderAtom(issetParamArray($this->datasrc[0]), "position16", $this->positionConfig, '') !== '') { ?>
            <div class="mt-2 w-100" style="text-align: right;">
                <?php echo '<span style="color:#A0A0A0;font-size: 11px" data-position="'. $renderAtom->renderAtom(issetParamArray($this->datasrc[0]), "position16", $this->positionConfig, '') .'" data-positiontype="'. $renderAtom->renderAtom(issetParamArray($this->datasrc[0]), "position17", $this->positionConfig, 'package') .'" data-morebtn="1" class="morebtn">'. Lang::line('More_10').'</span>'; ?>
            </div>   
        <?php } ?>
    </div>
</div>

<style type="text/css">
    .req_<?php echo $uid ?> {
        .table td, .table th {
            border: none !important;
        }
        .hover-pointer:hover {
            cursor: pointer;
        }

        .page-link {
            border-radius: 30px !important;
            display: grid;
            background: #FFF;
            color: #333;
            padding: 12px 14px;
            margin-left: 5px !important;
        }
        .w-40-percent {
            width: 40% !important;
            max-width: 40% !important;
            min-width: 40% !important;
        }
        .w-140px {
            width: 140px !important;
            max-width: 140px !important;
            min-width: 140px !important;
        }
        .w-120px {
            width: 120px !important;
            max-width: 120px !important;
            min-width: 120px !important;
        }
        .w-160px {
            width: 160px !important;
            max-width: 160px !important;
            min-width: 160px !important;
        }
        .w-80px {
            width: 80px !important;
            max-width: 80px !important;
            min-width: 80px !important;
        }
        .w-20-percent {
            width: 20% !important;
            max-width: 20% !important;
            min-width: 20% !important;
        }
        .page-link.active,
        .page-link:hover {
            background: #FF7E79 !important;
            color: #FFF !important;
        }

        .text-less {
            font-weight: 400;
            letter-spacing: 0em;
            text-align: center;

        }

        .badge-flat {
            background-color: #DCFDFD;
            border-color: #DCFDFD;
            color: #009EF7 !important;
            padding: 9px 14px 10px 12px;
            border-radius: 6px;
        }
        .morebtn:hover {
            cursor: pointer;
        }
    }
</style>
<script type="text/javascript">
    $('body').on('click', '.req_<?php echo $uid ?> .page-link[data-filterdate]', function () {
        var _this = $(this);
        reload_<?php echo $uid ?>(_this)
    });

    $('body').on('click', ".req_<?php echo $uid ?> .cloud-callprocess-action", function(){
        var element = this,
            $this = $(element),
            $parentSection = $this.closest('section[data-metadataid]');
            
            /* Core.blockUI({
                message: "Loading...",
                boxed: true,
            }); */

        var metaDataId = $(this).data("processid");
        if (metaDataId) {
            var getCustomerItems = $.ajax({
                type: "post",
                url: "mdmetadata/getMetaDataDrill/" + metaDataId,
                dataType: "json",
                async: false,
                success: function (data) {
                    /* Core.unblockUI(); */
                    return data.result;
                },
            });
            if (getCustomerItems.responseJSON.META_TYPE_CODE == 'BOOKMARK') {
                appMultiTab({weburl: getCustomerItems.responseJSON.BOOKMARK_URL, metaDataId: getCustomerItems.responseJSON.BOOKMARK_URL+'223999663325', title: getCustomerItems.responseJSON.META_DATA_NAME, type: 'selfurl'});
            } else {
                gridDrillDownLink(this, getCustomerItems.responseJSON.META_DATA_CODE, getCustomerItems.responseJSON.META_TYPE_CODE.toLowerCase(), '1', '',  $parentSection.attr('data-metadataid'), '', metaDataId, '', false, true)
            }

            /* setTimeout(() => {
                var _thisFilterAttr = $('.req_<?php echo $uid ?> .active[data-filterdate]');
                reload_<?php echo $uid ?>(_thisFilterAttr);
            }, 5000); */
        }
    });

    function reload_<?php echo $uid ?>(_this) {
        var filterDate = _this.attr('data-filterdate');
        $.ajax({
            type: 'post',
            url: 'mdlayout/layoutBySection', 
            data: {
                filterDate: filterDate,
                config: _this.closest('section[data-attr]').attr('data-attr'),
            }, 
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                _this.closest('section[data-attr]').empty().append(data.Html);
                Core.unblockUI();
            },
            error: function (jqXHR, exception) {
                Core.showErrorMessage(jqXHR, exception);
                Core.unblockUI();
            }
        });
    }

    function dataViewWfmStatusFlowViewer<?php echo $uid ?>(elem, rowId, wfmStatusId, wfmStatusName, dataViewId, refStructureId, wfmstatuscolor) {
        var _this = $(elem);
        _this.closest('table').find('.dv-twocol-f-selected').removeClass('dv-twocol-f-selected');
        _this.addClass('dv-twocol-f-selected');
        dataViewWfmStatusFlowViewer(elem, rowId, wfmStatusId, wfmStatusName, dataViewId, refStructureId, wfmstatuscolor);
    };

    $('body').on('click', '.req_<?php echo $uid ?> span[data-morebtn]', function() {
        var _this = $(this);
        appMultiTab({metaDataId: _this.data('position')+'', title: 'Дэлгэрэнгүй', type: 'selfurl', type: _this.data('positiontype')});
    });

</script>