<div class="col-md-12" id="businessPorcessWindow">
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
            <?php
            if (!$this->isAjax) {
                ?>
                <div class="caption buttons mr10">
                    <?php
                    echo html_tag('a', array(
                        'href' => 'javascript:history.back();',
                        'class' => 'btn btn-circle btn-secondary card-subject-btn-border',
                            ), '<i class="icon-arrow-left7"></i>', true
                    );
                    ?>     
                </div>
                    <?php
                }
                ?>
            <div class="card-title">
                <span class="caption-subject font-weight-bold uppercase card-subject-blue">
            <?php echo $this->title; ?>
                </span>
                <span class="caption-subject font-weight-bold text-uppercase text-gray2">УДИРДАХ</span>
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
<?php
if (isset($this->headerInfo)) {
    ?>
            <div class="row">
                <div class="col-md-12">
            <?php echo $this->headerInfo; ?>
                </div>
            </div>
                    <?php
                }
                ?>
        <div class="row mt20">
            <div class="col-md-12">
        <?php
        if (!empty($this->errorMessage)) {
            ?>   
                    <div class="alert alert-danger"><?php echo $this->errorMessage; ?></div>
                    <?php
                } else {
                    ?>
                    <div class="wizard-steps">
                    <?php
                    $trgLifecycleId = "";
                    $finished = false;

                    if (!empty($this->getMetaLifecycle)) {
                        foreach ($this->getMetaLifecycle as $index => $lifecycle) {
                            $class = "";
                            if ($lifecycle['status']) {
                                $class = "completed-step";
                                if (!empty($lifecycle['targetlifecycleid'])) {
                                    $trgLifecycleId = $lifecycle['targetlifecycleid'];
                                }
                            } elseif ($lifecycle['id'] == $trgLifecycleId) {
                                $class = "active-step";
                            }
                            if ($index == 0) {
                                $class .= " first";
                            }

                            $lastchild = count($this->getMetaLifecycle) - 1;
                            if ($this->getMetaLifecycle[$lastchild]['status']) {
                                $finished = true;
                            }
                            ?>
                                <div class="<?php echo $class; ?>" data-status="<?php echo $lifecycle['status']; ?>" data-step="<?php echo $lifecycle['id']; ?>">
                                    <a href="javascript:;">
                                        <span class="badge"><?php echo $index + 1; ?></span>  
                                        <span class="badge"><?php echo $lifecycle['name']; ?></span>
                                    </a>
                                </div>
            <?php
        }
    }
    ?>
                        <?php
                        if ($finished) {
                            ?>
                            <div class="step-finished">
                                <input type="hidden" name="finished" value="<?php $finished; ?>"/>
                                <span class="badge"> </span>
                            </div>
        <?php
    }
    ?>
                    </div>
                    <div class="clearfix w-100"></div>
                        <?php
                    }
                    ?>
                <div class="row mt20" style="width: 500px; margin-left: 0px">
                    <div id="lifecycle_tree">
                        <input type="hidden" id="sub_lifecycle" name="sub_lifecycle" class="form-control select2"/>
                    </div>
                </div>
                <div class="clearfix w-100 mb20"></div>
<?php echo Form::hidden(array('id' => 'selectedLifeCycleId')); ?>
<?php echo Form::hidden(array('id' => 'dataModelId', 'name' => 'dataModelId', 'value' => $this->dataModelId)); ?>
                <?php echo Form::hidden(array('id' => 'lcBookId', 'name' => 'lcBookId', 'value' => $this->lcBookId)); ?>
                <?php echo Form::hidden(array('id' => 'sourceId', 'name' => 'sourceId', 'value' => $this->sourceId)); ?>
            </div>
            <div class="clearfix w-100"></div>
        </div>
    </div>
</div>    

<script type="text/javascript">
    var businessProcessWindowId = '#businessPorcessWindow';
    $(function () {
        var subStep = "";
        $(".wizard-steps").find('div').on("click", function () {
            var to_be_selected = null;
            var step = $(this).data('step');

            $(this).addClass('active-step');
            $(".wizard-steps div.active-step").not(this).removeClass('active-step');

            $.ajax({
                type: 'post',
                url: "mdtaskflow/getChildLifecycle",
                data: {parent_id: step, source_id: '<?php echo $this->sourceId; ?>'},
                dataType: 'json',
                success: function (data) {
                    $("#metaProcessDetial").empty();
                    if (data !== null) {
                        $('input[name=sub_lifecycle]').select2('destroy');
                        $('input[name=sub_lifecycle]').select2({
                            data: {
                                results: data, text: function (item) {
                                    return item.name;
                                }
                            },
                            id: 'id',
                            tag: 'name',
                            multiple: false,
                            minimumResultsForSearch: -1,
                            formatSelection: function (item) {
                                return item.name;
                            },
                            formatResult: function (item, container, query) {
                                return item.name;
                            },
                            initSelection: function (item, callback) {
                                // despite select2 having already read the whole sources list when you 
                                // do .val(n) you have to explicitly tell it how to find that item again.
                                to_be_selected = data[0];
                                $.each(data, function (index, thing) {
                                    if (thing.selected) {
                                        to_be_selected = thing;
                                        return;
                                    }
                                });
                                callback(to_be_selected);
                            }
                        }).on('select2-selecting', function (e, data) {
                            if (typeof (data) !== 'undefined') {
                                e.object = data;
                            }
                            businessProcess(e.object.id, $("#sourceId", businessProcessWindowId).val());
                        });
                        $("input[name=sub_lifecycle]").trigger('select2-selecting', to_be_selected);
                    } else {
                        $("#sub_lifecycle").select2('destroy');
                        businessProcess(step, $("#sourceId", businessProcessWindowId).val());
                    }

                },
                error: function (xhr, textStatus, error) {
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                },
                async: false
            });
        });

        if ($(".wizard-steps").find(".active-step").length == 0) {
            $(".wizard-steps div:first").addClass("active-step");
        }

        $(".wizard-steps").find('.active-step').trigger('click');
    });
</script>
