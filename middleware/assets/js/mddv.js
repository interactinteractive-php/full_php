function gridNumField(val, row) {
    if (val !== '' && val !== undefined || val !== 'undefined') {
        return '<span class="num-field">' + val + '</span>';
    }
    return '';
}
function gridBooleanField(val, row) {
    if (val !== '' && (val !== undefined || val !== 'undefined') && val == '1') {
        return '<i class="fa fa-check"></i>';
    }
    return '';
}
function gridPasswordField(val, row) {
    return '******';
}
function gridAmountField(val, row) {
    if (val !== '' && (val !== undefined || val !== 'undefined')) {
        return number_format(val, 2, '.', ','); /* accounting.formatMoney(val, '') */
    }
    return '';
}
function gridAmountNullField(val, row) {
    if (val != '' && val != null) {
        return number_format(val, 2, '.', ',');
    }
    return '';
}
function gridScaleAmountField(val, mdec) {
    if (val !== '' && (val !== undefined || val !== 'undefined')) {
        return number_format(val, mdec.replace('.', '').charAt(0), '.', ','); /* accounting.formatMoney(val, '', mdec.replace('.', '').charAt(0)) */
    }
    return '';
}
function gridScaleAmountNullField(val, mdec) {
    if (val != '' && val != null) {
        return number_format(val, mdec.replace('.', '').charAt(0), '.', ',');
    }
    return '';
}
function gridLongField(val) {
    if (val != '' && val != null) {
        return number_format(val, 0, '.', '');
    }
    return '';
}
function gridIntegerField(val) {
    if (val != '' && val != null) {
        return number_format(val, 0, '.', '');
    }
    return '';
}
function gridPercentField(val) {
    if (val != '' && val != null && (val !== undefined || val !== 'undefined')) {
        return parseFloat(val) + '%';
    }
    return '';
}
function gridFileColumnRenderType(elem, dvId, renderType, showCount, v, r, i, c, opt) {
    if (v != '' && v != null) {   
        if (renderType == 'circlephoto') {
            return gridFileCirclePhoto(elem, dvId, showCount, v, r, i, c, opt);
        } else {
            return gridFileField(v, r);
        }
    }
    return '';
}
function gridFileCirclePhoto(elem, dvId, showCount, v, r, i, c, opt) {
    var namesArr = (v+'').split('|'), namesArrLength = namesArr.length, 
        img = [], field = elem.field, photosArr = [], showCount = showCount ? Number(showCount) : 2, 
        isIgnoreLink = false;
    
    if (r.hasOwnProperty(field + '_photo') && r[field + '_photo']) {
        photosArr = (r[field + '_photo']).split(',');
    }
    
    if (typeof opt != 'undefined' && opt.isIgnoreLink) {
        isIgnoreLink = true;
    }
    
    for (var i in namesArr) {
        
        var customStyle = (i == 0) ? '' : 'margin-left:-10px';
        
        if (photosArr.length && typeof photosArr[i] !== 'undefined') {
            if (isIgnoreLink) {
                img.push('<img src="api/image_thumbnail?width=30&src='+photosArr[i]+'" data-url="'+photosArr[i]+'" class="rounded-circle mr3 cursor-pointer" width="30" height="30" onerror="onUserImgError(this);" title="'+namesArr[i]+'" style="'+customStyle+'">');
            } else {
                img.push('<img src="api/image_thumbnail?width=30&src='+photosArr[i]+'" data-url="'+photosArr[i]+'" class="rounded-circle mr3 cursor-pointer" width="30" height="30" onclick="bpFilePreview(this);" onerror="onUserImgError(this);" title="'+namesArr[i]+'" style="'+customStyle+'">');
            }
        } else {
            img.push('<img src="#" class="rounded-circle mr3" width="30" height="30" onerror="onUserImgError(this);" title="'+namesArr[i]+'" style="'+customStyle+'">');
        }
        
        if (namesArrLength > showCount && i == (showCount - 1)) {
            if (isIgnoreLink) {
                img.push('<span class="d-inline-block rounded-circle" style="background-color:#eee;padding:6px 0 0 0;width:30px;height:30px;text-align:center;color:#000;margin-left:-10px">+'+(namesArrLength - showCount)+'</span>');
            } else {
                img.push('<a href="javascript:;" class="d-inline-block rounded-circle" style="background-color:#eee;padding:6px 0 0 0;width:30px;height:30px;text-align:center;color:#000;margin-left:-10px">+'+(namesArrLength - showCount)+'</a>');
            }
            break;
        }
    }
    
    return img.join('');
}
function gridFileField(val, row) {
    if (val !== '' && typeof val !== 'undefined' && val !== null) {
        
        val = val.toString();
        var splitedVal = val.split(','), firstVal = splitedVal[0].trim();
        
        if (isNumeric(firstVal)) {
            
            var fieldName = this.field, tabName = '', labelName = '<i class="icon-file-eye2 font-size-20"></i>';
            var usestampedfileview = '';
            if (row.hasOwnProperty(fieldName + '_tabname')) {
                tabName = row[fieldName + '_tabname'];
            }

            if (row.hasOwnProperty('usestampedfileview')) {
                usestampedfileview = '1';
            }
            
            if (row.hasOwnProperty(fieldName + '_labelname') && row[fieldName + '_labelname']) {
                labelName = row[fieldName + '_labelname'];
            }
            
            return '<a href="javascript:;" onclick="bpContentViewerById(this, \'\', \'' + val + '\', {tabName: \''+tabName+'\'}, \'' + usestampedfileview + '\');" title="'+plang.get('file_view')+'">'+labelName+'</a>';
        }
        
        var fieldName = this.field, 
            ext = ['jpg', 'jpeg', 'png', 'gif', 'tif', 'tiff'], 
            fileViewLabel = plang.get('see_btn'), 
            isFileNameShow = true, 
            contentId = '';
        
        if (row.hasOwnProperty(fieldName+'_isignorefilename') && row[fieldName+'_isignorefilename'] == '1') {
            isFileNameShow = false;
            fileViewLabel = '';
        }
        
        if (row.hasOwnProperty('contentid')) {
            contentId = row.contentid;
        }
        
        if (splitedVal.length > 1) {
            
            var tmpVal = '', isCustomFileName = false, isSplitedEcmContentId = false;
            
            if (isFileNameShow && row.hasOwnProperty('attachfilename') && row.attachfilename) {
                var splitedFileName = (row.attachfilename).split(',');
                isCustomFileName = true;
            }
            
            if (row.hasOwnProperty(fieldName+'_ecmcontentid')) {
                var splitedEcmContentId = (row[fieldName+'_ecmcontentid']).split(',');
                isSplitedEcmContentId = true;
            } 
            
            $.each(splitedVal, function (key, value) {
                
                value = value.trim();
                
                if (value !== '') {
                    
                    var fileViewLabelRename = fileViewLabel;
                    var lowerExtension = value.split('.').pop().toLowerCase();
                    var ecmContentId = contentId;
                    
                    if (['mp4', 'ogg', 'avi', 'mov', 'm4p', 'm4v'].indexOf(lowerExtension) !== -1) {
                        fileViewLabel = plang.get('show_btn');
                    } else if (['mp3'].indexOf(lowerExtension) !== -1) {
                        fileViewLabel = plang.get('Сонсох');
                    }
                    
                    if (isCustomFileName && typeof splitedFileName[key] != 'undefined') {
                        
                        fileViewLabelRename = (splitedFileName[key]).trim();
                        
                        if (!fileViewLabelRename) {
                            fileViewLabelRename = fileViewLabel;
                        }
                    }
                    
                    if (isSplitedEcmContentId && typeof splitedEcmContentId[key] != 'undefined') {
                        ecmContentId = (splitedEcmContentId[key]).trim();
                    }
                    
                    var opts = {ext: ext, val: value, fileViewLabel: fileViewLabelRename, contentid: ecmContentId};
            
                    if (row.hasOwnProperty(fieldName+'_isignoredownload')) {
                        opts.isIgnoreDownload = row[fieldName+'_isignoredownload'];
                    }
                    
                    tmpVal += getGridFileFieldByType(opts);
                    
                    if (isCustomFileName) {
                        tmpVal += '<br />';
                    }
                }
            });
            
            return tmpVal;
            
        } else {
            
            var lowerExtension = val.split('.').pop().toLowerCase();
                    
            if (['mp4', 'ogg', 'avi', 'mov', 'm4p', 'm4v'].indexOf(lowerExtension) !== -1) {
                fileViewLabel = plang.get('show_btn');
            } else if (['mp3'].indexOf(lowerExtension) !== -1) {
                fileViewLabel = plang.get('Сонсох');
            }
                    
            if (isFileNameShow && row.hasOwnProperty('attachfilename') && row.attachfilename) {
                fileViewLabel = row.attachfilename;
            }
            
            var ecmContentId = '';
            
            if (row.hasOwnProperty(fieldName+'_ecmcontentid')) {
                ecmContentId = row[fieldName+'_ecmcontentid'];
            } else if (row.hasOwnProperty('contentid')) {
                ecmContentId = row.contentid;
            }
            
            var opts = {ext: ext, val: val, fileViewLabel: fileViewLabel, contentid: ecmContentId};
            
            if (row.hasOwnProperty(fieldName+'_isignoredownload')) {
                opts.isIgnoreDownload = row[fieldName+'_isignoredownload'];
            }
            if (row.hasOwnProperty(fieldName+'_isignoretoolbarprint')) {
                opts.isIgnoreToolbarPrint = row[fieldName+'_isignoretoolbarprint'];
            }
            if (row.hasOwnProperty(fieldName+'_printlogprocess')) {
                opts.printLogProcess = row[fieldName+'_printlogprocess'];
            }
            
            return getGridFileFieldByType(opts);
        }
    }
    
    return '';
}
function gridFileOnlyIconField(val, row, isCustomerField) {
    if (val !== '' && typeof val !== 'undefined' && val !== null) {
        
        var fieldName = this.field;
        var ext = ['jpg', 'jpeg', 'png', 'gif', 'tif', 'tiff'], splitedVal = val.split(',');
        var fileViewLabel = 'Харах';
        var isFileNameShow = true;
        
        if (row.hasOwnProperty(fieldName+'_isignorefilename') && row[fieldName+'_isignorefilename'] == '1') {
            isFileNameShow = false;
            fileViewLabel = '';
        }
        
        if (splitedVal.length > 1) {
            
            var tmpVal = '', isCustomFileName = false;
            
            if (isFileNameShow && row.hasOwnProperty('attachfilename') && row.attachfilename) {
                var splitedFileName = (row.attachfilename).split(',');
                isCustomFileName = true;
            }
            
            $.each(splitedVal, function (key, value) {
                
                value = value.trim();
                
                if (value !== '') {
                    
                    var fileViewLabelRename = fileViewLabel;
                    
                    if (isCustomFileName && typeof splitedFileName[key] != 'undefined') {
                        
                        fileViewLabelRename = (splitedFileName[key]).trim();
                        
                        if (!fileViewLabelRename) {
                            fileViewLabelRename = fileViewLabel;
                        }
                    }
                    
                    var opts = {ext: ext, val: value, fileViewLabel: fileViewLabelRename, contentid: row.contentid, isicon: 'icon'};
            
                    if (row.hasOwnProperty(fieldName+'_isignoredownload')) {
                        opts.isIgnoreDownload = row[fieldName+'_isignoredownload'];
                    }
                    
                    tmpVal += getGridFileFieldByType(opts);
                    
                    if (isCustomFileName && typeof isCustomerField === 'undefined') {
                        tmpVal += '<br />';
                    }
                }
            });
            
            return tmpVal;
            
        } else {
            
            if (isFileNameShow && row.hasOwnProperty('attachfilename') && row.attachfilename) {
                fileViewLabel = row.attachfilename;
            }
            
            var ecmContentId = '';
            
            if (row.hasOwnProperty(fieldName+'_ecmcontentid')) {
                ecmContentId = row[fieldName+'_ecmcontentid'];
            } else if (row.hasOwnProperty('contentid')) {
                ecmContentId = row.contentid;
            }
            
            var opts = {ext: ext, val: val, fileViewLabel: fileViewLabel, contentid: ecmContentId, isicon: 'icon'};
            
            if (row.hasOwnProperty(fieldName+'_isignoredownload')) {
                opts.isIgnoreDownload = row[fieldName+'_isignoredownload'];
            }
            
            return getGridFileFieldByType(opts);
        }
    }
    
    return '';
}
function gridFileTabViewField(val, row) {
    if (val !== '' && typeof val !== 'undefined' && val !== null) {
        
        var ext = ['jpg', 'jpeg', 'png', 'gif'], splitedVal = val.split(',');
        var fileViewLabel = 'Харах';
        
        if (splitedVal.length > 1) {
            
            var tmpVal = '', isCustomFileName = false, n = 1;
            
            if (row.hasOwnProperty('attachfilename') && row.attachfilename) {
                var splitedFileName = (row.attachfilename).split(',');
                isCustomFileName = true;
            }
            
            $.each(splitedVal, function (key, value) {
                
                value = value.trim();
                
                if (value !== '') {
                    
                    var fileViewLabelRename = fileViewLabel;
                    var tabName = '';
                    
                    if (isCustomFileName && typeof splitedFileName[key] != 'undefined') {
                        
                        fileViewLabelRename = (splitedFileName[key]).trim();
                        
                        if (!fileViewLabelRename) {
                            fileViewLabelRename = fileViewLabel;
                        }
                    }
                    
                    if (row.hasOwnProperty('contractcode') && row.contractcode) {
                        tabName = row.contractcode + ' - ' + n;
                    } else if (row.hasOwnProperty('contentid') && row.contentid) {
                        tabName = row.contentid + ' - ' + n;
                    } else if (row.hasOwnProperty('id') && row.id) {
                        tabName = row.id + ' - ' + n;
                    }
                    
                    tmpVal += getGridFileTabViewFieldByType(tabName, ext, value, fileViewLabelRename, row.contentid);
                    
                    if (isCustomFileName) {
                        tmpVal += '<br />';
                    }
                    
                    n++;
                }
            });
            
            return tmpVal;
            
        } else {
            
            var tabName = '';
            
            if (row.hasOwnProperty('attachfilename') && row.attachfilename) {
                fileViewLabel = row.attachfilename;
            }
            
            if (row.hasOwnProperty('contractcode') && row.contractcode) {
                tabName = row.contractcode;
            } else if (row.hasOwnProperty('contentid') && row.contentid) {
                tabName = row.contentid;
            } else if (row.hasOwnProperty('id') && row.id) {
                tabName = row.id;
            }
            
            return getGridFileTabViewFieldByType(tabName, ext, val, fileViewLabel, row.contentid);
        }
    }
    
    return '';
}
function gridFileUrlField(val, row) {
    if (val !== '' && typeof val !== 'undefined' && val !== null) {
        
        val = val.toString();
        var splitedVal = val.split(',');
        
        var fieldName = this.field, 
            ext = ['jpg', 'jpeg', 'png', 'gif', 'tif', 'tiff'], 
            fileViewLabel = plang.get('see_btn'), 
            isFileNameShow = true, 
            contentId = '';
        
        if (row.hasOwnProperty(fieldName+'_isignorefilename') && row[fieldName+'_isignorefilename'] == '1') {
            isFileNameShow = false;
            fileViewLabel = '';
        }
        
        if (splitedVal.length > 1) {
            
            var tmpVal = '', isCustomFileName = false, isSplitedEcmContentId = false;
            
            if (isFileNameShow && row.hasOwnProperty('attachfilename') && row.attachfilename) {
                var splitedFileName = (row.attachfilename).split(',');
                isCustomFileName = true;
            }
            
            if (row.hasOwnProperty(fieldName+'_ecmcontentid')) {
                var splitedEcmContentId = (row[fieldName+'_ecmcontentid']).split(',');
                isSplitedEcmContentId = true;
            } 
            
            $.each(splitedVal, function (key, value) {
                
                value = value.trim();
                
                if (value !== '') {
                    
                    var fileViewLabelRename = fileViewLabel;
                    var lowerExtension = value.split('.').pop().toLowerCase();
                    var ecmContentId = contentId;
                    
                    if (['mp4', 'ogg', 'avi', 'mov', 'm4p', 'm4v'].indexOf(lowerExtension) !== -1) {
                        fileViewLabel = plang.get('show_btn');
                    } else if (['mp3'].indexOf(lowerExtension) !== -1) {
                        fileViewLabel = plang.get('Сонсох');
                    } else {
                        lowerExtension = 'png';
                    }
                    
                    if (isCustomFileName && typeof splitedFileName[key] != 'undefined') {
                        
                        fileViewLabelRename = (splitedFileName[key]).trim();
                        
                        if (!fileViewLabelRename) {
                            fileViewLabelRename = fileViewLabel;
                        }
                    }
                    
                    if (isSplitedEcmContentId && typeof splitedEcmContentId[key] != 'undefined') {
                        ecmContentId = (splitedEcmContentId[key]).trim();
                    }
                    
                    var opts = {ext: ext, val: value, fileViewLabel: fileViewLabelRename, contentid: ecmContentId};
            
                    if (row.hasOwnProperty(fieldName+'_isignoredownload')) {
                        opts.isIgnoreDownload = row[fieldName+'_isignoredownload'];
                    }
                    
                    tmpVal += getGridFileFieldByType(opts);
                    
                    if (isCustomFileName) {
                        tmpVal += '<br />';
                    }
                }
            });
            
            return tmpVal;
            
        } else {
            
            var lowerExtension = val.split('.').pop().toLowerCase();
                    
            if (['mp4', 'ogg', 'avi', 'mov', 'm4p', 'm4v'].indexOf(lowerExtension) !== -1) {
                fileViewLabel = plang.get('show_btn');
            } else if (['mp3'].indexOf(lowerExtension) !== -1) {
                fileViewLabel = plang.get('Сонсох');
            } else {
                lowerExtension = 'png';
            }
                    
            if (isFileNameShow && row.hasOwnProperty('attachfilename') && row.attachfilename) {
                fileViewLabel = row.attachfilename;
            }
            
            var ecmContentId = '';
            
            if (row.hasOwnProperty(fieldName+'_ecmcontentid')) {
                ecmContentId = row[fieldName+'_ecmcontentid'];
            } else if (row.hasOwnProperty('contentid')) {
                ecmContentId = row.contentid;
            }
            
            var opts = {ext: ext, val: val, fileViewLabel: fileViewLabel, contentid: ecmContentId, realextension: lowerExtension};
            
            if (row.hasOwnProperty(fieldName+'_isignoredownload')) {
                opts.isIgnoreDownload = row[fieldName+'_isignoredownload'];
            }
            if (row.hasOwnProperty(fieldName+'_isignoretoolbarprint')) {
                opts.isIgnoreToolbarPrint = row[fieldName+'_isignoretoolbarprint'];
            }
            if (row.hasOwnProperty(fieldName+'_printlogprocess')) {
                opts.printLogProcess = row[fieldName+'_printlogprocess'];
            }
            
            return getGridFileFieldByType(opts);
        }
    }
    
    return '';
}
function gridStarField(val, row) {
    if (val == 'nostar') {
        return '';
    }

    var star = '', s = val;
    for (var i = 1; i <= 5; i++) {
        if (i <= s) {
            star += '<li data-id="' + i + '" title="' + i + '"><i class="icon-star-full2" style="color: orange; cursor: pointer;"></i></li>';
        } else {
            star += '<li data-id="' + i + '" title="' + i + '"><i class="icon-star-empty3" style="color: #ccc; cursor: pointer;"></i></li>';
        }
    }
    return '<ul class="nav navbar-nav dv-star-rating d-flex flex-row justify-content-center">' + star + '</ul>';
}
function gridNumberToTime(minutes, row) {
    if (minutes !== '' && minutes !== null && minutes !== 0 && minutes !== '0' && (minutes !== undefined || minutes !== 'undefined')) {
        return bpNumberToTime(minutes);
    }
    return '';
}
function gridHtmlDecode(htmlstring, row) {
    if (htmlstring !== '' && htmlstring !== null && (htmlstring !== undefined || htmlstring !== 'undefined')) {
        return html_entity_decode(htmlstring, "ENT_QUOTES");
    }
    return '';
}
function getGridFileFieldByType(opts) {
    
    var isFullUrl = false;
    if (opts.hasOwnProperty('realextension')) {
        var lowerExtension = opts.realextension;
        isFullUrl = true;
    } else {
        var lowerExtension = (opts.val).split('.').pop().toLowerCase();
    }
    
    if ((opts.ext).indexOf(lowerExtension) !== -1) {
        if (isFullUrl) {
            return '<a href="' + opts.val + '" class="fancybox-button" data-fancybox="images" data-rel="fancybox-button" data-contentid="'+opts.contentid+'"><img src="' + opts.val + '" class="rounded-circle dataview-list-icon" onerror="onUserImgError(this);" height="32" width="32"></a> ';
        } else if (typeof (opts.isicon) !== 'undefined' && opts.isicon == 'icon') {
            return '<a href="' + opts.val + '" class="fancybox-button p-0" data-rel="fancybox-button" data-contentid="'+opts.contentid+'"><i class="icon-file-picture text-danger-400" style="font-size: 13.9px;"></i><div class="d-none"><img src="api/image_thumbnail?width=32&src=' + opts.val + '" class="rounded-circle dataview-list-icon" onerror="onUserImgError(this);" height="32" width="32"></div></a> ';
        } else {
            return '<a href="' + opts.val + '" class="fancybox-button" data-fancybox="images" data-rel="fancybox-button" data-contentid="'+opts.contentid+'"><img src="api/image_thumbnail?width=32&src=' + opts.val + '" class="rounded-circle dataview-list-icon" onerror="onUserImgError(this);" height="32" width="32"></a> ';
        }
    
    } else {
        var lowerVal = (opts.val).toLowerCase();
        
        if (lowerVal.indexOf("c:\\") !== -1 || lowerVal.indexOf("d:\\") !== -1) {
            if (typeof isicon !== 'undefined' && isicon === 'icon') {
                return '<a href="file:///' + val + '" target="_blank"><i class="fa fa-download"></i></a>';
            } else {
                return '<a href="file:///' + val + '" target="_blank"><i class="fa fa-download"></i> Файл татах</a>';
            }
        } else if (lowerVal.indexOf('http') !== -1 || lowerVal.indexOf('https') !== -1) {
            if (typeof (opts.isicon) !== 'undefined' && opts.isicon === 'icon') {
                return '<a href="' + opts.val + '" target="_blank"><i class="fa fa-download"></i></a>';
            } else {
                return '<a href="' + opts.val + '" target="_blank"><i class="fa fa-download"></i> Файл татах</a>';
            }
        } else if (lowerExtension == 'dcm') {
        
            return '<a href="javascript:;" onclick="dataViewDcmFileViewer(this, \'' + lowerExtension + '\', \'' + opts.val + '\');"><i class="fa fa-file-text"></i>'+opts.fileViewLabel+'</a> ';

        } else if (['pdf', 'doc', 'docx', 'xls', 'xlsx', 'html'].indexOf(lowerExtension) !== -1) {

            var iconClass = 'fa-file-o';

            if (lowerExtension == 'pdf') {
                iconClass = 'fa-file-pdf-o fa-pdf-color';
            } else if (lowerExtension == 'xls' || lowerExtension == 'xlsx') {
                iconClass = 'fa-file-excel-o fa-excel-color';
            } else if (lowerExtension == 'ppt' || lowerExtension == 'pptx') {
                iconClass = 'fa-file-powerpoint-o fa-powerpoint-color';
            } else if (lowerExtension == 'doc' || lowerExtension == 'docx') {
                iconClass = 'fa-file-word-o';
            } else if (lowerExtension == 'html') {
                iconClass = 'fa-file-code-o';
            }
            
            var printOpts = 'rowId: 1, fileExtension: \'' + lowerExtension + '\', fileName: \'' + opts.val + '\', fullPath: \'' + URL_APP + opts.val + '\', contentId: \'' + opts.contentid + '\'';
            
            if (opts.hasOwnProperty('isIgnoreDownload')) {
                printOpts += ', isIgnoreDownload: ' + opts.isIgnoreDownload;
            }
            if (opts.hasOwnProperty('isIgnoreToolbarPrint')) {
                printOpts += ', isIgnoreToolbarPrint: ' + opts.isIgnoreToolbarPrint;
            }
            if (opts.hasOwnProperty('printLogProcess')) {
                printOpts += ', printLogProcess: \'' + opts.printLogProcess + '\'';
            }
            
            if (typeof (opts.isicon) !== 'undefined' && opts.isicon === 'icon') {
                return '<a href="javascript:;" onclick="dataViewFileViewerByOpts(this, {'+printOpts+'});"><i class="fa ' + iconClass + '"></i></a>';
            } else {
                return '<a href="javascript:;" onclick="dataViewFileViewerByOpts(this, {'+printOpts+'});"><i class="fa ' + iconClass + '"></i> '+opts.fileViewLabel+'</a>';
            }

        } else if (['mp4', 'ogg', 'avi', 'mov', 'm4p', 'm4v', 'mp3'].indexOf(lowerExtension) !== -1) {
            
            var iconClass = 'fa-video-camera';

            if (lowerExtension == 'mp3') {
                iconClass = 'fa-volume-up';
            }
            
            if (typeof (opts.isicon) !== 'undefined' && opts.isicon === 'icon') {
                return '<a href="' + URL_APP + opts.val + '" data-fancybox data-width="840" data-height="560"><i class="fa ' + iconClass + '"></i></a>';
            } else {
                return '<a href="' + URL_APP + opts.val + '" data-fancybox data-width="840" data-height="560"><i class="fa ' + iconClass + '"></i> '+opts.fileViewLabel+'</a>';
            }
            
        } else {
            if (typeof (opts.isicon) !== 'undefined' && opts.isicon === 'icon') {
                return '<a href="mdobject/downloadFile?file=' + opts.val + '&contentId=' + opts.contentid + '&fDownload=1"><i class="fa fa-download"></i></a>';
            } else {
                return '<a href="mdobject/downloadFile?file=' + opts.val + '&contentId=' + opts.contentid + '&fDownload=1"><i class="fa fa-download"></i> Файл татах</a>';
            }
        }
    }
    return '';
}
function getGridFileTabViewFieldByType(tabName, ext, val, fileViewLabel, contentid) {
    var lowerExtension = val.split('.').pop().toLowerCase();

    if (ext.indexOf(lowerExtension) !== -1) {
        return '<a href="' + URL_APP + val + '" class="fancybox-button" data-fancybox="images" data-rel="fancybox-button"><img src="' + URL_APP + val + '" class="rounded-circle dataview-list-icon" onerror="onUserImgError(this);" height="32" width="32"></a> ';
    } else {
        var lowerVal = val.toLowerCase();
        
        if (lowerVal.indexOf("c:\\") !== -1 || lowerVal.indexOf("d:\\") !== -1) {
            return '<a href="file:///' + val + '" target="_blank"><i class="fa fa-download"></i> Файл татах</a>';
        } else if (lowerVal.indexOf('http') !== -1 || lowerVal.indexOf('https') !== -1) {
            return '<a href="' + val + '" target="_blank"><i class="fa fa-download"></i> Файл татах</a>';
        } else {
            if (['pdf', 'doc', 'docx', 'xls', 'xlsx'].indexOf(lowerExtension) !== -1) {

                var iconClass = 'fa-file-o';

                if (lowerExtension == 'pdf') {
                    iconClass = 'fa-file-pdf-o fa-pdf-color';
                } else if (lowerExtension == 'xls' || lowerExtension == 'xlsx') {
                    iconClass = 'fa-file-excel-o fa-excel-color';
                } else if (lowerExtension == 'ppt' || lowerExtension == 'pptx') {
                    iconClass = 'fa-file-powerpoint-o fa-powerpoint-color';
                } else if (lowerExtension == 'doc' || lowerExtension == 'docx') {
                    iconClass = 'fa-file-word-o';
                }
                
                var printOpts = '{tabName: \''+tabName+'\', rowId: \''+getUniqueId(1)+'\', fileExtension: \'' + lowerExtension + '\', fileName: \'' + val + '\', fullPath: \'' + URL_APP + val + '\', contentId: \'' + contentid + '\'}';

                return '<a href="javascript:;" onclick="dataViewFileTabViewer(this, '+printOpts+');"><i class="fa ' + iconClass + '"></i> '+fileViewLabel+'</a> ';
            } else {
                return '<a href="mdobject/downloadFile?file=' + val + '&contentId=' + contentid + '&fDownload=1"><i class="fa fa-download"></i> Файл татах</a> ';
            }
        }
    }
    return '';
}
function dataViewDcmFileViewer(elem, ext, physicalpath) {
    Core.blockUI({
        boxed: true, 
        message: 'Loading...'
    });

    if ("WebSocket" in window) {
        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function () {
            ws.send('{"command":"view_mri", details: [{"key": "url", "value": "'+URL_APP+physicalpath+'"}]}');
        };

        ws.onmessage = function (evt) {
            PNotify.removeAll();
            
            var response = JSON.parse(evt.data);
            
            if (response.hasOwnProperty('status') && response.hasOwnProperty('description') && response.status == 'error') {
                new PNotify({
                    title: 'Error',
                    text: response.description, 
                    type: 'error',
                    sticker: false
                });
            }
            
            Core.unblockUI();
        };

        ws.onerror = function (event) {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: (typeof event.code == 'undefined' ? 'Client асаагүй байна' : event.code), 
                type: 'error',
                sticker: false
            });
            Core.unblockUI();
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
            Core.unblockUI();
        };
        
    } else {
        
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: 'WebSocket NOT supported by your Browser!', 
            type: 'error',
            sticker: false
        });
        
        Core.unblockUI();
    }
}
function getDataGridSortFields(element) {
    var sortFields = '';
    $(".datagrid-sort-asc", element).each(function () {
        var $thisAscField = $(this);
        sortFields += $thisAscField.parent().attr("field") + "=asc&";
    });
    $(".datagrid-sort-desc", element).each(function () {
        var $thisDescField = $(this);
        sortFields += $thisDescField.parent().attr("field") + "=desc&";
    });
    return sortFields;
}
function dataViewCardViewPhoto(photoField, defaultImage) {
    return defaultImage;
}
function onDataViewImgError(source) {
    var imgUrl = $(source).attr('data-default-image');
    /*if ((/\.(png|gif|jpeg|pjpeg|jpg|x-png|bmp)$/i).test(imgUrl)) {
        source.src = imgUrl;
    }*/
    source.src = imgUrl;
    source.onerror = '';
    return true;
}
function initFileViewer(elem, opts, callback) {

    Core.blockUI({message: 'Loading...', boxed: true});
    
    var isTab = (opts.hasOwnProperty('tabName') && opts.tabName != '') ? true : false;
    var isIgnoreDownload = (opts.hasOwnProperty('isIgnoreDownload') && opts.isIgnoreDownload == '1') ? 1 : 0;
    var isIgnoreToolbarPrint = (opts.hasOwnProperty('isIgnoreToolbarPrint') && opts.isIgnoreToolbarPrint == '1') ? 1 : 0;
    var printLogProcess = (opts.hasOwnProperty('printLogProcess') && opts.printLogProcess) ? opts.printLogProcess : null;

    setTimeout(function () {
        var selectedRows = getDataViewSelectedRows(opts.dvId);
        var selectedRow = selectedRows[0];

        $.ajax({
            type: 'post',
            url: 'mdpreview/fileViewer',
            data: {
                rowId: opts.rowId,
                fileExtension: opts.fileExtension,
                fileName: opts.fileName,
                fullPath: opts.fullPath,
                contentId: opts.contentId,
                dvId: opts.dvId,
                refStructureId: opts.refStructureId,
                selectedRow: selectedRow, 
                isTab: isTab, 
                isIgnoreDownload: isIgnoreDownload, 
                isIgnoreToolbarPrint: isIgnoreToolbarPrint
            },
            dataType: 'json',
            success: function (data) {
                
                if (typeof callback !== 'undefined') {
                    if (typeof window[callback] === 'function') {
                        window[callback](data);
                        return;
                    }
                    
                    if (typeof callback === 'function') {
                        callback(data);
                        return;
                    }
                }
                
                if (isTab) {
                    
                    var tabId = str_replace(Array('\'', '/', '"', "'", '#', ' '), '', opts.tabName);
                    appMultiTabByContent({metaDataId: tabId, title: opts.tabName, type: 'filepreview', content: data.html});
                    
                } else {
                    
                    var $dialogName = 'dialog-fileviewer-' + opts.rowId;
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '" class="dialog-after-save-close"></div>').appendTo('body');
                    }
                    var $dialog = $('#' + $dialogName);
                    var title = 'Preview';
                    
                    if (data.hasOwnProperty('title') && data.title) {
                        title += ' - ' + data.title;
                    }
                    
                    var buttons = [
                        {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ];
                    
                    if (isIgnoreDownload == false && opts.fileExtension != 'html') {
                        
                        if (data.hasOwnProperty('fullPath')) {
                            opts.fullPath = data.fullPath;
                        }
                        
                        buttons.splice(1, 0, {
                            text: plang.get('download_btn'), 
                            class: 'btn blue-hoki btn-sm pull-left', 
                            click: function () {
                                window.location = opts.fullPath;
                            }
                        });
                    }
                    
                    if (printLogProcess && opts.contentId) {
                        
                        buttons.splice(1, 0, {
                            text: plang.get('print_btn'), 
                            class: 'btn blue-hoki btn-sm pull-left', 
                            click: function() {
                                PNotify.removeAll();
                                $.ajax({
                                    type: 'post',
                                    url: 'mdpreview/runPrintLogProcess',
                                    data: {bpCode: printLogProcess, contentId: opts.contentId}, 
                                    dataType: 'json',
                                    beforeSend: function(){
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(dataSub) {
                                        Core.unblockUI();
                                        if (dataSub.status == 'success') {
                                            if (typeof printJS === 'function') {
                                                printJS(opts.fullPath);
                                            } else {
                                                $.cachedScript('assets/custom/addon/plugins/printjs/print.min.js').done(function() { 
                                                    $('head').append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/printjs/print.min.css"/>');
                                                    printJS(opts.fullPath);
                                                });
                                            }
                                        } else {
                                            new PNotify({
                                                title: dataSub.status,
                                                text: dataSub.message, 
                                                type: dataSub.status,
                                                sticker: false
                                            });
                                        }
                                    }
                                });
                            }
                        });
                        
                    } else if (opts.fileExtension != 'html' && opts.fileExtension != 'pdf' && opts.fileExtension != 'ifc') {
                        buttons.splice(1, 0, {
                            text: plang.get('print_btn'), 
                            class: 'btn blue-hoki btn-sm pull-left', 
                            click: function () {
                                
                                if (opts.fileExtension != 'html') {
                                    var iframeEl = document.getElementById('file_viewer_1');
                                    iframeEl.contentWindow.postMessage('print', '*');                                
                                } else {
                                    $dialog.find('.ui-dialog-content').printThis({
                                        debug: false,             
                                        importCSS: true,           
                                        printContainer: false,      
                                        loadCSS: [
                                            URL_APP + 'assets/core/css/core.css', 
                                            URL_APP + 'assets/custom/css/print/print.css'
                                        ],
                                        removeInline: false        
                                    });
                                }
                            }
                        });
                    }

                    $dialog.empty().append(data.html);
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: title,
                        width: 1100,
                        height: 'auto',
                        modal: true,
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: buttons 
                    }).dialogExtend({
                        'closable': true,
                        'maximizable': true,
                        'minimizable': true,
                        'collapsable': true,
                        'dblclick': 'maximize',
                        'minimizeLocation': 'left',
                        'icons': {
                            'close': 'ui-icon-circle-close',
                            'maximize': 'ui-icon-extlink',
                            'minimize': 'ui-icon-minus',
                            'collapse': 'ui-icon-triangle-1-s',
                            'restore': 'ui-icon-newwin'
                        }, 
                        "maximize" : function() { 
                            var $buttons = $dialog.find('.wfm-buttons-preview'), 
                            wfmRowHeight = 7;

                            if ($buttons.length) {
                                wfmRowHeight = 43;
                            }
                            
                            var dialogHeight = $dialog.height() - wfmRowHeight;
                            $dialog.find('#file_viewer_'+opts.rowId).css({"height": dialogHeight + 'px'});
                        }, 
                        "restore" : function() { 
                            var $buttons = $dialog.find('.wfm-buttons-preview'), 
                            wfmRowHeight = 5;

                            if ($buttons.length) {
                                wfmRowHeight = 43;
                            }
                            
                            var dialogHeight = $dialog.height() - wfmRowHeight;
                            $dialog.find('#file_viewer_'+opts.rowId).css({"height": dialogHeight + 'px'});
                        }
                    });
                    $dialog.dialog('open');
                    $dialog.dialogExtend('maximize');
                }

                Core.unblockUI();
            }
        });
    }, 100);
}
function dataViewFileView(v, r, dvId, refStructureId) {
    if (v !== '' && v !== null && v !== 'null') {
        if (typeof r.physicalpath !== 'undefined') {
            var physicalpath = r.physicalpath;

            if (physicalpath !== '' && physicalpath !== null && physicalpath !== 'null') {
                var photoExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'];
                var fileExt = physicalpath.split('.').pop().toLowerCase();

                if (photoExts.indexOf(fileExt) !== -1) {
                    return '<a href="' + URL_APP + physicalpath + '" class="fancybox-button" data-rel="fancybox-button" title="' + v + '">' + v + '</a>';
                } else if (fileExt === 'pdf' || fileExt === 'xls' || fileExt === 'xlsx' || fileExt === 'doc' || fileExt === 'docx' || fileExt === 'ppt' || fileExt === 'pptx') {
                    
                    var printOpts = '{rowId: \''+r.id+'\', fileExtension: \'' + fileExt + '\', fileName: \'' + v + '\', fullPath: \'' + URL_APP + physicalpath + '\', contentId: \'' + r.contentid + '\', dvId: \'' + dvId + '\', refStructureId: \'' + refStructureId + '\'}';
                    return '<a href="javascript:;" onclick="dataViewFileViewerByOpts(this, '+printOpts+');">' + v + '</a>';
                } else {
                    return '<a href="javascript:;">' + v + '</a>';
                }
            }
        }
        return v;
    }
    return '';
}
function dataViewFileViewerByOpts(elem, opts, callback) {
    initFileViewer(elem, opts, callback);
}
function dataViewFileViewer(elem, rowId, fileExtension, fileName, fullPath, contentId, dvId, refStructureId, callback) {
    var opts = {tabName: '', rowId: rowId, fileExtension: fileExtension, fileName: fileName, fullPath: fullPath, contentId: contentId, dvId: dvId, refStructureId: refStructureId};
    initFileViewer(elem, opts, callback);
}
function dataViewFileTabViewer(elem, opts) {
    initFileViewer(elem, opts);
}
function dataViewWfmStatusName(v, r, i, dataViewId, refStructureId) {
    if (v !== '' && v !== 'null' && v !== null && r.hasOwnProperty('wfmstatusid') && r.hasOwnProperty('wfmstatusname')) {
        if (r.hasOwnProperty('wfmignoreclick') && r.wfmignoreclick == '1') {
            return '<a href="javascript:;" class="pf-dvrow-wfm-status"><span class="badge badge-pill" style="background-color: ' + r.wfmstatuscolor + '">' + v + '</span></a>';
        } else {
            return '<a href="javascript:;" onclick="dataViewWfmStatusFlowViewer(this, \'' + r.id + '\', \'' + r.wfmstatusid + '\', \'' + r.wfmstatusname + '\', \'' + dataViewId + '\', \'' + refStructureId + '\', \'' + r.wfmstatuscolor + '\');" class="pf-dvrow-wfm-status"><span class="badge badge-pill" style="background-color: ' + r.wfmstatuscolor + '">' + v + '</span></a>';
        }
    }
    return v;
}
function dataViewWfmStatusButtons(vl, r, i, dataViewId, refStructureId, dataViewCode) {
    var buttons = '';
    if (vl !== '' && vl !== 'null' && vl !== null && (typeof vl === 'object') && vl.length) {
        var icon = '';
        $.each(vl, function (idx, v) {
            if (typeof v.wfmstatusname != 'undefined' && typeof v.processname != 'undefined' && v.processname != '') {
                v.wfmstatusname = v.processname;
            }
            icon = (v.wfmstatusicon ? v.wfmstatusicon : 'fa-cog');
            if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                buttons += '<a href="javascript:;" onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'' + dataViewId + '\', \'' + refStructureId + '\', \'' + v.wfmstatuscolor + '\', \'' + v.wfmstatusname + '\');" id="' + v.wfmstatusid + '" title="' + v.wfmstatusname + '" class="btn btn-xs btn-secondary btn-margin-right"><i class="fa ' + icon + '"></i></a>';
            } else {
                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                    if (v.wfmisneedsign == '1') {
                        buttons += '<a href="javascript:;" onclick="beforeSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'' + dataViewId + '\', \'' + refStructureId + '\', \'' + v.wfmstatuscolor + '\', \'' + v.wfmstatusname + '\');" id="' + v.wfmstatusid + '" title="' + v.wfmstatusname + '" class="btn btn-xs btn-secondary btn-margin-right"><i class="fa ' + icon + '"></i></a>';
                    } else if (v.wfmisneedsign == '2') {
                        buttons += '<a href="javascript:;" onclick="beforeHardSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'' + dataViewId + '\', \'' + refStructureId + '\', \'' + v.wfmstatuscolor + '\', \'' + v.wfmstatusname + '\');" id="' + v.wfmstatusid + '" class="btn btn-xs btn-secondary btn-margin-right"><i class="fa ' + icon + '"></i></a>';
                    } else {
                        buttons += '<a href="javascript:;" onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'' + dataViewId + '\', \'' + refStructureId + '\', \'' + v.wfmstatuscolor + '\', \'' + v.wfmstatusname + '\');" id="' + v.wfmstatusid + '" title="' + v.wfmstatusname + '" class="btn btn-xs btn-secondary btn-margin-right"><i class="fa ' + icon + '"></i></a>';
                    }
                } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {
                    var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : '';
                    var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';
                    if (v.wfmisneedsign == '1') {
                        buttons += '<a href="javascript:;" onclick="transferProcessAction(\'signProcess\', \'' + dataViewId + '\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'' + dataViewCode + '\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=' + dataViewId + '&refStructureId=' + refStructureId + '&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + v.wfmstatuscolor + '&rowId=' + r.id + '\');" title="' + v.wfmstatusname + '" class="btn btn-xs btn-secondary btn-margin-right"><i class="fa ' + icon + '"></i></a>';
                    } else if (v.wfmisneedsign == '2') {
                        buttons += '<a href="javascript:;" onclick="transferProcessAction(\'hardSignProcess\', \'' + dataViewId + '\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'' + dataViewCode + '\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=' + dataViewId + '&refStructureId=' + refStructureId + '&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + v.wfmstatuscolor + '&rowId=' + r.id + '\');" title="' + v.wfmstatusname + '" class="btn btn-xs btn-secondary btn-margin-right"><i class="fa ' + icon + '"></i></a>';
                    } else {
                        buttons += '<a href="javascript:;" onclick="transferProcessAction(\'\', \'' + dataViewId + '\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'' + dataViewCode + '\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=' + dataViewId + '&refStructureId=' + refStructureId + '&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + v.wfmstatuscolor + '&rowId=' + r.id + '\');" title="' + v.wfmstatusname + '" class="btn btn-xs btn-secondary btn-margin-right"><i class="fa ' + icon + '"></i></a>';
                    }
                }
            }
        });
    }
    return buttons;
}
function dvWfmStatusButtonsByJson(vl, r, i, dataViewId, dataViewCode) {
    var buttons = '';

    if (typeof vl != 'undefined' && vl !== '' && vl !== 'null' && vl !== null) {
        
        var pfNextStatusColumnJson = JSON.parse(vl);
        
        if (pfNextStatusColumnJson.hasOwnProperty('pfnextstatuscolumnjson')) {
            
            var icon = '', wfmStatusCode = '', metaTypeId = '', refStructureId = r.refstructureid;
            var statusList = pfNextStatusColumnJson.pfnextstatuscolumnjson, v;
            var parameterStr = (r.parameterde).replace(/(?:\r\n|\r|\n)/g, ' ');
            var rowData = JSON.parse(parameterStr);
            
            for (var key in rowData) {
                rowData = rowData[key];
                break;
            }
            
            if (rowData.hasOwnProperty('newWfmStatusId')) {
                rowData['wfmstatusid'] = rowData.newWfmStatusId;
                delete rowData.newWfmStatusId;
            }
            
            var rowId = rowData.id;
            
            if (rowData.hasOwnProperty('systemMetaGroupId')) {
                dataViewId = rowData.systemMetaGroupId;
            } else if (rowData.hasOwnProperty('systemmetagroupid')) {
                dataViewId = rowData.systemmetagroupid;
            }
            
            if (rowData.hasOwnProperty('systemMetaGroupCode')) {
                dataViewCode = rowData.systemMetaGroupCode;
            } else if (rowData.hasOwnProperty('systemmetagroupcode')) {
                dataViewCode = rowData.systemmetagroupcode;
            }
            
            for (var key in statusList) {
                
                v = statusList[key];
                icon = (v.wfmstatusicon ? v.wfmstatusicon : 'fa-cog');
                
                if (typeof v.wfmusedescriptionwindow != 'undefined' 
                        && (v.wfmusedescriptionwindow == '0' || v.wfmusedescriptionwindow == 'false') 
                        && typeof v.wfmuseprocesswindow != 'undefined' 
                        && (v.wfmuseprocesswindow == '0' || v.wfmuseprocesswindow == 'false')) {
                
                    buttons += '<button type="button" onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'' + dataViewId + '\', \'' + refStructureId + '\', \'' + v.wfmstatuscolor + '\', \'' + v.wfmstatusname + '\');" id="' + v.wfmstatusid + '" title="' + v.wfmstatusname + '" class="btn btn-xs" style="margin: 1px 0; background-color:'+v.wfmstatuscolor+'; color: #fff"><i class="fa ' + icon + '"></i> ' + v.wfmstatusname + '</button><div class="d-row-divider"></div>';

                } else {

                    if (typeof v.wfmstatusname != 'undefined' 
                            && v.wfmstatusname != '' 
                            && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null) 
                            && (v.wfmisneedsign != '1' && v.wfmisneedsign != '2' && v.wfmisneedsign != 'true')) {

                        buttons += '<button type="button" onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'' + dataViewId + '\', \'' + refStructureId + '\', \'' + v.wfmstatuscolor + '\', \'' + v.wfmstatusname + '\');" id="' + v.wfmstatusid + '" title="' + v.wfmstatusname + '" class="btn btn-xs" style="margin: 1px 0; background-color:'+v.wfmstatuscolor+'; color: #fff"><i class="fa ' + icon + '"></i> ' + v.wfmstatusname + '</button><div class="d-row-divider"></div>';

                    } else if (v.wfmstatusprocessid != '' 
                            && v.wfmstatusprocessid != 'null' 
                            && v.wfmstatusprocessid != null
                            && (v.wfmisneedsign != '1' && v.wfmisneedsign != '2' && v.wfmisneedsign != 'true')) {

                        wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : '';
                        metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';

                        if (r.hasOwnProperty('attachmenturl') && r.attachmenturl) {
                            r.attachmenturl = r.attachmenturl.replace(/['()]/g, escape);
                        }
                        buttons += '<button type="button" onclick="transferProcessAction(\'\', \'' + dataViewId + '\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'' + dataViewCode + '\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=' + dataViewId + '&refStructureId=' + refStructureId + '&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + v.wfmstatuscolor + '&rowId=' + rowId + '\', \'\', \''+encodeURIComponent(JSON.stringify(r)).replace(/'/g, '')+'\');" title="' + v.wfmstatusname + '" class="btn btn-xs" style="margin: 1px 0; background-color:'+v.wfmstatuscolor+'; color: #fff"><i class="fa ' + icon + '"></i> ' + v.wfmstatusname + '</button>';
                    }
                }
            }
        }
    }
    return buttons;
}
function dataViewWfmStatusFlowViewer(elem, rowId, wfmStatusId, wfmStatusName, dataViewId, refStructureId, wfmstatuscolor) {
    
    if (window['isIgnoreWfmHistory_' + dataViewId]) {
        return;
    }
    
    setTimeout(function () {

        var row = [];
        var rows = getDataViewSelectedRowsByRow(elem);

        if (rows.hasOwnProperty(0)) {
            row = rows[0];
        } else {
            var rowIndex = $(elem).closest('tr').attr('datagrid-row-index');
            row = rows[rowIndex];
        }
        
        row.id = rowId;

        $.ajax({
            type: 'post',
            url: 'mdobject/getRowWfmStatusForm',
            data: {
                refStructureId: $("#refStructureId", "#object-value-list-" + dataViewId).val(),
                dataViewId: dataViewId,
                metaDataId: dataViewId,
                rowId: rowId,
                dataRow: row,
                wfmStatusId: wfmStatusId,
                wfmStatusName: wfmStatusName,
                wfmstatuscolor: wfmstatuscolor,
                isSee: true
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                var $dialogName = 'dialog-wfmstatus-user-' + rowId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);

                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1400,
                    height: 'auto',
                    maxHeight: $(window).height() - 50,
                    modal: true,
                    open: function () {
                        $dialog.parent().css('background', '#F0F0F0');
                        Core.unblockUI();
                    },
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('close_btn'), "class": 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                
                Core.initFancybox($dialog);
                $dialog.dialog('open');
            }
        });
    }, 2);
}
function databank(element, type, mainMetadataid, processId, $checkUpdate) {

    var $this = $(element);
    var $dialogName = 'dialog-customercode-khan-' + mainMetadataid;

    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    if (type == '1') {

        setTimeout(function () {

            var dataGrid = window['objectdatagrid_' + mainMetadataid];
            if (typeof dataGrid !== 'undefined' && dataGrid !== '') {

                var rows = dataGrid.datagrid('getSelections');
                var selectedRow = rows[0];
                
                var q = dataGrid.datagrid('options').queryParams;
                var dvMetaDataId = q.metaDataId;
                
                selectedRow = dataViewSelectedRowsResolver(selectedRow);
                
                $.ajax({
                    type: 'post',
                    url: 'mdwidget/controlTemplateDatabank',
                    data: {
                        metaDataId: dvMetaDataId,
                        row: selectedRow,
                        processId: processId,
                        updateType: $checkUpdate
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {

                        if (data.Html !== '') {
                            var $btnClass = (typeof data.Btn !== 'undefined' && data.Btn === '0') ? 'hidden' : '';
                            $("#" + $dialogName).empty().append(data.Html);
                            $("#" + $dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: data.Title,
                                width: (typeof data.Width !== 'undefined') ? data.Width : 1100,
                                height: $(window).height() - 100,
                                modal: true,
                                close: function () {
                                    $("#" + $dialogName).empty().dialog('destroy').remove();
                                },
                                buttons: [
                                    {text: data.save_btn, class: 'btn btn-sm green-meadow ' + $btnClass, click: function () {

                                            Core.blockUI({
                                                animate: true
                                            });

                                            $("#changeWfmStatusForm_" + data.metaDataId).validate({errorPlacement: function () {}});

                                            if ($("#changeWfmStatusForm_" + data.metaDataId).valid()) {
                                                $("#changeWfmStatusForm_" + data.metaDataId).ajaxSubmit({
                                                    type: 'post',
                                                    data: {
                                                        levelType: $('#levelType').val(),
                                                        metaDataId: data.metaDataId,
                                                        checkUpdate: $checkUpdate,
                                                        selectedRow: selectedRow,
                                                    },
                                                    url: "mdwidget/saveTemplateDatabank1",
                                                    dataType: 'json',
                                                    success: function (response) {

                                                        new PNotify({
                                                            title: response.status,
                                                            text: response.message,
                                                            type: response.status,
                                                            sticker: false
                                                        });

                                                        dataViewReloadByElement(dataGrid);
                                                        
                                                        if ($checkUpdate == '1' && typeof response.nextProcess !== 'undefined' && response.nextProcess === '1') {

                                                            var selectedRows = getDataViewSelectedRows(mainMetadataid);
                                                            selectedRow = selectedRows[0];
                                                            setTimeout(function () {
                                                                transferProcessAction('', '1535617010635', '1532357110034', '200101010000011', 'toolbar', this, {callerType: 'posRequestList_11COPY'}, 'dataViewId=1535617010635&refStructureId=1528784819251&rowId=' + selectedRow.ids, undefined, selectedRow);
                                                            }, 1000);
                                                        }

                                                        if ($checkUpdate == '2' && typeof response.nextProcess !== 'undefined' && response.nextProcess === '1') {
                                                            //&statusId=1530417562134983&statusName=Харилцагч бүртгэх&statusColor=orange
                                                            transferProcessAction('', '1530760343467544', '1530619112555', '200101010000011', 'toolbar', this, {callerType: 'ADD_POS_LIST', isWorkFlow: true, wfmStatusId: '1530417562134983', wfmStatusCode: ''}, 'dataViewId=1530760343467544&refStructureId=1528784819251&rowId=' + selectedRow.id);
                                                        }

                                                        if ($checkUpdate == '3' && typeof response.nextProcess !== 'undefined' && response.nextProcess === '1') {
                                                            //statusId=1530417562134983&statusName=Харилцагч бүртгэх&statusColor=orange&
                                                            //transferProcessAction('', '1530760343467544', '1536131129614', '200101010000011', 'toolbar', this, {callerType: 'ADD_POS_LIST', isWorkFlow: true, wfmStatusId: '1530417562134983', wfmStatusCode: ''}, 'dataViewId=1530760343467544&refStructureId=1528784819251&wfmStatusId=1530417562134983&statusId=1530417562134983&statusName=Харилцагч бүртгэх&statusColor=orange&rowId='+ selectedRow.id);
                                                        }

                                                        if ($checkUpdate == '4' && typeof response.nextProcess !== 'undefined' && response.nextProcess === '1') {
                                                            dataViewReloadByElement(dataGrid);
                                                            setTimeout(function () {
                                                                //&statusId=1533266159653241&statusName=Харилцагч бүртгэсэн&statusColor=Blue
                                                                transferProcessAction('', '1530416207536008', '1530160571947', '200101010000011', 'toolbar', this, {callerType: 'REQUEST_INTERMEDIATE_POS_LIST', isWorkFlow: true, wfmStatusId: '1533266159653241', wfmStatusCode: ''}, 'dataViewId=1530416207536008&refStructureId=1528784819251&rowId=' + selectedRow.id);
                                                            }, 900);
                                                        }

                                                        if (response.status === 'success') {

                                                            Core.unblockUI();
                                                            $("#" + $dialogName).dialog('close');

                                                        }

                                                    }
                                                });

                                            } else {
                                                Core.unblockUI();
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: 'warning',
                                                    text: "Шаардлагатай талбаруудыг бөглөнө үү",
                                                    type: 'warning',
                                                    sticker: false
                                                });
                                            }


                                        }},
                                    {text: data.close_btn, class: 'btn blue-madison btn-sm ' + $btnClass, click: function () {
                                            $("#" + $dialogName).dialog('close');
                                            dataViewReloadByElement(dataGrid);
                                        }}
                                ]
                            });
                            $("#" + $dialogName).dialog('open');
                            Core.initAjax($('.bp-window-' + data.uniqId));
                        } else {
                            if (typeof data.message !== 'undefined') {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'warning',
                                    text: data.message,
                                    type: 'warning',
                                    sticker: false
                                });
                            }
                        }
                        Core.unblockUI();

                    },
                    error: function (jqXHR, exception) {
                        var msg = '';
                        if (jqXHR.status === 0) {
                            msg = 'Not connect.\n Verify Network.';
                        } else if (jqXHR.status == 404) {
                            msg = 'Requested page not found. [404]';
                        } else if (jqXHR.status == 500) {
                            msg = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }
                        
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error',
                            text: msg,
                            type: 'error',
                            sticker: false
                        });
                        Core.unblockUI();
                    }
                });
            }

        }, 5);

    } else {

        setTimeout(function () {

            var dataGrid = window['objectdatagrid_' + mainMetadataid];
            if (typeof dataGrid !== 'undefined' && dataGrid !== '') {

                var rows = dataGrid.datagrid('getSelections');
                var selectedRow = rows[0];
                var q = dataGrid.datagrid('options').queryParams;
                var dvMetaDataId = q.metaDataId;
                
                selectedRow = dataViewSelectedRowsResolver(selectedRow);
                
                $.ajax({
                    type: 'post',
                    url: 'mdwidget/viewTemplateDatabank',
                    data: {
                        metaDataId: dvMetaDataId,
                        row: selectedRow,
                        processId: processId,
                        updateType: $checkUpdate
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        if (data.Html !== '') {
                            var $btnClass = (typeof data.Btn !== 'undefined' && data.Btn === '0') ? 'hidden' : '';
                            $("#" + $dialogName).empty().append(data.Html);
                            $("#" + $dialogName).dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: data.Title,
                                width: (typeof data.Width !== 'undefined') ? data.Width : 1100,
                                height: $(window).height() - 100,
                                modal: true,
                                close: function () {
                                    $("#" + $dialogName).empty().dialog('destroy').remove();
                                },
                                buttons: [
                                    {text: data.close_btn, class: 'btn blue-madison btn-sm ', click: function () {
                                            dataViewReloadByElement(dataGrid);
                                            $("#" + $dialogName).empty().dialog('destroy').remove();
                                        }}
                                ]
                            });
                            $("#" + $dialogName).dialog('open');
                        } else {
                            if (typeof data.message !== 'undefined') {
                                PNotify.removeAll();
                                new PNotify({
                                    title: 'warning',
                                    text: data.message,
                                    type: 'warning',
                                    sticker: false
                                });
                            }
                        }
                        Core.unblockUI();
                    },
                    error: function (jqXHR, exception) {
                        var msg = '';
                        if (jqXHR.status === 0) {
                            msg = 'Not connect.\n Verify Network.';
                        } else if (jqXHR.status == 404) {
                            msg = 'Requested page not found. [404]';
                        } else if (jqXHR.status == 500) {
                            msg = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.\n' + jqXHR.responseText;
                        }
                        
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error',
                            text: msg,
                            type: 'error',
                            sticker: false
                        });
                        Core.unblockUI();
                    }
                });
            }
        }, 5);
    }
}

