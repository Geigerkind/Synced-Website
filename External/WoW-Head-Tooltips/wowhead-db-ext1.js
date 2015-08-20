if (typeof $WH == "undefined") {
    var $WH = {};
}

$WH.$E = function (a) {
    if (!a) {
        if (typeof event != "undefined") {
            a = event;
        } else {
            return null;
        }
    }

    if (a.which) {
        a._button = a.which;
    } else {
        a._button = a.button;
        if ($WH.Browser.ie6789 && a._button) {
            if (a._button & 4) {
                a._button = 2;
            } else {
                if (a._button & 2) {
                    a._button = 3;
                }
            }
        } else {
            a._button = a.button + 1;
        }
    }

    a._target = a.target ? a.target : a.srcElement;
    a._wheelDelta = a.wheelDelta ? a.wheelDelta : -a.detail;

    return a;
};

$WH.$A = function (c) {
    var e = [];
    for (var d = 0, b = c.length; d < b; ++d) {
        e.push(c[d]);
    }
    return e;
};

if (!Function.prototype.bind) {
    Function.prototype.bind = function () {
        var c = this,
         a = $WH.$A(arguments),
         b = a.shift();
        return function () {
            return c.apply(b, a.concat($WH.$A(arguments)));
        }
    }
}

$WH.bindfunc = function () {
    args = $WH.$A(arguments);
    var b = args.shift();
    var a = args.shift();
    return function () {
        return b.apply(a, args.concat($WH.$A(arguments)));
    }
};

if (!String.prototype.ltrim) {
    String.prototype.ltrim = function () {
        return this.replace(/^\s*/, "");
    }
}

if (!String.prototype.rtrim) {
    String.prototype.rtrim = function () {
        return this.replace(/\s*$/, "");
    }
}

if (!String.prototype.trim) {
    String.prototype.trim = function () {
        return this.ltrim().rtrim();
    }
}

if (!String.prototype.removeAllWhitespace) {
    String.prototype.removeAllWhitespace = function () {
        return this.replace("/s+/g", "");
    }
}

$WH.strcmp = function (d, c) {
    if (d == c) {
        return 0;
    }

    if (d == null) {
        return -1;
    }

    if (c == null) {
        return 1;
    }

    var f = parseFloat(d);
    var e = parseFloat(c);

    if (!isNaN(f) && !isNaN(e) && f != e) {
        return f < e ? -1 : 1;
    }

    if (typeof d == "string" && typeof c == "string") {
        return d.localeCompare(c);
    }

    return d < c ? -1 : 1;
};

$WH.trim = function (a) {
    return a.replace(/(^\s*|\s*$)/g, "");
};

$WH.rtrim = function (c, d) {
    var b = c.length;

    while (--b > 0 && c.charAt(b) == d) {}

    c = c.substring(0, b + 1);

    if (c == d) {
        c = "";
    }

    return c;
};

$WH.sprintf = function (b) {
    var a;

    for (a = 1, len = arguments.length; a < len; ++a) {
        b = b.replace("$" + a, arguments[a]);
    }

    return b;
};

$WH.sprintfa = function (b) {
    var a;

    for (a = 1, len = arguments.length; a < len; ++a) {
        b = b.replace(new RegExp("\\$" + a, "g"), arguments[a]);
    }

    return b;
};

$WH.sprintfo = function (c) {
    if (typeof c == "object" && c.length) {
        var a = c;

        c = a[0];

        var b;

        for (b = 1; b < a.length; ++b) {
            c = c.replace("$" + b, a[b]);
        }

        return c;
    }
};

$WH.str_replace = function (e, d, c) {
    while (e.indexOf(d) != -1) {
        e = e.replace(d, c);
    }

    return e;
};

$WH.urlencode = function (a) {
    a = encodeURIComponent(a);
    a = $WH.str_replace(a, "+", "%2B");

    return a;
};

$WH.urlencode2 = function (a) {
    a = encodeURIComponent(a);
    a = $WH.str_replace(a, "%20", "+");
    a = $WH.str_replace(a, "%3D", "=");

    return a;
};

