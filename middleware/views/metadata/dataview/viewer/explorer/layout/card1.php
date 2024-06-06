<div id="board-card-<?php echo $this->dataViewId; ?>" class="board-card">
  <div id="board" class="u-fancy-scrollbar js-no-higher-edits js-list-sortable">
      <?php
      foreach ($this->recordList as $recordRow) {
          $groupName = $recordRow['row'][$this->groupName];
          $rows      = $recordRow['rows'];
          ?>
        <div class="js-list list-wrapper">
          <div class="list js-list-content">
            <div class="list-header js-list-header u-clearfix is-menu-shown">
              <textarea class="list-header-name mod-list-name js-list-name-input" spellcheck="false" dir="auto" maxlength="512"><?php echo $groupName; ?>  
              </textarea>
            </div>
            <div class="list-cards u-fancy-scrollbar u-clearfix js-list-cards js-sortable">
                <?php
                foreach ($rows as $recordRow) {
                    $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
                    $rowJson    = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
                    ?>
                  <div class="list-card js-member-droppable" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);">
                    <div class="list-card-cover js-card-cover">
                    </div>
                    <span class="icon-sm icon-edit list-card-operation dark-hover js-open-quick-card-editor js-card-menu">
                    </span>                    
                    <div class="list-card-details">
                      <div class="list-card-labels js-card-labels">
                        <span class="card-label card-label-blue mod-card-front" title="">&nbsp;
                        </span>
                      </div>
                      <a class="list-card-title js-card-name" dir="auto" href="javascript:;">
                          <?php echo $recordRow[$this->name1]; ?> 
                      </a>
                      <div class="badges">    
                          <?php if ($recordRow[$this->name2] != '') { ?>
                            <div title="" class="badge">
                              <span class="badge-icon icon-sm"><i class="fa fa-comment-o"></i></span>
                              <span class="badge-text"><?php echo $recordRow[$this->name2]; ?></span>
                            </div>
                        <?php } ?>
                        <?php if ($recordRow[$this->name3] != '') { ?>
                            <div title="" class="is-due-past badge">
                              <span class="badge-icon icon-sm"><i class="fa fa-clock-o"></i></span>
                              <span class="badge-text"><?php echo substr($recordRow[$this->name3], 0, 10); ?></span>
                            </div>                            
                        <?php } ?>
                      </div>
                      <div class="list-card-members js-list-card-members">
                        <div class="member js-member-on-card-menu" data-idmem="">
                          <img class="member-avatar" height="30" width="30" src="<?php echo $photoField; ?>" alt="" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);">                        
                        </div>
                      </div>
                    </div>
                  </div>    
              <?php } ?>
            </div>
            <a class="open-card-composer js-open-card-composer" href="#"></a>
          </div>
        </div>    
    <?php } ?>
  </div>
</div>


<script type="text/javascript">
    $(function(){
      $('#board-card-<?php echo $this->dataViewId; ?>').on('click', '.list-card', function(){
        var elem=this;
        var _this=$(elem);
        var _parent=_this.closest('.board-card');
        _parent.find('.selected-row').removeClass('selected-row');
        _this.addClass('selected-row');
      });

    $('#board-card-<?php echo $this->dataViewId; ?>').on('contextmenu', '.list-card', function(e){
        var elem=this;
        var _this=$(elem);
        var _parent=_this.closest('.board-card');
        _parent.find('.selected-row').removeClass('selected-row');
        _this.addClass('selected-row');
      });

    });
</script>
