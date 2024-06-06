/**
 * Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

// This file contains style definitions that can be used by CKEditor plugins.
//
// The most common use for it is the "stylescombo" plugin which shows the Styles drop-down
// list containing all styles in the editor toolbar. Other plugins, like
// the "div" plugin, use a subset of the styles for their features.
//
// If you do not have plugins that depend on this file in your editor build, you can simply
// ignore it. Otherwise it is strongly recommended to customize this file to match your
// website requirements and design properly.
//
// For more information refer to: https://ckeditor.com/docs/ckeditor4/latest/guide/dev_styles.html#style-rules

CKEDITOR.stylesSet.add("addstyles", [
  /* Block styles */

  // These styles are already available in the "Format" drop-down list ("format" plugin),
  // so they are not needed here by default. You may enable them to avoid
  // placing the "Format" combo in the toolbar, maintaining the same features.
  /*
	{ name: 'Paragraph',		element: 'p' },
	{ name: 'Heading 1',		element: 'h1' },
	{ name: 'Heading 2',		element: 'h2' },
	{ name: 'Heading 3',		element: 'h3' },
	{ name: 'Heading 4',		element: 'h4' },
	{ name: 'Heading 5',		element: 'h5' },
	{ name: 'Heading 6',		element: 'h6' },
	{ name: 'Preformatted Text',element: 'pre' },
	{ name: 'Address',			element: 'address' },
	*/

  //   { name: "Italic Title", element: "h2", styles: { "font-style": "italic" } },
  {
    name: "UPPER",
    element: "span",
    styles: {
      "text-transform": "uppercase",
    },
  },
  {
    name: "lower",
    element: "span",
    styles: {
      "text-transform": "lowercase",
    },
  },
  {
    name: "Capitalize",
    element: "span",
    styles: {
      "text-transform": "capitalize",
    },
  },
  {
    name: "Гарчиг ",
    element: "h2",
    styles: {
      color: "#000",
      "font-style": "normal",
      "font-weight": "700",
      "font-size": "20px",
    },
  },
  {
    name: "Дэд гарчиг ",
    element: "h3",
    styles: { color: "#000", "font-weight": "700", "font-size": "18px" },
  },
  {
    name: "Гарчиг",
    element: "h2",
    styles: {
      color: "#3498db",
      "font-style": "normal",
      "font-weight": "700",
      "font-size": "20px",
    },
  },
  {
    name: "Дэд гарчиг",
    element: "h3",
    styles: { color: "#31708f", "font-weight": "700", "font-size": "18px" },
  },
  {
    name: "Дэлгэрэнгүй",
    element: "p",

    styles: {
      color: "#000",
      "font-style": "normal",
      "font-size": "14px",
      "text-align": "justify",
    },
  },
  {
    name: "Мэдээлэл",
    element: "div",
    styles: {
      padding: "20px 15px",
      background: "#E6F1FF",
      border: "1px solid #E6F1FF",
      "border-radius": "4px",
      //   with: "100%",
      //   display: "block",
      "font-size": "12px",
      color: "#3382E7",
    },
  },
  {
    name: "Амжилттай",
    element: "div",
    styles: {
      padding: "20px 15px",
      background: "#DBF6EF",
      "border-left": "4px solid #28AE86",
      //   with: "100%",
      //   display: "block",
      color: "#28AE86",
      "border-radius": "4px",
      "font-size": "12px",
    },
  },

  {
    name: "Aнхааруулга",
    element: "div",
    styles: {
      padding: "20px 15px",
      background: "#FFF0DA",
      "border-left": "4px solid #F19317",
      //   with: "100%",
      //   display: "block",
      "border-radius": "4px",
      color: "#F19317",
      "font-size": "12px",
    },
  },
  {
    name: "Алдаа",
    element: "div",
    styles: {
      padding: "20px 15px",
      background: "#fadad9",
      "border-left": "4px solid #E64442",
      //   with: "100%",
      //   display: "block",
      color: "#E64442",
      "border-radius": "4px",
      "font-size": "12px",
    },
  },

  {
    name: "Жишээ код",
    element: "code",
    styles: {
      padding: "20px 15px",
      margin: "15px 0",
      background: "#2f2e2e",
      border: "1px solid #000000",
      color: "#fff6f6",
      with: "100%",
      "border-radius": "4px",
      display: "block",
      "font-size": "12px",
    },
  },

  /* Inline styles */ // These are core styles available as toolbar buttons. You may opt enabling
  // some of them in the Styles drop-down list, removing them from the toolbar.
  // (This requires the "stylescombo" plugin.)
  /*
	{ name: 'Strong',			element: 'strong', overrides: 'b' },
	{ name: 'Emphasis',			element: 'em'	, overrides: 'i' },
	{ name: 'Underline',		element: 'u' },
	{ name: 'Strikethrough',	element: 'strike' }, 
	{ name: 'Subscript',		element: 'sub' },
	{ name: 'Superscript',		element: 'sup' },
	*/

  { name: "Marker", element: "span", attributes: { class: "marker" } },

  { name: "Big", element: "big" },
  { name: "Small", element: "small" },
  { name: "Typewriter", element: "tt" },

  { name: "Deleted Text", element: "del" },
  { name: "Inserted Text", element: "ins" },

  /* Object styles */

  {
    name: "Styled Image (left)",
    element: "img",
    attributes: { class: "left" },
  },
  {
    name: "Styled Image (right)",
    element: "img",
    attributes: { class: "right" },
  },

  {
    name: "Compact Table",
    element: "table",
    attributes: {
      cellpadding: "5",
      cellspacing: "0",
      border: "1",
      bordercolor: "#ccc",
    },
    styles: {
      "border-collapse": "collapse",
    },
  },

  {
    name: "Borderless Table",
    element: "table",
    styles: { "border-style": "hidden", "background-color": "#E6E6FA" },
  },
  {
    name: "Square Bulleted List",
    element: "ul",
    styles: { "list-style-type": "square" },
  },

  /* Widget styles */

  {
    name: "Clean Image",
    type: "widget",
    widget: "image",
    attributes: { class: "image-clean" },
  },
  {
    name: "Grayscale Image",
    type: "widget",
    widget: "image",
    attributes: { class: "image-grayscale" },
  },

  {
    name: "Featured Snippet",
    type: "widget",
    widget: "codeSnippet",
    attributes: { class: "code-featured" },
  },

  {
    name: "Featured Formula",
    type: "widget",
    widget: "mathjax",
    attributes: { class: "math-featured" },
  },

  {
    name: "240p",
    type: "widget",
    widget: "embedSemantic",
    attributes: { class: "embed-240p" },
    group: "size",
  },
  {
    name: "360p",
    type: "widget",
    widget: "embedSemantic",
    attributes: { class: "embed-360p" },
    group: "size",
  },
  {
    name: "480p",
    type: "widget",
    widget: "embedSemantic",
    attributes: { class: "embed-480p" },
    group: "size",
  },
  {
    name: "720p",
    type: "widget",
    widget: "embedSemantic",
    attributes: { class: "embed-720p" },
    group: "size",
  },
  {
    name: "1080p",
    type: "widget",
    widget: "embedSemantic",
    attributes: { class: "embed-1080p" },
    group: "size",
  },

  // Adding space after the style name is an intended workaround. For now, there
  // is no option to create two styles with the same name for different widget types. See https://dev.ckeditor.com/ticket/16664.
  {
    name: "240p ",
    type: "widget",
    widget: "embed",
    attributes: { class: "embed-240p" },
    group: "size",
  },
  {
    name: "360p ",
    type: "widget",
    widget: "embed",
    attributes: { class: "embed-360p" },
    group: "size",
  },
  {
    name: "480p ",
    type: "widget",
    widget: "embed",
    attributes: { class: "embed-480p" },
    group: "size",
  },
  {
    name: "720p ",
    type: "widget",
    widget: "embed",
    attributes: { class: "embed-720p" },
    group: "size",
  },
  {
    name: "1080p ",
    type: "widget",
    widget: "embed",
    attributes: { class: "embed-1080p" },
    group: "size",
  },
]);