function paramDataToObject(paramData) {
    if (typeof paramData !== 'undefined' && paramData.hasOwnProperty(0) && paramData[0].hasOwnProperty('name')) {
        var obj = {};

        for (var i = 0; i < paramData.length; i++) {
            obj[paramData[i]['name']] = $.trim(paramData[i]['value']);
        }

        return obj;
    } else {
        return paramData;
    }
}

function toArchiveReport(elem, defaultName, uniqId) {
    var $dialogName = 'dialog-archive-' + uniqId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $.ajax({
        type: 'post',
        url: 'mddoc/toArchiveReport',
        data: {defaultName: defaultName},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            $("#" + $dialogName).empty().append(data.html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 800,
                height: "auto",
                modal: true,
                close: function () {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {

                            $("#report-archive-form").validate({errorPlacement: function () {}});

                            if ($("#report-archive-form").valid()) {

                                var _parent = $(elem).closest(".report-preview");
                                $("div#contentRepeat", _parent).empty();

                                if (copies >= 1) {
                                    $("page", _parent).each(function (j) {
                                        if (pageType == '2col') {
                                            $("#contentRepeat", _parent).append($(this).find("#exContent").get(0).outerHTML);
                                        } else {
                                            for (var i = 0; i < copies; i++) {
                                                if (isNewPage == '1') {
                                                    $("#contentRepeat", _parent).append($(this).find("#externalContent").get(0).outerHTML);
                                                } else {
                                                    if (pageType == '2col') {
                                                        $("#contentRepeat", _parent).append($(this).find("#exContent").html());
                                                    } else {
                                                        $("#contentRepeat", _parent).append($(this).find("#externalContent").get(0).outerHTML);
                                                    }
                                                }
                                            }
                                        }
                                    });
                                    $("div#contentRepeat", _parent).find("#externalContent").last().removeAttr('style');
                                }

                                $.ajax({
                                    type: 'post',
                                    url: 'mddoc/toArchiveSave',
                                    data: {
                                        contentName: $("#report-archive-form").find("#contentName").val(),
                                        directoryId: $("#report-archive-form").find("#directoryId").val(),
                                        content: $("div#contentRepeat", _parent).html(),
                                        headerHtml: _parent.find('script[data-template="templateHeader"]').text(),
                                        footerHtml: _parent.find('script[data-template="templateFooter"]').text(),                                             
                                        orientation: pageOrientation
                                    },
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                    },
                                    success: function (data) {
                                        PNotify.removeAll();
                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: 'Success',
                                                text: data.message,
                                                type: 'success',
                                                sticker: false
                                            });
                                            $("#" + $dialogName).dialog('close');
                                        } else {
                                            new PNotify({
                                                title: 'Error',
                                                text: data.message,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }},
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }}
                ]
            });
            $("#" + $dialogName).dialog('open');

            Core.unblockUI();
        },
        error: function () {
            alert('Error');
        }
    }).done(function () {
        Core.initAjax($("#" + $dialogName));
    });
}
function toAutoArchiveReport(elem, dataViewId, recordId, contentName, directoryId) {
    var $parent = $(elem).closest('.report-preview');
    var divide = Math.ceil(copies / 2);
    
    $parent.find('div#contentRepeat').empty();
    if (copies >= 1) {
        var $page = $parent.find('page'), pageLength = $page.length, divideTag = '';
        if (pageLength > 1 || divide > 1) {
            divideTag = '<div style="page-break-after: always;"></div>'; 
        }
        $page.each(function() {
            var $thisPage = $(this);
            if (pageType == '2col') {
                $parent.find('#contentRepeat').append($thisPage.find("#exContent").get(0).outerHTML + divideTag);
            } else {
                for (var i = 0; i < divide; i++) {
                    $parent.find('#contentRepeat').append($thisPage.find("#externalContent").get(0).outerHTML + divideTag);
                }
            }
        });
    }

    $.ajax({
        type: 'post',
        url: 'mddoc/toArchiveSave',
        data: {
            dataViewId: dataViewId,
            recordId: recordId,
            contentName: contentName,
            directoryId: directoryId,
            content: $parent.find('div#contentRepeat').html(),
            headerHtml: $parent.find('script[data-template="templateHeader"]').text(),
            footerHtml: $parent.find('script[data-template="templateFooter"]').text(),              
            orientation: pageOrientation,
            size: pageSize,
            top: pageRtTop,
            left: pageRtLeft,
            bottom: pageRtBottom,
            right: pageRtRight,
            wfmStatusId: 'isnull',
            typeId: 'isnull'
        },
        dataType: 'json',
        success: function (data) {
            PNotify.removeAll();
            if (data.status === 'error') {
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            }
        }
    });
}
function toArchiveStatement(elem, defaultName, uniqId, props) {
    var $dialogName = 'dialog-archive-' + uniqId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $.ajax({
        type: 'post',
        url: 'mddoc/toArchiveReport',
        data: {defaultName: defaultName},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            $("#" + $dialogName).empty().append(data.html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: "auto",
                modal: true,
                close: function () {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {

                            $("#report-archive-form").validate({errorPlacement: function () {}});

                            if ($("#report-archive-form").valid()) {

                                var _this = $(elem);
                                var $parent = _this.closest("div.report-preview");
                                var $fileIdElem = $parent.find('div[data-file-id]');
                                var fileId = $fileIdElem.attr('data-file-id');
                                var statementContent = '';
                                
                                if ($fileIdElem.hasAttr('data-count') && Number($fileIdElem.attr('data-count') < 300)) {
                                    try {
                                        statementHeaderFreezeDestroy($parent);
                                        statementContent = encodeURIComponent($parent.find('div.report-preview-print').html());
                                        statementHeaderFreeze($parent);
                                    } catch(e) {}
                                }                                

                                $.ajax({
                                    type: 'post',
                                    url: 'mddoc/toArchiveSaveStatement',
                                    data: $("#report-archive-form").serialize() + '&statementContent=' + statementContent + '&' + props + '&fileId=' + fileId,
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                    },
                                    success: function (data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status === 'success') {
                                            $("#" + $dialogName).dialog('close');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }},
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }}
                ]
            });
            $("#" + $dialogName).dialog('open');

            Core.unblockUI();
        },
        error: function () {
            alert('Error');
        }
    }).done(function () {
        Core.initAjax($("#" + $dialogName));
    });
}
function toArchiveReportByWfm(elem, params) {
    var $parent = $(elem).closest('.report-preview');
    var divide = Math.ceil(copies / 2);
    var dvId = params.metaDataId;
    var wfmstatusname = params.wfmstatusname;
    var isformnotsubmit = params.isformnotsubmit;
    var wfmStatusProcessId = params.wfmstatusprocessid;
    var reportMetaDataId = params.hasOwnProperty('reportMetaDataId') ? params.reportMetaDataId : '';
    var selectedRows = getDataViewSelectedRows(dvId);
    var selectedRow = selectedRows[0];
   
    $parent.find('div#contentRepeat').empty();
    if (copies >= 1) {
        var $page = $parent.find('page'), pageLength = $page.length, divideTag = '';
        if (pageLength > 1 || divide > 1) {
            divideTag = '<div style="page-break-after: always;"></div>'; 
        }
        $page.each(function() {
            var $thisPage = $(this);
            if (pageType == '2col') {
                $parent.find('#contentRepeat').append($thisPage.find("#exContent").get(0).outerHTML + divideTag);
            } else {
                for (var i = 0; i < divide; i++) {
                    $parent.find('#contentRepeat').append($thisPage.find("#externalContent").get(0).outerHTML + divideTag);
                }
            }
        });
    }
    
    var postData = {
        content: $parent.find('div#contentRepeat').html(),
        orientation: pageOrientation,
        size: pageSize,
        top: pageRtTop,
        left: pageRtLeft,
        bottom: pageRtBottom,
        right: pageRtRight,
        wfmStatusId: 'isnull',
        typeId: 'isnull',
        params: params,
        selectedRow: selectedRow, 
        reportMetaDataId: reportMetaDataId, 
        headerHtml: $parent.find('script[data-template="templateHeader"]').text(),
        footerHtml: $parent.find('script[data-template="templateFooter"]').text()       
    };
    
    if (wfmStatusProcessId != '' && wfmStatusProcessId != 'null' && wfmStatusProcessId != null) {
        
        var dvCode = $('div[data-process-id="'+dvId+'"]').attr('data-meta-code');
        
        _processReportTemplateArchive[dvId + '_' + wfmStatusProcessId] = postData;
        transferProcessAction('', dvId, wfmStatusProcessId, params.metatypeid, 'toolbar', elem, {callerType: dvCode, isWorkFlow: true, wfmStatusId: params.wfmstatusid, wfmStatusCode: params.wfmstatuscode, wfmStatusName: params.wfmstatusname}, 'dataViewId='+dvId+'&refStructureId='+dvId+'&statusId='+params.wfmstatusid+'&statusName='+params.wfmstatusname+'&statusColor='+$.trim(params.wfmstatuscolor)+'&rowId='+params.recordId);
        
    } else {

        if (isformnotsubmit === '1') {

            var $dialogName = 'dialog-wfm-stutus-confirm';

            var $dialog = $('#' + $dialogName);
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }

            $dialog.empty().append('Та <strong> ' + wfmstatusname +' </strong>  төлөвт шижлүүлэхэд итгэлтэй байна уу?');
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Сануулах',
                width: 400,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: plang.get('yes_btn'),
                        class: 'btn green-meadow btn-sm',
                        click: function() {
                            $.ajax({
                                type: 'post',
                                url: 'mdtemplate/toArchiveWfm',
                                data: postData,
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function (data) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                    if (data.status === 'success') {
                                        dataViewReload(dvId);
                                        $('#dialog-printOption').dialog('close');
                                        $dialog.dialog('close');
                                    }
                                    Core.unblockUI();
                                }
                            });
                        }
                    },
                    {
                        text: plang.get('no_btn'),
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });

            $dialog.dialog('open');

        } else {
            $.ajax({
                type: 'post',
                url: 'mdtemplate/toArchiveWfm',
                data: postData,
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    if (data.status === 'success') {
                        dataViewReload(dvId);
                        $('#dialog-printOption').dialog('close');
                    }
                    Core.unblockUI();
                }
            });
        }
    }
}

