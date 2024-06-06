<div class="col-md-12" id="user">
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="caption font-weight-bold"><i class="fa fa-list"></i> <?php echo $this->title; ?></div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="tabbable-line">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#userTabSearch" class="nav-link active" data-toggle="tab">Шүүлтүүр</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="userTabSearch">
                                <form role="form" id="metavalue-search-form" method="post">
                                    <div class="form-body">
                                        <div class="form-group row fom-row">
                                            <?php
                                            echo Form::label(array('text' => 'Хэрэглэгчийн нэр'));
                                            echo Form::text(array('name' => 'user_name', 'data-name' => 'user_name', 'class' => 'form-control form-control-sm textInit', 'value' => ''));
                                            ?>
                                        </div>
                                        <div class="form-group row fom-row">
                                            <?php
                                            echo Form::label(array('text' => 'И-мэйл хаяг'));
                                            echo Form::text(array('name' => 'email', 'data-name' => 'email', 'class' => 'form-control form-control-sm textInit', 'value' => ''));
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <?php
                                        echo Form::button(array('class' => 'btn blue btn-sm searchMetaValue', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))) . " ";
                                        echo Form::button(array('class' => 'btn grey-cascade btn-sm resetMetaValue', 'value' => $this->lang->line('clear_btn')));
                                        ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>  
                </div>
                <div class="col-md-9" style="min-height: 591px;">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="btn-group">
                                    <?php
                                    echo Html::anchor(
                                            'javascript:;', '<i class="icon-plus3 font-size-12"></i> ' . $this->lang->line('add_btn'), array(
                                        'class' => 'btn green-meadow btn-sm',
                                        'onclick' => 'mdUserAdd(this);'
                                            ), $this->isAdd
                                    );
                                    echo Html::anchor(
                                            'javascript:;', '<i class="fa fa-edit"></i> ' . $this->lang->line('edit_btn'), array(
                                        'class' => 'btn blue btn-sm',
                                        'onclick' => 'mdUserAdd(this);'
                                            ), $this->isEdit
                                    );
                                    echo Html::anchor(
                                            'javascript:;', '<i class="fa fa-trash"></i> ' . $this->lang->line('delete_btn'), array(
                                        'class' => 'btn red btn-sm',
                                        'onclick' => 'mdUserAdd(this);'
                                            ), $this->isDelete
                                    );
                                    ?>  
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="btn-group">
                                    <?php
                                    echo Html::anchor(
                                            'javascript:;', '<i class="fa fa-file-excel-o"></i> ' . $this->lang->line('excel_btn'), array(
                                        'class' => 'btn btn-secondary btn-xs',
                                        'onclick' => 'exportToExcel()'
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="umUserDataGrid"></table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>    

<script type="text/javascript">
    $(function () {
        $('#umUserDataGrid').datagrid({
            url: 'mduser/umUserDataGrid',
            rownumbers: true,
            singleSelect: true,
            pagination: true,
            pageSize: 20,
            remoteFilter: true,
            filterDelay: 10000000000,
            fit: true,
            fitColumn: true,
            striped: true,
            nowrap: false,
            pagePosition: 'both',
            columns: [[
                    {field: 'USERNAME', title: 'Хэрэглэгчийн нэр', halign: 'center', sortable: true},
                    {field: 'EMAIL', title: 'И-мэйл хаяг', halign: 'center', sortable: false, width: 300},
                    {field: 'INACTIVE', title: 'Идэвхитэй эсэх', sortable: true, halign: 'center', align: 'center', width: 70, formatter: gridIsactiveField},
                    {field: 'CREATE_DATE', title: 'Үүсгэсэн огноо', sortable: true, halign: 'center', align: 'left', width: 150},
                    {field: 'CHANGE_DATE', title: 'Өөрчилсөн', sortable: true, halign: 'center', align: 'left', width: 150}
                ]],
            onLoadSuccess: function () {
                showGridMessage($('#umUserDataGrid'));
                $('#umUserDataGrid').datagrid('getPanel').children('div.datagrid-view')
                        .find(".datagrid-htable")
                        .find(".datagrid-filter-row")
                Core.initInputType();
            }
        });
    });

    function gridIsactiveField(val, row) {
        if (val !== "1") {
            return 'Үгүй';
        } else {
            return 'Тийм';
        }
    }
</script>
