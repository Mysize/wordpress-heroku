<style>
    .wc_pbp_settings .form-table,
    .wc_pbp_settings h2,
    .wc_pbp_settings .submit {
        display: none;
    }
</style>
<iframe src="https://mysize-embed.s3.amazonaws.com/woo/settings.html" id="settingsIframe" frameborder="0"></iframe>
<script>
    (function() {        
        window.addEventListener("message", function(event) {            
            setMysizeSettings(event.data.retailerToken, event.data.integrationCode);
            jQuery(".wc_pbp_settings .submit input").click();
        })
        document.getElementById('settingsIframe').onload = function() {
            var token = jQuery('#wc_pbp_general_wc_pbp_retailer_token_textbox').val();
            var code = jQuery('#wc_pbp_general_wc_pbp_integration_code_textbox').val();
            this.contentWindow.postMessage({
                token: token,
                code: code
            }, "*");
        };
    })();

    function setMysizeSettings(token, code) {
        jQuery('#wc_pbp_general_wc_pbp_retailer_token_textbox').val(token);
        jQuery('#wc_pbp_general_wc_pbp_integration_code_textbox').val(code);
    }
</script>