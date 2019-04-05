var XIV =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/assets/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/js/app.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/Account.js":
/*!******************************!*\
  !*** ./assets/js/Account.js ***!
  \******************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__ButtonLoader__ = __webpack_require__(/*! ./ButtonLoader */ "./assets/js/ButtonLoader.js");
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }



var AccountSettings = {
    urls: {
        add: '/account/characters/add',
        confirm: '/account/characters/confirm'
    }
};
var AccountCharacterInstance = {
    id: null
};

var Account = function () {
    function Account() {
        _classCallCheck(this, Account);
    }

    _createClass(Account, null, [{
        key: 'init',
        value: function init() {
            var _this = this;

            // character add submit
            $('.acc-add-char').on('submit', function (event) {
                event.preventDefault();
                _this.handleCharacterAdding();
            });

            // character verification
            $('html').on('click', '.acc-add-char-verify', function (event) {
                event.preventDefault();
                _this.handleCharacterAddingVerification();
            });
        }
    }, {
        key: 'handleCharacterAdding',
        value: function handleCharacterAdding() {
            var $form = $('.acc-add-char');
            var $view = $('.acc-add-char-res');
            __WEBPACK_IMPORTED_MODULE_0__ButtonLoader__["a" /* default */].on($form.find('button'), 'light');

            $.ajax({
                url: AccountSettings.urls.add,
                data: {
                    name: $(event.target).find('#name').val().trim(),
                    server: $(event.target).find('#server').val().trim()
                },
                success: function success(response) {
                    if (response.error) {
                        return $view.html('\n                        <br>\n                        <div class="alert alert-danger">\n                        ' + response.error + '\n                        </div>\n                    ');
                    }

                    // set instance
                    AccountCharacterInstance.id = response.id;

                    // set response
                    $view.html('\n                    <hr>\n                    <div class="acc-add-char-found">\n                        <div>\n                            <img src="' + response.avatar + '" height="80">\n                        </div>\n                        <div>\n                            <h3>' + response.name + ' &nbsp; <small>' + response.server.toUpperCase() + '</small></h3>\n                            <div><small>#' + response.id + '</small></div>\n                            <p>You now need to verify this character is yours. To do this, please enter your\n                            <strong>character verification code</strong> onto your Lodestone profile. Once\n                            you have done this, click <strong>Confirm Verification</strong>. You can remove\n                            the code once verification is complete.</p>\n                            <div class="acc-add-char-confirm">\n                                <button class="btn btn-success acc-add-char-verify">Confirm Verification</button>\n                                <div class="acc-add-char-confirm-res"></div>\n                            </div>\n                        </div>\n                    </div>\n                ');
                },
                error: function error(a, b, c) {
                    console.error(a, b, c);
                    return $view.html('\n                    <div class="alert alert-danger">' + c + ' - Please try again in a few minutes.</div>\n                ');
                },
                complete: function complete() {
                    __WEBPACK_IMPORTED_MODULE_0__ButtonLoader__["a" /* default */].off($form.find('button'));
                }
            });
        }
    }, {
        key: 'handleCharacterAddingVerification',
        value: function handleCharacterAddingVerification() {
            var $view = $('.acc-add-char-confirm-res');

            __WEBPACK_IMPORTED_MODULE_0__ButtonLoader__["a" /* default */].on($('.acc-add-char-verify'), 'light');

            $.ajax({
                url: AccountSettings.urls.confirm,
                data: {
                    id: AccountCharacterInstance.id
                },
                success: function success(response) {
                    if (response.error) {
                        return $view.html('\n                        <br>\n                        <div class="alert alert-danger">\n                        ' + response.error + '\n                        </div>\n                    ');
                    }

                    return $view.html('\n                    <br>\n                    <div class="alert alert-success alert-success-pop">\n                    <strong>' + response.success + '</strong>\n                    </div>\n                ');
                },
                error: function error(a, b, c) {
                    console.error(a, b, c);
                    return $view.html('\n                    <div class="alert alert-danger">' + c + ' - Please try again in a few minutes.</div>\n                ');
                },
                complete: function complete() {
                    __WEBPACK_IMPORTED_MODULE_0__ButtonLoader__["a" /* default */].off($('.acc-add-char-verify'));
                }
            });
        }
    }]);

    return Account;
}();

