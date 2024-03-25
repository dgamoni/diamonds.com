<?php
/**
 * Checkout engraving form
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// find in the checkout the product 
$engraving_product = get_field('engraving_product', 'options');
if (!$engraving_product || !WC()->cart->get_cart_contents()) return;


$engraving_key = false; // false if not in the cart, cart key if exists 
$engraving_content = array();

if (WC()->cart->get_cart_contents()) {
	foreach(WC()->cart->get_cart_contents() as $item) {
		if ($item['product_id'] == $engraving_product->ID) {
			$engraving_key = $item['key'];
			$engraving_content = $item;
		}
	}
}


?>
<div class="woocommerce-engraving-fields">
	<h3>Add Engraving</h3>
	<p id="show-engraving">
        <input id="add_engraving" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="checkbox" name="add_engraving" value="1">
		<label for="add_engraving" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox"> <span>Show engraving options</span></label>
	</p>
	<div id="engraving-body" style="display: none;">
		<label>Choose Font</label>
		<p class="engraving-buttons">
			<input id="engraving_print" class="woocommerce-form__input" type="radio" name="engraving_font_choose" value="print"
				<?php if ($engraving_content && isset($engraving_content['font']) && ($engraving_content['font'] == 'print')) echo 'checked="checked"'; ?> <?php if (!$engraving_content || !isset($engraving_content['font'])) echo 'checked="checked"'; ?>>
			<label for="engraving_print" class="woocommerce-form__label "> <span>Print</span></label>
			<input id="engraving_cursive" class="woocommerce-form__input" type="radio" name="engraving_font_choose" value="cursive" <?php if ($engraving_content && isset($engraving_content['font']) && ($engraving_content['font'] == 'cursive')) echo 'checked="checked"'; ?>>
			<label for="engraving_cursive" class="woocommerce-form__label "> <span>Cursive</span></label>
			<input id="engraving_custom" class="woocommerce-form__input" type="radio" name="engraving_font_choose" value="custom" <?php if ($engraving_content && isset($engraving_content['font']) && ($engraving_content['font'] == 'custom')) echo 'checked="checked"'; ?>>
			<label for="engraving_custom" class="woocommerce-form__label "> <span>Custom</span></label>
		</p>
		<p id="custom_font_wrap" <?php if (!$engraving_content || !isset($engraving_content['font']) || !($engraving_content['font'] == 'custom')) echo 'style="display: none;"'; ?>>
			<label for="engraving_custom_font" class="">Custom font name</label>
			<span class="woocommerce-input-wrapper">
				<input type="text" class="input-text " name="engraving_custom_font" id="engraving_custom_font" placeholder="Please write your engraving font name" <?php if ($engraving_content) echo 'value="'.$engraving_content['font-family'].'"'; ?>>
			</span>
			<select id="engraving-font" style="display: none; width: 100%;" name="engraving-font">
				<?php 
					//get fonts
					$fonts = get_field('engraving_fonts', 'options');
				
					if ($fonts) {
						foreach ($fonts as $font) {
							
							?>
							<option value="<?php echo $font['font_name']; ?>" 
								data-name="<?php echo $font['font_name']; ?>"
								data-image="<?php echo $font['font_image']; ?>"
								data-family="<?php echo $font['font-family']; ?>"
								data-link="<?php echo $font['font_link']; ?>"
								data-file="<?php echo $font['font_file']; ?>"
								<?php if ($engraving_content && isset($engraving_content['font-family']) && ($engraving_content['font-family'] == $font['font_name'])) echo 'selected="selected"'; ?>
								>
								<?php echo $font['font_name']; ?>
								</option>
							<?php
						}
					}
				?>
			</select>
		</p>
		<p id="upload_font" <?php if (!$engraving_content || !isset($engraving_content['font']) || !($engraving_content['font'] == 'custom')) echo 'style="display: none;"'; ?>>
			Or choose your font (only TTF, 2mb max)<br>
			
			<input type="hidden" name="custom_engraving_font_name" id="custom_engraving_font_name" <?php if ($engraving_content && isset($engraving_content['use-file']) && ($engraving_content['use-file'])) echo 'value="'.$engraving_content['font-family'].'"'; ?>>
			
			<input type="hidden" name="custom_engraving_font_url" id="custom_engraving_font_url" <?php if ($engraving_content && isset($engraving_content['use-file']) && ($engraving_content['use-file'])) echo 'value="'.$engraving_content['file-link'].'"'; ?>>
			
			<input type="hidden" name="custom_engraving_font_face" id="custom_engraving_font_face" <?php if ($engraving_content && isset($engraving_content['use-file']) && ($engraving_content['use-file'])) echo 'value="'.$engraving_content['font-family'].'"'; ?>>
			
			<input id="use_custom_engraving" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="checkbox" name="use_custom_engraving" value="1" <?php if ($engraving_content && isset($engraving_content['use-file']) && ($engraving_content['use-file'])) echo 'checked="checked"'; ?>>
			<label for="use_custom_engraving" class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox"> <span></span>
			</label>
			
			<input type="file" name="custom_engraving_file" id="custom_engraving_file" accept=".ttf" <?php if ($engraving_content && isset($engraving_content['use-file']) && ($engraving_content['use-file'])) echo 'style="display:none;"'; ?>>
			
			<span id="custom_engraving_response"><?php if ($engraving_content && isset($engraving_content['use-file']) && ($engraving_content['use-file'])) echo $engraving_content['font-family']; ?></span>
		</p>
		<p class="form-row form-row-wide" >
			<label for="engraving_text" class="">Inscription</label>
			<span class="woocommerce-input-wrapper">
				<input type="text" class="input-text " name="engraving_text" id="engraving_text" placeholder="Please write your engraving here" <?php if ($engraving_content && isset($engraving_content['inscription']) && ($engraving_content['inscription'])) echo 'value="'.$engraving_content['inscription'].'"'; ?> maxlength="30">
			</span>
			<label for="engraving_text" id="engraving_text_counter"></label>
		</p>
		<div id="engraving_preview"></div>
		<button id="save_engraving">Save Engraving</button>
	</div>
</div>

<script>
	jQuery(function ($) {
		var select = $('#engraving-font');
		
		select.select2({
			templateSelection: formatSelection,
			templateResult: formatResult
		});
		
		function formatSelection(state) {
			if (!state.id) {
				return state.text;
			}
			
			var $state = $(
				'<img src="'+ state.element.dataset.image+'" class="engraving-img" />'
			);
			return $state;
		}
		
		function formatResult(state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<img src="'+ state.element.dataset.image+'" class="engraving-img" />'
			);
			return $state;
		}
		
		select.on('select2:select', function(e) {
			renderPreview();
		});
		
		function renderPreview() { // <option> with the data atributes, jquery object 
			// check font
			var fontExists = false;
			var fontFamily = '';
			
			// check buttons 
			if ($('#engraving_print').prop("checked")) fontFamily = 'Times New Roman';
			if ($('#engraving_cursive').prop("checked")) fontFamily = 'Italianno';
			
			if ($('#engraving_custom').prop("checked")) {
				if ($('#use_custom_engraving').prop("checked") && $('#custom_engraving_font_face').val()) { // use custom user's font 
					fontFamily = $('#custom_engraving_font_face').val()
				} else { // use preset fonts 
					// set $option 
					$option = $('#engraving-font option').eq(0);
					
					$('#engraving-font option').each(function() {
						if ($(this).data('name') == $('#engraving-font').val()) $option = $(this);
					});
					
					if (!$option.data('file')) { // check google fonts 
						$('link').each(function(){
							if ($(this).attr('href') == $option.data('link')) fontExists = true;
						});
					
						if (!fontExists) {
							var link = document.createElement('link');
							link.setAttribute('rel', 'stylesheet');
							link.setAttribute('type', 'text/css');
							link.setAttribute('href', $option.data('link'));
							document.head.appendChild(link);
						}
						
					} else { // check custom fonts 
						$('style').each(function(){
							if (~$(this).text().indexOf($option.data('file')) ) fontExists = true;
						});
						
						if (!fontExists) { // add <style>
							var newStyle = document.createElement('style');
							newStyle.appendChild(document.createTextNode('@font-face {font-family: "' + $option.data('family') + '"; src: url("' + $option.data('file') + '");}'));
							document.head.appendChild(newStyle);
						}
					}
					
					fontFamily = $option.data('family');
				}
			}
			
			$('#engraving_preview').text($('#engraving_text').val());
			$('#engraving_preview').css({'font-family' : fontFamily});
		}
		
		//start render
		renderPreview();
		
		// upload the file 
		$('#custom_engraving_file').change(function(e){
			
			$('#custom_engraving_response').text('');
			
			$('#engraving-body').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			
			// check size 
			if (!this.files || this.files[0].size > 2*1024*1024) {
				$('#engraving-body').unblock();
				$('#custom_engraving_response').text('File is too big, select the file less than 2MB');
				$(this).wrap('<form>').closest('form').get(0).reset();
				$(this).unwrap();
			} else {
				ajaxs( 'ajaxs_engraving_upload_font', {'file' : $('#custom_engraving_file')}, function(response) {
					console.log(response);
					$('#engraving-body').unblock();
					if (response.data.error) {
						$('#custom_engraving_response').text(response.data.error);
						console.log('error');
						$('#custom_engraving_file').wrap('<form>').closest('form').get(0).reset();
						$('#custom_engraving_file').unwrap();
					} else {
						$('#custom_engraving_font_name').val('User font');
						$('#custom_engraving_font_url').val(response.data.url);
						$('#custom_engraving_font_face').val(response.data.name);
						$('#custom_engraving_response').text(response.data.name);
						$('#use_custom_engraving').prop('checked', true);
						$('#custom_engraving_file').hide();
						
						var fontExists = false;
						
						$('style').each(function(){
							if (~$(this).text().indexOf(response.data.url) ) fontExists = true;
						});
						
						if (!fontExists) { // add <style>
							var newStyle = document.createElement('style');
							newStyle.appendChild(document.createTextNode('@font-face {font-family: "' + response.data.name + '"; src: url("' + response.data.url + '");}'));
							document.head.appendChild(newStyle);
						}
					}
					
					renderPreview();
				});
				
			}
			
			renderPreview();
		});
		
		// show/hide engrabing settings 
		$('#add_engraving').change(function(){
			if ($(this).prop("checked")) { //show
				$('#engraving-body').show();
			} else { //hide
				$('#engraving-body').hide();
			}
			renderPreview();
		});
		
		$('#use_custom_engraving').change(function(){
			$('#custom_engraving_file').show();
			$('#custom_engraving_response').text('');
			renderPreview();
		});
		
		
		$('input[name=engraving_font_choose]').change(function(){
			if ($('#engraving_custom').prop("checked")) { //show
				$('#custom_font_wrap, #upload_font').show();
			} else { //hide
				$('#custom_font_wrap, #upload_font').hide();
			}
			renderPreview();
		});
		
		$('#engraving_text').keyup(function() {
			renderPreview();
			var num = 30 - $(this).val().length;
			$('#engraving_text_counter').text('Characters Left: '+num);
		});
		
		$('#save_engraving').click(function(){
			$('body').trigger('update_checkout');
			return false;
		});
		
	});
</script>