function checkAllDvCriteria(element, $path) {
    var $element = $(element);
    var $selector = $element.closest('.panel-group').find('div[data-paramname="'+ $path +'"]');
    if ($element.is(":checked")) {
        $selector.find("input[type=radio], input[type=checkbox]").prop('checked', true);
        $selector.find("input[type=radio], input[type=checkbox]").parent().addClass('checked');
    } else {
        $selector.find("input[type=radio], input[type=checkbox]").removeAttr('checked');
        $selector.find("input[type=radio], input[type=checkbox]").closest('span.checked').removeClass('checked');
    }
}

function seeMoreDvCriteria(element, $title, uniqId, $metaDataId, $heigth, $seemore) {
    var $element = $(element);
    if (typeof $seemore !== 'undefined') {
        if ($element.attr('see-more-status') === '0') {
            $element.closest('div.toggle-group-criteria').find('.radio-list-hidden').toggle( "slow", function() {
                // Animation complete.
                $element.attr('see-more-status', '1');
                $element.html($($element).attr('see-more-text-1'));
            });
        } else {
            $element.closest('div.toggle-group-criteria').find('.radio-list-hidden').toggle( "hide", function() {
                // Animation complete.
                $element.attr('see-more-status', '0');
                $element.html($element.attr('see-more-text-0'));
            });
        }
        $element.closest('div.sidebar-secondary').attr('style', "overflow: auto; overflow-x: hidden;");
        return;
    }
    
    var $dialogName = 'dialog-see-more-' + uniqId;
    var windowHeight = $(window).height();
    var $row = $element.closest('.group-metacriteria-' + $metaDataId + '_' + uniqId).find('.hidden-seemore-' + $metaDataId + '_' + uniqId);
    
    $.uniform.restore($row.find('input[type=checkbox], input[type=radio]'));
        
    var $html = $row.html();
    var $modalFull = '';
    
    if ($heigth == '1') {
        $modalFull = 'modal-full';
    }

    $('<div class="modal pl0 fade" id="' + $dialogName + '" tabindex="-1" role="dialog" aria-hidden="true">' +
            '<div class="modal-dialog ' + $modalFull + '" style="margin-top: 10px;">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h4 class="modal-title">' + $title + '</h4>' +
            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>' +
            '</div>' +
            '<div class="modal-body leftweb-criteria-seemore pb0">' + 
            '<div class="padding15"><div class="col-md-3">'+
            '<label class="radio-inline">'+
            '<input type="checkbox" onclick="seeMoreDvCriteriaAllCheck(this);"> '+plang.get('ischeck_all')+
            '</label>'+
            '</div></div>'+
            (typeof $html === 'undefined' ? '' : $html) + 
            '</div>' +
            '<div class="modal-footer">' +
            '<button type="button" data-dismiss="modal" class="btn blue-hoki btn-sm">' + plang.get('close_btn') + '</button>' +
            '</div></div></div></div>').appendTo('body');

    var $dialog = $('#' + $dialogName);
    
    if ($heigth == '1') {
        $dialog.find('.radio-list').css({'height': windowHeight - 160, 'overflow': 'auto', 'border-top': '1px #eee solid'});
    } else {
        $dialog.find('.radio-list').css({'max-height': $dialog.height() - 180, 'overflow': 'auto', 'border-top': '1px #eee solid'});
    }
    
    Core.initUniform($dialog);

    $dialog.modal();
    $dialog.on('shown.bs.modal', function () {
        disableScrolling();
    });
    $dialog.on('hidden.bs.modal', function () {
        $dialog.remove();
        enableScrolling();
    });
}
function seeMoreDvCriteriaAllCheck(elem) {
    var $this = $(elem), $parent = $this.closest('.modal-body').find('.radio-list');
    
    if ($this.is(':checked')) {
        $parent.find('input').prop('checked', true);
    } else {
        $parent.find('input').prop('checked', false);
    }
    $.uniform.update($parent.find('input'));
    
    $parent.find('input').each(function() {
        var _this = this, $this = $(_this), onClick = $this.attr('onclick').replace(/'/g, '').replace(/\)/g, ''), 
            onClickArr = onClick.split(',');
        seeMoreDvSelection(_this, (onClickArr[1]).trim(), (onClickArr[2]).trim(), (onClickArr[3]).trim());
    });
}
function seeMoreDvSelection(element, $displayField, uniqId, metaDataId) {
    var $this = $(element);
    var $append = $('.append-more-selected-' + metaDataId + '_' + uniqId);
    var $input = $append.find('input[value="'+$this.attr('value')+'"]');
    
    if ($input.length == 0) {
        var $html = '<label class="radio-inline"><input type="' + $this.attr('type') + '" name="' + $this.attr('name') + '" checked="checked" data-path="' + $this.attr('data-path') + '" value="' + $this.attr('value') + '"> ' + $displayField + '</label>';
        if ($this.attr('type') === 'radio') {
            $append.empty().append($html).promise().done(function () {
                Core.initUniform($append);
                $append.find('input[type="' + $this.attr('type') + '"]').trigger('click');
            });
        } else {
            $append.append($html).promise().done(function () {
                Core.initUniform($append);
            });
        }
    } else if (!$this.is(':checked')) {
        $input.closest('label').remove();
    }
}
function callMarriageFnc(element) {
    var $this = $(element);
    $('.cvl-marriage-table').addClass('hidden');
    $('.' + $this.val()).removeClass('hidden');
}
function dataViewFirstColumnFocus(dvId) {
    if (typeof window['objectdatagrid_'+dvId] !== 'undefined') {

        if (typeof $.data(window['objectdatagrid_'+dvId][0], 'datagrid') != 'undefined') {
            
            setTimeout(function() {
                window['objectdatagrid_'+dvId].datagrid('getPanel').find('> div.datagrid-view').find('> .datagrid-view2 > .datagrid-header tr.datagrid-filter-row > td:eq(0) input[type=text]').focus().select();
            }, 50);
        }
    }
}
function dataViewSearchParamFocus(dvId, path) {
    if (window['dv_search_'+dvId].find('[data-path="'+path+'"]').length) {
        window['dv_search_'+dvId].find('[data-path="'+path+'"]').focus().select();
        return true;
    } else {
        return false;
    }
}
function subgridExcelExport(elem) {
    Core.blockUI({
        message: 'Exporting...', 
        boxed: true
    });
        
    var dataGrid = $(elem).closest('.dv-subgrid').find('div.datagrid-view').children('table');
    var q = dataGrid.datagrid('options').queryParams;
    
    $.fileDownload(URL_APP + 'mdobject/dataViewExcelExport', {
        httpMethod: 'POST',
        data: {
            metaDataId: q.metaDataId,
            uriParams: q.uriParams,
            total: dataGrid.datagrid('getData').total
        }
    }).done(function() {
        Core.unblockUI();
    }).fail(function(response){
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: response,
            type: 'error',
            addclass: pnotifyPosition,
            sticker: false
        });
        Core.unblockUI();
    });
    
    return;
}
function dataViewSelectedRowsResolver(rows) {
    
    if (rows) {
        var strRows = JSON.stringify(rows);
        strRows = strRows.replace(/<\/?.+?>/ig, '');
        rows = JSON.parse(strRows);
    }
    
    return rows;
}