/* harmony default export */ __webpack_exports__["a"] = (Account);

/***/ }),

/***/ "./assets/js/ButtonLoader.js":
/*!***********************************!*\
  !*** ./assets/js/ButtonLoader.js ***!
  \***********************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ButtonLoaderSettings = {
    svg: '<img src="/img/ui/loaders/dual_ring_{theme}.svg" height="{height}" class="btn-loader-icon">',
    attr: 'button-loader-id',
    data: {}
};

var ButtonLoader = function () {
    function ButtonLoader() {
        _classCallCheck(this, ButtonLoader);
    }

    _createClass(ButtonLoader, null, [{
        key: 'on',

        /**
         * Turn on loading
         * @param $element
         * @param theme
         */
        value: function on($element, theme) {
            // get the text and id
            var text = $element.text();
            var width = $element.outerWidth(true) + 'px';
            var height = $element.outerHeight(true) + 'px';
            var id = Math.random().toString(36);

            var icon = ButtonLoaderSettings.svg.replace('{theme}', theme);
            icon = icon.replace('{height}', $element.hasClass('btn-sm') ? 18 : 28);

            // fix width
            $element.css({
                width: width,
                height: height,
                position: 'relative'
            });

            // store the current text
            $element.attr(ButtonLoaderSettings.attr, id);
            ButtonLoaderSettings.data[id] = text;

            // set state
            $element.html(icon).prop('disabled', true);
        }

        /**
         * Turn off loading
         * @param $element
         */

    }, {
        key: 'off',
        value: function off($element) {
            // renable button
            var id = $element.attr(ButtonLoaderSettings.attr);
            var text = ButtonLoaderSettings.data[id];

            $element.html(text).prop('disabled', false);
        }
    }]);

    return ButtonLoader;
}();

/* harmony default export */ __webpack_exports__["a"] = (ButtonLoader);

/***/ }),

/***/ "./assets/js/Nav.js":
/*!**************************!*\
  !*** ./assets/js/Nav.js ***!
  \**************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Nav = function () {
    function Nav() {
        _classCallCheck(this, Nav);
    }

    _createClass(Nav, null, [{
        key: 'init',
        value: function init() {
            // detect main nav menu buttons
            $('[data-menu]').on('click', function (event) {
                var $button = $(event.target);
                var menuClassName = $button.attr('data-menu');

                $('[data-menu]:not([data-menu="' + menuClassName + '"])').removeClass('on');
                $button.toggleClass('on');

                $('.h-box:not(.' + menuClassName + ')').removeClass('on');
                $('.' + menuClassName).toggleClass('on');
            });

            // Detect clicking outside to close it
            $(document).on('click', function (event) {
                var container = $('[data-menu], .h-box.on');

                if (!container.is(event.target) && container.has(event.target).length === 0) {
                    $('[data-menu].on').removeClass('on');
                    $('.h-box.on').removeClass('on');
                }
            });
        }
    }]);

    return Nav;
}();

/* harmony default export */ __webpack_exports__["a"] = (Nav);

/***/ }),

/***/ "./assets/js/Search.js":
/*!*****************************!*\
  !*** ./assets/js/Search.js ***!
  \*****************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__ButtonLoader__ = __webpack_require__(/*! ./ButtonLoader */ "./assets/js/ButtonLoader.js");
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }



