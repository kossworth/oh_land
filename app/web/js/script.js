$(document).ready(function(){
    
    // функция форматирования чисел
    function number_format (number, decimals, decPoint, thousandsSep) 
    { 
        //  discuss at: http://locutus.io/php/number_format/
        // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        // improved by: Kevin van Zonneveld (http://kvz.io)
        // improved by: davook
        // improved by: Brett Zamir (http://brett-zamir.me)
        // improved by: Brett Zamir (http://brett-zamir.me)
        // improved by: Theriault (https://github.com/Theriault)
        // improved by: Kevin van Zonneveld (http://kvz.io)
        // bugfixed by: Michael White (http://getsprink.com)
        // bugfixed by: Benjamin Lupton
        // bugfixed by: Allan Jensen (http://www.winternet.no)
        // bugfixed by: Howard Yeend
        // bugfixed by: Diogo Resende
        // bugfixed by: Rival
        // bugfixed by: Brett Zamir (http://brett-zamir.me)
        //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        //  revised by: Luke Smith (http://lucassmith.name)
        //    input by: Kheang Hok Chin (http://www.distantia.ca/)
        //    input by: Jay Klehr
        //    input by: Amir Habibi (http://www.residence-mixte.com/)
        //    input by: Amirouche
        //   example 1: number_format(1234.56)
        //   returns 1: '1,235'
        //   example 2: number_format(1234.56, 2, ',', ' ')
        //   returns 2: '1 234,56'
        //   example 3: number_format(1234.5678, 2, '.', '')
        //   returns 3: '1234.57'
        //   example 4: number_format(67, 2, ',', '.')
        //   returns 4: '67,00'
        //   example 5: number_format(1000)
        //   returns 5: '1,000'
        //   example 6: number_format(67.311, 2)
        //   returns 6: '67.31'
        //   example 7: number_format(1000.55, 1)
        //   returns 7: '1,000.6'
        //   example 8: number_format(67000, 5, ',', '.')
        //   returns 8: '67.000,00000'
        //   example 9: number_format(0.9, 0)
        //   returns 9: '1'
        //  example 10: number_format('1.20', 2)
        //  returns 10: '1.20'
        //  example 11: number_format('1.20', 4)
        //  returns 11: '1.2000'
        //  example 12: number_format('1.2000', 3)
        //  returns 12: '1.200'
        //  example 13: number_format('1 000,50', 2, '.', ' ')
        //  returns 13: '100 050.00'
        //  example 14: number_format(1e-8, 8, '.', '')
        //  returns 14: '0.00000001'
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
        var n = !isFinite(+number) ? 0 : +number
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
        var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
        var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
        var s = ''
        var toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec)
          return '' + (Math.round(n * k) / k)
            .toFixed(prec)
        }
        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
        if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
        }
        if ((s[1] || '').length < prec) {
          s[1] = s[1] || ''
          s[1] += new Array(prec - s[1].length + 1).join('0')
        }
        return s.join(dec)
    }
    // сабмит формы калькулятора
    // $('#clt_form').submit(function(event){
    //     event.preventDefault();
    //     var data = $('#clt_form').serialize();
    //
    //     $.ajax({
    //         type: "post",
    //         url: '/content/perform-calculate',
    //         data: data,
    //         success: function(data) {
    //             $('main.c-page').html('');
    //             $('main.c-page').html(data);
    //         }
    //     });
    //
    //     return false;
    // });

    // создание заявки обратного звонка
//    $('#cb_form').submit(function(event){
//        event.preventDefault();
//        var data = $('#cb_form').serialize();
//
//        $.ajax({
//            type: "post",
//            url: '/callbacks/create-callback',
//            data: data,
//            success: function(data) {
//                response = JSON.parse(data);
//                if(response.status == true) {
//                    $.arcticmodal('close');
//                    $('#cb_form input').val('');
//                    $('#successCallback').arcticmodal();
//                } else {
//                    $.arcticmodal('close');
//                    $('#actionFailed').arcticmodal();
//                }
//
//            }
//        });
//
//        return false;
//    });

    // создание заявки обратной связи
//    $('#cbf_form').submit(function(event){
//        event.preventDefault();
//        var data = $('#cbf_form').serialize();
//
//        $.ajax({
//            type: "post",
//            url: '/callbacks/create-callback-request',
//            data: data,
//            success: function(data) {
//                response = JSON.parse(data);
//                if(response.status == true) {
//                    $.arcticmodal('close');
//                    $('#cbf_form input, #cbf_form textarea').val('');
//                    $('#successCallback').arcticmodal();
//                } else {
//                    $.arcticmodal('close');
//                    $('#actionFailed').arcticmodal();
//                }
//
//            }
//        });
//
//        return false;
//    });

    // создание заявки на консультацию
//    $('#cons_form').submit(function(event){
//        event.preventDefault();
//        var data = $('#cons_form').serialize();
//
//        $.ajax({
//            type: "post",
//            url: '/callbacks/create-cons-order',
//            data: data,
//            success: function(data) {
//                response = JSON.parse(data);
//                if(response.status == true) {
//                    $('#cons_form input, #cons_form textarea').val('');
//                    $.arcticmodal('close');
//                    $('#successCallback').arcticmodal();
//                } else {
//                    $.arcticmodal('close');
//                    $('#actionFailed').arcticmodal();
//                }
//            }
//        });
//
//        return false;
//    });

    // создание заявки покупки в 1 клик
