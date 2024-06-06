<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="row">
    <div class="col-md-12">
        <?php
        echo Form::create(array('class' => 'form-horizontal', 'id' => 'processDtlCriteria-form', 'method' => 'post'));
        ?>
        <div class="tabbable-line">
            <ul class="nav nav-tabs process-dtl-criteria-tabs">
                <li class="nav-item">
                    <a href="#tab_criteria_config" class="nav-link active" data-toggle="tab">Criteria</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_advanced_criteria_config" class="nav-link" data-toggle="tab" title="Процесс дуудахад жагсаалт дээрээс мөрийн утгууд ижилхэн байх нөхцлийг шалгана">Advanced criteria</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_confirm_msg_config" class="nav-link" data-toggle="tab">Confirm message</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_criteria_config">
                    <?php
                    echo Form::textArea(
                        array(
                            'name' => 'groupBpDtlCriteria',
                            'id' => 'groupBpDtlCriteria',
                            'class' => 'form-control form-control-sm',
                            'value' => $this->groupBpDtlCriteria
                        )
                    );
                    ?>
                </div>
                <div class="tab-pane" id="tab_advanced_criteria_config">
                    <?php
                    echo Form::textArea(
                        array(
                            'name' => 'groupBpDtlAdvancedCriteria',
                            'id' => 'groupBpDtlAdvancedCriteria',
                            'class' => 'form-control form-control-sm',
                            'value' => $this->groupBpDtlAdvancedCriteria
                        )
                    );
                    ?>
                </div>
                <div class="tab-pane" id="tab_confirm_msg_config">
                    <?php
                    echo Form::textArea(
                        array(
                            'name' => 'groupBpDtlConfirmMsg',
                            'id' => 'groupBpDtlConfirmMsg',
                            'class' => 'form-control form-control-sm',
                            'value' => $this->groupBpDtlConfirmMsg
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <?php
        echo Form::close();
        ?>
    </div>
</div> 
<script type="text/javascript">
    var groupBpDtlAdvancedCriteriaEditor;
    
    if (typeof CodeMirror === 'undefined') {
        $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
            $.getStylesheet(URL_APP + 'assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css');
            setGroupBpDtlAdvancedCriteriaEditor();
        });
    } else {
        setGroupBpDtlAdvancedCriteriaEditor();
    }
    
    function setGroupBpDtlAdvancedCriteriaEditor() {
        groupBpDtlAdvancedCriteriaEditor = CodeMirror.fromTextArea(document.getElementById("groupBpDtlAdvancedCriteria"), {
            mode: 'javascript',
            styleActiveLine: true,
            lineNumbers: true,
            lineWrapping: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            indentUnit: 4,
            theme: 'material', 
            extraKeys: {
                "F11": function(cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                },
                "Esc": function(cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
            }
        });
    }

    $(function() {
        $('.process-dtl-criteria-tabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            groupBpDtlAdvancedCriteriaEditor.refresh();
        });
    });
</script>