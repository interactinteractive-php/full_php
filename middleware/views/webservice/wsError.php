<div class="alert alert-danger">
    <strong>Error!</strong> <?php echo $this->errorMsg; ?>.
</div>

<?php
if (isset($this->resultMsg)) {
?>
Validation Error
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th style="width: 30%">Талбар</th>
            <th>Алдаа</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($this->resultMsg as $k=>$v) {
        ?>
        <tr>
            <td><?php echo $k; ?></td>
            <td>
                <?php 
                if (is_array($v)) {
                    print_array($v);
                } else {
                    echo $v;
                }
                ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php
}
?>