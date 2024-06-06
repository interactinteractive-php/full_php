<div class="erl-parent" data-id="" data-name="">    
    <div class="table-toolbar">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group btn-group-devided float-right">
                    <span class="workflow-buttons-<?php echo $this->id; ?>" style=""></span>
                </div>
            </div>
        </div>
    </div>    
    <table style="table-layout: fixed; width: 100%; border-top: 1px #999 solid; border-bottom: 1px #999 solid; border-left: 1px #999 solid;">
        <tbody>
            <tr>
                <td style="width: 550px; vertical-align: top; padding: 0 10px 0 10px;">
                    <div class="ecl-height ecl-jstree-container">
                    </div>
                    <hr style="margin: 8px 0;">
                    <table style="width: 100%; display:none;">
                        <tbody>
                            <tr>
                                <td>
                                    Регистрийн дугаар:
                                </td>
                                <td style="width: 32%">
                                    <strong><?php echo isset($this->row['stateregnumber']) ? issetVar($this->row['stateregnumber']) : ''; ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Овог:
                                </td>
                                <td>
                                    <strong><?php echo isset($this->row['lastname']) ? issetVar($this->row['lastname']) : ''; ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Нэр:
                                </td>
                                <td>
                                    <strong><?php echo isset($this->row['firstname']) ? issetVar($this->row['firstname']): ''; ?></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>                  
                    <div class="mt5" style="font-size: 11px;">
                        Бэлтгэсэн: <strong><?php echo isset($this->row['preparedfilecount']) ? issetVar($this->row['preparedfilecount']) : '0'; ?></strong> &nbsp;&nbsp;&nbsp;
                    </div>
                </td>
                <td style="width: 100%; vertical-align: top; padding: 0;">
                    <span style="position: absolute; cursor: pointer;" onclick="erlImgFullsize(this)" class="ml5 mt5"><i class="fa fa-search-plus"></i> Томоор харах</span>
                    <div class="erl-image-preview"></div>
                </td>
                <td style="width: 350px; vertical-align: top; padding: 0;">
                    <div class="col-md-12 main-view-metadata-<?php echo $this->uniqid ?>">
                        <form class="xs-form">
                            <div class="col-md-12 cvl-bookMeta mt10"></div>
                        </form>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="hidden hidden-html-<?php echo $this->uniqid ?>">
    <div id="metadata-<?php echo $this->uniqid ?>"></div>
</div>

<link rel="stylesheet" href="assets/custom/addon/plugins/jqTree/jqtree.css">

<style type="text/css">
    .erl-image-preview {
        padding: 20px;
        overflow: auto;
        border: 1px #999 solid;
        border-top: 0;
        border-bottom: 0;
        border-right: 0;
        background: #ccc;
        text-align: center;
    }    
    .erl-image-preview img {
        -webkit-box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.3);
        -moz-box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.3);
        box-shadow: 0px 0px 15px 3px rgba(0,0,0,0.3);
        max-width: 100%;
        /*max-height: 100%;*/
    }
    .ecl-height {
        overflow: auto;
    }
    .erl-content-tbl > tbody > tr {
        cursor: pointer;
    }
    .erl-content-tbl > tbody > tr.selected-row > td {
        background-color: #a8d3f3;
    }
</style>