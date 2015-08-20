if (typeof $WH == "undefined") {
    $WH = {
        wowheadRemote: true
    }
}

if (typeof $WowheadPower == "undefined") {
    var $WowheadPower = new function () {
        var isRemote = $WH.wowheadRemote;
        var opt = {
            applyto: 3
        },
         head = document.getElementsByTagName("head")[0],
         currentType, currentId, currentLocale, currentA, cursorX, cursorY, mode = 0,
         eventAttached = false,
         npcs = {},
         objects = {},
         items = {},
         quests = {},
         spells = {},
         showIcon = 1,
         STATUS_NONE = 0,
         STATUS_QUERYING = 1,
         STATUS_ERROR = 2,
         STATUS_NOTFOUND = 3,
         STATUS_OK = 4,
         TYPE_NPC = 1,
         TYPE_OBJECT = 2,
         TYPE_ITEM = 3,
         TYPE_QUEST = 5,
         TYPE_SPELL = 6,
         CURSOR_HSPACE = 15,
         CURSOR_VSPACE = 15,
         _LANG = {
                loading: "Loading...",
                noresponse: "No response from server :(",
         },
         LOOKUPS = {
                1: [npcs, "npc", "NPC"],
                2: [objects, "object", "Object"],
                3: [items, "item", "Item"],
                5: [quests, "quest", "Quest"],
                6: [spells, "spell", "Spell"]
         },
         LOCALES = {
                0: "enus",
                8: "ruru"
         };
        if (isRemote) {
            var Locale = {
                getId: function () {
                    return 0;
                },
                getName: function () {
                    return "enus";
                }
            }
        }

        function init() {
            if (isRemote) {
                var script = document.createElement("script");
                script.src = "../../../../../../External/WoW-Head-Tooltips/wowhead-db-ext1.js";
                head.appendChild(script);
            } else {
                attachEvent();
            }
        }

        function attachEvent() {
            if (eventAttached) {
                return
            }
            eventAttached = true;
            $WH.aE(document, "mouseover", onMouseOver)
        }

        this.init = function () {
            if (isRemote) {
                $WH.ae(head, $WH.ce("link", {
                    type: "text/css",
                    href: "../../../../../../External/WoW-Head-Tooltips/wowhead-db-ext2.css",
                    rel: "stylesheet"
                }))
            }

            attachEvent();

            if (typeof wowhead_tooltips != "undefined") {
                for (var i = 0; i < document.links.length; i++) {
                    var link = document.links[i];
                    scanElement(link)
                }
            }
        };

        function updateCursorPos(e) {
            var pos = $WH.g_getCursorPos(e);
            cursorX = pos.x;
            cursorY = pos.y
        }

        function scanElement(t, e) {
            if (t.nodeName != "A" && t.nodeName != "AREA") {
                return -2323;
            }

            if (!t.href.length && !t.rel) {
                return;
            }

            if (t.rel && t.rel.indexOf("np") != -1) {
                return;
            }

            var i0, i1, i2, m;

            if (opt.applyto & 1) {
                i1 = 3;
                i2 = 4;

                if (t.href.indexOf("http://") == 0 || t.href.indexOf("https://") == 0) {
                    i0 = 1;
                    m = t.href.match(/^https?:\/\/(db.valkyrie-wow.(com|ru|org))\/\??(npc|object|item|quest|spell)=([0-9]+)/);
                } else {
                    m = t.href.match(/()\/\??(npc|object|item|quest|spell)=([0-9]+)/);
                }
            }

            if (m == null && t.rel && (opt.applyto & 2)) {
                i0 = 0;
                i1 = 1;
                i2 = 2;
                m = t.rel.match(/(npc|object|item|quest|spell).?([0-9]+)/);
            }

            if (m) {
                var locale = 0;

                currentA = t;

                if (t.href.indexOf("#") != -1 && document.location.href.indexOf(m[i1] + "=" + m[i2]) != -1) {
                    return;
                }

                mode = ((t.parentNode.className.indexOf("icon") == 0 && t.parentNode.nodeName == "DIV") ? 1 : 0);

                if (!t.onmouseout) {
                    if (mode == 0) {
                        t.onmousemove = onMouseMove;
                    }

                    t.onmouseout = onMouseOut;
                }

                if (e) {
                    updateCursorPos(e);
                }

                var type = $WH.g_getIdFromTypeName(m[i1]);
                var typeId = m[i2];

                display(type, typeId, locale);

                if (e || typeof wowhead_tooltips == "undefined") {
                    return;
                }

                var data = LOOKUPS[type][0][typeId];

                var timeout = function (t) {
                    if (data.status[locale] != STATUS_OK && data.status[locale] != STATUS_NOTFOUND) {
                        window.setTimeout(function () {
                            timeout(t);
                        }, 5);

                        return;
                    }

                    if (wowhead_tooltips.renamelinks) {
                        eval("t.innerHTML = '<span>' + data.name_" + LOCALES[locale] + " + '</span>';");
                    }

                    if (wowhead_tooltips.iconizelinks && (type == TYPE_ITEM || type == TYPE_SPELL)) {
                        t.children[0].style.marginLeft = "18px";
                        t.className += " icontinyl";
                        t.style.paddingLeft = "18px !important";
                        t.style.verticalAlign = "center";
                        t.style.background = "url(//db.valkyrie-wow.org/images/icons/tiny/" + data.icon.toLocaleLowerCase() + ".gif) left center no-repeat";
                    }

                    if (wowhead_tooltips.colorlinks) {
                        if (type == TYPE_ITEM) {
                            t.className += " q" + data.quality;
                        }
                    }
                };

                timeout(t);
            }
        }

        function onMouseOver(e) {
            e = $WH.$E(e);
            var t = e._target;
            var i = 0;
            while (t != null && i < 5 && scanElement(t, e) == -2323) {
                t = t.parentNode;
                ++i
            }
        }

        function onMouseMove(e) {
            e = $WH.$E(e);
            updateCursorPos(e);
            $WH.Tooltip.move(cursorX, cursorY, 0, 0, CURSOR_HSPACE, CURSOR_VSPACE)
        }

        function onMouseOut() {
            currentType = null;
            currentA = null;
            $WH.Tooltip.hide()
        }

        function getTooltipField(locale, n) {
            return "tooltip" + (n ? n : "") + "_" + LOCALES[locale]
        }

        function getSpellsField(locale) {
            return "spells_" + LOCALES[locale]
        }

        function getImageField(locale, whichimage) {
            if (typeof whichimage == "undefined") {
                return "image_NONE"
            }
            return "image" + whichimage + "_" + LOCALES[locale]
        }

        function initElement(type, id, locale) {
            var arr = LOOKUPS[type][0];
            if (arr[id] == null) {
                arr[id] = {}
            }
            if (arr[id].status == null) {
                arr[id].status = {}
            }
            if (arr[id].response == null) {
                arr[id].response = {}
            }
            if (arr[id].status[locale] == null) {
                arr[id].status[locale] = STATUS_NONE
            }
        }

        function display(type, id, locale) {
            currentType = type;
            currentId = id;
            currentLocale = locale;

            initElement(type, id, locale);

            var arr = LOOKUPS[type][0];

            if (arr[id].status[locale] == STATUS_OK || arr[id].status[locale] == STATUS_NOTFOUND) {
                showTooltip(arr[id][getTooltipField(locale)], arr[id].icon, arr[id].map, arr[id][getSpellsField(locale)], arr[id][getTooltipField(locale, 2)]);
            } else {
                if (arr[id].status[locale] == STATUS_QUERYING) {
                    showTooltip(_LANG.loading)
                } else {
                    request(type, id, locale, null)
                }
            }
        }

        function request(type, id, locale, stealth) {
            var arr = LOOKUPS[type][0];

            if (arr[id].status[locale] != STATUS_NONE && arr[id].status[locale] != STATUS_ERROR) {
                return
            }

            arr[id].status[locale] = STATUS_QUERYING;

            if (!stealth) {
                arr[id].timer = setTimeout(function () {
                    showLoading.apply(this, [type, id, locale])
                }, 333)
            }

            var url = document.location.protocol + "//db.valkyrie-wow.org/ajax.php";

            $WH.g_ajaxIshRequest(url + "?" + LOOKUPS[type][1] + "=" + id + "&power");
        }

        function showTooltip(html, icon, map, spellData, html2, image, imageClass) {
            if (currentA && currentA._fixTooltip) {
                html = currentA._fixTooltip(html, currentType, currentId, currentA);
            }

            var notFound = false;

            if (!html) {
                html = LOOKUPS[currentType][2] + " not found :(";
                icon = "inv_misc_questionmark";
                notFound = true;
            }

            if (mode == 1) {
                $WH.Tooltip.setIcon(null);
                $WH.Tooltip.show(currentA, html, null, null, null, html2, image, imageClass);
            } else {
                $WH.Tooltip.setIcon(icon);
                $WH.Tooltip.showAtXY(html, cursorX, cursorY, CURSOR_HSPACE, CURSOR_VSPACE, html2, image, imageClass);
            }
        }

        function showLoading(type, id, locale) {
            if (currentType == type && currentId == id && currentLocale == locale) {
                showTooltip(_LANG.loading);

                var arr = LOOKUPS[type][0];

                arr[id].timer = setTimeout(function () {
                    notFound.apply(this, [type, id, locale])
                }, 3850);
            }
        }

        function notFound(type, id, locale) {
            var arr = LOOKUPS[type][0];

            arr[id].status[locale] = STATUS_ERROR;

            if (currentType == type && currentId == id && currentLocale == locale) {
                showTooltip(_LANG.noresponse)
            }
        }

        this.register = function (type, id, locale, json) {
            var arr = LOOKUPS[type][0];

            initElement(type, id, locale);

            if (arr[id].timer) {
                clearTimeout(arr[id].timer);
                arr[id].timer = null;
            }

            if (!$WH.wowheadRemote && json.map) {
                if (arr[id].map == null) {
                    arr[id].map = new Mapper({
                        parent: $WH.ce("div"),
                        zoom: 3,
                        zoomable: false,
                        buttons: false
                    })
                }

                arr[id].map.update(json.map);
                delete json.map;
            }

            $WH.cO(arr[id], json);

            if (arr[id].status[locale] == STATUS_QUERYING) {
                if (arr[id][getTooltipField(locale)]) {
                    arr[id].status[locale] = STATUS_OK
                } else {
                    arr[id].status[locale] = STATUS_NOTFOUND;
                }
            }

            if (currentType == type && id == currentId && currentLocale == locale) {
                showTooltip(arr[id][getTooltipField(locale)], arr[id].icon, arr[id].map, arr[id][getSpellsField(locale)], arr[id][getTooltipField(locale, 2)]);
            }
        };

        this.registerNpc = function (id, locale, json) {
            this.register(TYPE_NPC, id, locale, json)
        };

        this.registerObject = function (id, locale, json) {
            this.register(TYPE_OBJECT, id, locale, json)
        };

        this.registerItem = function (id, locale, json) {
            this.register(TYPE_ITEM, id, locale, json)
        };

        this.registerQuest = function (id, locale, json) {
            this.register(TYPE_QUEST, id, locale, json)
        };

        this.registerSpell = function (id, locale, json) {
            this.register(TYPE_SPELL, id, locale, json)
        };

        this.request = function (type, id, locale) {
            initElement(type, id, locale);
            request(type, id, locale, 1);
        };

        this.requestItem = function (id, params) {
            this.request(TYPE_ITEM, id, Locale.getId());
        };

        this.requestSpell = function (id) {
            this.request(TYPE_SPELL, id, Locale.getId());
        };

        this.getStatus = function (type, id, locale) {
            var arr = LOOKUPS[type][0];

            if (arr[id] != null) {
                return arr[id].status[locale];
            } else {
                return STATUS_NONE;
            }
        };

        this.getItemStatus = function (id, locale) {
            this.getStatus(TYPE_ITEM, id, locale);
        };

        this.getSpellStatus = function (id, locale) {
            this.getStatus(TYPE_SPELL, id, locale);
        };

        if (isRemote) {
            this.set = function (foo) {
                $WH.cO(opt, foo);
            };

            this.showTooltip = function (e, tooltip, icon) {
                updateCursorPos(e);
                showTooltip(tooltip, icon);
            };

            this.hideTooltip = function () {
                $WH.Tooltip.hide();
            };

            this.moveTooltip = function (e) {
                onMouseMove(e);
            }
        }

        init();
    }
};

var wowhead_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }