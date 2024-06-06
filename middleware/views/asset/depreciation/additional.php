<div class="table-scrollable table-scrollable-borderless bp-header-param">
    <table class="table table-sm table-no-bordered bp-header-param">
        <tbody>
            <tr data-cell-path="description">
                <td class="text-right middle" style="width: 8%">
                    <label for="description" data-label-path="description">Гүйлгээний утга:</label>
                </td>
                <td class="middle" style="width: 55%">
                    <div style="width: 300px !important;">
                        <?php echo Form::textArea(array('name' => 'description', 'id' => 'description', 'class' => 'form-control form-control-sm descriptionInit', 'rows'=>3, 'value' => '')); ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>