$WH.number_format = function (a) {
    x = ("" + parseFloat(a)).split(".");
    a = x[0];
    x = x.length > 1 ? "." + x[1] : "";

    if (a.length <= 3) {
        return a + x;
    }

    return $WH.number_format(a.substr(0, a.length - 3)) + "," + a.substr(a.length - 3) + x;
};

$WH.is_array = function (b) {
    return !!(b && b.constructor == Array);
};

$WH.in_array = function (c, g, h, e) {
    if (c == null) {
        return -1;
    }

    if (h) {
        return $WH.in_arrayf(c, g, h, e);
    }

    for (var d = e || 0, b = c.length; d < b; ++d) {
        if (c[d] == g) {
            return d;
        }
    }

    return -1;
};

$WH.in_arrayf = function (c, g, h, e) {
    for (var d = e || 0, b = c.length; d < b; ++d) {
        if (h(c[d]) == g) {
            return d;
        }
    }
    return -1;
};

$WH.rs = function () {
    var e = $WH.rs.random;
    var b = "";

    for (var a = 0; a < 16; a++) {
        var d = Math.floor(Math.random() * e.length);

        if (a == 0 && d < 11) {
            d += 10;
        }

        b += e.substring(d, d + 1);
    }

    return b;
};

$WH.rs.random = "0123456789abcdefghiklmnopqrstuvwxyz";

$WH.isset = function (a) {
    return typeof window[a] != "undefined";
};

if (!$WH.isset("console")) {
    console = {
        log: function () {}
    }
}

$WH.array_walk = function (d, h, c) {
    var g;

    for (var e = 0, b = d.length; e < b; ++e) {
        g = h(d[e], c, d, e);

        if (g != null) {
            d[e] = g;
        }
    }
};

$WH.array_apply = function (d, h, c) {
    var g;

    for (var e = 0, b = d.length; e < b; ++e) {
        h(d[e], c, d, e);
    }
};

$WH.array_filter = function (c, g) {
    var e = [];

    for (var d = 0, b = c.length; d < b; ++d) {
        if (g(c[d])) {
            e.push(c[d]);
        }
    }

    return e;
};

$WH.array_index = function (c, e, g, h) {
    if (!$WH.is_array(c)) {
        return false;
    }

    if (!c.__R || h) {
        c.__R = {};

        if (!g) {
            g = function (a) {
                return a;
            }
        }

        for (var d = 0, b = c.length; d < b; ++d) {
            c.__R[g(c[d])] = d;
        }
    }

    return (e == null ? c.__R : !isNaN(c.__R[e]));
};

$WH.array_compare = function (d, c) {
    if (d.length != c.length) {
        return false;
    }

    var g = {};

    for (var f = d.length; f >= 0; --f) {
        g[d[f]] = true;
    }

    var e = true;

    for (var f = c.length; f >= 0; --f) {
        if (g[c[f]] === undefined) {
            e = false;
        }
    }
    return e;
};

$WH.array_unique = function (b) {
    var c = [];
    var e = {};

    for (var d = b.length - 1; d >= 0; --d) {
        e[b[d]] = 1;
    }

    for (var d in e) {
        c.push(d);
    }

    return c;
};

$WH.ge = function (a) {
    if (typeof a != "string") {
        return a;
    }

    return document.getElementById(a);
};

$WH.gE = function (a, b) {
    return a.getElementsByTagName(b);
};

$WH.ce = function (d, b, e) {
    var a = document.createElement(d);

    if (b) {
        $WH.cOr(a, b);
    }

    if (e) {
        $WH.ae(a, e);
    }

    return a;
};

$WH.de = function (a) {
    if (!a || !a.parentNode) {
        return;
    }

    a.parentNode.removeChild(a);
};

$WH.ae = function (a, b) {
    if ($WH.is_array(b)) {
        $WH.array_apply(b, a.appendChild.bind(a));
        return b
    } else {
        return a.appendChild(b)
    }
};

$WH.aef = function (a, b) {
    return a.insertBefore(b, a.firstChild);
};

