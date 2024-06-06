<table class="table table-hover table-bordered mb0">
    <tbody>
<?php 
foreach ($this->rows as $row) {
    
    $clickFunction = $this->clickFunction;
    
    foreach ($row as $key => $val) {
        $clickFunction = str_replace('{'.$key.'}', $val, $clickFunction);
    }
?>
    <tr>
        <td>
            <a href="javascript:;" data-row="<?php echo $row['rowData']; ?>" onclick="<?php echo $clickFunction; ?>">
                <?php echo $row['title']; ?>
            </a>
        </td>
    </tr>    
<?php    
}
?>
    </tbody>
</table>