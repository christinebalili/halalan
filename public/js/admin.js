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

function selectChosen() {
	$('#chosen').children().attr('selected', true);
	$('#chosen_elections').children().attr('selected', true);
	$('#general_positions').attr('disabled', false);
	$('#general_positions').children().attr('selected', true);
}

function copySelectedWithAjax() {
	var from;
	var to;
	var selected;
	var array = new Array();
	var direction = $(this).val();

	if (direction === "  >>  ") {
		from = $('#possible_elections');
		to = $('#chosen_elections');
	} else {
		from = $('#chosen_elections');
		to = $('#possible_elections');
	}

	selected = from.children(':selected');

	if (!selected.length) {
		alert("No items selected.");
	} else {
		to.append(selected);
		selected.removeAttr('selected')
			.each(function(i){ array[i] = '"' + this.value + '"'; });
		$.ajax({
			type: "POST",
			url: window.location.href,
			data: "election_ids=[" + array + "]&halalan_csrf_token_name=" + $('input[name="halalan_csrf_token_name"]').val(),
			success: function(msg){
				var msg = $.parseJSON(msg);
				var general = msg[0];
				var specific = msg[1];
				$('#notice').hide();
				for (i = 0; i < general.length; i++) {
					if (direction === "  >>  ") {
						var gen = new Option();
						gen.value = general[i].value;
						gen.text = general[i].text;
						$('#general_positions').append(gen);
					} else {
						$('option[value="' + general[i].value + '"]').remove();
					}
				}
				for (i = 0; i < specific.length; i++) {
					if (direction === "  >>  ") {
						var spe = new Option();
						spe.value = specific[i].value;
						spe.text = specific[i].text;
						$('#possible').append(spe);
					} else {
						$('option[value="' + specific[i].value + '"]').remove();
					}
				}
			}
		});
	}
}

$(document).ready(function () {
	$('a.confirmDelete').click(confirmDelete);
	$('select.changeElections').change(changeElections);
	$('select.changePositions').change(changePositions);
	$('select.fillPositionsAndParties').change(fillPositionsAndParties);
	$('form.selectChosen').submit(selectChosen);
	$('input:button.copySelectedWithAjax').click(copySelectedWithAjax);
});