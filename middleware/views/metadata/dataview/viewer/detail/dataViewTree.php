<div id="commonSelectableDynamicProccessWindowTreeView" class="tree-demo">
    <?php echo $this->folderTreeView; ?>                         
</div>
<style type="text/css">
.jstree {
    overflow-x: auto; 
    overflow-y: hidden;
    padding-bottom: 10px;
}    
</style>

<script type="text/javascript">
var commonSelectableDynamicProccessWindowTreeView = $('#commonSelectableDynamicProccessWindowTreeView');
$(function(){
    Core.initInputType();

    commonSelectableDynamicProccessWindowTreeView.jstree({
        "core" : {
            "themes" : {
                "responsive": true
            }   
        },
        "types" : {
            "default" : {
                "icon" : "icon-folder2 text-orange-300"
            }
        },
        "plugins": ["types", "cookies"]
    }).on('ready.jstree', function (e, data) {
        $("#commonSelectableDynamicProccessWindowTreeView > ul > li > a").click();
    }); 
    commonSelectableDynamicProccessWindowTreeView.jstree('open_all');
});
</script>