var SearchSettings = {
    //Url: 'http://api.xivdb-staging.com/Search',
    Url: 'http://api.xivdb.local/Search',
    Delay: 300
};
var SearchInstance = {
    LastSearchTerm: null,
    LastSearchPage: null,

    InfiniteScrollCheck: true,
    InputTimeout: null,
    RequestId: null,
    EnterActive: false,
    AllowMoreResults: false,
    Page: 1
};
var SearchViews = {
    achievement: function achievement(data) {
        return '\n            ' + data['AchievementCategory.Name'] + ' ' + data['AchievementCategory.AchievementKind.Name'] + '\n        ';
    },

    action: function action(data) {
        return '\n            ' + (data.ClassJobLevel ? data.ClassJobLevel : '') + ' \n            ' + (data['ClassJobCategory.Name'] ? data['ClassJobCategory.Name'] : 'System') + ' \n            ' + data['ActionCategory.Name'] + '\n        ';
    },

    bnpcname: function bnpcname(data) {
        return '';
    },

    companion: function companion(data) {
        return '\n            ' + (data['Behaviour.Name'] ? data['Behaviour.Name'] : '') + '\n            ' + (data['MinionRace.Name'] ? data['MinionRace.Name'] : '') + '\n        ';
    },

    emote: function emote(data) {
        return '\n            ' + data['TextCommand.Command'] + ' ' + data['EmoteCategory.Name'] + '\n        ';
    },

    enpcresident: function enpcresident(data) {
        return '' + data.Title;
    },

    fate: function fate(data) {
        return '\n            Lv: ' + data.ClassJobLevel + ' - ' + data.ClassJobLevelMax + '\n        ';
    },

    instancecontent: function instancecontent(data) {
        return '\n            ' + data['ContentType.Name'] + '<br>\n            Sync: ' + data['ContentFinderCondition.ClassJobLevelSync'] + '\n            &nbsp;&nbsp;&nbsp;\n            Ilv: ' + (data['ContentFinderCondition.ItemLevelSync'] < 1 ? '-' : data['ContentFinderCondition.ItemLevelRequired'] + '-' + data['ContentFinderCondition.ItemLevelSync']) + ' \n        ';
    },

    item: function item(data) {
        return '\n            ' + data['ItemUICategory.Name'] + '<br>\n            ' + (data['ItemSearchCategory.Name'] ? data['ItemSearchCategory.Name'] : '') + '\n            ' + (data['ClassJobCategory.Name'] ? data['LevelEquip'] + ' ' + data['ClassJobCategory.Name'] : '') + '\n        ';
    },

    leve: function leve(data) {
        return '\n            ' + data['JournalGenre.JournalCategory.Name'] + '<br>\n            ' + data['ClassJobCategory.Name'] + ' - ' + data['JournalGenre.Name'] + '\n        ';
    },

    mount: function mount(data) {
        return '';
    },

    placename: function placename(data) {
        return '';
    },

    quest: function quest(data) {
        return '\n            ' + data['JournalGenre.JournalCategory.Name'] + '<br>\n            ' + data['JournalGenre.Name'] + '\n        ';
    },

    recipe: function recipe(data) {
        return '\n            ' + data['ClassJob.Name'] + '\n            ' + (data['SecretRecipeBook.Name'] ? '<br>' + data['SecretRecipeBook.Name'] : '') + '\n        ';
    },

    status: function status(data) {
        return '';
    },

    title: function title(data) {
        return '\n            \u2640 ' + data.NameFemale + '\n        ';
    },

    weather: function weather(data) {
        return '\n            ' + data.Description + '\n        ';
    }
};

