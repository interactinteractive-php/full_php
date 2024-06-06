<div class="panel panel-default bg-inverse grid-row-content">
    <table class="table sheetTable sidebar_detail">
        <tbody>
            <tr>
                <td style="width: 160px;" class="left-padding">
                    <label for="customerCode">Харилцагч:</label>
                </td>
                <td>
                    <input type="text" id="customerCode" class="form-control form-control-sm" value="<?php echo $this->customerCode; ?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td style="width: 160px;" class="left-padding">
                    <label for="usageYear">Cанхүүгийн ашиглах жил/норм:</label>
                </td>
                <td>
                    <input type="text" id="usageYear" class="form-control form-control-sm bigdecimalInit text-right" value="<?php echo $this->usageYear; ?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td style="width: 160px;" class="left-padding">
                    <label for="usageYear">Татварын ашиглах жил/норм:</label>
                </td>
                <td>
                    <input type="text" id="stusageYear" class="form-control form-control-sm bigdecimalInit text-right" value="<?php echo $this->stusageyear; ?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td style="width: 160px;" class="left-padding">
                    <label for="usageYear">Анхны санхүүгийн ашиглах жил/норм:</label>
                </td>
                <td>
                    <input type="text" id="originalUsageYear" class="form-control form-control-sm bigdecimalInit text-right" value="<?php echo $this->originalusageyear; ?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td style="width: 160px;" class="left-padding">
                    <label for="usageYear">Анхны татварын ашиглах жил/норм:</label>
                </td>
                <td>
                    <input type="text" id="originalStUsageYear" class="form-control form-control-sm bigdecimalInit text-right" value="<?php echo $this->originalstusageyear; ?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td style="width: 160px;" class="left-padding">
                    <label for="accountName">Зардлын данс:</label>
                </td>
                <td>
                    <input type="hidden" id="accountId" name="expenseAccountId[]" class="form-control form-control-sm stringInit" value="<?php echo $this->accountId; ?>" readonly="readonly">
                    <input type="text" id="accountName" name="accountName[]" class="form-control form-control-sm stringInit" value="<?php echo $this->accountName; ?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td style="width: 160px;" class="left-padding">
                    <label for="locName">Байршил:</label>
                </td>
                <td>
                    <input type="text" id="locName" name="locName[]" class="form-control form-control-sm stringInit" value="<?php echo $this->assetLocationName; ?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td style="width: 160px;" class="left-padding">
                    <label for="dtlassetEmployeeName">Эд хариуцагч:</label>
                </td>
                <td>
                    <input type="text" id="dtlassetEmployeeName" name="dtlassetEmployeeName[]" class="form-control form-control-sm stringInit" value="<?php echo $this->assetEmployeeName; ?>" readonly="readonly">
                </td>
            </tr>
            <tr>
                <td style="width: 160px;" class="left-padding">
                    <label for="dtlassetDeprMethodName">Элэгдэл тооцох арга:</label>
                </td>
                <td>
                    <input type="text" id="dtlassetDeprMethodName" name="dtlassetDeprMethodName[]" class="form-control form-control-sm stringInit" value="<?php echo $this->assetDeprMethodName; ?>" readonly="readonly">
                </td>
            </tr>
        </tbody>
    </table>
</div>