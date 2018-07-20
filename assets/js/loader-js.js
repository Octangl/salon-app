window.onload = function(){
  var delayInMilliseconds = 100; //100 millisecond
	setTimeout(function() {
		$('.circle-loader').toggleClass('load-complete');
    $('.checkmark').toggle();
	}, delayInMilliseconds);
}


/*
// Get the modal
var modal = document.getElementById('loaderModal');

// Get the button that opens the modal
var btn = document.getElementById("mySubmit");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("hide")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
    $('.circle-loader').toggleClass('load-complete');
    $('.checkmark').toggle();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}*/
