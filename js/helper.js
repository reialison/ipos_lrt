! function(t) {
    t.extend({
        loadPage: function(a) {
            var e = window.location.href.split("/"),
                n = e[0] + "//" + e[2] + "/" + e[3] + "/",
                i = t.extend({
                    loadTxt: '<center><img src="' + n + 'img/rotate.gif" width="70"><br>Loading ...</center>',
                    load: !0
                }, a);
            if (i.load) {
                var o = t(document).height();
                t("body").append("<div id='overlayProgress'></div>"), t("#overlayProgress").height(o).css({
                    position: "absolute",
                    top: 0,
                    left: 0,
                    "background-color": "rgba(0,0,0,.5)",
                    width: "100%",
                    "z-index": 5e3
                });
                var s = t("<span/>").css({
                        position: "absolute",
                        display: "block",
                        width: "100%",
                        color: "#fff",
                        "font-size": "24px",
                        margin: "10px auto"
                    }).html(i.loadTxt),
                    r = t("<div/>").css({
                        width: "20%",
                        height: "40px",
                        margin: "20% auto",
                        position: "relative"
                    }).append(s);
                t("<div/>").append(r).appendTo("#overlayProgress")
            } else t("#overlayProgress").remove()
        },
        callEditGuests: function(a) {
            var e = t.extend({
                    title: "",
                    success: function(t) {}
                }, a),
                n = bootbox.dialog({
                    message: baseUrl + "cashier/guest_edit_call",
                    title: e.title,
                    className: "manager-call-pop",
                    buttons: {
                        submit: {
                            label: "Submit Guest No",
                            className: "btn btn-guest-submit pop-manage pop-manage-green",
                            callback: function() {
                                var a = t("#guest-call-pin-login").val();
                                return t.post(baseUrl + "cashier/guest_edit_call_go/" + a, function(t) {
                                    e.success.call(this, a)
                                }), !0
                            }
                        },
                        cancel: {
                            label: "CANCEL",
                            className: "btn pop-manage pop-manage-red",
                            callback: function() {}
                        }
                    }
                });
            n.bind("shown.bs.modal", function() {
                input = n.find("#guest-call-pin-login"), input.focus(), input.keypress(function(t) {
                    13 == t.keyCode && n.find(".btn-guest-submit").trigger("click")
                })
            })
        },
        callServerNo: function(a) {
            var e = t.extend({
                    title: "",
                    success: function(t) {}
                }, a),
                n = bootbox.dialog({
                    message: baseUrl + "cashier/server_no_call",
                    title: e.title,
                    className: "manager-call-pop",
                    buttons: {
                        submit: {
                            label: "Submit Serve No.",
                            className: "btn btn-serve-submit pop-manage pop-manage-green",
                            callback: function() {
                                var a = t("#server-no-pin").val();
                                return t.post(baseUrl + "cashier/server_no_call_go/" + a, function(t) {
                                    e.success.call(this, a)
                                }), !0
                            }
                        },
                        cancel: {
                            label: "CANCEL",
                            className: "btn pop-manage pop-manage-red",
                            callback: function() {}
                        }
                    }
                });
            n.bind("shown.bs.modal", function() {
                input = n.find("#server-no-pin"), input.focus(), input.keypress(function(t) {
                    13 == t.keyCode && n.find(".btn-serve-submit").trigger("click")
                })
            })
        },
        callManager: function(a) {
            var e = t.extend({
                    title: "",
                    addData: "",
                    success: function(t) {},
                    fail: function(t) {},
                    cancel: function(t) {}
                }, a),
                n = bootbox.dialog({
                    message: baseUrl + "cashier/manager_call",
                    title: e.title,
                    addData: e.addData,
                    className: "manager-call-pop",
                    buttons: {
                        submit: {
                            label: "MANAGER PIN",
                            className: "btn btn-manager-submit pop-manage pop-manage-green",
                            callback: function() {
                                var a = t("#manager-call-pin-login").val();
                                a = a.replace("%", "");
                                a = a.replace("+", "");

                                var n = "pin=" + a + "&ptype=" + e.addData;
                                // alert(n);
                                return "" != a ? (t.post(baseUrl + "cashier/manager_go_login", n, function(t) {
                                    void 0 === t.error_msg ? e.success.call(this, t.manager) : (rMsg(t.error_msg, "error"), e.fail.call(this))
                                }, "json").fail(function(t, a, e) {
                                    alert(t.responseText)
                                }), !0) : (rMsg("Invalid Pin.", "error"), !1)
                            }
                        },
                        cancel: {
                            label: "CANCEL",
                            className: "btn pop-manage pop-manage-red",
                            callback: function() {
                                e.cancel.call(this)
                            }
                        }
                    }
                });
            n.bind("shown.bs.modal", function() {
                input = n.find("#manager-call-pin-login"), input.focus(), input.keypress(function(t) {
                    13 == t.keyCode && n.find(".btn-manager-submit").trigger("click")
                })
            })
        },
        callBuy2Take1: function(a) {
            var e = t.extend({
                    title: "",
                    success: function(t) {}
                }, a),
                n = bootbox.dialog({
                    message: baseUrl + "cashier/buy2take1_call",
                    title: e.title,
                    className: "manager-call-pop promo-call",
                    buttons: {
                        submit: {
                            label: "ADD ITEMS",
                            className: "btn btn-manager-submit pop-manage pop-manage-green",
                            callback: function() {
                                return !0
                            }
                        },
                        cancel: {
                            label: "CANCEL",
                            className: "btn pop-manage pop-manage-red",
                            callback: function() {
                                return !0
                            }
                        }
                    }
                });
            n.bind("shown.bs.modal", function() {
                input = n.find("#manager-call-pin-login"), input.focus(), input.keypress(function(t) {
                    13 == t.keyCode && n.find(".btn-manager-submit").trigger("click")
                })
            })
        },
        callLoyaltyCard: function(a) {
            var e = t.extend({
                    title: "",
                    success: function(t) {}
                }, a),
                n = bootbox.dialog({
                    message: baseUrl + "cashier/loyalty",
                    title: e.title,
                    className: "manager-call-pop",
                    buttons: {
                        submit: {
                            label: "Submit",
                            className: "btn btn-loyalty pop-manage pop-manage-green pop-manage-btn-half",
                            callback: function() {
                                var a = "pin=" + t("#loyalty-code").val();
                                return t("body").goLoad2(), t.post(baseUrl + "cashier/loyalty_add", a, function(a) {
                                    if (0 == a.error) {
                                        var e = a.card;
                                        t.each(e, function(a, e) {
                                            t("#loyalty-text").html("Loyalty: " + e.name)
                                        }), rMsg(a.msg, "success"), t("body").goLoad2({
                                            load: !1
                                        }), n.modal("hide")
                                    } else rMsg(a.msg, "error")
                                }, "json"), !1
                            }
                        },
                        cancel: {
                            label: "Remove",
                            className: "btn pop-manage pop-manage-red pop-manage-btn-half",
                            callback: function() {
                                t("body").goLoad2(), t.post(baseUrl + "cashier/loyalty_remove", function(a) {
                                    rMsg("Loyalty Removed.", "success"), t("#loyalty-text").html(""), t("body").goLoad2({
                                        load: !1
                                    }), n.modal("hide")
                                })
                            }
                        },
                        remove: {
                            label: "Close",
                            className: "btn pop-manage pop-manage-red pop-manage-btn-half",
                            callback: function() {}
                        }
                    }
                });
            n.bind("shown.bs.modal", function() {
                input = n.find("#loyalty-code"), input.focus(), input.keypress(function(t) {
                    13 == t.keyCode && n.find(".btn-loyalty").trigger("click")
                })
            })
        },
        callFS: function(a) {
            var e = t.extend({
                    title: "",
                    success: function(t) {}
                }, a),
                n = bootbox.dialog({
                    message: baseUrl + "cashier/food_server_call",
                    title: e.title,
                    className: "manager-call-pop",
                    buttons: {
                        submit: {
                            label: "FOOD SERVER PIN",
                            className: "btn btn-manager-submit pop-manage pop-manage-green",
                            callback: function() {
                                var a = "pin=" + t("#fs-call-pin-login").val();
                                return t.post(baseUrl + "cashier/food_server_login", a, function(t) {
                                    void 0 === t.error_msg ? e.success.call(this, t.emp) : rMsg(t.error_msg, "error")
                                }, "json"), !0
                            }
                        },
                        cancel: {
                            label: "CANCEL",
                            className: "btn pop-manage pop-manage-red",
                            callback: function() {}
                        }
                    }
                });
            n.bind("shown.bs.modal", function() {
                input = n.find("#fs-call-pin-login"), input.focus(), input.keypress(function(t) {
                    13 == t.keyCode && n.find(".btn-manager-submit").trigger("click")
                })
            })
        },
        callReasons: function(a) {
            var e = t.extend({
                submit: function(t) {}
            }, a);
            bootbox.dialog({
                message: baseUrl + "cashier/manager_reasons",
                className: "manager-call-pop bootbox-wide",
                buttons: {
                    submit: {
                        label: "SUBMIT",
                        className: "btn  pop-manage pop-manage-green",
                        callback: function() {
                            var a = t("#pop-reason").val(),
                                n = t("#other-reason-txt").val();
                            return "" != n && (a = n), e.submit.call(this, a), !0
                        }
                    },
                    cancel: {
                        label: "CANCEL",
                        className: "btn pop-manage pop-manage-red",
                        callback: function() {}
                    }
                }
            })
        },
        callFreeReasons: function(a) {
            var e = t.extend({
                submit: function(t) {}
            }, a);
            bootbox.dialog({
                message: baseUrl + "cashier/manager_free_reasons",
                className: "manager-call-pop bootbox-wide",
                buttons: {
                    submit: {
                        label: "SUBMIT",
                        className: "btn  pop-manage pop-manage-green",
                        callback: function() {
                            var a = t("#pop-reason").val(),
                                n = t("#other-reason-txt").val();

                                if(a == "" && n == ""){
                                    rMsg('Please select/input a reason', "error")
                                    return false;
                                }else{
                                    return "" != n && (a = n), e.submit.call(this, a), !0
                                }

                        }
                    },
                    cancel: {
                        label: "CANCEL",
                        className: "btn pop-manage pop-manage-red",
                        callback: function() {}
                    }
                }
            })
        },
        callDiscReasons: function(a) {
            var e = t.extend({
                submit: function(t) {}
            }, a);
            bootbox.dialog({
                message: baseUrl + "cashier/discount_reasons",
                className: "manager-call-pop bootbox-wide",
                buttons: {
                    submit: {
                        label: "SUBMIT",
                        className: "btn  pop-manage pop-manage-green",
                        callback: function() {
                            var a = t("#pop-code").val(),
                                n = t("#pop-reason").val();

                                if(a == "" && n == ""){
                                    rMsg('Please select/input a reason', "error")
                                    return false;
                                }else{
                                    return "" != a && (n = a), e.submit.call(this, n), !0
                                }

                        }
                    },
                    cancel: {
                        label: "CANCEL",
                        className: "btn pop-manage pop-manage-red",
                        callback: function() {}
                    }
                }
            })
        },
        callQuickEdit: function(a) {
            var e = t.extend({
                submit: function(t) {}
            }, a);
            bootbox.dialog({
                message: baseUrl + "menu/quick_edit",
                className: "manager-call-pop bootbox-wide bootbox-quick-edit media-quick-edit",
                // buttons: {
                //     submit: {
                //         label: "SUBMIT",
                //         className: "btn  pop-manage pop-manage-green",
                //         callback: function() {
                //             var a = t("#pop-code").val(),
                //                 n = t("#pop-reason").val();

                //                 if(a == "" && n == ""){
                //                     rMsg('Please select/input a reason', "error")
                //                     return false;
                //                 }else{
                //                     return "" != a && (n = a), e.submit.call(this, n), !0
                //                 }

                //         }
                //     },
                //     cancel: {
                //         label: "CANCEL",
                //         className: "btn pop-manage pop-manage-red",
                //         callback: function() {}
                //     }
                // }
            })
        },
        callPrinterSetup: function(a) {
            var e = t.extend({
                submit: function(t) {}
            }, a);
            bootbox.dialog({
                message: baseUrl + "cashier/printer_setup",
                className: " bootbox-wide printer-setup-class",
            })
        },
        callPrinterSetup2: function(a) {
            var e = t.extend({
                addData: "",
                submit: function(t) {}
            }, a);
            bootbox.dialog({
                message: baseUrl + "cashier/printer_option/"+e.addData,
                className: " bootbox-wide printer-setup-class",
            })
        },
        beep: function(a) {
            var e = t.extend({
                status: "success"
            }, a);
            "success" == e.status ? t.playSound(baseUrl + "img/beep") : "error" == e.status && t.playSound(baseUrl + "img/beeperror")
        },
        rProgressBar: function(a) {
            t.extend({
                element: null
            }, a);
            var e = t(document).height();
            t("body").append("<div id='overlayProgress'></div>"), t("#overlayProgress").height(e).css({
                position: "absolute",
                top: 0,
                left: 0,
                "background-color": "rgba(0,0,0,.5)",
                width: "100%",
                "z-index": 5e3
            });
            var n, i, o, s = t("<span/>").css({
                    position: "absolute",
                    display: "block",
                    width: "100%",
                    color: "#000",
                    "font-size": "24px",
                    margin: "10px auto"
                }).text("0%"),
                r = t("<div/>").attr({
                    class: "progress-bar progress-bar-primary",
                    role: "progressbar",
                    "aria-valuenow": "0",
                    "aria-valuemin": "0",
                    "aria-valuemax": "100"
                }).css({
                    width: "0%"
                }).append(s);
            t("<div/>").attr({
                class: "progress",
                id: "rProgressBar"
            }).css({
                width: "80%",
                height: "40px",
                margin: "20% auto",
                position: "relative"
            }).append(r).appendTo("#overlayProgress"), n = t("#rProgressBar .progress-bar span"), i = t("#rProgressBar .progress-bar"), o = setInterval(function() {
                t.ajax({
                    url: baseUrl + "site/get_load",
                    dataType: "json",
                    success: function(a) {
                        n.text(a.load + "% " + a.text), i.css({
                            width: a.load + "%"
                        }).attr({
                            "aria-valuenow": a.load
                        }), 100 == a.load && (setTimeout(function() {
                            t("#overlayProgress").remove()
                        }, 1e3), clearInterval(o))
                    },
                    error: function() {
                        setTimeout(function() {
                            t("#overlayProgress").remove()
                        }, 1e3), clearInterval(o)
                    }
                })
            }, 1e3)
        },
        rProgressBarEnd: function(a) {
            var e = t.extend({
                    onComplete: function(t) {}
                }, a),
                n = setInterval(function() {
                    var a = t("#rProgressBar .progress-bar");
                    a.exists() ? 100 == a.attr("aria-valuenow") && (e.onComplete.call(), clearInterval(n)) : (e.onComplete.call(), clearInterval(n))
                }, 1e3)
        },
        rPopForm: function(a) {
            var e = t.extend({
                    loadUrl: "",
                    passTo: "",
                    title: "",
                    rform: "",
                    wide: !1,
                    submit_btn_txt: "",
                    addData: "",
                    hide: !1,
                    asJson: !1,
                    serArray: !1,
                    noButton: !1,
                    onComplete: function(t) {},
                    onEscape: function(t) {},
                    onCancel: function(t) {}
                }, a),
                n = "<i class='fa fa-save'></i> Submit";
            if ("" != e.submit_btn_txt && (n = e.submit_btn_txt), e.wide) var i = " bootbox_wide";
            else i = null;
            if(e.noButton){
                g = bootbox.dialog({
                    message: baseUrl + e.loadUrl,
                    title: e.title,
                    className: i,
                    onEscape: function() {
                        e.onEscape.call(this)
                    }
                });
                g.bind("shown.bs.modal", function() {
                    input = g.find("#process"), input.click(function(t) {
                        g.find(".bootbox-close-button").trigger("click");
                    })
                })
            }
            else{
                g = bootbox.dialog({
                    message: baseUrl + e.loadUrl,
                    title: e.title,
                    className: i,
                    buttons: {
                        cancel: {
                            label: "Cancel",
                            className: "btn-default",
                            callback: function() {
                                e.onCancel.call(this)
                            }
                        },
                        submit: {
                            label: n,
                            className: "btn-primary rFormSubmitBtn",
                            callback: function() {
                                return t("#" + e.rform).rOkay({
                                    onComplete: e.onComplete,
                                    passTo: e.passTo,
                                    addData: e.addData,
                                    asJson: e.asJson,
                                    serArray: e.serArray,
                                    btn_load: t(".rFormSubmitBtn")
                                }), e.hide
                            }
                        }
                    },
                    onEscape: function() {
                        e.onEscape.call(this)
                    }
                });
                g.bind("shown.bs.modal", function() {
                    input = g.find("#process"), input.click(function(t) {
                        alert('aw');
                    })
                })
            }  
        }
    }), t.fn.center = function() {
        return this.css("position", "absolute"), this.css("top", Math.max(0, (t(window).height() - t(this).outerHeight()) / 2 + t(window).scrollTop()) - 30 + "px"), this.css("left", Math.max(0, (t(window).width() - t(this).outerWidth()) / 2 + t(window).scrollLeft()) + "px"), this
    }, t.fn.rLoadBar = function(a) {
        var e, n, i, o = t.extend({
                bar: t(this).find(".progress-bar"),
                statTxt: null
            }, a),
            s = o.bar;
        e = s, n = o.statTxt, i = setInterval(function() {
            t.ajax({
                url: baseUrl + "site/get_load",
                dataType: "json",
                success: function(a) {
                    null != n && n.exists() && n.text(a.text), t(".loadTxt").text(a.load), e.css({
                        width: a.load + "%"
                    }).attr({
                        "aria-valuenow": a.load
                    }), 100 == a.load && (setTimeout(function() {
                        t("#overlayProgress").remove()
                    }, 1e3), clearInterval(i))
                },
                error: function() {
                    setTimeout(function() {
                        t("#overlayProgress").remove()
                    }, 1e3), clearInterval(i)
                }
            })
        }, 1e3)
    }, t.fn.rLoadBarEnd = function(a) {
        var e = t.extend({
                onComplete: function(t) {},
                bar: t(this).find(".progress-bar")
            }, a),
            n = setInterval(function() {
                var t = e.bar;
                t.exists() ? 100 == t.attr("aria-valuenow") && (e.onComplete.call(), clearInterval(n)) : (e.onComplete.call(), clearInterval(n))
            }, 1e3)
    }, t.fn.print = function() {
        if (this.size() > 1) this.eq(0).print();
        else if (this.size()) {
            var a = "printer-" + (new Date).getTime(),
                e = t("<iframe name='" + a + "'>");
            e.css("width", "1px").css("height", "1px").css("position", "absolute").css("left", "-9999px").appendTo(t("body:first"));
            var n = window.frames[a],
                i = n.document,
                o = t("<div>").append(t("style").clone());
            i.open(), i.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'), i.write("<html>"), i.write("<body>"), i.write("<head>"), i.write("<title>"), i.write(document.title), i.write("</title>"), i.write(o.html()), i.write("</head>"), i.write(this.html()), i.write("</body>"), i.write("</html>"), i.close(), n.focus(), n.print(), setTimeout(function() {
                e.remove()
            }, 6e4)
        }
    }, t.fn.disableSelection = function() {
        return this.attr("unselectable", "on").css("user-select", "none").on("selectstart", !1)
    }, t.fn.exists = function() {
        return this.length > 0
    }, t.fn.hasAttr = function(t) {
        return void 0 !== this.attr(t)
    }, t.fn.rOkay = function(a) {
        var e = t.extend({
            passTo: this.attr("action"),
            addData: "",
            checkCart: null,
            validate: !0,
            asJson: !1,
            btn_load: null,
            goSubmit: !0,
            bnt_load_remove: !0,
            onComplete: function(t) {}
        }, a);

        function n(t) {
            noty({
                text: t,
                type: "error",
                dismissQueue: !0,
                layout: "topRight",
                theme: "defaultTheme",
                animation: {
                    open: {
                        height: "toggle"
                    },
                    close: {
                        height: "toggle"
                    },
                    easing: "swing",
                    speed: 500
                }
            }).setTimeout(3e3)
        }
        var i = 0,
            o = t(this);
        if (e.validate && o.find(".rOkay").each(function() {
                if ("" == t(this).val()) {
                    var a = t(this).prev("label").text(),
                        e = t(this).attr("ro-msg"),
                        o = "Error! " + a + " must not be empty.";
                    return void 0 !== e && !1 !== e && (o = e), n(o), t(this).focus(), i += 1, !1
                }
            }), !e.goSubmit) return !(i > 0);
        if (0 == i) {
            var s = o.serialize();
            if ("" != e.addData && (s = s + "&" + e.addData), null != e.btn_load) {
                o.attr("id");
                var r = e.btn_load.html();
                e.btn_load.attr("disabled", "disabled").html(r + ' <i class="fa fa-spinner fa-spin fa-fw"></i>')
            }
            e.asJson ? null != e.checkCart ? t.post(baseUrl + "wagon/check_wagon/" + e.checkCart, function(a) {
                a.error > 0 ? (e.btn_load.html(r), e.btn_load.removeAttr("disabled"), n("Error! " + a.msg)) : (null != e.btn_load && e.bnt_load_remove && (e.btn_load.html(r), e.btn_load.removeAttr("disabled")), t.post(baseUrl + e.passTo, s, function(t) {
                    e.onComplete.call(this, t)
                }, "json"))
            }, "json").fail(function(t, a, e) {
                alert(t.responseText)
            }) : t.post(baseUrl + e.passTo, s, function(t) {
                null != e.btn_load && e.bnt_load_remove && (e.btn_load.html(r), e.btn_load.removeAttr("disabled")), e.onComplete.call(this, t)
            }, "json").fail(function(t, a, e) {
                alert(t.responseText)
            }) : null != e.checkCart ? t.post(baseUrl + "wagon/check_wagon/" + e.checkCart, function(a) {
                a.error > 0 ? (e.btn_load.html(r), e.btn_load.removeAttr("disabled"), n("Error! " + a.msg)) : (null != e.btn_load && e.bnt_load_remove && (e.btn_load.html(r), e.btn_load.removeAttr("disabled")), t.post(baseUrl + e.passTo, s, function(t) {
                    e.onComplete.call(this, t)
                }))
            }, "json").fail(function(t, a, e) {
                alert(t.responseText)
            }) : t.post(baseUrl + e.passTo, s, function(t) {
                null != e.btn_load && e.bnt_load_remove && (e.btn_load.html(r), e.btn_load.removeAttr("disabled")), e.onComplete.call(this, t)
            })
        }
    }, t.fn.shiftLoad = function(a) {
        var e = t.extend({
            url: ""
        }, a);
        this.html('</br></br><center><span><i class="fa fa-spinner fa-lg fa-fw fa-spin" style="color: #fff;font-size:30px!important;"></i></span></center><br><h4 class="headline text-center" style="font-size:20px;">Please wait while loading the printer.</br>Note: Make sure the printer is on.</h4>').load(e.url)
    }, t.fn.rLoad = function(a) {
        var e = t.extend({
            url: ""
        }, a);
        this.html('<center><span><i class="fa fa-spinner fa-lg fa-fw fa-spin"></i></span></center>').load(e.url)
    }, t.fn.goLoad = function(a) {
        var e = t.extend({
                load: !0
            }, a),
            n = this.html();
        e.load ? this.attr("disabled", "disabled").html(n + ' <i class="fa fa-spinner fa-spin fa-fw go-load-spinner"></i>') : (this.removeAttr("disabled"), this.find(".go-load-spinner").remove())
    }, t.fn.goBoxLoad = function(a) {
        var e = t.extend({
                load: !0
            }, a),
            n = this.html();
        if (e.load) {
            this.attr("disabled", "disabled");
            var i = this.height();
            this.append("<div id='overlayProgress'></div>"), t("#overlayProgress").height(i).css({
                position: "absolute",
                top: 0,
                left: 0,
                "background-color": "rgba(255,255,255,0.7)",
                width: "100%",
                "z-index": 5e3
            }), n = t("<span/>").css({
                position: "absolute",
                display: "block",
                width: "100%",
                color: "#ddd",
                "font-size": "24px",
                margin: "10px auto"
            }).html('<i class="fa fa-refresh fa-spin"></i>');
            var o = t("<div/>").css({
                width: "10%",
                height: "40px",
                margin: "22% auto",
                position: "relative"
            }).append(n);
            t("<div/>").append(o).appendTo("#overlayProgress")
        } else this.removeAttr("disabled"), t("#overlayProgress").remove()
    }, t.fn.goLoad2 = function(a) {
        var e = t.extend({
                loadTxt: "Loading...",
                load: !0
            }, a),
            n = this.html();
        if (e.load) {
            this.attr("disabled", "disabled");
            var i = t(document).height();
            t("body").append("<div id='overlayProgress'></div>"), t("#overlayProgress").height(i).css({
                position: "absolute",
                top: 0,
                left: 0,
                "background-color": "rgba(0,0,0,.5)",
                width: "100%",
                "z-index": 5e3
            }), n = t("<span/>").css({
                position: "absolute",
                display: "block",
                width: "100%",
                color: "#fff",
                "font-size": "24px",
                margin: "10px auto"
            }).text(e.loadTxt);
            var o = t("<div/>").css({
                width: "20%",
                height: "40px",
                margin: "20% auto",
                position: "relative"
            }).append(n);
            t("<div/>").append(o).appendTo("#overlayProgress")
        } else this.removeAttr("disabled"), t("#overlayProgress").remove()
    }, t.fn.rTable = function(a) {
        var e = t.extend({
                tbl: t(this),
                loadFrom: t(this).attr("data-tbl-url"),
                loadData: "",
                noEdit: !1,
                noAdd: !1,
                beforeLoad: function(t) {},
                afterLoad: function(t) {},
                add: function(t) {},
                addBtnTxt: "",
                edit: function(t) {},
                editBtnTxt: "",
                noBtn1: !0,
                btn1: function(t) {},
                btn1Txt: "",
                noBtn2: !0,
                btn2: function(t) {},
                btn2Txt: "",
                noBtn3: !0,
                btn3: function(t) {},
                btn3Txt: "",
            }, a),
            n = e.tbl;
        if (n.addClass("rTable"), null != e.loadFrom) {
            var i = e.loadFrom,
                o = "";
            "" != e.loadData && (o = e.loadData), l(n), e.beforeLoad.call(this), t.post(baseUrl + i, o, function(a) {
                c(n),
                    function a(n, i) {
                        var o = n.attr("search-url"),
                            r = n.attr("listyle-multi"),
                            d = n.attr("listyle"),
                            u = i.post;
                        if (t("#appends-btns").remove(), n.before('<div class="row" id="appends-btns"><div class="col-md-12 text-left"><div class="btn-group" id="rTable-btns"></div></div></div>'), !e.noAdd) {
                            var f = '<i class="fa fa-plus fa-fw"></i> Create';
                            console.log(e.addBtnTxt), "" != e.addBtnTxt && (f = e.addBtnTxt), t("<button/>").attr({
                                id: "rtable-add-btn",
                                class: "btn btn-padding btn-circle green btn-outline",
                                style: "margin-right:6px;"
                            }).html(f).appendTo("#rTable-btns").click(function(t) {
                                return e.add.call(t), !1
                            })
                        }
                        if (e.noEdit || t("<button/>").attr({
                                id: "rtable-edit-btn",
                                class: "btn btn-circle green btn-outline",
                                style: "margin-right:6px;"
                            }).html('<i class="fa fa-edit fa-fw"></i> EDIT').prop("disabled", !0).appendTo("#rTable-btns").click(function(t) {
                                var a = n.find("tr.selected").attr("ref");
                                return e.edit.call(this, a), !1
                            }), "" != o && t("<button/>").attr({
                                id: "rtable-search-btn",
                                class: "btn btn-padding btn-circle green btn-outline btn-manager",
                                style: "margin-right:6px;"
                            }).html('<i class="fa fa-filter fa-fw"></i> Filter').appendTo("#rTable-btns").click(function(i) {
                                return bootbox.dialog({
                                    message: baseUrl + o,
                                    title: '<i class="fa fa-filter fa-fw"></i> Filter',
                                    className: "rTable-search-form",
                                    buttons: {
                                        cancel: {
                                            label: "Cancel",
                                            className: "btn-default",
                                            callback: function() {
                                                return !0
                                            }
                                        },
                                        submit: {
                                            label: "<i class='fa fa-check'></i> Submit",
                                            className: "btn-primary rFormSubmitBtn",
                                            callback: function() {
                                                var i = t(".rTable-search-form .bootbox-body").children("form").serialize(),
                                                    o = e.loadFrom,
                                                    r = e.loadData;
                                                return r += "" != r ? "&" + i : i, l(n), t.post(baseUrl + o, r, function(t) {
                                                    c(n), console.log(t), s(n, t), a(n, t)
                                                }, "json"), !0
                                            }
                                        }
                                    }
                                }), !1
                            }), e.noBtn1 || t("<button/>").attr({
                                id: "rtable-btn1-btn",
                                class: "btn btn-circle green btn-outline",
                                style: "margin-right:6px;"
                            }).html(e.btn1Txt).appendTo("#rTable-btns").click(function(t) {
                                var a = n.find("tr.selected").attr("ref");
                                return e.btn1.call(this, a), !1
                            }), e.noBtn2 || t("<button/>").attr({
                                id: "rtable-btn2-btn",
                                class: "btn btn-circle green btn-outline",
                                style: "margin-right:6px;"
                            }).html(e.btn2Txt).appendTo("#rTable-btns").click(function(t) {
                                var a = n.find("tr.selected").attr("ref");
                                return e.btn2.call(this, a), !1
                            }), e.noBtn3 || t("<button/>").attr({
                                id: "rtable-btn3-btn",
                                class: "btn btn-circle green btn-outline",
                                style: "margin-right:6px;"
                            }).html(e.btn3Txt).appendTo("#rTable-btns").click(function(t) {
                                var a = n.find("tr.selected").attr("ref");
                                return e.btn3.call(this, a), !1
                            }), "yes" == r) {
                            var p = t("<div/>").addClass("btn-group").css({
                                    "margin-right": "10px"
                                }),
                                b = t("<button/>").attr({
                                    id: "rtable-list-btn",
                                    class: "btn listyle-btns"
                                }).html('<i class="fa fa-align-justify fa-fw"></i>').appendTo(p).click(function(a) {
                                    var i = e.loadFrom,
                                        o = "",
                                        r = t(this);
                                    return "" != e.loadData && (o = e.loadData), console.log(u), void 0 !== u && !1 !== u && (t.each(u, function(t, a) {
                                        "" != a && "pagi" != t && (o += "&" + t + "=" + a)
                                    }), console.log(o)), n.attr("listyle", "list"), l(n), t.post(baseUrl + i, o, function(a) {
                                        c(n), s(n, a), t(".listyle-btns").removeClass("active"), r.addClass("active")
                                    }, "json"), !1
                                }),
                                h = t("<button/>").attr({
                                    id: "rtable-grid-btn",
                                    class: "btn listyle-btns"
                                }).html('<i class="fa fa-th fa-fw"></i>').appendTo(p).click(function(a) {
                                    var i = e.loadFrom,
                                        o = "",
                                        r = t(this);
                                    return "" != e.loadData && (o = e.loadData), void 0 !== u && !1 !== u && (t.each(u, function(t, a) {
                                        "" != a && "pagi" != t && (o += "&" + t + "=" + a)
                                    }), console.log(o)), n.attr("listyle", "grid"), l(n), t.post(baseUrl + i, o, function(a) {
                                        c(n), s(n, a), t(".listyle-btns").removeClass("active"), r.addClass("active")
                                    }, "json"), !1
                                });
                            "list" == d ? (t(".listyle-btns").removeClass("active"), b.addClass("active")) : "grid" == d && (t(".listyle-btns").removeClass("active"), h.addClass("active")), t("#rTable-btns").after(p)
                        }
                    }(n, a), s(n, a), e.afterLoad.call(this, a)
            }, "json").fail(function(t, a, e) {
                alert(t.responseText)
            })
        } else n.find("tr:gt(0)").each(function() {
            t(this).click(function() {
                return r(n, t(this)), !1
            })
        });

        function s(a, n) {
            var i = 0;
            if ("list" == a.attr("listyle")) t.each(n.rows, function(n, o) {
                var s;
                o.hasOwnProperty("inactive") && "Yes" == o.inactive && (s = "inactive-td");
                var l = t("<tr/>").attr({
                    ref: n,
                    class: s
                });
                e.noEdit || l.addClass("needHover").click(function() {
                    return r(a, t(this)), a.find("tr.selected").exists() ? t("#rtable-edit-btn").prop("disabled", !1) : t("#rtable-edit-btn").prop("disabled", !0), !1
                }), console.log(o), t.each(o, function(a, e) {
                    "image" != a && "inactive" != a && t("<td/>").html(e).appendTo(l), i++
                }), l.appendTo(a)
            });
            else {
                t("#rtable-ths").hide();
                var o = t("<tr/>"),
                    d = t("<td/>").attr({
                        colspan: "100%"
                    }).css({
                        "background-color": "#fff"
                    }).appendTo(o),
                    u = t("<div/>").attr({
                        class: "row"
                    });
                t.each(n.rows, function(a, n) {
                    var i, o = t("<div/>").attr({
                            class: "col-md-3 text-left"
                        }),
                        s = '<img src="' + baseUrl + 'img/noimage.png" style="height:100%;width:100%">';
                    n.hasOwnProperty("image") && (s = '<img src="' + baseUrl + n.image + '" style="height:100%;width:100%">'), n.hasOwnProperty("inactive") && "Yes" == n.inactive && (i = "bg-red"), o.html('<div class="info-box ' + i + '" style="cursor:pointer"><span class="info-box-icon" style="line-height:0px">' + s + '</span><div class="info-box-content"><h5>' + n.title + "</h5><h5>" + n.subtitle + '</h5><h6 style="margin:0px;">' + n.caption + "</h6></div></div>").click(function() {
                        return e.edit.call(this, a), !1
                    }), o.appendTo(u)
                }), d.append(u), o.appendTo(a)
            }
            if ("" != n.page) {
                var f = t("<tr/>");
                t("<td/>").attr("colspan", i).appendTo(f).append(n.page), a.append(f);
                var p = n.post;
                t(".pagination li a.ragi").click(function(n) {
                    if (!t(this).parent().hasClass("disabled")) {
                        var i = t(this).attr("pagi");
                        l(a);
                        var o = e.loadFrom,
                            r = e.loadData;
                        "" != e.loadData ? (r = e.loadData, r += "&pagi=" + i) : r += "pagi=" + i, console.log(p), void 0 !== p && !1 !== p && (t.each(p, function(t, a) {
                            "" != a && "pagi" != t && (r += "&" + t + "=" + a)
                        }), console.log(r)), t.post(baseUrl + o, r, function(t) {
                            c(a), s(a, t)
                        }, "json")
                    }
                    return !1
                })
            }
        }

        function r(t, a) {
            a.hasClass("selected") ? t.find("tr").removeClass("selected") : (t.find("tr").removeClass("selected"), a.addClass("selected"))
        }

        function l(a) {
            var e = t('<tr><td colspan="100%"><center><i class="fa fa-spin fa-spinner fa-fw fa-2x"></i></center></td></tr>');
            t("#rtable-ths").hide(), a.find("tr:gt(0)").remove(), a.append(e)
        }

        function c(a) {
            a.find("tr:gt(0)").remove(), t("#rtable-ths").show()
        }
    }, t.fn.rPopForm = function(a) {
        t(this).click(function() {
            var e = t.extend({
                loadUrl: t(this).attr("href"),
                passTo: t(this).attr("rata-pass"),
                title: t(this).attr("rata-title"),
                rform: t(this).attr("rata-form"),
                wide: !1,
                addData: "",
                hide: !1,
                asJson: !1,
                onComplete: function(t) {}
            }, a);
            if (e.wide) var n = "bootbox-wide";
            else n = null;
            return bootbox.dialog({
                message: baseUrl + e.loadUrl,
                title: e.title,
                className: n,
                buttons: {
                    cancel: {
                        label: "Cancel",
                        className: "btn-default",
                        callback: function() {}
                    },
                    submit: {
                        label: "<i class='fa fa-save'></i> Submit",
                        className: "btn-primary rFormSubmitBtn",
                        callback: function() {
                            return t("#" + e.rform).rOkay({
                                onComplete: e.onComplete,
                                passTo: e.passTo,
                                addData: e.addData,
                                asJson: e.asJson,
                                btn_load: t(".rFormSubmitBtn")
                            }), e.hide
                        }
                    }
                }
            }), !1
        })
    }, t.fn.rPopFormFile = function(a) {
        t(this).click(function() {
            var e = t.extend({
                loadUrl: t(this).attr("href"),
                passTo: t(this).attr("rata-pass"),
                title: t(this).attr("rata-title"),
                rform: t(this).attr("rata-form"),
                wide: !1,
                addData: "",
                hide: !1,
                asJson: !1,
                onComplete: function(t) {}
            }, a);
            if (e.wide) var n = "bootbox-wide";
            else n = null;
            return bootbox.dialog({
                message: baseUrl + e.loadUrl,
                title: e.title,
                className: n,
                buttons: {
                    cancel: {
                        label: "Cancel",
                        className: "btn-default",
                        callback: function() {}
                    },
                    submit: {
                        label: "<i class='fa fa-save'></i> Submit",
                        className: "btn-primary rFormSubmitBtn",
                        callback: function() {
                            if (t("#" + e.rform).rOkay({
                                    asJson: e.asJson,
                                    btn_load: t(".rFormSubmitBtn"),
                                    goSubmit: !1,
                                    bnt_load_remove: !0
                                })) {
                                var a = "script";
                                e.asJson && (a = "json"), t("#" + e.rform).submit(function(n) {
                                    t(this);
                                    var i = e.passTo,
                                        o = new FormData(this);
                                    t.ajax({
                                        url: baseUrl + i,
                                        type: "POST",
                                        data: o,
                                        dataType: a,
                                        mimeType: "multipart/form-data",
                                        contentType: !1,
                                        cache: !1,
                                        processData: !1,
                                        success: function(t, a, n) {
                                            e.onComplete.call(this, t)
                                        },
                                        error: function(t, a, e) {}
                                    }), n.preventDefault()
                                }), t("#" + e.rform).submit()
                            }
                            return e.hide
                        }
                    }
                }
            }), !1
        })
    }, t.fn.rPopView = function(a) {
        t(this).click(function() {
            var e = t.extend({
                loadUrl: t(this).attr("href"),
                title: t(this).attr("rata-title"),
                wide: !1,
                addData: ""
            }, a);
            if (e.wide) var n = "bootbox_wide";
            else n = "";
            return bootbox.dialog({
                message: baseUrl + e.loadUrl,
                title: e.title,
                className: n,
                buttons: {
                    cancel: {
                        label: "Close",
                        className: "btn-default",
                        callback: function() {}
                    }
                }
            }), !1
        })
    }, t.fn.rPrint = function(a) {
        t.extend({
            loadUrl: t(this).attr("href"),
            title: t(this).attr("print-title")
        }, a), t(this).click(function() {
            var a = t(this).attr("print-title"),
                e = t(this).attr("href");
            return window.open(e, a, "height=600,width=800"), !1
        })
    }, t.fn.rChangeSetVal = function(a) {
        var e = t.extend({
            tbl: "",
            where: "",
            col: "",
            changee: ""
        }, a);
        this.change(function() {
            var a, n, i = t(this).val();
            a = "tbl=" + e.tbl + "&col=" + e.col + "&where=" + e.where + "&val=" + i, n = function(a) {
                var n = t("#" + e.changee);
                n.is("input") || n.is("select") || n.is("textarea") ? n.val(a) : n.text(a)
            }, t.post(baseUrl + "/site/custom_get_val", a, function(t) {
                n(t)
            })
        })
    }, t.fn.rWagon = function(a) {
        var e, n = t.extend({
            cart: "",
            tbl: t(this),
            add_wagon_cell: null,
            input_row: null,
            inputs: null,
            reset_row: !0,
            datas: null,
            removeAdd: !1,
            beforeAddShow: null,
            onAdd: function(t) {},
            onUpdate: function(t) {},
            onCancelUpdate: function(t) {},
            onEdit: function(t) {},
            onDelete: function(t) {}
        }, a);

        function i(a, e, i) {
            t("#edit-" + a + "-" + e).click(function(s) {
                s.preventDefault();
                var r = t(n.input_row);
                r.show();
                var l = t("#line-" + a + "-" + e).index();
                t.each(n.inputs, function(t, a) {
                    var e = r.find(a.from),
                        n = r.find(a.show);
                    n.exists() && (n.is("input") || n.is("textarea") ? n.val(i[t]) : n.is("select") ? n.val(i[t]).trigger("chosen:updated") : n.text(i[t])), e.exists() && (e.is("input") || e.is("textarea") ? e.val(i[t]) : e.is("select") ? e.val(i[t]).trigger("chosen:updated") : o(i[t]) && void 0 === a["string-type"] ? e.number(i[t], a.dec) : e.text(i[t]))
                }), t(".line-row").show(), t("#line-" + a + "-" + e).hide(), n.tbl.find("tbody > tr").eq(l).after(r), t(".wagon-add-btns").hide(), t(".wagon-update-btns").show(), t(".wagon-update-btns").attr("ref", e), t("#show-input-row").hide(), n.onEdit.call(this)
            }), t("#delete-" + a + "-" + e).click(function(i) {
                var o = "delete=" + e;
                t.post(baseUrl + "wagon/delete_to_wagon/" + a, o, function(i) {
                    t("#line-" + a + "-" + e).remove(), n.onDelete.call(this, i)
                }, "json"), i.preventDefault()
            })
        }

        function o(t) {
            return !isNaN(parseFloat(t)) && isFinite(t)
        }(e = t(".wagon-edit-rows")).length > 0 && e.each(function() {
                var a = t(this).attr("ref");
                if (t(this).attr("cart") == n.cart) {
                    var e = "";
                    t(this).addClass("line-row line-row-" + n.cart), t(this).attr("id", "line-" + n.cart + "-" + a);
                    var o = t(this);
                    t.post(baseUrl + "wagon/get_wagon/" + n.cart + "/" + a, function(t) {
                        e = (e = e + "<a href='#' id='edit-" + n.cart + "-" + a + "' class = 'edit-" + n.cart + "' ref='" + a + "'><i class='fa fa-edit fa-lg'></i></a>") + " <a href='#' id='delete-" + n.cart + "-" + a + "' class = 'delete-" + n.cart + "' ref='" + a + "'><i class='fa fa-trash-o fa-lg'></i></a>", o.find("td:last-child").html(e), i(n.cart, a, t.items)
                    }, "json")
                }
            }),
            function() {
                var t = 0,
                    a = "<tr id='show-input-row" + n.cart + "'>";
                a += "<td colspan='100%'><a href='#' id='show-input" + n.cart + "' >Add an Item</a></td>", a += "</tr>";
                var e = n.tbl.find(".wagon-edit-rows");
                e.length > 0 && (ctr = 1, e.each(function() {
                    t = ctr, ctr++
                })), n.removeAdd || n.tbl.find("tbody > tr").eq(t).after(a)
            }(),
            function() {
                var a = t(n.input_row),
                    e = a.find("td:last-child");
                null != n.add_wagon_cell && (e = a.find(n.add_wagon_cell));
                var i = "";
                i += "<a href='#' id='wAdd" + n.cart + "' class='wagon-add-btns'><i class='fa fa-check fa-lg'></i></a> <a href='#' id='wAddCancel" + n.cart + "' class='wagon-add-btns'><i class='fa fa-ban fa-lg'></i></a>", i += "<a href='#' id='wUpdate" + n.cart + "' class='wagon-update-btns' style='display:none;'><i class='fa fa-check fa-lg'></i></a> <a href='#' id='wUpdateCancel" + n.cart + "' class='wagon-update-btns' style='display:none;'><i class='fa fa-ban fa-lg'></i></a>", e.html(i)
            }(), t("#show-input" + n.cart).click(function() {
                var a = !0,
                    e = null;
                if (t.isFunction(n.beforeAddShow)) e = n.beforeAddShow.call(this);
                if (null != e && (a = e), a) {
                    var i = t(n.input_row);
                    i.show(), t("#show-input-row" + n.cart).hide();
                    var o = t("#show-input-row" + n.cart).index();
                    n.tbl.find("tbody > tr").eq(o).after(i)
                }
                return !1
            }), t("#wAddCancel" + n.cart).click(function() {
                return t(n.input_row).hide(), t("#show-input-row" + n.cart).show(), !1
            }), t("#wAdd" + n.cart).click(function() {
                var a, e, s, r, l;
                return a = t("#show-input-row").index(), e = "", s = t(n.input_row), r = 1, l = "", t.each(n.inputs, function(t, a) {
                    var n = s.find(a.from);
                    if (n.exists()) {
                        var i = "";
                        if (r > 1 && (i = "&"), n.is("input") || n.is("textarea")) {
                            var c = n.val();
                            e = e + i + t + "=" + n.val()
                        } else if (n.is("select")) {
                            c = n.find(":selected").text();
                            e = e + i + t + "=" + n.val()
                        } else {
                            c = n.text();
                            e = e + i + t + "=" + n.text()
                        }
                        if (null != a.show)
                            if (o(c) && void 0 === a["string-type"]) {
                                var d = formatNumber(parseFloat(c), a.dec);
                                l = l + "<td>" + d + "</td>"
                            } else l = l + "<td>" + c + "</td>"
                    }
                    r++
                }), t.post(baseUrl + "wagon/add_to_wagon/" + n.cart, e, function(e) {
                    var o;
                    o = (o = (o = (o = "<tr id='line-" + n.cart + "-" + e.id + "' class='line-row line-row-" + n.cart + "'>") + l) + "<td><a href='#' id='edit-" + n.cart + "-" + e.id + "' class = 'edit-" + n.cart + "' ref='" + e.id + "'><i class='fa fa-edit fa-lg'></i></a>") + " <a href='#' id='delete-" + n.cart + "-" + e.id + "' class = 'delete-" + n.cart + "' ref='" + e.id + "'><i class='fa fa-trash-o fa-lg'></i></a></td>", o += "</tr>", n.tbl.find("tbody > tr").eq(a).before(o), i(n.cart, e.id, e.items), n.onAdd.call(this, e), n.reset_row && t.each(n.inputs, function(a, e) {
                        var i = t(n.input_row).find(e.from);
                        i.exists() && (i.is("input") || i.is("textarea") ? i.val("") : i.is("select") ? i.hasClass("dropitize") ? i.find("option:first-child").prop("selected", !0).end().trigger("chosen:updated") : i.val(i.find("option:first").val()) : i.text(""))
                    })
                }, "json"), !1
            }), t("#wUpdate" + n.cart).click(function() {
                var a, e, s, r, l, c, d = t(this).attr("ref");
                return a = d, e = t("#line-" + n.cart + "-" + a).index(), s = "", r = "", l = 1, c = t(n.input_row), t.each(n.inputs, function(t, a) {
                    var e = c.find(a.from);
                    if (e.exists()) {
                        var n = "";
                        if (l > 1 && (n = "&"), e.is("input") || e.is("textarea")) {
                            var i = e.val();
                            s = s + n + t + "=" + e.val()
                        } else if (e.is("select")) {
                            i = e.find(":selected").text();
                            s = s + n + t + "=" + e.val()
                        } else {
                            i = e.text();
                            s = s + n + t + "=" + e.text()
                        }
                        if (null != a.show)
                            if (o(i) && void 0 === a["string-type"]) {
                                var d = formatNumber(parseFloat(i), a.dec);
                                r = r + "<td>" + d + "</td>"
                            } else r = r + "<td>" + i + "</td>"
                    }
                    l++
                }), s = s + "&update=" + a, t.post(baseUrl + "wagon/update_to_wagon/" + n.cart, s, function(o) {
                    var s;
                    t("#line-" + n.cart + "-" + a).remove(), s = (s = (s = (s = "<tr id='line-" + n.cart + "-" + o.id + "' class='line-row line-row-" + n.cart + "'>") + r) + "<td><a href='#' id='edit-" + n.cart + "-" + o.id + "' class = 'edit-" + n.cart + "' ref='" + o.id + "'><i class='fa fa-edit fa-lg'></i></a>") + " <a href='#' id='delete-" + n.cart + "-" + o.id + "' class = 'delete-" + n.cart + "' ref='" + o.id + "'><i class='fa fa-trash-o fa-lg'></i></a></td>", s += "</tr>", n.tbl.find("tbody > tr").eq(e).before(s), i(n.cart, o.id, o.items), n.onUpdate.call(this, o)
                }, "json"), t(".wagon-add-btns").show(), t(".wagon-update-btns").hide(), t(".wagon-update-btns").removeAttr("ref"), c.hide(), t("#show-input-row").show(), !1
            }), t("#wUpdateCancel" + n.cart).click(function() {
                var a = t(n.input_row),
                    e = t(this).attr("ref");
                return t(".line-row").show(), t("#line-" + n.cart + "-" + e).show(), t(".wagon-add-btns").show(), t(".wagon-update-btns").hide(), t(".wagon-update-btns").removeAttr("ref"), a.hide(), t("#show-input-row").show(), n.onCancelUpdate.call(this), !1
            })
    }, t.fn.rWagonClear = function(a) {
        var e = t.extend({
                cart: "",
                tbl: t(this),
                beforeClear: null,
                onClear: function(t) {}
            }, a),
            n = !0,
            i = null;
        t.isFunction(e.beforeClear) && (i = e.beforeClear.call(this)), null != i && (n = i), n && (e.tbl.find(".line-row-" + e.cart).hide(), t.post(baseUrl + "wagon/clear_wagon/" + e.cart, function(t) {
            e.tbl.find(".line-row-" + e.cart).remove()
        }))
    }, t.fn.rAddOpt = function(a) {
        var e = t.extend({
                select: t(this),
                loadUrl: "",
                form: "",
                passTo: "",
                text: "Add New",
                val: "new-opt",
                onComplete: function(t) {}
            }, a),
            n = e.select;
        if (0 == n.find("option").length) {
            var i = t("<option>", {
                text: "",
                value: ""
            });
            n.append(i)
        }
        var o = t("<option>", {
            text: e.text,
            value: e.val
        });

        function s() {
            n.val(n.find("option:first").val())
        }
        n.append(o), n.change(function() {
            "new-opt" == t(this).val() && t.rPopForm({
                loadUrl: e.loadUrl,
                passTo: e.passTo,
                title: e.text,
                rform: e.form,
                hide: !0,
                asJson: !0,
                onCancel: function() {
                    s()
                },
                onEscape: function() {
                    s()
                },
                onComplete: function(a) {
                    var e = a.addOpt,
                        i = a.id,
                        o = t("<option>", {
                            text: e,
                            value: i
                        });
                    n.append(o), n.val(i), a.hasOwnProperty("msg") && rMsg(a.msg, "success")
                }
            })
        })
    }, t.fn.srchModifiers = function(a) {
        var e = t.extend({
            ajax: {
                url: baseUrl + "search/modifiers",
                type: "POST",
                dataType: "json",
                data: {
                    q: "{{{q}}}"
                }
            },
            locale: {
                emptyTitle: "Select to search modifier"
            },
            log: 3,
            cache: !1,
            preprocessData: function(a) {
                var e, n = a.length,
                    i = [];
                if (n)
                    for (e = 0; e < n; e++) i.push(t.extend(!0, a[e], {
                        text: a[e].Text,
                        value: a[e].Id,
                        data: {
                            subtext: a[e].Subtext
                        }
                    }));
                return i
            }
        }, a);
        t(this).selectpicker().filter(".with-ajax").ajaxSelectPicker(e)
    }, t(".ajax-modifiers-drop").srchModifiers(), t.fn.srchGrpModifiers = function(a) {
        var e = t.extend({
            ajax: {
                url: baseUrl + "search/modifiersGroup",
                type: "POST",
                dataType: "json",
                data: {
                    q: "{{{q}}}"
                }
            },
            locale: {
                emptyTitle: "Select to search items"
            },
            log: 3,
            cache: !1,
            preprocessData: function(a) {
                var e, n = a.length,
                    i = [];
                if (n)
                    for (e = 0; e < n; e++) i.push(t.extend(!0, a[e], {
                        text: a[e].Text,
                        value: a[e].Id,
                        data: {
                            subtext: a[e].Subtext
                        }
                    }));
                return i
            }
        }, a);
        t(this).selectpicker().filter(".with-ajax").ajaxSelectPicker(e)
    }, t(".ajax-group-modifiers-drop").srchGrpModifiers(), t.fn.srchMenus = function(a) {
        var e = t.extend({
            ajax: {
                url: baseUrl + "search/menus",
                type: "POST",
                dataType: "json",
                data: {
                    q: "{{{q}}}"
                }
            },
            locale: {
                emptyTitle: "Select to search items"
            },
            log: 3,
            cache: !1,
            preprocessData: function(a) {
                var e, n = a.length,
                    i = [];
                if (n)
                    for (e = 0; e < n; e++) i.push(t.extend(!0, a[e], {
                        text: a[e].Text,
                        value: a[e].Id,
                        data: {
                            subtext: a[e].Subtext
                        }
                    }));
                return i
            }
        }, a);
        t(this).selectpicker().filter(".with-ajax").ajaxSelectPicker(e)
    }, t(".ajax-menus-drop").srchMenus(), t.fn.srchMenusWithCat = function(a) {
        var e = t.extend({
            ajax: {
                url: baseUrl + "search/menus_cat",
                type: "POST",
                dataType: "json",
                data: {
                    q: "{{{q}}}"
                }
            },
            locale: {
                emptyTitle: "Select to search items"
            },
            log: 3,
            cache: !1,
            preprocessData: function(a) {
                var e, n = a.length,
                    i = [];
                if (n)
                    for (e = 0; e < n; e++) i.push(t.extend(!0, a[e], {
                        text: a[e].Text,
                        value: a[e].Id,
                        data: {
                            subtext: a[e].Subtext
                        }
                    }));
                return i
            }
        }, a);
        t(this).selectpicker().filter(".with-ajax").ajaxSelectPicker(e)
    }, t(".ajax-menus-cat-drop").srchMenusWithCat(), t.fn.srchItems = function(a) {
        var e = t.extend({
            ajax: {
                url: baseUrl + "search/items",
                type: "POST",
                dataType: "json",
                data: {
                    q: "{{{q}}}"
                }
            },
            locale: {
                emptyTitle: "Select to search items"
            },
            log: 3,
            cache: !1,
            preprocessData: function(a) {
                var e, n = a.length,
                    i = [];
                if (n)
                    for (e = 0; e < n; e++) i.push(t.extend(!0, a[e], {
                        text: a[e].Text,
                        value: a[e].Id,
                        data: {
                            subtext: a[e].Subtext
                        }
                    }));
                return i
            }
        }, a);
        t(this).selectpicker().filter(".with-ajax").ajaxSelectPicker(e)
    }, t(".ajax-items-drop").srchItems(), t.fn.srchModSub = function(a) {
        var e = t.extend({
            ajax: {
                url: baseUrl + "search/mod_sub",
                type: "POST",
                dataType: "json",
                data: {
                    q: "{{{q}}}"
                }
            },
            locale: {
                emptyTitle: "Select to search modifier"
            },
            log: 3,
            cache: !1,
            preprocessData: function(a) {
                var e, n = a.length,
                    i = [];
                if (n)
                    for (e = 0; e < n; e++) i.push(t.extend(!0, a[e], {
                        text: a[e].Text,
                        value: a[e].Id,
                        data: {
                            subtext: a[e].Subtext
                        }
                    }));
                return i
            }
        }, a);
        t(this).selectpicker().filter(".with-ajax").ajaxSelectPicker(e)
    }, t(".ajax-modifier-sub-drop").srchModSub()
}(jQuery);