/*
@license

dhtmlxGantt v.6.3.5 Standard

This version of dhtmlxGantt is distributed under GPL 2.0 license and can be legally used in GPL projects.

To use dhtmlxGantt in non-GPL projects (and get Pro version of the product), please obtain Commercial/Enterprise or Ultimate license on our site https://dhtmlx.com/docs/products/dhtmlxGantt/#licensing or contact us at sales@dhtmlx.com

(c) XB Software Ltd.

*/
! function(e, t) {
    "object" == typeof exports && "object" == typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define("locale/locale", [], t) : "object" == typeof exports ? exports["locale/locale"] = t() : e["locale/locale"] = t()
}(window, function() {
    return function(e) {
        var t = {};

        function n(o) {
            if (t[o]) return t[o].exports;
            var r = t[o] = {
                i: o,
                l: !1,
                exports: {}
            };
            return e[o].call(r.exports, r, r.exports, n), r.l = !0, r.exports
        }
        return n.m = e, n.c = t, n.d = function(e, t, o) {
            n.o(e, t) || Object.defineProperty(e, t, {
                enumerable: !0,
                get: o
            })
        }, n.r = function(e) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
                value: "Module"
            }), Object.defineProperty(e, "__esModule", {
                value: !0
            })
        }, n.t = function(e, t) {
            if (1 & t && (e = n(e)), 8 & t) return e;
            if (4 & t && "object" == typeof e && e && e.__esModule) return e;
            var o = Object.create(null);
            if (n.r(o), Object.defineProperty(o, "default", {
                    enumerable: !0,
                    value: e
                }), 2 & t && "string" != typeof e)
                for (var r in e) n.d(o, r, function(t) {
                    return e[t]
                }.bind(null, r));
            return o
        }, n.n = function(e) {
            var t = e && e.__esModule ? function() {
                return e.default
            } : function() {
                return e
            };
            return n.d(t, "a", t), t
        }, n.o = function(e, t) {
            return Object.prototype.hasOwnProperty.call(e, t)
        }, n.p = "/codebase/", n(n.s = 212)
    }({
        212: function(e, t) {
            gantt.locale = {
                date: {
                    month_full: ["Нэг сар", "Хоёр сар", "Гурван сар", "Дөрвөн сар", "Таван сар", "Зургаа сар", "Долоон сар", "Найман сар", "Есөн сар", "Арван сар", "Арван нэг", "Арван хоёр"],
                    month_short: ["Нэг", "Хоёр", "Гур", "Дөр", "Тав", "Зург", "Доло", "Найм", "Ес", "Арав", "Арван Нэг", "Арван хоёр"],
                    day_full: ["Ням", "Даваа", "Мягмар", "Лхагва", "Пүрэв", "Баасан", "Бямба"],
                    day_short: ["Ням", "Дав", "Мяг", "Лха", "Пүр", "Баа", "Бям"]
                },
                labels: {
                    new_task: "Шинэ ажил",
                    icon_save: "Хадгалах",
                    icon_cancel: "Цуцлах",
                    icon_details: "Дэлгэрэнгүй",
                    icon_edit: "Засах",
                    icon_delete: "Устгах",
                    confirm_closing: "",
                    confirm_deleting: "Устгахдаа итгэлтэй байна уу?",
                    section_description: "Тайлбар",
                    section_time: "Цагийн хугацаа",
                    section_type: "Төрөл",
                    column_wbs: "WBS",
                    column_text: "Ажлын нэр",
                    column_start_date: "Эхлэх цаг",
                    column_duration: "Хугацаа",
                    column_add: "",
                    link: "Линк",
                    confirm_link_deleting: "устгагдана",
                    link_start: " (эхлэх)",
                    link_end: " (дуусах)",
                    type_task: "Ажил",
                    type_project: "Төсөл",
                    type_milestone: "Майлстоүн",
                    minutes: "Минут",
                    hours: "Цаг",
                    days: "Өдөр",
                    weeks: "Долоо хоног",
                    months: "Сар",
                    years: "Жил",
                    message_ok: "ОК",
                    message_cancel: "Цуцлах",
                    section_constraint: "Баригдац",
                    constraint_type: "Constraint type",
                    constraint_date: "Constraint date",
                    asap: "Хамгийн түрүүнд",
                    alap: "Хамгийн сүүлд",
                    snet: "Үүнээс хойш эхэл",
                    snlt: "Үүнээс өмнө эхэл",
                    fnet: "Үүнээс хойш дуусга",
                    fnlt: "Үүнээс өмнө эхэл",
                    mso: "Эхлэх ёстой",
                    mfo: "Дуусгах ёстой",
                    resources_filter_placeholder: "Бичиж шүүх",
                    resources_filter_label: "хоосон бол нуу"
                }
            }
        }
    })
});
//# sourceMappingURL=locale.js.map