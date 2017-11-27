(function (a) {
  a.fn.countdown = function (F) {
    var K = {
      date: null,
      updateTime: 1000,
      htmlTemplate: "%d <span class='cd-time'>days</span> %h <span class='cd-time'>hours</span> %i <span class='cd-time'>mins</span> %s <span class='cd-time'>sec</span>",
      minus: !1,
      onChange: null,
      onComplete: null,
      onResume: null,
      onPause: null,
      leadingZero: !1,
      offset: null,
      servertime: null,
      hoursOnly: !1,
      minsOnly: !1,
      secsOnly: !1,
      weeks: !1,
      hours: !1,
      yearsAndMonths: !1,
      direction: "down",
      stopwatch: !1
    }, H = Array.prototype.slice, O = window.clearInterval, G = Math.floor, J = 3600000, C = 31556926, X = 2629743.83, R = 604800, M = 86400, V = 3600, P = 60, I = 1, U = /(%y|%m|%w|%d|%h|%i|%s)/g, B = /%y/, L = /%m/, Q = /%w/, q = /%d/, W = /%h/, A = /%i/, D = /%s/, j = function (c) {
      var b = new Date, d = c.data("jcdData");
      return d ? (d.offset !== null ? b = z(d.offset) : b = z(null, d.difference), b.setMilliseconds(0), b) : new Date
    }, z = function (g, c) {
      var h, f, b, d = new Date;
      return g === null ? f = d.getTime() - c : (h = g * J, b = d.getTime() - -d.getTimezoneOffset() / 60 * J + h, f = d.setTime(b)), new Date(f)
    }, e = function () {
      var s = this, E, h, c, g, w, l, p, d, i, v, f, m = "", u, y = function (o) {
        var n;
        return n = G(u / o), u -= n * o, n
      }, b = s.data("jcdData");
      if (!b) {
        return !1
      }
      E = b.htmlTemplate, h = j(s), c = b.dateObj, c.setMilliseconds(0), g = b.direction === "down" ? c.getTime() - h.getTime() : h.getTime() - c.getTime(), u = Math.round(g / 1000), d = y(M), i = y(V), v = y(P), f = y(I), b.yearsAndMonths && (u += d * M, w = y(C), l = y(X), d = y(M)), b.weeks && (u += d * M, p = y(R), d = y(M)), b.hoursOnly && (i += d * 24, d = 0), b.minsOnly && (v += i * 60 + d * 24 * 60, d = i = 0), b.secsOnly && (f += v * 60, d = i = v = 0), b.yearsLeft = w, b.monthsLeft = l, b.weeksLeft = p, b.daysLeft = d, b.hrsLeft = i, b.minsLeft = v, b.secLeft = f, f === 60 && (f = 0), b.leadingZero && (d < 10 && !b.hoursOnly && (d = "0" + d), w < 10 && (w = "0" + w), l < 10 && (l = "0" + l), p < 10 && (p = "0" + p), i < 10 && (i = "0" + i), v < 10 && (v = "0" + v), f < 10 && (f = "0" + f)), b.direction === "down" && (h < c || b.minus) || b.direction === "up" && (c < h || b.minus) ? (m = E.replace(B, w).replace(L, l).replace(Q, p), m = m.replace(q, d).replace(W, i).replace(A, v).replace(D, f)) : (m = E.replace(U, "00"), b.hasCompleted = !0), s.html(m).trigger("change.jcdevt", [b]).trigger("countChange", [b]), b.hasCompleted && (s.trigger("complete.jcdevt").trigger("countComplete"), O(b.timer)), s.data("jcdData", b)
    }, k = {
      init: function (c) {
        var f = a.extend({}, K, c), b, d;
        return this.each(function () {
          var l = a(this), i = {}, h;
          l.data("jcdData") && (l.countdown("changeSettings", c, !0), f = l.data("jcdData"));
          if (f.date === null) {
            return a.error("No Date passed to jCountdown. date option is required."), !0
          }
          d = new Date(f.date), d.toString() === "Invalid Date" && a.error("Invalid Date passed to jCountdown: " + f.date), d = null, f.onChange && l.on("change.jcdevt", f.onChange), f.onComplete && l.on("complete.jcdevt", f.onComplete), f.onPause && l.on("pause.jcdevt", f.onPause), f.onResume && l.on("resume.jcdevt", f.onResume), i = a.extend({}, f), i.originalHTML = l.html(), i.dateObj = new Date(f.date), i.hasCompleted = !1, i.timer = 0, i.yearsLeft = i.monthsLeft = i.weeksLeft = i.daysLeft = i.hrsLeft = i.minsLeft = i.secLeft = 0, i.difference = null;
          if (f.servertime !== null) {
            var g;
            b = new Date, g = a.isFunction(i.servertime) ? i.servertime() : i.servertime, i.difference = b.getTime() - g, g = null
          }
          h = a.proxy(e, l), i.timer = setInterval(h, i.updateTime), l.data("jcdData", i), h()
        })
      }, changeSettings: function (b, c) {
        return this.each(function () {
          var g = a(this), f, h, d = a.proxy(e, g);
          if (!g.data("jcdData")) {
            return !0
          }
          f = a.extend({}, g.data("jcdData"), b), b.hasOwnProperty("date") && (h = new Date(b.date), h.toString() === "Invalid Date" && a.error("Invalid Date passed to jCountdown: " + b.date)), f.hasCompleted = !1, f.dateObj = new Date(b.date), O(f.timer), g.off(".jcdevt").data("jcdData", f), c || (f.onChange && g.on("change.jcdevt", f.onChange), f.onComplete && g.on("complete.jcdevt", f.onComplete), f.onPause && g.on("pause.jcdevt", f.onPause), f.onResume && g.on("resume.jcdevt", f.onResume), f.timer = setInterval(d, f.updateTime), g.data("jcdData", f), d()), f = null
        })
      }, resume: function () {
        return this.each(function () {
          var c = a(this), g = c.data("jcdData"), f = a.proxy(e, c);
          if (!g) {
            return !0
          }
          c.data("jcdData", g).trigger("resume.jcdevt", [g]).trigger("countResume", [g]);
          if (!g.hasCompleted) {
            g.timer = setInterval(f, g.updateTime);
            if (g.stopwatch && g.direction === "up") {
              var b = j(c).getTime() - g.pausedAt.getTime(), d = new Date;
              d.setTime(g.dateObj.getTime() + b), g.dateObj = d
            }
            f()
          }
        })
      }, pause: function () {
        return this.each(function () {
          var b = a(this), c = b.data("jcdData");
          if (!c) {
            return !0
          }
          c.stopwatch && (c.pausedAt = j(b)), O(c.timer), b.data("jcdData", c).trigger("pause.jcdevt", [c]).trigger("countPause", [c])
        })
      }, complete: function () {
        return this.each(function () {
          var b = a(this), c = b.data("jcdData");
          if (!c) {
            return !0
          }
          O(c.timer), c.hasCompleted = !0, b.data("jcdData", c).trigger("complete.jcdevt").trigger("countComplete", [c]).off(".jcdevt")
        })
      }, destroy: function () {
        return this.each(function () {
          var b = a(this), c = b.data("jcdData");
          if (!c) {
            return !0
          }
          O(c.timer), b.off(".jcdevt").removeData("jcdData").html(c.originalHTML)
        })
      }, getSettings: function (b) {
        var d = a(this), c = d.data("jcdData");
        return b && c ? c.hasOwnProperty(b) ? c[b] : undefined : c
      }
    };
    if (k[F]) {
      return k[F].apply(this, H.call(arguments, 1))
    }
    if (typeof F == "object" || !F) {
      return k.init.apply(this, arguments)
    }
    a.error("Method " + F + " does not exist in the jCountdown Plugin")
  }
})(jQuery);