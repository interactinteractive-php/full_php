<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="row">
    <div class="content-name-choosen col-md-4 col-sm-4 col-xs-12"></div>

    <div class="col-md-8 col-sm-8 col-xs-12" id="pagingContentHtmlList"></div>
</div>

<div class="row search-area">
    <div class="col-md-3">
        <input type="text" class="form-control form-control-sm" id="contentNameSearch" value="<?php echo isset($this->contentName) ? $this->contentName : ''; ?>" placeholder="Хайх утгаа оруулаад ENTER дарна уу..."/>
    </div>
</div>

<?php
if (!is_null($this->contentHtmlList['rows'])) {
    foreach ($this->contentHtmlList['rows'] as $key => $contentHtml) {
        ?>
        <div class="col-md-3">
            <div class="card card--small contentHtml" data-content-id="<?php echo $contentHtml['CONTENT_ID'] ?>" data-content-name="<?php echo $contentHtml['FILE_NAME'] ?>">
                <div style="background-image: url(assets/core/global/img/veritech-erp.png)" class="card__image"></div>
                <h2 class="card__title" title="<?php echo $contentHtml['FILE_NAME'] ?>"><?php echo $contentHtml['FILE_NAME'] ?></h2>
                <div class="card__action-bar">
                    <a href="javascript:;" class="card__button"><?php echo $contentHtml['PERSON_NAME'] ?></a>
                    <a href="javascript:;" class="card__button"><?php echo $contentHtml['CREATED_DATE'] ?></a>
                </div>
            </div>
        </div>
        <?php
    }
}
?>

<script type="text/javascript">
    $(function(){
        /* global contentHtmlList */
<?php if (!is_null($this->contentHtmlList['total'])) { ?>
            contentHtmlList.init(<?php echo $this->contentHtmlList['total'] ?>);
<?php } ?>
    });
</script>