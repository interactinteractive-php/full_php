
<?php 
echo Form::create(array(
    'name' => 'archivForm', 
    'id' => 'archivForm'));

    echo Form::textArea(array(
        'name'=>'description', 
        'id'=>'description', 
        'class'=>'form-control', 
        'placeholde' => 'Архив үүсгэх шалгааныг бичнэ үү', 
        'required'=>true));
    
echo Form::close();
?>