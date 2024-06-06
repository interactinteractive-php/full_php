<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="card light">
    <div class="card-header card-header-no-padding header-elements-inline">
        <div class="card-title">
            <i class="fa fa-cogs font-green-sharp"></i>
            <span class="caption-subject font-green-sharp font-weight-bold uppercase">Ерөнхий мэдээлэл</span>
        </div>
        <div class="tools">
            <a title="" data-original-title="" href="javascript:;" class="reload">
            </a>
        </div>
    </div>
    <div class="card-body form">
       

        <div id="message_c"></div>
        <div class="table-scrollable">
            <table class="table table-hover" id="dlgGrid"></table>
        </div>
    </div>
</div>


<script type="text/javascript">
    var dlgMainGrid = '#dlgGrid';
    $(function () {
        create();
    });

    function create()
    {
        $(dlgMainGrid).datagrid({
            data: <?php echo json_encode($this->row['data']); ?>,
            singleSelect: true,
            fitColumns: true,
            rownumbers: true,
            showFooter: true,
            height: 300,
            columns: [[
    <?php
    
    foreach (array_merge($this->row['facts']) as $key) {
        echo '{field: "' . $key['field'] . '", title: "' . $key['field'] . '", sortable: true},';
    }
    
    foreach (array_merge($this->row['rows'], $this->row['cols']) as $key) {
        echo '{field: "' . $key['field'] . '", title: "' . $key['title'] . '", sortable: true},';
    }
    ?>
                ]],
            onLoadSuccess: function (data) {
            }
        });
    }

</script>