$WH.ee = function (a, b) {
    if (!b) {
        b = 0;
    }

    while (a.childNodes[b]) {
        a.removeChild(a.childNodes[b]);
    }
};

$WH.ct = function (a) {
    return document.createTextNode(a);
};

$WH.st = function (a, b) {
    if (a.firstChild && a.firstChild.nodeType == 3) {
        a.firstChild.nodeValue = b;
    } else {
        $WH.aef(a, $WH.ct(b));
    }
};

$WH.nw = function (a) {
    a.style.whiteSpace = "nowrap";
};

$WH.rf = function () {
    return false;
};

$WH.rf2 = function (a) {
    a = $WH.$E(a);

    if (a.ctrlKey || a.shiftKey || a.altKey || a.metaKey) {
        return;
    }

    return false;
};

$WH.tb = function () {
    this.blur();
};

$WH.aE = function (b, c, a) {
    if (b.addEventListener) {
        b.addEventListener(c, a, false);
    } else {
        if (b.attachEvent) {
            b.attachEvent("on" + c, a);
        }
    }
};

$WH.dE = function (b, c, a) {
    if (b.removeEventListener) {
        b.removeEventListener(c, a, false);
    } else {
        if (b.detachEvent) {
            b.detachEvent("on" + c, a);
        }
    }
};

$WH.sp = function (a) {
    if (!a) {
        a = event;
    }

    if ($WH.Browser.ie6789) {
        a.cancelBubble = true;
    } else {
        a.stopPropagation();
    }
};

$WH.sc = function (h, j, d, f, g) {
    var e = new Date();
    var c = h + "=" + escape(d) + "; ";
    e.setDate(e.getDate() + j);
    c += "expires=" + e.toUTCString() + "; ";

    if (f) {
        c += "path=" + f + "; ";
    }

    if (g) {
        c += "domain=" + g + "; ";
    }

    document.cookie = c;

    $WH.gc(h);
    $WH.gc.C[h] = d
};

$WH.dc = function (a) {
    $WH.sc(a, -1);
    $WH.gc.C[a] = null;
};

$WH.gc = function (f) {
    if ($WH.gc.I == null) {
        var e = unescape(document.cookie).split("; ");

        $WH.gc.C = {};

        for (var c = 0, a = e.length; c < a; ++c) {
            var g = e[c].indexOf("="),
             b, d;

            if (g != -1) {
                b = e[c].substr(0, g);
                d = e[c].substr(g + 1);
            } else {
                b = e[c];
                d = "";
            }

            $WH.gc.C[b] = d;
        }

        $WH.gc.I = 1;
    }
    if (!f) {
        return $WH.gc.C;
    } else {
        return $WH.gc.C[f];
    }
};

$WH.ns = function (a) {
    if ($WH.Browser.ie6789) {
        a.onfocus = $WH.tb;
        a.onmousedown = a.onselectstart = a.ondragstart = $WH.rf;
    }
};

$WH.eO = function (b) {
    for (var a in b) {
        delete b[a];
    }
};

$WH.dO = function (a) {
    function b() {}
    b.prototype = a;
    return new b;
};

$WH.cO = function (c, a) {
    for (var b in a) {
        if (a[b] !== null && typeof a[b] == "object" && a[b].length) {
            c[b] = a[b].slice(0)
        } else {
            c[b] = a[b]
        }
    }
    return c
};
$WH.cOr = function (c, a) {
    for (var b in a) {
        if (typeof a[b] == "object") {
            if (a[b].length) {
                c[b] = a[b].slice(0)
            } else {
                if (!c[b]) {
                    c[b] = {}
                }
                $WH.cOr(c[b], a[b])
            }
        } else {
            c[b] = a[b]
        }
    }
    return c
};

$WH.Browser = {
    ie: !! (window.attachEvent && !window.opera),
    opera: !! window.opera,
    safari: navigator.userAgent.indexOf("Safari") != -1,
    firefox: navigator.userAgent.indexOf("Firefox") != -1,
    chrome: navigator.userAgent.indexOf("Chrome") != -1
};

