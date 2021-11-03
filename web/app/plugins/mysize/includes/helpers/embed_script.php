<!-- ====== MYSIZE CODE START ===== -->
<div id="mySize"></div>
<script>
    (function() {
        var sWidget = document.createElement('script');        
        sWidget.src = "<?php echo $GLOBALS['url_widget']?>/v1/js/woocommerce.js?retailer_token=<?php echo $retailer_token ?>&integration_code=<?php echo $chart_code[0] ?>";
        document.body.appendChild(sWidget);        
    }());
</script>
<!-- ====== MYSIZE CODE END ===== -->