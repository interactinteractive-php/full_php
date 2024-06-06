(function (tinymce) {
    tinymce.PluginManager.add('lineheight', function (editor, url, $) {

        editor.on('init', function () {
            editor.formatter.register({
                lineheight: {inline: 'span', styles: {'line-height': '%value'}}
            });
        });

        editor.addButton('lineheightselect', function() {
            var items = [], defaultLineHeightFormats = '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px';
            var lineheight_formats = editor.settings.lineheight_formats || defaultLineHeightFormats;
            lineheight_formats.split(' ').forEach(function(item) {
                var text = item, value = item;
                // Allow text=value for line-height formats
                var values = item.split('=');
                if (values.length > 1) {
                    text = values[0];
                    value = values[1];
                }
                items.push({text: text, value: value});
            });
            return {
                type: 'listbox',
                text: 'Line Height',
                tooltip: 'Line Height',
                values: items,
                fixedWidth: true,
                onPostRender: function() {
                    var self = this;
                    editor.on('nodeChange', function(e) {
                        var formatName = 'lineheight';
                        var formatter = editor.formatter;
                        var value = null;
                        e.parents.forEach(function(node) {
                            items.forEach(function(item) {
                                if (formatName) {
                                    if (formatter.matchNode(node, formatName, {value: item.value})) {
                                        value = item.value;
                                    }
                                } else {
                                    if (formatter.matchNode(node, item.value)) {
                                        value = item.value;
                                    }
                                }
                                if (value) {
                                    return false;
                                }
                            });
                            if (value) {
                                return false;
                            }
                        });
                        self.value(value);
                    });
                },
                onselect: function(e) {
                    tinymce.activeEditor.formatter.apply('lineheight', {value : this.value()});
                }
            };
        });
    });

    tinymce.PluginManager.requireLangPack('lineheight', 'de');
})(tinymce);

