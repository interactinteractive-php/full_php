<form class="xs-form" id="confirmDescriptionForm">
    <?php
    echo Form::textArea(
        array(
            'id' => 'confirmDescription',
            'name' => 'confirmDescription',
            'class' => 'form-control',
            'style' => 'min-height: 100px;',
            'required' => true
            ));
    ?>
</form>