$WH.Browser.ie9 = $WH.Browser.ie && navigator.userAgent.indexOf("MSIE 9.0") != -1;
$WH.Browser.ie8 = $WH.Browser.ie && navigator.userAgent.indexOf("MSIE 8.0") != -1 && !$WH.Browser.ie9;
$WH.Browser.ie7 = $WH.Browser.ie && navigator.userAgent.indexOf("MSIE 7.0") != -1 && !$WH.Browser.ie8;
$WH.Browser.ie6 = $WH.Browser.ie && navigator.userAgent.indexOf("MSIE 6.0") != -1 && !$WH.Browser.ie7;
$WH.Browser.ie67 = $WH.Browser.ie6 || $WH.Browser.ie7;
$WH.Browser.ie678 = $WH.Browser.ie67 || $WH.Browser.ie8;
$WH.Browser.ie6789 = $WH.Browser.ie678 || $WH.Browser.ie9;

$WH.OS = {
    windows: navigator.appVersion.indexOf("Windows") != -1,
    mac: navigator.appVersion.indexOf("Macintosh") != -1,
    linux: navigator.appVersion.indexOf("Linux") != -1
};

$WH.g_getWindowSize = function () {
    var a = 0,
     b = 0;

    if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
        a = document.documentElement.clientWidth;
        b = document.documentElement.clientHeight;
    } else {
        if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
            a = document.body.clientWidth;
            b = document.body.clientHeight;
        } else {
            if (typeof window.innerWidth == "number") {
                a = window.innerWidth;
                b = window.innerHeight;
            }
        }
    }

    return {
        w: a,
        h: b
    }
};

$WH.g_getScroll = function () {
    var a = 0,
     b = 0;

    if (typeof(window.pageYOffset) == "number") {
        a = window.pageXOffset;
        b = window.pageYOffset;
    } else {
        if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
            a = document.body.scrollLeft;
            b = document.body.scrollTop;
        } else {
            if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
                a = document.documentElement.scrollLeft;
                b = document.documentElement.scrollTop;
            }
        }
    }

    return {
        x: a,
        y: b
    }
};

$WH.g_getCursorPos = function (c) {
    var a, d;

    if (window.innerHeight) {
        a = c.pageX;
        d = c.pageY;
    } else {
        var b = $WH.g_getScroll();

        a = c.clientX + b.x;
        d = c.clientY + b.y;
    }

    return {
        x: a,
        y: d
    }
};

$WH.ac = function (c, d) {
    var a = 0,
     g = 0,
     b;

    while (c) {
        a += c.offsetLeft;
        g += c.offsetTop;
        b = c.parentNode;

        while (b && b != c.offsetParent && b.offsetParent) {
            if (b.scrollLeft || b.scrollTop) {
                a -= (b.scrollLeft | 0);
                g -= (b.scrollTop | 0);
                break;
            }

            b = b.parentNode;
        }

        c = c.offsetParent;
    }

    if ($WH.isset("Lightbox") && Lightbox.isVisible()) {
        d = true;
    }

    if (d) {
        var f = $WH.g_getScroll();
        a += f.x;
        g += f.y
    }

    var e = [a, g];
    e.x = a;
    e.y = g;

    return e;
};

$WH.g_scrollTo = function (c, b) {
    var m, l = $WH.g_getWindowSize(),
     o = $WH.g_getScroll(),
     j = l.w,
     e = l.h,
     g = o.x,
     d = o.y;

    c = $WH.ge(c);

    if (b == null) {
        b = [];
    } else {
        if (typeof b == "number") {
            b = [b];
        }
    }

    m = b.length;

    if (m == 0) {
        b[0] = b[1] = b[2] = b[3] = 0;
    } else {
        if (m == 1) {
            b[1] = b[2] = b[3] = b[0];
        } else {
            if (m == 2) {
                b[2] = b[0];
                b[3] = b[1];
            } else {
                if (m == 3) {
                    b[3] = b[1];
                }
            }
        }
    }

    m = $WH.ac(c);

    var a = m[0] - b[3],
     h = m[1] - b[0],
     k = m[0] + c.offsetWidth + b[1],
     f = m[1] + c.offsetHeight + b[2];

    if (k - a > j || a < g) {
        g = a;
    } else {
        if (k - j > g) {
            g = k - j;
        }
    }

    if (f - h > e || h < d) {
        d = h;
    } else {
        if (f - e > d) {
            d = f - e;
        }
    }

    scrollTo(g, d);
};

