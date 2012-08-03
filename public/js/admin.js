function confirmDelete() {
	var name = $(this).parent().siblings().eq(1).children().text();
        return confirm("Are you sure you want to delete " + name + "?\nWarning: This action cannot be undone!");
}

function changeElections() {
	$.cookie('selected_election', $(this).val(), {path: '/'});
	$.cookie('selected_position', '', {path: '/'});
	window.location.href = CURRENT_URL;
}

function changePositions() {
	$.cookie('selected_position', $(this).val(), {path: '/'});
	window.location.href = CURRENT_URL;
}

function fillPositionsAndParties() {
	$.ajax({
		type: "POST",
		url: window.location.href,
		data: $(this).serialize() + '&halalan_csrf_token_name=' + $('input[name="halalan_csrf_token_name"]').val(),
		success: function(msg){
			var msg = $.parseJSON(msg);
			var positions = msg[0];
			var parties = msg[1];
			var option = new Option();
			$('#position_id').children().remove();
			option.value = '';
			option.text = 'Select Position';
			$('#position_id').append(option);
			for (i = 0; i < positions.length; i++) {
				option = new Option();
				option.value = positions[i].id;
				option.text = positions[i].position;
				$('#position_id').append(option);
			}
			var option = new Option();
			$('#party_id').children().remove();
			option.value = '';
			option.text = 'Select Party';
			$('#party_id').append(option);
			for (i = 0; i < parties.length; i++) {
				option = new Option();
				option.value = parties[i].id;
				option.text = parties[i].party;
				$('#party_id').append(option);
			}
		}
	});
}

$(document).ready(function () {
	$('a.confirmDelete').click(confirmDelete);
	$('select.changeElections').change(changeElections);
	$('select.changePositions').change(changePositions);
	$('select.fillPositionsAndParties').change(fillPositionsAndParties);
});