function dataViewSelectedRowsResolverTimeOut(rows) {
    
    if (rows) {
        var strRows = JSON.stringify(rows);
        strRows = strRows.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/'/g, '&#039;');
        
        setTimeout(function () {
            rows = JSON.parse(strRows);
        }, 1);
    }
    
    return rows;
}

function removeRenderProcessRight (elem) {
    $(elem).closest('.render-process-page-right-wrapper').remove();
    $(window).resize();
}

function renderProcessPage(callerType, metadataid, html, _elem) {
    if (callerType === 'dataview') {
        var $parent = $('div.div-objectdatagrid-' + metadataid).parent();
        
        if ($parent.closest('.intranet1').length && $(_elem).data('dvbtn-position') == 'right') {
            $inlinebpappend = $parent.closest('.intranet').children();
            if (!$inlinebpappend.find('.render-process-page').length) {
                $inlinebpappend.append('<div style="width: 20rem;z-index:10" class="sidebar v2 sidebar-light sidebar-expand-md render-process-page-right-wrapper"><div class="render-process-page pl-2 pr-2">' + html + '</div></div>');
            } else {
                $inlinebpappend.find('.render-process-page').empty().append(html);
            }
            $inlinebpappend.find('.render-process-page').find('.meta-toolbar').find('span.text-uppercase').hide();
            $inlinebpappend.find('.render-process-page').find('.meta-toolbar').find('div').append('<a href="javascript:void(0);" onclick="removeRenderProcessRight(this)" title="Хаах" class="btn btn-light bg-gray border-0 p-1 pl-2 pr-2 removeRenderProcessRightBtn bg-grey-c0"><i class="icon-cross"></i></a>');
            $(window).resize();
        } else if ($(_elem).data('dvbtn-position') == 'right') {
            $inlinebpappend = $parent.parent().parent();
            if (!$inlinebpappend.find('.render-process-page').length) {
                $inlinebpappend.append('<div style="width: 20rem;z-index:10" class="ml4 sidebar v2 sidebar-light sidebar-expand-md render-process-page-right-wrapper"><div class="render-process-page pl-2 pr-2">' + html + '</div></div>');
            } else {
                $inlinebpappend.find('.render-process-page').empty().append(html);
            }
            var getBpName = $inlinebpappend.find('.render-process-page').find('.meta-toolbar').find('span.text-uppercase').text();
            $inlinebpappend.find('.render-process-page').find('.meta-toolbar').after('<div style="background: #e5e5e5;border-top: solid 1px #c9c9c9;border-bottom: solid 1px #c9c9c9;text-transform: uppercase;font-weight: bold;>'+getBpName+'</div>');
            $inlinebpappend.find('.render-process-page').find('.meta-toolbar').find('span.text-uppercase').hide();
            $inlinebpappend.find('.render-process-page').find('.meta-toolbar').css('position', 'static');
            $inlinebpappend.find('.render-process-page').find('.meta-toolbar').find('div').append('<a href="javascript:void(0);" onclick="removeRenderProcessRight(this)" title="Хаах" class="btn btn-light bg-gray border-0 p-1 pl-2 pr-2 removeRenderProcessRightBtn bg-grey-c0"><i class="icon-cross"></i></a>');
            $(window).resize();
        } else {

            //sidebar v2 sidebar-light sidebar-main sidebar-expand-md
            if ($parent.closest('#objectDataView_' + metadataid).find('#md-bp-left-' + metadataid).length) {
                $parent.closest('#objectDataView_' + metadataid).find('#md-bp-left-' + metadataid).empty().append(html);
            } else {
                if (!$parent.find('.render-process-page').length) {
                    if ($parent.closest('.explorer-table-row').length) {
                        if ($parent.closest('.row').find('.render-process-page').length) {
                            $parent.closest('.row').find('.render-process-page').empty().append(html);
                        } else {
                            $parent.closest('.row').prepend('<div class="render-process-page pl-2 pr-2">' + html + '</div>');
                        }
                    } else {
                        $parent.prepend('<div class="render-process-page pl-2 pr-2">' + html + '</div>');
                    }
                } else {
                    $parent.find('.render-process-page').empty().append(html);
                }
            }
        }
    }
    return;
}

