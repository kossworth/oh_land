
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

		$containerAjax.queue("ajax", function(){
			// place for Ajax sending
			$(this).load("./ajax/__propositions.html", function(){propositionsInit($containerAjax)});	// підвантажуємо пропозиції та ініціалізуємо на них js-функціонал
			
			$(this).dequeue("ajax");
			// showBcrumbs($bCrumbs);	// show breadcrumbs
		});

		showContainerAjax($containerAjax);

		$containerAjax.dequeue("ajax");
	};
	var propositionsInit = function($containerAjax){	// ф-я ініціалізації js-функціоналу на підвантаженому блоці пропозицій
		// hide-show details -----
		// show:
		$(".js-btn_readmore").click(function(){
			$(this).hide();
			$(this).next(".js-content_readmore").slideDown();
		})
		// hide:
		$(".b-proposition").mouseleave(function(){
			var $hiddenContent = $(this).find(".js-content_readmore");
			$hiddenContent.slideUp(
				function(){
					$hiddenContent.prev(".js-btn_readmore").slideDown();
				}
			);
		})

		//propositions slider
		var $sliderPropos = $(".b-propositions .b-propositions__string").slick({
			arrows: false,
			infinite: true,
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
		$(".b-vehicle").click(function(){
			$(".b-vehicle").removeClass("b-vehicle_active");
			$(this).addClass("b-vehicle_active");
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
				},
				{
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
				},
				{
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
				},
				{
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
				},
				{
					duration: 200,
					easing: "linear",
					queue: "unactive"	// черга для анімації цього слайда
				}
			);
			$slide.animate({
					top: 30,
					left: 404
				},
				{
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