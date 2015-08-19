
/**
 * Shows an image object into the main viewer
 * requires: attribute data-id
 */
function doImageShow( imageObject ) {
	if ( imageObject ) {
		var dataImageReference = $(imageObject).attr("data-id");
		if ( dataImageReference ) {
			var dataImageObject = new Image() ;
			dataImageObject.src = "/imagepool/" + dataImageReference + "?width=*&height="+($(window).height()-50);
			$(".overlay").fadeIn();
			$(".imageview").children().remove();
			$(".imageview").fadeIn();
			$(".imageview").append(dataImageObject);
			$(".imageview").click(function(){
				$(".imageview").hide();
				$(".overlay").fadeOut();
			});
		}
	}
}