//    $('#oco_form').submit(function(event){
//        event.preventDefault();
//        var data = $('#oco_form').serialize();
//
//        $.ajax({
//            type: "post",
//            url: '/callbacks/one-click-buy',
//            data: data,
//            success: function(data) {
//                response = JSON.parse(data);
//                if(response.status == true) {
//                    $('#oco_form input').val('');
//                    $.arcticmodal('close');
//                    $('#successCallback').arcticmodal();
//                } else {
//                    $.arcticmodal('close');
//                    $('#actionFailed').arcticmodal();
//                }
//            }
//        });
//
//        return false;
//    });

    // SEARCH
    // $('.b-search__form').submit(function(event){
    //     event.preventDefault();
    //     var data = $('.b-search__form').serialize();
    //
    //     $.ajax({
    //         type: "post",
    //         url: '/content/search',
    //         data: data,
    //         error: function(data) {
    //             $.arcticmodal('close');
    //             $('#actionFailed').arcticmodal();
    //         }
    //     });
    //
    //     return false;
    // });

    // создание комментария товара
//    $('#cmt_form').submit(function(event){
//        event.preventDefault();
//        var data = $('#cmt_form').serialize();
//
//        $.ajax({
//            type: "post",
//            url: '/feedbacks/create-product-feedback',
//            data: data,
//                success: function(data) {
//                    $('#cmt_form input, #cmt_form textarea').val('');
//                    $.arcticmodal('close');
//                    $('#successFeedback').arcticmodal();
//                },
//                error: function(){
//                    $.arcticmodal('close');
//                    $('#actionFailed').arcticmodal();
//                }
//        });
//
//        return false;
//    });

    // создание комментария компании
//    $('#tsm_form').submit(function(event){
//        event.preventDefault();
//        var formData = new FormData($('#tsm_form')[0]);
//
//        $.ajax({
//            type: "post",
//            url: '/feedbacks/create-company-feedback',
//            data: formData,
//            contentType: false,
//            processData: false,
//                success: function(data) {
//                    $.arcticmodal('close');
//                    $('#tsm_form input, #tsm_form textarea').val('');
//                    $('#successFeedback').arcticmodal();
//                },
//                error: function(){
//                    $.arcticmodal('close');
//                    $('#actionFailed').arcticmodal();
//                }
//        });
//
//        return false;
//    });

    // добавление товара в корзину
    $('#form_add_to_cart').submit(function(event){
        event.preventDefault();
        var data = $('#form_add_to_cart').serialize();
        $.ajax({
            type: "post",
            url: '/cart/request',
            data: data,
                success: function(data) {
                    $.arcticmodal('close');
                    $('#successAdded').arcticmodal();
                    $('#b-cart__qty').html(data.cart_count);
                },
                error: function(){
                    $.arcticmodal('close');
                    $('#actionFailed').arcticmodal();
                }
        });

        return false;
    });


    // действия в корзине. переключалки всякие))
    function changeOrderSum(obj) {
        // для изменения общей стоимости товара в заказе
        var current_count = $(obj).siblings('.js-qty-input').val();
        var current_price = $(obj).siblings('.js-qty-price').val();
        var curr_count = parseInt(current_count);
        var curr_price = parseInt(current_price);
        var curr_sum = curr_count * curr_price;
        curr_sum = parseInt(curr_sum);
        formatted_sum = number_format(curr_sum, 0, ' ', ' ');
        
        $(obj).siblings('.js-qty-input').val(curr_count);
        $(obj).parents('.b-order-item').find('span.item_sum').html(formatted_sum);
        $(obj).parents('.b-order-item').find('input.item_sum').val(curr_sum);

        // для изменения общей суммы заказа
        // ищем все инпуты с суммой товара. суммируем их и перезаписываем сумму заказа
        var sum_elements = $(document).find('input.item_sum');
        var order_sum = 0;
        var elements_count = sum_elements.length;
        for(var i = 0; i < elements_count; i++){
            var temp_value = parseInt($(sum_elements[i]).val());
            order_sum = order_sum + temp_value;
        }
        // форматируем для красивого вывода
        order_sum = parseInt(order_sum);
        var formatted_order_sum = number_format(order_sum, 0, ' ', ' ');
        
        $('input.order_sum').val(order_sum);
        $('span.order_sum').html(formatted_order_sum);
    }

    $(document).on('click', '.js-qty-plus', function(){
        changeOrderSum(this);
    });

    $(document).on('click', '.js-qty-minus', function(){
        changeOrderSum(this);
    });

    // сохранение заказа
//    $('#usr_form').submit(function(event){
//        event.preventDefault();
//        var data = $('#order_items_info').serialize() + '&' +$('#usr_form').serialize();
//
//        $.ajax({
//            type: "post",
//            url: '/cart/order',
//            data: data,
//            success: function(data) {
//                response = JSON.parse(data);
//                if(response.status == true) {
//                    window.location.href = response.url;
//                } else {
//                    $.arcticmodal('close');
//                    $('#actionFailed').arcticmodal();
//                }
//            },
//            error: function(){
//                $.arcticmodal('close');
//                $('#actionFailed').arcticmodal();
//            }
//        });
//
//        return false;
//    });

    // удаление товара из заказа
    $(document).on('click', '.icon-close-circle', function(event){
        var obj = $(this);
        var product_id = obj.data('product');
        $.post('/cart/request', {product_id : product_id, method: 'remove'})
            .done(function (data) {
                obj.parents('.b-order-item').hide();
            });
    });


});