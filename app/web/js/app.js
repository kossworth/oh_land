// pickadate plugin defaults
jQuery.extend( jQuery.fn.pickadate.defaults, {
	monthsFull: [ 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря' ],
	monthsShort: [ 'янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек' ],
	weekdaysFull: [ 'воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота' ],
	weekdaysShort: [ 'вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб' ],
	today: 'сегодня',
	clear: 'удалить',
	close: 'закрыть',
	firstDay: 1,
	// format: 'd mmmm yyyy г.',
	format: 'dd.mm.yyyy',
	formatSubmit: 'dd.mm.yyyy'
});

$(document).ready(function(){
// Global variables
	var  $containerAjax = $(".js-ajax_calculator")
		// ,$bCrumbs = $(".b-crumbs")
		;

	// Functions for the calc-choose-buy block AJAX loads and js-functional inits on them
	var hideContainerAjax = function($containerAjax){
		// $containerAjax.fadeOut({
		// 	duration: 400,
		// 	queue: "ajax"
		// 	}
		// );
                $("#toTop").trigger("click");	// прокручуємо сторінку нагору
		$containerAjax.animate({
				opacity: 0,
			},{
				duration: 400,
				queue: "ajax",
				// complete: function(){
				// 	$containerAjax.dequeue("ajax");
				// }
			}
		);
	};
	var showContainerAjax = function($containerAjax){
		// $containerAjax.fadeIn({
		// 	duration: 400,
		// 	queue: "ajax"
		// 	}
		// );
		$containerAjax.animate({
				opacity: 1,
			},{
				duration: 400,
				queue: "ajax"
			}
		);
	};
	// var hideBcrumbs = function($bCrumbs){
	// 	$bCrumbs.fadeOut({	
	// 		duration: 400,
	// 		queue: "ajax"
	// 		}
	// 	).dequeue("ajax");
	// };
	// var showBcrumbs = function($bCrumbs){
	// 	$bCrumbs.fadeIn({	
	// 		duration: 400,
	// 		queue: "ajax"
	// 		}
	// 	).dequeue("ajax");
	// };



	// OSAGO propositions
	var showPropositions = function($containerAjax){	// ф-я для підвантаження пропозицій
            hideContainerAjax($containerAjax);

            var type = $("#vehicleForm input[name='type']:checked").val()
            var notTaxi = $("#vehicleForm [name='notTaxi']").is(':checked') ? 1 : 0;
            var franshiza = $("#vehicleForm [name='franshiza']").val();
            var city = $("#vehicleForm #cityId").val();
            var cityName = $("#vehicleForm #regCity").val();
            var zone = $("#vehicleForm #zoneId").val();

            $containerAjax.queue("ajax", function(){
                $.ajax({
                    type: "get",
                    url : "/ohproject/osago/send-request",
                    data: { type : type, notTaxi : notTaxi, 
                        franshiza : franshiza, city : city, cityName : cityName, zone: zone },
                    error : function(){
                        alert('error');
                    },
                    success: function(response){
                        $containerAjax.html(response);
                        propositionsInit($containerAjax);
                    },
                    complete: function(){
                        showContainerAjax($containerAjax);
                        $containerAjax.dequeue("ajax");
                    }
                });
            });

            $containerAjax.dequeue("ajax");
	};
	var propositionsInit = function($containerAjax){	// ф-я ініціалізації js-функціоналу на підвантаженому блоці пропозицій
            var $btnsReadMore = $(".js-btn_readmore")
                    ;

            // hide-show details  by click on "Подробнее"
                    // show:
            $btnsReadMore.click(function(){	// show-hide details text on "Подробнее" click
                // $(this).hide();
                var  $toggleList = $(this).siblings(".js-content_readmore")
                    // ,$listItems = $toggleList().children()
                    ,$toggleListItems = $toggleList.children().filter("li:nth-child(3)~li")
                    ,$proposition = $toggleList.parents(".b-proposition")
                    ;

                if ($toggleList.hasClass("js-opened")){
                    $toggleListItems.slideUp(200, function(){
                        $proposition.css("z-index","0");
                        $toggleList.removeClass("js-opened");
                    });
                } else {
                    $proposition.css("z-index","1");
                    $toggleListItems.slideDown(200, function(){
                        $toggleList.addClass("js-opened");
                    });
                }
            });
			// hide:
		// $(".b-proposition").mouseleave(function(){
			// var $hiddenContent = $(this).find(".js-content_readmore");
			// $hiddenContent.slideUp(
				// function(){
					// $hiddenContent.next(".js-btn_readmore").slideDown();
				// }
			// );
		// })

		// hide-show additional propositions by click on "Посмотреть еще предложения"
		var  $propositionsCalc = $containerAjax.find(".b-calculator_propos")
                    ,$proposStrings = $propositionsCalc.find(".b-propositions__string")
                    ,$hiddenProposStrings = $proposStrings.filter(".b-propositions__string_hidden")
                    ,$moreProposBtn = $propositionsCalc.find("#morePropositions")
                    ;
		$moreProposBtn.click(function(){
                    if (!$hiddenProposStrings.is(":animated")){
                        $hiddenProposStrings.slideToggle();
                        $moreProposBtn.find(".fa").toggleClass("fa-angle-down").toggleClass("fa-angle-up");
                    }
		});
		
		//	Повертаємось до вибора тз при кліку на "Изменить данные"
		$("#vehicleEdit").click(function(){showVehicleCalc($containerAjax)});

		// підванатажимо блок оформлення при кліку на "Купить"
		var  $buyBtns = $("#propositions").find(".b-proposition__buy")	// кнопки купівлі
			; 
		$buyBtns.click(function(){
                    var proposNum = $(this).attr("data-proposition");	// номер пропозиції для підвантаження потрібної пропозиції

                    // place for Ajax sending
                    //send proposNum
                    showOrderBlock(proposNum, $containerAjax);
		});
		
		//propositions slider
		// var $sliderPropos = $(".b-propositions .b-propositions__string").slick({
		// 	arrows: false,
		// 	infinite: false,
		// 	speed: 400,
		// 	slidesToShow: 4,
		// 	slidesToScroll: 4,
		// 	// autoplay: true,
		// 	// autoplaySpeed: 4400
		// });

		//	morePropositions btn functionality
			// var  $sliderProposMoreBtn = $sliderPropos.parents(".b-calculator_propos").find("#morePropositions");
			// $sliderProposMoreBtn.click(function(event){
			// 	event.preventDefault();
			// 	$sliderPropos.slick("slickNext");
			// });



		// $("#propositions .b-proposition__buy").click(function(){
		// 	var propositonId = $(this).attr("data-proposition");	// id пропозиції яку треба передати
		// 	//$(this).load("./ajax/__calcVehicle.html", function(){vehicleCalcInit($containerAjax)});
		// });
	};
	
	// order block
	var showOrderBlock = function(proposNum, $containerAjax){
            hideContainerAjax($containerAjax);

            $containerAjax.queue("ajax", function(){
                    // place for Ajax sending
            $.ajax({
            	type: "post",
                url : "/ohproject/osago/create-osago-order",
                data: { counter : proposNum },
            	error : function(){
            	    alert('error');
            	},
            	success: function(response){
            	    $containerAjax.html(response);
            	    orderBlockInit($containerAjax);
            	},
            	complete: function(){
                    showContainerAjax($containerAjax);
                    $containerAjax.dequeue("ajax");
            	}
            });
                // $(this).dequeue("ajax");
                // hideBcrumbs($bCrumbs);	// show breadcrumbs
            });

            // showContainerAjax($containerAjax);

            $containerAjax.dequeue("ajax");
	}

	// валідація форм
	var commonValidateRules = {	//валідація полів форм - об'єкт глобальних правил
            errorPlacement: function(error, element) {
                element.attr('title', error.text());	// запишемо текст помилки в title атрибут поля
            }
        };
	var orderFormsValidation = function($method){	//$method - блок з формою (самостійно, по телефону, відвантаживши документи)
        // для полів з автокомплітом: валідація при втраті фокусу
        var	$autoCompleteFields = $method.find(".js-autocomplete");
        $autoCompleteFields.blur(function(){	// втрата фокуса поля автокомпліта
            // var  dataItem = $(this).attr("data-item")
            // 	,fieldValue = $(this).val()
            // 	;
            // if (dataItem && (fieldValue != dataItem)){
            // 	$(this).val(dataItem);
            // }
            if ($(this).next().val()){	// додамо позначку помилки валідації якщо не вибрали значення автокомпліта
                    $(this).parent(".b-form__cell").removeClass("b-cell_error").addClass("b-cell_valid")
            } else{
                    $(this).parent(".b-form__cell").removeClass("b-cell_valid").addClass("b-cell_error")
            }
        });
		
        var finalizeValidateRules = {	// валідація полів форм - об'єкт локальних правил
            highlight: function (element, errorClass, validClass) {
                $(element).addClass(errorClass).removeClass(validClass);
                $(element.form).find("label[for=" + element.id + "]").parent('.b-form__cell').addClass('b-cell_' + errorClass).removeClass('b-cell_' + validClass);
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass(errorClass).addClass(validClass);
                $(element.form).find("label[for=" + element.id + "]").parent('.b-form__cell').removeClass('b-cell_' + errorClass).addClass('b-cell_' + validClass);
            },
			ignore: ".js-ignoreValidate",
        	rules: {
        		lastName: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                    pattern: /[A-Za-zА-Яа-яЁёІіЇї\-\s]+/
                },
        		firstName:{
                    required: true,
                    minlength: 2,
                    maxlength: 75,
                    pattern: /[A-Za-zА-Яа-яЁёІіЇї\-\s]+/
        		},
        		email:    {
                    required: true,
                    minlength: 5,
                    maxlength: 50,
                    email: true,
                    pattern: /^(\S+)@([a-z0-9-]+)(\.)([a-z]{2,4})(\.?)([a-z]{0,4})+$/
        		},
        		inn:      {
                    required: true,
                    minlength: 1,
                    maxlength: 10,
                    pattern: /[0-9]+/
                },
        		phone:    {
        			required: true,
                    pattern: /^\+38[0-9]{10}$/
        		},
        		address:  {
        			required: true,
                    minlength: 2,
                    maxlength: 255,
                    pattern: /[A-Za-zА-Яа-яЁёІіЇї\-\s\,\.\/0-9]+/
        		},
        		deliveryAddr:  {
        			required: true,
                    minlength: 2,
                    maxlength: 255,
                    pattern: /[A-Za-zА-Яа-яЁёІіЇї\-\s\,\.\/0-9]+/
        		},
        		year:     {
        			required: true,
        			number: true,
        			min: 1960,
        			max: 2018
                    //pattern: /[0-9]{4}/
        		},
        		chassis:  {
        			required: true,
                    minlength: 2,
                    maxlength: 17,
                    pattern: /[A-Za-zА-Яа-яЁёІіЇї\-0-9]+/
        		},
        		plateNum: {
        			required: true,
                    minlength: 2,
                    maxlength: 10,
                    pattern: /[A-Za-zА-Яа-яЁёІіЇї\-0-9]+/
        		},
        		date: {
        			required: true,
                    pattern: /[0-9\.]+/
        		},
        		regionNP:{
        			required: true
        		}
        	},
        	messages: {
                    lastName: {
                        required: "Поле обязательно для заполнения!",
                        minlength: "не менее 2х символов",
                        maxlength: "не более 100 символов",
                        pattern: "латинница, кириллица, пробел, дефис"
                    },
                    firstName:{
                        required: "Поле обязательно для заполнения!",
                        minlength: "не менее 2х символов",
                        maxlength: "не более 75 символов",
                        pattern: "латинница, кириллица, пробел, дефис"
                    },
                    email:    {
                        required: "Поле обязательно для заполнения!",
                        email: "Введите валидный email-адресс"
                    },
                    inn:      {
                        required: "Поле обязательно для заполнения!",
                        minlength: "не менее одной цифры ",
                        maxlength: "не более 10ти цифр",
                        pattern: "только цифры"
                    },
                    phone:    {
                        required: "Поле обязательно для заполнения!",
                        minlength: "введите до конца номер",
                        pattern: "введите номер украинского оператора связи"
                    },
                    address:  {
                        required: "Поле обязательно для заполнения!",
                        minlength: "не менее 2х символов",
                        maxlength: "не более 255 символов",
                        pattern: "ваш почтовый адресс"
                    },
                    deliveryAddr:  {
                        required: "Поле обязательно для заполнения!",
                        minlength: "не менее 2х символов",
                        maxlength: "не более 255 символов",
                        pattern: "ваш почтовый адресс"
                    },
                    year:     {
                        required: "Поле обязательно для заполнения!",
                        number: "1999 например",
                        min: "не ранее 1960 года выпуска",
                        max: "не позднее 2018 года выпуска",
                    //pattern: "1999 например"
                    },
        		chassis:  {
        			required: "Поле обязательно для заполнения!",
                    minlength: "не менее 2х символов",
                    maxlength: "не более 17 символов",
                    pattern: "латинница, кириллица, дефис, цифры"
        		},
        		plateNum: {
                    required: "Поле обязательно для заполнения!",
                    minlength: "не менее 2х символов",
                    maxlength: "не более 10ти символов",
                    pattern: "латинница, кириллица, дефис, цифры, без пробелов"
        		},
        		date: {
                    required: "Поле обязательно для заполнения!",
                    pattern: "формат: ДД.ММ.ГГГГ"
        		},
        		regionNP:{
        			required: "Выберите область"
        		}
        	},
			submitHandler: function(form) {	// replaces default form submit behavior
                            // треба також перевірити поля автокомпліта
                            var  bFocused = false
                                ,bValid = true
                                ;

                            $autoCompleteFields.each(function(){
                                if(!$(this).prop("disabled")){
                                    if ($(this).next().val()){
                                            $(this).parent(".b-form__cell").addClass("b-cell_valid")
                                    } else{
                                        $(this).parent(".b-form__cell").addClass("b-cell_error");
                                        bValid = false;
                                        if (!bFocused){
                                            $(this).focus();
                                            bFocused = true;
                                        }
                                    }
                                }
                            });
                            if (bValid){
                                hideContainerAjax($containerAjax);
                                $containerAjax.queue("ajax", function(){
                                if(form.id == 'formByUpload'){
                                    var formData = new FormData($('#formByUpload')[0]);
                                    $.ajax({
                                        url: "/ohproject/osago/osago-doc-order",  
                                        type: 'POST',
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        cache: false,
                                        success: function(response) {
                                            $containerAjax.html(response);
                                        },
                                        error: function(){
                                            console.log('ERROR at PHP side!!');
                                        },
                                        complete: function(){
                                            showContainerAjax($containerAjax);
                                            $containerAjax.dequeue("ajax");
                                        }
                                        //Options to tell jQuery not to process data or worry about content-type.

                                    });
                                }
                                else
                                {
                                    $.ajax({
                                        url: form.action,
                                        type: form.method,
                                        data: $(form).serialize(),
                                        success: function(response){
                                            $containerAjax.html(response);
                                        },
                                        complete: function(){
                                            showContainerAjax($containerAjax);
                                            $containerAjax.dequeue("ajax");
                                        }           
                                    });
                                }
                                });
                                $containerAjax.dequeue("ajax");
					//Show thanks function end ------
                            }
		    }
        };
        
        return $method.find(".b-form_finalize").validate($.extend(commonValidateRules, finalizeValidateRules));
	};

	// ініціалізація блока оформлення замовлень
	var orderBlockInit = function($containerAjax){		
		var  $orderBlock = $("#finalize")
                    ,$methodBtns = $orderBlock.find(".b-finalize__btn_method")
                    ,$methods = $orderBlock.find(".b-finalize__method")
                    ,$activeMethod = $methods.filter(".js-method_active")	// метод "Заповнити самостійно"
                    ,$trash = $orderBlock.find(".b-method__trash")	// блок хлопець-дівчина
                    ,$trashTabs = $trash.find(".b-trash__tab")
                    ,$trashCard = $trash.find(".b-trash__sides")
                    ,$brand = $("input[name = 'brand']")
                    ,$model = $("input[name = 'model']")
                    ,$filesWrap = $orderBlock.find(".b-wrap_files")
                    ,$filesInput = $filesWrap.find("input[type='file']")
                    ,$filesList = $filesWrap.find(".b-list_files")
                    ,$filesProgress = $filesWrap.find(".b-progress_files")
                    ,$submitButtons = $methods.find("#submitBySelf, #submitByPhone, #submitByUpload")
                    ,validatorCurrent = orderFormsValidation($methods.filter("#bySelf"))	// formBySelf validation init
                    // змінні для логіки відображення доставки
                    ,$deliveryMode = $methods.find("select[name='deliveryMode']")	// селекти методів доставки
                    ,deliveryStr = "bySelf"	//самовывоз
                    ,regionId = "bySelf"	//самовывоз
                    // наступних елементів буде по 2 об'єкти - один в формі методу "Заповнити самостійно", другий в "Отправить документы"
                    ,$selfMap = $methods.find(".b-form__cell_map")
                    ,$courierCity = $methods.find("input[name='delivCityId']").prev()
                    ,$courierAddr = $methods.find("input[name='deliveryAddr']")
                    ,$newPostRegion = $methods.find("input[name='delivRegionIdNP']").prev()
                    ,$newPostCity = $methods.find("input[name='delivCityIdNP']").prev()
                    ,$newPostDivision = $methods.find("input[name='delivDivisionIdNP']").prev()
                    ,$newPostRow = $newPostDivision.parents(".row_form")
                    ;
		
		// show only selected buy method
		$methodBtns.click(function(){
                    var $activeBtn = $methodBtns.filter(".b-finalize__btn_active")
                        ,methodNum = $methodBtns.index($(this))
                        ;
                        // $activeMethod = $methods.filter(".js-method_active");

                    if($activeBtn[0] != $(this)[0]){
                        $activeBtn.removeClass("b-finalize__btn_active");
                        $(this).addClass("b-finalize__btn_active");
                        $activeMethod.fadeOut(400, function(){
                            $activeMethod.removeClass("js-method_active");
                            $activeMethod = $methods.eq(methodNum).addClass("js-method_active");
                            $activeMethod.fadeIn(400);	// show active method
                            // validate current method form
                            validatorCurrent = orderFormsValidation($activeMethod);	// hidden method could not be initialized before
                        });
                    }
		});
		// перемикання блоку "парень-девушка"
		$trashTabs.click(function(){
                    if (!$trashCard.is(":animated")){
                        if (!$(this).hasClass("b-trash__tab_active")){
                            $trashTabs.removeClass("b-trash__tab_active");
                            $(this).addClass("b-trash__tab_active");
                            $trashCard.toggleClass("b-sides_flip")
                        }
                    }
		});

		// повісимо маски на поля:
			// номерів телефону
		$methods.find("input[type='tel']").mask("?+380999999999");
			// року випуску авто
		$methods.find("input[name='year']").mask("?9999");
			// дати початку дії поліса
		$methods.find("input[name='date']").mask("?99.99.9999");

		// pickadate initialization
		var pickadayOptions = {
                    min: +1,
                    onClose: function () {
                        $("#bySelf").find(".picker__holder").blur();	// заборонимо спливати календарю при згортанні-розгортанні вікна браузера (коли фокус на даті)
                    }
		}
		$orderBlock.find("#bySelf").find("#date").pickadate(pickadayOptions);

		//	file input logic
		$filesInput.change(function (){
                    var  fileNames = []
                        ,filesListStr=""
                        ;
                    if (this.files.length > 10){	// обмеження за к-тю файлів
                        this.files.length = 10;
                    }
                    for (var i = 0; i < this.files.length; ++i) {
                        //fileNames.push(this.files[i].name);	// populate file names array
                        filesListStr += '<span class="b-filename">' + this.files[i].name + '<span class="fa fa-check" aria-hidden="true"></span></span>'
                    };

                    $filesProgress.animate({
                        width: "" + this.files.length*10 + "%"
                    },400);
                    $filesList.html(filesListStr);
		});

	// autocomplete для полів:
//		fieldAutocomplete($model, "./ajax/model.json", $brand.next());
                
                fieldAutocomplete(2, $("input[name = 'brandId']").prev(), "/ohproject/vehicles/ewa-brand", null, function(){
                    $model.prop("disabled", false);
		});
                fieldAutocomplete(2, $("input[name = 'modelId']").prev(), "/ohproject/vehicles/ewa-model", $brand.next(), function(){});
// - Delivery fields -------------------------
	// delivery selects stylization
		// delivery method select
		$deliveryMode.selectric({	// стилізуємо селекти вибора доставки
                    onChange: function(element) {	// element==this - це наш select, він лишається тим самим об'єктом і після ініціалізації selectric
                        deliveryStr = $(element).val();	// current select value				
                        var indexOfThis = $deliveryMode.index($(element));	// index of current $(element) between $deliveryMode selects

                        // при зміні значення клікнутого селекта змінимо значення решти селектів доставки в інших методах з доставкою
                        for (var i=0; i < $deliveryMode.length; ++i){
                            if (i != indexOfThis){
                                $deliveryMode.eq(i).val(deliveryStr).selectric("refresh");	// змінюємо значення і оновлюємо selectric
                            }
                        }

                        switch (deliveryStr) {
                            case "bySelf":	// самовивоз
                                // елементи Кур'єра
                                $courierCity.prop("disabled", true).addClass("hidden")
                                                        .parent().addClass("hidden");
                                $courierAddr.prop("disabled", true).addClass("hidden")
                                                        .parent().addClass("hidden");
                                // елементи НП						
                                $newPostRegion.prop("disabled", true).addClass("hidden");
                                $newPostCity.prop("disabled", true).addClass("hidden");
                                $newPostDivision.prop("disabled", true).addClass("hidden");
                                $newPostRow.addClass("hidden");
                                // елементи Самовивоза
                                $selfMap.removeClass("hidden");
                                break;
                            case "byCourier":	// кур'єр
                                // елементи Самовивоза
                                $selfMap.addClass("hidden");
                                // елементи НП						
                                $newPostRegion.prop("disabled", true).addClass("hidden");
                                $newPostCity.prop("disabled", true).addClass("hidden");
                                $newPostDivision.prop("disabled", true).addClass("hidden");
                                $newPostRow.addClass("hidden");
                                // елементи Кур'єра
                                $courierCity.prop("disabled", false).removeClass("hidden")
                                                        .parent().removeClass("hidden");
                                $courierAddr.prop("disabled", false).removeClass("hidden")
                                                        .parent().removeClass("hidden");
                                break;
                            case "byNP":	// НП
                                // елементи Кур'єра
                                $courierCity.prop("disabled", true).addClass("hidden")
                                                        .parent().addClass("hidden");
                                $courierAddr.prop("disabled", true).addClass("hidden")
                                                        .parent().addClass("hidden");
                                // елементи Самовивоза
                                $selfMap.addClass("hidden");
                                // елементи НП						
                                $newPostRow.removeClass("hidden");
                                $newPostRegion.prop("disabled", false).removeClass("hidden");
                                // Якщо є введені значення в полях міста чи відділення, то при тимчасовій зміні вибору способа доставки 
                                // при поверненні значення зберігаються в цих полях, але вони disabled, виправимо це
                                if ($newPostCity.next().val()){
                                        $newPostCity.prop("disabled", false);
                                }
                                $newPostCity.removeClass("hidden");
                                if ($newPostDivision.next().val()){
                                        $newPostDivision.prop("disabled", false);
                                }
                                $newPostDivision.removeClass("hidden");	// покажемо рядок опцій НП
                                break;
                        }
                        $(element).change();	// fired by default
                    }
		});
		$newPostRegion.selectric({
                    onInit: function() {
                        $(this).parents(".selectric-wrapper").find(".selectric-items li.disabled").remove();	//прибираємо з меню неактивний пункт (placeholder)
                        $(this).each(function(){
                            $(this).prop("disabled", true)
                        })
                    },
                    onChange: function(element) {	// element==this - це наш select, він лишається тим самим об'єктом і після ініціалізації selectric
                        regionId = $(element).val();	// current select value				
                        var indexOfThis = $deliveryMode.index($(element));	// index of current $(element) between $newPostRegion selects

                        // при зміні значення клікнутого селекта змінимо значення решти селектів доставки в інших методах з доставкою
                        for (var i=0; i < $deliveryMode.length; ++i){
                            if (i != indexOfThis){
                                $newPostRegion.eq(i).val(regionId).selectric("refresh");	// змінюємо значення і оновлюємо selectric
                                $newPostRegion.eq(i).parents(".selectric-wrapper").find(".selectric-items li.disabled").remove();	//прибираємо з меню неактивний пункт (placeholder)
                            }
                        }
                        $(this).parents(".b-form__cell").removeClass("b-cell_error");	// фікс незникаючої помилки валідації поля
                        //треба показати поле міста, видалити значення з нього і прихованого поля
                        $newPostCity.each(function(index){
                            $(this).val("");
                            $(this).next().val("");
                            $(this).prop("disabled", false);
                        });
                        $newPostDivision.each(function(index){
                            $(this).val("");
                            $(this).next().val("");
                            $(this).prop("disabled", true)
                        });
                        //треба сховати поле відділення, видалити значення з нього і прихованого поля

                        $(element).change();	// fired by default
                    }
		});
		

	// delivery selects stylization end
	
	//delivery autocompletes...
		// місто доставки (із областю для кур’єра) delivRegionIdNP
		fieldAutocomplete(2, $courierCity, "/ohproject/cities/np-city");

		// НП:
                // область
		// fieldAutocomplete($newPostRegion, "./ajax/region.json", null, function(){
		// 	$newPostCity.each(function(index){
		// 		$(this).prop("disabled", false)
		// 	});
		// });
                // місто
		fieldAutocomplete(2, $newPostCity, "/ohproject/cities/np-city",  $newPostRegion, function(){
                    $newPostDivision.each(function(index){
                        $(this).val("");
                        $(this).next().val("");
                        $(this).prop("disabled", false)
                    });
		});
                // відділення
		fieldAutocomplete(1, $newPostDivision, "/ohproject/cities/np-filial", $newPostCity.next());

//- Delivery fields END -------------------------
	
		$("#formBySelf, #formByUpload").submit(function(event){
                    event.preventDefault();
                    $(".js-autocomplete").each(function(){
                        if (!$(this).prop("disabled")){
                            $(this).focus().blur();
                        }
                    })
		});
		// $submitButtons.click(function(event){
		// 		event.preventDefault();
		// 	// if (validatorCurrent.numberOfInvalids() == 0){
		// 	// 	showThanks($containerAjax);
		// 	// }
		// });
	}
	
	// show thanks page
//	var showThanks = function($containerAjax, responseFromPhp){
//		hideContainerAjax($containerAjax);
//
//		// -- temporary Ajax for static demo --
//		// !!! comment it on server
//		$containerAjax.queue("ajax", function(){
//			// place for Ajax sending
//			$.ajax({
//            	type: "get",
//            	url : "./ajax/__thanks.html",
//            	error : function(){
//            	    alert('error');
//            	},
//            	success: function(response){
//            	    $containerAjax.html(response);
//            	    // thanksInit($containerAjax);
//            	},
//            	complete: function(){
//            		showContainerAjax($containerAjax);
//            		$containerAjax.dequeue("ajax");
//            	}
//            });
//		});
//		//-----------------------------------
//
//		// !!! 
//		// commented variant for php server
//		// $containerAjax.queue("ajax", function(){
//  //           $containerAjax.html(responseFromPhp);
//  //           showContainerAjax($containerAjax);
//  //           $containerAjax.dequeue("ajax");
//		// });
//
//		$containerAjax.dequeue("ajax");
//	}

	// vehicle calculator
	var showVehicleCalc = function($containerAjax){
            hideContainerAjax($containerAjax);

            $containerAjax.queue("ajax", function(){
                // place for Ajax sending
                $.ajax({
                    type: "get",
                    url : "/ohproject/osago/osago-change-data",
                    error : function(){
                        alert('error');
                    },
                    success: function(response){
                        $containerAjax.html(response);
                        vehicleCalcInit($containerAjax);
                    },
                    complete: function(){
                        showContainerAjax($containerAjax);
                        $containerAjax.dequeue("ajax");
                    }
                });
            });
            // $containerAjax.queue("ajax", function(){
            // 	// place for Ajax sending
            // 	$(this).load("./ajax/__calcVehicle.html", function(){vehicleCalcInit($containerAjax)});	// підвантажуємо пропозиції та ініціалізуємо на них js-функціонал

            // 	$(this).dequeue("ajax");
            // 	// hideBcrumbs($bCrumbs);	// show breadcrumbs
            // });

            // showContainerAjax($containerAjax);

            $containerAjax.dequeue("ajax");
	};
	var vehicleCalcInit = function($containerAjax){
		// selects stylization
		$(".js-selectric").selectric();
		// vehicles labels select
		// $(".b-vehicle").click(function(){
			// $(".b-vehicle").removeClass("b-vehicle_active");
			// $(this).addClass("b-vehicle_active");
			// // $("#" + $(this).attr("data-id")).trigger("click");	//перемикання радіобатонів об’єму при кліку на тз
		// });
		var  $vehicles = $(".b-vehicle")	//	блоки тз з картинками
			,$vehiclesBlock = $(".b-vehicles")	// $vehicles container
			,$paramBlocks = $(".b-params")	// відповідні блоки з радіобатонами до кожного тз
			;
		$vehiclesBlock.mouseleave(function(){	// виділимо вибраний блок коли курсор ззовні
			$vehicles.filter(".js-vehicle_active").addClass("b-vehicle_active");
		});
		$vehicles.hover(function(){	// при наведенні курсора на певний тз виділятимемо лише його, а активний буде невидимим
			$vehicles.removeClass("b-vehicle_active");
			$(this).addClass("b-vehicle_active");
		}, function(){
			$(this).removeClass("b-vehicle_active");
		});
		$vehicles.click(function(){
			$vehicles.removeClass("js-vehicle_active");
			$(this).addClass("js-vehicle_active");
			var index = $vehicles.index($(this))	// індекс типу тз (від 0 до 3)
                            ,$paramBlockActive = $paramBlocks.filter(".js-params_active")
                            ;
			if ($paramBlockActive != $(this)){
                            $paramBlockActive.removeClass("js-params_active");
                            $paramBlocks.eq(index).addClass("js-params_active");
                            $paramBlocks.eq(index).find("input").eq(1).prop("checked", true); // вибиратимемо 2й радіобатн вибраного тз
                            $paramBlockActive.fadeOut(0, function(){
                                $paramBlocks.eq(index).fadeIn(0);
                            })
			}
			// $("#" + $(this).attr("data-id")).trigger("click");	//перемикання радіобатонів об’єму при кліку на тз
		});

		//ajax registration city autocomplete
		fieldAutocomplete(3, $("#regCity"), "/ohproject/cities/ewa-city")
		
		// валідація
		var  $vehicleForm = $("#vehicleForm")
                    ,$cityId = $vehicleForm.find("#cityId")
                    ,$cityName = $vehicleForm.find("#regCity")
                    ;
		$vehicleForm.submit(function(event){
                    event.preventDefault();
                    if (!$cityId.val()){	//якщо не вибране місто реєстрації (відповідне приховане поле без значення)
                        $cityName.focus();
                    } else {
                        showPropositions($containerAjax);	// load of propositions
                    }
		});
	};

	// autocomplete function 
	// (enter string, shows items, select item -> send item id)
	// note: under autocompleted field must be placed hidden input with name attr, to return item id
	var fieldAutocomplete = function(iMinChars, $objToComplete, jsonAddr, $dataIdtoSend, callbackFn){
		var  oJS		//відповідний JSоб'єкт до JSON об'єкту AJAX відповіді
                    ,items = []		// масив елементів
                    ,propertiesLength	// зберігаємо тут к-ть властивостей item-а
                    ,bLength3
  		    ,itemIds = []	// масив id елементів
		    ,itemOtherIds = []	// масив додаткових id елементів
		    ,criteria = null
		    ,itemIndex
		    ,currentId
		    ,currentOtherId
                    ;

		$objToComplete.each(function(index){
                    var t = this;
                    $(t).autoComplete({
                        minChars: iMinChars,
                        source: function(term, response){
                            if ($dataIdtoSend instanceof jQuery){
                                criteria = $dataIdtoSend.val();
                            }
                        $.ajax({
                            type: "get",
                            data: {item: term, criteria: criteria},
                            url : jsonAddr,
                            error : function(){
                                alert('error');
                            },
                            success: function(data){
                                items = []; // масив елементів
                                itemIds = [];	// масив id елементів
                                oJS = data;	//відповідний JSоб'єкт до JSON об'єкту AJAX відповіді
                                propertiesLength = 0;
                                var i;
                                for (i in oJS.items[0]) {
                                    if (oJS.items[0].hasOwnProperty(i)) {
                                        ++propertiesLength;
                                    }
                                }
                                bLength3 = (propertiesLength == 3); // маємо додатковий Id треба створитиїх масив itemOtherIds

                                if (oJS.length != 0){	// перевіряємо чи не відсутні співпадіння (чи відповідь не пустий масив)
                                    for (var i = 0; i < oJS.items.length; ++i) {	// наповнимо масив елементів і їхніх id
                                        items.push(oJS.items[i].name);
                                        itemIds.push(oJS.items[i].id);
                                        if (bLength3) {itemOtherIds.push(oJS.items[i].zone_id)};
                                    };
                                    response(items);
                                }
                            }
                        });
                        },
			    onSelect: function (event, term, item) {
			    	itemIndex = items.indexOf(term);	// індекс елемента в масиві
			    	currentId = itemIds[itemIndex];		// id обраного елемента
		    		if (bLength3){currentOtherId = itemOtherIds[itemIndex]}	// зберігаємо обраний zone_id (якщо такі є)
			    	$objToComplete.each(function(){	// заповнимо решту однакових полів однаковими значеннями
                                    if($(this) != $(t)){	// значення в полі на якому ми вибрали значення автокомпліта
                                        $(this).val(term);
                                    };
                                    $(this).attr("data-item", term);	// додаємо атрибут в якому зберігатиметься вибраний item
                                    $(this).next().val(currentId);	// повертаємо id елемента прихованому елементу форми
                                    if (bLength3){$(this).next().next().val(currentOtherId)}	// присвоюємо zone_id 2му прихованому полю
			    	});

                                    $objToComplete.parent(".b-form__cell").removeClass("b-cell_error").addClass("b-cell_valid");	// позначаємо валідним поле

                                    if (callbackFn){	// перевіряємо чи існує колбек ф-я в параметрах, щоб уникнути помилки
                                        callbackFn();
                                    }
			    }
			});
		});
		$objToComplete.blur(function(){	// при втраті фокуса, якщо ми змінили значення, але не обрали з меню автокомпліта, то повернемо раніше обране значення полю
                    var  dataItem = $(this).attr("data-item")	// раніше обране значення, збережене в атрибуті "data-item"
                        ,fieldValue = $(this).val()	// поточне значення
                        ;
                    if (dataItem && (fieldValue != dataItem)){	// перевірка чи є попередньо обране значення (якщо)
                        $(this).val(dataItem);	// як є то запишемо вибране раніше значення
                    }
		})
		// objToComplete.focus(function(){
		// 	var e = jQuery.Event( "keydown", { keyCode: 128 } );
		// 	$(this).trigger(e);
		// })
	}
				

//	vehicle calculator js initialization
	vehicleCalcInit($containerAjax);	//
	
// reasons slider
	var $sliderReasons = $(".b-reasons__slider .b-slider__string").slick({
		arrows: false,
		infinite: true,
		speed: 400,
		slidesToShow: 3,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 4400
	});
	// add my buttons to ads slider
	var  $sliderReasonsPrevBtn = $sliderReasons.parents(".b-reasons__slider").find(".b-slider__control_left")
		,$sliderReasonsNextBtn = $sliderReasons.parents(".b-reasons__slider").find(".b-slider__control_right")
		;
	$sliderReasonsPrevBtn.click(function(event){
		event.preventDefault();
		$sliderReasons.slick("slickPrev");
	});	
	$sliderReasonsNextBtn.click(function(event){
		event.preventDefault();
		$sliderReasons.slick("slickNext");
	});

// clientsRating/mtsb switch
	var  $ratingBtns = $(".b-section_rating .b-rating__btn")
		,$ratingClients = $(".b-rating__wrapper_clients")
		,$ratingMtsbu = $(".b-rating__wrapper_mtsbu")
		;
	$ratingBtns.click(function(){
		if (!$(this).hasClass("b-rating__btn_active")){
			$ratingBtns.removeClass("b-rating__btn_active");
			$(this).addClass("b-rating__btn_active")
			if($(this).hasClass("b-rating__btn_mtsbu")){
				$ratingClients.fadeOut(600);
				$ratingMtsbu.fadeIn(600);
			} else if($(this).hasClass("b-rating__btn_clients")){
				$ratingMtsbu.fadeOut(600);
				$ratingClients.fadeIn(600);
			}
		}
	});

//	scroll to calculator
	$(".b-anchor_calculator").click(function(event){
		event.preventDefault();
		var  $anchor = $($(this).attr("href"))
			,offsetAnchor = $anchor.offset().top
			;
		$('html, body').animate({ scrollTop: offsetAnchor}, 600);
		return false;
	});

// client rating slider
	var $sliderRating = $(".b-rating__slider .b-slider__string_rating").slick({
		arrows: false,
		infinite: true,
		speed: 400,
		slidesToShow: 1,
		slidesToScroll: 1,
		// autoplay: true,
		// autoplaySpeed: 4400
	});
	// add my buttons to ads slider
	var  $sliderRatingPrevBtn = $sliderRating.parents(".b-rating__slider").find(".b-slider__control_left")
		,$sliderRatingNextBtn = $sliderRating.parents(".b-rating__slider").find(".b-slider__control_right")
		;
	$sliderRatingPrevBtn.click(function(event){
		event.preventDefault();
		$sliderRating.slick("slickPrev");
	});	
	$sliderRatingNextBtn.click(function(event){
		event.preventDefault();
		$sliderRating.slick("slickNext");
	});

// faq accordion
	var  $faq = $(".b-questAnsw")
		,$faqHeading = $(".b-questAnsw h5")
		;

		$(".b-questAnsw:nth-child(n+2)").find("p").hide();
		$faqHeading.click(function(){
			if ($(this).parent(".b-questAnsw").hasClass("b-questAnsw_unrolled")){
				$(this).next("p").slideUp(400).parent(".b-questAnsw").removeClass("b-questAnsw_unrolled");
			} else {
				$faq.find(".b-questAnsw_unrolled").next("p").slideUp(400).parent(".b-questAnsw").removeClass("b-questAnsw_unrolled");
				$(this).next("p").slideDown(400).parent(".b-questAnsw").addClass("b-questAnsw_unrolled");
			}
		})

// responses slider
	var  $sliderResp = $(".b-slider_responses")
		,$slideResp = $(".b-slide_responses")
		// ,$slideWrapRespFirst = $(".b-slide__wrap_first")
		// ,$slideWrapRespLast = $(".b-slide__wrap_last")
		,$respFedbBtn = $(".b-section_responses .b-responses__block_btns .b-rating__btn_responses")
		,$responsesBtn = $respFedbBtn.filter(".js-btn_responses")
		,$feedbackBtn = $respFedbBtn.filter(".js-btn_feedback")
		,$sliderRespControlBtn = $sliderResp.find(".b-slider__control_responses")
		,$sliderRespPrevBtn = $sliderResp.find(".b-slider__controls_responses .b-slider__control_left")
		,$sliderRespNextBtn = $sliderResp.find(".b-slider__controls_responses .b-slider__control_right")
		,$activeSlide = $slideResp.filter(".b-slide_active")
		,$unactiveSlide = $slideResp.filter(":not(.b-slide_active)"); 
		;

	$unactiveSlide.css("top", "0px");
	$unactiveSlide.css("left", "0px");
	$activeSlide.css("top", "30px");
	$activeSlide.css("left", "404px");
	var  moveLeft = function($slide, bNext){
			$slide.animate({
					top: 20,
					left: 444
				},	{
					duration: 200,
					easing: "linear",
					queue: "active",	// черга для анімації цього слайда
					done: function(){
						$(this).css("z-index", "-1")	// приберемо на задній фон
					}
				}
			);
			$slide.animate({
					top: 5,
					left: 400
				},	{
					duration: 200,
					easing: "linear",
					queue: "active"	// черга для анімації цього слайда
				}
			);
			$slide.animate({
					top: 2,
					left: 201
				},	{
					duration: 300,
					easing: "linear",
					queue: "active",	// черга для анімації цього слайда
					done: function(){
						// завантажимо новий контент слайда
						var $slideContent = $(this).find(".b-slide__side_front .b-slide__wrap");
						$slideContent.fadeOut(100);
						if(bNext){
							$slideContent.load("./ajax/__nextSlide.html")
						} else{
							$slideContent.load("./ajax/__prevSlide.html")
						}
						$slideContent.fadeIn(100);
					}
				}
			);
			$slide.animate({
					top: 0,
					left: 0
				},	{
					duration: 300,
					easing: "linear",
					queue: "active",
					done: function(){
						$slideResp.toggleClass("b-slide_active");
					}
				}
			);
			$slide.dequeue("active");	// запустимо чергу
		}
		,moveRight = function($slide){
			$slide.animate({
					top: 40,
					left: -44
				},	{
					duration: 200,
					easing: "linear",
					queue: "unactive",	// черга для анімації цього слайда
					done: function(){
						$(this).css("z-index", "4")
					}
				}
			);
			$slide.animate({
					top: 60,
					left: 4
				},	{
					duration: 200,
					easing: "linear",
					queue: "unactive"	// черга для анімації цього слайда
				}
			);
			$slide.animate({
					top: 30,
					left: 404
				},	{
					duration: 600,
					easing: "linear",
					queue: "unactive"
				}
			);
			$slide.dequeue("unactive");	// запустимо чергу
		}
		,reshuffle = function($active, $unActive, bNext){
			moveLeft($active, bNext);
			moveRight($unActive);
		}

	$responsesBtn.click(function(){
		$respFedbBtn.removeClass("b-rating__btn_active");
		$responsesBtn.addClass("b-rating__btn_active");
		$activeSlide.removeClass("b-slide_rotated")
	});
	$feedbackBtn.click(function(){
		$respFedbBtn.toggleClass("b-rating__btn_active");
		$activeSlide = $slideResp.filter(".b-slide_active");
		if (!$activeSlide.is(":animated")) {
			$activeSlide.toggleClass("b-slide_rotated");
		}
	});

	$sliderRespControlBtn.click(function(){
		$activeSlide = $slideResp.filter(".b-slide_active");
		if ($activeSlide.hasClass("b-slide_rotated")){
			$feedbackBtn.trigger("click");
		}
	});
	$sliderRespPrevBtn.click(function(){
		$activeSlide = $slideResp.filter(".b-slide_active");
		$unactiveSlide = $slideResp.filter(":not(.b-slide_active)");
		if(!$activeSlide.is(":animated")){
			reshuffle($activeSlide, $unactiveSlide, false);
		}
	});
	$sliderRespNextBtn.click(function(){
		$activeSlide = $slideResp.filter(".b-slide_active");
		$unactiveSlide = $slideResp.filter(":not(.b-slide_active)");

		// $activeSlideWrap.addClass("moveLeft")
		// $unactiveSlideWrap.addClass("moveRight")
		// activeSlideWrap.addClass("moveLeft");
		// $slideResp.toggleClass("b-slide_active");
		// reshuffle($activeSlide, );
		// moveLeft($activeSlide);
		// moveRight($unactiveSlide);
		if(!$activeSlide.is(":animated")){
			reshuffle($activeSlide, $unactiveSlide, true);
		}
	});
	//	Додаємо маску на номер телефона в формах
	$("input[type='tel']").mask("+38 (099) 999-99-99");

	// feedback form submission
	$(".js-form_feedback").submit(function(event){
            event.preventDefault();
            var data = $(this).serialize();

            $.ajax({
                type : 'post',
                url: '/ohproject/feedbacks/create-feedback',
                data : data,
                cache : false,
                success : function(response){
                    if(response.status == true)
                    {
                        // in a case of Ajax success:
                        $modalOvl.fadeIn();	// show success modal
                        $modalFeedbackSuccess.fadeIn();
                        $(".js-form_feedback input, .js-form_feedback textarea").val('');
                    } 
                    else
                    {
                        $modalOvl.fadeIn();	// show error modal
                        $modalError.fadeIn();
                    }
                },
                error: function(){
                    alert('There is an error!');
                }
            });

            $feedbackBtn.trigger("click");	//розвертаємо слайд
	})

	// callback form submission
	$(".js-form_callback").submit(function(event){
            event.preventDefault();
            var data = $(this).serialize();

            $.ajax({
                type : 'post',
                url: '/ohproject/feedbacks/create-callback',
                data : data,
                cache : false,
                success : function(response){
                    if(response.status == true)
                    {
                        // in a case of Ajax success:
                        $modalOvl.fadeIn();	// show success modal
                        $modalCallbackSuccess.fadeIn();
                        $(".js-form_callback input").val('');
                    } 
                    else
                    {
                        $modalOvl.fadeIn();	// show error modal
                        $modalError.fadeIn();
                    }
                },
                error: function(){
                    alert('There is an error!');
                }
            });
	})

	// modals
	var	 $modalOvl = $(".b-overlay_modal")
		,$modals = $modalOvl.find(".b-modal")
		,$modalError = $modals.filter(".b-modal_error")
		,$modalCloseBtn = $modals.find(".b-modal__btn_close")
		,$modalCallback = $modals.filter(".b-modal_callback")
		,$modalCallbackSuccess = $modals.filter(".b-modal_callbackSuccess")
		,$modalFeedbackSuccess = $modals.filter(".b-modal_feedbackSuccess")
		,$modalCallbackForm = $modalCallback.find(".js-form_callback")
		;

	$("#callbackBtn").click(function(){
            $modalOvl.fadeIn();
            $modalCallback.fadeIn();
	});

	$modalCloseBtn.click(function(){	// hide modals
            $modalOvl.fadeOut();
            $modals.fadeOut();
	});

	$modalCallbackForm.submit(function(event){
            event.preventDefault();
            // place for Ajax sending
            // in a case of Ajax success:
            $modals.fadeOut();
            $modalCallbackSuccess.fadeIn();
            // in a case of Ajax error:
            //$modals.fadeOut();
            //$modalError.fadeIn();
	})
        
        // scroll to top
	$("#toTop").click(function(){
		// event.preventDefault();
		var  $anchor = $($(this).find(".b-btn_toTop").attr("href"))
			,offsetAnchor = $anchor.offset().top
			;
		$('html, body').animate({ scrollTop: offsetAnchor}, 400);
		return false;
	})

	$("#toTop").trigger("click");	// scroll to top after page is loaded
	
});