function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$(".image-upload-wrap").hide();
			$(".file-upload-image").cropper("destroy");
			$(".file-upload-image").attr("src", e.target.result);
			$(".file-upload-image").cropper({
				aspectRatio: 1,
				viewMode: 2,
				responsive: true,
				checkOrientation: true,
				autoCrop: true,
				zoomable: false
			});
			$(".file-upload-content").show();
			$(".image-title").html(input.files[0].name);
		};

		reader.readAsDataURL(input.files[0]);
	} else {
		removeUploadFromForm();
	}
}

function removeUploadFromForm() {
	$('.file-upload-input').val('');
	$(".file-upload-image").cropper("destroy");
	$(".file-upload-content").hide();
	$(".image-upload-wrap").show();
}

$(".image-upload-wrap").bind("dragover", function() {
	$(".image-upload-wrap").addClass("image-dropping");
});
$(".image-upload-wrap").bind("dragleave", function() {
	$(".image-upload-wrap").removeClass("image-dropping");
});
