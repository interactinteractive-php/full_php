/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
  config.enterMode = CKEDITOR.ENTER_P;
  config.shiftEnterMode = CKEDITOR.ENTER_BR;
  config.tabSpaces = 2;
  config.indentation = "40px";
  config.line_height = "1;1.15;1.5;2.0;2.5;3.0";
  // config.codeSnippet_languages = {
  //     javascript: "JavaScript",
  //     php: "PHP",
  //     Typescript: "TYPESCRIPT",
  //     sql: "SQL",
  //     json: "JSON",
  //     html: "HTML",
  //     css: "CSS",
  //     bash: "BASH",
  //   };
  config.stylesSet = "addstyles";
  config.baseHref = URL_APP;
  config.language = "en";
  config.extraPlugins = "uploadimage,lineheight,textindent";
  config.removePlugins = "exportpdf,save,smiley,about,language";
  config.uploadUrl =
    "assets/custom/addon/plugins/ckeditor/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json";
  config.filebrowserBrowseUrl =
    "assets/custom/addon/plugins/ckeditor/plugins/ckfinder/ckfinder.html";
  config.filebrowserImageBrowseUrl =
    "assets/custom/addon/plugins/ckeditor/plugins/ckfinder/ckfinder.html?type=Images";
  config.filebrowserUploadUrl =
    "assets/custom/addon/plugins/ckeditor/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files";
  config.filebrowserImageUploadUrl =
    "assets/custom/addon/plugins/ckeditor/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images";
  config.fontSize_sizes =
    "8pt/8pt;9pt/9pt;10pt/10pt;11pt/11pt;12pt/12pt;14pt/14pt;16pt/16pt;18pt/18pt;20pt/20pt;8px/8px;9px/9px;10px/10px;11px/11px;12px/12px;14px/14px;16px/16px;18px/18px;20px/20px;22px/22px;24px/24px;26px/26px;28px/28px;36px/36px;48px/48px;72px/72px;";
};
