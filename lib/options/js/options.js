function checkPasswordStrength( $pass1,
								$pass2,
								$strengthResult,
								$submitButton,
								blacklistArray ) {
	var pass1 = $pass1.val();
	var pass2 = $pass2.val();

	// Reset the form & meter
	$submitButton.attr( 'disabled', 'disabled' );
	$strengthResult.removeClass( 'short bad good strong' );

	// Extend our blacklist array with those from the inputs & site data
	blacklistArray = blacklistArray.concat( wp.passwordStrength.userInputBlacklist() )

	// Get the password strength
	var strength = wp.passwordStrength.meter( pass1, blacklistArray, pass2 );

	// Add the strength meter results
	switch ( strength ) {

		case 2:
			$strengthResult.addClass( 'bad' ).html( pwsL10n.bad );
			break;

		case 3:
			$strengthResult.addClass( 'good' ).html( pwsL10n.good );
			break;

		case 4:
			$strengthResult.addClass( 'strong' ).html( pwsL10n.strong );
			break;

		case 5:
			$strengthResult.addClass( 'short' ).html( pwsL10n.mismatch );
			break;

		default:
			$strengthResult.addClass( 'short' ).html( pwsL10n.short );

	}

	// The meter function returns a result even if pass2 is empty,
	// enable only the submit button if the password is strong and
	// both passwords are filled up
	if ( 4 === strength && '' !== pass2.trim() ) {
		$submitButton.removeAttr( 'disabled' );
	}

	return strength;
}

jQuery(document).ready(function($){

	/**
	 * Preserves user's currently selected tab after page reload
	 */
	
	var hash = window.location.hash;
	hash && $('ul.nav a[href="' + hash + '"]').tab('show');

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

		var scrollmem = $('body').scrollTop();
		window.location.hash = e.target.hash;
		$('html,body').scrollTop(scrollmem);

		$(".code_text").each(function(i) {
			editor[i].refresh();
		});

	});

	// Binding to trigger checkPasswordStrength
	$( 'body' ).on( 'keyup', 'input[type=password]',
		function( event ) {
			checkPasswordStrength(
				$('input[type=password]'),         // First password field
				$('input[type=password].retyped'), // Second password field
				$('#password-strength'),           // Strength meter
				$('input[type=submit]'),           // Submit button
				['password']        // Blacklisted words
			);
		}
	);


	// Select2 select fields
	$(".select2").select2({
		minimumResultsForSearch: 10
	});

	/**
	 * Code Editor Field
	 * @since 2.0
	 */
	
	var editor = new Array();

	function codemirror_resize_fix() {
		var evt = document.createEvent('UIEvents');
		evt.initUIEvent('resize', true, false, window, 0);
		window.dispatchEvent(evt);
	}

	$(".code_text").each(function(i) {

		var lang = jQuery(this).attr("data-lang");
		switch(lang) {
			case 'php':
				lang = 'application/x-httpd-php';
				break;
			case 'css':
				lang = 'text/css';
				break;
			case 'html':
				lang = 'text/html';
				break;
			case 'javascript':
				lang = 'text/javascript';
				break;
			default:
				lang = 'application/x-httpd-php';
		}

		var theme = $(this).attr("data-theme");

		switch(theme) {
			case 'default':
				theme = 'default';
		 		break;
			case 'dark':
				theme = 'twilight';
				break;
			default:
		 		theme = 'default';
		}

		editor[i] = CodeMirror.fromTextArea(this, {
			lineNumbers:    true,
			//matchBrackets:  true,
			mode:           lang,
			indentUnit:     4,
			indentWithTabs: true
			//enterMode:      "keep",
			//tabMode:        "shift"
		});

		editor[i].setOption("theme", theme);
	});

	/**
	 * File upload button
	 * @since 2.0
	 */
	$('.fileinput-field .btn-file').bind('click', function(e) {
		tb_show('', 'media-upload.php?post_id=0&type=image&apc=apc&TB_iframe=true');
		var filebutton = $(this);
		//store old send to editor function
		window.restore_send_to_editor = window.send_to_editor;

		//overwrite send to editor function
		window.send_to_editor = function(html) {

			imgurl = $('img', html).attr('src');

			var index = imgurl.lastIndexOf("/") + 1;
			var filename = imgurl.substr(index);

			filebutton.find('.fileinput-input').val(imgurl);
			filebutton.prev().find('.fileinput-filename').text(filename);

			filebutton.parent().parent().removeClass('fileinput-new').addClass('fileinput-exists');

			// Update image preview
			filebutton.parent().parent().find('.fileinput-preview').html("<img src='" + imgurl + "'>");

			//load_images_muploader();
			tb_remove();
			//restore old send to editor function
			window.send_to_editor = window.restore_send_to_editor;

		}

		return false;
	});

	$('.fileinput-field .btn.fileinput-exists').bind('click', function(e) {

		$(this).parent().parent().removeClass('fileinput-exists').addClass('fileinput-new');
		$(this).prev().find('.fileinput-input').val('');
		$(this).prev().prev().find('.fileinput-filename').text('');


	});

});
