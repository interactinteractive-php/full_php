<div class="row-fluid columns">
    <div class="col-md-2"> 
        <row>
            <div><h4><?php echo $this->root['META_DATA_NAME']; ?></h4></div>
        <input type="hidden" name="dataModelId" id="dataModelId" value="<?php echo $this->root['META_DATA_ID']; ?>">
        <div id="childs">
            <ul id="childList" class="tree">
                <?php
                $parents = array();
                
                foreach ($this->childs as $key => $value) {
                    
                    if ($value['PARENT_ID'] == null) {
                        
                        $childList = '';
                        $collapsible = '';
                        $isParent = (new Mdmetadata())->isParentMetaGroup($value['ID'], $this->root['META_DATA_ID']);
                        $haveChild = false;
                        
                        if ($isParent) {
                            
                            $haveChild = true;
                            $childList .= '<ul>';
                            $grandChild = (new Mdmetadata())->getMetaDataByParent($value['ID']);
                            
                            foreach ($grandChild as $k => $v) {
                                $isGrandParent = (new Mdmetadata())->isParentMetaGroup($v['ID'], $this->root['META_DATA_ID']);
                                if ($v['IS_SHOW'] == 1 || $isGrandParent) {
                                    $childList .= '<li>'
                                            . '<span class="tree_label"><div class="metaData tag-meta" data-metaData="' . strtolower($v['FIELD_PATH']) . '" draggable="true" tabindex="0">' . $v['META_DATA_NAME'] . '</div></span>'
                                            . ' </li>';
                                }
                            }
                            
                            $childList .= '</ul>';
                            $collapsible = '<input type="checkbox" class="toggle" id="' . strtolower($value['FIELD_PATH']) . '"/>';
                        }
                        
                        if ($value['IS_SHOW'] == 1 || $haveChild) {
                            ?>
                            <li>
                                <?php
                                echo $collapsible;
                                $li = '<div class="metaData tag-meta" data-metaData="' . strtolower($value['FIELD_PATH']) . '" draggable="true" tabindex="0">' . $value['META_DATA_NAME'] . '</div>';
                                if ($haveChild) {
                                    echo '<label for="' . strtolower($value['FIELD_PATH']) . '" class="tree_label">' . $li . '</label>';
                                } else {
                                    echo '<span class="tree_label">' . $li . '</span>';
                                }
                                echo $childList;
                                ?>
                            </li>   
                            <?php
                        }
                    }
                }
                ?>
            </ul>
        </div>
        </row>
    </div>
    <div class="col-md-8">  
        <div class="editor">
            <textarea name="tempEditor" id="tempEditor"><?php echo $this->htmlContent; ?></textarea>
        </div>
    </div>
    <div class="col-md-2">  
            <p class="consts-title">Тогтмолууд</p>
            <ul id="constants">
                <li><div class="const tag-const" data-constant="sysdatetime" draggable="true">sysdatetime</div></li> 
                <li><div class="const tag-const" data-constant="sysdate" draggable="true">sysdate</div></li> 
                <li><div class="const tag-const" data-constant="systime" draggable="true">systime</div></li> 
                <li><div class="const tag-const" data-constant="userSessionId" draggable="true">userSessionId</div></li>
            </ul>
            <hr>
            <p class="method-title">Функцууд</p>
            <ul id="functions">
                <li><div class="method tag-method" data-method="sum" draggable="true">sum</div></li> 
                <li><div class="method tag-method" data-method="avg" draggable="true">avg</div></li> 
                <li><div class="method tag-method" data-method="max" draggable="true">max</div></li> 
                <li><div class="method tag-method" data-method="min" draggable="true">min</div></li> 
                <li><div class="method tag-method" data-method="first" draggable="true">first</div></li> 
                <li><div class="method tag-method" data-method="last" draggable="true">last</div></li> 
                <li><div class="method tag-method" data-method="count" draggable="true">count</div></li> 
                <li><div class="method tag-method" data-method="numbering" draggable="true">numbering</div></li> 
            </ul>
    </div>
</div>

<style type="text/css">
#functions {
    list-style-type: none;
    margin: 0 !important;
    padding: 0;
}
#constants {
    list-style-type: none;
    margin: 0 !important;
    padding: 0;
}
#functions li {
    color : #5b5558;
    background: #FAFAFA;
    margin-bottom: 1px;
    height: 50px;
    font-weight: bold;
    line-height: 50px;
    cursor: pointer;
}
#constants li {
    color: #916c80;
    background: #FAFAFA;
    margin-bottom: 1px;
    height: 50px;
    font-weight: bold;
    line-height: 50px;
    cursor: pointer;
}
#functions li:nth-child(2n) {
    background: #F3F3F3;
}
#constants li:nth-child(2n) {
    background: #F3F3F3;
}
#functions li:hover {
    background: #FFFDE3;
    border-left: 5px solid #DCDAC1;
    margin-left: -5px;
}
#constants li:hover {
    background: #FFFDE3;
    border-left: 5px solid #DCDAC1;
    margin-left: -5px;
}
.method {
    padding: 0 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.const {
    padding: 0 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.method-title {
    font-weight: bold;
    text-align: center;
    color : #5b5558;
}
.consts-title {
    font-weight: bold;
    text-align: center;
    color: #916c80;
}
#editor .tag-meta {
    background: #FFFDE3;
    padding: 3px 6px;
    border-bottom: 1px dashed #ccc;
}
#editor {
    border: 1px solid #E7E7E7;
    padding: 0 20px;
    background: #fff;
    position: relative;
}
#editor .tag-meta .metaValue {
    font-style: normal;
}
.tree { margin: 1em;}

