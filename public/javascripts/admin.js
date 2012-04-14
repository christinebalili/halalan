
/* jQuery event handlers */

function confirmDelete() {
	var name = $(this).parent().siblings().eq(1).children().text();

	return confirm("Are you sure you want to delete " + name + "?\nWarning: This action cannot be undone!");
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

function copySelected() {
	var from;
	var to;
	var selected;

	if ($(this).val() === "  >>  ") {
		from = $('#possible');
		to = $('#chosen');
	} else {
		from = $('#chosen');
		to = $('#possible');
	}

	selected = from.children(':selected');

	if (!selected.length) {
		alert("No items selected.");
	} else {
		to.append(selected);
		selected.removeAttr('selected');
	}
}

function manipulateAllPositions() {
	var img = $('img.togglePosition');
	var src = img.attr('src');
	var alt;

	if ($(this).text() === "expand all") {
		alt = "Collapse";
		src = src.replace("plus", "minus");
		img.siblings('span').hide();
		$('table.table').show();
	} else {
		alt = "Expand";
		src = src.replace("minus", "plus");
		img.siblings('span').show();
		$('table.table').hide();
	}

	img.attr({
		'src': src,
		'alt': alt,
		'title': alt
	});

	return false;
}

function togglePosition() {
	var idx = $('img.togglePosition').index(this);
	var src = $(this).attr('src');
	var alt = $(this).attr('alt');

	if (alt === "Expand") {
		alt = "Collapse";
		src = src.replace("plus", "minus");
	} else {
		alt = "Expand";
		src = src.replace("minus", "plus");
	}

	$(this).siblings('span').toggle();
	$('table.table').eq(idx).toggle();
	$(this).attr({
		'src': src,
		'alt': alt,
		'title': alt
	});

	return false;
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

function changeElections() {
	$.cookie('selected_election', $(this).val(), {path: '/'});
	$.cookie('selected_position', '', {path: '/'});
	window.location.href = CURRENT_URL;
}

function changePositions() {
	$.cookie('selected_position', $(this).val(), {path: '/'});
	window.location.href = CURRENT_URL;
}

function changeBlocks() {
	$.cookie('selected_block', $(this).val(), {path: '/'});
	window.location.href = CURRENT_URL;
}

/* DOM is ready */
$(document).ready(function () {
	var menu_map = {};
	menu_map['home'] = "HOME";
	menu_map['elections'] = "ELECTIONS";
	menu_map['positions'] = "POSITIONS";
	menu_map['parties'] = "PARTIES";
	menu_map['candidates'] = "CANDIDATES";
	menu_map['blocks'] = "BLOCKS";
	menu_map['voters'] = "VOTERS";

	/* Bind handlers to events */
	$('a.confirmDelete').click(confirmDelete);
	$('a.manipulateAllPositions').click(manipulateAllPositions);
	$('img.togglePosition').click(togglePosition);
	$('input:button.copySelectedWithAjax').click(copySelectedWithAjax);
	$('input:button.copySelected').click(copySelected);
	$('form.selectChosen').submit(selectChosen);
	$('select.changeElections').change(changeElections);
	$('select.changePositions').change(changePositions);
	$('select.changeBlocks').change(changeBlocks);
	$('select.fillPositionsAndParties').change(fillPositionsAndParties);
	
	/* Code that aren't bound to events */
	highlightMenuItem(menu_map);
	animateFlashMessage();
	$('input[type="text"]:eq(0)').focus();
});
