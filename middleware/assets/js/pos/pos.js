var checkInitPosJS = true;
var isClickF5 = false;
var isAcceptPrintPos = true;
var posCandyQrCheckInterval;
var posQpayQrCheckInterval;
var posTokipayQrCheckInterval;
var posSocialPayQrCheckInterval;
var globalLoopPrint = 1,
  coldF9 = true,
  vartypeCancel = "",
  globalOrderData = [],
  isMultiCustomerPrintBill = false,
  lastReadDateOrder = '',
  isConfigItemCheckEndQtyInvoice = true;

$(function () {
  Core.initDecimalPlacesInput();

  /* Hotkey help */

  $(document).unbind("keydown", "F1");
  $(document).bind("keydown", "F1", function (e) {
    posHotKeys();
    e.preventDefault();
    return false;
  });
  $(document.body).unbind(
    "keydown",
    "input, select, textarea, a, button",
    "F1"
  );
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F1",
    function (e) {
      $(this).trigger("change");
      posHotKeys();
      e.preventDefault();
      return false;
    }
  );

  /* Тест баримт хэвлэх */
  if (posTypeCode != 3) {
    $(document).bind("keydown", "F2", function (e) {
      if (typeof posElectronTalonWindow == "undefined") {
        posTestBillPrint();
      }
      e.preventDefault();
      return false;
    });

    $(document.body).on(
      "keydown",
      "input, select, textarea, a, button",
      "F2",
      function (e) {
        if (typeof posElectronTalonWindow == "undefined") {
          posTestBillPrint();
        }
        e.preventDefault();
        return false;
      }
    );
  }
  /* Нэхэмжлэхийн жагсаалт */
  $(document).bind("keydown", "F3", function (e) {
    if (isConfigInvoiceList) {
      if (typeof posElectronTalonWindow !== "undefined") {
        posElectronInvoiceList(this);
      } else {
        posInvoiceList(this);
      }
    }
    e.preventDefault();
    return false;
  });

  $(document.body).on("keydown", "#sejimPhoneNumber", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;
    if (keyCode == 13 && $(this).val() != '') {
      $("#sejimId").val("");
      $("#sejimLastName").val("");
      $("#sejimFirstName").val("");
      $("#sejimEmail").val("");
      $("#sejimGenderId").val("");
      $("#sejimAgeRange").val("");

      $.ajax({
        type: "post",
        url: "api/callProcess",
        data: {
          processCode: "stCrmLead_GET_004",
          paramData: {
            filterPhoneNumber: $(this).val()
          }
        },
        dataType: "json",
        success: function (data) {
          $(".serjim-fields").removeClass("d-none");
          if (data.result) {
            $("#sejimId").val(data.result.id);
            $("#sejimLastName").val(data.result.lastname);
            $("#sejimFirstName").val(data.result.firstname);
            $("#sejimEmail").val(data.result.email);
            $("#sejimGenderId").val(data.result.genderid);
            $("#sejimAgeRange").val(data.result.agerange);
          }
        }
      });
    }
  });

  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F3",
    function (e) {
      if (isConfigInvoiceList) {
        if (typeof posElectronTalonWindow !== "undefined") {
          posElectronInvoiceList(this);
        } else {
          posInvoiceList(this);
        }
      }
      e.preventDefault();
      return false;
    }
  );

  // Virtual Keyboard Render
  if (posTypeCode == "3") {
    $(document.body).on("focus", 'input.bigdecimalInit:not([readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled), input.integerInit:not([readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)',
      function (e) {
        var $this = $(this);
        $this.attr("data-prevent-select-and-caret", "");
        if (isIpad || typeof $this.attr('data-field-name') !== 'undefined') return;

        $this.keyboard({
          usePreview: false,
          autoAccept: "true",
          //            visible: function(e, keyboard, el) {
          //              keyboard.$el.trigger('change').trigger('keyup');
          //            },
          beforeCloseKb: function (e, keyboard, el) {
            setTimeout(function () {
              $this.trigger("change").trigger("keydown");
            }, 100);
          },
          change: function (e, keyboard, el) {
            $this.trigger("keydown");
          },

          visible: function (e, keyboard, el) {

            setTimeout(function () {
              if (keyboard.$preview && keyboard.$preview.length) {
                keyboard.$preview[0].select();
              }
            }, 100);
            if (
              $this.closest(".ui-dialog").length &&
              $(".virtual-keyboard-customtheme").length
            ) {
              $(".virtual-keyboard-customtheme").css(
                "z-index",
                $this.closest(".ui-dialog").css("z-index")
              );
            }
            $(".virtual-keyboard-customtheme").css({
              top:
                parseInt($(".virtual-keyboard-customtheme").css("top"), 10) +
                15 +
                "px",
            });
          },
          display: {
            bksp: "\u2190",
            enterkey: "&nbsp;&nbsp;&nbsp;&nbsp;Enter&nbsp;&nbsp;&nbsp;",
          },
          layout: "custom",
          customLayout: {
            normal: [
              "7 8 9",
              "4 5 6",
              "1 2 3",
              "{bksp} 0 .",
              "{enterkey!!} {c}",
            ],
          },
          css: {
            // input & preview
            input:
              "ui-widget-content ui-corner-all virtual-keyboard-input-customtheme",
            // keyboard container
            container:
              "virtual-keyboard-customtheme virtual-num-keyboard-customtheme",
          },
        });
        $('.virtual-keyboard-customtheme').draggable({ cursor: "move" });
        $.keyboard.keyaction.enterkey = function (base) {
          var e = jQuery.Event("keydown");
          e.keyCode = e.which = 13;
          $this.trigger(e);

          var kb = $this.getkeyboard();
          if (kb.isOpen) {
            kb.accept();
            kb.close();
          }
        };
      }
    );

    $(document.body).on("focus", 'input.form-control:not([type="radio"],.select2, [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, [data-field-name]), textarea', function (e) {
      var $this = $(this);
      if (isIpad) return;
      if (
        $this.attr("name") == "empCustomerId_displayField" ||
        $this.attr("name") == "empCustomerId_nameField"
      ) {
        virtualKeyboard($this, "sidebar");
      } else {
        virtualKeyboard($this);
      }
    });

    $(document.body).on("focus", "input.textbox-text", function (e) {
      var $this = $(this);
      if (isIpad) return;
      virtualKeyboard($this, "sidebar");
    });
  }


  function virtualKeyboard($this, renderType) {
    $this.keyboard({
      usePreview: false,
      autoAccept: true,
      closeByClickEvent: renderType == "sidebar" ? true : false,
      beforeCloseKb: function (e, keyboard, el) {
        setTimeout(function () {
          $this.trigger("change").trigger("keyup");
        }, 100);
      },
      visible: function (e, keyboard, el) {
        if (renderType == "sidebar") {
          $(".virtual-keyboard-customtheme").css({
            left: "280px",
            "z-index": "120000",
          });
        }
        if (
          $this.closest(".ui-dialog").length &&
          $(".virtual-keyboard-customtheme").length
        ) {
          $(".virtual-keyboard-customtheme").css(
            "z-index",
            $this.closest(".ui-dialog").css("z-index")
          );
        }
        $(".virtual-keyboard-customtheme").css({
          top: Number($(".virtual-keyboard-customtheme").css("top")) + 100 + "px",
        });
      },
      change: function (e, keyboard, el) {
        $this.trigger("keydown");
      },
      display: {
        bksp: "\u2190",
        accept: "оруулах",
        cancel: "clear",
        mn: "MN",
        en: "EN",
        enterkey: "Enter",
      },
      layout: "custom",
      customLayout: {
        normal: [
          "` 1 2 3 4 5 6 7 8 9 0 - = {bksp}",
          "{tab} q w e r t y u i o p [ ] \\",
          "a s d f g h j k l ; ' {enterkey!!}",
          "{shift} z x c v b n m , . / {shift}",
          "{mn!!} {en!!} {accept} {space} {cancel}",
        ],
        shift: [
          "~ ! @ # $ % ^ & * ( ) _ + {bksp}",
          "{tab} Q W E R T Y U I O P { } |",
          'A S D F G H J K L : " {enterkey!!}',
          "{shift} Z X C V B N M < > ? {shift}",
          "{mn!!} {en!!} {accept} {space} {cancel}",
        ],
      },
      css: {
        // input & preview
        input:
          "ui-widget-content ui-corner-all virtual-keyboard-input-text-customtheme",
        // keyboard container
        container:
          "virtual-keyboard-customtheme virtual-text-keyboard-customtheme",
      },
    });
    $('.virtual-keyboard-customtheme').draggable({ cursor: "move" });
    $.keyboard.keyaction.enterkey = function (base) {
      var e = jQuery.Event("keydown");
      e.keyCode = e.which = 13;
      $this.trigger(e);

      var kb = $this.getkeyboard();
      if (kb.isOpen) {
        kb.accept();
        kb.close();
      }
    };
    $.keyboard.keyaction.mn = function (base) {
      var kb = $this.getkeyboard();
      (kb.options.customLayout = {
        normal: [
          '= \u2116 - " \u20AE : . _ , % ? \u0435 \u0449 {bksp}',
          "{tab} \u0444 \u0446 \u0443 \u0436 \u044d \u043D \u0433 \u0448 \u04af \u0437 \u043A \u044A \\",
          "\u0439 \u044B \u0431 \u04e9 \u0430 \u0445 \u0440 \u043e \u043B \u0434 \u043f {enter}",
          "{shift} \u044F \u0447 \u0451 \u0441 \u043c \u0438 \u0442 \u044c \u0432 \u044e {shift}",
          "{mn!!} {en!!} {accept} {space} {cancel}",
        ],

        shift: [
          "+ 1 2 3 4 5 6 7 8 9 0 \u0415 \u0429 {bksp}",
          "{tab} \u0424 \u0426 \u0423 \u0416 \u042d \u041D \u0413 \u0428 \u04AE \u0417 \u041a \u042A |",
          "\u0419 \u042B \u0411 \u04e8 \u0410 \u0425 \u0420 \u041e \u041b \u0414 \u041f {enter}",
          "{shift} \u042F \u0427 \u0401 \u0421 \u041c \u0418 \u0422 \u042c \u0412 \u042e {shift}",
          "{mn!!} {en!!} {accept} {space} {cancel}",
        ],
        alt: [
          "` 1 2 3 4 5 6 7 8 9 0 - = {bksp}",
          "{tab} q w e r t y u i o p [ ] \\",
          "a s d f g h j k l ; ' {enter}",
          "{shift} z x c v b n m , . / {shift}",
          "{mn!!} {en!!} {accept} {space} {cancel}",
        ],
        "alt-shift": [
          "~ ! @ # $ % ^ & * ( ) _ + {bksp}",
          "{tab} Q W E R T Y U I O P { } |",
          'A S D F G H J K L : " {enter}',
          "{shift} Z X C V B N M < > ? {shift}",
          "{mn!!} {en!!} {accept} {space} {cancel}",
        ],
      }),
        kb.redraw();
    };
    $.keyboard.keyaction.en = function (base) {
      var kb = $this.getkeyboard();
      (kb.options.customLayout = {
        normal: [
          "` 1 2 3 4 5 6 7 8 9 0 - = {bksp}",
          "{tab} q w e r t y u i o p [ ] \\",
          "a s d f g h j k l ; ' {enter}",
          "{shift} z x c v b n m , . / {shift}",
          "{mn!!} {en!!} {accept} {space} {cancel}",
        ],
        shift: [
          "~ ! @ # $ % ^ & * ( ) _ + {bksp}",
          "{tab} Q W E R T Y U I O P { } |",
          'A S D F G H J K L : " {enter}',
          "{shift} Z X C V B N M < > ? {shift}",
          "{mn!!} {en!!} {accept} {space} {cancel}",
        ],
      }),
        kb.redraw();
    };
  }

  /* Барааны жагсаалтыг дуудах */
  $(document).bind("keydown", "F4", function (e) {
    if (
      $("body").find("#dialog-pos-payment").length > 0 &&
      $("body").find("#dialog-pos-payment").is(":visible")
    ) {
      return;
    }
    $(".pos-item-combogrid-cell a.combo-arrow").click();
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F4",
    function (e) {
      if (
        $("body").find("#dialog-pos-payment").length > 0 &&
        $("body").find("#dialog-pos-payment").is(":visible")
      ) {
        return;
      }
      $(this).trigger("change");
      $(".pos-item-combogrid-cell a.combo-arrow").click();
      e.preventDefault();
      return false;
    }
  );

  /* Төлбөр төлөх */
  $(document).bind("keydown", "F5", function (e) {
    isClickF5 = true;
    if (isBasketOnly) {
      posNoPayment();
    } else {
      if (typeof posElectronTalonWindow == "undefined") {
        var isPrint = posTalonNotLotteryPrintCall();

        if (isPrint == false) {
          posPayment();
        }
      }
    }
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F5",
    function (e) {
      isClickF5 = true;
      $(this).trigger("change");
      if (isBasketOnly) {
        posNoPayment();
      } else {
        if (typeof posElectronTalonWindow == "undefined") {
          var isPrint = posTalonNotLotteryPrintCall();

          if (isPrint == false) {
            posPayment();
          }
        }
      }
      e.preventDefault();
      return false;
    }
  );

  /* Хямдралын дүн */
  $(document).bind("keydown", "F6", function (e) {
    if (typeof posElectronTalonWindow == "undefined") {
      if (
        $("body").find("#dialog-pos-payment").length > 0 &&
        $("body").find("#dialog-pos-payment").is(":visible")
      ) {
        posFocusBillType();
      } else {
        posFocusDiscountInput();
      }
    }
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F6",
    function (e) {
      if (typeof posElectronTalonWindow == "undefined") {
        if (
          $("body").find("#dialog-pos-payment").length > 0 &&
          $("body").find("#dialog-pos-payment").is(":visible")
        ) {
          posFocusBillType();
        } else {
          posFocusDiscountInput();
        }
      }
      e.preventDefault();
      return false;
    }
  );

  /* Хямдрал тооцох */
  $(document).bind("keydown", "F7", function (e) {
    if ($('.pos-append-quick-item').length === 1) {
      $('.pos-append-quick-item').first().trigger('click');
      e.preventDefault();
      return false;
    }
    //if (isConfigRowDiscount && typeof posElectronTalonWindow == 'undefined') {
    if (typeof posElectronTalonWindow == "undefined") {
      posItemDiscountBtn();
    }
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F7",
    function (e) {
      if ($('.pos-append-quick-item').length === 1) {
        $('.pos-append-quick-item').first().trigger('click');
        e.preventDefault();
        return false;
      }
      //if (isConfigRowDiscount) {
      $(this).trigger("change");
      if (typeof posElectronTalonWindow == "undefined") {
        posItemDiscountBtn();
      }
      //}
      e.preventDefault();
      return false;
    }
  );

  /* Мөнгөн дэвсгэрт */
  $(document).bind("keydown", "F8", function (e) {
    if (typeof posElectronTalonWindow == "undefined") {
      posCashMoneyBill();
    }
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F8",
    function (e) {
      $(this).trigger("change");
      if (typeof posElectronTalonWindow == "undefined") {
        posCashMoneyBill();
      }
      e.preventDefault();
      return false;
    }
  );

  /* Хүлээлгийн талон */
  $(document).bind("keydown", "F9", function (e) {
    if (!isBasketOnly) {
      if (typeof posElectronTalonWindow == "undefined") {
        posToBasket();
      }
    }
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F9",
    function (e) {
      $(this).trigger("change");
      if (typeof posElectronTalonWindow == "undefined") {
        posToBasket();
      }
      e.preventDefault();
      return false;
    }
  );

  /* Барааны жагсаалтыг дуудах */
  $(document).bind("keydown", "F10", function (e) {
    isClickF5 = true;
    posItemQtyInputFocus();
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F10",
    function (e) {
      isClickF5 = true;
      $(this).trigger("change");
      posItemQtyInputFocus();
      e.preventDefault();
      return false;
    }
  );
  $(document).bind("keydown", function (e) {
    if (e.which == 46) {
      if (
        $("body").find("#dialog-pos-payment").length > 0 &&
        $("body").find("#dialog-pos-payment").is(":visible")
      ) {
        $('select[name="posBankIdDtl[]"]:visible:last')
          .closest(".pos-bank-row")
          .find('button[data-bank-action="remove"]')
          .trigger("click");
        e.preventDefault();
        return false;
      }
    }
    //        if (e.which == 40) {
    //            var $posBody = $('#posTable > tbody');
    //            if ($posBody.find('> tr[data-item-id]').length == 0) {
    //                return;
    //            }
    //            if (($('.panel-eui.combo-p').length > 0 && $('.panel-eui.combo-p').is(':visible')) || ($('body').find('#dialog-pos-payment').length > 0 && $('body').find('#dialog-pos-payment').is(':visible'))) {
    //                return;
    //            }
    //            posItemQtyInputFocus();
    //            e.preventDefault();
    //            return false;
    //        }
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    function (e) {
      if (e.which == 46) {
        if (
          $("body").find("#dialog-pos-payment").length > 0 &&
          $("body").find("#dialog-pos-payment").is(":visible")
        ) {
          $('select[name="posBankIdDtl[]"]:visible:last')
            .closest(".pos-bank-row")
            .find('button[data-bank-action="remove"]')
            .trigger("click");
          e.preventDefault();
          return false;
        }
      }
      //        if (e.which == 40) {
      //            var $posBody = $('#posTable > tbody');
      //            if ($posBody.find('> tr[data-item-id]').length == 0) {
      //                return;
      //            }
      //            if (($('.panel-eui.combo-p').length > 0 && $('.panel-eui.combo-p').is(':visible')) || ($('body').find('#dialog-pos-payment').length > 0 && $('body').find('#dialog-pos-payment').is(':visible'))) {
      //                return;
      //            }
      //            $(this).trigger('change');
      //            posItemQtyInputFocus();
      //            e.preventDefault();
      //            return false;
      //        }
    }
  );

  /* Банк сонгох */
  $(document).bind("keydown", "F11", function (e) {
    $('select[name="posBankIdDtl[]"]:visible:eq(0)')
      .closest(".pos-bank-row")
      .find("button")
      .trigger("click");
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F11",
    function (e) {
      $('select[name="posBankIdDtl[]"]:visible:eq(0)')
        .closest(".pos-bank-row")
        .find("button")
        .trigger("click");
      e.preventDefault();
      return false;
    }
  );

  /* Талон буцаах */
  $(document).bind("keydown", "F12", function (e) {
    posTalonListReturnProcessCall();
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "F12",
    function (e) {
      posTalonListReturnProcessCall();
      e.preventDefault();
      return false;
    }
  );

  $(document).bind("keydown", "Shift+C", function (e) {
    if ($('input[name="empCustomerId"]').length) {
      $('input[name="empCustomerId"]')
        .closest(".input-group")
        .find("button")
        .trigger("click");
      e.preventDefault();
      return false;
    }
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Shift+C",
    function (e) {
      if ($('input[name="empCustomerId"]').length) {
        $('input[name="empCustomerId"]')
          .closest(".input-group")
          .find("button")
          .trigger("click");
        e.preventDefault();
        return false;
      }
    }
  );

  $(document).bind("keydown", "Shift+Z", function (e) {
    if ($('input[name="voucherDtlSerialNumber[]"]:visible').length) {
      $('input[name="voucherDtlSerialNumber[]"]:visible:eq(0)')
        .focus()
        .select();
      e.preventDefault();
      return false;
    }
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Shift+Z",
    function (e) {
      if ($('input[name="voucherDtlSerialNumber[]"]:visible').length) {
        $('input[name="voucherDtlSerialNumber[]"]:visible:eq(0)')
          .focus()
          .select();
        e.preventDefault();
        return false;
      }
    }
  );

  /* Талоны жагсаалт */
  $(document).bind("keydown", "Shift+F3", function (e) {
    if (typeof posElectronTalonWindow == "undefined") {
      posTalonList();
    }
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Shift+F3",
    function (e) {
      if (typeof posElectronTalonWindow == "undefined") {
        posTalonList();
      }
      e.preventDefault();
      return false;
    }
  );

  /* Хаалт */
  $(document).bind("keydown", "Shift+F8", function (e) {
    closePos();
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Shift+F8",
    function (e) {
      closePos();
      e.preventDefault();
      return false;
    }
  );

  /* Ipterminal жагсаалт */
  $(document).bind("keydown", "Ctrl+B", function (e) {
    posTerminalList();
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Ctrl+B",
    function (e) {
      posTerminalList();
      e.preventDefault();
      return false;
    }
  );

  /* Ipterminal check connection */
  $(document).bind("keydown", "Ctrl+Q", function (e) {
    if (posUseIpTerminal === "0") {
      PNotify.removeAll();
      new PNotify({
        title: "Bank terminal connection",
        text: "IPPOS terminal холболт хийгээгүй байна.",
        type: "error",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }

    Core.blockUI({
      message: "Loading...",
      boxed: true,
    });

    if (bankIpterminals.hasOwnProperty("500000")) {
      posConnectBankTerminal(bankIpterminals["500000"], "khanbank");
    }
    if (bankIpterminals.hasOwnProperty("400000")) {
      posConnectBankTerminal(bankIpterminals["400000"], "tdbank");
    }
    if (bankIpterminals.hasOwnProperty("320000")) {
      posConnectBankTerminal(bankIpterminals["320000"], "xacbank");
    }
    if (bankIpterminals.hasOwnProperty("150000")) {
      posConnectBankTerminal(bankIpterminals["150000"], "golomtbank");
    }
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Ctrl+Q",
    function (e) {
      if (posUseIpTerminal === "0") {
        PNotify.removeAll();
        new PNotify({
          title: "Bank terminal connection",
          text: "IPPOS terminal холболт хийгээгүй байна.",
          type: "error",
          sticker: false,
          addclass: "pnotify-center",
        });
        return;
      }

      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });

      if (bankIpterminals.hasOwnProperty("500000")) {
        posConnectBankTerminal(bankIpterminals["500000"], "khanbank");
      }
      if (bankIpterminals.hasOwnProperty("400000")) {
        posConnectBankTerminal(bankIpterminals["400000"], "tdbank");
      }
      if (bankIpterminals.hasOwnProperty("320000")) {
        posConnectBankTerminal(bankIpterminals["320000"], "xacbank");
      }
      if (bankIpterminals.hasOwnProperty("150000")) {
        posConnectBankTerminal(bankIpterminals["150000"], "golomtbank");
      }
      e.preventDefault();
      return false;
    }
  );

  /* Ipterminal open connection */
  $(document).bind("keydown", "Ctrl+X", function (e) {
    Core.blockUI({
      message: "Loading...",
      boxed: true,
    });
    posOpenIpTerminal();
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Ctrl+X",
    function (e) {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
      posOpenIpTerminal();
      e.preventDefault();
      return false;
    }
  );

  /* Ipterminal close connection */
  $(document).bind("keydown", "Ctrl+M", function (e) {
    posCloseIpTerminal();

    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Ctrl+M",
    function (e) {
      posCloseIpTerminal();
      e.preventDefault();
      return false;
    }
  );

  /* Түр хүлээлгэнд оруулсан талоны жагсаалт */
  $(document).bind("keydown", "Shift+F9", function (e) {
    if (typeof posElectronTalonWindow == "undefined") {
      posBasketListClickBtn();
    }
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Shift+F9",
    function (e) {
      if (typeof posElectronTalonWindow == "undefined") {
        posBasketListClickBtn();
      }
      e.preventDefault();
      return false;
    }
  );
  /**
   * Generate candy QR event duplicate error!!!
   */
  /*$(document.body).on('keydown', 'input, select, textarea, a, button', 'Shift+q', function(e){
      console.log(e)
      posQRSocialPay();
      e.preventDefault();
      return false;
    });*/
  $(document).bind("keydown", "Shift+q", function (e) {
    posQRSocialPay();
    e.preventDefault();
    return false;
  });

  /* Бараа устгах */
  $(document).bind("keydown", "Shift+del", function (e) {
    if (!$("#lockerId").length) {
      posDisplayReset("", false);
    }
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Shift+del",
    function (e) {
      if (!$("#lockerId").length) {
        posDisplayReset("", false);
      }
      e.preventDefault();
      return false;
    }
  );

  /* Мөнгөн дэвсгэрт хэвлэх */
  $(document).bind("keydown", "Alt+t", function (e) {
    if (typeof posElectronTalonWindow == "undefined") {
      posBankNotesPrint();
    }
    e.preventDefault();
    return false;
  });
  $(document.body).on(
    "keydown",
    "input, select, textarea, a, button",
    "Alt+t",
    function (e) {
      if (typeof posElectronTalonWindow == "undefined") {
        var $this = $(this);
        $this.trigger("change");
        posBankNotesPrint();
      }
      e.preventDefault();
      return false;
    }
  );

  posConfigVisibler($("body"));
  posTableSetHeight();
  posFixedHeaderTable();
  posPageLoadEndVisibler();

  $(window).resize(function () {
    posTableSetHeight();
    posFixedHeaderTable();
  });

  posItemCombogridList("");

  $(".pos-item-combogrid-cell").find("input.textbox-text").val("").focus();

  if (isConfigServiceJob) {
    $("#posServiceCode").combogrid({
      panelWidth: 780,
      panelHeight: 400,
      url: "mdpos/getServiceList",
      idField: "jobid",
      textField: "jobname",
      mode: "remote",
      fitColumns: true,
      pagination: true,
      rownumbers: true,
      remoteSort: true,
      singleSelect: false,
      ctrlSelect: true,
      pageList: [10, 20, 50, 100],
      pageSize: 10,
      columns: [
        [
          {
            field: "jobcode",
            title: plang.get("POS_0005"),
            width: 18,
            sortable: true,
          },
          {
            field: "jobname",
            title: plang.get("POS_0006"),
            width: 70,
            sortable: true,
          },
          {
            field: "jobrate",
            title: plang.get("POS_0007"),
            width: 12,
            sortable: true,
            align: "right",
            formatter: gridAmountField,
          },
        ],
      ],
      onDblClickRow: function (index, row) {
        var $posServiceCode = $("#posServiceCode"),
          rows = [];
        rows[0] = row;

        posServiceAddRow(rows);

        $posServiceCode.combogrid("hidePanel");
        $posServiceCode.combogrid("clear", "");
        $posServiceCode.val("");
      },
      onLoadSuccess: function (data) {
        $(this)
          .combogrid("grid")
          .datagrid("getPanel")
          .find(".datagrid-row")
          .css("height", "34px");
      },
      onRowContextMenu: function (e, index, row) {
        e.preventDefault();
        var serviceRows = $(this).datagrid("getSelections");
        $.contextMenu({
          selector:
            ".combo-panel:visible:last .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
          callback: function (key, opt) {
            if (key === "addToList") {
              posServiceAddRow(serviceRows);
            }
          },
          items: {
            addToList: { name: plang.get("POS_0008"), icon: "plus-circle" },
          },
        });
      },
      keyHandler: $.extend({}, $.fn.combogrid.defaults.keyHandler, {
        enter: function (e) {
          var target = this;
          var state = $.data(target, "combogrid");
          var grid = state.grid;
          var row = grid.datagrid("getSelected");
          var $posServiceCode = $("#posServiceCode");
          var rows = [];
          rows[0] = row;

          posServiceAddRow(rows);

          $posServiceCode.combogrid("hidePanel");
          $posServiceCode.combogrid("clear");
          $posServiceCode.val("");
        },
      }),
    });
  }

  $(document.body).on("keydown", "#scanItemCode", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which,
      $this = $(this),
      thisVal = $this.val().trim();
    console.log("scanItemCode...");

    if (keyCode == 13) {
      PNotify.removeAll();

      if (thisVal != "") {
        var itemPostData = {
          code: thisVal,
          isReceiptNumber: isReceiptNumber,
          receiptRegNumber: receiptRegNumber,
          receiptDetails: drugPrescription,
        };

        appendItem(
          itemPostData,
          $(".pos-card-layout").length ? "card" : "",
          function () {
            $this.val("").focus();

            //                    var e = jQuery.Event('keydown', {keyCode: 8});
            //
            //                    $('.pos-item-combogrid-cell').find('input.textbox-text, input.textbox-value, #scanItemCode').trigger(e);

            var $scanItemCode = $("#scanItemCode");
            var p = $scanItemCode.combogrid("panel");

            if (p.is(":visible")) {
              $scanItemCode.combogrid("hidePanel");
              $scanItemCode.combogrid("clear");
            }
          }
        );
      } else {
        new PNotify({
          title: "Warning",
          text: plang.get("POS_0015"),
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
      }
    }
    return e.preventDefault();
  });

  $(document.body).on("click", ".textbox-addon-right", function () {
    var e = jQuery.Event("keydown", { keyCode: 8 });
    $(".pos-item-combogrid-cell")
      .find("input.textbox-text, input.textbox-value, #scanItemCode")
      .trigger(e);
  });

  $(document.body).on("keyup", "input.pos-quantity-input", function (e) {
    var $this = $(this),
      $row = $this.closest("tr");
    var $tbody = $row.closest("tbody");
    var qty = Number(pureNumber($this.val()));

    if (qty === "" || $this.hasClass("ignoreZeroValue")) {
      return;
    }

    if ((qty > 99999 || qty < minItemQty) && returnBillType != "typeReduce") {
      PNotify.removeAll();
      new PNotify({
        title: "Warning",
        text: "Тоо хэмжээ буруу байна!",
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      setTimeout(function () {
        $this.autoNumeric("set", 1);
        $this.attr("data-oldvalue", 1);
        posCalcRow($row);
        posItemPackageAction($tbody);
      }, 2);
      return e.preventDefault();
    }
  });

  $(document.body).on("click", "input.pos-quantity-input", function (e) {
    var $this = $(this);
    $this.focus().select();
  });

  $(document.body).on("keydown", "input.pos-quantity-input", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;
    var $this = $(this);

    if (keyCode === 38) {
      // up

      var $rowCell = $this.closest("td"),
        $row = $this.closest("tr"),
        $prevRow = $row.prevAll("tr[data-item-id]:visible:eq(0)"),
        $colIndex = $rowCell.index();

      if ($prevRow.length) {
        $prevRow
          .find("td:eq(" + $colIndex + ") input:not(:hidden):first")
          .focus()
          .select();
        $prevRow.click();
      }

      return e.preventDefault();
    } else if (keyCode === 40) {
      // down

      var $rowCell = $this.closest("td"),
        $row = $this.closest("tr"),
        $nextRow = $row.nextAll("tr[data-item-id]:visible:eq(0)"),
        $colIndex = $rowCell.index();

      if ($nextRow.length) {
        $nextRow
          .find("td:eq(" + $colIndex + ") input:not(:hidden):first")
          .focus()
          .select();
        $nextRow.click();
      }

      return e.preventDefault();
    } else if (keyCode === 13) {
      // enter

      var $rowCell = $this.closest("td"),
        $row = $this.closest("tr"),
        $nextRow = $row.nextAll("tr[data-item-id]:visible:eq(0)"),
        $colIndex = $rowCell.index();

      if ($nextRow.length) {
        $nextRow
          .find("td:eq(" + $colIndex + ") input:not(:hidden):first")
          .focus()
          .select();
        $nextRow.click();
      } else {
        $row
          .find("td:eq(" + $colIndex + ") input:not(:hidden):first")
          .trigger("change")
          .focus()
          .select();
      }

      return e.preventDefault();
    } else if (keyCode === 46) {
      // delete

      if (isTalonListProtect) {
        if (posTypeCode == "3" || posTypeCode == "4") {
          posRowRemove($this.closest("tr"));
          return;
        }
        var $dialogName = "dialog-talon-protect";
        if (!$("#" + $dialogName).length) {
          $('<div id="' + $dialogName + '"></div>').appendTo("body");
        }
        var $dialog = $("#" + $dialogName);

        $dialog
          .empty()
          .append(
            '<form method="post" autocomplete="off" id="talonListPassForm"><input type="password" name="talonListPass" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>'
          );
        $dialog.dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: "Нууц үг оруулах",
          width: 400,
          height: "auto",
          modal: true,
          open: function () {
            $(this).keypress(function (e) {
              if (e.keyCode == $.ui.keyCode.ENTER) {
                $(this)
                  .parent()
                  .find(".ui-dialog-buttonpane button:first")
                  .trigger("click");
              }
            });
            $('input[name="talonListPass"]').on("keydown", function (e) {
              var keyCode = e.keyCode ? e.keyCode : e.which;
              if (keyCode == 13) {
                $(this)
                  .closest(".ui-dialog")
                  .find(".ui-dialog-buttonpane button:first")
                  .trigger("click");
              }
            });
          },
          close: function () {
            $this.val($this.attr("value"));
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("insert_btn"),
              class: "btn btn-sm green-meadow",
              click: function () {
                PNotify.removeAll();
                var $form = $("#talonListPassForm");

                $form.validate({ errorPlacement: function () { } });

                if ($form.valid()) {
                  $.ajax({
                    type: "post",
                    url: "mdpos/checkTalonListPass",
                    data: $form.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                      Core.blockUI({
                        message: "Loading...",
                        boxed: true,
                      });
                    },
                    success: function (dataSub) {
                      if (dataSub.status == "success") {
                        $dialog.dialog("close");
                        if (
                          returnBillType == "" &&
                          isDisableRowDiscountInput == false
                        ) {
                          if (
                            !$this.is("[readonly]") ||
                            $this.hasAttr("data-accept-remove")
                          ) {
                            var $thisRow = $this.closest("tr");
                            if ($thisRow.hasClass("bundelgroup")) {
                              var bundleId = $thisRow.attr(
                                "data-bundle-group-id"
                              );
                              $thisRow
                                .closest("tbody")
                                .find(".bundelgroup-" + bundleId)
                                .each(function () {
                                  posRowRemove($(this));
                                });
                            } else {
                              posRowRemove($thisRow);
                            }
                          }
                        } else if (returnBillType == "typeReduce") {
                          var $thisRow = $this.closest("tr");
                          posRowTempRemove($thisRow);
                        } else if (isDisableRowDiscountInput == true) {
                          if (!$this.is("[readonly]")) {
                            var $thisRow = $this.closest("tr");
                            posRowRemove($thisRow);
                          }
                        }

                        posItemPackageAction($this.closest("tbody"));
                      } else {
                        new PNotify({
                          title: dataSub.status,
                          text: dataSub.message,
                          type: dataSub.status,
                          sticker: false,
                        });
                      }
                      Core.unblockUI();
                    },
                  });
                }
              },
            },
            {
              text: plang.get("close_btn"),
              class: "btn btn-sm blue-madison",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });
        $dialog.dialog("open");
      } else {
        if (returnBillType == "" && isDisableRowDiscountInput == false) {
          if (!$this.is("[readonly]") || $this.hasAttr("data-accept-remove")) {
            var $thisRow = $this.closest("tr");
            if ($thisRow.hasClass("bundelgroup")) {
              var bundleId = $thisRow.attr("data-bundle-group-id");
              $thisRow
                .closest("tbody")
                .find(".bundelgroup-" + bundleId)
                .each(function () {
                  posRowRemove($(this));
                });
            } else {
              posRowRemove($thisRow);
            }
          }
        } else if (returnBillType == "typeReduce") {
          var $thisRow = $this.closest("tr");
          posRowTempRemove($thisRow);
        } else if (isDisableRowDiscountInput == true) {
          if (!$this.is("[readonly]")) {
            var $thisRow = $this.closest("tr");
            posRowRemove($thisRow);
          }
        }

        var $posTableBody = $("#posTable > tbody"),
          isServiceExist = false;
        $posTableBody.find("> tr[data-item-id]:visible").each(function () {
          if ($(this).find('input[name="isJob[]"]').val() == "1") {
            isServiceExist = true;
          }
        });
        if (!isServiceExist && isRequiredJobDelivery == "1") {
          PNotify.removeAll();
          new PNotify({
            title: "Warning",
            text: plang.get("POS_0216"),
            type: "warning",
            sticker: false,
          });
          $posTableBody.find("> tr[data-item-id]:visible").each(function () {
            $(this).find("input.isDelivery").prop("checked", false);
            $.uniform.update($(this).find("input.isDelivery"));
          });
          return;
        }

        posItemPackageAction($this.closest("tbody"));
      }

      return e.preventDefault();
    }
  });

  $(document.body).on("change", "input.pos-quantity-input", function (e) {
    var $this = $(this),
      $row = $this.closest("tr");
    var qty = Number(pureNumber($this.val()));

    if (posOrderTimer && isBasketOnly) {
      $(".posTimerInit").countdown("option", { until: posOrderTimer });
    }

    if (isTalonListProtect && Number($this.attr("data-oldvalue")) > qty) {
      var $dialogName = "dialog-talon-protect";
      if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo("body");
      }
      var $dialog = $("#" + $dialogName);

      if (!$("#talonListPassForm").is(":visible")) {
        $dialog
          .empty()
          .append(
            '<form method="post" autocomplete="off" id="talonListPassForm"><input type="password" autocomplete="off" style="display:none" /><input type="password" name="talonListPass" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>'
          );
        $dialog.dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: "Нууц үг оруулах",
          width: 400,
          height: "auto",
          modal: true,
          open: function () {
            setTimeout(function () {
              $('input[name="talonListPass"]').focus().select();
            }, 100);
            $(this).keypress(function (e) {
              if (e.keyCode == $.ui.keyCode.ENTER) {
                $(this)
                  .parent()
                  .find(".ui-dialog-buttonpane button:first")
                  .trigger("click");
              }
            });
            $('input[name="talonListPass"]').on("keydown", function (e) {
              var keyCode = e.keyCode ? e.keyCode : e.which;
              if (keyCode == 13) {
                $(this)
                  .closest(".ui-dialog")
                  .find(".ui-dialog-buttonpane button:first")
                  .trigger("click");
              }
            });
          },
          close: function () {
            $this.val($this.attr("data-oldvalue"));
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("insert_btn"),
              class: "btn btn-sm green-meadow",
              click: function () {
                PNotify.removeAll();
                var $form = $("#talonListPassForm");

                $form.validate({ errorPlacement: function () { } });

                if ($form.valid()) {
                  $.ajax({
                    type: "post",
                    url: "mdpos/checkTalonListPass",
                    data: $form.serialize(),
                    dataType: "json",
                    beforeSend: function () {
                      Core.blockUI({
                        message: "Loading...",
                        boxed: true,
                      });
                    },
                    success: function (dataSub) {
                      var dataResponse = dataSub;
                      if (dataResponse.status != "success") {
                        $.ajax({
                          type: "post",
                          url: "api/callDataview",
                          async: false,
                          data: {
                            dataviewId: "16237213033721",
                            criteriaData: {
                              pincode: [
                                {
                                  operator: "=",
                                  operand: $form
                                    .find('input[name="talonListPass"]')
                                    .val(),
                                },
                              ],
                            },
                          },
                          dataType: "json",
                          beforeSend: function () {
                            Core.blockUI({
                              message: "Loading...",
                              boxed: true,
                            });
                          },
                          success: function (dataSub) {
                            if (
                              dataSub.status == "success" &&
                              dataSub.result.length
                            ) {
                              dataResponse.status = "success";
                              $this.closest("tr").find('input[name="employeeId[]"]').val(dataSub.result[0]["employeeid"]);
                            }
                            Core.unblockUI();
                          },
                        });
                      }
                      if (dataResponse.status == "success") {
                        $dialog.dialog("close");

                        $dialog.empty().append(
                          '<form method="post" autocomplete="off" id="talonListDescriptionForm"><input type="password" autocomplete="off" style="display:none" /><textarea name="talonListDescriptionForm" required style="height: 46px;margin-top: 4px;width: 100%;font-size: 15px"></textarea></form>'
                        );
                        $dialog.dialog({
                          cache: false,
                          resizable: true,
                          bgiframe: true,
                          autoOpen: false,
                          title: "Буцаалтын тайлбар оруулах",
                          width: 350,
                          height: "auto",
                          modal: true,
                          open: function () {
                            setTimeout(function () {
                              $('textarea[name="talonListDescriptionForm"]').focus().select();
                            }, 100);
                            $(this).keypress(function (e) {
                              if (e.keyCode == $.ui.keyCode.ENTER) {
                                $(this)
                                  .parent()
                                  .find(".ui-dialog-buttonpane button:first")
                                  .trigger("click");
                              }
                            });
                            $('textarea[name="talonListDescriptionForm"]').on("keydown", function (e) {
                              var keyCode = e.keyCode ? e.keyCode : e.which;
                              if (keyCode == 13) {
                                $(this).closest(".ui-dialog").find(".ui-dialog-buttonpane button:first").trigger("click");
                              }
                            });
                          },
                          close: function () {
                            $this.val($this.attr("data-oldvalue"));
                            $dialog.empty().dialog("destroy").remove();
                          },
                          buttons: [{
                            text: plang.get("insert_btn"),
                            class: "btn btn-sm green-meadow",
                            click: function () {
                              PNotify.removeAll();
                              var $form = $("#talonListDescriptionForm");

                              $form.validate({ errorPlacement: function () { } });

                              if ($form.valid()) {
                                if (
                                  isConfigItemCheckEndQty &&
                                  isConfigItemCheckEndQtyInvoice &&
                                  Number(
                                    $row.find('[data-field-name="endQty"]').val()
                                  ) < qty
                                ) {
                                  var endQty = $row.find('[data-field-name="endQty"]').val(),
                                    oldVal = $this.attr("data-oldvalue");

                                  PNotify.removeAll();
                                  new PNotify({
                                    title: "Warning",
                                    text: plang.getVar("POS_0016", { endQty: endQty }),
                                    type: "warning",
                                    sticker: false,
                                    addclass: "pnotify-center",
                                  });

                                  setTimeout(function () {
                                    if (Number(endQty) < Number(oldVal)) {
                                      $this.autoNumeric("set", endQty);
                                    } else {
                                      $this.autoNumeric("set", oldVal);
                                    }
                                  }, 2);

                                  return false;
                                }

                                if (
                                  isConfigItemCheckDiscountQty &&
                                  Number(
                                    $row.find('[data-field-name="discountQty"]').val()
                                  ) < qty
                                ) {
                                  var endQty = $row
                                    .find('[data-field-name="discountQty"]')
                                    .val(),
                                    oldVal = $this.attr("data-oldvalue");

                                  PNotify.removeAll();
                                  new PNotify({
                                    title: "Warning",
                                    text: plang.getVar("POS_0213", {
                                      discountQty: endQty,
                                    }),
                                    type: "warning",
                                    sticker: false,
                                    addclass: "pnotify-center",
                                  });

                                  setTimeout(function () {
                                    if (Number(endQty) < Number(oldVal)) {
                                      $this.autoNumeric("set", endQty);
                                    } else {
                                      $this.autoNumeric("set", oldVal);
                                    }
                                  }, 2);

                                  return false;
                                }

                                var $tbody = $row.closest("tbody");
                                $this.attr("data-oldvalue", qty);
                                $this.autoNumeric("set", qty);
                                if ($this.attr("data-seperatevalue") < qty) {
                                  $this.attr("data-seperatevalue", qty);
                                }
                                $row.find('input[name="returnDescription[]"]').val($form.find('textarea').val());

                                posCalcRow($row);
                                posItemPackageAction($tbody);

                                if ($this.closest("tr").hasClass("bundelgroup")) {
                                  var bundleId = $this.closest("tr").attr("data-bundle-group-id");
                                  $this.closest("tbody").find(".bundelgroup-" + bundleId).each(function () {
                                    if ($(this).find('input[name="quantity[]"]').length) {
                                      $(this).find('input[name="quantity[]"]').attr("data-oldvalue", qty).autoNumeric("set", qty);
                                      posCalcRow($(this));
                                    }
                                  });
                                }
                                $dialog.dialog("close");
                              }
                            }
                          },
                          {
                            text: plang.get("close_btn"),
                            class: "btn btn-sm blue-madison",
                            click: function () {
                              $row.find('input[name="employeeId[]"]').val('');
                              $dialog.dialog("close");
                            }
                          }]
                        });
                        $dialog.dialog("open");

                      } else {
                        new PNotify({
                          title: dataSub.status,
                          text: dataSub.message,
                          type: dataSub.status,
                          sticker: false,
                        });
                      }
                      Core.unblockUI();
                    },
                  });
                }
              },
            },
            {
              text: plang.get("close_btn"),
              class: "btn btn-sm blue-madison",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });
        $dialog.dialog("open");
      }
    } else {
      if (
        isConfigItemCheckEndQty &&
        isConfigItemCheckEndQtyInvoice &&
        Number($row.find('[data-field-name="endQty"]').val()) < qty
      ) {
        var endQty = $row.find('[data-field-name="endQty"]').val(),
          oldVal = $this.attr("data-oldvalue");

        PNotify.removeAll();
        new PNotify({
          title: "Warning",
          text: plang.getVar("POS_0016", { endQty: endQty }),
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });

        setTimeout(function () {
          if (Number(endQty) < Number(oldVal)) {
            $this.autoNumeric("set", endQty);
          } else {
            $this.autoNumeric("set", oldVal);
          }
        }, 2);

        return false;
      }

      if (isConfigItemCheckDiscountQty) {
        var sumDisQty = $row
          .parent()
          .find('tr[data-item-code="' + $row.attr("data-item-code") + '"]')
          .find(".pos-quantity-input")
          .sum();
        if (
          Number($row.find('[data-field-name="discountQty"]').val()) < sumDisQty
        ) {
          var endQty = $row.find('[data-field-name="discountQty"]').val(),
            oldVal = $this.attr("data-oldvalue");

          PNotify.removeAll();
          new PNotify({
            title: "Warning",
            text: plang.getVar("POS_0213", { discountQty: endQty }),
            type: "warning",
            sticker: false,
            addclass: "pnotify-center",
          });

          setTimeout(function () {
            if (Number(endQty) < Number(oldVal)) {
              $this.autoNumeric("set", endQty);
            } else {
              $this.autoNumeric("set", oldVal);
            }
          }, 2);

          return false;
        }
      }

      var $tbody = $row.closest("tbody");

      $this.attr("data-oldvalue", qty);
      if ($this.attr("data-seperatevalue") < qty) {
        $this.attr("data-seperatevalue", qty);
      }
      $this.autoNumeric("set", qty);
      posCalcRow($row);
      posItemPackageAction($tbody);

      if ($this.closest("tr").hasClass("bundelgroup")) {
        var bundleId = $this.closest("tr").attr("data-bundle-group-id");
        $this
          .closest("tbody")
          .find(".bundelgroup-" + bundleId)
          .each(function () {
            if ($(this).find('input[name="quantity[]"]').length) {
              $(this)
                .find('input[name="quantity[]"]')
                .attr("data-oldvalue", qty)
                .autoNumeric("set", qty);
              posCalcRow($(this));
            }
          });
      }
    }

    return e.preventDefault();
  });

  $(document.body).on("change", "input.pos-saleprice-input", function (e) {
    $(this)
      .closest("tr")
      .find('input[name="salePrice[]"]')
      .val(pureNumber($(this).val()));
    $(this).closest("tr").find("input.pos-quantity-input").trigger("change");
  });

  $(document.body).on("keydown", "input.pos-saleprice-input", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;
    var $this = $(this);

    if (keyCode === 13) {
      $this.closest("tr").find("input.pos-quantity-input").trigger("change");

      return e.preventDefault();
    }
  });

  $(document.body).on("keydown", "#posEshopQrcode", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;
    var $this = $(this);

    if (keyCode === 13) {
      $("#posEshopOrderTime").val("");
      var basketParams = [
        { id: "test", event: "qrcode", typeid: "12", qrcode: $this.val() },
      ];
      posFillItemsByBasket("", "", "", "", basketParams);

      return e.preventDefault();
    }
  });

  $(document.body).on("focus", "input.pos-saleprice-input", function (e) {
    var $this = $(this);
    var $dialogNameWaterPin = "dialog-employee-pincode";
    if (
      $("#" + $dialogNameWaterPin + ":visible").length ||
      typeof $this.attr("data-isedit-permission") !== "undefined"
    ) {
      return;
    }

    checkzbpassword($this);
  });

  $(document.body).on("click", "input.isDelivery", function (e) {
    var $this = $(this);

    var $posTableBody = $("#posTable > tbody"),
      isServiceExist = false;
    $posTableBody.find("> tr[data-item-id]:visible").each(function () {
      if ($(this).find('input[name="isJob[]"]').val() == "1") {
        isServiceExist = true;
      }
    });

    if (!isServiceExist && isRequiredJobDelivery == "1") {
      PNotify.removeAll();
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0216"),
        type: "warning",
        sticker: false,
      });
      $this.prop("checked", false);
      $.uniform.update($this);
      return;
    }

    if ($this.is(":checked")) {
      $this.closest("td").find('input[type="hidden"]').val("1");
    } else {
      $this.closest("td").find('input[type="hidden"]').val("0");
    }
  });

  $(document.body).on("click", "input.isGiftDelivery", function (e) {
    var $this = $(this),
      $index = $this.closest("tr").index() - 1,
      $row = $this.closest('tr[data-item-gift-row="true"]'),
      $itemRow = $row.prev("tr[data-item-id]:eq(0)"),
      $giftJson = JSON.parse($itemRow.find('input[name="giftJson[]"]').val());

    if ($this.is(":checked")) {
      $giftJson[$index]["isDelivery"] = 1;
    } else {
      $giftJson[$index]["isDelivery"] = 0;
    }
    $itemRow.find('input[name="giftJson[]"]').val(JSON.stringify($giftJson));
  });

  $(document).on("change", ".seperate-calculation", function () {
    if (!$(this).is(":checked")) {
      $("#posTable")
        .find(".pos-selected-seperate-row")
        .removeClass("pos-selected-seperate-row");
      posCalcTotal();
    }
  });

  $(document.body).on("click", "tbody > tr[data-item-id]", function (e) {
    var $this = $(this),
      $tbody = $this.closest("tbody");

    $tbody.find("tr.pos-selected-row").removeClass("pos-selected-row");
    $this.addClass("pos-selected-row");

    if ($(".seperate-calculation").is(":checked")) {
      if ($this.hasClass("pos-selected-seperate-row")) {
        if (
          $(e.target)[0]["nodeName"] == "TD" ||
          $(e.target)[0]["nodeName"] == "DIV"
        ) {
          $this.removeClass("pos-selected-seperate-row");
        }
      } else {
        $this.addClass("pos-selected-seperate-row");
      }
      posCalcTotal();
    }

    $('td[data-field-name="detail-code"]').text(
      $this.find('td[data-field-name="itemCode"]').text().substr(0, 18)
    );
    $('td[data-field-name="detail-name"]').text(
      $this.find('td[data-field-name="itemName"]').text()
    );
    $('td[data-field-name="detail-measure"]').text(
      $this.find('input[name="measureCode[]"]').val()
    );

    $('td[data-field-name="detail-saleprice"]').text(
      pureNumberFormat($this.find('input[name="salePrice[]"]').val())
    );
    $('td[data-field-name="detail-vatprice"]').text(
      pureNumberFormat($this.find('input[name="vatPrice[]"]').val())
    );
    $('td[data-field-name="detail-novatprice"]').text(
      pureNumberFormat($this.find('input[name="noVatPrice[]"]').val())
    );

    if (isConfigRowDiscount) {
      if (
        returnBillType == "typeChange" ||
        returnBillType == "typeCancel" ||
        returnBillType == "typeReduce" ||
        isDisableRowDiscountInput == true
      ) {
        if (
          isDisableRowDiscountInput == true &&
          !$this.find(".pos-quantity-input").is("[readonly]")
        ) {
          $("#posCalcItemRowDiscount, #posCalcItemRowDiscountRemove").prop(
            "disabled",
            false
          );
        } else {
          $("#posCalcItemRowDiscount, #posCalcItemRowDiscountRemove").prop(
            "disabled",
            true
          );
        }
      } else {
        $("#posCalcItemRowDiscount, #posCalcItemRowDiscountRemove").prop(
          "disabled",
          false
        );
      }

      $("#pos-discount-percent")
        .val($this.find('input[name="discountPercent[]"]').val())
        .prop("readonly", true);
      $("#pos-discount-amount")
        .autoNumeric("set", $this.find('input[name="unitDiscount[]"]').val())
        .prop("readonly", true);
    }

    if (isConfigSalesPerson) {
      if ($this.find('input[name="employeeId[]"]').val() != "") {
        $('td[data-field-name="detail-salesperson"]').text(
          $this.find("input.lookup-code-autocomplete").val() +
          " - " +
          $this.find("input.lookup-name-autocomplete").val()
        );
      } else {
        $('td[data-field-name="detail-salesperson"]').text("");
      }
    }

    if (isConfigHealthRecipe) {
      $('td[data-field-name="detail-emd-amount"]').autoNumeric(
        "set",
        $this.find('input[name="unitReceivable[]"]').val()
      );
    }

    if (
      isConfigAccompanyItem &&
      $this.find('input[data-name="accompanyItems"]').length &&
      $this.find('input[data-name="accompanyItems"]').val()
    ) {
      var getAccompanyJson = JSON.parse(
        decodeURIComponent(
          $this.find('input[data-name="accompanyItems"]').val()
        )
      );

      $("#posAccompanyItem").combogrid({
        panelWidth: 650,
        panelHeight: 400,
        idField: "itemid",
        textField: "itemname",
        fitColumns: true,
        pagination: true,
        rownumbers: true,
        remoteSort: false,
        singleSelect: false,
        ctrlSelect: true,
        pageList: [10, 20, 50, 100],
        pageSize: 10,
        columns: [
          [
            {
              field: "itemcode",
              title: plang.get("POS_0003"),
              width: 18,
              sortable: true,
            },
            {
              field: "itemname",
              title: plang.get("POS_0004"),
              width: 70,
              sortable: true,
            },
          ],
        ],
        onDblClickRow: function (index, row) {
          var $posServiceCode = $("#posAccompanyItem");
          var $scanItemCode = $("#scanItemCode");

          $scanItemCode.val(row.itemcode);

          var e = jQuery.Event("keydown");
          e.keyCode = e.which = 13;
          $scanItemCode.trigger(e);

          $posServiceCode.combogrid("hidePanel");
          $posServiceCode.combogrid("clear", "");
          $posServiceCode.val("");
        },
        loader: function (param, success, error) {
          success(getAccompanyJson);
        },
        onLoadSuccess: function (data) {
          $(this)
            .combogrid("grid")
            .datagrid("getPanel")
            .find(".datagrid-row")
            .css("height", "34px");
        },
        keyHandler: $.extend({}, $.fn.combogrid.defaults.keyHandler, {
          enter: function (e) {
            var target = this;
            var state = $.data(target, "combogrid");
            var grid = state.grid;
            var row = grid.datagrid("getSelected");
            var $posServiceCode = $("#posAccompanyItem");

            var $scanItemCode = $("#scanItemCode");

            $scanItemCode.val(row.itemcode);

            var e = jQuery.Event("keydown");
            e.keyCode = e.which = 13;
            $scanItemCode.trigger(e);

            $posServiceCode.combogrid("hidePanel");
            $posServiceCode.combogrid("clear", "");
            $posServiceCode.val("");
          },
        }),
      });
    }

    if (
      isConfigServiceJobAccompany &&
      $this.find('input[data-name="accompanyServices"]').length &&
      $this.find('input[data-name="accompanyServices"]').val()
    ) {
      var getAccompanyServiceJson = JSON.parse(
        decodeURIComponent(
          $this.find('input[data-name="accompanyServices"]').val()
        )
      );

      $("#posServiceCodeAccompany").combogrid({
        panelWidth: 650,
        panelHeight: 400,
        idField: "jobid",
        textField: "jobname",
        fitColumns: true,
        pagination: true,
        rownumbers: true,
        remoteSort: false,
        singleSelect: false,
        ctrlSelect: true,
        pageList: [10, 20, 50, 100],
        pageSize: 10,
        columns: [
          [
            {
              field: "jobcode",
              title: plang.get("POS_0005"),
              width: 18,
              sortable: true,
            },
            {
              field: "jobname",
              title: plang.get("POS_0006"),
              width: 70,
              sortable: true,
            },
          ],
        ],
        onDblClickRow: function (index, row) {
          var $posServiceCode = $("#posServiceCodeAccompany"),
            rows = [];
          rows[0] = row;

          posServiceAddRow(rows);

          $posServiceCode.combogrid("hidePanel");
          $posServiceCode.combogrid("clear", "");
          $posServiceCode.val("");
        },
        loader: function (param, success, error) {
          success(getAccompanyServiceJson);
        },
        onLoadSuccess: function (data) {
          $(this)
            .combogrid("grid")
            .datagrid("getPanel")
            .find(".datagrid-row")
            .css("height", "34px");
        },
        keyHandler: $.extend({}, $.fn.combogrid.defaults.keyHandler, {
          enter: function (e) {
            var target = this;
            var state = $.data(target, "combogrid");
            var grid = state.grid;
            var row = grid.datagrid("getSelected"),
              rows = [];
            var $posServiceCode = $("#posServiceCodeAccompany");

            rows[0] = row;

            posServiceAddRow(rows);

            $posServiceCode.combogrid("hidePanel");
            $posServiceCode.combogrid("clear", "");
            $posServiceCode.val("");
          },
        }),
      });
    }

    /*$this.find('input:not(:hidden):first').focus().caret({start:0, end:10});*/
  });

  $(document.body).on("click", 'input[name="posBillType"]', function () {
    posPaymentBillType();
  });

  $(document.body).on("keydown", "#pos-org-number", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which,
      $this = $(this),
      regNumber = $this.val().trim();

    if (keyCode == 13) {
      var $posPayAmount = $("#posPayAmount"),
        vatAmount = Number($("td.pos-amount-vat").autoNumeric("get")),
        payAmount = Number($("#tmpPayAmount").val());

      if (regNumber != "") {
        if (isConfigClearSidebarData == 1) {
          $('input[name="serviceCustomerId"]').val("");
          $('input[name="serviceCustomerId_displayField"]').val("");
          $('input[name="serviceCustomerId_nameField"]').val("");
          $('input[name="serviceCustomerId"]').attr("data-row-data", "");
          $("#invInfoCustomerLastName").val("");
          $("#invInfoCustomerName").val("");
          $("#invInfoCustomerRegNumber").val("");
          $("#invInfoPhoneNumber").val("");
          $("#invInfoTransactionValue").val("");
        }

        $.ajax({
          type: "post",
          url: "mdpos/getOrganizationInfo",
          data: { regNumber: regNumber },
          dataType: "json",
          beforeSend: function () {
            Core.blockUI({
              message: "Loading...",
              boxed: true,
            });
          },
          success: function (data) {
            if (data.name != "") {
              data.vatpayer = true;

              $("#pos-org-name").val(data.name);
              $("#pos-org-vatpayer").val(data.vatpayer);

              if (data.vatpayer == false) {
                $posPayAmount.autoNumeric("set", payAmount - vatAmount);
              } else {
                $posPayAmount.autoNumeric("set", payAmount);
              }

              $("#posCashAmount").focus();

              $.ajax({
                type: "post",
                url: "api/callProcess",
                data: {
                  processCode: "CHECK_CUSTOMER_UNIQUE_004",
                  paramData: { positionName: regNumber },
                },
                dataType: "json",
                success: function (data) {
                  if (data.status === "success" && data.result) {
                    var getData = data;
                    if (data.result.customerid) {
                      $.ajax({
                        type: "post",
                        url: "api/callDataview",
                        data: {
                          dataviewId: "1522946988985",
                          criteriaData: {
                            id: [
                              {
                                operator: "=",
                                operand: data.result.customerid,
                              },
                            ],
                          },
                        },
                        dataType: "json",
                        success: function (data) {
                          if (data.status === "success" && data.result[0]) {
                            $('input[name="serviceCustomerId"]').val(
                              getData.result.customerid
                            );
                            $(
                              'input[name="serviceCustomerId_displayField"]'
                            ).val(getData.result.customercode);
                            $('input[name="serviceCustomerId_nameField"]').val(
                              getData.result.customername
                            );
                            $('input[name="serviceCustomerId"]')
                              .attr(
                                "data-row-data",
                                JSON.stringify(data.result[0])
                              )
                              .trigger("change");
                          } else {
                            $('input[name="serviceCustomerId"]').val("");
                            $(
                              'input[name="serviceCustomerId_displayField"]'
                            ).val("");
                            $('input[name="serviceCustomerId_nameField"]').val(
                              ""
                            );
                            $('input[name="serviceCustomerId"]').attr(
                              "data-row-data",
                              ""
                            );
                            $("#invInfoCustomerLastName").val("");
                            $("#invInfoCustomerName").val("");
                            $("#invInfoCustomerRegNumber").val("");
                            $("#invInfoPhoneNumber").val("");
                            $("#invInfoTransactionValue").val("");
                          }
                        },
                      });
                    }
                  }
                },
              });
            } else {
              PNotify.removeAll();

              if (data.hasOwnProperty("message") && data.message != "") {
                new PNotify({
                  title: "Warning",
                  text: data.message,
                  type: "warning",
                  sticker: false,
                });
              } else {
                new PNotify({
                  title: "Warning",
                  text: "Татвар төлөгчийн дугаар буруу байна!",
                  type: "warning",
                  sticker: false,
                });
              }

              $("#pos-org-vatpayer").val("");
              $posPayAmount.autoNumeric("set", payAmount);
            }

            Core.unblockUI();
          },
        });
      } else {
        PNotify.removeAll();
        new PNotify({
          title: "Warning",
          text: plang.get("POS_0018"),
          type: "warning",
          sticker: false,
        });

        $("#pos-org-vatpayer").val("");
        $posPayAmount.autoNumeric("set", payAmount);
      }
    }
  });

  $(document.body).on(
    "keydown",
    "#posReceiptNumber, #posReceiptRegNumber",
    function (e) {
      var keyCode = e.keyCode ? e.keyCode : e.which;

      if (keyCode == 13) {
        PNotify.removeAll();
        var receiptNumber = $("#posReceiptNumber").val().trim(),
          regNumber = $("#posReceiptRegNumber").val().trim();

        if (receiptNumber != "" && regNumber != "") {
          $.ajax({
            type: "post",
            url: "mdpos/getReceiptNumber",
            data: { receiptNumber: receiptNumber, regNumber: regNumber },
            dataType: "json",
            beforeSend: function () {
              Core.blockUI({
                message: "Loading...",
                boxed: true,
              });
            },
            success: function (data) {
              if (data.status == "success") {
                if (data.active == "active") {
                  posReceiptNumberFill(data);
                  $("#posReceiptNumber, #posReceiptRegNumber").val("");
                } else {
                  posReceiptNumberExpired(data);
                }
              } else {
                new PNotify({
                  title: data.status,
                  text: data.message,
                  type: data.status,
                  sticker: false,
                  addclass: "pnotify-center",
                  delay: 1000000000,
                });

                Core.unblockUI();
              }
            },
          });
        } else {
          new PNotify({
            title: "Warning",
            text: plang.get("POS_0019"),
            type: "warning",
            sticker: false,
            addclass: "pnotify-center",
          });
        }
      }
    }
  );

  $(document.body).on(
    "change",
    'input[name="bankAmountDtl[]"]',
    function (e, custom) {
      if (typeof $(this).attr("data-prevent-change") !== "undefined") {
        return;
      }
      posSumBankAmount();
    }
  );

  $(document.body).on("keyup", 'input[name="bankAmountDtl[]"]', function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      var $tselect = $(this);
      var bankCode = $tselect
        .closest(".pos-bank-row")
        .find('select[name="posBankIdDtl[]"]')
        .find("option:selected")
        .data("bankcode");

      if (posUseIpTerminal === "1" && bankCode == 150000) {
        var amount = $tselect.val();

        if (
          $tselect
            .closest(".pos-bank-row")
            .find('select[name="posBankIdDtl[]"]')
            .val() != "" &&
          bankIpterminals.hasOwnProperty(bankCode)
        ) {
          isAcceptPrintPos = false;
          bankIpTerminalTransfer(
            amount,
            bankIpterminals[bankCode],
            "golomtbank",
            function (res) {
              setValuePosGolomtBank($tselect, res);
            }
          );
        } else {
          if (isBasketOnly) {
          } else {
            posPayment();
          }
        }
      }

      if (posUseIpTerminal === "1" && bankCode == 500000) {
        var amount = $tselect.val();

        if (
          $tselect
            .closest(".pos-bank-row")
            .find('select[name="posBankIdDtl[]"]')
            .val() != "" &&
          bankIpterminals.hasOwnProperty(bankCode)
        ) {
          isAcceptPrintPos = false;
          bankIpTerminalTransfer(
            amount,
            bankIpterminals[bankCode],
            "khanbank",
            function (res) {
              setValuePosKhaanBank($tselect, res);
            }
          );
        } else {
          if (isBasketOnly) {
          } else {
            posPayment();
          }
        }
      }

      if (posUseIpTerminal === "1" && bankCode == 320000) {
        var amount = $tselect.val();

        if (
          $tselect
            .closest(".pos-bank-row")
            .find('select[name="posBankIdDtl[]"]')
            .val() != "" &&
          bankIpterminals.hasOwnProperty(bankCode)
        ) {
          isAcceptPrintPos = false;
          bankIpTerminalTransfer(
            amount,
            bankIpterminals[bankCode],
            "xacbank",
            function (res) {
              setValuePosXacBank($tselect, res);
            }
          );
        } else {
          if (isBasketOnly) {
          } else {
            posPayment();
          }
        }
      }

      if (posUseIpTerminal === "1" && bankCode == 400000) {
        var amount = $tselect.val();

        if (
          $tselect
            .closest(".pos-bank-row")
            .find('select[name="posBankIdDtl[]"]')
            .val() != "" &&
          bankIpterminals.hasOwnProperty(bankCode)
        ) {
          isAcceptPrintPos = false;
          bankIpTerminalTransfer(
            amount,
            bankIpterminals[bankCode],
            "tdbank",
            function (res) {
              setValuePosTdBank($tselect, res);
            }
          );
        } else {
          if (isBasketOnly) {
          } else {
            posPayment();
          }
        }
      }
    }
  });

  $(document.body).on(
    "keydown",
    'input[name="posSocialpayPhoneNumber"]',
    function (e) {
      var keyCode = e.keyCode ? e.keyCode : e.which;

      if (keyCode == 13) {
        posSaleSocialPay(
          Number($('input[name="posSocialpayAmt"]').autoNumeric("get")),
          $('input[name="posSocialpayPhoneNumber"]').val()
        );
        e.preventDefault();
        return false;
      }
    }
  );

  $(document.body).on(
    "change",
    'input[name="voucherDtlAmount[]"]',
    function () {
      if (typeof $(this).attr("data-prevent-change") !== "undefined") {
        return;
      }
      posSumVoucherAmount();
    }
  );

  $(document.body).on(
    "change",
    'input[name="voucher2DtlAmount[]"]',
    function () {
      if (typeof $(this).attr("data-prevent-change") !== "undefined") {
        return;
      }
      posSumVoucher2Amount();
    }
  );

  $(document.body).on(
    "change",
    'input[name="accountTransferAmountDtl[]"]',
    function () {
      if (typeof $(this).attr("data-prevent-change") !== "undefined") {
        return;
      }
      posSumAccountTransferAmount();
    }
  );

  $(document.body).on(
    "change",
    'input[name="posRecievableAmtDtl[]"]',
    function () {
      if (typeof $(this).attr("data-prevent-change") !== "undefined") {
        return;
      }
      posSumRecievableAmount();
    }
  );

  $(document.body).on("change", 'input[name="candyAmountDtl[]"]', function () {
    if (typeof $(this).attr("data-prevent-change") !== "undefined") {
      return;
    }
    posSumCandyAmount();
  });

  $(document.body).on("change", 'input[name="phone1"]', function () {
    if ($(this).val().length < 8) {
      alert('Утасны дугаар буруу байна!');
    }
  });

  $(document.body).on("change", 'input[name="phone2"]', function () {
    if ($(this).val().length < 8) {
      alert('Утасны дугаар буруу байна!');
    }
  });

  $(document.body).on("change", 'input[name="upointAmountDtl[]"]', function () {
    if (typeof $(this).attr("data-prevent-change") !== "undefined") {
      return;
    }
    posSumUpointAmount();
  });

  $(document.body).on(
    "change",
    'input[name="candyCouponAmountDtl[]"]',
    function () {
      if (typeof $(this).attr("data-prevent-change") !== "undefined") {
        return;
      }
      posSumCandyCouponAmount();
    }
  );

  $(document.body).on(
    "change",
    "#posCashAmount, #posBankAmount, #posAccountTransferAmt, #posCandyAmt, #posUpointAmt, #posCandyCouponAmt, #posCertificateExpenseAmt, #posLiciengExpenseAmt, #posVoucher2Amount, #posdiscountActivityAmount, #posSocialpayAmt",
    function () {
      if (typeof $(this).attr("data-prevent-change") !== "undefined") {
        return;
      }
      posCalcChangeAmount();
    }
  );

  $(document.body).on(
    "focusin",
    "#posBonusCardAmount:not([readonly])",
    function () {
      var $this = $(this);
      $this.attr("data-oldvalue", $this.autoNumeric("get"));
    }
  );

  $(document.body).on("change", "#posBonusCardAmount", function () {
    var $this = $(this);
    if (typeof $this.attr("data-prevent-change") !== "undefined") {
      return;
    }

    var posBonusCardAmount = Number($this.autoNumeric("get")),
      cardBeginAmount = Number($("#cardBeginAmount").autoNumeric("get")),
      cardPayPercentAmount = Number(
        $("#cardPayPercentAmount").autoNumeric("get")
      );

    if (posBonusCardAmount > cardBeginAmount) {
      PNotify.removeAll();
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0020"),
        type: "warning",
        sticker: false,
      });

      setTimeout(function () {
        $this.autoNumeric("set", cardPayPercentAmount);

        posCalcChangeAmount();
        posBonusCardEndAmountCalc();
      }, 2);

      return;
    } else if (posBonusCardAmount > cardPayPercentAmount) {
      PNotify.removeAll();
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0021"),
        type: "warning",
        sticker: false,
      });

      setTimeout(function () {
        $this.autoNumeric("set", $this.attr("data-oldvalue"));

        posCalcChangeAmount();
        posBonusCardEndAmountCalc();
      }, 2);

      return;
    }

    posCalcChangeAmount();
    posBonusCardEndAmountCalc();
  });

  $(document.body).on("keydown", "#posCashAmount", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      posCalcChangeAmount();
    }
  });

  $(document.body).on("keydown", "#posBankAmount", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      var $this = $(this),
        $row = $this.closest(".pos-bank-row");

      posCalcChangeAmount();

      if ($row.find('select[name="posBankIdDtl[]"]').val() == "") {
        $row.find('select[name="posBankIdDtl[]"]').select2("open");
      } else {
        $("#posChangeAmount").focus().select();
        return e.preventDefault();
      }
    }
  });

  $(document.body).on("change", ".invAmountField", function () {
    if (typeof $(this).attr("data-prevent-change") !== "undefined") {
      return;
    }
    posCalcChangeAmount();
  });

  $(document.body).on("keydown", ".posKeyAmount", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;
    if (keyCode == 13) {
      var $this = $(this),
        $tbl = $this.closest(".pos-payment-area"),
        $tblInput = $tbl.find(
          '.posKeyAmount:visible:not([readonly="readonly"], [readonly], readonly)'
        ),
        $cellIndex = $tblInput.index($this),
        $focusField = $tblInput.eq($cellIndex + 1);

      if ($this.attr("name") == "bankAmountDtl[]" && $this.val()) {
        $this
          .closest(".pos-bank-row")
          .find('select[name="posBankIdDtl[]"]')
          .select2("open");
      } else if (
        $this.attr("name") == "accountTransferAmountDtl[]" &&
        $this.val()
      ) {
        $this
          .closest(".pos-accounttransfer-row")
          .find('select[name="accountTransferBankIdDtl[]"]')
          .select2("open");
      } else if ($this.attr("name") == "posMobileNetAmt" && $this.val()) {
        $this
          .closest(".form-group")
          .find('select[name="posMobileNetBankId"]')
          .select2("open");
      } else if ($focusField.length) {
        $focusField.focus().select();
      } else {
        $tblInput.eq(0).focus().select();
      }

      return e.preventDefault();
    }
  });

  $(document.body).on("click", "input.pos-gift-item", function () {
    var $this = $(this);

    if ($this.is(":checked")) {
      var $row = $this.closest("tr"),
        $table = $this.closest("table"),
        vNum = $row.attr("data-v-num"),
        $sameRows = $table.find('tr[data-v-num="' + vNum + '"]'),
        isSinglePolicy = false;

      if ($table.hasClass("rulepolicypackagelist")) {
        var $parent = $table;
      } else if ($table.hasClass("pos-policy-list")) {
        var $parent = $this.closest(".ui-dialog-content");
        isSinglePolicy = true;
      } else {
        var $parentPolicyList = $table.closest(
          '[data-tablerulepolicylist="true"]'
        );

        if ($parentPolicyList.hasClass("tablerulepolicylist")) {
          var $dialog = $this.closest(".ui-dialog-content"),
            $unCheckBoxRulePolicyList = $dialog
              .find(".tablerulepolicylist")
              .not($parentPolicyList),
            $unCheckBoxsRulePolicyList = $unCheckBoxRulePolicyList.find(
              "input.pos-gift-item"
            );

          $unCheckBoxsRulePolicyList.prop("checked", false);
          $unCheckBoxsRulePolicyList.removeAttr("checked");

          $.uniform.update($unCheckBoxsRulePolicyList);

          var $parent = $table;
        } else {
          var $parent = $this.closest(".ui-dialog-content");
        }
      }

      if ($sameRows.length > 1) {
        var $unCheckBoxs = $parent
          .find("input.pos-gift-item")
          .not($sameRows.find("input.pos-gift-item"));
      } else {
        var $unCheckBoxs = $parent.find("input.pos-gift-item").not($this);
      }

      $unCheckBoxs.prop("checked", false);
      $unCheckBoxs.removeAttr("checked");

      $.uniform.update($unCheckBoxs);

      if (isSinglePolicy) {
        var $singlePolicyTbl = $row.closest("table[data-single-policy-count]"),
          singlePolicyTblCount = Number(
            $singlePolicyTbl.attr("data-single-policy-count")
          );

        if (singlePolicyTblCount > 1) {
          var $singlePolicyCheckboxs = $singlePolicyTbl
            .find("tr[data-single-policy-price]")
            .find('input[type="checkbox"]');

          $singlePolicyCheckboxs.prop("checked", false);
          $singlePolicyCheckboxs.removeAttr("checked");

          $.uniform.update($singlePolicyCheckboxs);

          var $thisPolicyId = $this
            .closest("tr[data-single-policy-id]")
            .attr("data-single-policy-id"),
            $thisPolicyCheckbox = $singlePolicyTbl
              .find('tr[data-single-policy-id="' + $thisPolicyId + '"]')
              .find("input.single-policy-price-checkbox");

          $thisPolicyCheckbox.attr("checked", "checked");
          $.uniform.update($thisPolicyCheckbox);
        }
      }
    }
  });

  $(document.body).on(
    "click",
    "input.single-policy-price-checkbox",
    function () {
      var $this = $(this);

      if ($this.is(":checked")) {
        var $policyTbl = $this.closest("table"),
          policyId = $this.closest("tr").attr("data-single-policy-id"),
          $policyRows = $policyTbl.find(
            'tr[data-single-policy-id="' + policyId + '"]'
          ),
          $onlyPolicyRows = $policyTbl.find("tr[data-single-policy-price]"),
          policyLength = $policyRows.length;

        if (policyLength > 1) {
          var $unCheckBoxs = $policyTbl
            .find('input[type="checkbox"]')
            .not($policyRows.find('input[type="checkbox"]'));

          $unCheckBoxs.prop("checked", false);
          $unCheckBoxs.removeAttr("checked");

          $.uniform.update($unCheckBoxs);
        } else if ($onlyPolicyRows.length > 1) {
          var $unCheckBoxs = $policyTbl
            .find('input[type="checkbox"]')
            .not($this);

          $unCheckBoxs.prop("checked", false);
          $unCheckBoxs.removeAttr("checked");

          $.uniform.update($unCheckBoxs);
        }
      }
    }
  );

  $(document.body).on(
    "click",
    'input[name="posPolicyCheckBox[]"]',
    function () {
      var $this = $(this);
      var getPolicyId = $this
        .closest("table")
        .closest("tr")
        .data("single-policy-id");

      if ($this.is(":checked")) {
        $("#dialog-pos-gift")
          .find('tr[data-single-policy-id="' + getPolicyId + '"]')
          .find(".single-policy-price-checkbox")
          .prop("checked", true);
        $("#dialog-pos-gift")
          .find('tr[data-single-policy-id="' + getPolicyId + '"]')
          .find(".single-policy-price-checkbox")
          .parent()
          .addClass("checked");
      } else {
        $("#dialog-pos-gift")
          .find('tr[data-single-policy-id="' + getPolicyId + '"]')
          .find(".single-policy-price-checkbox")
          .prop("checked", false);
        $("#dialog-pos-gift")
          .find('tr[data-single-policy-id="' + getPolicyId + '"]')
          .find(".single-policy-price-checkbox")
          .parent()
          .removeClass("checked");
      }
    }
  );

  $(document.body).on(
    "keydown",
    'input[name="voucherDtlSerialNumber[]"]',
    function (e) {
      var keyCode = e.keyCode ? e.keyCode : e.which;

      if (keyCode == 13) {
        var $this = $(this),
          $row = $this.closest(".pos-voucher-row"),
          serialNumber = $this.val().trim();

        if (serialNumber != "") {
          var $voucherRowDtl = $this
            .closest(".pos-voucher-row-dtl")
            .find("input[name='voucherDtlSerialNumber[]']")
            .filter(function () {
              return this.value == serialNumber;
            });
          if ($voucherRowDtl.length > 1) {
            $this.val("");
            PNotify.removeAll();
            new PNotify({
              title: "Warning",
              text: "СЕРИЙН ДУГААР давхцаж байна!",
              type: "warning",
              sticker: false,
            });
            return;
          }

          $.ajax({
            type: "post",
            url: "mdpos/getVoucherBySerialNumber",
            data: { serialNumber: $this.val() },
            dataType: "json",
            beforeSend: function () {
              Core.blockUI({
                message: "Loading...",
                boxed: true,
              });
            },
            success: function (data) {
              PNotify.removeAll();
              if (data.status == "success") {
                var $voucherDtlAmount = $row.find(
                  'input[name="voucherDtlAmount[]"]'
                );

                if (
                  data.hasOwnProperty("discountPercent") &&
                  Number(data.discountPercent) > 0
                ) {
                  var posPayAmount = Number(
                    $("#posPayAmount").autoNumeric("get")
                  );
                  var discountAmount =
                    (Number(data.discountPercent) / 100) * posPayAmount;

                  $voucherDtlAmount.autoNumeric("set", discountAmount);
                } else {
                  $voucherDtlAmount.autoNumeric("set", data.amount);
                  //$voucherDtlAmount.autoNumeric('set', data.amount).removeAttr('readonly');
                }

                $row.find('input[name="voucherDtlId[]"]').val(data.id);
                $row.find('input[name="voucherTypeId[]"]').val(data.typeId);

                if (posRemainderCoupon) {
                  $("#cardBeginAmountCoupon").autoNumeric(
                    "set",
                    data.beginamount
                  );
                  var posPayAmount = Number(
                    $("#posPayAmount").autoNumeric("get")
                  );
                  var endAmt = posPayAmount - Number(data.beginamount);
                  endAmt = endAmt < 0 ? endAmt * -1 : endAmt;
                  $("#cardEndAmountCoupon").autoNumeric("set", endAmt);
                  $("#cardOwnerNameCoupon").autoNumeric(
                    "set",
                    data.customername
                  );
                }

                posSumVoucherAmount();
                posCalcChangeAmount();

                $voucherDtlAmount.focus().select();
              } else {
                $row
                  .find('input[name="voucherDtlAmount[]"]')
                  .val("")
                  .attr("readonly", "readonly");
                $row
                  .find(
                    'input[name="voucherDtlId[]"], input[name="voucherTypeId[]"]'
                  )
                  .val("");

                new PNotify({
                  title: data.status,
                  text: data.message,
                  type: data.status,
                  sticker: false,
                });
              }

              Core.unblockUI();
            },
          });
        } else {
          $row
            .find(
              'input[name="voucherDtlAmount[]"], input[name="voucherDtlId[]"], input[name="voucherTypeId[]"]'
            )
            .val("");

          posSumVoucherAmount();
          posCalcChangeAmount();
        }
      }
    }
  );

  $(document.body).on(
    "keydown",
    'input[name="voucher2DtlSerialNumber[]"]',
    function (e) {
      var keyCode = e.keyCode ? e.keyCode : e.which;

      if (keyCode == 13) {
        var $this = $(this),
          $row = $this.closest(".pos-voucher2-row"),
          serialNumber = $this.val().trim();

        if (serialNumber != "") {
          var postData = {
            serialNumber: $this.val(),
            storeId: posStoreId,
            filterItemIds: getPosGridItemCommaIds(),
          };
          var $empCustomerId = $('input[name="empCustomerId"]');

          if ($empCustomerId.length && $empCustomerId.val()) {
            postData["filterCustomerId"] = $empCustomerId.val();
          }

          $.ajax({
            type: "post",
            url: "mdpos/getVoucherBySerialNumber",
            data: postData,
            dataType: "json",
            beforeSend: function () {
              Core.blockUI({ message: "Loading...", boxed: true });
            },
            success: function (data) {
              PNotify.removeAll();

              if (
                ($('input[name="empCustomerId"]').length &&
                  $('input[name="empCustomerId"]').val() == "") ||
                typeof $('input[name="empCustomerId"]').attr(
                  "iscouponbonus"
                ) !== "undefined"
              ) {
                $("#invInfoCustomerLastName").val("");
                $("#invInfoCustomerName").val("");
                $("#invInfoCustomerRegNumber").val("");
                $('input[name="empCustomerId"]').val("");
                $('input[name="empCustomerId_nameField"]').val("");
                $('input[name="empCustomerId_displayField"]').val("");
                $('input[name="serviceCustomerId"]').val("");
                $('input[name="serviceCustomerId_nameField"]')
                  .val("")
                  .prop("disabled", false);
                $('input[name="serviceCustomerId_displayField"]')
                  .val("")
                  .prop("disabled", false);
                $('input[name="empCustomerId"]').attr("iscouponbonus", "1");
              }

              if (data.status == "success") {
                var $voucherDtlAmounts = $row.find(
                  'input[name="voucher2DtlAmount[]"]'
                );
                var $voucherDtlAmount = $row.find(".voucherstramount");
                var posPayAmount = Number(
                  $("#posPayAmount").autoNumeric("get")
                );
                var useUserAmount = Number($(".posUserAmount").sum());

                var $dsCount = posPayAmount - useUserAmount;

                if (
                  data.hasOwnProperty("discountPercent") &&
                  Number(data.discountPercent) > 0
                ) {
                  var discountAmount =
                    (Number(data.discountPercent) / 100) * posPayAmount;
                  $voucherDtlAmounts
                    .autoNumeric("set", discountAmount)
                    .removeAttr("readonly");
                } else {
                  var voucherAmount = Number(data.amount);

                  if (voucherAmount < $dsCount) {
                    $dsCount = voucherAmount;
                  }

                  $voucherDtlAmounts
                    .autoNumeric("set", $dsCount)
                    .removeAttr("readonly");
                  $voucherDtlAmount
                    .empty()
                    .append(
                      "Үлдэгдэл: <strong>" +
                      pureNumberFormat(data.amount) +
                      "</strong>"
                    );
                }

                $row.find('input[name="voucher2DtlId[]"]').val(data.id);
                $row.find('input[name="voucher2TypeId[]"]').val(data.typeId);

                if (posRemainderCoupon) {
                  $("#cardBeginAmountCoupon").autoNumeric(
                    "set",
                    data.beginamount
                  );
                  var posPayAmount = Number(
                    $("#posPayAmount").autoNumeric("get")
                  );
                  var endAmt = posPayAmount - Number(data.beginamount);
                  endAmt = endAmt < 0 ? endAmt * -1 : endAmt;
                  $("#cardEndAmountCoupon").autoNumeric("set", endAmt);
                  $("#cardOwnerNameCoupon").autoNumeric(
                    "set",
                    data.customername
                  );
                }

                if (
                  ($('input[name="empCustomerId"]').length &&
                    $('input[name="empCustomerId"]').val() == "") ||
                  typeof $('input[name="empCustomerId"]').attr(
                    "iscouponbonus"
                  ) !== "undefined"
                ) {
                  $('input[name="empCustomerId"]').val(data.customerid);
                  $('input[name="empCustomerId_nameField"]').val(
                    data.customername
                  );
                  $('input[name="empCustomerId_displayField"]').val(
                    data.customercode
                  );
                  $('input[name="serviceCustomerId"]').val(data.customerid);
                  $('input[name="serviceCustomerId_nameField"]')
                    .val(data.customername)
                    .prop("disabled", true);
                  $('input[name="serviceCustomerId_displayField"]')
                    .val(data.customercode)
                    .prop("disabled", true);
                  $("#invInfoCustomerLastName").val(data.lastname);
                  $("#invInfoCustomerName").val(data.customername);
                  $("#invInfoCustomerRegNumber").val(data.stateregnumber);
                }

                posSumVoucher2Amount();
                posCalcChangeAmount();

                $voucherDtlAmount.focus().select();
              } else {
                $row
                  .find('input[name="voucher2DtlAmount[]"]')
                  .val("")
                  .attr("readonly", "readonly");
                $row
                  .find(
                    'input[name="voucher2DtlId[]"], input[name="voucher2TypeId[]"]'
                  )
                  .val("");

                new PNotify({
                  title: data.status,
                  text: data.message,
                  type: data.status,
                  sticker: false,
                });
              }

              Core.unblockUI();
            },
          });
        } else {
          $row
            .find('input[name="voucher2DtlAmount[]"]')
            .val("")
            .attr("readonly", "readonly");
          $row
            .find(
              'input[name="voucher2DtlId[]"], input[name="voucher2TypeId[]"]'
            )
            .val("");

          posSumVoucher2Amount();
          posCalcChangeAmount();
        }
      }
    }
  );

  $(document.body).on("keydown", "#cardNumber, #cardPhoneNumber", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      var $this = $(this);
      posCardNumber($this);
      posCardNumberByPhoneNumber($this);
      e.preventDefault();
    }
  });

  $(document.body).on("keydown", "#cardPinCode", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      var $this = $(this);
      posCardNumberPinCode($this);
      e.preventDefault();
    }
  });

  $(document.body).on("change", "select#cityId", function () {
    if ($(this).val() !== "") {
      var $districtId = $("select#districtId");
      $districtId.select2("enable");
      $districtId.removeClass("data-combo-set");
    }
  });
  $(document.body).on("change", "select#districtId", function () {
    if ($(this).val() !== "") {
      var $streetId = $("select#streetId");
      $streetId.select2("enable");
      $streetId.removeClass("data-combo-set");
    }
  });

  $(document.body).on(
    "change",
    '#pos-payment-form input[data-path="serviceCustomerId"]',
    function () {
      var $deliveryPanel = $(".pos-payment-delivery-header");
      var $this = $(this),
        rowData = JSON.parse($this.attr("data-row-data"));
      var descr = "";

      $("#invInfoCustomerLastName").val(rowData.lastname);
      $("#invInfoCustomerName").val(rowData.customername);
      $("#invInfoCustomerRegNumber").val(rowData.positionname);
      $("#invInfoPhoneNumber").val(rowData.phonenumber1);

      if (rowData.lastname) {
        descr += rowData.lastname + " ";
      }

      if (rowData.customername) {
        descr += rowData.customername + " ";
      }

      if (rowData.positionname) {
        descr += rowData.positionname + " ";
      }

      if (rowData.phonenumber1) {
        descr += rowData.phonenumber1;
      }
      
      $("#invInfoTransactionValue").val(descr.trim());
      
      return;

      $("#pos-org-name").val("");
      $("#pos-org-number").val("");
      $("#pos-org-vatpayer").val("");
      var $posPayAmount = $("#posPayAmount"),
        vatAmount = Number($("td.pos-amount-vat").autoNumeric("get")),
        payAmount = Number($("#tmpPayAmount").val()),
        regNumber = rowData.positionname;

      $.ajax({
        type: "post",
        url: "mdpos/getOrganizationInfo",
        data: { regNumber: regNumber },
        dataType: "json",
        beforeSend: function () {
          Core.blockUI({
            message: "Loading...",
            boxed: true,
          });
        },
        success: function (data) {
          if (data.name != "") {
            data.vatpayer = true;

            $("#pos-org-number").val(regNumber);
            $("#pos-org-name").val(data.name);
            $("#pos-org-vatpayer").val(data.vatpayer);

            if (data.vatpayer == false) {
              $posPayAmount.autoNumeric("set", payAmount - vatAmount);
            } else {
              $posPayAmount.autoNumeric("set", payAmount);
            }
          } else {
            PNotify.removeAll();

            if (data.hasOwnProperty("message") && data.message != "") {
              new PNotify({
                title: "Warning",
                text: data.message,
                type: "warning",
                sticker: false,
              });
            } else {
              $("#pos-org-number").select().focus();
              new PNotify({
                title: "Warning",
                text: "Татвар төлөгчийн дугаар буруу байна!",
                type: "warning",
                sticker: false,
              });
            }

            $("#pos-org-vatpayer").val("");
            $posPayAmount.autoNumeric("set", payAmount);
          }

          Core.unblockUI();
        },
      });     

      if ($deliveryPanel.is(":visible")) {
        $("#recipientName").val(rowData.customername);

        if (rowData.cityid != null) {
          $('select[name="cityId"]').trigger("select2-opening", [true]);
          $('select[name="cityId"]').select2("val", rowData.cityid);

          var $districtId = $("select#districtId");
          $districtId.select2("enable");
          $districtId.removeClass("data-combo-set");
        }

        if (rowData.districtid != null) {
          $('select[name="districtId"]').trigger("select2-opening", [true]);
          $('select[name="districtId"]').select2("val", rowData.districtid);

          var $streetId = $("select#streetId");
          $streetId.select2("enable");
          $streetId.removeClass("data-combo-set");
        }

        if (rowData.streetid != null) {
          $('select[name="streetId"]').trigger("select2-opening", [true]);
          $('select[name="streetId"]').select2("val", rowData.streetid);
        }

        $("#detailAddress").val(rowData.description);
        $("#phone1").val(rowData.phonenumber1);
        $("#phone2").val(rowData.phonenumber2);
      }
    }
  );

  $(document.body).on(
    "change",
    '#pos-payment-form input[data-path="prePaymentCustomerId"]',
    function () {
      var $this = $(this),
        rowData = JSON.parse($this.attr("data-row-data"));

      $row = $this.closest(".pos-prepayment-row");
      var $prePaymentAmount = $row.find('input[name="prePyamentDtlAmount"]');
      $prePaymentAmount.autoNumeric("set", rowData.debitamount);

      posSumPrePaymentAmount();
      posCalcChangeAmount();
    }
  );

  $(document.body).on(
    "change",
    '#posTable input[data-path="employeeId"]',
    function () {
      var $this = $(this),
        $parent = $this.parent();

      if ($parent.find('input[name="employeeId[]"]').val() != "") {
        var empCode = $parent.find("input.lookup-code-autocomplete").val(),
          empName = $parent.find("input.lookup-name-autocomplete").val();

        $('td[data-field-name="detail-salesperson"]').text(
          empCode + " - " + empName
        );
      } else {
        $('td[data-field-name="detail-salesperson"]').text("");
      }
    }
  );

  $(document.body).on(
    "keydown",
    '#posTable input[data-field-name="employeeId"]',
    function (e) {
      var keyCode = e.keyCode ? e.keyCode : e.which;
      var $this = $(this);

      if (keyCode === 38) {
        // up

        if ($(".ui-autocomplete.ui-widget:visible").length == 0) {
          var $rowCell = $this.closest("td"),
            $row = $this.closest("tr"),
            $prevRow = $row.prevAll("tr[data-item-id]:visible:eq(0)"),
            $colIndex = $rowCell.index();

          if ($prevRow.length) {
            $prevRow
              .find(
                "td:eq(" +
                $colIndex +
                ') input[data-field-name="employeeId"]:first'
              )
              .focus()
              .select();
            $prevRow.click();
          }

          return e.preventDefault();
        }
      } else if (keyCode === 40) {
        // down

        if ($(".ui-autocomplete.ui-widget:visible").length == 0) {
          var $rowCell = $this.closest("td"),
            $row = $this.closest("tr"),
            $nextRow = $row.nextAll("tr[data-item-id]:visible:eq(0)"),
            $colIndex = $rowCell.index();

          if ($nextRow.length) {
            $nextRow
              .find(
                "td:eq(" +
                $colIndex +
                ') input[data-field-name="employeeId"]:first'
              )
              .focus()
              .select();
            $nextRow.click();
          }

          return e.preventDefault();
        }
      } else if (keyCode === 13) {
        // enter

        var $rowCell = $this.closest("td"),
          $row = $this.closest("tr"),
          $nextRow = $row.nextAll("tr[data-item-id]:visible:eq(0)"),
          $colIndex = $rowCell.index();

        if ($nextRow.length) {
          $nextRow
            .find(
              "td:eq(" +
              $colIndex +
              ') input[data-field-name="employeeId"]:first'
            )
            .focus()
            .select();
          $nextRow.click();
        } else {
          $row
            .find(
              "td:eq(" +
              $colIndex +
              ') input[data-field-name="employeeId"]:first'
            )
            .focus()
            .select();
        }

        return e.preventDefault();
      } else if (keyCode === 46) {
        // delete

        if (returnBillType == "" || returnBillType == "typeReduce") {
          var $thisRow = $this.closest("tr");
          posRowRemove($thisRow);
        }

        return e.preventDefault();
      }
    }
  );

  $(document.body).on("keydown", ".invInfoField", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      var $this = $(this),
        attrId = $this.attr("id");

      if (attrId == "invInfoCustomerRegNumber") {
        if ($this.val() != "") {
          PNotify.removeAll();

          $.ajax({
            type: "post",
            url: "mdpos/getCustomerInfoByRegNumber",
            data: { regNumber: $this.val() },
            dataType: "json",
            beforeSend: function () {
              Core.blockUI({
                message: "Харилцагчийг хайж байна...",
                boxed: true,
              });
            },
            success: function (rowData) {
              if (rowData.hasOwnProperty("positionname")) {
                var descr = "";

                if (rowData.lastname) {
                  $("#invInfoCustomerLastName").val(rowData.lastname);

                  descr += rowData.lastname + " ";
                }

                if (rowData.customername) {
                  $("#invInfoCustomerName").val(rowData.customername);

                  descr += rowData.customername + " ";
                }

                if (rowData.positionname) {
                  descr += rowData.positionname + " ";
                }

                if (rowData.phonenumber1) {
                  $("#invInfoPhoneNumber").val(rowData.phonenumber1);

                  descr += rowData.phonenumber1;
                }

                $("#invInfoTransactionValue").val(descr.trim());
              } else {
                new PNotify({
                  title: "Warning",
                  text: "Уг регистрээр харилцагч олдсонгүй!",
                  type: "warning",
                  sticker: false,
                });
              }

              Core.unblockUI();
            },
          });
        }
      } else {
        var $tbl = $this.closest("#pos-payment-account-transfer"),
          $tblInput = $tbl.find(
            '.invInfoField:visible:not([readonly="readonly"], [readonly], readonly)'
          ),
          $cellIndex = $tblInput.index($this),
          $focusField = $tblInput.eq($cellIndex + 1),
          invoiceJsonStr = $("#invoiceJsonStr").val(),
          invInfoInvoiceNumber = $("#invInfoInvoiceNumber").val(),
          invInfoBookNumber = $("#invInfoBookNumber").val(),
          invInfoCustomerLastName = $("#invInfoCustomerLastName").val(),
          invInfoCustomerName = $("#invInfoCustomerName").val(),
          invInfoCustomerRegNumber = $("#invInfoCustomerRegNumber").val(),
          invInfoPhoneNumber = $("#invInfoPhoneNumber").val();

        if ($focusField.length) {
          $focusField.focus().select();
        } else {
          $tblInput.eq(0).focus().select();
        }

        if (invoiceJsonStr != "") {
          var invoiceJsonStrObj = JSON.parse(invoiceJsonStr);
          invInfoInvoiceNumber = invoiceJsonStrObj.booknumber;
        }

        var descr =
          invInfoInvoiceNumber +
          " " +
          invInfoBookNumber +
          " " +
          invInfoCustomerLastName +
          " " +
          invInfoCustomerName +
          " " +
          invInfoCustomerRegNumber +
          " " +
          invInfoPhoneNumber;

        $("#invInfoTransactionValue").val(descr.trim());
      }

      return e.preventDefault();
    }
  });

  $(document.body).on("change", ".invInfoField", function () {
    var invoiceJsonStr = $("#invoiceJsonStr").val(),
      invInfoInvoiceNumber = $("#invInfoInvoiceNumber").val(),
      invInfoBookNumber = $("#invInfoBookNumber").val(),
      invInfoCustomerLastName = $("#invInfoCustomerLastName").val(),
      invInfoCustomerName = $("#invInfoCustomerName").val(),
      invInfoCustomerRegNumber = $("#invInfoCustomerRegNumber").val(),
      invInfoPhoneNumber = $("#invInfoPhoneNumber").val();

    if (invoiceJsonStr != "") {
      var invoiceJsonStrObj = JSON.parse(invoiceJsonStr);
      invInfoInvoiceNumber = invoiceJsonStrObj.booknumber;
    }

    var descr =
      invInfoInvoiceNumber +
      " " +
      invInfoBookNumber +
      " " +
      invInfoCustomerLastName +
      " " +
      invInfoCustomerName +
      " " +
      invInfoCustomerRegNumber +
      " " +
      invInfoPhoneNumber;

    $("#invInfoTransactionValue").val(descr.trim());

    var $this = $(this),
      inputName = $this.attr("name");

    if (
      inputName == "invInfoCustomerLastName" ||
      inputName == "invInfoCustomerName"
    ) {
      descr = invInfoCustomerLastName + " " + invInfoCustomerName;
      $("#recipientName").val(descr.trim());
    }

    if (inputName == "invInfoPhoneNumber") {
      descr = invInfoPhoneNumber;
      $("#phone1").val(descr.trim());
    }
  });

  /*$(document.body).on('change', '.invAmountField', function(){
      
      var invAmount = Number($('.invAmountField').sum());
      
      if (invAmount > 0) {
        $('.invInfoFieldAll').prop('disabled', false);
      } else {
        $('.invInfoFieldAll').prop('disabled', true);
      }
    });*/

  $(document.body).on("keydown", "#pos-discount-percent", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      var $itemRow = $("#posTable").find("tbody > tr.pos-selected-row");

      $itemRow.find("input.pos-quantity-input").focus().select();
      return e.preventDefault();
    }
  });

  $(document.body).on("keyup", "#upointCardPinCode", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      posUpointCardRead();
      return e.preventDefault();
    }
    // if ($(this).val().length === 4) {
    //   posUpointCardRead();
    //   return e.preventDefault();
    // }
  });

  $(document.body).on("keydown", "#upointCardNumber2", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      $("#upointMobile2").val("");
      $("#upointMobile").val("");
      $("#upointCardPinCode").focus().select();
      return e.preventDefault();
    }
  });

  $(document.body).on("keydown", "#upointMobile2", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      $("#upointCardNumber2").val("");
      $("#upointCardNumber").val("");
      $("#upointCardPinCode").focus().select();
      return e.preventDefault();
    }
  });

  $(document.body).on("change", "#pos-discount-percent", function () {
    var $this = $(this),
      $itemRow = $("#posTable").find("tbody > tr.pos-selected-row");

    posCalcRowDiscountPercent($this, $itemRow);
  });

  $(document.body).on("keydown", "#pos-discount-amount", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      var $itemRow = $("#posTable").find("tbody > tr.pos-selected-row");

      $itemRow.find("input.pos-quantity-input").focus().select();
      return e.preventDefault();
    }
  });

  $(document.body).on("change", "#pos-discount-amount", function () {
    var $this = $(this),
      $itemRow = $("#posTable").find("tbody > tr.pos-selected-row");

    posCalcRowDiscountAmount($this, $itemRow);
  });

  $(document.body).on(
    "dblclick",
    '.posKeyAmount:not([readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)',
    function () {
      var $this = $(this),
        posBalanceAmount = Number($("#posBalanceAmount").autoNumeric("get"));

      // Hide input дээр давхар үнэ нт нэмэгдэ дэж бодоод банкны төлбөрийн нөхцөл дээр алдаа гараад байгаа
      // if (thisValue > 0 && posBalanceAmount > 0) {
      //   const total = posBalanceAmount + thisValue; 
      //   $this.autoNumeric("set", total);
      // }

      if (posBalanceAmount > 0) {
        $this.autoNumeric("set", "").trigger('change').trigger('keydown');
      }

      if ($this.attr("name") == "posLocalExpenseAmt") {
        $("#posLocalExpenseAmt")
          .autoNumeric("set", $("#posPayAmount").autoNumeric("get"))
          .trigger("change");
      }

      posCalcPaidAmount();
      posBalanceAmount = Number($("#posBalanceAmount").autoNumeric("get"));

      if (posBalanceAmount > 0) {
        var inputName = $this.attr("name");

        $this.autoNumeric("set", posBalanceAmount);

        if (
          inputName == "bankAmountDtl[]" ||
          inputName == "voucherDtlAmount[]"
        ) {
          $this.trigger("change", [true]);
        }

        posCalcPaidAmount();
      }
    }
  );

  $(document.body).on("keydown", ".posAddressField", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      var $this = $(this),
        $tbl = $this.closest("#pos-payment-accordion-delivery"),
        $tblInput = $tbl.find(
          'input.posAddressField:visible:not([readonly="readonly"], [readonly], readonly), select.posAddressField:visible:not([readonly="readonly"], [readonly], readonly), textarea.posAddressField:visible:not([readonly="readonly"], [readonly], readonly)'
        ),
        $cellIndex = $tblInput.index($this),
        $focusField = $tblInput.eq($cellIndex + 1);

      if ($focusField.length) {
        if ($focusField.prop("tagName") == "SELECT") {
          $focusField.select2("open");
        } else {
          $focusField.focus().select();
        }
      } else {
        $tblInput.eq(0).focus().select();
      }

      return e.preventDefault();
    }
  });

  if (selectedCustomerId) {
    $.ajax({
      type: "post",
      url: "api/callDataview",
      data: {
        dataviewId: "1536742182010",
        criteriaData: { id: [{ operator: "=", operand: selectedCustomerId }] },
      },
      dataType: "json",
      success: function (data) {
        if (data.status === "success" && data.result[0]) {
          $('input[name="empCustomerId"]').val(data.result[0].id);
          $('input[name="empCustomerId_displayField"]').val(
            data.result[0].customercode
          );
          $('input[name="empCustomerId_nameField"]').val(
            data.result[0].customername
          );
          $('input[name="empCustomerId"]')
            .attr("data-row-data", JSON.stringify(data.result[0]))
            .trigger("change");
        } else {
          $('input[name="empCustomerId"]').val("");
          $('input[name="empCustomerId_displayField"]').val("");
          $('input[name="empCustomerId_nameField"]').val("");
          $('input[name="empCustomerId"]').attr("data-row-data", "");
        }
      },
    });
  }
  if (selectedItemId) {
    var itemPostData = {
      itemId: selectedItemId,
      isReceiptNumber: isReceiptNumber,
      receiptRegNumber: receiptRegNumber,
      receiptDetails: drugPrescription,
    };
    appendItem(
      itemPostData,
      $(".pos-card-layout").length ? "card" : "",
      function () { }
    );
  }

  $(document.body).on("change", "select.posAddressField", function (e) {
    var $this = $(this),
      $tbl = $this.closest("#pos-payment-accordion-delivery"),
      $tblInput = $tbl.find(
        'input.posAddressField:visible:not([readonly="readonly"], [readonly], readonly), select.posAddressField:visible:not([readonly="readonly"], [readonly], readonly), textarea.posAddressField:visible:not([readonly="readonly"], [readonly], readonly)'
      ),
      $cellIndex = $tblInput.index($this),
      $focusField = $tblInput.eq($cellIndex + 1);

    if ($focusField.length) {
      if ($focusField.prop("tagName") == "SELECT") {
        $focusField.select2("open");
      } else {
        $focusField.focus().select();
      }
    } else {
      $tblInput.eq(0).focus().select();
    }

    return e.preventDefault();
  });

  $(document.body).on("change", "#posLocalExpenseAmt", function () {
    var $this = $(this),
      localExpenseAmount = $this.autoNumeric("get"),
      $parent = $this.closest(".pos-payment-area");


    $(".posKeyAmount").addClass("newReadOnly");

    if ($(".posKeyAmount[readonly]").hasClass("oldReadOnly") === false) {
      $(".posKeyAmount[readonly]").addClass("oldReadOnly").removeClass('newReadOnly');
    } else if ($(".posKeyAmount[readonly]").hasClass("oldReadOnly") === true) {
      $(".oldReadOnly").removeClass('newReadOnly');
    }

    if (localExpenseAmount > 0) {
      var $bankDtl = $(".pos-bank-row-dtl"),
        $bankRow = $bankDtl.find(".pos-bank-row");

      if ($bankRow.length > 1) {
        $bankRow.not(":eq(0)").remove();
      }

      $parent
        .find(".newReadOnly")
        .not($this)
        .attr("readonly", "readonly")
        .autoNumeric("set", "");

      $parent
        .find(".posUserAmount")
        .not($this)
        .attr("readonly", "readonly")
        .autoNumeric("set", "");

    } else {
      $parent.find(".newReadOnly").removeAttr("readonly").removeClass('newReadOnly');
      $parent.find(".posKeyAmount").removeClass('oldReadOnly');
    }

    posCalcChangeAmount();
  });

  $(document.body).on("keydown", "#posLocalExpenseAmt", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;

    if (keyCode == 13) {
      $(this).trigger("change");
    }
  });

  $(document.body).on(
    "keydown",
    ".pos-account-statement-input:visible",
    function (e) {
      var keyCode = e.keyCode ? e.keyCode : e.which;

      if (keyCode == 13) {
        var $this = $(this),
          $tbl = $this.closest(".xs-form"),
          $tblInput = $tbl.find(
            '.pos-account-statement-input:visible:not([readonly="readonly"], [readonly], readonly)'
          ),
          $cellIndex = $tblInput.index($this),
          $focusField = $tblInput.eq($cellIndex + 1);

        if ($focusField.length) {
          $focusField.focus().select();
        } else {
          $tbl
            .find('button[onclick*="filterAccountStatement"]')
            .focus()
            .click();
        }

        return e.preventDefault();
      }
    }
  );

  $(document.body).on(
    "change",
    'select[name="accountTransferBankIdDtl[]"]',
    function () {
      var $this = $(this);
      if ($this.select2("val") == "") {
        var $atrow = $this.closest(".pos-accounttransfer-row");
        $atrow
          .find(".bigdecimalInit")
          .autoNumeric("set", "")
          .removeAttr("readonly");
        $atrow.find('input[type="hidden"]').val("");
        posSumAccountTransferAmount();
      }
      var $this = $(this);
      ($tbl = $this.closest(".pos-payment-area")),
        ($tblInput = $tbl.find(
          '.posKeyAmount:visible:not([readonly="readonly"], [readonly], readonly)'
        )),
        ($cellIndex = $tblInput.index(
          $this
            .closest(".pos-accounttransfer-row")
            .find(
              '.posKeyAmount:visible:not([readonly="readonly"], [readonly], readonly)'
            )
        )),
        ($focusField = $tblInput.eq($cellIndex + 1));
      if ($focusField.length) {
        $focusField.focus().select();
      } else {
        $tblInput.eq(0).focus().select();
      }
    }
  );

  $(document.body).on(
    "click",
    "#account-statement-list > tbody > tr",
    function () {
      var $this = $(this);
      var $tbody = $this.closest("tbody");
      $tbody.find(".selected").removeClass("selected");
      $this.addClass("selected");
      $tbody.find("i").remove();
      $this
        .find('td[data-cell-name="check"]')
        .html('<i class="fa fa-check-circle"></i>');
    }
  );

  $(".pos-card-layout").on("click", ".grid-card-item", function (e) {
    var $this = $(this);
    if ($(e.target).closest(".basket-qty-button").length) {
      return;
    }

    var itemPostData = {
      code: $this.attr("data-itemcode"),
      isReceiptNumber: isReceiptNumber,
      receiptRegNumber: receiptRegNumber,
      receiptDetails: drugPrescription,
    };

    appendItem(itemPostData, "card", function () { });
  });

  $(".pos-card-layout").on("click", ".basket-button", function () {
    var $this = $(this);
    var $thisP = $(this).closest(".grid-card-item");

    var itemPostData = {
      code: $thisP.attr("data-itemcode"),
      isReceiptNumber: isReceiptNumber,
      receiptRegNumber: receiptRegNumber,
      receiptDetails: drugPrescription,
    };

    $this.addClass("d-none");
    $this.parent().find(".basket-qty-button").attr("style", "");
    appendItem(itemPostData, "card", function () { });
  });

  $(".pos-card-layout").on("click", ".basket-qty-button input", function () {
    var $this = $(this);
    $this.focus().select();
  });

  $(".pos-card-layout").on("change", ".basket-qty-button input", function (e) {
    var $this = $(this);
    var qty = Number($this.autoNumeric("get"));
    var itemId = $this.closest(".grid-card-item").attr("data-itemid");

    if ((qty > 99999 || qty < 1) && returnBillType != "typeReduce") {
      PNotify.removeAll();
      new PNotify({
        title: "Warning",
        text: "Тоо хэмжээ буруу байна!",
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      setTimeout(function () {
        $this.autoNumeric("set", 1);
        $("#posTable")
          .find('tr[data-item-id="' + itemId + '"]')
          .find("input.pos-quantity-input")
          .autoNumeric("set", 1)
          .trigger("change");
      }, 2);
      return e.preventDefault();
    }

    $("#posTable")
      .find('tr[data-item-id="' + itemId + '"]')
      .find("input.pos-quantity-input")
      .autoNumeric("set", qty)
      .trigger("change");
    if ($this.attr("data-seperatevalue") < qty) {
      $this.attr("data-seperatevalue", qty);
    }
  });

  $(".pos-card-layout").on(
    "click",
    ".basket-qty-button > span:first-child",
    function (e) {
      var $this = $(this);
      var qty = Number(
        $this.closest(".basket-qty-button").find("input").autoNumeric("get")
      );

      if (qty > 1) {
        $this
          .parent()
          .find("input")
          .autoNumeric("set", qty - 1)
          .trigger("change");
      } else {
        var itemId = $this.closest(".grid-card-item").attr("data-itemid");
        posRowRemove($("#posTable").find('tr[data-item-id="' + itemId + '"]'));
      }
    }
  );

  $(".pos-card-layout").on(
    "click",
    ".basket-qty-button > span:last-child",
    function (e) {
      var $this = $(this);
      var qty = Number(
        $this.closest(".basket-qty-button").find("input").autoNumeric("get")
      );
      qty++;

      $this.parent().find("input").autoNumeric("set", qty).trigger("change");
      if ($this.attr("data-seperatevalue") < qty) {
        $this.attr("data-seperatevalue", qty);
      }
    }
  );

  $(".pos-card-layout").on(
    "change",
    ".basket-inputqty-button input",
    function (e) {
      var $this = $(this);
      var qty = Number($this.autoNumeric("get"));
      var itemId = $this.closest("tr").attr("data-item-id");

      if (
        $(".pos-card-layout").find('div[data-itemid="' + itemId + '"]').length
      ) {
        $(".pos-card-layout")
          .find('div[data-itemid="' + itemId + '"]')
          .find("input")
          .autoNumeric("set", qty);
      }
    }
  );

  $(document.body).on("keydown", '#guestName', function (e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    var _this = $(this);
    if (code === 13) {
      if (_this.data("ui-autocomplete")) {
        _this.autocomplete("destroy");
      }
      $.ajax({
        type: "post",
        url: "api/callDataview",
        dataType: 'json',
        data: {
          dataviewId: "1536742182010",
          criteriaData: {
            filterCustomerKyc: [
              {
                operator: "like",
                operand: '%' + _this.val() + '%',
              },
            ],
          },
        },
        success: function (data) {
          if (data.status == 'success' && data.result[0]) {
            $('input[name="empCustomerId"]').val(data.result[0].id);
            $('input[name="empCustomerId_displayField"]').val(data.result[0].customercode);
            $('input[name="empCustomerId_nameField"]').val(data.result[0].customername);
            $('#guestName').val(data.result[0].customercode + ' - ' + $.trim(data.result[0].customername));
          }
        }
      });
      posRestBasketList(
        "nullmeta",
        "0",
        tempInvoiceDvId,
        "single",
        "nullmeta",
        $(this),
        "casherCheck"
      );
      return false;
    } else {
      if (!_this.data("ui-autocomplete")) {
        lookupAutoCompletePosGuestName(_this, 'code');
      }
    }
  });

  $(".pos-card-layout").on(
    "click",
    ".basket-inputqty-button > span:first-child",
    function (e) {
      var $this = $(this);
      var qty = Number($this.parent().find("input").autoNumeric("get"));
      var itemId = $this.closest("tr").attr("data-item-id");

      if (qty > 1) {
        qty--;
        if (
          $(".pos-card-layout").find('div[data-itemid="' + itemId + '"]').length
        ) {
          $(".pos-card-layout")
            .find('div[data-itemid="' + itemId + '"]')
            .find("input")
            .autoNumeric("set", qty);
        }
        $this.parent().find("input.pos-quantity-input").focus().select();
        $this
          .parent()
          .find("input.pos-quantity-input")
          .autoNumeric("set", qty)
          .trigger("change");
      } else {
        var $thisRow = $this.closest("tr");
        posRowRemove($thisRow);
      }
    }
  );

  $(".pos-card-layout").on(
    "click",
    ".basket-inputqty-button > span:last-child",
    function (e) {
      var $this = $(this);
      var qty = Number($this.parent().find("input").autoNumeric("get"));
      var itemId = $this.closest("tr").attr("data-item-id");

      qty++;
      if (
        $(".pos-card-layout").find('div[data-itemid="' + itemId + '"]').length
      ) {
        $(".pos-card-layout")
          .find('div[data-itemid="' + itemId + '"]')
          .find("input")
          .autoNumeric("set", qty);
      }
      $this.parent().find("input.pos-quantity-input").focus().select();
      $this
        .parent()
        .find("input.pos-quantity-input")
        .autoNumeric("set", qty)
        .trigger("change");
      if ($this.attr("data-seperatevalue") < qty) {
        $this.attr("data-seperatevalue", qty);
      }
    }
  );

  $(".pos-card-layout").on(
    "click",
    ".back-item-btn:not(.change-view)",
    function (e) {
      var $this = $(this);
      itemGroup("");
    }
  );

  $(".pos-card-layout").on("click", ".change-view", function (e) {
    var $this = $(this);

    $this.parent().find(".change-view").removeClass("active");
    $this.addClass("active");
    $(".pos-card-layout")
      .find(".card-data-container")
      .removeClass("pos-card-view")
      .removeClass("pos-list-view")
      .addClass("pos-" + $this.attr("data-actiontype") + "-view");
    if ($this.attr("data-actiontype") == "card") {
      $(".pos-card-layout").find(".card-img-actions").removeClass("d-none");
    } else {
      $(".pos-card-layout").find(".card-img-actions").addClass("d-none");
    }
  });

  if ($(".pos-card-layout").length) {
    itemGroup("");
  }

  $(".pos-card-layout").on("click", ".grid-card-itemgroup", function (e) {
    var $this = $(this);
    var itemId = $this.attr("data-filterid");

    $(".pos-card-layout")
      .find(".card-options")
      .show()
      .removeClass("justify-content-center");
    $(".pos-card-layout").find(".back-item-btn").show();
    $(".pos-card-layout")
      .find(".item-card-toptitle")
      .text($this.attr("data-name"));

    if ($this.attr("data-ischild") == "1") {
      itemGroup(itemId);
      //$('.pos-card-layout').find('.back-item-btn').attr('data-actiontype', 'card');
    } else {
      item(itemId, $this.attr("data-name"));
    }
  });

  $(document.body).on("change", "#posEmpLoanAmt", function () {
    var $this = $(this);
    var posEmpLoanAmt = Number($this.autoNumeric("get"));

    var dataCriteria = "";
    var posBarterAmt = Number($("#posBarterAmt").autoNumeric("get"));

    if (posBarterAmt > 0) {
      dataCriteria = "5";
    }

    if (posEmpLoanAmt > 0) {
      if (dataCriteria != "") {
        dataCriteria += ",7";
      } else {
        dataCriteria = "7";
      }

      $("#serviceCustomerId_valueField").attr(
        "data-criteria",
        "filterTypeId=" + dataCriteria
      );
    } else {
      if (posBarterAmt == 0) {
        $("#serviceCustomerId_valueField").removeAttr("data-criteria");
      }
    }
  });

  $(document.body).on("change", "#candyTypeCode", function () {
    var candyTypeCode = $(this).select2("val");

    if (candyTypeCode) {
      $("#candyAmount").focus();

      if (candyTypeCode == "ISDN") {
        $(".candy-tancode-send").removeClass("hide");
        $(
          ".candy-tancode-confirm, .candy-pincode-confirm, .candy-qr-generate, .candy-qrcode-read"
        ).addClass("hide");
        $("#pincode-row").hide();
        $("#candyNumber, #tancode-row").show();
      } else if (
        candyTypeCode == "CARDID" ||
        candyTypeCode == "NFCID" ||
        candyTypeCode == "LOYALTYID"
      ) {
        $(
          ".candy-tancode-send, .candy-tancode-confirm, .candy-qr-generate, .candy-qrcode-read"
        ).addClass("hide");
        $(".candy-pincode-confirm").removeClass("hide");
        $("#tancode-row").hide();
        $("#candyNumber, #pincode-row").show();
      } else if (candyTypeCode == "QRCODEGENERATE") {
        $(".candy-qr-generate").removeClass("hide");
        $(
          ".candy-tancode-confirm, .candy-pincode-confirm, .candy-tancode-send, .candy-qrcode-read"
        ).addClass("hide");
        $("#candyNumber, #tancode-row, #pincode-row").hide();
      } else if (candyTypeCode == "QRCODEREAD") {
        $("#candyNumber").val("").attr("readonly", "readonly").show();
        $(
          ".candy-tancode-send, .candy-tancode-confirm, .candy-pincode-confirm, .candy-qr-generate"
        ).addClass("hide");
        $("#tancode-row, #pincode-row").hide();
      } else {
        $("#candyNumber").val("").attr("readonly", "readonly").show();
        $(
          ".candy-tancode-send, .candy-tancode-confirm, .candy-pincode-confirm, .candy-qr-generate, .candy-qrcode-read"
        ).addClass("hide");
        $("#tancode-row, #pincode-row").hide();
      }
    } else {
      $("#candyNumber").val("").attr("readonly", "readonly").show();
      $(
        ".candy-tancode-send, .candy-tancode-confirm, .candy-pincode-confirm, .candy-qr-generate, .candy-qrcode-read"
      ).addClass("hide");
      $("#tancode-row, #pincode-row").hide();
    }
  });

  $(document.body).on("keyup", "#candyAmount", function () {
    var amount = $(this).autoNumeric("get");
    var posPayAmount = $("#posPayAmount").autoNumeric("get");
    if (Number(posPayAmount) < Number(amount)) {
      $(".candy-overflow-amount-message").removeClass("d-none");
      $(this).val("");
    } else {
      $(".candy-overflow-amount-message").addClass("d-none");
    }
  });

  $(document.body).on("keydown", "#candyAmount", function (e) {
    var keyCode = e.keyCode ? e.keyCode : e.which;
    if (keyCode === 13) {
      if ($("#candyNumber").is(":visible")) {
        $("#candyNumber").focus();
      } else {
        $(".candy-qr-generate").click();
      }

      return e.preventDefault();
    }
  });

  $(document).scannerDetection({
    timeBeforeScanTest: 250,
    avgTimeByChar: 150,
    stopPropagation: true,
    ignoreIfFocusOn: $("body").find("input.ignorebarcode"),
    onComplete: function (barcode, qty) {
//        if ($("#scanItemCode").length) {
//            $("#scanItemCode").val(barcode);
//            var e = jQuery.Event("keydown");
//            e.keyCode = e.which = 13;
//            $("#scanItemCode").trigger(e);          
//            console.log('barcode',barcode)
//        }
      if (
        $("body").find("#dialog-pos-payment").length > 0 &&
        $("body").find("#dialog-pos-payment").is(":visible")
      ) {
        if ($("body").find("#candyNumber").is(":visible")) {
          var candyTypeCode = $("#candyTypeCode").select2("val");
          if (candyTypeCode == "QRCODEREAD") {
            $("#candyNumber").focus();
            candyQRCodeRead(barcode);
          }
        } else if ($("body").find("#candyCoupen").is(":visible")) {
          var $cardNumber = $("#cardNumber");
          $cardNumber.val(barcode);
          candyCoupenQRCodeRead(barcode);
        }
      }
      if (
        $("body").find("#dialog-pos-candy").length > 0 &&
        $("body").find("#dialog-pos-candy").is(":visible")
      ) {
        $("#candyCoupen").val(barcode);
      }
    },
  });

  var beforePrint = function () {
    console.log("Functionality to run before printing.");
  };
  var afterPrint = function () {
    console.log("Functionality to run after printing");
  };

  if (window.matchMedia) {
    var mediaQueryList = window.matchMedia("print");
    mediaQueryList.addListener(function (mql) {
      if (mql.matches) {
        beforePrint();
      } else {
        afterPrint();
      }
    });
  }

  var $tbody = $("#posTable").find("> tbody");

  if ($tbody.find("> tr").length) {
    posConfigVisibler($tbody);
    Core.initLongInput($tbody);
    Core.initDecimalPlacesInput($tbody, 3);
    Core.initUniform($tbody);

    posGiftRowsSetDelivery($tbody);

    var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");
    $firstRow.click();

    posFixedHeaderTable();
    posCalcTotal();
  }

  if (posUseIpTerminal === "1") {
    if (bankIpterminals.hasOwnProperty("500000")) {
      posConnectBankTerminal(bankIpterminals["500000"], "khanbank");
    }
    if (bankIpterminals.hasOwnProperty("150000")) {
      posConnectBankTerminal(bankIpterminals["150000"], "golomtbank");
    }
    if (bankIpterminals.hasOwnProperty("320000")) {
      posConnectBankTerminal(bankIpterminals["320000"], "xacbank");
    }
    if (bankIpterminals.hasOwnProperty("400000")) {
      posConnectBankTerminal(bankIpterminals["400000"], "tdbank");
    }
  }

  $(document.body).on("change", 'select[name="posBankIdDtl[]"]', function () {
    if (posUseIpTerminal === "1") {
      var $tselect = $(this);
      var bankCode = $tselect.find("option:selected").data("bankcode");

      if (bankCode == 150000) {
        var posBalanceAmountChangeCombo = $tselect
          .closest(".pos-bank-row")
          .find('input[name="bankAmountDtl[]"]')
          .autoNumeric("get");

        if (bankIpterminals.hasOwnProperty(bankCode)) {
          isAcceptPrintPos = false;
          bankIpTerminalTransfer(
            posBalanceAmountChangeCombo,
            bankIpterminals[bankCode],
            "golomtbank",
            function (res) {
              setValuePosGolomtBank($tselect, res);
            }
          );
        }
      }

      if (bankCode == 500000) {
        var posBalanceAmountChangeCombo = $tselect
          .closest(".pos-bank-row")
          .find('input[name="bankAmountDtl[]"]')
          .autoNumeric("get");

        if (bankIpterminals.hasOwnProperty(bankCode)) {
          isAcceptPrintPos = false;
          bankIpTerminalTransfer(
            posBalanceAmountChangeCombo,
            bankIpterminals[bankCode],
            "khanbank",
            function (res) {
              setValuePosKhaanBank($tselect, res);
            }
          );
        }
      }

      if (bankCode == 320000) {
        var posBalanceAmountChangeCombo = $tselect
          .closest(".pos-bank-row")
          .find('input[name="bankAmountDtl[]"]')
          .autoNumeric("get");

        if (bankIpterminals.hasOwnProperty(bankCode)) {
          isAcceptPrintPos = false;
          bankIpTerminalTransfer(
            posBalanceAmountChangeCombo,
            bankIpterminals[bankCode],
            "xacbank",
            function (res) {
              setValuePosXacBank($tselect, res);
            }
          );
        }
      }

      if (bankCode == 400000) {
        var posBalanceAmountChangeCombo = $tselect
          .closest(".pos-bank-row")
          .find('input[name="bankAmountDtl[]"]')
          .autoNumeric("get");

        if (bankIpterminals.hasOwnProperty(bankCode)) {
          isAcceptPrintPos = false;
          bankIpTerminalTransfer(
            posBalanceAmountChangeCombo,
            bankIpterminals[bankCode],
            "tdbank",
            function (res) {
              setValuePosTdBank($tselect, res);
            }
          );
        }
      }
    }
    var $this = $(this);
    ($tbl = $this.closest(".pos-payment-area")),
      ($tblInput = $tbl.find(
        '.posKeyAmount:visible:not([readonly="readonly"], [readonly], readonly)'
      )),
      ($cellIndex = $tblInput.index(
        $this
          .closest(".pos-bank-row")
          .find(
            '.posKeyAmount:visible:not([readonly="readonly"], [readonly], readonly)'
          )
      )),
      ($focusField = $tblInput.eq($cellIndex + 1));
    if ($focusField.length) {
      $focusField.focus().select();
    } else {
      $tblInput.eq(0).focus().select();
    }
  });

  $(document.body).on(
    "change",
    'select[name="posMobileNetBankId"]',
    function () {
      var $this = $(this);
      ($tbl = $this.closest(".pos-payment-area")),
        ($tblInput = $tbl.find(
          '.posKeyAmount:visible:not([readonly="readonly"], [readonly], readonly)'
        )),
        ($cellIndex = $tblInput.index(
          $this
            .closest(".form-group")
            .find(
              '.posKeyAmount:visible:not([readonly="readonly"], [readonly], readonly)'
            )
        )),
        ($focusField = $tblInput.eq($cellIndex + 1));
      if ($focusField.length) {
        $focusField.focus().select();
      } else {
        $tblInput.eq(0).focus().select();
      }
    }
  );

  if (posCashierInsertC1 && isConfirmSaleDate !== "1") {
    posCashMoneyBill("1");
  }
  if (isConfirmSaleDate === "1" && !isBasketOnly) {
    askDateTransaction();
  }

  window.onbeforeprint = beforePrint;
  window.onafterprint = afterPrint;

  $(document.body).on("change", "#coordinate", function () {
    var coordinateVal = $(this).val();
    $("#what3words").val(bpGetWhat3words(null, null, coordinateVal));

    var response = $.ajax({
      type: "post",
      url: "api/gmap",
      data: {
        method: "geocode",
        coordinate: coordinateVal,
        googleApiKey: gmapApiKey,
      },
      dataType: "json",
      async: false,
    });
    var responseValue = response.responseJSON;

    var response = $.ajax({
      type: "post",
      url: "mdpos/getInfoLocationName",
      data: { suggestText: responseValue["results"][1] },
      dataType: "json",
      async: false,
    });
    var responseParam = response.responseJSON;
    if (responseParam.cityId != "") {
      $('select[name="cityId"]').trigger("select2-opening", [true]);
      $('select[name="cityId"]').select2("val", responseParam.cityId);

      var $districtId = $("select#districtId");
      $districtId.select2("enable");
      $districtId.removeClass("data-combo-set");
      //$('textarea[name="detailAddress"]').val(responseParam.moreAddress);
    }

    if (responseParam.districtId != "") {
      $('select[name="districtId"]').trigger("select2-opening", [true]);
      $('select[name="districtId"]').select2("val", responseParam.districtId);

      var $streetId = $("select#streetId");
      $streetId.select2("enable");
      $streetId.removeClass("data-combo-set");
    }

    if (responseParam.streetId != "") {
      $('select[name="streetId"]').trigger("select2-opening", [true]);
      $('select[name="streetId"]').select2("val", responseParam.streetId);
    }
  });

  $(document.body).on("change", 'input[name="chooseMatrix"]', function () {
    var $this = $(this);

    if ($this.attr("id") === "chooseMatrix1") {
      $("#chooseMatrix2").removeAttr("checked");
    } else {
      $("#chooseMatrix1").removeAttr("checked");
    }
    $(this).attr("checked", "checked");
  });

  $(document.body).on("keyup", 'input[name="lotteryEmail"]', function () {
    var $this = $(this);

    $this.parent().find("div").remove();
    if (
      !/^[_A-Za-z0-9-\+]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,})$/.test(
        $this.val()
      ) &&
      $this.val() != ""
    ) {
      $this
        .parent()
        .append(
          '<div style="color:red">Имэйл формат одоогоор буруу байна</div>'
        );
    }
  });

  $(document.body).on("click", 'input[name="upointAmountDtl[]"]', function () {
    $('a[href="#pos-payment-accordion-upoint"]').trigger("click");
  });

  if (posOrderTimer && isBasketOnly) {
    $(".posTimerInit").countdown({
      until: posOrderTimer,
      compact: true,
      description: "",
      format: "MS",
      onExpiry: function () {
        if (isBasketOnly) {
          posNoPayment("auto");
        }
      },
    });
    if (!$("#posTable > tbody > tr[data-item-id]").length) {
      $(".posTimerInit").countdown("pause");
    }
  }

  $(document.body).on("click", 'input[name="cardDiscountType"]', function () {
    if ($(this).val() === "+") {
      if (typeof $("#posPayAmount").attr("data-oldvalue") !== "undefined") {
        $("#posPayAmount").autoNumeric(
          "set",
          $("#posPayAmount").attr("data-oldvalue")
        );
      }
    } else {
      posBonusCardDiscountAmount();
    }
  });

  $(document).on("change", 'select[name="invoiceTypeId"]', function (e) {
    var selAttr = $(this).find("option:selected").data("notcheckqty");
    if (selAttr) {
      isConfigItemCheckEndQtyInvoice = false;
      isConfigItemCheckEndQtyMsg = 0;
    } else {
      isConfigItemCheckEndQtyInvoice = true;
      isConfigItemCheckEndQtyMsg = 1;
    }
  });

  $(document).on("change", 'input[name="empCustomerId"]', function (e) {
    posItemCombogridList("", "", $(this).val());
    $('input[name="empCustomerId"]').removeAttr("iscouponbonus");
    var $basketListBtn = $(".pos-header-basket");
    if ($basketListBtn.length && false) {
      var getsplit = $basketListBtn.attr("data-criteria").split("&");
      $basketListBtn.attr(
        "data-criteria",
        getsplit[0] + "&customerId=" + $(this).val()
      );

      $.ajax({
        type: "post",
        url: "mdpos/getBasketOrderBookCount",
        data: {
          customerIdFromSidebar: $(this).val(),
        },
        dataType: "html",
        success: function (data) {
          $basketListBtn.find(".pos-basket-count").text(data);
        },
      });
    }

    if ($(this).attr("data-row-data")) {
      var customerRow = JSON.parse($(this).attr("data-row-data"));
      $('td[data-field-name="detail-customer-customercode"]').text(
        getKeyValue(customerRow, "customercode")
      );
      $('td[data-field-name="detail-customer-customername"]').text(
        getKeyValue(customerRow, "customername")
      );
      $('td[data-field-name="detail-customer-lastname"]').text(
        getKeyValue(customerRow, "lastname")
      );
      $('td[data-field-name="detail-customer-hisnumber"]').text(
        getKeyValue(customerRow, "hisnumber")
      );
      $('td[data-field-name="detail-customer-stateregnumber"]').text(
        getKeyValue(customerRow, "stateregnumber")
      );
      $('td[data-field-name="detail-customer-phonenumber"]').text(
        getKeyValue(customerRow, "phonenumber")
      );
      $('td[data-field-name="detail-customer-receivableamount"]').text(
        getKeyValue(customerRow, "receivableamount")
      );
      $('td[data-field-name="detail-customer-payableamount"]').text(
        getKeyValue(customerRow, "payableamount")
      );
      $('td[data-field-name="detail-customer-segmentationname"]').text(
        getKeyValue(customerRow, "segmentationname")
      );
      $('td[data-field-name="detail-customer-segmentationvaliddate"]').text(
        getKeyValue(customerRow, "segmentationvaliddate")
      );
      $('td[data-field-name="detail-customer-loyaltydiscountpercent"]').text(
        getKeyValue(customerRow, "loyaltydiscountpercent")
      );
      if (posTypeCode == "3" || posTypeCode == "4") {
        $("#guestName").val(getKeyValue(customerRow, "customercode") + " - " + $.trim(getKeyValue(customerRow, "customername"))).prop("readonly", true).trigger('change');
        posRestBasketList(
          "nullmeta",
          "0",
          tempInvoiceDvId,
          "single",
          "nullmeta",
          $(this),
          "casherCheck"
        );
      }
      posDiscountCustomer(customerRow.id);
      //            if ($('#posTable > tbody > tr').length) {
      //                $(this).closest('.meta-autocomplete-wrap').append('<span style="font-size: 9px;">Та бараануудаа устгаж байж сонгох боломжтой</span>')
      //                $('input[name="empCustomerId_displayField"]').prop('disabled', true);
      //                $('input[name="empCustomerId_nameField"]').prop('disabled', true);
      //                $('input[name="empCustomerId_nameField"]').closest('.meta-autocomplete-wrap').find('button').prop('disabled', true);
      //            }
    } else {
      posDiscountCustomer("");
      $("#guestName").val("").prop("readonly", false);
    }
  });

  /*if (posTypeCode == "3") {
      $(document).on("change", "#guestName", function () {
          if ($(this).val()) {
            if (/^[A-Za-zА-Яа-яӨҮөү0-9-=#_/* ]{1,256}$/.test($(this).val()) === false) {
                PNotify.removeAll();
                new PNotify({
                  title: "Анхааруулга",
                  text: "Том жижиг үсэг, тоо, -, =, #, /, _, * тэмдэгтүүдээс бичих боломжтой",
                  type: "warning",
                  sticker: false
                });        
                $(this).val('').prop("readonly", false);
                $('input[name="empCustomerId"]').val("");
                $('input[name="empCustomerId_displayField"]').val("");
                $('input[name="empCustomerId_nameField"]').val("");
                $('input[name="empCustomerId"]').attr("data-row-data", "");         
                return true;       
            }    
          }    
      });
  }*/

  $(document).on("change", "#upointIsCost", function () {
    if (returnBillType == "") {
      if ($("#upointIsCost").is(":checked")) {
        if ($("#upointCardNumber2").val().indexOf("*") !== -1) {
          PNotify.removeAll();
          new PNotify({
            title: "Анхааруулга",
            text: "Утасны дугаараар зарцуулалах боломжгүй",
            type: "warning",
            sticker: false,
          });
          $("#upointIsCost").prop("checked", false);
          $.uniform.update($("#upointIsCost"));
          return;
        }

        var ubalance = $("#upointBalance").val(),
          uPointAmt = 0;
        var posPayAmount = Math.round(Number($("#upointPayAmount").autoNumeric("get")) / 2);

        if (posPayAmount < ubalance) {
          uPointAmt = posPayAmount;
        } else if (posPayAmount == ubalance) {
          uPointAmt = posPayAmount;
        } else if (posPayAmount > ubalance) {
          uPointAmt = ubalance;
        }
        $('input[name="upointAmountDtl[]"]').autoNumeric("set", uPointAmt).trigger("change");
        $('input[name="upointPayAmount"]').autoNumeric("set", Number($("#upointPayAmount").autoNumeric("get")) - uPointAmt);
      } else {
        $('input[name="upointAmountDtl[]"]').val("").trigger("change");
        $('input[name="upointPayAmount"]').autoNumeric("set", $('input[name="upointPayAmount"]').attr("data-old"));
      }
    }
  });

  $(document).on("click", ".pos-payment-accordion-upoint", function () {
    if (returnBillType == "") {
      var $tbody = $("#posTable > tbody"),
        $rows = $tbody.find("> tr[data-item-id]"),
        sum = 0;

      $rows.each(function () {
        var $row = $(this),
          totalPrice = Number($row.find('input[name="totalPrice[]"]').val());

        if (
          $row.find('input[data-name="isCalcUPoint"]').length &&
          $row.find('input[data-name="isCalcUPoint"]').val() == "1"
        ) {
          sum += totalPrice;
        }
      });

      $('input[name="upointPayAmount"]').autoNumeric("set", sum);
      $('input[name="upointPayAmount"]').attr("data-old", sum);
    }
  });

  /**
   * Restauran POS call
   */
  if (posTypeCode == "3") {
    // $(".pos-header-basket").hide();
    restTables();
  }

  //    getEshop(function(data){
  //            if (data.status == 'success') {
  //
  //                posDisplayReset('', false);
  //                var $tbody = $('#posTable').find('> tbody');
  //
  //                $tbody.html(data.html).promise().done(function() {
  //
  //                    posConfigVisibler($tbody);
  //                    Core.initLongInput($tbody);
  //                    Core.initDecimalPlacesInput($tbody, 3);
  //                    Core.initUniform($tbody);
  //
  //                    posGiftRowsSetDelivery($tbody);
  //
  //                    var $firstRow = $tbody.find('tr[data-item-id]:eq(0)');
  //                    $firstRow.click();
  //
  //                    posFixedHeaderTable();
  //                    posCalcTotal();
  //                });
  //
  //            } else {
  //
  //                new PNotify({
  //                    title: data.status,
  //                    text: data.message,
  //                    type: data.status,
  //                    sticker: false,
  //                    addclass: 'pnotify-center'
  //                });
  //            }
  //        }
  //    );
});

/*window.onbeforeunload = function (evt) {
  var message = 'Are you sure you want to leave?';
  if (typeof evt == 'undefined') {
    evt = window.event;
  }
  if (evt) {
    evt.returnValue = message;
  }
  return message;
}*/

function posCalcRow(rowElem) {
  var isCityTax = rowElem.find('input[name="isCityTax[]"]').val(),
    isVat = rowElem.find('input[name="isVat[]"]').val(),
    isDiscount = rowElem.find('input[name="isDiscount[]"]').val(),
    unitDiscount = Number(rowElem.find('input[name="unitDiscount[]"]').val()),
    qty = Number(pureNumber(rowElem.find("input.pos-quantity-input").val())),
    salePrice = !rowElem.find('input[name="salePriceInput[]"]').length
      ? Number(rowElem.find('input[name="salePrice[]"]').val())
      : pureNumber(rowElem.find('input[name="salePriceInput[]"]').val()),
    totalPrice = qty * salePrice,
    lineTotalPrice = totalPrice;

  if (isDiscount == "1") {
    if (unitDiscount <= salePrice) {
      salePrice = salePrice - unitDiscount;
      rowElem.find('input[name="totalDiscount[]"]').val(salePrice * qty);
    }
  }

  totalPrice = qty * salePrice;

  if (isCityTax == "1") {
    rowElem
      .find('input[name="cityTax[]"]')
      .val(setNumberToFixed(salePrice / 112 * 2));
    rowElem
      .find('input[name="lineTotalCityTax[]"]')
      .val(setNumberToFixed(totalPrice / 112 * 2));
  }

  if (isVat == "1" && isCityTax == "1") {
    rowElem
      .find('input[name="noVatPrice[]"]')
      .val(setNumberToFixed(salePrice - salePrice / 11.2));
    rowElem
      .find('input[name="lineTotalVat[]"]')
      .val(setNumberToFixed(totalPrice / 11.2));
  } else if (isVat == "1") {
    rowElem
      .find('input[name="noVatPrice[]"]')
      .val(setNumberToFixed(salePrice - salePrice / 11));
    rowElem
      .find('input[name="lineTotalVat[]"]')
      .val(setNumberToFixed(totalPrice / 11));
  }

  rowElem.find('input[name="totalPrice[]"]').val(lineTotalPrice);
  rowElem
    .find('td[data-field-name="totalPrice"]')
    .autoNumeric("set", totalPrice);

  posBonusCardDiscountAmount();
  posCalcTotal();
  if (posTypeCode == "3") {
    posCalcServiceCharge();
  }

  return;
}

function posCalcServiceCharge() {
  var $posBody = $("#posTable > tbody > tr.multi-customer-group");

  if ($posBody.length) {
    $posBody.each(function () {
      var $row = $(this);
      var $serviceChargeRow = $(
        '#posTable > tbody > tr[data-customerid="' +
        $row.attr("data-customerid") +
        '"]'
      );
      var sumCustomerTotal = 0;

      if (
        $serviceChargeRow.length &&
        $serviceChargeRow.find('input[data-name="isServiceCharge"][value="1"]')
          .length
      ) {
        $serviceChargeRow.each(function () {
          var $row2 = $(this);
          if (
            $row2.find('input[name="totalPrice[]"]').length &&
            $row2.find('input[data-name="isServiceCharge"]').length &&
            $row2.find('input[data-name="isServiceCharge"]').val() != "1"
          ) {
            var totalPrice = Number(
              $row2.find('input[name="totalPrice[]"]').val()
            );
            sumCustomerTotal += totalPrice;
          }
        });

        if (sumCustomerTotal) {
          var $serviceChargeTr = $serviceChargeRow
            .find('input[data-name="isServiceCharge"][value="1"]')
            .closest("tr");
          if ($serviceChargeTr.find('td[data-field-name="salePrice"]').find('input').length) {
            return;
          }

          // $serviceChargeTr.find('input[name="isDiscount[]"]').val("0");
          // $serviceChargeTr.find('input[name="unitDiscount[]"]').val("");
          // $serviceChargeTr.find('input[name="discountAmount[]"]').val("");
          // $serviceChargeTr.find('input[name="discountPercent[]"]').val("");
          // $serviceChargeTr.find('input[name="totalDiscount[]"]').val("");

          // $serviceChargeTr.find('input[name="unitBonusAmount[]"]').val("");
          // $serviceChargeTr.find('input[name="unitBonusPercent[]"]').val("");
          // $serviceChargeTr.find('input[name="lineTotalBonusAmount[]"]').val("");
          // $serviceChargeTr.find('input[data-name="calcBonusPercent"]').val("");

          var serviceAmt = (sumCustomerTotal * 5) / 100;
          $serviceChargeTr.find('input[name="salePrice[]"]').val(serviceAmt);
          $serviceChargeTr.find('input[name="totalPrice[]"]').val(serviceAmt);
          $serviceChargeTr.find('td[data-field-name="salePrice"]').autoNumeric("set", serviceAmt);
          $serviceChargeTr
            .find('td[data-field-name="totalPrice"]')
            .autoNumeric(
              "set",
              pureNumber(
                $serviceChargeTr.find('input[name="quantity[]"]').val()
              ) * serviceAmt
            );

          if (
            $serviceChargeTr.find('input[name="isDiscount[]"]').val() == "1" &&
            $serviceChargeTr.find('input[name="discountPercent[]"]').val()
          ) {
            discountPercent = Number(
              $serviceChargeTr.find('input[name="discountPercent[]"]').val()
            );
            var unitDiscount =
              (Number(
                $serviceChargeTr.find('input[name="discountPercent[]"]').val()
              ) /
                100) *
              serviceAmt;
            var discountAmount = serviceAmt - unitDiscount;
            serviceAmt = discountAmount;
            $serviceChargeTr
              .find('td[data-field-name="totalPrice"]')
              .autoNumeric(
                "set",
                pureNumber(
                  $serviceChargeTr.find('input[name="quantity[]"]').val()
                ) * discountAmount
              );
            $serviceChargeTr
              .find('input[name="unitDiscount[]"]')
              .val(unitDiscount);
            $serviceChargeTr
              .find('input[name="discountAmount[]"]')
              .val(discountAmount);
            $serviceChargeTr
              .find('input[name="totalDiscount[]"]')
              .val(
                pureNumber(
                  $serviceChargeTr.find('input[name="quantity[]"]').val()
                ) * unitDiscount
              );
          } else if (
            $serviceChargeTr.find('input[name="isDiscount[]"]').val() == "1" &&
            $serviceChargeTr.find('input[name="unitDiscount[]"]').val()
          ) {
            var unitDiscount = $serviceChargeTr
              .find('input[name="unitDiscount[]"]')
              .val();
            var discountAmount = serviceAmt - unitDiscount;
            serviceAmt = discountAmount;
            $serviceChargeTr
              .find('td[data-field-name="totalPrice"]')
              .autoNumeric(
                "set",
                pureNumber(
                  $serviceChargeTr.find('input[name="quantity[]"]').val()
                ) * discountAmount
              );
            $serviceChargeTr
              .find('input[name="unitDiscount[]"]')
              .val(unitDiscount);
            $serviceChargeTr
              .find('input[name="discountAmount[]"]')
              .val(discountAmount);
            $serviceChargeTr
              .find('input[name="totalDiscount[]"]')
              .val(
                pureNumber(
                  $serviceChargeTr.find('input[name="quantity[]"]').val()
                ) * unitDiscount
              );
          }

          if ($serviceChargeTr.find('input[name="isCityTax[]"]').val() == "1") {
            $serviceChargeTr
              .find('input[name="cityTax[]"]')
              .val(setNumberToFixed(serviceAmt / 111));
            $serviceChargeTr
              .find('input[name="lineTotalCityTax[]"]')
              .val(setNumberToFixed(serviceAmt / 111));
          }

          if (
            $serviceChargeTr.find('input[name="isVat[]"]').val() == "1" &&
            $serviceChargeTr.find('input[name="isCityTax[]"]').val() == "1"
          ) {
            $serviceChargeTr
              .find('input[name="noVatPrice[]"]')
              .val(setNumberToFixed(serviceAmt - serviceAmt / 11.1));
            $serviceChargeTr
              .find('input[name="lineTotalVat[]"]')
              .val(
                setNumberToFixed(
                  (serviceAmt *
                    pureNumber(
                      $serviceChargeTr.find('input[name="quantity[]"]').val()
                    )) /
                  11.1
                )
              );
          } else if (
            $serviceChargeTr.find('input[name="isVat[]"]').val() == "1"
          ) {
            $serviceChargeTr
              .find('input[name="noVatPrice[]"]')
              .val(setNumberToFixed(serviceAmt - serviceAmt / 11));
            $serviceChargeTr
              .find('input[name="lineTotalVat[]"]')
              .val(
                setNumberToFixed(
                  (serviceAmt *
                    pureNumber(
                      $serviceChargeTr.find('input[name="quantity[]"]').val()
                    )) /
                  11
                )
              );
          }

          posCalcTotal();
        }
      }
    });
  }
}

function posCalcTotal() {
  var $tbody = $("#posTable > tbody"),
    $rows = $tbody.find("> tr[data-item-id]"),
    $giftRows = $tbody.find("tr[data-calc-price]"),
    qtySum = 0,
    sum = 0,
    vatTotal = 0,
    totalCityTax = 0,
    discountSum = 0,
    receivableFromPerson = 0,
    receivableSum = 0;

  if (
    posTypeCode == "3" &&
    $(".seperate-calculation").is(":checked") &&
    $("#posTable").find("tr.pos-selected-seperate-row").length
  ) {
    $rows = $tbody.find("> tr[data-item-id].pos-selected-seperate-row");
  }

  $rows.each(function () {
    var $row = $(this),
      totalPrice = Number($row.find('input[name="totalPrice[]"]').val()),
      qty = pureNumber($row.find("input.pos-quantity-input").val()),
      isVat = $row.find('input[name="isVat[]"]').val(),
      isCityTax = $row.find('input[name="isCityTax[]"]').val(),
      isDiscount = $row.find('input[name="isDiscount[]"]').val();

    if (isVat == "1") {
      vatTotal += Number($row.find('input[name="lineTotalVat[]"]').val());
    }

    if (isCityTax == "1") {
      totalCityTax += Number(
        $row.find('input[name="lineTotalCityTax[]"]').val()
      );
    }

    if (isDiscount == "1") {
      var unitDiscount = Number(
        $row.find('input[name="unitDiscount[]"]').val()
      );
      discountSum += unitDiscount * qty;
    }

    if (isConfigPaymentUnitReceivable && isReceiptNumber) {
      var unitReceivable = Number(
        $row.find('input[name="unitReceivable[]"]').val()
      ),
        maxPrice = Number($row.find('input[name="maxPrice[]"]').val()),
        salePrice = !$row.find('input[name="salePriceInput[]"]').length
          ? Number($row.find('input[name="salePrice[]"]').val())
          : Number(
            $row.find('input[name="salePriceInput[]"]').autoNumeric("get")
          );

      if (salePrice > unitReceivable) {
        /*if (salePrice > maxPrice) {
                
              receivableFromPerson += (salePrice - unitReceivable) * qty;
              receivableSum        += unitReceivable * qty;
                
            } else if (salePrice <= maxPrice) {
                
              receivableFromPerson += (salePrice - unitReceivable) * qty;
              receivableSum        += unitReceivable * qty;
            }*/
        receivableFromPerson += (salePrice - unitReceivable) * qty;
        receivableSum += unitReceivable * qty;
      } else if (salePrice == unitReceivable) {
        receivableFromPerson += 0;
        receivableSum += unitReceivable * qty;
      }
    }

    sum += totalPrice;
    qtySum += qty;
  });

  /*if ($giftRows.length) {
    $giftRows.each(function () {
      var $giftRow = $(this),
        $parentItemRow = $giftRow
          .closest('tr[data-item-gift-row="true"]')
          .prev("tr[data-item-id]:eq(0)"),
        giftQty = Number(
          pureNumber($parentItemRow.find("input.pos-quantity-input").val())
        ),
        giftPrice = Number($giftRow.attr("data-calc-price")) * giftQty;

      sum += giftPrice;
      qtySum += giftQty;
    });
  }*/

  $("td.pos-amount-total").autoNumeric("set", sum);
  $("td.pos-amount-vat").autoNumeric("set", vatTotal);
  $("td.pos-amount-citytax").autoNumeric("set", totalCityTax);

  $("td.pos-total-qty").autoNumeric("set", qtySum);

  if (isConfigPaymentUnitReceivable && isReceiptNumber) {
    $("td.pos-amount-receivable").autoNumeric("set", receivableSum);
    $("td.pos-amount-receivable-from-person").autoNumeric(
      "set",
      receivableFromPerson
    );
  } else {
    $(
      "td.pos-amount-receivable, td.pos-amount-receivable-from-person"
    ).autoNumeric("set", 0);
  }

  if (discountSum > 0 || discountSum < 0) {
    var payAmount = sum - discountSum;

    $("td.pos-amount-paid").autoNumeric("set", payAmount);
    $("td.pos-amount-discount").autoNumeric("set", discountSum);

    $("#posPayAmount").autoNumeric("set", payAmount);
  } else {
    $("td.pos-amount-paid").autoNumeric("set", sum);
    $("td.pos-amount-discount").autoNumeric("set", discountSum);

    $("#posPayAmount").autoNumeric("set", sum);
  }

  //    if ($('#posTable > tbody > tr').length) {
  //        $('input[name="empCustomerId_nameField"]').closest('.meta-autocomplete-wrap').find('> span').remove().append('<span style="font-size: 9px;">Та бараануудаа устгаж байж сонгох боломжтой</span>')
  //        $('input[name="empCustomerId_displayField"]').prop('disabled', true);
  //        $('input[name="empCustomerId_nameField"]').prop('disabled', true);
  //        $('input[name="empCustomerId_nameField"]').closest('.meta-autocomplete-wrap').find('button').prop('disabled', true);
  //    } else {
  //        $('input[name="empCustomerId_nameField"]').closest('.meta-autocomplete-wrap').find('> span').remove().append('<span style="font-size: 9px;">Та бараануудаа устгаж байж сонгох боломжтой</span>')
  //        $('input[name="empCustomerId_displayField"]').prop('disabled', false);
  //        $('input[name="empCustomerId_nameField"]').prop('disabled', false);
  //        $('input[name="empCustomerId_nameField"]').closest('.meta-autocomplete-wrap').find('button').prop('disabled', false);
  //    }

  return;
}

function posRowRemove(row) {
  var $nextRow = row.nextAll("tr[data-item-id]:visible:eq(0)"),
    $prevRow = row.prevAll("tr[data-item-id]:visible:eq(0)"),
    $giftRow = row.next('tr[data-item-gift-row="true"]:eq(0)'),
    $tbody = row.closest("tbody");

  //    if ($('.pos-card-layout').length) {
  //        $('.pos-card-layout').find('.pos-cardmiddle').find('div[data-itemid="'+row.attr('data-item-id')+'"]').find('.basket-button').removeClass('d-none');
  //        $('.pos-card-layout').find('.pos-cardmiddle').find('div[data-itemid="'+row.attr('data-item-id')+'"]').find('.basket-qty-button').attr('style', 'display:none !important');
  //        $('.pos-card-layout').find('.pos-cardmiddle').find('div[data-itemid="'+row.attr('data-item-id')+'"]').find('.basket-qty-button').find('input').autoNumeric('set', 1);
  //    }

  if (posTypeCode == "3" || posTypeCode == "4") {
    if (typeof row.find('.pos-quantity-input').attr('readonly') !== 'undefined') {
      PNotify.removeAll();
      new PNotify({
        title: "Warning",
        text: "Устгах үйлдэл ажиллах боломжгүй байна",
        type: "warning",
        sticker: false,
      });
      return;
    }
    if (!row.find('input[data-name="isSavedOrder"]').length) {
      row.remove();

      if ($tbody.find("> tr[data-item-id]").length == 0) {
        if ($('select[name="invoiceTypeId"]').length) {
          $('select[name="invoiceTypeId"]').select2("enable");
        }
        if (typeof posElectronTalonWindow !== "undefined") {
          console.log("no item");
        } else {
          posDisplayReset("", false);
        }
      } else {
        posCalcTotal();
      }
      return;
    }

    var $dialogName = "dialog-talon-protect-removeorder";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialogP = $("#" + $dialogName);

    $dialogP
      .empty()
      .append(
        '<form method="post" autocomplete="off" id="talonListPassForm"><input type="password" name="talonListPass" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>'
      );
    $dialogP.dialog({
      cache: false,
      resizable: true,
      bgiframe: true,
      autoOpen: false,
      title: "Нууц үг оруулах",
      width: 400,
      height: "auto",
      modal: true,
      open: function () {
        $(this).keypress(function (e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            $(this)
              .parent()
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
          }
        });
        $('input[name="talonListPass"]').on("keydown", function (e) {
          var keyCode = e.keyCode ? e.keyCode : e.which;
          if (keyCode == 13) {
            $(this)
              .closest(".ui-dialog")
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
          }
        });
      },
      close: function () {
        $dialogP.empty().dialog("destroy").remove();
      },
      buttons: [{
        text: plang.get("insert_btn"),
        class: "btn btn-sm green-meadow",
        click: function () {
          PNotify.removeAll();
          var $form = $("#talonListPassForm");

          $form.validate({ errorPlacement: function () { } });

          if ($form.valid()) {
            $.ajax({
              type: "post",
              url: "mdpos/checkTalonListPass",
              data: $form.serialize(),
              dataType: "json",
              beforeSend: function () {
                Core.blockUI({
                  message: "Loading...",
                  boxed: true,
                });
              },
              success: function (dataSub) {
                var dataResponse = dataSub;
                if (dataResponse.status != "success") {
                  $.ajax({
                    type: "post",
                    url: "api/callDataview",
                    async: false,
                    data: {
                      dataviewId: "16237213033721",
                      criteriaData: {
                        pincode: [
                          {
                            operator: "=",
                            operand: $form
                              .find('input[name="talonListPass"]')
                              .val(),
                          },
                        ],
                      },
                    },
                    dataType: "json",
                    beforeSend: function () {
                      Core.blockUI({
                        message: "Loading...",
                        boxed: true,
                      });
                    },
                    success: function (dataSub) {
                      if (
                        dataSub.status == "success" &&
                        dataSub.result.length
                      ) {
                        dataResponse.status = "success";
                        row.find('input[name="employeeId[]"]').val(dataSub.result[0]["employeeid"]);
                      }
                      Core.unblockUI();
                    },
                  });
                }
                if (dataResponse.status == "success") {
                  $dialogP.dialog("close");

                  $dialogP.empty().append(
                    '<form method="post" autocomplete="off" id="talonListDescriptionForm"><input type="password" autocomplete="off" style="display:none" /><textarea name="talonListDescriptionForm" required style="height: 46px;margin-top: 4px;width: 100%;font-size: 15px"></textarea></form>'
                  );
                  $dialogP.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: "Буцаалтын тайлбар оруулах",
                    width: 350,
                    height: "auto",
                    modal: true,
                    open: function () {
                      setTimeout(function () {
                        $('textarea[name="talonListDescriptionForm"]').focus().select();
                      }, 100);
                      $(this).keypress(function (e) {
                        if (e.keyCode == $.ui.keyCode.ENTER) {
                          $(this)
                            .parent()
                            .find(".ui-dialog-buttonpane button:first")
                            .trigger("click");
                        }
                      });
                      $('textarea[name="talonListDescriptionForm"]').on("keydown", function (e) {
                        var keyCode = e.keyCode ? e.keyCode : e.which;
                        if (keyCode == 13) {
                          $(this).closest(".ui-dialog").find(".ui-dialog-buttonpane button:first").trigger("click");
                        }
                      });
                    },
                    close: function () {
                      $dialogP.empty().dialog("destroy").remove();
                    },
                    buttons: [{
                      text: plang.get("insert_btn"),
                      class: "btn btn-sm green-meadow",
                      click: function () {
                        PNotify.removeAll();
                        var $form = $("#talonListDescriptionForm");

                        $form.validate({ errorPlacement: function () { } });

                        if ($form.valid()) {
                          row.find("input.pos-quantity-input").autoNumeric("set", 0);
                          row.find("input.pos-quantity-input").attr("data-oldvalue", 0);
                          row.find('input[name="returnDescription[]"]').val($form.find('textarea').val());
                          posCalcRow(row);
                          $dialogP.dialog("close");
                        }
                      }
                    },
                    {
                      text: plang.get("close_btn"),
                      class: "btn btn-sm blue-madison",
                      click: function () {
                        row.find('input[name="employeeId[]"]').val('');
                        $dialogP.dialog("close");
                      }
                    }]
                  });
                  $dialogP.dialog("open");

                } else {
                  new PNotify({
                    title: dataSub.status,
                    text: dataSub.message,
                    type: dataSub.status,
                    sticker: false,
                  });
                }
                Core.unblockUI();
              },
            });
          }
        }
      },
      {
        text: plang.get("close_btn"),
        class: "btn btn-sm blue-madison",
        click: function () {
          $dialogP.dialog("close");
        }
      }],
    });
    $dialogP.dialog("open");
  } else if (
    row.find('input[name="salesorderdetailid[]"]').length &&
    row.find('input[name="salesorderdetailid[]"]').val()
  ) {
    var $dialogName = "dialog-talon-protect-removeorder";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialogP = $("#" + $dialogName);

    $dialogP
      .empty()
      .append(
        '<form method="post" autocomplete="off" id="talonListPassForm"><input type="password" name="talonListPass" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>'
      );
    $dialogP.dialog({
      cache: false,
      resizable: true,
      bgiframe: true,
      autoOpen: false,
      title: "Нууц үг оруулах",
      width: 400,
      height: "auto",
      modal: true,
      open: function () {
        $(this).keypress(function (e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            $(this)
              .parent()
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
          }
        });
        $('input[name="talonListPass"]').on("keydown", function (e) {
          var keyCode = e.keyCode ? e.keyCode : e.which;
          if (keyCode == 13) {
            $(this)
              .closest(".ui-dialog")
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
          }
        });
      },
      close: function () {
        $dialogP.empty().dialog("destroy").remove();
      },
      buttons: [
        {
          text: plang.get("insert_btn"),
          class: "btn btn-sm green-meadow",
          click: function () {
            PNotify.removeAll();
            var $form = $("#talonListPassForm");

            $form.validate({ errorPlacement: function () { } });

            if ($form.valid()) {
              $.ajax({
                type: "post",
                url: "mdpos/checkTalonListPass",
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function () {
                  Core.blockUI({
                    message: "Loading...",
                    boxed: true,
                  });
                },
                success: function (dataSub) {
                  if (dataSub.status == "success") {
                    $dialogP.dialog("close");
                    row.remove();

                    if ($tbody.find("> tr[data-item-id]").length == 0) {
                      if ($('select[name="invoiceTypeId"]').length) {
                        $('select[name="invoiceTypeId"]').select2("enable");
                      }
                      if (typeof posElectronTalonWindow !== "undefined") {
                        console.log("no item");
                      } else {
                        posDisplayReset("", false);
                      }
                    } else {
                      posCalcTotal();
                    }
                    $.ajax({
                      type: "post",
                      url: "api/callProcess",
                      data: {
                        processCode: "DELETE_SDM_SALES_ORDER_ITEM_DTL_DV_005",
                        paramData: {
                          id: row
                            .find('input[name="salesorderdetailid[]"]')
                            .val(),
                          cashRegisterId: row
                            .find('input[name="editPriceEmployeeId[]"]')
                            .val(),
                        },
                      },
                      dataType: "json",
                      async: false,
                      success: function (data) {
                        Core.unblockUI();
                      },
                    });
                  } else {
                    new PNotify({
                      title: dataSub.status,
                      text: dataSub.message,
                      type: dataSub.status,
                      sticker: false,
                    });
                  }
                  Core.unblockUI();
                },
              });
            }
          },
        },
        {
          text: plang.get("close_btn"),
          class: "btn btn-sm blue-madison",
          click: function () {
            $dialogP.dialog("close");
          },
        },
      ],
    });
    $dialogP.dialog("open");
  } else {
    if ($nextRow.length) {
      $nextRow.click();
      $nextRow.find("input.pos-quantity-input").focus().select();
    } else if ($prevRow.length) {
      $prevRow.click();
      $prevRow.find("input.pos-quantity-input").focus().select();
    }

    if (isReturnValueZero == "1") {
      row.find("input.pos-quantity-input").focus().select();
      row.find("input.pos-quantity-input").autoNumeric("set", 0);
      row.find("input.pos-quantity-input").attr("data-oldvalue", 0);
      row.find("input.pos-quantity-input").addClass("ignoreZeroValue");
      posCalcTotal();
      return;
    }

    if ($giftRow.length) {
      $giftRow.remove();
    }

    if (row.attr("data-matrix-row") !== "undefined") {
      var getMatId = row.data("matrix-row");
      row.remove();

      var $getMatRow = $tbody.find('tr[data-matrix-row="' + getMatId + '"]');

      if ($getMatRow.length) {
        $giftRow = $getMatRow.next('tr[data-item-gift-row="true"]:eq(0)');

        if ($giftRow.length) {
          $giftRow.remove();
        }

        $getMatRow.find('input[name="giftJson[]"]').val("");
        posCalcRowDiscountPercent("0", $getMatRow);
        $getMatRow.find("td:eq(0)").html("");
      }
    } else {
      row.remove();
    }

    if ($tbody.find("> tr[data-item-id]").length == 0) {
      if ($('select[name="invoiceTypeId"]').length) {
        $('select[name="invoiceTypeId"]').select2("enable");
      }
      if (typeof posElectronTalonWindow !== "undefined") {
        console.log("no item");
      } else {
        posDisplayReset("", false);
      }
    } else {
      posCalcTotal();
    }
  }

  return;
}

function posRowTempRemove(row) {
  /*var $nextRow = row.nextAll('tr[data-item-id]:visible:eq(0)'), 
      $prevRow = row.prevAll('tr[data-item-id]:visible:eq(0)');
    
    if ($nextRow.length) {
      $nextRow.click();
      $nextRow.find('input.pos-quantity-input').focus().select();
    } else if ($prevRow.length) {
      $prevRow.click();
      $prevRow.find('input.pos-quantity-input').focus().select();
    }*/

  var $qty = row.find("input.pos-quantity-input");

  if (row.hasClass("row-removed")) {
    row.removeClass("row-removed");
    $qty.autoNumeric("set", row.attr("data-old-qty"));
  } else {
    row.addClass("row-removed");
    row.attr("data-old-qty", $qty.autoNumeric("get"));

    $qty.autoNumeric("set", 0);
  }

  posCalcRow(row);

  return;
}

function posPayment() {
  if ($(".blockOverlay").length) {
    return;
  }

  PNotify.removeAll();

  var $posBody = $("#posTable > tbody");

  if (
    $("body").find("#dialog-pos-rest-tables").length > 0 &&
    $("body").find("#dialog-pos-rest-tables").is(":visible")
  ) {
    return;
  }

  // Check item list
  if ($posBody.find("> tr[data-item-id]").length == 0) {
    if (posTypeCode == 3 || posTypeCode == 4) {
      posRestBasketListPayment(
        "nullmeta",
        "0",
        '',
        "single",
        "nullmeta",
        '',
        "casherCheck"
      );
      return;
    }
    new PNotify({
      title: "Warning",
      text: plang.get("POS_0022"),
      type: "warning",
      sticker: false,
    });

    return;
  }

  if ($(".multipleLockerId").length) {
    if (Number($(".pos-total-qty").text()) < $(".multipleLockerId").length) {
      new PNotify({
        title: "Анхааруулга",
        text: "Барааны тоо локерийн тооноос бага байж болохгүй!",
        type: "warning",
        sticker: false,
      });
      return;
    }
  }

  if (
    $("#posUpointAmt").length &&
    $('input[name="posUpointAmt"]').val() &&
    returnBillType == ""
  ) {
    var posPayAmount = Number($("#posPayAmount").autoNumeric("get")) / 2;
    if (posPayAmount < Number($('input[name="posUpointAmt"]').val())) {
      PNotify.removeAll();
      new PNotify({
        title: "Info",
        text: "Upoint дүн нийт дүнгийн 50%-аас ИХ байна!",
        type: "info",
        sticker: false,
      });
      return;
    }
  }

  if ($("#posUpointAmt").length && returnBillType == "typeReduce") {
    var $posBody = $("#posTable > tbody > tr"),
      uamt = 0;
    $posBody.each(function () {
      if (
        $(this).hasClass("row-removed") &&
        $(this).find('input[data-name="isCalcUPoint"]').length &&
        $(this).find('input[data-name="isCalcUPoint"]').val() == "1"
      )
        uamt += Number(
          $(this).find('input[data-name="upointTotalPrice"]').val()
        );
    });
    $('input[name="upointPayAmount"]').autoNumeric("set", uamt);
  }

  multiCustomer(function (res) {
    if (res) return;

    var $posBody = $("#posTable > tbody");
    try {
      var $scanItemCode = $("#scanItemCode");
      $scanItemCode.combogrid("hidePanel");
      $scanItemCode.combogrid("clear", "");
      $scanItemCode.val("");
    } catch (e) { }

    if (
      $("body").find("#dialog-pos-payment").length > 0 &&
      $("body").find("#dialog-pos-payment").is(":visible")
    ) {
      var socialAmt = Number(
        $('input[name="posSocialpayAmt"]').autoNumeric("get")
      );
      var upointAmt = Number($('input[name="posUpointAmt"]').val());
      var upointInAmt = $('input[name="intamt[]"]').val();
      var qpayAmt = Number($("#posqpayAmt").autoNumeric("get"));

      var $posBody = $("#posTable > tbody"),
        checkSumAmt = 0;
      $posBody.find("> tr[data-item-id]").each(function () {
        checkSumAmt += Number($(this).find('input[name="totalPrice[]"]').val());
      });

      if (
        returnBillType != "" &&
        $('input[name="upointTransactionIdDtl[]"]').val()
      ) {
        if ($("#upointBalance").val() == "") {
          PNotify.removeAll();
          new PNotify({
            title: "Info",
            text: "Upoint картны ПИН код оруулна уу!",
            type: "info",
            sticker: false,
          });
          return;
        }

        var $posBody = $("#posTable > tbody > tr"),
          totalUamt = 0;
        $posBody.each(function () {
          if (
            $(this).find('input[data-name="isCalcUPoint"]').length &&
            $(this).find('input[data-name="isCalcUPoint"]').val() == "1"
          )
            totalUamt += Number(
              $(this).find('input[data-name="upointTotalPrice"]').val()
            );
        });
        var upointAmt2 =
          returnBillType == "typeReduce"
            ? Number($('input[name="upointPayAmount"]').autoNumeric("get"))
            : totalUamt;

        if ($("#posUpointReturnResult").val() != "") {
          if (isConfirmSaleDate === "1" && !isBasketOnly) {
            askDateTransaction();
          } else {
            posBillPrint();
          }
          return;
        } else {
          $.ajax({
            type: "post",
            url: "mdpos/upointCancel",
            data: {
              amount: upointAmt,
              amount2: upointAmt2,
              totalAmount: totalUamt,
              upointIntAmt: upointInAmt,
              bankAmount: $("#posBankAmount").val(),
              paymentData: $("#pos-payment-form").serialize(),
              transactionId: $('input[name="upointTransactionIdDtl[]"]').val(),
              returnBillType: returnBillType,
            },
            dataType: "json",
            beforeSend: function () { },
            success: function (data) {
              PNotify.removeAll();
              if (data.status == "success") {
                $("#posUpointReturnResult").val(JSON.stringify(data.data));
                if (isConfirmSaleDate === "1" && !isBasketOnly) {
                  askDateTransaction();
                } else {
                  posBillPrint();
                }
                return;
              } else {
                new PNotify({
                  title: "Warning",
                  text: data.message,
                  type: "warning",
                  sticker: false,
                  addclass: "pnotify-center",
                });
                return;
              }
            },
          });
        }
      } else if (socialAmt > 0) {
        if (returnBillType == "typeCancel" || returnBillType == "typeChange") {
          $.ajax({
            type: "post",
            url: "mdpos/socialPayCancel",
            data: {
              amount: Number(
                $('input[name="posSocialpayAmt"]').autoNumeric("get")
              ),
              id: $('input[name="posSocialpayUID"]').val(),
            },
            dataType: "json",
            beforeSend: function () { },
            success: function (data) {
              PNotify.removeAll();
              if (data.status == "success") {
                if (isConfirmSaleDate === "1" && !isBasketOnly) {
                  askDateTransaction();
                } else {
                  posBillPrint();
                }
                return;
              } else {
                new PNotify({
                  title: "Warning",
                  text: data.message,
                  type: "warning",
                  sticker: false,
                  addclass: "pnotify-center",
                });
                return;
              }
            },
          });
        } else {
          if ($('input[name="posSocialpayUID"]').val() == "") {
            posSaleSocialPay(
              Number($('input[name="posSocialpayAmt"]').autoNumeric("get")),
              $('input[name="posSocialpayPhoneNumber"]').val()
            );
            return;
          }

          $.ajax({
            type: "post",
            url: "mdpos/socialPayCheckInvoice",
            data: {
              amount: socialAmt,
              id: $('input[name="posSocialpayUID"]').val(),
            },
            dataType: "json",
            beforeSend: function () { },
            success: function (data) {
              PNotify.removeAll();
              if (data.status == "success") {
                if (data.message.resp_code == "00") {
                  $('input[name="posSocialpayApprovalCode"]').val(
                    data.message.approval_code
                  );
                  $('input[name="posSocialpayCardNumber"]').val(
                    data.message.card_number
                  );
                  $('input[name="posSocialpayTerminal"]').val(
                    data.message.terminal
                  );

                  if (isConfirmSaleDate === "1" && !isBasketOnly) {
                    askDateTransaction();
                  } else {
                    posBillPrint();
                  }
                } else {
                  new PNotify({
                    title: "Warning",
                    text: data.message.resp_desc,
                    type: "warning",
                    sticker: false,
                    addclass: "pnotify-center",
                  });
                }
                return;
              } else {
                new PNotify({
                  title: "Warning",
                  text: data.message,
                  type: "warning",
                  sticker: false,
                  addclass: "pnotify-center",
                });
                return;
              }
            },
          });
        }
      } else if (qpayAmt > 0) {
        $.ajax({
          type: "post",
          url: "mdpos/qpayCheckQrCode",
          data: { uuid: $('input[name="qpay_traceNo"]').val() },
          dataType: "json",
          success: function (dataQrCode) {
            Core.unblockUI();
            if (dataQrCode.status == "success") {
              if (isConfirmSaleDate === "1" && !isBasketOnly) {
                askDateTransaction();
              } else {
                posBillPrint();
              }
            } else {
              new PNotify({
                title: "Warning",
                text: 'QPAY төлбөр төлөлт хийгдээгүй байна!',
                type: "warning",
                sticker: false,
                addclass: "pnotify-center"
              });
              return;
            }
          }
        });
      } else {
        if (isConfirmSaleDate === "1" && !isBasketOnly) {
          askDateTransaction();
        } else {
          posBillPrint();
        }
        return;
      }
    }

    isAcceptPrintPos = true;

    // Check salesperson
    if (
      isConfigSalesPerson &&
      $posBody.find(
        'input.lookup-code-autocomplete[data-field-name="employeeId"]:visible'
      ).length
    ) {
      var $itemRows = $posBody.find("> tr[data-item-id]:visible"),
        salesPersonResult = true;

      $itemRows.each(function () {
        var $itemRow = $(this),
          $employeeId = $itemRow.find('input[data-path="employeeId"]'),
          $employeeCode = $itemRow.find(
            'input.lookup-code-autocomplete[data-field-name="employeeId"]:not([readonly])'
          ),
          $employeeName = $itemRow.find(
            'input.lookup-name-autocomplete[data-field-name="employeeId"]:not([readonly])'
          );

        if (
          $employeeCode.length &&
          ($employeeId.val() == "" ||
            $employeeCode.val() == "" ||
            $employeeName.val() == "")
        ) {
          salesPersonResult = false;
          $employeeCode.addClass("error");
          $employeeName.addClass("error");
        } else {
          $employeeCode.removeClass("error");
          $employeeName.removeClass("error");
        }
      });

      if (salesPersonResult == false) {
        new PNotify({
          title: "Warning",
          text: plang.get("POS_0023"),
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
        return;
      }
    }

    // Create payment dialog
    var $dialogName = "dialog-pos-payment";

    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
      var $dialog = $("#" + $dialogName);

      var $isDeliveryRows = $posBody
        .find('input[name="isDelivery[]"]')
        .filter(function () {
          return $(this).val() == "1";
        }),
        $isServiceGiftDelivery = $posBody.find(
          "input.isGiftDelivery:checked, input.isServiceDelivery"
        ).length,
        $isCashVoucher = $posBody.find('[data-coupontypeid="6"]').length;

      var paymentData = {
        amount: $(".pos-amount-paid").autoNumeric("get"),
        vat: $(".pos-amount-vat").autoNumeric("get"),
        isDelivery:
          Number($isDeliveryRows.length) + Number($isServiceGiftDelivery),
        invoiceId: $("#invoiceId").val(),
        invoiceRow: $("#invoiceJsonStr").val(),
        invoiceBasketTypeId: $("#invoiceBasketTypeId").val(),
        isCashVoucher: $isCashVoucher,
      };

      if ($("#posEshopQrcode").length && $("#posEshopQrcode").val() != "") {
        paymentData.invoiceId = "test";
        paymentData.invoiceRow = JSON.stringify({
          id: "test",
          typeid: "12",
          qrcode: $("#posEshopQrcode").val(),
        });
      }

      if (isReceiptNumber) {
        paymentData["emdAmount"] = $(".pos-amount-receivable").autoNumeric(
          "get"
        );
        paymentData["emdInsuredAmount"] = $(
          ".pos-amount-receivable-from-person"
        ).autoNumeric("get");
      }

      $.ajax({
        type: "post",
        url: "mdpos/payment",
        data: paymentData,
        dataType: "json",
        beforeSend: function () {
          Core.blockUI({
            message: "Loading...",
            boxed: true,
          });
        },
        success: function (data) {
          $dialog.empty().append(data.html);
          var dialogWidthPay = $("#lockerId").length ? "800" : "1030";

          $dialog
            .dialog({
              cache: false,
              resizable: false,
              bgiframe: true,
              autoOpen: false,
              title: data.title,
              width: dialogWidthPay,
              minWidth: dialogWidthPay,
              height: "auto",
              modal: true,
              dialogClass: "pos-payment-dialog",
              closeOnEscape: isCloseOnEscape,
              position: { my: "top", at: "top" },
              open: function () {
                // disableScrolling();

                setTimeout(function () {
                  Core.initClean($dialog);
                }, 2);
              },
              close: function () {
                // enableScrolling();
                $dialog.empty().dialog("destroy").remove();
              },
            })
            .dialogExtend({
              closable: true,
              maximizable: true,
              minimizable: true,
              collapsable: true,
              dblclick: "maximize",
              minimizeLocation: "left",
              icons: {
                close: "ui-icon-circle-close",
                maximize: "ui-icon-extlink",
                minimize: "ui-icon-minus",
                collapse: "ui-icon-triangle-1-s",
                restore: "ui-icon-newwin",
              },
            });
          $dialog.dialog("open");

          $dialog.bind("dialogextendminimize", function () {
            $dialog
              .closest(".ui-dialog")
              .nextAll(".ui-widget-overlay:first")
              .addClass("display-none");
          });
          $dialog.bind("dialogextendmaximize", function () {
            $dialog
              .closest(".ui-dialog")
              .nextAll(".ui-widget-overlay:first")
              .removeClass("display-none");
          });
          $dialog.bind("dialogextendrestore", function () {
            $dialog
              .closest(".ui-dialog")
              .nextAll(".ui-widget-overlay:first")
              .removeClass("display-none");
          });

          if (isPosSejim != '') {
            $.ajax({
              type: "post",
              url: "api/callDataview",
              data: {
                dataviewId: "1699602708629179"
              },
              dataType: "json",
              beforeSend: function () {
              },
              success: function (dataSub) {
                if (dataSub.status == "success" && dataSub.result.length) {
                  var sejimAge = '<option value="">- Сонгох -</option>';
                  for (var i = 0; i < dataSub.result.length; i++) {
                    sejimAge += '<option value="' + dataSub.result[i].agerang + '">' + dataSub.result[i].agerang + '</option>';
                  }
                  $('#sejimAgeRange').html(sejimAge);
                }
              }
            });

            $.ajax({
              type: "post",
              url: "api/callDataview",
              data: {
                dataviewId: "1448432578544"
              },
              dataType: "json",
              beforeSend: function () {
              },
              success: function (dataSub) {
                if (dataSub.status == "success" && dataSub.result.length) {
                  var sejimGender = '<option value="">- Сонгох -</option>';
                  for (var i = 0; i < dataSub.result.length; i++) {
                    sejimGender += '<option value="' + dataSub.result[i].id + '">' + dataSub.result[i].name + '</option>';
                  }
                  $('#sejimGenderId').html(sejimGender);
                }
              }
            });
          }

          Core.unblockUI();
        },
        error: function () {
          alert("Error");
        },
      }).done(function () {
        if (isReceiptNumber) {
          $("#posCashAmount").trigger("change");
        }
        setTimeout(function () {
          if (POS_FILL_CASH_AMOUNT_PAYMENT) {
            var posPayAmount = Number($("#posPayAmount").autoNumeric("get"));
            $("#posCashAmount").dblclick();
          }
        }, 800);
        if (
          $('input[name="serviceCustomerId"]').length &&
          $('input[name="empCustomerId"]').length &&
          $('input[name="empCustomerId"]').val()
        ) {
          $('input[name="serviceCustomerId"]').val(
            $('input[name="empCustomerId"]').val()
          );
          $('input[name="serviceCustomerId_displayField"]').val(
            $('input[name="empCustomerId_displayField"]').val()
          );
          $('input[name="serviceCustomerId_nameField"]').val(
            $('input[name="empCustomerId_nameField"]').val()
          );
          $('input[name="serviceCustomerId"]')
            .attr(
              "data-row-data",
              $('input[name="empCustomerId"]').attr("data-row-data")
            )
            .trigger("change");

          posCardNumberPinCode("", $('input[name="empCustomerId"]').val());
        }

        if (
          $('select[name="recievableCustomerId"]').length &&
          $('input[name="empCustomerId"]').length &&
          $('input[name="empCustomerId"]').val()
        ) {
          $('select[name="recievableCustomerId"]').attr(
            "data-criteria",
            "customerId=" + $('input[name="empCustomerId"]').val()
          );
        }
      });
    } else {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });

      var $dialog = $("#" + $dialogName);

      $dialog
        .dialog({
          cache: false,
          resizable: false,
          bgiframe: true,
          autoOpen: false,
          title: plang.get("POS_0024"),
          width: 1000,
          minWidth: 1000,
          height: "auto",
          modal: true,
          dialogClass: "pos-payment-dialog",
          closeOnEscape: isCloseOnEscape,
          position: { my: "top", at: "top" },
          open: function () {
            disableScrolling();

            if (
              returnBillType == "typeCancel" ||
              returnBillType == "typeChange"
            ) {
              $dialog
                .find(".select2-container")
                .addClass("select2-container-disabled");
              //                    if (Number($('input[name="posSocialpayAmt"]').autoNumeric('get')) > 0) {
              //                        $('input[name="posSocialpayPhoneNumber"]').prop('readonly', false).closest('.form-group').find('button.btn').prop('disabled', false);
              //                    }
            }
          },
          close: function () {
            enableScrolling();
          },
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
        });
      $dialog.dialog("open");

      $dialog.bind("dialogextendminimize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .addClass("display-none");
      });
      $dialog.bind("dialogextendmaximize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
      $dialog.bind("dialogextendrestore", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });

      Core.unblockUI();
    }
  });
}

function posPaymentBillType() {
  var billType = $('input[name="posBillType"]:checked').val();

  if (billType == "person") {
    $("#pos-org-number-row, #pos-org-name-row").hide();
  } else {
    $("#pos-org-number-row, #pos-org-name-row").show();
    $("#pos-org-number").focus().select();
  }
  return;
}

function posCalcChangeAmount() {
  var posPayAmount = Number($("#posPayAmount").autoNumeric("get"));
  var posBarterAmt = Number($("#posBarterAmt").autoNumeric("get"));

  if (posBarterAmt > 0) {
    var useBarterUserAmount = Number($(".posUserAmount").sum());
    var barterChangeAmount = useBarterUserAmount - posPayAmount;
    $("#posBarterAmt").autoNumeric("set", posBarterAmt - barterChangeAmount);
  }

  var posUserAmount = Number($(".posUserAmount").sum());
  var posChangeAmount = posUserAmount - posPayAmount;

  if (posChangeAmount > 0) {
    if (Number($("#posBankAmount").val()) > 0) {
      PNotify.removeAll();
      new PNotify({
        title: "Warning",
        text: "Банкаар төлбөр төлөх үед ХАРИУЛТ МӨНГӨ өгөх боломжгүй!",
        type: "warning",
        addclass: "pnotify-center",
        sticker: false,
      });
      $('input[name="bankAmountDtl[]"]').val("");
      posSumBankAmount();
    } else {
      $("#posChangeAmount").autoNumeric("set", posChangeAmount);
    }
  } else {
    $("#posChangeAmount").autoNumeric("set", 0);
  }

  posBonusCardDiscountAmount();
  posCalcPaidAmount();

  return;
}

function posCalcPaidAmount() {
  var payAmount = Number($("#posPayAmount").autoNumeric("get")),
    changeAmount = Number($("#posChangeAmount").autoNumeric("get")),
    userAmount = Number($(".posUserAmount").sum());

  var paidAmount = userAmount - changeAmount,
    balanceAmount = payAmount - paidAmount;

  $("#posPaidAmount").autoNumeric("set", paidAmount);

  if (balanceAmount > 0) {
    $("#posBalanceAmount").autoNumeric("set", balanceAmount);
  } else {
    $("#posBalanceAmount").autoNumeric("set", 0);
  }

  return;
}

function posBillPrint() {
  enableScrolling();
  PNotify.removeAll();

  var returnInvoiceBillType = $("#returnInvoiceBillType").val(),
    billType = $('input[name="posBillType"]:checked').val();

  if (returnBillType == "typeChange" && returnInvoiceBillType == billType) {
    new PNotify({
      title: "Warning",
      text: plang.get("POS_0025"),
      type: "warning",
      sticker: false,
    });

    $(".pos-payment-header .radio-list").pulsate({
      color: "#F3565D",
      reach: 9,
      speed: 500,
      glow: false,
      repeat: 3,
    });

    return;
  }

  if (billType == "organization" && ($("#pos-org-number").val() == "" || $("#pos-org-name").val() == "")) {
    new PNotify({
      title: "Warning",
      text: plang.get("POS_0026"),
      type: "warning",
      sticker: false,
    });

    if ($("#pos-org-number").val() == "") {
      $("#pos-org-number").focus();
    } else if ($("#pos-org-name").val() == "") {
      $("#pos-org-name").focus();
    }

    return;
  }

  if (
    $('input[name="isLotterySendEmail"]').length &&
    $('input[name="isLotterySendEmail"]').is(":checked") &&
    $('input[name="lotteryEmail"]').val() == ""
  ) {
    new PNotify({
      title: "Warning",
      text: "Сугалаа имэйлээр илгээх - Имэйл хаяг хоосон байна",
      type: "warning",
      sticker: false,
    });
    return;
  }
  if ($('input[name="lotteryEmail"]').length && $('input[name="lotteryEmail"]').val() != "") {
    if (
      !/^[_A-Za-z0-9-\+]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,})$/.test(
        $('input[name="lotteryEmail"]').val()
      )
    ) {
      new PNotify({
        title: "Warning",
        text: "Сугалаа имэйлээр илгээх - Имэйл формат буруу байна",
        type: "warning",
        sticker: false,
      });
      return;
    }
  }

  if ($('#sejimPhoneNumber').val() != '') {
    var sejimMsg = '';
    if ($('#sejimLastName').val() == '') {
      sejimMsg += '<li>Овог заавал утга оруулна уу!</li>';
    }
    if ($('#sejimFirstName').val() == '') {
      sejimMsg += '<li>Нэр заавал утга оруулна уу!</li>';
    }
    if ($('#sejimEmail').val() == '') {
      sejimMsg += '<li>Имэйл заавал утга оруулна уу!</li>';
    }
    if ($('#sejimGenderId').val() == '') {
      sejimMsg += '<li>Хүйс заавал утга оруулна уу!</li>';
    }
    if ($('#sejimAgeRange').val() == '') {
      sejimMsg += '<li>Насны бүлэг заавал утга оруулна уу!</li>';
    }
    if (sejimMsg) {
      new PNotify({
        title: "Warning",
        text: "<strong>Сэжим бүртгэх шалгуурын алдаа</strong></br><ul style='margin-left: -12px;'>" + sejimMsg + "</ul>",
        type: "warning",
        sticker: false,
      });
      return;
    }
  }

  var payAmount = Number($("#posPayAmount").autoNumeric("get")),
    paidAmount = Number($("#posPaidAmount").autoNumeric("get")),
    bankAmount = Number($("#posBankAmount").val()),
    bankTransferAmount = Number($("#posAccountTransferAmt").val()),
    invAmountSum = Number($(".invAmountField").sum());

  if (bankAmount > 0) {
    var $bankRows = $(".pos-bank-row"),
      bankResult = true;

    $bankRows.each(function () {
      var $bankRow = $(this),
        $bankRowAmount = $bankRow.find('input[name="bankAmountDtl[]"]'),
        $bankRowId = $bankRow.find('select[name="posBankIdDtl[]"]');

      if ($bankRowAmount.val() == "" || $bankRowId.val() == "") {
        bankResult = false;
        return false;
      } else {
        $bankRowAmount.removeClass("error");
        $bankRowId.removeClass("error");
      }
    });

    if (bankResult == false) {
      var $bankRequiredInputs = $(
        ".pos-bank-row-dtl input, .pos-bank-row-dtl select"
      ).filter(function () {
        return this.value == "";
      });
      $bankRequiredInputs.each(function () {
        $(this).addClass("error");
      });

      new PNotify({
        title: "Warning",
        text: plang.get("POS_0027"),
        type: "warning",
        sticker: false,
      });

      return;
    }
  }

  if (bankTransferAmount > 0) {
    var $bankRows = $(".pos-accounttransfer-row"),
      bankResult = true;

    $bankRows.each(function () {
      var $bankRow = $(this),
        $bankRowAmount = $bankRow.find(
          'input[name="accountTransferAmountDtl[]"]'
        ),
        $bankRowId = $bankRow.find('select[name="accountTransferBankIdDtl[]"]');

      if ($bankRowAmount.val() == "" || $bankRowId.val() == "") {
        bankResult = false;
        return false;
      } else {
        $bankRowAmount.removeClass("error");
        $bankRowId.removeClass("error");
      }
    });

    if (bankResult == false) {
      var $bankRequiredInputs = $(
        ".pos-accounttransfer-row-dtl input, .pos-accounttransfer-row-dtl select"
      ).filter(function () {
        return this.value == "";
      });
      $bankRequiredInputs.each(function () {
        $(this).addClass("error");
      });

      new PNotify({
        title: "Warning",
        text: "Банкаа сонгоно уу.",
        type: "warning",
        sticker: false,
      });

      return;
    }
  }

  if (returnBillType != "typeCancel" && returnBillType != "typeChange" && invAmountSum > 0) {
    var accountTransferAmt = Number(
      $("#posAccountTransferAmt").autoNumeric("get")
    ),
      mobileNetAmt = Number($("#posMobileNetAmt").autoNumeric("get")),
      leasingAmt = Number($("#posLeasingAmt").autoNumeric("get")),
      barterAmt = Number($("#posBarterAmt").autoNumeric("get")),
      empLoanAmt = Number($("#posEmpLoanAmt").autoNumeric("get")),
      lendMnAmt = Number($("#posLendMnAmt").autoNumeric("get"));

    $('select[name="posMobileNetBankId"], select[name="posLeasingBankId"]').removeClass("error");

    if (accountTransferAmt > 0) {
      var $accountTransferRows = $(".pos-accounttransfer-row"),
        accountTransferResult = true;

      $accountTransferRows.each(function () {
        var $accounttransferRow = $(this),
          $accounttransferAmount = $accounttransferRow.find(
            'input[name="accountTransferAmountDtl[]"]'
          ),
          $accounttransferBankId = $accounttransferRow.find(
            'select[name="accountTransferBankIdDtl[]"]'
          );

        if (
          $accounttransferAmount.val() == "" ||
          $accounttransferBankId.val() == ""
        ) {
          accountTransferResult = false;
          return false;
        } else {
          $accounttransferAmount.removeClass("error");
          $accounttransferBankId.removeClass("error");
        }
      });

      if (accountTransferResult == false) {
        var $accountTransferRequiredInputs = $(
          ".pos-accounttransfer-row-dtl input, .pos-accounttransfer-row-dtl select"
        ).filter(function () {
          return this.value == "";
        });
        $accountTransferRequiredInputs.each(function () {
          $(this).addClass("error");
        });

        new PNotify({
          title: "Warning",
          text: "Дансны шилжүүллэгийн төлөх дүний талбаруудыг гүйцэд оруулна уу!",
          type: "warning",
          sticker: false,
        });

        return;
      }
    }

    if (
      mobileNetAmt > 0 &&
      $('select[name="posMobileNetBankId"]').val() == ""
    ) {
      $('select[name="posMobileNetBankId"]').addClass("error");
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0029"),
        type: "warning",
        sticker: false,
      });

      return;
    }

    if (leasingAmt > 0 && $('select[name="posLeasingBankId"]').val() == "") {
      $('select[name="posLeasingBankId"]').addClass("error");

      new PNotify({
        title: "Warning",
        text: plang.get("POS_0030"),
        type: "warning",
        sticker: false,
      });

      return;
    }

    if (
      (barterAmt > 0 || empLoanAmt > 0 || lendMnAmt > 0) &&
      $('input[name="serviceCustomerId"]').val() == "" &&
      $('input[name="empCustomerId"]').val() == ""
    ) {
      var $customerAccordian = $('a[href="#pos-payment-account-transfer"]');

      if ($customerAccordian.hasClass("collapsed")) {
        $customerAccordian.click();
      }

      $("#serviceCustomerId_displayField").focus();

      new PNotify({
        title: "Warning",
        text: plang.get("POS_0031"),
        type: "warning",
        sticker: false,
      });

      return;
    }
  }

  if (
    isConfigBankBilling == "1" &&
    returnBillType != "typeCancel" &&
    returnBillType != "typeChange" &&
    $('input[name="accountTransferAmountDtl[]"]').length &&
    $('input[name="accountTransferAmountDtl[]"]').val()
  ) {
    if (
      $('input[name="accountTransferBillingIdDtl[]"]').val() == "" &&
      $('input[name="accountTransferDescrDtl[]"]').val() == ""
    ) {
      new PNotify({
        title: "Info",
        text: "Billing ID эсвэл Гүйлгээний утга хоосон байна",
        type: "info",
        sticker: false,
      });
      return;
    }
  }

  if (paidAmount !== payAmount) {
    new PNotify({
      title: "Warning",
      text: plang.get("POS_0033"),
      type: "warning",
      sticker: false,
    });

    return;
  }

  if (!isAcceptPrintPos) {
    new PNotify({
      title: "Warning",
      text: "IPPOS terminal гүйлгээ АМЖИЛТГҮЙ болсон эсвэл УНШИЖ байна!",
      type: "warning",
      sticker: false,
    });

    return;
  }

  var $posTableBody = $("#posTable > tbody");

  if (returnBillType != "typeCancel") {
    var isCashVoucher = $posTableBody.find('[data-coupontypeid="6"]').length;

    if (
      isCashVoucher &&
      ($('select[name="customerBankId"]').val() == "" ||
        $.trim($("#customerBankAccount").val()) == "" ||
        $.trim($("#invInfoCustomerLastName").val()) == "" ||
        $.trim($("#invInfoCustomerName").val()) == "" ||
        $.trim($("#invInfoPhoneNumber").val()) == "")
    ) {
      new PNotify({
        title: "Warning",
        text: "Бэлэн мөнгөний ваучер сонгосон учир харилцагчийн банк, данс, овог, нэр, утсыг заавал бөглөнө үү!",
        type: "warning",
        sticker: false,
      });
      return;
    }
  }

  if (isConfigDescriptionRequired) {
    var $invInfoTransactionValueElem = $("#invInfoTransactionValue"),
      invInfoTransactionValue = $invInfoTransactionValueElem.val().trim();

    if (
      returnBillType != "typeCancel" &&
      returnBillType != "typeChange" &&
      invInfoTransactionValue == "" &&
      (isConfigOnlyInvDescrRequired == false ||
        (isConfigOnlyInvDescrRequired == true && invAmountSum > 0))
    ) {
      var $accountTransferAccordian = $(
        'a[href="#pos-payment-account-transfer"]'
      );

      if ($accountTransferAccordian.hasClass("collapsed")) {
        $accountTransferAccordian.click();
      }

      new PNotify({
        title: "Warning",
        text: plang.get("POS_0032"),
        type: "warning",
        sticker: false,
      });
      $invInfoTransactionValueElem.focus();

      return;
    }
  }

  var $isDeliveryRows = $posTableBody
    .find('input[name="isDelivery[]"]')
    .filter(function () {
      return $(this).val() == "1";
    }),
    $deliveryPanel = $(".pos-payment-delivery-header");

  if (
    ($deliveryPanel.is(":visible") && $isDeliveryRows.length) ||
    $posTableBody.find("input.isGiftDelivery:checked").length ||
    $posTableBody.find("input.isServiceDelivery").length
  ) {
    var recipientName = $("#recipientName").val(),
      cityId = $("#cityId").val(),
      districtId = $("#districtId").val(),
      streetId = $("#streetId").val(),
      detailAddress = $("#detailAddress").val(),
      phone1 = $("#phone1").val(),
      coordinate = $("#coordinate").val(),
      phone2 = $("#phone2").val();

    if (
      recipientName == "" ||
      cityId == "" ||
      districtId == "" ||
      streetId == "" ||
      detailAddress == "" ||
      (phone1 == "" && phone2 == "") ||
      (coordinate == "" && !$("#coordinate").hasClass("hidden"))
    ) {
      var $deliveryAccordian = $('a[href="#pos-payment-accordion-delivery"]');

      if ($deliveryAccordian.hasClass("collapsed")) {
        $deliveryAccordian.click();
      }

      new PNotify({
        title: "Warning",
        text: plang.get("POS_0034"),
        type: "warning",
        sticker: false,
      });

      if (recipientName == "") {
        $("#recipientName").focus();
      } else if (cityId == "") {
        $("#cityId").focus();
      } else if (districtId == "") {
        $("#districtId").focus();
      } else if (streetId == "") {
        $("#streetId").focus();
      } else if (detailAddress == "") {
        $("#detailAddress").focus();
      } else if (phone1 == "") {
        $("#phone1").focus();
      } else if (coordinate == "") {
        $("#coordinate").focus();
      }

      return;
    }
  }

  if (
    (returnBillType == "typeCancel" || returnBillType == "typeReduce") &&
    $("#bp-window-1594091838794").length
  ) {
    var processUniqId = $("#bp-window-1594091838794").attr("data-bp-uniq-id");
    if (window["processBeforeSave_" + processUniqId]()) {
      reasonReturnBp(function (res) {
        if (res.status === "error") {
          new PNotify({
            title: res.status,
            text: res.message,
            type: res.status,
            sticker: false,
            addclass: "pnotify-center",
          });
          return;
        }
      });
    }
  }

  if (
    returnBillType == "typeCancel" &&
    isReturnCustomerInfoRequired &&
    isTodayReturn == false
  ) {
    var invInfoCustomerRegNumber = $.trim($("#invInfoCustomerRegNumber").val());

    if (
      $.trim($("#invInfoCustomerLastName").val()) == "" ||
      $.trim($("#invInfoCustomerName").val()) == "" ||
      invInfoCustomerRegNumber == "" ||
      $.trim($("#invInfoPhoneNumber").val()) == ""
    ) {
      new PNotify({
        title: "Warning",
        text: "Харилцагчийн овог, нэр, регистр, утсыг заавал бөглөнө үү!",
        type: "warning",
        sticker: false,
      });
      return;
    }

    if (billType == "person" && /^[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ]{2}[0-9]{8}$/.test(invInfoCustomerRegNumber) == false) {
      new PNotify({
        title: "Warning",
        text: "Харилцагчийн регистрийн дугаарыг зөв бөглөнө үү!",
        type: "warning",
        sticker: false,
      });
      return;
    }
  }

  var paymentData = $("#pos-payment-form").serialize(),
    itemData = $posTableBody.find("input").serialize(),
    vatAmount = $(".pos-amount-vat").autoNumeric("get"),
    cityTaxAmount = $(".pos-amount-citytax").autoNumeric("get"),
    discountAmount = $(".pos-amount-discount").autoNumeric("get"),
    invoiceId = $("#invoiceId").val(),
    returnInvoiceId = $("#returnInvoiceId").val(),
    returnTypeInvoice = $("#returnTypeInvoice").val(),
    returnInvoiceBillId = $("#returnInvoiceBillId").val(),
    returnInvoiceBillStateRegNumber = $("#returnInvoiceBillStateRegNumber").val(),
    returnInvoiceBillStoreCode = $("#returnInvoiceBillStoreCode").val(),
    returnInvoiceBillCashRegisterCode = $("#returnInvoiceBillCashRegisterCode").val(),
    returnInvoiceNumber = $("#returnInvoiceNumber").val(),
    returnInvoiceRefNumber = $("#returnInvoiceRefNumber").val(),
    returnInvoiceBillDate = $("#returnInvoiceBillDate").val(),
    returnInvoiceIsGL = $("#returnInvoiceIsGL").val(),
    posUpointReturnResult = $("#posUpointReturnResult").val(),
    upointDetectedNumberDtl = $('input[name="upointDetectedNumberDtl[]"]').val(),
    basketInvoiceId = $("#basketInvoiceId").val(),
    returnInvoiceReceiptNumber = $("#returnInvoiceReceiptNumber").val(),
    lockerId = $("#lockerId").val();

  if (posTypeCode == "3" && $(".seperate-calculation").is(":checked") && $("#posTable").find("tr.pos-selected-seperate-row").length) {
    itemData = $posTableBody.find("> tr.pos-selected-seperate-row").find("input").serialize();
  }

  paymentData =
    paymentData +
    "&vatAmount=" +
    vatAmount +
    "&cityTaxAmount=" +
    cityTaxAmount +
    "&discountAmount=" +
    discountAmount +
    "&invoiceId=" +
    invoiceId;

  if (isNotSendVatsp == false) {
    if (
      isConfigUseCandy &&
      isBeforePrintAskLoyaltyPoint &&
      billType == "person" &&
      returnBillType != "typeCancel"
    ) {
      posBeforePrintAskLoyaltyPoint(paymentData);
      return;
    } else if (
      isConfigUseCandy &&
      isBeforePrintAskLoyaltyPoint == false &&
      billType == "person" &&
      returnBillType != "typeCancel"
    ) {
      if ($("#pos-loyalty-form").length) {
        paymentData += "&" + $("#pos-loyalty-form").serialize();
      }
    }
  }

  var billPostData = {
    paymentData: paymentData,
    itemData: itemData,
    returnInvoiceId: returnInvoiceId,
    returnTypeInvoice: vartypeCancel ? vartypeCancel : returnTypeInvoice,
    returnInvoiceBillId: returnInvoiceBillId,
    returnInvoiceBillStateRegNumber: returnInvoiceBillStateRegNumber,
    returnInvoiceBillStoreCode: returnInvoiceBillStoreCode,
    returnInvoiceBillCashRegisterCode: returnInvoiceBillCashRegisterCode,
    returnInvoiceNumber: returnInvoiceNumber,
    returnInvoiceRefNumber: returnInvoiceRefNumber,
    returnInvoiceBillDate: returnInvoiceBillDate,
    returnInvoiceIsGL: returnInvoiceIsGL,
    posUpointReturnResult: posUpointReturnResult,
    upointDetectedNumberDtl: upointDetectedNumberDtl,
    returnInvoiceReceiptNumber: returnInvoiceReceiptNumber,
    basketInvoiceId: posTypeCode == "3" ? $("#posRestSalesOrderId").val() : basketInvoiceId,
    isReceiptNumber: isReceiptNumber,
    drugPrescription: drugPrescription,
    locationId: $("#posLocationId").length ? $("#posLocationId").val() : "",
    waiterId: $("#posRestWaiterId").length ? $("#posRestWaiterId").val() : "",
    posEshopOrderTime: $("#posEshopOrderTime").length ? $("#posEshopOrderTime").val() : "",
    waiterText: ($("#posRestWaiter").length && $("#posRestWaiter").val() ? "Зөөгч: " + $("#posRestWaiter").val() : "") + ($(".selected-pos-location").text() ? ", Ширээ: " + $(".selected-pos-location").text() : "") + ($("#guestName").length && $("#guestName").val() ? ", Харилцагч: " + $("#guestName").val().trim() : ""),
    serialText: $('input[name="serialText"]').length ? $('input[name="serialText"]').val() : "",
    lockerId: lockerId,
  };

  if (isConfigEmpCustomer && $("#empCustomerId_valueField").val() != "") {
    billPostData["empCustomerId"] = $("#empCustomerId_valueField").val();
    billPostData["empCustomerName"] = $("#empCustomerId_nameField").val();
  }

  if ($(".multipleLockerId").length) {
    billPostData["multipleLockerId"] = $(".multipleLockerId").serialize();
  }

  if ($(".exp-blockui-overlay").length) {
    return;
  }

  if (returnBillType == "typeCancel" && bankAmount > 0 && !$("#isNotUseIpterminal").is(":checked")) {
    posVoidBankTerminal($("#posTerminalConfirmCode").val(), "", function (res) {
      if (res.status == 'error') return;

      $.ajax({
        type: "post",
        url: "mdpos/billPrint",
        data: billPostData,
        dataType: "json",
        beforeSend: function () {
          bpBlockMessageStart("Printing...");
        },
        success: function (data) {
          if (data.status === "success") {
            if (data.printData !== "") {
              $("div.pos-preview-print")
                .html(data.printData)
                .promise()
                .done(function () {
                  $("div.pos-preview-print").printThis({
                    debug: false,
                    importCSS: false,
                    printContainer: false,
                    dataCSS: data.css,
                    removeInline: false,
                  });

                  if (data.hasOwnProperty("basketCount")) {
                    $(".pos-basket-count").text(data.basketCount);
                  }
                  var $posChangeAmount = $("#posChangeAmount");

                  if ($posChangeAmount.length) {
                    $(".pos-amount-change").autoNumeric(
                      "set",
                      $posChangeAmount.autoNumeric("get")
                    );
                  }

                  posDisplayReset(data.billNumber);
                });
            } else {
              new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false,
              });

              posDisplayReset(data.billNumber);
            }
          } else {
            new PNotify({
              title: data.status,
              text: data.message,
              type: data.status,
              sticker: false,
            });

            bpBlockMessageStop();
          }

          //Core.unblockUI();
        },
        error: function () {
          alert("Error");
          bpBlockMessageStop();
        },
      });
    });
  } else {
    billPostData["restPosEventType"] =
      posTypeCode == "3" ? restPosEventType["event"] : "";

    $.ajax({
      type: "post",
      url: "mdpos/billPrint",
      data: billPostData,
      dataType: "json",
      beforeSend: function () {
        bpBlockMessageStart("Printing...");
      },
      success: function (data) {
        if (data.status === "amounterror") {
          alert("ҮЙЛЧИЛГЭЭНИЙ НИЙТ ДҮН, ТӨЛӨХ ДҮНТЭЙ ТЭНЦЭХГҮЙ БАЙНА!!! /PP/");
          return;
        }

        if (data.status === "success") {
          if (typeof data.gift !== "undefined" && data.gift) {
            var $dialogName2 = "dialog-pos-gift-print";
            if (!$($dialogName2).length) {
              $('<div id="' + $dialogName2 + '"></div>').appendTo("body");
            }
            var $dialog2 = $("#" + $dialogName2);

            $dialog2.empty().append(data.gift);

            $dialog2.dialog({
              cache: false,
              resizable: false,
              bgiframe: true,
              autoOpen: false,
              title: plang.get("POS_0035"),
              width: 750,
              height: "auto",
              maxHeight: $(window).height() - 40,
              modal: true,
              closeOnEscape: isCloseOnEscape,
              position: { my: "top", at: "top+10" },
              open: function () {
                disableScrolling();
              },
              close: function () {
                enableScrolling();
                $dialog2.empty().dialog("destroy").remove();
              },
              buttons: [
                {
                  text: "Сонгох",
                  class: "btn btn-sm green-meadow",
                  click: function () {
                    posGiftSaveRowPayment($dialog2);

                    $.uniform.restore($dialog2.find("input[type=checkbox]"));
                    //row.find('script[data-template="giftrow"]').text($dialog2.html());

                    $dialog2.dialog("close");
                  },
                },
                {
                  text: plang.get("close_btn"),
                  class: "btn btn-sm blue-hoki",
                  click: function () {
                    $dialog2.dialog("close");
                  },
                },
              ],
            });

            Core.initUniform($dialog2);
            $dialog2.dialog("open");
            bpBlockMessageStop();
            return;
          }

          if (data.printData !== "") {
            $("div.pos-preview-print")
              .html(data.printData)
              .promise()
              .done(function () {
                $("div.pos-preview-print").printThis({
                  debug: false,
                  importCSS: false,
                  printContainer: false,
                  dataCSS: data.css,
                  removeInline: false,
                });

                if (data.hasOwnProperty("basketCount")) {
                  $(".pos-basket-count").text(data.basketCount);
                }
                var $posChangeAmount = $("#posChangeAmount");

                if ($posChangeAmount.length) {
                  $(".pos-amount-change").autoNumeric(
                    "set",
                    $posChangeAmount.autoNumeric("get")
                  );
                }

                callPosLiftPrint(data);

                // if (posTypeCode == '3' && $('.seperate-calculation').is(':checked') && $('#posTable').find('tr.pos-selected-seperate-row').length && returnBillType == '') {
                //     $('#posTable').find('.pos-selected-seperate-row').each(function(){
                //         var inputQty = Number($(this).find('input[name="quantity[]"]').val());
                //         var seperateQty = Number($(this).find('input[name="quantity[]"]').attr('data-seperatevalue'));

                //         if (seperateQty > inputQty) {
                //             $(this).find('input[name="quantity[]"]').val(seperateQty - inputQty);
                //             posCalcRow($(this));
                //         } else {
                //             $(this).remove();
                //         }
                //     });
                //     posFixedHeaderTable();
                //     posCalcTotal();
                //     $('.seperate-calculation').prop('checked', false).trigger('change');
                //     deleteRestOrder(function(){
                //         posToBasketRestauron('f5', function(){
                //             posDisplayReset(data.billNumber, '', '');
                //             restClears();
                //         });
                //     });
                // } else

                globalOrderData = [];
                coldF9 = true;
                isMultiCustomerPrintBill = false;

                if (
                  posTypeCode == "3" &&
                  restPosEventType["event"] === "splitCalculate" &&
                  returnBillType == ""
                ) {
                  splitCalculateSaveRest(data.invoiceId, function (resp) {
                    if (resp.status == "success") {
                      restPosEventType = { event: "", data: [] };
                      restTables();
                      restClears(data.billNumber);
                    } else {
                      new PNotify({
                        title: resp.status,
                        text: resp.message,
                        type: resp.status,
                        sticker: false,
                      });
                    }
                  });
                } else {
                  if (posTypeCode == "3" && returnBillType == "") {
                    restTables();
                    restClears(data.billNumber);

                    /*if ($("#posRestSalesOrderId").val() == "") {
                      posToBasketRestauron(
                      "f5",
                      function (id) {
                        deleteRestOrder(function () {
                        restTables();
                        restClears(data.billNumber);
                        }, id);
                      },
                      "",
                      ""
                      );
                    } else {
                      restTables();
                      restClears(data.billNumber);
                    }*/
                  } else {
                    if ($("#posLocationId").length) {
                      $("#posLocationId").val("");
                      $("#posRestWaiterId").val("");
                    }
                    posDisplayReset(data.billNumber);
                  }
                }
              });
          } else {
            new PNotify({
              title: data.status,
              text: data.message,
              type: data.status,
              sticker: false,
            });

            posDisplayReset(data.billNumber);
          }
        } else {
          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
          });

          bpBlockMessageStop();
        }

        //Core.unblockUI();
      },
      error: function () {
        alert("Error");
        bpBlockMessageStop();
      },
    });
  }
}

function posSumBankAmount() {
  var sum = $(
    'input[name="bankAmountDtl[]"]:not(.ui-keyboard-preview-clone)'
  ).sum();
  $("#posBankAmount").val(sum).trigger("change");
  return;
}

function posSumVoucherAmount() {
  var sum = $('input[name="voucherDtlAmount[]"]').sum();
  $("#posVoucherAmount").val(sum).trigger("change");
  return;
}

function posSumVoucher2Amount() {
  var sum = $('input[name="voucher2DtlAmount[]"]').sum();
  $("#posVoucher2Amount").val(sum).trigger("change");
  return;
}

function posSumPrePaymentAmount() {
  var sum = $('input[name="prePyamentDtlAmount"]').sum();
  $("#posPrePaymentAmount").val(sum).trigger("change");
  return;
}

function posSumAccountTransferAmount() {
  var sum = $(
    'input[name="accountTransferAmountDtl[]"]:not(.ui-keyboard-preview-clone)'
  ).sum();
  $("#posAccountTransferAmt").val(sum).trigger("change");
  return;
}

function posSumRecievableAmount() {
  var sum = $(
    'input[name="posRecievableAmtDtl[]"]:not(.ui-keyboard-preview-clone)'
  ).sum();
  $("#posRecievableAmt").val(sum).trigger("change");
  return;
}

function posSumCandyAmount() {
  var sum = $('input[name="candyAmountDtl[]"]').sum();
  $("#posCandyAmt").val(sum).trigger("change");
  return;
}

function posSumUpointAmount() {
  var sum = $('input[name="upointAmountDtl[]"]').sum();
  $("#posUpointAmt").val(sum).trigger("change");
  return;
}

function posSumCandyCouponAmount() {
  var sum = $('input[name="candyCouponAmountDtl[]"]').sum();
  $("#posCandyCouponAmt").val(sum).trigger("change");
  return;
}

function addPosBankRow(elem) {
  var $this = $(elem),
    $bankRowDtl = $this.closest(".pos-bank-row-dtl");

  $bankRowDtl.append(
    $('script[data-template="bankrow"]')
      .text()
      .replace(
        '<span class="infoShortcut" style="position: absolute;">(F11)</span>',
        '<span class="infoShortcut" style="position: absolute;">(Delete)</span>'
      )
  );

  var $lastRow = $bankRowDtl.find("> div.pos-bank-row:last");
  $lastRow
    .find('[data-bank-action="add"]')
    .replaceWith(
      '<button type="button" class="btn btn-circle btn-sm red" onclick="removePosBankRow(this);" data-bank-action="remove" title="' +
      plang.get("POS_0153") +
      '"><i class="fa fa-trash"></i></button>'
    );
  $lastRow.addClass("mt5");

  Core.initSelect2($lastRow);
  Core.initDecimalPlacesInput($lastRow);

  $lastRow.find(".bigdecimalInit").focus();

  return;
}

function removePosBankRow(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-bank-row");

  $row.remove();

  posSumBankAmount();
  posCalcChangeAmount();

  return;
}

function addPosAccountTransferRow(elem) {
  var $this = $(elem),
    $bankRowDtl = $this.closest(".pos-accounttransfer-row-dtl");

  $bankRowDtl.append($('script[data-template="accounttransferrow"]').text());

  var $lastRow = $bankRowDtl.find("> div.pos-accounttransfer-row:last");
  $lastRow
    .find('[data-row-action="add"]')
    .replaceWith(
      '<button type="button" class="btn btn-circle btn-sm red" onclick="removePosAccountTransferRow(this);" data-bank-action="remove" title="' +
      plang.get("POS_0153") +
      '"><i class="fa fa-trash"></i></button>'
    );
  $lastRow.addClass("mt5");

  Core.initSelect2($lastRow);
  Core.initDecimalPlacesInput($lastRow);

  return;
}

function removePosAccountTransferRow(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-accounttransfer-row");

  $row.remove();

  posSumAccountTransferAmount();
  posCalcChangeAmount();

  return;
}

function addPosRecievableRow(elem) {
  var $this = $(elem),
    $bankRowDtl = $this.closest(".pos-recievable-row-dtl");

  $bankRowDtl.append($('script[data-template="recievablerow"]').text());

  var $lastRow = $bankRowDtl.find("> div.pos-recievable-row:last");
  $lastRow
    .find('[data-row-action="add"]')
    .replaceWith(
      '<button type="button" class="btn btn-circle btn-sm red" onclick="removePosRecievableRow(this);" data-bank-action="remove" title="' +
      plang.get("POS_0153") +
      '"><i class="fa fa-trash"></i></button>'
    );
  $lastRow.addClass("mt5");

  Core.initSelect2($lastRow);
  Core.initDecimalPlacesInput($lastRow);

  return;
}

function removePosRecievableRow(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-recievable-row");

  $row.remove();

  posSumRecievableAmount();
  posCalcChangeAmount();

  return;
}

function addPosCandyRow(elem) {
  var $this = $(elem),
    $candyRowDtl = $this.closest(".pos-candy-row-dtl");

  $candyRowDtl.append($('script[data-template="candyrow"]').text());

  var $lastRow = $candyRowDtl.find("> div.pos-candy-row:last");
  $lastRow
    .find('[data-row-action="add"]')
    .replaceWith(
      '<button type="button" class="btn btn-circle btn-sm red" onclick="removePosCandyRow(this);" data-bank-action="remove" title="' +
      plang.get("POS_0153") +
      '"><i class="fa fa-trash"></i></button>'
    );
  $lastRow.addClass("mt5");

  Core.initSelect2($lastRow);
  Core.initDecimalPlacesInput($lastRow);

  return;
}

function removePosCandyRow(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-candy-row");

  $row.remove();

  posSumCandyAmount();
  posCalcChangeAmount();

  return;
}

function posDisplayReset(billNumber, noTabClose, isSeperate) {
  var $paymentDialog = $("#dialog-pos-payment"),
    $receiptDialog = $("#dialog-receiptNumberFill"),
    $loyaltyDialog = $("#dialog-pos-loyaltypoint");

  if ($paymentDialog.length) {
    if ($paymentDialog.hasClass("ui-dialog-content")) {
      $paymentDialog.empty().dialog("destroy").remove();
    } else {
      $paymentDialog.remove();
    }
  }

  if ($receiptDialog.length) {
    if ($receiptDialog.hasClass("ui-dialog-content")) {
      $receiptDialog.empty().dialog("destroy").remove();
    } else {
      $receiptDialog.remove();
    }

    isItemSearchEmptyFocus = true;
    posItemCombogridList("");
  }

  if ($loyaltyDialog.length) {
    if ($loyaltyDialog.hasClass("ui-dialog-content")) {
      $loyaltyDialog.empty().dialog("destroy").remove();
    } else {
      $loyaltyDialog.remove();
    }
  }

  if (isPOSLayoutAjaxLoad) {
    var isNoTabClose = typeof noTabClose == "undefined" ? true : noTabClose;
    if (isNoTabClose) {
      multiTabActiveAutoClose();
      if (dataViewId) {
        dataViewReload(dataViewId);
      }
    }
    bpBlockMessageStop();
  }

  if (billNumber != "") {
    $("#pos-bill-number").text(billNumber);
  }

  if (typeof isSeperate === "undefined") {
    $("#posTable > tbody").empty();
    $("td[data-field-name], .pos-footer-msg").text("");

    $(
      "td.pos-amount-total, td.pos-amount-vat, td.pos-amount-citytax, td.pos-amount-discount, td.pos-amount-paid, td.pos-total-qty, td.pos-amount-receivable"
    ).autoNumeric("set", 0);

    $(".pos-invoice-number").hide();

    $(
      "#invoiceId, #invoiceBasketTypeId, #invoiceJsonStr, .pos-invoice-number-text, #returnInvoiceId, #returnTypeInvoice, #returnInvoiceBillId, #returnInvoiceNumber, #returnInvoiceRefNumber, #returnInvoiceBillType, #returnInvoiceBillDate, #returnInvoiceIsGL, #basketInvoiceId, #returnInvoiceBillStateRegNumber, #returnInvoiceBillStorecode, #returnInvoiceBillCashRegisterCode"
    ).val("");
    $("#pos-discount-percent, #pos-discount-amount")
      .val("")
      .prop("readonly", true);
    $("#posCalcItemRowDiscount, #posCalcItemRowDiscountRemove").prop(
      "disabled",
      true
    );
    //        if ($('.seperate-calculation').length && !$('.seperate-calculation').hasClass('d-none')) {
    //            $('.seperate-calculation').removeClass('d-none');
    //            $('.seperate-calculation').parent().find('span').text('Тооцоо салгах эсэх');
    //        }
  }

  //    if ($('.pos-card-layout').length) {
  //        $('.pos-card-layout').find('.pos-cardmiddle').find('div.grid-card-item').find('.basket-button').removeClass('d-none');
  //        $('.pos-card-layout').find('.pos-cardmiddle').find('div.grid-card-item').find('.basket-qty-button').attr('style', 'display:none !important');
  //        $('.pos-card-layout').find('.pos-cardmiddle').find('div.grid-card-item').find('.basket-qty-button').find('input').autoNumeric('set', 1);
  //    }

  $("#scanItemCode, #posServiceCode").combogrid("enable");
  if (posTypeCode != "3") {
    $(".pos-item-combogrid-cell").find("input.textbox-text").val("").focus();
  }
  if ($(".posRemoveItemBtnHeader").length) {
    $(".posRemoveItemBtnHeader").show();
  }

  if (isConfigEmpCustomer) {
    $(
      "#empCustomerId_valueField, #empCustomerId_displayField, #empCustomerId_nameField"
    ).val("");
    $('input[name="empCustomerId"]').removeAttr("iscouponbonus");
    var $basketListBtn = $(".pos-header-basket");
    if ($basketListBtn.length) {
      var getsplit = $basketListBtn.attr("data-criteria").split("&");
      $basketListBtn.attr("data-criteria", getsplit[0]);
    }
  }

  vartypeCancel = "";
  isReceiptNumber = false;
  isDisableRowDiscountInput = false;
  isBeforePrintAskLoyaltyPoint = true;
  isTodayReturn = false;
  returnBillType = "";
  tbltCount = 0;
  drugPrescription = [];
  if (posTypeCode == "3") {
    $(".pos-card-view").find(".grid-card-itemgroup").show();
  }

  bpBlockMessageStop();

  return;
}

function posChooseItemGift(row, rowHtml) {
  var giftTemplate = typeof rowHtml === "undefined" ? row.find('script[data-template="giftrow"]').text() : rowHtml;

  if (giftTemplate == "") {
    return;
  }

  var $dialogName = "dialog-pos-gift";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $dialog.empty().append(giftTemplate);

  $dialog.dialog({
    cache: false,
    resizable: false,
    bgiframe: true,
    autoOpen: false,
    title: plang.get("POS_0035"),
    width: 750,
    height: "auto",
    maxHeight: $(window).height() - 40,
    modal: true,
    closeOnEscape: isCloseOnEscape,
    position: { my: "top", at: "top+10" },
    open: function () {
      disableScrolling();
    },
    close: function () {
      enableScrolling();
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: [
      {
        text: "Сонгох",
        class: "btn btn-sm green-meadow",
        click: function () {
          if (
            $(".single-bundle-price-checkbox").length &&
            $(".single-bundle-price-checkbox").is(":checked")
          ) {
            posBundleSaveRow(row, $dialog);
          } else if (typeof rowHtml !== "undefined") {
            posBundleSaveRow2(row, $dialog);
          } else {
            posGiftSaveRow(row, $dialog);
          }

          $dialog.dialog("close");

          if (typeof rowHtml === "undefined") {
            $.uniform.restore($dialog.find("input[type=checkbox]"));
            row.find('script[data-template="giftrow"]').text($dialog.html());

            var $prevItemRow = row.prev("tr[data-item-id]:eq(0)");
            if (!$prevItemRow.length) {
              $prevItemRow = row.prev().prev("tr[data-item-id]:eq(0)");
            }

            /*if (
              $prevItemRow.length &&
              typeof $prevItemRow.attr("data-matrix-row") === "undefined"
            ) {
              var matrixPrevItemId = $prevItemRow
              .find('input[name="itemId[]"]')
              .val();
              var matrixCurrentItemId = row
              .find('input[name="itemId[]"]')
              .val();
              var uiMatrix = getUniqueId("sent-matrix-row-");
    	
              $prevItemRow.attr("data-matrix-row", uiMatrix);
              row.attr("data-matrix-row", uiMatrix);
    	
              $.ajax({
              type: "post",
              url: "mdpos/getMatrixDiscound",
              data: {
                filterItemId1: matrixPrevItemId,
                filterItemId2: matrixCurrentItemId,
              },
              dataType: "json",
              beforeSend: function () {
                Core.blockUI({
                message: "Loading...",
                boxed: true,
                });
              },
              success: function (data) {
                if (data) {
                if (data.discountpercent && data.gift) {
                  posChooseItemMatrixGift(data, row, $prevItemRow);
                } else if (data.discountpercent) {
                  posCalcRowDiscountPercent(
                  data.discountpercent,
                  $prevItemRow
                  );
                  posCalcRowDiscountPercent(data.discountpercent, row);
                }
                }
                Core.unblockUI();
              },
              });
            }*/
          }
        },
      },
      {
        text: plang.get("close_btn"),
        class: "btn btn-sm blue-hoki",
        click: function () {
          $dialog.dialog("close");
        },
      },
    ],
  });

  Core.initUniform($dialog);
  $dialog.dialog("open");

  if (typeof rowHtml === "undefined") {
    if (
      !$(".single-bundle-price-checkbox").length &&
      !$(".single-bundle-price-checkbox").is(":checked")
    ) {
      if (row.find("td:eq(0)").find(".gift-icon").length) {
        row
          .find('td[data-field-name="gift"]')
          .find(".gift-icon")
          .remove()
          .append(
            '<button type="button" class="btn btn-xs purple gift-icon" onclick="posChooseItemGiftBtn(this);" title="' +
            plang.get("POS_0036") +
            '"><i class="fa fa-gift"></i></button>'
          );
      } else {
        row
          .find('td[data-field-name="gift"]')
          .append(
            '<button type="button" class="btn btn-xs purple gift-icon" onclick="posChooseItemGiftBtn(this);" title="' +
            plang.get("POS_0036") +
            '"><i class="fa fa-gift"></i></button>'
          );
      }
    }
  }

  return;
}

function posGiftRowToggle(elem) {
  var $this = $(elem);
  var $row = $this.closest("tr");
  var status = $this.attr("data-toggle-status");

  if (status == "closed") {
    $row.next("tr:eq(0)").css({ display: "" });
    $this.attr("data-toggle-status", "opened");
    $this.find("i").removeClass("fa-chevron-right").addClass("fa-chevron-down");
  } else {
    $row.next("tr:eq(0)").css({ display: "none" });
    $this.attr("data-toggle-status", "closed");
    $this.find("i").removeClass("fa-chevron-down").addClass("fa-chevron-right");
  }

  return;
}

function posChooseItemGiftBtn(elem) {
  posChooseItemGift($(elem).closest("tr"));
  return;
}

function posGiftSaveRow(row, elem, matrix) {
  var $checkboxs = elem.find("input.pos-gift-item:checked"),
    $checkboxsLength = $checkboxs.length,
    $giftRow = row.next("tr[data-item-gift-row]:eq(0)"),
    $policyPrices = elem
      .find("tr[data-single-policy-price]")
      .filter(function () {
        return $(this).attr("data-single-policy-price") !== "";
      }),
    $policyPricesLen = $policyPrices.length,
    isCalcRow = false;

  if ($checkboxsLength) {
    var i = 0,
      jsonStr = "[",
      giftTable;

    if (matrix === "") {
      giftTable =
        '<table style="width: 100%" class="table table-sm table-bordered pos-matrix-table">';

      giftTable += "<tbody>";
      giftTable += "<tr>";
      giftTable +=
        '<td style="text-align: center">' +
        plang.get("POS_0037") +
        " (матриц)</td>";
      giftTable +=
        '<td style="width: 115px; text-align: center">' +
        plang.get("POS_0038") +
        "</td>";
      giftTable +=
        '<td style="width: 45px; text-align: center"><i class="fa fa-truck" title="' +
        plang.get("POS_0014") +
        '"></i></td>';
      giftTable += '<td style="width: 269px"></td>';
      giftTable += "</tr>";

      var sumDisQty = row
        .parent()
        .find('tr[data-item-code="' + row.attr("data-item-code") + '"]')
        .find(".pos-quantity-input")
        .sum();

      if (
        Number(
          $checkboxs
            .closest("tr[data-single-policy-qty]")
            .attr("data-single-policy-qty")
        ) < sumDisQty
      ) {
        PNotify.removeAll();
        new PNotify({
          title: "Warning",
          text: plang.getVar("POS_0213", {
            discountQty: $checkboxs
              .closest("tr[data-single-policy-qty]")
              .attr("data-single-policy-qty"),
          }),
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
        return false;
      }

      for (i; i < $checkboxsLength; i++) {
        var $checkbox = $($checkboxs[i]),
          $row = $checkbox.closest("tr"),
          $cell = $checkbox.closest("td"),
          $json = $cell.find('input[type="hidden"]'),
          giftPrice = $row.attr("data-gift-price"),
          rowAttribute =
            ' data-coupontypeid="' + $row.attr("data-coupon-type") + '"';

        $checkbox.attr("checked", "checked");

        jsonStr += $json.val() + ", ";

        if (Number(giftPrice) > 0) {
          rowAttribute += ' data-calc-price="' + giftPrice + '"';
          isCalcRow = true;
        }

        giftTable += "<tr" + rowAttribute + ">";

        giftTable +=
          '<td style="text-align: left">' +
          $row.find('td[data-gift-name="true"]').text() +
          "</td>";
        giftTable +=
          '<td style="text-align: right">' +
          $row.find('td[data-gift-amount="true"]').text() +
          "</td>";

        if (
          $row.attr("data-coupon-type") == "" &&
          $row.attr("data-is-service") == ""
        ) {
          giftTable +=
            '<td style="text-align: center"><input type="checkbox" class="isGiftDelivery" value="1" title="' +
            plang.get("POS_0014") +
            '"></td>';
        } else if ($row.attr("data-is-service") == "1") {
          giftTable +=
            '<td><input type="hidden" class="isServiceDelivery"></td>';
        } else {
          giftTable += "<td></td>";
        }

        giftTable += "<td></td>";

        giftTable += "</tr>";
      }
      giftTable += "</tbody>";
      giftTable += "</table>";
    } else {
      giftTable =
        '<table style="width: 100%" class="table table-sm table-bordered pos-gift-table">';

      giftTable += "<tbody>";
      giftTable += "<tr>";
      giftTable +=
        '<td style="text-align: center">' + plang.get("POS_0037") + "</td>";
      giftTable +=
        '<td style="width: 115px; text-align: center">' +
        plang.get("POS_0038") +
        "</td>";
      giftTable +=
        '<td style="width: 45px; text-align: center"><i class="fa fa-truck" title="' +
        plang.get("POS_0014") +
        '"></i></td>';
      giftTable += '<td style="width: 269px"></td>';
      giftTable += "</tr>";

      var sumDisQty = row
        .parent()
        .find('tr[data-item-code="' + row.attr("data-item-code") + '"]')
        .find(".pos-quantity-input")
        .sum();

      if (
        Number(
          $checkboxs
            .closest("tr[data-single-policy-qty]")
            .attr("data-single-policy-qty")
        ) < sumDisQty
      ) {
        PNotify.removeAll();
        new PNotify({
          title: "Warning",
          text: plang.getVar("POS_0213", {
            discountQty: $checkboxs
              .closest("tr[data-single-policy-qty]")
              .attr("data-single-policy-qty"),
          }),
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
        return false;
      }

      for (i; i < $checkboxsLength; i++) {
        var $checkbox = $($checkboxs[i]),
          $row = $checkbox.closest("tr"),
          $cell = $checkbox.closest("td"),
          $json = $cell.find('input[type="hidden"]'),
          giftPrice = $row.attr("data-gift-price"),
          rowAttribute =
            ' data-coupontypeid="' + $row.attr("data-coupon-type") + '"';

        $checkbox.attr("checked", "checked");

        jsonStr += $json.val() + ", ";

        if (Number(giftPrice) > 0) {
          rowAttribute += ' data-calc-price="' + giftPrice + '"';
          isCalcRow = true;
        }

        giftTable += "<tr" + rowAttribute + ">";

        giftTable +=
          '<td style="text-align: left">' +
          $row.find('td[data-gift-name="true"]').text() +
          "</td>";
        giftTable +=
          '<td style="text-align: right">' +
          $row.find('td[data-gift-amount="true"]').text() +
          "</td>";

        if (
          $row.attr("data-coupon-type") == "" &&
          $row.attr("data-is-service") == ""
        ) {
          giftTable +=
            '<td style="text-align: center"><input type="checkbox" class="isGiftDelivery" value="1" title="' +
            plang.get("POS_0014") +
            '"></td>';
        } else if ($row.attr("data-is-service") == "1") {
          giftTable +=
            '<td><input type="hidden" class="isServiceDelivery"></td>';
        } else {
          giftTable += "<td></td>";
        }

        giftTable += "<td></td>";

        giftTable += "</tr>";
      }
      giftTable += "</tbody>";
      giftTable += "</table>";
    }

    jsonStr = rtrim(jsonStr, ", ");
    jsonStr += "]";

    row.find('input[name="giftJson[]"]').val(jsonStr);
    row
      .find('input[data-field-name="discountQty"]')
      .val(
        $checkboxs
          .closest("tr[data-single-policy-qty]")
          .attr("data-single-policy-qty")
      );

    if (matrix === "") {
      $giftRow
        .find("td[data-item-gift-cell]")
        .find("table.pos-matrix-table")
        .remove();
    } else {
      $giftRow
        .find("td[data-item-gift-cell]")
        .find("table.pos-gift-table")
        .remove();
    }
    $giftRow
      .find("td[data-item-gift-cell]")
      .html($giftRow.find("td[data-item-gift-cell]").html() + giftTable);

    Core.initUniform($giftRow.find("td[data-item-gift-cell]"));
    $giftRow.show();
  } else {
    row.find('input[name="giftJson[]"]').val("");
    elem.find("input.pos-gift-item").removeAttr("checked");

    if ($policyPricesLen == 0) {
      elem.find("input.single-policy-price-checkbox").removeAttr("checked");
    }

    $giftRow.hide();
    $giftRow.find("td[data-item-gift-cell]").empty();
  }

  if ($policyPricesLen) {
    var policyPriceSum = 0;

    if ($policyPricesLen == 1) {
      policyPriceSum = Number($policyPrices.attr("data-single-policy-price"));

      if (!$checkboxsLength) {
        var jsonStr = "[";
        jsonStr +=
          '{"onlyPolicyId":' +
          $policyPrices.attr("data-single-policy-id") +
          "}";
        jsonStr += "]";
      }

      row.find('input[name="giftJson[]"]').val(jsonStr);
    } else {
      var $policyCheckbox = elem.find(
        "input.single-policy-price-checkbox:checked"
      );

      if ($policyCheckbox.length) {
        var $policyPriceRow = $policyCheckbox.closest(
          "tr[data-single-policy-price]"
        );

        if (!$checkboxsLength) {
          var jsonStr = "[";
          jsonStr +=
            '{"onlyPolicyId":' +
            $policyPriceRow.attr("data-single-policy-id") +
            "}";
          jsonStr += "]";
          row.find('input[name="giftJson[]"]').val(jsonStr);
        }

        $policyCheckbox.attr("checked", "checked");
        policyPriceSum = Number(
          $policyPriceRow.attr("data-single-policy-price")
        );
      }
    }

    if (policyPriceSum > 0) {
      row
        .find('td[data-field-name="salePrice"]')
        .autoNumeric("set", policyPriceSum);
      row
        .find('input[name="salePrice[]"], input[name="vatPrice[]"]')
        .val(policyPriceSum);
      row.find('input[name="noVatPrice[]"]').val(bpRound(policyPriceSum / 1.1));
      row.find('input[name="discountAmount[]"]').val("");
      row.find('input[name="discountPercent[]"]').val("");
      row.find('input[name="unitDiscount[]"]').val("");
      row.find('input[name="isDiscount[]"]').val("0");
    }

    isCalcRow = true;
  }

  if (isCalcRow) {
    posCalcRow(row);
  }

  return $checkboxsLength;
}

function posBundleSaveRow(row, elem, matrix) {
  var $budleSelector = $(".single-bundle-price-checkbox:checked");
  var policyId = $budleSelector.closest("tr").attr("data-single-policy-id");

  var $policyParent = $budleSelector
    .closest("table")
    .find('tr[data-single-policy-id="' + policyId + '"]');

  if ($policyParent.attr("data-single-policy-discountamount")) {
    row
      .find('td[data-field-name="salePrice"]')
      .autoNumeric(
        "set",
        $('input[name="salePrice[]"]').val() -
        $policyParent.attr("data-single-policy-discountamount")
      );
    row
      .find('input[name="discountAmount[]"]')
      .val(
        $('input[name="salePrice[]"]').val() -
        $policyParent.attr("data-single-policy-discountamount")
      );
    row
      .find('input[name="unitDiscount[]"]')
      .val($policyParent.attr("data-single-policy-discountamount"));
    row.find('input[name="isDiscount[]"]').val("1");
  }

  var jsonStr = "[";
  jsonStr += '{"onlyPolicyId":' + policyId + "}";
  jsonStr += "]";
  row.find('input[name="giftJson[]"]').val(jsonStr);
  posCalcRow(row);

  row
    .css("border-left", "8px solid #de26ff")
    .addClass("bundelgroup bundelgroup-" + policyId)
    .attr("data-bundle-group-id", policyId);
  $(
    '<tr style="height: 25px;border-left:8px solid #de26ff" class="bundle-discount-group bundelgroup bundelgroup-' +
    policyId +
    '" data-bundle-group-id="' +
    policyId +
    '" data-customerid=""><td colspan="7" style="font-size: 15px;color: #de26ff;font-weight: bold">Багц ' +
    (!$(".bundle-discount-group").length
      ? 1
      : ++$(".bundle-discount-group").length) +
    '</td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td></tr>'
  ).insertBefore(row);
  var $policyRows = $budleSelector
    .closest("table")
    .find('tr[data-single-child-policy-id="' + policyId + '"]');
  $policyRows.each(function () {
    var itemJsonData = JSON.parse(
      $(this).find('input[name="posPolicyJson[]"]').val()
    );
    var $tbody = $("#posTable").find("> tbody"),
      rowHtml = "";
    var salesPersonInput = '<input type="hidden" name="employeeId[]">';

    rowHtml +=
      '<tr data-item-id="' +
      itemJsonData.itemid +
      '" data-item-code="' +
      itemJsonData.itemcode.toLowerCase() +
      '" style="border-left:8px solid #de26ff" class="bundelgroup bundelgroup-' +
      policyId +
      '" data-bundle-group-id="' +
      policyId +
      '">' +
      '<td data-field-name="gift" class="text-center"></td>' +
      '<td data-field-name="itemCode" class="text-left" style="font-size: 14px;">' +
      itemJsonData.itemcode +
      "</td>" +
      '<td data-field-name="serialNumber" data-config-column="serialnumber" class="text-left"></td>' +
      '<td data-field-name="itemName" class="text-left" title="' +
      itemJsonData.itemname +
      '" style="font-size: 14px; line-height: 15px;">' +
      '<input type="hidden" name="itemId[]" value="' +
      itemJsonData.itemid +
      '">' +
      '<input type="hidden" name="itemCode[]" value="' +
      itemJsonData.itemcode +
      '">' +
      '<input type="hidden" name="itemName[]" value="' +
      itemJsonData.itemname +
      '">' +
      '<input type="hidden" name="salePrice[]" value="' +
      itemJsonData.saleprice +
      '">' +
      '<input type="hidden" name="totalPrice[]" value="' +
      itemJsonData.saleprice +
      '">' +
      '<input type="hidden" name="measureId[]" value="1">' +
      '<input type="hidden" name="measureCode[]" value="ш">' +
      '<input type="hidden" name="barCode[]" value="' +
      itemJsonData.barcode +
      '">' +
      '<input type="hidden" name="isVat[]" value="1">' +
      '<input type="hidden" name="vatPercent[]" value="10">' +
      '<input type="hidden" name="vatPrice[]" value="' +
      itemJsonData.saleprice +
      '">' +
      '<input type="hidden" name="noVatPrice[]" value="' +
      bpRound(itemJsonData.saleprice / 1.1) +
      '">' +
      '<input type="hidden" name="isCityTax[]" value="0">' +
      '<input type="hidden" name="cityTax[]" value="0">' +
      '<input type="hidden" name="lineTotalVat[]" value="0">' +
      '<input type="hidden" name="lineTotalCityTax[]" value="0">' +
      '<input type="hidden" name="cityTaxPercent[]" value="0">' +
      '<input type="hidden" name="discountPercent[]">' +
      '<input type="hidden" name="discountAmount[]">' +
      '<input type="hidden" name="unitDiscount[]">' +
      '<input type="hidden" name="totalDiscount[]">' +
      '<input type="hidden" name="isDiscount[]">' +
      '<input type="hidden" name="storeWarehouseId[]">' +
      '<input type="hidden" name="deliveryWarehouseId[]">' +
      '<input type="hidden" name="isJob[]" value="">' +
      '<input type="hidden" name="giftJson[]" value=\'' +
      jsonStr +
      "'>" +
      '<input type="hidden" name="serialNumber[]">' +
      '<input type="hidden" name="itemKeyId[]">' +
      '<input type="hidden" name="sectionId[]">' +
      '<input type="hidden" name="unitReceivable[]">' +
      '<input type="hidden" name="isDelivery[]">' +
      '<input type="hidden" name="editPriceEmployeeId[]">' +
      '<input type="hidden" name="stateRegNumber[]">' +
      '<input type="hidden" name="merchantId[]">' +
      '<input type="hidden" name="lineTotalBonusAmount[]">' +
      '<input type="hidden" name="maxPrice[]">' +
      '<input type="hidden" name="printCopies[]">' +
      '<input type="hidden" name="discountEmployeeId[]">' +
      '<input type="hidden" name="discountTypeId[]">' +
      '<input type="hidden" name="discountDescription[]">' +
      itemJsonData.itemname +
      "</td>" +
      '<td data-field-name="salePrice" class="text-right bigdecimalInit">' +
      itemJsonData.saleprice +
      "</td>" +
      '<td data-field-name="unitReceivable" data-config-column="unitreceivable" class="text-right bigdecimalInit"></td>' +
      '<td data-field-name="quantity" class="pos-quantity-cell text-right">' +
      '<input type="text" name="quantity[]" class="pos-quantity-input bigdecimalInit ignorebarcode" data-oldvalue="1" value="1" data-mdec="3"' +
      1 +
      ">" +
      "</td>" +
      '<td data-field-name="totalPrice" class="text-right bigdecimalInit">' +
      itemJsonData.saleprice +
      "</td>" +
      '<td data-field-name="delivery" class="text-center" data-config-column="delivery"></td>' +
      '<td data-field-name="salesperson" class="text-center" data-config-column="salesperson">' +
      salesPersonInput +
      "</td>" +
      "</tr>";

    $tbody.append(rowHtml);

    var $allRow = $tbody.find("tr[data-item-id]");

    var $lastRow = $tbody.find("tr[data-item-id]:last");
    $lastRow.click();
    posConfigVisibler($lastRow);
    Core.initLongInput($lastRow);
    Core.initDecimalPlacesInput($lastRow);
    Core.initUniform($lastRow);
    if (itemJsonData.discountamount) {
      $lastRow
        .find('td[data-field-name="salePrice"]')
        .autoNumeric(
          "set",
          itemJsonData.saleprice - itemJsonData.discountamount
        );
      $lastRow
        .find('input[name="discountAmount[]"]')
        .val(itemJsonData.saleprice - itemJsonData.discountamount);
      $lastRow
        .find('input[name="unitDiscount[]"]')
        .val(itemJsonData.discountamount);
      $lastRow.find('input[name="isDiscount[]"]').val("1");
    }
    posCalcRow($lastRow);

    posTableFillLastAction($tbody);
  });
}

function posBundleSaveRow2(row, elem, matrix) {
  var $checkboxs = elem.find("input.pos-gift-item:checked"),
    $checkboxsLength = $checkboxs.length;

  if ($checkboxsLength) {
    for (var i = 0; i < $checkboxsLength; i++) {
      var $checkbox = $($checkboxs[i]),
        $row = $checkbox.closest("tr"),
        $cell = $checkbox.closest("td"),
        itemJsonData = JSON.parse($cell.find('input[type="hidden"]').val());
      var $tbody = $("#posTable").find("> tbody"),
        rowHtml = "";
      var salesPersonInput = '<div class="meta-autocomplete-wrap" data-section-path="employeeId">' +
        '<div class="input-group double-between-input">' +
        '<input type="hidden" name="employeeId[]" id="employeeId_valueField" data-path="employeeId" class="popupInit">' +
        '<input type="text" name="employeeId_displayField[]" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="employeeId" id="employeeId_displayField" data-processid="1454315883636" data-lookupid="1522404331251" placeholder="' +
        plang.get("code_search") +
        '" autocomplete="off">' +
        '<span class="input-group-btn">' +
        "<button type=\"button\" class=\"btn default btn-bordered form-control-sm mr0\" onclick=\"dataViewSelectableGrid('employeeId', '1454315883636', '1522404331251', 'single', 'employeeId', this);\" tabindex=\"-1\"><i class=\"fa fa-search\"></i></button>" +
        "</span>" +
        '<span class="input-group-btn">' +
        '<input type="text" name="employeeId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="employeeId" id="employeeId_nameField" data-processid="1454315883636" data-lookupid="1522404331251" placeholder="' +
        plang.get("name_search") +
        '" tabindex="-1" autocomplete="off">' +
        "</span>" +
        "</div>" +
        "</div>";

      rowHtml +=
        '<tr data-item-id="' +
        itemJsonData.itemid +
        '" data-item-code="' +
        itemJsonData.itemcode.toLowerCase() +
        '" style="" class="">' +
        '<td data-field-name="gift" class="text-center"></td>' +
        '<td data-field-name="itemCode" class="text-left" style="font-size: 14px;">' +
        itemJsonData.itemcode +
        "</td>" +
        '<td data-field-name="serialNumber" data-config-column="serialnumber" class="text-left"></td>' +
        '<td data-field-name="itemName" class="text-left" title="' +
        itemJsonData.itemname +
        '" style="font-size: 14px; line-height: 15px;">' +
        '<input type="hidden" name="itemId[]" value="' +
        itemJsonData.itemid +
        '">' +
        '<input type="hidden" name="itemCode[]" value="' +
        itemJsonData.itemcode +
        '">' +
        '<input type="hidden" name="itemName[]" value="' +
        itemJsonData.itemname +
        '">' +
        '<input type="hidden" name="salePrice[]" value="' +
        itemJsonData.saleprice +
        '">' +
        '<input type="hidden" name="totalPrice[]" value="' +
        itemJsonData.saleprice +
        '">' +
        '<input type="hidden" name="measureId[]" value="1">' +
        '<input type="hidden" name="measureCode[]" value="ш">' +
        '<input type="hidden" name="barCode[]" value="' +
        (itemJsonData.barcode ? itemJsonData.barcode : "") +
        '">' +
        '<input type="hidden" name="isOperating[]" value="' +
        (itemJsonData.isoperating ? itemJsonData.isoperating : "") +
        '">' +
        '<input type="hidden" name="isVat[]" value="' +
        (itemJsonData.isvat ? itemJsonData.isvat : "1") +
        '">' +
        '<input type="hidden" name="vatPercent[]" value="10">' +
        '<input type="hidden" name="vatPrice[]" value="' +
        itemJsonData.saleprice +
        '">' +
        '<input type="hidden" name="noVatPrice[]" value="' +
        bpRound(itemJsonData.saleprice / 1.1) +
        '">' +
        '<input type="hidden" name="isCityTax[]" value="0">' +
        '<input type="hidden" name="cityTax[]" value="0">' +
        '<input type="hidden" name="lineTotalVat[]" value="0">' +
        '<input type="hidden" name="lineTotalCityTax[]" value="0">' +
        '<input type="hidden" name="cityTaxPercent[]" value="0">' +
        '<input type="hidden" name="discountPercent[]">' +
        '<input type="hidden" name="discountAmount[]">' +
        '<input type="hidden" name="unitDiscount[]">' +
        '<input type="hidden" name="totalDiscount[]">' +
        '<input type="hidden" name="isDiscount[]">' +
        '<input type="hidden" name="storeWarehouseId[]">' +
        '<input type="hidden" name="deliveryWarehouseId[]">' +
        '<input type="hidden" name="isJob[]" value="">' +
        '<input type="hidden" name="giftJson[]" value=\'\'>' +
        '<input type="hidden" name="serialNumber[]">' +
        '<input type="hidden" name="itemKeyId[]">' +
        '<input type="hidden" name="sectionId[]">' +
        '<input type="hidden" name="unitReceivable[]">' +
        '<input type="hidden" name="isDelivery[]">' +
        '<input type="hidden" name="editPriceEmployeeId[]">' +
        '<input type="hidden" name="stateRegNumber[]">' +
        '<input type="hidden" name="merchantId[]">' +
        '<input type="hidden" name="lineTotalBonusAmount[]">' +
        '<input type="hidden" name="maxPrice[]">' +
        '<input type="hidden" name="printCopies[]">' +
        '<input type="hidden" name="discountEmployeeId[]">' +
        '<input type="hidden" name="discountTypeId[]">' +
        '<input type="hidden" name="discountDescription[]">' +
        '<input type="hidden" name="internalId[]" value="' + (itemJsonData.internalid ? itemJsonData.internalid : '') + '">' +
        '<input type="hidden" name="parentInvoiceDtlId[]" value=\'' +
        row.salesinvoicedetailid +
        "'>" +
        itemJsonData.itemname +
        "</td>" +
        '<td data-field-name="salePrice" class="text-right bigdecimalInit">' +
        itemJsonData.saleprice +
        "</td>" +
        '<td data-field-name="unitReceivable" data-config-column="unitreceivable" class="text-right bigdecimalInit"></td>' +
        '<td data-field-name="quantity" class="pos-quantity-cell text-right">' +
        '<input type="text" name="quantity[]" class="pos-quantity-input bigdecimalInit ignorebarcode" data-oldvalue="1" value="1" data-mdec="3"' +
        1 +
        ">" +
        "</td>" +
        '<td data-field-name="totalPrice" class="text-right bigdecimalInit">' +
        itemJsonData.saleprice +
        "</td>" +
        '<td data-field-name="delivery" class="text-center" data-config-column="delivery"></td>' +
        '<td data-field-name="salesperson" class="text-center" data-config-column="salesperson">' +
        salesPersonInput +
        "</td>" +
        "</tr>";

      $tbody.append(rowHtml);

      var $allRow = $tbody.find("tr[data-item-id]");

      var $lastRow = $tbody.find("tr[data-item-id]:last");
      $lastRow.click();
      posConfigVisibler($lastRow);
      Core.initLongInput($lastRow);
      Core.initDecimalPlacesInput($lastRow);
      Core.initUniform($lastRow);
      if (itemJsonData.discountamount) {
        $lastRow
          .find('td[data-field-name="salePrice"]')
          .autoNumeric(
            "set",
            itemJsonData.saleprice - itemJsonData.discountamount
          );
        $lastRow
          .find('input[name="discountAmount[]"]')
          .val(itemJsonData.saleprice - itemJsonData.discountamount);
        $lastRow
          .find('input[name="unitDiscount[]"]')
          .val(itemJsonData.discountamount);
        $lastRow.find('input[name="isDiscount[]"]').val("1");
      }
      posCalcRow($lastRow);

      posTableFillLastAction($tbody);
    }
  }
}

function posGiftSaveRowPayment(elem) {
  var $checkboxs = elem.find("input.pos-gift-item:checked"),
    $checkboxsLength = $checkboxs.length,
    //$giftRow         = row.next('tr[data-item-gift-row]:eq(0)'),
    $policyPrices = elem
      .find("tr[data-single-policy-price]")
      .filter(function () {
        return $(this).attr("data-single-policy-price") !== "";
      }),
    $policyPricesLen = $policyPrices.length,
    isCalcRow = false;

  if ($checkboxsLength) {
    var i = 0,
      jsonStr = "[",
      giftTable =
        '<table style="width: 100%" class="table table-sm table-bordered pos-gift-table">';

    giftTable += "<tbody>";
    giftTable += "<tr>";
    giftTable +=
      '<td style="text-align: center">' + plang.get("POS_0037") + "</td>";
    giftTable +=
      '<td style="width: 115px; text-align: center">' +
      plang.get("POS_0038") +
      "</td>";
    giftTable +=
      '<td style="width: 45px; text-align: center"><i class="fa fa-truck" title="' +
      plang.get("POS_0014") +
      '"></i></td>';
    giftTable += '<td style="width: 269px"></td>';
    giftTable += "</tr>";

    for (i; i < $checkboxsLength; i++) {
      var $checkbox = $($checkboxs[i]),
        $row = $checkbox.closest("tr"),
        $cell = $checkbox.closest("td"),
        $json = $cell.find('input[type="hidden"]'),
        giftPrice = $row.attr("data-gift-price"),
        rowAttribute =
          ' data-coupontypeid="' + $row.attr("data-coupon-type") + '"';

      $checkbox.attr("checked", "checked");

      jsonStr += $json.val() + ", ";

      if (Number(giftPrice) > 0) {
        rowAttribute += ' data-calc-price="' + giftPrice + '"';
        isCalcRow = true;
      }

      giftTable += "<tr" + rowAttribute + ">";

      giftTable +=
        '<td style="text-align: left">' +
        $row.find('td[data-gift-name="true"]').text() +
        "</td>";
      giftTable +=
        '<td style="text-align: right">' +
        $row.find('td[data-gift-amount="true"]').text() +
        "</td>";

      if (
        $row.attr("data-coupon-type") == "" &&
        $row.attr("data-is-service") == ""
      ) {
        giftTable +=
          '<td style="text-align: center"><input type="checkbox" class="isGiftDelivery" value="1" title="' +
          plang.get("POS_0014") +
          '"></td>';
      } else if ($row.attr("data-is-service") == "1") {
        giftTable += '<td><input type="hidden" class="isServiceDelivery"></td>';
      } else {
        giftTable += "<td></td>";
      }

      giftTable += "<td></td>";

      giftTable += "</tr>";
    }

    jsonStr = rtrim(jsonStr, ", ");
    jsonStr += "]";

    $('input[name="giftPaymentJson"]').val(jsonStr);

    giftTable += "</tbody>";
    giftTable += "</table>";

    //$giftRow.find('td[data-item-gift-cell]').html(giftTable);

    //Core.initUniform($giftRow.find('td[data-item-gift-cell]'));
    //$giftRow.show();
  }
  /*else {
      row.find('input[name="giftJson[]"]').val('');
      elem.find('input.pos-gift-item').removeAttr('checked');
      
      if ($policyPricesLen == 0) {
        elem.find('input.single-policy-price-checkbox').removeAttr('checked');
      }
    
      $giftRow.hide();
      $giftRow.find('td[data-item-gift-cell]').empty();
    }*/

  /*if ($policyPricesLen) {
      var policyPriceSum = 0;
      
      if ($policyPricesLen == 1) {
        policyPriceSum = Number($policyPrices.attr('data-single-policy-price'));
      } else {
        var $policyCheckbox = elem.find('input.single-policy-price-checkbox:checked');   
          
        if ($policyCheckbox.length) {
          var $policyPriceRow = $policyCheckbox.closest('tr[data-single-policy-price]');
          
          $policyCheckbox.attr('checked', 'checked');    
          policyPriceSum = Number($policyPriceRow.attr('data-single-policy-price'));
        }    
      }
      
      if (policyPriceSum > 0) {
        
        row.find('td[data-field-name="salePrice"]').autoNumeric('set', policyPriceSum);
        row.find('input[name="salePrice[]"], input[name="vatPrice[]"]').val(policyPriceSum);
        row.find('input[name="noVatPrice[]"]').val(bpRound(policyPriceSum / 1.1));
        row.find('input[name="discountAmount[]"]').val('');
        row.find('input[name="discountPercent[]"]').val('');
        row.find('input[name="unitDiscount[]"]').val('');
        row.find('input[name="isDiscount[]"]').val('0');
      }
      
      isCalcRow = true;
    }
    
    if (isCalcRow) {
      posCalcRow(row);
    }*/

  return $checkboxsLength;
}

function addPosVoucherRow(elem) {
  var $this = $(elem),
    $voucherRowDtl = $this.closest(".pos-voucher-row-dtl");

  $voucherRowDtl.append($('script[data-template="voucherrow"]').text());

  var $lastRow = $voucherRowDtl.find("> div.pos-voucher-row:last");

  $lastRow
    .find('[data-voucher-action="add"]')
    .replaceWith(
      '<button type="button" class="btn btn-circle btn-sm red" onclick="removePosVoucherRow(this);" data-voucher-action="remove" title="' +
      plang.get("POS_0153") +
      '"><i class="fa fa-trash"></i></button>'
    );
  $lastRow.addClass("mt2");

  Core.initDecimalPlacesInput($lastRow);

  return;
}

function addPosVoucher2Row(elem) {
  var $this = $(elem),
    $voucherRowDtl = $this.closest(".pos-voucher2-row-dtl");

  $voucherRowDtl.append($('script[data-template="voucherrow2"]').text());

  var $lastRow = $voucherRowDtl.find("> div.pos-voucher2-row:last");

  $lastRow
    .find('[data-voucher-action="add"]')
    .replaceWith(
      '<button type="button" class="btn btn-circle btn-sm red" onclick="removePosVoucher2Row(this);" data-voucher-action="remove" title="' +
      plang.get("POS_0153") +
      '"><i class="fa fa-trash"></i></button>'
    );
  $lastRow.addClass("mt2");

  Core.initDecimalPlacesInput($lastRow);

  return;
}

function removePosVoucherRow(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-voucher-row");

  $row.remove();

  posSumVoucherAmount();
  posCalcChangeAmount();

  return;
}

function removePosVoucher2Row(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-voucher2-row");

  $row.remove();

  posSumVoucherAmount();
  posCalcChangeAmount();

  return;
}

function posCardNumber(elem) {
  elem.val(elem.val().replace(/[^0-9]/g, ""));
  var cardNumber = elem.val().trim();

  if (cardNumber != "") {
    $("#cardPinCode").focus().select();
  } else {
    $("#cardNumber").focus();
    $(
      "#cardMemberShipId, #cardId, #cardPinCode, #cardOwnerName, #cardBeginAmount, #cardDiscountPercentAmount, #cardPayPercentAmount, #cardDiscountPercent"
    ).val("");
    $("#cardPayPercent-label").text("0");
    $("#cardDiscountPercent-label").text("0");
  }

  return;
}

function posCardNumberPinCode(elem, customerId) {
  var cardNumber = $("#cardNumber").val().trim();
  var $cardPhoneNumberElement = $("#cardPhoneNumber");
  var pinCode = typeof customerId === "undefined" ? elem.val().trim() : "",
    cardPhoneNumber = "";

  if ($cardPhoneNumberElement.length) {
    cardPhoneNumber = $cardPhoneNumberElement.val().trim();
  }

  if (
    ((cardNumber != "" || cardPhoneNumber != "") && pinCode != "") ||
    typeof customerId !== "undefined"
  ) {
    $.ajax({
      type: "post",
      url: "mdpos/getCardNumber",
      data: {
        cardNumber: cardNumber,
        cardPhoneNumber: cardPhoneNumber,
        pinCode: pinCode,
        customerId: customerId,
      },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        PNotify.removeAll();

        if (data.status === "success") {
          $("#cardMemberShipId").val(data.membershipid);
          $("#cardId").val(data.cardid);
          $("#cardOwnerName").val(data.firstname);
          $("#cardOwnerFirstName").val(data.lastname);
          $("#cardOwnerRegisterNumber").val(data.stateregnumber);
          $("#cardOwnerBirthday").val(moment(data.dateofbirth).format("YYYY-MM-DD"));
          $('input[name="cardDiscountType"]').prop("checked", false);
          $("#cardDiscountType").closest(".form-group").show();
          if (typeof $("#posPayAmount").attr("data-oldvalue") !== "undefined") {
            $("#posPayAmount").autoNumeric(
              "set",
              $("#posPayAmount").attr("data-oldvalue")
            );
          }

          if (data.discounttype == "-") {
            $("#cardDiscountType2").prop("checked", true);
          } else if (data.discounttype == "+") {
            $("#cardDiscountType").prop("checked", true);
            $("#cardDiscountType").closest(".form-group").hide();
          }
          $.uniform.update($('input[name="cardDiscountType"]'));

          $("#cardBeginAmount").autoNumeric("set", data.endbonusamt);

          $("#cardPayPercent-label").text(data.paypercent);
          $("#cardDiscountPercent-label").text(data.discountpercent);
          $("#cardDiscountPercent").val(data.discountpercent);

          posBonusCardAmountCalc();
        } else {
          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
          });
        }

        Core.unblockUI();
      },
      error: function () {
        alert("Error");
      },
    });
  } else {
    PNotify.removeAll();

    new PNotify({
      title: "Warning",
      text: plang.get("POS_0039"),
      type: "warning",
      sticker: false,
    });
  }

  return;
}

function posCardNumberByPhoneNumber(elem) {
  var $cardPhoneNumberElement = $("#cardPhoneNumber");
  var cardPhoneNumber = "";

  if ($cardPhoneNumberElement.length) {
    cardPhoneNumber = $cardPhoneNumberElement.val().trim();
  }

  if (cardPhoneNumber !== "" && $("#cardNumber").val().trim() === "") {
    $.ajax({
      type: "post",
      url: "mdpos/getCardNumberByPhoneNumber",
      data: { cardPhoneNumber: cardPhoneNumber },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        PNotify.removeAll();

        if (data.status === "success") {
          $("#cardNumber").val(data.cardnumber);
        } else {
          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
          });
        }

        Core.unblockUI();
      },
      error: function () {
        alert("Error");
      },
    });
  }

  return;
}

function posBonusCardAmountCalc() {
  var posPayAmount = Number($("#posPayAmount").autoNumeric("get")),
    cardPayPercent = Number($("#cardPayPercent-label").text()),
    cardBeginAmount = Number($("#cardBeginAmount").autoNumeric("get")),
    $posBonusCardAmount = $("#posBonusCardAmount");

  var $tbody = $("#posTable > tbody"),
    $rows = $tbody.find("> tr[data-item-id]"),
    sum = 0,
    sumCheck = false;

  $rows.each(function () {
    var $row = $(this),
      totalPrice = Number($row.find('input[name="totalPrice[]"]').val());

    if (
      $row.find('input[data-name="isNotUseBonusCard"]').length &&
      $row.find('input[data-name="isNotUseBonusCard"]').val() == "0"
    ) {
      sum += totalPrice;
    } else if (
      $row.find('input[data-name="isNotUseBonusCard"]').length &&
      $row.find('input[data-name="isNotUseBonusCard"]').val() == "1"
    ) {
      sumCheck = true;
    }
  });
  posPayAmount = sum > 0 || sumCheck ? sum : posPayAmount;

  if (cardPayPercent > 0) {
    var percentAmount = (cardPayPercent / 100) * posPayAmount;

    if (percentAmount > cardBeginAmount) {
      $("#cardPayPercentAmount").autoNumeric("set", cardBeginAmount);
    } else {
      $("#cardPayPercentAmount").autoNumeric("set", percentAmount);
    }
  }

  if (cardBeginAmount > 0) {
    /*var cardPayPercentAmount = Number($('#cardPayPercentAmount').autoNumeric('get'));
        
      if (cardBeginAmount > cardPayPercentAmount) {
        $posBonusCardAmount.autoNumeric('set', cardPayPercentAmount);
      } else {
        $posBonusCardAmount.autoNumeric('set', cardBeginAmount);
      }*/

    $posBonusCardAmount.removeAttr("readonly");
    $posBonusCardAmount.focus().select();

    posBonusCardEndAmountCalc();
  } else {
    $posBonusCardAmount.attr("readonly", "readonly");
    $("#cardOwnerName").focus();
  }

  posBonusCardDiscountAmount();

  return;
}

function posBonusCardDiscountAmount() {
  var posPayAmount = Number($("#posPayAmount").autoNumeric("get")),
    posVoucherAmount = Number($("#posVoucherAmount").val()),
    posPrePaymentAmount = Number($("#posPrePaymentAmount").val()),
    cardDiscountPercent = Number($("#cardDiscountPercent-label").text()),
    bonusCardAmount = Number($("#posBonusCardAmount").autoNumeric("get")),
    totalDiscountAmount =
      posPayAmount - posVoucherAmount - posPrePaymentAmount - bonusCardAmount;

  var $posBody = $("#posTable > tbody > tr[data-item-id]"),
    totalUamt = 0,
    totalUamtCheck = false,
    limitBonusAmountSection = 0,
    limitBonusAmountSection2 = 0,
    sumCustomerAmount = {};

  $posBody.each(function () {
    var $row = $(this);
    var customerId = $row.find('input[name="customerId[]"]').val();

    if (
      limitBonusAmount.hasOwnProperty(
        "sectionid=" + $row.find('input[name="sectionId[]"]').val()
      )
    ) {
      limitBonusAmountSection += parseInt(
        limitBonusAmount[
        "sectionid=" + $row.find('input[name="sectionId[]"]').val()
        ],
        10
      );
    }
    if (
      limitBonusAmount.hasOwnProperty(
        "sectionid2=" + $row.find('input[name="sectionId[]"]').val()
      ) &&
      !limitBonusAmountSection2
    ) {
      limitBonusAmountSection2 =
        limitBonusAmount[
        "sectionid2=" + $row.find('input[name="sectionId[]"]').val()
        ];
      limitBonusAmountSection += parseInt(limitBonusAmountSection2, 10);
    }

    if (
      $posBody.find("> tr.multi-customer-group").length === 1 &&
      $posBody.find('> tr[data-customerid=""]').length
    ) {
      var $rows = $('#posTable > tbody > tr[data-customerid=""]');
    } else if (
      $posBody.find('> tr[data-customerid="' + customerId + '"]').length
    ) {
      var $rows = $(
        '#posTable > tbody > tr[data-customerid="' + customerId + '"]'
      );
    } else {
      var $rows = $("#posTable > tbody > tr[data-item-id]");
    }

    sumCustomerAmount[customerId] = 0;
    sumCustomerAmount[customerId + "_percent"] = 0;
    sumCustomerAmount[customerId + "_amount"] = 0;
    $rows.each(function () {
      var $rowc = $(this);
      if ($rowc.find('input[name="customerId[]"]').length) {
        sumCustomerAmount[customerId] += Number(
          $rowc.find('input[name="totalPrice[]"]').val()
        );
        if (
          limitBonusAmountSection &&
          limitBonusAmountSection < sumCustomerAmount[customerId]
        ) {
          sumCustomerAmount[customerId] = limitBonusAmountSection;
        }
        sumCustomerAmount[customerId + "_percent"] = $rowc
          .find('input[name="unitBonusPercent[]"]')
          .val();
        sumCustomerAmount[customerId + "_amount"] +=
          Number($rowc.find('input[name="unitBonusAmount[]"]').val()) *
          Number($rowc.find('input[name="quantity[]"]').val());
      }
    });
  });

  if (Object.keys(sumCustomerAmount).length > 0) {
    var rowList = Object.keys(sumCustomerAmount);
    for (var i = 0; i < rowList.length; i++) {
      if (
        rowList[i].indexOf("_percent") === -1 &&
        rowList[i].indexOf("_amount") === -1
      ) {
        if (Number(sumCustomerAmount[rowList[i] + "_amount"])) {
          totalUamt += Number(sumCustomerAmount[rowList[i] + "_amount"]);
        } else {
          totalUamt +=
            (Number(sumCustomerAmount[rowList[i] + "_percent"]) / 100) *
            Number(sumCustomerAmount[rowList[i]]);
        }
      }
    }
  }

  $posBody.each(function () {
    var $row = $(this),
      linebonusAmount = 0,
      totalPrice = Number($row.find('input[name="totalPrice[]"]').val()),
      unitPrice = Number($row.find('input[name="salePrice[]"]').val());

    if (limitBonusAmountSection && limitBonusAmountSection < totalPrice) {
      totalPrice = limitBonusAmountSection;
    }
    if (limitBonusAmountSection && limitBonusAmountSection < unitPrice) {
      unitPrice = limitBonusAmountSection;
    }

    if (
      $row.find('input[data-name="calcBonusPercent"]').length &&
      Number($row.find('input[data-name="calcBonusPercent"]').val())
    ) {
      linebonusAmount =
        (Number($row.find('input[data-name="calcBonusPercent"]').val()) / 100) *
        totalPrice;
      $row
        .find('input[name="unitBonusAmount[]"]')
        .val(
          (Number($row.find('input[data-name="calcBonusPercent"]').val()) /
            100) *
          unitPrice
        );
      $row.find('input[name="lineTotalBonusAmount[]"]').val(isNaN(linebonusAmount) ? 0 : linebonusAmount);
      //totalUamt += linebonusAmount;
      totalUamtCheck = true;
    } else if (
      $row.find('input[name="unitBonusAmount[]"]').length &&
      $row.find('input[name="unitBonusAmount[]"]').val() !== ""
    ) {
      linebonusAmount =
        Number($row.find('input[name="unitBonusAmount[]"]').val()) *
        Number($row.find('input[name="quantity[]"]').val());
      $row.find('input[name="lineTotalBonusAmount[]"]').val(isNaN(linebonusAmount) ? 0 : linebonusAmount);
      //totalUamt += linebonusAmount;
      totalUamtCheck = true;
    }
  });

  // console.log(limitBonusAmount);
  // console.log(limitBonusAmountSection);
  // console.log(sumCustomerAmount);

  if (totalUamtCheck) {
    // if (limitBonusAmountSection && limitBonusAmountSection < totalUamt) {
    //     totalUamt = limitBonusAmountSection;
    // }
    $('label[for="cardDiscountPercentAmount"]').text("Нэмэгдэх бонусын дүн:");
    $("#cardDiscountPercentAmount").autoNumeric("set", totalUamt);
  } else if (cardDiscountPercent > 0) {
    $("#cardDiscountPercentAmount").autoNumeric(
      "set",
      (cardDiscountPercent / 100) * totalDiscountAmount
    );
    /*if ($('input[name="cardDiscountType"]:checked').val() == '-') {
        $('#posPayAmount').attr('data-oldvalue', posPayAmount);
        $('#posPayAmount').autoNumeric('set', posPayAmount - ((cardDiscountPercent / 100) * totalDiscountAmount));
      }*/
  }

  posBonusCardEndAmountCalc();

  return;
}

function posBonusCardEndAmountCalc() {
  var cardBeginAmount = Number($("#cardBeginAmount").autoNumeric("get")),
    bonusCardAmount = Number($("#posBonusCardAmount").autoNumeric("get")),
    discountPercentAmount = Number(
      $("#cardDiscountPercentAmount").autoNumeric("get")
    );

  $("#cardEndAmount").autoNumeric(
    "set",
    cardBeginAmount - bonusCardAmount + discountPercentAmount
  );

  return;
}

function posFixedHeaderTable() {
  var $posTable = $("#posTable");
  $posTable.fixedHeaderTable("destroy");
  $posTable.fixedHeaderTable(/*{footer: true}*/);
}

function posTableSetHeight(h) {
  if ($(".pos-center-inside-height").length) {
    $(".pos-center-inside-height").css(
      "height",
      $(window).height() -
      $(".pos-center-inside-height").offset().top -
      ($("#pos-bottom-bar").length
        ? $("#pos-bottom-bar").height()
        : $("#pos-card-bar").height()) -
      (typeof h !== 'undefined' ? h : 3)
    );
  }

  if ($(".pos-cardleft").length) {
    $(".pos-cardleft").css(
      "height",
      $(window).height() - $(".pos-cardleft").offset().top - 1
    );
    $(".pos-cardright").css(
      "height",
      $(window).height() - $(".pos-cardright").offset().top - 1
    );
    $(".pos-cardmiddle").css(
      "height",
      $(window).height() - $(".pos-cardmiddle").offset().top - 1
    );
    $(".pos-cardmiddle").css(
      "width",
      $(window).width() -
      $(".pos-cardleft").width() -
      $(".pos-cardright").width() -
      23
    );
  }
  return;
}

function posTestBillPrint() {
  $.ajax({
    type: "post",
    url: "mdpos/testBillPrint",
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Printing...",
        boxed: true,
      });
    },
    success: function (data) {
      $("div.pos-preview-print")
        .html(data.html)
        .promise()
        .done(function () {
          $("div.pos-preview-print").printThis({
            debug: false,
            importCSS: false,
            printContainer: false,
            dataCSS: data.css,
            removeInline: false,
          });
        });

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  });

  return;
}

function posNewCardCustomer(elem, p1) {
  var $dialogName = "dialog-pos-new-crm";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo(
      "body"
    );
  }
  var $dialog = $("#" + $dialogName),
    jsonParam;

  if ($("#newServiceCustomerJson").val() == "") {
    jsonParam = JSON.stringify({
      customerName: $("#invInfoCustomerName").val(),
      phoneNumber: $("#invInfoPhoneNumber").val(),
      cityId: $("#cityId").val(),
      districtId: $("#districtId").val(),
      streetId: $("#streetId").val(),
      positionName: $("#invInfoCustomerRegNumber").val(),
      address: $("#detailAddress").val(),
    });
  } else {
    jsonParam = $("#newServiceCustomerJson").val();
  }

  $.ajax({
    type: "post",
    url: "mdwebservice/callMethodByMeta",
    data: {
      metaDataId: "1522036719483",
      isDialog: true,
      isSystemMeta: false,
      fillJsonParam: jsonParam,
      responseType: p1 ? "" : "json",
      callerType: "pos",
      openParams: '{"callerType":"pos"}'
    },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data.Html);

      var processForm = $("#wsForm", "#" + $dialogName);
      var processUniqId = processForm.parent().attr("data-bp-uniq-id");

      var buttons = [
        {
          text: data.run_btn,
          class: "btn green-meadow btn-sm bp-btn-save",
          click: function (e) {
            if (window["processBeforeSave_" + processUniqId]($(e.target))) {
              processForm.validate({
                ignore: "",
                highlight: function (element) {
                  $(element).addClass("error");
                  $(element).parent().addClass("error");
                  if (
                    processForm.find("div.tab-pane:hidden:has(.error)").length
                  ) {
                    processForm
                      .find("div.tab-pane:hidden:has(.error)")
                      .each(function (index, tab) {
                        var tabId = $(tab).attr("id");
                        processForm
                          .find('a[href="#' + tabId + '"]')
                          .tab("show");
                      });
                  }
                },
                unhighlight: function (element) {
                  $(element).removeClass("error");
                  $(element).parent().removeClass("error");
                },
                errorPlacement: function () { },
              });

              var isValidPattern = initBusinessProcessMaskEvent(processForm);

              if (processForm.valid() && isValidPattern.length === 0) {
                processForm.ajaxSubmit({
                  type: "post",
                  url: "mdwebservice/runProcess",
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      boxed: true,
                      message: plang.get("POS_0040"),
                    });
                  },
                  success: function (responseData) {
                    if (responseData.status === "success") {
                      var responseParam = responseData.paramData;
                      $("#newCardCustomerJson").val(
                        JSON.stringify(responseParam)
                      );
                      if (p1) {
                        new PNotify({
                          title: 'Success',
                          text: plang.get('msg_save_success'),
                          type: 'success',
                          sticker: false,
                          addclass: 'pnotify-center'
                        });
                      }
                      $dialog.dialog("close");
                    }
                    Core.unblockUI();
                  },
                  error: function () {
                    alert("Error");
                  },
                });
              }
            }
          },
        },
        {
          text: data.close_btn,
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ];

      var dialogWidth = data.dialogWidth,
        dialogHeight = data.dialogHeight;

      if (data.isDialogSize === "auto") {
        dialogWidth = 1200;
        dialogHeight = "auto";
      }

      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.Title,
          width: dialogWidth,
          height: dialogHeight,
          modal: true,
          closeOnEscape:
            typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: buttons,
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
        });
      if (data.dialogSize === "fullscreen") {
        $dialog.dialogExtend("maximize");
      }
      $dialog.dialog("open");
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initBPAjax($dialog);
    Core.unblockUI();
  });
}

function posNewCardCustomerCancel(elem) {
  var $newCardCustomerJson = $("#newCardCustomerJson");

  if ($newCardCustomerJson.val() != "") {
    var $dialogName = "dialog-crm-confirm";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialog = $("#" + $dialogName);

    $dialog.empty().append(plang.get("POS_0041"));
    $dialog.dialog({
      cache: false,
      resizable: false,
      bgiframe: true,
      autoOpen: false,
      title: "Confirm",
      width: 400,
      height: "auto",
      modal: true,
      close: function () {
        $dialog.empty().dialog("destroy").remove();
      },
      buttons: [
        {
          text: "Тийм",
          class: "btn green-meadow btn-sm",
          click: function () {
            $newCardCustomerJson.val("");
            $dialog.dialog("close");
          },
        },
        {
          text: "Үгүй",
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ],
    });

    $dialog.dialog("open");
  }

  return;
}

function posNewServiceCustomer(elem, p2) {
  var $dialogName = "dialog-pos-new-crm";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo(
      "body"
    );
  }
  var $dialog = $("#" + $dialogName),
    jsonParam = '';

  if ($("#newServiceCustomerJson").val() == "") {
    jsonParam = JSON.stringify({
      customerName: $("#invInfoCustomerName").val(),
      cityId: $("#cityId").val(),
      districtId: $("#districtId").val(),
      streetId: $("#streetId").val(),
      phoneNumber: $("#invInfoPhoneNumber").val(),
    });
  } else {
    jsonParam = $("#newServiceCustomerJson").val();
  }

  $.ajax({
    type: "post",
    url: "mdwebservice/callMethodByMeta",
    data: {
      metaDataId: "1451013525557",
      isDialog: true,
      isSystemMeta: false,
      fillJsonParam: jsonParam,
      responseType: p2 ? "" : "json",
      callerType: "pos",
      openParams: '{"callerType":"pos"}',
    },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data.Html);

      var $processForm = $("#wsForm", "#" + $dialogName),
        processUniqId = $processForm.parent().attr("data-bp-uniq-id");

      var buttons = [
        {
          text: data.run_btn,
          class: "btn green-meadow btn-sm bp-btn-save",
          click: function (e) {
            if (window["processBeforeSave_" + processUniqId]($(e.target))) {
              $processForm.validate({
                ignore: "",
                highlight: function (element) {
                  $(element).addClass("error");
                  $(element).parent().addClass("error");
                  if (
                    $processForm.find("div.tab-pane:hidden:has(.error)").length
                  ) {
                    $processForm
                      .find("div.tab-pane:hidden:has(.error)")
                      .each(function (index, tab) {
                        var tabId = $(tab).attr("id");
                        $processForm
                          .find('a[href="#' + tabId + '"]')
                          .tab("show");
                      });
                  }
                },
                unhighlight: function (element) {
                  $(element).removeClass("error");
                  $(element).parent().removeClass("error");
                },
                errorPlacement: function () { },
              });

              var isValidPattern = initBusinessProcessMaskEvent($processForm);

              if ($processForm.valid() && isValidPattern.length === 0) {
                $processForm.ajaxSubmit({
                  type: "post",
                  url: "mdwebservice/runProcess",
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      boxed: true,
                      message: plang.get("POS_0040"),
                    });
                  },
                  success: function (responseData) {
                    if (responseData.status === "success") {
                      if (p2) {
                        $dialog.dialog("close");
                        Core.unblockUI();
                        return;
                      }
                      var responseParam = responseData.paramData;
                      $("#newServiceCustomerJson").val(
                        JSON.stringify(responseParam)
                      );

                      $(
                        "#serviceCustomerId_valueField, #serviceCustomerId_displayField, #serviceCustomerId_nameField"
                      )
                        .val("")
                        .attr("title", "");

                      $("#recipientName").val(responseParam.customerName);

                      if (responseParam.cityId != null) {
                        $('select[name="cityId"]').trigger("select2-opening", [
                          true,
                        ]);
                        $('select[name="cityId"]').select2(
                          "val",
                          responseParam.cityId
                        );

                        var $districtId = $("select#districtId");
                        $districtId.select2("enable");
                        $districtId.removeClass("data-combo-set");
                      }

                      if (responseParam.districtId != null) {
                        $('select[name="districtId"]').trigger(
                          "select2-opening",
                          [true]
                        );
                        $('select[name="districtId"]').select2(
                          "val",
                          responseParam.districtId
                        );

                        var $streetId = $("select#streetId");
                        $streetId.select2("enable");
                        $streetId.removeClass("data-combo-set");
                      }

                      if (responseParam.streetId != null) {
                        $('select[name="streetId"]').trigger(
                          "select2-opening",
                          [true]
                        );
                        $('select[name="streetId"]').select2(
                          "val",
                          responseParam.streetId
                        );
                      }

                      $("#invInfoCustomerLastName").val(responseParam.lastName);
                      $("#invInfoCustomerName").val(responseParam.customerName);
                      $("#invInfoPhoneNumber").val(responseParam.phoneNumber);

                      $("#detailAddress").val(responseParam.address);
                      $("#phone1").val(responseParam.phoneNumber);
                      $("#phone2").val(responseParam.phoneNumber1);

                      $dialog.dialog("close");
                    }
                    Core.unblockUI();
                  },
                  error: function () {
                    alert("Error");
                  },
                });
              }
            }
          },
        },
        {
          text: data.close_btn,
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ];

      var dialogWidth = data.dialogWidth,
        dialogHeight = data.dialogHeight;

      if (data.isDialogSize === "auto") {
        dialogWidth = 1200;
        dialogHeight = "auto";
      }

      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.Title,
          width: dialogWidth,
          height: dialogHeight,
          modal: true,
          closeOnEscape:
            typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: buttons,
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
        });
      if (data.dialogSize === "fullscreen") {
        $dialog.dialogExtend("maximize");
      }
      $dialog.dialog("open");
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initBPAjax($dialog);
    Core.unblockUI();
  });
}

function posNewServiceCustomerCancel(elem) {
  var $newServiceCustomerJson = $("#newServiceCustomerJson");

  if ($newServiceCustomerJson.val() != "") {
    var $dialogName = "dialog-crm-confirm";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialog = $("#" + $dialogName);

    $dialog.empty().append(plang.get("POS_0042"));
    $dialog.dialog({
      cache: false,
      resizable: false,
      bgiframe: true,
      autoOpen: false,
      title: "Confirm",
      width: 400,
      height: "auto",
      modal: true,
      close: function () {
        $dialog.empty().dialog("destroy").remove();
      },
      buttons: [
        {
          text: "Тийм",
          class: "btn green-meadow btn-sm",
          click: function () {
            $newServiceCustomerJson.val("");
            $dialog.dialog("close");
          },
        },
        {
          text: "Үгүй",
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ],
    });

    $dialog.dialog("open");
  }

  return;
}

function posCandyInfo(elem) {
  var $dialogName = "dialog-crm-confirm";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);
  $.ajax({
    type: "post",
    url: "mdpos/candyback",
    data: {},
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading ...",
        boxed: true,
      });
    },
    success: function (data) {
      if (data.status == "success") {
        $dialog.empty().append(data.html);
        $dialog.dialog({
          cache: false,
          resizable: false,
          bgiframe: true,
          autoOpen: false,
          title: data.title,
          width: 500,
          minWidth: 500,
          height: "auto",
          modal: true,
          dialogClass: "pos-payment-dialog",
          closeOnEscape: isCloseOnEscape,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: data.close_btn,
              class: "btn btn-sm blue-hoki",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });
        $dialog.dialog("open");
      } else {
        new PNotify({
          title: "Warning",
          text: data.message,
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
      }
      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initClean($dialog);
  });
}

function posCustomerList(elem) {
  var $tbody = $("#posTable").find("> tbody");

  if (
    $(elem).closest("td").text().indexOf("харилцагч өөрчлөх") !== -1 &&
    $tbody
      .find(
        'tr[data-customerid="' +
        $(elem).closest("tr").attr("data-customerid") +
        '"]:last'
      )
      .find('input[name="customerId[]"]')
      .val() == ""
  ) {
    var $dialogNameWaterPin = "dialog-change-customer-row";
    if (!$("#" + $dialogNameWaterPin).length) {
      $('<div id="' + $dialogNameWaterPin + '"></div>').appendTo("body");
    }
    var $dialogWaiterPin = $("#" + $dialogNameWaterPin);

    $dialogWaiterPin
      .empty()
      .append(
        '<form method="post" autocomplete="off" id="customerChangeForm"><input type="text" name="waiterPinCode" value="' +
        $(elem).closest("tr").attr("data-customerid") +
        '" class="form-control" style="height:40px;font-size: 18px;" autocomplete="off" required="required"></form>'
      );
    $dialogWaiterPin.dialog({
      cache: false,
      resizable: true,
      bgiframe: true,
      autoOpen: false,
      title: "Харилцагч өөрчлөх",
      width: 400,
      height: "auto",
      modal: true,
      open: function () {
        $dialogWaiterPin.on(
          "keydown",
          'input[name="waiterPinCode"]',
          function (e) {
            var keyCode = e.keyCode ? e.keyCode : e.which;
            if (keyCode == 13) {
              $(this)
                .closest(".ui-dialog")
                .find(".ui-dialog-buttonpane button:first")
                .trigger("click");
              return false;
            }
          }
        );
      },
      close: function () {
        $dialogWaiterPin.empty().dialog("destroy").remove();
      },
      buttons: [
        {
          text: plang.get("insert_btn"),
          class: "btn btn-sm green-meadow",
          click: function () {
            var $form = $("#customerChangeForm");
            $form.validate({ errorPlacement: function () { } });
            if ($form.valid()) {
              var newcust = $form.find('input[name="waiterPinCode"]').val();

              if (
                $tbody.find("> tr.multi-customer-group").length === 1 &&
                $tbody.find('> tr[data-customerid=""]').length
              ) {
                var $rows = $('#posTable > tbody > tr[data-customerid=""]');
              } else {
                var $rows = $(
                  '#posTable > tbody > tr[data-customerid="' +
                  $(elem).closest("tr").attr("data-customerid") +
                  '"]'
                );
              }

              if ($rows.length) {
                $rows.each(function () {
                  var $tr = $(this);

                  $tr.attr("data-customerid", newcust);
                  if ($tr.hasClass("multi-customer-group")) {
                    $tr
                      .find("> td:eq(0)")
                      .html(
                        newcust +
                        ' <a href="javascript:;" style="background-color: #e4b700;color: #333;padding: 4px 3px 3px 4px;margin-left: 13px; display:none" onclick="posCustomerList(this);">харилцагч өөрчлөх</a>'
                      );
                  }

                  if ($tr.find('input[name="customerId[]"]').length) {
                    $tr.attr(
                      "data-item-id-customer-id",
                      $tr.attr("data-item-id") + "_" + newcust
                    );
                    $tr.find('input[name="guestName[]"]').val(newcust);
                  }
                });

                if (
                  $(
                    '#posTable > tbody > tr[data-customerid="' +
                    newcust +
                    '"][class="multi-customer-group"]'
                  ).length == 2
                ) {
                  $(
                    '#posTable > tbody > tr[data-customerid="' +
                    newcust +
                    '"][class="multi-customer-group"]'
                  )
                    .eq(1)
                    .remove();
                }
              }
              $dialogWaiterPin.dialog("close");
            }
          },
        },
        {
          text: plang.get("close_btn"),
          class: "btn btn-sm blue-madison",
          click: function () {
            $dialogWaiterPin.dialog("close");
          },
        },
      ],
    });
    $dialogWaiterPin.dialog("open");
  } else {
    dataViewSelectableGrid(
      "nullmeta",
      "0",
      "1536742182010",
      "single",
      "nullmeta",
      elem,
      "posSelectedCustomer"
    );
  }
}
function posSelectedCustomer(
  metaDataCode,
  processMetaDataId,
  chooseType,
  elem,
  rows,
  paramRealPath,
  lookupMetaDataId,
  isMetaGroup
) {
  var row = rows[0];
  posDiscountCustomer($(elem).closest("tr").attr("data-customerid"), row);
}

function posInvoiceList(elem, metaId) {
  if (metaId != 0) {
    if (isUserPosV3) {
      dataViewSelectableGrid(
        "nullmeta",
        "0",
        metaId,
        "single",
        "nullmeta",
        elem,
        "casherCheck"
      );
    } else {
      dataViewSelectableGrid(
        "nullmeta",
        "0",
        metaId,
        "single",
        "nullmeta",
        elem,
        "createBillResultDataFromInvoice"
      );
    }
  } else {
    dataViewSelectableGrid(
      "nullmeta",
      "0",
      "1521452365722",
      "single",
      "nullmeta",
      elem,
      "posFillItemsByInvoiceId"
    );
  }
}
function posFillItemsByInvoiceId(
  metaDataCode,
  processMetaDataId,
  chooseType,
  elem,
  rows,
  paramRealPath,
  lookupMetaDataId,
  isMetaGroup
) {
  var row = rows[0];
  isDisableRowDiscountInput = false;

  if (posTypeCode == "3" || posTypeCode == "4") {
    row["typeid"] = 1;
  }

  $.ajax({
    type: "post",
    url: "mdpos/fillItemsByInvoiceId",
    data: { row: row },
    dataType: "json",
    beforeSend: function () {
      bpBlockMessageStart("Loading...");
    },
    success: function (data) {
      PNotify.removeAll();

      if (data.status == "success") {
        posDisplayReset("");

        if (posTypeCode == "3" || posTypeCode == "4") {
          $("#basketInvoiceId").val(row.id);

          if (row.hasOwnProperty("cardnumber")) {
            $("#basketCustomerId").val(row.customerid);
            $("#basketCustomerCode").val(row.customercode);
            $("#basketCustomerName").val(row.customername);
            $("#basketCardNumber").val(row.cardnumber);
            $("#basketCreatedUserId").val(row.createduserid);
          }

          if (
            data.orderData &&
            data.orderData.data.hasOwnProperty("locationid")
          ) {
            $("#posLocationId").val(data.orderData.data.locationid);
            if ($("#posRestWaiterId").val() == '') {
              $("#posRestWaiterId").val(data.orderData.data.salespersonid);
            }
          }
        } else {
          new PNotify({
            title: plang.get("POS_0011"),
            text: data.message,
            type: "warning",
            sticker: false,
            addclass: "pnotify-center",
          });

          $(".pos-invoice-number-text").val(row.booknumber);
          $("#invoiceId").val(row.id);
          $("#invoiceBasketTypeId").val(row.invoicetypeid);
          $("#invoiceJsonStr").val(JSON.stringify(row));
        }
        if (posTypeCode !== "3") {
          $(".pos-invoice-number").show();
        }

        var $tbody = $("#posTable").find("> tbody");

        $tbody
          .html(data.html)
          .promise()
          .done(function () {
            posConfigVisibler($tbody);
            Core.initLongInput($tbody);
            Core.initDecimalPlacesInput($tbody);
            Core.initUniform($tbody);

            if (
              (posTypeCode == "3" || posTypeCode == "4") &&
              data.orderData &&
              data.orderData.data.customerid
            ) {
              $.ajax({
                type: "post",
                url: "api/callDataview",
                data: {
                  dataviewId: "1536742182010",
                  criteriaData: {
                    id: [
                      {
                        operator: "=",
                        operand: data.orderData.data.customerid,
                      },
                    ],
                  },
                },
                dataType: "json",
                success: function (data) {
                  if (data.status === "success" && data.result[0]) {
                    $('input[name="empCustomerId"]').val(data.result[0]["id"]);
                    $('input[name="empCustomerId_displayField"]').val(
                      data.result[0]["customercode"]
                    );
                    $('input[name="empCustomerId_nameField"]').val(
                      data.result[0]["customername"]
                    );
                    $('input[name="empCustomerId"]').attr(
                      "data-row-data",
                      JSON.stringify(data.result[0])
                    );
                  } else {
                    $('input[name="empCustomerId"]').val("");
                    $('input[name="empCustomerId_displayField"]').val("");
                    $('input[name="empCustomerId_nameField"]').val("");
                    $('input[name="empCustomerId"]').attr("data-row-data", "");
                  }
                },
              });
            }

            if (row.hasOwnProperty("typeid") && row.typeid == "3") {
              $tbody.find("button.btn").prop("disabled", true);
              $tbody.find('input[type="text"]').prop("readonly", true);

              $("#scanItemCode").combogrid("disable");

              isDisableRowDiscountInput = true;
            }

            if (data.hasOwnProperty("description")) {
              $(".pos-footer-msg").text(data.description);
            }

            posGiftRowsSetDelivery($tbody);

            var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");
            $firstRow.click();

            posFixedHeaderTable();
            posCalcTotal();

            $tbody.find(".gift-icon").hide();
          });
      } else {
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false,
          addclass: "pnotify-center",
        });

        $(".pos-invoice-number").hide();
        $(
          ".pos-invoice-number-text, #invoiceId, #invoiceJsonStr, #invoiceBasketTypeId"
        ).val("");
      }

      bpBlockMessageStop();
    },
    error: function (request, status, error) {
      alert(request.responseText);
      bpBlockMessageStop();
    },
  });
}

function posContractList(elem) {
  dataViewSelectableGrid(
    "nullmeta",
    "0",
    "1547268625221588",
    "single",
    "nullmeta",
    elem,
    "posFillItemsByContractId"
  );
}
function posFillItemsByContractId(
  metaDataCode,
  processMetaDataId,
  chooseType,
  elem,
  rows,
  paramRealPath,
  lookupMetaDataId,
  isMetaGroup
) {
  var row = rows[0];
  isDisableRowDiscountInput = false;

  $.ajax({
    type: "post",
    url: "mdpos/fillItemsByContractId",
    data: { row: row },
    dataType: "json",
    beforeSend: function () {
      bpBlockMessageStart("Loading...");
    },
    success: function (data) {
      PNotify.removeAll();

      if (data.status == "success") {
        posDisplayReset("");

        $("#pos-invoice-label-title").text("Гэрээний дугаар");
        $(".pos-invoice-number-text").val(row.contractcode);
        $("#invoiceId").val(row.id);
        $(".pos-invoice-number").show();

        var $tbody = $("#posTable").find("> tbody");

        $tbody
          .html(data.html)
          .promise()
          .done(function () {
            posConfigVisibler($tbody);
            Core.initLongInput($tbody);
            Core.initDecimalPlacesInput($tbody);
            Core.initUniform($tbody);

            //if (row.hasOwnProperty('typeid') && row.typeid == '3') {
            $tbody.find("button.btn").prop("disabled", true);
            $tbody.find('input[type="text"]').prop("readonly", true);

            $("#scanItemCode").combogrid("disable");

            isDisableRowDiscountInput = true;
            //}

            if (data.hasOwnProperty("description")) {
              $(".pos-footer-msg").text(data.description);
            }

            posGiftRowsSetDelivery($tbody);

            var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");
            $firstRow.click();

            posFixedHeaderTable();
            posCalcTotal();
          });
      } else {
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false,
          addclass: "pnotify-center",
        });

        $(".pos-invoice-number").hide();
        $(
          ".pos-invoice-number-text, #invoiceId, #invoiceJsonStr, #invoiceBasketTypeId"
        ).val("");
      }

      bpBlockMessageStop();
    },
    error: function (request, status, error) {
      alert(request.responseText);
      bpBlockMessageStop();
    },
  });
}

function posGiftRowsSetDelivery($tbody) {
  var $checkedGiftDelivery = $tbody.find("input.isGiftDelivery:checked");

  if ($checkedGiftDelivery.length) {
    $checkedGiftDelivery.each(function () {
      var $this = $(this),
        $index = $this.closest("tr").index() - 1,
        $row = $this.closest('tr[data-item-gift-row="true"]'),
        $itemRow = $row.prev("tr[data-item-id]:eq(0)"),
        $giftJson = JSON.parse($itemRow.find('input[name="giftJson[]"]').val());

      $giftJson[$index]["isDelivery"] = 1;
      $itemRow.find('input[name="giftJson[]"]').val(JSON.stringify($giftJson));
    });
  }
  return;
}
function posRemoveInvoiceNumber() {
  posDisplayReset($("#pos-bill-number").text());
  return;
}

function posServiceAddRow(rows) {
  var $tbody = $("#posTable").find("> tbody"),
    rowHtml = "";

  $.each(rows, function (i, rowData) {
    var itemName = rowData.jobname.trim(),
      jobAmount = Number(rowData.jobrate),
      saleprice = jobAmount,
      vatprice = jobAmount,
      novatprice = bpRound(jobAmount / 1.1),
      isDelivery = "0",
      isJob = "1",
      qtyReadonly = "",
      salesPersonInput = '<input type="hidden" name="employeeId[]">',
      rowBtn =
        '<button type="button" class="btn btn-xs yellow" title="' +
        plang.get("POS_0043") +
        '"><i class="fa fa-wrench"></i></button>',
      salesPersonInputTmp =
        '<div class="meta-autocomplete-wrap" data-section-path="employeeId">' +
        '<div class="input-group double-between-input">' +
        '<input type="hidden" name="employeeId[]" id="employeeId_valueField" data-path="employeeId" class="popupInit">' +
        '<input type="text" name="employeeId_displayField[]" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="employeeId" id="employeeId_displayField" data-processid="1454315883636" data-lookupid="1522404331251" placeholder="' +
        plang.get("code_search") +
        '" autocomplete="off">' +
        '<span class="input-group-btn">' +
        "<button type=\"button\" class=\"btn default btn-bordered form-control-sm mr0\" onclick=\"dataViewSelectableGrid('employeeId', '1454315883636', '1522404331251', 'single', 'employeeId', this);\" tabindex=\"-1\"><i class=\"fa fa-search\"></i></button>" +
        "</span>" +
        '<span class="input-group-btn">' +
        '<input type="text" name="employeeId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="employeeId" id="employeeId_nameField" data-processid="1454315883636" data-lookupid="1522404331251" placeholder="' +
        plang.get("name_search") +
        '" tabindex="-1" autocomplete="off">' +
        "</span>" +
        "</div>" +
        "</div>";

    if (rowData.isservice == "1") {
      isDelivery = "1";
      salesPersonInput = salesPersonInputTmp;
    }

    if (rowData.iscoupon == "1") {
      isJob = "2";
      salesPersonInput = salesPersonInputTmp;
      qtyReadonly = ' readonly="readonly" data-accept-remove="1"';
      rowBtn =
        '<button type="button" class="btn btn-xs red-intense" title="' +
        plang.get("POS_0044") +
        '"><i class="fa fa-credit-card"></i></button>';

      var $addedRow = $tbody.find(
        'tr[data-item-code="' + rowData.jobcode + '"]'
      );
      if ($addedRow.length) {
        return;
      }
    }

    rowHtml +=
      '<tr data-item-id="' +
      rowData.jobid +
      '" data-item-code="' +
      rowData.jobcode.toLowerCase() +
      '">' +
      '<td data-field-name="gift" class="text-center">' +
      rowBtn +
      "</td>" +
      '<td data-field-name="itemCode" class="text-left" style="font-size: 14px;">' +
      rowData.jobcode +
      "</td>" +
      '<td data-field-name="serialNumber" data-config-column="serialnumber" class="text-left"></td>' +
      '<td data-field-name="itemName" class="text-left" title="' +
      itemName +
      '" style="font-size: 14px; line-height: 15px;">' +
      '<input type="hidden" name="itemId[]" value="' +
      rowData.jobid +
      '">' +
      '<input type="hidden" name="itemCode[]" value="' +
      rowData.jobcode +
      '">' +
      '<input type="hidden" name="itemName[]" value="' +
      itemName +
      '">' +
      '<input type="hidden" name="salePrice[]" value="' +
      saleprice +
      '">' +
      '<input type="hidden" name="totalPrice[]" value="' +
      saleprice +
      '">' +
      '<input type="hidden" name="measureId[]" value="1">' +
      '<input type="hidden" name="measureCode[]" value="ш">' +
      '<input type="hidden" name="barCode[]" value="' +
      rowData.taxcode +
      '">' +
      '<input type="hidden" name="isVat[]" value="1">' +
      '<input type="hidden" name="vatPercent[]" value="10">' +
      '<input type="hidden" name="vatPrice[]" value="' +
      vatprice +
      '">' +
      '<input type="hidden" name="noVatPrice[]" value="' +
      novatprice +
      '">' +
      '<input type="hidden" name="isCityTax[]" value="0">' +
      '<input type="hidden" name="cityTax[]" value="0">' +
      '<input type="hidden" name="lineTotalVat[]" value="0">' +
      '<input type="hidden" name="lineTotalCityTax[]" value="0">' +
      '<input type="hidden" name="cityTaxPercent[]" value="0">' +
      '<input type="hidden" name="discountPercent[]">' +
      '<input type="hidden" name="discountAmount[]">' +
      '<input type="hidden" name="unitDiscount[]">' +
      '<input type="hidden" name="totalDiscount[]">' +
      '<input type="hidden" name="isDiscount[]">' +
      '<input type="hidden" name="storeWarehouseId[]">' +
      '<input type="hidden" name="deliveryWarehouseId[]">' +
      '<input type="hidden" name="isJob[]" value="' +
      isJob +
      '">' +
      '<input type="hidden" name="giftJson[]">' +
      '<input type="hidden" name="serialNumber[]">' +
      '<input type="hidden" name="itemKeyId[]">' +
      '<input type="hidden" name="sectionId[]">' +
      '<input type="hidden" name="unitReceivable[]">' +
      '<input type="hidden" name="maxPrice[]">' +
      '<input type="hidden" name="printCopies[]">' +
      '<input type="hidden" name="discountEmployeeId[]">' +
      '<input type="hidden" name="discountTypeId[]">' +
      '<input type="hidden" name="discountDescription[]">' +
      itemName +
      "</td>" +
      '<td data-field-name="salePrice" class="text-right bigdecimalInit">' +
      (!posServiceRowPriceEdit
        ? saleprice
        : '<input type="text" name="salePriceInput[]" class="pos-saleprice-input bigdecimalInit" value="' +
        saleprice +
        '" data-mdec="3">') +
      "</td>" +
      '<td data-field-name="unitReceivable" data-config-column="unitreceivable" class="text-right bigdecimalInit"></td>' +
      '<td data-field-name="quantity" class="pos-quantity-cell text-right">' +
      '<input type="text" name="quantity[]" class="pos-quantity-input data-oldvalue="1" bigdecimalInit ignorebarcode" value="1" data-mdec="3"' +
      qtyReadonly +
      ">" +
      "</td>" +
      '<td data-field-name="totalPrice" class="text-right bigdecimalInit">' +
      saleprice +
      "</td>" +
      '<td data-field-name="delivery" class="text-center" data-config-column="delivery">' +
      '<input type="hidden" name="isDelivery[]" value="' +
      isDelivery +
      '">' +
      "</td>" +
      '<td data-field-name="salesperson" class="text-center" data-config-column="salesperson">' +
      salesPersonInput +
      "</td>" +
      "</tr>";
  });

  $tbody.append(rowHtml);

  var $allRow = $tbody.find("tr[data-item-id]");

  posConfigVisibler($allRow);
  Core.initLongInput($allRow);
  Core.initDecimalPlacesInput($allRow);
  Core.initUniform($allRow);

  var $lastRow = $tbody.find("tr[data-item-id]:last");
  $lastRow.click();
  posCalcRow($lastRow);

  posTableFillLastAction($tbody);

  return;
}

function posTableFillLastAction($tbody) {
  posFixedHeaderTable();
  //posCalcTotal();

  var $parent = $tbody.closest(".fht-tbody");
  if ($parent.hasScrollBar()) {
    $parent.scrollTop(3000);
  }
  return;
}

function posItemPackageAction($tbody) {
  var $packageRows = $tbody.find("tr[data-packageid]");
  var packageLength = $packageRows.length;

  if (packageLength) {
    var i = 0,
      $itemRow,
      packageId,
      hdrPackageQty,
      dtlPackageQty,
      discountAmount,
      salePrice,
      totalPackageQty,
      unitDiscount,
      discountPercent,
      itemQty;

    for (i; i < packageLength; i++) {
      $itemRow = $($packageRows[i]);
      packageId = $itemRow.attr("data-packageid");
      hdrPackageQty = Number($itemRow.attr("data-hdrpackageqty"));
      dtlPackageQty = Number($itemRow.attr("data-dtlpackageqty"));
      totalPackageQty = Number(
        $tbody
          .find(
            'tr[data-packageid="' + packageId + '"][data-hdrpackageqty!=""]'
          )
          .find(".pos-quantity-input")
          .sum()
      );
      salePrice = Number($itemRow.find('input[name="salePrice[]"]').val());
      itemQty = Number($itemRow.find(".pos-quantity-input").autoNumeric("get"));

      if (
        (hdrPackageQty > 0 && hdrPackageQty <= totalPackageQty) ||
        (dtlPackageQty > 0 && dtlPackageQty <= itemQty)
      ) {
        discountAmount = Number($itemRow.attr("data-packageprice"));
        unitDiscount = salePrice - discountAmount;
        discountPercent = bpRound((discountAmount * 100) / salePrice);

        $itemRow
          .find('td[data-field-name="salePrice"]')
          .autoNumeric("set", discountAmount);
        $itemRow.find('input[name="discountAmount[]"]').val(discountAmount);
        $itemRow.find('input[name="discountPercent[]"]').val(discountPercent);
        $itemRow.find('input[name="unitDiscount[]"]').val(unitDiscount);
        $itemRow.find('input[name="isDiscount[]"]').val("1");
      } else {
        $itemRow
          .find('td[data-field-name="salePrice"]')
          .autoNumeric("set", salePrice);
        $itemRow.find('input[name="discountAmount[]"]').val("");
        $itemRow.find('input[name="discountPercent[]"]').val("");
        $itemRow.find('input[name="unitDiscount[]"]').val("");
        $itemRow.find('input[name="isDiscount[]"]').val("");
        $itemRow.find('input[name="discountTypeId[]"]').val("");
        $itemRow.find('input[name="discountDescription[]"]').val("");
      }

      posCalcRow($itemRow);
    }
  }

  return;
}

function posCalcRowDiscountPercent($this, $itemRow) {
  var discountPercent = typeof $this === "string" ? $this : Number($this.val()),
    salePrice =
      typeof posIsEditBasketPrice === "undefined"
        ? Number($itemRow.find('input[name="salePrice[]"]').val())
        : Number(
          $itemRow.find('input[name="salePriceInput[]"]').autoNumeric("get")
        );

  if (discountPercent > 0) {
    var discount = (discountPercent / 100) * salePrice,
      discountAmount = salePrice - discount;

    $itemRow
      .find('td[data-field-name="salePrice"]')
      .autoNumeric("set", discountAmount);
    $itemRow.find('input[name="discountAmount[]"]').val(discountAmount);
    $itemRow.find('input[name="discountPercent[]"]').val(discountPercent);
    $itemRow.find('input[name="unitDiscount[]"]').val(discount);
    $itemRow.find('input[name="isDiscount[]"]').val("1");

    $("#pos-discount-amount").autoNumeric("set", discount);
  } else {
    $itemRow
      .find('td[data-field-name="salePrice"]')
      .autoNumeric("set", salePrice);
    $itemRow.find('input[name="discountAmount[]"]').val("");
    $itemRow.find('input[name="discountPercent[]"]').val("");
    $itemRow.find('input[name="unitDiscount[]"]').val("");
    $itemRow.find('input[name="isDiscount[]"]').val("");

    $("#pos-discount-amount").autoNumeric("set", "");
  }

  posCalcRow($itemRow);
  return;
}

function posCalcRowDiscountAmount($this, $itemRow) {
  var discountAmount = Number($this.autoNumeric("get")),
    salePrice =
      typeof posIsEditBasketPrice === "undefined"
        ? Number($itemRow.find('input[name="salePrice[]"]').val())
        : Number(
          $itemRow.find('input[name="salePriceInput[]"]').autoNumeric("get")
        );

  if (discountAmount > 0) {
    if (salePrice < discountAmount) {
      $itemRow
        .find('td[data-field-name="salePrice"]')
        .autoNumeric("set", salePrice);

      $itemRow.find('input[name="discountAmount[]"]').val("");
      $itemRow.find('input[name="discountPercent[]"]').val("");
      $itemRow.find('input[name="unitDiscount[]"]').val("");
      $itemRow.find('input[name="isDiscount[]"]').val("");

      $("#pos-discount-percent").val("");

      PNotify.removeAll();

      new PNotify({
        title: "Warning",
        text: plang.get("POS_0045"),
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
    } else {
      var discountPercent = (discountAmount * 100) / salePrice,
        discount = discountAmount,
        discountAmount = salePrice - discount;

      $itemRow
        .find('td[data-field-name="salePrice"]')
        .autoNumeric("set", discountAmount);
      $itemRow.find('input[name="discountAmount[]"]').val(discountAmount);
      $itemRow.find('input[name="discountPercent[]"]').val(discountPercent);
      $itemRow.find('input[name="unitDiscount[]"]').val(discount);
      $itemRow.find('input[name="isDiscount[]"]').val("1");

      $("#pos-discount-percent").val(discountPercent);
    }
  } else {
    $itemRow
      .find('td[data-field-name="salePrice"]')
      .autoNumeric("set", salePrice);
    $itemRow.find('input[name="discountAmount[]"]').val("");
    $itemRow.find('input[name="discountPercent[]"]').val("");
    $itemRow.find('input[name="unitDiscount[]"]').val("");
    $itemRow.find('input[name="isDiscount[]"]').val("");

    $("#pos-discount-percent").val("");
  }

  posCalcRow($itemRow);
  return;
}

function posFocusDiscountInput() {
  var $itemSelectedRow = $("#posTable").find("tbody > tr.pos-selected-row");

  if (
    $itemSelectedRow.length &&
    !$("#posCalcItemRowDiscount").is("[disabled]")
  ) {
    posCalcItemRowDiscount();
  }
  return;
}

function posTalonList() {
  if (isTalonListProtect) {
    var $dialogName = "dialog-talon-protect";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialog = $("#" + $dialogName);

    $dialog
      .empty()
      .append(
        '<form method="post" autocomplete="off" id="talonListPassForm"><input type="password" name="talonListPass" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>'
      );
    $dialog.dialog({
      cache: false,
      resizable: true,
      bgiframe: true,
      autoOpen: false,
      title: "Нууц үг оруулах",
      width: 400,
      height: "auto",
      modal: true,
      open: function () {
        $(this).keypress(function (e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            $(this)
              .parent()
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
          }
        });
        $('input[name="talonListPass"]').on("keydown", function (e) {
          var keyCode = e.keyCode ? e.keyCode : e.which;
          if (keyCode == 13) {
            $(this)
              .closest(".ui-dialog")
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
          }
        });
      },
      close: function () {
        $dialog.empty().dialog("destroy").remove();
      },
      buttons: [
        {
          text: plang.get("insert_btn"),
          class: "btn btn-sm green-meadow",
          click: function () {
            PNotify.removeAll();
            var $form = $("#talonListPassForm");

            $form.validate({ errorPlacement: function () { } });

            if ($form.valid()) {
              $.ajax({
                type: "post",
                url: "mdpos/checkTalonListPass",
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function () {
                  Core.blockUI({
                    message: "Loading...",
                    boxed: true,
                  });
                },
                success: function (dataSub) {
                  if (dataSub.status == "success") {
                    $dialog.dialog("close");
                    posTalonDataViewList();
                  } else {
                    new PNotify({
                      title: dataSub.status,
                      text: dataSub.message,
                      type: dataSub.status,
                      sticker: false,
                    });
                  }
                  Core.unblockUI();
                },
              });
            }
          },
        },
        {
          text: plang.get("close_btn"),
          class: "btn btn-sm blue-madison",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ],
    });
    $dialog.dialog("open");
  } else {
    posTalonDataViewList();
  }
}

function posTalonDataViewList() {
  var $dialogName = "dialog-talon-dataview";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdobject/dataValueViewer",
    data: {
      metaDataId: "1522115383994585",
      viewType: "detail",
      dataGridDefaultHeight: $(window).height() - 260,
      uriParams: '{"storeId": ' + posStoreId + "}",
      ignorePermission: 1,
    },
    beforeSend: function () {
      Core.blockUI({
        animate: true,
      });
    },
    success: function (dataHtml) {
      $dialog
        .empty()
        .append(
          '<div class="row" id="object-value-list-1522115383994585">' +
          dataHtml +
          "</div>"
        );
      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: plang.get("POS_0046"),
          width: 1000,
          height: 600,
          modal: true,
          open: function () {
            $dialog
              .find(".top-sidebar-content:eq(0)")
              .attr("style", "padding-left: 15px !important");
            $dialog.find('a[onclick*="toQuickMenu"]').remove();
          },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("close_btn"),
              class: "btn blue-madison btn-sm",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
          /*maximize : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }, 
              restore : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }*/
        });

      $dialog.dialog("open");
      $dialog.dialogExtend("maximize");

      $dialog.bind("dialogextendminimize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .addClass("display-none");
      });
      $dialog.bind("dialogextendmaximize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
      $dialog.bind("dialogextendrestore", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    //Core.initDVAjax($dialog);
  });
}

function posTerminalDataViewList() {
  var $dialogName = "dialog-talon-dataview";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdobject/dataValueViewer",
    data: {
      metaDataId: "1568715373855272",
      viewType: "detail",
      dataGridDefaultHeight: $(window).height() - 190,
      uriParams:
        '{"storeId": ' +
        posStoreId +
        ', "cashRegisterId": ' +
        cashRegisterId +
        ', "createdCashierId": ' +
        cashierId +
        "}",
      ignorePermission: 1,
    },
    beforeSend: function () {
      Core.blockUI({
        animate: true,
      });
    },
    success: function (dataHtml) {
      $dialog
        .empty()
        .append(
          '<div class="row" id="object-value-list-1568715373855272">' +
          dataHtml +
          "</div>"
        );
      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: plang.get("POS_0046"),
          width: 1000,
          height: 600,
          modal: true,
          open: function () {
            $dialog
              .find(".top-sidebar-content:eq(0)")
              .attr("style", "padding-left: 15px !important");
            $dialog.find('a[onclick*="toQuickMenu"]').remove();
          },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("close_btn"),
              class: "btn blue-madison btn-sm",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
          /*maximize : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }, 
              restore : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }*/
        });

      $dialog.dialog("open");
      $dialog.dialogExtend("maximize");

      $dialog.bind("dialogextendminimize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .addClass("display-none");
      });
      $dialog.bind("dialogextendmaximize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
      $dialog.bind("dialogextendrestore", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    //Core.initDVAjax($dialog);
  });
}

function posAfterSale(selectedRow) {
  posDisplayReset("Нэмэлт борлуулалт", false);
  $("#dialog-talon-dataview").dialog("close");

  selectedRow["code"] = selectedRow.itemcode;
  appendItem(selectedRow, "posaftersale", function () { });
}

function posTalonReturnCancel(rowId, isId) {
  var invoiceId = isId == false ? rowId.id : rowId;

  if (invoiceId) {
    PNotify.removeAll();

    if (rowId.booktypeid == "201" || rowId.booktypeid == "203") {
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0047"),
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }

    posDisplayReset(plang.get("POS_0048"), false);
    $("#dialog-talon-dataview").dialog("close");

    $.ajax({
      type: "post",
      url: "mdpos/getInvoiceRender",
      data: { invoiceId: invoiceId },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        if (data.status == "success") {
          $("#returnInvoiceId").val(invoiceId);
          $("#returnTypeInvoice").val("typeCancel");
          $("#returnInvoiceBillId").val(data.billid);
          $("#returnInvoiceNumber").val(data.invoiceNumber);
          $("#returnInvoiceRefNumber").val(data.refNumber);
          $("#returnInvoiceBillType").val(data.billType);
          $("#returnInvoiceBillDate").val(data.vatdate);
          $("#returnInvoiceIsGL").val(rowId.isgl);
          $(".posRemoveItemBtnHeader").hide();
          if (posUseIpTerminal === "1" && $("#isNotUseIpterminal").length) {
            $("#isNotUseIpterminal")
              .closest(".form-group")
              .removeClass("d-none");
          }
          if (rowId.stateregnumber && rowId.orgcashregistercode) {
            $("#returnInvoiceBillStateRegNumber").val(rowId.stateregnumber);
            $("#returnInvoiceBillStorecode").val(rowId.orgstorecode);
            $("#returnInvoiceBillCashRegisterCode").val(
              rowId.orgcashregistercode
            );
          }

          if (isConfigHealthRecipe) {
            $("#returnInvoiceReceiptNumber").val(rowId.receiptnumber);

            if (rowId.receiptnumber) {
              isReceiptNumber = true;
            }
          }

          if (data.hasOwnProperty("isTodayReturn") && data.isTodayReturn == 1) {
            isTodayReturn = true;
          }

          returnBillType = "typeCancel";

          var $tbody = $("#posTable").find("> tbody");

          $tbody.append(data.html);

          posConfigVisibler($tbody);
          Core.initLongInput($tbody);
          Core.initDecimalPlacesInput($tbody);
          Core.initUniform($tbody);

          $tbody.find("button.btn").prop("disabled", true);
          $tbody.find('input[type="text"]').prop("readonly", true);
          $tbody.find(".basket-inputqty-button").each(function () {
            $(this).find("span:eq(0)").hide();
            $(this).find("span:eq(2)").hide();
          });

          var $checkboxs = $tbody.find('input[type="checkbox"]');
          if ($checkboxs.length) {
            $checkboxs.attr({
              "data-isdisabled": "true",
              style: "cursor: not-allowed",
              tabindex: "-1",
            });
            $checkboxs.closest(".checker").addClass("disabled");
          }

          var $dialogName = "dialog-pos-payment";
          $(
            '<div id="' + $dialogName + '" style="display: none"></div>'
          ).appendTo("body");
          var $dialog = $("#" + $dialogName);

          $dialog.empty().append(data.payment);
          Core.initClean($dialog);

          $("#scanItemCode, #posServiceCode").combogrid("disable");

          var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");

          $firstRow.click();

          posFixedHeaderTable();
          posCalcTotal();

          $("#posPaidAmount").autoNumeric(
            "set",
            $("#posPayAmount").autoNumeric("get")
          );
        } else {
          $(
            "#returnInvoiceId, #returnTypeInvoice, #returnInvoiceBillId, #returnInvoiceNumber, #returnInvoiceRefNumber, #returnInvoiceBillType, #returnInvoiceBillDate, #returnInvoiceIsGL, #returnInvoiceBillStateRegNumber, #returnInvoiceBillStorecode, #returnInvoiceBillCashRegisterCode"
          ).val("");

          $("#scanItemCode, #posServiceCode").combogrid("enable");

          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
            addclass: "pnotify-center",
          });
        }

        Core.unblockUI();
      },
    });
  }

  return;
}

function posTalonReturnCancel2(rowId, isId) {
  var invoiceId = isId == false ? rowId.id : rowId;

  if (invoiceId) {
    PNotify.removeAll();

    if (rowId.booktypeid == "201" || rowId.booktypeid == "203") {
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0047"),
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }

    posDisplayReset(plang.get("POS_0048"), false);
    $("#dialog-talon-dataview").dialog("close");

    $.ajax({
      type: "post",
      url: "mdpos/getInvoiceRender",
      data: { invoiceId: invoiceId },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        if (data.status == "success") {
          vartypeCancel = "typeCancel2";
          $("#returnInvoiceId").val(invoiceId);
          $("#returnTypeInvoice").val("typeCancel");
          $("#returnInvoiceBillId").val(data.billid);
          $("#returnInvoiceNumber").val(data.invoiceNumber);
          $("#returnInvoiceRefNumber").val(data.refNumber);
          $("#returnInvoiceBillType").val(data.billType);
          $("#returnInvoiceBillDate").val(data.vatdate);
          $("#returnInvoiceIsGL").val(rowId.isgl);
          $(".posRemoveItemBtnHeader").hide();
          if (posUseIpTerminal === "1" && $("#isNotUseIpterminal").length) {
            $("#isNotUseIpterminal")
              .closest(".form-group")
              .removeClass("d-none");
          }
          if (rowId.stateregnumber && rowId.orgcashregistercode) {
            $("#returnInvoiceBillStateRegNumber").val(rowId.stateregnumber);
            $("#returnInvoiceBillStorecode").val(rowId.orgstorecode);
            $("#returnInvoiceBillCashRegisterCode").val(
              rowId.orgcashregistercode
            );
          }

          if (isConfigHealthRecipe) {
            $("#returnInvoiceReceiptNumber").val(rowId.receiptnumber);

            if (rowId.receiptnumber) {
              isReceiptNumber = true;
            }
          }

          if (data.hasOwnProperty("isTodayReturn") && data.isTodayReturn == 1) {
            isTodayReturn = true;
          }

          returnBillType = "typeCancel";

          var $tbody = $("#posTable").find("> tbody");

          $tbody.append(data.html);

          posConfigVisibler($tbody);
          Core.initLongInput($tbody);
          Core.initDecimalPlacesInput($tbody);
          Core.initUniform($tbody);

          $tbody.find("button.btn").prop("disabled", true);
          $tbody.find('input[type="text"]').prop("readonly", true);
          $tbody.find(".basket-inputqty-button").each(function () {
            $(this).find("span:eq(0)").hide();
            $(this).find("span:eq(2)").hide();
          });

          var $checkboxs = $tbody.find('input[type="checkbox"]');
          if ($checkboxs.length) {
            $checkboxs.attr({
              "data-isdisabled": "true",
              style: "cursor: not-allowed",
              tabindex: "-1",
            });
            $checkboxs.closest(".checker").addClass("disabled");
          }

          var $dialogName = "dialog-pos-payment";
          $(
            '<div id="' + $dialogName + '" style="display: none"></div>'
          ).appendTo("body");
          var $dialog = $("#" + $dialogName);

          $dialog.empty().append(data.payment);
          Core.initClean($dialog);

          $("#scanItemCode, #posServiceCode").combogrid("disable");

          var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");

          $firstRow.click();

          posFixedHeaderTable();
          posCalcTotal();

          $("#posPaidAmount").autoNumeric(
            "set",
            $("#posPayAmount").autoNumeric("get")
          );
        } else {
          $(
            "#returnInvoiceId, #returnTypeInvoice, #returnInvoiceBillId, #returnInvoiceNumber, #returnInvoiceRefNumber, #returnInvoiceBillType, #returnInvoiceBillDate, #returnInvoiceIsGL, #returnInvoiceBillStateRegNumber, #returnInvoiceBillStorecode, #returnInvoiceBillCashRegisterCode"
          ).val("");

          $("#scanItemCode, #posServiceCode").combogrid("enable");

          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
            addclass: "pnotify-center",
          });
        }

        Core.unblockUI();
      },
    });
  }

  return;
}

function posTalonReturnCancel3(rowId, isId) {
  var invoiceId = isId == false ? rowId.id : rowId;

  if (invoiceId) {
    PNotify.removeAll();

    if (rowId.booktypeid == "201" || rowId.booktypeid == "203") {
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0047"),
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }

    posDisplayReset(plang.get("POS_0048"), false);
    $("#dialog-talon-dataview").dialog("close");

    $.ajax({
      type: "post",
      url: "mdpos/getInvoiceRender",
      data: { invoiceId: invoiceId },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        if (data.status == "success") {
          vartypeCancel = "typeCancel3";
          $("#returnInvoiceId").val(invoiceId);
          $("#returnTypeInvoice").val("typeCancel");
          $("#returnInvoiceBillId").val(data.billid);
          $("#returnInvoiceNumber").val(data.invoiceNumber);
          $("#returnInvoiceRefNumber").val(data.refNumber);
          $("#returnInvoiceBillType").val(data.billType);
          $("#returnInvoiceBillDate").val(data.vatdate);
          $("#returnInvoiceIsGL").val(rowId.isgl);
          $(".posRemoveItemBtnHeader").hide();
          if (posUseIpTerminal === "1" && $("#isNotUseIpterminal").length) {
            $("#isNotUseIpterminal")
              .closest(".form-group")
              .removeClass("d-none");
          }
          if (rowId.stateregnumber && rowId.orgcashregistercode) {
            $("#returnInvoiceBillStateRegNumber").val(rowId.stateregnumber);
            $("#returnInvoiceBillStorecode").val(rowId.orgstorecode);
            $("#returnInvoiceBillCashRegisterCode").val(
              rowId.orgcashregistercode
            );
          }

          if (isConfigHealthRecipe) {
            $("#returnInvoiceReceiptNumber").val(rowId.receiptnumber);

            if (rowId.receiptnumber) {
              isReceiptNumber = true;
            }
          }

          if (data.hasOwnProperty("isTodayReturn") && data.isTodayReturn == 1) {
            isTodayReturn = true;
          }

          returnBillType = "typeCancel";

          var $tbody = $("#posTable").find("> tbody");

          $tbody.append(data.html);

          posConfigVisibler($tbody);
          Core.initLongInput($tbody);
          Core.initDecimalPlacesInput($tbody);
          Core.initUniform($tbody);

          $tbody.find("button.btn").prop("disabled", true);
          $tbody.find('input[type="text"]').prop("readonly", true);
          $tbody.find(".basket-inputqty-button").each(function () {
            $(this).find("span:eq(0)").hide();
            $(this).find("span:eq(2)").hide();
          });

          var $checkboxs = $tbody.find('input[type="checkbox"]');
          if ($checkboxs.length) {
            $checkboxs.attr({
              "data-isdisabled": "true",
              style: "cursor: not-allowed",
              tabindex: "-1",
            });
            $checkboxs.closest(".checker").addClass("disabled");
          }

          var $dialogName = "dialog-pos-payment";
          $(
            '<div id="' + $dialogName + '" style="display: none"></div>'
          ).appendTo("body");
          var $dialog = $("#" + $dialogName);

          $dialog.empty().append(data.payment);
          Core.initClean($dialog);

          $("#scanItemCode, #posServiceCode").combogrid("disable");

          var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");

          $firstRow.click();

          posFixedHeaderTable();
          posCalcTotal();

          $("#posPaidAmount").autoNumeric(
            "set",
            $("#posPayAmount").autoNumeric("get")
          );
        } else {
          $(
            "#returnInvoiceId, #returnTypeInvoice, #returnInvoiceBillId, #returnInvoiceNumber, #returnInvoiceRefNumber, #returnInvoiceBillType, #returnInvoiceBillDate, #returnInvoiceIsGL, #returnInvoiceBillStateRegNumber, #returnInvoiceBillStorecode, #returnInvoiceBillCashRegisterCode"
          ).val("");

          $("#scanItemCode, #posServiceCode").combogrid("enable");

          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
            addclass: "pnotify-center",
          });
        }

        Core.unblockUI();
      },
    });
  }

  return;
}
function posTalonReturnReduce(rowId, isId) {
  var invoiceId = isId == false ? rowId.id : rowId;

  if (invoiceId) {
    PNotify.removeAll();

    if (rowId.booktypeid == "201" || rowId.booktypeid == "203") {
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0047"),
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }

    posDisplayReset(plang.get("POS_0049"));
    $("#dialog-talon-dataview").dialog("close");

    $.ajax({
      type: "post",
      url: "mdpos/getInvoiceRender",
      data: { invoiceId: invoiceId },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        if (data.status == "success") {
          $("#returnInvoiceId").val(invoiceId);
          $("#returnTypeInvoice").val("typeReduce");
          $("#returnInvoiceBillId").val(data.billid);
          $("#returnInvoiceNumber").val(data.invoiceNumber);
          $("#returnInvoiceRefNumber").val(data.refNumber);
          $("#returnInvoiceBillType").val(data.billType);
          $("#returnInvoiceBillDate").val(data.vatdate);
          $("#returnInvoiceIsGL").val(rowId.isgl);

          returnBillType = "typeReduce";

          var $tbody = $("#posTable").find("> tbody");

          $tbody.append(data.html);

          posConfigVisibler($tbody);
          Core.initLongInput($tbody);
          Core.initDecimalPlacesInput($tbody);
          Core.initUniform($tbody);

          $tbody.find("button.btn").prop("disabled", true);
          $tbody
            .find('input[type="text"]:not(.pos-quantity-input)')
            .prop("readonly", true);

          var $checkboxs = $tbody.find('input[type="checkbox"]');
          if ($checkboxs.length) {
            $checkboxs.attr({
              "data-isdisabled": "true",
              style: "cursor: not-allowed",
              tabindex: "-1",
            });
            $checkboxs.closest(".checker").addClass("disabled");
          }

          var $dialogName = "dialog-pos-payment";
          $(
            '<div id="' + $dialogName + '" style="display: none"></div>'
          ).appendTo("body");
          var $dialog = $("#" + $dialogName);

          $dialog.empty().append(data.payment);
          Core.initClean($dialog);

          $("#scanItemCode, #posServiceCode").combogrid("disable");

          var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");

          $firstRow.click();

          posFixedHeaderTable();
          posCalcTotal();
        } else {
          $(
            "#returnInvoiceId, #returnTypeInvoice, #returnInvoiceBillId, #returnInvoiceNumber, #returnInvoiceRefNumber, #returnInvoiceBillType, #returnInvoiceBillDate, #returnInvoiceIsGL"
          ).val("");

          $("#scanItemCode, #posServiceCode").combogrid("enable");

          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
            addclass: "pnotify-center",
          });
        }

        setTimeout(function () {
          $dialog.find(".posUserAmount").removeAttr("readonly");
          $("#posPaidAmount").autoNumeric("set", "");
        }, 100);

        Core.unblockUI();
      },
    });
  }

  return;
}
function posTalonReturnChangeType(rowId, isId) {
  var invoiceId = isId == false ? rowId.id : rowId;

  if (invoiceId) {
    PNotify.removeAll();

    if (rowId.booktypeid == "201" || rowId.booktypeid == "203") {
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0047"),
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }

    posDisplayReset(plang.get("POS_0050"));
    $("#dialog-talon-dataview").dialog("close");

    $.ajax({
      type: "post",
      url: "mdpos/getInvoiceRender",
      data: { invoiceId: invoiceId },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        if (data.status == "success") {
          $("#returnInvoiceId").val(invoiceId);
          $("#returnTypeInvoice").val("typeChange");
          $("#returnInvoiceBillId").val(data.billid);
          $("#returnInvoiceNumber").val(data.invoiceNumber);
          $("#returnInvoiceRefNumber").val(data.refNumber);
          $("#returnInvoiceBillType").val(data.billType);
          $("#returnInvoiceBillDate").val(data.vatdate);
          $("#returnInvoiceBillStateRegNumber").val(data.stateregnumber);
          $("#returnInvoiceBillStorecode").val(data.orgstorecode);
          $("#returnInvoiceBillCashRegisterCode").val(data.orgcashregistercode);

          returnBillType = "typeChange";
          if (posTypeCode == "3") {
            $(".pos-card-view").find(".grid-card-itemgroup").hide();
          }

          var $tbody = $("#posTable").find("> tbody");

          $tbody.append(data.html);

          posConfigVisibler($tbody);
          Core.initLongInput($tbody);
          Core.initDecimalPlacesInput($tbody);
          Core.initUniform($tbody);

          $tbody.find("button.btn").prop("disabled", true);
          $tbody.find('input[type="text"]').prop("readonly", true);
          $tbody.find(".basket-inputqty-button").each(function () {
            $(this).find("span:eq(0)").hide();
            $(this).find("span:eq(2)").hide();
          });

          var $checkboxs = $tbody.find('input[type="checkbox"]');
          if ($checkboxs.length) {
            $checkboxs.attr({
              "data-isdisabled": "true",
              style: "cursor: not-allowed",
              tabindex: "-1",
            });
            $checkboxs.closest(".checker").addClass("disabled");
          }

          var $dialogName = "dialog-pos-payment";
          $(
            '<div id="' + $dialogName + '" style="display: none"></div>'
          ).appendTo("body");
          var $dialog = $("#" + $dialogName);

          $dialog.empty().append(data.payment);
          Core.initClean($dialog);

          $("#scanItemCode, #posServiceCode").combogrid("disable");

          var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");

          $firstRow.click();

          posFixedHeaderTable();
          posCalcTotal();
        } else {
          $(
            "#returnInvoiceId, #returnTypeInvoice, #returnInvoiceBillId, #returnInvoiceNumber, #returnInvoiceRefNumber, #returnInvoiceBillType, #returnInvoiceBillDate, #returnInvoiceIsGL, #returnInvoiceBillStateRegNumber, #returnInvoiceBillStorecode, #returnInvoiceBillCashRegisterCode"
          ).val("");

          $("#scanItemCode, #posServiceCode").combogrid("enable");

          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
            addclass: "pnotify-center",
          });
        }

        Core.unblockUI();
      },
    });
  }

  return;
}

function posReceiptNumberExpired(data) {
  var $dialogName = "dialog-receiptNumberExpired";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $dialog.empty().append(data.html);

  $dialog.dialog({
    cache: false,
    resizable: true,
    bgiframe: true,
    autoOpen: false,
    title: data.title,
    width: 900,
    height: "auto",
    modal: true,
    closeOnEscape: isCloseOnEscape,
    close: function () {
      $dialog.empty().dialog("destroy").remove();
      $("#posReceiptNumber").val("").focus();
      $("#posReceiptRegNumber").val("");
    },
    buttons: [
      {
        text: data.history_btn,
        class: "btn btn-sm purple-plum float-left",
        click: function () {
          posReceiptHistoryList(data.regNumber);
        },
      },
      {
        text: data.close_btn,
        class: "btn btn-sm blue-hoki",
        click: function () {
          $dialog.dialog("close");
        },
      },
    ],
  });
  $dialog.dialog("open");

  Core.unblockUI();
}
function posReceiptNumberFill(data) {
  posDisplayReset("");

  isReceiptNumber = true;
  receiptRegNumber = data.regNumber;
  drugPrescription = data.saveJson;
  tbltCount = data.tbltCount;

  var $dialogName = "dialog-receiptNumberFill";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);
  var tbltIds = "";

  if (drugPrescription && drugPrescription.hasOwnProperty("receiptDetails")) {
    var receiptDetails = drugPrescription.receiptDetails;
    var receiptDetailsLength = receiptDetails.length;
    var i = 0;
    tbltIds = "";

    for (i; i < receiptDetailsLength; i++) {
      tbltIds += receiptDetails[i]["tbltId"] + ",";
    }

    tbltIds = rtrim(tbltIds, ",");
  }

  isItemSearchEmptyFocus = true;
  posItemCombogridList(tbltIds);

  $dialog.empty().append(data.html);

  $dialog.dialog({
    cache: false,
    resizable: true,
    bgiframe: true,
    autoOpen: false,
    title: data.title,
    width: 900,
    height: "auto",
    modal: false,
    closeOnEscape: isCloseOnEscape,
    position: { my: "left bottom", at: "left+257 bottom-146", of: window },
    close: function () {
      posDisplayReset("");

      $dialog.empty().dialog("destroy").remove();

      $("#posReceiptNumber").val("").focus();
      $("#posReceiptRegNumber").val("");
    },
    buttons: [
      {
        text: data.history_btn,
        class: "btn btn-sm purple-plum float-left",
        click: function () {
          posReceiptHistoryList(data.regNumber);
        },
      },
      {
        text: data.close_btn,
        class: "btn btn-sm blue-hoki",
        click: function () {
          $dialog.dialog("close");
        },
      },
    ],
  });
  $dialog.dialog("open");

  Core.unblockUI();
}
function posReceivableAmountsReset() {
  isReceiptNumber = false;
  tbltCount = 0;
  drugPrescription = [];

  $('[data-field-name="unitReceivable"]').autoNumeric("set", "");
  $(
    "td.pos-amount-receivable, td.pos-amount-receivable-from-person"
  ).autoNumeric("set", 0);
  return;
}
function posReceiptHistoryList(regNumber) {
  var $dialogName = "dialog-receipt-history";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdobject/dataValueViewer",
    data: {
      viewType: "detail",
      dataGridDefaultHeight: $(window).height() - 155,
      uriParams: '{"patientRegNo": "' + regNumber + '"}',
      metaDataId: "1524584235700",
      ignorePermission: 1,
    },
    beforeSend: function () {
      Core.blockUI({
        animate: true,
      });
    },
    success: function (dataHtml) {
      $dialog
        .empty()
        .append(
          '<div class="row" id="object-value-list-1524584235700">' +
          dataHtml +
          "</div>"
        );
      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: plang.get("POS_0051"),
          width: 1000,
          height: 600,
          modal: true,
          open: function () {
            $dialog
              .find(".top-sidebar-content:eq(0)")
              .attr("style", "padding-left: 15px !important");
            $dialog.find('a[onclick*="toQuickMenu"]').remove();
          },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("close_btn"),
              class: "btn blue-madison btn-sm",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
          maximize: function () {
            //$('#objectdatagrid-1524584235700').datagrid('resize');
          },
          restore: function () {
            $("#objectdatagrid-1524584235700").datagrid("resize");
          },
        });

      $dialog.dialog("open");
      $dialog.dialogExtend("maximize");

      $dialog.bind("dialogextendminimize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .addClass("display-none");
      });
      $dialog.bind("dialogextendmaximize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
      $dialog.bind("dialogextendrestore", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  });
}

function posHotKeys() {
  var $dialogName = "dialog-hotkeys";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdpos/hotkeys",
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data.html);
      $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: 900,
        height: "auto",
        modal: true,
        closeOnEscape: isCloseOnEscape,
        close: function () {
          enableScrolling();
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [
          {
            text: data.close_btn,
            class: "btn btn-sm blue-hoki",
            click: function () {
              $dialog.dialog("close");
            },
          },
        ],
      });
      $dialog.dialog("open");
      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  });
}

function posConfigVisibler(elem) {
  if (!isConfigDelivery) {
    elem.find('[data-config-column="delivery"]').addClass("hide");
  }
  if (!isConfigSalesPerson) {
    elem.find('[data-config-column="salesperson"]').addClass("hide");
  }
  if (!isConfigSerialNumber) {
    elem.find('[data-config-column="serialnumber"]').addClass("hide");
  }
  if (!isConfigPaymentUnitReceivable) {
    elem.find('[data-config-column="unitreceivable"]').addClass("hide");
  }
  $(".pos-amount-change").autoNumeric("set", 0);

  return;
}
function posPageLoadEndVisibler() {
  $(".pos-center, .pos-right, .pos-cardcenter, .pos-cardright").css({
    display: "",
  });
  return;
}

function posItemFillBySerialNumber(row) {
  var $dialogName = "dialog-item-serials";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdpos/itemSerialNumberList",
    data: row,
    dataType: "json",
    success: function (data) {
      $dialog.empty().append(data.html);
      $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: 500,
        height: "auto",
        modal: true,
        closeOnEscape: isCloseOnEscape,
        open: function () {
          disableScrolling();
          var $thisDialogButton = $(this).parent();
          $thisDialogButton.on("keydown", function (e) {
            var keyCode = e.keyCode ? e.keyCode : e.which;

            if (keyCode == 38) {
              /* up */

              var $thisButton = $thisDialogButton.find(
                "a.pos-item-serial-row-active"
              );

              if ($thisButton.length) {
                var $thisParent = $thisButton.prevAll(
                  ".pos-item-serial-row:eq(0)"
                );

                if ($thisParent.length) {
                  $thisDialogButton
                    .find("a.pos-item-serial-row-active")
                    .removeClass("pos-item-serial-row-active");
                  $thisParent.addClass("pos-item-serial-row-active").focus();
                }
              } else {
                $thisDialogButton
                  .find("a.pos-item-serial-row:eq(0)")
                  .addClass("pos-item-serial-row-active")
                  .focus();
              }
            } else if (keyCode == 40) {
              /* down */
              var $thisButton = $thisDialogButton.find(
                "a.pos-item-serial-row-active"
              ),
                $thisParent = $thisButton.nextAll(
                  "a.pos-item-serial-row:eq(0)"
                );

              if ($thisParent.length) {
                $thisDialogButton
                  .find("a.pos-item-serial-row-active")
                  .removeClass("pos-item-serial-row-active");
                $thisParent.addClass("pos-item-serial-row-active").focus();
              }
            }
          });
        },
        close: function () {
          enableScrolling();
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [
          {
            text: data.close_btn,
            class: "btn btn-sm blue-hoki",
            click: function () {
              $dialog.dialog("close");
            },
          },
        ],
      });
      $dialog.dialog("open");
      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  });
}

function posSectionFillBySerialNumber(row) {
  var $dialogName = "dialog-item-serials";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdpos/sectionSerialNumberList",
    data: row,
    dataType: "json",
    success: function (data) {
      $dialog.empty().append(data.html);
      $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: 500,
        height: "auto",
        modal: true,
        closeOnEscape: isCloseOnEscape,
        open: function () {
          disableScrolling();
          var $thisDialogButton = $(this).parent();
          $thisDialogButton.on("keydown", function (e) {
            var keyCode = e.keyCode ? e.keyCode : e.which;

            if (keyCode == 38) {
              /* up */

              var $thisButton = $thisDialogButton.find(
                "a.pos-item-serial-row-active"
              );

              if ($thisButton.length) {
                var $thisParent = $thisButton.prevAll(
                  ".pos-item-serial-row:eq(0)"
                );

                if ($thisParent.length) {
                  $thisDialogButton
                    .find("a.pos-item-serial-row-active")
                    .removeClass("pos-item-serial-row-active");
                  $thisParent.addClass("pos-item-serial-row-active").focus();
                }
              } else {
                $thisDialogButton
                  .find("a.pos-item-serial-row:eq(0)")
                  .addClass("pos-item-serial-row-active")
                  .focus();
              }
            } else if (keyCode == 40) {
              /* down */
              var $thisButton = $thisDialogButton.find(
                "a.pos-item-serial-row-active"
              ),
                $thisParent = $thisButton.nextAll(
                  "a.pos-item-serial-row:eq(0)"
                );

              if ($thisParent.length) {
                $thisDialogButton
                  .find("a.pos-item-serial-row-active")
                  .removeClass("pos-item-serial-row-active");
                $thisParent.addClass("pos-item-serial-row-active").focus();
              }
            }
          });
        },
        close: function () {
          enableScrolling();
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [
          {
            text: data.close_btn,
            class: "btn btn-sm blue-hoki",
            click: function () {
              $dialog.dialog("close");
            },
          },
        ],
      });
      $dialog.dialog("open");
      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  });
}

function posFillItemRowBySerialNumber(elem) {
  $("#dialog-item-serials").dialog("close");

  var $row = $(elem),
    rowData = JSON.parse($row.attr("data-row")),
    serialNumber = "",
    unitReceivable = "",
    maxPrice = "",
    isOperating = "",
    endQty = "",
    salesPersonId = "",
    itemKeyId = "",
    quantity = 1,
    isDiscount = "",
    discountPercent = "",
    discountAmount = "",
    unitDiscount = "",
    totalDiscount = "",
    isCalcRow = false,
    sectionId = "",
    customerId2 = "",
    registerNo = "",
    internalId = "",
    isown = "",
    discountId = "",
    copperCartDiscount = 0,
    selectedCusId = $('input[name="empCustomerId"]').length
      ? $('input[name="empCustomerId"]').val()
      : "",
    guestName = $("#guestName").length ? $("#guestName").val().trim() : "",
    $tbody = $("#posTable").find("> tbody");
  var renderType = $(".pos-card-layout").length ? "card" : "";
  var addClassName = renderType === "card" ? "d-none" : "";

  if (Number(rowData.endqty) > 0 && Number(rowData.endqty) < 1) {
    quantity = Number(rowData.endqty);
  }

  if (rowData.hasOwnProperty("merchantid")) {
    customerId2 = rowData.merchantid;
  }
  if (rowData.hasOwnProperty("serialnumber")) {
    serialNumber = rowData.serialnumber;
  }
  if (rowData.hasOwnProperty("itemkeyid")) {
    itemKeyId = rowData.itemkeyid;
  }
  if (rowData.hasOwnProperty("internalid")) {
    internalId = rowData.internalid;
  }
  if (rowData.hasOwnProperty("isoperating")) {
    isOperating = rowData.isoperating;
  }
  if (rowData.hasOwnProperty("discountid")) {
    discountId = rowData.discountid;
  }
  if (rowData.hasOwnProperty("stateregnumber")) {
    registerNo = rowData.stateregnumber;
  }
  if (rowData.hasOwnProperty("sectionid")) {
    sectionId = rowData.sectionid;
  }
  if (rowData.hasOwnProperty("receivableamount")) {
    unitReceivable = rowData.receivableamount;
    maxPrice = rowData.maxprice;
  }

  var isIgnoreEndQty =
    rowData.hasOwnProperty("isignoreendqty") && rowData.isignoreendqty == "1"
      ? true
      : false;
  var concatItemName = (rowData.itemcode + "" + serialNumber).toLowerCase();

  if (rowData.hasOwnProperty("endqty")) {
    endQty = Number(rowData.endqty);
  }
  if (!isConfigItemCheckEndQtyInvoice) {
    isIgnoreEndQty = true;
  }
  if (isConfigItemCheckEndQtyMsg && rowData.hasOwnProperty("endqty")) {
    endQty = isIgnoreEndQty ? 1000 : Number(rowData.endqty);
  }

  if (isConfigItemCheckDuplicate) {
    var $addedRow = $tbody.find('tr[data-item-code="' + concatItemName + '"]');

    if (posTypeCode == "3") {
      $addedRow = $tbody.find(
        'tr[data-item-id-customer-id="' + rowData.id + "_" + guestName + '"]'
      );
      salesPersonId = $("#posRestWaiterId").val();
    }

    if ($addedRow.length) {
      var alreadyEndQty = isIgnoreEndQty
        ? 1000
        : Number($addedRow.find('input[data-field-name="endQty"]').val());
      var qty = Number(
        $addedRow.find("input.pos-quantity-input").autoNumeric("get")
      );
      var addedQty = qty + 1;

      if (alreadyEndQty >= addedQty) {
        $addedRow.find("input.pos-quantity-input").autoNumeric("set", addedQty);
        posCalcRow($addedRow);
      } else {
        new PNotify({
          title: "Warning",
          text: plang.getVar("POS_0010", { endQty: alreadyEndQty }),
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
      }

      $tbody.find("tr.pos-selected-row").removeClass("pos-selected-row");
      $addedRow.addClass("pos-selected-row");
      $(".pos-item-combogrid-cell").find("input.textbox-text").val("").focus();
      callback("");

      Core.unblockUI();
      return;
    }
  }

  if (isConfigItemCheckEndQtyMsg && rowData.hasOwnProperty("endqty")) {
    var msgEndQty = isIgnoreEndQty ? 1000 : Number(rowData.endqty);

    if (msgEndQty <= 0) {
      PNotify.removeAll();
      new PNotify({
        title: plang.get("POS_0011"),
        text: plang.getVar("POS_0012", { endQty: msgEndQty }),
        type: "error",
        sticker: false,
        addclass: "pnotify-center",
      });
      Core.unblockUI();
      $(".pos-item-combogrid-cell").find("input.textbox-text").val("").focus();
      return;
    } else if (msgEndQty <= 5) {
      PNotify.removeAll();
      new PNotify({
        title: plang.get("POS_0011"),
        text: plang.getVar("POS_0013", { endQty: msgEndQty }),
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
    }
  }

  var itemName = rowData.itemname.trim(),
    displayPrice = rowData.saleprice,
    itemAttr = "",
    printCopies = "";

  if (
    rowData.hasOwnProperty("discountpercent") &&
    Number(rowData.discountpercent) > 0 &&
    discountId != "100000000001"
  ) {
    var discountSalePrice = Number(rowData.saleprice);

    discountPercent = rowData.discountpercent;
    unitDiscount = (Number(rowData.discountpercent) / 100) * discountSalePrice;
    discountAmount = discountSalePrice - unitDiscount;
    isDiscount = "1";
    totalDiscount = quantity * unitDiscount;
  } else if (
    rowData.hasOwnProperty("discountpercent") &&
    Number(rowData.discountpercent) > 0 &&
    discountId == "100000000001"
  ) {
    copperCartDiscount = rowData.discountpercent;
  } else if (
    rowData.hasOwnProperty("discountamount") &&
    Number(rowData.discountamount) > 0
  ) {
    var discountSalePrice = Number(rowData.saleprice);

    discountPercent = 0;
    unitDiscount = Number(rowData.discountamount);
    discountAmount = discountSalePrice - unitDiscount;
    isDiscount = "1";
    totalDiscount = quantity * unitDiscount;
  }

  if (
    rowData.hasOwnProperty("calcbonuspercent") &&
    Number(rowData.calcbonuspercent) > 0
  ) {
    rowData.calcbonusamount =
      (Number(rowData.calcbonuspercent) / 100) * Number(rowData.saleprice);
  }

  if (rowData.hasOwnProperty("discountdtl") && rowData.discountdtl) {
    posDiscountFillByItemCode(rowData.discountdtl);
  }

  if (
    rowData.hasOwnProperty("packageid") &&
    rowData.packageid != "" &&
    Number(rowData.packageprice) > 0 &&
    (Number(rowData.hdrpackageqty) > 0 || Number(rowData.dtlpackageqty) > 0)
  ) {
    itemAttr =
      'data-packageid="' +
      rowData.packageid +
      '" data-packageprice="' +
      rowData.packageprice +
      '" data-hdrpackageqty="' +
      rowData.hdrpackageqty +
      '" data-dtlpackageqty="' +
      rowData.dtlpackageqty +
      '"';
  }

  if (rowData.hasOwnProperty("printcopies")) {
    printCopies = rowData.printcopies;
  }

  var accompanyItemsDataJson = "";
  if (rowData.hasOwnProperty("promproductdtl") && rowData.promproductdtl) {
    accompanyItemsDataJson = encodeURIComponent(
      JSON.stringify(rowData.promproductdtl)
    );
  }

  var accompanyServiceDataJson = "";
  if (
    rowData.hasOwnProperty("mesjobmaterialdtl") &&
    rowData.mesjobmaterialdtl
  ) {
    accompanyServiceDataJson = encodeURIComponent(
      JSON.stringify(rowData.mesjobmaterialdtl)
    );
  }

  var rowSalePrice = discountAmount === "" ? rowData.saleprice : discountAmount;
  if (!rowData.hasOwnProperty("ishideinvoiceqty")) {
    rowData.ishideinvoiceqty = "";
  }
  var iseditprice = typeof rowData.iseditprice !== "undefined" ? rowData.iseditprice : "";

  var rowHtml =
    '<tr data-item-id="' +
    rowData.id +
    '" data-item-code="' +
    concatItemName +
    '" ' +
    itemAttr +
    '" data-item-id-customer-id="' + rowData.id + "_" + (selectedCusId ? selectedCusId : guestName) + '" data-customerid="' + (selectedCusId ? selectedCusId : guestName) + '">' +
    '<td data-field-name="gift" class="text-center ' +
    addClassName +
    '"></td>' +
    '<td data-field-name="itemCode" class="text-left ' +
    addClassName +
    '" style="font-size: 14px;">' +
    rowData.itemcode +
    "</td>" +
    '<td data-field-name="serialNumber" data-config-column="serialnumber" class="text-left">' +
    serialNumber +
    "</td>" +
    '<td data-field-name="itemName" class="text-left" title="' +
    itemName +
    '" style="font-size: 14px; line-height: 15px;">' +
    '<input type="hidden" name="itemId[]" value="' +
    rowData.id +
    '">' +
    '<input type="hidden" name="itemCode[]" value="' +
    rowData.itemcode +
    '">' +
    '<input type="hidden" name="customerId[]" value="' +
    selectedCusId +
    '">' +
    '<input type="hidden" name="salesOrderId[]" value="">' +
    '<input type="hidden" name="customerIdSaved[]" value="">' +
    '<input type="hidden" name="isSavedOrder[]" value="">' +
    '<input type="hidden" name="guestName[]" value="' +
    guestName +
    '">' +
    '<input type="hidden" name="refSalePrice[]" value="' +
    (rowData.refsaleprice ? rowData.refsaleprice : "") +
    '">' +
    '<input type="hidden" name="itemName[]" value="' +
    itemName +
    '">' +
    '<input type="hidden" name="salePrice[]" value="' +
    rowData.saleprice +
    '">' +
    '<input type="hidden" name="totalPrice[]" value="' +
    rowData.saleprice +
    '">' +
    '<input type="hidden" name="measureId[]" value="' +
    rowData.measureid +
    '">' +
    '<input type="hidden" name="measureCode[]" value="' +
    rowData.measurecode +
    '">' +
    '<input type="hidden" name="barCode[]" value="' +
    rowData.barcode +
    '">' +
    '<input type="hidden" name="isVat[]" value="' +
    rowData.isvat +
    '">' +
    '<input type="hidden" name="isOperating[]" value="' +
    isOperating +
    '">' +
    '<input type="hidden" name="vatPercent[]" value="' +
    rowData.vatpercent +
    '">' +
    '<input type="hidden" name="vatPrice[]" value="">' +
    '<input type="hidden" name="noVatPrice[]" value="' +
    bpRound(rowData.novatprice) +
    '">' +
    '<input type="hidden" name="isCityTax[]" value="' +
    rowData.iscitytax +
    '">' +
    '<input type="hidden" name="lineTotalVat[]" value="0">' +
    '<input type="hidden" name="cityTax[]" value="">' +
    '<input type="hidden" name="cityTaxPercent[]" value="' +
    rowData.citytaxpercent +
    '">' +
    '<input type="hidden" name="lineTotalCityTax[]" value="0">' +
    '<input type="hidden" name="discountPercent[]" value="' +
    discountPercent +
    '">' +
    '<input type="hidden" name="discountAmount[]" value="' +
    discountAmount +
    '">' +
    '<input type="hidden" name="unitDiscount[]" value="' +
    unitDiscount +
    '">' +
    '<input type="hidden" name="isDiscount[]" value="' +
    isDiscount +
    '">' +
    '<input type="hidden" name="totalDiscount[]" value="' +
    totalDiscount +
    '">' +
    '<input type="hidden" name="storeWarehouseId[]" value="' +
    rowData.storewarehouseid +
    '">' +
    '<input type="hidden" name="deliveryWarehouseId[]" value="' +
    rowData.deliverywarehouseid +
    '">' +
    '<input type="hidden" name="isJob[]">' +
    '<input type="hidden" name="giftJson[]">' +
    '<input type="hidden" name="serialNumber[]" value="' +
    serialNumber +
    '">' +
    '<input type="hidden" name="itemKeyId[]" value="' +
    itemKeyId +
    '">' +
    '<input type="hidden" name="sectionId[]" value="' +
    sectionId +
    '">' +
    '<input type="hidden" name="unitReceivable[]" value="' +
    unitReceivable +
    '">' +
    '<input type="hidden" name="maxPrice[]" value="' +
    maxPrice +
    '">' +
    '<input type="hidden" name="printCopies[]" value="' +
    printCopies +
    '">' +
    '<input type="hidden" name="discountEmployeeId[]">' +
    '<input type="hidden" name="orgCashRegisterCode[]">' +
    '<input type="hidden" name="orgStoreCode[]">' +
    '<input type="hidden" name="orgPosHeaderName[]">' +
    '<input type="hidden" name="orgPosLogo[]">' +
    '<input type="hidden" name="storeId[]">' +
    '<input type="hidden" name="salesorderdetailid[]">' +
    '<input type="hidden" name="editPriceEmployeeId[]">' +
    '<input type="hidden" name="cashRegisterId[]">' +
    '<input type="hidden" name="discountTypeId[]">' +
    '<input type="hidden" name="salesPersonId[]" value="' + salesPersonId + '">' +
    '<input type="hidden" name="discountDescription[]">' +
    '<input type="hidden" data-field-name="endQty" value="' +
    endQty +
    '">' +
    '<input type="hidden" data-field-name="discountQty" value="10000000">' +
    '<input type="hidden" name="stateRegNumber[]" value="' +
    registerNo +
    '">' +
    '<input type="hidden" name="merchantId[]" value="' +
    customerId2 +
    '">' +
    '<input type="hidden" name="internalId[]" value="' +
    internalId +
    '">' +
    '<input type="hidden" data-name="accompanyItems" value="' +
    accompanyItemsDataJson +
    '">' +
    '<input type="hidden" data-name="isServiceCharge" value="">' +
    '<input type="hidden" data-name="accompanyServices" value="' +
    accompanyServiceDataJson +
    '">' +
    '<input type="hidden" name="discountId[]" data-name="discountId" value="' +
    discountId +
    '">' +
    '<input type="hidden" name="lineTotalBonusAmount[]" value="' +
    (typeof rowData.calcbonusamount !== "undefined"
      ? rowData.calcbonusamount
      : "") +
    '">' +
    '<input type="hidden" name="unitBonusAmount[]" value="' +
    (typeof rowData.calcbonusamount !== "undefined"
      ? rowData.calcbonusamount
      : "") +
    '">' +
    '<input type="hidden" data-name="copperCartDiscount" value="' +
    copperCartDiscount +
    '">' +
    '<input type="hidden" data-name="isCalcUPoint" name="isCalcUPoint[]" value="' +
    (typeof rowData.iscalcupoint !== "undefined" ? rowData.iscalcupoint : "") +
    '">' +
    '<input type="hidden" data-name="calcBonusPercent" name="unitBonusPercent[]" value="' +
    (typeof rowData.calcbonuspercent !== "undefined"
      ? rowData.calcbonuspercent
      : "") +
    '">' +
    '<input type="hidden" data-name="isNotUseBonusCard" name="isNotUseBonusCard[]" value="' +
    (typeof rowData.isnotusebonuscard !== "undefined"
      ? rowData.isnotusebonuscard
      : "") +
    '">' +
    '<input type="hidden" data-name="isFood" value="' +
    (typeof rowData.isfood !== "undefined" ? rowData.isfood : "") +
    '">' +
    (renderType === "card"
      ? '<div class="item-code-mini">' +
      rowData.itemcode +
      "</div>" +
      '<div class="mt3">' +
      itemName +
      "</div>"
      : itemName) +
    "</td>" +
    '<td data-field-name="salePrice" class="text-right bigdecimalInit">' +
    (typeof posIsEditBasketPrice === "undefined" && iseditprice !== "1"
      ? rowData.ishideinvoiceqty === "1"
        ? ""
        : rowSalePrice
      : '<input type="text" name="salePriceInput[]" class="pos-saleprice-input bigdecimalInit" value="' +
      displayPrice +
      '" data-mdec="3">') +
    "</td>" +
    '<td data-field-name="unitReceivable" data-config-column="unitreceivable" class="text-right bigdecimalInit ' +
    addClassName +
    '">' +
    unitReceivable +
    "</td>" +
    '<td data-field-name="quantity" style="height:28.8px;" class="pos-quantity-cell text-right">' +
    '<script type="text/template" data-template="giftrow"></script>' +
    '<script type="text/template" data-template="matrixgiftrow"></script>' +
    (renderType === "card"
      ? rowData.ishideinvoiceqty != "1"
        ? '<a href="javascript:;" class="list-icons-item basket-inputqty-button d-flex justify-content-between" title="">' +
        '<span><i class="icon-minus3 mr5"></i></span>' +
        '<span><input type="text" name="quantity[]" class="pos-quantity-input bigdecimalInit ignorebarcode" data-oldvalue="' +
        quantity +
        '" autocomplete="off" value="' +
        quantity +
        '" data-mdec="3"></span>' +
        '<span><i class="icon-plus3 ml5"></i></span>' +
        "</a>"
        : '<input type="hidden" name="quantity[]" class="pos-quantity-input bigdecimalInit ignorebarcode" data-oldvalue="' +
        quantity +
        '" autocomplete="off" value="' +
        quantity +
        '" data-mdec="3">'
      : '<input type="' +
      (rowData.ishideinvoiceqty === "1" ? "hidden" : "text") +
      '" name="quantity[]" class="pos-quantity-input bigdecimalInit ignorebarcode" data-oldvalue="' +
      quantity +
      '" autocomplete="off" value="' +
      quantity +
      '" data-mdec="3">') +
    "</td>" +
    '<td data-field-name="totalPrice" class="text-right bigdecimalInit">' +
    rowSalePrice +
    "</td>" +
    '<td data-field-name="delivery" class="text-center" data-config-column="delivery">' +
    '<input type="hidden" name="isDelivery[]" value="0">' +
    '<input type="checkbox" class="isDelivery" value="1" title="' +
    plang.get("POS_0014") +
    '">' +
    "</td>" +
    '<td data-field-name="salesperson" class="text-center" data-config-column="salesperson">' +
    '<div class="meta-autocomplete-wrap" data-section-path="employeeId">' +
    '<div class="input-group double-between-input">' +
    '<input type="hidden" name="employeeId[]" id="employeeId_valueField" data-path="employeeId" class="popupInit">' +
    '<input type="text" name="employeeId_displayField[]" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="employeeId" id="employeeId_displayField" data-processid="1454315883636" data-lookupid="1522404331251" placeholder="' +
    plang.get("code_search") +
    '" autocomplete="off">' +
    '<span class="input-group-btn">' +
    "<button type=\"button\" class=\"btn default btn-bordered form-control-sm mr0\" onclick=\"dataViewSelectableGrid('employeeId', '1454315883636', '1522404331251', 'single', 'employeeId', this);\" tabindex=\"-1\"><i class=\"fa fa-search\"></i></button>" +
    "</span>" +
    '<span class="input-group-btn">' +
    '<input type="text" name="employeeId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="employeeId" id="employeeId_nameField" data-processid="1454315883636" data-lookupid="1522404331251" placeholder="' +
    plang.get("name_search") +
    '" tabindex="-1" autocomplete="off">' +
    "</span>" +
    "</div>" +
    "</div>" +
    "</td>" +
    "</tr>";

  // if (data.gift != '' && data.gift != null) {
  //     rowHtml += '<tr data-item-gift-row="true" style="display: none">'+
  //         '<td colspan="2"></td>'+
  //         '<td colspan="6" data-item-gift-cell="true"></td>'+
  //     '</tr>';
  // }

  if (posTypeCode == "3" && selectedCusId) {
    if ($tbody.find('tr[data-customerid="' + selectedCusId + '"]').length) {
      $tbody
        .find('tr[data-customerid="' + selectedCusId + '"]:last')
        .after(rowHtml);
      var $lastRow = $tbody.find(
        'tr[data-customerid="' + selectedCusId + '"]:last'
      );
    } else {
      $tbody.append(
        '<tr style="height: 20px;" class="multi-customer-group" data-customerid="' +
        selectedCusId +
        '"><td colspan="4" style="font-size: 12px;background-color: #ffcc0099;">' +
        // guestName +
        $('input[name="empCustomerId_displayField"]').val() +
        "-" +
        $('input[name="empCustomerId_nameField"]').val() + '<a href="javascript:;" style="background-color: #e4b700;color: #333;padding: 4px 3px 3px 4px;margin-left: 13px; display:none" onclick="posCustomerList(this);">харилцагч өөрчлөх</a>' +
        '</td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td></tr>'
      );
      $tbody.append(rowHtml);
      var $lastRow = $tbody.find("tr[data-item-id]:last");
    }
  } else if (posTypeCode == "3") {
    if ($("#guestName").val()) {
      if ($tbody.find('tr.multi-customer-group').length === 2 && $('#posLocationId').val() == '') {
        PNotify.removeAll();
        new PNotify({
          title: 'Анхааруулга',
          text: 'Ширээ сонгоогүй үед зөвхөн нэг харилцагчийн бараа бичнэ',
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
        Core.unblockUI();
        return;
      }
      if ($tbody.find('tr[data-customerid="' + $("#guestName").val() + '"]').length) {
        $tbody
          .find('tr[data-customerid="' + $("#guestName").val() + '"]:last')
          .after(rowHtml);
        var $lastRow = $tbody.find(
          'tr[data-customerid="' + $("#guestName").val() + '"]:last'
        );
      } else {
        $tbody.append(
          '<tr style="height: 20px;" class="multi-customer-group" data-customerid="' + $("#guestName").val() + '"><td colspan="4" style="font-size: 12px;background-color: #ffcc0099;">' + $("#guestName").val() + ' (guest)</td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td></tr>'
        );
        $tbody.append(rowHtml);
        var $lastRow = $tbody.find("tr[data-item-id]:last");
      }
    } else if ($tbody.find('tr[data-customerid=""]').length) {
      $tbody.find('tr[data-customerid=""]:last').after(rowHtml);
      var $lastRow = $tbody.find('tr[data-customerid=""]:last');
    } else {
      if ($('#posLocationId').val() == '') {
        PNotify.removeAll();
        new PNotify({
          title: 'Анхааруулга',
          text: 'Харилцагч эсвэл Зочин заавал сонгоно уу!',
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
        Core.unblockUI();
        return;
      }
      $tbody.append(
        '<tr style="height: 20px;" class="multi-customer-group" data-customerid=""><td colspan="4" style="font-size: 12px;background-color: #ffcc0099;">харилцагч сонгоогүй</td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td></tr>'
      );
      $tbody.append(rowHtml);
      var $lastRow = $tbody.find("tr[data-item-id]:last");
    }
  } else {
    $tbody.append(rowHtml);
    var $lastRow = $tbody.find("tr[data-item-id]:last");
  }

  if (isConfigPaymentUnitReceivable && isReceiptNumber) {
    var lastSalePrice = Number(rowData.saleprice),
      lastMaxPrice = Number(maxPrice);

    if (lastSalePrice > lastMaxPrice) {
      $lastRow.find('input[name="salePrice[]"]').val(lastMaxPrice);
      $lastRow.find('input[name="totalPrice[]"]').val(lastMaxPrice);

      $lastRow.find('input[name="vatPrice[]"]').val(lastMaxPrice);
      $lastRow
        .find('input[name="noVatPrice[]"]')
        .val(bpRound(lastMaxPrice / 1.1));

      $lastRow.find('td[data-field-name="salePrice"]').text(lastMaxPrice);
      $lastRow.find('td[data-field-name="totalPrice"]').text(lastMaxPrice);
    } else if (lastSalePrice < lastMaxPrice) {
      $lastRow.find('input[name="salePrice[]"]').val(lastSalePrice);
      $lastRow.find('input[name="totalPrice[]"]').val(lastSalePrice);

      $lastRow.find('input[name="vatPrice[]"]').val(lastSalePrice);
      $lastRow
        .find('input[name="noVatPrice[]"]')
        .val(bpRound(lastSalePrice / 1.1));

      $lastRow.find('td[data-field-name="salePrice"]').text(lastSalePrice);
      $lastRow.find('td[data-field-name="totalPrice"]').text(lastSalePrice);
    }
  }

  posConfigVisibler($lastRow);
  Core.initLongInput($lastRow);
  Core.initUniform($lastRow);

  if (isCalcRow) {
    Core.initDecimalPlacesInput($lastRow, 3);
  } else {
    Core.initDecimalPlacesInput($lastRow);
  }

  posCalcRow($lastRow);

  $lastRow.click();

  posItemPackageAction($tbody);
  posTableFillLastAction($tbody);

  posChooseItemGift($lastRow);

  return;
}

function posItemEndQtyInfo(itemId, message) {
  var $dialogName = "dialog-item-info";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $dialog.empty().append(message);
  $dialog.dialog({
    cache: false,
    resizable: true,
    bgiframe: true,
    autoOpen: false,
    title: plang.get("POS_0052"),
    width: 500,
    height: "auto",
    modal: true,
    closeOnEscape: isCloseOnEscape,
    close: function () {
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: [
      {
        text: plang.get("POS_0053"),
        class: "btn btn-sm purple-plum float-left",
        click: function () {
          posItemEndQtyList(itemId);
        },
      },
      {
        text: plang.get("close_btn"),
        class: "btn btn-sm blue-hoki",
        click: function () {
          $dialog.dialog("close");
        },
      },
    ],
  });
  $dialog.dialog("open");

  return;
}
function posItemEndQtyList(itemId) {
  var $dialogName = "dialog-item-endqty-list";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdobject/dataValueViewer",
    data: {
      viewType: "detail",
      dataGridDefaultHeight: $(window).height() - 180,
      uriParams: '{"itemId": "' + itemId + '"}',
      metaDataId: "1525387875570",
      ignorePermission: 1,
    },
    beforeSend: function () {
      Core.blockUI({
        animate: true,
      });
    },
    success: function (dataHtml) {
      $dialog
        .empty()
        .append(
          '<div class="row" id="object-value-list-1525387875570">' +
          dataHtml +
          "</div>"
        );
      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: plang.get("POS_0054"),
          width: 1000,
          height: 600,
          modal: true,
          open: function () {
            $dialog
              .find(".top-sidebar-content:eq(0)")
              .attr("style", "padding-left: 15px !important");
            $dialog.find('a[onclick*="toQuickMenu"]').remove();
          },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("close_btn"),
              class: "btn blue-madison btn-sm",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
          /*maximize : function () {
                $('#objectdatagrid-1525387875570').datagrid('resize');
              }, 
              restore : function () {
                $('#objectdatagrid-1525387875570').datagrid('resize');
              }*/
        });

      $dialog.dialog("open");
      $dialog.dialogExtend("maximize");

      $dialog.bind("dialogextendminimize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .addClass("display-none");
      });
      $dialog.bind("dialogextendmaximize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
      $dialog.bind("dialogextendrestore", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  });
}

function posItemEndQtyShowList() {
  var $tbody = $("#posTable > tbody"),
    $itemSelectedRow = $tbody.find("> tr[data-item-id].pos-selected-row:eq(0)");

  if ($itemSelectedRow.length) {
    posItemEndQtyList($itemSelectedRow.find('input[name="itemId[]"]').val());
  }
  return;
}

function posCalcItemRowDiscount() {
  var $tbody = $("#posTable > tbody"),
    $itemSelectedRow = $tbody.find("> tr[data-item-id].pos-selected-row:eq(0)");

  if ($itemSelectedRow.length) {
    var $dialogName = "dialog-item-discount";
    if (!$($dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialog = $("#" + $dialogName);

    try {
      var $scanItemCode = $("#scanItemCode");
      $scanItemCode.combogrid("hidePanel");
      $scanItemCode.combogrid("clear", "");
      $scanItemCode.val("");
    } catch (e) { }

    $.ajax({
      type: "post",
      url: "mdpos/calcItemDiscount",
      dataType: "json",
      success: function (data) {
        $dialog.empty().append(data.html);
        $dialog.dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.title,
          width: 380,
          height: "auto",
          modal: true,
          closeOnEscape: isCloseOnEscape,
          position: { my: "top", at: "top+13%" },
          open: function () {
            disableScrolling();
          },
          close: function () {
            enableScrolling();
            $dialog.empty().dialog("destroy").remove();
          },
        });
        $dialog.dialog("open");
        setTimeout(function () {
          $dialog.find("#discountTypeId").select2("open");
        }, 10);
        $dialog.on("change", "#discountTypeId", function () {
          $("#calcRowDiscountPercentInput").focus().select();
        });
        Core.unblockUI();
      },
      error: function () {
        alert("Error");
        Core.unblockUI();
      },
    });
  }
  return;
}
function posItemDiscountBtn() {
  var $form = $(".pos-item-row-discount");
  var acceptEmployee = $("#discountEmployeeId_valueField").val();

  $form.validate({ errorPlacement: function () { } });

  if ($form.valid()) {
    if (acceptEmployee === "") {
      PNotify.removeAll();
      new PNotify({
        title: "Warning",
        text: "Зөвшөөрөх ажилтнаа сонгоно уу.",
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }

    var $tbody = $("#posTable > tbody"),
      $itemRow = $tbody.find("> tr[data-item-id].pos-selected-row:eq(0)"),
      discountPercent = Number($("#calcRowDiscountPercentInput").val());
    var salePrice = Number($itemRow.find('input[name="salePrice[]"]').val());

    if ($('#isAllItemsForDiscount').is(':checked')) {
      $tbody.find('> tr').each(function () {
        $itemRow = $(this);
        salePrice = Number($itemRow.find('input[name="salePrice[]"]').val());

        if (discountPercent > 0) {
          var $discountTypeId = $("#discountTypeId"),
            isDiscountPlus = $discountTypeId.find("option:selected").attr("param"),
            discount = (discountPercent / 100) * salePrice;

          // if (isDiscountPlus == "1") {
          //   var discountAmount = salePrice + discount;
          //   discountPercent = -1 * discountPercent;
          //   discount = -1 * discount;
          // } else {
          // }
          var discountAmount = salePrice - discount;

          $itemRow.find('td[data-field-name="salePrice"]').autoNumeric("set", discountAmount);
          /*$itemRow.find('input[name="salePrice[]"]').val(discountAmount);*/
          $itemRow.find('input[name="discountAmount[]"]').val(discountAmount);
          $itemRow.find('input[name="discountPercent[]"]').val(discountPercent);
          $itemRow.find('input[name="unitDiscount[]"]').val(discount);
          $itemRow.find('input[name="isDiscount[]"]').val("1");

          $("#pos-discount-percent").val(discountPercent);
          $("#pos-discount-amount").autoNumeric("set", discount);

          $itemRow.find('input[name="discountEmployeeId[]"]').attr({
            value: $("#discountEmployeeId_valueField").val(),
            "data-emp-code": $("#discountEmployeeId_displayField").val(),
            "data-emp-name": $("#discountEmployeeId_nameField").val(),
            "data-emp-json": $("#discountEmployeeId_valueField").attr(
              "data-row-data"
            ),
          });
          $itemRow
            .find('input[name="discountTypeId[]"]')
            .val($discountTypeId.val());
          $itemRow
            .find('input[name="discountDescription[]"]')
            .val($("#discountDescription").val());
        } else {
          $itemRow
            .find('td[data-field-name="salePrice"]')
            .autoNumeric("set", salePrice);
          $itemRow.find('input[name="discountAmount[]"]').val("");
          $itemRow.find('input[name="discountPercent[]"]').val("");
          $itemRow.find('input[name="unitDiscount[]"]').val("");
          $itemRow.find('input[name="isDiscount[]"]').val("");
          $itemRow.find('input[name="discountEmployeeId[]"]').val("").attr({
            "data-emp-code": "",
            "data-emp-name": "",
            "data-emp-json": "",
          });
          $itemRow.find('input[name="discountTypeId[]"]').val("");
          $itemRow.find('input[name="discountDescription[]"]').val("");

          $("#pos-discount-percent").val("");
          $("#pos-discount-amount").autoNumeric("set", "");
        }
        posCalcRow($itemRow);
      });

    } else {

      if (discountPercent > 0) {
        var $discountTypeId = $("#discountTypeId"),
          isDiscountPlus = $discountTypeId.find("option:selected").attr("param"),
          discount = Number($("#calcRowDiscountAmountInput").autoNumeric("get"));

        if (isDiscountPlus == "1") {
          var discountAmount = salePrice + discount;
          discountPercent = -1 * discountPercent;
          discount = -1 * discount;
        } else {
          var discountAmount = salePrice - discount;
        }

        $itemRow
          .find('td[data-field-name="salePrice"]')
          .autoNumeric("set", discountAmount);
        /*$itemRow.find('input[name="salePrice[]"]').val(discountAmount);*/
        $itemRow.find('input[name="discountAmount[]"]').val(discountAmount);
        $itemRow.find('input[name="discountPercent[]"]').val(discountPercent);
        $itemRow.find('input[name="unitDiscount[]"]').val(discount);
        $itemRow.find('input[name="isDiscount[]"]').val("1");

        $("#pos-discount-percent").val(discountPercent);
        $("#pos-discount-amount").autoNumeric("set", discount);

        $itemRow.find('input[name="discountEmployeeId[]"]').attr({
          value: $("#discountEmployeeId_valueField").val(),
          "data-emp-code": $("#discountEmployeeId_displayField").val(),
          "data-emp-name": $("#discountEmployeeId_nameField").val(),
          "data-emp-json": $("#discountEmployeeId_valueField").attr(
            "data-row-data"
          ),
        });
        $itemRow
          .find('input[name="discountTypeId[]"]')
          .val($discountTypeId.val());
        $itemRow
          .find('input[name="discountDescription[]"]')
          .val($("#discountDescription").val());
      } else {
        $itemRow
          .find('td[data-field-name="salePrice"]')
          .autoNumeric("set", salePrice);
        $itemRow.find('input[name="discountAmount[]"]').val("");
        $itemRow.find('input[name="discountPercent[]"]').val("");
        $itemRow.find('input[name="unitDiscount[]"]').val("");
        $itemRow.find('input[name="isDiscount[]"]').val("");
        $itemRow.find('input[name="discountEmployeeId[]"]').val("").attr({
          "data-emp-code": "",
          "data-emp-name": "",
          "data-emp-json": "",
        });
        $itemRow.find('input[name="discountTypeId[]"]').val("");
        $itemRow.find('input[name="discountDescription[]"]').val("");

        $("#pos-discount-percent").val("");
        $("#pos-discount-amount").autoNumeric("set", "");
      }
      posCalcRow($itemRow);
    }

    $("#dialog-item-discount").dialog("close");
  }

  return;
}
function posCalcItemRowDiscountRemove() {
  var $tbody = $("#posTable > tbody"),
    $itemRow = $tbody.find("> tr[data-item-id].pos-selected-row:eq(0)");

  if ($itemRow.length) {
    var salePrice = $itemRow.find('input[name="salePrice[]"]').val();

    $itemRow
      .find('td[data-field-name="salePrice"]')
      .autoNumeric("set", salePrice);
    $itemRow.find('input[name="discountAmount[]"]').val("");
    $itemRow.find('input[name="discountPercent[]"]').val("");
    $itemRow.find('input[name="unitDiscount[]"]').val("");
    $itemRow.find('input[name="isDiscount[]"]').val("");
    $itemRow
      .find('input[name="discountEmployeeId[]"]')
      .val("")
      .attr({ "data-emp-code": "", "data-emp-name": "", "data-emp-json": "" });
    $itemRow.find('input[name="discountTypeId[]"]').val("");
    $itemRow.find('input[name="discountDescription[]"]').val("");

    $("#pos-discount-percent").val("");
    $("#pos-discount-amount").autoNumeric("set", "");

    posCalcRow($itemRow);
  }

  return;
}

function posCashMoneyBill(open) {
  if (
    $("body").find("#dialog-cash-moneybill").length > 0 &&
    $("body").find("#dialog-cash-moneybill").is(":visible") &&
    $("body").find("#dialog-cash-moneybill:last").text().length
  ) {
    var $posCashAmount = $("#posCashAmount"),
      $dialogMoneyBill = $("#dialog-cash-moneybill"),
      buttons = $dialogMoneyBill.dialog("option", "buttons");
    buttons[0].click.apply($dialogMoneyBill);
    return;
  }

  if (
    $("body").find("#dialog-banknotes").length > 0 &&
    $("body").find("#dialog-banknotes").is(":visible") &&
    $("body").find("#dialog-banknotes:last").text().length
  ) {
    var $dialogMoneyBill = $("#dialog-banknotes"),
      buttons = $dialogMoneyBill.dialog("option", "buttons");
    buttons[0].click.apply($dialogMoneyBill);
    return;
  }

  var $posCashAmount = $("#posCashAmount:visible");

  if ($posCashAmount.length) {
    return;

    var $dialogName = "dialog-cash-moneybill";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
      type: "post",
      url: "mdcommon/moneyBill",
      dataType: "json",
      success: function (data) {
        $dialog.empty().append(data.html);
        $dialog.dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.title,
          width: 440,
          height: "auto",
          modal: true,
          closeOnEscape: isCloseOnEscape,
          position: { my: "top", at: "top+13%" },
          open: function () {
            disableScrolling();
          },
          close: function () {
            enableScrolling();
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: data.insert_btn,
              class: "btn btn-sm green-meadow",
              click: function () {
                $posCashAmount
                  .autoNumeric(
                    "set",
                    $("td.money-bill-total-amount").autoNumeric("get")
                  )
                  .trigger("change");
                $dialog.dialog("close");
              },
            },
            {
              text: data.close_btn,
              class: "btn btn-sm blue-hoki",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });
        $dialog.dialog("open");
        Core.unblockUI();
      },
      error: function () {
        alert("Error");
      },
    }).done(function () {
      Core.initDecimalPlacesInput($dialog);
    });
  } else {
    var $dialogName = "dialog-banknotes";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialog = $("#" + $dialogName);
    open = typeof open !== "undefined" ? open : "";

    $.ajax({
      type: "post",
      url: "mdpos/moneyBill",
      data: { open: open },
      dataType: "json",
      success: function (data) {
        if (data.isExist) {
          return;
        }

        var moneyHtml = data.html;

        if (posCashierInsertC1) {
          moneyHtml +=
            '<div class="row mt10 d-none"><div class="col-md-4"><label for="localCost">' +
            plang.get("POS_0498") +
            '</div><div class="col-md-8"><input type="text" name="localCost" class="form-control form-control-sm bigdecimalInit" id="localCost"></div></div>';
        } else {
          moneyHtml +=
            '<div class="row mt10"><div class="col-md-4"><label for="localCost">' +
            plang.get("POS_0498") +
            '</div><div class="col-md-8"><input type="text" name="localCost" class="form-control form-control-sm bigdecimalInit" id="localCost"></div></div>';
        }

        moneyHtml +=
          '<input type="hidden" name="bankNoteTypeId" id="bankNoteTypeId" class="form-control form-control-sm" value="' +
          open +
          '">';

        $dialog.empty().append(moneyHtml);
        $dialog.dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.title,
          width: 440,
          height: "auto",
          modal: true,
          closeOnEscape: isCloseOnEscape,
          position: { my: "top", at: "top+13%" },
          open: function () {
            disableScrolling();
          },
          close: function () {
            enableScrolling();
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: data.insert_btn,
              class: "btn btn-sm green-meadow",
              click: function () {
                PNotify.removeAll();
                var totalBankNoteAmount = $(
                  "td.money-bill-total-amount"
                ).autoNumeric("get");

                if (totalBankNoteAmount > 0) {
                  $.ajax({
                    type: "post",
                    url: "mdpos/saveBankNotes",
                    data: {
                      bankNotes:
                        $("#banknotesForm").serialize() +
                        "&localCost=" +
                        $dialog.find("#localCost").val() +
                        "&bankNoteTypeId=" +
                        $dialog.find("#bankNoteTypeId").val(),
                    },
                    dataType: "json",
                    beforeSend: function () {
                      Core.blockUI({ message: "Loading...", boxed: true });
                    },
                    success: function (dataSub) {
                      if (dataSub.status == "success") {
                        $dialog.dialog("close");
                      }

                      new PNotify({
                        title: dataSub.status,
                        text: dataSub.message,
                        type: dataSub.status,
                        sticker: false,
                      });

                      Core.unblockUI();
                    },
                  });
                } else {
                  new PNotify({
                    title: "Error",
                    text: "Дэвсгэртийн тоог оруулна уу!",
                    type: "error",
                    sticker: false,
                  });
                }
              },
            },
            {
              text: data.close_btn,
              class: "btn btn-sm blue-hoki",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });
        $dialog.dialog("open");
        Core.unblockUI();
      },
      error: function () {
        alert("Error");
      },
    }).done(function () {
      Core.initDecimalPlacesInput($dialog);
      Core.initDateMinuteMaskInput($dialog);
    });
  }

  return;
}

function posOrderSave(isInv) {
  PNotify.removeAll();

  var $posTableBody = $("#posTable > tbody");

  // Check item list
  if ($posTableBody.find("> tr[data-item-id]").length == 0) {
    new PNotify({
      title: "Warning",
      text: plang.get("POS_0022"),
      type: "warning",
      sticker: false,
      addclass: "pnotify-center",
    });

    return;
  }

  // Check salesperson
  if (
    isConfigSalesPerson &&
    $posTableBody.find(
      'input.lookup-code-autocomplete[data-field-name="employeeId"]:visible'
    ).length
  ) {
    var $itemRows = $posTableBody.find("> tr[data-item-id]:visible"),
      salesPersonResult = true;

    $itemRows.each(function () {
      var $itemRow = $(this),
        $employeeId = $itemRow.find('input[data-path="employeeId"]'),
        $employeeCode = $itemRow.find(
          'input.lookup-code-autocomplete[data-field-name="employeeId"]:not([readonly])'
        ),
        $employeeName = $itemRow.find(
          'input.lookup-name-autocomplete[data-field-name="employeeId"]:not([readonly])'
        );

      if (
        $employeeCode.length &&
        ($employeeId.val() == "" ||
          $employeeCode.val() == "" ||
          $employeeName.val() == "")
      ) {
        salesPersonResult = false;
        $employeeCode.addClass("error");
        $employeeName.addClass("error");
      } else {
        $employeeCode.removeClass("error");
        $employeeName.removeClass("error");
      }
    });

    if (salesPersonResult == false) {
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0023"),
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }
  }

  var $dialogName = "dialog-pos-order";
  $('<div id="' + $dialogName + '"></div>').appendTo("body");
  var $dialog = $("#" + $dialogName);

  var $isDeliveryRows = $posTableBody
    .find('input[name="isDelivery[]"]')
    .filter(function () {
      return $(this).val() == "1";
    }),
    $isServiceGiftDelivery = $posTableBody.find(
      "input.isGiftDelivery:checked, input.isServiceDelivery"
    ).length;

  var paymentData = {
    amount: $(".pos-amount-paid").autoNumeric("get"),
    isDelivery: Number($isDeliveryRows.length) + Number($isServiceGiftDelivery),
    invoiceId: $("#invoiceId").val(),
    isInv: typeof isInv !== 'undefined' ? isInv : '',
  };

  $.ajax({
    type: "post",
    url: "mdpos/orderForm",
    data: paymentData,
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data.html);

      $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: 620,
        minWidth: 620,
        height: "auto",
        modal: true,
        dialogClass: "pos-payment-dialog",
        closeOnEscape: isCloseOnEscape,
        position: { my: "top", at: "top+50" },
        open: function () {
          disableScrolling();
        },
        close: function () {
          enableScrolling();
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [
          {
            text: data.save_btn,
            class: "btn btn-sm green-meadow pos-order-save",
            click: function () {
              var $form = $("#pos-order-form");
              $form.validate({ errorPlacement: function () { } });

              if ($form.valid()) {
                var paymentData = $form.serialize(),
                  itemData = $posTableBody.find("input").serialize(),
                  vatAmount = $(".pos-amount-vat").autoNumeric("get"),
                  cityTaxAmount = $(".pos-amount-citytax").autoNumeric("get"),
                  discountAmount = $(".pos-amount-discount").autoNumeric("get");

                paymentData =
                  paymentData +
                  "&vatAmount=" +
                  vatAmount +
                  "&cityTaxAmount=" +
                  cityTaxAmount +
                  "&discountAmount=" +
                  discountAmount +
                  "&basketInvoiceId=" +
                  $("#basketInvoiceId").val();

                $.ajax({
                  type: "post",
                  url: "mdpos/orderSave",
                  data: {
                    paymentData: paymentData,
                    itemData: itemData,
                    isInv: typeof isInv !== 'undefined' ? isInv : '',
                  },
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({ message: "Saving...", boxed: true });
                  },
                  success: function (data) {
                    PNotify.removeAll();

                    if (data.status === "success") {
                      $dialog.dialog("close");
                      new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false,
                        addclass: "pnotify-center",
                      });
                      posDisplayReset("");
                    } else {
                      new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false,
                      });
                    }

                    Core.unblockUI();
                  },
                });
              }
            },
          },
          {
            text: data.save_print_btn,
            class: "btn btn-sm purple-plum pos-order-save-print hide",
            click: function () {
              var $form = $("#pos-order-form");
              $form.validate({ errorPlacement: function () { } });

              if ($form.valid()) {
                var paymentData = $form.serialize(),
                  itemData = $posTableBody.find("input").serialize(),
                  vatAmount = $(".pos-amount-vat").autoNumeric("get"),
                  cityTaxAmount = $(".pos-amount-citytax").autoNumeric("get"),
                  discountAmount = $(".pos-amount-discount").autoNumeric("get");

                paymentData =
                  paymentData +
                  "&vatAmount=" +
                  vatAmount +
                  "&cityTaxAmount=" +
                  cityTaxAmount +
                  "&discountAmount=" +
                  discountAmount;

                $.ajax({
                  type: "post",
                  url: "mdpos/orderSave",
                  data: {
                    paymentData: paymentData,
                    itemData: itemData,
                  },
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({ message: "Saving...", boxed: true });
                  },
                  success: function (data) {
                    PNotify.removeAll();

                    if (data.status === "success") {
                      $dialog.dialog("close");

                      new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false,
                        addclass: "pnotify-center",
                      });

                      posDisplayReset("");

                      var selectedRow = data,
                        dataHtml = data.html,
                        selectedRows = [];

                      delete selectedRow["html"];
                      selectedRows[0] = selectedRow;

                      posInvoicePrint(selectedRows, data.templateId, dataHtml);
                    } else {
                      new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false,
                      });
                    }

                    Core.unblockUI();
                  },
                });
              }
            },
          },
          {
            text: data.close_btn,
            class: "btn btn-sm blue-hoki",
            click: function () {
              $dialog.dialog("close");
            },
          },
        ],
      });
      $dialog.dialog("open");

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initClean($dialog);
  });
}
function posInvoicePrint(selectedRows, tempMetaId, dialogHtml) {
  var $dialogName = "dialog-printSettings";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $dialog.empty().append(dialogHtml);
  $dialog.dialog({
    cache: false,
    resizable: true,
    bgiframe: true,
    autoOpen: false,
    title: plang.get("POS_0055"),
    width: 500,
    minWidth: 400,
    height: "auto",
    modal: false,
    open: function () {
      Core.initDVAjax($dialog);
    },
    close: function () {
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: [
      {
        text: "Хэвлэх",
        class: "btn btn-sm blue",
        click: function () {
          PNotify.removeAll();

          var numberOfCopies = $("#numberOfCopies").val(),
            isPrintNewPage = $("#isPrintNewPage").is(":checked") ? "1" : "0",
            isShowPreview = $("#isShowPreview").is(":checked") ? "1" : "0",
            isPrintPageBottom = $("#isPrintPageBottom").is(":checked")
              ? "1"
              : "0",
            isPrintPageRight = $("#isPrintPageRight").is(":checked")
              ? "1"
              : "0",
            pageOrientation = $("#pageOrientation").val(),
            paperInput = $("#paperInput").val(),
            pageSize = $("#pageSize").val(),
            printType = $("#printType").val();

          var print_options = {
            numberOfCopies: numberOfCopies,
            isPrintNewPage: isPrintNewPage,
            isShowPreview: isShowPreview,
            isPrintPageBottom: isPrintPageBottom,
            isPrintPageRight: isPrintPageRight,
            isSettingsDialog: "0",
            pageOrientation: pageOrientation,
            paperInput: paperInput,
            pageSize: pageSize,
            printType: printType,
            templateMetaId: tempMetaId,
          };

          if (numberOfCopies != "" && numberOfCopies != "0") {
            $dialog.dialog("close");
            callTemplate(selectedRows, tempMetaId, print_options);
          } else {
            new PNotify({
              title: "Warning",
              text: plang.get("POS_0056"),
              type: "warning",
              sticker: false,
            });
          }
        },
      },
      {
        text: plang.get("close_btn"),
        class: "btn btn-sm blue-hoki",
        click: function () {
          $dialog.dialog("close");
        },
      },
    ],
  });
  $dialog.dialog("open");

  return;
}
function posNotVatCustomerList() {
  var $dialogName = "dialog-cash-moneybill";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdpos/notVatCustomerList",
    dataType: "json",
    success: function (data) {
      $dialog.empty().append(data.html);
      $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: 550,
        height: $(window).height() - 100,
        modal: true,
        closeOnEscape: isCloseOnEscape,
        /*position: {my: 'top', at: 'top+13%'},*/
        open: function () {
          disableScrolling();
        },
        close: function () {
          enableScrolling();
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [
          {
            text: data.insert_btn,
            class: "btn btn-sm green-meadow",
            click: function () {
              var $parentRow = $(".tbl-notvat-crm > tbody > tr.selected");

              if ($parentRow.length) {
                var $posPayAmount = $("#posPayAmount"),
                  vatAmount = Number($("td.pos-amount-vat").autoNumeric("get")),
                  payAmount = Number($("#tmpPayAmount").val());

                $posPayAmount.autoNumeric("set", payAmount - vatAmount);

                $("#pos-org-number").val(
                  $parentRow.find('td[data-crm-ttd="1"]').text()
                );
                $("#pos-org-name").val(
                  $parentRow.find('td[data-crm-name="1"]').text()
                );
                $("#pos-org-vatpayer").val("false");

                $dialog.dialog("close");
              } else {
                PNotify.removeAll();
                new PNotify({
                  title: "Warning",
                  text: plang.get("POS_0031"),
                  type: "warning",
                  sticker: false,
                });
              }
            },
          },
          {
            text: data.close_btn,
            class: "btn btn-sm blue-hoki",
            click: function () {
              $dialog.dialog("close");
            },
          },
        ],
      });
      $dialog.dialog("open");
      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  });

  return;
}
function posToBasket() {
  if (
    $("#dialog-talon-onlyfood").length &&
    $("#dialog-talon-onlyfood").is(":visible")
  ) {
    $(".send-kitchen-fooditem").click();
  }
  if ($(".blockOverlay").length) {
    return;
  }
  if ($("#dialog-talon-protect").length && $("#dialog-talon-protect").is(":visible")) {
    return;
  }
  if (
    $("body").find("#dialog-pos-payment").length > 0 &&
    $("body").find("#dialog-pos-payment").is(":visible")
  ) {
    return;
  }
  PNotify.removeAll();

  var $posTableBody = $("#posTable > tbody");

  // Check item list
  if ($posTableBody.find("> tr[data-item-id]").length == 0) {
    new PNotify({
      title: "Warning",
      text: plang.get("POS_0022"),
      type: "warning",
      sticker: false,
      addclass: "pnotify-center",
    });
    return;
  }

  var paymentData = {
    amount: $(".pos-amount-paid").autoNumeric("get"),
  };

  var isBasketSelected = false;

  if ($("#basketCustomerId").val() != "") {
    paymentData["customerId"] = $("#basketCustomerId").val();
    paymentData["customerCode"] = $("#basketCustomerCode").val();
    paymentData["customerName"] = $("#basketCustomerName").val();
    paymentData["customerCardNumber"] = $("#basketCardNumber").val();
    paymentData["createdUserId"] = $("#basketCreatedUserId").val();
    isBasketSelected = true;
  } else if ($("#empCustomerId_valueField").val() != "") {
    paymentData["customerId"] = $("#empCustomerId_valueField").val();
    paymentData["customerCardNumber"] = $("#empCustomerId_displayField").val();
    paymentData["customerName"] = $("#empCustomerId_nameField").val();
  }

  if (posTypeCode == "4" && isMultiCustomerPrintBill) {
    new PNotify({
      title: "Warning",
      text: "Харилцагчийн үйлчилгээ нэгтгэж харуулж байгаа тул хүлээлгэнд оруулах боломжгүй байна!",
      type: "warning",
      sticker: false,
      addclass: "pnotify-center",
    });
    return;
  }

  if (posTypeCode == "3") {
    if (isMultiCustomerPrintBill) {
      new PNotify({
        title: "Warning",
        text: "Харилцагчийн үйлчилгээ нэгтгэж харуулж байгаа тул хүлээлгэнд оруулах боломжгүй байна!",
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }
    if (
      restPosEventType["event"] === "splitCalculate" &&
      returnBillType == ""
    ) {
      new PNotify({
        title: "Warning",
        text: "Тооцоо салгаж харуулж байгаа тул хүлээлгэнд оруулах боломжгүй байна!",
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }
    if (coldF9) {
      foodItem();
    }
    return;
  }

  $.ajax({
    type: "post",
    url: "mdpos/basketForm",
    data: paymentData,
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      if (data.status != "success") {
        PNotify.removeAll();
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false,
        });
        Core.unblockUI();
        return;
      }

      if (data.type === "locker") {
        var paymentData =
          "lockerId=" +
          ($("#lockerId").length ? $("#lockerId").val() : "") +
          "&lockerOrderId=" +
          ($("#lockerOrderId").length ? $("#lockerOrderId").val() : "") +
          "&windowSessionId=" +
          ($("#windowSessionId").length ? $("#windowSessionId").val() : "") +
          "&isBasket=1&payAmount=" +
          $(".pos-amount-paid").autoNumeric("get"),
          itemData = $posTableBody.find("input").serialize(),
          vatAmount = $(".pos-amount-vat").autoNumeric("get"),
          cityTaxAmount = $(".pos-amount-citytax").autoNumeric("get"),
          discountAmount = $(".pos-amount-discount").autoNumeric("get");

        paymentData =
          paymentData +
          "&vatAmount=" +
          vatAmount +
          "&cityTaxAmount=" +
          cityTaxAmount +
          "&discountAmount=" +
          discountAmount;
        paymentData +=
          "&isBasketSelected=1&basketInvoiceId=" + $("#basketInvoiceId").val();

        $.ajax({
          type: "post",
          url: "mdpos/orderSaveNotSendVat",
          data: { paymentData: paymentData, itemData: itemData },
          dataType: "json",
          beforeSend: function () {
            Core.blockUI({
              message: "Saving...",
              boxed: true,
            });
          },
          success: function (data) {
            PNotify.removeAll();

            if (data.status === "success") {
              if (typeof isAuto !== "undefined") {
                data.message =
                  data.message + "<hr/>" + plang.get("posOrderTimerMessage");
              }
              new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false,
              });
              if (data.printData !== "") {
                $("div.pos-preview-print")
                  .html(data.printData)
                  .promise()
                  .done(function () {
                    $("div.pos-preview-print").printThis({
                      debug: false,
                      importCSS: false,
                      printContainer: false,
                      dataCSS: data.css,
                      removeInline: false,
                    });
                  });
              }
              $(".pos-basket-count").text(data.basketCount);
              posDisplayReset("");
            } else {
              new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false,
              });
            }

            Core.unblockUI();
          },
        });
        return;
      }

      var $dialogName = "dialog-pos-basket";
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
      var $dialog = $("#" + $dialogName);

      $dialog.empty().append(data.html);

      $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: 600,
        minWidth: 600,
        height: "auto",
        modal: true,
        dialogClass: "pos-payment-dialog",
        closeOnEscape: isCloseOnEscape,
        open: function () {
          disableScrolling();
        },
        close: function () {
          enableScrolling();
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [
          {
            text: data.save_btn,
            class: "btn btn-sm green-meadow pos-order-save",
            click: function () {
              var $form = $("#pos-basket-form");
              $form.validate({ errorPlacement: function () { } });

              if ($form.valid()) {
                var paymentData = $form.serialize(),
                  itemData = $posTableBody.find("input").serialize(),
                  vatAmount = $(".pos-amount-vat").autoNumeric("get"),
                  cityTaxAmount = $(".pos-amount-citytax").autoNumeric("get"),
                  discountAmount = $(".pos-amount-discount").autoNumeric("get");

                paymentData =
                  paymentData +
                  "&vatAmount=" +
                  vatAmount +
                  "&cityTaxAmount=" +
                  cityTaxAmount +
                  "&discountAmount=" +
                  discountAmount +
                  "&basketInvoiceId=" +
                  $("#basketInvoiceId").val();

                if (isBasketSelected == true) {
                  paymentData += "&isBasketSelected=1";
                }
                if ($("#posRestWaiterId").length) {
                  paymentData += "&waiterId=" + $("#posRestWaiterId").val();
                }
                if ($("#posLocationId").length) {
                  paymentData += "&deskId=" + $("#posLocationId").val();
                }
                if ($("#guestName").length) {
                  paymentData += "&guestName=" + $("#guestName").val();
                }

                $.ajax({
                  type: "post",
                  url: "mdpos/orderSave",
                  data: {
                    paymentData: paymentData,
                    itemData: itemData,
                    orderData: "",
                  },
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      message: "Saving...",
                      boxed: true,
                    });
                  },
                  success: function (data) {
                    PNotify.removeAll();

                    if (posTypeCode === "4" && data.chooseorder && false) {
                      var $dialogNameWaiter = "dialog-chooseorder-form";
                      if (!$("#" + $dialogNameWaiter).length) {
                        $(
                          '<div id="' + $dialogNameWaiter + '"></div>'
                        ).appendTo("body");
                      }

                      var $dialogPWaiter = $("#" + $dialogNameWaiter);
                      var selectHtml = '<div style="overflow:auto">';
                      for (var i = 0; i < data.data.length; i++) {
                        if (data.data[i]["deliverycontactname"]) {
                          selectHtml +=
                            '<div data-id="' +
                            data.data[i]["id"] +
                            '" class="mb10 d-flex justify-content-start rest-choose-order" style="background: #FFFFFF;box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.08);border-radius: 10px;cursor: pointer">';
                          selectHtml +=
                            '<div style="padding:10px;font-size:14px"><div>' +
                            data.data[i]["deliverycontactname"] +
                            '</div><div style="color:#A0A0A0;font-size:12px;" class="mt3">' +
                            data.data[i]["deliverycontactphone"] +
                            "</div></div>";
                          selectHtml += "</div>";
                        } else {
                          selectHtml +=
                            '<div data-id="' +
                            data.data[i]["id"] +
                            '" class="mb10 d-flex justify-content-start rest-choose-order" style="background: #FFFFFF;box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.08);border-radius: 10px;cursor: pointer">';
                          selectHtml +=
                            '<div style="padding:10px;font-size:14px"><div>Нэр бөглөөгүй байна</div><div style="color:#A0A0A0;font-size:12px;" class="mt3"></div></div>';
                          selectHtml += "</div>";
                        }
                      }
                      selectHtml += "</div>";

                      $dialogPWaiter
                        .empty()
                        .append(
                          '<form method="post" autocomplete="off" id="chooseOrderForm">' +
                          selectHtml +
                          "</form>"
                        );
                      $dialogPWaiter.dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: "Харилцагч сонгох",
                        width: 280,
                        height: "auto",
                        maxHeight: 750,
                        position: { my: "top", at: "top+30" },
                        modal: false,
                        open: function () {
                          $dialogPWaiter.css("background-color", "#F5F5F5");
                          $dialogPWaiter.on(
                            "click",
                            ".rest-choose-order",
                            function (e) {
                              $.ajax({
                                type: "post",
                                url: "mdpos/orderSave",
                                data: {
                                  paymentData: paymentData,
                                  itemData: itemData,
                                  orderData:
                                    posTypeCode == "2" ? globalOrderData : "",
                                  selectedOrderId: $(this).data("id"),
                                },
                                dataType: "json",
                                beforeSend: function () {
                                  Core.blockUI({
                                    message: "Saving...",
                                    boxed: true,
                                  });
                                },
                                success: function (data) {
                                  PNotify.removeAll();

                                  if (data.status === "success") {
                                    $dialog.dialog("close");
                                    new PNotify({
                                      title: data.status,
                                      text: data.message,
                                      type: data.status,
                                      sticker: false,
                                      addclass: "pnotify-center",
                                    });
                                    $(".pos-basket-count").text(
                                      data.basketCount
                                    );
                                    posDisplayReset("");
                                    if ($("#posLocationId").length) {
                                      $("#posLocationId").val("");
                                      $("#posRestWaiterId").val("");
                                    }
                                  } else {
                                    new PNotify({
                                      title: data.status,
                                      text: data.message,
                                      type: data.status,
                                      sticker: false,
                                    });
                                  }

                                  Core.unblockUI();
                                },
                              });

                              $dialogPWaiter.dialog("close");
                            }
                          );
                        },
                        close: function () {
                          $dialogPWaiter.empty().dialog("destroy").remove();
                        },
                        buttons: [],
                      });
                      $dialogPWaiter.dialog("open");
                      Core.unblockUI();
                      return;
                    }

                    if (data.status === "success") {
                      $dialog.dialog("close");
                      new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false,
                        addclass: "pnotify-center",
                      });
                      $(".pos-basket-count").text(data.basketCount);
                      posDisplayReset("");
                    } else {
                      new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false,
                      });
                    }
                    if ($("#posLocationId").length) {
                      $("#posLocationId").val("");
                      $("#posRestWaiterId").val("");
                    }
                    $('#guestName').val('').prop('readonly', false);

                    Core.unblockUI();
                  },
                });
              }
            },
          },
          {
            text: data.close_btn,
            class: "btn btn-sm blue-hoki",
            click: function () {
              $dialog.dialog("close");
            },
          },
        ],
      });
      $dialog.dialog("open");
      Core.initClean($dialog);

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
      Core.unblockUI();
    },
  });
}

function posBasketList(elem) {
  if (posTypeCode == 3 || posTypeCode == 4) {
    posRestBasketList(
      "nullmeta",
      "0",
      tempInvoiceDvId,
      "single",
      "nullmeta",
      elem,
      "casherCheck"
    );
  } else {
    dataViewSelectableGrid(
      "nullmeta",
      "0",
      tempInvoiceDvId,
      "single",
      "nullmeta",
      elem,
      "casherCheck"
    );
  }
}

// Pin ask dialog 
function posRestBasketList(metaDataCode,
  processMetaDataId,
  chooseType,
  elem,
  rows,
  paramRealPath,
  lookupMetaDataId,
  isMetaGroup,
  callback) {

  var $dialogName = "dialog-multiuser-basket-dataview";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdobject/dataValueViewer",
    data: {
      metaDataId: "1529014380513",
      viewType: "detail",
      dataGridDefaultHeight: 400,
      ignorePermission: 1,
      drillDownDefaultCriteria: 'customername=' + ($("#guestName").length ? $("#guestName").val().trim() : "") + '&cashRegisterId=' + cashRegisterId
    },
    beforeSend: function () {
      Core.blockUI({
        animate: true,
      });
    },
    success: function (dataHtml) {
      $dialog
        .empty()
        .append(
          '<div class="row" id="object-value-list-1529014380513">' +
          dataHtml +
          "</div>"
        );
      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: "Харилцагчийн үйлчилгээнүүд",
          position: { my: "top", at: "top+50" },
          width: 1000,
          height: "auto",
          modal: true,
          open: function () {
            $dialog.find(".top-sidebar-content:eq(0)").attr("style", "padding-left: 15px !important");
            $dialog.find('a[onclick*="toQuickMenu"]').remove();
          },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: "Сонгох",
              class: "btn blue-madison btn-sm",
              click: function () {
                selectedRestRowFromBasket($dialog);
              },
            },
          ],
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
          /*maximize : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }, 
              restore : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }*/
        });

      $dialog.dialog("open");
      //$dialog.dialogExtend('maximize');

      $dialog.bind("dialogextendminimize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .addClass("display-none");
      });
      $dialog.bind("dialogextendmaximize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
      $dialog.bind("dialogextendrestore", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    //Core.initDVAjax($dialog);
  });

}

// Pin ask dialog 
function posRestBasketListPayment(metaDataCode,
  processMetaDataId,
  chooseType,
  elem,
  rows,
  paramRealPath,
  lookupMetaDataId,
  isMetaGroup,
  callback) {

  var $dialogName = "dialog-multiuser-basket-dataview";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdobject/dataValueViewer",
    data: {
      metaDataId: POS_PAY_BASKET_LIST,
      viewType: "detail",
      dataGridDefaultHeight: 400,
      ignorePermission: 1
    },
    beforeSend: function () {
      Core.blockUI({
        animate: true,
      });
    },
    success: function (dataHtml) {
      $dialog
        .empty()
        .append(
          '<div class="row" id="object-value-list-' + POS_PAY_BASKET_LIST + '">' +
          dataHtml +
          "</div>"
        );
      $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: "Харилцагчийн үйлчилгээнүүд",
        position: { my: "top", at: "top+50" },
        width: 1000,
        height: "auto",
        modal: true,
        open: function () {
          $dialog.find(".top-sidebar-content:eq(0)").attr("style", "padding-left: 15px !important");
          $dialog.find('a[onclick*="toQuickMenu"]').remove();
        },
        close: function () {
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [{
          text: "Сонгох",
          class: "btn blue-madison btn-sm",
          click: function () {
            selectedRestRowFromBasketPayment($dialog);
          }
        }]
      })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
          /*maximize : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }, 
              restore : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }*/
        });

      $dialog.dialog("open");
      //$dialog.dialogExtend('maximize');

      $dialog.bind("dialogextendminimize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .addClass("display-none");
      });
      $dialog.bind("dialogextendmaximize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
      $dialog.bind("dialogextendrestore", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    //Core.initDVAjax($dialog);
  });

}

function selectedRestRowFromBasket($dialog) {
  isMultiCustomerPrintBill = false;
  restClears();
  Core.blockUI({
    message: "Loading...",
    boxed: true,
  });

  if (posTypeCode == 4) {
    var rows = getDataViewSelectedRows("1529014380513"),
      rows2 = [];

    if ($("div[data-path-uniqid]").length === 1) {
      rows2 = getDataViewSelectedRows(
        $("div[data-path-uniqid]").attr("data-path-uniqid")
      );
    }
    if ($("div[data-path-uniqid]").length > 1) {
      rows2 = getDataViewSelectedRows(
        $("div[data-path-uniqid]").eq(0).attr("data-path-uniqid")
      );
      var rows22 = getDataViewSelectedRows(
        $("div[data-path-uniqid]").eq(1).attr("data-path-uniqid")
      );
      rows2 = rows2.concat(rows22);
    }

    if (rows.length || rows2.length) {
      if (rows.length) {
        for (var cus = 0; cus < rows.length; cus++) {
          if (rows[0]["customername"] != rows[cus]["customername"]) {
            alert("Нэг харилцагчийн бараа сонгоно уу!");
            return;
          }
        }
      }

      if (rows2.length) {
        for (var cus = 0; cus < rows2.length; cus++) {
          if (rows2[0]["customername"] != rows2[cus]["customername"]) {
            alert("Нэг харилцагчийн бараа сонгоно уу!");
            return;
          }
        }
      }

      if (rows2.length) {
        var row = rows2[0];
      } else {
        var row = rows[0];
      }

      if (row.customername) {
        $("#guestName").val(row.customername);
      }

      var getCustomerItemsArray = "",
        customerId = "";

      if (rows2.length) {
        getCustomerItemsArray = rows2;
        customerId = row["customerid"];
      } else {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
        var getCustomerItems = $.ajax({
          type: "post",
          url: "api/callDataview",
          data: {
            dataviewId: "16842299439629",
            pagingWithoutAggregate: 1,
            criteriaData: {
              salesOrderId: [
                { operator: "=", operand: row["salesorderid"] }
              ],
              filterGuestNames: [
                { operator: "=", operand: row["customername"] },
              ]
            }
          },
          dataType: "json",
          async: false,
          success: function (data) {
            return data.result;
          },
        });
        getCustomerItemsArray =
          getCustomerItems.responseJSON.result;
        customerId = row["customerid"];
        Core.unblockUI();
      }

      var prms = {
        status: "success",
        data: {
          id: row['salesorderid'],
          locationid: $("#posLocationId").val(),
          salespersonid: $("#posRestWaiterId").val(),
          customerid: customerId,
          pos_item_list_get: getCustomerItemsArray,
        },
      };

      var basketParams = [
        { id: "", event: "multiCustomer", data: prms },
      ];
      posFillItemsByBasket(
        "",
        "",
        "",
        "",
        basketParams
      );
      $dialog.dialog("close");
    } else {
      alert("Жагсаалтаас сонгоно уу!");
    }
    return;
  }

  let $dialogNameWaterPin = "dialog-waiter-pincode";
  if (!$("#" + $dialogNameWaterPin).length) {
    $('<div id="' + $dialogNameWaterPin + '"></div>').appendTo("body");
  }
  let $dialogWaiterPin = $("#" + $dialogNameWaterPin);

  $dialogWaiterPin
    .empty()
    .append('<form method="post" autocomplete="off" id="waiterPassForm"><input type="password" name="waiterPinCode" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>');

  $dialogWaiterPin.dialog({
    cache: false,
    resizable: true,
    bgiframe: true,
    autoOpen: false,
    title: "Нууц үг оруулах",
    width: 400,
    height: "auto",
    modal: true,
    open: function () {
      $dialogWaiterPin.on(
        "keydown",
        'input[name="waiterPinCode"]',
        function (e) {
          let keyCode = e.keyCode ? e.keyCode : e.which;
          if (keyCode === 13) {
            $(this)
              .closest(".ui-dialog")
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
            return false;
          }
        }
      );
    },
    close: function () {
      $dialogWaiterPin.empty().dialog("destroy").remove();
    },
    buttons: [{
      text: plang.get("insert_btn"),
      class: "btn btn-sm green-meadow",
      click: function () {
        PNotify.removeAll();
        let $form = $("#waiterPassForm");
        $form.validate({ errorPlacement: function () { } });

        if ($form.valid()) {
          let isPinSuccess = false,
            waiterObj = [];
          talonListPass = $form.find('input[name="waiterPinCode"]').val(),

            $.ajax({
              type: "post",
              url: "mdpos/checkTalonListPass",
              data: { talonListPass },
              dataType: "json",
              beforeSend: function () {
                Core.blockUI({
                  message: "Loading...",
                  boxed: true,
                });
              },
              success: function (dataSub) {
                let waiterId = $("#posRestWaiterId").val(),
                  salesOrderId = $("#posRestSalesOrderId").val(),
                  posLocationId = $(`#posLocationId`).val();

                if (dataSub.status == 'success') {
                  isPinSuccess = true;
                }

                Core.unblockUI();
                const dataviewId = "16207061606511";
                $.ajax({
                  type: "post",
                  url: "api/callDataview",
                  data: {
                    dataviewId,
                    criteriaData: {
                      pincode: [
                        {
                          operator: "=",
                          operand: talonListPass
                        },
                      ],
                    },
                  },
                  dataType: "json",
                  async: false,
                  beforeSend: function () {
                    Core.blockUI({
                      message: "Loading...",
                      boxed: true,
                    });
                  },
                  success: function (dataSub) {
                    if (
                      dataSub.status == "success" &&
                      dataSub.result.length
                    ) {
                      isPinSuccess = true;
                      waiterObj = dataSub.result;
                    } else {
                      new PNotify({
                        title: "Анхааруулга",
                        text: "Зөөгчийн мэдээлэл олдсонгүй",
                        type: "warning",
                        sticker: false,
                      });
                    }
                    Core.unblockUI();
                  },
                });

                if (isPinSuccess) {
                  $dialogWaiterPin.dialog("close");
                  var rows = getDataViewSelectedRows("1529014380513"),
                    rows2 = [];

                  if ($("div[data-path-uniqid]").length === 1) {
                    rows2 = getDataViewSelectedRows(
                      $("div[data-path-uniqid]").attr("data-path-uniqid")
                    );
                  }
                  if ($("div[data-path-uniqid]").length > 1) {
                    rows2 = getDataViewSelectedRows(
                      $("div[data-path-uniqid]").eq(0).attr("data-path-uniqid")
                    );
                    var rows22 = getDataViewSelectedRows(
                      $("div[data-path-uniqid]").eq(1).attr("data-path-uniqid")
                    );
                    rows2 = rows2.concat(rows22);
                  }

                  if (rows.length || rows2.length) {
                    if (rows.length) {
                      for (var cus = 0; cus < rows.length; cus++) {
                        if (rows[0]["customername"] != rows[cus]["customername"]) {
                          alert("Нэг харилцагчийн бараа сонгоно уу!");
                          return;
                        }
                      }
                    }

                    if (rows2.length) {
                      for (var cus = 0; cus < rows2.length; cus++) {
                        if (rows2[0]["customername"] != rows2[cus]["customername"]) {
                          alert("Нэг харилцагчийн бараа сонгоно уу!");
                          return;
                        }
                      }
                    }

                    if (rows2.length) {
                      var row = rows2[0];
                    } else {
                      var row = rows[0];
                    }

                    if (row['locationid']) {
                      $("#posLocationId").val(row['locationid']);
                      $(".rest-table-btn")
                        .find("div")
                        .html('[ Сонгосон ширээ: <strong class="selected-pos-location">' +
                          row['locationname'] + "</strong> ]");
                    }

                    if (($("#posRestWaiterId").val() == "" ||
                      $("#posRestSalesOrderId").val() == "" ||
                      row['locationid'] != $("#posLocationId").val()) &&
                      (row["salespersonid"] == null ||
                        row["salespersonid"] == "")) {

                      $("#posRestWaiterId").val(waiterObj[0]["id"]);
                      $("#posRestWaiter").val(
                        waiterObj[0]["salespersonname"]
                      );
                      $(".rest-table-btn")
                        .find("div")
                        .html($(".rest-table-btn").find("div").html() + "<div>[ Сонгосон зөөгч: <strong>" + waiterObj[0]["salespersonname"] + "</strong> ]</div>");
                    } else {
                      $("#posRestWaiterId").val(
                        row["salespersonid"]
                      );
                      $("#posRestWaiter").val(
                        row["salespersonname"]
                      );
                      $(".rest-table-btn")
                        .find("div")
                        .html(
                          $(".rest-table-btn").find("div").html() +
                          "<div>[ Сонгосон зөөгч: <strong>" +
                          row["salespersonname"] +
                          "</strong> ]</div>"
                        );
                    }

                    if (row.customername) {
                      $("#guestName").val(row.customername);
                    }

                    // хүснэгт дээр зөөгчийн мэдээлэл ороод ирвэл энд ширээний зөөгчийн мэдээлэл нь таар ч байгаа үгүй шалгана
                    if ((row["salespersonid"] != null && row["salespersonid"] != "" && row["salespersonid"] != undefined) && !waiterObj.length) {

                      new PNotify({
                        title: "Анхааруулга",
                        text: "Зөөгчийн мэдээлэлтэй таарахгүй байна.",
                        type: "warning",
                        sticker: false,
                      });
                      Core.unblockUI();
                      return;
                    }

                    var getCustomerItemsArray = "",
                      customerId = "";

                    if (rows2.length) {
                      getCustomerItemsArray = rows2;
                      customerId = row["customerid"];
                    } else {
                      Core.blockUI({
                        message: "Loading...",
                        boxed: true,
                      });
                      var getCustomerItems = $.ajax({
                        type: "post",
                        url: "api/callDataview",
                        data: {
                          dataviewId: "16842299439629",
                          pagingWithoutAggregate: 1,
                          criteriaData: {
                            salesOrderId: [
                              { operator: "=", operand: row["salesorderid"] }
                            ],
                            filterGuestNames: [
                              { operator: "=", operand: row["customername"] },
                            ]
                          },
                        },
                        dataType: "json",
                        async: false,
                        success: function (data) {
                          return data.result;
                        },
                      });
                      getCustomerItemsArray =
                        getCustomerItems.responseJSON.result;
                      customerId = row["customerid"];
                      Core.unblockUI();
                    }

                    var prms = {
                      status: "success",
                      data: {
                        id: row['salesorderid'],
                        locationid: $("#posLocationId").val(),
                        salespersonid: $("#posRestWaiterId").val(),
                        customerid: customerId,
                        pos_item_list_get: getCustomerItemsArray,
                      },
                    };

                    var basketParams = [
                      { id: "", event: "multiCustomer", data: prms },
                    ];
                    posFillItemsByBasket(
                      "",
                      "",
                      "",
                      "",
                      basketParams
                    );
                    $dialog.dialog("close");
                  } else {
                    alert("Жагсаалтаас сонгоно уу!");
                  }

                } else {
                  new PNotify({
                    title: "Анхааруулга",
                    text: "Нууц үг буруу байна!",
                    type: "warning",
                    sticker: false,
                  });
                }

              }
            });
        }
      },
    },
    {
      text: plang.get("close_btn"),
      class: "btn btn-sm blue-madison",
      click: function () {
        $dialogWaiterPin.dialog("close");
      },
    },
    ],
  });
  $dialogWaiterPin.dialog("open");
  Core.unblockUI();
}

function selectedRestRowFromBasketPayment($dialog) {
  isMultiCustomerPrintBill = true;
  restClears();
  Core.blockUI({
    message: "Loading...",
    boxed: true,
  });

  if (posTypeCode == 4) {
    var rows = getDataViewSelectedRows(POS_PAY_BASKET_LIST),
      rows2 = [];

    if ($("div[data-path-uniqid]").length === 1) {
      rows2 = getDataViewSelectedRows(
        $("div[data-path-uniqid]").attr("data-path-uniqid")
      );
    }
    if ($("div[data-path-uniqid]").length > 1) {
      rows2 = getDataViewSelectedRows(
        $("div[data-path-uniqid]").eq(0).attr("data-path-uniqid")
      );
      var rows22 = getDataViewSelectedRows(
        $("div[data-path-uniqid]").eq(1).attr("data-path-uniqid")
      );
      rows2 = rows2.concat(rows22);
    }

    if (rows.length || rows2.length) {
      //          if (rows.length) {
      //            for (var cus = 0; cus < rows.length; cus++) {
      //              if (rows[0]["customername"] != rows[cus]["customername"]) {
      //                alert("Нэг харилцагчийн бараа сонгоно уу!");
      //                return;
      //              }
      //            }
      //          }
      //
      //          if (rows2.length) {
      //            for (var cus = 0; cus < rows2.length; cus++) {
      //              if (rows2[0]["customername"] != rows2[cus]["customername"]) {
      //                alert("Нэг харилцагчийн бараа сонгоно уу!");
      //                return;
      //              }
      //            }
      //          }

      if (rows2.length) {
        var row = rows2[0];
      } else {
        var row = rows[0];
      }

      if (row.customername) {
        $("#guestName").val(row.customername);
      }

      var getCustomerItemsArray = "",
        customerId = "";

      if (rows2.length) {
        getCustomerItemsArray = rows2;
        customerId = row["customerid"];
      } else {
        var salesIds = '';
        for (var cus = 0; cus < rows.length; cus++) {
          salesIds += rows[cus]["salesorderid"] + ',';
        }
        salesIds = rtrim(salesIds, ',');
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
        var getCustomerItems = $.ajax({
          type: "post",
          url: "api/callDataview",
          data: {
            dataviewId: "16842299439629",
            pagingWithoutAggregate: 1,
            criteriaData: {
              salesOrderId: [
                { operator: "in", operand: salesIds }
              ],
            },
          },
          dataType: "json",
          async: false,
          success: function (data) {
            return data.result;
          },
        });
        getCustomerItemsArray =
          getCustomerItems.responseJSON.result;
        customerId = row["customerid"];
        Core.unblockUI();
      }

      var prms = {
        status: "success",
        data: {
          id: row['salesorderid'],
          locationid: $("#posLocationId").val(),
          salespersonid: $("#posRestWaiterId").val(),
          customerid: customerId,
          pos_item_list_get: getCustomerItemsArray,
        }
      };

      var basketParams = [
        { id: "", event: "multiCustomer", data: prms }
      ];
      posFillItemsByBasket(
        "",
        "",
        "onlyviewqty",
        "mergeCustomer",
        basketParams
      );
      $dialog.dialog("close");
    } else {
      alert("Жагсаалтаас сонгоно уу!");
    }
    return;
  }

  let $dialogNameWaterPin = "dialog-waiter-pincode";
  if (!$("#" + $dialogNameWaterPin).length) {
    $('<div id="' + $dialogNameWaterPin + '"></div>').appendTo("body");
  }
  let $dialogWaiterPin = $("#" + $dialogNameWaterPin);

  $dialogWaiterPin.empty().append('<form method="post" autocomplete="off" id="waiterPassForm"><input type="password" name="waiterPinCode" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>');

  $dialogWaiterPin.dialog({
    cache: false,
    resizable: true,
    bgiframe: true,
    autoOpen: false,
    title: "Нууц үг оруулах",
    width: 400,
    height: "auto",
    modal: true,
    open: function () {
      $dialogWaiterPin.on(
        "keydown",
        'input[name="waiterPinCode"]',
        function (e) {
          let keyCode = e.keyCode ? e.keyCode : e.which;
          if (keyCode === 13) {
            $(this)
              .closest(".ui-dialog")
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
            return false;
          }
        }
      );
    },
    close: function () {
      $dialogWaiterPin.empty().dialog("destroy").remove();
    },
    buttons: [{
      text: plang.get("insert_btn"),
      class: "btn btn-sm green-meadow",
      click: function () {
        PNotify.removeAll();
        let $form = $("#waiterPassForm");
        $form.validate({ errorPlacement: function () { } });

        if ($form.valid()) {
          let isPinSuccess = false,
            waiterObj = [];
          talonListPass = $form.find('input[name="waiterPinCode"]').val(),

            $.ajax({
              type: "post",
              url: "mdpos/checkTalonListPass",
              data: { talonListPass },
              dataType: "json",
              beforeSend: function () {
                Core.blockUI({
                  message: "Loading...",
                  boxed: true,
                });
              },
              success: function (dataSub) {
                let waiterId = $("#posRestWaiterId").val(),
                  salesOrderId = $("#posRestSalesOrderId").val(),
                  posLocationId = $(`#posLocationId`).val();

                if (dataSub.status == 'success') {
                  isPinSuccess = true;
                }

                Core.unblockUI();
                const dataviewId = "16207061606511";
                $.ajax({
                  type: "post",
                  url: "api/callDataview",
                  data: {
                    dataviewId,
                    criteriaData: {
                      pincode: [{
                        operator: "=",
                        operand: talonListPass
                      }]
                    }
                  },
                  dataType: "json",
                  async: false,
                  beforeSend: function () {
                    Core.blockUI({
                      message: "Loading...",
                      boxed: true,
                    });
                  },
                  success: function (dataSub) {
                    if (
                      dataSub.status == "success" &&
                      dataSub.result.length
                    ) {
                      isPinSuccess = true;
                      waiterObj = dataSub.result;
                    } else {
                      new PNotify({
                        title: "Анхааруулга",
                        text: "Зөөгчийн мэдээлэл олдсонгүй",
                        type: "warning",
                        sticker: false,
                      });
                    }
                    Core.unblockUI();
                  },
                });

                if (isPinSuccess) {
                  $dialogWaiterPin.dialog("close");
                  var rows = getDataViewSelectedRows(POS_PAY_BASKET_LIST),
                    rows2 = [];

                  if ($("div[data-path-uniqid]").length === 1) {
                    rows2 = getDataViewSelectedRows(
                      $("div[data-path-uniqid]").attr("data-path-uniqid")
                    );
                  }
                  if ($("div[data-path-uniqid]").length > 1) {
                    rows2 = getDataViewSelectedRows(
                      $("div[data-path-uniqid]").eq(0).attr("data-path-uniqid")
                    );
                    var rows22 = getDataViewSelectedRows(
                      $("div[data-path-uniqid]").eq(1).attr("data-path-uniqid")
                    );
                    rows2 = rows2.concat(rows22);
                  }

                  if (rows.length || rows2.length) {
                    if (rows.length) {
                      for (var cus = 0; cus < rows.length; cus++) {
                        if (rows[0]["customername"] != rows[cus]["customername"]) {
                          alert("Нэг харилцагчийн бараа сонгоно уу!");
                          return;
                        }
                      }
                    }

                    if (rows2.length) {
                      for (var cus = 0; cus < rows2.length; cus++) {
                        if (rows2[0]["customername"] != rows2[cus]["customername"]) {
                          alert("Нэг харилцагчийн бараа сонгоно уу!");
                          return;
                        }
                      }
                    }

                    if (rows2.length) {
                      var row = rows2[0];
                    } else {
                      var row = rows[0];
                    }

                    if (row['locationid']) {
                      $("#posLocationId").val(row['locationid']);
                      $(".rest-table-btn").find("div").html('[ Сонгосон ширээ: <strong class="selected-pos-location">' + row['locationname'] + "</strong> ]");
                    }

                    if (($("#posRestWaiterId").val() == "" ||
                      $("#posRestSalesOrderId").val() == "" ||
                      row['locationid'] != $("#posLocationId").val()) &&
                      (row["salespersonid"] == null ||
                        row["salespersonid"] == "")) {

                      $("#posRestWaiterId").val(waiterObj[0]["id"]);
                      $("#posRestWaiter").val(
                        waiterObj[0]["salespersonname"]
                      );
                      $(".rest-table-btn")
                        .find("div")
                        .html($(".rest-table-btn").find("div").html() + "<div>[ Сонгосон зөөгч: <strong>" + waiterObj[0]["salespersonname"] + "</strong> ]</div>");
                    } else {
                      $("#posRestWaiterId").val(
                        waiterObj[0]["id"]
                      );
                      $("#posRestWaiter").val(waiterObj[0]["salespersonname"]);
                      $(".rest-table-btn")
                        .find("div")
                        .html(
                          $(".rest-table-btn").find("div").html() +
                          "<div>[ Сонгосон зөөгч: <strong>" +
                          waiterObj[0]["salespersonname"] +
                          "</strong> ]</div>"
                        );
                    }

                    if (row.customername) {
                      $("#guestName").val(row.customername);
                    }

                    // хүснэгт дээр зөөгчийн мэдээлэл ороод ирвэл энд ширээний зөөгчийн мэдээлэл нь таар ч байгаа үгүй шалгана
                    if ((row["salespersonid"] != null && row["salespersonid"] != "" && row["salespersonid"] != undefined) && !waiterObj.length) {

                      new PNotify({
                        title: "Анхааруулга",
                        text: "Зөөгчийн мэдээлэлтэй таарахгүй байна.",
                        type: "warning",
                        sticker: false,
                      });
                      Core.unblockUI();
                      return;
                    }

                    var getCustomerItemsArray = "",
                      customerId = "";

                    if (rows2.length) {
                      getCustomerItemsArray = rows2;
                      customerId = row["customerid"];
                    } else {
                      var salesIds = '';
                      for (var cus = 0; cus < rows.length; cus++) {
                        salesIds += rows[cus]["salesorderid"] + ',';
                      }
                      salesIds = rtrim(salesIds, ',');
                      Core.blockUI({
                        message: "Loading...",
                        boxed: true,
                      });
                      var getCustomerItems = $.ajax({
                        type: "post",
                        url: "api/callDataview",
                        data: {
                          dataviewId: "16842299439629",
                          pagingWithoutAggregate: 1,
                          criteriaData: {
                            salesOrderId: [
                              { operator: "in", operand: salesIds }
                            ],
                          },
                        },
                        dataType: "json",
                        async: false,
                        success: function (data) {
                          return data.result;
                        },
                      });
                      getCustomerItemsArray =
                        getCustomerItems.responseJSON.result;
                      customerId = row["customerid"];
                      Core.unblockUI();
                    }

                    var prms = {
                      status: "success",
                      data: {
                        id: row['salesorderid'],
                        locationid: $("#posLocationId").val(),
                        salespersonid: $("#posRestWaiterId").val(),
                        customerid: customerId,
                        pos_item_list_get: getCustomerItemsArray,
                      },
                    };

                    var basketParams = [
                      { id: "", event: "multiCustomer", data: prms },
                    ];
                    posFillItemsByBasket(
                      "",
                      "",
                      "onlyviewqty",
                      "mergeCustomer",
                      basketParams
                    );
                    $dialog.dialog("close");
                  } else {
                    alert("Жагсаалтаас сонгоно уу!");
                  }

                } else {
                  new PNotify({
                    title: "Анхааруулга",
                    text: "Нууц үг буруу байна!",
                    type: "warning",
                    sticker: false,
                  });
                }

              }
            });
        }
      },
    },
    {
      text: plang.get("close_btn"),
      class: "btn btn-sm blue-madison",
      click: function () {
        $dialogWaiterPin.dialog("close");
      },
    },
    ],
  });
  $dialogWaiterPin.dialog("open");
  Core.unblockUI();
}

function dataviewHandlerDblClickRow1529014380513(row) {
  selectedRestRowFromBasket($('#dialog-multiuser-basket-dataview'));
}

function dataviewHandlerDblClickRow16860252240099(row) {
  selectedRestRowFromBasketPayment($('#dialog-multiuser-basket-dataview'));
}

// Pin ask dialog 
function casherCheck(metaDataCode,
  processMetaDataId,
  chooseType,
  elem,
  rows,
  paramRealPath,
  lookupMetaDataId,
  isMetaGroup,
  callback) {

  posFillItemsByBasket(metaDataCode,
    processMetaDataId,
    chooseType,
    elem,
    rows,
    paramRealPath,
    lookupMetaDataId,
    isMetaGroup,
    callback);
  return;
}

function posFillItemsByBasket(
  metaDataCode,
  processMetaDataId,
  chooseType,
  elem,
  rows,
  paramRealPath,
  lookupMetaDataId,
  isMetaGroup,
  callback
) {
  // if (posTypeCode === '4' && elem != 'mergeCustomer') {
  //     new PNotify({
  //         title: 'Info',
  //         text: 'ХАРИЛЦАГЧААР товчийг дарж захиалгаа дуудна уу.',
  //         type: 'info',
  //         sticker: false,
  //         addclass: 'pnotify-center'
  //     });
  //     return;
  // }

  var row = rows[0];
  if (!$("#lockerId").length) {
    row["typeid"] = 1;
    //    } else if (posTypeCode == '3') {
    //        row['typeid'] = 11;
  } else {
    row["typeid"] = 5;
  }

  if (elem != "mergeCustomer") {
    isMultiCustomerPrintBill = false;
  }

  if (
    posTypeCode == "4" &&
    row.isnotoktoadditems &&
    row.isnotoktoadditems == "1"
  ) {
    new PNotify({
      title: "Warning",
      text: "Check in хийгээгүй захиалга дээр бараа бичих боломжгүй!",
      type: "warning",
      sticker: false,
      addclass: "pnotify-center",
    });
    return;
  }

  $.ajax({
    type: "post",
    url: "mdpos/fillItemsByInvoiceId",
    data: { row: row },
    dataType: "json",
    beforeSend: function () {
      bpBlockMessageStart("Loading...");
    },
    success: function (data) {
      PNotify.removeAll();

      if (data.status == "success") {
        if (callback) {
          bpBlockMessageStop();
          callback(data);
          return;
        }

        /*if (posTypeCode === '4') {
  	
              var chooseCustomers = $.ajax({
                type: 'post',
                url: 'api/callDataview',
                data: {dataviewId: '1620273814067215', criteriaData: {salesOrderId: [{operator: '=', operand: row['id']}]}}, 
                dataType: 'json',
                async: false,
                success: function(data) {                            
                  return data.result;
                }
              }); 
                
              if (chooseCustomers.responseJSON.result && chooseCustomers.responseJSON.result.length > 1 && typeof $this.attr('data-customerid') === 'undefined') {                            
        
                var $dialogNameWaiter = 'dialog-chooseorder-form';
                if (!$("#" + $dialogNameWaiter).length) {
                  $('<div id="' + $dialogNameWaiter + '"></div>').appendTo('body');
                }
        
                var $dialogPWaiter = $('#' + $dialogNameWaiter);
                var selectHtml = '<div style="overflow:auto">';
                for(var i = 0; i < chooseCustomers.responseJSON.result.length; i++) {
                  selectHtml += '<div data-id="'+chooseCustomers.responseJSON.result[i]['customerid']+'" class="mb10 d-flex justify-content-start rest-choose-customer" style="background: #FFFFFF;box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.08);border-radius: 10px;cursor: pointer">';
                  selectHtml += '<div style="padding:10px;font-size:14px"><div>'+chooseCustomers.responseJSON.result[i]['customername']+'</div><div style="color:#A0A0A0;font-size:12px;" class="mt3">'+chooseCustomers.responseJSON.result[i]['customercode']+'</div></div>';
                  selectHtml += '</div>';
                }   
                selectHtml += '</div>';                    
  	
                // var $dialogNameWaiter = 'dialog-chooseorder-form';
                // if (!$("#" + $dialogNameWaiter).length) {
                //     $('<div id="' + $dialogNameWaiter + '"></div>').appendTo('body');
                // }
  	
                // var $dialogPWaiter = $('#' + $dialogNameWaiter);
                // var selectHtml = '<div style="overflow:auto">';
                // for(var i = 0; i < data.data.length; i++) {
                //     if (data.data[i]['deliverycontactname']) {
                //         selectHtml += '<div data-id="'+data.data[i]['id']+'" class="mb10 d-flex justify-content-start rest-choose-order" style="background: #FFFFFF;box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.08);border-radius: 10px;cursor: pointer">';
                //         selectHtml += '<div style="padding:10px;font-size:14px"><div>'+data.data[i]['deliverycontactname']+'</div><div style="color:#A0A0A0;font-size:12px;" class="mt3">'+data.data[i]['deliverycontactphone']+'</div></div>';
                //         selectHtml += '</div>';
                //     } else {
                //         selectHtml += '<div data-id="'+data.data[i]['id']+'" class="mb10 d-flex justify-content-start rest-choose-order" style="background: #FFFFFF;box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.08);border-radius: 10px;cursor: pointer">';
                //         selectHtml += '<div style="padding:10px;font-size:14px"><div>Нэр бөглөөгүй байна</div><div style="color:#A0A0A0;font-size:12px;" class="mt3"></div></div>';
                //         selectHtml += '</div>';                                                
                //     }
                // }   
                // selectHtml += '</div>';
  	
                $dialogPWaiter.empty().append('<form method="post" autocomplete="off" id="chooseOrderForm">'+selectHtml+'</form>');
                $dialogPWaiter.dialog({
                  cache: false,
                  resizable: false,
                  bgiframe: true,
                  autoOpen: false,
                  title: 'Харилцагч сонгох', 
                  width: 280,
                  height: 'auto',
                  maxHeight: 750,                        
                  position: {my: 'top', at: 'top+30'},
                  modal: false,
                  open: function () {
                    $dialogPWaiter.css('background-color', '#F5F5F5');
                    $dialogPWaiter.on('click', '.rest-choose-order', function(e){
  	
                      $.ajax({
                        type: 'post',
                        url: 'mdpos/orderSave',
                        data: {
                          paymentData: paymentData, 
                          itemData: itemData,
                          orderData: posTypeCode == '2' ? globalOrderData : '',
                          selectedOrderId: $(this).data('id')
                        }, 
                        dataType: 'json',
                        beforeSend: function() {
                          Core.blockUI({
                            message: 'Saving...',
                            boxed: true
                          });
                        },
                        success: function(data) {
          
                          PNotify.removeAll();
          
                          if (data.status === 'success') {                                        
                            $dialog.dialog('close');
                            new PNotify({
                              title: data.status,
                              text: data.message, 
                              type: data.status, 
                              sticker: false, 
                              addclass: 'pnotify-center'
                            });
                            $('.pos-basket-count').text(data.basketCount);
                            posDisplayReset('');
                            if ($('#posLocationId').length) {
                              $('#posLocationId').val('');
                              $('#posRestWaiterId').val('');
                            }
                              
                          } else {
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
                        
                      $dialogPWaiter.dialog('close');
                    });
                  },
                  close: function () {
                    $dialogPWaiter.empty().dialog('destroy').remove();
                  },
                  buttons: []
                });
                $dialogPWaiter.dialog('open');          
                Core.unblockUI();           
                return;                      
              }
            }*/

        posDisplayReset("", false);

        globalOrderData = data.orderData;
        coldF9 = true;
        $("#basketInvoiceId").val(row.id);
        if ((posTypeCode == "3" || posTypeCode == "4") && globalOrderData) {
          $("#basketInvoiceId").val(globalOrderData.data.id);
          $("#posRestSalesOrderId").val(globalOrderData.data.id);
        }

        if (row.hasOwnProperty("cardnumber")) {
          $("#basketCustomerId").val(row.customerid);
          $("#basketCustomerCode").val(row.customercode);
          $("#basketCustomerName").val(row.customername);
          $("#basketCardNumber").val(row.cardnumber);
          $("#basketCreatedUserId").val(row.createduserid);
        }

        if (
          data.orderData &&
          data.orderData.data.hasOwnProperty("locationid")
        ) {
          $("#posLocationId").val(data.orderData.data.locationid);
          if ($("#posRestWaiterId").val() == '') {
            $("#posRestWaiterId").val(data.orderData.data.salespersonid);
          }
        }

        var $tbody = $("#posTable").find("> tbody");

        $tbody
          .html(data.html)
          .promise()
          .done(function () {
            posConfigVisibler($tbody);
            Core.initLongInput($tbody);
            Core.initDecimalPlacesInput($tbody, 3);
            Core.initUniform($tbody);

            if (
              posTypeCode == "3" &&
              restPosEventType["event"] === "splitCalculate"
            ) {
              if (restPosEventType["data"]["islastsplit"] == "0") {
                $("#basketInvoiceId").val("");
              }
              $tbody.find("button.btn").prop("disabled", true);
              $tbody.find('input[type="text"]').prop("readonly", true);
              $tbody.find(".basket-inputqty-button").each(function () {
                $(this).find("span:eq(0)").hide();
                $(this).find("span:eq(2)").hide();
              });
            }

            if (posTypeCode == "4") {
              $('#guestName').prop('readonly', true);
              var savedCustomerId = data.data ? data.data.customerid : data.orderData.data.customerid;

              if (savedCustomerId) {
                $.ajax({
                  type: "post",
                  url: "api/callDataview",
                  data: {
                    dataviewId: "1536742182010",
                    criteriaData: {
                      id: [
                        {
                          operator: "=",
                          operand: savedCustomerId
                        }
                      ]
                    }
                  },
                  dataType: "json",
                  success: function (data) {
                    if (data.status === "success" && data.result[0]) {
                      $('input[name="empCustomerId"]').val(data.result[0]["id"]);
                      $('input[name="empCustomerId_displayField"]').val(data.result[0]["customercode"]);
                      $('input[name="empCustomerId_nameField"]').val(
                        data.result[0]["customername"]
                      );
                      $('input[name="empCustomerId"]').attr("data-row-data", JSON.stringify(data.result[0]));
                    } else {
                      $('input[name="empCustomerId"]').val("");
                      $('input[name="empCustomerId_displayField"]').val("");
                      $('input[name="empCustomerId_nameField"]').val("");
                      $('input[name="empCustomerId"]').attr("data-row-data", "");
                    }
                  }
                });
              }
            } else if (posTypeCode == "3") {
              if ($("#posLocationId").val() == '') {
                $('#guestName').prop('readonly', true);
              }
              var response = $.ajax({
                type: "post",
                url: "api/callProcess",
                data: {
                  processCode: "SOD_CUSTOMER_COUNT_004",
                  paramData: { salesOrderId: $("#posRestSalesOrderId").val() },
                },
                dataType: "json",
                async: false,
              });
              var responseParam = response.responseJSON;
              if (
                responseParam.status == "success" &&
                responseParam.result.count == 1
              ) {
                var savedCustomerId = data.data ? data.data.customerid : data.orderData.data.customerid;

                if (savedCustomerId) {
                  $.ajax({
                    type: "post",
                    url: "api/callDataview",
                    data: {
                      dataviewId: "1536742182010",
                      criteriaData: {
                        id: [{ operator: "=", operand: savedCustomerId ? savedCustomerId : '' }]
                      }
                    },
                    dataType: "json",
                    success: function (data) {
                      if (data.status === "success" && data.result[0]) {
                        $('input[name="empCustomerId"]').attr("data-row-data", JSON.stringify(data.result[0]));
                        $('input[name="empCustomerId"]').val(data.result[0]["id"]);
                        $('input[name="empCustomerId_displayField"]').val(data.result[0]["customercode"]);
                        $('input[name="empCustomerId_nameField"]').val(data.result[0]["customername"]);
                      } else {
                        $('input[name="empCustomerId"]').val("");
                        $('input[name="empCustomerId_displayField"]').val("");
                        $('input[name="empCustomerId_nameField"]').val("");
                        $('input[name="empCustomerId"]').attr("data-row-data", "");
                      }
                    },
                  });
                }
              }
            }

            posGiftRowsSetDelivery($tbody);

            var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");
            $firstRow.click();
            if (row.event === "qrcode") {
              $("#posEshopOrderTime").val(
                "Захиалсан цаг: " + globalOrderData.data.ordertime
              );
              $(".pos-quantity-input").prop("readonly", true);
            }

            posFixedHeaderTable();
            posCalcTotal();

            if (chooseType === 'onlyviewqty') {
              $tbody.find("button.btn").prop("disabled", true);
              $tbody.find('input[type="text"]').prop("readonly", true);
              $tbody.find(".basket-inputqty-button").each(function () {
                $(this).find("span:eq(0)").hide();
                $(this).find("span:eq(2)").hide();
              });
            }
          });
      } else {
        if (posTypeCode != "3") {
          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
            addclass: "pnotify-center",
          });
        }

        $("#basketInvoiceId").val("");
        if (callback) {
          bpBlockMessageStop();
          callback(false);
          return;
        }
      }

      bpBlockMessageStop();
    },
    error: function (request, status, error) {
      alert(request.responseText);
      bpBlockMessageStop();
    },
  });
}
function posNFCCardRead(elem, formName) {
  if ("WebSocket" in window) {
    var ws = new WebSocket("ws://localhost:58324/socket");
    var formCode = typeof formName !== "undefined" ? formName : "";

    ws.onopen = function () {
      var currentDateTime = GetCurrentDateTime();
      ws.send(
        '{"command":"nfc_card_read", "dateTime":"' + currentDateTime + '"}'
      );
    };

    ws.onmessage = function (evt) {
      var received_msg = evt.data;
      var jsonData = JSON.parse(received_msg);

      if ("details" in Object(jsonData)) {
        var cardObj = convertDataElementToArray(jsonData.details);

        if (formCode == "tempInvoice") {
          if (cardObj.hasOwnProperty("CardNumber") && cardObj.CardNumber) {
            var $parent = $(elem).closest(".form-group"),
              $code = $parent.find(".lookup-code-autocomplete");
            $code.val(cardObj.CardNumber);

            var e = jQuery.Event("keydown");
            e.keyCode = e.which = 13;
            $code.trigger(e);
          }
        } else {
          if (cardObj.hasOwnProperty("CardNumber") && cardObj.CardNumber) {
            $("#cardNumber").val(cardObj.CardNumber);
            $("#cardPinCode").focus();
          } else {
            $("#cardNumber").val("");
          }
        }
      } else {
        var resultJson = {
          Status: "Error",
          Error: jsonData.message,
        };
        console.log(JSON.stringify(resultJson));
      }
    };

    ws.onerror = function (event) {
      var resultJson = {
        Status: "Error",
        Error: event.code,
      };
      console.log(JSON.stringify(resultJson));
    };

    ws.onclose = function () {
      console.log("Connection is closed...");
    };
  } else {
    var resultJson = {
      Status: "Error",
      Error: "WebSocket NOT supported by your Browser!",
    };
    console.log(JSON.stringify(resultJson));
  }
}

function posElectronInvoiceList(elem) {
  var $dialogName = "dialog-talon-dataview";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdobject/dataValueViewer",
    data: {
      metaDataId: "1522395387619611",
      viewType: "detail",
      dataGridDefaultHeight: $(window).height() - 130,
      uriParams: '{"storeId": ' + posStoreId + "}",
      ignorePermission: 1,
    },
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (dataHtml) {
      Core.unblockUI();
      $dialog
        .empty()
        .append(
          '<div class="row" id="object-value-list-1522395387619611">' +
          dataHtml +
          "</div>"
        );
      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: plang.get("POS_0046"),
          width: 1000,
          height: 600,
          modal: true,
          open: function () {
            $dialog
              .find(".top-sidebar-content:eq(0)")
              .attr("style", "padding-left: 15px !important");
            $dialog.find('a[onclick*="toQuickMenu"]').remove();
          },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("close_btn"),
              class: "btn blue-madison btn-sm",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
          maximize: function () {
            $("#objectdatagrid-1522395387619611").datagrid("resize");
          },
          restore: function () {
            $("#objectdatagrid-1522395387619611").datagrid("resize");
          },
        });

      $dialog.dialog("open");
      $dialog.dialogExtend("maximize");

      $dialog.bind("dialogextendminimize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .addClass("display-none");
      });
      $dialog.bind("dialogextendmaximize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
      $dialog.bind("dialogextendrestore", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
    },
    error: function () {
      alert("Error");
    },
  });
}
function posElectronInvoiceEdit(row) {
  row["id"] = row.salesorderid;
  row["typeid"] = 1;

  $.ajax({
    type: "post",
    url: "mdpos/fillItemsByInvoiceId",
    data: { row: row },
    dataType: "json",
    beforeSend: function () {
      bpBlockMessageStart("Loading...");
    },
    success: function (data) {
      PNotify.removeAll();

      if (data.status == "success") {
        $("#dialog-talon-dataview").dialog("close");
        posDisplayReset("");

        $(".pos-invoice-number-text").val(row.ordernumber);
        $("#invoiceId").val(row.id);
        $(".pos-invoice-number").show();

        var $tbody = $("#posTable").find("> tbody");

        $tbody
          .html(data.html)
          .promise()
          .done(function () {
            posConfigVisibler($tbody);
            Core.initLongInput($tbody);
            Core.initDecimalPlacesInput($tbody);
            Core.initUniform($tbody);

            posGiftRowsSetDelivery($tbody);

            var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");
            $firstRow.click();

            posFixedHeaderTable();
            posCalcTotal();
          });
      } else {
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false,
          addclass: "pnotify-center",
        });

        $("#invoiceId").val("");
      }

      bpBlockMessageStop();
    },
    error: function (request, status, error) {
      alert(request.responseText);
      bpBlockMessageStop();
    },
  });
}
function searchPosAccountStatement(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-accounttransfer-row");
  var $bankCombo = $row.find("select");
  var bankId = $bankCombo.select2("val");

  PNotify.removeAll();

  if (bankId != "") {
    $row.css("background-color", "rgb(185, 185, 185)");

    var $dialogName = "dialog-pos-statement";
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
    var $dialog = $("#" + $dialogName);

    $.ajax({
      type: "post",
      url: "mdpos/searchAccountStatementForm",
      data: { bankId: bankId, bankName: $bankCombo.select2("data")["text"] },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        $dialog.empty().append(data.html);

        $dialog.dialog({
          cache: false,
          resizable: false,
          bgiframe: true,
          autoOpen: false,
          title: data.title,
          width: 700,
          minWidth: 700,
          height: "auto",
          modal: true,
          dialogClass: "pos-payment-dialog",
          closeOnEscape: isCloseOnEscape,
          position: { my: "top", at: "top+50" },
          close: function () {
            $row.css("background-color", "");
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: data.insert_btn,
              class: "btn btn-sm green-meadow",
              click: function () {
                PNotify.removeAll();

                var $selectedRow = $("#account-statement-list > tbody").find(
                  "tr.selected"
                );

                if ($selectedRow.length) {
                  $row
                    .find("input.bigdecimalInit")
                    .autoNumeric(
                      "set",
                      $selectedRow.find('td[data-cell-name="amount"]').text()
                    )
                    .attr("readonly", "readonly");
                  $row
                    .find('input[name="accountTransferBillingIdDtl[]"]')
                    .val(
                      $selectedRow
                        .find('td[data-cell-name="journalId"]')
                        .find("input")
                        .val()
                    );
                  $row
                    .find('input[name="accountTransferDescrDtl[]"]')
                    .val(
                      $selectedRow
                        .find('td[data-cell-name="description"]')
                        .text()
                    );

                  posSumAccountTransferAmount();

                  $dialog.dialog("close");
                } else {
                  new PNotify({
                    title: "Info",
                    text: "Та хуулганы илэрцээс нэг мөр сонгоно уу!",
                    type: "info",
                    sticker: false,
                    addclass: "pnotify-center",
                  });
                }
              },
            },
            {
              text: data.close_btn,
              class: "btn btn-sm blue-hoki",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });
        $dialog.dialog("open");

        Core.unblockUI();
      },
      error: function () {
        alert("Error");
      },
    }).done(function () {
      Core.initClean($dialog);
    });
  } else {
    new PNotify({
      title: "Info",
      text: plang.get("POS_0028"),
      type: "info",
      sticker: false,
      addclass: "pnotify-center",
    });
  }

  return;
}

function filterAccountStatement(bankId) {
  PNotify.removeAll();

  var statementAmount = Number(
    $("#statementAmount:visible").autoNumeric("get")
  );
  var statementDescr = $("#statementDescr:visible").val().trim();
  var statementId = $("#statementId:visible").val().trim();

  if ((statementAmount > 0 && statementDescr != "") || statementId != "") {
    $.ajax({
      type: "post",
      url: "mdpos/filterAccountStatement",
      data: {
        bankId: bankId,
        amount: statementAmount,
        descr: statementDescr,
        statementId: statementId,
        billingId: $(".pos-accounttransfer-row-dtl").find("input").serialize(),
      },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Хуулгаас хайж байна...",
          boxed: true,
        });
      },
      success: function (dataSub) {
        Core.unblockUI();

        if (dataSub.status == "success") {
          var htmlTableRows = "";
          var billingRows = dataSub.billingRows;
          var billingRowsLength = billingRows.length;
          var i = 0,
            n = 1;

          for (i; i < billingRowsLength; i++) {
            htmlTableRows += '<tr class="cursor-pointer">';
            htmlTableRows += "<td>" + n++ + "</td>";
            htmlTableRows +=
              '<td data-cell-name="check" class="text-center"></td>';
            htmlTableRows +=
              '<td data-cell-name="journalId">' +
              billingRows[i]["JOURNAL_ID"] +
              '<input type="hidden" value="' +
              billingRows[i]["ID"] +
              '"></td>';
            htmlTableRows += "<td>" + billingRows[i]["BILL_DATE"] + "</td>";
            htmlTableRows +=
              '<td data-cell-name="amount">' +
              billingRows[i]["AMOUNT"] +
              "</td>";
            htmlTableRows +=
              '<td data-cell-name="description">' +
              billingRows[i]["DESCRIPTION"] +
              "</td>";
            htmlTableRows += "</tr>";
          }

          $("#account-statement-list > tbody").empty().append(htmlTableRows);
        } else {
          if (dataSub.hasOwnProperty("message")) {
            new PNotify({
              title: dataSub.status,
              text: dataSub.message,
              type: dataSub.status,
              sticker: false,
              addclass: "pnotify-center",
            });
          } else {
            new PNotify({
              title: "Error",
              text: "Unknown error",
              type: "error",
              sticker: false,
              addclass: "pnotify-center",
            });
          }

          $("#account-statement-list > tbody").empty();
        }
      },
    });
  } else {
    new PNotify({
      title: "Info",
      text: "Дүн болон Гүйлгээний утга эсвэл Гүйлгээний дугаарыг оруулна уу!",
      type: "info",
      sticker: false,
      addclass: "pnotify-center",
    });
  }
}
function posCoupenCandy(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-candy-coupon-row");

  $row
    .css("background-color", "rgb(185, 185, 185)")
    .addClass("candy-selected-row");

  var $dialogName = "dialog-pos-candy-coupen";
  $('<div id="' + $dialogName + '"></div>').appendTo("body");
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdpos/candyCoupen",
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading ...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data.html);
      $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: 500,
        minWidth: 500,
        height: "auto",
        modal: true,
        dialogClass: "pos-payment-dialog",
        closeOnEscape: isCloseOnEscape,
        close: function () {
          $row.css("background-color", "").removeClass("candy-selected-row");
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [
          {
            text: data.close_btn,
            class: "btn btn-sm blue-hoki",
            click: function () {
              $dialog.dialog("close");
            },
          },
        ],
      });
      $dialog.dialog("open");
      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initClean($dialog);
  });
}
function candyCoupenQRCodeRead(qrcode) {
  var $this = $(this),
    $row = $this.closest(".pos-candy-coupon-row");

  $.ajax({
    type: "post",
    url: "mdpos/candyCoupenCode",
    data: { qrCode: qrcode },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Checking...",
        boxed: true,
      });
    },
    success: function (response) {
      Core.unblockUI();

      if (response.status == "success") {
        new PNotify({
          title: response.status,
          text: response.message.description,
          type: response.status,
          sticker: false,
          addclass: "pnotify-center",
        });

        $('input[name="candyCouponAmountDtl[]"]').autoNumeric(
          "set",
          response.message.couponAmount
        );
        $('input[name="candyCouponDetectedNumberDtl[]"]').val(
          response.message.couponCode
        );
        $('input[name="candyCouponTransactionIdDtl[]"]').val("");
        posSumCandyCouponAmount();

        $("#dialog-pos-candy-coupen").dialog("close");
      } else {
        new PNotify({
          title: response.status,
          text: response.message,
          type: response.status,
          sticker: false,
          addclass: "pnotify-center",
        });
      }
    },
  });
}
function posSearchCandy(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-candy-row");

  $row
    .css("background-color", "rgb(185, 185, 185)")
    .addClass("candy-selected-row");

  var $dialogName = "dialog-pos-candy";
  $('<div id="' + $dialogName + '"></div>').appendTo("body");
  var $dialog = $("#" + $dialogName);
  var monAmount = $row.find(".bigdecimalInit").autoNumeric("get");
  if (monAmount === "") {
    monAmount = $("#posCashAmount").autoNumeric("get");
  }

  $.ajax({
    type: "post",
    url: "mdpos/searchCandy",
    data: { amount: monAmount },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data.html);

      $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: 500,
        minWidth: 500,
        height: "auto",
        modal: true,
        dialogClass: "pos-payment-dialog",
        closeOnEscape: isCloseOnEscape,
        open: function () {
          $("#candyTypeCode").trigger("change");
        },
        close: function () {
          $row.css("background-color", "").removeClass("candy-selected-row");
          clearInterval(posCandyQrCheckInterval);
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [
          {
            text: "ТАН код илгээх",
            class: "btn btn-sm btn-primary float-left candy-tancode-send hide",
            click: function () {
              PNotify.removeAll();

              var phoneNumber = $("#candyNumber").val().trim();
              var amount = $("#candyAmount").autoNumeric("get");

              if (phoneNumber && amount) {
                $.ajax({
                  type: "post",
                  url: "mdpos/candySendTanCode",
                  data: { phoneNumber: phoneNumber, amount: amount },
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      message: "Sending...",
                      boxed: true,
                    });
                  },
                  success: function (dataTanCode) {
                    Core.unblockUI();
                    new PNotify({
                      title: dataTanCode.status,
                      text: dataTanCode.message,
                      type: dataTanCode.status,
                      sticker: false,
                      addclass: "pnotify-center",
                    });

                    if (dataTanCode.status == "success") {
                      $(".candy-tancode-confirm").removeClass("hide");
                      $("#candyTanCode").removeAttr("readonly").focus();
                    }
                  },
                });
              } else {
                $("#candyNumber").focus();
                new PNotify({
                  title: "Warning",
                  text: "Утасны дугаар болон дүнг бөглөнө үү!",
                  type: "warning",
                  sticker: false,
                  addclass: "pnotify-center",
                });
              }
            },
          },
          {
            text: "ТАН КОД шалгах",
            class:
              "btn btn-sm btn-warning float-left candy-tancode-confirm hide",
            click: function () {
              PNotify.removeAll();

              var phoneNumber = $("#candyNumber").val().trim();
              var amount = $("#candyAmount").autoNumeric("get");
              var tanCode = $("#candyTanCode").val().trim();

              if (phoneNumber && amount && tanCode) {
                $.ajax({
                  type: "post",
                  url: "mdpos/candyConfirmTanCode",
                  data: {
                    phoneNumber: phoneNumber,
                    amount: amount,
                    tanCode: tanCode,
                  },
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      message: "Checking...",
                      boxed: true,
                    });
                  },
                  success: function (dataTanCode) {
                    Core.unblockUI();
                    new PNotify({
                      title: dataTanCode.status,
                      text: dataTanCode.message,
                      type: dataTanCode.status,
                      sticker: false,
                      addclass: "pnotify-center",
                    });

                    if (dataTanCode.status == "success") {
                      $row.find(".bigdecimalInit").autoNumeric("set", amount);
                      $row
                        .find('input[name="candyTypeCodeDtl[]"]')
                        .val($("#candyTypeCode").select2("val"));
                      $row
                        .find('input[name="candyDetectedNumberDtl[]"]')
                        .val(phoneNumber);
                      $row
                        .find('input[name="candyTransactionIdDtl[]"]')
                        .val(dataTanCode.transactionId);

                      posSumCandyAmount();

                      $dialog.dialog("close");
                    }
                  },
                });
              } else {
                $("#candyTanCode").focus();
                new PNotify({
                  title: "Warning",
                  text: "Талбаруудыг бөглөнө үү!",
                  type: "warning",
                  sticker: false,
                  addclass: "pnotify-center",
                });
              }
            },
          },
          {
            text: "ПИН КОД шалгах",
            class:
              "btn btn-sm btn-warning float-left candy-pincode-confirm hide",
            click: function () {
              PNotify.removeAll();

              var cardNumber = $("#candyNumber").val().trim();
              var amount = $("#candyAmount").autoNumeric("get");
              var pinCode = $("#candyPinCode").val().trim();

              if (cardNumber && amount && pinCode) {
                var candyTypeCode = $("#candyTypeCode").select2("val");

                $.ajax({
                  type: "post",
                  url: "mdpos/candyConfirmPinCode",
                  data: {
                    cardNumber: cardNumber,
                    amount: amount,
                    pinCode: pinCode,
                    typeCode: candyTypeCode,
                  },
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      message: "Checking...",
                      boxed: true,
                    });
                  },
                  success: function (dataPinCode) {
                    Core.unblockUI();
                    new PNotify({
                      title: dataPinCode.status,
                      text: dataPinCode.message,
                      type: dataPinCode.status,
                      sticker: false,
                      addclass: "pnotify-center",
                    });

                    if (dataPinCode.status == "success") {
                      $row.find(".bigdecimalInit").autoNumeric("set", amount);
                      $row
                        .find('input[name="candyTypeCodeDtl[]"]')
                        .val(candyTypeCode);
                      $row
                        .find('input[name="candyDetectedNumberDtl[]"]')
                        .val(cardNumber);
                      $row
                        .find('input[name="candyTransactionIdDtl[]"]')
                        .val(dataPinCode.transactionId);

                      posSumCandyAmount();

                      $dialog.dialog("close");
                    }
                  },
                });
              } else {
                $("#candyPinCode").focus();
                new PNotify({
                  title: "Warning",
                  text: "Талбаруудыг бөглөнө үү!",
                  type: "warning",
                  sticker: false,
                  addclass: "pnotify-center",
                });
              }
            },
          },
          {
            text: "QR CODE үүсгэх",
            class: "btn btn-sm btn-primary float-left candy-qr-generate hide",
            click: function () {
              PNotify.removeAll();

              var amount = $("#candyAmount").autoNumeric("get");

              if (amount) {
                $.ajax({
                  type: "post",
                  url: "mdpos/candyGenerateQrCode",
                  data: { amount: amount },
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      message: "Generating...",
                      boxed: true,
                    });
                  },
                  success: function (dataQrCode) {
                    Core.unblockUI();

                    if (dataQrCode.status == "success") {
                      candyQrUuid = dataQrCode.uuid;
                      candyQRCodeShow($row, amount, dataQrCode);

                      posCandyQrCheckInterval = setInterval(function () {
                        candyCheckQrCode(candyQrUuid, amount, $row);
                      }, 3000);
                    } else {
                      new PNotify({
                        title: dataQrCode.status,
                        text: dataQrCode.message,
                        type: dataQrCode.status,
                        sticker: false,
                        addclass: "pnotify-center",
                      });
                    }
                  },
                });
              } else {
                $("#candyAmount").focus();
                new PNotify({
                  title: "Warning",
                  text: "Дүнг бөглөнө үү!",
                  type: "warning",
                  sticker: false,
                  addclass: "pnotify-center",
                });
              }
            },
          },
          {
            text: "QR CODE шалгах",
            class: "btn btn-sm btn-primary float-left candy-qrcode-read hide",
            click: function () {
              candyQRCodeRead($("#candyNumber").val());
            },
          },
          {
            text: data.close_btn,
            class: "btn btn-sm blue-hoki",
            click: function () {
              $dialog.dialog("close");
            },
          },
        ],
      });
      $dialog.dialog("open");

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initClean($dialog);
    clearInterval(posCandyQrCheckInterval);
  });
}

function candyQRCodeShow(row, amount, data) {
  var $dialogName = "dialog-pos-candyqr";
  $('<div id="' + $dialogName + '"></div>').appendTo("body");
  var $dialog = $("#" + $dialogName);

  $dialog.empty().append(data.html);

  $dialog.dialog({
    cache: false,
    resizable: false,
    bgiframe: true,
    autoOpen: false,
    title: data.title,
    width: 420,
    minWidth: 420,
    height: "auto",
    modal: true,
    dialogClass: "pos-payment-dialog",
    closeOnEscape: isCloseOnEscape,
    close: function () {
      clearInterval(posCandyQrCheckInterval);
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: [
      {
        text: "Дахин QR CODE үүсгэх",
        class: "btn btn-sm btn-primary float-left",
        click: function () {
          PNotify.removeAll();

          $.ajax({
            type: "post",
            url: "mdpos/candyGenerateQrCode",
            data: { amount: amount },
            dataType: "json",
            beforeSend: function () {
              Core.blockUI({
                message: "Generating ...",
                boxed: true,
              });
            },
            success: function (dataQrCode) {
              Core.unblockUI();
              if (dataQrCode.status == "success") {
                candyQrUuid = dataQrCode.uuid;
                $dialog.html(dataQrCode.html);

                posCandyQrCheckInterval = setInterval(function () {
                  candyCheckQrCode(candyQrUuid, amount, row);
                }, 3000);
              } else {
                new PNotify({
                  title: dataQrCode.status,
                  text: dataQrCode.message,
                  type: dataQrCode.status,
                  sticker: false,
                  addclass: "pnotify-center",
                });
              }
            },
          });
        },
      },
      // {text: 'QR CODE шалгах', class: 'btn btn-sm btn-warning float-left', click: function() {
      //     PNotify.removeAll();
      //     candyCheckQrCode(candyQrUuid, amount, row);
      // }},
      {
        text: data.close_btn,
        class: "btn btn-sm blue-hoki",
        click: function () {
          $dialog.dialog("close");
          clearInterval(posCandyQrCheckInterval);
        },
      },
    ],
  });
  $dialog.dialog("open");
}

function candyCheckQrCode(uuid, amount, row) {
  row
    .css("background-color", "rgb(185, 185, 185)")
    .addClass("candy-selected-row");

  $.ajax({
    type: "post",
    url: "mdpos/candyCheckQrCode",
    data: { uuid: uuid },
    dataType: "json",
    success: function (dataQrCode) {
      Core.unblockUI();
      if (dataQrCode.status == "success") {
        row.find(".bigdecimalInit").autoNumeric("set", amount);
        row
          .find('input[name="candyTypeCodeDtl[]"]')
          .val($("#candyTypeCode").select2("val"));
        row.find('input[name="candyDetectedNumberDtl[]"]').val("");
        row.find('input[name="candyTransactionIdDtl[]"]').val("");
        row.find(".fa-qrcode").parent().hide();
        posSumCandyAmount();

        new PNotify({
          title: dataQrCode.status,
          text: dataQrCode.message,
          type: dataQrCode.status,
          sticker: false,
          addclass: "pnotify-center",
        });
        clearInterval(posCandyQrCheckInterval);
        $("#dialog-pos-candyqr").dialog("close");
        $("#dialog-pos-candy").dialog("close");
      }
    },
  });
}

function candyQRCodeRead(qrCode) {
  $("#candyNumber").val(qrCode);
  clearInterval(posCandyQrCheckInterval);
  PNotify.removeAll();
  new PNotify({
    title: "Info",
    text: "QRcode амжилттай уншлаа.",
    type: "info",
    sticker: false,
    addclass: "pnotify-center",
  });

  var amount = $("#candyAmount").autoNumeric("get");

  if (amount) {
    $.ajax({
      type: "post",
      url: "mdpos/candyChargeQrCode",
      data: { qrCode: qrCode, amount: amount },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Checking ...",
          boxed: true,
        });
      },
      success: function (dataQrCode) {
        if (dataQrCode.status == "success") {
          var $row = $(".candy-selected-row");

          posCandyQrCheckInterval = setInterval(
            function (a, b, c) {
              candyCheckQrCode(a, b, c);
            },
            3000,
            dataQrCode.uuid,
            amount,
            $row
          );
        } else {
          new PNotify({
            title: dataQrCode.status,
            text: dataQrCode.message,
            type: dataQrCode.status,
            sticker: false,
            addclass: "pnotify-center",
          });
          Core.unblockUI();
        }
      },
    });
  } else {
    $("#candyAmount").focus();
    PNotify.removeAll();
    new PNotify({
      title: "Warning",
      text: "Дүнг бөглөнө үү!",
      type: "warning",
      sticker: false,
      addclass: "pnotify-center",
    });
  }
}

function cashbackAction(elem, phone, action) {
  var $html = "";

  var $dialogName = "dialog-pos-candyqr";

  $('<div id="' + $dialogName + '"></div>').appendTo("body");
  var $dialog = $("#" + $dialogName);

  $dialog
    .empty()
    .append(
      '<div class="row cashbackActionWindow ">' +
      '<form method="post" class="d-flex justify-content-center ml35" autocomplete="off" id="cactionForm">' +
      '<label for="cphone" class="mr15">Утас:</label><br>' +
      '<input type="text" id="cphone"class="pl10" name="cphone" value="' +
      phone +
      '"><br>' +
      '<input type="hidden" id="caction" name="caction" value="' +
      action +
      '"><br>' +
      "</form> " +
      "</div>"
    );
  var $form = $("#cactionForm");
  $dialog
    .dialog({
      cache: false,
      resizable: false,
      bgiframe: true,
      autoOpen: false,
      title: "Утасны дугаар",
      width: 420,
      minWidth: 420,
      height: "auto",
      modal: true,
      dialogClass: "pos-cashback-dialog",
      closeOnEscape: isCloseOnEscape,
      close: function () {
        $dialog.empty().dialog("destroy").remove();
      },
      buttons: [
        {
          text: plang.get("action_cb_" + action),
          class: "btn btn-sm btn-primary float-right",
          id: "dialog_accept_phone",
          click: function () {
            PNotify.removeAll();
            var $infodesc = $(".cashinfodescription");
            var $infophone = $(".candyPhone");
            $.ajax({
              type: "post",
              url: "mdpos/candyCashBackAction",
              data: $form.serialize(),
              dataType: "json",
              beforeSend: function () {
                Core.blockUI({
                  message: "түр хүлээн үү",
                  boxed: true,
                });
              },
              success: function (response) {
                if (response.status == "success") {
                  $infophone.empty().append(response.message);
                  $infodesc.empty().append(response.info);
                  setTimeout(function () {
                    $infodesc.addClass("hide");
                  }, 3000);
                } else {
                  new PNotify({
                    title: response.status,
                    text: response.message,
                    type: response.status,
                    sticker: false,
                    addclass: "pnotify-center",
                  });
                }
                $dialog.dialog("close");
                Core.unblockUI();
              },
            });
          },
        },

        {
          text: $html.close_btn,
          class: "hide",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ],
    })
    .keyup(function (e) {
      if (e.keyCode == 13) $("#dialog_accept_phone").trigger("click");
    });
  $dialog.dialog("open");
}

function posItemCombogridList(tbltIds, focusMode, customerId) {
  customerId = typeof customerId === "undefined" ? "" : customerId;
  var colsObj = [],
    queryParams = { tbltIds: tbltIds, customerId: customerId };

  if (tempInvKeyField === "locker") {
    colsObj.push({
      field: "itemtypename",
      title: plang.get("pos_auto_itemtypename"),
      width: 30,
      sortable: true,
    });
    queryParams["isSpecialUse"] = $("#specialLocker").val();
  }

  colsObj.push({
    field: "itemcode",
    title: plang.get("POS_0003"),
    width: 18,
    sortable: true,
  });
  colsObj.push({
    field: "itemname",
    title: plang.get("POS_0004"),
    width: 68,
    sortable: true,
  });

  if (tempInvKeyField === "locker") {
    colsObj.push({
      field: "saleprice",
      title: plang.get("item_sale_price"),
      width: 14,
      sortable: true,
      formatter(val) {
        return pureNumberFormat(val);
      },
    });
  } else {
    colsObj.push({
      field: "barcode",
      title: plang.get("item_barcode"),
      width: 14,
      sortable: true,
    });
    colsObj.push({
      field: "modelcode",
      title: plang.get("pos_item_model"),
      width: 18,
      sortable: true,
    });
  }
  console.log("posItemCombogridList...");

  $("#scanItemCode").combogrid({
    panelWidth: 800,
    panelHeight: 400,
    url: "mdpos/getItemList",
    queryParams: queryParams,
    idField: "itemid",
    textField: "itemname",
    mode: "remote",
    fitColumns: true,
    pagination: true,
    rownumbers: true,
    remoteSort: true,
    pageList: [10, 20, 50, 100],
    pageSize: 50,
    columns: [colsObj],
    onClickRow: function (index, row) {
      if (posTypeCode == "3" && isTouchEnabled) {
        var $scanItemCode = $("#scanItemCode");

        $scanItemCode.val(row.itemcode);

        var e = jQuery.Event("keydown");
        e.keyCode = e.which = 13;
        $scanItemCode.trigger(e);

        $scanItemCode.combogrid("hidePanel");
        $scanItemCode.combogrid("clear", "");

        $scanItemCode.val("");
      }
    },
    onDblClickRow: function (index, row) {
      var $scanItemCode = $("#scanItemCode");

      $scanItemCode.val(row.itemcode);

      var e = jQuery.Event("keydown");
      e.keyCode = e.which = 13;
      $scanItemCode.trigger(e);

      $scanItemCode.combogrid("hidePanel");
      $scanItemCode.combogrid("clear", "");

      $scanItemCode.val("");
      //posItemCombogridList('');
    },
    onLoadSuccess: function (data) {
      if (
        ($("body").find("#dialog-pos-payment").length > 0 &&
          $("body").find("#dialog-pos-payment").is(":visible")) ||
        isClickF5
      ) {
        $("#scanItemCode").combogrid("hidePanel");
        isClickF5 = false;
      }
      if (isItemSearchEmptyFocus) {
        $(".pos-item-combogrid-cell")
          .find("input.textbox-text")
          .val("")
          .focus();
        isItemSearchEmptyFocus = false;
      }
      $(this)
        .combogrid("grid")
        .datagrid("getPanel")
        .find(".datagrid-row")
        .css("height", "34px");
    },
    keyHandler: $.extend({}, $.fn.combogrid.defaults.keyHandler, {
      enter: function (e) {
        var target = this;
        var state = $.data(target, "combogrid");
        var grid = state.grid;
        var row = grid.datagrid("getSelected");

        if (row) {
          var $scanItemCode = $("#scanItemCode");

          $scanItemCode.val(row.itemcode);

          var e = jQuery.Event("keydown");
          e.keyCode = e.which = 13;
          $scanItemCode.trigger(e);

          $scanItemCode.combogrid("hidePanel");
          $scanItemCode.combogrid("clear");
          $scanItemCode.val("");
          //posItemCombogridList('');
        } else {
          var scannerValue = $(".pos-item-combogrid-cell")
            .find("input.textbox-text")
            .val(),
            $scanItemCode = $("#scanItemCode");

          $scanItemCode.val(scannerValue);

          var e = jQuery.Event("keydown");
          e.keyCode = e.which = 13;
          $scanItemCode.trigger(e);

          //posItemCombogridList('');
          $(".pos-item-combogrid-cell")
            .find("input.textbox-text, input.textbox-value, #scanItemCode")
            .val("");
        }
      },
    }),
  });
}

function posBeforePrintAskLoyaltyPoint(paymentData) {
  $.ajax({
    type: "post",
    url: "mdpos/printAskLoyaltyPoint",
    data: paymentData,
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      if (data.status == "success") {
        var $dialogName = "dialog-pos-loyaltypoint";
        $('<div id="' + $dialogName + '"></div>').appendTo("body");
        var $dialog = $("#" + $dialogName);

        $dialog.empty().append(data.html);
        $dialog.dialog({
          cache: false,
          resizable: false,
          bgiframe: true,
          autoOpen: false,
          title: data.title,
          width: 550,
          minWidth: 550,
          height: "auto",
          modal: true,
          dialogClass: "pos-payment-dialog",
          closeOnEscape: isCloseOnEscape,
          position: { my: "top", at: "top+25" },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: data.print_btn,
              class: "btn btn-primary float-left",
              click: function () {
                isBeforePrintAskLoyaltyPoint = false;

                posBillPrint();
              },
            },
            {
              text: data.close_btn,
              class: "btn blue-hoki",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });
        Core.initSelect2($dialog);
        Core.initDecimalPlacesInput($dialog);

        $dialog.dialog("open");
      } else if (data.status == "directprint") {
        isBeforePrintAskLoyaltyPoint = false;
        posBillPrint();
      } else {
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false,
        });
      }

      Core.unblockUI();
    },
  });
}

function posRedPointItemList() {
  $.ajax({
    type: "post",
    url: "mdpos/redPointItems",
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      if (data.status == "success") {
        var $dialogName = "dialog-pos-redpoint-items";
        $('<div id="' + $dialogName + '"></div>').appendTo("body");
        var $dialog = $("#" + $dialogName);

        $dialog.empty().append(data.html);
        $dialog.dialog({
          cache: false,
          resizable: false,
          bgiframe: true,
          autoOpen: false,
          title: data.title,
          width: 1000,
          minWidth: 1000,
          height: "auto",
          modal: true,
          dialogClass: "pos-payment-dialog",
          closeOnEscape: isCloseOnEscape,
          position: { my: "top", at: "top+0" },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: data.insert_btn,
              class: "btn btn-sm green-meadow",
              click: function () {
                PNotify.removeAll();

                var $redPointItems = $(".pos-redpoint-item-selected");

                if ($redPointItems.length) {
                  $.ajax({
                    type: "post",
                    url: "mdpos/fillRedPointItems",
                    data: $(".pos-redpoint-item-selected")
                      .find("input")
                      .serialize(),
                    dataType: "json",
                    beforeSend: function () {
                      Core.blockUI({
                        message: "Loading...",
                        boxed: true,
                      });
                    },
                    success: function (dataSub) {
                      if (dataSub.status == "success") {
                        $dialog.dialog("close");

                        posDisplayReset("");

                        var $tbody = $("#posTable").find("> tbody");

                        $tbody
                          .html(dataSub.html)
                          .promise()
                          .done(function () {
                            posConfigVisibler($tbody);
                            Core.initLongInput($tbody);
                            Core.initDecimalPlacesInput($tbody);
                            Core.initUniform($tbody);

                            $tbody.find("button.btn").prop("disabled", true);
                            $tbody
                              .find('input[type="text"]')
                              .prop("readonly", true);

                            $("#scanItemCode").combogrid("disable");

                            isDisableRowDiscountInput = true;

                            $(".pos-footer-msg").text("RedPoint");

                            posGiftRowsSetDelivery($tbody);

                            var $firstRow = $tbody.find(
                              "tr[data-item-id]:eq(0)"
                            );
                            $firstRow.click();

                            posFixedHeaderTable();
                            posCalcTotal();
                          });
                      } else {
                        new PNotify({
                          title: dataSub.status,
                          text: dataSub.message,
                          type: dataSub.status,
                          sticker: false,
                        });
                      }

                      Core.unblockUI();
                    },
                  });
                } else {
                  new PNotify({
                    title: "Info",
                    text: "Бараа сонгоогүй байна.",
                    type: "info",
                    sticker: false,
                  });
                }
              },
            },
            {
              text: data.close_btn,
              class: "btn btn-sm blue-hoki",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });

        $dialog.dialog("open");
      } else {
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false,
        });
      }

      Core.unblockUI();
    },
  });
}

function posItemQtyInputFocus() {
  var $posBody = $("#posTable > tbody");

  if ($posBody.find("> tr[data-item-id]").length == 0) {
    new PNotify({
      title: "Warning",
      text: plang.get("POS_0022"),
      type: "warning",
      sticker: false,
      addclass: "pnotify-center",
    });
  } else {
    var $selectedRow = $posBody.find("> tr.pos-selected-row");
    if ($selectedRow.length) {
      $selectedRow.find("input.pos-quantity-input").focus().select();
    } else {
      $posBody.find("> tr:eq(0) input.pos-quantity-input").focus().select();
    }
  }
}
function posFocusBillType() {
  var $posBillType = $('input[name="posBillType"]:checked');

  if ($posBillType.length) {
    var billType = $posBillType.val();

    if (billType == "person") {
      $('input[name="posBillType"][value="organization"]').click();
    } else {
      $('input[name="posBillType"][value="person"]').click();
    }
  }
}
function posTalonListReturnProcessCall() {
  var $btn = $('a[data-dvbtn-processcode="posTalonReturnCancel"]');
  if ($btn.length) {
    $btn.click();
  }
}
function posBasketListClickBtn() {
  var $basketListBtn = $(".pos-header-basket");
  if ($basketListBtn.length) {
    $basketListBtn.click();
  }
}
function posBankComboOpen(elem) {
  var isInput = typeof elem == "undefined" ? false : true;

  if (isInput) {
    var $bankRow = elem.closest(".pos-bank-row");
    if ($bankRow.length) {
      $bankRow.find('select[name="posBankIdDtl[]"]').select2("open");
      return;
    }
  }

  $('select[name="posBankIdDtl[]"]:visible:eq(0)').select2("open");
  return;
}
function posTalonNotLotteryPrintCall() {
  var $btn = $('a[data-dvbtn-processcode="posTalonNotLotteryPrint"]');

  if ($btn.length) {
    $btn.click();
    return true;
  }

  return false;
}
function posBankNotesPrint() {
  $.ajax({
    type: "post",
    url: "mdpos/bankNotesPrint",
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Printing...",
        boxed: true,
      });
    },
    success: function (data) {
      $("div.pos-preview-print")
        .html(data.html)
        .promise()
        .done(function () {
          $("div.pos-preview-print").printThis({
            debug: false,
            importCSS: false,
            printContainer: false,
            dataCSS: data.css,
            removeInline: false,
          });
        });

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
      Core.unblockUI();
    },
  });

  return;
}
function posNoPayment(isAuto) {
  if ($(".blockOverlay").length) {
    return;
  }

  PNotify.removeAll();

  var $posTableBody = $("#posTable > tbody");

  // Check item list
  if ($posTableBody.find("> tr[data-item-id]").length == 0) {
    new PNotify({
      title: "Warning",
      text: plang.get("POS_0022"),
      type: "warning",
      sticker: false,
      addclass: "pnotify-center",
    });

    return;
  }

  if (posOrderTimer && isBasketOnly) {
    $(".posTimerInit").countdown("option", { until: posOrderTimer });
    $(".posTimerInit").countdown("pause");
  }

  var paymentData = {
    amount: $(".pos-amount-paid").autoNumeric("get"),
  };

  var isBasketSelected = false;

  if ($("#basketCustomerId").val() != "") {
    paymentData["customerId"] = $("#basketCustomerId").val();
    paymentData["customerCode"] = $("#basketCustomerCode").val();
    paymentData["customerName"] = $("#basketCustomerName").val();
    paymentData["customerCardNumber"] = $("#basketCardNumber").val();
    paymentData["createdUserId"] = $("#basketCreatedUserId").val();
    isBasketSelected = true;
  }

  $.ajax({
    type: "post",
    url: "mdpos/basketForm",
    data: paymentData,
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      if (data.status != "success") {
        PNotify.removeAll();
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false,
        });
        Core.unblockUI();
        return;
      }

      if (data.type === "locker") {
        var paymentData =
          "lockerId=" +
          ($("#lockerId").length ? $("#lockerId").val() : "") +
          "&lockerOrderId=" +
          ($("#lockerOrderId").length ? $("#lockerOrderId").val() : "") +
          "&windowSessionId=" +
          ($("#windowSessionId").length ? $("#windowSessionId").val() : "") +
          "&isBasket=1&payAmount=" +
          $(".pos-amount-paid").autoNumeric("get"),
          itemData = $posTableBody.find("input").serialize(),
          vatAmount = $(".pos-amount-vat").autoNumeric("get"),
          cityTaxAmount = $(".pos-amount-citytax").autoNumeric("get"),
          discountAmount = $(".pos-amount-discount").autoNumeric("get");

        paymentData =
          paymentData +
          "&vatAmount=" +
          vatAmount +
          "&cityTaxAmount=" +
          cityTaxAmount +
          "&discountAmount=" +
          discountAmount;
        paymentData +=
          "&isBasketSelected=1&basketInvoiceId=" + $("#basketInvoiceId").val();

        $.ajax({
          type: "post",
          url: "mdpos/orderSaveNotSendVat",
          data: { paymentData: paymentData, itemData: itemData },
          dataType: "json",
          beforeSend: function () {
            Core.blockUI({
              message: "Saving...",
              boxed: true,
            });
          },
          success: function (data) {
            PNotify.removeAll();

            if (data.status === "success") {
              if (typeof isAuto !== "undefined") {
                data.message =
                  data.message + "<hr/>" + plang.get("posOrderTimerMessage");
              }
              new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false,
              });
              if (data.printData !== "") {
                $("div.pos-preview-print")
                  .html(data.printData)
                  .promise()
                  .done(function () {
                    $("div.pos-preview-print").printThis({
                      debug: false,
                      importCSS: false,
                      printContainer: false,
                      dataCSS: data.css,
                      removeInline: false,
                    });
                  });
              }
              $(".pos-basket-count").text(data.basketCount);
              posDisplayReset("");
            } else {
              new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false,
              });
            }

            Core.unblockUI();
          },
        });
        return;
      }

      var $dialogName = "dialog-pos-basket";
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
      var $dialog = $("#" + $dialogName);

      $dialog.empty().append(data.html);

      $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: data.title,
        width: 600,
        minWidth: 600,
        height: "auto",
        modal: true,
        dialogClass: "pos-payment-dialog",
        closeOnEscape: isCloseOnEscape,
        open: function () {
          disableScrolling();
        },
        close: function () {
          enableScrolling();
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: [
          {
            text: data.save_btn,
            class: "btn btn-sm green-meadow pos-order-save",
            click: function () {
              var $form = $("#pos-basket-form");
              $form.validate({ errorPlacement: function () { } });

              if ($form.valid()) {
                var paymentData = $form.serialize(),
                  itemData = $posTableBody.find("input").serialize(),
                  vatAmount = $(".pos-amount-vat").autoNumeric("get"),
                  cityTaxAmount = $(".pos-amount-citytax").autoNumeric("get"),
                  discountAmount = $(".pos-amount-discount").autoNumeric("get");

                paymentData =
                  paymentData +
                  "&vatAmount=" +
                  vatAmount +
                  "&cityTaxAmount=" +
                  cityTaxAmount +
                  "&discountAmount=" +
                  discountAmount;

                if (isBasketSelected == true) {
                  paymentData += "&isBasketSelected=1";
                }

                $.ajax({
                  type: "post",
                  url: "mdpos/orderSaveNotSendVat",
                  data: { paymentData: paymentData, itemData: itemData },
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      message: "Saving...",
                      boxed: true,
                    });
                  },
                  success: function (data) {
                    PNotify.removeAll();

                    if (data.status === "success") {
                      $dialog.dialog("close");

                      if (data.printData !== "") {
                        $("div.pos-preview-print")
                          .html(data.printData)
                          .promise()
                          .done(function () {
                            $("div.pos-preview-print").printThis({
                              debug: false,
                              importCSS: false,
                              printContainer: false,
                              dataCSS: data.css,
                              removeInline: false,
                            });
                          });
                      }

                      new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false,
                        addclass: "pnotify-center",
                      });
                      $(".pos-basket-count").text(data.basketCount);
                      posDisplayReset("");
                    } else {
                      new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false,
                      });
                    }

                    Core.unblockUI();
                  },
                });
              }
            },
          },
          {
            text: data.close_btn,
            class: "btn btn-sm blue-hoki",
            click: function () {
              $dialog.dialog("close");
            },
          },
        ],
      });
      $dialog.dialog("open");
      Core.initClean($dialog);

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
      Core.unblockUI();
    },
  });
}
function posConnectBankTerminal(terminalId, deviceType) {
  bankCheckIpTerminal(terminalId, deviceType, function (res) {
    new PNotify({
      title: res.status,
      text: res.text,
      type: res.status,
      sticker: false,
      addclass: "pnotify-center",
    });
  });
}
function callPosLiftPrint(data) {
  if (typeof data.liftdata !== "undefined") {
    var liftvar = data.liftdata;
    var liftLength = data.liftdata.length;

    setTimeout(function () {
      if (liftLength) {
        if (liftvar[0]["loopprint"]) {
          globalLoopPrint = 1;
          posLiftPrinter(liftvar[0], liftvar[0]["loopprint"], function () {
            if (liftLength >= 2) {
              if (liftvar[1]["loopprint"]) {
                globalLoopPrint = 1;
                posLiftPrinter(
                  liftvar[1],
                  liftvar[1]["loopprint"],
                  function () {
                    if (liftLength >= 3) {
                      if (liftvar[2]["loopprint"]) {
                        globalLoopPrint = 1;
                        posLiftPrinter(
                          liftvar[2],
                          liftvar[2]["loopprint"],
                          function () {
                            if (liftLength >= 4) {
                              if (liftvar[3]["loopprint"]) {
                                globalLoopPrint = 1;
                                posLiftPrinter(
                                  liftvar[3],
                                  liftvar[3]["loopprint"],
                                  function () {
                                    if (liftLength >= 5) {
                                      if (liftvar[4]["loopprint"]) {
                                        globalLoopPrint = 1;
                                        posLiftPrinter(
                                          liftvar[4],
                                          liftvar[4]["loopprint"],
                                          function () { }
                                        );
                                      }
                                    }
                                  }
                                );
                              }
                            }
                          }
                        );
                      }
                    }
                  }
                );
              }
            }
          });
        }
      }
    }, 3000);
  }
}
function posLiftPrinter(liftData, loopPrint, callback) {
  if ("WebSocket" in window) {
    console.log("WebSocket is supported by your Browser!");
    // Let us open a web socket
    var ws = new WebSocket("ws://localhost:58324/socket");
    /* var liftPort = 'COM2'; */
    var liftPort = 'Star TSP700II (TSP743II)';

    ws.onopen = function () {
      var currentDateTime = GetCurrentDateTime();
      ws.send(
        '{"command":"skyresort_dot_printer", "dateTime":"' +
        currentDateTime +
        '", details: [{"key": "port", "value": "' + liftPort + '"},' +
        '{"key": "title", "value": "' +
        liftData.title +
        '"}, ' +
        '{"key": "header", "value": "' +
        liftData.header +
        '"}, ' +
        '{"key": "date", "value": "' +
        liftData.date +
        '"},' +
        '{"key": "securecode", "value": "' +
        liftData.securecode +
        '"},' +
        '{"key": "endtime", "value": "' +
        liftData.endtime +
        '"}, ' +
        '{"key": "footer", "value": "' +
        liftData.footer +
        '"}]}'
      );
    };

    ws.onmessage = function (evt) {
      var received_msg = evt.data;
      var jsonData = JSON.parse(received_msg);

      if (globalLoopPrint < loopPrint) {
        globalLoopPrint++;
        posLiftPrinter(liftData, loopPrint, callback);
        return;
      }
      callback(jsonData);

      if (jsonData.status == "success") {
        //console.log(jsonData.details);
      } else {
        PNotify.removeAll();
        new PNotify({
          title: "Print Lift error",
          text: jsonData.description,
          type: "error",
          sticker: false,
        });
      }
    };

    ws.onerror = function (event) {
      PNotify.removeAll();
      new PNotify({
        title: "Print Lift error",
        text: "Veritech Client асаагүй байна!!!",
        type: "error",
        sticker: false,
        hide: false,
        addclass: "pnotify-center",
      });
    };

    ws.onclose = function () {
      console.log("Connection is closed...");
    };
  } else {
    var resultJson = {
      Status: "Error",
      Error: "WebSocket NOT supported by your Browser!",
    };

    console.log(JSON.stringify(resultJson));
  }
}
function setValuePosGolomtBank($elem, getParse) {
  if (getParse["status"] == "error") {
    new PNotify({
      title: "Bank terminal error [Golomtbank]",
      text: "ГҮЙЛГЭЭ АМЖИЛТГҮЙ: " + getParse.text,
      type: "error",
      sticker: false,
      addclass: "pnotify-center",
    });
    return;
  }

  var $getParent = $elem.closest(".pos-bank-row");
  $getParent.find('input[name="deviceRrn[]"]').val(getParse.rrn);
  $getParent.find('input[name="devicePan[]"]').val(getParse.pan);
  $getParent.find('input[name="deviceAuthcode[]"]').val(getParse.authcode);
  $getParent.find('input[name="deviceTerminalId[]"]').val(getParse.terminalid);

  isAcceptPrintPos = true;
  new PNotify({
    title: "Success",
    text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
    type: "success",
    sticker: false,
    addclass: "pnotify-center",
  });
}
function setValuePosKhaanBank($elem, getParse) {
  if (getParse["status"] == "error") {
    new PNotify({
      title: "Bank terminal error [Khanbank]",
      text: "ГҮЙЛГЭЭ АМЖИЛТГҮЙ: " + getParse.text,
      type: "error",
      sticker: false,
      addclass: "pnotify-center",
    });
    return;
  }

  var $getParent = $elem.closest(".pos-bank-row");
  $getParent.find('input[name="deviceRrn[]"]').val(getParse.rrn);
  $getParent.find('input[name="devicePan[]"]').val(getParse.pan);
  $getParent.find('input[name="deviceAuthcode[]"]').val(getParse.authcode);
  $getParent.find('input[name="deviceTerminalId[]"]').val(getParse.terminalid);
  $getParent.find('input[name="deviceTraceNo[]"]').val(getParse.traceno);

  isAcceptPrintPos = true;
  new PNotify({
    title: "Success",
    text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
    type: "success",
    sticker: false,
    addclass: "pnotify-center",
  });
}
function setValuePosXacBank($elem, getParse) {
  if (getParse["status"] == "error") {
    new PNotify({
      title: "Bank terminal error [Xacbank]",
      text: "ГҮЙЛГЭЭ АМЖИЛТГҮЙ: " + getParse.text,
      type: "error",
      sticker: false,
      addclass: "pnotify-center",
    });
    return;
  }

  var $getParent = $elem.closest(".pos-bank-row");
  $getParent.find('input[name="deviceRrn[]"]').val(getParse.rrn);
  $getParent.find('input[name="devicePan[]"]').val(getParse.pan);
  $getParent.find('input[name="deviceAuthcode[]"]').val(getParse.authcode);
  $getParent.find('input[name="deviceTerminalId[]"]').val(getParse.terminalid);
  $getParent.find('input[name="deviceTraceNo[]"]').val(getParse.traceno);

  isAcceptPrintPos = true;
  new PNotify({
    title: "Success",
    text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
    type: "success",
    sticker: false,
    addclass: "pnotify-center",
  });
}
function setValuePosTdBank($elem, getParse) {
  if (getParse["status"] == "error") {
    new PNotify({
      title: "Bank terminal error [Tdbank]",
      text: "ГҮЙЛГЭЭ АМЖИЛТГҮЙ: " + getParse.text,
      type: "error",
      sticker: false,
      addclass: "pnotify-center",
    });
    return;
  }

  var $getParent = $elem.closest(".pos-bank-row");
  $getParent.find('input[name="deviceRrn[]"]').val(getParse.rrn);
  $getParent.find('input[name="devicePan[]"]').val(getParse.pan);
  $getParent.find('input[name="deviceAuthcode[]"]').val(getParse.authcode);
  $getParent.find('input[name="deviceTerminalId[]"]').val(getParse.terminalid);
  $getParent.find('input[name="deviceTraceNo[]"]').val(getParse.traceno);

  isAcceptPrintPos = true;
  new PNotify({
    title: "Success",
    text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
    type: "success",
    sticker: false,
    addclass: "pnotify-center",
  });
}
function posSaleBankTerminal($elem, amount, terminalId, deviceType) {
  if ("WebSocket" in window) {
    console.log("WebSocket is supported by your Browser!");
    // Let us open a web socket
    var ws = new WebSocket("ws://localhost:58324/socket");

    Core.blockUI({
      message: "МЭДЭЭЛЭЛ ДАМЖУУЛЖ БАЙНА...",
      boxed: true,
    });
    ws.onopen = function () {
      var currentDateTime = GetCurrentDateTime();
      ws.send(
        '{"command":"bank_terminal_pos_sale", "dateTime":"' +
        currentDateTime +
        '", details: [{"key": "devicetype", "value": "' +
        deviceType +
        '"},{"key": "terminalid", "value": "' +
        terminalId +
        '"},{"key": "totalamount", "value": "' +
        amount +
        '"}]}'
      );
    };
    isAcceptPrintPos = false;

    ws.onmessage = function (evt) {
      var received_msg = evt.data;
      var jsonData = JSON.parse(received_msg);

      if (jsonData.status == "success") {
        var getParse = JSON.parse(jsonData.details[0].value),
          $getParent = $elem.closest(".pos-bank-row");

        if (deviceType === "databank") {
          if (
            getParse.status &&
            getParse["response"]["response_code"] == "000"
          ) {
            console.log(`getParse`, getParse);
            $getParent
              .find('input[name="deviceRrn[]"]')
              .val(getParse["response"]["rrn"]);
            $getParent
              .find('input[name="devicePan[]"]')
              .val(getParse["response"]["pan"]);
            $getParent
              .find('input[name="deviceAuthcode[]"]')
              .val(getParse["response"]["auth_code"]);
            $getParent
              .find('input[name="deviceTerminalId[]"]')
              .val(getParse["response"]["terminal_id"]);
            $getParent
              .find('input[name="deviceTraceNo[]"]')
              .val(getParse["response"]["trace_no"]);
            new PNotify({
              title: "Success",
              text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
              type: "success",
              sticker: false,
              addclass: "pnotify-center",
            });
            isAcceptPrintPos = true;
          } else {
            PNotify.removeAll();
            new PNotify({
              title: "Bank terminal error [" + deviceType + "]",
              text:
                "ГҮЙЛГЭЭ АМЖИЛТГҮЙ: [" +
                getParse["response"]["response_code"] +
                "] " +
                getParse["response"]["response_msg"],
              type: "error",
              sticker: false,
              addclass: "pnotify-center",
            });
            Core.unblockUI();
            return;
          }
        }

        if (deviceType === "glmt") {
          $getParent.find('input[name="deviceRrn[]"]').val(getParse.RRN);
          $getParent.find('input[name="devicePan[]"]').val(getParse.PAN);
          $getParent
            .find('input[name="deviceAuthcode[]"]')
            .val(getParse.AuthCode);
          $getParent
            .find('input[name="deviceTerminalId[]"]')
            .val(getParse.TerminalId);

          isAcceptPrintPos = true;
          new PNotify({
            title: "Success",
            text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
            type: "success",
            sticker: false,
            addclass: "pnotify-center",
          });
        }
      } else {
        PNotify.removeAll();
        new PNotify({
          title: "Bank terminal error [" + deviceType + "]",
          text: jsonData.description,
          type: "error",
          sticker: false,
          addclass: "pnotify-center",
        });
      }
      Core.unblockUI();
    };

    ws.onerror = function (event) {
      var resultJson = {
        Status: "Error",
        Error: event.code,
      };
      console.log(JSON.stringify(resultJson));
    };

    ws.onclose = function () {
      console.log("Connection is closed...");
    };
  } else {
    var resultJson = {
      Status: "Error",
      Error: "WebSocket NOT supported by your Browser!",
    };
    console.log(JSON.stringify(resultJson));
  }
}
function posSaleBankTerminalCopper($elem) {
  var $elem = $($elem);

  if (posUseIpTerminal === "1") {
    var amount = $elem
      .closest(".pos-bank-row")
      .find('input[name="bankAmountDtl[]"]')
      .autoNumeric("get");
    $elem
      .closest(".pos-bank-row")
      .find('select[name="posBankIdDtl[]"]')
      .select2("val", "150000");

    var bankCode = $elem
      .closest(".pos-bank-row")
      .find('select[name="posBankIdDtl[]"]')
      .find("option:selected")
      .data("bankcode");

    if (amount == "") {
      new PNotify({
        title: "Warning",
        text: "Дүнгээ оруулна уу!",
        type: "warning",
        sticker: false,
      });
      return;
    }

    if ("WebSocket" in window) {
      console.log("WebSocket is supported by your Browser!");
      // Let us open a web socket
      var ws = new WebSocket("ws://localhost:58324/socket");

      Core.blockUI({
        message: "МЭДЭЭЛЭЛ ДАМЖУУЛЖ БАЙНА...",
        boxed: true,
      });
      ws.onopen = function () {
        var currentDateTime = GetCurrentDateTime();
        ws.send(
          '{"command":"bank_terminal_pos_zes_discount", "dateTime":"' +
          currentDateTime +
          '", details: [{"key": "operation", "value": "10"},{"key": "devicetype", "value": "glmt"},{"key": "terminalid", "value": "' +
          bankIpterminals[bankCode] +
          '"},{"key": "totalamount", "value": "' +
          amount +
          '"}]}'
        );
      };
      isAcceptPrintPos = false;

      ws.onmessage = function (evt) {
        var received_msg = evt.data;
        var jsonData = JSON.parse(received_msg);

        if (jsonData.status == "success") {
          var ws = new WebSocket("ws://localhost:58324/socket");
          Core.blockUI({
            message: "МЭДЭЭЛЭЛ ДАМЖУУЛЖ БАЙНА...",
            boxed: true,
          });

          var $tbody = $("#posTable > tbody"),
            $rows = $tbody.find("> tr[data-item-id]");
          var discountZesAmt = 0,
            copperCartDiscountRowValue;

          $rows
            .each(function () {
              var $row = $(this);
              if (
                $row.find('input[data-name="discountId"]').val() ==
                "100000000001"
              ) {
                copperCartDiscountRowValue = $row
                  .find('input[data-name="copperCartDiscount"]')
                  .val();
                posCalcRowDiscountPercent(copperCartDiscountRowValue, $row);
              }
            })
            .promise()
            .done(function () {
              ws.onopen = function () {
                var currentDateTime = GetCurrentDateTime();
                amount = $("#posPayAmount").autoNumeric("get");
                $("#posPaidAmount").autoNumeric("set", amount);
                $elem
                  .closest(".pos-bank-row")
                  .find('input[name="bankAmountDtl[]"]')
                  .autoNumeric("set", amount);
                ws.send(
                  '{"command":"bank_terminal_pos_zes_discount", "dateTime":"' +
                  currentDateTime +
                  '", details: [{"key": "operation", "value": "0"},{"key": "devicetype", "value": "glmt"},{"key": "terminalid", "value": "' +
                  bankIpterminals[bankCode] +
                  '"},{"key": "totalamount", "value": "' +
                  amount +
                  '"}]}'
                );
              };
              ws.onmessage = function (evt) {
                var received_msg = evt.data;
                var jsonData = JSON.parse(received_msg);

                if (jsonData.status == "success") {
                  var getParse = JSON.parse(jsonData.details[0].value),
                    $getParent = $elem.closest(".pos-bank-row");

                  $getParent
                    .find('input[name="deviceRrn[]"]')
                    .val(getParse.RRN);
                  $getParent
                    .find('input[name="devicePan[]"]')
                    .val(getParse.PAN);
                  $getParent
                    .find('input[name="deviceAuthcode[]"]')
                    .val(getParse.AuthCode);
                  $getParent
                    .find('input[name="deviceTerminalId[]"]')
                    .val(getParse.TerminalId);

                  new PNotify({
                    title: "Success",
                    text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
                    type: "success",
                    sticker: false,
                    addclass: "pnotify-center",
                  });
                  isAcceptPrintPos = true;
                } else {
                  PNotify.removeAll();
                  new PNotify({
                    title: "Bank terminal error",
                    text: jsonData.description,
                    type: "error",
                    sticker: false,
                    addclass: "pnotify-center",
                  });

                  var $tbody = $("#posTable > tbody"),
                    $rows = $tbody.find("> tr[data-item-id]");
                  $rows
                    .each(function () {
                      posCalcRowDiscountPercent("0", $(this));
                    })
                    .promise()
                    .done(function () {
                      $elem
                        .closest(".pos-bank-row")
                        .find('input[name="bankAmountDtl[]"]')
                        .autoNumeric(
                          "set",
                          $("#posPayAmount").autoNumeric("get")
                        );
                      $("#posPaidAmount").autoNumeric(
                        "set",
                        $("#posPayAmount").autoNumeric("get")
                      );
                    });
                }
                Core.unblockUI();
              };
            });
        } else {
          PNotify.removeAll();
          new PNotify({
            title: "Bank terminal error",
            text: jsonData.description,
            type: "error",
            sticker: false,
            addclass: "pnotify-center",
          });

          var $tbody = $("#posTable > tbody"),
            $rows = $tbody.find("> tr[data-item-id]");
          $rows
            .each(function () {
              posCalcRowDiscountPercent("0", $(this));
            })
            .promise()
            .done(function () {
              $elem
                .closest(".pos-bank-row")
                .find('input[name="bankAmountDtl[]"]')
                .autoNumeric("set", $("#posPayAmount").autoNumeric("get"));
              $("#posPaidAmount").autoNumeric(
                "set",
                $("#posPayAmount").autoNumeric("get")
              );
            });
          Core.unblockUI();
        }
      };

      ws.onerror = function (event) {
        var resultJson = {
          Status: "Error",
          Error: event.code,
        };
        console.log(JSON.stringify(resultJson));
      };

      ws.onclose = function () {
        console.log("Connection is closed...");
      };
    } else {
      var resultJson = {
        Status: "Error",
        Error: "WebSocket NOT supported by your Browser!",
      };
      console.log(JSON.stringify(resultJson));
    }
  }
}
function posVoidBankTerminalFn(
  deviceType,
  terminalId,
  bankAmount,
  confCode,
  callback
) {
  Core.blockUI({
    message: "МЭДЭЭЛЭЛ ДАМЖУУЛЖ БАЙНА...",
    boxed: true,
  });

  if (deviceType === "tdb_paxs300") {
    setTimeout(function () {
      var response = $.ajax({
        type: 'post',
        url: 'http://127.0.0.1:8088/ecrt1000',
        data: {
          traceNum: confCode,
          operation: "Void"
        },
        dataType: 'json',
        async: false
      });
      var result = response.responseJSON;
      Core.unblockUI();

      if (result.ecrResult['RespCode'] == 00) {
        new PNotify({
          title: "Success",
          text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
          type: "success",
          sticker: false,
          addclass: "pnotify-center",
        });
        callback({ status: '' });
        return;
      } else {
        PNotify.removeAll();
        new PNotify({
          title: "Bank terminal error [TDB]",
          text: "ГҮЙЛГЭЭ АМЖИЛТГҮЙ: [" + result.ecrResult['RespCode'] + "]",
          type: "error",
          sticker: false,
          addclass: "pnotify-center",
        });
        callback({ status: 'error' });
        Core.unblockUI();
        return;
      }
    }, 100);
    return;
  }

  var ws = new WebSocket("ws://localhost:58324/socket");

  ws.onopen = function () {
    var currentDateTime = GetCurrentDateTime();
    ws.send(
      '{"command":"bank_terminal_pos_void", "dateTime":"' +
      currentDateTime +
      '", details: [{"key": "devicetype", "value": "' +
      deviceType +
      '"},{"key": "terminalid", "value": "' +
      terminalId +
      '"},{"key": "totalamount", "value": "' +
      bankAmount +
      '"},{"key": "approvalcode", "value": "' +
      confCode +
      '"}]}'
    );
  };

  ws.onmessage = function (evt) {
    var received_msg = evt.data;
    var jsonData = JSON.parse(received_msg);
    if (jsonData.status == "success") {
      if (deviceType === "databank") {
        var getParse = JSON.parse(jsonData.details[0].value);
        if (getParse.status && getParse["response"]["response_code"] == "000") {
          new PNotify({
            title: "Success",
            text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
            type: "success",
            sticker: false,
            addclass: "pnotify-center",
          });
          callback({ status: 'success' });
        } else {
          PNotify.removeAll();
          new PNotify({
            title: "Bank terminal error [" + deviceType + "]",
            text:
              "ГҮЙЛГЭЭ АМЖИЛТГҮЙ: [" +
              getParse["response"]["response_code"] +
              "] " +
              getParse["response"]["response_msg"],
            type: "error",
            sticker: false,
            addclass: "pnotify-center",
          });
          callback({ status: 'error' });
          Core.unblockUI();
          return;
        }
      } else if (deviceType === "tdb_paxs300") {
        var getParse = JSON.parse(jsonData.details[0].value);
        if (getParse["code"] == "0") {
          new PNotify({
            title: "Success",
            text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
            type: "success",
            sticker: false,
            addclass: "pnotify-center",
          });
          callback({ status: '' });
        } else {
          PNotify.removeAll();
          new PNotify({
            title: "Bank terminal error [TDB]",
            text: "ГҮЙЛГЭЭ АМЖИЛТГҮЙ: [" + getParse["code"] + "] " + getParse["message"],
            type: "error",
            sticker: false,
            addclass: "pnotify-center",
          });
          callback({ status: 'error' });
          Core.unblockUI();
          return;
        }
      } else if (deviceType === "khas_paxA35") {
        var getParse = JSON.parse(JSON.parse(jsonData.details[0].value));
        if (getParse["code"] == "0") {
          new PNotify({
            title: "Success",
            text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
            type: "success",
            sticker: false,
            addclass: "pnotify-center",
          });
          callback({ status: '' });
        } else {
          PNotify.removeAll();
          new PNotify({
            title: "Bank terminal error [Xacbank]",
            text: "ГҮЙЛГЭЭ АМЖИЛТГҮЙ: [" + getParse["code"] + "] " + getParse["desc"],
            type: "error",
            sticker: false,
            addclass: "pnotify-center",
          });
          callback({ status: 'error' });
          Core.unblockUI();
          return;
        }
      } else {
        callback({ status: '' });

        new PNotify({
          title: "Success",
          text: "<strong>ГҮЙЛГЭЭ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
          type: "success",
          sticker: false,
          addclass: "pnotify-center",
        });
      }
    } else {
      PNotify.removeAll();
      new PNotify({
        title: "Bank terminal error [" + deviceType + "]",
        text: jsonData.description,
        type: "error",
        sticker: false,
        addclass: "pnotify-center",
      });
      callback({ status: 'error' })
    }
    Core.unblockUI();
  };

  ws.onerror = function (event) {
    var resultJson = {
      Status: "Error",
      Error: event.code,
    };
  };

  ws.onclose = function () {
    console.log("Connection is closed...");
  };
}
function posVoidBankTerminal(confCode, bankAmount, callback) {
  if ("WebSocket" in window) {
    console.log("WebSocket is supported by your Browser!");

    /*if (confCode == "") {
      callback({status:'success'});
      return;
    }*/

    var $bankRow = $(".pos-bank-row");
    // #1-------------------------------------
    $bankRow.each(function (index) {
      var $self = $(this);
      var bankCode = $self.find('select[name="posBankIdDtl[]"]').find("option:selected").data("bankcode");

      if (($self.find("input[name='rowTerminalConfirmCode[]']").val() != "" || $self.find("input[name='deviceTraceNo[]']").val() != "") && !$self.hasClass("worked")) {
        var deviceType = "";
        if (bankCode == 150000 && bankIpterminals.hasOwnProperty(bankCode)) {
          deviceType = "glmt";
        }
        if (bankCode == 500000 && bankIpterminals.hasOwnProperty(bankCode)) {
          deviceType = "databank";
        }
        if (bankCode == 320000 && bankIpterminals.hasOwnProperty(bankCode)) {
          deviceType = "khas_paxA35";
        }
        if (bankCode == 400000 && bankIpterminals.hasOwnProperty(bankCode)) {
          deviceType = "tdb_paxs300";
        }

        if (deviceType == "") {
          callback({ status: 'success' });
          return;
        }

        posVoidBankTerminalFn(
          deviceType,
          $self.find("input[name='deviceTerminalId[]']").val(),
          $self.find("input[name='rowTerminalAmount[]']").val(),
          deviceType === "glmt" ? $self.find("input[name='rowTerminalConfirmCode[]']").val() : $self.find("input[name='deviceTraceNo[]']").val(),
          function (res) {
            if (res.status != 'error') {
              $self.addClass("worked");
            }

            if (index == $bankRow.length - 1) {
              callback(res);
              return;
            }

            // #2-------------------------------------
            $bankRow.each(function (index) {
              var $self = $(this);
              var bankCode = $self.find('select[name="posBankIdDtl[]"]').find("option:selected").data("bankcode");

              if (($self.find("input[name='rowTerminalConfirmCode[]']").val() != "" || $self.find("input[name='deviceTraceNo[]']").val() != "") && !$self.hasClass("worked")) {
                var deviceType = "";
                if (
                  bankCode == 150000 &&
                  bankIpterminals.hasOwnProperty(bankCode)
                ) {
                  deviceType = "glmt";
                }
                if (
                  bankCode == 500000 &&
                  bankIpterminals.hasOwnProperty(bankCode)
                ) {
                  deviceType = "databank";
                }
                if (
                  bankCode == 320000 &&
                  bankIpterminals.hasOwnProperty(bankCode)
                ) {
                  deviceType = "khas_paxA35";
                }
                if (bankCode == 400000 && bankIpterminals.hasOwnProperty(bankCode)) {
                  deviceType = "tdb_paxs300";
                }

                if (deviceType == "") {
                  callback(res);
                  return;
                }

                posVoidBankTerminalFn(
                  deviceType,
                  $self.find("input[name='deviceTerminalId[]']").val(),
                  $self.find("input[name='rowTerminalAmount[]']").val(),
                  deviceType === "glmt"
                    ? $self.find("input[name='rowTerminalConfirmCode[]']").val()
                    : $self.find("input[name='deviceTraceNo[]']").val(),
                  function (res) {
                    if (res.status != 'error') {
                      $self.addClass("worked");
                    }

                    if (index == $bankRow.length - 1) {
                      callback(res);
                      return;
                    }

                    // #3-------------------------------------
                    $bankRow.each(function (index) {
                      var $self = $(this);
                      var bankCode = $self
                        .find('select[name="posBankIdDtl[]"]')
                        .find("option:selected")
                        .data("bankcode");

                      if (($self.find("input[name='rowTerminalConfirmCode[]']").val() != "" || $self.find("input[name='deviceTraceNo[]']").val() != "") &&
                        !$self.hasClass("worked")
                      ) {
                        var deviceType = "";
                        if (
                          bankCode == 150000 &&
                          bankIpterminals.hasOwnProperty(bankCode)
                        ) {
                          deviceType = "glmt";
                        }
                        if (
                          bankCode == 500000 &&
                          bankIpterminals.hasOwnProperty(bankCode)
                        ) {
                          deviceType = "databank";
                        }
                        if (
                          bankCode == 320000 &&
                          bankIpterminals.hasOwnProperty(bankCode)
                        ) {
                          deviceType = "khas_paxA35";
                        }
                        if (bankCode == 400000 && bankIpterminals.hasOwnProperty(bankCode)) {
                          deviceType = "tdb_paxs300";
                        }

                        if (deviceType == "") {
                          callback(res);
                          return;
                        }

                        posVoidBankTerminalFn(
                          deviceType,
                          $self.find("input[name='deviceTerminalId[]']").val(),
                          $self.find("input[name='rowTerminalAmount[]']").val(),
                          deviceType === "glmt"
                            ? $self.find("input[name='rowTerminalConfirmCode[]']").val()
                            : $self.find("input[name='deviceTraceNo[]']").val(),
                          function (res) {
                            $self.addClass("worked");

                            if (index == $bankRow.length - 1) {
                              callback(res);
                              return;
                            }

                            // #4-------------------------------------
                            $bankRow.each(function (index) {
                              var $self = $(this);
                              var bankCode = $self
                                .find('select[name="posBankIdDtl[]"]')
                                .find("option:selected")
                                .data("bankcode");

                              if (($self.find("input[name='rowTerminalConfirmCode[]']").val() != "" || $self.find("input[name='deviceTraceNo[]']").val() != "") &&
                                !$self.hasClass("worked")
                              ) {
                                var deviceType = "";
                                if (
                                  bankCode == 150000 &&
                                  bankIpterminals.hasOwnProperty(bankCode)
                                ) {
                                  deviceType = "glmt";
                                }
                                if (
                                  bankCode == 500000 &&
                                  bankIpterminals.hasOwnProperty(bankCode)
                                ) {
                                  deviceType = "databank";
                                }
                                if (
                                  bankCode == 320000 &&
                                  bankIpterminals.hasOwnProperty(bankCode)
                                ) {
                                  deviceType = "khas_paxA35";
                                }
                                if (bankCode == 400000 && bankIpterminals.hasOwnProperty(bankCode)) {
                                  deviceType = "tdb_paxs300";
                                }

                                if (deviceType == "") {
                                  callback(res);
                                  return;
                                }

                                posVoidBankTerminalFn(
                                  deviceType,
                                  $self.find("input[name='deviceTerminalId[]']").val(),
                                  $self.find("input[name='rowTerminalAmount[]']").val(),
                                  deviceType === "glmt"
                                    ? $self.find("input[name='rowTerminalConfirmCode[]']").val()
                                    : $self.find("input[name='deviceTraceNo[]']").val(),
                                  function (res) {
                                    $self.addClass("worked");
                                    callback(res);
                                    return;
                                  }
                                );
                                return false;
                              }
                            });
                          }
                        );
                        return false;
                      }
                    });
                  }
                );
                return false;
              }
            });
          }
        );
        return false;
      } else {
        callback({ status: 'success' });
        return;
      }
    });
  } else {
    var resultJson = {
      Status: "Error",
      Error: "WebSocket NOT supported by your Browser!",
    };

    console.log(JSON.stringify(resultJson));
  }
}
function posSaleSocialPay(amount, phone) {
  $.ajax({
    type: "post",
    url: "mdpos/socialPaySendInvoice",
    data: { amount: amount, phone: phone },
    async: false,
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      PNotify.removeAll();
      if (data.status == "success") {
        new PNotify({
          title: "Success",
          text: "<strong>НЭХЭМЖЛЭЛ АМЖИЛТТАЙ ХИЙГДЛЭЭ</strong>",
          type: "success",
          sticker: false,
          addclass: "pnotify-center",
        });
        $('input[name="posSocialpayUID"]').val(data.message);
        posSocialPayQrCheckInterval = setInterval(function () {
          socialPayCheckQrCode(data.message, amount);
        }, 3000);
      } else {
        new PNotify({
          title: "Warning",
          text: data.message,
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
      }
      Core.unblockUI();
    },
  });
}
function posQRSocialPay() {
  var amount = Number($('input[name="posSocialpayAmt"]').autoNumeric("get"));

  if (amount <= 0) {
    PNotify.removeAll();
    new PNotify({
      title: "Warning",
      text: "Дүн буруу байна!",
      type: "warning",
      sticker: false,
      addclass: "pnotify-center",
    });
    return;
  }

  $.ajax({
    type: "post",
    url: "mdpos/socialPayGetInvoiceQr",
    data: { amount: amount },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      PNotify.removeAll();
      if (data.status == "success") {
        var $dialogName = "dialog-qr-socialpay";
        if (!$("#" + $dialogName).length) {
          $('<div id="' + $dialogName + '"></div>').appendTo("body");
        }
        var $dialog = $("#" + $dialogName);

        $('input[name="posSocialpayUID"]').val(data.message.invoiceid);
        posSocialPayQrCheckInterval = setInterval(function () {
          socialPayCheckQrCode(data.message.invoiceid, amount);
        }, 3000);
        $dialog.empty().append('<div class="w-100 text-center mt15 mb15">' + data.message.qr + "</div>");
        $dialog.dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: "SOCIAL PAY QRCODE",
          width: 400,
          height: "auto",
          modal: true,
          open: function () { },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("close_btn"),
              class: "btn btn-sm blue-madison",
              click: function () {
                $dialog.dialog("close");
              },
            },
          ],
        });
        $dialog.dialog("open");
      } else {
        new PNotify({
          title: "Warning",
          text: data.message,
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
      }
      Core.unblockUI();
    },
  });
}

function posTerminalList() {
  if (false) {
    var $dialogName = "dialog-talon-protect";
    if (!$("#" + $dialogName).length) {
      $('<div id="' + $dialogName + '"></div>').appendTo("body");
    }
    var $dialog = $("#" + $dialogName);

    $dialog
      .empty()
      .append(
        '<form method="post" autocomplete="off" id="talonListPassForm"><input type="password" name="talonListPass" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>'
      );
    $dialog.dialog({
      cache: false,
      resizable: true,
      bgiframe: true,
      autoOpen: false,
      title: "Нууц үг оруулах",
      width: 400,
      height: "auto",
      modal: true,
      open: function () {
        $(this).keypress(function (e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            $(this)
              .parent()
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
          }
        });
        $('input[name="talonListPass"]').on("keydown", function (e) {
          var keyCode = e.keyCode ? e.keyCode : e.which;
          if (keyCode == 13) {
            $(this)
              .closest(".ui-dialog")
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
          }
        });
      },
      close: function () {
        $dialog.empty().dialog("destroy").remove();
      },
      buttons: [
        {
          text: plang.get("insert_btn"),
          class: "btn btn-sm green-meadow",
          click: function () {
            PNotify.removeAll();
            var $form = $("#talonListPassForm");

            $form.validate({ errorPlacement: function () { } });

            if ($form.valid()) {
              $.ajax({
                type: "post",
                url: "mdpos/checkTalonListPass",
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function () {
                  Core.blockUI({
                    message: "Loading...",
                    boxed: true,
                  });
                },
                success: function (dataSub) {
                  if (dataSub.status == "success") {
                    $dialog.dialog("close");
                    posTerminalDataViewList();
                  } else {
                    new PNotify({
                      title: dataSub.status,
                      text: dataSub.message,
                      type: dataSub.status,
                      sticker: false,
                    });
                  }
                  Core.unblockUI();
                },
              });
            }
          },
        },
        {
          text: plang.get("close_btn"),
          class: "btn btn-sm blue-madison",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ],
    });
    $dialog.dialog("open");
  } else {
    posTerminalDataViewList();
  }
}

function posCloseIpTerminal(elem) {
  var $dialogName = "dialog-pos-close-ipterminal";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo(
      "body"
    );
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdwebservice/callMethodByMeta",
    data: {
      metaDataId: "1572930206574",
      isDialog: true,
      isSystemMeta: false,
      fillDataParams: "id=" + cashRegisterId + "&defaultGetPf=1",
      responseType: "",
      callerType: "pos",
      openParams: '{"callerType":"pos"}',
    },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data.Html);

      var processForm = $("#wsForm", "#" + $dialogName);
      var processUniqId = processForm.parent().attr("data-bp-uniq-id");

      var buttons = [
        {
          text: data.run_btn,
          class: "btn green-meadow btn-sm bp-btn-save",
          click: function (e) {
            if (window["processBeforeSave_" + processUniqId]($(e.target))) {
              processForm.validate({
                ignore: "",
                highlight: function (element) {
                  $(element).addClass("error");
                  $(element).parent().addClass("error");
                  if (
                    processForm.find("div.tab-pane:hidden:has(.error)").length
                  ) {
                    processForm
                      .find("div.tab-pane:hidden:has(.error)")
                      .each(function (index, tab) {
                        var tabId = $(tab).attr("id");
                        processForm
                          .find('a[href="#' + tabId + '"]')
                          .tab("show");
                      });
                  }
                },
                unhighlight: function (element) {
                  $(element).removeClass("error");
                  $(element).parent().removeClass("error");
                },
                errorPlacement: function () { },
              });

              var isValidPattern = initBusinessProcessMaskEvent(processForm);

              if (processForm.valid() && isValidPattern.length === 0) {
                processForm.ajaxSubmit({
                  type: "post",
                  url: "mdwebservice/runProcess",
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      boxed: true,
                      message: plang.get("POS_0040"),
                    });
                  },
                  success: function (responseData) {
                    if (responseData.status === "success") {
                      var responseParam = responseData.paramData;
                      posUseIpTerminal = "0";
                      PNotify.removeAll();
                      new PNotify({
                        title: "Амжилттай",
                        text: "IPPOS terminal холболт амжилттай саллаа.",
                        type: "success",
                        sticker: false,
                        addclass: "pnotify-center",
                      });

                      $.ajax({
                        type: "post",
                        url: "mdpos/posCloseIpTerminal",
                        data: {},
                        dataType: "json",
                        success: function (data) { },
                      });
                      isAcceptPrintPos = true;

                      $dialog.dialog("close");
                    }
                    Core.unblockUI();
                  },
                  error: function () {
                    alert("Error");
                  },
                });
              }
            }
          },
        },
        {
          text: data.close_btn,
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ];

      var dialogWidth = data.dialogWidth,
        dialogHeight = data.dialogHeight;

      if (data.isDialogSize === "auto") {
        dialogWidth = 1200;
        dialogHeight = "auto";
      }

      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.Title,
          width: dialogWidth,
          height: dialogHeight,
          modal: true,
          closeOnEscape:
            typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: buttons,
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
        });
      if (data.dialogSize === "fullscreen") {
        $dialog.dialogExtend("maximize");
      }
      $dialog.dialog("open");
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initBPAjax($dialog);
    Core.unblockUI();
  });
}

function posOpenIpTerminal(elem) {
  var $dialogName = "dialog-pos-open-ipterminal";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo(
      "body"
    );
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdwebservice/callMethodByMeta",
    data: {
      metaDataId: "1572930206574",
      isDialog: true,
      isSystemMeta: false,
      fillDataParams: "id=" + cashRegisterId + "&defaultGetPf=1",
      responseType: "",
      callerType: "pos",
      openParams: '{"callerType":"pos"}',
    },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data.Html);

      var processForm = $("#wsForm", "#" + $dialogName);
      var processUniqId = processForm.parent().attr("data-bp-uniq-id");

      var buttons = [
        {
          text: data.run_btn,
          class: "btn green-meadow btn-sm bp-btn-save",
          click: function (e) {
            if (window["processBeforeSave_" + processUniqId]($(e.target))) {
              processForm.validate({
                ignore: "",
                highlight: function (element) {
                  $(element).addClass("error");
                  $(element).parent().addClass("error");
                  if (
                    processForm.find("div.tab-pane:hidden:has(.error)").length
                  ) {
                    processForm
                      .find("div.tab-pane:hidden:has(.error)")
                      .each(function (index, tab) {
                        var tabId = $(tab).attr("id");
                        processForm
                          .find('a[href="#' + tabId + '"]')
                          .tab("show");
                      });
                  }
                },
                unhighlight: function (element) {
                  $(element).removeClass("error");
                  $(element).parent().removeClass("error");
                },
                errorPlacement: function () { },
              });

              var isValidPattern = initBusinessProcessMaskEvent(processForm);

              if (processForm.valid() && isValidPattern.length === 0) {
                processForm.ajaxSubmit({
                  type: "post",
                  url: "mdwebservice/runProcess",
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      boxed: true,
                      message: plang.get("POS_0040"),
                    });
                  },
                  success: function (responseData) {
                    if (responseData.status === "success") {
                      var responseParam = responseData.paramData;
                      posUseIpTerminal = "1";
                      PNotify.removeAll();
                      new PNotify({
                        title: "Амжилттай",
                        text: "IPPOS terminal холболт амжилттай.",
                        type: "success",
                        sticker: false,
                        addclass: "pnotify-center",
                      });

                      $.ajax({
                        type: "post",
                        url: "mdpos/posOpenIpTerminal",
                        data: {},
                        dataType: "json",
                        success: function (data) { },
                      });

                      $dialog.dialog("close");
                    }
                    Core.unblockUI();
                  },
                  error: function () {
                    alert("Error");
                  },
                });
              }
            }
          },
        },
        {
          text: data.close_btn,
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ];

      var dialogWidth = data.dialogWidth,
        dialogHeight = data.dialogHeight;

      if (data.isDialogSize === "auto") {
        dialogWidth = 1200;
        dialogHeight = "auto";
      }

      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.Title,
          width: dialogWidth,
          height: dialogHeight,
          modal: true,
          closeOnEscape:
            typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: buttons,
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
        });
      if (data.dialogSize === "fullscreen") {
        $dialog.dialogExtend("maximize");
      }
      $dialog.dialog("open");
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initBPAjax($dialog);
    Core.unblockUI();
  });
}

function askDateTransaction() {
  var $dialogName = "dialog-ask-datetransaction",
    askDateDefault;
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdpos/getDates",
    async: false,
    dataType: "json",
    success: function (dataSub) {
      askDateDefault = dataSub;
    },
  });

  if (askDateDefault["isExist"] === "0") {
    // if (isPosActiveLogin === '0' || askDateDefault['isExist'] === '0') {
    isPosActiveLogin = "1";
    $dialog
      .empty()
      .append(
        '<form method="post" autocomplete="off" id="askDateTransactionForm"><div data-path-message="description" style="background-color:#d9edf7; color:#31708f; padding: 5px; ; "><strong font-style:="" italic;=""><i class="fa fa-info"></i> Санамж </strong>Та огноогоо сонгоод ENTER дарна уу.</div>' +
        '<div class="mt15"><input type="radio" id="askDateInput" name="askDateInput" class="form-control" value="' +
        askDateDefault["date1"] +
        '" required="required"> <label class="ml6" style="font-size:28px;position: absolute;margin-top: -5px" for="askDateInput">' +
        askDateDefault["date1"] +
        "</label></div>" +
        '<div class="mt15"><input type="radio" id="askDateInput1" name="askDateInput" class="form-control" value="' +
        askDateDefault["date2"] +
        '" required="required"> <label class="ml6" style="font-size:28px;position: absolute;margin-top: -5px" for="askDateInput1">' +
        askDateDefault["date2"] +
        ' <button type="button" class="ml20 btn btn-circle btn-lg green-meadow uppercase" onclick="askDateTransactionClick();">Сонгох</button></label></div>' +
        "</form>"
      );
    $dialog.dialog({
      cache: false,
      resizable: true,
      bgiframe: true,
      autoOpen: false,
      closeOnEscape: false,
      title: "ЭЭЛЖИЙН ОГНОО СОНГОХ",
      resizable: false,
      draggable: true,
      width: 360,
      height: "180",
      modal: true,
      open: function () {
        $(".ui-dialog-titlebar-close", $dialog.parent()).hide();
        setTimeout(function () {
          $('input[name="askDateInput"]').focus();
        }, 100);
        $(this).keypress(function (e) {
          if (e.keyCode == $.ui.keyCode.ENTER) {
            var $form = $("#askDateTransactionForm");
            $form.validate({ errorPlacement: function () { } });

            if ($form.valid()) {
              if (
                confirm(
                  $form.find('input[name="askDateInput"]:checked').val() +
                  " өдрийг сонгосон байна. Итгэлтэй байна уу?"
                )
              ) {
                $.ajax({
                  type: "post",
                  url: "mdpos/saveDateCashier",
                  data: $form.serialize(),
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      message: "Loading...",
                      boxed: true,
                    });
                  },
                  success: function (dataSub) {
                    if (dataSub.status == "success") {
                      getDateCashier = true;

                      if (posCashierInsertC1) {
                        posCashMoneyBill("1");
                      }

                      if (
                        $("body").find("#dialog-pos-payment").length > 0 &&
                        $("body").find("#dialog-pos-payment").is(":visible")
                      ) {
                        posBillPrint();
                      }
                      $dialog.dialog("close");
                    } else {
                      new PNotify({
                        title: dataSub.status,
                        text: dataSub.message,
                        type: dataSub.status,
                        sticker: false,
                      });
                    }
                    Core.unblockUI();
                  },
                });
              }
            }
          }
          return e.preventDefault();
        });
      },
      close: function () {
        $dialog.empty().dialog("destroy").remove();
      },
      buttons: [],
    });
    $dialog.dialog("open");
    Core.initUniform($dialog);
  } else {
    if (posCashierInsertC1) {
      posCashMoneyBill("1");
    }
    if (
      $("body").find("#dialog-pos-payment").length > 0 &&
      $("body").find("#dialog-pos-payment").is(":visible")
    ) {
      posBillPrint();
    }
  }
}

function askDateTransactionClick() {
  var $form = $("#askDateTransactionForm");
  $form.validate({ errorPlacement: function () { } });

  if ($form.valid()) {
    if (
      confirm(
        $form.find('input[name="askDateInput"]:checked').val() +
        " өдрийг сонгосон байна. Итгэлтэй байна уу?"
      )
    ) {
      $.ajax({
        type: "post",
        url: "mdpos/saveDateCashier",
        data: $form.serialize(),
        dataType: "json",
        beforeSend: function () {
          Core.blockUI({
            message: "Loading...",
            boxed: true,
          });
        },
        success: function (dataSub) {
          if (dataSub.status == "success") {
            getDateCashier = true;
            if (posCashierInsertC1) {
              posCashMoneyBill("1");
            }
            if (
              $("body").find("#dialog-pos-payment").length > 0 &&
              $("body").find("#dialog-pos-payment").is(":visible")
            ) {
              posBillPrint();
            }
            $("#dialog-ask-datetransaction").dialog("close");
          } else {
            new PNotify({
              title: dataSub.status,
              text: dataSub.message,
              type: dataSub.status,
              sticker: false,
            });
          }
          Core.unblockUI();
        },
      });
    }
  }
}

function closePos() {
  if (posTypeCode == "3" || posTypeCode == "4") {
    var checkCloseDateCashier = $.ajax({
      type: "post",
      url: "mdpos/checkCloseDateCashier",
      async: false,
      success: function (data) {
        return data;
      },
    });
    if (checkCloseDateCashier.responseText != "0") {
      PNotify.removeAll();
      new PNotify({
        title: "Захиалга байна!",
        text: "Та тооцоогоо хийсний дараа хаалтаа хийнэ үү!",
        type: "warning",
        sticker: false,
      });
      return;
    }
  }

  var $dialogName = "dialog-pos-close",
    askDateDefault;
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdpos/getDates",
    async: false,
    dataType: "json",
    success: function (dataSub) {
      askDateDefault = dataSub;
    },
  });

  if (typeof askDateDefault["startdate"] === "undefined") {
    askDateDefault["startdate"] = "";
  }

  $dialog
    .empty()
    .append(
      '<form method="post" autocomplete="off" id="posCloseForm">' +
      '<div class="mt5 pb10" style="border-bottom: 1px solid #ccc;"><input type="hidden"  name="closeStartDate" class="form-control" value="' +
      askDateDefault["startdate"] +
      '"> <label class="ml6" style="font-size:28px;" for="">' +
      askDateDefault["startdate"] +
      "</label></div>" +
      '<div class="mt15"><input type="hidden" name="closeEndDate" class="form-control" value="' +
      askDateDefault["datetime"] +
      '"> <label class="ml6" style="font-size:28px;" for="">' +
      askDateDefault["datetime"] +
      "</label></div>" +
      "</form>"
    );

  $dialog.dialog({
    cache: false,
    resizable: true,
    bgiframe: true,
    autoOpen: false,
    closeOnEscape: false,
    title: "ХААЛТ",
    resizable: false,
    draggable: true,
    width: 320,
    modal: true,
    open: function () { },
    close: function () {
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: [
      {
        text: "Илгээх",
        class: "btn btn-sm green-meadow",
        click: function () {
          var $form = $("#posCloseForm");
          $form.validate({ errorPlacement: function () { } });

          if ($form.valid()) {
            $.ajax({
              type: "post",
              url: "mdpos/saveCloseDateCashier",
              data: $form.serialize(),
              dataType: "json",
              beforeSend: function () {
                Core.blockUI({ message: "Loading...", boxed: true });
              },
              success: function (dataSub) {
                Core.unblockUI();

                if (dataSub.status == "success") {
                  if (dataSub.hasOwnProperty("report") && dataSub.report) {
                    $("div.pos-preview-print")
                      .html(dataSub.report)
                      .promise()
                      .done(function () {
                        $("div.pos-preview-print").printThis({
                          debug: false,
                          importCSS: false,
                          printContainer: false,
                          dataCSS: dataSub.css,
                          removeInline: false,
                        });
                      });
                    setTimeout(function () {
                      window.location = "mdpos";
                    }, 3000);
                  }
                } else {
                  new PNotify({
                    title: dataSub.status,
                    text: dataSub.message,
                    type: dataSub.status,
                    sticker: false,
                  });
                }
              },
            });
          }

          $dialog.dialog("close");
        },
      },
      {
        text: plang.get("close_btn"),
        class: "btn btn-sm blue-hoki",
        click: function () {
          $dialog.dialog("close");
        },
      },
    ],
  });
  $dialog.dialog("open");
}

function posRound(num) {
  return Number(Math.round(num));
}

function reLotteryPrint(dataViewId, selectedRow) {
  if (selectedRow.length > 1) {
    new PNotify({
      title: "Info",
      text: "Нэг баримт сонгоно уу!",
      type: "info",
      sticker: false,
    });
    return;
  }
  $.ajax({
    type: "post",
    url: "mdpos/billRePrint",
    data: {
      selectedRows: selectedRow,
    },
    dataType: "json",
    beforeSend: function () {
      bpBlockMessageStart("Printing...");
    },
    success: function (data) {
      if (data.status === "success") {
        if (data.printData !== "") {
          var $dialogName = "pos-preview-print-relottery";
          if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="hidden"></div>').appendTo(
              "body"
            );
          }
          $("#pos-preview-print-relottery")
            .html(data.printData)
            .promise()
            .done(function () {
              $("#pos-preview-print-relottery").printThis({
                debug: false,
                importCSS: false,
                printContainer: false,
                dataCSS: data.css,
                removeInline: false,
              });
            });

          dataViewReload("1522115383994585");
        } else {
          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
          });
        }
      } else {
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false,
        });
      }

      bpBlockMessageStop();
    },
    error: function () {
      alert("Error");
      bpBlockMessageStop();
    },
  });
}

function posChooseItemMatrixGift(row, $lastRow, $prevItemRow) {
  var $dialogName = "dialog-pos-gift-matrix";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);
  var matrixHideSale = posMatrixHideSale ? " d-none" : "";
  var giftList =
    '<div class="mt5' +
    matrixHideSale +
    '"><input type="radio" id="chooseMatrix1" name="chooseMatrix" data-discount="' +
    row.discountpercent +
    '" class="notuniform" value="discount"> <label class="ml6" style="font-size:18px;" for="chooseMatrix1">Хямдрал - <strong>' +
    row.discountpercent +
    "%</strong></label></div>";
  giftList +=
    '<div class="mt10"><input type="radio" id="chooseMatrix2" name="chooseMatrix" class="notuniform" value="gift"> <label class="ml6" style="font-size:18px;" for="chooseMatrix2">Бэлэг</label></div>';

  giftList += '<div class="mt10">' + row.gift + "</div>";

  $dialog.empty().append(giftList);

  $dialog.dialog({
    cache: false,
    resizable: false,
    bgiframe: true,
    autoOpen: false,
    title: plang.get("choose"),
    width: 750,
    height: "auto",
    maxHeight: $(window).height() - 40,
    modal: true,
    closeOnEscape: isCloseOnEscape,
    position: { my: "top", at: "top+10" },
    open: function () {
      disableScrolling();
    },
    close: function () {
      enableScrolling();
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: [
      {
        text: "Сонгох",
        class: "btn btn-sm green-meadow",
        click: function () {
          $.uniform.restore($dialog.find("input[type=checkbox]"));

          if ($('input[name="chooseMatrix"]:checked').val() === "discount") {
            posCalcRowDiscountPercent(row.discountpercent, $prevItemRow);
            posCalcRowDiscountPercent(row.discountpercent, $lastRow);
            generateGiftRow($lastRow);

            $dialog.find("input.pos-gift-item:checked").each(function () {
              $(this).prop("checked", false).removeAttr("checked");
            });

            $lastRow
              .find('script[data-template="matrixgiftrow"]')
              .text($dialog.html());

            posGiftSaveRow($prevItemRow, $dialog, "");
            posGiftSaveRow($lastRow, $dialog, "");
          } else {
            posCalcRowDiscountPercent("0", $prevItemRow);
            posCalcRowDiscountPercent("0", $lastRow);

            if (
              $prevItemRow.find('input[name="salePrice[]"]').val() >
              $lastRow.find('input[name="salePrice[]"]').val()
            ) {
              generateGiftRow($prevItemRow);
              posGiftSaveRow($prevItemRow, $dialog, "");
              $prevItemRow
                .find('script[data-template="matrixgiftrow"]')
                .text($dialog.html());
            } else {
              generateGiftRow($lastRow);
              posGiftSaveRow($lastRow, $dialog, "");
              $lastRow
                .find('script[data-template="matrixgiftrow"]')
                .text($dialog.html());
            }
          }

          $dialog.dialog("close");
        },
      },
      {
        text: plang.get("close_btn"),
        class: "btn btn-sm blue-hoki",
        click: function () {
          $dialog.dialog("close");
        },
      },
    ],
  });

  $dialog.on("click", "input.pos-gift-item", function () {
    $dialog.find("#chooseMatrix1").removeAttr("checked");
    $dialog.find("#chooseMatrix2").attr("checked", "checked");
    $dialog.find("#chooseMatrix1").prop("checked", false);
    $dialog.find("#chooseMatrix2").prop("checked", true);
  });

  Core.initUniform($dialog);
  $dialog.dialog("open");

  return;
}

function generateGiftRow($tr) {
  if ($tr.find("td:eq(0)").find(".matrix-gift-icon").length) {
    $tr.find("td:eq(0)").find(".matrix-gift-icon").remove();
    $tr
      .find("td:eq(0)")
      .append(
        '<button type="button" class="btn btn-xs green matrix-gift-icon" onclick="posChooseItemGiftMatrixBtn(this);" title="Matrix бэлэг"><i class="fa fa-gift"></i></button>'
      );
  } else {
    $tr
      .find("td:eq(0)")
      .append(
        '<button type="button" class="btn btn-xs green matrix-gift-icon" onclick="posChooseItemGiftMatrixBtn(this);" title="Matrix бэлэг"><i class="fa fa-gift"></i></button>'
      );
  }

  if (!$tr.next('tr[data-item-gift-row="true"]:eq(0)').length) {
    $tr.after(
      '<tr data-item-gift-row="true" style="display: none">' +
      '<td colspan="2"></td>' +
      '<td colspan="6" data-item-gift-cell="true"></td>' +
      "</tr>"
    );
  }
}

function posChooseItemGiftMatrixBtn(elem) {
  posChooseItemGiftMatrix($(elem).closest("tr"));
  return;
}

function posChooseItemGiftMatrix(row) {
  var giftTemplate = row.find('script[data-template="matrixgiftrow"]').text();
  var getMatId = row.data("matrix-row");
  var $getMatRows = row.parent().find('tr[data-matrix-row="' + getMatId + '"]');
  var $prevItemRow = $getMatRows.eq(0);
  var $lastRow = $getMatRows.eq(1);

  if (giftTemplate == "") {
    return;
  }

  var $dialogName = "dialog-pos-gift";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $dialog.empty().append(giftTemplate);

  $dialog.dialog({
    cache: false,
    resizable: false,
    bgiframe: true,
    autoOpen: false,
    title: plang.get("POS_0035"),
    width: 750,
    height: "auto",
    maxHeight: $(window).height() - 40,
    modal: true,
    closeOnEscape: isCloseOnEscape,
    position: { my: "top", at: "top+10" },
    open: function () {
      disableScrolling();
    },
    close: function () {
      enableScrolling();
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: [
      {
        text: "Сонгох",
        class: "btn btn-sm green-meadow",
        click: function () {
          $.uniform.restore($dialog.find("input[type=checkbox]"));

          if ($('input[name="chooseMatrix"]:checked').val() === "discount") {
            var matrixDiscount = $('input[name="chooseMatrix"]:checked')
              .data("discount")
              .toString();

            $dialog.find("input.pos-gift-item:checked").each(function () {
              $(this).prop("checked", false).removeAttr("checked");
            });

            posCalcRowDiscountPercent(matrixDiscount, $prevItemRow);
            posCalcRowDiscountPercent(matrixDiscount, $lastRow);
            $lastRow
              .find('script[data-template="matrixgiftrow"]')
              .text($dialog.html());

            posGiftSaveRow($prevItemRow, $dialog, "");
            posGiftSaveRow($lastRow, $dialog, "");
            generateGiftRow($lastRow);
            if (
              $prevItemRow.find("td:eq(0)").find(".matrix-gift-icon").length
            ) {
              $prevItemRow.find("td:eq(0)").find(".matrix-gift-icon").remove();
            }
          } else {
            posCalcRowDiscountPercent("0", $prevItemRow);
            posCalcRowDiscountPercent("0", $lastRow);

            if (
              $prevItemRow.find('input[name="salePrice[]"]').val() >
              $lastRow.find('input[name="salePrice[]"]').val()
            ) {
              generateGiftRow($prevItemRow);
              if ($lastRow.find("td:eq(0)").find(".matrix-gift-icon").length) {
                $lastRow.find("td:eq(0)").find(".matrix-gift-icon").remove();
              }
              posGiftSaveRow($prevItemRow, $dialog, "");
              $prevItemRow
                .find('script[data-template="matrixgiftrow"]')
                .text($dialog.html());
            } else {
              generateGiftRow($lastRow);
              if (
                $prevItemRow.find("td:eq(0)").find(".matrix-gift-icon").length
              ) {
                $prevItemRow
                  .find("td:eq(0)")
                  .find(".matrix-gift-icon")
                  .remove();
              }
              posGiftSaveRow($lastRow, $dialog, "");
              $lastRow
                .find('script[data-template="matrixgiftrow"]')
                .text($dialog.html());
            }
          }

          $dialog.dialog("close");
        },
      },
      {
        text: plang.get("close_btn"),
        class: "btn btn-sm blue-hoki",
        click: function () {
          $dialog.dialog("close");
        },
      },
    ],
  });

  $dialog.on("click", "input.pos-gift-item", function () {
    $dialog.find("#chooseMatrix1").prop("checked", false);
    $dialog.find("#chooseMatrix2").prop("checked", true);
    $dialog.find("#chooseMatrix1").removeAttr("checked");
    $dialog.find("#chooseMatrix2").attr("checked", "checked");
  });

  Core.initUniform($dialog);
  $dialog.dialog("open");

  return;
}
function getPosGridItemCommaIds() {
  var $itemIdInputs = $("#posTable > tbody").find('input[name="itemId[]"]');

  return $itemIdInputs
    .map(function () {
      return this.value;
    })
    .get()
    .join(",");
}

function posDiscountFillByItemCode(jdata) {
  var $dialogName = "dialog-item-discounts";
  if (!$($dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  var discountTable =
    '<table style="width: 100%" class="table table-sm table-bordered pos-discounts-table">';
  discountTable += "<tbody>";
  discountTable += '<tr class="discounttrnotselected">';
  discountTable += '<td style="width: 115px; text-align: center">Хөн.хувь</td>';
  discountTable += '<td style="width: 115px; text-align: center">Хөн.дүн</td>';
  discountTable += "</tr>";
  var clsName = "";

  for (var i = 0; i < jdata.length; i++) {
    if (i === 0) {
      clsName = ' class="discounttrselected"';
    } else {
      clsName = "";
    }
    discountTable += "<tr" + clsName + ' tabindex="' + i + '">';
    discountTable +=
      '<td style="text-align: right" data-percent="' +
      jdata[i]["discountpercent"] +
      '">' +
      jdata[i]["discountpercent"] +
      "</td>";
    discountTable +=
      '<td style="text-align: right" data-amount="' +
      jdata[i]["discountamount"] +
      '">' +
      pureNumberFormat(jdata[i]["discountamount"]) +
      "</td>";
    discountTable += "</tr>";
  }

  discountTable += "</tbody>";
  discountTable += "</table>";
  discountTable +=
    "<style>tr.discounttrselected td {background-color: #d4edfc;} .pos-discounts-table tr {outline:none;}</style>";

  $dialog.empty().append(discountTable);
  $dialog.dialog({
    cache: false,
    resizable: true,
    bgiframe: true,
    autoOpen: false,
    title: "Хөнгөлөлтийн жагсаалт",
    width: 300,
    height: "auto",
    modal: true,
    closeOnEscape: isCloseOnEscape,
    open: function () {
      disableScrolling();
      var $thisDialogButton = $(".pos-discounts-table");
      $(document).on("keydown", ".pos-discounts-table tr", function (e) {
        var keyCode = e.keyCode ? e.keyCode : e.which;
        var $this = $(this);

        if (keyCode == 38) {
          /* up */

          var $thisButton = $thisDialogButton.find("tr.discounttrselected"),
            $thisParent = $thisButton.prevAll("tr:eq(0)");

          if ($thisParent.length) {
            $thisParent.trigger("click");
          }
        } else if (keyCode == 40) {
          /* down */

          var $thisButton = $thisDialogButton.find("tr.discounttrselected"),
            $thisParent = $thisButton.nextAll("tr:eq(0)");

          if ($thisParent.length) {
            $thisParent.trigger("click");
          }
        } else if (keyCode == 13) {
          $this = $thisDialogButton.find("tr.discounttrselected");
          $itemRow = $("#posTable").find("tbody > tr.pos-selected-row");
          if (
            $this.find("td:eq(0)").attr("data-percent") &&
            Number($this.find("td:eq(0)").attr("data-percent")) > 0
          ) {
            var discountSalePrice =
              typeof posIsEditBasketPrice === "undefined"
                ? Number($itemRow.find('input[name="salePrice[]"]').val())
                : Number(
                  $itemRow
                    .find('input[name="salePriceInput[]"]')
                    .autoNumeric("get")
                );

            discountPercent = $this.find("td:eq(0)").attr("data-percent");
            unitDiscount =
              (Number($this.find("td:eq(0)").attr("data-percent")) / 100) *
              discountSalePrice;
            discountAmount = discountSalePrice - unitDiscount;

            $itemRow
              .find('td[data-field-name="salePrice"]')
              .autoNumeric("set", discountAmount);
            $itemRow.find('input[name="discountAmount[]"]').val(discountAmount);
            $itemRow
              .find('input[name="discountPercent[]"]')
              .val(discountPercent);
            $itemRow.find('input[name="unitDiscount[]"]').val(unitDiscount);
            $itemRow.find('input[name="isDiscount[]"]').val("1");

            $("#pos-discount-amount").autoNumeric("set", unitDiscount);
          } else if (
            $this.find("td:eq(1)").attr("data-amount") &&
            Number($this.find("td:eq(1)").attr("data-amount")) > 0
          ) {
            var discountSalePrice =
              typeof posIsEditBasketPrice === "undefined"
                ? Number($itemRow.find('input[name="salePrice[]"]').val())
                : Number(
                  $itemRow
                    .find('input[name="salePriceInput[]"]')
                    .autoNumeric("get")
                );

            discountPercent = 0;
            unitDiscount = Number($this.find("td:eq(1)").attr("data-amount"));
            discountAmount = discountSalePrice - unitDiscount;

            $itemRow
              .find('td[data-field-name="salePrice"]')
              .autoNumeric("set", discountAmount);
            $itemRow.find('input[name="discountAmount[]"]').val(discountAmount);
            $itemRow
              .find('input[name="discountPercent[]"]')
              .val(discountPercent);
            $itemRow.find('input[name="unitDiscount[]"]').val(unitDiscount);
            $itemRow.find('input[name="isDiscount[]"]').val("1");

            $("#pos-discount-amount").autoNumeric("set", unitDiscount);
          }

          $dialog.empty().dialog("destroy").remove();
          posCalcRow($itemRow);
        }
      });
      $(".pos-discounts-table tr").on("click", function () {
        var $this = $(this);
        if ($this.hasClass("discounttrnotselected")) {
          return;
        }
        $this.parent().find("tr").removeClass("discounttrselected");
        $this.addClass("discounttrselected");
      });
      $(".pos-discounts-table tr").on("dblclick", function () {
        var $this = $(this);
        if ($this.hasClass("discounttrnotselected")) {
          return;
        }
        $this.parent().find("tr").removeClass("discounttrselected");
        $this.addClass("discounttrselected");

        $itemRow = $("#posTable").find("tbody > tr.pos-selected-row");
        if (
          $this.find("td:eq(0)").attr("data-percent") &&
          Number($this.find("td:eq(0)").attr("data-percent")) > 0
        ) {
          var discountSalePrice =
            typeof posIsEditBasketPrice === "undefined"
              ? Number($itemRow.find('input[name="salePrice[]"]').val())
              : Number(
                $itemRow
                  .find('input[name="salePriceInput[]"]')
                  .autoNumeric("get")
              );

          discountPercent = $this.find("td:eq(0)").attr("data-percent");
          unitDiscount =
            (Number($this.find("td:eq(0)").attr("data-percent")) / 100) *
            discountSalePrice;
          discountAmount = discountSalePrice - unitDiscount;

          $itemRow
            .find('td[data-field-name="salePrice"]')
            .autoNumeric("set", discountAmount);
          $itemRow.find('input[name="discountAmount[]"]').val(discountAmount);
          $itemRow.find('input[name="discountPercent[]"]').val(discountPercent);
          $itemRow.find('input[name="unitDiscount[]"]').val(unitDiscount);
          $itemRow.find('input[name="isDiscount[]"]').val("1");

          $("#pos-discount-amount").autoNumeric("set", unitDiscount);
        } else if (
          $this.find("td:eq(1)").attr("data-amount") &&
          Number($this.find("td:eq(1)").attr("data-amount")) > 0
        ) {
          var discountSalePrice =
            typeof posIsEditBasketPrice === "undefined"
              ? Number($itemRow.find('input[name="salePrice[]"]').val())
              : Number(
                $itemRow
                  .find('input[name="salePriceInput[]"]')
                  .autoNumeric("get")
              );

          discountPercent = 0;
          unitDiscount = Number($this.find("td:eq(1)").attr("data-amount"));
          discountAmount = discountSalePrice - unitDiscount;

          $itemRow
            .find('td[data-field-name="salePrice"]')
            .autoNumeric("set", discountAmount);
          $itemRow.find('input[name="discountAmount[]"]').val(discountAmount);
          $itemRow.find('input[name="discountPercent[]"]').val(discountPercent);
          $itemRow.find('input[name="unitDiscount[]"]').val(unitDiscount);
          $itemRow.find('input[name="isDiscount[]"]').val("1");

          $("#pos-discount-amount").autoNumeric("set", unitDiscount);
        }

        $dialog.empty().dialog("destroy").remove();
        posCalcRow($itemRow);
      });
    },
    close: function () {
      enableScrolling();
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: [
      {
        text: plang.get("close_btn"),
        class: "btn btn-sm blue-hoki",
        click: function () {
          $dialog.dialog("close");
        },
      },
    ],
  });
  $dialog.dialog("open");
}

function reasonReturnBp(callback) {
  var processForm = $("#pos-payment-form");
  processForm.validate({
    ignore: "",
    highlight: function (element) {
      $(element).addClass("error");
      $(element).parent().addClass("error");
      if (processForm.find("div.tab-pane:hidden:has(.error)").length) {
        processForm
          .find("div.tab-pane:hidden:has(.error)")
          .each(function (index, tab) {
            var tabId = $(tab).attr("id");
            processForm.find('a[href="#' + tabId + '"]').tab("show");
          });
      }
    },
    unhighlight: function (element) {
      $(element).removeClass("error");
      $(element).parent().removeClass("error");
    },
    errorPlacement: function () { },
  });

  var isValidPattern = initBusinessProcessMaskEvent(processForm);

  if (processForm.valid() && isValidPattern.length === 0) {
    processForm.ajaxSubmit({
      type: "post",
      url: "mdwebservice/runProcess",
      async: false,
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          boxed: true,
          message: plang.get("POS_0040"),
        });
      },
      success: function (responseData) {
        callback(responseData);
        Core.unblockUI();
      },
      error: function () {
        alert("Error");
      },
    });
  }
}

function posUpointCardRead(elem) {
  PNotify.removeAll();
  var cardNumber = $("#upointCardNumber2").val().trim();
  var pinCode = $("#upointCardPinCode").val().trim();
  var mobile = $("#upointMobile2").val().trim();

  if ((cardNumber || mobile) && pinCode) {
    $.ajax({
      type: "post",
      url: "mdpos/upointCheckInfo",
      data: {
        cardNumber: $("#upointCardNumber").val()
          ? $("#upointCardNumber").val().trim()
          : cardNumber,
        pinCode: pinCode,
        mobile: $("#upointMobile").val().trim()
          ? $("#upointMobile").val().trim()
          : mobile,
      },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Checking...",
          boxed: true,
        });
      },
      success: function (response) {
        Core.unblockUI();
        if (response.status === "success") {
          $("#upointBalance").val(response.data.balance);
          $("#upointMobile").val(response.data.mobile);
          $("#upointCardNumber").val(response.data.card_number);
          if (
            cardNumber &&
            ($("#upointMobile2").val() == "" ||
              $("#upointMobile2").val().indexOf("*") !== -1)
          ) {
            $("#upointCardNumber2").val(response.data.card_number);
            $("#upointMobile2").val(
              response.data.mobile.substr(0, response.data.mobile.length - 4) +
              "****"
            );
          }
          if (
            mobile &&
            ($("#upointCardNumber2").val() == "" ||
              $("#upointCardNumber2").val().indexOf("*") !== -1)
          ) {
            $("#upointMobile2").val(response.data.mobile);
            $("#upointCardNumber2").val(
              response.data.card_number.substr(
                0,
                response.data.card_number.length - 4
              ) + "****"
            );
          }
          $("#upointCreated").val(
            moment(response.data.created_at).format("YYYY-MM-DD HH:MM")
          );

          var posPayAmount = Math.round(
            Number($("#upointPayAmount").autoNumeric("get")) / 2
          );
          var uPointAmt = 0;

          if (returnBillType == "" && $("#upointIsCost").is(":checked")) {
            if (posPayAmount < response.data.balance) {
              uPointAmt = posPayAmount;
            } else if (posPayAmount == response.data.balance) {
              uPointAmt = posPayAmount;
            } else if (posPayAmount > response.data.balance) {
              uPointAmt = response.data.balance;
            }
            $('input[name="upointAmountDtl[]"]')
              .autoNumeric("set", uPointAmt)
              .trigger("change");
            $('input[name="upointPayAmount"]').autoNumeric(
              "set",
              Number($("#upointPayAmount").autoNumeric("get")) - uPointAmt
            );
          }
        } else {
          $("#upointBalance").val("");
          $("#upointMobile").val("");
          $("#upointMobile2").val("");
          $("#upointCreated").val("");
          new PNotify({
            title: "Warning",
            text: response.message.replace("card not found", "КАРТ ОЛДСОНГҮЙ"),
            type: "warning",
            sticker: false,
            addclass: "pnotify-center",
          });
        }
      },
    });
  } else {
    new PNotify({
      title: "Warning",
      text: "Талбаруудыг бөглөнө үү!",
      type: "warning",
      sticker: false,
      addclass: "pnotify-center",
    });
  }
}

function posCreateDepozit(bpId) {
  var $dialogName = "dialog-pos-create-depozit";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo(
      "body"
    );
  }
  var $dialog = $("#" + $dialogName),
    jsonParam = "";

  if (
    $('input[name="empCustomerId"]').length &&
    $('input[name="empCustomerId"]').val()
  ) {
    jsonParam = JSON.stringify({
      customerId: $('input[name="empCustomerId"]').val(),
    });
  }

  $.ajax({
    type: "post",
    url: "mdwebservice/callMethodByMeta",
    data: {
      metaDataId: bpId,
      isDialog: true,
      isSystemMeta: false,
      fillJsonParam: jsonParam,
      responseType: "json",
      callerType: "pos",
      openParams: '{"callerType":"pos"}',
    },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data.Html);

      var $processForm = $("#wsForm", "#" + $dialogName),
        processUniqId = $processForm.parent().attr("data-bp-uniq-id");

      var buttons = [
        {
          text: data.run_btn,
          class: "btn green-meadow btn-sm bp-btn-save",
          click: function (e) {
            if (window["processBeforeSave_" + processUniqId]($(e.target))) {
              $processForm.validate({
                ignore: "",
                highlight: function (element) {
                  $(element).addClass("error");
                  $(element).parent().addClass("error");
                  if (
                    $processForm.find("div.tab-pane:hidden:has(.error)").length
                  ) {
                    $processForm
                      .find("div.tab-pane:hidden:has(.error)")
                      .each(function (index, tab) {
                        var tabId = $(tab).attr("id");
                        $processForm
                          .find('a[href="#' + tabId + '"]')
                          .tab("show");
                      });
                  }
                },
                unhighlight: function (element) {
                  $(element).removeClass("error");
                  $(element).parent().removeClass("error");
                },
                errorPlacement: function () { },
              });

              var isValidPattern = initBusinessProcessMaskEvent($processForm);

              if ($processForm.valid() && isValidPattern.length === 0) {
                $processForm.ajaxSubmit({
                  type: "post",
                  url: "mdwebservice/runProcess",
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      boxed: true,
                      message: plang.get("POS_0040"),
                    });
                  },
                  success: function (res) {
                    new PNotify({
                      title: res.status,
                      text: res.message,
                      type: res.status,
                      sticker: false,
                      addclass: "pnotify-center",
                    });
                    if (res.status === "success") {
                      $dialog.dialog("close");
                    }
                    Core.unblockUI();
                  },
                  error: function () {
                    alert("Error");
                  },
                });
              }
            }
          },
        },
        {
          text: data.close_btn,
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ];

      var dialogWidth = data.dialogWidth,
        dialogHeight = data.dialogHeight;

      if (data.isDialogSize === "auto") {
        dialogWidth = 1200;
        dialogHeight = "auto";
      }

      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.Title,
          width: dialogWidth,
          height: dialogHeight,
          modal: true,
          closeOnEscape:
            typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: buttons,
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
        });
      if (data.dialogSize === "fullscreen") {
        $dialog.dialogExtend("maximize");
      }
      $dialog.dialog("open");
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initBPAjax($dialog);
    Core.unblockUI();
  });
}

function getKeyValue(row, key) {
  return typeof row[key] === "undefined" ? "" : row[key];
}

function appendItem(itemPostData, renderType, callback) {
  var $tbody = $("#posTable").find("> tbody");
  var addClassName = renderType === "card" ? "d-none" : "";
  console.log("appendItem...");

  if (isReceiptNumber && tbltCount > 0) {
    var alreadyItemCount = $tbody.find("> tr[data-item-code]").length;

    if (alreadyItemCount >= tbltCount) {
      new PNotify({
        title: "Warning",
        text: plang.get("POS_0009"),
        type: "warning",
        sticker: false,
        addclass: "pnotify-center",
      });
      return;
    }
  }

  if (isConfigEmpCustomer && $("#empCustomerId_valueField").val() != "") {
    itemPostData["empCustomerId"] = $("#empCustomerId_valueField").val();
  }

  if ($("#vipLockerId").length && $("#vipLockerId").val() != "") {
    itemPostData["vipLockerId"] = $("#vipLockerId").val();
  }

  if ($("#lockerCustomerId").length && $("#lockerCustomerId").val() != "") {
    itemPostData["lockerCustomerId"] = $("#lockerCustomerId").val();
  }

  $.ajax({
    type: "post",
    url: "mdpos/getItemByCode",
    data: itemPostData,
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({ message: "Loading...", boxed: true });
    },
    success: function (data) {
      PNotify.removeAll();
      if (data.status == "success") {
        /*data-criteria="storeId='+posStoreId+'"*/
        if (posOrderTimer && isBasketOnly) {
          $(".posTimerInit").countdown("option", { until: posOrderTimer });
          $(".posTimerInit").countdown("resume");
        }

        if (renderType === "posaftersale") {
          Core.unblockUI();
          posChooseItemGift(itemPostData, data.gift);
          return;
        }

        var rowData = data.row,
          serialNumber = "",
          unitReceivable = "",
          isOperating = "",
          maxPrice = "",
          endQty = "",
          itemKeyId = "",
          quantity = 1,
          isDiscount = "",
          discountPercent = "",
          discountAmount = "",
          unitDiscount = "",
          totalDiscount = "",
          isCalcRow = false,
          sectionId = "",
          customerId2 = "",
          salesPersonId = $("#posRestWaiterId").length ? $("#posRestWaiterId").val() : "",
          registerNo = "",
          internalId = "",
          isown = "",
          discountId = "",
          packageId = "",
          packageName = "",
          copperCartDiscount = 0,
          lineTotalBonusAmount = "",
          selectedCusId = $('input[name="empCustomerId"]').length
            ? $('input[name="empCustomerId"]').val()
            : "",
          guestName = $("#guestName").length ? $("#guestName").val().trim() : "";

        if (Number(rowData.endqty) > 0 && Number(rowData.endqty) < 1) {
          quantity = Number(rowData.endqty);
        }

        if (rowData.hasOwnProperty("merchantid")) {
          customerId2 = rowData.merchantid;
        }
        if (rowData.hasOwnProperty("internalid")) {
          internalId = rowData.internalid;
        }
        if (rowData.hasOwnProperty("discountid")) {
          discountId = rowData.discountid;
        }
        if (rowData.hasOwnProperty("isoperating")) {
          isOperating = rowData.isoperating;
        }
        if (rowData.hasOwnProperty("stateregnumber")) {
          registerNo = rowData.stateregnumber;
        }
        if (rowData.hasOwnProperty("mainpackageid")) {
          packageId = rowData.mainpackageid;
        }
        if (rowData.hasOwnProperty("mainpackagename")) {
          packageName = rowData.mainpackagename;
        }
        if (rowData.hasOwnProperty("positempackagelist") && rowData.positempackagelist) {
          appendItemPackage(rowData.positempackagelist, rowData);
          return;
        }

        if (
          isConfigSerialNumber &&
          rowData.hasOwnProperty("posimitemkeylist")
        ) {
          var posimitemkeylist = rowData.posimitemkeylist;

          if (posimitemkeylist.length == 1) {
            serialNumber = posimitemkeylist[0].serialnumber
              ? posimitemkeylist[0].serialnumber
              : "";
            itemKeyId = posimitemkeylist[0].itemkeyid
              ? posimitemkeylist[0].itemkeyid
              : "";
            endQty = Number(posimitemkeylist[0].endqty);

            if (rowData.hasOwnProperty("receivableamount")) {
              unitReceivable = rowData.receivableamount;
              maxPrice = rowData.maxprice;
            }

            if (endQty < quantity) {
              quantity = endQty;
              isCalcRow = true;
            }
          } else if (posimitemkeylist.length > 1) {
            posItemFillBySerialNumber(rowData);
            return;
          } else {
            alert("No serial!");
            Core.unblockUI();
            return;
          }
        }

        if (rowData.hasOwnProperty("posimitemsectionlist")) {
          var posimsectionlist = rowData.posimitemsectionlist;

          if (posimsectionlist.length == 1) {
            var sectionFirstRow = posimsectionlist[0];
            sectionId = sectionFirstRow.sectionid
              ? sectionFirstRow.sectionid
              : "";

            if (
              sectionId &&
              sectionFirstRow.hasOwnProperty("saleprice") &&
              sectionFirstRow.saleprice
            ) {
              rowData.saleprice = sectionFirstRow.saleprice;
            }

            if (
              typeof sectionFirstRow.calcbonusamount !== "undefined" &&
              sectionFirstRow.calcbonusamount &&
              sectionFirstRow.calcbonusamount != 0
            ) {
              rowData.calcbonusamount = sectionFirstRow.calcbonusamount;
            }

            if (
              typeof sectionFirstRow.calcbonuspercent !== "undefined" &&
              sectionFirstRow.calcbonuspercent &&
              sectionFirstRow.calcbonuspercent != 0
            ) {
              rowData.calcbonuspercent = sectionFirstRow.calcbonuspercent;
            }

            if (
              typeof sectionFirstRow.discountamount !== "undefined" &&
              sectionFirstRow.discountamount &&
              sectionFirstRow.discountamount != 0
            ) {
              rowData.discountamount = sectionFirstRow.discountamount;
            }

            if (
              typeof sectionFirstRow.discountpercent !== "undefined" &&
              sectionFirstRow.discountpercent &&
              sectionFirstRow.discountpercent != 0
            ) {
              rowData.discountpercent = sectionFirstRow.discountpercent;
            }
          } else if (posimsectionlist.length > 1) {
            posSectionFillBySerialNumber(rowData);
            return;
          }
        }

        var isIgnoreEndQty = rowData.hasOwnProperty("isignoreendqty") && rowData.isignoreendqty == "1" ? true : false;
        var concatItemName = (rowData.itemcode + "" + serialNumber).toLowerCase();

        if (rowData.hasOwnProperty("endqty")) {
          endQty = Number(rowData.endqty);
        }
        if (!isConfigItemCheckEndQtyInvoice) {
          isIgnoreEndQty = true;
        }
        if (isConfigItemCheckEndQtyMsg && rowData.hasOwnProperty("endqty")) {
          endQty = isIgnoreEndQty ? 1000 : Number(rowData.endqty);
        }

        if (isConfigItemCheckDuplicate) {
          var $addedRow = $tbody.find('tr[data-item-code="' + concatItemName + '"]');

          if (posTypeCode == "3") {
            $addedRow = $tbody.find(
              'tr[data-item-id-customer-id="' +
              rowData.id +
              "_" +
              guestName +
              '"]'
            );
          }

          //if ($addedRow.length && itemPostData.hasOwnProperty("packageIdItem") && itemPostData.packageIdItem) {
          if ($addedRow.length) {
            var alreadyEndQty = isIgnoreEndQty ? 1000 : Number($addedRow.find('input[data-field-name="endQty"]').val());
            var qty = Number(
              $addedRow.find("input.pos-quantity-input").autoNumeric("get")
            );
            var addedQty = qty + 1;

            if (alreadyEndQty >= addedQty) {
              $addedRow
                .find("input.pos-quantity-input")
                .autoNumeric("set", addedQty);
              posCalcRow($addedRow);
            } else {
              new PNotify({
                title: "Warning",
                text: plang.getVar("POS_0010", { endQty: alreadyEndQty }),
                type: "warning",
                sticker: false,
                addclass: "pnotify-center",
              });
            }

            $tbody.find("tr.pos-selected-row").removeClass("pos-selected-row");
            $addedRow.addClass("pos-selected-row");
            $(".pos-item-combogrid-cell")
              .find("input.textbox-text")
              .val("")
              .focus();
            callback("");

            Core.unblockUI();
            return;
          }
        }

        if (isConfigItemCheckEndQtyMsg && rowData.hasOwnProperty("endqty")) {
          var msgEndQty = isIgnoreEndQty ? 1000 : Number(rowData.endqty);

          if (msgEndQty <= 0) {
            PNotify.removeAll();
            new PNotify({
              title: plang.get("POS_0011"),
              text: plang.getVar("POS_0012", { endQty: msgEndQty }),
              type: "error",
              sticker: false,
              addclass: "pnotify-center",
            });
            Core.unblockUI();
            $(".pos-item-combogrid-cell").find("input.textbox-text").val("").focus();
            return;
          } else if (msgEndQty <= 5) {
            PNotify.removeAll();
            new PNotify({
              title: plang.get("POS_0011"),
              text: plang.getVar("POS_0013", { endQty: msgEndQty }),
              type: "warning",
              sticker: false,
              addclass: "pnotify-center",
            });
          }
        }

        var itemName = rowData.itemname.trim(),
          displayPrice = rowData.saleprice,
          itemAttr = "",
          printCopies = "";

        if (
          rowData.hasOwnProperty("calcbonuspercent") &&
          Number(rowData.calcbonuspercent) > 0
        ) {
          rowData.calcbonusamount =
            (Number(rowData.calcbonuspercent) / 100) *
            Number(rowData.saleprice);
        }

        if (
          rowData.hasOwnProperty("discountpercent") &&
          Number(rowData.discountpercent) > 0 &&
          discountId != "100000000001"
        ) {
          var discountSalePrice = Number(rowData.saleprice);

          discountPercent = rowData.discountpercent;
          unitDiscount =
            (Number(rowData.discountpercent) / 100) * discountSalePrice;
          discountAmount = discountSalePrice - unitDiscount;
          isDiscount = "1";
          totalDiscount = quantity * unitDiscount;
        } else if (
          rowData.hasOwnProperty("discountpercent") &&
          Number(rowData.discountpercent) > 0 &&
          discountId == "100000000001"
        ) {
          copperCartDiscount = rowData.discountpercent;
        } else if (
          rowData.hasOwnProperty("discountamount") &&
          Number(rowData.discountamount) > 0
        ) {
          var discountSalePrice = Number(rowData.saleprice);

          discountPercent = 0;
          unitDiscount = Number(rowData.discountamount);
          discountAmount = discountSalePrice - unitDiscount;
          isDiscount = "1";
          totalDiscount = quantity * unitDiscount;
        }

        if (rowData.hasOwnProperty("discountdtl") && rowData.discountdtl) {
          posDiscountFillByItemCode(rowData.discountdtl);
        }

        if (
          rowData.hasOwnProperty("packageid") &&
          rowData.packageid != "" &&
          Number(rowData.packageprice) > 0 &&
          (Number(rowData.hdrpackageqty) > 0 ||
            Number(rowData.dtlpackageqty) > 0)
        ) {
          itemAttr = 'data-packageid="' + rowData.packageid + '" data-packageprice="' + rowData.packageprice + '" data-hdrpackageqty="' + rowData.hdrpackageqty + '" data-dtlpackageqty="' + rowData.dtlpackageqty + '"';
        }

        if (rowData.hasOwnProperty("printcopies")) {
          printCopies = rowData.printcopies;
        }

        var accompanyItemsDataJson = "";
        if (
          rowData.hasOwnProperty("promproductdtl") &&
          rowData.promproductdtl
        ) {
          accompanyItemsDataJson = encodeURIComponent(
            JSON.stringify(rowData.promproductdtl)
          );
        }

        var accompanyServiceDataJson = "";
        if (
          rowData.hasOwnProperty("mesjobmaterialdtl") &&
          rowData.mesjobmaterialdtl
        ) {
          accompanyServiceDataJson = encodeURIComponent(
            JSON.stringify(rowData.mesjobmaterialdtl)
          );
        }

        var rowSalePrice =
          discountAmount === "" ? rowData.saleprice : discountAmount;
        if (!rowData.hasOwnProperty("ishideinvoiceqty")) {
          rowData.ishideinvoiceqty = "";
        }
        var iseditprice =
          typeof rowData.iseditprice !== "undefined" ? rowData.iseditprice : "";

        var rowHtml =
          '<tr data-item-id="' +
          rowData.id +
          '" data-item-id-customer-id="' + rowData.id + "_" + guestName +
          '" data-customerid="' + guestName + '" data-item-code="' +
          concatItemName +
          '" ' +
          itemAttr +
          ">" +
          '<td data-field-name="gift" class="text-center ' +
          addClassName +
          '"></td>' +
          '<td data-field-name="itemCode" class="text-left ' +
          addClassName +
          '" style="font-size: 14px;">' +
          rowData.itemcode +
          "</td>" +
          '<td data-field-name="serialNumber" data-config-column="serialnumber" class="text-left">' +
          serialNumber +
          "</td>" +
          '<td data-field-name="itemName" class="text-left" title="' +
          itemName +
          '" style="font-size: 14px; line-height: 15px;">' +
          '<input type="hidden" name="itemId[]" value="' +
          rowData.id +
          '">' +
          '<input type="hidden" name="refSalePrice[]" value="' +
          (rowData.refsaleprice ? rowData.refsaleprice : "") +
          '">' +
          '<input type="hidden" name="customerId[]" value="' +
          selectedCusId +
          '">' +
          '<input type="hidden" name="salesOrderId[]" value="">' +
          '<input type="hidden" name="customerIdSaved[]" value="">' +
          '<input type="hidden" name="isSavedOrder[]" value="">' +
          '<input type="hidden" name="guestName[]" value="' +
          guestName +
          '">' +
          '<input type="hidden" name="itemCode[]" value="' +
          rowData.itemcode +
          '">' +
          '<input type="hidden" name="itemName[]" value="' +
          itemName +
          '">' +
          '<input type="hidden" name="salePrice[]" value="' +
          rowData.saleprice +
          '">' +
          '<input type="hidden" name="totalPrice[]" value="' +
          rowData.saleprice +
          '">' +
          '<input type="hidden" name="measureId[]" value="' +
          rowData.measureid +
          '">' +
          '<input type="hidden" name="measureCode[]" value="' +
          rowData.measurecode +
          '">' +
          '<input type="hidden" name="barCode[]" value="' +
          rowData.barcode +
          '">' +
          '<input type="hidden" name="isVat[]" value="' +
          rowData.isvat +
          '">' +
          '<input type="hidden" name="vatPercent[]" value="' +
          rowData.vatpercent +
          '">' +
          '<input type="hidden" name="vatPrice[]" value="">' +
          '<input type="hidden" name="noVatPrice[]" value="' +
          bpRound(rowData.novatprice) +
          '">' +
          '<input type="hidden" name="isCityTax[]" value="' +
          rowData.iscitytax +
          '">' +
          '<input type="hidden" name="lineTotalVat[]" value="0">' +
          '<input type="hidden" name="cityTax[]" value="">' +
          '<input type="hidden" name="cityTaxPercent[]" value="' +
          rowData.citytaxpercent +
          '">' +
          '<input type="hidden" name="lineTotalCityTax[]" value="0">' +
          '<input type="hidden" name="discountPercent[]" value="' +
          discountPercent +
          '">' +
          '<input type="hidden" name="discountAmount[]" value="' +
          discountAmount +
          '">' +
          '<input type="hidden" name="unitDiscount[]" value="' +
          unitDiscount +
          '">' +
          '<input type="hidden" name="isDiscount[]" value="' +
          isDiscount +
          '">' +
          '<input type="hidden" name="totalDiscount[]" value="' +
          totalDiscount +
          '">' +
          '<input type="hidden" name="storeWarehouseId[]" value="' +
          rowData.storewarehouseid +
          '">' +
          '<input type="hidden" name="deliveryWarehouseId[]" value="' +
          rowData.deliverywarehouseid +
          '">' +
          '<input type="hidden" name="isJob[]">' +
          '<input type="hidden" name="giftJson[]">' +
          '<input type="hidden" name="serialNumber[]" value="' +
          serialNumber +
          '">' +
          '<input type="hidden" name="itemKeyId[]" value="' +
          itemKeyId +
          '">' +
          '<input type="hidden" name="sectionId[]" value="' +
          sectionId +
          '">' +
          '<input type="hidden" name="unitReceivable[]" value="' +
          unitReceivable +
          '">' +
          '<input type="hidden" name="maxPrice[]" value="' +
          maxPrice +
          '">' +
          '<input type="hidden" name="printCopies[]" value="' +
          printCopies +
          '">' +
          '<input type="hidden" name="isOperating[]" value="' +
          isOperating +
          '">' +
          '<input type="hidden" name="discountEmployeeId[]">' +
          '<input type="hidden" name="orgCashRegisterCode[]">' +
          '<input type="hidden" name="orgStoreCode[]">' +
          '<input type="hidden" name="orgPosHeaderName[]">' +
          '<input type="hidden" name="orgPosLogo[]">' +
          '<input type="hidden" name="storeId[]">' +
          '<input type="hidden" name="editPriceEmployeeId[]">' +
          '<input type="hidden" name="cashRegisterId[]">' +
          '<input type="hidden" name="salesorderdetailid[]">' +
          '<input type="hidden" name="discountTypeId[]">' +
          '<input type="hidden" name="salesPersonId[]" value="' + salesPersonId + '">' +
          '<input type="hidden" name="packageId[]" value="' + packageId + '">' +
          '<input type="hidden" name="packageName[]" value="' + packageName + '">' +
          '<input type="hidden" name="discountDescription[]">' +
          '<input type="hidden" data-field-name="endQty" value="' +
          endQty +
          '">' +
          '<input type="hidden" data-field-name="discountQty" value="10000000">' +
          '<input type="hidden" name="stateRegNumber[]" value="' +
          registerNo +
          '">' +
          '<input type="hidden" name="merchantId[]" value="' +
          customerId2 +
          '">' +
          '<input type="hidden" name="internalId[]" value="' +
          internalId +
          '">' +
          '<input type="hidden" data-name="accompanyItems" value="' +
          accompanyItemsDataJson +
          '">' +
          '<input type="hidden" data-name="isServiceCharge" value="">' +
          '<input type="hidden" data-name="accompanyServices" value="' +
          accompanyServiceDataJson +
          '">' +
          '<input type="hidden" name="discountId[]" data-name="discountId" value="' +
          discountId +
          '">' +
          '<input type="hidden" name="lineTotalBonusAmount[]" value="' +
          (typeof rowData.calcbonusamount !== "undefined"
            ? rowData.calcbonusamount
            : "") +
          '">' +
          '<input type="hidden" name="unitBonusAmount[]" value="' +
          (typeof rowData.calcbonusamount !== "undefined"
            ? rowData.calcbonusamount
            : "") +
          '">' +
          '<input type="hidden" data-name="copperCartDiscount" value="' +
          copperCartDiscount +
          '">' +
          '<input type="hidden" data-name="isCalcUPoint" name="isCalcUPoint[]" value="' +
          (typeof rowData.iscalcupoint !== "undefined"
            ? rowData.iscalcupoint
            : "") +
          '">' +
          '<input type="hidden" data-name="calcBonusPercent" name="unitBonusPercent[]" value="' +
          (typeof rowData.calcbonuspercent !== "undefined"
            ? rowData.calcbonuspercent
            : "") +
          '">' +
          '<input type="hidden" data-name="isNotUseBonusCard" name="isNotUseBonusCard[]" value="' +
          (typeof rowData.isnotusebonuscard !== "undefined"
            ? rowData.isnotusebonuscard
            : "") +
          '">' +
          '<input type="hidden" data-name="isFood" value="' +
          (typeof rowData.isfood !== "undefined" ? rowData.isfood : "") +
          '">' +
          (renderType === "card"
            ? '<div class="item-code-mini">' +
            rowData.itemcode +
            "</div>" +
            '<div class="mt3">' +
            itemName +
            "</div>"
            : itemName) +
          "</td>" +
          '<td data-field-name="salePrice" class="text-right bigdecimalInit">' +
          (typeof posIsEditBasketPrice === "undefined" && iseditprice !== "1"
            ? rowData.ishideinvoiceqty === "1"
              ? ""
              : rowSalePrice
            : '<input type="text" name="salePriceInput[]" class="pos-saleprice-input bigdecimalInit" value="' +
            displayPrice +
            '" data-mdec="3">') +
          "</td>" +
          '<td data-field-name="unitReceivable" data-config-column="unitreceivable" class="text-right bigdecimalInit ' +
          addClassName +
          '">' +
          unitReceivable +
          "</td>" +
          '<td data-field-name="quantity" style="height:28.8px;" class="pos-quantity-cell text-right">' +
          '<script type="text/template" data-template="giftrow">' +
          data.gift +
          "</script>" +
          '<script type="text/template" data-template="matrixgiftrow"></script>' +
          (renderType === "card"
            ? rowData.ishideinvoiceqty != "1"
              ? '<a href="javascript:;" class="list-icons-item basket-inputqty-button d-flex justify-content-between" title="">' +
              '<span><i class="icon-minus3 mr5"></i></span>' +
              '<span><input type="text" name="quantity[]" class="pos-quantity-input bigdecimalInit ignorebarcode" data-oldvalue="' +
              quantity +
              '" autocomplete="off" value="' +
              quantity +
              '" data-mdec="3"></span>' +
              '<span><i class="icon-plus3 ml5"></i></span>' +
              "</a>"
              : '<input type="hidden" name="quantity[]" class="pos-quantity-input bigdecimalInit ignorebarcode" data-oldvalue="' +
              quantity +
              '" autocomplete="off" value="' +
              quantity +
              '" data-mdec="3">'
            : '<input type="' +
            (rowData.ishideinvoiceqty === "1" ? "hidden" : "text") +
            '" name="quantity[]" class="pos-quantity-input bigdecimalInit ignorebarcode" data-oldvalue="' +
            quantity +
            '" autocomplete="off" value="' +
            quantity +
            '" data-mdec="3">') +
          "</td>" +
          '<td data-field-name="totalPrice" class="text-right bigdecimalInit">' +
          rowSalePrice +
          "</td>" +
          '<td data-field-name="delivery" class="text-center" data-config-column="delivery">' +
          '<input type="hidden" name="isDelivery[]" value="0">' +
          '<input type="checkbox" class="isDelivery" value="1" title="' +
          plang.get("POS_0014") +
          '">' +
          "</td>" +
          '<td data-field-name="salesperson" class="text-center" data-config-column="salesperson">' +
          '<div class="meta-autocomplete-wrap" data-section-path="employeeId">' +
          '<div class="input-group double-between-input">' +
          '<input type="hidden" name="employeeId[]" id="employeeId_valueField" data-path="employeeId" class="popupInit">' +
          '<input type="text" name="employeeId_displayField[]" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="employeeId" id="employeeId_displayField" data-processid="1454315883636" data-lookupid="1522404331251" placeholder="' +
          plang.get("code_search") +
          '" autocomplete="off">' +
          '<span class="input-group-btn">' +
          "<button type=\"button\" class=\"btn default btn-bordered form-control-sm mr0\" onclick=\"dataViewSelectableGrid('employeeId', '1454315883636', '1522404331251', 'single', 'employeeId', this);\" tabindex=\"-1\"><i class=\"fa fa-search\"></i></button>" +
          "</span>" +
          '<span class="input-group-btn">' +
          '<input type="text" name="employeeId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="employeeId" id="employeeId_nameField" data-processid="1454315883636" data-lookupid="1522404331251" placeholder="' +
          plang.get("name_search") +
          '" tabindex="-1" autocomplete="off">' +
          "</span>" +
          "</div>" +
          "</div>" +
          "</td>" +
          "</tr>";

        if (data.gift != "" && data.gift != null) {
          rowHtml +=
            '<tr data-item-gift-row="true" style="display: none">' +
            '<td colspan="2"></td>' +
            '<td colspan="6" data-item-gift-cell="true"></td>' +
            "</tr>";
        }

        if ($tbody.find('tr.item-package').length && !$tbody.find('tr.item-nopackage').length && rowData.hasOwnProperty("mainpackageid") && rowData.mainpackageid == '') {
          $tbody.append(
            '<tr style="height: 20px;" class="item-nopackage"><td style="font-size: 12px;background-color: #ffcc0099;"></td><td colspan="5" style="font-size: 12px;background-color: #ffcc0099;">Багцгүй</td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td></tr>'
          );
        }

        if (posTypeCode == "3" && selectedCusId) {
          if ($tbody.find('tr[data-customerid="' + $("#guestName").val() + '"]').length) {
            $tbody.find('tr[data-customerid="' + $("#guestName").val() + '"]:last').after(rowHtml);
            var $lastRow = $tbody.find("tr[data-item-id]:last");
          } else {
            $tbody.append(
              '<tr style="height: 20px;" class="multi-customer-group" data-customerid="' +
              selectedCusId +
              '"><td colspan="4" style="font-size: 12px;background-color: #ffcc0099;">' +
              $("#guestName").val() + '<a href="javascript:;" style="background-color: #e4b700;color: #333;padding: 4px 3px 3px 4px;margin-left: 13px; display:none" onclick="posCustomerList(this);">харилцагч өөрчлөх</a>' +
              '</td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td></tr>'
            );
            $tbody.append(rowHtml);
            var $lastRow = $tbody.find("tr[data-item-id]:last");
          }
        } else if (posTypeCode == "3") {
          if ($("#guestName").val()) {
            if ($tbody.find('tr.multi-customer-group').length === 2 && $('#posLocationId').val() == '') {
              PNotify.removeAll();
              new PNotify({
                title: 'Анхааруулга',
                text: 'Ширээ сонгоогүй үед зөвхөн нэг харилцагчийн бараа бичнэ',
                type: "warning",
                sticker: false,
                addclass: "pnotify-center",
              });
              Core.unblockUI();
              return;
            }
            if ($tbody.find('tr[data-customerid="' + $("#guestName").val() + '"]').length) {
              $tbody
                .find('tr[data-customerid="' + $("#guestName").val() + '"]:last')
                .after(rowHtml);
              var $lastRow = $tbody.find(
                'tr[data-customerid="' + $("#guestName").val() + '"]:last'
              );
            } else {
              $tbody.append(
                '<tr style="height: 20px;" class="multi-customer-group" data-customerid="' + $("#guestName").val() + '"><td colspan="4" style="font-size: 12px;background-color: #ffcc0099;">' + $("#guestName").val() + ' (guest)</td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td></tr>'
              );
              $tbody.append(rowHtml);
              var $lastRow = $tbody.find("tr[data-item-id]:last");
            }
          } else if ($tbody.find('tr[data-customerid=""]').length) {
            $tbody.find('tr[data-customerid=""]:last').after(rowHtml);
            var $lastRow = $tbody.find('tr[data-customerid=""]:last');
          } else {
            if ($('#posLocationId').val() == '') {
              PNotify.removeAll();
              new PNotify({
                title: 'Анхааруулга',
                text: 'Харилцагч эсвэл Зочин заавал сонгоно уу!',
                type: "warning",
                sticker: false,
                addclass: "pnotify-center",
              });
              Core.unblockUI();
              return;
            }
            $tbody.append(
              '<tr style="height: 20px;" class="multi-customer-group" data-customerid=""><td colspan="4" style="font-size: 12px;background-color: #ffcc0099;">харилцагч сонгоогүй</td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td></tr>'
            );
            $tbody.append(rowHtml);
            var $lastRow = $tbody.find("tr[data-item-id]:last");
          }
        } else {
          $tbody.append(rowHtml);
          var $lastRow = $tbody.find("tr[data-item-id]:last");
        }

        if (isConfigPaymentUnitReceivable && isReceiptNumber) {
          var lastSalePrice = Number(rowData.saleprice),
            lastMaxPrice = Number(maxPrice);

          if (lastSalePrice > lastMaxPrice) {
            $lastRow.find('input[name="salePrice[]"]').val(lastMaxPrice);
            $lastRow.find('input[name="totalPrice[]"]').val(lastMaxPrice);

            $lastRow.find('input[name="vatPrice[]"]').val(lastMaxPrice);
            $lastRow
              .find('input[name="noVatPrice[]"]')
              .val(bpRound(lastMaxPrice / 1.1));

            $lastRow.find('td[data-field-name="salePrice"]').text(lastMaxPrice);
            $lastRow
              .find('td[data-field-name="totalPrice"]')
              .text(lastMaxPrice);
          } else if (lastSalePrice < lastMaxPrice) {
            $lastRow.find('input[name="salePrice[]"]').val(lastSalePrice);
            $lastRow.find('input[name="totalPrice[]"]').val(lastSalePrice);

            $lastRow.find('input[name="vatPrice[]"]').val(lastSalePrice);
            $lastRow
              .find('input[name="noVatPrice[]"]')
              .val(bpRound(lastSalePrice / 1.1));

            $lastRow
              .find('td[data-field-name="salePrice"]')
              .text(lastSalePrice);
            $lastRow
              .find('td[data-field-name="totalPrice"]')
              .text(lastSalePrice);
          }
        }

        posConfigVisibler($lastRow);
        Core.initLongInput($lastRow);
        Core.initUniform($lastRow);

        if (isCalcRow) {
          Core.initDecimalPlacesInput($lastRow, 3);
        } else {
          Core.initDecimalPlacesInput($lastRow);
        }

        posCalcRow($lastRow);
        if (
          $tbody.find("> tr[data-item-id]").length &&
          $('select[name="invoiceTypeId"]').length
        ) {
          $('select[name="invoiceTypeId"]').select2("disable");
        }

        $lastRow.click();
        setTimeout(function () {
          $lastRow.find("input.pos-quantity-input").focus().select();
        }, 300);

        posItemPackageAction($tbody);
        posTableFillLastAction($tbody);

        posChooseItemGift($lastRow);

        //                var $prevItemRow = $lastRow.prev('tr[data-item-id]:eq(0)');
        //
        //                if ($prevItemRow.length && typeof $prevItemRow.attr('data-matrix-row') === 'undefined') {
        //                    var matrixPrevItemId = $prevItemRow.find('input[name="itemId[]"]').val();
        //                    var matrixCurrentItemId = rowData.id;
        //                    var uiMatrix = getUniqueId('sent-matrix-row-');
        //
        //                    $prevItemRow.attr('data-matrix-row', uiMatrix);
        //                    $lastRow.attr('data-matrix-row', uiMatrix);
        //
        //                    $.ajax({
        //                        type: 'post',
        //                        url: 'mdpos/getMatrixDiscound',
        //                        data: {
        //                            'filterItemId1': matrixPrevItemId,
        //                            'filterItemId2': matrixCurrentItemId
        //                        },
        //                        dataType: 'json',
        //                        beforeSend: function() {
        //                            Core.blockUI({
        //                                message: 'Loading...',
        //                                boxed: true
        //                            });
        //                        },
        //                        success: function(data) {
        //                            if (data) {
        //                                if (data.discountpercent && data.gift) {
        //                                    posChooseItemMatrixGift(data, $lastRow, $prevItemRow);
        //                                } else if (data.discountpercent) {
        //                                    posCalcRowDiscountPercent(data.discountpercent, $prevItemRow);
        //                                    posCalcRowDiscountPercent(data.discountpercent, $lastRow);
        //                                }
        //                            }
        //                            Core.unblockUI();
        //                        }
        //                    });
        //                }
        console.log("appendItem5...");
      } else if (data.status == "noendqty") {
        posItemEndQtyInfo(data.itemId, data.message);
      } else {
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false,
          addclass: "pnotify-center",
        });
      }

      Core.unblockUI();
      callback("");
    },
  });
}

function itemGroup(id) {
  $.ajax({
    type: "post",
    url: "mdpos/itemGroup",
    data: { parentId: id },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      if (id == '') {
        $(".pos-card-layout")
          .find(".card-options")
          .hide()
          .addClass("justify-content-center");
        $(".pos-card-layout").find(".back-item-btn").hide();
        $(".pos-card-layout").find(".item-card-toptitle").text("Ангилалууд");
      }

      var $mainContent = $(".pos-card-layout").find(".card-data-container"),
        html = "",
        isChild;

      if (data) {
        for (var i = 0; i < data.length; i++) {
          isChild = 0;
          if (data[i]["childrecordcount"]) {
            isChild = 1;
          }

          html +=
            '<div class="grid-card-itemgroup mr5 ml5" data-ischild="' +
            isChild +
            '" data-id="' +
            data[i]["id"] +
            '" data-filterid="' +
            (data[i]["filterid"] ? data[i]["filterid"] : "") +
            '" data-name="' +
            data[i]["name"] +
            '">' +
            '<div class="card">' +
            '<div class="card-body text-center">' +
            '<div class="card-img-actions d-inline-block d-none" style="background: url(' +
            (data[i]["picture"]
              ? data[i]["picture"]
              : "middleware/assets/img/pos/noimage.png") +
            ') center center no-repeat;background-size: cover;opacity: .1;position: absolute;width: 100%;top: 0;bottom: 0;left: 0;right: 0;height: 100%; margin: 0">' +
            //'<img class="rounded-circle" src="'+(data[i]['picture'] ? data[i]['picture'] : 'middleware/assets/img/pos/noimage.png')+'" width="80" height="60" alt="">'+
            "</div>" +
            '<h6 class="font-weight-bold mb-0 name">' +
            data[i]["name"] +
            "</h6>" +
            "</div>" +
            "</div>" +
            "</div>";
        }
      }
      $mainContent.empty().append(html);
      Core.unblockUI();
    },
  });
}

function item(id, name) {
  $.ajax({
    type: "post",
    url: "mdpos/getItemList",
    data: {
      filterid: id,
      rows: "1000",
      sort: "itemname",
      order: "asc",
    },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      var $cardContainer = $(".pos-card-layout");
      var $mainContent = $cardContainer.find(".card-data-container"),
        html = "";

      if (data && data.rows && data.rows.length) {
        data = data.rows;
        for (var i = 0; i < data.length; i++) {
          var existItem = 0;
          if (
            $cardContainer.find('div[data-itemid="' + data[i]["itemid"] + '"]')
              .length
          ) {
            existItem = $cardContainer
              .find('div[data-itemid="' + itemId + '"]')
              .find("input")
              .autoNumeric("get");
          }

          html +=
            '<div class="grid-card-item mr5" data-itemid="' +
            data[i]["itemid"] +
            '" data-itemcode="' +
            data[i]["itemcode"] +
            '">' +
            '<div class="card">' +
            '<div class="card-img-actions d-none">' +
            '<img class="card-img-top" src="' +
            (data[i]["picture"]
              ? data[i]["picture"]
              : "middleware/assets/img/pos/noimage.png") +
            '" width="100" height="60" alt="">' +
            "</div>" +
            '<div class="card-body mt3 mr15 mb3 ml6">' +
            '<span class="d-block text-muted">' +
            data[i]["itemcode"] +
            "</span>" +
            '<h6 class="font-weight-bold mt0 mb-0 name">' +
            data[i]["itemname"] +
            "</h6>" +
            '<div class="list-icons list-icons-extended mt-2 d-flex justify-content-between">' +
            '<a href="javascript:;" class="list-icons-item price" title="">' +
            pureNumberFormat(data[i]["saleprice"]) +
            "₮</a>" +
            '<a href="javascript:;" class="list-icons-item basket-button' +
            (!existItem ? " d-none" : " d-none") +
            '" title=""><i class="icon-bag mr5"></i>Сагс</a>' +
            '<a href="javascript:;" class="list-icons-item basket-qty-button d-flex justify-content-between" style="' +
            (!existItem ? "display:none !important" : "") +
            '" title="">' +
            '<span><i class="icon-minus3 mr5"></i></span>' +
            '<span><input class="bigdecimalInit" type="text" value="1"></span>' +
            '<span><i class="icon-plus3 ml5"></i></span>' +
            "</a>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";
        }
        $mainContent.empty().append(html);
      } else {
        PNotify.removeAll();
        new PNotify({
          title: "Info",
          text: "<strong>" + name + "</strong> ангилалд бараа байхгүй байна.",
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
      }
      Core.initDecimalPlacesInput($mainContent);
      Core.unblockUI();
      $(".pos-card-layout").find(".change-view:first").trigger("click");
      //                $this.parent().find('.change-view').removeClass('active');
      //                $this.addClass('active');
      //                $('.pos-card-layout').find('.card-data-container').removeClass('pos-card-view').removeClass('pos-list-view').addClass('pos-'+$this.attr('data-actiontype')+'-view');
    },
  });
}

function restTables(elem, openType) {
  var $dialogName = "dialog-pos-rest-tables";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo(
      "body"
    );
  }
  var $dialog = $("#" + $dialogName);

  if (typeof openType === "undefined") {
    restClears();
  }

  $("#guestName").prop("readonly", false);

  $.ajax({
    type: "post",
    url: "mdpos/restTables",
    data: {
      openType: openType,
    },
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data);

      var buttons = [
        {
          text: plang.get("close_btn"),
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ];

      $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: "Ширээний жагсаалт",
        modal: true,
        closeOnEscape: true,
        close: function () {
          $dialog.empty().dialog("destroy").remove();
        },
        buttons: buttons,
      }).dialogExtend({
        closable: true,
        maximizable: true,
        minimizable: true,
        collapsable: true,
        dblclick: "maximize",
        minimizeLocation: "left",
        icons: {
          close: "ui-icon-circle-close",
        },
      });
      $dialog.dialogExtend("maximize");
      $dialog.dialog("open");

      $dialog.on("dblclick", ".imageMarkerViewDivImage2", function (e) {
        var $this = $(this);
        $this.parent().find(".selected-row").removeClass("selected-row");
        $this.addClass("selected-row");

        var locationId = $this.attr("data-locationid");
        var selectedRow = JSON.parse($this.attr("data-row-data"));

        if (restPosEventType["event"] === "changeTable") {
          restPosEventType = { event: "", data: [] };
          restChangeTable();
          return;
        }

        if (restPosEventType["event"] === "mergeTable") {
          restPosEventType = { event: "", data: [] };
          restMergeTable();
          return;
        }

        if (restPosEventType["event"] === "pieceCalculate") {
          restSavePieceMergeTable(restPosEventType["data"], selectedRow);
          restPosEventType = { event: "", data: [] };
          return;
        }

        isMultiCustomerPrintBill = false;
        restClears();

        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });

        //                if (selectedRow['salesorderid'] != null && selectedRow['salesorderid'] != '') {
        //                    $('.seperate-calculation').removeClass('d-none');
        //                    $('.seperate-calculation').parent().find('span').text('Тооцоо салгах эсэх');
        //                }

        // if (($('#posRestWaiterId').val() == '' || locationId != $('#posLocationId').val()) && (selectedRow['salespersonid'] == null || selectedRow['salespersonid'] == '')) {
        var $dialogNameWaterPin = "dialog-waiter-pincode";
        if (!$("#" + $dialogNameWaterPin).length) {
          $('<div id="' + $dialogNameWaterPin + '"></div>').appendTo("body");
        }
        var $dialogWaiterPin = $("#" + $dialogNameWaterPin);

        $dialogWaiterPin
          .empty()
          .append(
            '<form method="post" autocomplete="off" id="waiterPassForm"><input type="password" name="waiterPinCode" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>'
          );
        $dialogWaiterPin.dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: "Нууц үг оруулах",
          width: 400,
          height: "auto",
          modal: true,
          open: function () {
            $dialogWaiterPin.on(
              "keydown",
              'input[name="waiterPinCode"]',
              function (e) {
                var keyCode = e.keyCode ? e.keyCode : e.which;
                if (keyCode == 13) {
                  $(this)
                    .closest(".ui-dialog")
                    .find(".ui-dialog-buttonpane button:first")
                    .trigger("click");
                  return false;
                }
              }
            );
          },
          close: function () {
            $dialogWaiterPin.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: plang.get("insert_btn"),
              class: "btn btn-sm green-meadow",
              click: function () {
                PNotify.removeAll();
                var $form = $("#waiterPassForm");

                $form.validate({ errorPlacement: function () { } });

                if ($form.valid()) {
                  var isPinSuccess = false,
                    waiterObj = [];
                  if (posCheckZBpassword) {
                    $.ajax({
                      type: "post",
                      url: "api/callDataview",
                      data: {
                        dataviewId: "16237213033721",
                        criteriaData: {
                          pincode: [{
                            operator: "=",
                            operand: $form.find('input[name="waiterPinCode"]').val(),
                          }
                          ],
                        },
                      },
                      dataType: "json",
                      beforeSend: function () {
                        Core.blockUI({
                          message: "Loading...",
                          boxed: true,
                        });
                      },
                      success: function (dataSub) {
                        if (dataSub.status == "success" && Object.keys(dataSub.result).length) {
                          isPinSuccess = true;
                        }
                        Core.unblockUI();
                        if (
                          ($("#posRestWaiterId").val() == "" ||
                            $("#posRestSalesOrderId").val() == "" ||
                            locationId != $("#posLocationId").val()) &&
                          (selectedRow["salespersonid"] == null ||
                            selectedRow["salespersonid"] == "")
                        ) {
                          isPinSuccess = false;
                        }

                        if (!isPinSuccess) {
                          $.ajax({
                            type: "post",
                            url: "api/callDataview",
                            data: {
                              dataviewId: "16207061606511",
                              criteriaData: {
                                pincode: [
                                  {
                                    operator: "=",
                                    operand: $form.find('input[name="waiterPinCode"]').val(),
                                  },
                                ],
                              },
                            },
                            dataType: "json",
                            async: false,
                            beforeSend: function () {
                              Core.blockUI({
                                message: "Loading...",
                                boxed: true,
                              });
                            },
                            success: function (dataSub) {
                              if (
                                dataSub.status == "success" &&
                                dataSub.result.length
                              ) {
                                isPinSuccess = true;
                                waiterObj = dataSub.result;
                              } else {
                                new PNotify({
                                  title: "Анхааруулга",
                                  text: "Зөөгчийн мэдээлэл олдсонгүй",
                                  type: "warning",
                                  sticker: false,
                                });
                              }
                              Core.unblockUI();
                            },
                          });
                        }

                        if (isPinSuccess) {
                          if (
                            selectedRow["salespersonid"] != null &&
                            selectedRow["salespersonid"] != ""
                          ) {
                            if (!waiterObj.length) {
                              new PNotify({
                                title: "Анхааруулга",
                                text: "Зөөгчийн мэдээлэлтэй таарахгүй байна.",
                                type: "warning",
                                sticker: false,
                              });
                              Core.unblockUI();
                              return;
                            }
                          }

                          if (
                            postParams["openType"] &&
                            postParams["openType"] == "f9" &&
                            $("#posLocationId").val() == "" &&
                            $(".ui-dialog").find(".pos-order-save").length
                          ) {
                            $.ajax({
                              type: "post",
                              url: "api/callDataview",
                              data: {
                                dataviewId: "1506324916539",
                                criteriaData: {
                                  id: [{ operator: "=", operand: locationId }],
                                },
                              },
                              dataType: "json",
                              async: false,
                              success: function (data) {
                                if (data.status === "success" && data.result[0]) {
                                  $('input[name="deskId"]').val(data.result[0].id);
                                  $('input[name="deskId_displayField"]').val(data.result[0].locationcode);
                                  $('input[name="deskId_nameField"]').val(data.result[0].locationname);
                                  $('input[name="deskId"]').attr("data-row-data", JSON.stringify(data.result[0])).trigger("change");
                                } else {
                                  $('input[name="deskId"]').val("");
                                  $('input[name="deskId_displayField"]').val("");
                                  $('input[name="deskId_nameField"]').val("");
                                  $('input[name="deskId"]').attr(
                                    "data-row-data",
                                    ""
                                  );
                                }
                                $(".ui-dialog")
                                  .find(".pos-order-save")
                                  .trigger("click");
                              },
                            });
                          } else {
                            var basketParams = [
                              { id: selectedRow["salesorderid"] },
                            ];
                            if (restPosEventType["event"] === "splitCalculate") {
                              basketParams = [
                                {
                                  id: selectedRow["salesorderid"],
                                  event: "splitCalculate",
                                  data: restPosEventType["data"],
                                },
                              ];
                            }
                            posFillItemsByBasket("", "", "", "", basketParams);
                          }

                          var getLastReadDateOrder = $.ajax({
                            type: "post",
                            url: "api/callProcess",
                            data: {
                              processCode: "GET_DATABASE_SYSDATE_004",
                              paramData: { salesOrderId: '' },
                            },
                            dataType: "json",
                            async: false,
                          });
                          getLastReadDateOrder = getLastReadDateOrder.responseJSON;
                          if (getLastReadDateOrder.status == "success") {
                            lastReadDateOrder = getLastReadDateOrder.result.result
                          }

                          $("#posLocationId").val(locationId);
                          $("#posRestSalesOrderId").val(selectedRow["salesorderid"] ? selectedRow["salesorderid"] : "");
                          $(".rest-table-btn").find("div").html('[ Сонгосон ширээ: <strong class="selected-pos-location">' + $this.attr("data-locationname") + "</strong> ]");

                          if (
                            ($("#posRestWaiterId").val() == "" ||
                              $("#posRestSalesOrderId").val() == "" ||
                              locationId != $("#posLocationId").val()) &&
                            (selectedRow["salespersonid"] == null ||
                              selectedRow["salespersonid"] == "")
                          ) {
                            $("#posRestWaiterId").val(waiterObj[0]["id"]);
                            $("#posRestWaiter").val(
                              waiterObj[0]["salespersonname"]
                            );
                            $(".rest-table-btn").find("div").html($(".rest-table-btn").find("div").html() + "<div>[ Сонгосон зөөгч: <strong>" + waiterObj[0]["salespersonname"] + "</strong> ]</div>");
                          } else {
                            $("#posRestWaiterId").val(waiterObj[0]["id"]);
                            $("#posRestWaiter").val(waiterObj[0]["salespersonname"]);
                            $(".rest-table-btn").find("div").html(
                              $(".rest-table-btn").find("div").html() + "<div>[ Сонгосон зөөгч: <strong>" + waiterObj[0]["salespersonname"] + "</strong> ]</div>"
                            );
                          }

                          $dialogWaiterPin.dialog("close");
                          setTimeout(function () {
                            $("#dialog-pos-rest-tables").dialog("close");
                          }, 300);
                        } else {
                          new PNotify({
                            title: "Анхааруулга",
                            text: "Нууц үг буруу байна!",
                            type: "warning",
                            sticker: false,
                          });
                        }
                      },
                    });
                  } else {
                    $.ajax({
                      type: "post",
                      url: "mdpos/checkTalonListPass",
                      data: {
                        talonListPass: $form
                          .find('input[name="waiterPinCode"]')
                          .val(),
                      },
                      dataType: "json",
                      beforeSend: function () {
                        Core.blockUI({
                          message: "Loading...",
                          boxed: true,
                        });
                      },
                      success: function (dataSub) {
                        if (dataSub.status == "success") {
                          isPinSuccess = true;
                        }
                        Core.unblockUI();
                        if (
                          ($("#posRestWaiterId").val() == "" ||
                            $("#posRestSalesOrderId").val() == "" ||
                            locationId != $("#posLocationId").val()) &&
                          (selectedRow["salespersonid"] == null ||
                            selectedRow["salespersonid"] == "")
                        ) {
                          isPinSuccess = false;
                        }

                        if (!isPinSuccess) {
                          $.ajax({
                            type: "post",
                            url: "api/callDataview",
                            data: {
                              dataviewId: "16207061606511",
                              criteriaData: {
                                pincode: [
                                  {
                                    operator: "=",
                                    operand: $form.find('input[name="waiterPinCode"]').val(),
                                  },
                                ],
                              },
                            },
                            dataType: "json",
                            async: false,
                            beforeSend: function () {
                              Core.blockUI({
                                message: "Loading...",
                                boxed: true,
                              });
                            },
                            success: function (dataSub) {
                              if (
                                dataSub.status == "success" &&
                                dataSub.result.length
                              ) {
                                isPinSuccess = true;
                                waiterObj = dataSub.result;
                              } else {
                                new PNotify({
                                  title: "Анхааруулга",
                                  text: "Зөөгчийн мэдээлэл олдсонгүй",
                                  type: "warning",
                                  sticker: false,
                                });
                              }
                              Core.unblockUI();
                            },
                          });
                        }

                        if (isPinSuccess) {
                          if (
                            selectedRow["salespersonid"] != null &&
                            selectedRow["salespersonid"] != ""
                          ) {
                            if (!waiterObj.length) {
                              new PNotify({
                                title: "Анхааруулга",
                                text: "Зөөгчийн мэдээлэлтэй таарахгүй байна.",
                                type: "warning",
                                sticker: false,
                              });
                              Core.unblockUI();
                              return;
                            }
                          }

                          if (
                            postParams["openType"] &&
                            postParams["openType"] == "f9" &&
                            $("#posLocationId").val() == "" &&
                            $(".ui-dialog").find(".pos-order-save").length
                          ) {
                            $.ajax({
                              type: "post",
                              url: "api/callDataview",
                              data: {
                                dataviewId: "1506324916539",
                                criteriaData: {
                                  id: [{ operator: "=", operand: locationId }],
                                },
                              },
                              dataType: "json",
                              async: false,
                              success: function (data) {
                                if (data.status === "success" && data.result[0]) {
                                  $('input[name="deskId"]').val(data.result[0].id);
                                  $('input[name="deskId_displayField"]').val(data.result[0].locationcode);
                                  $('input[name="deskId_nameField"]').val(data.result[0].locationname);
                                  $('input[name="deskId"]').attr("data-row-data", JSON.stringify(data.result[0])).trigger("change");
                                } else {
                                  $('input[name="deskId"]').val("");
                                  $('input[name="deskId_displayField"]').val("");
                                  $('input[name="deskId_nameField"]').val("");
                                  $('input[name="deskId"]').attr(
                                    "data-row-data",
                                    ""
                                  );
                                }
                                $(".ui-dialog")
                                  .find(".pos-order-save")
                                  .trigger("click");
                              },
                            });
                          } else {
                            var basketParams = [
                              { id: selectedRow["salesorderid"] },
                            ];
                            if (restPosEventType["event"] === "splitCalculate") {
                              basketParams = [
                                {
                                  id: selectedRow["salesorderid"],
                                  event: "splitCalculate",
                                  data: restPosEventType["data"],
                                },
                              ];
                            }
                            posFillItemsByBasket("", "", "", "", basketParams);
                          }

                          var getLastReadDateOrder = $.ajax({
                            type: "post",
                            url: "api/callProcess",
                            data: {
                              processCode: "GET_DATABASE_SYSDATE_004",
                              paramData: { salesOrderId: '' },
                            },
                            dataType: "json",
                            async: false,
                          });
                          getLastReadDateOrder = getLastReadDateOrder.responseJSON;
                          if (getLastReadDateOrder.status == "success") {
                            lastReadDateOrder = getLastReadDateOrder.result.result
                          }

                          $("#posLocationId").val(locationId);
                          $("#posRestSalesOrderId").val(selectedRow["salesorderid"] ? selectedRow["salesorderid"] : "");
                          $(".rest-table-btn").find("div").html('[ Сонгосон ширээ: <strong class="selected-pos-location">' + $this.attr("data-locationname") + "</strong> ]");

                          if (
                            ($("#posRestWaiterId").val() == "" ||
                              $("#posRestSalesOrderId").val() == "" ||
                              locationId != $("#posLocationId").val()) &&
                            (selectedRow["salespersonid"] == null ||
                              selectedRow["salespersonid"] == "")
                          ) {
                            $("#posRestWaiterId").val(waiterObj[0]["id"]);
                            $("#posRestWaiter").val(
                              waiterObj[0]["salespersonname"]
                            );
                            $(".rest-table-btn").find("div").html($(".rest-table-btn").find("div").html() + "<div>[ Сонгосон зөөгч: <strong>" + waiterObj[0]["salespersonname"] + "</strong> ]</div>");
                          } else {
                            $("#posRestWaiterId").val(waiterObj[0]["id"]);
                            $("#posRestWaiter").val(waiterObj[0]["salespersonname"]);
                            $(".rest-table-btn").find("div").html(
                              $(".rest-table-btn").find("div").html() +
                              "<div>[ Сонгосон зөөгч: <strong>" +
                              waiterObj[0]["salespersonname"] +
                              "</strong> ]</div>"
                            );
                          }

                          $dialogWaiterPin.dialog("close");
                          setTimeout(function () {
                            $("#dialog-pos-rest-tables").dialog("close");
                          }, 300);
                        } else {
                          new PNotify({
                            title: "Анхааруулга",
                            text: "Нууц үг буруу байна!",
                            type: "warning",
                            sticker: false,
                          });
                        }
                      },
                    });
                  }
                }
              },
            },
            {
              text: plang.get("close_btn"),
              class: "btn btn-sm blue-madison",
              click: function () {
                $dialogWaiterPin.dialog("close");
              },
            },
          ],
        });
        $dialogWaiterPin.dialog("open");

        /*var $dialogNameWaiter = 'dialog-waiter-form';
              if (!$("#" + $dialogNameWaiter).length) {
                $('<div id="' + $dialogNameWaiter + '"></div>').appendTo('body');
              }
  	
              var $dialogPWaiter = $('#' + $dialogNameWaiter);
              var selectHtml = '<div style="overflow:auto">';
              for(var i = 0; i < waiter.responseJSON.result.length; i++) {
                selectHtml += '<div data-id="'+waiter.responseJSON.result[i]['id']+'" data-code="'+waiter.responseJSON.result[i]['salespersoncode']+'" data-name="'+waiter.responseJSON.result[i]['salespersonname']+'" class="mb10 d-flex justify-content-start rest-choose-waiter" style="background: #FFFFFF;box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.08);border-radius: 10px;cursor: pointer">';
                selectHtml += '<div style="padding:10px"><img class="rounded-circle" src="middleware/assets/img/pos/noprofile.png" width="36" height="36" alt=""></div>';
                selectHtml += '<div style="padding:10px;font-size:14px"><div>'+waiter.responseJSON.result[i]['salespersonname']+'</div><div style="color:#A0A0A0;font-size:12px;" class="mt3">'+waiter.responseJSON.result[i]['salespersoncode']+'</div></div>';
                selectHtml += '</div>';
              }   
              selectHtml += '</div>';
  	
              $dialogPWaiter.empty().append('<form method="post" autocomplete="off" id="waiterForm">'+selectHtml+'</form>');
              $dialogPWaiter.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Зөөгч сонгох', 
                width: 280,
                height: 'auto',
                maxHeight: 750,                        
                position: {my: 'top', at: 'top+15'},
                modal: true,
                open: function () {
                  $dialogPWaiter.css('background-color', '#F5F5F5');
                  $dialogPWaiter.on('click', '.rest-choose-waiter', function(e){
                    $('#posRestWaiterId').val($(this).data('id'));
                    $('#posRestWaiter').val($(this).data('name'));
                    $('.rest-table-btn').find('div').html($('.rest-table-btn').find('div').html() + '<div>[ Сонгосон зөөгч: <strong>'+$(this).data('name')+'</strong> ]</div>');                                                
  	
                    if (postParams['openType'] && postParams['openType'] == 'f9' && $('#posLocationId').val() == '') {
                      $.ajax({
                        type: 'post',
                        url: 'api/callDataview',
                        data: {dataviewId: '1506324916539', criteriaData: {id: [{operator: '=', operand: locationId}]}}, 
                        dataType: 'json',
                        async: false,
                        success: function(data) {                            
                          if (data.status === 'success' && data.result[0]) {
                            $('input[name="deskId"]').val(data.result[0].id);
                            $('input[name="deskId_displayField"]').val(data.result[0].locationcode);
                            $('input[name="deskId_nameField"]').val(data.result[0].locationname);                                                    
                            $('input[name="deskId"]').attr('data-row-data', JSON.stringify(data.result[0])).trigger('change');
                          } else {
                            $('input[name="deskId"]').val('');
                            $('input[name="deskId_displayField"]').val('');
                            $('input[name="deskId_nameField"]').val('');                                                    
                            $('input[name="deskId"]').attr('data-row-data', '');                          
                          }
                          $('.ui-dialog').find('.pos-order-save').trigger('click');
                        }
                      });                     
                    } else {
                      posFillItemsByBasket('','','','',[{id: selectedRow['salesorderid']}]);                                        
                    }         
  	
                    $('#posLocationId').val(locationId);                    
                    $('#posRestWaiterId').val(selectedRow['salespersonid'] ? selectedRow['salespersonid'] : $('#posRestWaiterId').val());      
                    $('#posRestSalesOrderId').val(selectedRow['salesorderid'] ? selectedRow['salesorderid'] : '');
                    $('#posRestWaiter').val(selectedRow['salespersonid'] ? selectedRow['salespersoncode']+' - '+selectedRow['salespersonname'] : $('#posRestWaiter').val());
                    var waiterHtml = selectedRow['salespersonid'] ? '<div>[ Сонгосон зөөгч: <strong>'+selectedRow['salespersoncode']+' - '+selectedRow['salespersonname']+' - '+selectedRow['salespersonname']+'</strong> ]</div>' : '<div>[ Сонгосон зөөгч: <strong>'+$('#posRestWaiter').val()+'</strong> ]</div>';
                    $('.rest-table-btn').find('div').html('[ Сонгосон ширээ: <strong class="selected-pos-location">'+$this.attr('data-locationname')+'</strong> ]'+waiterHtml);                                
                      
                    $dialogPWaiter.dialog('close');
                    setTimeout(function(){
                      $('#dialog-pos-rest-tables').dialog('close');
                    }, 300);
                  });
                },
                close: function () {
                  $dialogPWaiter.empty().dialog('destroy').remove();
                },
                buttons: []
              });
              Core.initSelect2($dialogPWaiter);
              $dialogPWaiter.dialog('open');*/

        // } else {

        //     $('#posLocationId').val(locationId);
        //     $('#posRestWaiterId').val(selectedRow['salespersonid'] ? selectedRow['salespersonid'] : $('#posRestWaiterId').val());
        //     $('#posRestSalesOrderId').val(selectedRow['salesorderid'] ? selectedRow['salesorderid'] : '');
        //     $('#posRestWaiter').val(selectedRow['salespersonid'] ? selectedRow['salespersoncode']+' - '+selectedRow['salespersonname'] : $('#posRestWaiter').val());
        //     $('#dialog-pos-rest-tables').empty().dialog('destroy').remove();
        //     var waiterHtml = selectedRow['salespersonid'] ? '<div>[ Сонгосон зөөгч: <strong>'+selectedRow['salespersoncode']+' - '+selectedRow['salespersonname']+'</strong> ]</div>' : '<div>[ Сонгосон зөөгч: <strong>'+$('#posRestWaiter').val()+'</strong> ]</div>';
        //     $('.rest-table-btn').find('div').html('[ Сонгосон ширээ: <strong class="selected-pos-location">'+$this.attr('data-locationname')+'</strong> ]'+waiterHtml);
        // }
        Core.unblockUI();
      });
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initBPAjax($dialog);
    Core.unblockUI();
  });
}

function getEshop(callback) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: "mdpos/fillItemsByEshop",
    data: {
      qr: "abcd12345",
    },
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      callback(data);
    },
    error: function () {
      alert("Error");
      Core.unblockUI();
    },
  }).done(function () { });
}

function restPrePrint() {
  var $tbody = $("#posTable > tbody"),
    $rows = $tbody.find("> tr[data-item-id]");
  if ($tbody.length) {
    var htmlPrint =
      '<table border="0" width="100%" style="width: 100%; table-layout: fixed;">' +
      "<tbody>" +
      "<tr>" +
      '<td style="width: 100%; border-top:1px #000 dashed; border-bottom:1px #000 dashed; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: 600; padding: 2px 0; text-align: left;">Барааны нэр</td>' +
      '<td style="width: 65px; border-top:1px #000 dashed; border-bottom:1px #000 dashed; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: 600; padding: 2px 0; text-align: right;">Үнэ</td>' +
      '<td style="width: 35px; border-top:1px #000 dashed; border-bottom:1px #000 dashed; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: 600; padding: 2px 0; text-align: right;">Тоо</td>' +
      '<td style="width: 85px; border-top:1px #000 dashed; border-bottom:1px #000 dashed; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: 600; padding: 2px 0; text-align: right;">Дүн</td>' +
      "</tr>";

    $rows.each(function () {
      var $this = $(this);
      htmlPrint +=
        "<tr>" +
        '<td style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-weight: normal; padding: 3px 0; text-align: left; line-height: 12px; vertical-align: bottom">' +
        '<span style="font-family: Arial, Helvetica, sans-serif;">' +
        $this.find('input[name="itemName[]"]').val() +
        "</span>" +
        "</td>" +
        '<td style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">' +
        pureNumberFormat($this.find('input[name="salePrice[]"]').val()) +
        "</td>" +
        '<td style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">' +
        $this.find('input[name="quantity[]"]').val() +
        "</td>" +
        '<td style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">' +
        pureNumberFormat($this.find('input[name="totalPrice[]"]').val()) +
        "</td>" +
        "</tr>";
    });
    htmlPrint +=
      "<tr>" +
      '<td style="border-top:1px #000 solid, font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-weight: normal; padding: 3px 0; text-align: left; line-height: 12px; vertical-align: bottom">' +
      "</td>" +
      '<td style="border-top:1px #000 solid, font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">' +
      "</td>" +
      '<td style="border-top:1px #000 solid, font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">' +
      "</td>" +
      '<td style="border-top:1px #000 solid, font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">' +
      $("td.pos-amount-total").text() +
      "</td>" +
      "</tr>";
    htmlPrint += "</tbody>" + "</table>";

    $.ajax({
      type: "post",
      url: "mdpos/testBillPrint",
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Printing...",
          boxed: true,
        });
      },
      success: function (data) {
        $("div.pos-preview-print").html(htmlPrint).promise().done(function () {
          $("div.pos-preview-print").printThis({
            debug: false,
            importCSS: false,
            printContainer: false,
            dataCSS: data.css,
            removeInline: false,
          });
        });

        Core.unblockUI();
      },
      error: function () {
        alert("Error");
      },
    });
  }
}
function restClears(billNumber) {
  $("#posLocationId").val("");
  $("#guestName").val("");
  $("#posRestWaiterId").val("");
  $("#posRestWaiter").val("");
  $("#posRestSalesOrderId").val("");
  $(".rest-table-btn").find("div").html("");
  billNumber = billNumber ? billNumber : "";
  posDisplayReset(billNumber);
  itemGroup("");
}
function deleteRestOrder(callback, v) {
  if ($("#posRestSalesOrderId").val() || (typeof v !== "undefined" && v)) {
    $.ajax({
      type: "post",
      url: "mdpos/returnTableRest",
      data: {
        id: typeof v !== "undefined" && v ? v : $("#posRestSalesOrderId").val(),
      },
      dataType: "json",
      success: function (data) {
        callback();
      },
    });
  } else {
    callback();
  }
}
function splitCalculateSaveRest(invoiceId, callback) {
  $.ajax({
    type: "post",
    url: "mdpos/splitCalculateSaveRest",
    data: {
      id: $("#posRestSalesOrderId").val(),
      data: restPosEventType["data"],
      invoiceId: invoiceId,
    },
    dataType: "json",
    success: function (data) {
      callback(data);
    },
  });
}
function posToBasketRestauron(type, callback, serializestring, notLocation) {
  if (posTypeCode == "3") {
    var $posTableBody = $("#posTable > tbody");
    if (
      //($("#posLocationId").val() != "" || typeof notLocation !== "undefined") &&
      $posTableBody.find("> tr[data-item-id]").length
    ) {
      var $posTableBody = $("#posTable > tbody");
      var paymentData = "isBasket=&deskId=" + $("#posLocationId").val() + "&payAmount=" + $(".pos-amount-paid").autoNumeric("get") + "&customerId=" + $('input[name="empCustomerId"]').val(),
        itemData = $posTableBody.find("input").serialize(),
        vatAmount = $(".pos-amount-vat").autoNumeric("get"),
        cityTaxAmount = $(".pos-amount-citytax").autoNumeric("get"),
        discountAmount = $(".pos-amount-discount").autoNumeric("get");

      paymentData = paymentData + "&vatAmount=" + vatAmount + "&cityTaxAmount=" + cityTaxAmount + "&discountAmount=" + discountAmount + "&basketInvoiceId=" + $("#basketInvoiceId").val();
      if ($("#posRestWaiterId").length) {
        paymentData += "&waiterId=" + $("#posRestWaiterId").val();
      }

      // var modifiedOrderDate = $.ajax({
      //   type: "post",
      //   url: "api/callProcess",
      //   data: {
      //     processCode: "POS_IS_NOT_LAST_ORDER_LIST_004",
      //     paramData: { salesOrderId: $("#posRestSalesOrderId").val(), lastReadDate: lastReadDateOrder },
      //   },
      //   dataType: "json",
      //   async: false,
      // });
      // modifiedOrderDate = modifiedOrderDate.responseJSON;
      // if (modifiedOrderDate.status == "success" && modifiedOrderDate.result.result == 1) {
      //   new PNotify({
      //     title: "Warning",
      //     text: "Энэ ширээн дээр өөрчлөлт орсон тул refresh хийж захиалгаа шинэчилнэ үү!",
      //     type: "warning",
      //     sticker: false,
      //     addclass: "pnotify-center",
      //   });
      //   return;
      // }

      $.ajax({
        type: "post",
        url: "mdpos/orderSave",
        data: {
          paymentData: paymentData,
          itemData: itemData,
          orderData: globalOrderData,
          kitchenData:
            typeof serializestring !== "undefined" ? serializestring : "",
          resetOrderDtl: type,
        },
        dataType: "json",
        beforeSend: function () {
          Core.blockUI({
            message: "Saving...",
            boxed: true,
          });
        },
        success: function (data) {
          PNotify.removeAll();

          if (data.status === "success") {
            lastReadDateOrder = '';
            globalOrderData = [];
            coldF9 = true;
            //True
            if (type === "resetOrderDtl") {
              if (callback) {
                callback(data.id);
              }
              Core.unblockUI();
              return;
            }

            if (isIpad) {
              alert("Pad order printing...");

              $.ajax({
                type: "post",
                url: "api/callProcess",
                data: {
                  processCode: "PRINT_ORDER_BILL_SKY_DV_001",
                  paramData: {
                    orderId: data.id,
                    PRINT_ORDER_BILL_SKY_DTL:
                      data.orderData.pos_sdm_sales_order_item_dtl,
                  },
                },
                dataType: "json",
                async: false,
                success: function (data) {
                  console.log(data);
                },
              });

              $.ajax({
                type: "post",
                url: "api/callProcess",
                data: {
                  processCode: "UPDATE_SSOID_IS_PRINT_004",
                  paramData: { id: data.id },
                },
                dataType: "json",
                async: false,
                success: function (data) {
                  console.log(data);
                },
              });

              if (callback) {
                callback(data.id);
              }

              restTables("", "f9");
              restClears();

              return;
            }

            $.ajax({
              type: "post",
              url: "api/callProcess",
              data: {
                processCode: "print_kitchen_bill",
                paramData: { orderId: data.id },
              },
              dataType: "json",
              async: false,
              success: function (data) {
                console.log(data);
              },
            });

            $.ajax({
              type: "post",
              url: "mdstatement/renderDataModelByFilter",
              data: {
                dataViewId: "1610966203040059",
                statementId: "1610967334732273",
                param: { salesOrderId: data.id },
                criteriaCondition: { salesOrderId: "=" },
              },
              dataType: "json",
              beforeSend: function () { },
              success: function (sdata) {
                /*if ($("#posRestSalesOrderId").val() && type == "f9") {
                  posToBasketRestauron(
                  "resetOrderDtl",
                  function () {
                    restClears();
                    restTables("", "f9");
                  },
                  typeof serializestring !== "undefined"
                    ? serializestring
                    : ""
                  );
                } else if (typeof notLocation === "undefined") {
                  restTables("", "f9");
                  restClears();
                }*/

                $.ajax({
                  type: "post",
                  url: "api/callProcess",
                  data: {
                    processCode: "UPDATE_SSOID_IS_PRINT_004",
                    paramData: { id: data.id },
                  },
                  dataType: "json",
                  async: false,
                  success: function (data) {
                    console.log(data);
                  },
                });

                if (callback) {
                  callback(data.id);
                }

                restTables("", "f9");
                restClears();

                if (sdata.htmlData.indexOf("Тохирох үр дүн олдсонгүй") === -1) {
                  $("div.pos-preview-print")
                    .html(sdata.htmlData)
                    .promise()
                    .done(function () {
                      $("div.pos-preview-print").printThis({
                        debug: false,
                        importCSS: false,
                        printContainer: false,
                        dataCSS: data.css,
                        removeInline: false,
                      });
                    });
                }
                Core.unblockUI();
              },
            });
          } else {
            new PNotify({
              title: "Warning",
              text: data.message,
              type: "warning",
              sticker: false,
              addclass: "pnotify-center",
            });
            Core.unblockUI();
          }
        },
      });
      // } else if (type === "f9") {
      //   PNotify.removeAll();
      //   new PNotify({
      //     title: "Info",
      //     text: "Ширээгээ сонгоно уу.",
      //     type: "info",
      //     sticker: false,
      //     addclass: "pnotify-center",
      //   });
    } else {
      if (callback) {
        callback();
      }
    }
  }
}
function foodItem() {
  var $tbody = $("#posTable > tbody"),
    isFood = false,
    $rows = $tbody.find("> tr[data-item-id]");
  var itemHtml =
    '<div><a class="btn green-meadow btn-circle btn-sm insert-text-food" href="javascript:;">түр хойшлуулах</a>' +
    '<a class="btn green-meadow btn-circle btn-sm insert-text-food ml6" href="javascript:;">халуун ногоо багатай</a>' +
    '<a class="btn green-meadow btn-circle btn-sm insert-text-food ml6" href="javascript:;">сонгино багатай</a>' +
    '<div><table class="fancyTable fht-table fht-table-init table-only-food" cellpadding="0" cellspacing="0">' +
    '<thead style=""><tr><th style="width: 70px;" class="d-none"><div class="fht-cell" style="width: 0px;"></div></th><th style="width: 140px; text-align: left;" class="d-none">Код<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 120px; text-align: left;" data-config-column="serialnumber" class="hide">Сериал<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 200px; text-align: left;font-weight: normal;"><span style="">Барааны нэр</span><div class="fht-cell" style="width: 199px;"></div></th><th style="width: 100px; text-align: right;font-weight: normal;" class="d-none">Үнэ<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 100px; text-align: right;" data-config-column="unitreceivable" class="d-none hide">Даатгал<div class="fht-cell" style="width: 0px;"></div></th><th style="width: 50px; text-align: center;font-weight: normal;"><span class="infoShortcut" style="position: absolute;margin-left: 27px;font-size: 9px;"></span> Тоо<div class="fht-cell" style="width: 50px;"></div></th><th style="width: 50px;font-weight: normal;"><span class="infoShortcut" style="position: absolute;margin-left: 27px;font-size: 9px;"></span> Тайлбар<div class="fht-cell" style="width: 76px;"></div></th><th colspan="2" style="width: 80px;font-weight: normal;">Дараалал<div class="fht-cell" style="width: 125px;"></div></th><th style="width: 20px; text-align: center;" data-config-column="delivery" class="hide"><i class="fa fa-truck" title="Хүргэлттэй эсэх"></i><div class="fht-cell" style="width: 24px;"></div></th><th style="width: 280px; text-align: center;" data-config-column="salesperson" class="hide">Худалдааны зөвлөх<div class="fht-cell" style="width: 131px;"></div></th></tr></thead><tbody>';

  $rows.each(function () {
    var $row = $(this);
    if ($row.find('input[data-name="isFood"]').length && $row.find('input[data-name="isFood"]').val() == "1" && $row.find('input[name="salesOrderId[]"]').val() == "") {
      isFood = true;
      itemHtml +=
        '<tr class=""><td data-field-name="gift" class="text-center d-none" style=""></td><td data-field-name="itemCode" class="text-left d-none" style="font-size: 14px;">8656170014839</td><td data-field-name="serialNumber" data-config-column="serialnumber" class="text-left hide" style=""></td><td data-field-name="itemName" class="text-left pt10 pb10" title="" style="font-size: 14px; line-height: 15px;font-weight: normal;"><div class="item-code-mini" style="color:#A0A0A0;font-size: 12px;">' +
        $row.find('input[name="itemCode[]"]').val() +
        '</div><div class="mt3">' +
        $row.find('input[name="itemName[]"]').val() +
        '</div><input type="hidden" data-name="salePrice" value=""></td><td data-field-name="salePrice" class="text-right bigdecimalInit d-none" style=""></td><td data-field-name="unitReceivable" data-config-column="unitreceivable" class="text-right bigdecimalInit d-none hide" style=""></td><td data-field-name="quantity" style="height:28.8px;" class="pos-quantity-cell text-right">' +
        $row.find('input[name="quantity[]"]').val() +
        '</td><td style="height:28.8px;" class="pos-quantity-cell"><input type="hidden" class="foodNumber" name="foodNumber[' +
        $row.find('input[name="itemId[]"]').val() +
        ']"/><textarea style="height: 46px;margin-top: 4px;" name="foodDescription[' +
        $row.find('input[name="itemId[]"]').val() +
        ']"></textarea></td><td class="text-right bigdecimalInit"><button type="button" class="btn green-meadow only-food-up" style="background-color: #FFCC00;"><i class="fa fa-arrow-up" style="font-size: 27px;"></i></button></td><td data-field-name="totalPrice" class="text-right bigdecimalInit"><button type="button" class="btn green-meadow only-food-down" style="background-color: #FFCC00;"><i class="fa fa-arrow-down" style="font-size: 27px;color:#656565"></i></button></td><td data-field-name="delivery" class="text-center hide" data-config-column="delivery" style=""></td><td data-field-name="salesperson" class="text-center hide" data-config-column="salesperson" style=""></td></tr>';
    }
  });
  itemHtml += "</tbody></table>";
  if (!isFood) {
    posToBasketRestauron("f9", false, "");
    return;
  }

  var $dialogName = "dialog-talon-onlyfood";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialogP = $("#" + $dialogName);
  $dialogP.empty().append(
    '<form method="post" autocomplete="off" id="talonFoodCalcForm">' +
    itemHtml +
    "</form>"
  );
  $dialogP.dialog({
    cache: false,
    resizable: true,
    bgiframe: true,
    autoOpen: false,
    title: "Гал тогоо тайлбар",
    width: 650,
    height: "auto",
    modal: true,
    open: function () {
      var $footable = $dialogP.find("table.table-only-food > tbody > tr");
      $footable.first().addClass("pos-selected-row");

      if ($footable.length == 1) {
        $(".only-food-up").addClass("d-none");
        $(".only-food-down").addClass("d-none");
      } else {
        $footable.first().find(".only-food-up").addClass("d-none");
        $footable.last().find(".only-food-down").addClass("d-none");
      }
      $footable.each(function (i, r) {
        $(this).find(".foodNumber").val(i);
      });

      $(document.body).on("click", "table.table-only-food > tbody > tr", function (e) {
        $(this).parents("tbody").find("tr").removeClass("pos-selected-row");
        if ($(e.target)[0]["nodeName"] != "BUTTON" || $(e.target)[0]["nodeName"] != "I") {
          $(this).addClass("pos-selected-row");
        }
      }
      );
      $(document.body).on("click", ".insert-text-food", function (e) {
        var oldText = $dialogP.find("table.table-only-food > tbody > tr.pos-selected-row").find("textarea").val();
        $dialogP.find("table.table-only-food > tbody > tr.pos-selected-row").find("textarea").val(oldText + (oldText ? ", " : "") + $(this).text());
      });
      $(document.body).on("click", ".only-food-up", function (e) {
        var $tr = $(this).closest("tr");
        $tr.insertBefore($tr.prev());

        var $footable = $dialogP.find("table.table-only-food > tbody > tr");
        if ($footable.length == 2) {
          $footable.first().find(".only-food-up").addClass("d-none");
          $footable.first().find(".only-food-down").removeClass("d-none");
          $footable.last().find(".only-food-down").addClass("d-none");
          $footable.last().find(".only-food-up").removeClass("d-none");
        } else {
          $footable.first().find(".only-food-up").addClass("d-none");
          $footable.last().find(".only-food-down").addClass("d-none");
        }
        $footable.each(function (i, r) {
          $(this).find(".foodNumber").val(i);
        });
      });
      $(document.body).on("click", ".only-food-down", function (e) {
        var $tr = $(this).closest("tr");
        $tr.insertAfter($tr.next());

        var $footable = $dialogP.find("table.table-only-food > tbody > tr");
        if ($footable.length == 2) {
          $footable.first().find(".only-food-up").addClass("d-none");
          $footable.first().find(".only-food-down").removeClass("d-none");
          $footable.last().find(".only-food-down").addClass("d-none");
          $footable.last().find(".only-food-up").removeClass("d-none");
        } else {
          $footable.first().find(".only-food-up").addClass("d-none");
          $footable.last().find(".only-food-down").addClass("d-none");
        }
        $footable.each(function (i, r) {
          $(this).find(".foodNumber").val(i);
        });
      });
      $(document.body).on("keydown", "textarea", function (e) {
        var keyCode = e.keyCode ? e.keyCode : e.which;
        if (keyCode == 13) {
          var $tr = $(this).closest("tr");
          $tr.next("tr").find("textarea").focus().select().click();
          setTimeout(function () {
            $tr.next("tr").find("textarea").focus().select().click();
          }, 100);
        }
      });
    },
    close: function () {
      $dialogP.empty().dialog("destroy").remove();
    },
    buttons: [
      {
        text: "Илгээх",
        class: "btn btn-sm green-meadow send-kitchen-fooditem",
        click: function () {
          var $form = $("#talonFoodCalcForm");
          posToBasketRestauron("f9", false, $form.serialize());
          $dialogP.dialog("close");
        },
      },
      {
        text: plang.get("close_btn"),
        class: "btn btn-sm blue-madison",
        click: function () {
          posToBasketRestauron("f9", false, "");
          $dialogP.dialog("close");
        },
      },
    ],
  });
  $dialogP.dialog("open");
}
function saveOrderF9Btn() {
  if (!isBasketOnly) {
    if (typeof posElectronTalonWindow == "undefined") {
      posToBasket();
    }
  }
}

function posRemoveItemHeader() {
  posRowRemove($("#posTable").find("tbody > tr.pos-selected-row"));
}
function posDiscountCustomer(id, changeCustomer) {
  var $posTableBody = $("#posTable > tbody");
  var itemData = $posTableBody.find("input").serialize();
  var $posBody = $("#posTable > tbody");

  if (id) {
    if (
      $posBody.find("> tr.multi-customer-group").length === 1 &&
      $posBody.find('> tr[data-customerid=""]').length
    ) {
      var $rows = $('#posTable > tbody > tr[data-customerid=""]');
    } else {
      var $rows = $('#posTable > tbody > tr[data-customerid="' + id + '"]');
    }

    if (
      $posBody.find("> tr.multi-customer-group").length === 1 &&
      typeof changeCustomer !== "undefined"
    ) {
      $('input[name="empCustomerId_displayField"]').val(
        changeCustomer.customercode
      );
      $('input[name="empCustomerId_nameField"]').val(
        changeCustomer.customername
      );
      $('input[name="empCustomerId"]').val(changeCustomer.id);
    }

    if ($rows.length) {
      $rows.each(function () {
        var $tr = $(this);

        if (typeof changeCustomer !== "undefined") {
          $tr.attr("data-customerid", changeCustomer.id);
          if ($tr.hasClass("multi-customer-group")) {
            $tr
              .find("> td:eq(0)")
              .html(
                changeCustomer.customercode +
                "-" +
                changeCustomer.customername +
                ' <a href="javascript:;" style="background-color: #e4b700;color: #333;padding: 4px 3px 3px 4px;margin-left: 13px; display:none" onclick="posCustomerList(this);">харилцагч өөрчлөх</a>'
              );
          }
        } else {
          $tr.attr("data-customerid", id);
          if ($tr.hasClass("multi-customer-group")) {
            $tr.find("> td:eq(0)").html(
              $('input[name="empCustomerId_displayField"]').val() +
              "-" +
              $('input[name="empCustomerId_nameField"]').val() +
              ' <a href="javascript:;" style="background-color: #e4b700;color: #333;padding: 4px 3px 3px 4px;margin-left: 13px; display:none" onclick="posCustomerList(this);">харилцагч өөрчлөх</a>'
            );
          }
        }

        if ($tr.find('input[name="customerId[]"]').length) {
          if (typeof changeCustomer !== "undefined") {
            $tr.attr("data-item-id-customer-id", $tr.attr("data-item-id") + "_" + changeCustomer.id);
            $tr.find('input[name="customerId[]"]').val(changeCustomer.id);
            $tr.find('input[name="guestName[]"]').val(changeCustomer.customercode + "-" + changeCustomer.customername);
          } else {
            $tr.attr("data-item-id-customer-id", $tr.attr("data-item-id") + "_" + id);
            $tr.find('input[name="customerId[]"]').val(id);
            $tr.find('input[name="guestName[]"]').val($("#guestName").length ? $("#guestName").val() : "");
          }
          $tr.find('input[name="isDiscount[]"]').val("0");
          $tr.find('input[name="unitDiscount[]"]').val("");
          $tr.find('input[name="discountAmount[]"]').val("");
          $tr.find('input[name="discountPercent[]"]').val("");
          $tr.find('input[name="totalDiscount[]"]').val("");

          $tr.find('input[name="unitBonusAmount[]"]').val("");
          $tr.find('input[name="unitBonusPercent[]"]').val("");
          $tr.find('input[name="lineTotalBonusAmount[]"]').val("");
          $tr.find('input[data-name="calcBonusPercent"]').val("");
          posCalcRow($tr);
        }
      });

      if (typeof changeCustomer !== "undefined" && $('#posTable > tbody > tr[data-customerid="' + changeCustomer.id + '"][class="multi-customer-group"]').length == 2) {
        $('#posTable > tbody > tr[data-customerid="' + changeCustomer.id + '"][class="multi-customer-group"]').eq(1).remove();
      }
    }

    if (typeof changeCustomer !== "undefined") {
      id = changeCustomer.id;
    }

    $.ajax({
      type: "post",
      url: "mdpos/customerDiscount",
      data: { itemData: itemData, customerId: id },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        if (data) {
          if (data.noSectionId) {
            new PNotify({
              title: "Warning",
              text:
                data.noSectionId +
                " кодтой бараанууд тасаг тохируулаагүй байна!",
              type: "warning",
              sticker: false,
              addclass: "pnotify-center",
            });
          }

          var $posBody = $("#posTable > tbody");
          data = data.data;
          for (var i = 0; i < data.length; i++) {
            if ($posBody.find("> tr.multi-customer-group").length === 1 && $posBody.find('> tr[data-customerid=""]').length) {
              var $tr = $posBody.find('> tr[data-item-id="' + data[i]["itemid"] + '"]');
              if ($tr.length) {
                var discountSalePrice = Number($tr.find('input[name="salePrice[]"]').val());
                $tr.find('input[name="isDiscount[]"]').val("0");
                $tr.find('input[name="unitDiscount[]"]').val("");
                $tr.find('input[name="discountAmount[]"]').val("");
                $tr.find('input[name="discountPercent[]"]').val("");
                $tr.find('input[name="totalDiscount[]"]').val("");

                $tr.find('input[name="unitBonusAmount[]"]').val("");
                $tr.find('input[name="unitBonusPercent[]"]').val("");
                $tr.find('input[name="lineTotalBonusAmount[]"]').val("");
                $tr.find('input[data-name="calcBonusPercent"]').val("");

                if (data[i]["discountpercent"]) {
                  discountPercent = Number(data[i]["discountpercent"]);
                  unitDiscount = (Number(data[i]["discountpercent"]) / 100) * discountSalePrice;
                  discountAmount = discountSalePrice - unitDiscount;
                  $tr.find('input[name="isDiscount[]"]').val("1");
                  $tr.find('input[name="unitDiscount[]"]').val(unitDiscount);
                  $tr.find('input[name="discountAmount[]"]').val(discountAmount);
                  $tr.find('input[name="discountPercent[]"]').val(discountPercent);
                  $tr.find('input[name="totalDiscount[]"]').val(pureNumber($tr.find('input[name="quantity[]"]').val()) * discountAmount);
                } else if (data[i]["discountamount"]) {
                  discountPercent = 0;
                  unitDiscount = Number(data[i]["discountamount"]);
                  discountAmount = discountSalePrice - unitDiscount;
                  $tr.find('input[name="isDiscount[]"]').val("1");
                  $tr.find('input[name="unitDiscount[]"]').val(unitDiscount);
                  $tr.find('input[name="discountAmount[]"]').val(discountAmount);
                  $tr.find('input[name="discountPercent[]"]').val(discountPercent);
                  $tr.find('input[name="totalDiscount[]"]').val(pureNumber($tr.find('input[name="quantity[]"]').val()) * discountAmount);
                }

                if (data[i].hasOwnProperty("calcbonuspercent") && Number(data[i].calcbonuspercent) > 0) {
                  var calcamt = (Number(data[i].calcbonuspercent) / 100) * discountSalePrice;
                  $tr.find('input[name="unitBonusAmount[]"]').val(calcamt);
                  $tr.find('input[name="unitBonusPercent[]"]').val(data[i].calcbonuspercent);
                  $tr.find('input[name="lineTotalBonusAmount[]"]').val(calcamt * pureNumber($tr.find('input[name="quantity[]"]').val()));
                  $tr.find('input[data-name="calcBonusPercent"]').val(data[i].calcbonuspercent);
                } else if (data[i].hasOwnProperty("calcbonusamount") && Number(data[i].calcbonusamount) > 0) {
                  $tr.find('input[name="unitBonusAmount[]"]').val(data[i].calcbonusamount);
                  $tr.find('input[name="unitBonusPercent[]"]').val("");
                  $tr.find('input[name="lineTotalBonusAmount[]"]').val(data[i].calcbonusamount * pureNumber($tr.find('input[name="quantity[]"]').val()));
                  $tr.find('input[data-name="calcBonusPercent"]').val("");
                }

                posCalcRow($tr, data[i], "");
              }
            } else {
              var $tr = $posBody.find('> tr[data-item-id-customer-id="' + data[i]["itemid"] + "_" + id + '"]');
              if ($tr.length) {
                var discountSalePrice = Number($tr.find('input[name="salePrice[]"]').val());
                $tr.find('input[name="isDiscount[]"]').val("0");
                $tr.find('input[name="unitDiscount[]"]').val("");
                $tr.find('input[name="discountAmount[]"]').val("");
                $tr.find('input[name="discountPercent[]"]').val("");
                $tr.find('input[name="totalDiscount[]"]').val("");

                $tr.find('input[name="unitBonusAmount[]"]').val("");
                $tr.find('input[name="unitBonusPercent[]"]').val("");
                $tr.find('input[name="lineTotalBonusAmount[]"]').val("");
                $tr.find('input[data-name="calcBonusPercent"]').val("");

                if (data[i]["discountpercent"]) {
                  discountPercent = Number(data[i]["discountpercent"]);
                  unitDiscount = (Number(data[i]["discountpercent"]) / 100) * discountSalePrice;
                  discountAmount = discountSalePrice - unitDiscount;
                  $tr.find('input[name="isDiscount[]"]').val("1");
                  $tr.find('input[name="unitDiscount[]"]').val(unitDiscount);
                  $tr.find('input[name="discountAmount[]"]').val(discountAmount);
                  $tr.find('input[name="discountPercent[]"]').val(discountPercent);
                  $tr.find('input[name="totalDiscount[]"]').val(pureNumber($tr.find('input[name="quantity[]"]').val()) * discountAmount);
                } else if (data[i]["discountamount"]) {
                  discountPercent = 0;
                  unitDiscount = Number(data[i]["discountamount"]);
                  discountAmount = discountSalePrice - unitDiscount;
                  $tr.find('input[name="isDiscount[]"]').val("1");
                  $tr.find('input[name="unitDiscount[]"]').val(unitDiscount);
                  $tr.find('input[name="discountAmount[]"]').val(discountAmount);
                  $tr.find('input[name="discountPercent[]"]').val(discountPercent);
                  $tr.find('input[name="totalDiscount[]"]').val(pureNumber($tr.find('input[name="quantity[]"]').val()) * discountAmount);
                }

                if (data[i].hasOwnProperty("calcbonuspercent") && Number(data[i].calcbonuspercent) > 0) {
                  var calcamt = (Number(data[i].calcbonuspercent) / 100) * discountSalePrice;
                  $tr.find('input[name="unitBonusAmount[]"]').val(calcamt);
                  $tr.find('input[name="unitBonusPercent[]"]').val(data[i].calcbonuspercent);
                  $tr.find('input[name="lineTotalBonusAmount[]"]').val(calcamt * pureNumber($tr.find('input[name="quantity[]"]').val()));
                  $tr.find('input[data-name="calcBonusPercent"]').val(data[i].calcbonuspercent);
                } else if (data[i].hasOwnProperty("calcbonusamount") && Number(data[i].calcbonusamount) > 0) {
                  $tr.find('input[name="unitBonusAmount[]"]').val(data[i].calcbonusamount);
                  $tr.find('input[name="unitBonusPercent[]"]').val("");
                  $tr.find('input[name="lineTotalBonusAmount[]"]').val(data[i].calcbonusamount * pureNumber($tr.find('input[name="quantity[]"]').val()));
                  $tr.find('input[data-name="calcBonusPercent"]').val("");
                }

                posCalcRow($tr, data[i], id);
              }
            }
          }
        }
        Core.unblockUI();
      },
    });
  } else if ($posBody.find("> tr.multi-customer-group").length === 1) {
    var $tbody = $("#posTable > tbody"),
      $rows = $tbody.find("> tr[data-item-id]");
    $rows.each(function () {
      var $tr = $(this);

      $tr.find('input[name="isDiscount[]"]').val("0");
      $tr.find('input[name="unitDiscount[]"]').val("");
      $tr.find('input[name="discountAmount[]"]').val("");
      $tr.find('input[name="discountPercent[]"]').val("");
      $tr.find('input[name="totalDiscount[]"]').val("");

      $tr.find('input[name="unitBonusAmount[]"]').val("");
      $tr.find('input[name="unitBonusPercent[]"]').val("");
      $tr.find('input[name="lineTotalBonusAmount[]"]').val("");
      $tr.find('input[data-name="calcBonusPercent"]').val("");
      posCalcRow($tr);
    });
  }
}

function kitchenIsPrint(callback, responseData) {
  $.ajax({
    type: "post",
    url: "mdpos/kitchenIsPrint",
    data: responseData,
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      Core.unblockUI();
      callback();
    },
  });
}

function dataviewHandlerDblClickRow1622644015973310(row) {
  Core.blockUI({
    animate: true,
  });

  var getCustomerItems = $.ajax({
    type: "post",
    url: "api/callDataview",
    data: {
      dataviewId: "1621573481500557",
      criteriaData: {
        customerId: [{ operator: "=", operand: row["customerid"] }],
        filterGuestNames: [{ operator: "=", operand: row["customername"] }],
      },
    },
    dataType: "json",
    async: false,
    success: function (data) {
      return data.result;
    },
  });
  getCustomerItemsArray = getCustomerItems.responseJSON.result;
  customerId = row["customerid"];
  Core.unblockUI();

  var prms = {
    status: "success",
    data: {
      id: $("#basketInvoiceId").val(),
      locationid: $("#posLocationId").val(),
      salespersonid: $("#posRestWaiterId").val(),
      customerid: customerId,
      pos_item_list_get: getCustomerItemsArray,
    },
  };

  isMultiCustomerPrintBill = true;

  var basketParams = [{ id: "", event: "multiCustomer", data: prms }];
  posFillItemsByBasket("", "", "", "mergeCustomer", basketParams);
  $("#dialog-multiuser-dataview").dialog("close");
}

function dataviewHandlerDblClickRow16838643965049(row) {
  Core.blockUI({
    animate: true,
  });

  var getCustomerItems = $.ajax({
    type: "post",
    url: "api/callDataview",
    data: {
      dataviewId: "16838637815969",
      criteriaData: {
        customerId: [{ operator: "=", operand: row["customerid"] }],
        filterGuestNames: [{ operator: "=", operand: row["customername"] }],
      },
    },
    dataType: "json",
    async: false,
    success: function (data) {
      return data.result;
    },
  });
  getCustomerItemsArray = getCustomerItems.responseJSON.result;
  customerId = row["customerid"];
  Core.unblockUI();

  var prms = {
    status: "success",
    data: {
      id: $("#basketInvoiceId").val(),
      locationid: $("#posLocationId").val(),
      salespersonid: $("#posRestWaiterId").val(),
      customerid: customerId,
      pos_item_list_get: getCustomerItemsArray,
    },
  };

  isMultiCustomerPrintBill = true;

  var basketParams = [{ id: "", event: "multiCustomer", data: prms }];
  posFillItemsByBasket("", "", "", "mergeCustomer", basketParams);
  $("#dialog-multiuser-dataview").dialog("close");
}

function multiCustomerListPos(locationId, customerId) {
  var $dialogName = "dialog-multiuser-dataview";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdobject/dataValueViewer",
    data: {
      metaDataId: "1622644015973310",
      viewType: "detail",
      dataGridDefaultHeight: 400,
      uriParams: '{"locationId": "' + (locationId ? locationId : "") + '"}',
      // uriParams: '{"locationId": "'+locationId+'","filterCustomerId": "'+customerId+'"}',
      ignorePermission: 1,
    },
    beforeSend: function () {
      Core.blockUI({
        animate: true,
      });
    },
    success: function (dataHtml) {
      $dialog
        .empty()
        .append(
          '<div class="row" id="object-value-list-1622644015973310">' +
          dataHtml +
          "</div>"
        );
      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: "Харилцагчийн үйлчилгээнүүд",
          position: { my: "top", at: "top+50" },
          width: 1000,
          height: "auto",
          modal: true,
          open: function () {
            $dialog.find(".top-sidebar-content:eq(0)").attr("style", "padding-left: 15px !important");
            $dialog.find('a[onclick*="toQuickMenu"]').remove();
          },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: "Сонгох",
              class: "btn blue-madison btn-sm",
              click: function () {
                var rows = getDataViewSelectedRows("1622644015973310"),
                  rows2 = [];

                if ($("div[data-path-uniqid]").length === 1) {
                  rows2 = getDataViewSelectedRows(
                    $("div[data-path-uniqid]").attr("data-path-uniqid")
                  );
                }
                if ($("div[data-path-uniqid]").length > 1) {
                  rows2 = getDataViewSelectedRows(
                    $("div[data-path-uniqid]").eq(0).attr("data-path-uniqid")
                  );
                  var rows22 = getDataViewSelectedRows(
                    $("div[data-path-uniqid]").eq(1).attr("data-path-uniqid")
                  );
                  rows2 = rows2.concat(rows22);
                }

                if (rows.length || rows2.length) {
                  if (rows.length) {
                    for (var cus = 0; cus < rows.length; cus++) {
                      if (rows[0]["customerid"] != rows[cus]["customerid"]) {
                        alert("Нэг харилцагчийн бараа сонгоно уу!");
                        return;
                      }
                    }
                  }

                  if (rows2.length) {
                    for (var cus = 0; cus < rows2.length; cus++) {
                      if (rows2[0]["customerid"] != rows2[cus]["customerid"]) {
                        alert("Нэг харилцагчийн бараа сонгоно уу!");
                        return;
                      }
                    }
                  }

                  var getCustomerItemsArray = "",
                    customerId = "";

                  if (rows2.length) {
                    getCustomerItemsArray = rows2;
                    customerId = rows2[0]["customerid"];
                  } else {
                    Core.blockUI({
                      message: "Loading...",
                      boxed: true,
                    });
                    var getCustomerItems = $.ajax({
                      type: "post",
                      url: "api/callDataview",
                      data: {
                        dataviewId: "1621573481500557",
                        criteriaData: {
                          customerId: [
                            { operator: "=", operand: rows[0]["customerid"] },
                          ],
                          filterGuestNames: [
                            { operator: "=", operand: rows[0]["customername"] },
                          ],
                        },
                      },
                      dataType: "json",
                      async: false,
                      success: function (data) {
                        return data.result;
                      },
                    });
                    getCustomerItemsArray =
                      getCustomerItems.responseJSON.result;
                    customerId = rows[0]["customerid"];
                    Core.unblockUI();
                  }

                  var prms = {
                    status: "success",
                    data: {
                      id: $("#basketInvoiceId").val(),
                      locationid: $("#posLocationId").val(),
                      salespersonid: $("#posRestWaiterId").val(),
                      customerid: customerId,
                      pos_item_list_get: getCustomerItemsArray,
                    },
                  };

                  isMultiCustomerPrintBill = true;

                  var basketParams = [
                    { id: "", event: "multiCustomer", data: prms },
                  ];
                  posFillItemsByBasket(
                    "",
                    "",
                    "",
                    "mergeCustomer",
                    basketParams
                  );
                  $dialog.dialog("close");
                } else {
                  alert("Жагсаалтаас сонгоно уу!");
                }
              },
            },
          ],
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
          /*maximize : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }, 
              restore : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }*/
        });

      $dialog.dialog("open");
      //$dialog.dialogExtend('maximize');

      $dialog.bind("dialogextendminimize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .addClass("display-none");
      });
      $dialog.bind("dialogextendmaximize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
      $dialog.bind("dialogextendrestore", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    //Core.initDVAjax($dialog);
  });
}

function multiCustomerList2Pos(locationId, customerId) {
  var $dialogName = "dialog-multiuser-dataview";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '"></div>').appendTo("body");
  }
  var $dialog = $("#" + $dialogName);

  $.ajax({
    type: "post",
    url: "mdobject/dataValueViewer",
    data: {
      metaDataId: "16838643965049",
      viewType: "detail",
      dataGridDefaultHeight: 400,
      uriParams: '{"locationId": "' + (locationId ? locationId : "") + '"}',
      // uriParams: '{"locationId": "'+locationId+'","filterCustomerId": "'+customerId+'"}',
      ignorePermission: 1,
    },
    beforeSend: function () {
      Core.blockUI({
        animate: true,
      });
    },
    success: function (dataHtml) {
      $dialog
        .empty()
        .append(
          '<div class="row" id="object-value-list-16838643965049">' +
          dataHtml +
          "</div>"
        );
      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: "Харилцагчийн үйлчилгээнүүд",
          position: { my: "top", at: "top+50" },
          width: 1000,
          height: "auto",
          modal: true,
          open: function () {
            $dialog.find(".top-sidebar-content:eq(0)").attr("style", "padding-left: 15px !important");
            $dialog.find('a[onclick*="toQuickMenu"]').remove();
          },
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: [
            {
              text: "Сонгох",
              class: "btn blue-madison btn-sm",
              click: function () {
                var rows = getDataViewSelectedRows("16838643965049"),
                  rows2 = [];

                if ($("div[data-path-uniqid]").length === 1) {
                  rows2 = getDataViewSelectedRows(
                    $("div[data-path-uniqid]").attr("data-path-uniqid")
                  );
                }
                if ($("div[data-path-uniqid]").length > 1) {
                  rows2 = getDataViewSelectedRows(
                    $("div[data-path-uniqid]").eq(0).attr("data-path-uniqid")
                  );
                  var rows22 = getDataViewSelectedRows(
                    $("div[data-path-uniqid]").eq(1).attr("data-path-uniqid")
                  );
                  rows2 = rows2.concat(rows22);
                }

                if (rows.length || rows2.length) {
                  if (rows.length) {
                    for (var cus = 0; cus < rows.length; cus++) {
                      if (rows[0]["customerid"] != rows[cus]["customerid"]) {
                        alert("Нэг харилцагчийн бараа сонгоно уу!");
                        return;
                      }
                    }
                  }

                  if (rows2.length) {
                    for (var cus = 0; cus < rows2.length; cus++) {
                      if (rows2[0]["customerid"] != rows2[cus]["customerid"]) {
                        alert("Нэг харилцагчийн бараа сонгоно уу!");
                        return;
                      }
                    }
                  }

                  var getCustomerItemsArray = "",
                    customerId = "";

                  if (rows2.length) {
                    getCustomerItemsArray = rows2;
                    customerId = rows2[0]["customerid"];
                  } else {
                    Core.blockUI({
                      message: "Loading...",
                      boxed: true,
                    });
                    var getCustomerItems = $.ajax({
                      type: "post",
                      url: "api/callDataview",
                      data: {
                        dataviewId: "16838637815969",
                        criteriaData: {
                          customerId: [
                            { operator: "=", operand: rows[0]["customerid"] },
                          ],
                          filterGuestNames: [
                            { operator: "=", operand: rows[0]["customername"] },
                          ],
                        },
                      },
                      dataType: "json",
                      async: false,
                      success: function (data) {
                        return data.result;
                      },
                    });
                    getCustomerItemsArray =
                      getCustomerItems.responseJSON.result;
                    customerId = rows[0]["customerid"];
                    Core.unblockUI();
                  }

                  var prms = {
                    status: "success",
                    data: {
                      id: $("#basketInvoiceId").val(),
                      locationid: $("#posLocationId").val(),
                      salespersonid: $("#posRestWaiterId").val(),
                      customerid: customerId,
                      pos_item_list_get: getCustomerItemsArray,
                    },
                  };

                  isMultiCustomerPrintBill = true;

                  var basketParams = [
                    { id: "", event: "multiCustomer", data: prms },
                  ];
                  posFillItemsByBasket(
                    "",
                    "",
                    "",
                    "mergeCustomer",
                    basketParams
                  );
                  $dialog.dialog("close");
                } else {
                  alert("Жагсаалтаас сонгоно уу!");
                }
              },
            },
          ],
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
          /*maximize : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }, 
              restore : function () {
                $('#objectdatagrid-1522115383994585').datagrid('resize');
              }*/
        });

      $dialog.dialog("open");
      //$dialog.dialogExtend('maximize');

      $dialog.bind("dialogextendminimize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .addClass("display-none");
      });
      $dialog.bind("dialogextendmaximize", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });
      $dialog.bind("dialogextendrestore", function () {
        $dialog
          .closest(".ui-dialog")
          .nextAll(".ui-widget-overlay:first")
          .removeClass("display-none");
      });

      Core.unblockUI();
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    //Core.initDVAjax($dialog);
  });
}
function selectedMultiCustomerListPos(
  metaDataCode,
  processMetaDataId,
  chooseType,
  elem,
  rows,
  paramRealPath,
  lookupMetaDataId,
  isMetaGroup
) {
  var row = rows[0];
  isDisableRowDiscountInput = false;

  if (posTypeCode == "3" || posTypeCode == "4") {
    row["typeid"] = 1;
  }

  $.ajax({
    type: "post",
    url: "mdpos/fillItemsByInvoiceId",
    data: { row: row },
    dataType: "json",
    beforeSend: function () {
      bpBlockMessageStart("Loading...");
    },
    success: function (data) {
      PNotify.removeAll();

      if (data.status == "success") {
        posDisplayReset("");

        if (posTypeCode == "3" || posTypeCode == "4") {
          $("#basketInvoiceId").val(row.id);

          if (row.hasOwnProperty("cardnumber")) {
            $("#basketCustomerId").val(row.customerid);
            $("#basketCustomerCode").val(row.customercode);
            $("#basketCustomerName").val(row.customername);
            $("#basketCardNumber").val(row.cardnumber);
            $("#basketCreatedUserId").val(row.createduserid);
          }

          if (
            data.orderData &&
            data.orderData.data.hasOwnProperty("locationid")
          ) {
            $("#posLocationId").val(data.orderData.data.locationid);
            if ($("#posRestWaiterId").val() == '') {
              $("#posRestWaiterId").val(data.orderData.data.salespersonid);
            }
          }
        } else {
          new PNotify({
            title: plang.get("POS_0011"),
            text: data.message,
            type: "warning",
            sticker: false,
            addclass: "pnotify-center",
          });

          $(".pos-invoice-number-text").val(row.booknumber);
          $("#invoiceId").val(row.id);
          $("#invoiceBasketTypeId").val(row.invoicetypeid);
          $("#invoiceJsonStr").val(JSON.stringify(row));
        }
        if (posTypeCode !== "3") {
          $(".pos-invoice-number").show();
        }

        var $tbody = $("#posTable").find("> tbody");

        $tbody
          .html(data.html)
          .promise()
          .done(function () {
            posConfigVisibler($tbody);
            Core.initLongInput($tbody);
            Core.initDecimalPlacesInput($tbody);
            Core.initUniform($tbody);

            if (
              (posTypeCode == "3" || posTypeCode == "4") &&
              data.orderData &&
              data.orderData.data.customerid
            ) {
              $.ajax({
                type: "post",
                url: "api/callDataview",
                data: {
                  dataviewId: "1536742182010",
                  criteriaData: {
                    id: [
                      {
                        operator: "=",
                        operand: data.orderData.data.customerid,
                      },
                    ],
                  },
                },
                dataType: "json",
                success: function (data) {
                  if (data.status === "success" && data.result[0]) {
                    $('input[name="empCustomerId"]').val(data.result[0]["id"]);
                    $('input[name="empCustomerId_displayField"]').val(
                      data.result[0]["customercode"]
                    );
                    $('input[name="empCustomerId_nameField"]').val(
                      data.result[0]["customername"]
                    );
                    $('input[name="empCustomerId"]').attr(
                      "data-row-data",
                      JSON.stringify(data.result[0])
                    );
                  } else {
                    $('input[name="empCustomerId"]').val("");
                    $('input[name="empCustomerId_displayField"]').val("");
                    $('input[name="empCustomerId_nameField"]').val("");
                    $('input[name="empCustomerId"]').attr("data-row-data", "");
                  }
                },
              });
            }

            if (row.hasOwnProperty("typeid") && row.typeid == "3") {
              $tbody.find("button.btn").prop("disabled", true);
              $tbody.find('input[type="text"]').prop("readonly", true);

              $("#scanItemCode").combogrid("disable");

              isDisableRowDiscountInput = true;
            }

            if (data.hasOwnProperty("description")) {
              $(".pos-footer-msg").text(data.description);
            }

            posGiftRowsSetDelivery($tbody);

            var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");
            $firstRow.click();

            posFixedHeaderTable();
            posCalcTotal();
          });
      } else {
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false,
          addclass: "pnotify-center",
        });

        $(".pos-invoice-number").hide();
        $(
          ".pos-invoice-number-text, #invoiceId, #invoiceJsonStr, #invoiceBasketTypeId"
        ).val("");
      }

      bpBlockMessageStop();
    },
    error: function (request, status, error) {
      alert(request.responseText);
      bpBlockMessageStop();
    },
  });
}

function multiCustomer(callback) {
  if (
    posTypeCode == "3" &&
    returnBillType == "" &&
    $("#posRestSalesOrderId").val() &&
    !isMultiCustomerPrintBill
  ) {
    if (restPosEventType["event"] === "splitCalculate") {
      callback(false);
      return;
    }

    var response = $.ajax({
      type: "post",
      url: "api/callProcess",
      data: {
        processCode: "SOD_CUSTOMER_COUNT_004",
        paramData: { salesOrderId: $("#posRestSalesOrderId").val() },
      },
      dataType: "json",
      async: false,
    });
    var responseParam = response.responseJSON;
    if (responseParam.status == "success" && responseParam.result.count > 1) {
      multiCustomerListPos($("#posLocationId").val());
      callback(true);
    } else if (
      responseParam.status == "success" &&
      responseParam.result.count == 1 &&
      !isMultiCustomerPrintBill
    ) {
      var isMultiCustomer = false;

      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });

      var guestName = responseParam.result.guestnames, guestNameConcat = "";
      if (guestName) {
        guestName = guestName.split(",");
        for (var g = 0; g < guestName.length; g++) {
          guestNameConcat += "'" + guestName[g] + "',";
        }
        guestNameConcat = rtrim(guestNameConcat, ',');
      }
      $.ajax({
        type: "post",
        url: "api/callDataview",
        data: {
          dataviewId: "1621573481500557",
          criteriaData: {
            locationId: [{ operator: "=", operand: $("#posLocationId").val() }],
            customerId: [
              { operator: "=", operand: responseParam.result.customerids },
            ],
            filterGuestNames: [
              { operator: "=", operand: guestNameConcat },
            ],
          },
        },
        dataType: "json",
        async: false,
        success: function (data) {
          Core.unblockUI();
          if (data.status === "success" && data.result[0]) {
            for (var customer = 0; customer < data.result.length; customer++) {
              if (!data.result[customer]["mainlocationid"]) {
                isMultiCustomer = true;
              }
            }

            if (isMultiCustomer) {
              var prms = {
                status: "success",
                data: {
                  id: $("#basketInvoiceId").val(),
                  locationid: $("#posLocationId").val(),
                  salespersonid: $("#posRestWaiterId").val(),
                  customerid: data.result[0]["customerid"],
                  pos_item_list_get: data.result,
                },
              };

              isMultiCustomerPrintBill = true;

              var basketParams = [
                { id: "", event: "multiCustomer", data: prms },
              ];
              posFillItemsByBasket("", "", "", "mergeCustomer", basketParams);
              callback(isMultiCustomer);

              setTimeout(function () {
                new PNotify({
                  title: "Info",
                  text: "ГОЛЬФООС ЭНЭ ХАРИЛЦАГЧИЙН ҮЙЛЧИЛГЭЭ НЭМЭГДЛЭЭ.",
                  type: "info",
                  addclass: "pnotify-center",
                  sticker: false,
                });
              }, 1000);
            } else {
              callback(false);
            }
          } else {
            callback(false);
          }
        },
      });
    } else {
      callback(false);
    }
    /*} else if (posTypeCode == '4' && returnBillType == '' && $('input[name="empCustomerId"]').val() && !isMultiCustomerPrintBill) {
      var getCustomerItems = $.ajax({
        type: 'post',
        url: 'api/callDataview',
        data: {dataviewId: '1622644015973310', criteriaData: {filterCustomerId: [{operator: '=', operand: $('input[name="empCustomerId"]').val()}]}},
        dataType: 'json',
        async: false,
        success: function(data) {                            
          return data.result;
        }
      });  
      getCustomerItemsArray = getCustomerItems.responseJSON.result;
        
      if (getCustomerItemsArray.length) {
        multiCustomerListPos('', $('input[name="empCustomerId"]').val());
        callback(true);        
      } else {
        callback(false);        
      }*/
  } else {
    callback(false);
  }
}

function checkzbpassword($this) {
  var pinCodeSuccess = false;
  var $dialogNameWaterPin = "dialog-employee-pincode";
  if (!$("#" + $dialogNameWaterPin).length) {
    $('<div id="' + $dialogNameWaterPin + '"></div>').appendTo("body");
  }
  var $dialogWaiterPin = $("#" + $dialogNameWaterPin);

  $dialogWaiterPin
    .empty()
    .append(
      '<form method="post" autocomplete="off" id="employeePassForm"><input type="password" name="employeePinCode" class="form-control" style="font-size:60px; height:40px;" autocomplete="off" required="required"></form>'
    );
  $dialogWaiterPin.dialog({
    cache: false,
    resizable: true,
    bgiframe: true,
    autoOpen: false,
    title: "Нууц үг оруулах",
    width: 400,
    height: "auto",
    modal: true,
    open: function () {
      $dialogWaiterPin.on(
        "keydown",
        'input[name="employeePinCode"]',
        function (e) {
          var keyCode = e.keyCode ? e.keyCode : e.which;
          if (keyCode == 13) {
            $(this)
              .closest(".ui-dialog")
              .find(".ui-dialog-buttonpane button:first")
              .trigger("click");
            return false;
          }
        }
      );
      $dialogWaiterPin.find('input[name="employeePinCode"]').focus();
    },
    close: function () {
      if (!pinCodeSuccess) {
        $this.closest("tr").find("input.pos-quantity-input").focus().select();
      }
      $dialogWaiterPin.empty().dialog("destroy").remove();
    },
    buttons: [
      {
        text: plang.get("insert_btn"),
        class: "btn btn-sm green-meadow",
        click: function () {
          PNotify.removeAll();
          var $form = $("#employeePassForm");

          $form.validate({ errorPlacement: function () { } });

          if ($form.valid()) {
            $.ajax({
              type: "post",
              url: "api/callDataview",
              data: {
                dataviewId: "16237213033721",
                criteriaData: {
                  pincode: [
                    {
                      operator: "=",
                      operand: $form
                        .find('input[name="employeePinCode"]')
                        .val(),
                    },
                  ],
                },
              },
              dataType: "json",
              beforeSend: function () {
                Core.blockUI({
                  message: "Loading...",
                  boxed: true,
                });
              },
              success: function (dataSub) {
                if (dataSub.status == "success" && dataSub.result.length) {
                  pinCodeSuccess = true;
                  $this
                    .closest("tr")
                    .find('input[name="editPriceEmployeeId[]"]')
                    .val(dataSub.result[0]["employeeid"]);
                  $this.attr("data-isedit-permission", "1");
                  $dialogWaiterPin.dialog("close");
                } else {
                  new PNotify({
                    title: "Анхааруулга",
                    text: "Нууц үг буруу байна!",
                    type: "warning",
                    sticker: false,
                  });
                }
                Core.unblockUI();
              },
            });
          }
        },
      },
      {
        text: plang.get("close_btn"),
        class: "btn btn-sm blue-madison",
        click: function () {
          $dialogWaiterPin.dialog("close");
        },
      },
    ],
  });
  $dialogWaiterPin.dialog("open");
}

function posOrderBpSave(elem) {
  var $dialogName = "dialog-pos-new-orderbp";
  if (!$("#" + $dialogName).length) {
    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo(
      "body"
    );
  }
  var $dialog = $("#" + $dialogName),
    jsonParam = {};

  // if ($("#newServiceCustomerJson").val() == "") {
  //   jsonParam = JSON.stringify({
  //     customerName: $("#invInfoCustomerName").val(),
  //     phoneNumber: $("#invInfoPhoneNumber").val(),
  //     cityId: $("#cityId").val(),
  //     districtId: $("#districtId").val(),
  //     streetId: $("#streetId").val(),
  //     positionName: $("#invInfoCustomerRegNumber").val(),
  //     address: $("#detailAddress").val(),
  //   });
  // } else {
  //   jsonParam = $("#newServiceCustomerJson").val();
  // }
  var itemDtl = [];
  jsonParam = {
    subTotal: $("td.pos-amount-total").autoNumeric("get"),
    total: $("td.pos-amount-paid").autoNumeric("get"),
    vat: $("td.pos-amount-vat").autoNumeric("get"),
    discount: $("td.pos-amount-discount").autoNumeric("get"),
    storeId: $("td.pos-amount-discount").autoNumeric("get"),
  };

  var $tbody = $("#posTable > tbody"),
    $rows = $tbody.find("> tr[data-item-id]");

  if ($rows.length) {
    // Check salesperson
    if (
      isConfigSalesPerson &&
      $tbody.find(
        'input.lookup-code-autocomplete[data-field-name="employeeId"]:visible'
      ).length
    ) {
      var $itemRows = $tbody.find("> tr[data-item-id]:visible"),
        salesPersonResult = true;

      $itemRows.each(function () {
        var $itemRow = $(this),
          $employeeId = $itemRow.find('input[data-path="employeeId"]'),
          $employeeCode = $itemRow.find(
            'input.lookup-code-autocomplete[data-field-name="employeeId"]:not([readonly])'
          ),
          $employeeName = $itemRow.find(
            'input.lookup-name-autocomplete[data-field-name="employeeId"]:not([readonly])'
          );

        if (
          $employeeCode.length &&
          ($employeeId.val() == "" ||
            $employeeCode.val() == "" ||
            $employeeName.val() == "")
        ) {
          salesPersonResult = false;
          $employeeCode.addClass("error");
          $employeeName.addClass("error");
        } else {
          $employeeCode.removeClass("error");
          $employeeName.removeClass("error");
        }
      });

      if (salesPersonResult == false) {
        new PNotify({
          title: "Warning",
          text: plang.get("POS_0023"),
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
        return;
      }
    }

    $rows.each(function () {
      var $tr = $(this);
      itemDtl.push({
        itemId:
          $tr.find('input[name="isJob[]"]').val() != 1 &&
            $tr.find('input[name="isJob[]"]').val() != 2
            ? $tr.find('input[name="itemId[]"]').val()
            : "",
        jobId:
          $tr.find('input[name="isJob[]"]').val() == 1
            ? $tr.find('input[name="itemId[]"]').val()
            : "",
        couponKeyId:
          $tr.find('input[name="isJob[]"]').val() == 2
            ? $tr.find('input[name="itemId[]"]').val()
            : "",
        itemCode: $tr.find('input[name="itemCode[]"]').val(),
        itemName: $tr.find('input[name="itemName[]"]').val(),
        orderQty: $tr.find('input[name="quantity[]"]').val(),
        unitPrice: $tr.find('input[name="salePrice[]"]').val(),
        lineTotalPrice: $tr.find('input[name="totalPrice[]"]').val(),
        unitDiscount: $tr.find('input[name="unitDiscount[]"]').val(),
        lineTotalDiscount:
          $tr.find('input[name="unitDiscount[]"]').val() *
          $tr.find('input[name="quantity[]"]').val(),
        unitAmount: $tr.find('input[name="discountAmount[]"]').val()
          ? $tr.find('input[name="discountAmount[]"]').val()
          : $tr.find('input[name="salePrice[]"]').val(),
        lineTotalAmount: $tr.find('input[name="totalDiscount[]"]').val()
          ? $tr.find('input[name="totalDiscount[]"]').val()
          : $tr.find('input[name="totalPrice[]"]').val(),
        unitVat:
          $tr.find('input[name="isVat[]"]').val() == "1"
            ? $tr.find('input[name="salePrice[]"]').val() -
            $tr.find('input[name="noVatPrice[]"]').val()
            : "",
        lineTotalVat:
          $tr.find('input[name="isVat[]"]').val() == "1"
            ? $tr.find('input[name="lineTotalVat[]"]').val()
            : "",
        isVat: $tr.find('input[name="isVat[]"]').val() == "1" ? "1" : "0",
        warehouseId: $tr.find('input[name="storeWarehouseId[]"]').val(),
        isDelivery: $tr.find('input[name="isDelivery[]"]').val(),
        employeeId: $tr.find('input[name="employeeId[]"]').val(),
        deliveryWarehouseId: $tr
          .find('input[name="deliveryWarehouseId[]"]')
          .val(),
      });
    });
  } else {
    PNotify.removeAll();
    new PNotify({
      title: "Info",
      text: "Бараа сонгоогүй байна.",
      type: "info",
      sticker: false,
    });
    return;
  }
  jsonParam["itemDtl"] = itemDtl;

  jsonParam = JSON.stringify(jsonParam);

  $.ajax({
    type: "post",
    url: "mdwebservice/callMethodByMeta",
    data: {
      metaDataId: "1645520778616304",
      isDialog: true,
      isSystemMeta: false,
      fillJsonParam: jsonParam,
      callerType: "pos",
      openParams: '{"callerType":"pos"}',
    },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Loading...",
        boxed: true,
      });
    },
    success: function (data) {
      $dialog.empty().append(data.Html);

      var processForm = $("#wsForm", "#" + $dialogName);
      var processUniqId = processForm.parent().attr("data-bp-uniq-id");

      var buttons = [
        {
          text: data.run_btn,
          class: "btn green-meadow btn-sm bp-btn-save",
          click: function (e) {
            if (window["processBeforeSave_" + processUniqId]($(e.target))) {
              processForm.validate({
                ignore: "",
                highlight: function (element) {
                  $(element).addClass("error");
                  $(element).parent().addClass("error");
                  if (
                    processForm.find("div.tab-pane:hidden:has(.error)").length
                  ) {
                    processForm
                      .find("div.tab-pane:hidden:has(.error)")
                      .each(function (index, tab) {
                        var tabId = $(tab).attr("id");
                        processForm
                          .find('a[href="#' + tabId + '"]')
                          .tab("show");
                      });
                  }
                },
                unhighlight: function (element) {
                  $(element).removeClass("error");
                  $(element).parent().removeClass("error");
                },
                errorPlacement: function () { },
              });

              var isValidPattern = initBusinessProcessMaskEvent(processForm);

              if (processForm.valid() && isValidPattern.length === 0) {
                processForm.ajaxSubmit({
                  type: "post",
                  url: "mdwebservice/runProcess",
                  dataType: "json",
                  beforeSend: function () {
                    Core.blockUI({
                      boxed: true,
                      message: plang.get("POS_0040"),
                    });
                  },
                  success: function (responseData) {
                    if (responseData.status === "success") {
                      posDisplayReset("", false);
                      $dialog.dialog("close");
                    }
                    Core.unblockUI();
                  },
                  error: function () {
                    alert("Error");
                  },
                });
              }
            }
          },
        },
        {
          text: data.close_btn,
          class: "btn blue-madison btn-sm",
          click: function () {
            $dialog.dialog("close");
          },
        },
      ];

      var dialogWidth = data.dialogWidth,
        dialogHeight = data.dialogHeight;

      if (data.isDialogSize === "auto") {
        dialogWidth = 1200;
        dialogHeight = "auto";
      }

      $dialog
        .dialog({
          cache: false,
          resizable: true,
          bgiframe: true,
          autoOpen: false,
          title: data.Title,
          width: dialogWidth,
          height: dialogHeight,
          modal: true,
          closeOnEscape:
            typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
          close: function () {
            $dialog.empty().dialog("destroy").remove();
          },
          buttons: buttons,
        })
        .dialogExtend({
          closable: true,
          maximizable: true,
          minimizable: true,
          collapsable: true,
          dblclick: "maximize",
          minimizeLocation: "left",
          icons: {
            close: "ui-icon-circle-close",
            maximize: "ui-icon-extlink",
            minimize: "ui-icon-minus",
            collapse: "ui-icon-triangle-1-s",
            restore: "ui-icon-newwin",
          },
        });
      if (data.dialogSize === "fullscreen") {
        $dialog.dialogExtend("maximize");
      }
      $dialog.dialog("open");
    },
    error: function () {
      alert("Error");
    },
  }).done(function () {
    Core.initBPAjax($dialog);
    Core.unblockUI();
  });
}

function createBillResultDataFromInvoice(
  metaDataCode,
  processMetaDataId,
  chooseType,
  elem,
  rows,
  paramRealPath,
  lookupMetaDataId,
  isMetaGroup
) {
  var row = rows[0];

  var invoiceId = row.salesinvoiceid;

  if (invoiceId) {
    PNotify.removeAll();

    $("#posLocationId").val("");
    $("#posRestWaiterId").val("");
    $("#posRestWaiter").val("");
    $("#posRestSalesOrderId").val("");
    $(".rest-table-btn").find("div").html("");
    posDisplayReset(plang.get("POS_0048"), false);
    $("#dialog-talon-dataview").dialog("close");

    $.ajax({
      type: "post",
      url: "mdpos/getInvoiceRender",
      data: { invoiceId: invoiceId, type: "typeSalesPayment" },
      dataType: "json",
      beforeSend: function () {
        setTimeout(function () {
          Core.blockUI({
            message: "Loading...",
            boxed: true,
          });
        }, 300);
      },
      success: function (data) {
        if (data.status == "success") {
          $(".posRemoveItemBtnHeader").hide();

          $("#returnTypeInvoice").val("typeSalesPayment");
          $("#returnInvoiceId").val(invoiceId);
          $("#pos-bill-number").text("Сугалаа олгох");

          var $tbody = $("#posTable").find("> tbody");

          $tbody.append(data.html);

          posConfigVisibler($tbody);
          Core.initLongInput($tbody);
          Core.initDecimalPlacesInput($tbody);
          Core.initUniform($tbody);

          $tbody.find("button.btn").prop("disabled", true);
          $tbody.find('input[type="text"]').prop("readonly", true);
          $tbody.find(".basket-inputqty-button").each(function () {
            $(this).find("span:eq(0)").hide();
            $(this).find("span:eq(2)").hide();
          });

          var $checkboxs = $tbody.find('input[type="checkbox"]');
          if ($checkboxs.length) {
            $checkboxs.attr({
              "data-isdisabled": "true",
              style: "cursor: not-allowed",
              tabindex: "-1",
            });
            $checkboxs.closest(".checker").addClass("disabled");
          }

          var $dialogName = "dialog-pos-payment";
          $('<div id="' + $dialogName + '" style="display: none"></div>').appendTo("body");
          var $dialog = $("#" + $dialogName);

          $dialog.empty().append(data.payment);
          Core.initClean($dialog);

          $("#scanItemCode, #posServiceCode").combogrid("disable");

          var $firstRow = $tbody.find("tr[data-item-id]:eq(0)");

          $firstRow.click();

          posFixedHeaderTable();
          posCalcTotal();

          // $("#posPaidAmount").autoNumeric("set", 0);
        } else {
          $("#returnInvoiceId, #returnTypeInvoice, #returnInvoiceBillId, #returnInvoiceNumber, #returnInvoiceRefNumber, #returnInvoiceBillType, #returnInvoiceBillDate, #returnInvoiceIsGL, #returnInvoiceBillStateRegNumber, #returnInvoiceBillStorecode, #returnInvoiceBillCashRegisterCode").val("");

          $("#scanItemCode, #posServiceCode").combogrid("enable");

          new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
            addclass: "pnotify-center",
          });
        }

        Core.unblockUI();
      },
    });
  }

  return;
}

function appendItemPackage(packageItems, item) {
  var $tbody = $("#posTable").find("> tbody");
  $tbody.append(
    '<tr style="height: 20px;" class="item-package"><td style="font-size: 12px;background-color: #ffcc0099;"></td><td colspan="5" style="font-size: 12px;background-color: #ffcc0099;">' + item.itemname + '</td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td><td class="d-none"></td></tr>'
  );
  var itemPostData;
  for (var i = 0; i < packageItems.length; i++) {
    itemPostData = {
      code: packageItems[i]['itemcode'],
      packageIdItem: item.itemid
    };
    appendItem(itemPostData, "", function () {
    });
  }
}

function appendQuickItem(elem, itemCode, itemId) {
  var itemPostData = {
    code: itemCode
  };
  appendItem(
    itemPostData,
    "",
    function () { }
  );
}

function posSearchQpay(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-qpay-row");

  var monAmount = $row.find(".bigdecimalInit").autoNumeric("get");
  if (!monAmount) {
    PNotify.removeAll();
    new PNotify({
      title: 'Warning',
      text: 'Qpay төлөх дүнгээ оруулна уу!',
      type: 'warning',
      sticker: false,
      addclass: "pnotify-center",
    });
    return;
  }

  $.ajax({
    type: "post",
    url: "mdpos/qpayGenerateQrCode",
    data: { amount: monAmount },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Generating...",
        boxed: true,
      });
    },
    success: function (dataQrCode) {
      Core.unblockUI();

      if (dataQrCode.status == "success") {
        candyQrUuid = dataQrCode.bill_no;
        qpayQRCodeShow($row, monAmount, dataQrCode);
        $('input[name="qpay_bill_no"]').val(dataQrCode.bill_no);
        $('input[name="qpay_traceNo"]').val(dataQrCode.traceNo);

        posQpayQrCheckInterval = setInterval(function () {
          qpayCheckQrCode(dataQrCode.traceNo, monAmount, $row);
        }, 3000);
      } else {
        new PNotify({
          title: dataQrCode.status,
          text: dataQrCode.message,
          type: dataQrCode.status,
          sticker: false,
          addclass: "pnotify-center"
        });
      }
    },
  });
}

function posSearchTokipay(elem) {
  var $this = $(elem),
    $row = $this.closest(".pos-tokipay-row");

  var monAmount = $row.find(".bigdecimalInit").autoNumeric("get");
  if (!monAmount) {
    PNotify.removeAll();
    new PNotify({
      title: 'Warning',
      text: 'Qpay төлөх дүнгээ оруулна уу!',
      type: 'warning',
      sticker: false,
      addclass: "pnotify-center",
    });
    return;
  }

  $.ajax({
    type: "post",
    url: "mdpos/tokipayGenerateQrCode",
    data: { amount: monAmount },
    dataType: "json",
    beforeSend: function () {
      Core.blockUI({
        message: "Generating...",
        boxed: true,
      });
    },
    success: function (dataQrCode) {
      Core.unblockUI();

      if (dataQrCode.status == "success") {
        candyQrUuid = dataQrCode.bill_no;
        tokipayQRCodeShow($row, monAmount, dataQrCode);
        $('input[name="tokipay_bill_no"]').val(dataQrCode.bill_no);
        $('input[name="tokipay_traceNo"]').val(dataQrCode.traceNo);

        posTokipayQrCheckInterval = setInterval(function () {
          tokipayCheckQrCode(dataQrCode.traceNo, monAmount, $row);
        }, 3000);
      } else {
        new PNotify({
          title: dataQrCode.status,
          text: dataQrCode.message,
          type: dataQrCode.status,
          sticker: false,
          addclass: "pnotify-center"
        });
      }
    },
  });
}

function qpayQRCodeShow(row, amount, data) {
  var $dialogName = "dialog-pos-qpayqr";
  $('<div id="' + $dialogName + '"></div>').appendTo("body");
  var $dialog = $("#" + $dialogName);

  $dialog.empty().append(data.html);

  $dialog.dialog({
    cache: false,
    resizable: false,
    bgiframe: true,
    autoOpen: false,
    title: data.title,
    width: 420,
    minWidth: 420,
    height: "auto",
    modal: true,
    dialogClass: "pos-payment-dialog",
    closeOnEscape: isCloseOnEscape,
    close: function () {
      clearInterval(posQpayQrCheckInterval);
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: [{
      text: data.close_btn,
      class: "btn btn-sm blue-hoki",
      click: function () {
        $dialog.dialog("close");
        clearInterval(posQpayQrCheckInterval);
      },
    }],
  });
  $dialog.dialog("open");
}

function tokipayQRCodeShow(row, amount, data) {
  var $dialogName = "dialog-pos-tokipayqr";
  $('<div id="' + $dialogName + '"></div>').appendTo("body");
  var $dialog = $("#" + $dialogName);

  $dialog.empty().append(data.html);

  $dialog.dialog({
    cache: false,
    resizable: false,
    bgiframe: true,
    autoOpen: false,
    title: data.title,
    width: 420,
    minWidth: 420,
    height: "auto",
    modal: true,
    dialogClass: "pos-payment-dialog",
    closeOnEscape: isCloseOnEscape,
    close: function () {
      clearInterval(posTokipayQrCheckInterval);
      $dialog.empty().dialog("destroy").remove();
    },
    buttons: [{
      text: data.close_btn,
      class: "btn btn-sm blue-hoki",
      click: function () {
        $dialog.dialog("close");
        clearInterval(posQpayQrCheckInterval);
      },
    }],
  });
  $dialog.dialog("open");
}

function qpayCheckQrCode(uuid, amount, row) {
  $.ajax({
    type: "post",
    url: "mdpos/qpayCheckQrCode",
    data: { uuid: uuid },
    dataType: "json",
    success: function (dataQrCode) {
      Core.unblockUI();
      if (dataQrCode.status == "success") {
        clearInterval(posQpayQrCheckInterval);
        $("#dialog-pos-qpayqr").html('<h3 style="color: green;padding: 20px 20px 20px 0px;">Төлбөр амжилттай төлөгдлөө.</h3>')
      }
    },
  });
}

function tokipayCheckQrCode(uuid, amount, row) {
  $.ajax({
    type: "post",
    url: "mdpos/tokipayCheckQrCode",
    data: { uuid: uuid },
    dataType: "json",
    success: function (dataQrCode) {
      Core.unblockUI();
      if (dataQrCode.status == "success") {
        clearInterval(posTokipayQrCheckInterval);
        $("#dialog-pos-tokipayqr").html('<h3 style="color: green;padding: 20px 20px 20px 0px;">Төлбөр амжилттай төлөгдлөө.</h3>')
      }
    },
  });
}

function socialPayCheckQrCode(uuid, amount) {
  $.ajax({
    type: "post",
    url: "mdpos/socialPayCheckInvoice",
    data: {
      amount: amount,
      id: uuid
    },
    dataType: "json",
    beforeSend: function () { },
    success: function (data) {
      PNotify.removeAll();
      if (data.status == "success") {
        if (data.message.resp_code == "00") {
          $('input[name="posSocialpayApprovalCode"]').val(
            data.message.approval_code
          );
          $('input[name="posSocialpayCardNumber"]').val(
            data.message.card_number
          );
          $('input[name="posSocialpayTerminal"]').val(
            data.message.terminal
          );
          new PNotify({
            title: "Success",
            text: "Төлбөр амжилттай төлөгдлөө",
            type: "success",
            sticker: false,
            addclass: "pnotify-center",
          });
          clearInterval(posSocialPayQrCheckInterval);
        } else {
          new PNotify({
            title: "Warning",
            text: data.message.resp_desc,
            type: "warning",
            sticker: false,
            addclass: "pnotify-center",
          });
        }
        return;
      } else {
        new PNotify({
          title: "Warning",
          text: data.message,
          type: "warning",
          sticker: false,
          addclass: "pnotify-center",
        });
        return;
      }
    },
  });
}

function lookupAutoCompletePosGuestName(elem, type) {
  var _this = elem;
  var _lookupId = _this.attr("data-lookupid");
  var _metaDataId = _this.attr("data-metadataid");
  var _processId = _this.attr("data-processid");
  var bpElem = _this.parent().parent().find("input[type='hidden']");
  var _paramRealPath = bpElem.attr("data-path");
  var _parent = _this.closest("meta-autocomplete-wrap");
  var params = '';
  var isHoverSelect = false;

  _this.autocomplete({
    minLength: 1,
    maxShowItems: 30,
    delay: 500,
    highlightClass: "lookup-ac-highlight",
    appendTo: "body",
    position: { my: "left top", at: "left bottom", collision: "flip flip" },
    autoSelect: false,
    source: function (request, response) {

      if (lookupAutoCompleteRequest != null) {
        lookupAutoCompleteRequest.abort();
        lookupAutoCompleteRequest = null;
      }

      lookupAutoCompleteRequest = $.ajax({
        type: "post",
        url: "api/callDataview",
        dataType: 'json',
        data: {
          dataviewId: "1536742182010",
          criteriaData: {
            filterCustomerKyc: [
              {
                operator: "like",
                operand: '%' + request.term + '%',
              },
            ],
          },
        },
        success: function (data) {
          if (data.status == 'success') {
            response($.map(data.result, function (item) {
              return {
                value: item.id,
                label: item.customercode,
                name: item.customername,
                id: item.id
              };
            }));
          }
        }
      });
    },
    focus: function (event, ui) {
      if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
        isHoverSelect = false;
      } else {
        if (event.keyCode == 38 || event.keyCode == 40) {
          isHoverSelect = true;
        }
      }
      return false;
    },
    open: function () {
      $(this).autocomplete('widget').zIndex(99999999999999);
      return false;
    },
    close: function () {
      $(this).autocomplete("option", "appendTo", "body");
    },
    select: function (event, ui) {
      var origEvent = event;

      if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
        $('input[name="empCustomerId"]').val(ui.item.id);
        $('input[name="empCustomerId_displayField"]').val(ui.item.label);
        $('input[name="empCustomerId_nameField"]').val(ui.item.name);
        $('#guestName').val(ui.item.label + ' - ' + $.trim(ui.item.name));
        event.preventDefault();
      } else {
        $('input[name="empCustomerId"]').val(ui.item.id);
        $('input[name="empCustomerId_displayField"]').val(ui.item.label);
        $('input[name="empCustomerId_nameField"]').val(ui.item.name);
        $('#guestName').val(ui.item.label + ' - ' + $.trim(ui.item.name));
        event.preventDefault();
      }

      while (origEvent.originalEvent !== undefined) {
        origEvent = origEvent.originalEvent;
      }

      if (origEvent.type === 'click') {
        var e = jQuery.Event("keydown");
        e.keyCode = e.which = 13;
        _this.trigger(e);
      }
    }
  }).autocomplete("instance")._renderItem = function (ul, item) {
    ul.addClass('lookup-ac-render');
    var re = new RegExp("(" + this.term + ")", "gi"),
      cls = this.options.highlightClass,
      template = "<span class='" + cls + "'>$1</span>",
      name = item.name.replace(re, template);

    return $('<li>').append('<div class="lookup-ac-render-code">' + item.label + '</div><div class="lookup-ac-render-name">' + name + '</div>').appendTo(ul);
  };
}
