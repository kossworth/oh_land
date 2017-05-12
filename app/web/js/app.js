
$(document).ready(function(){
// Global variables
	var  $containerAjax = $(".js-ajax_calculator")
		// ,$bCrumbs = $(".b-crumbs")
		;

	// Functions for the calc-choose-buy block AJAX loads and js-functional inits on them
	var hideContainerAjax = function($containerAjax){
		$containerAjax.fadeOut({
			duration: 400,
			queue: "ajax"
			}
		);
	};
	var showContainerAjax = function($containerAjax){
		$containerAjax.fadeIn({
			duration: 400,
			queue: "ajax"
		});
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
                var type = $("#vehicleForm [name='type']").val();
                var notTaxi = $("#vehicleForm [name='notTaxi']").val();
                var franshiza = $("#vehicleForm [name='franshiza']").val();
                var city = $("#vehicleForm [name='regCity']").val();
                
		$containerAjax.queue("ajax", function(){
                    $.ajax({
                        type: "get",
                        url : "/ohproject/osago/send-request",
                        data: { type : type, notTaxi : notTaxi, franshiza : franshiza, city : city },
                        error : function(){
                            alert('error');
                        },
                        success: function(response){
                            $containerAjax.html(response);
                            propositionsInit($containerAjax);
                        }
                    });
			// place for Ajax sending
//			$(this).load("./ajax/__propositions.html", function(){
//                            propositionsInit($containerAjax)
//                        });	// підвантажуємо пропозиції та ініціалізуємо на них js-функціонал
			
			$(this).dequeue("ajax");
			// showBcrumbs($bCrumbs);	// show breadcrumbs
		});

		showContainerAjax($containerAjax);

		$containerAjax.dequeue("ajax");
	};
	var propositionsInit = function($containerAjax){	// ф-я ініціалізації js-функціоналу на підвантаженому блоці пропозицій
		// hide-show details -----
		// show:
		$(".js-btn_readmore").click(function(){	// show-hide details text on "Подробнее" click
			// $(this).hide();
			$(this).prev(".js-content_readmore").slideToggle();
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
		var $sliderPropos = $(".b-propositions .b-propositions__string").slick({
			arrows: false,
			infinite: false,
			speed: 400,
			slidesToShow: 4,
			slidesToScroll: 4,
			// autoplay: true,
			// autoplaySpeed: 4400
		});
		//	morePropositions btn functionality
		var  $sliderProposMoreBtn = $sliderPropos.parents(".b-calculator_propos").find("#morePropositions");
		$sliderProposMoreBtn.click(function(event){
			event.preventDefault();
			$sliderPropos.slick("slickNext");
		});

		$("#vehicleEdit").click(function(){showVehicleCalc($containerAjax)});
		$("#propositions .b-proposition__buy").click(function(){
			var propositonId = $(this).attr("data-proposition");	// id пропозиції яку треба передати
			//$(this).load("./ajax/__calcVehicle.html", function(){vehicleCalcInit($containerAjax)});
		});
	};
	
	// order block
	var showOrderBlock = function(proposNum, $containerAjax){
		hideContainerAjax($containerAjax);

		$containerAjax.queue("ajax", function(){
			// place for Ajax sending
                      
                    $.ajax({
                        type: "get",
                        url : "/ohproject/osago/",
                        data: { type : type, notTaxi : notTaxi, franshiza : franshiza, city : city },
                        error : function(){
                            alert('error');
                        },
                        success: function(response){
                            $containerAjax.html(response);
                            orderBlockInit($containerAjax)
                        }
                    });
                        
//                    $(this).load("./ajax/__orderBlock.html", function(){orderBlockInit($containerAjax)});	// підвантажуємо пропозиції та ініціалізуємо на них js-функціонал

                    $(this).dequeue("ajax");
                    // hideBcrumbs($bCrumbs);	// show breadcrumbs
		});

		showContainerAjax($containerAjax);
		
		$containerAjax.dequeue("ajax");
	}
	var orderBlockInit = function($containerAjax){		
		var  $orderBlock = $("#finalize")
			,$methodBtns = $orderBlock.find(".b-finalize__btn_method")
			,$methods = $orderBlock.find(".b-finalize__method")
			,$trash = $orderBlock.find(".b-method__trash")
			,$trashTabs = $trash.find(".b-trash__tab")
			,$trashCard = $trash.find(".b-trash__sides")
			;
		
		// selects stylization
		$orderBlock.find("#bySelf").find(".js-selectric").selectric({
			onInit: function() {
				$orderBlock.find(".selectric-wrapper>.selectric-items ul>li.disabled").remove();	//прибираємо неактивний пункт
			}
		});
		
		// show only selected buy method
		$methodBtns.click(function(){
			var $activeBtn = $methodBtns.filter(".b-finalize__btn_active")
				,methodNum = $methodBtns.index($(this))
				,$activeMethod = $methods.filter(".js-method_active")
				;
				
			if($activeBtn != $(this)){
				$activeBtn.removeClass("b-finalize__btn_active");
				$(this).addClass("b-finalize__btn_active");
				$activeMethod.fadeOut(400, function(){
					$activeMethod.removeClass("js-method_active");
					$activeMethod = $methods.eq(methodNum).addClass("js-method_active");
					$activeMethod.fadeIn(400);
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

		// pickadate initialization
		jQuery.extend( jQuery.fn.pickadate.defaults, {
			monthsFull: [ 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря' ],
			monthsShort: [ 'янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек' ],
			weekdaysFull: [ 'воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота' ],
			weekdaysShort: [ 'вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб' ],
			today: 'сегодня',
			clear: 'удалить',
			close: 'закрыть',
			firstDay: 1,
			format: 'd mmmm yyyy г.',
			formatSubmit: 'yyyy/mm/dd'
		});

		var pickadayOptions = {
			min: +1,
			onClose: function () {
			   $("#bySelf").find(".picker__holder").blur();
			}
		  //selectYears: true,
		  //selectMonths: true
		}

		$orderBlock.find("#bySelf").find("#date").pickadate(pickadayOptions);
	}
	
	// vehicle calculator
	var showVehicleCalc = function($containerAjax){
		hideContainerAjax($containerAjax);

		$containerAjax.queue("ajax", function(){
			// place for Ajax sending
			$(this).load("./ajax/__calcVehicle.html", function(){vehicleCalcInit($containerAjax)});	// підвантажуємо пропозиції та ініціалізуємо на них js-функціонал
			 
			$(this).dequeue("ajax");
			// hideBcrumbs($bCrumbs);	// show breadcrumbs
		});

		showContainerAjax($containerAjax);
		
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

		$("#vehicleForm").submit(function(event){
			event.preventDefault();
			showPropositions($containerAjax);	// load of propositions
		});
	};
	var showFinalCalc = function(propositonId){
		hideContainerAjax($containerAjax);

		$containerAjax.queue("ajax", function(){
			// place for Ajax sending
			$(this).load("./ajax/__finalBlock.html", function(){propositionsInit($containerAjax)});	// підвантажуємо пропозиції та ініціалізуємо на них js-функціонал
			
			$(this).dequeue("ajax");
		});

		showContainerAjax($containerAjax);

		$containerAjax.dequeue("ajax");
	}
	var finalCalcInit = function(){}

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