<div id="lock-category-treeview" class="tree-demo">
    <?php echo $this->lockCategoryTreeView; ?>
</div>

<script type="text/javascript">
var $lockTreeview = $('#lock-category-treeview');

$(function(){ 
    
    $lockTreeview.css('max-height', ($(window).height() - $lockTreeview.offset().top - 20) + 'px');
    
    $lockTreeview.jstree({
        "core": {
            "themes": {
                "responsive": true
            }
        },
        "types": {
            "default": {
                "icon": "icon-folder2 text-orange-300"
            }
        },
        "plugins": ["types", "cookies"]
    });
    /*$lockTreeview.jstree('open_all');*/
});
</script>    