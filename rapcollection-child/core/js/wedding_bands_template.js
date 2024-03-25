jQuery(document).ready(function($) {

		//var options = [];
		// $(".attribute-pa_width-ring").each(function() {
		//     var radios = $(this).find(":radio").hide();
		//     //options.push(radios.val());
		//     $("<div id='atribbute_slider'></div>").slider({
		//       min: parseInt(radios.first().val(), 10),
		//       max: parseInt(radios.last().val(), 10),
		//       step: 0.5,
		//       slide: function(event, ui) {
		//         radios.filter("[value=" + ui.value + "]").click();
		//         // radios.filter("[value=" + ui.value + "]").addClass('active');
		//       }
		//     }).appendTo(this);
		// });

		var options = [];
		$(".attribute-pa_width-ring .value label").each(function() {
			var radios = $(this);
			//console.log( radios );
			options.push(radios[0].textContent);

		});
		// console.log(options);
		// console.log(options[0]);
		// console.log(options[options.length-1]);

		$(".attribute-pa_width-ring").append('<div class="atribbute_slider"></div>')

		$(".atribbute_slider").ionRangeSlider({
	        //type: "double",
	        // skin: "flat",
	        skin: "flat",
            //type: "single",
	        grid: true,
	        hide_min_max: true,
	        min: options[0],
	        max: options[options.length-1],
	        values: options,

	        onFinish: function (data) {
	            //console.log(data.from_value);
	            $(".attribute-pa_width-ring input").each(function(index, el) {
	            	if ( $(el).attr('data-name') == data.from_value ) {
	            		$(el).click();
	            	}
	            });
	        },

	    });

	    //$('.irs').addClass('irs--flat');


}); 