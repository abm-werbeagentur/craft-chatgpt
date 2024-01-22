document.addEventListener("DOMContentLoaded", () => {
	$('.abm-slider input[type="range"]').on('mousedown mousemove mouseup keydown',function () {
		let cont = $(this).parents('.abm-slider').find('span');
		cont.text($(this).val());
	});
});

$(document).on("click", "a.doAi-abm-chatgpt", function( ) {

	let $field = $('button[data-hash="' + $(this).data('hash') + '"]').parents('.field').first();
	let input = $field.find('.input input, .input textarea').first();
	let query = input.val();
	
	let prompt = $(this).data('prompt');
	let lang = $(this).data('lang');

	abm_chatgpt_sendRequest(prompt, query, lang).then((response) => {
		
		let data = "";
		let errorMessages = "";

		if(response.res) {
			let responseText = response.result.processed;
			
			if(responseText) {
				if(response.result.replaceText) {
					data = responseText;
				} else {
					data = query + " " + responseText;
				}
			}
		} else {
			errorMessages = (response.msg).replaceAll('<br>','\n');
		}
		
		if(data) {
			if ($field.attr('data-type') == 'craft\\redactor\\Field') {
				let textareaId = input.attr('id');
				$R('#' + textareaId, 'source.setCode', data);

			} else if ($field.attr('data-type') == 'craft\\ckeditor\\Field') {
				let ckEditorInstance = $field.find(".input .ck-editor__editable")[0].ckeditorInstance;
				ckEditorInstance.setData(data);		

			} else {
				input.val(data);
			}
		}

		if(errorMessages) {
			alert(errorMessages);
		}
	});
});