$(function() {

	// Get the form.
	var form = $('#ajax-contact');

	// Get the modal
	var modal = $('#loaderModal');

	// Get the <span> element that closes the modal
	var span = $(".hide")[0];

	// Get the messages div.
	var formMessages = $('#form-messages');

	// make sure the modal is hidden by default
	$('#loaderModal').css('display','none');

	// Set up an event listener for the contact form.
	$(form).submit(function(e) {
		// Stop the browser from submitting the form.
		e.preventDefault();

		// Serialize the form data.
		var formData = $(form).serialize();

		// Submit the form using AJAX.
		$.ajax({
			type: 'POST',
			url: $(form).attr('action'),
			data: formData
		})
		.done(function(response) {
			$('#loaderModal').modal('show');
			//$('#loaderModal').css('display','block');
			//$('.circle-loader').toggleClass('load-complete');
			//$('.checkmark').toggle();
			

			// When the user clicks on <span> (x), close the modal
			$( "#toggle" ).click(function() {
				$('#loaderModal').modal('hide');
			  });

			

			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
				if (event.target == modal) {
					modal.style.display = "none";
				}
			}
			

			//Clear the form.
			$('#probootstrap-date-departure').val('');
			$('#name').val('');
			$('#phone').val('');
		})
		.fail(function(data) {
			
			// Set the message text.
			if (data.responseText !== '') {
				$(formMessages).text(data.responseText);
			} else {
				modal.style.display = "block";
				$('.circle-loader').toggleClass('load-complete');
				$('.checkmark').toggle();
				/*$(formMessages).text('Oops! An error occured and your message could not be sent.');*/
			}
		});

	});

});
