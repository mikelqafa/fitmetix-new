!function (t) {
  function n(i) {
    if (s[i])return s[i].exports;
    var a = s[i] = {i: i, l: !1, exports: {}};
    return t[i].call(a.exports, a, a.exports, n), a.l = !0, a.exports
  }

  var s = {};
  n.m = t, n.c = s, n.i = function (t) {
    return t
  }, n.d = function (t, s, i) {
    n.o(t, s) || Object.defineProperty(t, s, {configurable: !1, enumerable: !0, get: i})
  }, n.n = function (t) {
    var s = t && t.__esModule ? function () {
      return t.default
    } : function () {
      return t
    };
    return n.d(s, "a", s), s
  }, n.o = function (t, n) {
    return Object.prototype.hasOwnProperty.call(t, n)
  }, n.p = "", n(n.s = 4)
}([function (t, n) {
  !function (t, n) {
    "use strict";
    var s = {
      SHOWN: "ca.snackbar.shown",
      HIDDEN: "ca.snackbar.hidden",
      CLICKED: "ca.snackbar.clicked"
    }, i = function (t) {
      this.init_(t)
    };
    i.VERSION = "1.0", i.prototype.Classes_ = {
      SURFACE: "md-snackbar__surface",
      ALIGN_CENTER: "md-snackbar--center",
      MULTILINE: "md-snackbar--multiline",
      SURFACE_TEXT: "md-snackbar__text",
      SNACKBAR: "md-snackbar",
      IS_OPEN: "md-snackbar--open",
      IS_ANIMATING: "md-snackbar--animating",
      BODY_CLASS: "is-snackbar-open"
    }, i.prototype.init_ = function (s) {
      this.config = t.extend({}, this.Default, s);
      var i = t(document.createElement("div"));
      i.addClass(this.Classes_.SNACKBAR), this.config.alignCenter && i.addClass(this.Classes_.ALIGN_CENTER), this.config.multiLine && i.addClass(this.Classes_.MULTILINE), this.randomId = this.getRandomId(), i.attr("id", this.randomId);
      var a = document.createElement("div");
      a.className = this.Classes_.SURFACE_TEXT, a.innerText = this.config.messageText;
      var o = document.createElement("div");
      if (o.className = this.Classes_.SURFACE, o.appendChild(a), this.config.showButton) {
        var e = document.createElement("button");
        e.className = "md-button md-button--accent md-snackbar__button", e.innerText = this.config.buttonText, o.appendChild(e)
      }
      if (!this.config.showButton && !this.autoClose) {
        var e = document.createElement("span");
        e.className = "md-button md-button--accent md-snackbar__button md-button--icon", e.innerHTML = '<i class="icon icon-close"></i>', o.appendChild(e)
      }
      return i.append(o).appendTo("body"), this.$snackBar = t("#" + this.randomId), this.$snackBar.addClass(this.Classes_.IS_ANIMATING), this.$surface = this.$snackBar.find("." + this.Classes_.SURFACE), this.boundOnTransitionEnd = this.onTransitionEnd_.bind(this), this.boundOnTransitionEndDestroy = this.onTransitionEndDestroy_.bind(this), this.config.showButton && (this.$button = i.find(".md-snackbar__button"), this.$button.on("click", this.onButtonClick_.bind(this))), this.config.showButton || this.autoClose || (this.$button = i.find(".md-snackbar__button"), this.$button.on("click", this.onButtonClick_.bind(this))), n.setTimeout(function () {
        this.show_()
      }.bind(this), 100), this.$snackBar.data("ca.snackbar", this), this.$snackBar
    }, i.prototype.Default = {
      messageText: "",
      buttonText: "UNDO",
      showButton: !1,
      autoClose: !0,
      multiLine: !1,
      alignCenter: !1,
      closeTimeOut: 3e3
    }, i.prototype.show_ = function () {
      this.$snackBar.addClass(this.Classes_.IS_OPEN),
          t("body").addClass(this.Classes_.BODY_CLASS),
          this.$surface.on("transitionend", this.boundOnTransitionEnd),
          this.$snackBar.trigger(s.SHOWN),
      this.config.autoClose && (this.hideTimeOut = n.setTimeout(function () {
        this.hide_()
      }.bind(this), this.config.closeTimeOut))
    }, i.prototype.hide_ = function () {
      this.$snackBar.removeClass(this.Classes_.IS_OPEN).addClass(this.Classes_.IS_ANIMATING),
          this.$surface.on("transitionend", this.boundOnTransitionEndDestroy),
      this.config.showButton && this.$button.unbind("click"), t("body").find(".md-snackbar").length > 1 || t("body").removeClass(this.Classes_.BODY_CLASS), this.$snackBar.remove()
      this.$snackBar.trigger(s.HIDDEN)
    }, i.prototype.onTransitionEndDestroy_ = function () {
      this.$snackBar.removeClass(this.Classes_.IS_ANIMATING),
          this.$surface.unbind("transitionend", this.boundOnTransitionEndDestroy)
    }, i.prototype.onTransitionEnd_ = function () {
      this.$snackBar.removeClass(this.Classes_.IS_ANIMATING), this.$surface.unbind("transitionend", this.boundOnTransitionEnd)
    }, i.prototype.onButtonClick_ = function () {
      this.$snackBar.trigger(s.CLICKED), this.hide_()
    }, i.prototype.getRandomId = function () {
      return function () {
        return Math.floor(65536 * (1 + Math.random())).toString(16).substring(1)
      }()
    };
    var a = function (t) {
      return new i(t).$snackBar
    };
    void 0 === n.materialSnackBar && (n.materialSnackBar = a)
  }(jQuery, "undefined" != typeof window ? window : this)
}, , , , function (t, n, s) {
  t.exports = s(0)
}]);