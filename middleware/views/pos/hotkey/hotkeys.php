<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-12">
        <div id="fz-hotkeys" class="freeze-overflow-xy-auto" style="border: 1px solid #ddd;">
        <table class="table table-sm table-bordered table-hover mb0" id="hotkeys-tbl">
            <thead>
                <tr>
                    <th class="text-center" style="vertical-align: middle; width: 25px">№</th>
                    <th class="text-center" style="vertical-align: middle; width: 28%">Үйлдэл</th>
                    <th class="text-center" style="width: 90px"><?php echo $this->lang->line('POS_0129'); ?></th>
                    <th class="text-center" style="width: 90px">Hotkey</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><?php echo $this->lang->line('POS_0143'); ?></td>
                    <td><?php echo $this->lang->line('POS_0144'); ?></td>
                    <td>F1</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><?php echo $this->lang->line('POS_0145'); ?></td>
                    <td><?php echo $this->lang->line('POS_0146'); ?></td>
                    <td>F2</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><?php echo $this->lang->line('POS_0147'); ?></td>
                    <td>Нэхэмжлэхийн жагсаалт дуудна.</td>
                    <td>F3</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Барааны жагсаалтыг дуудах</td>
                    <td>Бараа сонгож, хайж оруулах цонхыг гараас дуудах</td>
                    <td>F4</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><?php echo $this->lang->line('POS_0060'); ?></td>
                    <td>Төлбөр төлөх цонхыг дуудна. Хэрвээ төлбөр төлөх цонх нээгдсэн байвал баримт хэвлэх үйлдэл хийгдэнэ.</td>
                    <td>F5</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Талоны төрөл өөрчлөх</td>
                    <td>Хувь хүн, байгууллага гэсэн сонголтыг хооронд нь шилжүүлэх</td>
                    <td>F6</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><?php echo $this->lang->line('POS_0131'); ?></td>
                    <td>Идэвхитэй байгаа мөрний хямдралын хувь оруулах талбар дээр очно.</td>
                    <td>F7</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Мөнгөн дэвсгэрт</td>
                    <td>Төлбөр төлөлт дээр мөнгөн дэвсгэртийг тоолох цонхыг дуудна</td>
                    <td>F8</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Барааны тоог өөрчлөх</td>
                    <td>Барааны тоо хэмжээ оруулах талбарт курсор очно</td>
                    <td>F10</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Банк мөр нэмэх</td>
                    <td>Төлбөр төлөх цонхны банкны мөр нэмэх</td>
                    <td>F11</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Талон буцаах</td>
                    <td>Талоны жагсаалтаас сонгосон талоныг хучингүй болгох процессыг ажиллуулна</td>
                    <td>F12</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Мөр устгах</td>
                    <td>Идэвхитэй байгаа мөрийг устгана.</td>
                    <td>Delete</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Бараа устгах</td>
                    <td>Талон дээр бичсэн бүх барааг устгах</td>
                    <td>Shift+Delete</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Үүсгэсэн талоны жагсаалт</td>
                    <td>Талоны жагсаалтаас баримтын буцаалт болон бусад мэдээллийг харах боломжтой.</td>
                    <td>Shift+F3</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Барааг сагснаас гаргах</td>
                    <td>Түр хүлээлгэнд оруулсан талоны жагсаалтыг харуулах</td>
                    <td>Shift+F9</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Мөнгөн дэвсгэрт хэвлэх</td>
                    <td>Кассын тухайн өдрийн бэлэн мөнгөний дэвсгэртийг тоолж хэвлэнэ</td>
                    <td>Alt+T</td>
                </tr>
                <?php
                if (!Config::getFromCache('CONFIG_IS_CLOSE_ON_ESCAPE')) {
                ?>
                <tr>
                    <td>4</td>
                    <td>Гарах/Хаах</td>
                    <td>Нээгдсэн байгаа идэвхтэй цонхыг хаана</td>
                    <td>SHIFT+Esc</td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td>5</td>
                    <td>Терминал холбох</td>
                    <td></td>
                    <td>CTRL + X</td>
                </tr>                
                <tr>
                    <td>5</td>
                    <td>Холболт шалгах</td>
                    <td></td>
                    <td>CTRL + Q</td>
                </tr>                
                <tr>
                    <td>5</td>
                    <td>Холболт салгах</td>
                    <td></td>
                    <td>CTRL + V</td>
                </tr>                
                <tr>
                    <td>5</td>
                    <td>Банкны холболтын жагсаалт</td>
                    <td></td>
                    <td>CTRL + B</td>
                </tr>                
                <tr>
                    <td>5</td>
                    <td>Ээлжийн хаалт хийх</td>
                    <td></td>
                    <td>shift + F8</td>
                </tr>                
                <tr>
                    <td>5</td>
                    <td>Хүлээлгэнд оруулах</td>
                    <td></td>
                    <td>F9</td>
                </tr>                
            </tbody>
        </table>
        </div>     
    </div>    
</div>

<style type="text/css">
table#hotkeys-tbl > tbody > tr > td:first-child {
    text-align: center;
}
</style>

<script type="text/javascript">
disableScrolling();

$(function() {
    $('div#fz-hotkeys').css({"maxHeight": '450px'});
    var $hotKeyTable = $('table', 'div#fz-hotkeys');
    $hotKeyTable.tableHeadFixer({'head': true, 'z-index': 9}); 
    
    var $hotKeyRows = $hotKeyTable.find('tbody > tr:visible'), 
        $hotKeyRowsLen = $hotKeyRows.length, $hotKeyI = 0;
    for ($hotKeyI; $hotKeyI < $hotKeyRowsLen; $hotKeyI++) { 
        $($hotKeyRows[$hotKeyI]).find('td:first').text($hotKeyI + 1);
    }
});
</script>


