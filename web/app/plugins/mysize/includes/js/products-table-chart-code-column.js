document.addEventListener('DOMContentLoaded', function () {
  var $ = jQuery;

  var items = $('div[data-chart-code-product-id]');
  $.fn.mysizeChartCodeTableEdit = function () {
    var self = this;
    var productId = this.attr('data-chart-code-product-id');
    var inputBlock = this.find('.input_chart_code');

    var open = function () {
      inputBlock.addClass('open');

      var input = inputBlock.find('input');
      input.focus();
      input.select();
    };

    var close = function () {
      inputBlock.removeClass('open');
    };

    this.find('span').on('click', open);
    inputBlock.find('button.cancel').on('click', close);
    inputBlock.find('button.save').on('click', function () {
      var input = inputBlock.find('input');
      var val = input.val();
      var data = {
        action: 'woocommerce_mysize_update_chart_code',
        product_id: productId,
        value: val
      };
      $.post(ajaxurl, data, function () {
        self.find('span').text(val);
        close();
      });
    });

    return this;
  };

  for (var i = 0; i < items.length; i++) {
    $(items[i]).mysizeChartCodeTableEdit();
  }
});