function changeWfmStatusByStr(elem, paramStr) {
    
    var $dialogName = 'dialog-change-wfm-str';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var formHtml = '<form method="post" id="form-change-wfm-str">';
    formHtml += '<textarea name="description" class="form-control form-control-sm" spellcheck="false" placeholder="Тайлбар бичнэ үү" rows="5"></textarea>';
    formHtml += '</form>';

    $dialog.empty().append(formHtml);
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('change_workflow'),
        width: 500,
        height: 'auto',
        modal: true,
        close: function () {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {

                $('#form-change-wfm-str').validate({errorPlacement: function () {}});

                if ($('#form-change-wfm-str').valid()) {

                    $.ajax({
                        type: 'post',
                        url: 'mdworkflow/changeWfmStatusByStr', 
                        data: 'paramStr='+paramStr+'&'+$('#form-change-wfm-str').serialize(),
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({
                                message: 'Loading...',
                                boxed: true
                            });
                        },
                        success: function (data) {
                            PNotify.removeAll();
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
                            if (data.status == 'success') {
                                $dialog.dialog('close');
                                dataViewReloadByRowElement(elem);
                            }
                            Core.unblockUI();
                        }
                    });
                }
            }}, 
            {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}

function pfLinkViewer(elem, fileUrl) {
    var $dialogName = 'dialog-pf-link-viewer';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var dialogHtml = '';
    var iframeHeight = $(window).height() - 100;
    
    if (fileUrl.indexOf('.pdf') !== -1) {
        dialogHtml = '<iframe src="'+URL_APP+'api/pdf/web/viewer.html?file=../../../'+fileUrl+'" frameborder="0" style="width: 100%;height: '+iframeHeight+'px;"></iframe>';
    } else {
        dialogHtml = '<iframe src="'+fileUrl+'" frameborder="0" style="width: 100%;height: '+iframeHeight+'px;"></iframe>';
    }

    $dialog.empty().append(dialogHtml);
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'File view',
        width: 1000,
        height: 'auto',
        modal: true,
        close: function () {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [ 
            {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    }).dialogExtend({ 
        'closable': true, 
        'maximizable': true, 
        'minimizable': true, 
        'collapsable': true, 
        'dblclick': 'maximize', 
        'minimizeLocation': 'left', 
        'icons': { 
            'close': 'ui-icon-circle-close', 
            'maximize': 'ui-icon-extlink', 
            'minimize': 'ui-icon-minus', 
            'collapse': 'ui-icon-triangle-1-s', 
            'restore': 'ui-icon-newwin' 
        } 
    }); 
    $dialog.dialog('open');
    // $dialog.dialogExtend('maximize');
}
function pfNextStatusColumnJsonCleanField(data) {
    if (data.hasOwnProperty('newwfmstatusid')) {
        delete data.newwfmstatusid;
    }

    if (data.hasOwnProperty('newwfmstatusname')) {
        delete data.newwfmstatusname;
    }

    if (data.hasOwnProperty('newWfmDescription')) {
        delete data.newWfmDescription;
    }

    if (data.hasOwnProperty('newwfmdescription')) {
        delete data.newwfmdescription;
    }
    
    for (var key in data) {
        if ((!!data[key]) && (data[key].constructor === Object)) {
            delete data[key];
        }
    }
    
    return data;
}
function dvProcessButtonsShow(dvId, selectedRow) {
    var $buttons = $('#object-value-list-'+dvId).find('.dv-bp-btn-visible');
    if ($buttons.length) {
        $buttons.each(function (index, row) {
            var ticket = true, $buttonRow = $(row), $criteriaRow = $buttonRow;
            
            if (!$buttonRow.hasAttr('data-simple-criteria')) {
                $criteriaRow = $buttonRow.find('> a:eq(0)');
            }
            
            if (typeof $criteriaRow.attr('data-simple-criteria') !== 'undefined' && typeof selectedRow != 'undefined' && $criteriaRow.attr('data-simple-criteria')) {
                var evalcriteria = $criteriaRow.attr('data-simple-criteria').toLowerCase();
                
                if (evalcriteria.indexOf('#') > -1) {
                    var criteriaSplit = evalcriteria.split('#');
                    evalcriteria = (criteriaSplit[0]).trim();
                }
                
                $.each(selectedRow, function(index, row) {
                    if (evalcriteria.indexOf(index) > -1) {
                        row = (row === null) ? '' : row.toLowerCase();
                        var regex = new RegExp('\\b' + index + '\\b', 'g');
                        evalcriteria = evalcriteria.replace(regex, "'" + row.toString() + "'");
                    }
                });
                
                evalcriteria = evalcriteria.replace(/match\(/g, 'checkMatchValue(');
                
                try {
                    if (!eval(evalcriteria)) {
                        ticket = false;
                    }
                } catch (err) { ticket = false; console.log(err); }
            }
            
            if (ticket) {
                $buttonRow.removeClass('d-none');
            } else {
                $buttonRow.addClass('d-none');
            }
        });
        
    }
    return;
}
function checkMatchValue(glue, needle, checkVal) {
        
    if (needle != '') {
        var needleArr = needle.split(glue);
        for (var c in needleArr) {
            if (needleArr[c] == checkVal) {
                return true;
            }
        }
    }

    return false;
}
function dvFieldValueShow(val) {
    return val != null ? val : '';
}
function dvFieldNumeric(val) {
    var num = Number(val);
    if (isNaN(num)) {
        return 0;
    }
    return num;
}
function initExpandCollapseAllRowEvent(dvId) {
    var $dataGridExpander = $('div.div-objectdatagrid-'+dvId).find('.datagrid-header-expander');
    
    if ($dataGridExpander.length) {
        
        $dataGridExpander.html('<span class="datagrid-row-expander datagrid-row-expand expandCollapseAll" style="display:inline-block;width:16px;height:16px;cursor:pointer;"></span>');
        $dataGridExpander.css('text-align', 'center');

        $dataGridExpander.find('.expandCollapseAll').click(function() {      
            var $expandCollapseAll = $(this);
            var countRow = window['objectdatagrid_'+dvId].datagrid('getRows').length;

            if ($expandCollapseAll.hasClass('datagrid-row-expand')){
                $expandCollapseAll.removeClass('datagrid-row-expand').addClass('datagrid-row-collapse');
                for (var c = 0; c < countRow; c++) {
                    window['objectdatagrid_'+dvId].datagrid('expandRow', c);
                }    
            } else {
                $expandCollapseAll.removeClass('datagrid-row-collapse').addClass('datagrid-row-expand');
                for (var c = 0; c < countRow; c++) {
                    window['objectdatagrid_'+dvId].datagrid('collapseRow', c);
                }
            }
        });
    }
}

function initDVClearColumnFilterBtn($panelView, $panelFilterRow) {
    
    if ($panelFilterRow && $panelFilterRow.length) {
        
        var $inputBoxes = $panelFilterRow.find('input:text').filter(function() { return this.value != ''; });
        var $multiFilters = $panelFilterRow.find('input[data-multifilter="1"]');
        var $exists = $panelFilterRow.find('.dv-filter-clear-parent');
        
        if ($multiFilters.length) {
            $inputBoxes = $inputBoxes.add($multiFilters);
        }

        if ($inputBoxes.length) {
            if ($exists.length == 0) {
                var $firstEmptyCell = $panelView.find('.datagrid-view1 .datagrid-filter-row td:empty:last');
                if ($firstEmptyCell.length) {
                    $firstEmptyCell.html('<div class="text-center dv-filter-clear-parent"><a onclick="dvClearColumnFilter(this);" class="cursor-pointer font-size-12" title="'+plang.get('Баганын шүүлтийг цэвэрлэх')+'"><i class="icon-cross3"></i></a></div>');
                } 
            }
        } else {
            $panelView.find('.dv-filter-clear-parent').remove();
        }
    }
}
function dvClearColumnFilter(elem) {
    var $panelView = $(elem).closest('.datagrid-view');
    var $panelFilterRow = $panelView.find('.datagrid-filter-row');
    var $inputBoxes = $panelFilterRow.find('input:text').filter(function() { return this.value != ''; });
    var $multiFilters = $panelFilterRow.find('input[data-multifilter="1"]'), 
        multiFiltersLength = $multiFilters.length; 
    var dg = $panelView.children('table[id]');
    var q = dg.datagrid('options').queryParams;
    
    if (q.hasOwnProperty('metaDataId')) {
        var dvMetaDataId = q.metaDataId;
    } else {
        var dvMetaDataId = q.indicatorId;
    }
    
    if (multiFiltersLength) {
        $inputBoxes = $inputBoxes.add($multiFilters);
    }             
        
    if ($inputBoxes.length) {
                
        $('style[id*="'+dvMetaDataId+'-"]').remove();
        
        if (multiFiltersLength) {
            
            var $multipleFilterValues = $('#object-value-list-' + dvMetaDataId).find('.multiple_filter_values'), 
                $multiValueFilter = $('#object-value-list-' + dvMetaDataId).find('.dataview-multivalue-filter'); 
                
            $multipleFilterValues.each(function() {
                $('#'+$(this).attr('data-dialog-id')).empty().dialog('destroy').remove();
            });
            $multipleFilterValues.remove();
            $multiFilters.removeAttr('data-multifilter');
            
            if ($multiValueFilter.length) {
                $multiValueFilter.closest('.dataview-multivalue-filter-sticky').removeClass('dataview-multivalue-filter-sticky');
                $multiValueFilter.remove();
            } 
            
            dg.datagrid('removeFilterRule');
            window['dvSearchParamData_' + dvMetaDataId]('');         
            
        } else {
            dg.datagrid('removeFilterRule');
            dg.datagrid('doFilter');
        }
        
    } else {
        
        if (typeof window['datagridGroupHide_' + dvMetaDataId] !== 'undefined' && window['datagridGroupHide_' + dvMetaDataId]) {
            window['datagridGroupHide_' + dvMetaDataId] = false;
            dg.datagrid('removeFilterRule');
            dg.datagrid('doFilter');
        }
    }
    
    var $multiValueFilterDialog = $('div[data-dv-multi-filter="'+dvMetaDataId+'"]');

    if ($multiValueFilterDialog.length) {
        $multiValueFilterDialog.each(function() {
            var $this = $(this);
            if ($this.data('ui-dialog')) {
                $this.dialog('destroy').remove();
            } else {
                $this.empty();
            }
        });
    }
}
function dvClearColumnSort(elem, dvMetaDataId, defaultSort) {
    if (typeof window['datagridGroupHide_' + dvMetaDataId] !== 'undefined' && window['datagridGroupHide_' + dvMetaDataId]) {
        
        window['datagridGroupHide_' + dvMetaDataId] = false;
        elem.remove();
        
        $("button.dataview-default-filter-btn", "#object-value-list-" + dvMetaDataId).trigger('click');
    }
}
function dataViewPivotInit(elem, selectedRow, paramData) {
    if (typeof isPivotToolAddonScript === 'undefined') {
        $.getScript('middleware/assets/js/addon/pivot.js').done(function() {
            dataViewPivotTool(elem, selectedRow, paramData);
        });
    } else {
        dataViewPivotTool(elem, selectedRow, paramData);
    }
}
function dvFilterColumnColor(dvId, column) {
    if ($('head').find('style#'+dvId+'-'+column).length == 0) {    
        $('head').append('<style type="text/css" id="'+dvId+'-'+column+'">'+
        '.div-objectdatagrid-'+dvId+' .datagrid-header td[field="'+column+'"] span { color: #00139a } '+
        '.div-objectdatagrid-'+dvId+' .datagrid-header td[field="'+column+'"],'+
        '.div-objectdatagrid-'+dvId+' .datagrid-body td[field="'+column+'"] {'+
            'background-color: rgb(166, 233, 255);'+
        '}</style>');      
    }
    return;
}
function dvToProcessRender(metaDataId) {
    
    var processParam = {
        metaDataId: metaDataId,
        isDialog: false,
        isSystemMeta: true
    };
    
    $.ajax({
        type: 'post',
        url: 'mdwebservice/dvToProcessRender',
        data: processParam,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {

            var $viewFormMeta = $('#viewFormMeta');
            $viewFormMeta.empty().append(data.Html);
            Core.initBPAjax($viewFormMeta);
            $('#renderMeta, #editFormGroup').hide();
            $viewFormMeta.show();

            $('html, body').animate({
                scrollTop: 0
            }, 'slow');

            Core.unblockUI();
        },
        error: function() { alert('Error'); Core.unblockUI(); }
    });
}
function dvDmRecordMapSet(elem, dvId, refStrId) {
    
    var rows = getDataViewSelectedRows(dvId);
    var rowsLength = rows.length;
    
    PNotify.removeAll();
    
    if (rowsLength) {
        
        var firstRow = rows[0];
        
        if (!firstRow.hasOwnProperty('wfmstatusid')) {
            new PNotify({
                title: 'Info',
                text: 'Төлөвтэй мөр сонгоно уу!', 
                type: 'info',
                sticker: false, 
                addclass: pnotifyPosition
            });
            return;
        }
        
        if (rowsLength > 1) {
            
            var firstRowStatusId = firstRow['wfmstatusid'];
            var isSameStatus = true;
            
            for (var k in rows) {
                if (rows[k]['wfmstatusid'] != firstRowStatusId) {
                    isSameStatus = false;
                    break;
                }
            }
            
            if (!isSameStatus) {
                new PNotify({
                    title: 'Info',
                    text: 'Ижил төлөвтэй мөр сонгоно уу!', 
                    type: 'info',
                    sticker: false, 
                    addclass: pnotifyPosition
                });
                return;
            }
        }
        
        var dialogName = '#dialog-dvrecordmap-set';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }                          
        var $dialog = $(dialogName);
        
        $.ajax({
            type: 'post',
            url: 'mddatamodel/setFormDvDmRecordMap',
            data: {dvId: dvId, refStrId: refStrId, rowsLength: rowsLength, row: firstRow},
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                $dialog.empty().append(data);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('dmrmap_connect'),
                    width: 550,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            
                            var $form = $dialog.find('form');
                            $form.validate({errorPlacement: function() {}});
                            
                            if ($form.valid()) {
                                
                                $form.ajaxSubmit({
                                    type: 'post',
                                    url: 'mddatamodel/saveDvDmRecordMap',
                                    dataType: 'json',
                                    beforeSubmit: function(formData, jqForm, options) {
                                        formData.push({ name: 'rows', value: JSON.stringify(rows)});
                                    },
                                    beforeSend: function() {
                                        Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                    },
                                    success: function(data) {
                                        
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                            
                                        if (data.status === 'success') {
                                            $dialog.dialog('close');
                                        }
                                        
                                        Core.unblockUI();
                                    }
                                });
                            }
                            
                        }},
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open'); 
                
                Core.initSelect2($dialog);
                Core.unblockUI();
            }, 
            error: function () { alert('Error!'); Core.unblockUI(); } 
        }); 
        
    } else {
        new PNotify({
            title: 'Info', 
            text: plang.get('msg_pls_list_select'), 
            type: 'info', 
            sticker: false, 
            addclass: pnotifyPosition
        });
    }
}
function dvDmRecordMapList(elem, dvId) {
    
    PNotify.removeAll();
    var rows = getDataViewSelectedRows(dvId);
    
    if (rows.length) {
        
        var firstRow = rows[0];
        
        var dialogName = '#dialog-dvrecordmap-list';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }                          
        var $dialog = $(dialogName);
        
        $.ajax({
            type: 'post',
            url: 'mddatamodel/historyDvDmRecordMap',
            data: {dvId: dvId, id: firstRow.id},
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                $dialog.empty().append(data);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('dmrmap_history'),
                    width: 800,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open'); 
                
                Core.initSelect2($dialog);
                Core.unblockUI();
            }, 
            error: function () { alert('Error!'); Core.unblockUI(); } 
        }); 
        
    } else {
        new PNotify({
            title: 'Info', 
            text: plang.get('msg_pls_list_select'), 
            type: 'info', 
            sticker: false, 
            addclass: pnotifyPosition
        });
    }
}
function bpFingerImageDataDataView(elem, paramPath, uniqId) {
    var getRegNum = $(elem).closest('.xyp-finger-input').find('input[type="text"]').val();
    var $selectorRetRegNum = $(elem).closest('.xyp-finger-input').find('input[type="text"]');

    if (getRegNum === '') {
        $selectorRetRegNum.addClass('error').focus();
        PNotify.removeAll();
        new PNotify({
            title: 'Анхааруулга',
            text: 'Регистерийн дугаар оруулна уу!',
            type: 'warning',
            addclass: pnotifyPosition,
            sticker: false
        });        
        return;
    }
    $selectorRetRegNum.removeClass('error');

    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");
        var ws = new WebSocket("ws://localhost:58324/socket");
        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"finger_image", "dateTime":"' + currentDateTime + '", details: []}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);
            if (jsonData.status == 'success') {
                $.ajax({
                    type: 'post',
                    url: 'mddoc/tempFileSave',
                    data: {
                        finger: jsonData.details[0].value
                    },
                    dataType: 'json',
                    success: function (data) {

                        $.ajax({
                            type: 'post',
                            url: 'mdobject/getXypInfoDataView',
                            data: {
                                filePath: data.filePath,
                                registerNum: getRegNum
                            },
                            dataType: 'json',
                            success: function (data) {
        
                                PNotify.removeAll();
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    addclass: pnotifyPosition,
                                    sticker: false
                                });

                                if (data.status === 'success') {
                                    var $selectorXypInfo = $(elem).closest('.xyp-finger-input');

                                    if(!$selectorXypInfo.find('span.xyp-information').length) {
                                        $selectorXypInfo.append('<span class="xyp-information"></span>');
                                    } else {
                                        $selectorXypInfo.find('span.xyp-information').empty();
                                    }                                    
                                    for (var key in data.data) {
                                        $selectorXypInfo.find('span.xyp-information').append('<input type="hidden" name="param['+key+']" value="'+data.data[key]+'"/>');
                                    }
                                    $("button.dataview-default-filter-btn", "#object-value-list-" + uniqId).trigger('click');
                                }
                                
                                Core.unblockUI();
                            }
                        });                        
                        
                        Core.unblockUI();
                    }
                });
                
            } else {
                var resultJson = {
                    Status: 'Error',
                    Error: jsonData.message
                }

                new PNotify({
                    title: jsonData.status,
                    text: (jsonData.description !== 'undefined') ? jsonData.description : 'Амжилтгүй боллоо',
                    type: jsonData.status,
                    sticker: false
                });
                console.log(JSON.stringify(resultJson));
            }
        };

        ws.onerror = function (event) {
            var resultJson = {
                Status: 'Error',
                Error: event.code
            }

            console.log(JSON.stringify(resultJson));
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
        };
    } else {
        var resultJson = {
            Status: 'Error',
            Error: "WebSocket NOT supported by your Browser!"
        }

        console.log(JSON.stringify(resultJson));
    }
}
function userDefAssignWfmStatus(elem, ruleId, dvId) {
    $.ajax({
        type: 'post',
        url: 'mdworkflow/userDefAssignWfmStatus',
        data: {ruleId: ruleId, dataViewId: dvId},
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            var $dialogName = 'dialog-userdefassignwfmstatus';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            $dialog.empty().append(data);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('MET_99990846'),
                width: 1100,
                height: 'auto',
                maxHeight: $(window).height() - 10,
                modal: true,
                open: function () {
                    Core.unblockUI();
                },
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('save_btn'), "class": 'btn green-meadow btn-sm', click: function () {
                        
                        $('#form-userdefassignwfmstatus', '#' + $dialogName).ajaxSubmit({
                            type: 'post',
                            url: 'mdobject/setRowWfmStatus',
                            dataType: 'json',
                            beforeSubmit: function(formData, jqForm, options) {
                                
                                var selectedRows = getDataViewSelectedRows(dvId), selectedRow = selectedRows[0];
        
                                formData.push(
                                    {name: 'recordId', value: selectedRow.hasOwnProperty('id') ? selectedRow.id : '0'}, 
                                    {name: 'wfmStatusId', value: selectedRow.wfmstatusid}, 
                                    {name: 'ruleId', value: $dialog.find('input[name="wfmRuleId"]').val()},
                                    {name: 'waitTime', value: $dialog.find('input[name="waitTime"]').val()},
                                    {name: 'waitStatusId', value: $dialog.find('input[name="waitStatusId"]').val()}, 
                                    {name: 'selectedRow', value: JSON.stringify(selectedRow)}
                                );
                            },
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function(data) {
                                PNotify.removeAll();
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false
                                });
                                if (data.status === 'success') {
                                    $dialog.dialog('close');
                                }
                                Core.unblockUI();
                            }, 
                            error: function () { alert('Error!'); Core.unblockUI(); } 
                        });
                    }}, 
                    {text: plang.get('close_btn'), "class": 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            }).dialogExtend({
                'closable': true,
                'maximizable': true,
                'minimizable': false,
                'collapsable': false,
                'dblclick': 'maximize',
                'minimizeLocation': 'left',
                'icons': {
                    'close': 'ui-icon-circle-close',
                    'maximize': 'ui-icon-extlink',
                    'minimize': 'ui-icon-minus',
                    'collapse': 'ui-icon-triangle-1-s',
                    'restore': 'ui-icon-newwin'
                }
            });
            $dialog.dialog('open');
        }
    });
}
function addDataViewKpiCriteria(elem, refStructureId) {
    
    PNotify.removeAll();
    
    var $this = $(elem);
    var $parent = $this.closest('[data-meta-type="dv"]');
    var dvId = $parent.attr('data-process-id');
    var $row = $this.closest('.input-group-criteria');
    var $path = $row.find('[data-path]');
    var fieldPath = $path.attr('data-path').toLowerCase();
    
    var $dialogName = 'dialog-dvkpi-'+dvId+'-'+fieldPath;
    
    if (!$("#" + $dialogName).length) {
        
        $.ajax({
            type: 'post',
            url: 'mdform/getKpiTemplatesByRefStrId',
            data: {refStructureId: refStructureId},
            dataType: 'json', 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success:function(data) {
                if (data.status === 'success') {
                    
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    var $dialog = $('#' + $dialogName);
        
                    $dialog.empty().append(data.html);
                    $dialog.dialog({
                        appendTo: $parent,
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: $path.attr('placeholder') + ' - KPI шүүлт',
                        width: 600,
                        height: 'auto',
                        maxHeight: $(window).height() - 50,
                        modal: true,
                        open: function () {
                            Core.initSelect2($dialog);
                            Core.unblockUI();
                        },
                        buttons: [
                            {text: plang.get('save_btn'), "class": 'btn green-meadow btn-sm', click: function () {
                                
                                var $kpiSections = $dialog.find('.dv-kpiform-criteria'), kpiCriteria = {}, 
                                    i = 0, isEmpty = true;
                                
                                $kpiSections.each(function() {
                                    var $kpiSection = $(this), $indicators = $kpiSection.find('[data-is-input="1"]');
                                    
                                    if ($indicators.length) {
                                        
                                        var kpiIndicators = {}, kpiRow = {};
                                        
                                        $indicators.each(function(index) {
                                            
                                            var rowObj = {}, $indRow = $(this), $input = $indRow.find('[data-col-path]');
                                            
                                            rowObj['id'] = $indRow.find('[data-field-name="indicatorId"]').val();
                                            rowObj['operator'] = '=';
                                            
                                            if ($input.hasClass('decimalInit')) {
                                                rowObj['operand'] = $indRow.find('[data-col-path]').autoNumeric('get');
                                            } else {
                                                rowObj['operand'] = $indRow.find('[data-col-path]').val();
                                            }
                                            
                                            if (rowObj['operand']) {
                                                isEmpty = false;
                                            }
                                            
                                            kpiIndicators[index] = rowObj;
                                        });
                                        
                                        kpiRow['templateId'] = $kpiSection.find('select.kpitemplateid-combo').val();
                                        kpiRow['indicators'] = kpiIndicators;
                                        
                                        kpiCriteria[i] = kpiRow;
                                        i++;
                                    }
                                });
                                
                                var $kpiTextarea = $this.next('textarea.dv-kpi-criteria');
                                
                                if (Object.keys(kpiCriteria).length && !isEmpty) {
                                    
                                    if ($kpiTextarea.length) {
                                        $kpiTextarea.val(JSON.stringify(kpiCriteria));
                                    } else {
                                        $this.after('<textarea name="criteriaKpi['+fieldPath+']" class="d-none dv-kpi-criteria">'+JSON.stringify(kpiCriteria)+'</textarea>');
                                    }
                                    
                                } else {
                                    $kpiTextarea.remove();
                                }
                                
                                $dialog.dialog('close');
                            }}, 
                            {text: plang.get('close_btn'), "class": 'btn blue-madison btn-sm', click: function () {
                                $dialog.dialog('close');
                            }}
                        ]
                    });
                    $dialog.dialog('open');
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        addclass: pnotifyPosition
                    });
                }
                
                Core.unblockUI();
            },
            error:function() { alert('Error'); Core.unblockUI(); }
        });
    } else {
        var $dialog = $('#' + $dialogName);
        $dialog.dialog('open');
    }
}
function dvSaveCriteriaDialog(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    var $dialogName = 'dialog-dvcriteria-dialog';
    $('<div id="' + $dialogName + '"></div>').appendTo('body');
    var $dialog = $('#' + $dialogName), html = [];
    
    html.push('<form>');
        html.push('<div class="form-group row mb-2">');
            html.push('<label class="col-md-3 col-form-label text-right pr0"><span class="required">*</span> Загварын нэр:</label>');
            html.push('<div class="col-md-9"><input type="text" class="form-control" name="title" required="required"></div>');
        html.push('</div>');
        html.push('<div class="form-group row mb-2">');
            html.push('<label class="col-md-3 col-form-label text-right pr0">Тайлбар:</label>');
            html.push('<div class="col-md-9"><textarea class="form-control" name="description" rows="3"></textarea></div>');
        html.push('</div>');
    html.push('</form>');
                            
    $dialog.empty().append(html.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'шүүлт',
        width: 600,
        height: 'auto',
        modal: true,
        close: function () {
            $dialog.dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('save_btn'), "class": 'btn green-meadow btn-sm', click: function () {
                
                PNotify.removeAll();
                var $form = $dialog.find('form');
                $form.validate({ errorPlacement: function() {} });
                
                if ($form.valid()) {
                    
                    var postData = paramDataToObject(paramData);
                    
                    $form.ajaxSubmit({
                        type: 'post',
                        url: 'mdobject/dataViewDataGrid',
                        dataType: 'json',
                        beforeSubmit: function(formData, jqForm, options) {
                            
                            var $dataGrid = window['objectdatagrid_'+postData.dataViewId];
                            var postParams = $dataGrid.datagrid('options').queryParams;

                            postParams['filterRules'] = getDataViewFilterRules(postData.dataViewId, false);
                            
                            for (var i in postParams) {
                                if (typeof postParams[i] !== 'undefined') {
                                    if (i == 'defaultCriteriaData') {
                                        postParams[i] += '&isSaveCriteriaTemplate=1';
                                        postParams[i] += '&isReturnCriteriaTemplateId=1';
                                        postParams[i] += '&criteriaTemplateName='+$form.find('input[name="title"]').val();
                                        postParams[i] += '&criteriaTemplateDescription='+$form.find('textarea[name="description"]').val();
                                    }
                                    formData.push({name: i, value: postParams[i]});
                                }
                            }
                        },
                        beforeSend: function() {
                            Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                        },
                        success: function(response) {
                            
                            if (response.status == 'success') {
                                
                                $dialog.dialog('close');
                                
                                if (postData.hasOwnProperty('processId') && postData.processId) {
                                    
                                    var $parent = $(elem).closest('[data-meta-type="dv"]');
                                    var dvCode = $parent.attr('data-meta-code');
                                    var $processBtn = $parent.find('[data-dvbtn-processcode][onclick*="\''+postData.processId+'\'"]:eq(0)');
                                    
                                    if ($processBtn.length) {
                                        _processAddonParam['addonJsonParam'] = JSON.stringify({criteriaTemplateId: response.id});
                                        $processBtn.click();
                                    } else {
                                        _processPostParam = 'criteriaTemplateId=' + response.id;
                                        callWebServiceByMeta(postData.processId, true, '', false, {callerType: dvCode, isMenu: false});
                                    }
                                }
                                
                            } else {
                                new PNotify({
                                    title: response.status,
                                    text: response.message,
                                    type: response.status,
                                    sticker: false
                                });
                            }
                            
                            Core.unblockUI();
                        }
                    });
                }
            }}, 
            {text: plang.get('close_btn'), "class": 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}
function dvReSelectionRows(grid, panel) {
    var op = grid.datagrid('options');
    var $selectedRows = panel.find('.datagrid-view2').find('tr.datagrid-row-selected');
    
    grid.datagrid('clearSelections');
    
    if (op.idField === null) {
        //grid.datagrid('unselectAll');
        if ($selectedRows.length) {
            $selectedRows.each(function() {
                var $this = $(this);
                grid.datagrid('selectRow', $this.attr('datagrid-row-index'));
            });
        }
    } else {
        //grid.treegrid('unselectAll');
        if ($selectedRows.length) {
            $selectedRows.each(function() {
                var $this = $(this);
                grid.treegrid('select', $this.attr('node-id'));
            });
        }
    }
}
function pinCodeChangeWfmStatusId(elem, bpObj, wfmStatusId, metaDataId, refStructureId, newWfmStatusColor, newWfmStatusName) {
    var pinCodeArguments = arguments;
    var $dialogName = '#dialog-wfmstatus-pincode';
    if (!$($dialogName).length) {
        $('<div id="' + $dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $($dialogName);
    var html = '<form class="form-horizontal" id="form-wfmstatus-pincode" method="post" autocomplete="off">'+
        '<input type="password" autocomplete="password" style="display:none" />'+
        '<input type="password" autocomplete="username" style="display:none" />'+    
        '<div class="col-md-12 xs-form">'+
            '<div class="form-group row">'+
                '<label for="pinCode" class="col-form-label col-form-label-lg pt2 col-md-3"><span class="required">*</span>'+plang.get('MET_331182')+':</label>'+
                '<div class="col-md-9">'+
                    '<div class="input-group">'+
                        '<input type="password" id="pinCode" name="pinCode" class="form-control font-weight-bold readonly-white-bg" autocomplete="off" required="required" readonly="readonly" onfocus="this.removeAttribute(\'readonly\');">'+
                        '<span class="input-group-btn">'+
                            '<button class="btn default btn-sm mr0" type="button" onclick="pinPasswordShow(this);"><i class="fa fa-eye"></i></button>'+
                        '</span>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</form>';

    $dialog.empty().append(html);
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: '',
        width: 500,
        minWidth: 500,
        height: 'auto',
        modal: true,
        closeOnEscape: isCloseOnEscape,
        open: function() {
            $(this).keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER) {
                    $(this).parent().find(".ui-dialog-buttonpane button.pincode-verify").trigger("click");
                }
            });
        },
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [{
                text: 'Пин код сэргээх',
                class: 'btn btn-sm purple-plum float-left',
                click: function() {
                    $.ajax({
                        type: 'post',
                        url: 'mduser/pinCodeReset',
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(dataCheck) {
                            PNotify.removeAll();

                            new PNotify({
                                title: dataCheck.status,
                                text: dataCheck.message,
                                type: dataCheck.status,
                                sticker: false, 
                                addclass: pnotifyPosition
                            });

                            Core.unblockUI();
                        },
                        error: function() { alert('Error'); }
                    });
                    clearConsole();
                }
            },
            {
                text: 'Пин код солих',
                class: 'btn btn-sm purple-plum float-left',
                click: function() {
                    var $dialogNameForm = '#dialog-change-password';
                    if (!$($dialogNameForm).length) {
                        $('<div id="' + $dialogNameForm.replace('#', '') + '"></div>').appendTo('body');
                    }
                    var $dialogForm = $($dialogNameForm);

                    $.ajax({
                        type: 'post',
                        url: 'profile/changePasswordForm',
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {
                            $dialogForm.empty().append(data.html);
                            $dialogForm.dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: data.title,
                                width: 500,
                                minWidth: 500,
                                height: "auto",
                                modal: true,
                                closeOnEscape: isCloseOnEscape,
                                close: function() {
                                    $dialogForm.empty().dialog('destroy').remove();
                                },
                                buttons: [{
                                        text: data.save_btn,
                                        "class": 'btn btn-sm green-meadow',
                                        click: function() {
                                            $("#form-change-password").validate({
                                                rules: {
                                                    currentPassword: {
                                                        required: true
                                                    },
                                                    newPassword: {
                                                        required: true
                                                    },
                                                    confirmPassword: {
                                                        required: true,
                                                        equalTo: "#newPassword"
                                                    }
                                                },
                                                messages: {
                                                    currentPassword: {
                                                        required: plang.get('user_insert_password')
                                                    },
                                                    newPassword: {
                                                        required: plang.get('user_insert_password')
                                                    },
                                                    confirmPassword: {
                                                        required: plang.get('user_insert_password'),
                                                        equalTo: plang.get('user_equal_password')
                                                    }
                                                }
                                            });

                                            if ($('#form-change-password').valid()) {
                                                $.ajax({
                                                    type: 'post',
                                                    url: 'mduser/changePinCode',
                                                    data: $('#form-change-password').serialize(),
                                                    dataType: 'json',
                                                    beforeSend: function() {
                                                        Core.blockUI({message: 'Loading...', boxed: true});
                                                    },
                                                    success: function(data) {
                                                        PNotify.removeAll();
                                                        new PNotify({
                                                            title: data.status,
                                                            text: data.message,
                                                            type: data.status,
                                                            sticker: false, 
                                                            addclass: pnotifyPosition
                                                        });
                                                        if (data.status === 'success') {
                                                            $dialogForm.dialog('close');
                                                        }
                                                        Core.unblockUI();
                                                    },
                                                    error: function() { alert("Error"); }
                                                });
                                            }
                                        }
                                    },
                                    {
                                        text: data.close_btn,
                                        "class": 'btn btn-sm blue-hoki',
                                        click: function() {
                                            $dialogForm.dialog('close');
                                        }
                                    }
                                ]
                            });
                            $dialogForm.dialog('open');
                            Core.unblockUI();
                        },
                        error: function() { alert("Error"); }
                    });
                }
            },
            {
                text: plang.get('FIN_CONFIRM'),
                class: 'btn btn-sm green-meadow pincode-verify',
                click: function() {
                    
                    $('#form-wfmstatus-pincode').validate({ errorPlacement: function() {} });

                    if ($('#form-wfmstatus-pincode').valid()) {
                        $.ajax({
                            type: 'post',
                            url: 'mduser/checkPinCode',
                            data: $('#form-wfmstatus-pincode').serialize(),
                            dataType: 'json',
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function(data) {
                                PNotify.removeAll();

                                if (data.status === 'success') {
                                    
                                    $dialog.dialog('close');
                                    
                                    if (typeof bpObj == 'undefined') {
                                        var args = Array.prototype.slice.call(pinCodeArguments);
                                        args.splice(1, 1);     
                                        window['changeWfmStatusId'].apply(elem, args);
                                    } else {
                                        
                                        var rows = getDataViewSelectedRows(bpObj.mainMetaDataId);  
                                        var row = rows[0];

                                        if (row && row.hasOwnProperty('signatureimage')) {
                                            var funcArguments = [bpObj.mainMetaDataId, bpObj.processMetaDataId, bpObj.metaTypeId, bpObj.whereFrom, elem, bpObj.params, bpObj.dataGrid, bpObj.wfmStatusParams, bpObj.drillDownType];
                                            bpWatermarkByPdf(row, function () { window['privateTransferProcessAction'].apply(elem, funcArguments) });
                                        } else {
                                            var funcArguments = [bpObj.mainMetaDataId, bpObj.processMetaDataId, bpObj.metaTypeId, bpObj.whereFrom, elem, bpObj.params, bpObj.dataGrid, bpObj.wfmStatusParams, bpObj.drillDownType];
                                            window['privateTransferProcessAction'].apply(elem, funcArguments);
                                        }
                                    }
                                    
                                } else {
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false, 
                                        addclass: pnotifyPosition
                                    });
                                }
                                
                                Core.unblockUI();
                            },
                            error: function() { alert("Error"); Core.unblockUI(); }
                        });
                    }
                }
            },
            {
                text: plang.get('close_btn'),
                class: 'btn btn-sm blue-hoki',
                click: function() {
                    $dialog.dialog('close');
                }
            }
        ]
    });
    $dialog.dialog('open');
}
function otpChangeWfmStatusId(elem, bpObj, wfmStatusId, metaDataId, refStructureId, newWfmStatusColor, newWfmStatusName) {
    var pinCodeArguments = arguments;
    $.ajax({
        type: 'post',
        url: 'mduser/otpForm',
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            if (data.status == 'success') {
                
                var $dialogName = 'dialog-wfmstatus-otp';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);

                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Confirm',
                    width: 500,
                    height: 'auto',
                    modal: true,
                    open: function () {
                        Core.unblockUI();
                    },
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('continue_btn'), "class": 'btn green-meadow btn-sm d-none otp-continue-btn', click: function (e) {
                            
                            var $otpMode = $dialog.find('button.otp-btn-mode.btn-primary');
                            
                            if ($otpMode.length) {
                                
                                var $thisBtn = $(e.target);
                                $thisBtn.prop('disabled', true).prepend('<i class="icon-spinner4 spinner-sm mr-1"></i> ');
                                var sendType = $otpMode.attr('data-mode');

                                PNotify.removeAll();

                                $.ajax({
                                    type: 'post',
                                    url: 'mduser/sendOtp',
                                    data: {sendType: sendType},
                                    dataType: 'json',
                                    success: function(data) {

                                        if (data.status == 'success') {

                                            $dialog.find('.otp-choose-mode').addClass('d-none');
                                            
                                            var $otpInputMode = $dialog.find('.otp-input-mode'), $dialogParent = $dialog.parent();
                                            
                                            $otpInputMode.removeClass('d-none');
                                            $otpInputMode.find('.alert').html(data.message);
                                            $otpInputMode.find('input').focus();
                                            
                                            $dialogParent.find('.otp-continue-btn').addClass('d-none');
                                            $dialogParent.find('.otp-verify-btn').removeClass('d-none');

                                        } else {
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false, 
                                                addclass: 'pnotify-center'
                                            });
                                        }

                                        $thisBtn.prop('disabled', false).find('i').remove();
                                    }
                                });
                            }
                        }},
                        {text: plang.get('FIN_CONFIRM'), "class": 'btn green-meadow btn-sm d-none otp-verify-btn', click: function (e) {
                            
                            var $thisBtn = $(e.target);
                            var $input = $dialog.find('input[type="text"]');
                            var code = $input.val().trim();

                            $input.removeClass('border-danger');

                            if (code.length) {

                                PNotify.removeAll();

                                $.ajax({
                                    type: 'post',
                                    url: 'login/checkVerificationCode',
                                    data: {code: code},
                                    dataType: 'json',
                                    beforeSend: function() {
                                        $thisBtn.prop('disabled', true);
                                    },
                                    success: function(data) {
                                        if (data.status == 'success') {
                                            $dialog.dialog('close');
                                    
                                            if (typeof bpObj == 'undefined') {
                                                var args = Array.prototype.slice.call(pinCodeArguments);
                                                args.splice(1, 1);     
                                                window['changeWfmStatusId'].apply(elem, args);
                                            } else {
                                                var funcArguments = [bpObj.mainMetaDataId, bpObj.processMetaDataId, bpObj.metaTypeId, bpObj.whereFrom, elem, bpObj.params, bpObj.dataGrid, bpObj.wfmStatusParams, bpObj.drillDownType];
                                                window['privateTransferProcessAction'].apply(elem, funcArguments);
                                            }
                                            
                                        } else {
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false, 
                                                addclass: 'pnotify-center'
                                            });
                                        }

                                        $thisBtn.prop('disabled', false);
                                    }
                                });

                            } else {
                                $input.addClass('border-danger');
                            }
                        }},
                        {text: plang.get('close_btn'), "class": 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });

                $dialog.dialog('open');
                
                $dialog.on('click', 'button.otp-btn-mode', function() {
                    var $this = $(this), $parent = $this.closest('.form-group');
                    
                    $parent.find('.btn-primary').removeClass('btn-primary').addClass('btn-light');
                    $parent.find('.icon-checkmark-circle').removeClass('icon-checkmark-circle').addClass('icon-circle');
                    
                    $this.removeClass('btn-light').addClass('btn-primary');
                    $this.find('i').removeClass('icon-circle').addClass('icon-checkmark-circle');
                    
                    $dialog.parent().find('.otp-continue-btn').removeClass('d-none');
                });
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });
            }
        }
    });
}
function dvOnlySearchForm(elem, dvId, path) {
    var $this = $(elem), $parent = $this.closest('.main-action-meta'),     
        $dialogName = 'dialog-dv-onlysearchform';    
    
    if ($parent.find('.' + $dialogName + '[data-spath="'+path+'"]').length) {
        $parent.find('.' + $dialogName + '[data-spath="'+path+'"]').dialog('open');
    } else {
        $parent.append('<div class="' + $dialogName + '" data-spath="'+path+'"></div>');
        
        var $dialog = $parent.find('.' + $dialogName + '[data-spath="'+path+'"]');
        
        $.ajax({
            type: 'post',
            url: 'mdstatement/popupSearch',
            data: {metaDataId: dvId}, 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(html) {
                $dialog.empty().append(html);
                $dialog.dialog({
                    appendTo: $parent,
                    cache: false,
                    resizable: false,
                    draggable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: $this.closest('.form-group').find('.col-form-label').text() + ' - ' + plang.get('do_filter'),
                    width: 550,
                    minWidth: 550,
                    height: 'auto',
                    modal: true,
                    open: function() {
                        
                        if ($this.closest('.height-dynamic').length) {
                            $this.closest('.height-dynamic').css('overflow-y', '');
                        }
                        
                        disableScrolling();
                    },
                    close: function() {
                        
                        if ($this.closest('.height-dynamic').length) {
                            $this.closest('.height-dynamic').css('overflow-y', 'auto');
                        }
                        
                        enableScrolling();
                    },
                    buttons: [
                        {text: plang.get('do_filter'), class: 'btn btn-sm green-meadow', click: function() {
                                
                            var $validForm = $dialog.find('form');
                            $validForm.validate({errorPlacement: function () {}});

                            //if ($validForm.valid()) {
                                
                                $.ajax({
                                    type: 'post',
                                    url: 'mdobject/getRowsIdCommaByEncrypt',
                                    data: {metaDataId: dvId, defaultCriteriaData: $validForm.serialize()}, 
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        var $input = $this.next('input');
                                        var $path = $this.closest('.input-group').find('[data-path]');
                                        
                                        if (data.status == 'success') {
                                            if (data.total) {
                                                if ($input.length) {
                                                    $input.val(data.data);
                                                } else {
                                                    var input = '<input type="hidden" name="idWithComma['+path+']" value="'+data.data+'"/>';
                                                    var button = '<button type="button" class="btn btn-sm btn-danger font-size-12" onclick="dvOnlySearchFormReset(this, \''+path+'\');" title="'+plang.get('clear_btn')+'">'+data.total+'</button>';
                                                    $this.after(input + button);
                                                }
                                                $path.attr('data-idwithcomma', 1);
                                            } else {
                                                $input.remove();
                                                $path.removeAttr('data-idwithcomma');
                                            }
                                        } else {
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false, 
                                                addclass: pnotifyPosition
                                            });
                                            $input.remove();
                                            $path.removeAttr('data-idwithcomma');
                                        }
                                        
                                        $dialog.dialog('close');
                                        Core.unblockUI();
                                    }
                                });
                            //}
                        }}, 
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.parent().draggable({handle: '.ui-dialog-titlebar'});
                Core.initAjax($dialog);
                dvFilterDateCheckInterval($dialog); 
                
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function() { alert('Error'); Core.unblockUI(); }
        });
    }
}
function dvOnlySearchFormReset(elem, path) {
    var $this = $(elem), $parent = $this.closest('.main-action-meta'),    
        $path = $this.closest('.input-group').find('[data-path]'), 
        $dialogName = 'dialog-dv-onlysearchform';   

    $path.removeAttr('data-idwithcomma');
    $parent.find('.' + $dialogName + '[data-spath="'+path+'"]').remove();
    $this.prev('input').remove();
    $this.remove();
}
function dvSelectedRowsSumCount(dataGrid, rows, count) {
    var footerRows = dataGrid.datagrid('getFooterRows');
    
    var buildTbl = '<table class="table table-hover mb0"><tbody>';
        buildTbl += '<tr><td style="width: 50%;text-align:right;padding: 5px 8px;">Сонгогдсон мөрийн тоо :</td><td style="width: 50%;text-align:left;font-weight:bold;padding: 5px 8px;">'+count+'</td></tr>';
        
    if (typeof footerRows !== 'undefined' && footerRows.length) {
        var footerRow = footerRows[0];

        for (var fieldName in footerRow) {
            var colOpts = dataGrid.datagrid('getColumnOption', fieldName);
            var n = 0, sum = 0;

            for (n; n < count; n++) { 
                sum += Number(rows[n][fieldName]);
            }

            if(colOpts)
                buildTbl += '<tr><td style="text-align:right;padding: 5px 8px;" class="first-letter-upper">'+colOpts.title+' :</td><td style="text-align:left;padding: 5px 8px;">'+number_format(sum, 2, '.', ',')+'</td></tr>';
        }

    } else {
        var $panelView = dataGrid.datagrid('getPanel').children('div.datagrid-view'), 
            $panelFilterRow = $panelView.find('.datagrid-filter-row'),
            $decimalFields = $panelFilterRow.find('.bigdecimalInit'); 

        if ($decimalFields.length) {
            $decimalFields.each(function(){
                var fieldName = $(this).attr('name');
                var colOpts = dataGrid.datagrid('getColumnOption', fieldName);
                var n = 0, sum = 0;

                for (n; n < count; n++) { 
                    sum += Number(rows[n][fieldName]);
                }

                if(colOpts)
                    buildTbl += '<tr><td style="text-align:right;padding: 5px 8px;" class="first-letter-upper">'+colOpts.title+' :</td><td style="text-align:left;padding: 5px 8px;">'+number_format(sum, 2, '.', ',')+'</td></tr>';
            });
        }
    }

    buildTbl += '</tbody></table>';
        
    return buildTbl;
}
function dvSelectedRowsSumCountJson(dataGrid, rows, count) {
    
    var buildJson = {};
    
    if (!dataGrid.hasClass('jstree')) {
    
        var footerRows = dataGrid.datagrid('getFooterRows');
        
        if (typeof footerRows !== 'undefined' && footerRows.length) {
            var footerRow = footerRows[0];

            for (var fieldName in footerRow) {
                var colOpts = dataGrid.datagrid('getColumnOption', fieldName);
                var n = 0, sum = 0;

                for (n; n < count; n++) { 
                    sum += Number(rows[n][fieldName]);
                }

                if (colOpts) {
                    buildJson[fieldName] = sum;
                }
            }

        } else {
            var $panelView = dataGrid.datagrid('getPanel').children('div.datagrid-view'), 
                $panelFilterRow = $panelView.find('.datagrid-filter-row'),
                $decimalFields = $panelFilterRow.find('.bigdecimalInit'); 

            if ($decimalFields.length) {
                $decimalFields.each(function() {
                    var fieldName = $(this).attr('name');
                    var colOpts = dataGrid.datagrid('getColumnOption', fieldName);
                    var n = 0, sum = 0;

                    for (n; n < count; n++) { 
                        sum += Number(rows[n][fieldName]);
                    }

                    if (colOpts) {
                        buildJson[fieldName] = sum;
                    }
                });
            }
        }
    }
        
    return buildJson;
}
function dvSelectionRowsSumCount(dataGrid) {
    var rows = dataGrid.datagrid('getSelections');
    var selectionCount = rows.length;
    
    if (selectionCount) {
        
        var $dialogName = 'dialog-dv-column-sum';
        if(!$("#" + $dialogName).length){$('<div id="' + $dialogName + '"></div>').appendTo('body');}
        var $dialog = $('#' + $dialogName);
        
        var buildTbl = dvSelectedRowsSumCount(dataGrid, rows, selectionCount);
        
        $dialog.empty().append(buildTbl);
        $dialog.dialog({
            cache: false,
            resizable: false,
            draggable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Баганын нийлбэр',
            width: 380, 
            height: 'auto',
            maxHeight: 1000, 
            modal: true,
            buttons: [
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-save', click: function() {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
        
    } else {
        alert(plang.get('msg_pls_list_select'));
    }
    return;
}
function dvFilterIconControl(elem, mainDvId, controlDvId) {
    $.ajax({
        type: 'post',
        url: 'mddatamodel/dvFilterLookupSuggestVal',
        data: {mainDvId: mainDvId, controlDvId: controlDvId},
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
            if (!$().bootstrapDualListbox) {
                $.cachedScript('assets/core/js/plugins/forms/inputs/duallistbox/duallistbox.min.js');
            }
        },
        success: function(data) {
            
            if (data.status == 'success') {
                var $dialogName = 'dialog-dv-filtericoncontrol';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);

                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Favorite filter',
                    width: 650,
                    height: "auto",
                    modal: true,
                    open: function () {
                        $dialog.find('.listbox').bootstrapDualListbox({moveOnSelect: false, selectorMinimalHeight: 300, helperSelectNamePostfix: '[]'});
                        $dialog.dialog('option', 'position', {my: 'center', at: 'center', of: window});
                    },
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            var $form = $dialog.find('form');
                            $form.ajaxSubmit({
                                type: 'post',
                                url: 'mddatamodel/dvFilterLookupSuggestValSave',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                beforeSubmit: function(formData, jqForm, options) {
                                    formData.push({name: 'mainDvId', value: mainDvId});
                                    formData.push({name: 'controlDvId', value: controlDvId});
                                },
                                success: function (response) {
                                    if (response.status == 'success') {
                                        window['dataViewer_' + mainDvId]($("a[data-value='"+ response.objectValueViewType +"']", window['objectWindow_' + mainDvId]), response.objectValueViewType, mainDvId);
                                        $dialog.dialog('close');
                                    } else {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: response.status,
                                            text: response.message, 
                                            type: response.status,
                                            sticker: false, 
                                            addclass: pnotifyPosition
                                        });
                                    }
                                    Core.unblockUI();
                                }
                            });
                        }},
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message, 
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });
            }
            Core.unblockUI();
        }
    });
}
function listenLawParagraph(rowId, paragraphKeyId) {
    $.ajax({
        type: 'post',
        url: 'legal/listenLawParagraph',
        data: {lawid: rowId, paragraphKeyId: paragraphKeyId},
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (response) {
            var $dialogName = 'dialog-listenlawparagraph-' + rowId + '_' + paragraphKeyId;
            
            $('<div class="modal pl0 fade modal-after-save-close dialogoms" id="'+ $dialogName +'" tabindex="-1" role="dialog" aria-hidden="true">'+
                    '<div class="modal-dialog modal-sm" >'+
                        '<div class="modal-content modalcontent">'+
                            '<div class="modal-header">'+
                                '<h4 class="modal-title">'+ plang.get('listen') +'</h4>'+
                                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
                            '</div>'+
                            '<div class="modal-body">'+ response.file +
                            '</div>' +
                            '<div class="modal-footer">'+
                                '<button type="button" data-dismiss="modal" class="btn btn-sm btn-danger">' + plang.get('close_btn') + '</button>'+
                            '</div>' +
                        '</div>'+
                    '</div>'+
                '</div>').appendTo('body');
        
            var $dialog   = $('#' + $dialogName);
            
            $dialog.modal({
                show: false,
                keyboard: false,
                backdrop: 'static'
            });
            
            $dialog.on('shown.bs.modal', function () {
                Core.unblockUI();
            });   
            
            $dialog.draggable({
                handle: ".modal-header"
            });

            $dialog.on('hidden.bs.modal', function () {
                $dialog.remove();
            });            

            $dialog.modal('show');
            
        },
        error: function (jqXHR, exception) {
            Core.unblockUI();
            Core.showErrorMessage(jqXHR, exception);
        }
    });
}
function viewTaskFlowLog(elem, dvId, dvCode, dvUniqId, bpId, bpCode) {
    if (typeof dvUniqId !== 'undefined' && dvUniqId !== '') {
        var rows = getDataViewSelectedRows(dvUniqId);
    } else {
        var rows = getDataViewSelectedRows(dvId);
    }
    
    if (rows.length) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/viewTaskFlowLog',
            data: {dvId: dvId, bpId: bpId, rowData: rows[0]},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({boxed: true, message: 'Loading...'});
                if (!$().steps) {
                    $.cachedScript('assets/core/js/plugins/forms/wizards/steps.min.js');
                }
            },
            success: function(data) {
                
                PNotify.removeAll();
                
                if (data.status != 'success') {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    Core.unblockUI();
                    return;
                }
    
                var $dialogName = 'dialog-viewtaskflowlog';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);

                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Taskflow log',
                    width: 1000,
                    height: 'auto',
                    modal: true,
                    position: { my: 'top', at: 'top+0' },
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    /*buttons: [
                        {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]*/
                });
                $dialog.dialog('open');
                Core.initBPAjax($dialog);
        
                Core.unblockUI();
            }
        });
    } else {
        alert(plang.get('msg_pls_list_select'));
    }
}
function dataViewPivotView(dvId, elem) {

    var windowHeight = $(window).height();
    var dataGrid = window['objectdatagrid_' + dvId];
    var postParams = dataGrid.datagrid('options').queryParams;
    var sortFields = getDataGridSortFields($('div#object-value-list-' + dvId));

    postParams['filterRules'] = getDataViewFilterRules(dvId, false);
    postParams['windowHeight'] = windowHeight;

    if (sortFields != '') {
        postParams['sortFields'] = sortFields;
    }

    $.ajax({
        type: 'post',
        url: 'mdpivot/dataViewPivotView',
        data: postParams,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            if (data.status == 'success') {

                var $dialogName = 'dialog-dv-pivot-' + dvId;
                if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
                var $dialog = $('#' + $dialogName);

                $dialog.empty().append(data.html);
                var $detachedChildren = $dialog.children().detach();

                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    draggable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: $(window).width() - 20,
                    height: windowHeight - 10,
                    modal: true,
                    position: {my: 'top', at: 'top+0'},
                    closeOnEscape: isCloseOnEscape,
                    open: function() {
                        $detachedChildren.appendTo($dialog);
                        Core.initSelect2($dialog);
                    },
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                        text: plang.get('close_btn'),
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }]
                });
                $dialog.dialog('open');

            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }

            Core.unblockUI();
        }
    });
}

