<?php
if ($this->userKeys) {
?>
<input type="hidden" autofocus="true" />
<div class="mt10 mb0">
    <?php
    foreach ($this->userKeys as $row) {
        $id = Compression::gzdeflate($row['USER_ID'].'$'.$row['DEPARTMENT_NAME']);
        $id = Str::urlCharReplace($id);

        if (isset($row['OBJECT_PHOTO']) && file_exists($row['OBJECT_PHOTO'])) {
            $logo = '<img src="api/image_thumbnail?width=84&src='.$row['OBJECT_PHOTO'].'">';
        } else {
            $logo = 'No logo';
        }
    ?>
    <a href="mduser/changeKey/<?php echo $id; ?>" class="uk-link card">
        <div class="uk-tbl">
            <div class="uk-row">
                <div class="uk-logo-cell">
                    <?php echo $logo; ?>
                </div>
                <div class="uk-name-cell">
                    <?php echo $row['DEPARTMENT_NAME']; ?>
                </div>
                <div class="uk-code-cell">
                    <?php echo $row['DEPARTMENT_CODE']; ?>
                </div>
            </div>    
        </div>
    </a>
    <?php
    }
    ?>
</div>
<?php
}
?>

<style type="text/css">
.uk-link {
    display: flex;
    justify-content: center;
    transition: box-shadow .3s;
    background-color: #fff;
    border: 1px solid #ddd;
    margin-bottom: 8px;
    text-decoration: none;
    padding: 12px;
    min-height: 66px;
    border-color: var(--root-color02);
}
.uk-link:hover,
.uk-link:focus {
    background-color: var(--root-color01);
}
.uk-link > *,
.uk-link:active,
.uk-link:focus,
.uk-link:hover {
    text-decoration: none;
}
.uk-tbl {
    display: table;
    width: 100%;
    padding: 0;
    margin: 0;
    min-height: 30px;
}
.uk-tbl > .uk-row {
    display: table-row;
    padding: 0;
    margin: 0;
}
.uk-tbl > .uk-row > .uk-logo-cell {
    display: table-cell;
    text-align: left;
    vertical-align: middle;
    width: 100px;
    min-height: 100px;
    color: #999;
}
.uk-tbl > .uk-row > .uk-name-cell {
    display: table-cell;
    text-align: left;
    vertical-align: middle;
    text-transform: uppercase;
    font-weight: 600;
    line-height: 16px;
}
.uk-tbl > .uk-row > .uk-code-cell {
    display: table-cell;
    text-align: right;
    vertical-align: middle;
    font-weight: bold;
}
.uk-tbl > .uk-row > .uk-logo-cell > img {
    max-height: 40px;
    max-width: 84px;
}
</style>