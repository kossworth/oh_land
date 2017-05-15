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

		$containerAjax.queue("ajax", function(){
			// place for Ajax sending
			//$(this).load("./ajax/__propositions.html", function(){propositionsInit($containerAjax)});	// підвантажуємо пропозиції та ініціалізуємо на них js-функціонал
			// var t = this;
			// var dequeueThis = function(t){
			// 	return function(){
			// 		$(t).dequeue("ajax");
			// 	}
			// }
			$.ajax({
            	type: "get",
            	url : "./ajax/__propositions.html",
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

			// $(this).dequeue("ajax");
			// showBcrumbs($bCrumbs);	// show breadcrumbs
		});


		// showContainerAjax($containerAjax);

		$containerAjax.dequeue("ajax");
	};
	var propositionsInit = function($containerAjax){	// ф-я ініціалізації js-функціоналу на підвантаженому блоці пропозицій
		// hide-show details  by click on "Подробнее"
			// show:
		$(".js-btn_readmore").click(function(){	// show-hide details text on "Подробнее" click
			// $(this).hide();
			var  $toggleBlock = $(this).prev(".js-content_readmore")
				,$parentOfToggleBlock = $toggleBlock.parents(".b-proposition")
				;
				
			if ($toggleBlock.hasClass("js-opened")){
				$toggleBlock.slideUp(200, function(){
					$parentOfToggleBlock.css("z-index","0");
					$(this).removeClass("js-opened");
				});
			} else {
				$parentOfToggleBlock.css("z-index","1");
				$toggleBlock.slideDown(200, function(){
					$(this).addClass("js-opened");
				});
			}
		})
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
            	type: "get",
            	url : "./ajax/__orderBlock.html",
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
			// $(this).load("./ajax/__orderBlock.html", function(){orderBlockInit($containerAjax)});	// підвантажуємо пропозиції та ініціалізуємо на них js-функціонал
			 
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
		// console.log($autoCompleteFields);
		$autoCompleteFields.blur(function(){
			if ($(this).next().val()){
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
        		}
        	},
			submitHandler: function(form) {	// replaces default form submit behavior
				// треба також перевірити поля автокомпліта
				var  bFocused = false
					,bValid = true
					;
				$autoCompleteFields.each(function(){
					if($(this).attr("disabled") == ""){
						if ($(this).next().val() ){
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
					$.ajax({
						url: form.action,
						type: form.method,
						data: $(form).serialize(),
						success: function(response) {
							showThanks($containerAjax, response);
						}            
					});
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
			,$trash = $orderBlock.find(".b-method__trash")
			,$trashTabs = $trash.find(".b-trash__tab")
			,$trashCard = $trash.find(".b-trash__sides")
			,$filesWrap = $orderBlock.find(".b-wrap_files")
			,$filesInput = $filesWrap.find("input[type='file']")
			,$filesList = $filesWrap.find(".b-list_files")
			,$filesProgress = $filesWrap.find(".b-progress_files")
			,$submitButtons = $methods.find("#submitBySelf, #submitByPhone, #submitByUpload")
			// ,$deliveryMode = $methods.find("select[name='deliveryMode']")
			,validatorCurrent = orderFormsValidation($methods.filter("#bySelf"))	// formBySelf validation init
			;
		
		// show only selected buy method
		$methodBtns.click(function(){
			var $activeBtn = $methodBtns.filter(".b-finalize__btn_active")
				,methodNum = $methodBtns.index($(this))
				,$activeMethod = $methods.filter(".js-method_active")
				;
				
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
			// console.log("before:", this.files.length);
			if (this.files.length > 10){
				this.files.length = 10;
				// console.log("after:", this.files.length);
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
		// "модель"
		fieldAutocomplete("model", "./ajax/model.json");
		// "марка"
		fieldAutocomplete("brand", "./ajax/brand.json");
		// місто доставки (із областю для кур’єра)
		fieldAutocomplete("delivCitySelf", "./ajax/cityRegion.json");
		// НП область
		fieldAutocomplete("delivRegionNP", "./ajax/region.json");
		// НП місто
		fieldAutocomplete("delivCityNP", "./ajax/region.json");
		// НП відділення
		fieldAutocomplete("delivDivisionNP", "./ajax/division.json");


		// selects stylization
		// $methods.find("#deliveryMode").selectric();
		// var deliveryNum;
		$methods.find("select[name='deliveryMode']").selectric({
			onChange: function(element) {
				$(element).change();
				// $(element).parents(".selectric-js-selectric")
				// console.log($(element).parent());
			}
		});
		// $deliveryMode = $methods.find("select[name='deliveryMode']")

		// show thanks page
		// $submitButtons.click(function(event){
		// 		event.preventDefault();
		// 		console.log(validatorCurrent.numberOfInvalids());
		// 	// if (validatorCurrent.numberOfInvalids() == 0){
		// 	// 	showThanks($containerAjax);
		// 	// }
		// });
	}
	
	// show thanks page
	var showThanks = function($containerAjax, responseFromPhp){
		hideContainerAjax($containerAjax);

		// -- temporary Ajax for static demo --
		// !!! comment it on server
		$containerAjax.queue("ajax", function(){
			// place for Ajax sending
			$.ajax({
            	type: "get",
            	url : "./ajax/__thanks.html",
            	error : function(){
            	    alert('error');
            	},
            	success: function(response){
            	    $containerAjax.html(response);
            	    // thanksInit($containerAjax);
            	},
            	complete: function(){
            		showContainerAjax($containerAjax);
            		$containerAjax.dequeue("ajax");
            	}
            });
		});
		//-----------------------------------

		// !!! 
		// commented variant for php server
		// $containerAjax.queue("ajax", function(){
  //           $containerAjax.html(responseFromPhp);
  //           showContainerAjax($containerAjax);
  //           $containerAjax.dequeue("ajax");
		// });

		$containerAjax.dequeue("ajax");
	}

	// vehicle calculator
	var showVehicleCalc = function($containerAjax){
		hideContainerAjax($containerAjax);

		$containerAjax.queue("ajax", function(){
			// place for Ajax sending
			$.ajax({
            	type: "get",
            	url : "./ajax/__calcVehicle.html",
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
				// console.log(index);
			if ($paramBlockActive != $(this)){
				$paramBlockActive.removeClass("js-params_active");
				$paramBlocks.eq(index).addClass("js-params_active");
				$paramBlockActive.fadeOut(0, function(){
					$paramBlocks.eq(index).fadeIn(0);
				})
			}
			// $("#" + $(this).attr("data-id")).trigger("click");	//перемикання радіобатонів об’єму при кліку на тз
		});

		//ajax registration city autocomplete
		fieldAutocomplete("regCity", "./ajax/city.json")
		
		// валідація
		var  $vehicleForm = $("#vehicleForm")
			,$cityId = $vehicleForm.find("#cityId")
			,$cityName = $vehicleForm.find("#regCity")
			;
		$vehicleForm.submit(function(event){
			event.preventDefault();
			if (!$cityId.val()){
				$cityName.focus();
			} else {
				showPropositions($containerAjax);	// load of propositions
			}
		});
	};

	// autocomplete function 
	// (enter string, shows items, select item -> send item id)
	// note: after autocompleted field must be hidden input with name attr to returnitem id
	var fieldAutocomplete = function(fieldId, jsonAddr){
		var  oJS
			,items = []
		    ,itemIds = []
		    ,objToComplete = $("#" + fieldId)
			;

		objToComplete.autoComplete({
				minChars: 2,
			    source: function(term, response){
			        $.getJSON(jsonAddr, { city: term }, function(data){
			        	items = []; // масив елементів
			    		itemIds = [];	// масив id елементів
			        	oJS = data;	//відповідний JSоб'єкт до JSON об'єкту AJAX відповіді

			        	for (var i = 0; i < oJS.items.length; ++i) {	// наповнимо масив елементів і їхніх id
			        		items.push(oJS.items[i].name);
			        		itemIds.push(oJS.items[i].id);
			        	};
			        	response(items);
			        });
			    },
			    // renderItem: function (item, search){
			    //     search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
			    //     var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
			    //     return '<div class="autocomplete-suggestion" data-langname="'+item[0]+'" data-lang="'+item[1]+'" data-val="'+search+'"><img src="img/'+item[1]+'.png"> '+item[0].replace(re, "<b>$1</b>")+'</div>';
			    // },
			    onSelect: function (event, term, item) {
			    	var itemIndex = items.indexOf(term);	// індекс міста в масиві
			    	objToComplete.next().val(itemIds[itemIndex]);	// повертаємо id міста прихованому елементу форми
					objToComplete.parent(".b-form__cell").removeClass("b-cell_error").addClass("b-cell_valid");
			    }
			});
		// objToComplete.focus(function(){
		// 	var e = jQuery.Event( "keydown", { keyCode: 128 } );
		// 	$(this).trigger(e);
		// })

		// objToComplete.autoComplete({
		// 	minChars: 2,
		//     source: function(term, response){
		//         $.getJSON(jsonAddr, { city: term }, function(data){
		//         	items = []; // масив елементів
		//     		itemIds = [];	// масив id елементів
		//         	oJS = data;	//відповідний JSоб'єкт до JSON об'єкту AJAX відповіді

		//         	for (var i = 0; i < oJS.items.length; ++i) {	// наповнимо масив елементів і їхніх id
		//         		items.push(oJS.items[i].name);
		//         		itemIds.push(oJS.items[i].id);
		//         	};
		//         	response(items);
		//         });
		//     },
		//     onSelect: function (event, term, item) {
		//     	var itemIndex = items.indexOf(term);	// індекс міста в масиві
		//     	objToComplete.next().val(itemIds[itemIndex]);	// повертаємо id міста прихованому елементу форми
		//     }
		// });
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
		// place for Ajax sending
		// in a case of Ajax success:
		$modalOvl.fadeIn();	// show success modal
		$modalFeedbackSuccess.fadeIn();
		$feedbackBtn.trigger("click");	//розвертаємо слайд
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
		event.preventDefault();
		var  $anchor = $($(this).find(".b-btn_toTop").attr("href"))
			,offsetAnchor = $anchor.offset().top
			;
		$('html, body').animate({ scrollTop: offsetAnchor}, 600);
		return false;
	})

	
});