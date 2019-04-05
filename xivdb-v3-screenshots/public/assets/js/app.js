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

/***/ "./assets/js/Events.js":
/*!*****************************!*\
  !*** ./assets/js/Events.js ***!
  \*****************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Events; });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Events = function () {
    function Events() {
        _classCallCheck(this, Events);
    }

    _createClass(Events, [{
        key: 'watch',
        value: function watch(Uploader, $dom) {
            var dragCounter = 0;

            // form submit
            $dom.on('submit', function (event) {
                event.preventDefault();
                event.stopPropagation();

                Uploader.post(event);
            });

            // on file change
            $dom.on('change', 'form input[type="file"]', function (event) {
                event.preventDefault();
                event.stopPropagation();

                Uploader.post(event);
            });

            // drag enter
            $dom.on('dragenter', function (event) {
                event.preventDefault();
                event.stopPropagation();

                dragCounter++;
                if (dragCounter === 1) {
                    console.log('drag enter');
                }
            });

            // drag leave
            $dom.on('dragleave', function (event) {
                event.preventDefault();
                event.stopPropagation();

                dragCounter--;
                if (dragCounter === 0) {
                    console.log('drag leave');
                }
            });

            // drag over (this is important)
            $dom.on('dragover', function (event) {
                event.preventDefault();
                event.stopPropagation();
            });

            // drop
            $dom.on('drop', function (event) {
                console.log('dropped');
                event.preventDefault();
                event.stopPropagation();

                Uploader.post(event);
            });

            console.log('Watching', $dom);
        }
    }, {
        key: 'push',
        value: function push(name, data) {
            if (typeof MS_SCREENSHOTS === 'undefined') {
                return;
            }

            if (typeof MS_SCREENSHOTS[name] === 'undefined') {
                return;
            }

            MS_SCREENSHOTS[name](data);
        }
    }]);

    return Events;
}();



/***/ }),

/***/ "./assets/js/Instance.js":
/*!*******************************!*\
  !*** ./assets/js/Instance.js ***!
  \*******************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Instance; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Settings__ = __webpack_require__(/*! ./Settings */ "./assets/js/Settings.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Render__ = __webpack_require__(/*! ./Render */ "./assets/js/Render.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Events__ = __webpack_require__(/*! ./Events */ "./assets/js/Events.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__Uploader__ = __webpack_require__(/*! ./Uploader */ "./assets/js/Uploader.js");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }






var Instance = function Instance(id, dom) {
    var _this = this;

    _classCallCheck(this, Instance);

    this.id = id;
    this.$dom = $(dom);

    // dependencies
    this.Render = new __WEBPACK_IMPORTED_MODULE_1__Render__["a" /* default */]();
    this.Events = new __WEBPACK_IMPORTED_MODULE_2__Events__["a" /* default */]();
    this.Uploader = new __WEBPACK_IMPORTED_MODULE_3__Uploader__["a" /* default */]();

    // render
    this.$dom.html(this.Render.getFileSelectHtml());

    // watch for events
    this.Events.watch(this.Uploader, this.$dom);

    // set uploader callbacks
    this.Uploader.form({
        idUnique: this.id,
        userId: 'hello-world'
    }).on({
        start: function start() {
            _this.Events.push('uploadStart');
        },
        error: function error(response, code, message) {
            _this.Events.push('uploadError', { response: response, code: code, message: message });
        },
        success: function success(response) {
            _this.Events.push('uploadSuccess', { response: response });

            console.log(__WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].MS_SS_LISTS);

            // render images again
            __WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].MS_SS_LISTS[_this.id].render();
        },
        complete: function complete() {
            _this.Uploader.uploading = false;
            _this.$dom.find('input').val('');
            _this.Events.push('uploadComplete');
        },
        progress: function progress(response) {
            _this.Events.push('uploadProgress', { response: response });
        },
        load: function load(response) {
            _this.Events.push('uploadLoad', { response: response });
        }
    });
};



/***/ }),

/***/ "./assets/js/List.js":
/*!***************************!*\
  !*** ./assets/js/List.js ***!
  \***************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return List; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Settings__ = __webpack_require__(/*! ./Settings */ "./assets/js/Settings.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Render__ = __webpack_require__(/*! ./Render */ "./assets/js/Render.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Events__ = __webpack_require__(/*! ./Events */ "./assets/js/Events.js");
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }





var List = function () {
    function List(id, dom) {
        var _this = this;

        _classCallCheck(this, List);

        this.id = id;
        this.$dom = $(dom);

        // dependencies
        this.Render = new __WEBPACK_IMPORTED_MODULE_1__Render__["a" /* default */]();
        this.Events = new __WEBPACK_IMPORTED_MODULE_2__Events__["a" /* default */]();

        // render pictures
        this.render();

        $('html').on('click', '.xivdb-screenshots-delete', function (event) {
            var id = $(event.currentTarget).attr('id');
            $.ajax({
                url: __WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].ENDPOINT + '/' + id,
                method: 'DELETE',
                success: function success(response) {
                    _this.Events.push('deleteSuccess', { response: response });
                    _this.render();
                },
                error: function error(response) {
                    _this.Events.push('deleteError', { response: response, code: code, message: message });
                },
                complete: function complete() {
                    _this.Events.push('deleteComplete');
                }
            });
        });
    }

    _createClass(List, [{
        key: 'render',
        value: function render() {
            var _this2 = this;

            // get images
            $.ajax({
                url: __WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].ENDPOINT + '/list',
                data: {
                    'idUnique': this.id
                },
                success: function success(response) {
                    _this2.Events.push('listSuccess', { response: response });

                    if (typeof response.error === 'undefined') {
                        _this2.Events.push('listRendering');
                        _this2.renderImages(response);
                    } else {
                        _this2.Events.push('listEmpty');
                        _this2.renderEmpty(response.error);
                    }
                },
                error: function error(response) {
                    _this2.Events.push('listError', { response: response, code: code, message: message });
                },
                complete: function complete() {
                    _this2.Events.push('listComplete');
                }
            });
        }
    }, {
        key: 'renderImages',
        value: function renderImages(images) {
            this.$dom.html(this.Render.getScreenshotListHtml());

            for (var i in images) {
                var img = images[i],
                    html = this.Render.getScreenshotEmbedHtml(img);

                this.$dom.find('.xivdb-screenshots-display').append(html);
            }
        }
    }, {
        key: 'renderEmpty',
        value: function renderEmpty(code) {
            this.$dom.html(this.Render.getScreenshotEmptyHtml(code));
        }
    }]);

    return List;
}();



/***/ }),

/***/ "./assets/js/Render.js":
/*!*****************************!*\
  !*** ./assets/js/Render.js ***!
  \*****************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Render; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Settings__ = __webpack_require__(/*! ./Settings */ "./assets/js/Settings.js");
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }



var Render = function () {
    function Render() {
        _classCallCheck(this, Render);
    }

    _createClass(Render, [{
        key: 'getFileSelectHtml',
        value: function getFileSelectHtml() {
            return '\n            <form class="xivdb-screenshots-container">\n                <div class="xivdb-screenshots-fields active">\n                    <div class="xivdb-screenshots-input">\n                        <label for="file" class="btn">Choose a file</label>\n                        <input type="file" id="file">\n                    </div>\n                    <div class="xivdb-screenshots-droptext">\n                        Or drop your file here\n                    </div>\n                </div>\n                <div class="xivdb-screenshots-state">\n                    <div class="xivdb-screenshots-title">Uploading...</div>\n                    <div class="xivdb-screenshots-progress"><span style="width:0%;"></span></div>\n                </div>\n            </form>\n        ';
        }
    }, {
        key: 'getScreenshotListHtml',
        value: function getScreenshotListHtml() {
            return '\n            <div class="xivdb-screenshots-display"></div>\n        ';
        }
    }, {
        key: 'getScreenshotEmbedHtml',
        value: function getScreenshotEmbedHtml(image) {
            var imageUrl = __WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].ENDPOINT + '/' + image.id;

            return '\n            <span class="xivdb-screenshots-embed">\n                <a href="' + imageUrl + '" target="_blank">\n                    <img src="' + imageUrl + '" class="xivdb-screenshots-img" height="80">\n                </a>\n                <button class="xivdb-screenshots-delete" id="' + image.id + '">Delete</button>\n            </span>\n        ';
        }
    }, {
        key: 'getScreenshotEmptyHtml',
        value: function getScreenshotEmptyHtml(code) {
            return '\n            <div class="xivdb-screenshots-empty">[' + code + ']</div>\n        ';
        }
    }]);

    return Render;
}();



/***/ }),

/***/ "./assets/js/Settings.js":
/*!*******************************!*\
  !*** ./assets/js/Settings.js ***!
  \*******************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Settings; });
var Settings = {
    ENDPOINT: typeof MS_SCREENSHOTS_ENDPOINT !== 'undefined' ? MS_SCREENSHOTS_ENDPOINT : 'http://127.0.0.1:8000',

    INSTANCE_CLASS_NAME: 'xivdb-screenshots',
    LIST_CLASS_NAME: 'xivdb-screenshots-list',

    MAX_SIZE: 1024 * 1024 * 15,
    ALLOWED_TYPES: ['image/png', 'image/gif', 'image/bmp', 'image/jpg', 'image/jpeg'],

    MS_SS_INSTANCES: {},
    MS_SS_LISTS: {}
};



/***/ }),

