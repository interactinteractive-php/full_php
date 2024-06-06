<?php
if ($this->anketCopyrigthtext) {
?>
<footer class="mt-4" id="anket-footer">
    <div class="container">
        <div class="footer-text">
            <span class="copyright"><?php echo $this->anketCopyrigthtext; ?></span>
        </div>
    </div>
</footer>
<?php
}
?>
<script src="assets/custom/anket/js/script.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){
        anketJS.init();
    });
</script>
</body>
</html>