$(function () {
    $(document.body).on('click', '.bp-icon-selection > li, .bp-icon-selection > div > li', function () {
        var $this = $(this), $thisBpIconUl = $this.closest('ul.bp-icon-selection'), 
            dataId = $this.attr('data-id'), singleDataId = dataId;
        
        if ($this.hasClass('active') && !$this.closest('.wizard').length) {
            $this.removeClass('active');
            singleDataId = '';
        } else {
            if (typeof $thisBpIconUl.attr('data-choose-type') == 'undefined' || (typeof $thisBpIconUl.attr('data-choose-type') !== 'undefined' && $thisBpIconUl.attr('data-choose-type') != 'multi')) {
                $thisBpIconUl.find('li.active').removeClass('active');
            }
            $this.addClass('active');
        }

        if (typeof $thisBpIconUl.attr('data-choose-type') !== 'undefined') {
            if ($thisBpIconUl.attr('data-choose-type') == 'multi') {
                if ($this.hasClass('active')) {
                    $this.find('input[type="hidden"][data-path]').val(dataId).trigger('change');
                } else {
                    $this.find('input[type="hidden"][data-path]').val('').trigger('change');
                }
            } else {
                $thisBpIconUl.find('input[type="hidden"][data-path]').val(singleDataId).trigger('change');
            }
        }
        
        var $leftwebCriteria = $this.closest('.leftweb-criteria');
        
        if ($leftwebCriteria.length) {
            $leftwebCriteria.find('.dataview-default-filter-btn').click();
        }
    });
    
    $(document.body).on('select2-opening', 'select.dataview-select2', function(e, isTrigger) {
        var $this = $(this), 
            $relateElement = $this.prev('.select2-container:eq(0)');

        if (!$this.hasClass('data-combo-set')) {
            var select2 = $this.data('select2');

            $this.addClass('data-combo-set');
            Core.blockUI({target: $relateElement, animate: false, icon2Only: true});

            var comboDatas = [];
            $.ajax({
                type: 'post',
                async: false,
                url: 'mdwebservice/renderComboDataView/'+$this.data('lookup-id'),
                dataType: 'json',
                success: function(data) {
                    if (typeof data.emptyCombo === 'undefined') {

                        var isMulti = $this.prop('multiple');
                        var editVal = [];

                        if ($this.val() != '' && $this.val() != null && typeof $this.attr('data-edit-value') !== 'undefined' && $this.attr('data-edit-value') !== '') {

                            if (isMulti) {
                                editVal = $this.val();
                            } else {
                                editVal = $this.attr('data-edit-value').split(',');
                            }
                        } 

                        $this.empty();

                        if (!isMulti) {
                            $this.append($('<option />').val('').text(choose));  
                        }

                        $.each(data, function(){
                            if ($.inArray(this.META_VALUE_ID, editVal) != -1) {
                                $this.append($("<option />")
                                    .val(this.META_VALUE_ID)
                                    .text(this.META_VALUE_NAME)
                                    .attr({'selected': 'selected', 'data-row-data': JSON.stringify(this.ROW_DATA)}));
                            } else {
                                $this.append($("<option />")
                                    .val(this.META_VALUE_ID)
                                    .text(this.META_VALUE_NAME));
                            }
                            comboDatas.push({
                                id: this.META_VALUE_ID,
                                text: this.META_VALUE_NAME
                            });                     
                        });
                    }
                },
                error: function() { alert("Ajax Error!"); } 
                
            }).done(function() {
                Core.unblockUI($relateElement);
                $this.select2({results: comboDatas, closeOnSelect: false});
                if (typeof isTrigger === 'undefined' && !select2.opened()) {
                    $this.select2('open');
                }
            });
        }
    });
    
    var timerCellHover; var timerQtipHover;
    
    $(document.body).on('mouseenter', '.datagrid-body .datagrid-cell', function() {
        
        var self = this;
        
        timerCellHover = setTimeout(function() {
            
            if (self.offsetWidth < self.scrollWidth) {

                var $this = $(self);
                var cellText = $this.text().trim();

                if (cellText != '') {
                    $this.qtip({
                        content: {
                            text: '<div style="max-width:600px;max-height:450px;overflow-y:auto;overflow-x:hidden;">' + cellText + '</div>'
                        },
                        position: {
                            effect: false,
                            at: 'center right',
                            my: 'left center',
                            viewport: $(window) 
                        }, 
                        show: {
                            ready: true,
                            effect: false
                        },
                        hide: {
                            effect: false, 
                            fixed: true,
                            delay: 70
                        },
                        style: {
                            classes: 'qtip-bootstrap',
                            tip: {
                                width: 10,
                                height: 5
                            }
                        }, 
                        events: {
                            hidden: function(event, api) {
                                api.destroy(true);
                            }
                        }
                    });
                }
            }
        }, 600);
    });
    
    $(document.body).on('mouseleave', '.datagrid-body .datagrid-cell', function() {
        if (timerCellHover) {
            clearTimeout(timerCellHover);
        }
        if (timerQtipHover) {
            clearTimeout(timerQtipHover);
        }
    }); 
    
    $(document.body).on('mouseenter', '[data-qtip-title]', function() {
        
        var self = this;
        
        timerQtipHover = setTimeout(function() {

            var $this = $(self), cellText = $this.attr('data-qtip-title').trim();

            if (cellText != '') {
                
                var qtipPosAt = 'center right', qtipPosMy = 'left center';
                if ($this.hasAttr('data-qtip-pos') && $this.attr('data-qtip-pos')) {
                    if ($this.attr('data-qtip-pos') == 'top') {
                        qtipPosAt = 'top center';
                        qtipPosMy = 'bottom center';
                    }
                }
                
                $this.qtip({
                    content: {
                        text: '<div style="max-width:600px;max-height:450px;overflow-y:auto;overflow-x:hidden;">' + cellText + '</div>'
                    },
                    position: {
                        effect: false,
                        at: qtipPosAt,
                        my: qtipPosMy,
                        viewport: $(window) 
                    }, 
                    show: {
                        ready: true,
                        effect: false
                    },
                    hide: {
                        effect: false, 
                        fixed: true,
                        delay: 70
                    },
                    style: {
                        classes: 'qtip-bootstrap',
                        tip: {
                            width: 10,
                            height: 5
                        }
                    }, 
                    events: {
                        hidden: function(event, api) {
                            api.destroy(true);
                        }
                    }
                });
            }
        }, 500);
    });
    
    $(document.body).on('mouseleave', '[data-qtip-title]', function() {
        if (timerCellHover) {
            clearTimeout(timerCellHover);
        }
        if (timerQtipHover) {
            clearTimeout(timerQtipHover);
        }
    });  
    
    $.contextMenu({
        selector: '.datagrid-editable-input.datagrid-filter',
        callback: function(key, opt) {
            if (key === 'clearfilter') {
                dvClearColumnFilter(opt.$trigger);
            } 
        },
        items: {
            "clearfilter": {name: plang.get('Баганын шүүлтийг цэвэрлэх'), icon: 'times'}
        }
    });
    
});

