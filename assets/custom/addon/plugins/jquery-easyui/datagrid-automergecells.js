$.extend($.fn.datagrid.methods, {
    autoMergeCells: function (jq, fields) {
        return jq.each(function () {
            var target = $(this);
            if (!fields) {
                fields = target.datagrid('getColumnFields');
            }
            var rows = target.datagrid('getRows');
            var i = 0, j = 0, temp = {}, rowsLength = rows.length, fieldsLength = fields.length;
            
            for (i; i < rowsLength; i++) {
                var row = rows[i]; j = 0;
                
                for (j; j < fieldsLength; j++) {
                    var field = fields[j];
                    var tf = temp[field];
                    if (!tf) {
                        tf = temp[field] = {};
                        tf[row[field]] = [i];
                    } else {
                        var tfv = tf[row[field]];
                        if (tfv) {
                            tfv.push(i);
                        } else {
                            tfv = tf[row[field]] = [i];
                        }
                    }
                }
            }
            
            $.each(temp, function (field, column) {
                $.each(column, function () {
                    var group = this;
                    var groupLength = group.length;

                    if (groupLength > 1) {
                        var before, after, mergeIndex = group[0];

                        for (var i = 0; i < groupLength; i++) {
                            
                            before = group[i];
                            after = group[i + 1];
                            if (after && (after - before) == 1) {
                                continue;
                            }
                            var rowspan = before - mergeIndex + 1;
                            
                            if (rowspan > 1) {
                                target.datagrid('mergeCells', {
                                    index: mergeIndex,
                                    field: field,
                                    rowspan: rowspan
                                });
                            }
                            
                            if (after && (after - before) != 1) {
                                mergeIndex = after;
                            }
                        }
                    }
                });
            });
            
        });
    }
});