/***/ "./assets/js/Uploader.js":
/*!*******************************!*\
  !*** ./assets/js/Uploader.js ***!
  \*******************************/
/*! exports provided: default */
/*! exports used: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Uploader; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Settings__ = __webpack_require__(/*! ./Settings */ "./assets/js/Settings.js");
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }



var Uploader = function () {
    function Uploader() {
        _classCallCheck(this, Uploader);

        this.uploading = false;
        this.events = {};
        this.formdata = {};
    }

    _createClass(Uploader, [{
        key: 'form',
        value: function form(_form) {
            this.formdata = _form;
            return this;
        }
    }, {
        key: 'on',
        value: function on(callbacks) {
            this.events = callbacks;
            return this;
        }
    }, {
        key: 'post',
        value: function post(event) {
            if (this.uploading) {
                return;
            }

            // grab the correct files instance
            var dt = event.dataTransfer || event.originalEvent && event.originalEvent.dataTransfer;
            var files = event.target.files || dt && dt.files;

            console.log(event);

            // Convert files to array
            files = $.grep(files, function (a, b) {
                return a != b;
            });

            // get first file (not supporting multi at this time)
            var file = files[0];

            console.log(file);

            // check for errors
            var error = false;
            if (error = this.invalid(file)) {
                this.uploading = false;
                return this.events.error(error);
            }

            // upload
            this.upload(file);
        }
    }, {
        key: 'upload',
        value: function upload(file) {
            var _this = this;

            // Open a reader
            var reader = new FileReader();

            // On reader load
            reader.onload = function (temp) {
                _this.events.start();

                return function (event) {
                    // new image
                    var img = new Image();
                    img.src = event.target.result;

                    // On image load
                    img.onload = function () {
                        _this.uploading = true;

                        // create form data
                        var formData = new FormData();
                        formData.append('media', file);

                        // append form data
                        for (var i in _this.formdata) {
                            var value = _this.formdata[i];
                            formData.append(i, value);
                        }

                        // upload
                        $.ajax({
                            xhr: function xhr() {
                                var xhr = new window.XMLHttpRequest();

                                xhr.upload.addEventListener('progress', function (event) {
                                    var percent = (event.loaded / event.total * 100).toFixed(2);
                                    _this.events.progress(percent);
                                }, false);

                                xhr.upload.addEventListener('load', function (event) {
                                    _this.events.load(file);
                                }, false);

                                return xhr;
                            },
                            url: __WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].ENDPOINT,
                            data: formData,
                            type: 'POST',
                            enctype: 'multipart/form-data',
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: _this.events.success,
                            error: _this.events.error,
                            complete: _this.events.complete
                        });
                    };
                };
            }(file);

            // Read in the image file as data.
            reader.readAsDataURL(file);
        }

        /**
         * Check if a file is invalid or not
         *
         * @param file
         * @returns {*}
         */

    }, {
        key: 'invalid',
        value: function invalid(file) {
            if (typeof file === 'undefined') {
                return 'File entity invalid';
            }

            if (file.size > __WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].MAX_SIZE) {
                return 'File size is too big, please keep it below 15mb';
            }

            if (__WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].ALLOWED_TYPES.indexOf(file.type) === -1) {
                return file.type + ' is an invalid file type';
            }

            return false;
        }
    }]);

    return Uploader;
}();



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
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Settings__ = __webpack_require__(/*! ./Settings */ "./assets/js/Settings.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Instance__ = __webpack_require__(/*! ./Instance */ "./assets/js/Instance.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__List__ = __webpack_require__(/*! ./List */ "./assets/js/List.js");




// grab all instances
$('.' + __WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].INSTANCE_CLASS_NAME).each(function (i, dom) {
    var id = $(dom).attr('id');
    __WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].MS_SS_INSTANCES[id] = new __WEBPACK_IMPORTED_MODULE_1__Instance__["a" /* default */](id, dom);
});

