if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.clips = function()
{
	return {
		init: function()
		{
			var items = [
				['Container', '<div class="container">container</div>'],
				['Row 1col', '<div class="row"><div class="col-lg-12">1</div></div>'],
				['Row 3cols', '<div class="row"><div class="col-lg-4">1</div><div class="col-lg-4">2</div><div class="col-lg-4">3</div></div>'],
				['Row 4cols', '<div class="row"><div class="col-lg-3">1</div><div class="col-lg-3">2</div><div class="col-lg-3">3</div><div class="col-lg-3">4</div></div>'],
				['Row 2cols', '<div class="row"><div class="col-lg-6">1</<div><div class="col-lg-6">2</div></div>']
			];

			this.clips.template = $('<ul id="redactor-modal-list">');

			for (var i = 0; i < items.length; i++)
			{
				var li = $('<li>');
				var a = $('<a href="#" class="redactor-clip-link">').text(items[i][0]);
				var div = $('<div class="redactor-clip">').hide().html(items[i][1]);

				li.append(a);
				li.append(div);
				this.clips.template.append(li);
			}

			this.modal.addTemplate('clips', '<section>' + this.utils.getOuterHtml(this.clips.template) + '</section>');

			var button = this.button.add('clips', 'Clips');
			this.button.addCallback(button, this.clips.show);

		},
		show: function()
		{
			this.modal.load('clips', 'Insert Clips', 400);

			this.modal.createCancelButton();

			$('#redactor-modal-list').find('.redactor-clip-link').each($.proxy(this.clips.load, this));

			this.selection.save();
			this.modal.show();
		},
		load: function(i,s)
		{
			$(s).on('click', $.proxy(function(e)
			{
				e.preventDefault();
				this.clips.insert($(s).next().html());

			}, this));
		},
		insert: function(html)
		{
			this.selection.restore();
			this.insert.html(html, false);
			this.modal.close();
			this.observe.load();
		}
	};
};

