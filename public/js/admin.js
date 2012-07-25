function confirmDelete() {
	var name = $(this).parent().siblings().eq(1).children().text();
        return confirm("Are you sure you want to delete " + name + "?\nWarning: This action cannot be undone!");
}

function changeElections() {
        var url = SITE_URL;
        if (url.length - 1 != url.lastIndexOf('/')) {
                url += '/';
        }
        url += 'admin/';
        // get string after SITE_URL + admin and before the next /
        // example expected values: candidates, positions, etc
        url += window.location.href.substring(url.length).split('/')[0];
        url += '/index/' + $(this).val();
        $.cookie('selected_election', $(this).val(), {path: '/'});
        window.location.href = url;
}

$(document).ready(function () {
	$('a.confirmDelete').click(confirmDelete);
	$('select.changeElections').change(changeElections);
});