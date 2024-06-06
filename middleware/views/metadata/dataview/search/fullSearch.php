<?php
if ($this->filterValues) {
    includeLib('Compress/Compression');
    $decompressContent = ($this->selectedRow) ? Compression::encode_string_array(array('selectedRow' => $this->selectedRow, 'filterValues' => $this->filterValues)) : '';
?>
    <div class="bg-white p-2 full-<?php echo $this->uniqId ?>">
        <ul class="nav nav-tabs nav-tabs-bottom d-none">
            <li class="nav-item"><a href="#bottom-tab1-<?php echo $this->uniqId ?>" class="nav-link active" data-toggle="tab"><?php echo Lang::line('001') ?></a></li>
            <li class="nav-item"><a href="#bottom-tab2-<?php echo $this->uniqId ?>" class="nav-link" data-toggle="tab"><?php echo Lang::line('002') ?></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="bottom-tab1-<?php echo $this->uniqId ?>">
                <div class="bg-white filter-<?php echo $this->uniqId ?>">
                    <div class="ecommerce-breadcumb header-elements-md-inline p-0">
                        <div class="ecommerce-buttons header-elements">
                            <a href="javascript:;" class="btn btn-success btn-sm sidebar-control d-md-block view-register-<?php echo $this->uniqId ?>" data-row="<?php echo $decompressContent ?>">
                                <i class="icon-search4"></i> <?php echo Lang::line('VIEW_REGISTER_CIVIL') ?>
                            </a>
                            <?php if (issetParamArray($this->selectedRow) && Config::getFromCache('USE_COMPARE_PIC_LINK') === '1') { ?>
                                <a class="btn green btn-circle btn-sm dv-bp-btn-visible" title="compare_picture_link" onclick="transferProcessAction('', '<?php echo $this->metaDataId ?>', '1603360564399064', '200101010000010', 'toolbar', this, {callerType: 'crCivilMainList'}, undefined, undefined, undefined, undefined, '');" data-dvbtn-processcode="compare_picture_link" data-ismain="0" href="javascript:;"><i class="fa fa-image" style="color:"></i> Зураг тулгалт</a>
                                <a class="btn btn-secondary btn-circle btn-sm"  title="Цахимжуулалт харах" onclick="transferProcessAction('', '<?php echo $this->metaDataId ?>', '1537175917763', '200101010000010', 'toolbar', this, {callerType: 'CRS_BIRTH_CERTIFICATE_LIST_CP'}, undefined, undefined, undefined, undefined, '');" data-dvbtn-processcode="cvlInfoControlWeblink" data-ismain="0" href="javascript:;">Цахимжуулалт харах</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mr-auto option-<?php echo $this->uniqId ?>">
                        <?php 
                        $index = 1;
                        foreach ($this->filterValuesGr as $key1 => $filterValues) { ?>
                            <div class="card-group-control card-group-control-right col-12" id="accordion-control">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">
                                            <a data-toggle="collapse" class="text-default <?php echo $index == '1'  ? '' : 'collapsed';  ?>" href="#accordion-control-group<?php echo $index ?>" aria-expanded="<?php echo $index == '1'  ? 'true' : 'false';  ?>"><?php echo $key1 ?></a>
                                        </h6>
                                    </div>
                                    <div id="accordion-control-group<?php echo $index ?>" class="collapse <?php echo $index == '1'  ? 'show' : '';  ?>">
                                        <div class="card-body">
                                            <div class="row mr-auto ">
                                                <?php if ($filterValues) { ?>
                                                    <div class="col-md-12">
                                                        <div class="ecommerce-breadcumb header-elements-md-inline p-0">
                                                            <div class="ecommerce-buttons"></div>
                                                            <div class="ecommerce-buttons header-elements">
                                                                <a href="javascript:;" class="btn btn-success btn-sm sidebar-control d-md-block search-all-<?php echo $this->uniqId ?>">
                                                                    <i class="icon-checkbox-checked"></i> <?php echo Lang::line('SEARCH_ALL') ?>
                                                                </a>
                                                                <a href="javascript:;" class="btn btn-danger btn-sm sidebar-control d-md-block clear-all-<?php echo $this->uniqId ?>">
                                                                    <i class="icon-blocked"></i> <?php echo Lang::line('SEARCH_ALL_CLEAR') ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php foreach ($filterValues as $key => $row) { ?>
                                                        <div class="col-4">
                                                            <div class="d-flex">
                                                                <div class="checkbox-list mr-1">
                                                                    <input type="checkbox" name="registrytypename" id="registrytypename_<?php echo $key . '_' . $index ?>" <?php echo issetParam($row['ischeck']) === '1' ? 'checked="checked"' : '' ?> value="<?php echo $row['registrytypeid'] ?>">
                                                                </div>
                                                                <label for="registrytypename_<?php echo $key . '_' . $index ?>" ><?php echo $row['registrytypename'] ?></label>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php $index++;
                        } ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="bottom-tab2-<?php echo $this->uniqId ?>"></div>
        </div>
    </div>
    <div class="clearfix"></div>
    <style type="text/css">
        .full-<?php echo $this->uniqId ?> .card-group-control .card-title>a:before, 
        .full-<?php echo $this->uniqId ?> .card-group-control-left .card-title>a {
            font-size: 13px;
        }
        
        .full-<?php echo $this->uniqId ?> .card {
            border:none;
            margin-bottom: 5px;
        } 
        
        .full-<?php echo $this->uniqId ?> .card-header-no-padding, 
        .full-<?php echo $this->uniqId ?> .card-header:not([class*=bg-]):not([class*=alpha-]) {
            padding: 5px;
        }
        
        .full-<?php echo $this->uniqId ?> .card-group-control-right .card-title>a:before {
            /* right: 1.25rem; */
        }
        .full-<?php echo $this->uniqId ?> .card-group-control-right .card-title>a {
            color: #2196f3;
            text-transform: uppercase;
            font-weight: 600;
            font-size: 11px;
        }
        .full-<?php echo $this->uniqId ?> .card-group-control-right .card-title {
            padding: 5px 0;
        }
        .full-<?php echo $this->uniqId ?> .table-responsive {
            max-width: 1600px;
            width: auto;
            overflow-x: scroll;
        }
        @media (max-width: 1440px) {
            .full-<?php echo $this->uniqId ?> .table-responsive {
                max-width: 1100px;
            }
        }
        @media (max-width: 1280px) {
            .full-<?php echo $this->uniqId ?> .table-responsive {
                max-width: 1000px;
            }
        }
        .full-<?php echo $this->uniqId ?> .table td,
        .full-<?php echo $this->uniqId ?> .table th {
            padding: 3px;
            /* white-space: nowrap; */
            font-size: 10px;
        }
        .full-<?php echo $this->uniqId ?> .table th {
            font-weight: bold;
        }
        .full-<?php echo $this->uniqId ?> .ecommerce-breadcumb .ecommerce-buttons .btn > i {
            margin-right: 5px;
        }
        
        .full-<?php echo $this->uniqId ?> h6.card-title {
            line-height: 2px;
        }
        .full-<?php echo $this->uniqId ?> table {
            table-layout: fixed;
        }
    </style>
    <script type="text/javascript">

        $(function () {
            Core.initUniform($('.filter-<?php echo $this->uniqId ?>'));
        });

        $('body').on('click', '.view-register-<?php echo $this->uniqId ?>', function () {
            var $this = $(this);
            var $dataComp = $this.attr('data-row');

            $.ajax({
                type: 'post',
                url: 'mdobject/searchFilterValues',
                data: {
                    uniqId: '<?php echo $this->uniqId ?>',
                    dataComp: $dataComp,
                    metaDataId: '<?php echo $this->metaDataId ?>',
                    options: $('.option-<?php echo $this->uniqId ?>').find('input').serializeArray()
                },
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    if (data.status === 'error') {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error',
                            text: data.text,
                            type: 'error',
                            sticker: false
                        });
                        Core.unblockUI();
                        return;
                    }
                    $('#bottom-tab2-<?php echo $this->uniqId ?>').empty().append(data.Html).promise().done(function () {
                        $('a[href="#bottom-tab2-<?php echo $this->uniqId ?>"]').trigger('click');
                    });
                    Core.unblockUI();
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }

                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: msg,
                        type: 'error',
                        sticker: false
                    });
                    Core.unblockUI();
                }
            });
        });

        $('body').on('click', '.back-register-<?php echo $this->uniqId ?>', function () {
            $('a[href="#bottom-tab1-<?php echo $this->uniqId ?>"]').trigger('click');
        });
        
        $('body').on('click', '.search-all-<?php echo $this->uniqId ?>', function () {
            var $parent = $(this).closest('.card-body');
            $parent.find(':checkbox').each(function() {
                this.checked = true;                        
            });
            $parent.find('.checker > span').addClass('checked');
        });
        
        $('body').on('click', '.clear-all-<?php echo $this->uniqId ?>', function () {
            var $parent = $(this).closest('.card-body');
            $parent.find(':checkbox').each(function() {
                this.checked = false;                        
            });
            $parent.find('.checker > span').removeClass('checked');
        });
        
    </script>
<?php } ?>