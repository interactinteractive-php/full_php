<?php
if ($this->fileList) {    
    
    $bookDropDown = '';
    foreach ($this->bookList as $book) {
        $bookDropDown .= '<option data-value="' . $book['id'] . '" value="' . $book['id'] . '">' . $book['booktypename'] . '</option>';
    } 
    
    $contentDropDown = '';
    foreach ($this->contentList as $content) {
        $contentDropDown .= '<option data-value="' . $content['id'] . '" value="' . $content['id'] . '">' . $content['name'] . '</option>';
    } 
                
    foreach ($this->fileList as $k => $file) {
?>
<tr data-filepath="<?php echo $file['PHYSICAL_PATH']; ?>" data-hdr-id="<?php echo $this->id; ?>">
    <td style="width: 10px;vertical-align: middle;"><?php echo ++$k; ?>.</td>
    <td style="width: 120px;vertical-align: middle;" class="">
        <strong><?php echo $file['FILE_NAME']; ?></strong>
        <input type="hidden" name="erlContentId[]" value="<?php echo $file['CONTENT_ID']; ?>">
        <input type="hidden" name="erlCompanyBookId[]" value="<?php echo $file['COMPANY_BOOK_ID']; ?>">
        <input type="hidden" name="erlSemanticId[]" value="<?php echo $file['SEMANTIC_ID']; ?>">
    </td>
    <td class="stretchInput text-center" style="width:90px">
        <input type="text" name="bookDate[]" class="form-control form-control-sm erl-bookdate" data-path="" required="required" value="<?php echo $file['BOOK_DATE']; ?>" data-value="<?php echo $file['BOOK_DATE']; ?>" placeholder="">
    </td>
    <td class="stretchInput text-center" style="width:230px">
        <select id="" name="bookTypeId[]" class="form-control form-control-sm dropdownInput select2" data-path="" data-field-name="" required="required" style="width: 250px">
            <option value="">- Сонгох -</option>
            <?php 
            echo str_replace('value="'.$file['BOOK_TYPE_ID'].'"', 'value="'.$file['BOOK_TYPE_ID'].'" selected', $bookDropDown);
            ?>                    
        </select>
    </td>
    <td class="stretchInput text-center" style="width:200px">
        <select id="" name="contentTypeId[]" class="form-control form-control-sm dropdownInput select2" data-path="" data-field-name="" required="required" style="width: 220px">
            <option value="">- Сонгох -</option>
            <?php 
            echo str_replace('value="'.$file['CONTENT_TYPE_ID'].'"', 'value="'.$file['CONTENT_TYPE_ID'].'" selected', $contentDropDown);
            ?> 
        </select>
    </td>
</tr>
<?php
    }
}
?>