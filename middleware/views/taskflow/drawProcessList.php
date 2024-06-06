<div id="taskflow-list" class="tree-demo"></div>
<script type="text/javascript">
    $(function () {

        $.contextMenu({
            selector: '.process-log-view',
            callback: function (key, opt) {
                if (key === 'logview') {
                    var row = $(this).parents('a');
                    var lifeCycleDtlId = row.find('input[name="lifeCycleDtlId[]"]').val();
                    var sourceId = row.find('input[name="sourceId[]"]').val();
                    getBusinessProcessProcessLog(lifeCycleDtlId, sourceId);
                }
            },
            items: {
                "logview": {name: "Process log view", icon: "eye"}
            }
        });

        var tree = $('#taskflow-list').jstree({
            'plugins': ["types"], //"checkbox", 
            'core': {
                "themes": {
                    "responsive": false
                },
                'data': [<?php echo $this->lifeCycleAllList; ?>]
            },
            "checkbox": {
                real_checkboxes: false,
                real_checkboxes_names: function (n) {
                    var nid = 0;
                    $(n).each(function (data) {
                        nid = $(this).attr("nodeid");
                    });
                    return (["check_" + nid, nid]);
                },
                two_state: true
            },
            "types": {
                "default": {
                    "icon": "fa fa-play-circle text-orange-400"
                },
                "file": {
                    "icon": "fa fa-play-circle text-orange-400"
                }
            }
        });
        
        $('#taskflow-list').on('click', 'i.jstree-themeicon, span.run-process', function () {
            var _this = $(this);
            var row = _this.parents('a');
            var statusId = row.find('input[name="wfmStatusId[]"]').val();
            var processMetaDataId = row.find('input[name="processMetaDataId[]"]').val();
            var lifeCycleDtlId = row.find('input[name="lifeCycleDtlId[]"]').val();
            var lifeCycleId = row.find('input[name="lifeCycleId[]"]').val();
            var dataModelId = row.find('input[name="dataModelId[]"]').val();
            var sourceId = row.find('input[name="sourceId[]"]').val();
            var isSolved = row.find('input[name="isSolved[]"]').val();
            var lcBookId = row.find('input[name="lcBookId[]"]').val();
            var processStatusId = row.find('input[name="processStatusId[]"]').val(); //new, inProcess, done
            var type = row.find('input[name="type[]"]').val();
            if (type === 'process' && (processStatusId === 'new' || processStatusId === 'inProcess') && isSolved === 'true') {
                runProcessLifeCycle(processMetaDataId, lifeCycleDtlId, lifeCycleId, dataModelId, sourceId, '#object-value-list-<?php echo $this->dataModelId; ?>', lcBookId, statusId);
            }
        });
    });

</script>