function dvSearchFingerPrint(elem) {
    Core.blockUI({
        boxed: true,
        message: 'Loading...'
    });

    if ("WebSocket" in window) {
        console.log("WebSocket is supported by your Browser!");
        // Let us open a web socket
        var ws = new WebSocket("ws://localhost:2801/socket");

        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            ws.send('{"command":"finger_scan", "dateTime":"' + currentDateTime + '"}');
        };

        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);

            if (typeof saveFptLog !== 'undefined' && saveFptLog === '1') {
                var bpUniq = $(elem).closest('div.xs-form').attr('data-bp-uniq-id');
                if (typeof window['bpSaveFptLog_' + bpUniq] === 'function') {
                    window['bpSaveFptLog_' + bpUniq](jsonData);
                }
            }

            if (jsonData.status == 'success') {
                var $details = jsonData.details;
                if ($details.length > 0) {
                    $.each($details, function (i, r) {
                        switch (r['key']) {
                            case 'registernum':
                                $(elem).closest('div.input-group').find('input[type="hidden"]').val(r['value']);
                                $(elem).closest('div.input-group').find('input[type="hidden"]').trigger('change');
                                $(elem).closest('form').find('.dataview-default-filter-btn').trigger('click');
                            break;
                        }
                    });
                } else {
                    new PNotify({
                        title: "Error",
                        text: 'Өгөгдөл олдсонгүй.',
                        type: 'error',
                        sticker: false
                    });

                    return false;
                }
            } else {
                
                var resultJson = {
                    Status: 'Error',
                    Error: (jsonData.message !== 'undefined') ? jsonData.message : 'Амжилтгүй боллоо',
                }
                
                new PNotify({
                    title: (jsonData.status !== 'undefined') ? jsonData.status : 'Error',
                    text: (jsonData.message !== 'undefined') ? jsonData.message : 'Амжилтгүй боллоо',
                    type: (jsonData.status !== 'undefined') ? jsonData.status : 'error',
                    sticker: false
                });

                console.log(JSON.stringify(resultJson));
            }
        };

        ws.onerror = function (event) {
            console.log(event);
            var resultJson = {
                type: 'Error',
                message: plang.get('client_not_working'),
                description: plang.get('client_not_working'),
                error: event.code
            }
                        
            if (typeof saveFptLog !== 'undefined' && saveFptLog === '1') {
                var bpUniq = $(elem).closest('div.xs-form').attr('data-bp-uniq-id');
                if (typeof window['bpSaveFptLog_' + bpUniq] === 'function') {
                    window['bpSaveFptLog_' + bpUniq](resultJson);
                }
            }

            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: plang.get('client_not_working'),
                type: 'error',
                sticker: false
            });
        };

        ws.onclose = function () {
            console.log("Connection is closed...");
        };
    } else {

        var resultJson = {
            Status: 'Error',
            Error: "WebSocket NOT supported by your Browser!"
        }

        new PNotify({
            title: "Error",
            text: 'WebSocket NOT supported by your Browser!',
            type: 'error',
            sticker: false
        });
        
    }

    Core.unblockUI();
}

