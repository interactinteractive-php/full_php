<div class="row viewtype_<?php echo $this->viewType ?> fcdiv_<?php echo $this->metaDataId; ?>">
    <?php
    if ($this->isEmptyCriteria == false) {
    ?>
    <div class="d-flex m-2 mt0">
        <?php echo $this->searchForm; ?>
    </div>
    <?php
    }
    ?>
    <div class="col col-cus" >
        <div id="objectdatagrid-<?php echo $this->metaDataId; ?>" class="col not-datagrid dv-calendar-layout div-objectdatagrid-<?php echo $this->metaDataId; ?>"></div>
        <div class="col-md-4 calendar-bp-layout" style="display: none" ></div>
    </div>
</div>

<?php echo $this->calendarScripts; ?>

<style type="text/css">
    .fcdiv_<?php echo $this->metaDataId; ?> {
        background: #F3F4F6 !important;
        padding: 10px;
        
        .fc {
            .fc-toolbar.fc-header-toolbar {
                margin: 0 0 1.5em 0;
            }
        }

        .col-cus {
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            background: #FFF;
            border-top-left-radius: 20px !important;
            border-bottom-left-radius: 20px !important;
            border-top-right-radius: 20px !important;
            border-bottom-right-radius: 20px !important;
            padding-right: 0;
        }

        .fc-toolbar h2 {
            line-height: 2em;
            font-size: 1.5em;
        }
        .fc table,
        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #F3F4F6 !important;
        }
        .fa {
            font-family: "Font Awesome 5 Pro" !important;
            font-size: 0.89rem !important;
        }

        .sidebar-calendar {
            background: #F3F4F6 !important;
        }

        .dv-calendar-layout {
            background: #FFF !important;
            margin: 30px 0;
        }

        .calendar-bp-layout {
            margin-left: 0;
            background: #F9FDFF !important;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            padding: 30px;
        }

        .fc-button:hover {
            color: #FFF !important;
            background-color: #009EF7 !important;
            border: 2px solid #009EF7 !important;
        }

        .fc-button {
            background-color: #FFF !important;
            border: 2px solid #F5F6F7 !important;
            box-shadow: 0px 2px 5px 0px #26334D08 !important;
            border-radius: 100px;
            color: #6B7A99 !important;
        }

        .fc-today-button {
            background-color: #FFF !important;
            border: 2px solid #F5F6F7 !important;
            box-shadow: 0px 2px 5px 0px #26334D08 !important;
            border-radius: 100px;
            color: #6B7A99 !important;
        }

        .fc-button-group .fc-button:first-child {
            border-top-left-radius: 30px;
            border-bottom-left-radius: 30px;
        }

        .fc-button-group .fc-button:last-child {
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        .fc-button {
            font-size: 14px;
            line-height: 1.33;
            vertical-align: middle;
        }
        .fc-col-header-cell-cushion {
            color: #6B7A99;
            font-size: 16px;
            vertical-align: middle;
            font-weight: 700;
        }
        .fc th {
            height: 72px;
            vertical-align: middle;
            color: #6B7A99;
            font-weight: 700;
        }
        .fc .fc-daygrid-day-frame {
            min-height: 150px;
        }
        .fc .fc-daygrid-day.fc-day-today {
            background-color: #009ef72e;
            /* .fc-daygrid-day-number {
                color: #FFF !important;
            } */
        }
        .fc-daygrid-day-number {
            color: #6B7A99 !important;
        }
        .fc-view-container .fc-view>table .fc-head tr:first-child>td, .fc-view-container .fc-view>table .fc-head tr:first-child>th {
            background: #FFF !important;
            padding-top: 26px;
            padding-bottom: 26px;
            font-weight: 700;
        }
        .fc-view {
            border: 1px solid #FFF;
        }
        .fc-head-container {
            padding: 0 !important;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #F5F6F7;
        }
        
        .fc-day-grid-event {
            border: 1px solid #F00;
        }
        
        .fc-image1 {
            margin-right: 5px; 
            width: 18px; 
            height: 18px;
        }
        .fc .fc-timegrid-axis-cushion, .fc .fc-timegrid-slot-label-cushion {
            font-size: 16px;
            padding: 25px;
            color: #6B7A99;
            font-weight: 700;
        }
        .fc-daygrid-event-harness {
            display: none;
            .fc-daygrid-dot-event:hover {
                background: #00BCD4 !important;
            }
        }
        .fc-daygrid-day-frame {
            height: 100%;
        }
        .fc-highlight {
            background : #673ab7 !important;
        }
        .fc-timegrid-event-harness {
            .fc-event {
                background: #FFF !important;
                border-top-right-radius: 10px;
                border-bottom-right-radius: 10px;
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
                box-shadow: 0px 2px 4px 0px #00000040;
                min-height: 50px;
            }
            .fc-circle-event {
                color: #333;
            }
        }
        .fc-circle-event {
            overflow: hidden;
            padding: 1px 5px;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
        }
        .fc .fc-daygrid-day-top {
            display: flex !important;
            flex-direction: row;
            width: 100%;
            .fc-daygrid-day-number {
                margin-left: auto;
            }
        }
        .fc-event-pos-2,
        .fc-event-pos-1
         {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
            overflow: hidden;
        }
    }
    .dv-calendar .fc-prev-button, 
    .dv-calendar .fc-next-button,
    .dv-calendar .fc-toolbar .btn
    {
        background: #dbe4f7 !important;
        border-color: #dbe4f7 !important;
        padding: 6px 16px !important;
        height: auto !important;
        text-transform: uppercase;
    }
    
    .sidebar-calendar {
        width: 300px;
        background: #FFF;
        padding: 10px 15px;
    }
    .sidebar-calendar .form-control,
    .sidebar-calendar input.calendar {
        height: 26px;
    }
    .sidebar-calendar input.calendar {
        outline: 0;
        padding: 0px 10px;
    }
    .sidebar-calendar .form-control {
        border-radius: 0;
    }
    .sidebar-calendar .with-icon li {
        padding: 5px 10px;
        cursor: pointer;
    }
    .sidebar-calendar .with-icon li:hover {
        background: #e9e9e9;
    }
    .sidebar-calendar .with-icon li.active {
        background: #d5d9df;
    }
    .sidebar-calendar .dv-filter-criteria-condition {
        display: none;
    }
    .fc-body .fc-sat, .fc-body .fc-sun {
        background-color: #f6f6f6;
    }
    .fc-body .fc-today, 
    .fc-body .fc-sat.fc-today, 
    .fc-body .fc-sun.fc-today {
        background-color: #fffae4;
    }
    .fc-body .fc-day-number {
        border-radius: 50%;
        margin: 2px;
        min-width: 18px;
        padding: 5px;
        text-align: center;
        line-height: 18px;
        border-radius: 50%;
    }
    .fc-body .fc-day-number:hover {
        background-color: #EBEBEB;
        transition: background-color 0.5s;
    }
    .fc-body .fc-today .fc-day-number {
        color: #FFF;
        background-color: #2196f3;
    }
    .fc-body .fc-today .fc-day-number:hover {
        color: #FFF;
    }
    .fc-day-grid-event, tr:first-child>td>.fc-day-grid-event {
        margin: 5px;
        margin-top: 0;
        padding: 2px 4px;
    }
    .fc-day-grid-event:hover, tr:first-child>td>.fc-day-grid-event:hover {
        padding-left: 6px;
    }
    .fc-basicWeek-view .fc-day-grid-event, 
    .fc-basicDay-view .fc-day-grid-event {
        margin: 5px;
        margin-bottom: 0;
        padding: 2px 4px;
    }
    .fc-basicWeek-view tr:first-child>td>.fc-day-grid-event, 
    .fc-basicDay-view tr:first-child>td>.fc-day-grid-event {
        margin: 5px;
        margin-top: 12px;
        margin-bottom: 0;
        padding: 2px 4px;
    }
    .fc-day-top span.badge:not(.badge-mark), 
    .fc-basicWeek-view span.badge:not(.badge-mark), 
    .fc-basicDay-view span.badge:not(.badge-mark) {
        border-radius: 0;
    }
    .fc-day:hover {
        background-color: #f9f9f9;
    }
    .fc-view-container .fc-image1 {
        margin-right: 5px; 
        width: 18px; 
        height: 18px;
    }
    .fc-more-popover {
        width: 260px;
    }
    .fc-more-popover .fc-event-container {
        max-height: 256px;
        overflow: auto;
    }
    .fc-more-popover .fc-body .fc-image1 {
        width: 30px; 
        height: 30px;
    }
    .fc-body .badge-mark {
        width: 15px;
        height: 15px;
        margin-top: 2px;
    }
    .fc-day-grid-event .fc-title {
        color: #fff;
        font-size: 12px;
    }
    .fc-view-container .fc-view > table .fc-head th.fc-today {
        font-weight: bold;
    }
    .fc-view-container .fc-view > table .fc-head tr.cloned-head > th {
        background: #e6f3fb;
        padding: 5px 0;
    }
    .span-group {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-align: stretch;
        align-items: stretch;
        width: 95%;
        margin: 0 0 6px 5px;
    }
    .span-group > .span-group-control {
        position: relative;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        width: 1%;
        margin-bottom: 0;
        white-space: nowrap;
        overflow: hidden;
        border-radius: 3px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border: 1px #ddd solid;
        height: 23px;
        background-color: #eee;
    }
    .span-group > .input-group-append > .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        height: 25px;
        padding: 0 4px;
    }
    .fc-basicWeek-view .span-group, 
    .fc-basicDay-view .span-group {
        margin-top: 10px;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?>.fc-ltr .fc-time-grid .fc-event-container {
        margin: 0 4px 0 4px;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> th.fc-axis {
        width: 23px!important;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> hr.fc-divider {
        border-color: #b0b0b0;
        padding: 0;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-axis {
        height: auto;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-event-pos-1 {
        color: #000;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-event-pos-2 {
        color: #666;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-time-grid-event .fc-title {
        font-size: 12px;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-day-grid-event .fc-event-pos-1, 
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-day-grid-event .fc-event-pos-2 {
        font-size: 12px;
        padding-top: 2px;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-time-grid-event .fc-event-pos-1, 
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-time-grid-event .fc-event-pos-2 {
        font-size: 12px;
        padding-top: 2px;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-time-grid .fc-event {
        padding: 3px 5px;
        /*box-shadow: 0 0.25rem 0.4rem rgba(0,0,0,.2);*/
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-time-grid .fc-event.fc-normal-lineheight {
        padding: 0 5px;
        line-height: 11px;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-time-grid .fc-slats td {
        height: 2.1em;
    }
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-time-grid-event .fc-content.fc-content-middle {
        top: 50%;
        transform: translateY(-50%);
    }
    
    <?php
    if ($titleColor = issetParam($this->row['dataViewLayoutTypes']['calendar']['fields']['titleColor'])) {
    ?>
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-day-grid-event .fc-title, 
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-time-grid-event .fc-title, 
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-day-grid-event .fc-content {
        color: <?php echo $titleColor; ?>;
    }
    <?php
    }
    if ($cellMinHeight = issetParam($this->row['dataViewLayoutTypes']['calendar']['fields']['cellMinHeight'])) {
    ?>
    .div-objectdatagrid-<?php echo $this->metaDataId; ?> .fc-basic-view .fc-body .fc-row {
        min-height: <?php echo $cellMinHeight; ?> !important;
    }
    <?php
    }
    ?>
</style>