// grab all instances
$('.' + __WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].LIST_CLASS_NAME).each(function (i, dom) {
    var id = $(dom).attr('id');
    __WEBPACK_IMPORTED_MODULE_0__Settings__["a" /* default */].MS_SS_LISTS[id] = new __WEBPACK_IMPORTED_MODULE_2__List__["a" /* default */](id, dom);
});

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgNGNkNmI5MTY0MDc3ZDQ3ZDI5YjgiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL0V2ZW50cy5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvSW5zdGFuY2UuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL0xpc3QuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL1JlbmRlci5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvU2V0dGluZ3MuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL1VwbG9hZGVyLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9qcy9hcHAuanMiXSwibmFtZXMiOlsiRXZlbnRzIiwiVXBsb2FkZXIiLCIkZG9tIiwiZHJhZ0NvdW50ZXIiLCJvbiIsImV2ZW50IiwicHJldmVudERlZmF1bHQiLCJzdG9wUHJvcGFnYXRpb24iLCJwb3N0IiwiY29uc29sZSIsImxvZyIsIm5hbWUiLCJkYXRhIiwiTVNfU0NSRUVOU0hPVFMiLCJJbnN0YW5jZSIsImlkIiwiZG9tIiwiJCIsIlJlbmRlciIsImh0bWwiLCJnZXRGaWxlU2VsZWN0SHRtbCIsIndhdGNoIiwiZm9ybSIsImlkVW5pcXVlIiwidXNlcklkIiwic3RhcnQiLCJwdXNoIiwiZXJyb3IiLCJyZXNwb25zZSIsImNvZGUiLCJtZXNzYWdlIiwic3VjY2VzcyIsIlNldHRpbmdzIiwiTVNfU1NfTElTVFMiLCJyZW5kZXIiLCJjb21wbGV0ZSIsInVwbG9hZGluZyIsImZpbmQiLCJ2YWwiLCJwcm9ncmVzcyIsImxvYWQiLCJMaXN0IiwiY3VycmVudFRhcmdldCIsImF0dHIiLCJhamF4IiwidXJsIiwiRU5EUE9JTlQiLCJtZXRob2QiLCJyZW5kZXJJbWFnZXMiLCJyZW5kZXJFbXB0eSIsImltYWdlcyIsImdldFNjcmVlbnNob3RMaXN0SHRtbCIsImkiLCJpbWciLCJnZXRTY3JlZW5zaG90RW1iZWRIdG1sIiwiYXBwZW5kIiwiZ2V0U2NyZWVuc2hvdEVtcHR5SHRtbCIsImltYWdlIiwiaW1hZ2VVcmwiLCJNU19TQ1JFRU5TSE9UU19FTkRQT0lOVCIsIklOU1RBTkNFX0NMQVNTX05BTUUiLCJMSVNUX0NMQVNTX05BTUUiLCJNQVhfU0laRSIsIkFMTE9XRURfVFlQRVMiLCJNU19TU19JTlNUQU5DRVMiLCJldmVudHMiLCJmb3JtZGF0YSIsImNhbGxiYWNrcyIsImR0IiwiZGF0YVRyYW5zZmVyIiwib3JpZ2luYWxFdmVudCIsImZpbGVzIiwidGFyZ2V0IiwiZ3JlcCIsImEiLCJiIiwiZmlsZSIsImludmFsaWQiLCJ1cGxvYWQiLCJyZWFkZXIiLCJGaWxlUmVhZGVyIiwib25sb2FkIiwiSW1hZ2UiLCJzcmMiLCJyZXN1bHQiLCJmb3JtRGF0YSIsIkZvcm1EYXRhIiwidmFsdWUiLCJ4aHIiLCJ3aW5kb3ciLCJYTUxIdHRwUmVxdWVzdCIsImFkZEV2ZW50TGlzdGVuZXIiLCJwZXJjZW50IiwibG9hZGVkIiwidG90YWwiLCJ0b0ZpeGVkIiwidHlwZSIsImVuY3R5cGUiLCJjYWNoZSIsImNvbnRlbnRUeXBlIiwicHJvY2Vzc0RhdGEiLCJyZWFkQXNEYXRhVVJMIiwic2l6ZSIsImluZGV4T2YiLCJlYWNoIl0sIm1hcHBpbmdzIjoiO0FBQUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLG1DQUEyQiwwQkFBMEIsRUFBRTtBQUN2RCx5Q0FBaUMsZUFBZTtBQUNoRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQSw4REFBc0QsK0RBQStEOztBQUVySDtBQUNBOztBQUVBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7SUM3RE1BLE07Ozs7Ozs7OEJBRUlDLFEsRUFBVUMsSSxFQUNoQjtBQUNJLGdCQUFJQyxjQUFjLENBQWxCOztBQUVBO0FBQ0FELGlCQUFLRSxFQUFMLENBQVEsUUFBUixFQUFrQixpQkFBUztBQUN2QkMsc0JBQU1DLGNBQU47QUFDQUQsc0JBQU1FLGVBQU47O0FBRUFOLHlCQUFTTyxJQUFULENBQWNILEtBQWQ7QUFDSCxhQUxEOztBQU9BO0FBQ0FILGlCQUFLRSxFQUFMLENBQVEsUUFBUixFQUFrQix5QkFBbEIsRUFBNkMsaUJBQVM7QUFDbERDLHNCQUFNQyxjQUFOO0FBQ0FELHNCQUFNRSxlQUFOOztBQUVBTix5QkFBU08sSUFBVCxDQUFjSCxLQUFkO0FBQ0gsYUFMRDs7QUFPQTtBQUNBSCxpQkFBS0UsRUFBTCxDQUFRLFdBQVIsRUFBcUIsaUJBQVM7QUFDMUJDLHNCQUFNQyxjQUFOO0FBQ0FELHNCQUFNRSxlQUFOOztBQUVBSjtBQUNBLG9CQUFJQSxnQkFBZ0IsQ0FBcEIsRUFBdUI7QUFDbkJNLDRCQUFRQyxHQUFSLENBQVksWUFBWjtBQUNIO0FBQ0osYUFSRDs7QUFVQTtBQUNBUixpQkFBS0UsRUFBTCxDQUFRLFdBQVIsRUFBcUIsaUJBQVM7QUFDMUJDLHNCQUFNQyxjQUFOO0FBQ0FELHNCQUFNRSxlQUFOOztBQUVBSjtBQUNBLG9CQUFJQSxnQkFBZ0IsQ0FBcEIsRUFBdUI7QUFDbkJNLDRCQUFRQyxHQUFSLENBQVksWUFBWjtBQUNIO0FBQ0osYUFSRDs7QUFVQTtBQUNBUixpQkFBS0UsRUFBTCxDQUFRLFVBQVIsRUFBb0IsaUJBQVM7QUFDekJDLHNCQUFNQyxjQUFOO0FBQ0FELHNCQUFNRSxlQUFOO0FBQ0gsYUFIRDs7QUFLQTtBQUNBTCxpQkFBS0UsRUFBTCxDQUFRLE1BQVIsRUFBZ0IsaUJBQVM7QUFDckJLLHdCQUFRQyxHQUFSLENBQVksU0FBWjtBQUNBTCxzQkFBTUMsY0FBTjtBQUNBRCxzQkFBTUUsZUFBTjs7QUFFQU4seUJBQVNPLElBQVQsQ0FBY0gsS0FBZDtBQUNILGFBTkQ7O0FBUUFJLG9CQUFRQyxHQUFSLENBQVksVUFBWixFQUF3QlIsSUFBeEI7QUFFSDs7OzZCQUVJUyxJLEVBQU1DLEksRUFDWDtBQUNJLGdCQUFJLE9BQU9DLGNBQVAsS0FBMEIsV0FBOUIsRUFBMkM7QUFDdkM7QUFDSDs7QUFFRCxnQkFBSSxPQUFPQSxlQUFlRixJQUFmLENBQVAsS0FBZ0MsV0FBcEMsRUFBaUQ7QUFDN0M7QUFDSDs7QUFFREUsMkJBQWVGLElBQWYsRUFBcUJDLElBQXJCO0FBQ0g7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FDMUVMO0FBQ0E7QUFDQTtBQUNBOztJQUVNRSxRLEdBRUYsa0JBQVlDLEVBQVosRUFBZ0JDLEdBQWhCLEVBQXFCO0FBQUE7O0FBQUE7O0FBQ2pCLFNBQUtELEVBQUwsR0FBVUEsRUFBVjtBQUNBLFNBQUtiLElBQUwsR0FBWWUsRUFBRUQsR0FBRixDQUFaOztBQUVBO0FBQ0EsU0FBS0UsTUFBTCxHQUFjLElBQUksd0RBQUosRUFBZDtBQUNBLFNBQUtsQixNQUFMLEdBQWMsSUFBSSx3REFBSixFQUFkO0FBQ0EsU0FBS0MsUUFBTCxHQUFnQixJQUFJLDBEQUFKLEVBQWhCOztBQUVBO0FBQ0EsU0FBS0MsSUFBTCxDQUFVaUIsSUFBVixDQUNJLEtBQUtELE1BQUwsQ0FBWUUsaUJBQVosRUFESjs7QUFJQTtBQUNBLFNBQUtwQixNQUFMLENBQVlxQixLQUFaLENBQ0ksS0FBS3BCLFFBRFQsRUFFSSxLQUFLQyxJQUZUOztBQUtBO0FBQ0EsU0FBS0QsUUFBTCxDQUNLcUIsSUFETCxDQUNVO0FBQ0ZDLGtCQUFVLEtBQUtSLEVBRGI7QUFFRlMsZ0JBQVE7QUFGTixLQURWLEVBS0twQixFQUxMLENBS1E7QUFDQXFCLGVBQU8saUJBQU07QUFDVCxrQkFBS3pCLE1BQUwsQ0FBWTBCLElBQVosQ0FBaUIsYUFBakI7QUFDSCxTQUhEO0FBSUFDLGVBQU8sZUFBQ0MsUUFBRCxFQUFXQyxJQUFYLEVBQWlCQyxPQUFqQixFQUE2QjtBQUNoQyxrQkFBSzlCLE1BQUwsQ0FBWTBCLElBQVosQ0FBaUIsYUFBakIsRUFBZ0MsRUFBRUUsa0JBQUYsRUFBWUMsVUFBWixFQUFrQkMsZ0JBQWxCLEVBQWhDO0FBQ0gsU0FORDtBQU9BQyxpQkFBUywyQkFBWTtBQUNqQixrQkFBSy9CLE1BQUwsQ0FBWTBCLElBQVosQ0FBaUIsZUFBakIsRUFBa0MsRUFBRUUsa0JBQUYsRUFBbEM7O0FBRUFuQixvQkFBUUMsR0FBUixDQUFZLDBEQUFBc0IsQ0FBU0MsV0FBckI7O0FBRUE7QUFDQUQsWUFBQSwwREFBQUEsQ0FBU0MsV0FBVCxDQUFxQixNQUFLbEIsRUFBMUIsRUFBOEJtQixNQUE5QjtBQUNILFNBZEQ7QUFlQUMsa0JBQVUsb0JBQU07QUFDWixrQkFBS2xDLFFBQUwsQ0FBY21DLFNBQWQsR0FBMEIsS0FBMUI7QUFDQSxrQkFBS2xDLElBQUwsQ0FBVW1DLElBQVYsQ0FBZSxPQUFmLEVBQXdCQyxHQUF4QixDQUE0QixFQUE1QjtBQUNBLGtCQUFLdEMsTUFBTCxDQUFZMEIsSUFBWixDQUFpQixnQkFBakI7QUFDSCxTQW5CRDtBQW9CQWEsa0JBQVUsNEJBQVk7QUFDbEIsa0JBQUt2QyxNQUFMLENBQVkwQixJQUFaLENBQWlCLGdCQUFqQixFQUFtQyxFQUFFRSxrQkFBRixFQUFuQztBQUNILFNBdEJEO0FBdUJBWSxjQUFNLHdCQUFZO0FBQ2Qsa0JBQUt4QyxNQUFMLENBQVkwQixJQUFaLENBQWlCLFlBQWpCLEVBQStCLEVBQUVFLGtCQUFGLEVBQS9CO0FBQ0g7QUF6QkQsS0FMUjtBQWdDSCxDOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQzVETDtBQUNBO0FBQ0E7O0lBRU1hLEk7QUFFRixrQkFBWTFCLEVBQVosRUFBZ0JDLEdBQWhCLEVBQXFCO0FBQUE7O0FBQUE7O0FBQ2pCLGFBQUtELEVBQUwsR0FBVUEsRUFBVjtBQUNBLGFBQUtiLElBQUwsR0FBWWUsRUFBRUQsR0FBRixDQUFaOztBQUVBO0FBQ0EsYUFBS0UsTUFBTCxHQUFjLElBQUksd0RBQUosRUFBZDtBQUNBLGFBQUtsQixNQUFMLEdBQWMsSUFBSSx3REFBSixFQUFkOztBQUVBO0FBQ0EsYUFBS2tDLE1BQUw7O0FBRUFqQixVQUFFLE1BQUYsRUFBVWIsRUFBVixDQUFhLE9BQWIsRUFBc0IsMkJBQXRCLEVBQW1ELGlCQUFTO0FBQ3hELGdCQUFNVyxLQUFLRSxFQUFFWixNQUFNcUMsYUFBUixFQUF1QkMsSUFBdkIsQ0FBNEIsSUFBNUIsQ0FBWDtBQUNBMUIsY0FBRTJCLElBQUYsQ0FBTztBQUNIQyxxQkFBSywwREFBQWIsQ0FBU2MsUUFBVCxHQUFvQixHQUFwQixHQUEwQi9CLEVBRDVCO0FBRUhnQyx3QkFBUSxRQUZMO0FBR0hoQix5QkFBUywyQkFBWTtBQUNqQiwwQkFBSy9CLE1BQUwsQ0FBWTBCLElBQVosQ0FBaUIsZUFBakIsRUFBa0MsRUFBRUUsa0JBQUYsRUFBbEM7QUFDQSwwQkFBS00sTUFBTDtBQUNILGlCQU5FO0FBT0hQLHVCQUFPLHlCQUFZO0FBQ2YsMEJBQUszQixNQUFMLENBQVkwQixJQUFaLENBQWlCLGFBQWpCLEVBQWdDLEVBQUVFLGtCQUFGLEVBQVlDLFVBQVosRUFBa0JDLGdCQUFsQixFQUFoQztBQUNILGlCQVRFO0FBVUhLLDBCQUFVLG9CQUFNO0FBQ1osMEJBQUtuQyxNQUFMLENBQVkwQixJQUFaLENBQWlCLGdCQUFqQjtBQUNIO0FBWkUsYUFBUDtBQWNILFNBaEJEO0FBaUJIOzs7O2lDQUdEO0FBQUE7O0FBQ0k7QUFDQVQsY0FBRTJCLElBQUYsQ0FBTztBQUNIQyxxQkFBSywwREFBQWIsQ0FBU2MsUUFBVCxHQUFvQixPQUR0QjtBQUVIbEMsc0JBQU07QUFDRixnQ0FBWSxLQUFLRztBQURmLGlCQUZIO0FBS0hnQix5QkFBUywyQkFBWTtBQUNqQiwyQkFBSy9CLE1BQUwsQ0FBWTBCLElBQVosQ0FBaUIsYUFBakIsRUFBZ0MsRUFBRUUsa0JBQUYsRUFBaEM7O0FBRUEsd0JBQUksT0FBT0EsU0FBU0QsS0FBaEIsS0FBMEIsV0FBOUIsRUFBMkM7QUFDdkMsK0JBQUszQixNQUFMLENBQVkwQixJQUFaLENBQWlCLGVBQWpCO0FBQ0EsK0JBQUtzQixZQUFMLENBQWtCcEIsUUFBbEI7QUFDSCxxQkFIRCxNQUdPO0FBQ0gsK0JBQUs1QixNQUFMLENBQVkwQixJQUFaLENBQWlCLFdBQWpCO0FBQ0EsK0JBQUt1QixXQUFMLENBQWlCckIsU0FBU0QsS0FBMUI7QUFDSDtBQUNKLGlCQWZFO0FBZ0JIQSx1QkFBTyx5QkFBWTtBQUNmLDJCQUFLM0IsTUFBTCxDQUFZMEIsSUFBWixDQUFpQixXQUFqQixFQUE4QixFQUFFRSxrQkFBRixFQUFZQyxVQUFaLEVBQWtCQyxnQkFBbEIsRUFBOUI7QUFDSCxpQkFsQkU7QUFtQkhLLDBCQUFVLG9CQUFNO0FBQ1osMkJBQUtuQyxNQUFMLENBQVkwQixJQUFaLENBQWlCLGNBQWpCO0FBQ0g7QUFyQkUsYUFBUDtBQXVCSDs7O3FDQUVZd0IsTSxFQUNiO0FBQ0ksaUJBQUtoRCxJQUFMLENBQVVpQixJQUFWLENBQ0ksS0FBS0QsTUFBTCxDQUFZaUMscUJBQVosRUFESjs7QUFJQSxpQkFBSSxJQUFJQyxDQUFSLElBQWFGLE1BQWIsRUFBcUI7QUFDakIsb0JBQUlHLE1BQU1ILE9BQU9FLENBQVAsQ0FBVjtBQUFBLG9CQUNJakMsT0FBTyxLQUFLRCxNQUFMLENBQVlvQyxzQkFBWixDQUFtQ0QsR0FBbkMsQ0FEWDs7QUFHQSxxQkFBS25ELElBQUwsQ0FBVW1DLElBQVYsQ0FBZSw0QkFBZixFQUE2Q2tCLE1BQTdDLENBQW9EcEMsSUFBcEQ7QUFDSDtBQUNKOzs7b0NBRVdVLEksRUFDWjtBQUNJLGlCQUFLM0IsSUFBTCxDQUFVaUIsSUFBVixDQUNJLEtBQUtELE1BQUwsQ0FBWXNDLHNCQUFaLENBQW1DM0IsSUFBbkMsQ0FESjtBQUdIOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FDbkZMOztJQUVNWCxNOzs7Ozs7OzRDQUdGO0FBQ0k7QUFpQkg7OztnREFHRDtBQUNJO0FBR0g7OzsrQ0FFc0J1QyxLLEVBQ3ZCO0FBQ0ksZ0JBQUlDLFdBQVcsMERBQUExQixDQUFTYyxRQUFULEdBQW9CLEdBQXBCLEdBQTBCVyxNQUFNMUMsRUFBL0M7O0FBRUEsdUdBRW1CMkMsUUFGbkIsMERBR3dCQSxRQUh4Qix5SUFLdURELE1BQU0xQyxFQUw3RDtBQVFIOzs7K0NBRXNCYyxJLEVBQ3ZCO0FBQ0ksNEVBQzRDQSxJQUQ1QztBQUdIOzs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FDbkRMO0FBQUEsSUFBTUcsV0FBVztBQUNiYyxjQUFXLE9BQU9hLHVCQUFQLEtBQW1DLFdBQXBDLEdBQ0lBLHVCQURKLEdBRUksdUJBSEQ7O0FBS2JDLHlCQUFxQixtQkFMUjtBQU1iQyxxQkFBaUIsd0JBTko7O0FBUWJDLGNBQVcsT0FBTyxJQUFQLEdBQWMsRUFSWjtBQVNiQyxtQkFBZSxDQUNYLFdBRFcsRUFFWCxXQUZXLEVBR1gsV0FIVyxFQUlYLFdBSlcsRUFLWCxZQUxXLENBVEY7O0FBaUJiQyxxQkFBaUIsRUFqQko7QUFrQmIvQixpQkFBYTtBQWxCQSxDQUFqQjs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FDQUE7O0lBRU1oQyxRO0FBRUYsd0JBQ0E7QUFBQTs7QUFDSSxhQUFLbUMsU0FBTCxHQUFpQixLQUFqQjtBQUNBLGFBQUs2QixNQUFMLEdBQWMsRUFBZDtBQUNBLGFBQUtDLFFBQUwsR0FBZ0IsRUFBaEI7QUFDSDs7Ozs2QkFFSTVDLEssRUFDTDtBQUNJLGlCQUFLNEMsUUFBTCxHQUFnQjVDLEtBQWhCO0FBQ0EsbUJBQU8sSUFBUDtBQUNIOzs7MkJBRUU2QyxTLEVBQ0g7QUFDSSxpQkFBS0YsTUFBTCxHQUFjRSxTQUFkO0FBQ0EsbUJBQU8sSUFBUDtBQUNIOzs7NkJBRUk5RCxLLEVBQ0w7QUFDSSxnQkFBSSxLQUFLK0IsU0FBVCxFQUFvQjtBQUNoQjtBQUNIOztBQUVEO0FBQ0EsZ0JBQUlnQyxLQUFLL0QsTUFBTWdFLFlBQU4sSUFBdUJoRSxNQUFNaUUsYUFBTixJQUF1QmpFLE1BQU1pRSxhQUFOLENBQW9CRCxZQUEzRTtBQUNBLGdCQUFJRSxRQUFRbEUsTUFBTW1FLE1BQU4sQ0FBYUQsS0FBYixJQUF1QkgsTUFBTUEsR0FBR0csS0FBNUM7O0FBRUE5RCxvQkFBUUMsR0FBUixDQUFZTCxLQUFaOztBQUVBO0FBQ0FrRSxvQkFBUXRELEVBQUV3RCxJQUFGLENBQU9GLEtBQVAsRUFBYyxVQUFDRyxDQUFELEVBQUlDLENBQUosRUFBVTtBQUM1Qix1QkFBT0QsS0FBS0MsQ0FBWjtBQUNILGFBRk8sQ0FBUjs7QUFJQTtBQUNBLGdCQUFJQyxPQUFPTCxNQUFNLENBQU4sQ0FBWDs7QUFFQTlELG9CQUFRQyxHQUFSLENBQVlrRSxJQUFaOztBQUVBO0FBQ0EsZ0JBQUlqRCxRQUFRLEtBQVo7QUFDQSxnQkFBSUEsUUFBUSxLQUFLa0QsT0FBTCxDQUFhRCxJQUFiLENBQVosRUFBZ0M7QUFDNUIscUJBQUt4QyxTQUFMLEdBQWlCLEtBQWpCO0FBQ0EsdUJBQU8sS0FBSzZCLE1BQUwsQ0FBWXRDLEtBQVosQ0FBa0JBLEtBQWxCLENBQVA7QUFDSDs7QUFFRDtBQUNBLGlCQUFLbUQsTUFBTCxDQUFZRixJQUFaO0FBQ0g7OzsrQkFFTUEsSSxFQUNQO0FBQUE7O0FBQ0k7QUFDQSxnQkFBSUcsU0FBUyxJQUFJQyxVQUFKLEVBQWI7O0FBRUE7QUFDQUQsbUJBQU9FLE1BQVAsR0FBaUIsZ0JBQVE7QUFDckIsc0JBQUtoQixNQUFMLENBQVl4QyxLQUFaOztBQUVBLHVCQUFPLGlCQUFTO0FBQ1o7QUFDQSx3QkFBSTRCLE1BQU0sSUFBSTZCLEtBQUosRUFBVjtBQUNBN0Isd0JBQUk4QixHQUFKLEdBQVU5RSxNQUFNbUUsTUFBTixDQUFhWSxNQUF2Qjs7QUFFQTtBQUNBL0Isd0JBQUk0QixNQUFKLEdBQWEsWUFBTTtBQUNmLDhCQUFLN0MsU0FBTCxHQUFpQixJQUFqQjs7QUFFQTtBQUNBLDRCQUFJaUQsV0FBVyxJQUFJQyxRQUFKLEVBQWY7QUFDQUQsaUNBQVM5QixNQUFULENBQWdCLE9BQWhCLEVBQXlCcUIsSUFBekI7O0FBRUE7QUFDQSw2QkFBSSxJQUFJeEIsQ0FBUixJQUFhLE1BQUtjLFFBQWxCLEVBQTRCO0FBQ3hCLGdDQUFJcUIsUUFBUSxNQUFLckIsUUFBTCxDQUFjZCxDQUFkLENBQVo7QUFDQWlDLHFDQUFTOUIsTUFBVCxDQUFnQkgsQ0FBaEIsRUFBbUJtQyxLQUFuQjtBQUNIOztBQUVEO0FBQ0F0RSwwQkFBRTJCLElBQUYsQ0FBTztBQUNINEMsaUNBQUssZUFBTTtBQUNQLG9DQUFJQSxNQUFNLElBQUlDLE9BQU9DLGNBQVgsRUFBVjs7QUFFQUYsb0NBQUlWLE1BQUosQ0FBV2EsZ0JBQVgsQ0FBNEIsVUFBNUIsRUFBd0MsaUJBQVM7QUFDN0Msd0NBQUlDLFVBQVUsQ0FBQ3ZGLE1BQU13RixNQUFOLEdBQWV4RixNQUFNeUYsS0FBckIsR0FBNkIsR0FBOUIsRUFBbUNDLE9BQW5DLENBQTJDLENBQTNDLENBQWQ7QUFDQSwwQ0FBSzlCLE1BQUwsQ0FBWTFCLFFBQVosQ0FBcUJxRCxPQUFyQjtBQUNILGlDQUhELEVBR0csS0FISDs7QUFLQUosb0NBQUlWLE1BQUosQ0FBV2EsZ0JBQVgsQ0FBNEIsTUFBNUIsRUFBb0MsaUJBQVM7QUFDekMsMENBQUsxQixNQUFMLENBQVl6QixJQUFaLENBQWlCb0MsSUFBakI7QUFDSCxpQ0FGRCxFQUVHLEtBRkg7O0FBSUEsdUNBQU9ZLEdBQVA7QUFDSCw2QkFkRTtBQWVIM0MsaUNBQUssMERBQUFiLENBQVNjLFFBZlg7QUFnQkhsQyxrQ0FBTXlFLFFBaEJIO0FBaUJIVyxrQ0FBTSxNQWpCSDtBQWtCSEMscUNBQVMscUJBbEJOO0FBbUJIQyxtQ0FBTyxLQW5CSjtBQW9CSEMseUNBQWEsS0FwQlY7QUFxQkhDLHlDQUFhLEtBckJWO0FBc0JIckUscUNBQVMsTUFBS2tDLE1BQUwsQ0FBWWxDLE9BdEJsQjtBQXVCSEosbUNBQU8sTUFBS3NDLE1BQUwsQ0FBWXRDLEtBdkJoQjtBQXdCSFEsc0NBQVUsTUFBSzhCLE1BQUwsQ0FBWTlCO0FBeEJuQix5QkFBUDtBQTBCSCxxQkF4Q0Q7QUF5Q0gsaUJBL0NEO0FBZ0RILGFBbkRlLENBbURieUMsSUFuRGEsQ0FBaEI7O0FBcURBO0FBQ0FHLG1CQUFPc0IsYUFBUCxDQUFxQnpCLElBQXJCO0FBQ0g7O0FBRUQ7Ozs7Ozs7OztnQ0FNUUEsSSxFQUNSO0FBQ0ksZ0JBQUksT0FBT0EsSUFBUCxLQUFnQixXQUFwQixFQUFpQztBQUM3Qix1QkFBTyxxQkFBUDtBQUNIOztBQUVELGdCQUFJQSxLQUFLMEIsSUFBTCxHQUFZLDBEQUFBdEUsQ0FBUzhCLFFBQXpCLEVBQW1DO0FBQy9CLHVCQUFPLGlEQUFQO0FBQ0g7O0FBRUQsZ0JBQUksMERBQUE5QixDQUFTK0IsYUFBVCxDQUF1QndDLE9BQXZCLENBQStCM0IsS0FBS29CLElBQXBDLE1BQThDLENBQUMsQ0FBbkQsRUFBc0Q7QUFDbEQsdUJBQVVwQixLQUFLb0IsSUFBZjtBQUNIOztBQUVELG1CQUFPLEtBQVA7QUFDSDs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUM1SUw7QUFDQTtBQUNBOztBQUVBO0FBQ0EvRSxRQUFNLDBEQUFBZSxDQUFTNEIsbUJBQWYsRUFBc0M0QyxJQUF0QyxDQUEyQyxVQUFDcEQsQ0FBRCxFQUFJcEMsR0FBSixFQUFZO0FBQ25ELFFBQU1ELEtBQUtFLEVBQUVELEdBQUYsRUFBTzJCLElBQVAsQ0FBWSxJQUFaLENBQVg7QUFDQVgsSUFBQSwwREFBQUEsQ0FBU2dDLGVBQVQsQ0FBeUJqRCxFQUF6QixJQUErQixJQUFJLDBEQUFKLENBQWFBLEVBQWIsRUFBaUJDLEdBQWpCLENBQS9CO0FBQ0gsQ0FIRDs7QUFLQTtBQUNBQyxRQUFNLDBEQUFBZSxDQUFTNkIsZUFBZixFQUFrQzJDLElBQWxDLENBQXVDLFVBQUNwRCxDQUFELEVBQUlwQyxHQUFKLEVBQVk7QUFDL0MsUUFBTUQsS0FBS0UsRUFBRUQsR0FBRixFQUFPMkIsSUFBUCxDQUFZLElBQVosQ0FBWDtBQUNBWCxJQUFBLDBEQUFBQSxDQUFTQyxXQUFULENBQXFCbEIsRUFBckIsSUFBMkIsSUFBSSxzREFBSixDQUFTQSxFQUFULEVBQWFDLEdBQWIsQ0FBM0I7QUFDSCxDQUhELEUiLCJmaWxlIjoianMvYXBwLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7XG4gXHRcdFx0XHRjb25maWd1cmFibGU6IGZhbHNlLFxuIFx0XHRcdFx0ZW51bWVyYWJsZTogdHJ1ZSxcbiBcdFx0XHRcdGdldDogZ2V0dGVyXG4gXHRcdFx0fSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiL2Fzc2V0cy9cIjtcblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSBcIi4vYXNzZXRzL2pzL2FwcC5qc1wiKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyB3ZWJwYWNrL2Jvb3RzdHJhcCA0Y2Q2YjkxNjQwNzdkNDdkMjliOCIsImNsYXNzIEV2ZW50c1xue1xuICAgIHdhdGNoKFVwbG9hZGVyLCAkZG9tKVxuICAgIHtcbiAgICAgICAgbGV0IGRyYWdDb3VudGVyID0gMDtcblxuICAgICAgICAvLyBmb3JtIHN1Ym1pdFxuICAgICAgICAkZG9tLm9uKCdzdWJtaXQnLCBldmVudCA9PiB7XG4gICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgZXZlbnQuc3RvcFByb3BhZ2F0aW9uKCk7XG5cbiAgICAgICAgICAgIFVwbG9hZGVyLnBvc3QoZXZlbnQpO1xuICAgICAgICB9KTtcblxuICAgICAgICAvLyBvbiBmaWxlIGNoYW5nZVxuICAgICAgICAkZG9tLm9uKCdjaGFuZ2UnLCAnZm9ybSBpbnB1dFt0eXBlPVwiZmlsZVwiXScsIGV2ZW50ID0+IHtcbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICAgICAgVXBsb2FkZXIucG9zdChldmVudCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIC8vIGRyYWcgZW50ZXJcbiAgICAgICAgJGRvbS5vbignZHJhZ2VudGVyJywgZXZlbnQgPT4ge1xuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuXG4gICAgICAgICAgICBkcmFnQ291bnRlcisrO1xuICAgICAgICAgICAgaWYgKGRyYWdDb3VudGVyID09PSAxKSB7XG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coJ2RyYWcgZW50ZXInKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICAgICAgLy8gZHJhZyBsZWF2ZVxuICAgICAgICAkZG9tLm9uKCdkcmFnbGVhdmUnLCBldmVudCA9PiB7XG4gICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgZXZlbnQuc3RvcFByb3BhZ2F0aW9uKCk7XG5cbiAgICAgICAgICAgIGRyYWdDb3VudGVyLS07XG4gICAgICAgICAgICBpZiAoZHJhZ0NvdW50ZXIgPT09IDApIHtcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZygnZHJhZyBsZWF2ZScpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICAvLyBkcmFnIG92ZXIgKHRoaXMgaXMgaW1wb3J0YW50KVxuICAgICAgICAkZG9tLm9uKCdkcmFnb3ZlcicsIGV2ZW50ID0+IHtcbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgLy8gZHJvcFxuICAgICAgICAkZG9tLm9uKCdkcm9wJywgZXZlbnQgPT4ge1xuICAgICAgICAgICAgY29uc29sZS5sb2coJ2Ryb3BwZWQnKTtcbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblxuICAgICAgICAgICAgVXBsb2FkZXIucG9zdChldmVudCk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIGNvbnNvbGUubG9nKCdXYXRjaGluZycsICRkb20pO1xuXG4gICAgfVxuXG4gICAgcHVzaChuYW1lLCBkYXRhKVxuICAgIHtcbiAgICAgICAgaWYgKHR5cGVvZiBNU19TQ1JFRU5TSE9UUyA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmICh0eXBlb2YgTVNfU0NSRUVOU0hPVFNbbmFtZV0gPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICBNU19TQ1JFRU5TSE9UU1tuYW1lXShkYXRhKTtcbiAgICB9XG59XG5cbmV4cG9ydCB7IEV2ZW50cyBhcyBkZWZhdWx0IH1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9FdmVudHMuanMiLCJpbXBvcnQgU2V0dGluZ3MgZnJvbSAnLi9TZXR0aW5ncyc7XG5pbXBvcnQgUmVuZGVyIGZyb20gJy4vUmVuZGVyJztcbmltcG9ydCBFdmVudHMgZnJvbSAnLi9FdmVudHMnO1xuaW1wb3J0IFVwbG9hZGVyIGZyb20gJy4vVXBsb2FkZXInO1xuXG5jbGFzcyBJbnN0YW5jZVxue1xuICAgIGNvbnN0cnVjdG9yKGlkLCBkb20pIHtcbiAgICAgICAgdGhpcy5pZCA9IGlkO1xuICAgICAgICB0aGlzLiRkb20gPSAkKGRvbSk7XG5cbiAgICAgICAgLy8gZGVwZW5kZW5jaWVzXG4gICAgICAgIHRoaXMuUmVuZGVyID0gbmV3IFJlbmRlcigpO1xuICAgICAgICB0aGlzLkV2ZW50cyA9IG5ldyBFdmVudHMoKTtcbiAgICAgICAgdGhpcy5VcGxvYWRlciA9IG5ldyBVcGxvYWRlcigpO1xuXG4gICAgICAgIC8vIHJlbmRlclxuICAgICAgICB0aGlzLiRkb20uaHRtbChcbiAgICAgICAgICAgIHRoaXMuUmVuZGVyLmdldEZpbGVTZWxlY3RIdG1sKClcbiAgICAgICAgKTtcblxuICAgICAgICAvLyB3YXRjaCBmb3IgZXZlbnRzXG4gICAgICAgIHRoaXMuRXZlbnRzLndhdGNoKFxuICAgICAgICAgICAgdGhpcy5VcGxvYWRlcixcbiAgICAgICAgICAgIHRoaXMuJGRvbVxuICAgICAgICApO1xuXG4gICAgICAgIC8vIHNldCB1cGxvYWRlciBjYWxsYmFja3NcbiAgICAgICAgdGhpcy5VcGxvYWRlclxuICAgICAgICAgICAgLmZvcm0oe1xuICAgICAgICAgICAgICAgIGlkVW5pcXVlOiB0aGlzLmlkLFxuICAgICAgICAgICAgICAgIHVzZXJJZDogJ2hlbGxvLXdvcmxkJ1xuICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIC5vbih7XG4gICAgICAgICAgICAgICAgc3RhcnQ6ICgpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5FdmVudHMucHVzaCgndXBsb2FkU3RhcnQnKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGVycm9yOiAocmVzcG9uc2UsIGNvZGUsIG1lc3NhZ2UpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5FdmVudHMucHVzaCgndXBsb2FkRXJyb3InLCB7IHJlc3BvbnNlLCBjb2RlLCBtZXNzYWdlIH0pO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgc3VjY2VzczogcmVzcG9uc2UgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLkV2ZW50cy5wdXNoKCd1cGxvYWRTdWNjZXNzJywgeyByZXNwb25zZSB9KTtcblxuICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhTZXR0aW5ncy5NU19TU19MSVNUUyk7XG5cbiAgICAgICAgICAgICAgICAgICAgLy8gcmVuZGVyIGltYWdlcyBhZ2FpblxuICAgICAgICAgICAgICAgICAgICBTZXR0aW5ncy5NU19TU19MSVNUU1t0aGlzLmlkXS5yZW5kZXIoKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGNvbXBsZXRlOiAoKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuVXBsb2FkZXIudXBsb2FkaW5nID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuJGRvbS5maW5kKCdpbnB1dCcpLnZhbCgnJyk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuRXZlbnRzLnB1c2goJ3VwbG9hZENvbXBsZXRlJyk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBwcm9ncmVzczogcmVzcG9uc2UgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLkV2ZW50cy5wdXNoKCd1cGxvYWRQcm9ncmVzcycsIHsgcmVzcG9uc2UgfSk7XG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBsb2FkOiByZXNwb25zZSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuRXZlbnRzLnB1c2goJ3VwbG9hZExvYWQnLCB7IHJlc3BvbnNlIH0pO1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICB9KTtcbiAgICB9XG59XG5cbmV4cG9ydCB7IEluc3RhbmNlIGFzIGRlZmF1bHQgfVxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL0luc3RhbmNlLmpzIiwiaW1wb3J0IFNldHRpbmdzIGZyb20gJy4vU2V0dGluZ3MnO1xuaW1wb3J0IFJlbmRlciBmcm9tICcuL1JlbmRlcic7XG5pbXBvcnQgRXZlbnRzIGZyb20gJy4vRXZlbnRzJztcblxuY2xhc3MgTGlzdFxue1xuICAgIGNvbnN0cnVjdG9yKGlkLCBkb20pIHtcbiAgICAgICAgdGhpcy5pZCA9IGlkO1xuICAgICAgICB0aGlzLiRkb20gPSAkKGRvbSk7XG5cbiAgICAgICAgLy8gZGVwZW5kZW5jaWVzXG4gICAgICAgIHRoaXMuUmVuZGVyID0gbmV3IFJlbmRlcigpO1xuICAgICAgICB0aGlzLkV2ZW50cyA9IG5ldyBFdmVudHMoKTtcblxuICAgICAgICAvLyByZW5kZXIgcGljdHVyZXNcbiAgICAgICAgdGhpcy5yZW5kZXIoKTtcblxuICAgICAgICAkKCdodG1sJykub24oJ2NsaWNrJywgJy54aXZkYi1zY3JlZW5zaG90cy1kZWxldGUnLCBldmVudCA9PiB7XG4gICAgICAgICAgICBjb25zdCBpZCA9ICQoZXZlbnQuY3VycmVudFRhcmdldCkuYXR0cignaWQnKTtcbiAgICAgICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICAgICAgdXJsOiBTZXR0aW5ncy5FTkRQT0lOVCArICcvJyArIGlkLFxuICAgICAgICAgICAgICAgIG1ldGhvZDogJ0RFTEVURScsXG4gICAgICAgICAgICAgICAgc3VjY2VzczogcmVzcG9uc2UgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLkV2ZW50cy5wdXNoKCdkZWxldGVTdWNjZXNzJywgeyByZXNwb25zZSB9KTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5yZW5kZXIoKTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGVycm9yOiByZXNwb25zZSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuRXZlbnRzLnB1c2goJ2RlbGV0ZUVycm9yJywgeyByZXNwb25zZSwgY29kZSwgbWVzc2FnZSB9KTtcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGNvbXBsZXRlOiAoKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuRXZlbnRzLnB1c2goJ2RlbGV0ZUNvbXBsZXRlJyk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIHJlbmRlcigpXG4gICAge1xuICAgICAgICAvLyBnZXQgaW1hZ2VzXG4gICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICB1cmw6IFNldHRpbmdzLkVORFBPSU5UICsgJy9saXN0JyxcbiAgICAgICAgICAgIGRhdGE6IHtcbiAgICAgICAgICAgICAgICAnaWRVbmlxdWUnOiB0aGlzLmlkXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgc3VjY2VzczogcmVzcG9uc2UgPT4ge1xuICAgICAgICAgICAgICAgIHRoaXMuRXZlbnRzLnB1c2goJ2xpc3RTdWNjZXNzJywgeyByZXNwb25zZSB9KTtcblxuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgcmVzcG9uc2UuZXJyb3IgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuRXZlbnRzLnB1c2goJ2xpc3RSZW5kZXJpbmcnKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5yZW5kZXJJbWFnZXMocmVzcG9uc2UpO1xuICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuRXZlbnRzLnB1c2goJ2xpc3RFbXB0eScpO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnJlbmRlckVtcHR5KHJlc3BvbnNlLmVycm9yKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgZXJyb3I6IHJlc3BvbnNlID0+IHtcbiAgICAgICAgICAgICAgICB0aGlzLkV2ZW50cy5wdXNoKCdsaXN0RXJyb3InLCB7IHJlc3BvbnNlLCBjb2RlLCBtZXNzYWdlIH0pO1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGNvbXBsZXRlOiAoKSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5FdmVudHMucHVzaCgnbGlzdENvbXBsZXRlJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIHJlbmRlckltYWdlcyhpbWFnZXMpXG4gICAge1xuICAgICAgICB0aGlzLiRkb20uaHRtbChcbiAgICAgICAgICAgIHRoaXMuUmVuZGVyLmdldFNjcmVlbnNob3RMaXN0SHRtbCgpXG4gICAgICAgICk7XG5cbiAgICAgICAgZm9yKGxldCBpIGluIGltYWdlcykge1xuICAgICAgICAgICAgbGV0IGltZyA9IGltYWdlc1tpXSxcbiAgICAgICAgICAgICAgICBodG1sID0gdGhpcy5SZW5kZXIuZ2V0U2NyZWVuc2hvdEVtYmVkSHRtbChpbWcpO1xuXG4gICAgICAgICAgICB0aGlzLiRkb20uZmluZCgnLnhpdmRiLXNjcmVlbnNob3RzLWRpc3BsYXknKS5hcHBlbmQoaHRtbCk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICByZW5kZXJFbXB0eShjb2RlKVxuICAgIHtcbiAgICAgICAgdGhpcy4kZG9tLmh0bWwoXG4gICAgICAgICAgICB0aGlzLlJlbmRlci5nZXRTY3JlZW5zaG90RW1wdHlIdG1sKGNvZGUpXG4gICAgICAgICk7XG4gICAgfVxufVxuXG5leHBvcnQgeyBMaXN0IGFzIGRlZmF1bHQgfVxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL0xpc3QuanMiLCJpbXBvcnQgU2V0dGluZ3MgZnJvbSAnLi9TZXR0aW5ncyc7XG5cbmNsYXNzIFJlbmRlclxue1xuICAgIGdldEZpbGVTZWxlY3RIdG1sKClcbiAgICB7XG4gICAgICAgIHJldHVybiBgXG4gICAgICAgICAgICA8Zm9ybSBjbGFzcz1cInhpdmRiLXNjcmVlbnNob3RzLWNvbnRhaW5lclwiPlxuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ4aXZkYi1zY3JlZW5zaG90cy1maWVsZHMgYWN0aXZlXCI+XG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ4aXZkYi1zY3JlZW5zaG90cy1pbnB1dFwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsIGZvcj1cImZpbGVcIiBjbGFzcz1cImJ0blwiPkNob29zZSBhIGZpbGU8L2xhYmVsPlxuICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9XCJmaWxlXCIgaWQ9XCJmaWxlXCI+XG4gICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwieGl2ZGItc2NyZWVuc2hvdHMtZHJvcHRleHRcIj5cbiAgICAgICAgICAgICAgICAgICAgICAgIE9yIGRyb3AgeW91ciBmaWxlIGhlcmVcbiAgICAgICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICAgICAgPGRpdiBjbGFzcz1cInhpdmRiLXNjcmVlbnNob3RzLXN0YXRlXCI+XG4gICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ4aXZkYi1zY3JlZW5zaG90cy10aXRsZVwiPlVwbG9hZGluZy4uLjwvZGl2PlxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwieGl2ZGItc2NyZWVuc2hvdHMtcHJvZ3Jlc3NcIj48c3BhbiBzdHlsZT1cIndpZHRoOjAlO1wiPjwvc3Bhbj48L2Rpdj5cbiAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgIDwvZm9ybT5cbiAgICAgICAgYDtcbiAgICB9XG5cbiAgICBnZXRTY3JlZW5zaG90TGlzdEh0bWwoKVxuICAgIHtcbiAgICAgICAgcmV0dXJuIGBcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJ4aXZkYi1zY3JlZW5zaG90cy1kaXNwbGF5XCI+PC9kaXY+XG4gICAgICAgIGA7XG4gICAgfVxuXG4gICAgZ2V0U2NyZWVuc2hvdEVtYmVkSHRtbChpbWFnZSlcbiAgICB7XG4gICAgICAgIGxldCBpbWFnZVVybCA9IFNldHRpbmdzLkVORFBPSU5UICsgJy8nICsgaW1hZ2UuaWQ7XG5cbiAgICAgICAgcmV0dXJuIGBcbiAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwieGl2ZGItc2NyZWVuc2hvdHMtZW1iZWRcIj5cbiAgICAgICAgICAgICAgICA8YSBocmVmPVwiJHtpbWFnZVVybH1cIiB0YXJnZXQ9XCJfYmxhbmtcIj5cbiAgICAgICAgICAgICAgICAgICAgPGltZyBzcmM9XCIke2ltYWdlVXJsfVwiIGNsYXNzPVwieGl2ZGItc2NyZWVuc2hvdHMtaW1nXCIgaGVpZ2h0PVwiODBcIj5cbiAgICAgICAgICAgICAgICA8L2E+XG4gICAgICAgICAgICAgICAgPGJ1dHRvbiBjbGFzcz1cInhpdmRiLXNjcmVlbnNob3RzLWRlbGV0ZVwiIGlkPVwiJHtpbWFnZS5pZH1cIj5EZWxldGU8L2J1dHRvbj5cbiAgICAgICAgICAgIDwvc3Bhbj5cbiAgICAgICAgYDtcbiAgICB9XG5cbiAgICBnZXRTY3JlZW5zaG90RW1wdHlIdG1sKGNvZGUpXG4gICAge1xuICAgICAgICByZXR1cm4gYFxuICAgICAgICAgICAgPGRpdiBjbGFzcz1cInhpdmRiLXNjcmVlbnNob3RzLWVtcHR5XCI+WyR7Y29kZX1dPC9kaXY+XG4gICAgICAgIGA7XG4gICAgfVxufVxuXG5leHBvcnQgeyBSZW5kZXIgYXMgZGVmYXVsdCB9XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvanMvUmVuZGVyLmpzIiwiY29uc3QgU2V0dGluZ3MgPSB7XG4gICAgRU5EUE9JTlQ6ICh0eXBlb2YgTVNfU0NSRUVOU0hPVFNfRU5EUE9JTlQgIT09ICd1bmRlZmluZWQnKVxuICAgICAgICAgICAgICAgID8gTVNfU0NSRUVOU0hPVFNfRU5EUE9JTlRcbiAgICAgICAgICAgICAgICA6ICdodHRwOi8vMTI3LjAuMC4xOjgwMDAnLFxuXG4gICAgSU5TVEFOQ0VfQ0xBU1NfTkFNRTogJ3hpdmRiLXNjcmVlbnNob3RzJyxcbiAgICBMSVNUX0NMQVNTX05BTUU6ICd4aXZkYi1zY3JlZW5zaG90cy1saXN0JyxcblxuICAgIE1BWF9TSVpFOiAoMTAyNCAqIDEwMjQgKiAxNSksXG4gICAgQUxMT1dFRF9UWVBFUzogW1xuICAgICAgICAnaW1hZ2UvcG5nJyxcbiAgICAgICAgJ2ltYWdlL2dpZicsXG4gICAgICAgICdpbWFnZS9ibXAnLFxuICAgICAgICAnaW1hZ2UvanBnJyxcbiAgICAgICAgJ2ltYWdlL2pwZWcnXG4gICAgXSxcblxuICAgIE1TX1NTX0lOU1RBTkNFUzoge30sXG4gICAgTVNfU1NfTElTVFM6IHt9XG59O1xuXG5leHBvcnQgeyBTZXR0aW5ncyBhcyBkZWZhdWx0IH1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9TZXR0aW5ncy5qcyIsImltcG9ydCBTZXR0aW5ncyBmcm9tICcuL1NldHRpbmdzJztcblxuY2xhc3MgVXBsb2FkZXJcbntcbiAgICBjb25zdHJ1Y3RvcigpXG4gICAge1xuICAgICAgICB0aGlzLnVwbG9hZGluZyA9IGZhbHNlO1xuICAgICAgICB0aGlzLmV2ZW50cyA9IHt9O1xuICAgICAgICB0aGlzLmZvcm1kYXRhID0ge307XG4gICAgfVxuXG4gICAgZm9ybShmb3JtKVxuICAgIHtcbiAgICAgICAgdGhpcy5mb3JtZGF0YSA9IGZvcm07XG4gICAgICAgIHJldHVybiB0aGlzO1xuICAgIH1cblxuICAgIG9uKGNhbGxiYWNrcylcbiAgICB7XG4gICAgICAgIHRoaXMuZXZlbnRzID0gY2FsbGJhY2tzO1xuICAgICAgICByZXR1cm4gdGhpcztcbiAgICB9XG5cbiAgICBwb3N0KGV2ZW50KVxuICAgIHtcbiAgICAgICAgaWYgKHRoaXMudXBsb2FkaW5nKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICAvLyBncmFiIHRoZSBjb3JyZWN0IGZpbGVzIGluc3RhbmNlXG4gICAgICAgIGxldCBkdCA9IGV2ZW50LmRhdGFUcmFuc2ZlciB8fCAoZXZlbnQub3JpZ2luYWxFdmVudCAmJiBldmVudC5vcmlnaW5hbEV2ZW50LmRhdGFUcmFuc2Zlcik7XG4gICAgICAgIGxldCBmaWxlcyA9IGV2ZW50LnRhcmdldC5maWxlcyB8fCAoZHQgJiYgZHQuZmlsZXMpO1xuXG4gICAgICAgIGNvbnNvbGUubG9nKGV2ZW50KTtcblxuICAgICAgICAvLyBDb252ZXJ0IGZpbGVzIHRvIGFycmF5XG4gICAgICAgIGZpbGVzID0gJC5ncmVwKGZpbGVzLCAoYSwgYikgPT4ge1xuICAgICAgICAgICAgcmV0dXJuIGEgIT0gYjtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgLy8gZ2V0IGZpcnN0IGZpbGUgKG5vdCBzdXBwb3J0aW5nIG11bHRpIGF0IHRoaXMgdGltZSlcbiAgICAgICAgbGV0IGZpbGUgPSBmaWxlc1swXTtcblxuICAgICAgICBjb25zb2xlLmxvZyhmaWxlKTtcblxuICAgICAgICAvLyBjaGVjayBmb3IgZXJyb3JzXG4gICAgICAgIGxldCBlcnJvciA9IGZhbHNlO1xuICAgICAgICBpZiAoZXJyb3IgPSB0aGlzLmludmFsaWQoZmlsZSkpIHtcbiAgICAgICAgICAgIHRoaXMudXBsb2FkaW5nID0gZmFsc2U7XG4gICAgICAgICAgICByZXR1cm4gdGhpcy5ldmVudHMuZXJyb3IoZXJyb3IpO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gdXBsb2FkXG4gICAgICAgIHRoaXMudXBsb2FkKGZpbGUpO1xuICAgIH1cblxuICAgIHVwbG9hZChmaWxlKVxuICAgIHtcbiAgICAgICAgLy8gT3BlbiBhIHJlYWRlclxuICAgICAgICBsZXQgcmVhZGVyID0gbmV3IEZpbGVSZWFkZXIoKTtcblxuICAgICAgICAvLyBPbiByZWFkZXIgbG9hZFxuICAgICAgICByZWFkZXIub25sb2FkID0gKHRlbXAgPT4ge1xuICAgICAgICAgICAgdGhpcy5ldmVudHMuc3RhcnQoKTtcblxuICAgICAgICAgICAgcmV0dXJuIGV2ZW50ID0+IHtcbiAgICAgICAgICAgICAgICAvLyBuZXcgaW1hZ2VcbiAgICAgICAgICAgICAgICBsZXQgaW1nID0gbmV3IEltYWdlO1xuICAgICAgICAgICAgICAgIGltZy5zcmMgPSBldmVudC50YXJnZXQucmVzdWx0O1xuXG4gICAgICAgICAgICAgICAgLy8gT24gaW1hZ2UgbG9hZFxuICAgICAgICAgICAgICAgIGltZy5vbmxvYWQgPSAoKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMudXBsb2FkaW5nID0gdHJ1ZTtcblxuICAgICAgICAgICAgICAgICAgICAvLyBjcmVhdGUgZm9ybSBkYXRhXG4gICAgICAgICAgICAgICAgICAgIGxldCBmb3JtRGF0YSA9IG5ldyBGb3JtRGF0YSgpO1xuICAgICAgICAgICAgICAgICAgICBmb3JtRGF0YS5hcHBlbmQoJ21lZGlhJywgZmlsZSk7XG5cbiAgICAgICAgICAgICAgICAgICAgLy8gYXBwZW5kIGZvcm0gZGF0YVxuICAgICAgICAgICAgICAgICAgICBmb3IobGV0IGkgaW4gdGhpcy5mb3JtZGF0YSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgbGV0IHZhbHVlID0gdGhpcy5mb3JtZGF0YVtpXTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZvcm1EYXRhLmFwcGVuZChpLCB2YWx1ZSk7XG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICAvLyB1cGxvYWRcbiAgICAgICAgICAgICAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHhocjogKCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgd2luZG93LlhNTEh0dHBSZXF1ZXN0KCk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB4aHIudXBsb2FkLmFkZEV2ZW50TGlzdGVuZXIoJ3Byb2dyZXNzJywgZXZlbnQgPT4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBsZXQgcGVyY2VudCA9IChldmVudC5sb2FkZWQgLyBldmVudC50b3RhbCAqIDEwMCkudG9GaXhlZCgyKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5ldmVudHMucHJvZ3Jlc3MocGVyY2VudCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSwgZmFsc2UpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgeGhyLnVwbG9hZC5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZXZlbnQgPT4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmV2ZW50cy5sb2FkKGZpbGUpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSwgZmFsc2UpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHhocjtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgICAgICB1cmw6IFNldHRpbmdzLkVORFBPSU5ULFxuICAgICAgICAgICAgICAgICAgICAgICAgZGF0YTogZm9ybURhdGEsXG4gICAgICAgICAgICAgICAgICAgICAgICB0eXBlOiAnUE9TVCcsXG4gICAgICAgICAgICAgICAgICAgICAgICBlbmN0eXBlOiAnbXVsdGlwYXJ0L2Zvcm0tZGF0YScsXG4gICAgICAgICAgICAgICAgICAgICAgICBjYWNoZTogZmFsc2UsXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50VHlwZTogZmFsc2UsXG4gICAgICAgICAgICAgICAgICAgICAgICBwcm9jZXNzRGF0YTogZmFsc2UsXG4gICAgICAgICAgICAgICAgICAgICAgICBzdWNjZXNzOiB0aGlzLmV2ZW50cy5zdWNjZXNzLFxuICAgICAgICAgICAgICAgICAgICAgICAgZXJyb3I6IHRoaXMuZXZlbnRzLmVycm9yLFxuICAgICAgICAgICAgICAgICAgICAgICAgY29tcGxldGU6IHRoaXMuZXZlbnRzLmNvbXBsZXRlXG4gICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSkoZmlsZSk7XG5cbiAgICAgICAgLy8gUmVhZCBpbiB0aGUgaW1hZ2UgZmlsZSBhcyBkYXRhLlxuICAgICAgICByZWFkZXIucmVhZEFzRGF0YVVSTChmaWxlKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiBDaGVjayBpZiBhIGZpbGUgaXMgaW52YWxpZCBvciBub3RcbiAgICAgKlxuICAgICAqIEBwYXJhbSBmaWxlXG4gICAgICogQHJldHVybnMgeyp9XG4gICAgICovXG4gICAgaW52YWxpZChmaWxlKVxuICAgIHtcbiAgICAgICAgaWYgKHR5cGVvZiBmaWxlID09PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgcmV0dXJuICdGaWxlIGVudGl0eSBpbnZhbGlkJztcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChmaWxlLnNpemUgPiBTZXR0aW5ncy5NQVhfU0laRSkge1xuICAgICAgICAgICAgcmV0dXJuICdGaWxlIHNpemUgaXMgdG9vIGJpZywgcGxlYXNlIGtlZXAgaXQgYmVsb3cgMTVtYic7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAoU2V0dGluZ3MuQUxMT1dFRF9UWVBFUy5pbmRleE9mKGZpbGUudHlwZSkgPT09IC0xKSB7XG4gICAgICAgICAgICByZXR1cm4gYCR7ZmlsZS50eXBlfSBpcyBhbiBpbnZhbGlkIGZpbGUgdHlwZWA7XG4gICAgICAgIH1cblxuICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgfVxufVxuXG5leHBvcnQgeyBVcGxvYWRlciBhcyBkZWZhdWx0IH1cblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9qcy9VcGxvYWRlci5qcyIsImltcG9ydCBTZXR0aW5ncyBmcm9tICcuL1NldHRpbmdzJztcbmltcG9ydCBJbnN0YW5jZSBmcm9tICcuL0luc3RhbmNlJztcbmltcG9ydCBMaXN0IGZyb20gJy4vTGlzdCc7XG5cbi8vIGdyYWIgYWxsIGluc3RhbmNlc1xuJChgLiR7U2V0dGluZ3MuSU5TVEFOQ0VfQ0xBU1NfTkFNRX1gKS5lYWNoKChpLCBkb20pID0+IHtcbiAgICBjb25zdCBpZCA9ICQoZG9tKS5hdHRyKCdpZCcpO1xuICAgIFNldHRpbmdzLk1TX1NTX0lOU1RBTkNFU1tpZF0gPSBuZXcgSW5zdGFuY2UoaWQsIGRvbSk7XG59KTtcblxuLy8gZ3JhYiBhbGwgaW5zdGFuY2VzXG4kKGAuJHtTZXR0aW5ncy5MSVNUX0NMQVNTX05BTUV9YCkuZWFjaCgoaSwgZG9tKSA9PiB7XG4gICAgY29uc3QgaWQgPSAkKGRvbSkuYXR0cignaWQnKTtcbiAgICBTZXR0aW5ncy5NU19TU19MSVNUU1tpZF0gPSBuZXcgTGlzdChpZCwgZG9tKTtcbn0pO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2FwcC5qcyJdLCJzb3VyY2VSb290IjoiIn0=