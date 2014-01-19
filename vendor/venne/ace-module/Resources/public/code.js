$(function () {

	$.nette.ext('codemirror', {
		success: function (payload) {
			if (!payload.snippets) {
				return;
			}

			var _this = this;
			for (var i in payload.snippets) {
				$('#' + i).each(function () {
					_this.init($(this));
				});
			}
		}
	}, {
		init: function(target) {
			target.find(this.selector).each(function () {
				var textarea = $(this);
				$(this).before('<div id="' + textarea.attr('id') + '__ace" style="position: relative; height: 450px; overflow: inherit;" />');
				var editor = ace.edit(textarea.attr('id') + '__ace');
				editor.getSession().setValue(textarea.val());
				editor.getSession().on('change', function(){
					textarea.val(editor.getSession().getValue());
				});
				textarea.hide();
			});
		},
		selector: 'textarea[venne-form-code]'
	});
	$.nette.ext('codemirror').init($('body'));

});