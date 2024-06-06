<div class="card light shadow">
    <div class="card-body">
        <div id="hdr-meta-folder-view" class="tree-demo">
        </div>
    </div>
</div>

<script type="text/javascript">
var folderSystemTreeView = $('#hdr-meta-folder-view');
$(function(){
    var folderHeight = $(window).height() - 220;
    $("#hdr-meta-folder-view").css({"height": folderHeight+"px"});
    
    folderSystemTreeView.jstree({
        "core" : {
            "themes" : {
                "responsive": true
            }, 
            "check_callback" : true,
            "data" : {
                "url" : function(node) {
                    return 'mdmetadata/childFolderSystem';
                },
                "data" : function(node) {
                    return { 'parent' : node.id };
                }
            }
        },
        "types" : {
            "default" : {
                "icon" : "icon-folder2 text-orange-300"
            }
        },
        "plugins": ["types", "cookies"]
    }).bind("select_node.jstree", function (e, data){
        var folderId = data.node.id;
        childRecordView(folderId.toString(), 'folder', '');
    });
});
</script>  