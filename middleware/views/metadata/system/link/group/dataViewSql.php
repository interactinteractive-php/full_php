<div class="row">
    <div class="col-md-12 dataview-sql">
        <?php
        if ($this->dataViewSql['status'] == 'success') {
            echo Form::textArea(
                array(
                    'class' => 'form-control', 
                    'value' => SqlFormatter::format($this->dataViewSql['sql'], false), 
                    'spellcheck' => 'false',
                    'readonly' => 'readonly',
                    'style' => 'font-family:monospace,monospace;font-size:1em',
                    'rows' => 20
                )
            );
        } else {
            echo html_tag('div', array('class' => 'alert alert-danger'), $this->dataViewSql['message']);
        }
        ?>
    </div>
</div>