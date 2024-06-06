<div id="socialview-<?php echo $this->dataViewId; ?>" class="socialview">
  <div id="socialview-board" class="u-fancy-scrollbar js-no-higher-edits js-list-sortable">
    <?php
    $hasChild = false;
//    var_dump($this->recordList);
//    die;
    foreach ($this->recordList as $recordRow) {
//            $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
//                $rowJson    = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
        ?>
        <?php
        if (is_null($recordRow['parentid'])) {
            ?>
            <div class="media">
              <a href="javascript:;" class="float-left">
                <img alt="" src="<?php echo $recordRow['picture']; ?>" class="rounded-circle media-object">
              </a>
              <div class="media-body">
                <div class="timeline-body-arrow"></div>
                <h4 class="media-heading"><?php echo $recordRow['firstname']; ?> <span style="margin-left: 200px"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $recordRow['createddate']; ?></span></h4>
                <p>
                  <?php echo $recordRow['description']; ?>
                </p>
                <?php
                $hasChild = false;
                foreach ($this->recordList as $recordChild) {
                    if ($recordChild['parentid'] == $recordRow['id']) {
                        $hasChild = true;
                        ?>
                        <div class="replies">
                          <div class="media">
                            <a href="javascript:;" class="float-left">
                              <img alt="" src="<?php echo $recordChild['picture']; ?>" class="rounded-circle media-object">
                            </a>
                            <div class="media-body">
                              <div class="timeline-body-arrow"></div>
                              <h4 class="media-heading"><?php echo $recordChild['firstname']; ?> <span style="margin-left: 200px"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $recordChild['createddate']; ?></span></h4>
                              <p>
                                <?php echo $recordChild['description']; ?>
                              </p>
                            </div>
                          </div>
                        </div>
                        <hr>
                        <?php
                    }
                }
                ?>
                <div class="row">
                  <div class="col-md-12">
                    <textarea style="margin-left: 20px; margin-right: 20px; margin-top: 20px" class="form-control" id="" rows="6" placeholder="Коммент үлдээх"></textarea>
                  </div>
                </div>
                <div class="float-left">
                  <a href="javascript:;">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                  </a>
                  <a href="javascript:;" class="counts">
                    100
                  </a>
                  <a href="javascript:;">
                    <i class="fa fa-share-alt" aria-hidden="true"></i>
                  </a>
                  <a href="javascript:;" class="counts">
                    100
                  </a>
                  <a href="javascript:;">
                    <i class="fa fa-comments" aria-hidden="true"></i>
                  </a>
                  <a href="javascript:;" class="counts">
                    100
                  </a>
                </div>
                <div class="float-right">
                  <a href="javascript:;">
                    <i class="fa fa-reply" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
            <?php
        }
        else {
            ?>

            <?php
        }
        ?>
        <?php
        if (!$hasChild) {
            echo '<hr>';
        }
        ?>
    <?php } ?>
    <div class="row">
      <div class="col-md-12">
        <textarea style="margin-left: 20px; margin-right: 20px; margin-top: 20px" class="form-control" id="" rows="6" placeholder="Коммент үлдээх"></textarea>
      </div>
      <div class="col-md-12">
        <button class="btn">Зураг хавсаргах</button>
        <button class="btn">Хадгалах</button>
      </div>
    </div>
  </div>
</div>

<style>
  .socialview .fa {
    font-size: 1.5em;
  }
  .socialview .counts {
    font-size: 1.1em;
    padding-right: 20px;
  }
  .media-left, .media > .float-left {
    padding-top: 12px;
    padding-right: 20px;
  }
  .socialview .media img {
    height: 54px;
    position: relative;
    top: 3px;
    width: 54px;
  }
  .media, .media-body {
    overflow: visible;
  }
  .media-object {
    display: block;
  }
  .media{
    padding-left: 18px;
  }
  .socialview{
    background: #f5f6fa none repeat scroll 0 0;
    bottom: 0;
    content: "";
    display: block;
    margin-left: 10px;
    margin-right: 10px;
    /*position: absolute;*/
    top: 0;
    /*width: 4px;*/
  }
  .media-body {
    background-color: #fff;
    border-radius: 4px;
    margin-left: 110px;
    margin-top: 20px;
    padding: 20px;
    position: relative;
  }
  .replies .timeline-body-arrow {
    border-color: transparent #D8D1CF transparent transparent;
    border-style: solid;
    border-width: 14px 14px 14px 0;
    height: 0;
    left: -14px;
    position: absolute;
    top: 30px;
    width: 0;
  }
  .replies .media-body {
    background-color: #D8D1CF;
    border-radius: 4px;
    margin-left: 110px;
    margin-top: 20px;
    padding: 20px;
    position: relative;
  }
  .timeline-body-arrow {
    border-color: transparent #fff transparent transparent;
    border-style: solid;
    border-width: 14px 14px 14px 0;
    height: 0;
    left: -14px;
    position: absolute;
    top: 30px;
    width: 0;
  }
</style>