.tree input {
    position: absolute;
    clip: rect(0, 0, 0, 0);
}

.tree input ~ ul { display: none; }

.tree input:checked ~ ul { display: block; }

/* ————————————————————–
  Tree rows
*/
.tree li {
    line-height: 1.2;
    position: relative;
    padding: 0 0 1em 1em;
}

.tree ul li { padding: 1em 0 0 1em; }

.tree > li:last-child { padding-bottom: 0; }

/* ————————————————————–
  Tree labels
*/
.tree_label {
    position: relative;
    display: inline-block;
    background: #fff;
}

label.tree_label { cursor: pointer; }

label.tree_label:hover { color: #666; }

/* ————————————————————–
  Tree expanded icon
*/
label.tree_label:before {
    background: #000;
    color: #fff;
    position: relative;
    z-index: 1;
    float: left;
    margin: 0 1em 0 -2em;
    width: 1em;
    height: 1em;
    border-radius: 1em;
    content: '+';
    text-align: center;
    line-height: .9em;
}

:checked ~ label.tree_label:before { content: '–'; }

/* ————————————————————–
  Tree branches
*/
.tree li:before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: -.5em;
    display: block;
    width: 0;
    border-left: 1px solid #777;
    content: "";
}

.tree_label:after {
    position: absolute;
    top: 0;
    left: -1.5em;
    display: block;
    height: 0.5em;
    width: 1em;
    border-bottom: 1px solid #777;
    border-left: 1px solid #777;
    border-radius: 0 0 0 .3em;
    content: '';
}

label.tree_label:after { border-bottom: 0; }

:checked ~ label.tree_label:after {
    border-radius: 0 .3em 0 0;
    border-top: 1px solid #777;
    border-right: 1px solid #777;
    border-bottom: 0;
    border-left: 0;
    bottom: 0;
    top: 0.5em;
    height: auto;
}

.tree li:last-child:before {
    height: 1em;
    bottom: auto;
}

.tree > li:last-child:before { display: none; }

.tree_custom {
    display: block;
    background: #eee;
    padding: 1em;
    border-radius: 0.3em;
}

#childList{
    height: 650px;
    overflow-y: auto;
    overflow-x: auto;
}
</style>    
<script type="text/javascript" src="assets/custom/addon/plugins/ckeditor/4.5.4/ckeditor.js"></script>
<script type="text/javascript">
    var reportTemplateEditor = CKEDITOR.replace('tempEditor', {
        height: '600',
        width: '21cm',
        extraPlugins: 'hcard,layoutmanager,templates,stylesheetparser,pastefromword,tableresize',
        filebrowserBrowseUrl: '../browser/browse.php',
        filebrowserUploadUrl: '../uploader/upload.php'
    });
    CKEDITOR.config.contentsCss = URL_APP + 'assets/custom/addon/plugins/ckeditor/4.5.4/css/myEditorStyle.css';
    CKEDITOR.config.pasteFromWordRemoveStyles = false;
    CKEDITOR.config.pasteFromWordPromptCleanup = true;
    CKEDITOR.disableAutoInline = true;
    CKEDITOR.document.getById('childList').on('dragstart', function(evt) {
        var target = evt.data.getTarget().getAscendant('div', true);
        CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
        var dataTransfer = evt.data.dataTransfer;
        var fieldpath = $(evt.data.$.target).attr('data-metadata');
        dataTransfer.setData('metadata', fieldpath);
        dataTransfer.setData('text/html', target.getText());
    });
    CKEDITOR.document.getById('constants').on('dragstart', function(evt) {
        var target = evt.data.getTarget().getAscendant('div', true);
        CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
        var dataTransfer = evt.data.dataTransfer;
        var consts = $(evt.data.$.target).attr('data-constant');
        dataTransfer.setData('const', consts);
        dataTransfer.setData('text/html', target.getText());
    });
    CKEDITOR.document.getById('functions').on('dragstart', function(evt) {
        var target = evt.data.getTarget().getAscendant('div', true);
        CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
        var dataTransfer = evt.data.dataTransfer;
        var consts = $(evt.data.$.target).attr('data-method');
        dataTransfer.setData('method', consts);
        dataTransfer.setData('text/html', target.getText());
    });
    reportTemplateEditor.on('contentDom', function() {
        reportTemplateEditor.editable().attachListener(this.document, 'click', function(event) {
            if (event.data.$.target.closest("span") != null || event.data.$.target.closest("span") != undefined) {
                var clickedElement = $(event.data.$.target).prop("tagName");
                if (clickedElement !== 'SPAN') {
                    var el = event.data.$.target.closest("span");
                    var elementWithHandler = $(el).parent();
                    $(el).remove();
                    $(elementWithHandler).remove();
                }
            }
        });
    });
    $(document).on('focusin', function(e) {
        e.stopImmediatePropagation();
    });
</script>