$WH.g_getIdFromTypeName = function (a) {
    var b = $WH.g_getIdFromTypeName.L;

    return (b[a] ? b[a] : -1);
};

$WH.g_getIdFromTypeName.L = {
    npc: 1,
    object: 2,
    item: 3,
    itemset: 4,
    quest: 5,
    spell: 6,
    zone: 7,
    faction: 8,
};

$WH.g_ajaxIshRequest = function (b, d) {
    var c = document.getElementsByTagName("head")[0];

    if (d) {
        $WH.ae(c, $WH.ce("script", {
            type: "text/javascript",
            src: b
        }));

        return;
    }

    var a = $WH.g_getGets();

    if (a.refresh != null) {
        if (a.refresh.length) {
            b += ("&refresh=" + a.refresh);
        } else {
            b += "&refresh";
        }
    }

    if (a.locale != null) {
        b += "&locale=" + a.locale;
    }

    $WH.ae(c, $WH.ce("script", {
        type: "text/javascript",
        src: b,
        charset: "utf8"
    }));
};

$WH.g_getGets = function () {
    if ($WH.g_getGets.C != null) {
        return $WH.g_getGets.C;
    }

    var b = $WH.g_getQueryString();
    var a = $WH.g_parseQueryString(b);

    $WH.g_getGets.C = a;

    return a;
};

$WH.g_getQueryString = function () {
    var a = "";

    if (location.pathname) {
        a += location.pathname.substr(1);
    }

    if (location.search) {
        if (location.pathname) {
            a += "&";
        }

        a += location.search.substr(1);
    }

    return a;
};

$WH.g_parseQueryString = function (e) {
    e = decodeURIComponent(e);

    var d = e.split("&");
    var c = {};

    for (var b = 0, a = d.length; b < a; ++b) {
        $WH.g_splitQueryParam(d[b], c);
    }

    return c;
};

$WH.g_splitQueryParam = function (c, d) {
    var e = c.indexOf("=");
    var a;
    var b;

    if (e != -1) {
        a = c.substr(0, e);
        b = c.substr(e + 1);
    } else {
        a = c;
        b = "";
    }

    d[a] = b;
};

$WH.g_createRect = function (d, c, a, b) {
    return {
        l: d,
        t: c,
        r: d + a,
        b: c + b
    }
};

$WH.g_intersectRect = function (d, c) {
    return !(d.l >= c.r || c.l >= d.r || d.t >= c.b || c.t >= d.b);
};