var Search = function () {
    function Search() {
        _classCallCheck(this, Search);
    }

    _createClass(Search, null, [{
        key: 'init',
        value: function init() {
            var _this = this;

            var $input = $('.h-search-bar input');

            $input.on('keydown', function (event) {
                //clearTimeout(SearchInstance.InputTimeout);
            });

            $input.on('keyup', function (event) {
                //clearTimeout(SearchInstance.InputTimeout);

                // pressed enter, search immediately
                if (!SearchInstance.EnterActive && event.keyCode === 13) {
                    _this.search();
                    return;
                }

                // reset paging stuff
                SearchInstance.LastSearchPage = null;
                SearchInstance.Page = 1;

                // normal routine
                /*
                SearchInstance.EnterActive = false;
                SearchInstance.InputTimeout = setTimeout(() => {
                    this.search();
                }, SearchSettings.Delay);
                */
            });

            $('.sea-form').on('submit', function (event) {
                event.preventDefault();

                SearchInstance.EnterActive = false;
                _this.search();
            });

            $('.sea-more button').on('click', function (event) {
                if (SearchInstance.AllowMoreResults) {
                    SearchInstance.Page++;
                    _this.search();
                }
            });

            // todo - this should be for only logged in members
            $(window).on('scroll', function (event) {
                if (!SearchInstance.InfiniteScrollCheck || !SearchInstance.AllowMoreResults) {
                    return;
                }

                var $button = $('.sea-more > button');
                var top_of_element = $button.offset().top;
                var bottom_of_element = $button.offset().top + $button.outerHeight();
                var bottom_of_screen = $(window).scrollTop() + window.innerHeight;
                var top_of_screen = $(window).scrollTop();

                if (bottom_of_screen > top_of_element && top_of_screen < bottom_of_element) {
                    SearchInstance.InfiniteScrollCheck = false;
                    SearchInstance.Page++;
                    __WEBPACK_IMPORTED_MODULE_0__ButtonLoader__["a" /* default */].on($('.sea-more button'), 'light');
                    _this.search();
                }
            });

            // switch between filters+settings
            $('.sea-menu button').on('click', function (event) {
                var menu = $(event.target).attr('data-menu');

                $('.sea-block').removeClass('on');
                $('.sea-' + menu).addClass('on');
                $('.sea-menu .btn-primary').removeClass('btn-primary').addClass('btn-secondary');
                $(event.target).removeClass('btn-secondary').addClass('btn-primary');
            });
        }
    }, {
        key: 'search',
        value: function search() {
            var $input = $('.h-search-bar input');
            var req = {
                string: $input.val().trim(),
                string_algo: $('#searchOptStringAlgo').val().trim(),
                page: SearchInstance.Page
            };

            // don't do the same search again
            if (req.string === SearchInstance.LastSearchTerm && req.page === SearchInstance.LastSearchPage) {
                return;
            }

            SearchInstance.LastSearchTerm = req.string;
            SearchInstance.LastSearchPage = req.page;

            // always set false so it isn't fired during search
            SearchInstance.AllowMoreResults = false;
            SearchInstance.EnterActive = true;
            SearchInstance.RequestId = Math.floor(Math.random() * 9999999 + 1);

            this.request(SearchInstance.RequestId, req);
        }
    }, {
        key: 'request',
        value: function request(reqId, reqData) {
            var _this2 = this;

            if (reqData.string.length > 0) {
                $('.sea').addClass('on');
            } else {
                $('.sea').removeClass('on');
                return;
            }

            $('.sea-load').addClass('sea-load-on');

            $.ajax({
                url: SearchSettings.Url,
                data: reqData,
                success: function success(response) {
                    // only handle response if the most recent request is the response
                    if (reqId === SearchInstance.RequestId) {
                        _this2.render(response);
                    }
                },
                error: function error(a, b, c) {
                    _this2.error(a, b, c);
                },
                complete: function complete() {
                    SearchInstance.EnterActive = false;
                    $('.sea-load').removeClass('sea-load-on');
                    __WEBPACK_IMPORTED_MODULE_0__ButtonLoader__["a" /* default */].off($('.sea-more button'));
                }
            });
        }
    }, {
        key: 'render',
        value: function render(res) {
            $('.sea-top .sea-results').text(res.pagination.results_total.toLocaleString('us'));
            $('.sea-top .sea-pages').text(res.pagination.page_total.toLocaleString('us'));
            $('.sea-top .sea-ms span').text(res.ms);

            var $ui = $('.sea-res');

            // first page? empty results
            if (SearchInstance.Page === 1) {
                $ui.html('');

                // scroll to top
                window.scrollTo(0, 0);
            }

            if (SearchInstance.Page > 1) {
                $ui.append('\n                <div class="sea-page">Page: ' + SearchInstance.Page + '</div>\n            ');
            }

            // if no results
            if (SearchInstance.Page === 1 && res.pagination.results === 0) {
                $ui.html('\n                <div class="alert alert-secondary">\n                <h2>Oh no!?</h2>\n                No results for the specified search term or filters\n                </div>\n            ');
            }

            res.results.forEach(function (content) {
                if (typeof SearchViews[content._] === 'undefined') {
                    console.log('No view for: ', content._, content);
                    return;
                }

                // add level display
                var contentFigure = null;
                contentFigure = content.LevelItem ? content.LevelItem : contentFigure;
                contentFigure = content.ClassJobLevel ? content.ClassJobLevel : contentFigure;
                contentFigure = content.ClassJobLevel0 ? content.ClassJobLevel0 : contentFigure;
                contentFigure = content['RecipeLevelTable.ClassJobLevel'] ? content['RecipeLevelTable.ClassJobLevel'] : contentFigure;
                contentFigure = content['Points'] ? content['Points'] : contentFigure;
                contentFigure = content['ContentFinderCondition.ClassJobLevelRequired'] ? content['ContentFinderCondition.ClassJobLevelRequired'] : contentFigure;

                $ui.append('\n                <div class="sea-res-row sea-res-' + content.GameType + '">\n                    <div>\n                        <img src="' + content.Icon + '" class="sea-img-1">\n                        <img src="' + content.Icon + '" class="sea-img-2">\n                    </div>\n                    <div>\n                        <a href="' + content.SiteUrl.toLowerCase() + '">\n                            ' + (contentFigure ? '<em>' + contentFigure + '</em>' : '') + '\n                            <span>' + content.Name + '</span>\n                        </a>\n                        <small>(' + content.GameType + ') ' + SearchViews[content._](content).trim() + '</small>\n                    </div>\n                </div>\n            ');
            });

            console.log(res);

            // handle more button
            $('.sea-more')[res.pagination.page_next ? 'removeClass' : 'addClass']('off');
            SearchInstance.AllowMoreResults = res.pagination.page_next > 0;
            SearchInstance.InfiniteScrollCheck = res.pagination.page_next > 0;
        }
    }, {
        key: 'error',
        value: function error(a, b, c) {
            var $ui = $('.sea-res');
            $ui.html('\n            <div class="alert alert-danger">\n                <h2>Oh no!?</h2>\n                <p>' + c + '</p>\n            </div>\n        ');
        }
    }]);

    return Search;
}();

