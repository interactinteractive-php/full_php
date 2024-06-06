<style>
    P.blocktext {
        margin-left: auto;
        margin-right: auto;
        width: 8em;
    }
</style>

<h2>
    <P class="blocktext">Амжилттай дуудагдлаа</P>
</h2>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let data = '<?php echo $this->response; ?>';
        localStorage.setItem(<?php echo $this->state; ?>, data);
        window.close();
    });
</script>