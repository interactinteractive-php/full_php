<?php 
if ($this->controlsData) {
    foreach ($this->controlsData as $row) { 
?>
    <div class="col-md-12 mt5">
        <?php echo Mdform::eaRenderControl($row, '', $this->uniqId); ?>
    </div>
<?php 
    }
} 
?>

<script type="text/javascript">
function dynamicKeyCard<?php echo $this->uniqId; ?>(id) {
    $('#dynamicKeyCard'+id).slideToggle();
}
</script>