/* harmony default export */ __webpack_exports__["a"] = (Search);

/***/ }),

/***/ "./assets/js/Support.js":
/*!******************************!*\
  !*** ./assets/js/Support.js ***!
  \******************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Support = function () {
    function Support() {
        _classCallCheck(this, Support);
    }

    _createClass(Support, null, [{
        key: 'init',
        value: function init() {
            this.watchTicketPopupSubmit();
            this.watchOpenAndClose();
        }
    }, {
        key: 'watchOpenAndClose',
        value: function watchOpenAndClose() {
            var $supPop = $('.sup-pop');

            // open
            $('.btn-OpenSupportPopup').on('click', function (event) {
                $supPop.addClass('on');
                $('.h-user-in, .h-box-user').removeClass('on');
            });

            // Detect clicking outside to close it
            $(document).on('click', function (event) {
                var container = $('.sup-pop.on, .btn-OpenSupportPopup');

                if (!container.is(event.target) && container.has(event.target).length === 0) {
                    $supPop.removeClass('on');
                }
            });
        }
    }, {
        key: 'watchTicketPopupSubmit',
        value: function watchTicketPopupSubmit() {
            var $btn = $('.btn-createSupportTicket');

            $btn.on('click', function (event) {
                $btn.prop('disabled', true).text('Creating ticket ...');

                $.ajax({
                    url: '/issues/create',
                    data: $('.SupportTicketForm').serialize(),
                    success: function success(response) {
                        var $ui = $('.SupportTicketResponse');

                        if (response.error) {
                            return $ui.html('\n                            <br>\n                            <div class="alert alert-error">\n                                Sorry! Your ticket could not be created, reason: ' + response.error + '\n                            </div>\n                        ');
                        }

                        $ui.html('\n                        <br>\n                        <div class="alert alert-success alert-success-pop">\n                            Your ticket has been created! Ref: <strong>' + response.ref + '</strong><br>\n                            - <a href="/issues/' + response.id + '">Click here to view your ticket</a>\n                        </div>\n                    ');
                    }
                });
            });
        }
    }]);

    return Support;
}();

/* harmony default export */ __webpack_exports__["a"] = (Support);

/***/ }),

/***/ "./assets/js/app.js":
/*!**************************!*\
  !*** ./assets/js/app.js ***!
  \**************************/
/*! no exports provided */
/*! all exports used */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Nav__ = __webpack_require__(/*! ./Nav */ "./assets/js/Nav.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Search__ = __webpack_require__(/*! ./Search */ "./assets/js/Search.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Support__ = __webpack_require__(/*! ./Support */ "./assets/js/Support.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__Account__ = __webpack_require__(/*! ./Account */ "./assets/js/Account.js");

__WEBPACK_IMPORTED_MODULE_0__Nav__["a" /* default */].init();


__WEBPACK_IMPORTED_MODULE_1__Search__["a" /* default */].init();


__WEBPACK_IMPORTED_MODULE_2__Support__["a" /* default */].init();


__WEBPACK_IMPORTED_MODULE_3__Account__["a" /* default */].init();

/***/ })

/******/ })["default"];