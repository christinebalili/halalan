function confirmDelete() {
	var name = $(this).parent().siblings().eq(1).children().text();
        return confirm("Are you sure you want to delete " + name + "?\nWarning: This action cannot be undone!");
}

$(document).ready(function () {
	$('a.confirmDelete').click(confirmDelete);
});