function bpWatermarkByPdf (selectedRow, callback) {
    if (!(selectedRow && selectedRow.hasOwnProperty('physicalpath'))) { 
        new PNotify({
            title: "Error",
            text: 'Pdf файл олдсонгүй!',
            type: 'error',
            sticker: false
        });
        return false;
    }

    if (!(selectedRow && selectedRow.hasOwnProperty('signatureimage'))) { 
        new PNotify({
            title: "Error",
            text: 'Stamped зураг олдсонгүй!',
            type: 'error',
            sticker: false
        });
        return false;
    }
    
    if (!(selectedRow && selectedRow.hasOwnProperty('contentid'))) { 
        new PNotify({
            title: "Error",
            text: 'Contentid олдсонгүй!',
            type: 'error',
            sticker: false
        });
        return false;
    }
    
    var pdfPath = selectedRow.physicalpath;
    var signatureImage = selectedRow.signatureimage;
    var contentId = selectedRow.contentid;
    var pageSize = typeof selectedRow.pagesize !== 'undefined' && selectedRow.pagesize ? selectedRow.pagesize : '';
    var signaturePosition = typeof selectedRow.signatureposition !== 'undefined' && selectedRow.signatureposition ? selectedRow.signatureposition : '';
    var signaturetext = typeof selectedRow.signaturetext !== 'undefined' && selectedRow.signaturetext ? selectedRow.signaturetext : '';
    var pageStyle = typeof selectedRow.pagestyle !== 'undefined' && selectedRow.pagestyle ? selectedRow.pagestyle : '';

    var $windowHeight =  720;
    var $windowWidth =  491;
    
    switch (pageStyle) {
        case 'landspace':
            $windowWidth =  720;
            $windowHeight =  491;
            break;
        case 'portrait':
                        
            break;
    }

    if (selectedRow && selectedRow.hasOwnProperty('signaturetype') && selectedRow['signaturetype'] === 'all') { 
        setDocumentSign(pdfPath, signatureImage, signaturePosition, 'all', 10, 10, contentId, signaturetext, callback);
    } else {
        if (selectedRow && selectedRow.hasOwnProperty('pagenumber') && selectedRow.hasOwnProperty('signaturex')  && selectedRow.hasOwnProperty('signaturey')) { 
            setDocumentSign(pdfPath, signatureImage, signaturePosition, selectedRow['pagenumber'], selectedRow['signaturex'], selectedRow['signaturey'], contentId, signaturetext, callback);
        } else {
            var $uniqId = getUniqueId('no');
        
            signPdfWithCoordinate = function signPdfWithCoordinate(coordinate) {
                $("#callIframeCanvasHardSign" + $uniqId).empty().dialog('destroy').remove();
        
                /* var funcArguments = [elem, wfmStatusId, metaDataId, refStructureId, newWfmStatusColor, newWfmStatusName]; */
        
                if (typeof pdfPath !== 'undefined') {
                    if (pdfPath.split('.').pop().toLowerCase() === 'pdf') {
                        var paperSize = 573;
                        var minusSize = 0;
                        if (pageSize === 'a5') {
                            paperSize = 470;
                            minusSize = 100; 
                        }
    
                        setDocumentSign(pdfPath, signatureImage, signaturePosition, coordinate.pageNum, Math.floor(1.33333333* coordinate.x), Math.floor(1.33333333 * (paperSize-coordinate.y))-minusSize, contentId, signaturetext, callback);
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: 'PDF файл олдсонгүй.',
                            type: 'error',
                            sticker: false
                        });
                    }
                } else {
                    new PNotify({
                        title: 'Error',
                        text: 'PDF файл олдсонгүй.',
                        type: 'error',
                        sticker: false
                    });
                }
            };
            
            var filename = pdfPath.replace(/^.*[\\\/]/, '');
            iframe = '<iframe id="frameStampPos" src="mddoc/canvasStampPos?uniqid=HardSignWindow&pdfPath='+pdfPath+'" height="100%" width="100%" frameBorder="0"></iframe>';
        
            if (!$("#callIframeCanvasHardSign" + $uniqId).length) {
                var div = document.createElement("div");
                div.id = 'callIframeCanvasHardSign' + $uniqId;
                div.style = 'display: none';
                document.body.appendChild(div);
            }
        
            $("#callIframeCanvasHardSign" + $uniqId).empty().append(iframe);
            $("#callIframeCanvasHardSign" + $uniqId).dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Тамганы байршил',
                width: $windowWidth,
                height: $windowHeight,
                modal: false,
                open: function (event, ui) {
                    $("#callIframeCanvasHardSign" + $uniqId).css('overflow', 'hidden'); 
                },
                close: function () {
                    $("#callIframeCanvasHardSign" + $uniqId).empty().dialog('destroy').remove();
                },
                buttons: [{text: 'Сонгох', class: 'btn blue-madison btn-sm', click: function () {
                    var frame = $('#frameStampPos')[0];
                    frame.contentWindow.postMessage({call:'canvasClickSendValue_HardSignWindow', value: {'pdfPath': pdfPath}})}
                }]
            });
            $("#callIframeCanvasHardSign" + $uniqId).dialog('open');
        }
    } 
}

function setDocumentSign (pdfPath, signatureImage, signaturePosition, pageNum, positionX, positionY, contentId, signaturetext, callback) {
    
    $.ajax({
        type: 'post',
        url: 'mdpki/setDocumentSign',
        data: {
            filePath: pdfPath,
            x: positionX,
            y: positionY,
            pageNum: pageNum,
            signatureimage: signatureImage,
            signatureposition: signaturePosition,
            contentid: contentId,
            signaturetext: signaturetext,
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            Core.unblockUI();
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });
            
            if (data.status === 'success' && typeof callback === 'function') {
                callback();
            }
        },
        error: function (jqXHR, exception) {
            Core.unblockUI();
            Core.showErrorMessage(jqXHR, exception);
        }
    });
}