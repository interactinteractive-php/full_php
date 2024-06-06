<form id="kpiindicatorexcelimport-form" method="post" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="form-group row">
            <?php 
            echo Form::label(array(
                'text' => 'Sheet-ийн нэр',  
                'class' => 'col-form-label col-md-3 text-right pr0 pt2', 
                'required' => 'required'
            )); 
            ?>
            <div class="col-md-9">
                <?php 
                echo Form::text(array( 
                    'name' => 'sheetName', 
                    'class' => 'form-control form-control-sm stringInit', 
                    'required' => 'required'
                )); 
                ?> 
            </div>
        </div>
        <div class="form-group row">
            <?php 
            echo Form::label(array(
                'text' => 'Мөрийн дугаар',  
                'class' => 'col-form-label col-md-3 text-right pr0 pt2', 
                'required' => 'required'
            )); 
            ?>
            <div class="col-md-2">
                <?php 
                echo Form::text(array( 
                    'name' => 'rowNumber', 
                    'class' => 'form-control form-control-sm longInit', 
                    'required' => 'required'
                )); 
                ?> 
            </div>
        </div>
        <div class="form-group row">
            <?php 
            echo Form::label(array(
                'text' => 'Загвар',  
                'class' => 'col-form-label col-md-3 text-right pr0 pt2', 
                'required' => 'required'
            )); 
            ?>
            <div class="col-md-9">
                <?php 
                echo Form::select(array(
                    'class' => 'form-control form-control-sm select2', 
                    'name' => 'templateId', 
                    'data' => $this->templateList, 
                    'op_value' => 'ID', 
                    'op_text' => 'NAME', 
                    'required' => 'required'
                ));
                ?> 
            </div>
        </div>
        <div class="form-group row mb-2">
            <?php 
            echo Form::label(array(
                'text' => 'Файл /xls, xlsx/',  
                'class' => 'col-form-label col-md-3 text-right pr0 pt5', 
                'required' => 'required'
            )); 
            ?>
            <div class="col-md-9">
                <?php 
                echo Form::file(array(
                    'class' => 'form-control form-control-sm fileInit', 
                    'name' => 'excelFile', 
                    'required' => 'required', 
                    'data-valid-extension' => 'xls, xlsx', 
                    'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'
                ));
                ?> 
            </div>
        </div>
        
    </div>      
</form> 