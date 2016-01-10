if (!RedactorPlugins) var RedactorPlugins = {};

(function ($) {
    RedactorPlugins.customColors = function () {
        return {
            init: function () {
                var colors = [
                    '#999999', '#65870e', '#425909', '#d5f387', '#c4ee59',
                    '#15b3cc', '#108a9e', '#e4f9fc', '#b6eef7', '#d9ad00', '#a68400',
                    '#fff7d9', '#ffeda6',  '#d76644', '#bf4c29', '#c43125', '#99261d', '#f3c5c1', '#eb9d96'
                ];

                var buttons = ['fontCustomColor', 'backCustomColor'];
                var langNames = ['fontcolor', 'backcolor'];
                var buttonsIcon = ['fa-header text-info', 'fa-h-square text-success'];

                for (var i = 0; i < 2; i++) {
                    var name = buttons[i];
                    var icon = buttonsIcon[i];
                    var langName = langNames[i];

                    var button = this.button.add(name, this.lang.get(langName));
                    this.button.setAwesome(name, icon);
                    var $dropdown = this.button.addDropdown(button);

                    $dropdown.width(242);
                    this.customColors.buildPicker($dropdown, name, colors);

                }

            },
            buildPicker: function ($dropdown, name, colors) {
                var rule = (name == 'backCustomColor') ? 'background-color' : 'color';

                var len = colors.length;
                var self = this;
                var func = function (e) {
                    e.preventDefault();
                    self.customColors.set($(this).data('rule'), $(this).attr('rel'));
                };

                for (var z = 0; z < len; z++) {
                    var color = colors[z];

                    var $swatch = $('<a rel="' + color + '" data-rule="' + rule + '" href="#" style="float: left; font-size: 0; border: 2px solid #fff; padding: 0; margin: 0; width: 22px; height: 22px;"></a>');
                    $swatch.css('background-color', color);
                    $swatch.on('click', func);

                    $dropdown.append($swatch);
                }

                var $elNone = $('<a href="#" style="display: block; clear: both; padding: 5px; font-size: 12px; line-height: 1;"></a>').html(this.lang.get('none'));
                $elNone.on('click', $.proxy(function (e) {
                    e.preventDefault();
                    this.customColors.remove(rule);

                }, this));

                $dropdown.append($elNone);
            },
            set: function (rule, type) {
                this.inline.format('span', 'style', rule + ': ' + type + ';');
            },
            remove: function (rule) {
                this.inline.removeStyleRule(rule);
            }
        };
    };
})(jQuery);