$WH.Tooltip = {
    create: function (j, l) {
        var g = $WH.ce("div"),
         n = $WH.ce("table"),
         b = $WH.ce("tbody"),
         f = $WH.ce("tr"),
         c = $WH.ce("tr"),
         a = $WH.ce("td"),
         m = $WH.ce("th"),
         k = $WH.ce("th"),
         h = $WH.ce("th");

        g.className = "wowhead-tooltip";
        m.style.backgroundPosition = "top right";
        k.style.backgroundPosition = "bottom left";
        h.style.backgroundPosition = "bottom right";

        if (j) {
            a.innerHTML = j;
        }

        $WH.ae(f, a);
        $WH.ae(f, m);
        $WH.ae(b, f);
        $WH.ae(c, k);
        $WH.ae(c, h);
        $WH.ae(b, c);
        $WH.ae(n, b);

        if (!l) {
            $WH.Tooltip.icon = $WH.ce("p");
            $WH.Tooltip.icon.style.visibility = "hidden";
            $WH.ae($WH.Tooltip.icon, $WH.ce("div"));
            $WH.ae(g, $WH.Tooltip.icon)
        }

        $WH.ae(g, n);

        return g;
    },

    getMultiPartHtml: function (b, a) {
        return "<table><tr><td>" + b + "</td></tr></table><table><tr><td>" + a + "</td></tr></table>";
    },

    fix: function (d, b, f) {
        var e = $WH.gE(d, "table")[0],
         h = $WH.gE(e, "td")[0],
         g = h.childNodes;

        d.className = $WH.trim(d.className.replace("tooltip-slider", ""));

        if (g.length >= 2 && g[0].nodeName == "TABLE" && g[1].nodeName == "TABLE") {
            g[0].style.whiteSpace = "nowrap";

            var a = parseInt(d.style.width);

            if (!d.slider || !a) {
                if (g[1].offsetWidth == 0) {
                    a = 320;
                } else {
                    if (g[1].offsetWidth > 300) {
                        a = Math.max(300, g[0].offsetWidth) + 20;
                    } else {
                        a = Math.max(g[0].offsetWidth, g[1].offsetWidth) + 20;
                    }
                }
            }

            a = Math.min(320, a);

            if (a > 20) {
                d.style.width = a + "px";
                g[0].style.width = g[1].style.width = "100%";
                if (d.slider) {
                    Slider.setSize(d.slider, a - 6);
                    d.className += " tooltip-slider";
                }
                if (!b && d.offsetHeight > document.body.clientHeight) {
                    e.className = "shrink";
                }
            }
        }

        if (f) {
            d.style.visibility = "visible";
        }
    },

    fixSafe: function (c, b, a) {
        $WH.Tooltip.fix(c, b, a);
    },

    attachImage: function (d, e, k, h) {
        if (typeof h == "undefined") {
            h = "";
        }

        if (false && typeof $ != "undefined") {
            $(d.parentNode).children(".image" + h).remove();
        } else {
            var l = new RegExp("\\bimage" + h + "\\b");

            for (var g = 0; g < d.parentNode.childNodes.length; g++) {
                if (l.test(d.parentNode.childNodes[g].className)) {
                    d.parentNode.removeChild(d.parentNode.childNodes[g]);
                    g--;
                }
            }
        }

        var j = typeof e;

        if (j != "string") {
            return;
        }

        var a = $WH.ce("div");
        a.className = "image" + h + (k ? " " + k : "");
        a.style.backgroundImage = "url(" + e + ")";

        if (typeof $ != "undefined") {
            $(d).after(a);
        } else {
            d.parentNode.insertBefore(a, d.nextSibling);
        }
    },

    append: function (c, b) {
        var c = $WH.ge(c);
        var a = $WH.Tooltip.create(b);

        $WH.ae(c, a);
        $WH.Tooltip.fixSafe(a, 1, 1);
    },

    prepare: function () {
        if ($WH.Tooltip.tooltip) {
            return;
        }

        var a = $WH.Tooltip.create();
        a.style.position = "absolute";
        a.style.left = a.style.top = "-2323px";

        $WH.ae(document.body, a);
        $WH.Tooltip.tooltip = a;
        $WH.Tooltip.tooltipTable = $WH.gE(a, "table")[0];
        $WH.Tooltip.tooltipTd = $WH.gE(a, "td")[0];

        var a = $WH.Tooltip.create(null, true);
        a.style.position = "absolute";
        a.style.left = a.style.top = "-2323px";

        $WH.ae(document.body, a);
        $WH.Tooltip.tooltip2 = a;
        $WH.Tooltip.tooltipTable2 = $WH.gE(a, "table")[0];
        $WH.Tooltip.tooltipTd2 = $WH.gE(a, "td")[0];
    },

    set: function (e, c, d, b) {
        var a = $WH.Tooltip.tooltip;
        a.style.width = "550px";
        a.style.left = "-2323px";
        a.style.top = "-2323px";

        if (e.nodeName) {
            $WH.ee($WH.Tooltip.tooltipTd);
            $WH.ae($WH.Tooltip.tooltipTd, e);
        } else {
            $WH.Tooltip.tooltipTd.innerHTML = e;
        }

        a.style.display = "";
        a.visibility = "visible";

        $WH.Tooltip.fix(a, 0, 0);

        if (c) {
            $WH.Tooltip.showSecondary = true;

            var a = $WH.Tooltip.tooltip2;
            a.style.width = "550px";
            a.style.left = "-2323px";
            a.style.top = "-2323px";

            if (c.nodeName) {
                $WH.ee($WH.Tooltip.tooltipTd2);
                $WH.ae($WH.Tooltip.tooltipTd2, c);
            } else {
                $WH.Tooltip.tooltipTd2.innerHTML = c;
            }

            a.style.display = "";

            $WH.Tooltip.fix(a, 0, 0);
        } else {
            $WH.Tooltip.showSecondary = false;
        }

        $WH.Tooltip.tooltipTable.style.display = (e == "") ? "none" : "";
        $WH.Tooltip.attachImage($WH.Tooltip.tooltipTable, d, b);
        $WH.Tooltip.generateEvent("show");
    },

    moveTests: [
        [null, null],
        [null, false],
        [false, null],
        [false, false]
    ],

    move: function (p, o, e, q, d, b) {
        if (!$WH.Tooltip.tooltipTable) {
            return;
        }

        var n = $WH.Tooltip.tooltip,
         j = $WH.Tooltip.tooltipTable.offsetWidth,
         c = $WH.Tooltip.tooltipTable.offsetHeight,
         l = $WH.Tooltip.tooltip2,
         g = $WH.Tooltip.showSecondary ? $WH.Tooltip.tooltipTable2.offsetWidth : 0,
         a = $WH.Tooltip.showSecondary ? $WH.Tooltip.tooltipTable2.offsetHeight : 0,
         r;
        n.style.width = (j == 0) ? "auto" : (j + "px");
        l.style.width = g + "px";

        var m, f;

        for (var h = 0, k = $WH.Tooltip.moveTests.length; h < k; ++h) {
            r = $WH.Tooltip.moveTests[h];
            m = $WH.Tooltip.moveTest(p, o, e, q, d, b, r[0], r[1]);

            if ($WH.isset("Ads") && !Ads.intersect(m)) {
                f = true;
                break;
            } else {
                if (!$WH.isset("Ads")) {
                    break;
                }
            }
        }

        if ($WH.isset("Ads") && !f) {
            Ads.intersect(m, true);
        }

        n.style.left = m.l + "px";
        n.style.top = m.t + "px";
        n.style.visibility = "visible";

        if ($WH.Tooltip.showSecondary) {
            l.style.left = m.l + j + "px";
            l.style.top = m.t + "px";
            l.style.visibility = "visible";
        }

        $WH.Tooltip.generateEvent("move");
    },

    moveTest: function (e, n, q, B, c, a, p, b) {
        var m = e,
         z = n,
         g = $WH.Tooltip.tooltip,
         k = $WH.Tooltip.tooltipTable.offsetWidth,
         s = $WH.Tooltip.tooltipTable.offsetHeight,
         o = $WH.Tooltip.tooltip2,
         A = $WH.Tooltip.showSecondary ? $WH.Tooltip.tooltipTable2.offsetWidth : 0,
         f = $WH.Tooltip.showSecondary ? $WH.Tooltip.tooltipTable2.offsetHeight : 0,
         j = $WH.g_getWindowSize(),
         l = $WH.g_getScroll(),
         h = j.w,
         r = j.h,
         d = l.x,
         y = l.y,
         w = d,
         v = y,
         u = d + h,
         t = y + r;

        if (p == null) {
            p = (e + q + k + A <= u);
        }

        if (b == null) {
            b = (n - Math.max(s, f) >= v);
        }

        if (p) {
            e += q + c;
        } else {
            e = Math.max(e - (k + A), w) - c;
        }

        if (b) {
            n -= Math.max(s, f) + a;
        } else {
            n += B + a;
        }

        if (e < w) {
            e = w;
        } else {
            if (e + k + A > u) {
                e = u - (k + A);
            }
        }

        if (n < v) {
            n = v;
        } else {
            if (n + Math.max(s, f) > t) {
                n = Math.max(y, t - Math.max(s, f));
            }
        }

        if ($WH.Tooltip.iconVisible) {
            if (m >= e - 48 && m <= e && z >= n - 4 && z <= n + 48) {
                n -= 48 - (z - n);
            }
        }

        return $WH.g_createRect(e, n, k, s);
    },

    show: function (e, h, b, a, f, c, d, g) {
        if ($WH.Tooltip.disabled) {
            return;
        }

        if (!b || b < 1) {
            b = 1;
        }

        if (!a || a < 1) {
            a = 1;
        }

        if (f) {
            h = '<span class="' + f + '">' + h + "</span>";
        }

        var j = $WH.ac(e);

        $WH.Tooltip.prepare();
        $WH.Tooltip.set(h, c, d, g);
        $WH.Tooltip.move(j.x, j.y, e.offsetWidth, e.offsetHeight, b, a);
    },

    showAtCursor: function (f, k, b, a, g, c, d, j) {
        if ($WH.Tooltip.disabled) {
            return;
        }

        if (!b || b < 10) {
            b = 10;
        }

        if (!a || a < 10) {
            a = 10;
        }

        if (g) {
            k = '<span class="' + g + '">' + k + "</span>";

            if (c) {
                c = '<span class="' + g + '">' + c + "</span>";
            }
        }

        f = $WH.$E(f);

        var h = $WH.g_getCursorPos(f);

        $WH.Tooltip.prepare();
        $WH.Tooltip.set(k, c, d, j);
        $WH.Tooltip.move(h.x, h.y, 0, 0, b, a)
    },

    showAtXY: function (g, a, h, d, c, e, f, b) {
        if ($WH.Tooltip.disabled) {
            return;
        }

        $WH.Tooltip.prepare();
        $WH.Tooltip.set(g, e, f, b);
        $WH.Tooltip.move(a, h, 0, 0, d, c);
    },

    cursorUpdate: function (b, a, d) {
        if ($WH.Tooltip.disabled || !$WH.Tooltip.tooltip) {
            return;
        }

        b = $WH.$E(b);

        if (!a || a < 10) {
            a = 10;
        }

        if (!d || d < 10) {
            d = 10;
        }

        var c = $WH.g_getCursorPos(b);

        $WH.Tooltip.move(c.x, c.y, 0, 0, a, d);
    },

    hide: function () {
        if ($WH.Tooltip.tooltip) {
            $WH.Tooltip.tooltip.style.display = "none";
            $WH.Tooltip.tooltip.visibility = "hidden";
            $WH.Tooltip.tooltipTable.className = "";
            $WH.Tooltip.setIcon(null);

            if ($WH.isset("Ads")) {
                Ads.restoreHidden();
            }

            $WH.Tooltip.generateEvent("hide");
        }

        if ($WH.Tooltip.tooltip2) {
            $WH.Tooltip.tooltip2.style.display = "none";
            $WH.Tooltip.tooltip2.visibility = "hidden";
            $WH.Tooltip.tooltipTable2.className = "";
        }
    },

    setIcon: function (a) {
        $WH.Tooltip.prepare();

        if (a) {
            $WH.Tooltip.icon.style.backgroundImage = "url(http://db.valkyrie-wow.org/images/icons/medium/" + a.toLowerCase() + ".jpg)";
            $WH.Tooltip.icon.style.visibility = "visible";
        } else {
            $WH.Tooltip.icon.style.backgroundImage = "none";
            $WH.Tooltip.icon.style.visibility = "hidden";
        }

        $WH.Tooltip.iconVisible = a ? 1 : 0;
    },

    generateEvent: function (a) {
        if (!$WH.Tooltip.tooltip) {
            return;
        }

        try {
            $WH.Tooltip.tooltip.dispatchEvent(new Event(a));
        } catch (c) {
            try {
                var b = document.createEvent("Event");
                b.initEvent(a, true, true);
                $WH.Tooltip.tooltip.dispatchEvent(b);
            } catch (c) {
                void(0);
            }
        }
    }
};

if ($WH.isset("$WowheadPower")) {
    $WowheadPower.init();
};
