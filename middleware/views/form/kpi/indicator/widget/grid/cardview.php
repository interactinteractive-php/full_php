<style type="text/css">
.mv-cardview {
    display: inline-block;
    width: 250px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
    box-shadow: 0 1px 2px rgb(0 0 0 / 5%);
    margin-left: 10px;
    margin-right: 10px;
    margin-bottom: 20px;
}
.mv-cardview:hover {
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
}
.mv-cardview .card {
    margin-bottom: 0;
}
.mv-cardview .card-body .card-img {
    border-radius: 0;
    border-bottom: 1px #eee solid;
}
.mv-cardview h5 {
    display: block;
    padding: 0 10px;
    font-size: 14px;
    font-weight: bold;
    height: 50px;
    color: #222;
    text-transform: uppercase;
    line-height: 18px;
}
</style>

<?php
foreach ($this->response['rows'] as $row) {
?>
<a href="javascript:;" class="mv-cardview" onclick="mvCustomCardMoreView(this, '<?php echo $this->indicatorId; ?>', '<?php echo $row['ID']; ?>', '<?php echo $row['RELATED_INDICATOR_ID_DESC']; ?>');">
    <div class="card">
        <div class="card-body">
            <div class="card-img-actions mb-2">
                <img class="card-img img-fluid" src="<?php echo checkFileDefaultVal($row['PICTURE'], 'assets/custom/addon/img/noimage.png'); ?>">
            </div>
            <h5>
                <?php echo $row['RELATED_INDICATOR_ID_DESC']; ?>
            </h5>
        </div>
    </div>
</a>
<?php
}
?>

<script type="text/javascript">
function mvCustomCardMoreView(elem, indicatorId, rowId, title) {
    $.ajax({
        type: 'post',
        url: 'mdform/renderCustomMoreView',
        data: {indicatorId: indicatorId, rowId: rowId},
        success: function(content) {
            appMultiTabByContent({ metaDataId: indicatorId+'_'+rowId, title: title, type: 'newprocess', content: content });
        }
    });
}    
</script>