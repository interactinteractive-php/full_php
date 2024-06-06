<div class="content finance">
    <div class="container-fluid">
        <!-- <a class="list-icons-item" id="fullscreen" data-action="fullscreen"></a>
     -->
        <!-- body -->

        <div class="row top">
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('fin_section1'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="fin_section1" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('fin_section2'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="fin_section2" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="row top">
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('fin_section3'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="fin_section3" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('fin_section4'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="fin_section4" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row top">
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('fin_section5'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="fin_section5" class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="box-shadow ">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0"><?php echo $this->lang->line('fin_section6'); ?></h6>
                    </div>
                    <div class="card-body">
                        <div id="fin_section6" class="chart"></div>
                    </div>
                </div>
            </div>
        </div>
       
        <!-- end body -->
    </div>
</div>

<!-- amCharts javascript code -->
<script type="text/javascript">
    $('.sidebar-control i').addClass('hide');
    $('.sidebar-control').addClass('d-flex');

    var p9section5 = <?php echo json_encode($this->layoutPositionArr['fin_3']); ?>;
    
    console.log(p9section5);
    AmCharts.makeChart("fin_section1", {
        "type": "serial",
        "categoryField": "mth",
        "startDuration": 1,
        "theme": "default",
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"bottom"
        },
        "colors": [
            "#ee2f78", 
            "#6794dc",
            "#EF7E32",
        ],
        'legend': {
            spacing: 0,
            position: 'top',
            align: 'right',
            // 'markerType': 'circle',
            // 'periodValueText': 'Нийт: [[value.sum]]',
            'labelText': '[[title]] ',
            'valueText': '[[value]]',
            'valueWidth': 120
        },
        "graphs": [{
            "balloonText": "[[creditamount]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('fin_section1_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "type": "column",
            "valueField": "creditamount",
            "y": 20
            },
            {
                "balloonText": "[[debitamount]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "<?php echo $this->lang->line('fin_section1_label_1'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[debitamount]]",
                "type": "column",
                "valueField": "debitamount"
            },
        ],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['fin_1']); ?>
    });

    AmCharts.makeChart("fin_section2", {
        "type": "serial",
        "categoryField": "mth",
        "startDuration": 1,
        "theme": "default",
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"bottom"
        },
        "colors": [
            "#ee2f78", 
            "#6794dc",
            "#EF7E32",
        ],
        'legend': {
            spacing: 0,
            position: 'top',
            align: 'right',
            // 'markerType': 'circle',
            // 'periodValueText': 'Нийт: [[value.sum]]',
            'labelText': '[[title]] ',
            'valueText': '[[value]]',
            'valueWidth': 120
        },
        "graphs": [{
            "balloonText": "[[creditamount]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('fin_section2_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "type": "column",
            "valueField": "creditamount",
            "y": 20
            },
            {
                "balloonText": "[[debitamount]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "<?php echo $this->lang->line('fin_section2_label_2'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[debitamount]]",
                "type": "column",
                "valueField": "debitamount"
            },
        ],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['fin_2']); ?>
    });

    AmCharts.makeChart("fin_section3", {
        "type": "serial",
        "categoryField": "mth",
        "startDuration": 1,
        "theme": "default",
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"bottom"
        },
        "colors": [
            "#ee2f78", 
            "#6794dc",
            "#EF7E32",
        ],
        'legend': {
            spacing: 0,
            position: 'top',
            align: 'right',
            // 'markerType': 'circle',
            // 'periodValueText': 'Нийт: [[value.sum]]',
            'labelText': '[[title]] ',
            'valueText': '[[value]]',
            'valueWidth': 120
        },
        "graphs": [{
            "balloonText": "[[creditamount]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('fin_section3_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "type": "column",
            "valueField": "creditamount",
            "y": 20
            },
            {
                "balloonText": "[[debitamount]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "<?php echo $this->lang->line('fin_section3_label_2'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[debitamount]]",
                "type": "column",
                "valueField": "debitamount"
            },
        ],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['fin_3']); ?>
    });

    AmCharts.makeChart("fin_section4", {
        "type": "serial",
        "categoryField": "mth",
        "startDuration": 1,
        "theme": "default",
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"bottom"
        },
        "colors": [
            "#ee2f78", 
            "#6794dc",
            "#EF7E32",
        ],
        'legend': {
            spacing: 0,
            position: 'top',
            align: 'right',
            // 'markerType': 'circle',
            // 'periodValueText': 'Нийт: [[value.sum]]',
            'labelText': '[[title]] ',
            'valueText': '[[value]]',
            'valueWidth': 120
        },
        "graphs": [{
            "balloonText": "[[creditamount]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('fin_section4_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "type": "column",
            "valueField": "creditamount",
            "y": 20
            },
            {
                "balloonText": "[[debitamount]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "<?php echo $this->lang->line('fin_section4_label_2'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[debitamount]]",
                "type": "column",
                "valueField": "debitamount"
            },
        ],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['fin_4']); ?>
    });
    AmCharts.makeChart("fin_section5", {
        "type": "serial",
        "categoryField": "mth",
        "startDuration": 1,
        "theme": "default",
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"bottom"
        },
        "colors": [
            "#ee2f78", 
            "#6794dc",
            "#EF7E32",
        ],
        'legend': {
            spacing: 0,
            position: 'top',
            align: 'right',
            // 'markerType': 'circle',
            // 'periodValueText': 'Нийт: [[value.sum]]',
            'labelText': '[[title]] ',
            'valueText': '[[value]]',
            'valueWidth': 120
        },
        "graphs": [{
            "balloonText": "[[creditamount]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('fin_section5_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "type": "column",
            "valueField": "creditamount",
            "y": 20
            },
            {
                "balloonText": "[[debitamount]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "<?php echo $this->lang->line('fin_section5_label_2'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[debitamount]]",
                "type": "column",
                "valueField": "debitamount"
            },
        ],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['fin_5']); ?>
    });
    AmCharts.makeChart("fin_section6", {
        "type": "serial",
        "categoryField": "mth",
        "startDuration": 1,
        "theme": "default",
        "rotate": true,
        "categoryAxis": {
            "gridPosition": "middle",
            "position":"bottom"
        },
        "colors": [
            "#ee2f78", 
            "#6794dc",
            "#EF7E32",
        ],
        'legend': {
            spacing: 0,
            position: 'top',
            align: 'right',
            // 'markerType': 'circle',
            // 'periodValueText': 'Нийт: [[value.sum]]',
            'labelText': '[[title]] ',
            'valueText': '[[value]]',
            'valueWidth': 120
        },
        "graphs": [{
            "balloonText": "[[creditamount]] / [[value]]",
            "fillAlphas": 5,
            "precision": -3,
            "id": "AmGraph-1",
            "title": "<?php echo $this->lang->line('fin_section6_label_1'); ?>",
            "labelPosition": "top",
            "labelRotation":0,
            "type": "column",
            "valueField": "creditamount",
            "y": 20
            },
            {
                "balloonText": "[[debitamount]] / [[value]]",
                "fillAlphas": 1,
                "id": "AmGraph-2",
                "title": "<?php echo $this->lang->line('fin_section6_label_2'); ?>",
                "labelPosition": "top",
                "labelRotation":0,
                "labelText": "  [[debitamount]]",
                "type": "column",
                "valueField": "debitamount"
            },
        ],
        "dataProvider": <?php echo json_encode($this->layoutPositionArr['fin_6']); ?>
    });

</script>