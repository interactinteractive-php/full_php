<form name="googleMapParamForm" id="googleMapParamForm">
    <input type="hidden" name="googleMapLinkId" value="<?php echo $this->googleMapLinkId;?>">
    <div class="table-bordered">
        <table class="table googleMapParam mb0">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 250px;">DV name</th>
                    <th style="width: 200px;">DV параметр</th>
                    <th style="width: 300px;">Action meta параметр</th>
                    <th style="width: 50px;">#</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $this->googleMapParamHtml; ?>
            </tbody>
        </table>
    </div>
</form>