"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_SemillerosInvestigacion_Form_svelte"],{

/***/ "./node_modules/@material/animation/animationframe.js":
/*!************************************************************!*\
  !*** ./node_modules/@material/animation/animationframe.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "AnimationFrame": () => (/* binding */ AnimationFrame)
/* harmony export */ });
/**
 * @license
 * Copyright 2020 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
/**
 * AnimationFrame provides a user-friendly abstraction around requesting
 * and canceling animation frames.
 */
var AnimationFrame = /** @class */ (function () {
    function AnimationFrame() {
        this.rafIDs = new Map();
    }
    /**
     * Requests an animation frame. Cancels any existing frame with the same key.
     * @param {string} key The key for this callback.
     * @param {FrameRequestCallback} callback The callback to be executed.
     */
    AnimationFrame.prototype.request = function (key, callback) {
        var _this = this;
        this.cancel(key);
        var frameID = requestAnimationFrame(function (frame) {
            _this.rafIDs.delete(key);
            // Callback must come *after* the key is deleted so that nested calls to
            // request with the same key are not deleted.
            callback(frame);
        });
        this.rafIDs.set(key, frameID);
    };
    /**
     * Cancels a queued callback with the given key.
     * @param {string} key The key for this callback.
     */
    AnimationFrame.prototype.cancel = function (key) {
        var rafID = this.rafIDs.get(key);
        if (rafID) {
            cancelAnimationFrame(rafID);
            this.rafIDs.delete(key);
        }
    };
    /**
     * Cancels all queued callback.
     */
    AnimationFrame.prototype.cancelAll = function () {
        var _this = this;
        // Need to use forEach because it's the only iteration method supported
        // by IE11. Suppress the underscore because we don't need it.
        // tslint:disable-next-line:enforce-name-casing
        this.rafIDs.forEach(function (_, key) {
            _this.cancel(key);
        });
    };
    /**
     * Returns the queue of unexecuted callback keys.
     */
    AnimationFrame.prototype.getQueue = function () {
        var queue = [];
        // Need to use forEach because it's the only iteration method supported
        // by IE11. Suppress the underscore because we don't need it.
        // tslint:disable-next-line:enforce-name-casing
        this.rafIDs.forEach(function (_, key) {
            queue.push(key);
        });
        return queue;
    };
    return AnimationFrame;
}());

//# sourceMappingURL=animationframe.js.map

/***/ }),

/***/ "./node_modules/@material/base/foundation.js":
/*!***************************************************!*\
  !*** ./node_modules/@material/base/foundation.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "MDCFoundation": () => (/* binding */ MDCFoundation),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * @license
 * Copyright 2016 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
var MDCFoundation = /** @class */ (function () {
    function MDCFoundation(adapter) {
        if (adapter === void 0) { adapter = {}; }
        this.adapter = adapter;
    }
    Object.defineProperty(MDCFoundation, "cssClasses", {
        get: function () {
            // Classes extending MDCFoundation should implement this method to return an object which exports every
            // CSS class the foundation class needs as a property. e.g. {ACTIVE: 'mdc-component--active'}
            return {};
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCFoundation, "strings", {
        get: function () {
            // Classes extending MDCFoundation should implement this method to return an object which exports all
            // semantic strings as constants. e.g. {ARIA_ROLE: 'tablist'}
            return {};
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCFoundation, "numbers", {
        get: function () {
            // Classes extending MDCFoundation should implement this method to return an object which exports all
            // of its semantic numbers as constants. e.g. {ANIMATION_DELAY_MS: 350}
            return {};
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCFoundation, "defaultAdapter", {
        get: function () {
            // Classes extending MDCFoundation may choose to implement this getter in order to provide a convenient
            // way of viewing the necessary methods of an adapter. In the future, this could also be used for adapter
            // validation.
            return {};
        },
        enumerable: false,
        configurable: true
    });
    MDCFoundation.prototype.init = function () {
        // Subclasses should override this method to perform initialization routines (registering events, etc.)
    };
    MDCFoundation.prototype.destroy = function () {
        // Subclasses should override this method to perform de-initialization routines (de-registering events, etc.)
    };
    return MDCFoundation;
}());

// tslint:disable-next-line:no-default-export Needed for backward compatibility with MDC Web v0.44.0 and earlier.
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MDCFoundation);
//# sourceMappingURL=foundation.js.map

/***/ }),

/***/ "./node_modules/@material/dialog/constants.js":
/*!****************************************************!*\
  !*** ./node_modules/@material/dialog/constants.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "cssClasses": () => (/* binding */ cssClasses),
/* harmony export */   "numbers": () => (/* binding */ numbers),
/* harmony export */   "strings": () => (/* binding */ strings)
/* harmony export */ });
/**
 * @license
 * Copyright 2016 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
var cssClasses = {
    CLOSING: 'mdc-dialog--closing',
    OPEN: 'mdc-dialog--open',
    OPENING: 'mdc-dialog--opening',
    SCROLLABLE: 'mdc-dialog--scrollable',
    SCROLL_LOCK: 'mdc-dialog-scroll-lock',
    STACKED: 'mdc-dialog--stacked',
    FULLSCREEN: 'mdc-dialog--fullscreen',
    // Class for showing a scroll divider on full-screen dialog header element.
    // Should only be displayed on scrollable content, when the dialog content is
    // scrolled "underneath" the header.
    SCROLL_DIVIDER_HEADER: 'mdc-dialog-scroll-divider-header',
    // Class for showing a scroll divider on a full-screen dialog footer element.
    // Should only be displayed on scrolalble content, when the dialog content is
    // obscured "underneath" the footer.
    SCROLL_DIVIDER_FOOTER: 'mdc-dialog-scroll-divider-footer',
    // The "surface scrim" is a scrim covering only the surface of a dialog. This
    // is used in situations where a confirmation dialog is shown over an already
    // opened full-screen dialog. On larger screen-sizes, the full-screen dialog
    // is sized as a modal and so in these situations we display a "surface scrim"
    // to prevent a "double scrim" (where the scrim from the secondary
    // confirmation dialog would overlap with the scrim from the full-screen
    // dialog).
    SURFACE_SCRIM_SHOWN: 'mdc-dialog__surface-scrim--shown',
    // "Showing" animating class for the surface-scrim.
    SURFACE_SCRIM_SHOWING: 'mdc-dialog__surface-scrim--showing',
    // "Hiding" animating class for the surface-scrim.
    SURFACE_SCRIM_HIDING: 'mdc-dialog__surface-scrim--hiding',
    // Class to hide a dialog's scrim (used in conjunction with a surface-scrim).
    // Note that we only hide the original scrim rather than removing it entirely
    // to prevent interactions with the content behind this scrim, and to capture
    // scrim clicks.
    SCRIM_HIDDEN: 'mdc-dialog__scrim--hidden',
};
var strings = {
    ACTION_ATTRIBUTE: 'data-mdc-dialog-action',
    BUTTON_DEFAULT_ATTRIBUTE: 'data-mdc-dialog-button-default',
    BUTTON_SELECTOR: '.mdc-dialog__button',
    CLOSED_EVENT: 'MDCDialog:closed',
    CLOSE_ACTION: 'close',
    CLOSING_EVENT: 'MDCDialog:closing',
    CONTAINER_SELECTOR: '.mdc-dialog__container',
    CONTENT_SELECTOR: '.mdc-dialog__content',
    DESTROY_ACTION: 'destroy',
    INITIAL_FOCUS_ATTRIBUTE: 'data-mdc-dialog-initial-focus',
    OPENED_EVENT: 'MDCDialog:opened',
    OPENING_EVENT: 'MDCDialog:opening',
    SCRIM_SELECTOR: '.mdc-dialog__scrim',
    SUPPRESS_DEFAULT_PRESS_SELECTOR: [
        'textarea',
        '.mdc-menu .mdc-list-item',
        '.mdc-menu .mdc-deprecated-list-item',
    ].join(', '),
    SURFACE_SELECTOR: '.mdc-dialog__surface',
};
var numbers = {
    DIALOG_ANIMATION_CLOSE_TIME_MS: 75,
    DIALOG_ANIMATION_OPEN_TIME_MS: 150,
};
//# sourceMappingURL=constants.js.map

/***/ }),

/***/ "./node_modules/@material/dialog/foundation.js":
/*!*****************************************************!*\
  !*** ./node_modules/@material/dialog/foundation.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "MDCDialogFoundation": () => (/* binding */ MDCDialogFoundation),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _material_animation_animationframe__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @material/animation/animationframe */ "./node_modules/@material/animation/animationframe.js");
/* harmony import */ var _material_base_foundation__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @material/base/foundation */ "./node_modules/@material/base/foundation.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./constants */ "./node_modules/@material/dialog/constants.js");
/**
 * @license
 * Copyright 2017 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */




var AnimationKeys;
(function (AnimationKeys) {
    AnimationKeys["POLL_SCROLL_POS"] = "poll_scroll_position";
    AnimationKeys["POLL_LAYOUT_CHANGE"] = "poll_layout_change";
})(AnimationKeys || (AnimationKeys = {}));
var MDCDialogFoundation = /** @class */ (function (_super) {
    (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__extends)(MDCDialogFoundation, _super);
    function MDCDialogFoundation(adapter) {
        var _this = _super.call(this, (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)((0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)({}, MDCDialogFoundation.defaultAdapter), adapter)) || this;
        _this.dialogOpen = false;
        _this.isFullscreen = false;
        _this.animationFrame = 0;
        _this.animationTimer = 0;
        _this.escapeKeyAction = _constants__WEBPACK_IMPORTED_MODULE_1__.strings.CLOSE_ACTION;
        _this.scrimClickAction = _constants__WEBPACK_IMPORTED_MODULE_1__.strings.CLOSE_ACTION;
        _this.autoStackButtons = true;
        _this.areButtonsStacked = false;
        _this.suppressDefaultPressSelector = _constants__WEBPACK_IMPORTED_MODULE_1__.strings.SUPPRESS_DEFAULT_PRESS_SELECTOR;
        _this.animFrame = new _material_animation_animationframe__WEBPACK_IMPORTED_MODULE_2__.AnimationFrame();
        _this.contentScrollHandler = function () {
            _this.handleScrollEvent();
        };
        _this.windowResizeHandler = function () {
            _this.layout();
        };
        _this.windowOrientationChangeHandler = function () {
            _this.layout();
        };
        return _this;
    }
    Object.defineProperty(MDCDialogFoundation, "cssClasses", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCDialogFoundation, "strings", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.strings;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCDialogFoundation, "numbers", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.numbers;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCDialogFoundation, "defaultAdapter", {
        get: function () {
            return {
                addBodyClass: function () { return undefined; },
                addClass: function () { return undefined; },
                areButtonsStacked: function () { return false; },
                clickDefaultButton: function () { return undefined; },
                eventTargetMatches: function () { return false; },
                getActionFromEvent: function () { return ''; },
                getInitialFocusEl: function () { return null; },
                hasClass: function () { return false; },
                isContentScrollable: function () { return false; },
                notifyClosed: function () { return undefined; },
                notifyClosing: function () { return undefined; },
                notifyOpened: function () { return undefined; },
                notifyOpening: function () { return undefined; },
                releaseFocus: function () { return undefined; },
                removeBodyClass: function () { return undefined; },
                removeClass: function () { return undefined; },
                reverseButtons: function () { return undefined; },
                trapFocus: function () { return undefined; },
                registerContentEventHandler: function () { return undefined; },
                deregisterContentEventHandler: function () { return undefined; },
                isScrollableContentAtTop: function () { return false; },
                isScrollableContentAtBottom: function () { return false; },
                registerWindowEventHandler: function () { return undefined; },
                deregisterWindowEventHandler: function () { return undefined; },
            };
        },
        enumerable: false,
        configurable: true
    });
    MDCDialogFoundation.prototype.init = function () {
        if (this.adapter.hasClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.STACKED)) {
            this.setAutoStackButtons(false);
        }
        this.isFullscreen = this.adapter.hasClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.FULLSCREEN);
    };
    MDCDialogFoundation.prototype.destroy = function () {
        if (this.dialogOpen) {
            this.close(_constants__WEBPACK_IMPORTED_MODULE_1__.strings.DESTROY_ACTION);
        }
        if (this.animationTimer) {
            clearTimeout(this.animationTimer);
            this.handleAnimationTimerEnd();
        }
        if (this.isFullscreen) {
            this.adapter.deregisterContentEventHandler('scroll', this.contentScrollHandler);
        }
        this.animFrame.cancelAll();
        this.adapter.deregisterWindowEventHandler('resize', this.windowResizeHandler);
        this.adapter.deregisterWindowEventHandler('orientationchange', this.windowOrientationChangeHandler);
    };
    MDCDialogFoundation.prototype.open = function (dialogOptions) {
        var _this = this;
        this.dialogOpen = true;
        this.adapter.notifyOpening();
        this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.OPENING);
        if (this.isFullscreen) {
            // A scroll event listener is registered even if the dialog is not
            // scrollable on open, since the window resize event, or orientation
            // change may make the dialog scrollable after it is opened.
            this.adapter.registerContentEventHandler('scroll', this.contentScrollHandler);
        }
        if (dialogOptions && dialogOptions.isAboveFullscreenDialog) {
            this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCRIM_HIDDEN);
        }
        this.adapter.registerWindowEventHandler('resize', this.windowResizeHandler);
        this.adapter.registerWindowEventHandler('orientationchange', this.windowOrientationChangeHandler);
        // Wait a frame once display is no longer "none", to establish basis for
        // animation
        this.runNextAnimationFrame(function () {
            _this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.OPEN);
            _this.adapter.addBodyClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCROLL_LOCK);
            _this.layout();
            _this.animationTimer = setTimeout(function () {
                _this.handleAnimationTimerEnd();
                _this.adapter.trapFocus(_this.adapter.getInitialFocusEl());
                _this.adapter.notifyOpened();
            }, _constants__WEBPACK_IMPORTED_MODULE_1__.numbers.DIALOG_ANIMATION_OPEN_TIME_MS);
        });
    };
    MDCDialogFoundation.prototype.close = function (action) {
        var _this = this;
        if (action === void 0) { action = ''; }
        if (!this.dialogOpen) {
            // Avoid redundant close calls (and events), e.g. from keydown on elements
            // that inherently emit click
            return;
        }
        this.dialogOpen = false;
        this.adapter.notifyClosing(action);
        this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.CLOSING);
        this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.OPEN);
        this.adapter.removeBodyClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCROLL_LOCK);
        if (this.isFullscreen) {
            this.adapter.deregisterContentEventHandler('scroll', this.contentScrollHandler);
        }
        this.adapter.deregisterWindowEventHandler('resize', this.windowResizeHandler);
        this.adapter.deregisterWindowEventHandler('orientationchange', this.windowOrientationChangeHandler);
        cancelAnimationFrame(this.animationFrame);
        this.animationFrame = 0;
        clearTimeout(this.animationTimer);
        this.animationTimer = setTimeout(function () {
            _this.adapter.releaseFocus();
            _this.handleAnimationTimerEnd();
            _this.adapter.notifyClosed(action);
        }, _constants__WEBPACK_IMPORTED_MODULE_1__.numbers.DIALOG_ANIMATION_CLOSE_TIME_MS);
    };
    /**
     * Used only in instances of showing a secondary dialog over a full-screen
     * dialog. Shows the "surface scrim" displayed over the full-screen dialog.
     */
    MDCDialogFoundation.prototype.showSurfaceScrim = function () {
        var _this = this;
        this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SURFACE_SCRIM_SHOWING);
        this.runNextAnimationFrame(function () {
            _this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SURFACE_SCRIM_SHOWN);
        });
    };
    /**
     * Used only in instances of showing a secondary dialog over a full-screen
     * dialog. Hides the "surface scrim" displayed over the full-screen dialog.
     */
    MDCDialogFoundation.prototype.hideSurfaceScrim = function () {
        this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SURFACE_SCRIM_SHOWN);
        this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SURFACE_SCRIM_HIDING);
    };
    /**
     * Handles `transitionend` event triggered when surface scrim animation is
     * finished.
     */
    MDCDialogFoundation.prototype.handleSurfaceScrimTransitionEnd = function () {
        this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SURFACE_SCRIM_HIDING);
        this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SURFACE_SCRIM_SHOWING);
    };
    MDCDialogFoundation.prototype.isOpen = function () {
        return this.dialogOpen;
    };
    MDCDialogFoundation.prototype.getEscapeKeyAction = function () {
        return this.escapeKeyAction;
    };
    MDCDialogFoundation.prototype.setEscapeKeyAction = function (action) {
        this.escapeKeyAction = action;
    };
    MDCDialogFoundation.prototype.getScrimClickAction = function () {
        return this.scrimClickAction;
    };
    MDCDialogFoundation.prototype.setScrimClickAction = function (action) {
        this.scrimClickAction = action;
    };
    MDCDialogFoundation.prototype.getAutoStackButtons = function () {
        return this.autoStackButtons;
    };
    MDCDialogFoundation.prototype.setAutoStackButtons = function (autoStack) {
        this.autoStackButtons = autoStack;
    };
    MDCDialogFoundation.prototype.getSuppressDefaultPressSelector = function () {
        return this.suppressDefaultPressSelector;
    };
    MDCDialogFoundation.prototype.setSuppressDefaultPressSelector = function (selector) {
        this.suppressDefaultPressSelector = selector;
    };
    MDCDialogFoundation.prototype.layout = function () {
        var _this = this;
        this.animFrame.request(AnimationKeys.POLL_LAYOUT_CHANGE, function () {
            _this.layoutInternal();
        });
    };
    /** Handles click on the dialog root element. */
    MDCDialogFoundation.prototype.handleClick = function (evt) {
        var isScrim = this.adapter.eventTargetMatches(evt.target, _constants__WEBPACK_IMPORTED_MODULE_1__.strings.SCRIM_SELECTOR);
        // Check for scrim click first since it doesn't require querying ancestors.
        if (isScrim && this.scrimClickAction !== '') {
            this.close(this.scrimClickAction);
        }
        else {
            var action = this.adapter.getActionFromEvent(evt);
            if (action) {
                this.close(action);
            }
        }
    };
    /** Handles keydown on the dialog root element. */
    MDCDialogFoundation.prototype.handleKeydown = function (evt) {
        var isEnter = evt.key === 'Enter' || evt.keyCode === 13;
        if (!isEnter) {
            return;
        }
        var action = this.adapter.getActionFromEvent(evt);
        if (action) {
            // Action button callback is handled in `handleClick`,
            // since space/enter keydowns on buttons trigger click events.
            return;
        }
        // `composedPath` is used here, when available, to account for use cases
        // where a target meant to suppress the default press behaviour
        // may exist in a shadow root.
        // For example, a textarea inside a web component:
        // <mwc-dialog>
        //   <horizontal-layout>
        //     #shadow-root (open)
        //       <mwc-textarea>
        //         #shadow-root (open)
        //           <textarea></textarea>
        //       </mwc-textarea>
        //   </horizontal-layout>
        // </mwc-dialog>
        var target = evt.composedPath ? evt.composedPath()[0] : evt.target;
        var isDefault = this.suppressDefaultPressSelector ?
            !this.adapter.eventTargetMatches(target, this.suppressDefaultPressSelector) :
            true;
        if (isEnter && isDefault) {
            this.adapter.clickDefaultButton();
        }
    };
    /** Handles keydown on the document. */
    MDCDialogFoundation.prototype.handleDocumentKeydown = function (evt) {
        var isEscape = evt.key === 'Escape' || evt.keyCode === 27;
        if (isEscape && this.escapeKeyAction !== '') {
            this.close(this.escapeKeyAction);
        }
    };
    /**
     * Handles scroll event on the dialog's content element -- showing a scroll
     * divider on the header or footer based on the scroll position. This handler
     * should only be registered on full-screen dialogs with scrollable content.
     */
    MDCDialogFoundation.prototype.handleScrollEvent = function () {
        var _this = this;
        // Since scroll events can fire at a high rate, we throttle these events by
        // using requestAnimationFrame.
        this.animFrame.request(AnimationKeys.POLL_SCROLL_POS, function () {
            _this.toggleScrollDividerHeader();
            _this.toggleScrollDividerFooter();
        });
    };
    MDCDialogFoundation.prototype.layoutInternal = function () {
        if (this.autoStackButtons) {
            this.detectStackedButtons();
        }
        this.toggleScrollableClasses();
    };
    MDCDialogFoundation.prototype.handleAnimationTimerEnd = function () {
        this.animationTimer = 0;
        this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.OPENING);
        this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.CLOSING);
    };
    /**
     * Runs the given logic on the next animation frame, using setTimeout to
     * factor in Firefox reflow behavior.
     */
    MDCDialogFoundation.prototype.runNextAnimationFrame = function (callback) {
        var _this = this;
        cancelAnimationFrame(this.animationFrame);
        this.animationFrame = requestAnimationFrame(function () {
            _this.animationFrame = 0;
            clearTimeout(_this.animationTimer);
            _this.animationTimer = setTimeout(callback, 0);
        });
    };
    MDCDialogFoundation.prototype.detectStackedButtons = function () {
        // Remove the class first to let us measure the buttons' natural positions.
        this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.STACKED);
        var areButtonsStacked = this.adapter.areButtonsStacked();
        if (areButtonsStacked) {
            this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.STACKED);
        }
        if (areButtonsStacked !== this.areButtonsStacked) {
            this.adapter.reverseButtons();
            this.areButtonsStacked = areButtonsStacked;
        }
    };
    MDCDialogFoundation.prototype.toggleScrollableClasses = function () {
        // Remove the class first to let us measure the natural height of the
        // content.
        this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCROLLABLE);
        if (this.adapter.isContentScrollable()) {
            this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCROLLABLE);
            if (this.isFullscreen) {
                // If dialog is full-screen and scrollable, check if a scroll divider
                // should be shown.
                this.toggleScrollDividerHeader();
                this.toggleScrollDividerFooter();
            }
        }
    };
    MDCDialogFoundation.prototype.toggleScrollDividerHeader = function () {
        if (!this.adapter.isScrollableContentAtTop()) {
            this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCROLL_DIVIDER_HEADER);
        }
        else if (this.adapter.hasClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCROLL_DIVIDER_HEADER)) {
            this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCROLL_DIVIDER_HEADER);
        }
    };
    MDCDialogFoundation.prototype.toggleScrollDividerFooter = function () {
        if (!this.adapter.isScrollableContentAtBottom()) {
            this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCROLL_DIVIDER_FOOTER);
        }
        else if (this.adapter.hasClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCROLL_DIVIDER_FOOTER)) {
            this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.SCROLL_DIVIDER_FOOTER);
        }
    };
    return MDCDialogFoundation;
}(_material_base_foundation__WEBPACK_IMPORTED_MODULE_3__.MDCFoundation));

// tslint:disable-next-line:no-default-export Needed for backward compatibility with MDC Web v0.44.0 and earlier.
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MDCDialogFoundation);
//# sourceMappingURL=foundation.js.map

/***/ }),

/***/ "./node_modules/@material/dialog/util.js":
/*!***********************************************!*\
  !*** ./node_modules/@material/dialog/util.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "areTopsMisaligned": () => (/* binding */ areTopsMisaligned),
/* harmony export */   "createFocusTrapInstance": () => (/* binding */ createFocusTrapInstance),
/* harmony export */   "isScrollAtBottom": () => (/* binding */ isScrollAtBottom),
/* harmony export */   "isScrollAtTop": () => (/* binding */ isScrollAtTop),
/* harmony export */   "isScrollable": () => (/* binding */ isScrollable)
/* harmony export */ });
/**
 * @license
 * Copyright 2016 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
function createFocusTrapInstance(surfaceEl, focusTrapFactory, initialFocusEl) {
    return focusTrapFactory(surfaceEl, { initialFocusEl: initialFocusEl });
}
function isScrollable(el) {
    return el ? el.scrollHeight > el.offsetHeight : false;
}
/**
 * For scrollable content, returns true if the content has not been scrolled
 * (that is, the scroll content is as the "top"). This is used in full-screen
 * dialogs, where the scroll divider is expected only to appear once the
 * content has been scrolled "underneath" the header bar.
 */
function isScrollAtTop(el) {
    return el ? el.scrollTop === 0 : false;
}
/**
 * For scrollable content, returns true if the content has been scrolled all the
 * way to the bottom. This is used in full-screen dialogs, where the footer
 * scroll divider is expected only to appear when the content is "cut-off" by
 * the footer bar.
 */
function isScrollAtBottom(el) {
    return el ? Math.ceil(el.scrollHeight - el.scrollTop) === el.clientHeight :
        false;
}
function areTopsMisaligned(els) {
    var tops = new Set();
    [].forEach.call(els, function (el) { return tops.add(el.offsetTop); });
    return tops.size > 1;
}
//# sourceMappingURL=util.js.map

/***/ }),

/***/ "./node_modules/@material/dom/events.js":
/*!**********************************************!*\
  !*** ./node_modules/@material/dom/events.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "applyPassive": () => (/* binding */ applyPassive)
/* harmony export */ });
/**
 * @license
 * Copyright 2019 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
/**
 * Determine whether the current browser supports passive event listeners, and
 * if so, use them.
 */
function applyPassive(globalObj) {
    if (globalObj === void 0) { globalObj = window; }
    return supportsPassiveOption(globalObj) ?
        { passive: true } :
        false;
}
function supportsPassiveOption(globalObj) {
    if (globalObj === void 0) { globalObj = window; }
    // See
    // https://developer.mozilla.org/en-US/docs/Web/API/EventTarget/addEventListener
    var passiveSupported = false;
    try {
        var options = {
            // This function will be called when the browser
            // attempts to access the passive property.
            get passive() {
                passiveSupported = true;
                return false;
            }
        };
        var handler = function () { };
        globalObj.document.addEventListener('test', handler, options);
        globalObj.document.removeEventListener('test', handler, options);
    }
    catch (err) {
        passiveSupported = false;
    }
    return passiveSupported;
}
//# sourceMappingURL=events.js.map

/***/ }),

/***/ "./node_modules/@material/dom/focus-trap.js":
/*!**************************************************!*\
  !*** ./node_modules/@material/dom/focus-trap.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "FocusTrap": () => (/* binding */ FocusTrap)
/* harmony export */ });
/**
 * @license
 * Copyright 2020 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
var FOCUS_SENTINEL_CLASS = 'mdc-dom-focus-sentinel';
/**
 * Utility to trap focus in a given root element, e.g. for modal components such
 * as dialogs. The root should have at least one focusable child element,
 * for setting initial focus when trapping focus.
 * Also tracks the previously focused element, and restores focus to that
 * element when releasing focus.
 */
var FocusTrap = /** @class */ (function () {
    function FocusTrap(root, options) {
        if (options === void 0) { options = {}; }
        this.root = root;
        this.options = options;
        // Previously focused element before trapping focus.
        this.elFocusedBeforeTrapFocus = null;
    }
    /**
     * Traps focus in `root`. Also focuses on either `initialFocusEl` if set;
     * otherwises sets initial focus to the first focusable child element.
     */
    FocusTrap.prototype.trapFocus = function () {
        var focusableEls = this.getFocusableElements(this.root);
        if (focusableEls.length === 0) {
            throw new Error('FocusTrap: Element must have at least one focusable child.');
        }
        this.elFocusedBeforeTrapFocus =
            document.activeElement instanceof HTMLElement ? document.activeElement :
                null;
        this.wrapTabFocus(this.root);
        if (!this.options.skipInitialFocus) {
            this.focusInitialElement(focusableEls, this.options.initialFocusEl);
        }
    };
    /**
     * Releases focus from `root`. Also restores focus to the previously focused
     * element.
     */
    FocusTrap.prototype.releaseFocus = function () {
        [].slice.call(this.root.querySelectorAll("." + FOCUS_SENTINEL_CLASS))
            .forEach(function (sentinelEl) {
            sentinelEl.parentElement.removeChild(sentinelEl);
        });
        if (!this.options.skipRestoreFocus && this.elFocusedBeforeTrapFocus) {
            this.elFocusedBeforeTrapFocus.focus();
        }
    };
    /**
     * Wraps tab focus within `el` by adding two hidden sentinel divs which are
     * used to mark the beginning and the end of the tabbable region. When
     * focused, these sentinel elements redirect focus to the first/last
     * children elements of the tabbable region, ensuring that focus is trapped
     * within that region.
     */
    FocusTrap.prototype.wrapTabFocus = function (el) {
        var _this = this;
        var sentinelStart = this.createSentinel();
        var sentinelEnd = this.createSentinel();
        sentinelStart.addEventListener('focus', function () {
            var focusableEls = _this.getFocusableElements(el);
            if (focusableEls.length > 0) {
                focusableEls[focusableEls.length - 1].focus();
            }
        });
        sentinelEnd.addEventListener('focus', function () {
            var focusableEls = _this.getFocusableElements(el);
            if (focusableEls.length > 0) {
                focusableEls[0].focus();
            }
        });
        el.insertBefore(sentinelStart, el.children[0]);
        el.appendChild(sentinelEnd);
    };
    /**
     * Focuses on `initialFocusEl` if defined and a child of the root element.
     * Otherwise, focuses on the first focusable child element of the root.
     */
    FocusTrap.prototype.focusInitialElement = function (focusableEls, initialFocusEl) {
        var focusIndex = 0;
        if (initialFocusEl) {
            focusIndex = Math.max(focusableEls.indexOf(initialFocusEl), 0);
        }
        focusableEls[focusIndex].focus();
    };
    FocusTrap.prototype.getFocusableElements = function (root) {
        var focusableEls = [].slice.call(root.querySelectorAll('[autofocus], [tabindex], a, input, textarea, select, button'));
        return focusableEls.filter(function (el) {
            var isDisabledOrHidden = el.getAttribute('aria-disabled') === 'true' ||
                el.getAttribute('disabled') != null ||
                el.getAttribute('hidden') != null ||
                el.getAttribute('aria-hidden') === 'true';
            var isTabbableAndVisible = el.tabIndex >= 0 &&
                el.getBoundingClientRect().width > 0 &&
                !el.classList.contains(FOCUS_SENTINEL_CLASS) && !isDisabledOrHidden;
            var isProgrammaticallyHidden = false;
            if (isTabbableAndVisible) {
                var style = getComputedStyle(el);
                isProgrammaticallyHidden =
                    style.display === 'none' || style.visibility === 'hidden';
            }
            return isTabbableAndVisible && !isProgrammaticallyHidden;
        });
    };
    FocusTrap.prototype.createSentinel = function () {
        var sentinel = document.createElement('div');
        sentinel.setAttribute('tabindex', '0');
        // Don't announce in screen readers.
        sentinel.setAttribute('aria-hidden', 'true');
        sentinel.classList.add(FOCUS_SENTINEL_CLASS);
        return sentinel;
    };
    return FocusTrap;
}());

//# sourceMappingURL=focus-trap.js.map

/***/ }),

/***/ "./node_modules/@material/dom/ponyfill.js":
/*!************************************************!*\
  !*** ./node_modules/@material/dom/ponyfill.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "closest": () => (/* binding */ closest),
/* harmony export */   "estimateScrollWidth": () => (/* binding */ estimateScrollWidth),
/* harmony export */   "matches": () => (/* binding */ matches)
/* harmony export */ });
/**
 * @license
 * Copyright 2018 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
/**
 * @fileoverview A "ponyfill" is a polyfill that doesn't modify the global prototype chain.
 * This makes ponyfills safer than traditional polyfills, especially for libraries like MDC.
 */
function closest(element, selector) {
    if (element.closest) {
        return element.closest(selector);
    }
    var el = element;
    while (el) {
        if (matches(el, selector)) {
            return el;
        }
        el = el.parentElement;
    }
    return null;
}
function matches(element, selector) {
    var nativeMatches = element.matches
        || element.webkitMatchesSelector
        || element.msMatchesSelector;
    return nativeMatches.call(element, selector);
}
/**
 * Used to compute the estimated scroll width of elements. When an element is
 * hidden due to display: none; being applied to a parent element, the width is
 * returned as 0. However, the element will have a true width once no longer
 * inside a display: none context. This method computes an estimated width when
 * the element is hidden or returns the true width when the element is visble.
 * @param {Element} element the element whose width to estimate
 */
function estimateScrollWidth(element) {
    // Check the offsetParent. If the element inherits display: none from any
    // parent, the offsetParent property will be null (see
    // https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/offsetParent).
    // This check ensures we only clone the node when necessary.
    var htmlEl = element;
    if (htmlEl.offsetParent !== null) {
        return htmlEl.scrollWidth;
    }
    var clone = htmlEl.cloneNode(true);
    clone.style.setProperty('position', 'absolute');
    clone.style.setProperty('transform', 'translate(-9999px, -9999px)');
    document.documentElement.appendChild(clone);
    var scrollWidth = clone.scrollWidth;
    document.documentElement.removeChild(clone);
    return scrollWidth;
}
//# sourceMappingURL=ponyfill.js.map

/***/ }),

/***/ "./node_modules/@material/floating-label/constants.js":
/*!************************************************************!*\
  !*** ./node_modules/@material/floating-label/constants.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "cssClasses": () => (/* binding */ cssClasses)
/* harmony export */ });
/**
 * @license
 * Copyright 2016 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
var cssClasses = {
    LABEL_FLOAT_ABOVE: 'mdc-floating-label--float-above',
    LABEL_REQUIRED: 'mdc-floating-label--required',
    LABEL_SHAKE: 'mdc-floating-label--shake',
    ROOT: 'mdc-floating-label',
};
//# sourceMappingURL=constants.js.map

/***/ }),

/***/ "./node_modules/@material/floating-label/foundation.js":
/*!*************************************************************!*\
  !*** ./node_modules/@material/floating-label/foundation.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "MDCFloatingLabelFoundation": () => (/* binding */ MDCFloatingLabelFoundation),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _material_base_foundation__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @material/base/foundation */ "./node_modules/@material/base/foundation.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./constants */ "./node_modules/@material/floating-label/constants.js");
/**
 * @license
 * Copyright 2016 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */



var MDCFloatingLabelFoundation = /** @class */ (function (_super) {
    (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__extends)(MDCFloatingLabelFoundation, _super);
    function MDCFloatingLabelFoundation(adapter) {
        var _this = _super.call(this, (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)((0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)({}, MDCFloatingLabelFoundation.defaultAdapter), adapter)) || this;
        _this.shakeAnimationEndHandler_ = function () { return _this.handleShakeAnimationEnd_(); };
        return _this;
    }
    Object.defineProperty(MDCFloatingLabelFoundation, "cssClasses", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCFloatingLabelFoundation, "defaultAdapter", {
        /**
         * See {@link MDCFloatingLabelAdapter} for typing information on parameters and return types.
         */
        get: function () {
            // tslint:disable:object-literal-sort-keys Methods should be in the same order as the adapter interface.
            return {
                addClass: function () { return undefined; },
                removeClass: function () { return undefined; },
                getWidth: function () { return 0; },
                registerInteractionHandler: function () { return undefined; },
                deregisterInteractionHandler: function () { return undefined; },
            };
            // tslint:enable:object-literal-sort-keys
        },
        enumerable: false,
        configurable: true
    });
    MDCFloatingLabelFoundation.prototype.init = function () {
        this.adapter.registerInteractionHandler('animationend', this.shakeAnimationEndHandler_);
    };
    MDCFloatingLabelFoundation.prototype.destroy = function () {
        this.adapter.deregisterInteractionHandler('animationend', this.shakeAnimationEndHandler_);
    };
    /**
     * Returns the width of the label element.
     */
    MDCFloatingLabelFoundation.prototype.getWidth = function () {
        return this.adapter.getWidth();
    };
    /**
     * Styles the label to produce a shake animation to indicate an error.
     * @param shouldShake If true, adds the shake CSS class; otherwise, removes shake class.
     */
    MDCFloatingLabelFoundation.prototype.shake = function (shouldShake) {
        var LABEL_SHAKE = MDCFloatingLabelFoundation.cssClasses.LABEL_SHAKE;
        if (shouldShake) {
            this.adapter.addClass(LABEL_SHAKE);
        }
        else {
            this.adapter.removeClass(LABEL_SHAKE);
        }
    };
    /**
     * Styles the label to float or dock.
     * @param shouldFloat If true, adds the float CSS class; otherwise, removes float and shake classes to dock the label.
     */
    MDCFloatingLabelFoundation.prototype.float = function (shouldFloat) {
        var _a = MDCFloatingLabelFoundation.cssClasses, LABEL_FLOAT_ABOVE = _a.LABEL_FLOAT_ABOVE, LABEL_SHAKE = _a.LABEL_SHAKE;
        if (shouldFloat) {
            this.adapter.addClass(LABEL_FLOAT_ABOVE);
        }
        else {
            this.adapter.removeClass(LABEL_FLOAT_ABOVE);
            this.adapter.removeClass(LABEL_SHAKE);
        }
    };
    /**
     * Styles the label as required.
     * @param isRequired If true, adds an asterisk to the label, indicating that it is required.
     */
    MDCFloatingLabelFoundation.prototype.setRequired = function (isRequired) {
        var LABEL_REQUIRED = MDCFloatingLabelFoundation.cssClasses.LABEL_REQUIRED;
        if (isRequired) {
            this.adapter.addClass(LABEL_REQUIRED);
        }
        else {
            this.adapter.removeClass(LABEL_REQUIRED);
        }
    };
    MDCFloatingLabelFoundation.prototype.handleShakeAnimationEnd_ = function () {
        var LABEL_SHAKE = MDCFloatingLabelFoundation.cssClasses.LABEL_SHAKE;
        this.adapter.removeClass(LABEL_SHAKE);
    };
    return MDCFloatingLabelFoundation;
}(_material_base_foundation__WEBPACK_IMPORTED_MODULE_2__.MDCFoundation));

// tslint:disable-next-line:no-default-export Needed for backward compatibility with MDC Web v0.44.0 and earlier.
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MDCFloatingLabelFoundation);
//# sourceMappingURL=foundation.js.map

/***/ }),

/***/ "./node_modules/@material/line-ripple/constants.js":
/*!*********************************************************!*\
  !*** ./node_modules/@material/line-ripple/constants.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "cssClasses": () => (/* binding */ cssClasses)
/* harmony export */ });
/**
 * @license
 * Copyright 2018 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
var cssClasses = {
    LINE_RIPPLE_ACTIVE: 'mdc-line-ripple--active',
    LINE_RIPPLE_DEACTIVATING: 'mdc-line-ripple--deactivating',
};

//# sourceMappingURL=constants.js.map

/***/ }),

/***/ "./node_modules/@material/line-ripple/foundation.js":
/*!**********************************************************!*\
  !*** ./node_modules/@material/line-ripple/foundation.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "MDCLineRippleFoundation": () => (/* binding */ MDCLineRippleFoundation),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _material_base_foundation__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @material/base/foundation */ "./node_modules/@material/base/foundation.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./constants */ "./node_modules/@material/line-ripple/constants.js");
/**
 * @license
 * Copyright 2018 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */



var MDCLineRippleFoundation = /** @class */ (function (_super) {
    (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__extends)(MDCLineRippleFoundation, _super);
    function MDCLineRippleFoundation(adapter) {
        var _this = _super.call(this, (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)((0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)({}, MDCLineRippleFoundation.defaultAdapter), adapter)) || this;
        _this.transitionEndHandler_ = function (evt) { return _this.handleTransitionEnd(evt); };
        return _this;
    }
    Object.defineProperty(MDCLineRippleFoundation, "cssClasses", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCLineRippleFoundation, "defaultAdapter", {
        /**
         * See {@link MDCLineRippleAdapter} for typing information on parameters and return types.
         */
        get: function () {
            // tslint:disable:object-literal-sort-keys Methods should be in the same order as the adapter interface.
            return {
                addClass: function () { return undefined; },
                removeClass: function () { return undefined; },
                hasClass: function () { return false; },
                setStyle: function () { return undefined; },
                registerEventHandler: function () { return undefined; },
                deregisterEventHandler: function () { return undefined; },
            };
            // tslint:enable:object-literal-sort-keys
        },
        enumerable: false,
        configurable: true
    });
    MDCLineRippleFoundation.prototype.init = function () {
        this.adapter.registerEventHandler('transitionend', this.transitionEndHandler_);
    };
    MDCLineRippleFoundation.prototype.destroy = function () {
        this.adapter.deregisterEventHandler('transitionend', this.transitionEndHandler_);
    };
    MDCLineRippleFoundation.prototype.activate = function () {
        this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.LINE_RIPPLE_DEACTIVATING);
        this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.LINE_RIPPLE_ACTIVE);
    };
    MDCLineRippleFoundation.prototype.setRippleCenter = function (xCoordinate) {
        this.adapter.setStyle('transform-origin', xCoordinate + "px center");
    };
    MDCLineRippleFoundation.prototype.deactivate = function () {
        this.adapter.addClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.LINE_RIPPLE_DEACTIVATING);
    };
    MDCLineRippleFoundation.prototype.handleTransitionEnd = function (evt) {
        // Wait for the line ripple to be either transparent or opaque
        // before emitting the animation end event
        var isDeactivating = this.adapter.hasClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.LINE_RIPPLE_DEACTIVATING);
        if (evt.propertyName === 'opacity') {
            if (isDeactivating) {
                this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.LINE_RIPPLE_ACTIVE);
                this.adapter.removeClass(_constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses.LINE_RIPPLE_DEACTIVATING);
            }
        }
    };
    return MDCLineRippleFoundation;
}(_material_base_foundation__WEBPACK_IMPORTED_MODULE_2__.MDCFoundation));

// tslint:disable-next-line:no-default-export Needed for backward compatibility with MDC Web v0.44.0 and earlier.
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MDCLineRippleFoundation);
//# sourceMappingURL=foundation.js.map

/***/ }),

/***/ "./node_modules/@material/notched-outline/constants.js":
/*!*************************************************************!*\
  !*** ./node_modules/@material/notched-outline/constants.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "cssClasses": () => (/* binding */ cssClasses),
/* harmony export */   "numbers": () => (/* binding */ numbers),
/* harmony export */   "strings": () => (/* binding */ strings)
/* harmony export */ });
/**
 * @license
 * Copyright 2018 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
var strings = {
    NOTCH_ELEMENT_SELECTOR: '.mdc-notched-outline__notch',
};
var numbers = {
    // This should stay in sync with $mdc-notched-outline-padding * 2.
    NOTCH_ELEMENT_PADDING: 8,
};
var cssClasses = {
    NO_LABEL: 'mdc-notched-outline--no-label',
    OUTLINE_NOTCHED: 'mdc-notched-outline--notched',
    OUTLINE_UPGRADED: 'mdc-notched-outline--upgraded',
};

//# sourceMappingURL=constants.js.map

/***/ }),

/***/ "./node_modules/@material/notched-outline/foundation.js":
/*!**************************************************************!*\
  !*** ./node_modules/@material/notched-outline/foundation.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "MDCNotchedOutlineFoundation": () => (/* binding */ MDCNotchedOutlineFoundation),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _material_base_foundation__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @material/base/foundation */ "./node_modules/@material/base/foundation.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./constants */ "./node_modules/@material/notched-outline/constants.js");
/**
 * @license
 * Copyright 2017 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */



var MDCNotchedOutlineFoundation = /** @class */ (function (_super) {
    (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__extends)(MDCNotchedOutlineFoundation, _super);
    function MDCNotchedOutlineFoundation(adapter) {
        return _super.call(this, (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)((0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)({}, MDCNotchedOutlineFoundation.defaultAdapter), adapter)) || this;
    }
    Object.defineProperty(MDCNotchedOutlineFoundation, "strings", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.strings;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCNotchedOutlineFoundation, "cssClasses", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCNotchedOutlineFoundation, "numbers", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.numbers;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCNotchedOutlineFoundation, "defaultAdapter", {
        /**
         * See {@link MDCNotchedOutlineAdapter} for typing information on parameters and return types.
         */
        get: function () {
            // tslint:disable:object-literal-sort-keys Methods should be in the same order as the adapter interface.
            return {
                addClass: function () { return undefined; },
                removeClass: function () { return undefined; },
                setNotchWidthProperty: function () { return undefined; },
                removeNotchWidthProperty: function () { return undefined; },
            };
            // tslint:enable:object-literal-sort-keys
        },
        enumerable: false,
        configurable: true
    });
    /**
     * Adds the outline notched selector and updates the notch width calculated based off of notchWidth.
     */
    MDCNotchedOutlineFoundation.prototype.notch = function (notchWidth) {
        var OUTLINE_NOTCHED = MDCNotchedOutlineFoundation.cssClasses.OUTLINE_NOTCHED;
        if (notchWidth > 0) {
            notchWidth += _constants__WEBPACK_IMPORTED_MODULE_1__.numbers.NOTCH_ELEMENT_PADDING; // Add padding from left/right.
        }
        this.adapter.setNotchWidthProperty(notchWidth);
        this.adapter.addClass(OUTLINE_NOTCHED);
    };
    /**
     * Removes notched outline selector to close the notch in the outline.
     */
    MDCNotchedOutlineFoundation.prototype.closeNotch = function () {
        var OUTLINE_NOTCHED = MDCNotchedOutlineFoundation.cssClasses.OUTLINE_NOTCHED;
        this.adapter.removeClass(OUTLINE_NOTCHED);
        this.adapter.removeNotchWidthProperty();
    };
    return MDCNotchedOutlineFoundation;
}(_material_base_foundation__WEBPACK_IMPORTED_MODULE_2__.MDCFoundation));

// tslint:disable-next-line:no-default-export Needed for backward compatibility with MDC Web v0.44.0 and earlier.
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MDCNotchedOutlineFoundation);
//# sourceMappingURL=foundation.js.map

/***/ }),

/***/ "./node_modules/@material/ripple/constants.js":
/*!****************************************************!*\
  !*** ./node_modules/@material/ripple/constants.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "cssClasses": () => (/* binding */ cssClasses),
/* harmony export */   "numbers": () => (/* binding */ numbers),
/* harmony export */   "strings": () => (/* binding */ strings)
/* harmony export */ });
/**
 * @license
 * Copyright 2016 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
var cssClasses = {
    // Ripple is a special case where the "root" component is really a "mixin" of sorts,
    // given that it's an 'upgrade' to an existing component. That being said it is the root
    // CSS class that all other CSS classes derive from.
    BG_FOCUSED: 'mdc-ripple-upgraded--background-focused',
    FG_ACTIVATION: 'mdc-ripple-upgraded--foreground-activation',
    FG_DEACTIVATION: 'mdc-ripple-upgraded--foreground-deactivation',
    ROOT: 'mdc-ripple-upgraded',
    UNBOUNDED: 'mdc-ripple-upgraded--unbounded',
};
var strings = {
    VAR_FG_SCALE: '--mdc-ripple-fg-scale',
    VAR_FG_SIZE: '--mdc-ripple-fg-size',
    VAR_FG_TRANSLATE_END: '--mdc-ripple-fg-translate-end',
    VAR_FG_TRANSLATE_START: '--mdc-ripple-fg-translate-start',
    VAR_LEFT: '--mdc-ripple-left',
    VAR_TOP: '--mdc-ripple-top',
};
var numbers = {
    DEACTIVATION_TIMEOUT_MS: 225,
    FG_DEACTIVATION_MS: 150,
    INITIAL_ORIGIN_SCALE: 0.6,
    PADDING: 10,
    TAP_DELAY_MS: 300, // Delay between touch and simulated mouse events on touch devices
};
//# sourceMappingURL=constants.js.map

/***/ }),

/***/ "./node_modules/@material/ripple/foundation.js":
/*!*****************************************************!*\
  !*** ./node_modules/@material/ripple/foundation.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "MDCRippleFoundation": () => (/* binding */ MDCRippleFoundation),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _material_base_foundation__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @material/base/foundation */ "./node_modules/@material/base/foundation.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./constants */ "./node_modules/@material/ripple/constants.js");
/* harmony import */ var _util__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./util */ "./node_modules/@material/ripple/util.js");
/**
 * @license
 * Copyright 2016 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */




// Activation events registered on the root element of each instance for activation
var ACTIVATION_EVENT_TYPES = [
    'touchstart', 'pointerdown', 'mousedown', 'keydown',
];
// Deactivation events registered on documentElement when a pointer-related down event occurs
var POINTER_DEACTIVATION_EVENT_TYPES = [
    'touchend', 'pointerup', 'mouseup', 'contextmenu',
];
// simultaneous nested activations
var activatedTargets = [];
var MDCRippleFoundation = /** @class */ (function (_super) {
    (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__extends)(MDCRippleFoundation, _super);
    function MDCRippleFoundation(adapter) {
        var _this = _super.call(this, (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)((0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)({}, MDCRippleFoundation.defaultAdapter), adapter)) || this;
        _this.activationAnimationHasEnded_ = false;
        _this.activationTimer_ = 0;
        _this.fgDeactivationRemovalTimer_ = 0;
        _this.fgScale_ = '0';
        _this.frame_ = { width: 0, height: 0 };
        _this.initialSize_ = 0;
        _this.layoutFrame_ = 0;
        _this.maxRadius_ = 0;
        _this.unboundedCoords_ = { left: 0, top: 0 };
        _this.activationState_ = _this.defaultActivationState_();
        _this.activationTimerCallback_ = function () {
            _this.activationAnimationHasEnded_ = true;
            _this.runDeactivationUXLogicIfReady_();
        };
        _this.activateHandler_ = function (e) { return _this.activate_(e); };
        _this.deactivateHandler_ = function () { return _this.deactivate_(); };
        _this.focusHandler_ = function () { return _this.handleFocus(); };
        _this.blurHandler_ = function () { return _this.handleBlur(); };
        _this.resizeHandler_ = function () { return _this.layout(); };
        return _this;
    }
    Object.defineProperty(MDCRippleFoundation, "cssClasses", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCRippleFoundation, "strings", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.strings;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCRippleFoundation, "numbers", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.numbers;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCRippleFoundation, "defaultAdapter", {
        get: function () {
            return {
                addClass: function () { return undefined; },
                browserSupportsCssVars: function () { return true; },
                computeBoundingRect: function () { return ({ top: 0, right: 0, bottom: 0, left: 0, width: 0, height: 0 }); },
                containsEventTarget: function () { return true; },
                deregisterDocumentInteractionHandler: function () { return undefined; },
                deregisterInteractionHandler: function () { return undefined; },
                deregisterResizeHandler: function () { return undefined; },
                getWindowPageOffset: function () { return ({ x: 0, y: 0 }); },
                isSurfaceActive: function () { return true; },
                isSurfaceDisabled: function () { return true; },
                isUnbounded: function () { return true; },
                registerDocumentInteractionHandler: function () { return undefined; },
                registerInteractionHandler: function () { return undefined; },
                registerResizeHandler: function () { return undefined; },
                removeClass: function () { return undefined; },
                updateCssVariable: function () { return undefined; },
            };
        },
        enumerable: false,
        configurable: true
    });
    MDCRippleFoundation.prototype.init = function () {
        var _this = this;
        var supportsPressRipple = this.supportsPressRipple_();
        this.registerRootHandlers_(supportsPressRipple);
        if (supportsPressRipple) {
            var _a = MDCRippleFoundation.cssClasses, ROOT_1 = _a.ROOT, UNBOUNDED_1 = _a.UNBOUNDED;
            requestAnimationFrame(function () {
                _this.adapter.addClass(ROOT_1);
                if (_this.adapter.isUnbounded()) {
                    _this.adapter.addClass(UNBOUNDED_1);
                    // Unbounded ripples need layout logic applied immediately to set coordinates for both shade and ripple
                    _this.layoutInternal_();
                }
            });
        }
    };
    MDCRippleFoundation.prototype.destroy = function () {
        var _this = this;
        if (this.supportsPressRipple_()) {
            if (this.activationTimer_) {
                clearTimeout(this.activationTimer_);
                this.activationTimer_ = 0;
                this.adapter.removeClass(MDCRippleFoundation.cssClasses.FG_ACTIVATION);
            }
            if (this.fgDeactivationRemovalTimer_) {
                clearTimeout(this.fgDeactivationRemovalTimer_);
                this.fgDeactivationRemovalTimer_ = 0;
                this.adapter.removeClass(MDCRippleFoundation.cssClasses.FG_DEACTIVATION);
            }
            var _a = MDCRippleFoundation.cssClasses, ROOT_2 = _a.ROOT, UNBOUNDED_2 = _a.UNBOUNDED;
            requestAnimationFrame(function () {
                _this.adapter.removeClass(ROOT_2);
                _this.adapter.removeClass(UNBOUNDED_2);
                _this.removeCssVars_();
            });
        }
        this.deregisterRootHandlers_();
        this.deregisterDeactivationHandlers_();
    };
    /**
     * @param evt Optional event containing position information.
     */
    MDCRippleFoundation.prototype.activate = function (evt) {
        this.activate_(evt);
    };
    MDCRippleFoundation.prototype.deactivate = function () {
        this.deactivate_();
    };
    MDCRippleFoundation.prototype.layout = function () {
        var _this = this;
        if (this.layoutFrame_) {
            cancelAnimationFrame(this.layoutFrame_);
        }
        this.layoutFrame_ = requestAnimationFrame(function () {
            _this.layoutInternal_();
            _this.layoutFrame_ = 0;
        });
    };
    MDCRippleFoundation.prototype.setUnbounded = function (unbounded) {
        var UNBOUNDED = MDCRippleFoundation.cssClasses.UNBOUNDED;
        if (unbounded) {
            this.adapter.addClass(UNBOUNDED);
        }
        else {
            this.adapter.removeClass(UNBOUNDED);
        }
    };
    MDCRippleFoundation.prototype.handleFocus = function () {
        var _this = this;
        requestAnimationFrame(function () { return _this.adapter.addClass(MDCRippleFoundation.cssClasses.BG_FOCUSED); });
    };
    MDCRippleFoundation.prototype.handleBlur = function () {
        var _this = this;
        requestAnimationFrame(function () { return _this.adapter.removeClass(MDCRippleFoundation.cssClasses.BG_FOCUSED); });
    };
    /**
     * We compute this property so that we are not querying information about the client
     * until the point in time where the foundation requests it. This prevents scenarios where
     * client-side feature-detection may happen too early, such as when components are rendered on the server
     * and then initialized at mount time on the client.
     */
    MDCRippleFoundation.prototype.supportsPressRipple_ = function () {
        return this.adapter.browserSupportsCssVars();
    };
    MDCRippleFoundation.prototype.defaultActivationState_ = function () {
        return {
            activationEvent: undefined,
            hasDeactivationUXRun: false,
            isActivated: false,
            isProgrammatic: false,
            wasActivatedByPointer: false,
            wasElementMadeActive: false,
        };
    };
    /**
     * supportsPressRipple Passed from init to save a redundant function call
     */
    MDCRippleFoundation.prototype.registerRootHandlers_ = function (supportsPressRipple) {
        var _this = this;
        if (supportsPressRipple) {
            ACTIVATION_EVENT_TYPES.forEach(function (evtType) {
                _this.adapter.registerInteractionHandler(evtType, _this.activateHandler_);
            });
            if (this.adapter.isUnbounded()) {
                this.adapter.registerResizeHandler(this.resizeHandler_);
            }
        }
        this.adapter.registerInteractionHandler('focus', this.focusHandler_);
        this.adapter.registerInteractionHandler('blur', this.blurHandler_);
    };
    MDCRippleFoundation.prototype.registerDeactivationHandlers_ = function (evt) {
        var _this = this;
        if (evt.type === 'keydown') {
            this.adapter.registerInteractionHandler('keyup', this.deactivateHandler_);
        }
        else {
            POINTER_DEACTIVATION_EVENT_TYPES.forEach(function (evtType) {
                _this.adapter.registerDocumentInteractionHandler(evtType, _this.deactivateHandler_);
            });
        }
    };
    MDCRippleFoundation.prototype.deregisterRootHandlers_ = function () {
        var _this = this;
        ACTIVATION_EVENT_TYPES.forEach(function (evtType) {
            _this.adapter.deregisterInteractionHandler(evtType, _this.activateHandler_);
        });
        this.adapter.deregisterInteractionHandler('focus', this.focusHandler_);
        this.adapter.deregisterInteractionHandler('blur', this.blurHandler_);
        if (this.adapter.isUnbounded()) {
            this.adapter.deregisterResizeHandler(this.resizeHandler_);
        }
    };
    MDCRippleFoundation.prototype.deregisterDeactivationHandlers_ = function () {
        var _this = this;
        this.adapter.deregisterInteractionHandler('keyup', this.deactivateHandler_);
        POINTER_DEACTIVATION_EVENT_TYPES.forEach(function (evtType) {
            _this.adapter.deregisterDocumentInteractionHandler(evtType, _this.deactivateHandler_);
        });
    };
    MDCRippleFoundation.prototype.removeCssVars_ = function () {
        var _this = this;
        var rippleStrings = MDCRippleFoundation.strings;
        var keys = Object.keys(rippleStrings);
        keys.forEach(function (key) {
            if (key.indexOf('VAR_') === 0) {
                _this.adapter.updateCssVariable(rippleStrings[key], null);
            }
        });
    };
    MDCRippleFoundation.prototype.activate_ = function (evt) {
        var _this = this;
        if (this.adapter.isSurfaceDisabled()) {
            return;
        }
        var activationState = this.activationState_;
        if (activationState.isActivated) {
            return;
        }
        // Avoid reacting to follow-on events fired by touch device after an already-processed user interaction
        var previousActivationEvent = this.previousActivationEvent_;
        var isSameInteraction = previousActivationEvent && evt !== undefined && previousActivationEvent.type !== evt.type;
        if (isSameInteraction) {
            return;
        }
        activationState.isActivated = true;
        activationState.isProgrammatic = evt === undefined;
        activationState.activationEvent = evt;
        activationState.wasActivatedByPointer = activationState.isProgrammatic ? false : evt !== undefined && (evt.type === 'mousedown' || evt.type === 'touchstart' || evt.type === 'pointerdown');
        var hasActivatedChild = evt !== undefined &&
            activatedTargets.length > 0 &&
            activatedTargets.some(function (target) { return _this.adapter.containsEventTarget(target); });
        if (hasActivatedChild) {
            // Immediately reset activation state, while preserving logic that prevents touch follow-on events
            this.resetActivationState_();
            return;
        }
        if (evt !== undefined) {
            activatedTargets.push(evt.target);
            this.registerDeactivationHandlers_(evt);
        }
        activationState.wasElementMadeActive = this.checkElementMadeActive_(evt);
        if (activationState.wasElementMadeActive) {
            this.animateActivation_();
        }
        requestAnimationFrame(function () {
            // Reset array on next frame after the current event has had a chance to bubble to prevent ancestor ripples
            activatedTargets = [];
            if (!activationState.wasElementMadeActive
                && evt !== undefined
                && (evt.key === ' ' || evt.keyCode === 32)) {
                // If space was pressed, try again within an rAF call to detect :active, because different UAs report
                // active states inconsistently when they're called within event handling code:
                // - https://bugs.chromium.org/p/chromium/issues/detail?id=635971
                // - https://bugzilla.mozilla.org/show_bug.cgi?id=1293741
                // We try first outside rAF to support Edge, which does not exhibit this problem, but will crash if a CSS
                // variable is set within a rAF callback for a submit button interaction (#2241).
                activationState.wasElementMadeActive = _this.checkElementMadeActive_(evt);
                if (activationState.wasElementMadeActive) {
                    _this.animateActivation_();
                }
            }
            if (!activationState.wasElementMadeActive) {
                // Reset activation state immediately if element was not made active.
                _this.activationState_ = _this.defaultActivationState_();
            }
        });
    };
    MDCRippleFoundation.prototype.checkElementMadeActive_ = function (evt) {
        return (evt !== undefined && evt.type === 'keydown') ?
            this.adapter.isSurfaceActive() :
            true;
    };
    MDCRippleFoundation.prototype.animateActivation_ = function () {
        var _this = this;
        var _a = MDCRippleFoundation.strings, VAR_FG_TRANSLATE_START = _a.VAR_FG_TRANSLATE_START, VAR_FG_TRANSLATE_END = _a.VAR_FG_TRANSLATE_END;
        var _b = MDCRippleFoundation.cssClasses, FG_DEACTIVATION = _b.FG_DEACTIVATION, FG_ACTIVATION = _b.FG_ACTIVATION;
        var DEACTIVATION_TIMEOUT_MS = MDCRippleFoundation.numbers.DEACTIVATION_TIMEOUT_MS;
        this.layoutInternal_();
        var translateStart = '';
        var translateEnd = '';
        if (!this.adapter.isUnbounded()) {
            var _c = this.getFgTranslationCoordinates_(), startPoint = _c.startPoint, endPoint = _c.endPoint;
            translateStart = startPoint.x + "px, " + startPoint.y + "px";
            translateEnd = endPoint.x + "px, " + endPoint.y + "px";
        }
        this.adapter.updateCssVariable(VAR_FG_TRANSLATE_START, translateStart);
        this.adapter.updateCssVariable(VAR_FG_TRANSLATE_END, translateEnd);
        // Cancel any ongoing activation/deactivation animations
        clearTimeout(this.activationTimer_);
        clearTimeout(this.fgDeactivationRemovalTimer_);
        this.rmBoundedActivationClasses_();
        this.adapter.removeClass(FG_DEACTIVATION);
        // Force layout in order to re-trigger the animation.
        this.adapter.computeBoundingRect();
        this.adapter.addClass(FG_ACTIVATION);
        this.activationTimer_ = setTimeout(function () { return _this.activationTimerCallback_(); }, DEACTIVATION_TIMEOUT_MS);
    };
    MDCRippleFoundation.prototype.getFgTranslationCoordinates_ = function () {
        var _a = this.activationState_, activationEvent = _a.activationEvent, wasActivatedByPointer = _a.wasActivatedByPointer;
        var startPoint;
        if (wasActivatedByPointer) {
            startPoint = (0,_util__WEBPACK_IMPORTED_MODULE_2__.getNormalizedEventCoords)(activationEvent, this.adapter.getWindowPageOffset(), this.adapter.computeBoundingRect());
        }
        else {
            startPoint = {
                x: this.frame_.width / 2,
                y: this.frame_.height / 2,
            };
        }
        // Center the element around the start point.
        startPoint = {
            x: startPoint.x - (this.initialSize_ / 2),
            y: startPoint.y - (this.initialSize_ / 2),
        };
        var endPoint = {
            x: (this.frame_.width / 2) - (this.initialSize_ / 2),
            y: (this.frame_.height / 2) - (this.initialSize_ / 2),
        };
        return { startPoint: startPoint, endPoint: endPoint };
    };
    MDCRippleFoundation.prototype.runDeactivationUXLogicIfReady_ = function () {
        var _this = this;
        // This method is called both when a pointing device is released, and when the activation animation ends.
        // The deactivation animation should only run after both of those occur.
        var FG_DEACTIVATION = MDCRippleFoundation.cssClasses.FG_DEACTIVATION;
        var _a = this.activationState_, hasDeactivationUXRun = _a.hasDeactivationUXRun, isActivated = _a.isActivated;
        var activationHasEnded = hasDeactivationUXRun || !isActivated;
        if (activationHasEnded && this.activationAnimationHasEnded_) {
            this.rmBoundedActivationClasses_();
            this.adapter.addClass(FG_DEACTIVATION);
            this.fgDeactivationRemovalTimer_ = setTimeout(function () {
                _this.adapter.removeClass(FG_DEACTIVATION);
            }, _constants__WEBPACK_IMPORTED_MODULE_1__.numbers.FG_DEACTIVATION_MS);
        }
    };
    MDCRippleFoundation.prototype.rmBoundedActivationClasses_ = function () {
        var FG_ACTIVATION = MDCRippleFoundation.cssClasses.FG_ACTIVATION;
        this.adapter.removeClass(FG_ACTIVATION);
        this.activationAnimationHasEnded_ = false;
        this.adapter.computeBoundingRect();
    };
    MDCRippleFoundation.prototype.resetActivationState_ = function () {
        var _this = this;
        this.previousActivationEvent_ = this.activationState_.activationEvent;
        this.activationState_ = this.defaultActivationState_();
        // Touch devices may fire additional events for the same interaction within a short time.
        // Store the previous event until it's safe to assume that subsequent events are for new interactions.
        setTimeout(function () { return _this.previousActivationEvent_ = undefined; }, MDCRippleFoundation.numbers.TAP_DELAY_MS);
    };
    MDCRippleFoundation.prototype.deactivate_ = function () {
        var _this = this;
        var activationState = this.activationState_;
        // This can happen in scenarios such as when you have a keyup event that blurs the element.
        if (!activationState.isActivated) {
            return;
        }
        var state = (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)({}, activationState);
        if (activationState.isProgrammatic) {
            requestAnimationFrame(function () { return _this.animateDeactivation_(state); });
            this.resetActivationState_();
        }
        else {
            this.deregisterDeactivationHandlers_();
            requestAnimationFrame(function () {
                _this.activationState_.hasDeactivationUXRun = true;
                _this.animateDeactivation_(state);
                _this.resetActivationState_();
            });
        }
    };
    MDCRippleFoundation.prototype.animateDeactivation_ = function (_a) {
        var wasActivatedByPointer = _a.wasActivatedByPointer, wasElementMadeActive = _a.wasElementMadeActive;
        if (wasActivatedByPointer || wasElementMadeActive) {
            this.runDeactivationUXLogicIfReady_();
        }
    };
    MDCRippleFoundation.prototype.layoutInternal_ = function () {
        var _this = this;
        this.frame_ = this.adapter.computeBoundingRect();
        var maxDim = Math.max(this.frame_.height, this.frame_.width);
        // Surface diameter is treated differently for unbounded vs. bounded ripples.
        // Unbounded ripple diameter is calculated smaller since the surface is expected to already be padded appropriately
        // to extend the hitbox, and the ripple is expected to meet the edges of the padded hitbox (which is typically
        // square). Bounded ripples, on the other hand, are fully expected to expand beyond the surface's longest diameter
        // (calculated based on the diagonal plus a constant padding), and are clipped at the surface's border via
        // `overflow: hidden`.
        var getBoundedRadius = function () {
            var hypotenuse = Math.sqrt(Math.pow(_this.frame_.width, 2) + Math.pow(_this.frame_.height, 2));
            return hypotenuse + MDCRippleFoundation.numbers.PADDING;
        };
        this.maxRadius_ = this.adapter.isUnbounded() ? maxDim : getBoundedRadius();
        // Ripple is sized as a fraction of the largest dimension of the surface, then scales up using a CSS scale transform
        var initialSize = Math.floor(maxDim * MDCRippleFoundation.numbers.INITIAL_ORIGIN_SCALE);
        // Unbounded ripple size should always be even number to equally center align.
        if (this.adapter.isUnbounded() && initialSize % 2 !== 0) {
            this.initialSize_ = initialSize - 1;
        }
        else {
            this.initialSize_ = initialSize;
        }
        this.fgScale_ = "" + this.maxRadius_ / this.initialSize_;
        this.updateLayoutCssVars_();
    };
    MDCRippleFoundation.prototype.updateLayoutCssVars_ = function () {
        var _a = MDCRippleFoundation.strings, VAR_FG_SIZE = _a.VAR_FG_SIZE, VAR_LEFT = _a.VAR_LEFT, VAR_TOP = _a.VAR_TOP, VAR_FG_SCALE = _a.VAR_FG_SCALE;
        this.adapter.updateCssVariable(VAR_FG_SIZE, this.initialSize_ + "px");
        this.adapter.updateCssVariable(VAR_FG_SCALE, this.fgScale_);
        if (this.adapter.isUnbounded()) {
            this.unboundedCoords_ = {
                left: Math.round((this.frame_.width / 2) - (this.initialSize_ / 2)),
                top: Math.round((this.frame_.height / 2) - (this.initialSize_ / 2)),
            };
            this.adapter.updateCssVariable(VAR_LEFT, this.unboundedCoords_.left + "px");
            this.adapter.updateCssVariable(VAR_TOP, this.unboundedCoords_.top + "px");
        }
    };
    return MDCRippleFoundation;
}(_material_base_foundation__WEBPACK_IMPORTED_MODULE_3__.MDCFoundation));

// tslint:disable-next-line:no-default-export Needed for backward compatibility with MDC Web v0.44.0 and earlier.
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MDCRippleFoundation);
//# sourceMappingURL=foundation.js.map

/***/ }),

/***/ "./node_modules/@material/ripple/util.js":
/*!***********************************************!*\
  !*** ./node_modules/@material/ripple/util.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "getNormalizedEventCoords": () => (/* binding */ getNormalizedEventCoords),
/* harmony export */   "supportsCssVariables": () => (/* binding */ supportsCssVariables)
/* harmony export */ });
/**
 * Stores result from supportsCssVariables to avoid redundant processing to
 * detect CSS custom variable support.
 */
var supportsCssVariables_;
function supportsCssVariables(windowObj, forceRefresh) {
    if (forceRefresh === void 0) { forceRefresh = false; }
    var CSS = windowObj.CSS;
    var supportsCssVars = supportsCssVariables_;
    if (typeof supportsCssVariables_ === 'boolean' && !forceRefresh) {
        return supportsCssVariables_;
    }
    var supportsFunctionPresent = CSS && typeof CSS.supports === 'function';
    if (!supportsFunctionPresent) {
        return false;
    }
    var explicitlySupportsCssVars = CSS.supports('--css-vars', 'yes');
    // See: https://bugs.webkit.org/show_bug.cgi?id=154669
    // See: README section on Safari
    var weAreFeatureDetectingSafari10plus = (CSS.supports('(--css-vars: yes)') &&
        CSS.supports('color', '#00000000'));
    supportsCssVars =
        explicitlySupportsCssVars || weAreFeatureDetectingSafari10plus;
    if (!forceRefresh) {
        supportsCssVariables_ = supportsCssVars;
    }
    return supportsCssVars;
}
function getNormalizedEventCoords(evt, pageOffset, clientRect) {
    if (!evt) {
        return { x: 0, y: 0 };
    }
    var x = pageOffset.x, y = pageOffset.y;
    var documentX = x + clientRect.left;
    var documentY = y + clientRect.top;
    var normalizedX;
    var normalizedY;
    // Determine touch point relative to the ripple container.
    if (evt.type === 'touchstart') {
        var touchEvent = evt;
        normalizedX = touchEvent.changedTouches[0].pageX - documentX;
        normalizedY = touchEvent.changedTouches[0].pageY - documentY;
    }
    else {
        var mouseEvent = evt;
        normalizedX = mouseEvent.pageX - documentX;
        normalizedY = mouseEvent.pageY - documentY;
    }
    return { x: normalizedX, y: normalizedY };
}
//# sourceMappingURL=util.js.map

/***/ }),

/***/ "./node_modules/@material/textfield/character-counter/constants.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@material/textfield/character-counter/constants.js ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "cssClasses": () => (/* binding */ cssClasses),
/* harmony export */   "strings": () => (/* binding */ strings)
/* harmony export */ });
/**
 * @license
 * Copyright 2019 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
var cssClasses = {
    ROOT: 'mdc-text-field-character-counter',
};
var strings = {
    ROOT_SELECTOR: "." + cssClasses.ROOT,
};

//# sourceMappingURL=constants.js.map

/***/ }),

/***/ "./node_modules/@material/textfield/character-counter/foundation.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@material/textfield/character-counter/foundation.js ***!
  \**************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "MDCTextFieldCharacterCounterFoundation": () => (/* binding */ MDCTextFieldCharacterCounterFoundation),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _material_base_foundation__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @material/base/foundation */ "./node_modules/@material/base/foundation.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./constants */ "./node_modules/@material/textfield/character-counter/constants.js");
/**
 * @license
 * Copyright 2019 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */



var MDCTextFieldCharacterCounterFoundation = /** @class */ (function (_super) {
    (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__extends)(MDCTextFieldCharacterCounterFoundation, _super);
    function MDCTextFieldCharacterCounterFoundation(adapter) {
        return _super.call(this, (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)((0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)({}, MDCTextFieldCharacterCounterFoundation.defaultAdapter), adapter)) || this;
    }
    Object.defineProperty(MDCTextFieldCharacterCounterFoundation, "cssClasses", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCTextFieldCharacterCounterFoundation, "strings", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.strings;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCTextFieldCharacterCounterFoundation, "defaultAdapter", {
        /**
         * See {@link MDCTextFieldCharacterCounterAdapter} for typing information on parameters and return types.
         */
        get: function () {
            return {
                setContent: function () { return undefined; },
            };
        },
        enumerable: false,
        configurable: true
    });
    MDCTextFieldCharacterCounterFoundation.prototype.setCounterValue = function (currentLength, maxLength) {
        currentLength = Math.min(currentLength, maxLength);
        this.adapter.setContent(currentLength + " / " + maxLength);
    };
    return MDCTextFieldCharacterCounterFoundation;
}(_material_base_foundation__WEBPACK_IMPORTED_MODULE_2__.MDCFoundation));

// tslint:disable-next-line:no-default-export Needed for backward compatibility with MDC Web v0.44.0 and earlier.
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MDCTextFieldCharacterCounterFoundation);
//# sourceMappingURL=foundation.js.map

/***/ }),

/***/ "./node_modules/@material/textfield/constants.js":
/*!*******************************************************!*\
  !*** ./node_modules/@material/textfield/constants.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "ALWAYS_FLOAT_TYPES": () => (/* binding */ ALWAYS_FLOAT_TYPES),
/* harmony export */   "VALIDATION_ATTR_WHITELIST": () => (/* binding */ VALIDATION_ATTR_WHITELIST),
/* harmony export */   "cssClasses": () => (/* binding */ cssClasses),
/* harmony export */   "numbers": () => (/* binding */ numbers),
/* harmony export */   "strings": () => (/* binding */ strings)
/* harmony export */ });
/**
 * @license
 * Copyright 2016 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
var strings = {
    ARIA_CONTROLS: 'aria-controls',
    ARIA_DESCRIBEDBY: 'aria-describedby',
    INPUT_SELECTOR: '.mdc-text-field__input',
    LABEL_SELECTOR: '.mdc-floating-label',
    LEADING_ICON_SELECTOR: '.mdc-text-field__icon--leading',
    LINE_RIPPLE_SELECTOR: '.mdc-line-ripple',
    OUTLINE_SELECTOR: '.mdc-notched-outline',
    PREFIX_SELECTOR: '.mdc-text-field__affix--prefix',
    SUFFIX_SELECTOR: '.mdc-text-field__affix--suffix',
    TRAILING_ICON_SELECTOR: '.mdc-text-field__icon--trailing'
};
var cssClasses = {
    DISABLED: 'mdc-text-field--disabled',
    FOCUSED: 'mdc-text-field--focused',
    HELPER_LINE: 'mdc-text-field-helper-line',
    INVALID: 'mdc-text-field--invalid',
    LABEL_FLOATING: 'mdc-text-field--label-floating',
    NO_LABEL: 'mdc-text-field--no-label',
    OUTLINED: 'mdc-text-field--outlined',
    ROOT: 'mdc-text-field',
    TEXTAREA: 'mdc-text-field--textarea',
    WITH_LEADING_ICON: 'mdc-text-field--with-leading-icon',
    WITH_TRAILING_ICON: 'mdc-text-field--with-trailing-icon',
};
var numbers = {
    LABEL_SCALE: 0.75,
};
/**
 * Whitelist based off of https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/HTML5/Constraint_validation
 * under the "Validation-related attributes" section.
 */
var VALIDATION_ATTR_WHITELIST = [
    'pattern', 'min', 'max', 'required', 'step', 'minlength', 'maxlength',
];
/**
 * Label should always float for these types as they show some UI even if value is empty.
 */
var ALWAYS_FLOAT_TYPES = [
    'color', 'date', 'datetime-local', 'month', 'range', 'time', 'week',
];

//# sourceMappingURL=constants.js.map

/***/ }),

/***/ "./node_modules/@material/textfield/foundation.js":
/*!********************************************************!*\
  !*** ./node_modules/@material/textfield/foundation.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "MDCTextFieldFoundation": () => (/* binding */ MDCTextFieldFoundation),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var _material_base_foundation__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @material/base/foundation */ "./node_modules/@material/base/foundation.js");
/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./constants */ "./node_modules/@material/textfield/constants.js");
/**
 * @license
 * Copyright 2016 Google Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */



var POINTERDOWN_EVENTS = ['mousedown', 'touchstart'];
var INTERACTION_EVENTS = ['click', 'keydown'];
var MDCTextFieldFoundation = /** @class */ (function (_super) {
    (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__extends)(MDCTextFieldFoundation, _super);
    /**
     * @param adapter
     * @param foundationMap Map from subcomponent names to their subfoundations.
     */
    function MDCTextFieldFoundation(adapter, foundationMap) {
        if (foundationMap === void 0) { foundationMap = {}; }
        var _this = _super.call(this, (0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)((0,tslib__WEBPACK_IMPORTED_MODULE_0__.__assign)({}, MDCTextFieldFoundation.defaultAdapter), adapter)) || this;
        _this.isFocused_ = false;
        _this.receivedUserInput_ = false;
        _this.isValid_ = true;
        _this.useNativeValidation_ = true;
        _this.validateOnValueChange_ = true;
        _this.helperText_ = foundationMap.helperText;
        _this.characterCounter_ = foundationMap.characterCounter;
        _this.leadingIcon_ = foundationMap.leadingIcon;
        _this.trailingIcon_ = foundationMap.trailingIcon;
        _this.inputFocusHandler_ = function () { return _this.activateFocus(); };
        _this.inputBlurHandler_ = function () { return _this.deactivateFocus(); };
        _this.inputInputHandler_ = function () { return _this.handleInput(); };
        _this.setPointerXOffset_ = function (evt) { return _this.setTransformOrigin(evt); };
        _this.textFieldInteractionHandler_ = function () { return _this.handleTextFieldInteraction(); };
        _this.validationAttributeChangeHandler_ = function (attributesList) {
            return _this.handleValidationAttributeChange(attributesList);
        };
        return _this;
    }
    Object.defineProperty(MDCTextFieldFoundation, "cssClasses", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.cssClasses;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCTextFieldFoundation, "strings", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.strings;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCTextFieldFoundation, "numbers", {
        get: function () {
            return _constants__WEBPACK_IMPORTED_MODULE_1__.numbers;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCTextFieldFoundation.prototype, "shouldAlwaysFloat_", {
        get: function () {
            var type = this.getNativeInput_().type;
            return _constants__WEBPACK_IMPORTED_MODULE_1__.ALWAYS_FLOAT_TYPES.indexOf(type) >= 0;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCTextFieldFoundation.prototype, "shouldFloat", {
        get: function () {
            return this.shouldAlwaysFloat_ || this.isFocused_ || !!this.getValue() ||
                this.isBadInput_();
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCTextFieldFoundation.prototype, "shouldShake", {
        get: function () {
            return !this.isFocused_ && !this.isValid() && !!this.getValue();
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(MDCTextFieldFoundation, "defaultAdapter", {
        /**
         * See {@link MDCTextFieldAdapter} for typing information on parameters and
         * return types.
         */
        get: function () {
            // tslint:disable:object-literal-sort-keys Methods should be in the same order as the adapter interface.
            return {
                addClass: function () { return undefined; },
                removeClass: function () { return undefined; },
                hasClass: function () { return true; },
                setInputAttr: function () { return undefined; },
                removeInputAttr: function () { return undefined; },
                registerTextFieldInteractionHandler: function () { return undefined; },
                deregisterTextFieldInteractionHandler: function () { return undefined; },
                registerInputInteractionHandler: function () { return undefined; },
                deregisterInputInteractionHandler: function () { return undefined; },
                registerValidationAttributeChangeHandler: function () {
                    return new MutationObserver(function () { return undefined; });
                },
                deregisterValidationAttributeChangeHandler: function () { return undefined; },
                getNativeInput: function () { return null; },
                isFocused: function () { return false; },
                activateLineRipple: function () { return undefined; },
                deactivateLineRipple: function () { return undefined; },
                setLineRippleTransformOrigin: function () { return undefined; },
                shakeLabel: function () { return undefined; },
                floatLabel: function () { return undefined; },
                setLabelRequired: function () { return undefined; },
                hasLabel: function () { return false; },
                getLabelWidth: function () { return 0; },
                hasOutline: function () { return false; },
                notchOutline: function () { return undefined; },
                closeOutline: function () { return undefined; },
            };
            // tslint:enable:object-literal-sort-keys
        },
        enumerable: false,
        configurable: true
    });
    MDCTextFieldFoundation.prototype.init = function () {
        var _this = this;
        if (this.adapter.hasLabel() && this.getNativeInput_().required) {
            this.adapter.setLabelRequired(true);
        }
        if (this.adapter.isFocused()) {
            this.inputFocusHandler_();
        }
        else if (this.adapter.hasLabel() && this.shouldFloat) {
            this.notchOutline(true);
            this.adapter.floatLabel(true);
            this.styleFloating_(true);
        }
        this.adapter.registerInputInteractionHandler('focus', this.inputFocusHandler_);
        this.adapter.registerInputInteractionHandler('blur', this.inputBlurHandler_);
        this.adapter.registerInputInteractionHandler('input', this.inputInputHandler_);
        POINTERDOWN_EVENTS.forEach(function (evtType) {
            _this.adapter.registerInputInteractionHandler(evtType, _this.setPointerXOffset_);
        });
        INTERACTION_EVENTS.forEach(function (evtType) {
            _this.adapter.registerTextFieldInteractionHandler(evtType, _this.textFieldInteractionHandler_);
        });
        this.validationObserver_ =
            this.adapter.registerValidationAttributeChangeHandler(this.validationAttributeChangeHandler_);
        this.setCharacterCounter_(this.getValue().length);
    };
    MDCTextFieldFoundation.prototype.destroy = function () {
        var _this = this;
        this.adapter.deregisterInputInteractionHandler('focus', this.inputFocusHandler_);
        this.adapter.deregisterInputInteractionHandler('blur', this.inputBlurHandler_);
        this.adapter.deregisterInputInteractionHandler('input', this.inputInputHandler_);
        POINTERDOWN_EVENTS.forEach(function (evtType) {
            _this.adapter.deregisterInputInteractionHandler(evtType, _this.setPointerXOffset_);
        });
        INTERACTION_EVENTS.forEach(function (evtType) {
            _this.adapter.deregisterTextFieldInteractionHandler(evtType, _this.textFieldInteractionHandler_);
        });
        this.adapter.deregisterValidationAttributeChangeHandler(this.validationObserver_);
    };
    /**
     * Handles user interactions with the Text Field.
     */
    MDCTextFieldFoundation.prototype.handleTextFieldInteraction = function () {
        var nativeInput = this.adapter.getNativeInput();
        if (nativeInput && nativeInput.disabled) {
            return;
        }
        this.receivedUserInput_ = true;
    };
    /**
     * Handles validation attribute changes
     */
    MDCTextFieldFoundation.prototype.handleValidationAttributeChange = function (attributesList) {
        var _this = this;
        attributesList.some(function (attributeName) {
            if (_constants__WEBPACK_IMPORTED_MODULE_1__.VALIDATION_ATTR_WHITELIST.indexOf(attributeName) > -1) {
                _this.styleValidity_(true);
                _this.adapter.setLabelRequired(_this.getNativeInput_().required);
                return true;
            }
            return false;
        });
        if (attributesList.indexOf('maxlength') > -1) {
            this.setCharacterCounter_(this.getValue().length);
        }
    };
    /**
     * Opens/closes the notched outline.
     */
    MDCTextFieldFoundation.prototype.notchOutline = function (openNotch) {
        if (!this.adapter.hasOutline() || !this.adapter.hasLabel()) {
            return;
        }
        if (openNotch) {
            var labelWidth = this.adapter.getLabelWidth() * _constants__WEBPACK_IMPORTED_MODULE_1__.numbers.LABEL_SCALE;
            this.adapter.notchOutline(labelWidth);
        }
        else {
            this.adapter.closeOutline();
        }
    };
    /**
     * Activates the text field focus state.
     */
    MDCTextFieldFoundation.prototype.activateFocus = function () {
        this.isFocused_ = true;
        this.styleFocused_(this.isFocused_);
        this.adapter.activateLineRipple();
        if (this.adapter.hasLabel()) {
            this.notchOutline(this.shouldFloat);
            this.adapter.floatLabel(this.shouldFloat);
            this.styleFloating_(this.shouldFloat);
            this.adapter.shakeLabel(this.shouldShake);
        }
        if (this.helperText_ &&
            (this.helperText_.isPersistent() || !this.helperText_.isValidation() ||
                !this.isValid_)) {
            this.helperText_.showToScreenReader();
        }
    };
    /**
     * Sets the line ripple's transform origin, so that the line ripple activate
     * animation will animate out from the user's click location.
     */
    MDCTextFieldFoundation.prototype.setTransformOrigin = function (evt) {
        if (this.isDisabled() || this.adapter.hasOutline()) {
            return;
        }
        var touches = evt.touches;
        var targetEvent = touches ? touches[0] : evt;
        var targetClientRect = targetEvent.target.getBoundingClientRect();
        var normalizedX = targetEvent.clientX - targetClientRect.left;
        this.adapter.setLineRippleTransformOrigin(normalizedX);
    };
    /**
     * Handles input change of text input and text area.
     */
    MDCTextFieldFoundation.prototype.handleInput = function () {
        this.autoCompleteFocus();
        this.setCharacterCounter_(this.getValue().length);
    };
    /**
     * Activates the Text Field's focus state in cases when the input value
     * changes without user input (e.g. programmatically).
     */
    MDCTextFieldFoundation.prototype.autoCompleteFocus = function () {
        if (!this.receivedUserInput_) {
            this.activateFocus();
        }
    };
    /**
     * Deactivates the Text Field's focus state.
     */
    MDCTextFieldFoundation.prototype.deactivateFocus = function () {
        this.isFocused_ = false;
        this.adapter.deactivateLineRipple();
        var isValid = this.isValid();
        this.styleValidity_(isValid);
        this.styleFocused_(this.isFocused_);
        if (this.adapter.hasLabel()) {
            this.notchOutline(this.shouldFloat);
            this.adapter.floatLabel(this.shouldFloat);
            this.styleFloating_(this.shouldFloat);
            this.adapter.shakeLabel(this.shouldShake);
        }
        if (!this.shouldFloat) {
            this.receivedUserInput_ = false;
        }
    };
    MDCTextFieldFoundation.prototype.getValue = function () {
        return this.getNativeInput_().value;
    };
    /**
     * @param value The value to set on the input Element.
     */
    MDCTextFieldFoundation.prototype.setValue = function (value) {
        // Prevent Safari from moving the caret to the end of the input when the
        // value has not changed.
        if (this.getValue() !== value) {
            this.getNativeInput_().value = value;
        }
        this.setCharacterCounter_(value.length);
        if (this.validateOnValueChange_) {
            var isValid = this.isValid();
            this.styleValidity_(isValid);
        }
        if (this.adapter.hasLabel()) {
            this.notchOutline(this.shouldFloat);
            this.adapter.floatLabel(this.shouldFloat);
            this.styleFloating_(this.shouldFloat);
            if (this.validateOnValueChange_) {
                this.adapter.shakeLabel(this.shouldShake);
            }
        }
    };
    /**
     * @return The custom validity state, if set; otherwise, the result of a
     *     native validity check.
     */
    MDCTextFieldFoundation.prototype.isValid = function () {
        return this.useNativeValidation_ ? this.isNativeInputValid_() :
            this.isValid_;
    };
    /**
     * @param isValid Sets the custom validity state of the Text Field.
     */
    MDCTextFieldFoundation.prototype.setValid = function (isValid) {
        this.isValid_ = isValid;
        this.styleValidity_(isValid);
        var shouldShake = !isValid && !this.isFocused_ && !!this.getValue();
        if (this.adapter.hasLabel()) {
            this.adapter.shakeLabel(shouldShake);
        }
    };
    /**
     * @param shouldValidate Whether or not validity should be updated on
     *     value change.
     */
    MDCTextFieldFoundation.prototype.setValidateOnValueChange = function (shouldValidate) {
        this.validateOnValueChange_ = shouldValidate;
    };
    /**
     * @return Whether or not validity should be updated on value change. `true`
     *     by default.
     */
    MDCTextFieldFoundation.prototype.getValidateOnValueChange = function () {
        return this.validateOnValueChange_;
    };
    /**
     * Enables or disables the use of native validation. Use this for custom
     * validation.
     * @param useNativeValidation Set this to false to ignore native input
     *     validation.
     */
    MDCTextFieldFoundation.prototype.setUseNativeValidation = function (useNativeValidation) {
        this.useNativeValidation_ = useNativeValidation;
    };
    MDCTextFieldFoundation.prototype.isDisabled = function () {
        return this.getNativeInput_().disabled;
    };
    /**
     * @param disabled Sets the text-field disabled or enabled.
     */
    MDCTextFieldFoundation.prototype.setDisabled = function (disabled) {
        this.getNativeInput_().disabled = disabled;
        this.styleDisabled_(disabled);
    };
    /**
     * @param content Sets the content of the helper text.
     */
    MDCTextFieldFoundation.prototype.setHelperTextContent = function (content) {
        if (this.helperText_) {
            this.helperText_.setContent(content);
        }
    };
    /**
     * Sets the aria label of the leading icon.
     */
    MDCTextFieldFoundation.prototype.setLeadingIconAriaLabel = function (label) {
        if (this.leadingIcon_) {
            this.leadingIcon_.setAriaLabel(label);
        }
    };
    /**
     * Sets the text content of the leading icon.
     */
    MDCTextFieldFoundation.prototype.setLeadingIconContent = function (content) {
        if (this.leadingIcon_) {
            this.leadingIcon_.setContent(content);
        }
    };
    /**
     * Sets the aria label of the trailing icon.
     */
    MDCTextFieldFoundation.prototype.setTrailingIconAriaLabel = function (label) {
        if (this.trailingIcon_) {
            this.trailingIcon_.setAriaLabel(label);
        }
    };
    /**
     * Sets the text content of the trailing icon.
     */
    MDCTextFieldFoundation.prototype.setTrailingIconContent = function (content) {
        if (this.trailingIcon_) {
            this.trailingIcon_.setContent(content);
        }
    };
    /**
     * Sets character counter values that shows characters used and the total
     * character limit.
     */
    MDCTextFieldFoundation.prototype.setCharacterCounter_ = function (currentLength) {
        if (!this.characterCounter_) {
            return;
        }
        var maxLength = this.getNativeInput_().maxLength;
        if (maxLength === -1) {
            throw new Error('MDCTextFieldFoundation: Expected maxlength html property on text input or textarea.');
        }
        this.characterCounter_.setCounterValue(currentLength, maxLength);
    };
    /**
     * @return True if the Text Field input fails in converting the user-supplied
     *     value.
     */
    MDCTextFieldFoundation.prototype.isBadInput_ = function () {
        // The badInput property is not supported in IE 11 💩.
        return this.getNativeInput_().validity.badInput || false;
    };
    /**
     * @return The result of native validity checking (ValidityState.valid).
     */
    MDCTextFieldFoundation.prototype.isNativeInputValid_ = function () {
        return this.getNativeInput_().validity.valid;
    };
    /**
     * Styles the component based on the validity state.
     */
    MDCTextFieldFoundation.prototype.styleValidity_ = function (isValid) {
        var INVALID = MDCTextFieldFoundation.cssClasses.INVALID;
        if (isValid) {
            this.adapter.removeClass(INVALID);
        }
        else {
            this.adapter.addClass(INVALID);
        }
        if (this.helperText_) {
            this.helperText_.setValidity(isValid);
            // We dynamically set or unset aria-describedby for validation helper text
            // only, based on whether the field is valid
            var helperTextValidation = this.helperText_.isValidation();
            if (!helperTextValidation) {
                return;
            }
            var helperTextVisible = this.helperText_.isVisible();
            var helperTextId = this.helperText_.getId();
            if (helperTextVisible && helperTextId) {
                this.adapter.setInputAttr(_constants__WEBPACK_IMPORTED_MODULE_1__.strings.ARIA_DESCRIBEDBY, helperTextId);
            }
            else {
                this.adapter.removeInputAttr(_constants__WEBPACK_IMPORTED_MODULE_1__.strings.ARIA_DESCRIBEDBY);
            }
        }
    };
    /**
     * Styles the component based on the focused state.
     */
    MDCTextFieldFoundation.prototype.styleFocused_ = function (isFocused) {
        var FOCUSED = MDCTextFieldFoundation.cssClasses.FOCUSED;
        if (isFocused) {
            this.adapter.addClass(FOCUSED);
        }
        else {
            this.adapter.removeClass(FOCUSED);
        }
    };
    /**
     * Styles the component based on the disabled state.
     */
    MDCTextFieldFoundation.prototype.styleDisabled_ = function (isDisabled) {
        var _a = MDCTextFieldFoundation.cssClasses, DISABLED = _a.DISABLED, INVALID = _a.INVALID;
        if (isDisabled) {
            this.adapter.addClass(DISABLED);
            this.adapter.removeClass(INVALID);
        }
        else {
            this.adapter.removeClass(DISABLED);
        }
        if (this.leadingIcon_) {
            this.leadingIcon_.setDisabled(isDisabled);
        }
        if (this.trailingIcon_) {
            this.trailingIcon_.setDisabled(isDisabled);
        }
    };
    /**
     * Styles the component based on the label floating state.
     */
    MDCTextFieldFoundation.prototype.styleFloating_ = function (isFloating) {
        var LABEL_FLOATING = MDCTextFieldFoundation.cssClasses.LABEL_FLOATING;
        if (isFloating) {
            this.adapter.addClass(LABEL_FLOATING);
        }
        else {
            this.adapter.removeClass(LABEL_FLOATING);
        }
    };
    /**
     * @return The native text input element from the host environment, or an
     *     object with the same shape for unit tests.
     */
    MDCTextFieldFoundation.prototype.getNativeInput_ = function () {
        // this.adapter may be undefined in foundation unit tests. This happens when
        // testdouble is creating a mock object and invokes the
        // shouldShake/shouldFloat getters (which in turn call getValue(), which
        // calls this method) before init() has been called from the MDCTextField
        // constructor. To work around that issue, we return a dummy object.
        var nativeInput = this.adapter ? this.adapter.getNativeInput() : null;
        return nativeInput || {
            disabled: false,
            maxLength: -1,
            required: false,
            type: 'input',
            validity: {
                badInput: false,
                valid: true,
            },
            value: '',
        };
    };
    return MDCTextFieldFoundation;
}(_material_base_foundation__WEBPACK_IMPORTED_MODULE_2__.MDCFoundation));

// tslint:disable-next-line:no-default-export Needed for backward compatibility with MDC Web v0.44.0 and earlier.
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MDCTextFieldFoundation);
//# sourceMappingURL=foundation.js.map

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Item.svelte.27.css!./node_modules/svelte-select/src/Item.svelte":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Item.svelte.27.css!./node_modules/svelte-select/src/Item.svelte ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".item.svelte-bdnybl{cursor:default;height:var(--height, 42px);line-height:var(--height, 42px);padding:var(--itemPadding, 0 20px);color:var(--itemColor, inherit);text-overflow:ellipsis;overflow:hidden;white-space:nowrap}.groupHeader.svelte-bdnybl{text-transform:var(--groupTitleTextTransform, uppercase)}.groupItem.svelte-bdnybl{padding-left:var(--groupItemPaddingLeft, 40px)}.item.svelte-bdnybl:active{background:var(--itemActiveBackground, #b9daff)}.item.active.svelte-bdnybl{background:var(--itemIsActiveBG, #007aff);color:var(--itemIsActiveColor, #fff)}.item.first.svelte-bdnybl{border-radius:var(--itemFirstBorderRadius, 4px 4px 0 0)}.item.hover.svelte-bdnybl:not(.active){background:var(--itemHoverBG, #e7f2ff)}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/List.svelte.28.css!./node_modules/svelte-select/src/List.svelte":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/List.svelte.28.css!./node_modules/svelte-select/src/List.svelte ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".listContainer.svelte-ux0sbr{box-shadow:var(--listShadow, 0 2px 3px 0 rgba(44, 62, 80, 0.24));border-radius:var(--listBorderRadius, 4px);max-height:var(--listMaxHeight, 250px);overflow-y:auto;background:var(--listBackground, #fff)}.virtualList.svelte-ux0sbr{height:var(--virtualListHeight, 200px)}.listGroupTitle.svelte-ux0sbr{color:var(--groupTitleColor, #8f8f8f);cursor:default;font-size:var(--groupTitleFontSize, 12px);font-weight:var(--groupTitleFontWeight, 600);height:var(--height, 42px);line-height:var(--height, 42px);padding:var(--groupTitlePadding, 0 20px);text-overflow:ellipsis;overflow-x:hidden;white-space:nowrap;text-transform:var(--groupTitleTextTransform, uppercase)}.empty.svelte-ux0sbr{text-align:var(--listEmptyTextAlign, center);padding:var(--listEmptyPadding, 20px 0);color:var(--listEmptyColor, #78848F)}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/MultiSelection.svelte.30.css!./node_modules/svelte-select/src/MultiSelection.svelte":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/MultiSelection.svelte.30.css!./node_modules/svelte-select/src/MultiSelection.svelte ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".multiSelectItem.svelte-14r1jr2.svelte-14r1jr2{background:var(--multiItemBG, #EBEDEF);margin:var(--multiItemMargin, 5px 5px 0 0);border-radius:var(--multiItemBorderRadius, 16px);height:var(--multiItemHeight, 32px);line-height:var(--multiItemHeight, 32px);display:flex;cursor:default;padding:var(--multiItemPadding, 0 10px 0 15px);max-width:100%}.multiSelectItem_label.svelte-14r1jr2.svelte-14r1jr2{margin:var(--multiLabelMargin, 0 5px 0 0);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.multiSelectItem.svelte-14r1jr2.svelte-14r1jr2:hover,.multiSelectItem.active.svelte-14r1jr2.svelte-14r1jr2{background-color:var(--multiItemActiveBG, #006FFF);color:var(--multiItemActiveColor, #fff)}.multiSelectItem.disabled.svelte-14r1jr2.svelte-14r1jr2:hover{background:var(--multiItemDisabledHoverBg, #EBEDEF);color:var(--multiItemDisabledHoverColor, #C1C6CC)}.multiSelectItem_clear.svelte-14r1jr2.svelte-14r1jr2{border-radius:var(--multiClearRadius, 50%);background:var(--multiClearBG, #52616F);min-width:var(--multiClearWidth, 16px);max-width:var(--multiClearWidth, 16px);height:var(--multiClearHeight, 16px);position:relative;top:var(--multiClearTop, 8px);text-align:var(--multiClearTextAlign, center);padding:var(--multiClearPadding, 1px)}.multiSelectItem_clear.svelte-14r1jr2.svelte-14r1jr2:hover,.active.svelte-14r1jr2 .multiSelectItem_clear.svelte-14r1jr2{background:var(--multiClearHoverBG, #fff)}.multiSelectItem_clear.svelte-14r1jr2:hover svg.svelte-14r1jr2,.active.svelte-14r1jr2 .multiSelectItem_clear svg.svelte-14r1jr2{fill:var(--multiClearHoverFill, #006FFF)}.multiSelectItem_clear.svelte-14r1jr2 svg.svelte-14r1jr2{fill:var(--multiClearFill, #EBEDEF);vertical-align:top}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Select.svelte.26.css!./node_modules/svelte-select/src/Select.svelte":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Select.svelte.26.css!./node_modules/svelte-select/src/Select.svelte ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".selectContainer.svelte-17qb5ew.svelte-17qb5ew{--padding:0 16px;border:var(--border, 1px solid #d8dbdf);border-radius:var(--borderRadius, 3px);height:var(--height, 42px);position:relative;display:flex;align-items:center;padding:var(--padding);background:var(--background, #fff)}.selectContainer.svelte-17qb5ew input.svelte-17qb5ew{cursor:default;border:none;color:var(--inputColor, #3f4f5f);height:var(--height, 42px);line-height:var(--height, 42px);padding:var(--inputPadding, var(--padding));width:100%;background:transparent;font-size:var(--inputFontSize, 14px);letter-spacing:var(--inputLetterSpacing, -0.08px);position:absolute;left:var(--inputLeft, 0)}.selectContainer.svelte-17qb5ew input.svelte-17qb5ew::-moz-placeholder{color:var(--placeholderColor, #78848f);opacity:var(--placeholderOpacity, 1)}.selectContainer.svelte-17qb5ew input.svelte-17qb5ew:-ms-input-placeholder{color:var(--placeholderColor, #78848f);opacity:var(--placeholderOpacity, 1)}.selectContainer.svelte-17qb5ew input.svelte-17qb5ew::placeholder{color:var(--placeholderColor, #78848f);opacity:var(--placeholderOpacity, 1)}.selectContainer.svelte-17qb5ew input.svelte-17qb5ew:focus{outline:none}.selectContainer.svelte-17qb5ew.svelte-17qb5ew:hover{border-color:var(--borderHoverColor, #b2b8bf)}.selectContainer.focused.svelte-17qb5ew.svelte-17qb5ew{border-color:var(--borderFocusColor, #006fe8)}.selectContainer.disabled.svelte-17qb5ew.svelte-17qb5ew{background:var(--disabledBackground, #ebedef);border-color:var(--disabledBorderColor, #ebedef);color:var(--disabledColor, #c1c6cc)}.selectContainer.disabled.svelte-17qb5ew input.svelte-17qb5ew::-moz-placeholder{color:var(--disabledPlaceholderColor, #c1c6cc);opacity:var(--disabledPlaceholderOpacity, 1)}.selectContainer.disabled.svelte-17qb5ew input.svelte-17qb5ew:-ms-input-placeholder{color:var(--disabledPlaceholderColor, #c1c6cc);opacity:var(--disabledPlaceholderOpacity, 1)}.selectContainer.disabled.svelte-17qb5ew input.svelte-17qb5ew::placeholder{color:var(--disabledPlaceholderColor, #c1c6cc);opacity:var(--disabledPlaceholderOpacity, 1)}.selectedItem.svelte-17qb5ew.svelte-17qb5ew{line-height:var(--height, 42px);height:var(--height, 42px);overflow-x:hidden;padding:var(--selectedItemPadding, 0 20px 0 0)}.selectedItem.svelte-17qb5ew.svelte-17qb5ew:focus{outline:none}.clearSelect.svelte-17qb5ew.svelte-17qb5ew{position:absolute;right:var(--clearSelectRight, 10px);top:var(--clearSelectTop, 11px);bottom:var(--clearSelectBottom, 11px);width:var(--clearSelectWidth, 20px);color:var(--clearSelectColor, #c5cacf);flex:none !important}.clearSelect.svelte-17qb5ew.svelte-17qb5ew:hover{color:var(--clearSelectHoverColor, #2c3e50)}.selectContainer.focused.svelte-17qb5ew .clearSelect.svelte-17qb5ew{color:var(--clearSelectFocusColor, #3f4f5f)}.indicator.svelte-17qb5ew.svelte-17qb5ew{position:absolute;right:var(--indicatorRight, 10px);top:var(--indicatorTop, 11px);width:var(--indicatorWidth, 20px);height:var(--indicatorHeight, 20px);color:var(--indicatorColor, #c5cacf)}.indicator.svelte-17qb5ew svg.svelte-17qb5ew{display:inline-block;fill:var(--indicatorFill, currentcolor);line-height:1;stroke:var(--indicatorStroke, currentcolor);stroke-width:0}.spinner.svelte-17qb5ew.svelte-17qb5ew{position:absolute;right:var(--spinnerRight, 10px);top:var(--spinnerLeft, 11px);width:var(--spinnerWidth, 20px);height:var(--spinnerHeight, 20px);color:var(--spinnerColor, #51ce6c);-webkit-animation:svelte-17qb5ew-rotate 0.75s linear infinite;animation:svelte-17qb5ew-rotate 0.75s linear infinite}.spinner_icon.svelte-17qb5ew.svelte-17qb5ew{display:block;height:100%;transform-origin:center center;width:100%;position:absolute;top:0;bottom:0;left:0;right:0;margin:auto;-webkit-transform:none}.spinner_path.svelte-17qb5ew.svelte-17qb5ew{stroke-dasharray:90;stroke-linecap:round}.multiSelect.svelte-17qb5ew.svelte-17qb5ew{display:flex;padding:var(--multiSelectPadding, 0 35px 0 16px);height:auto;flex-wrap:wrap;align-items:stretch}.multiSelect.svelte-17qb5ew>.svelte-17qb5ew{flex:1 1 50px}.selectContainer.multiSelect.svelte-17qb5ew input.svelte-17qb5ew{padding:var(--multiSelectInputPadding, 0);position:relative;margin:var(--multiSelectInputMargin, 0)}.hasError.svelte-17qb5ew.svelte-17qb5ew{border:var(--errorBorder, 1px solid #ff2d55);background:var(--errorBackground, #fff)}@-webkit-keyframes svelte-17qb5ew-rotate{100%{transform:rotate(360deg)}}@keyframes svelte-17qb5ew-rotate{100%{transform:rotate(360deg)}}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Selection.svelte.29.css!./node_modules/svelte-select/src/Selection.svelte":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Selection.svelte.29.css!./node_modules/svelte-select/src/Selection.svelte ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".selection.svelte-ch6bh7{text-overflow:ellipsis;overflow-x:hidden;white-space:nowrap}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/VirtualList.svelte.55.css!./node_modules/svelte-select/src/VirtualList.svelte":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/VirtualList.svelte.55.css!./node_modules/svelte-select/src/VirtualList.svelte ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "svelte-virtual-list-viewport.svelte-p6ehlv{position:relative;overflow-y:auto;-webkit-overflow-scrolling:touch;display:block}svelte-virtual-list-contents.svelte-p6ehlv,svelte-virtual-list-row.svelte-p6ehlv{display:block}svelte-virtual-list-row.svelte-p6ehlv{overflow:hidden}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Button.svelte.14.css!./resources/js/Shared/Button.svelte":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Button.svelte.14.css!./resources/js/Shared/Button.svelte ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".mdc-button{height:auto;min-height:36px}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/LoadingButton.svelte.12.css!./resources/js/Shared/LoadingButton.svelte":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/LoadingButton.svelte.12.css!./resources/js/Shared/LoadingButton.svelte ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".mdc-button{height:auto;min-height:36px}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Select.svelte.13.css!./resources/js/Shared/Select.svelte":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Select.svelte.13.css!./resources/js/Shared/Select.svelte ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".items .listItem{border-bottom:1px solid #ccc}.items .item{height:auto !important;line-height:1.6 !important;text-overflow:initial !important;overflow:initial !important;white-space:break-spaces !important;padding-top:10px;padding-bottom:10px}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/SelectMulti.svelte.17.css!./resources/js/Shared/SelectMulti.svelte":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/SelectMulti.svelte.17.css!./resources/js/Shared/SelectMulti.svelte ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".items .listItem{border-bottom:1px solid #ccc}.items .item{height:auto !important;line-height:1.6 !important;text-overflow:initial !important;overflow:initial !important;white-space:break-spaces !important;padding-top:10px;padding-bottom:10px}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/runtime/api.js":
/*!*****************************************************!*\
  !*** ./node_modules/css-loader/dist/runtime/api.js ***!
  \*****************************************************/
/***/ ((module) => {



/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
// eslint-disable-next-line func-names
module.exports = function (cssWithMappingToString) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = cssWithMappingToString(item);

      if (item[2]) {
        return "@media ".concat(item[2], " {").concat(content, "}");
      }

      return content;
    }).join("");
  }; // import a list of modules into the list
  // eslint-disable-next-line func-names


  list.i = function (modules, mediaQuery, dedupe) {
    if (typeof modules === "string") {
      // eslint-disable-next-line no-param-reassign
      modules = [[null, modules, ""]];
    }

    var alreadyImportedModules = {};

    if (dedupe) {
      for (var i = 0; i < this.length; i++) {
        // eslint-disable-next-line prefer-destructuring
        var id = this[i][0];

        if (id != null) {
          alreadyImportedModules[id] = true;
        }
      }
    }

    for (var _i = 0; _i < modules.length; _i++) {
      var item = [].concat(modules[_i]);

      if (dedupe && alreadyImportedModules[item[0]]) {
        // eslint-disable-next-line no-continue
        continue;
      }

      if (mediaQuery) {
        if (!item[2]) {
          item[2] = mediaQuery;
        } else {
          item[2] = "".concat(mediaQuery, " and ").concat(item[2]);
        }
      }

      list.push(item);
    }
  };

  return list;
};

/***/ }),

/***/ "./node_modules/svelte-select/src/Item.svelte.27.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Item.svelte.27.css!./node_modules/svelte-select/src/Item.svelte":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/svelte-select/src/Item.svelte.27.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Item.svelte.27.css!./node_modules/svelte-select/src/Item.svelte ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Item_svelte_27_css_Item_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!../../postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!../../svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Item.svelte.27.css!./Item.svelte */ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Item.svelte.27.css!./node_modules/svelte-select/src/Item.svelte");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Item_svelte_27_css_Item_svelte__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Item_svelte_27_css_Item_svelte__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/svelte-select/src/List.svelte.28.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/List.svelte.28.css!./node_modules/svelte-select/src/List.svelte":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/svelte-select/src/List.svelte.28.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/List.svelte.28.css!./node_modules/svelte-select/src/List.svelte ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_List_svelte_28_css_List_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!../../postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!../../svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/List.svelte.28.css!./List.svelte */ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/List.svelte.28.css!./node_modules/svelte-select/src/List.svelte");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_List_svelte_28_css_List_svelte__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_List_svelte_28_css_List_svelte__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/svelte-select/src/MultiSelection.svelte.30.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/MultiSelection.svelte.30.css!./node_modules/svelte-select/src/MultiSelection.svelte":
/*!*********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/svelte-select/src/MultiSelection.svelte.30.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/MultiSelection.svelte.30.css!./node_modules/svelte-select/src/MultiSelection.svelte ***!
  \*********************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_MultiSelection_svelte_30_css_MultiSelection_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!../../postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!../../svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/MultiSelection.svelte.30.css!./MultiSelection.svelte */ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/MultiSelection.svelte.30.css!./node_modules/svelte-select/src/MultiSelection.svelte");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_MultiSelection_svelte_30_css_MultiSelection_svelte__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_MultiSelection_svelte_30_css_MultiSelection_svelte__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/svelte-select/src/Select.svelte.26.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Select.svelte.26.css!./node_modules/svelte-select/src/Select.svelte":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/svelte-select/src/Select.svelte.26.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Select.svelte.26.css!./node_modules/svelte-select/src/Select.svelte ***!
  \*********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Select_svelte_26_css_Select_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!../../postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!../../svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Select.svelte.26.css!./Select.svelte */ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Select.svelte.26.css!./node_modules/svelte-select/src/Select.svelte");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Select_svelte_26_css_Select_svelte__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Select_svelte_26_css_Select_svelte__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/svelte-select/src/Selection.svelte.29.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Selection.svelte.29.css!./node_modules/svelte-select/src/Selection.svelte":
/*!******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/svelte-select/src/Selection.svelte.29.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Selection.svelte.29.css!./node_modules/svelte-select/src/Selection.svelte ***!
  \******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Selection_svelte_29_css_Selection_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!../../postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!../../svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Selection.svelte.29.css!./Selection.svelte */ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Selection.svelte.29.css!./node_modules/svelte-select/src/Selection.svelte");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Selection_svelte_29_css_Selection_svelte__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Selection_svelte_29_css_Selection_svelte__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/svelte-select/src/VirtualList.svelte.55.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/VirtualList.svelte.55.css!./node_modules/svelte-select/src/VirtualList.svelte":
/*!************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/svelte-select/src/VirtualList.svelte.55.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/VirtualList.svelte.55.css!./node_modules/svelte-select/src/VirtualList.svelte ***!
  \************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_VirtualList_svelte_55_css_VirtualList_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!../../postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!../../svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/VirtualList.svelte.55.css!./VirtualList.svelte */ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/VirtualList.svelte.55.css!./node_modules/svelte-select/src/VirtualList.svelte");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_VirtualList_svelte_55_css_VirtualList_svelte__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_svelte_loader_index_js_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_VirtualList_svelte_55_css_VirtualList_svelte__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/Shared/Button.svelte.14.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Button.svelte.14.css!./resources/js/Shared/Button.svelte":
/*!************************************************************************************************************************************************************************************************!*\
  !*** ./resources/js/Shared/Button.svelte.14.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Button.svelte.14.css!./resources/js/Shared/Button.svelte ***!
  \************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_Button_svelte_14_css_Button_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!../../../node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!../../../node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Button.svelte.14.css!./Button.svelte */ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Button.svelte.14.css!./resources/js/Shared/Button.svelte");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_Button_svelte_14_css_Button_svelte__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_Button_svelte_14_css_Button_svelte__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/Shared/LoadingButton.svelte.12.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/LoadingButton.svelte.12.css!./resources/js/Shared/LoadingButton.svelte":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./resources/js/Shared/LoadingButton.svelte.12.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/LoadingButton.svelte.12.css!./resources/js/Shared/LoadingButton.svelte ***!
  \*********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_LoadingButton_svelte_12_css_LoadingButton_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!../../../node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!../../../node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/LoadingButton.svelte.12.css!./LoadingButton.svelte */ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/LoadingButton.svelte.12.css!./resources/js/Shared/LoadingButton.svelte");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_LoadingButton_svelte_12_css_LoadingButton_svelte__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_LoadingButton_svelte_12_css_LoadingButton_svelte__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/Shared/Select.svelte.13.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Select.svelte.13.css!./resources/js/Shared/Select.svelte":
/*!************************************************************************************************************************************************************************************************!*\
  !*** ./resources/js/Shared/Select.svelte.13.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Select.svelte.13.css!./resources/js/Shared/Select.svelte ***!
  \************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_Select_svelte_13_css_Select_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!../../../node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!../../../node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Select.svelte.13.css!./Select.svelte */ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Select.svelte.13.css!./resources/js/Shared/Select.svelte");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_Select_svelte_13_css_Select_svelte__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_Select_svelte_13_css_Select_svelte__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/Shared/SelectMulti.svelte.17.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/SelectMulti.svelte.17.css!./resources/js/Shared/SelectMulti.svelte":
/*!***************************************************************************************************************************************************************************************************************!*\
  !*** ./resources/js/Shared/SelectMulti.svelte.17.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/SelectMulti.svelte.17.css!./resources/js/Shared/SelectMulti.svelte ***!
  \***************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_SelectMulti_svelte_17_css_SelectMulti_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!../../../node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!../../../node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/SelectMulti.svelte.17.css!./SelectMulti.svelte */ "./node_modules/css-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[1]!./node_modules/postcss-loader/dist/cjs.js??ruleSet[1].rules[6].oneOf[1].use[2]!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/SelectMulti.svelte.17.css!./resources/js/Shared/SelectMulti.svelte");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_SelectMulti_svelte_17_css_SelectMulti_svelte__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_1_node_modules_postcss_loader_dist_cjs_js_ruleSet_1_rules_6_oneOf_1_use_2_node_modules_svelte_loader_index_js_cssPath_D_www_sipro_spa_resources_js_Shared_SelectMulti_svelte_17_css_SelectMulti_svelte__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js":
/*!****************************************************************************!*\
  !*** ./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js ***!
  \****************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {



var isOldIE = function isOldIE() {
  var memo;
  return function memorize() {
    if (typeof memo === 'undefined') {
      // Test for IE <= 9 as proposed by Browserhacks
      // @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
      // Tests for existence of standard globals is to allow style-loader
      // to operate correctly into non-standard environments
      // @see https://github.com/webpack-contrib/style-loader/issues/177
      memo = Boolean(window && document && document.all && !window.atob);
    }

    return memo;
  };
}();

var getTarget = function getTarget() {
  var memo = {};
  return function memorize(target) {
    if (typeof memo[target] === 'undefined') {
      var styleTarget = document.querySelector(target); // Special case to return head of iframe instead of iframe itself

      if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
        try {
          // This will throw an exception if access to iframe is blocked
          // due to cross-origin restrictions
          styleTarget = styleTarget.contentDocument.head;
        } catch (e) {
          // istanbul ignore next
          styleTarget = null;
        }
      }

      memo[target] = styleTarget;
    }

    return memo[target];
  };
}();

var stylesInDom = [];

function getIndexByIdentifier(identifier) {
  var result = -1;

  for (var i = 0; i < stylesInDom.length; i++) {
    if (stylesInDom[i].identifier === identifier) {
      result = i;
      break;
    }
  }

  return result;
}

function modulesToDom(list, options) {
  var idCountMap = {};
  var identifiers = [];

  for (var i = 0; i < list.length; i++) {
    var item = list[i];
    var id = options.base ? item[0] + options.base : item[0];
    var count = idCountMap[id] || 0;
    var identifier = "".concat(id, " ").concat(count);
    idCountMap[id] = count + 1;
    var index = getIndexByIdentifier(identifier);
    var obj = {
      css: item[1],
      media: item[2],
      sourceMap: item[3]
    };

    if (index !== -1) {
      stylesInDom[index].references++;
      stylesInDom[index].updater(obj);
    } else {
      stylesInDom.push({
        identifier: identifier,
        updater: addStyle(obj, options),
        references: 1
      });
    }

    identifiers.push(identifier);
  }

  return identifiers;
}

function insertStyleElement(options) {
  var style = document.createElement('style');
  var attributes = options.attributes || {};

  if (typeof attributes.nonce === 'undefined') {
    var nonce =  true ? __webpack_require__.nc : 0;

    if (nonce) {
      attributes.nonce = nonce;
    }
  }

  Object.keys(attributes).forEach(function (key) {
    style.setAttribute(key, attributes[key]);
  });

  if (typeof options.insert === 'function') {
    options.insert(style);
  } else {
    var target = getTarget(options.insert || 'head');

    if (!target) {
      throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");
    }

    target.appendChild(style);
  }

  return style;
}

function removeStyleElement(style) {
  // istanbul ignore if
  if (style.parentNode === null) {
    return false;
  }

  style.parentNode.removeChild(style);
}
/* istanbul ignore next  */


var replaceText = function replaceText() {
  var textStore = [];
  return function replace(index, replacement) {
    textStore[index] = replacement;
    return textStore.filter(Boolean).join('\n');
  };
}();

function applyToSingletonTag(style, index, remove, obj) {
  var css = remove ? '' : obj.media ? "@media ".concat(obj.media, " {").concat(obj.css, "}") : obj.css; // For old IE

  /* istanbul ignore if  */

  if (style.styleSheet) {
    style.styleSheet.cssText = replaceText(index, css);
  } else {
    var cssNode = document.createTextNode(css);
    var childNodes = style.childNodes;

    if (childNodes[index]) {
      style.removeChild(childNodes[index]);
    }

    if (childNodes.length) {
      style.insertBefore(cssNode, childNodes[index]);
    } else {
      style.appendChild(cssNode);
    }
  }
}

function applyToTag(style, options, obj) {
  var css = obj.css;
  var media = obj.media;
  var sourceMap = obj.sourceMap;

  if (media) {
    style.setAttribute('media', media);
  } else {
    style.removeAttribute('media');
  }

  if (sourceMap && typeof btoa !== 'undefined') {
    css += "\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))), " */");
  } // For old IE

  /* istanbul ignore if  */


  if (style.styleSheet) {
    style.styleSheet.cssText = css;
  } else {
    while (style.firstChild) {
      style.removeChild(style.firstChild);
    }

    style.appendChild(document.createTextNode(css));
  }
}

var singleton = null;
var singletonCounter = 0;

function addStyle(obj, options) {
  var style;
  var update;
  var remove;

  if (options.singleton) {
    var styleIndex = singletonCounter++;
    style = singleton || (singleton = insertStyleElement(options));
    update = applyToSingletonTag.bind(null, style, styleIndex, false);
    remove = applyToSingletonTag.bind(null, style, styleIndex, true);
  } else {
    style = insertStyleElement(options);
    update = applyToTag.bind(null, style, options);

    remove = function remove() {
      removeStyleElement(style);
    };
  }

  update(obj);
  return function updateStyle(newObj) {
    if (newObj) {
      if (newObj.css === obj.css && newObj.media === obj.media && newObj.sourceMap === obj.sourceMap) {
        return;
      }

      update(obj = newObj);
    } else {
      remove();
    }
  };
}

module.exports = function (list, options) {
  options = options || {}; // Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
  // tags it will allow on a page

  if (!options.singleton && typeof options.singleton !== 'boolean') {
    options.singleton = isOldIE();
  }

  list = list || [];
  var lastIdentifiers = modulesToDom(list, options);
  return function update(newList) {
    newList = newList || [];

    if (Object.prototype.toString.call(newList) !== '[object Array]') {
      return;
    }

    for (var i = 0; i < lastIdentifiers.length; i++) {
      var identifier = lastIdentifiers[i];
      var index = getIndexByIdentifier(identifier);
      stylesInDom[index].references--;
    }

    var newLastIdentifiers = modulesToDom(newList, options);

    for (var _i = 0; _i < lastIdentifiers.length; _i++) {
      var _identifier = lastIdentifiers[_i];

      var _index = getIndexByIdentifier(_identifier);

      if (stylesInDom[_index].references === 0) {
        stylesInDom[_index].updater();

        stylesInDom.splice(_index, 1);
      }
    }

    lastIdentifiers = newLastIdentifiers;
  };
};

/***/ }),

/***/ "./node_modules/@smui/button/Button.svelte":
/*!*************************************************!*\
  !*** ./node_modules/@smui/button/Button.svelte ***!
  \*************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _smui_ripple__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @smui/ripple */ "./node_modules/@smui/ripple/index.js");
/* harmony import */ var _smui_common_A_svelte__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @smui/common/A.svelte */ "./node_modules/@smui/common/A.svelte");
/* harmony import */ var _smui_common_Button_svelte__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @smui/common/Button.svelte */ "./node_modules/@smui/common/Button.svelte");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\button\Button.svelte generated by Svelte v3.48.0 */











function create_if_block(ctx) {
	let div;

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "mdc-button__touch");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
		}
	};
}

// (1:0) <svelte:component   this={component}   bind:this={element}   use={[     [       Ripple,       {         ripple,         unbounded: false,         color,         disabled: !!$$restProps.disabled,         addClass,         removeClass,         addStyle,       },     ],     forwardEvents,     ...use,   ]}   class={classMap({     [className]: true,     'mdc-button': true,     'mdc-button--raised': variant === 'raised',     'mdc-button--unelevated': variant === 'unelevated',     'mdc-button--outlined': variant === 'outlined',     'smui-button--color-secondary': color === 'secondary',     'mdc-button--touch': touch,     'mdc-card__action': context === 'card:action',     'mdc-card__action--button': context === 'card:action',     'mdc-dialog__button': context === 'dialog:action',     'mdc-top-app-bar__navigation-icon': context === 'top-app-bar:navigation',     'mdc-top-app-bar__action-item': context === 'top-app-bar:action',     'mdc-snackbar__action': context === 'snackbar:actions',     'mdc-banner__secondary-action': context === 'banner' && secondary,     'mdc-banner__primary-action': context === 'banner' && !secondary,     'mdc-tooltip__action': context === 'tooltip:rich-actions',     ...internalClasses,   })}   style={Object.entries(internalStyles)     .map(([name, value]) => `${name}: ${value};`)     .concat([style])     .join(' ')}   {...actionProp}   {...defaultProp}   {...secondaryProp}   {href}   on:click={handleClick}   {...$$restProps}   >
function create_default_slot(ctx) {
	let div;
	let t;
	let if_block_anchor;
	let current;
	const default_slot_template = /*#slots*/ ctx[26].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[28], null);
	let if_block = /*touch*/ ctx[6] && create_if_block(ctx);

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (default_slot) default_slot.c();
			if (if_block) if_block.c();
			if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "mdc-button__ripple");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);

			if (default_slot) {
				default_slot.m(target, anchor);
			}

			if (if_block) if_block.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
			current = true;
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 268435456)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[28],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[28])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[28], dirty, null),
						null
					);
				}
			}

			if (/*touch*/ ctx[6]) {
				if (if_block) {
					
				} else {
					if_block = create_if_block(ctx);
					if_block.c();
					if_block.m(if_block_anchor.parentNode, if_block_anchor);
				}
			} else if (if_block) {
				if_block.d(1);
				if_block = null;
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			if (default_slot) default_slot.d(detaching);
			if (if_block) if_block.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
		}
	};
}

function create_fragment(ctx) {
	let switch_instance;
	let switch_instance_anchor;
	let current;

	const switch_instance_spread_levels = [
		{
			use: [
				[
					_smui_ripple__WEBPACK_IMPORTED_MODULE_3__["default"],
					{
						ripple: /*ripple*/ ctx[3],
						unbounded: false,
						color: /*color*/ ctx[4],
						disabled: !!/*$$restProps*/ ctx[22].disabled,
						addClass: /*addClass*/ ctx[18],
						removeClass: /*removeClass*/ ctx[19],
						addStyle: /*addStyle*/ ctx[20]
					}
				],
				/*forwardEvents*/ ctx[16],
				.../*use*/ ctx[0]
			]
		},
		{
			class: (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[1]]: true,
				'mdc-button': true,
				'mdc-button--raised': /*variant*/ ctx[5] === 'raised',
				'mdc-button--unelevated': /*variant*/ ctx[5] === 'unelevated',
				'mdc-button--outlined': /*variant*/ ctx[5] === 'outlined',
				'smui-button--color-secondary': /*color*/ ctx[4] === 'secondary',
				'mdc-button--touch': /*touch*/ ctx[6],
				'mdc-card__action': /*context*/ ctx[17] === 'card:action',
				'mdc-card__action--button': /*context*/ ctx[17] === 'card:action',
				'mdc-dialog__button': /*context*/ ctx[17] === 'dialog:action',
				'mdc-top-app-bar__navigation-icon': /*context*/ ctx[17] === 'top-app-bar:navigation',
				'mdc-top-app-bar__action-item': /*context*/ ctx[17] === 'top-app-bar:action',
				'mdc-snackbar__action': /*context*/ ctx[17] === 'snackbar:actions',
				'mdc-banner__secondary-action': /*context*/ ctx[17] === 'banner' && /*secondary*/ ctx[8],
				'mdc-banner__primary-action': /*context*/ ctx[17] === 'banner' && !/*secondary*/ ctx[8],
				'mdc-tooltip__action': /*context*/ ctx[17] === 'tooltip:rich-actions',
				.../*internalClasses*/ ctx[11]
			})
		},
		{
			style: Object.entries(/*internalStyles*/ ctx[12]).map(func).concat([/*style*/ ctx[2]]).join(' ')
		},
		/*actionProp*/ ctx[15],
		/*defaultProp*/ ctx[14],
		/*secondaryProp*/ ctx[13],
		{ href: /*href*/ ctx[7] },
		/*$$restProps*/ ctx[22]
	];

	var switch_value = /*component*/ ctx[9];

	function switch_props(ctx) {
		let switch_instance_props = {
			$$slots: { default: [create_default_slot] },
			$$scope: { ctx }
		};

		for (let i = 0; i < switch_instance_spread_levels.length; i += 1) {
			switch_instance_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(switch_instance_props, switch_instance_spread_levels[i]);
		}

		return { props: switch_instance_props };
	}

	if (switch_value) {
		switch_instance = new switch_value(switch_props(ctx));
		/*switch_instance_binding*/ ctx[27](switch_instance);
		switch_instance.$on("click", /*handleClick*/ ctx[21]);
	}

	return {
		c() {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
			switch_instance_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (switch_instance) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, switch_instance_anchor, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			const switch_instance_changes = (dirty & /*Ripple, ripple, color, $$restProps, addClass, removeClass, addStyle, forwardEvents, use, classMap, className, variant, touch, context, secondary, internalClasses, Object, internalStyles, style, actionProp, defaultProp, secondaryProp, href*/ 6289919)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(switch_instance_spread_levels, [
					dirty & /*Ripple, ripple, color, $$restProps, addClass, removeClass, addStyle, forwardEvents, use*/ 6094873 && {
						use: [
							[
								_smui_ripple__WEBPACK_IMPORTED_MODULE_3__["default"],
								{
									ripple: /*ripple*/ ctx[3],
									unbounded: false,
									color: /*color*/ ctx[4],
									disabled: !!/*$$restProps*/ ctx[22].disabled,
									addClass: /*addClass*/ ctx[18],
									removeClass: /*removeClass*/ ctx[19],
									addStyle: /*addStyle*/ ctx[20]
								}
							],
							/*forwardEvents*/ ctx[16],
							.../*use*/ ctx[0]
						]
					},
					dirty & /*classMap, className, variant, color, touch, context, secondary, internalClasses*/ 133490 && {
						class: (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
							[/*className*/ ctx[1]]: true,
							'mdc-button': true,
							'mdc-button--raised': /*variant*/ ctx[5] === 'raised',
							'mdc-button--unelevated': /*variant*/ ctx[5] === 'unelevated',
							'mdc-button--outlined': /*variant*/ ctx[5] === 'outlined',
							'smui-button--color-secondary': /*color*/ ctx[4] === 'secondary',
							'mdc-button--touch': /*touch*/ ctx[6],
							'mdc-card__action': /*context*/ ctx[17] === 'card:action',
							'mdc-card__action--button': /*context*/ ctx[17] === 'card:action',
							'mdc-dialog__button': /*context*/ ctx[17] === 'dialog:action',
							'mdc-top-app-bar__navigation-icon': /*context*/ ctx[17] === 'top-app-bar:navigation',
							'mdc-top-app-bar__action-item': /*context*/ ctx[17] === 'top-app-bar:action',
							'mdc-snackbar__action': /*context*/ ctx[17] === 'snackbar:actions',
							'mdc-banner__secondary-action': /*context*/ ctx[17] === 'banner' && /*secondary*/ ctx[8],
							'mdc-banner__primary-action': /*context*/ ctx[17] === 'banner' && !/*secondary*/ ctx[8],
							'mdc-tooltip__action': /*context*/ ctx[17] === 'tooltip:rich-actions',
							.../*internalClasses*/ ctx[11]
						})
					},
					dirty & /*Object, internalStyles, style*/ 4100 && {
						style: Object.entries(/*internalStyles*/ ctx[12]).map(func).concat([/*style*/ ctx[2]]).join(' ')
					},
					dirty & /*actionProp*/ 32768 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*actionProp*/ ctx[15]),
					dirty & /*defaultProp*/ 16384 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*defaultProp*/ ctx[14]),
					dirty & /*secondaryProp*/ 8192 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*secondaryProp*/ ctx[13]),
					dirty & /*href*/ 128 && { href: /*href*/ ctx[7] },
					dirty & /*$$restProps*/ 4194304 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*$$restProps*/ ctx[22])
				])
			: {};

			if (dirty & /*$$scope, touch*/ 268435520) {
				switch_instance_changes.$$scope = { dirty, ctx };
			}

			if (switch_value !== (switch_value = /*component*/ ctx[9])) {
				if (switch_instance) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
					const old_component = switch_instance;

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (switch_value) {
					switch_instance = new switch_value(switch_props(ctx));
					/*switch_instance_binding*/ ctx[27](switch_instance);
					switch_instance.$on("click", /*handleClick*/ ctx[21]);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, switch_instance_anchor.parentNode, switch_instance_anchor);
				} else {
					switch_instance = null;
				}
			} else if (switch_value) {
				switch_instance.$set(switch_instance_changes);
			}
		},
		i(local) {
			if (current) return;
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
			current = true;
		},
		o(local) {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			/*switch_instance_binding*/ ctx[27](null);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(switch_instance_anchor);
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance, detaching);
		}
	};
}

const func = ([name, value]) => `${name}: ${value};`;

function instance($$self, $$props, $$invalidate) {
	let actionProp;
	let defaultProp;
	let secondaryProp;

	const omit_props_names = [
		"use","class","style","ripple","color","variant","touch","href","action","default","secondary","component","getElement"
	];

	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	const forwardEvents = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let { style = '' } = $$props;
	let { ripple = true } = $$props;
	let { color = 'primary' } = $$props;
	let { variant = 'text' } = $$props;
	let { touch = false } = $$props;
	let { href = null } = $$props;
	let { action = 'close' } = $$props;
	let { default: defaultAction = false } = $$props;
	let { secondary = false } = $$props;
	let element;
	let internalClasses = {};
	let internalStyles = {};
	let context = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)('SMUI:button:context');
	let { component = href == null ? _smui_common_Button_svelte__WEBPACK_IMPORTED_MODULE_5__["default"] : _smui_common_A_svelte__WEBPACK_IMPORTED_MODULE_4__["default"] } = $$props;
	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.setContext)('SMUI:label:context', 'button');
	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.setContext)('SMUI:icon:context', 'button');

	function addClass(className) {
		if (!internalClasses[className]) {
			$$invalidate(11, internalClasses[className] = true, internalClasses);
		}
	}

	function removeClass(className) {
		if (!(className in internalClasses) || internalClasses[className]) {
			$$invalidate(11, internalClasses[className] = false, internalClasses);
		}
	}

	function addStyle(name, value) {
		if (internalStyles[name] != value) {
			if (value === '' || value == null) {
				delete internalStyles[name];
				$$invalidate(12, internalStyles);
			} else {
				$$invalidate(12, internalStyles[name] = value, internalStyles);
			}
		}
	}

	function handleClick() {
		if (context === 'banner') {
			(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.dispatch)(getElement(), secondary
			? 'SMUI:banner:button:secondaryActionClick'
			: 'SMUI:banner:button:primaryActionClick');
		}
	}

	function getElement() {
		return element.getElement();
	}

	function switch_instance_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(10, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$invalidate(29, $$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props)));
		$$invalidate(22, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(1, className = $$new_props.class);
		if ('style' in $$new_props) $$invalidate(2, style = $$new_props.style);
		if ('ripple' in $$new_props) $$invalidate(3, ripple = $$new_props.ripple);
		if ('color' in $$new_props) $$invalidate(4, color = $$new_props.color);
		if ('variant' in $$new_props) $$invalidate(5, variant = $$new_props.variant);
		if ('touch' in $$new_props) $$invalidate(6, touch = $$new_props.touch);
		if ('href' in $$new_props) $$invalidate(7, href = $$new_props.href);
		if ('action' in $$new_props) $$invalidate(23, action = $$new_props.action);
		if ('default' in $$new_props) $$invalidate(24, defaultAction = $$new_props.default);
		if ('secondary' in $$new_props) $$invalidate(8, secondary = $$new_props.secondary);
		if ('component' in $$new_props) $$invalidate(9, component = $$new_props.component);
		if ('$$scope' in $$new_props) $$invalidate(28, $$scope = $$new_props.$$scope);
	};

	$$self.$$.update = () => {
		$: $$invalidate(15, actionProp = context === 'dialog:action' && action != null
		? { 'data-mdc-dialog-action': action }
		: { action: $$props.action });

		$: $$invalidate(14, defaultProp = context === 'dialog:action' && defaultAction
		? { 'data-mdc-dialog-button-default': '' }
		: { default: $$props.default });

		$: $$invalidate(13, secondaryProp = context === 'banner'
		? {}
		: { secondary: $$props.secondary });
	};

	$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$props);

	return [
		use,
		className,
		style,
		ripple,
		color,
		variant,
		touch,
		href,
		secondary,
		component,
		element,
		internalClasses,
		internalStyles,
		secondaryProp,
		defaultProp,
		actionProp,
		forwardEvents,
		context,
		addClass,
		removeClass,
		addStyle,
		handleClick,
		$$restProps,
		action,
		defaultAction,
		getElement,
		slots,
		switch_instance_binding,
		$$scope
	];
}

class Button_1 extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			use: 0,
			class: 1,
			style: 2,
			ripple: 3,
			color: 4,
			variant: 5,
			touch: 6,
			href: 7,
			action: 23,
			default: 24,
			secondary: 8,
			component: 9,
			getElement: 25
		});
	}

	get getElement() {
		return this.$$.ctx[25];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Button_1);



/***/ }),

/***/ "./node_modules/@smui/button/Group.svelte":
/*!************************************************!*\
  !*** ./node_modules/@smui/button/Group.svelte ***!
  \************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\button\Group.svelte generated by Svelte v3.48.0 */






function create_fragment(ctx) {
	let div;
	let div_class_value;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[8].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[7], null);

	let div_levels = [
		{
			class: div_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_1__.classMap)({
				[/*className*/ ctx[1]]: true,
				'smui-button__group': true,
				'smui-button__group--raised': /*variant*/ ctx[2] === 'raised'
			})
		},
		/*$$restProps*/ ctx[5]
	];

	let div_data = {};

	for (let i = 0; i < div_levels.length; i += 1) {
		div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(div_data, div_levels[i]);
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (default_slot) {
				default_slot.m(div, null);
			}

			/*div_binding*/ ctx[9](div);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_1__.useActions.call(null, div, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[4].call(null, div))
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 128)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[7],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[7])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[7], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(div_levels, [
				(!current || dirty & /*className, variant*/ 6 && div_class_value !== (div_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_1__.classMap)({
					[/*className*/ ctx[1]]: true,
					'smui-button__group': true,
					'smui-button__group--raised': /*variant*/ ctx[2] === 'raised'
				}))) && { class: div_class_value },
				dirty & /*$$restProps*/ 32 && /*$$restProps*/ ctx[5]
			]));

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (default_slot) default_slot.d(detaching);
			/*div_binding*/ ctx[9](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","class","variant","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	const forwardEvents = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_1__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let { variant = 'text' } = $$props;
	let element;

	function getElement() {
		return element;
	}

	function div_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(3, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(5, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(1, className = $$new_props.class);
		if ('variant' in $$new_props) $$invalidate(2, variant = $$new_props.variant);
		if ('$$scope' in $$new_props) $$invalidate(7, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		className,
		variant,
		element,
		forwardEvents,
		$$restProps,
		getElement,
		$$scope,
		slots,
		div_binding
	];
}

class Group extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			use: 0,
			class: 1,
			variant: 2,
			getElement: 6
		});
	}

	get getElement() {
		return this.$$.ctx[6];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Group);



/***/ }),

/***/ "./node_modules/@smui/common/A.svelte":
/*!********************************************!*\
  !*** ./node_modules/@smui/common/A.svelte ***!
  \********************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _internal_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\A.svelte generated by Svelte v3.48.0 */





function create_fragment(ctx) {
	let a;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[7].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[6], null);
	let a_levels = [{ href: /*href*/ ctx[0] }, /*$$restProps*/ ctx[4]];
	let a_data = {};

	for (let i = 0; i < a_levels.length; i += 1) {
		a_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(a_data, a_levels[i]);
	}

	return {
		c() {
			a = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("a");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(a, a_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, a, anchor);

			if (default_slot) {
				default_slot.m(a, null);
			}

			/*a_binding*/ ctx[8](a);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _internal_js__WEBPACK_IMPORTED_MODULE_1__.useActions.call(null, a, /*use*/ ctx[1])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[3].call(null, a))
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 64)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[6],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[6])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[6], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(a, a_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(a_levels, [
				(!current || dirty & /*href*/ 1) && { href: /*href*/ ctx[0] },
				dirty & /*$$restProps*/ 16 && /*$$restProps*/ ctx[4]
			]));

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 2) useActions_action.update.call(null, /*use*/ ctx[1]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(a);
			if (default_slot) default_slot.d(detaching);
			/*a_binding*/ ctx[8](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["href","use","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { href = 'javascript:void(0);' } = $$props;
	let { use = [] } = $$props;
	const forwardEvents = (0,_internal_js__WEBPACK_IMPORTED_MODULE_1__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let element = null;

	function getElement() {
		return element;
	}

	function a_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(2, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(4, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('href' in $$new_props) $$invalidate(0, href = $$new_props.href);
		if ('use' in $$new_props) $$invalidate(1, use = $$new_props.use);
		if ('$$scope' in $$new_props) $$invalidate(6, $$scope = $$new_props.$$scope);
	};

	return [
		href,
		use,
		element,
		forwardEvents,
		$$restProps,
		getElement,
		$$scope,
		slots,
		a_binding
	];
}

class A extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { href: 0, use: 1, getElement: 5 });
	}

	get getElement() {
		return this.$$.ctx[5];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (A);



/***/ }),

/***/ "./node_modules/@smui/common/Button.svelte":
/*!*************************************************!*\
  !*** ./node_modules/@smui/common/Button.svelte ***!
  \*************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _internal_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\Button.svelte generated by Svelte v3.48.0 */





function create_fragment(ctx) {
	let button;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[6].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[5], null);
	let button_levels = [/*$$restProps*/ ctx[3]];
	let button_data = {};

	for (let i = 0; i < button_levels.length; i += 1) {
		button_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(button_data, button_levels[i]);
	}

	return {
		c() {
			button = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("button");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(button, button_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, button, anchor);

			if (default_slot) {
				default_slot.m(button, null);
			}

			if (button.autofocus) button.focus();
			/*button_binding*/ ctx[7](button);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _internal_js__WEBPACK_IMPORTED_MODULE_1__.useActions.call(null, button, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[2].call(null, button))
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 32)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[5],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[5])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[5], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(button, button_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(button_levels, [dirty & /*$$restProps*/ 8 && /*$$restProps*/ ctx[3]]));
			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(button);
			if (default_slot) default_slot.d(detaching);
			/*button_binding*/ ctx[7](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { use = [] } = $$props;
	const forwardEvents = (0,_internal_js__WEBPACK_IMPORTED_MODULE_1__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let element = null;

	function getElement() {
		return element;
	}

	function button_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(1, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(3, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('$$scope' in $$new_props) $$invalidate(5, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		element,
		forwardEvents,
		$$restProps,
		getElement,
		$$scope,
		slots,
		button_binding
	];
}

class Button extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { use: 0, getElement: 4 });
	}

	get getElement() {
		return this.$$.ctx[4];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Button);



/***/ }),

/***/ "./node_modules/@smui/common/ClassAdder.svelte":
/*!*****************************************************!*\
  !*** ./node_modules/@smui/common/ClassAdder.svelte ***!
  \*****************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   "internals": () => (/* binding */ internals)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _forwardEventsBuilder_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./forwardEventsBuilder.js */ "./node_modules/@smui/common/forwardEventsBuilder.js");
/* harmony import */ var _classMap_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./classMap.js */ "./node_modules/@smui/common/classMap.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\ClassAdder.svelte generated by Svelte v3.48.0 */







function create_default_slot(ctx) {
	let current;
	const default_slot_template = /*#slots*/ ctx[10].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[12], null);

	return {
		c() {
			if (default_slot) default_slot.c();
		},
		m(target, anchor) {
			if (default_slot) {
				default_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 4096)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[12],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[12])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[12], dirty, null),
						null
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (default_slot) default_slot.d(detaching);
		}
	};
}

function create_fragment(ctx) {
	let switch_instance;
	let switch_instance_anchor;
	let current;

	const switch_instance_spread_levels = [
		{
			use: [/*forwardEvents*/ ctx[7], .../*use*/ ctx[0]]
		},
		{
			class: (0,_classMap_js__WEBPACK_IMPORTED_MODULE_3__.classMap)({
				[/*className*/ ctx[1]]: true,
				[/*smuiClass*/ ctx[5]]: true,
				.../*smuiClassMap*/ ctx[4]
			})
		},
		/*props*/ ctx[6],
		/*$$restProps*/ ctx[8]
	];

	var switch_value = /*component*/ ctx[2];

	function switch_props(ctx) {
		let switch_instance_props = {
			$$slots: { default: [create_default_slot] },
			$$scope: { ctx }
		};

		for (let i = 0; i < switch_instance_spread_levels.length; i += 1) {
			switch_instance_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(switch_instance_props, switch_instance_spread_levels[i]);
		}

		return { props: switch_instance_props };
	}

	if (switch_value) {
		switch_instance = new switch_value(switch_props(ctx));
		/*switch_instance_binding*/ ctx[11](switch_instance);
	}

	return {
		c() {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
			switch_instance_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (switch_instance) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, switch_instance_anchor, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			const switch_instance_changes = (dirty & /*forwardEvents, use, classMap, className, smuiClass, smuiClassMap, props, $$restProps*/ 499)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(switch_instance_spread_levels, [
					dirty & /*forwardEvents, use*/ 129 && {
						use: [/*forwardEvents*/ ctx[7], .../*use*/ ctx[0]]
					},
					dirty & /*classMap, className, smuiClass, smuiClassMap*/ 50 && {
						class: (0,_classMap_js__WEBPACK_IMPORTED_MODULE_3__.classMap)({
							[/*className*/ ctx[1]]: true,
							[/*smuiClass*/ ctx[5]]: true,
							.../*smuiClassMap*/ ctx[4]
						})
					},
					dirty & /*props*/ 64 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*props*/ ctx[6]),
					dirty & /*$$restProps*/ 256 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*$$restProps*/ ctx[8])
				])
			: {};

			if (dirty & /*$$scope*/ 4096) {
				switch_instance_changes.$$scope = { dirty, ctx };
			}

			if (switch_value !== (switch_value = /*component*/ ctx[2])) {
				if (switch_instance) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
					const old_component = switch_instance;

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (switch_value) {
					switch_instance = new switch_value(switch_props(ctx));
					/*switch_instance_binding*/ ctx[11](switch_instance);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, switch_instance_anchor.parentNode, switch_instance_anchor);
				} else {
					switch_instance = null;
				}
			} else if (switch_value) {
				switch_instance.$set(switch_instance_changes);
			}
		},
		i(local) {
			if (current) return;
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
			current = true;
		},
		o(local) {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			/*switch_instance_binding*/ ctx[11](null);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(switch_instance_anchor);
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance, detaching);
		}
	};
}

const internals = {
	component: null,
	class: '',
	// The class map maps classes to contexts. The context
	// should resolve to a Svelte store, and the class
	// will be added if the Svelte store's value is true.
	classMap: {},
	contexts: {},
	props: {}
};

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","class","component","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let element;
	const smuiClass = internals.class;
	const smuiClassMap = {};
	const smuiClassUnsubscribes = [];
	const contexts = internals.contexts;
	const props = internals.props;
	let { component = internals.component } = $$props;

	Object.entries(internals.classMap).forEach(([name, context]) => {
		const store = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)(context);

		if (store && 'subscribe' in store) {
			smuiClassUnsubscribes.push(store.subscribe(value => {
				$$invalidate(4, smuiClassMap[name] = value, smuiClassMap);
			}));
		}
	});

	const forwardEvents = (0,_forwardEventsBuilder_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());

	for (let context in contexts) {
		if (contexts.hasOwnProperty(context)) {
			(0,svelte__WEBPACK_IMPORTED_MODULE_1__.setContext)(context, contexts[context]);
		}
	}

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onDestroy)(() => {
		for (const unsubscribe of smuiClassUnsubscribes) {
			unsubscribe();
		}
	});

	function getElement() {
		return element.getElement();
	}

	function switch_instance_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(3, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(8, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(1, className = $$new_props.class);
		if ('component' in $$new_props) $$invalidate(2, component = $$new_props.component);
		if ('$$scope' in $$new_props) $$invalidate(12, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		className,
		component,
		element,
		smuiClassMap,
		smuiClass,
		props,
		forwardEvents,
		$$restProps,
		getElement,
		slots,
		switch_instance_binding,
		$$scope
	];
}

class ClassAdder extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			use: 0,
			class: 1,
			component: 2,
			getElement: 9
		});
	}

	get getElement() {
		return this.$$.ctx[9];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ClassAdder);




/***/ }),

/***/ "./node_modules/@smui/common/CommonIcon.svelte":
/*!*****************************************************!*\
  !*** ./node_modules/@smui/common/CommonIcon.svelte ***!
  \*****************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _internal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _I_svelte__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./I.svelte */ "./node_modules/@smui/common/I.svelte");
/* harmony import */ var _Svg_svelte__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Svg.svelte */ "./node_modules/@smui/common/Svg.svelte");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\CommonIcon.svelte generated by Svelte v3.48.0 */








function create_default_slot(ctx) {
	let current;
	const default_slot_template = /*#slots*/ ctx[9].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[11], null);

	return {
		c() {
			if (default_slot) default_slot.c();
		},
		m(target, anchor) {
			if (default_slot) {
				default_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 2048)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[11],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[11])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[11], dirty, null),
						null
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (default_slot) default_slot.d(detaching);
		}
	};
}

function create_fragment(ctx) {
	let switch_instance;
	let switch_instance_anchor;
	let current;

	const switch_instance_spread_levels = [
		{
			use: [/*forwardEvents*/ ctx[5], .../*use*/ ctx[0]]
		},
		{
			class: (0,_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[1]]: true,
				'mdc-button__icon': /*context*/ ctx[6] === 'button',
				'mdc-fab__icon': /*context*/ ctx[6] === 'fab',
				'mdc-icon-button__icon': /*context*/ ctx[6] === 'icon-button',
				'mdc-icon-button__icon--on': /*context*/ ctx[6] === 'icon-button' && /*on*/ ctx[2],
				'mdc-tab__icon': /*context*/ ctx[6] === 'tab',
				'mdc-banner__icon': /*context*/ ctx[6] === 'banner',
				'mdc-segmented-button__icon': /*context*/ ctx[6] === 'segmented-button'
			})
		},
		{ "aria-hidden": "true" },
		/*component*/ ctx[3] === _Svg_svelte__WEBPACK_IMPORTED_MODULE_4__["default"]
		? { focusable: 'false', tabindex: '-1' }
		: {},
		/*$$restProps*/ ctx[7]
	];

	var switch_value = /*component*/ ctx[3];

	function switch_props(ctx) {
		let switch_instance_props = {
			$$slots: { default: [create_default_slot] },
			$$scope: { ctx }
		};

		for (let i = 0; i < switch_instance_spread_levels.length; i += 1) {
			switch_instance_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(switch_instance_props, switch_instance_spread_levels[i]);
		}

		return { props: switch_instance_props };
	}

	if (switch_value) {
		switch_instance = new switch_value(switch_props(ctx));
		/*switch_instance_binding*/ ctx[10](switch_instance);
	}

	return {
		c() {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
			switch_instance_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (switch_instance) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, switch_instance_anchor, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			const switch_instance_changes = (dirty & /*forwardEvents, use, classMap, className, context, on, component, Svg, $$restProps*/ 239)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(switch_instance_spread_levels, [
					dirty & /*forwardEvents, use*/ 33 && {
						use: [/*forwardEvents*/ ctx[5], .../*use*/ ctx[0]]
					},
					dirty & /*classMap, className, context, on*/ 70 && {
						class: (0,_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
							[/*className*/ ctx[1]]: true,
							'mdc-button__icon': /*context*/ ctx[6] === 'button',
							'mdc-fab__icon': /*context*/ ctx[6] === 'fab',
							'mdc-icon-button__icon': /*context*/ ctx[6] === 'icon-button',
							'mdc-icon-button__icon--on': /*context*/ ctx[6] === 'icon-button' && /*on*/ ctx[2],
							'mdc-tab__icon': /*context*/ ctx[6] === 'tab',
							'mdc-banner__icon': /*context*/ ctx[6] === 'banner',
							'mdc-segmented-button__icon': /*context*/ ctx[6] === 'segmented-button'
						})
					},
					switch_instance_spread_levels[2],
					dirty & /*component, Svg*/ 8 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*component*/ ctx[3] === _Svg_svelte__WEBPACK_IMPORTED_MODULE_4__["default"]
					? { focusable: 'false', tabindex: '-1' }
					: {}),
					dirty & /*$$restProps*/ 128 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*$$restProps*/ ctx[7])
				])
			: {};

			if (dirty & /*$$scope*/ 2048) {
				switch_instance_changes.$$scope = { dirty, ctx };
			}

			if (switch_value !== (switch_value = /*component*/ ctx[3])) {
				if (switch_instance) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
					const old_component = switch_instance;

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (switch_value) {
					switch_instance = new switch_value(switch_props(ctx));
					/*switch_instance_binding*/ ctx[10](switch_instance);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, switch_instance_anchor.parentNode, switch_instance_anchor);
				} else {
					switch_instance = null;
				}
			} else if (switch_value) {
				switch_instance.$set(switch_instance_changes);
			}
		},
		i(local) {
			if (current) return;
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
			current = true;
		},
		o(local) {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			/*switch_instance_binding*/ ctx[10](null);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(switch_instance_anchor);
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance, detaching);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","class","on","component","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	const forwardEvents = (0,_internal_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let { on = false } = $$props;
	let element;
	let { component = _I_svelte__WEBPACK_IMPORTED_MODULE_3__["default"] } = $$props;
	const context = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)('SMUI:icon:context');

	function getElement() {
		return element.getElement();
	}

	function switch_instance_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(4, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(7, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(1, className = $$new_props.class);
		if ('on' in $$new_props) $$invalidate(2, on = $$new_props.on);
		if ('component' in $$new_props) $$invalidate(3, component = $$new_props.component);
		if ('$$scope' in $$new_props) $$invalidate(11, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		className,
		on,
		component,
		element,
		forwardEvents,
		context,
		$$restProps,
		getElement,
		slots,
		switch_instance_binding,
		$$scope
	];
}

class CommonIcon extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			use: 0,
			class: 1,
			on: 2,
			component: 3,
			getElement: 8
		});
	}

	get getElement() {
		return this.$$.ctx[8];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CommonIcon);



/***/ }),

/***/ "./node_modules/@smui/common/CommonLabel.svelte":
/*!******************************************************!*\
  !*** ./node_modules/@smui/common/CommonLabel.svelte ***!
  \******************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _internal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _Span_svelte__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Span.svelte */ "./node_modules/@smui/common/Span.svelte");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\CommonLabel.svelte generated by Svelte v3.48.0 */







function create_default_slot(ctx) {
	let current;
	const default_slot_template = /*#slots*/ ctx[9].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[11], null);

	return {
		c() {
			if (default_slot) default_slot.c();
		},
		m(target, anchor) {
			if (default_slot) {
				default_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 2048)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[11],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[11])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[11], dirty, null),
						null
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (default_slot) default_slot.d(detaching);
		}
	};
}

function create_fragment(ctx) {
	let switch_instance;
	let switch_instance_anchor;
	let current;

	const switch_instance_spread_levels = [
		{
			use: [/*forwardEvents*/ ctx[4], .../*use*/ ctx[0]]
		},
		{
			class: (0,_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[1]]: true,
				'mdc-button__label': /*context*/ ctx[5] === 'button',
				'mdc-fab__label': /*context*/ ctx[5] === 'fab',
				'mdc-tab__text-label': /*context*/ ctx[5] === 'tab',
				'mdc-image-list__label': /*context*/ ctx[5] === 'image-list',
				'mdc-snackbar__label': /*context*/ ctx[5] === 'snackbar',
				'mdc-banner__text': /*context*/ ctx[5] === 'banner',
				'mdc-segmented-button__label': /*context*/ ctx[5] === 'segmented-button',
				'mdc-data-table__pagination-rows-per-page-label': /*context*/ ctx[5] === 'data-table:pagination',
				'mdc-data-table__header-cell-label': /*context*/ ctx[5] === 'data-table:sortable-header-cell'
			})
		},
		/*context*/ ctx[5] === 'snackbar'
		? { 'aria-atomic': 'false' }
		: {},
		{ tabindex: /*tabindex*/ ctx[6] },
		/*$$restProps*/ ctx[7]
	];

	var switch_value = /*component*/ ctx[2];

	function switch_props(ctx) {
		let switch_instance_props = {
			$$slots: { default: [create_default_slot] },
			$$scope: { ctx }
		};

		for (let i = 0; i < switch_instance_spread_levels.length; i += 1) {
			switch_instance_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(switch_instance_props, switch_instance_spread_levels[i]);
		}

		return { props: switch_instance_props };
	}

	if (switch_value) {
		switch_instance = new switch_value(switch_props(ctx));
		/*switch_instance_binding*/ ctx[10](switch_instance);
	}

	return {
		c() {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
			switch_instance_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (switch_instance) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, switch_instance_anchor, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			const switch_instance_changes = (dirty & /*forwardEvents, use, classMap, className, context, tabindex, $$restProps*/ 243)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(switch_instance_spread_levels, [
					dirty & /*forwardEvents, use*/ 17 && {
						use: [/*forwardEvents*/ ctx[4], .../*use*/ ctx[0]]
					},
					dirty & /*classMap, className, context*/ 34 && {
						class: (0,_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
							[/*className*/ ctx[1]]: true,
							'mdc-button__label': /*context*/ ctx[5] === 'button',
							'mdc-fab__label': /*context*/ ctx[5] === 'fab',
							'mdc-tab__text-label': /*context*/ ctx[5] === 'tab',
							'mdc-image-list__label': /*context*/ ctx[5] === 'image-list',
							'mdc-snackbar__label': /*context*/ ctx[5] === 'snackbar',
							'mdc-banner__text': /*context*/ ctx[5] === 'banner',
							'mdc-segmented-button__label': /*context*/ ctx[5] === 'segmented-button',
							'mdc-data-table__pagination-rows-per-page-label': /*context*/ ctx[5] === 'data-table:pagination',
							'mdc-data-table__header-cell-label': /*context*/ ctx[5] === 'data-table:sortable-header-cell'
						})
					},
					dirty & /*context*/ 32 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*context*/ ctx[5] === 'snackbar'
					? { 'aria-atomic': 'false' }
					: {}),
					dirty & /*tabindex*/ 64 && { tabindex: /*tabindex*/ ctx[6] },
					dirty & /*$$restProps*/ 128 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*$$restProps*/ ctx[7])
				])
			: {};

			if (dirty & /*$$scope*/ 2048) {
				switch_instance_changes.$$scope = { dirty, ctx };
			}

			if (switch_value !== (switch_value = /*component*/ ctx[2])) {
				if (switch_instance) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
					const old_component = switch_instance;

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (switch_value) {
					switch_instance = new switch_value(switch_props(ctx));
					/*switch_instance_binding*/ ctx[10](switch_instance);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, switch_instance_anchor.parentNode, switch_instance_anchor);
				} else {
					switch_instance = null;
				}
			} else if (switch_value) {
				switch_instance.$set(switch_instance_changes);
			}
		},
		i(local) {
			if (current) return;
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
			current = true;
		},
		o(local) {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			/*switch_instance_binding*/ ctx[10](null);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(switch_instance_anchor);
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance, detaching);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","class","component","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	const forwardEvents = (0,_internal_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let element;
	let { component = _Span_svelte__WEBPACK_IMPORTED_MODULE_3__["default"] } = $$props;
	const context = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)('SMUI:label:context');
	const tabindex = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)('SMUI:label:tabindex');

	function getElement() {
		return element.getElement();
	}

	function switch_instance_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(3, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(7, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(1, className = $$new_props.class);
		if ('component' in $$new_props) $$invalidate(2, component = $$new_props.component);
		if ('$$scope' in $$new_props) $$invalidate(11, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		className,
		component,
		element,
		forwardEvents,
		context,
		tabindex,
		$$restProps,
		getElement,
		slots,
		switch_instance_binding,
		$$scope
	];
}

class CommonLabel extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			use: 0,
			class: 1,
			component: 2,
			getElement: 8
		});
	}

	get getElement() {
		return this.$$.ctx[8];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CommonLabel);



/***/ }),

/***/ "./node_modules/@smui/common/ContextFragment.svelte":
/*!**********************************************************!*\
  !*** ./node_modules/@smui/common/ContextFragment.svelte ***!
  \**********************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var svelte_store__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! svelte/store */ "./node_modules/svelte/store/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\ContextFragment.svelte generated by Svelte v3.48.0 */





function create_fragment(ctx) {
	let current;
	const default_slot_template = /*#slots*/ ctx[4].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[3], null);

	return {
		c() {
			if (default_slot) default_slot.c();
		},
		m(target, anchor) {
			if (default_slot) {
				default_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, [dirty]) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 8)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[3],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[3])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[3], dirty, null),
						null
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (default_slot) default_slot.d(detaching);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let $storeValue;
	let { $$slots: slots = {}, $$scope } = $$props;
	let { key } = $$props;
	let { value } = $$props;
	const storeValue = (0,svelte_store__WEBPACK_IMPORTED_MODULE_2__.writable)(value);
	(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, storeValue, value => $$invalidate(5, $storeValue = value));
	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.setContext)(key, storeValue);

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onDestroy)(() => {
		storeValue.set(undefined);
	});

	$$self.$$set = $$props => {
		if ('key' in $$props) $$invalidate(1, key = $$props.key);
		if ('value' in $$props) $$invalidate(2, value = $$props.value);
		if ('$$scope' in $$props) $$invalidate(3, $$scope = $$props.$$scope);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty & /*value*/ 4) {
			$: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(storeValue, $storeValue = value, $storeValue);
		}
	};

	return [storeValue, key, value, $$scope, slots];
}

class ContextFragment extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { key: 1, value: 2 });
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ContextFragment);



/***/ }),

/***/ "./node_modules/@smui/common/Div.svelte":
/*!**********************************************!*\
  !*** ./node_modules/@smui/common/Div.svelte ***!
  \**********************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _internal_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\Div.svelte generated by Svelte v3.48.0 */





function create_fragment(ctx) {
	let div;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[6].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[5], null);
	let div_levels = [/*$$restProps*/ ctx[3]];
	let div_data = {};

	for (let i = 0; i < div_levels.length; i += 1) {
		div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(div_data, div_levels[i]);
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (default_slot) {
				default_slot.m(div, null);
			}

			/*div_binding*/ ctx[7](div);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _internal_js__WEBPACK_IMPORTED_MODULE_1__.useActions.call(null, div, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[2].call(null, div))
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 32)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[5],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[5])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[5], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(div_levels, [dirty & /*$$restProps*/ 8 && /*$$restProps*/ ctx[3]]));
			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (default_slot) default_slot.d(detaching);
			/*div_binding*/ ctx[7](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { use = [] } = $$props;
	const forwardEvents = (0,_internal_js__WEBPACK_IMPORTED_MODULE_1__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let element = null;

	function getElement() {
		return element;
	}

	function div_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(1, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(3, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('$$scope' in $$new_props) $$invalidate(5, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		element,
		forwardEvents,
		$$restProps,
		getElement,
		$$scope,
		slots,
		div_binding
	];
}

class Div extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { use: 0, getElement: 4 });
	}

	get getElement() {
		return this.$$.ctx[4];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Div);



/***/ }),

/***/ "./node_modules/@smui/common/H2.svelte":
/*!*********************************************!*\
  !*** ./node_modules/@smui/common/H2.svelte ***!
  \*********************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _internal_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\H2.svelte generated by Svelte v3.48.0 */





function create_fragment(ctx) {
	let h2;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[6].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[5], null);
	let h2_levels = [/*$$restProps*/ ctx[3]];
	let h2_data = {};

	for (let i = 0; i < h2_levels.length; i += 1) {
		h2_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(h2_data, h2_levels[i]);
	}

	return {
		c() {
			h2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("h2");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(h2, h2_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, h2, anchor);

			if (default_slot) {
				default_slot.m(h2, null);
			}

			/*h2_binding*/ ctx[7](h2);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _internal_js__WEBPACK_IMPORTED_MODULE_1__.useActions.call(null, h2, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[2].call(null, h2))
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 32)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[5],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[5])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[5], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(h2, h2_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(h2_levels, [dirty & /*$$restProps*/ 8 && /*$$restProps*/ ctx[3]]));
			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(h2);
			if (default_slot) default_slot.d(detaching);
			/*h2_binding*/ ctx[7](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { use = [] } = $$props;
	const forwardEvents = (0,_internal_js__WEBPACK_IMPORTED_MODULE_1__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let element = null;

	function getElement() {
		return element;
	}

	function h2_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(1, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(3, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('$$scope' in $$new_props) $$invalidate(5, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		element,
		forwardEvents,
		$$restProps,
		getElement,
		$$scope,
		slots,
		h2_binding
	];
}

class H2 extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { use: 0, getElement: 4 });
	}

	get getElement() {
		return this.$$.ctx[4];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (H2);



/***/ }),

/***/ "./node_modules/@smui/common/I.svelte":
/*!********************************************!*\
  !*** ./node_modules/@smui/common/I.svelte ***!
  \********************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _internal_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\I.svelte generated by Svelte v3.48.0 */





function create_fragment(ctx) {
	let i;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[6].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[5], null);
	let i_levels = [/*$$restProps*/ ctx[3]];
	let i_data = {};

	for (let i = 0; i < i_levels.length; i += 1) {
		i_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(i_data, i_levels[i]);
	}

	return {
		c() {
			i = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("i");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(i, i_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, i, anchor);

			if (default_slot) {
				default_slot.m(i, null);
			}

			/*i_binding*/ ctx[7](i);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _internal_js__WEBPACK_IMPORTED_MODULE_1__.useActions.call(null, i, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[2].call(null, i))
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 32)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[5],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[5])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[5], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(i, i_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(i_levels, [dirty & /*$$restProps*/ 8 && /*$$restProps*/ ctx[3]]));
			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(i);
			if (default_slot) default_slot.d(detaching);
			/*i_binding*/ ctx[7](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { use = [] } = $$props;
	const forwardEvents = (0,_internal_js__WEBPACK_IMPORTED_MODULE_1__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let element = null;

	function getElement() {
		return element;
	}

	function i_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(1, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(3, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('$$scope' in $$new_props) $$invalidate(5, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		element,
		forwardEvents,
		$$restProps,
		getElement,
		$$scope,
		slots,
		i_binding
	];
}

class I extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { use: 0, getElement: 4 });
	}

	get getElement() {
		return this.$$.ctx[4];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (I);



/***/ }),

/***/ "./node_modules/@smui/common/Span.svelte":
/*!***********************************************!*\
  !*** ./node_modules/@smui/common/Span.svelte ***!
  \***********************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _internal_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\Span.svelte generated by Svelte v3.48.0 */





function create_fragment(ctx) {
	let span;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[6].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[5], null);
	let span_levels = [/*$$restProps*/ ctx[3]];
	let span_data = {};

	for (let i = 0; i < span_levels.length; i += 1) {
		span_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(span_data, span_levels[i]);
	}

	return {
		c() {
			span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(span, span_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);

			if (default_slot) {
				default_slot.m(span, null);
			}

			/*span_binding*/ ctx[7](span);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _internal_js__WEBPACK_IMPORTED_MODULE_1__.useActions.call(null, span, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[2].call(null, span))
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 32)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[5],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[5])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[5], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(span, span_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(span_levels, [dirty & /*$$restProps*/ 8 && /*$$restProps*/ ctx[3]]));
			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
			if (default_slot) default_slot.d(detaching);
			/*span_binding*/ ctx[7](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { use = [] } = $$props;
	const forwardEvents = (0,_internal_js__WEBPACK_IMPORTED_MODULE_1__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let element = null;

	function getElement() {
		return element;
	}

	function span_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(1, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(3, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('$$scope' in $$new_props) $$invalidate(5, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		element,
		forwardEvents,
		$$restProps,
		getElement,
		$$scope,
		slots,
		span_binding
	];
}

class Span extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { use: 0, getElement: 4 });
	}

	get getElement() {
		return this.$$.ctx[4];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Span);



/***/ }),

/***/ "./node_modules/@smui/common/Svg.svelte":
/*!**********************************************!*\
  !*** ./node_modules/@smui/common/Svg.svelte ***!
  \**********************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _internal_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\common\Svg.svelte generated by Svelte v3.48.0 */





function create_fragment(ctx) {
	let svg;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[6].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[5], null);
	let svg_levels = [/*$$restProps*/ ctx[3]];
	let svg_data = {};

	for (let i = 0; i < svg_levels.length; i += 1) {
		svg_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(svg_data, svg_levels[i]);
	}

	return {
		c() {
			svg = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.svg_element)("svg");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_svg_attributes)(svg, svg_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, svg, anchor);

			if (default_slot) {
				default_slot.m(svg, null);
			}

			/*svg_binding*/ ctx[7](svg);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _internal_js__WEBPACK_IMPORTED_MODULE_1__.useActions.call(null, svg, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[2].call(null, svg))
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 32)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[5],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[5])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[5], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_svg_attributes)(svg, svg_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(svg_levels, [dirty & /*$$restProps*/ 8 && /*$$restProps*/ ctx[3]]));
			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(svg);
			if (default_slot) default_slot.d(detaching);
			/*svg_binding*/ ctx[7](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { use = [] } = $$props;
	const forwardEvents = (0,_internal_js__WEBPACK_IMPORTED_MODULE_1__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let element = null;

	function getElement() {
		return element;
	}

	function svg_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(1, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(3, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('$$scope' in $$new_props) $$invalidate(5, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		element,
		forwardEvents,
		$$restProps,
		getElement,
		$$scope,
		slots,
		svg_binding
	];
}

class Svg extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { use: 0, getElement: 4 });
	}

	get getElement() {
		return this.$$.ctx[4];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Svg);



/***/ }),

/***/ "./node_modules/@smui/dialog/Dialog.svelte":
/*!*************************************************!*\
  !*** ./node_modules/@smui/dialog/Dialog.svelte ***!
  \*************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _material_dialog__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @material/dialog */ "./node_modules/@material/dialog/foundation.js");
/* harmony import */ var _material_dialog__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @material/dialog */ "./node_modules/@material/dialog/util.js");
/* harmony import */ var _material_dom__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @material/dom */ "./node_modules/@material/dom/focus-trap.js");
/* harmony import */ var _material_dom__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @material/dom */ "./node_modules/@material/dom/ponyfill.js");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var svelte_store__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! svelte/store */ "./node_modules/svelte/store/index.mjs");
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\dialog\Dialog.svelte generated by Svelte v3.48.0 */


const { document: document_1, window: window_1 } = svelte_internal__WEBPACK_IMPORTED_MODULE_0__.globals;








const get_over_slot_changes = dirty => ({});
const get_over_slot_context = ctx => ({});

// (47:6) {#if fullscreen}
function create_if_block(ctx) {
	let div;
	let mounted;
	let dispose;

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "mdc-dialog__surface-scrim");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (!mounted) {
				dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "transitionend", /*transitionend_handler*/ ctx[31]);
				mounted = true;
			}
		},
		p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			mounted = false;
			dispose();
		}
	};
}

function create_fragment(ctx) {
	let t0;
	let div3;
	let div1;
	let div0;
	let t1;
	let div0_class_value;
	let div1_class_value;
	let t2;
	let div2;
	let div3_class_value;
	let useActions_action;
	let forwardEvents_action;
	let t3;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[27].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[26], null);
	let if_block = /*fullscreen*/ ctx[5] && create_if_block(ctx);

	let div0_levels = [
		{
			class: div0_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.classMap)({
				[/*surface$class*/ ctx[7]]: true,
				'mdc-dialog__surface': true
			})
		},
		{ role: "alertdialog" },
		{ "aria-modal": "true" },
		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.prefixFilter)(/*$$restProps*/ ctx[17], 'surface$')
	];

	let div0_data = {};

	for (let i = 0; i < div0_levels.length; i += 1) {
		div0_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(div0_data, div0_levels[i]);
	}

	let div1_levels = [
		{
			class: div1_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.classMap)({
				[/*container$class*/ ctx[6]]: true,
				'mdc-dialog__container': true
			})
		},
		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.prefixFilter)(/*$$restProps*/ ctx[17], 'container$')
	];

	let div1_data = {};

	for (let i = 0; i < div1_levels.length; i += 1) {
		div1_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(div1_data, div1_levels[i]);
	}

	let div3_levels = [
		{
			class: div3_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.classMap)({
				[/*className*/ ctx[2]]: true,
				'mdc-dialog': true,
				'mdc-dialog--stacked': !/*autoStackButtons*/ ctx[4],
				'mdc-dialog--fullscreen': /*fullscreen*/ ctx[5],
				'smui-dialog--selection': /*selection*/ ctx[3],
				.../*internalClasses*/ ctx[10]
			})
		},
		{ role: "alertdialog" },
		{ "aria-modal": "true" },
		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.exclude)(/*$$restProps*/ ctx[17], ['container$', 'surface$'])
	];

	let div3_data = {};

	for (let i = 0; i < div3_levels.length; i += 1) {
		div3_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(div3_data, div3_levels[i]);
	}

	const over_slot_template = /*#slots*/ ctx[27].over;
	const over_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(over_slot_template, ctx, /*$$scope*/ ctx[26], get_over_slot_context);

	return {
		c() {
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			div1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			div0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (default_slot) default_slot.c();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block) if_block.c();
			t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			t3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (over_slot) over_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div0, div0_data);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div1, div1_data);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div2, "class", "mdc-dialog__scrim");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div3, div3_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t0, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div3, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div3, div1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div1, div0);

			if (default_slot) {
				default_slot.m(div0, null);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div0, t1);
			if (if_block) if_block.m(div0, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div3, t2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div3, div2);
			/*div3_binding*/ ctx[32](div3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t3, anchor);

			if (over_slot) {
				over_slot.m(target, anchor);
			}

			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(window_1, "resize", /*resize_handler*/ ctx[28]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(window_1, "orientationchange", /*orientationchange_handler*/ ctx[29]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(document_1.body, "keydown", /*keydown_handler*/ ctx[30]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.useActions.call(null, div3, /*use*/ ctx[1])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[11].call(null, div3)),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div3, "MDCDialog:opening", /*handleDialogOpening*/ ctx[14]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div3, "MDCDialog:opened", /*handleDialogOpened*/ ctx[15]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div3, "MDCDialog:closed", /*handleDialogClosed*/ ctx[16]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div3, "click", /*click_handler*/ ctx[33]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div3, "keydown", /*keydown_handler_1*/ ctx[34])
				];

				mounted = true;
			}
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty[0] & /*$$scope*/ 67108864)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[26],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[26])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[26], dirty, null),
						null
					);
				}
			}

			if (/*fullscreen*/ ctx[5]) {
				if (if_block) {
					if_block.p(ctx, dirty);
				} else {
					if_block = create_if_block(ctx);
					if_block.c();
					if_block.m(div0, null);
				}
			} else if (if_block) {
				if_block.d(1);
				if_block = null;
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div0, div0_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(div0_levels, [
				(!current || dirty[0] & /*surface$class*/ 128 && div0_class_value !== (div0_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.classMap)({
					[/*surface$class*/ ctx[7]]: true,
					'mdc-dialog__surface': true
				}))) && { class: div0_class_value },
				{ role: "alertdialog" },
				{ "aria-modal": "true" },
				dirty[0] & /*$$restProps*/ 131072 && (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.prefixFilter)(/*$$restProps*/ ctx[17], 'surface$')
			]));

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div1, div1_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(div1_levels, [
				(!current || dirty[0] & /*container$class*/ 64 && div1_class_value !== (div1_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.classMap)({
					[/*container$class*/ ctx[6]]: true,
					'mdc-dialog__container': true
				}))) && { class: div1_class_value },
				dirty[0] & /*$$restProps*/ 131072 && (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.prefixFilter)(/*$$restProps*/ ctx[17], 'container$')
			]));

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div3, div3_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(div3_levels, [
				(!current || dirty[0] & /*className, autoStackButtons, fullscreen, selection, internalClasses*/ 1084 && div3_class_value !== (div3_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.classMap)({
					[/*className*/ ctx[2]]: true,
					'mdc-dialog': true,
					'mdc-dialog--stacked': !/*autoStackButtons*/ ctx[4],
					'mdc-dialog--fullscreen': /*fullscreen*/ ctx[5],
					'smui-dialog--selection': /*selection*/ ctx[3],
					.../*internalClasses*/ ctx[10]
				}))) && { class: div3_class_value },
				{ role: "alertdialog" },
				{ "aria-modal": "true" },
				dirty[0] & /*$$restProps*/ 131072 && (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.exclude)(/*$$restProps*/ ctx[17], ['container$', 'surface$'])
			]));

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty[0] & /*use*/ 2) useActions_action.update.call(null, /*use*/ ctx[1]);

			if (over_slot) {
				if (over_slot.p && (!current || dirty[0] & /*$$scope*/ 67108864)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						over_slot,
						over_slot_template,
						ctx,
						/*$$scope*/ ctx[26],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[26])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(over_slot_template, /*$$scope*/ ctx[26], dirty, get_over_slot_changes),
						get_over_slot_context
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(over_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(over_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t0);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div3);
			if (default_slot) default_slot.d(detaching);
			if (if_block) if_block.d();
			/*div3_binding*/ ctx[32](null);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t3);
			if (over_slot) over_slot.d(detaching);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance_1($$self, $$props, $$invalidate) {
	const omit_props_names = [
		"use","class","open","selection","escapeKeyAction","scrimClickAction","autoStackButtons","fullscreen","container$class","surface$class","isOpen","setOpen","layout","getElement"
	];

	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let $aboveFullscreenShown;
	let $actionButtonsReversed;
	let { $$slots: slots = {}, $$scope } = $$props;
	const { FocusTrap } = _material_dom__WEBPACK_IMPORTED_MODULE_4__;
	const { closest, matches } = _material_dom__WEBPACK_IMPORTED_MODULE_5__;
	const forwardEvents = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let { open = false } = $$props;
	let { selection = false } = $$props;
	let { escapeKeyAction = 'close' } = $$props;
	let { scrimClickAction = 'close' } = $$props;
	let { autoStackButtons = true } = $$props;
	let { fullscreen = false } = $$props;
	let { container$class = '' } = $$props;
	let { surface$class = '' } = $$props;
	let element;
	let instance;
	let internalClasses = {};
	let focusTrap;
	let actionButtonsReversed = (0,svelte_store__WEBPACK_IMPORTED_MODULE_2__.writable)(false);
	(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, actionButtonsReversed, value => $$invalidate(37, $actionButtonsReversed = value));
	let addLayoutListener = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)('SMUI:addLayoutListener');
	let aboveFullscreen = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)('SMUI:dialog:aboveFullscreen');
	let aboveFullscreenShown = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)('SMUI:dialog:aboveFullscreenShown') || (0,svelte_store__WEBPACK_IMPORTED_MODULE_2__.writable)(false);
	(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, aboveFullscreenShown, value => $$invalidate(25, $aboveFullscreenShown = value));
	let removeLayoutListener;
	let layoutListeners = [];

	let addLayoutListenerFn = listener => {
		layoutListeners.push(listener);

		return () => {
			const idx = layoutListeners.indexOf(listener);

			if (idx >= 0) {
				layoutListeners.splice(idx, 1);
			}
		};
	};

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.setContext)('SMUI:dialog:actions:reversed', actionButtonsReversed);
	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.setContext)('SMUI:addLayoutListener', addLayoutListenerFn);
	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.setContext)('SMUI:dialog:selection', selection);
	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.setContext)('SMUI:dialog:aboveFullscreen', aboveFullscreen || fullscreen);
	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.setContext)('SMUI:dialog:aboveFullscreenShown', aboveFullscreenShown);

	if (addLayoutListener) {
		removeLayoutListener = addLayoutListener(layout);
	}

	let previousAboveFullscreenShown = $aboveFullscreenShown;

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		focusTrap = new FocusTrap(element, { initialFocusEl: getInitialFocusEl() });

		$$invalidate(8, instance = new _material_dialog__WEBPACK_IMPORTED_MODULE_6__.MDCDialogFoundation({
				addBodyClass: className => document.body.classList.add(className),
				addClass,
				areButtonsStacked: () => _material_dialog__WEBPACK_IMPORTED_MODULE_7__.areTopsMisaligned(getButtonEls()),
				clickDefaultButton: () => {
					const defaultButton = getDefaultButtonEl();

					if (defaultButton) {
						defaultButton.click();
					}
				},
				eventTargetMatches: (target, selector) => target ? matches(target, selector) : false,
				getActionFromEvent: evt => {
					if (!evt.target) {
						return '';
					}

					const element = closest(evt.target, '[data-mdc-dialog-action]');
					return element && element.getAttribute('data-mdc-dialog-action');
				},
				getInitialFocusEl,
				hasClass,
				isContentScrollable: () => _material_dialog__WEBPACK_IMPORTED_MODULE_7__.isScrollable(getContentEl()),
				notifyClosed: action => {
					$$invalidate(0, open = false);
					(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.dispatch)(getElement(), 'MDCDialog:closed', action ? { action } : {});
				},
				notifyClosing: action => (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.dispatch)(getElement(), 'MDCDialog:closing', action ? { action } : {}),
				notifyOpened: () => (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.dispatch)(getElement(), 'MDCDialog:opened', {}),
				notifyOpening: () => (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_3__.dispatch)(getElement(), 'MDCDialog:opening', {}),
				releaseFocus: () => focusTrap.releaseFocus(),
				removeBodyClass: className => document.body.classList.remove(className),
				removeClass,
				reverseButtons: () => {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(actionButtonsReversed, $actionButtonsReversed = true, $actionButtonsReversed);
				},
				trapFocus: () => focusTrap.trapFocus(),
				registerContentEventHandler: (evt, handler) => {
					const content = getContentEl();

					if (content instanceof HTMLElement) {
						content.addEventListener(evt, handler);
					}
				},
				deregisterContentEventHandler: (evt, handler) => {
					const content = getContentEl();

					if (content instanceof HTMLElement) {
						content.removeEventListener(evt, handler);
					}
				},
				isScrollableContentAtTop: () => {
					return _material_dialog__WEBPACK_IMPORTED_MODULE_7__.isScrollAtTop(getContentEl());
				},
				isScrollableContentAtBottom: () => {
					return _material_dialog__WEBPACK_IMPORTED_MODULE_7__.isScrollAtBottom(getContentEl());
				},
				registerWindowEventHandler: (evt, handler) => {
					window.addEventListener(evt, handler);
				},
				deregisterWindowEventHandler: (evt, handler) => {
					window.removeEventListener(evt, handler);
				}
			}));

		instance.init();

		return () => {
			instance.destroy();
		};
	});

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onDestroy)(() => {
		if (removeLayoutListener) {
			removeLayoutListener();
		}
	});

	function hasClass(className) {
		return className in internalClasses
		? internalClasses[className]
		: getElement().classList.contains(className);
	}

	function addClass(className) {
		if (!internalClasses[className]) {
			$$invalidate(10, internalClasses[className] = true, internalClasses);
		}
	}

	function removeClass(className) {
		if (!(className in internalClasses) || internalClasses[className]) {
			$$invalidate(10, internalClasses[className] = false, internalClasses);
		}
	}

	function getButtonEls() {
		return [].slice.call(element.querySelectorAll('.mdc-dialog__button'));
	}

	function getDefaultButtonEl() {
		return element.querySelector('[data-mdc-dialog-button-default');
	}

	function getContentEl() {
		return element.querySelector('.mdc-dialog__content');
	}

	function getInitialFocusEl() {
		return element.querySelector('[data-mdc-dialog-initial-focus]');
	}

	function handleDialogOpening() {
		if (aboveFullscreen) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(aboveFullscreenShown, $aboveFullscreenShown = true, $aboveFullscreenShown);
		}

		requestAnimationFrame(() => {
			layoutListeners.forEach(listener => listener());
		});
	}

	function handleDialogOpened() {
		layoutListeners.forEach(listener => listener());
	}

	function handleDialogClosed() {
		if (aboveFullscreen) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(aboveFullscreenShown, $aboveFullscreenShown = false, $aboveFullscreenShown);
		}
	}

	function isOpen() {
		return open;
	}

	function setOpen(value) {
		$$invalidate(0, open = value);
	}

	function layout() {
		return instance.layout();
	}

	function getElement() {
		return element;
	}

	const resize_handler = () => open && instance && instance.layout();
	const orientationchange_handler = () => open && instance && instance.layout();
	const keydown_handler = event => open && instance && instance.handleDocumentKeydown(event);
	const transitionend_handler = () => instance && instance.handleSurfaceScrimTransitionEnd();

	function div3_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(9, element);
		});
	}

	const click_handler = event => instance && instance.handleClick(event);
	const keydown_handler_1 = event => instance && instance.handleKeydown(event);

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(17, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(1, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(2, className = $$new_props.class);
		if ('open' in $$new_props) $$invalidate(0, open = $$new_props.open);
		if ('selection' in $$new_props) $$invalidate(3, selection = $$new_props.selection);
		if ('escapeKeyAction' in $$new_props) $$invalidate(18, escapeKeyAction = $$new_props.escapeKeyAction);
		if ('scrimClickAction' in $$new_props) $$invalidate(19, scrimClickAction = $$new_props.scrimClickAction);
		if ('autoStackButtons' in $$new_props) $$invalidate(4, autoStackButtons = $$new_props.autoStackButtons);
		if ('fullscreen' in $$new_props) $$invalidate(5, fullscreen = $$new_props.fullscreen);
		if ('container$class' in $$new_props) $$invalidate(6, container$class = $$new_props.container$class);
		if ('surface$class' in $$new_props) $$invalidate(7, surface$class = $$new_props.surface$class);
		if ('$$scope' in $$new_props) $$invalidate(26, $$scope = $$new_props.$$scope);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty[0] & /*instance, escapeKeyAction*/ 262400) {
			$: if (instance && instance.getEscapeKeyAction() !== escapeKeyAction) {
				instance.setEscapeKeyAction(escapeKeyAction);
			}
		}

		if ($$self.$$.dirty[0] & /*instance, scrimClickAction*/ 524544) {
			$: if (instance && instance.getScrimClickAction() !== scrimClickAction) {
				instance.setScrimClickAction(scrimClickAction);
			}
		}

		if ($$self.$$.dirty[0] & /*instance, autoStackButtons*/ 272) {
			$: if (instance && instance.getAutoStackButtons() !== autoStackButtons) {
				instance.setAutoStackButtons(autoStackButtons);
			}
		}

		if ($$self.$$.dirty[0] & /*autoStackButtons*/ 16) {
			$: if (!autoStackButtons) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_store_value)(actionButtonsReversed, $actionButtonsReversed = true, $actionButtonsReversed);
			}
		}

		if ($$self.$$.dirty[0] & /*instance, open*/ 257) {
			$: if (instance && instance.isOpen() !== open) {
				if (open) {
					instance.open({
						isAboveFullscreenDialog: !!aboveFullscreen
					});
				} else {
					instance.close();
				}
			}
		}

		if ($$self.$$.dirty[0] & /*fullscreen, instance, previousAboveFullscreenShown, $aboveFullscreenShown*/ 50331936) {
			$: if (fullscreen && instance && previousAboveFullscreenShown !== $aboveFullscreenShown) {
				$$invalidate(24, previousAboveFullscreenShown = $aboveFullscreenShown);

				if ($aboveFullscreenShown) {
					instance.showSurfaceScrim();
				} else {
					instance.hideSurfaceScrim();
				}
			}
		}
	};

	return [
		open,
		use,
		className,
		selection,
		autoStackButtons,
		fullscreen,
		container$class,
		surface$class,
		instance,
		element,
		internalClasses,
		forwardEvents,
		actionButtonsReversed,
		aboveFullscreenShown,
		handleDialogOpening,
		handleDialogOpened,
		handleDialogClosed,
		$$restProps,
		escapeKeyAction,
		scrimClickAction,
		isOpen,
		setOpen,
		layout,
		getElement,
		previousAboveFullscreenShown,
		$aboveFullscreenShown,
		$$scope,
		slots,
		resize_handler,
		orientationchange_handler,
		keydown_handler,
		transitionend_handler,
		div3_binding,
		click_handler,
		keydown_handler_1
	];
}

class Dialog extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(
			this,
			options,
			instance_1,
			create_fragment,
			svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal,
			{
				use: 1,
				class: 2,
				open: 0,
				selection: 3,
				escapeKeyAction: 18,
				scrimClickAction: 19,
				autoStackButtons: 4,
				fullscreen: 5,
				container$class: 6,
				surface$class: 7,
				isOpen: 20,
				setOpen: 21,
				layout: 22,
				getElement: 23
			},
			null,
			[-1, -1]
		);
	}

	get isOpen() {
		return this.$$.ctx[20];
	}

	get setOpen() {
		return this.$$.ctx[21];
	}

	get layout() {
		return this.$$.ctx[22];
	}

	get getElement() {
		return this.$$.ctx[23];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Dialog);



/***/ }),

/***/ "./node_modules/@smui/floating-label/FloatingLabel.svelte":
/*!****************************************************************!*\
  !*** ./node_modules/@smui/floating-label/FloatingLabel.svelte ***!
  \****************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _material_floating_label__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @material/floating-label */ "./node_modules/@material/floating-label/foundation.js");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\floating-label\FloatingLabel.svelte generated by Svelte v3.48.0 */








function create_else_block(ctx) {
	let label;
	let label_class_value;
	let label_style_value;
	let label_for_value;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[22].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[21], null);

	let label_levels = [
		{
			class: label_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[3]]: true,
				'mdc-floating-label': true,
				'mdc-floating-label--float-above': /*floatAbove*/ ctx[0],
				'mdc-floating-label--required': /*required*/ ctx[1],
				.../*internalClasses*/ ctx[8]
			})
		},
		{
			style: label_style_value = Object.entries(/*internalStyles*/ ctx[9]).map(func_1).concat([/*style*/ ctx[4]]).join(' ')
		},
		{
			for: label_for_value = /*forId*/ ctx[5] || (/*inputProps*/ ctx[11]
			? /*inputProps*/ ctx[11].id
			: null)
		},
		/*$$restProps*/ ctx[12]
	];

	let label_data = {};

	for (let i = 0; i < label_levels.length; i += 1) {
		label_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(label_data, label_levels[i]);
	}

	return {
		c() {
			label = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("label");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(label, label_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, label, anchor);

			if (default_slot) {
				default_slot.m(label, null);
			}

			/*label_binding*/ ctx[24](label);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.useActions.call(null, label, /*use*/ ctx[2])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[10].call(null, label))
				];

				mounted = true;
			}
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 2097152)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[21],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[21])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[21], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(label, label_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(label_levels, [
				(!current || dirty & /*className, floatAbove, required, internalClasses*/ 267 && label_class_value !== (label_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
					[/*className*/ ctx[3]]: true,
					'mdc-floating-label': true,
					'mdc-floating-label--float-above': /*floatAbove*/ ctx[0],
					'mdc-floating-label--required': /*required*/ ctx[1],
					.../*internalClasses*/ ctx[8]
				}))) && { class: label_class_value },
				(!current || dirty & /*internalStyles, style*/ 528 && label_style_value !== (label_style_value = Object.entries(/*internalStyles*/ ctx[9]).map(func_1).concat([/*style*/ ctx[4]]).join(' '))) && { style: label_style_value },
				(!current || dirty & /*forId*/ 32 && label_for_value !== (label_for_value = /*forId*/ ctx[5] || (/*inputProps*/ ctx[11]
				? /*inputProps*/ ctx[11].id
				: null))) && { for: label_for_value },
				dirty & /*$$restProps*/ 4096 && /*$$restProps*/ ctx[12]
			]));

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 4) useActions_action.update.call(null, /*use*/ ctx[2]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(label);
			if (default_slot) default_slot.d(detaching);
			/*label_binding*/ ctx[24](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

// (1:0) {#if wrapped}
function create_if_block(ctx) {
	let span;
	let span_class_value;
	let span_style_value;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const default_slot_template = /*#slots*/ ctx[22].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[21], null);

	let span_levels = [
		{
			class: span_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[3]]: true,
				'mdc-floating-label': true,
				'mdc-floating-label--float-above': /*floatAbove*/ ctx[0],
				'mdc-floating-label--required': /*required*/ ctx[1],
				.../*internalClasses*/ ctx[8]
			})
		},
		{
			style: span_style_value = Object.entries(/*internalStyles*/ ctx[9]).map(func).concat([/*style*/ ctx[4]]).join(' ')
		},
		/*$$restProps*/ ctx[12]
	];

	let span_data = {};

	for (let i = 0; i < span_levels.length; i += 1) {
		span_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(span_data, span_levels[i]);
	}

	return {
		c() {
			span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(span, span_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);

			if (default_slot) {
				default_slot.m(span, null);
			}

			/*span_binding*/ ctx[23](span);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.useActions.call(null, span, /*use*/ ctx[2])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[10].call(null, span))
				];

				mounted = true;
			}
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 2097152)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[21],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[21])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[21], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(span, span_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(span_levels, [
				(!current || dirty & /*className, floatAbove, required, internalClasses*/ 267 && span_class_value !== (span_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
					[/*className*/ ctx[3]]: true,
					'mdc-floating-label': true,
					'mdc-floating-label--float-above': /*floatAbove*/ ctx[0],
					'mdc-floating-label--required': /*required*/ ctx[1],
					.../*internalClasses*/ ctx[8]
				}))) && { class: span_class_value },
				(!current || dirty & /*internalStyles, style*/ 528 && span_style_value !== (span_style_value = Object.entries(/*internalStyles*/ ctx[9]).map(func).concat([/*style*/ ctx[4]]).join(' '))) && { style: span_style_value },
				dirty & /*$$restProps*/ 4096 && /*$$restProps*/ ctx[12]
			]));

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 4) useActions_action.update.call(null, /*use*/ ctx[2]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
			if (default_slot) default_slot.d(detaching);
			/*span_binding*/ ctx[23](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function create_fragment(ctx) {
	let current_block_type_index;
	let if_block;
	let if_block_anchor;
	let current;
	const if_block_creators = [create_if_block, create_else_block];
	const if_blocks = [];

	function select_block_type(ctx, dirty) {
		if (/*wrapped*/ ctx[6]) return 0;
		return 1;
	}

	current_block_type_index = select_block_type(ctx, -1);
	if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);

	return {
		c() {
			if_block.c();
			if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if_blocks[current_block_type_index].m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			let previous_block_index = current_block_type_index;
			current_block_type_index = select_block_type(ctx, dirty);

			if (current_block_type_index === previous_block_index) {
				if_blocks[current_block_type_index].p(ctx, dirty);
			} else {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
					if_blocks[previous_block_index] = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				if_block = if_blocks[current_block_type_index];

				if (!if_block) {
					if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
					if_block.c();
				} else {
					if_block.p(ctx, dirty);
				}

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
				if_block.m(if_block_anchor.parentNode, if_block_anchor);
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
			current = false;
		},
		d(detaching) {
			if_blocks[current_block_type_index].d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
		}
	};
}

const func = ([name, value]) => `${name}: ${value};`;
const func_1 = ([name, value]) => `${name}: ${value};`;

function instance_1($$self, $$props, $$invalidate) {
	const omit_props_names = [
		"use","class","style","for","floatAbove","required","wrapped","shake","float","setRequired","getWidth","getElement"
	];

	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	const forwardEvents = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let { style = '' } = $$props;
	let { for: forId = null } = $$props;
	let { floatAbove = false } = $$props;
	let { required = false } = $$props;
	let { wrapped = false } = $$props;
	let element;
	let instance;
	let internalClasses = {};
	let internalStyles = {};
	let inputProps = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)('SMUI:generic:input:props') || {};
	let previousFloatAbove = floatAbove;
	let previousRequired = required;

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		$$invalidate(18, instance = new _material_floating_label__WEBPACK_IMPORTED_MODULE_3__.MDCFloatingLabelFoundation({
				addClass,
				removeClass,
				getWidth: () => {
					const el = getElement();
					const clone = el.cloneNode(true);
					el.parentNode.appendChild(clone);
					clone.classList.add('smui-floating-label--remove-transition');
					clone.classList.add('smui-floating-label--force-size');
					clone.classList.remove('mdc-floating-label--float-above');
					const scrollWidth = clone.scrollWidth;
					el.parentNode.removeChild(clone);
					return scrollWidth;
				},
				registerInteractionHandler: (evtType, handler) => getElement().addEventListener(evtType, handler),
				deregisterInteractionHandler: (evtType, handler) => getElement().removeEventListener(evtType, handler)
			}));

		const accessor = {
			get element() {
				return getElement();
			},
			addStyle,
			removeStyle
		};

		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.dispatch)(element, 'SMUI:floating-label:mount', accessor);
		instance.init();

		return () => {
			(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.dispatch)(element, 'SMUI:floating-label:unmount', accessor);
			instance.destroy();
		};
	});

	function addClass(className) {
		if (!internalClasses[className]) {
			$$invalidate(8, internalClasses[className] = true, internalClasses);
		}
	}

	function removeClass(className) {
		if (!(className in internalClasses) || internalClasses[className]) {
			$$invalidate(8, internalClasses[className] = false, internalClasses);
		}
	}

	function addStyle(name, value) {
		if (internalStyles[name] != value) {
			if (value === '' || value == null) {
				delete internalStyles[name];
				$$invalidate(9, internalStyles);
			} else {
				$$invalidate(9, internalStyles[name] = value, internalStyles);
			}
		}
	}

	function removeStyle(name) {
		if (name in internalStyles) {
			delete internalStyles[name];
			$$invalidate(9, internalStyles);
		}
	}

	function shake(shouldShake) {
		instance.shake(shouldShake);
	}

	function float(shouldFloat) {
		$$invalidate(0, floatAbove = shouldFloat);
	}

	function setRequired(isRequired) {
		$$invalidate(1, required = isRequired);
	}

	function getWidth() {
		return instance.getWidth();
	}

	function getElement() {
		return element;
	}

	function span_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(7, element);
		});
	}

	function label_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(7, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(12, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(2, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(3, className = $$new_props.class);
		if ('style' in $$new_props) $$invalidate(4, style = $$new_props.style);
		if ('for' in $$new_props) $$invalidate(5, forId = $$new_props.for);
		if ('floatAbove' in $$new_props) $$invalidate(0, floatAbove = $$new_props.floatAbove);
		if ('required' in $$new_props) $$invalidate(1, required = $$new_props.required);
		if ('wrapped' in $$new_props) $$invalidate(6, wrapped = $$new_props.wrapped);
		if ('$$scope' in $$new_props) $$invalidate(21, $$scope = $$new_props.$$scope);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty & /*previousFloatAbove, floatAbove, instance*/ 786433) {
			$: if (previousFloatAbove !== floatAbove) {
				$$invalidate(19, previousFloatAbove = floatAbove);
				instance.float(floatAbove);
			}
		}

		if ($$self.$$.dirty & /*previousRequired, required, instance*/ 1310722) {
			$: if (previousRequired !== required) {
				$$invalidate(20, previousRequired = required);
				instance.setRequired(required);
			}
		}
	};

	return [
		floatAbove,
		required,
		use,
		className,
		style,
		forId,
		wrapped,
		element,
		internalClasses,
		internalStyles,
		forwardEvents,
		inputProps,
		$$restProps,
		shake,
		float,
		setRequired,
		getWidth,
		getElement,
		instance,
		previousFloatAbove,
		previousRequired,
		$$scope,
		slots,
		span_binding,
		label_binding
	];
}

class FloatingLabel extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance_1, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			use: 2,
			class: 3,
			style: 4,
			for: 5,
			floatAbove: 0,
			required: 1,
			wrapped: 6,
			shake: 13,
			float: 14,
			setRequired: 15,
			getWidth: 16,
			getElement: 17
		});
	}

	get shake() {
		return this.$$.ctx[13];
	}

	get float() {
		return this.$$.ctx[14];
	}

	get setRequired() {
		return this.$$.ctx[15];
	}

	get getWidth() {
		return this.$$.ctx[16];
	}

	get getElement() {
		return this.$$.ctx[17];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (FloatingLabel);



/***/ }),

/***/ "./node_modules/@smui/line-ripple/LineRipple.svelte":
/*!**********************************************************!*\
  !*** ./node_modules/@smui/line-ripple/LineRipple.svelte ***!
  \**********************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _material_line_ripple__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @material/line-ripple */ "./node_modules/@material/line-ripple/foundation.js");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\line-ripple\LineRipple.svelte generated by Svelte v3.48.0 */








function create_fragment(ctx) {
	let div;
	let div_class_value;
	let div_style_value;
	let useActions_action;
	let forwardEvents_action;
	let mounted;
	let dispose;

	let div_levels = [
		{
			class: div_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[1]]: true,
				'mdc-line-ripple': true,
				'mdc-line-ripple--active': /*active*/ ctx[3],
				.../*internalClasses*/ ctx[5]
			})
		},
		{
			style: div_style_value = Object.entries(/*internalStyles*/ ctx[6]).map(func).concat([/*style*/ ctx[2]]).join(' ')
		},
		/*$$restProps*/ ctx[8]
	];

	let div_data = {};

	for (let i = 0; i < div_levels.length; i += 1) {
		div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(div_data, div_levels[i]);
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			/*div_binding*/ ctx[13](div);

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.useActions.call(null, div, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[7].call(null, div))
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(div_levels, [
				dirty & /*className, active, internalClasses*/ 42 && div_class_value !== (div_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
					[/*className*/ ctx[1]]: true,
					'mdc-line-ripple': true,
					'mdc-line-ripple--active': /*active*/ ctx[3],
					.../*internalClasses*/ ctx[5]
				})) && { class: div_class_value },
				dirty & /*internalStyles, style*/ 68 && div_style_value !== (div_style_value = Object.entries(/*internalStyles*/ ctx[6]).map(func).concat([/*style*/ ctx[2]]).join(' ')) && { style: div_style_value },
				dirty & /*$$restProps*/ 256 && /*$$restProps*/ ctx[8]
			]));

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			/*div_binding*/ ctx[13](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

const func = ([name, value]) => `${name}: ${value};`;

function instance_1($$self, $$props, $$invalidate) {
	const omit_props_names = [
		"use","class","style","active","activate","deactivate","setRippleCenter","getElement"
	];

	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	const forwardEvents = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let { style = '' } = $$props;
	let { active = false } = $$props;
	let element;
	let instance;
	let internalClasses = {};
	let internalStyles = {};

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		instance = new _material_line_ripple__WEBPACK_IMPORTED_MODULE_3__.MDCLineRippleFoundation({
				addClass,
				removeClass,
				hasClass,
				setStyle: addStyle,
				registerEventHandler: (evtType, handler) => getElement().addEventListener(evtType, handler),
				deregisterEventHandler: (evtType, handler) => getElement().removeEventListener(evtType, handler)
			});

		instance.init();

		return () => {
			instance.destroy();
		};
	});

	function hasClass(className) {
		return className in internalClasses
		? internalClasses[className]
		: getElement().classList.contains(className);
	}

	function addClass(className) {
		if (!internalClasses[className]) {
			$$invalidate(5, internalClasses[className] = true, internalClasses);
		}
	}

	function removeClass(className) {
		if (!(className in internalClasses) || internalClasses[className]) {
			$$invalidate(5, internalClasses[className] = false, internalClasses);
		}
	}

	function addStyle(name, value) {
		if (internalStyles[name] != value) {
			if (value === '' || value == null) {
				delete internalStyles[name];
				$$invalidate(6, internalStyles);
			} else {
				$$invalidate(6, internalStyles[name] = value, internalStyles);
			}
		}
	}

	function activate() {
		instance.activate();
	}

	function deactivate() {
		instance.deactivate();
	}

	function setRippleCenter(xCoordinate) {
		instance.setRippleCenter(xCoordinate);
	}

	function getElement() {
		return element;
	}

	function div_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(4, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(8, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(1, className = $$new_props.class);
		if ('style' in $$new_props) $$invalidate(2, style = $$new_props.style);
		if ('active' in $$new_props) $$invalidate(3, active = $$new_props.active);
	};

	return [
		use,
		className,
		style,
		active,
		element,
		internalClasses,
		internalStyles,
		forwardEvents,
		$$restProps,
		activate,
		deactivate,
		setRippleCenter,
		getElement,
		div_binding
	];
}

class LineRipple extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance_1, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			use: 0,
			class: 1,
			style: 2,
			active: 3,
			activate: 9,
			deactivate: 10,
			setRippleCenter: 11,
			getElement: 12
		});
	}

	get activate() {
		return this.$$.ctx[9];
	}

	get deactivate() {
		return this.$$.ctx[10];
	}

	get setRippleCenter() {
		return this.$$.ctx[11];
	}

	get getElement() {
		return this.$$.ctx[12];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (LineRipple);



/***/ }),

/***/ "./node_modules/@smui/notched-outline/NotchedOutline.svelte":
/*!******************************************************************!*\
  !*** ./node_modules/@smui/notched-outline/NotchedOutline.svelte ***!
  \******************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _material_notched_outline__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @material/notched-outline */ "./node_modules/@material/notched-outline/foundation.js");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\notched-outline\NotchedOutline.svelte generated by Svelte v3.48.0 */








function create_if_block(ctx) {
	let div;
	let div_style_value;
	let current;
	const default_slot_template = /*#slots*/ ctx[14].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[13], null);

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "mdc-notched-outline__notch");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "style", div_style_value = Object.entries(/*notchStyles*/ ctx[7]).map(func).join(' '));
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (default_slot) {
				default_slot.m(div, null);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 8192)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[13],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[13])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[13], dirty, null),
						null
					);
				}
			}

			if (!current || dirty & /*notchStyles*/ 128 && div_style_value !== (div_style_value = Object.entries(/*notchStyles*/ ctx[7]).map(func).join(' '))) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "style", div_style_value);
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (default_slot) default_slot.d(detaching);
		}
	};
}

function create_fragment(ctx) {
	let div2;
	let div0;
	let t0;
	let t1;
	let div1;
	let div2_class_value;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	let if_block = !/*noLabel*/ ctx[3] && create_if_block(ctx);

	let div2_levels = [
		{
			class: div2_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[1]]: true,
				'mdc-notched-outline': true,
				'mdc-notched-outline--notched': /*notched*/ ctx[2],
				'mdc-notched-outline--no-label': /*noLabel*/ ctx[3],
				.../*internalClasses*/ ctx[6]
			})
		},
		/*$$restProps*/ ctx[9]
	];

	let div2_data = {};

	for (let i = 0; i < div2_levels.length; i += 1) {
		div2_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(div2_data, div2_levels[i]);
	}

	return {
		c() {
			div2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			div0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block) if_block.c();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div0, "class", "mdc-notched-outline__leading");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div1, "class", "mdc-notched-outline__trailing");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div2, div2_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div2, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div2, div0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div2, t0);
			if (if_block) if_block.m(div2, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div2, t1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div2, div1);
			/*div2_binding*/ ctx[15](div2);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.useActions.call(null, div2, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[8].call(null, div2)),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div2, "SMUI:floating-label:mount", /*SMUI_floating_label_mount_handler*/ ctx[16]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div2, "SMUI:floating-label:unmount", /*SMUI_floating_label_unmount_handler*/ ctx[17])
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			if (!/*noLabel*/ ctx[3]) {
				if (if_block) {
					if_block.p(ctx, dirty);

					if (dirty & /*noLabel*/ 8) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
					}
				} else {
					if_block = create_if_block(ctx);
					if_block.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
					if_block.m(div2, t1);
				}
			} else if (if_block) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block, 1, 1, () => {
					if_block = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div2, div2_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(div2_levels, [
				(!current || dirty & /*className, notched, noLabel, internalClasses*/ 78 && div2_class_value !== (div2_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
					[/*className*/ ctx[1]]: true,
					'mdc-notched-outline': true,
					'mdc-notched-outline--notched': /*notched*/ ctx[2],
					'mdc-notched-outline--no-label': /*noLabel*/ ctx[3],
					.../*internalClasses*/ ctx[6]
				}))) && { class: div2_class_value },
				dirty & /*$$restProps*/ 512 && /*$$restProps*/ ctx[9]
			]));

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div2);
			if (if_block) if_block.d();
			/*div2_binding*/ ctx[15](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

const func = ([name, value]) => `${name}: ${value};`;

function instance_1($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","class","notched","noLabel","notch","closeNotch","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	const forwardEvents = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let { notched = false } = $$props;
	let { noLabel = false } = $$props;
	let element;
	let instance;
	let floatingLabel;
	let internalClasses = {};
	let notchStyles = {};

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		instance = new _material_notched_outline__WEBPACK_IMPORTED_MODULE_3__.MDCNotchedOutlineFoundation({
				addClass,
				removeClass,
				setNotchWidthProperty: width => addNotchStyle('width', width + 'px'),
				removeNotchWidthProperty: () => removeNotchStyle('width')
			});

		instance.init();

		return () => {
			instance.destroy();
		};
	});

	function addClass(className) {
		if (!internalClasses[className]) {
			$$invalidate(6, internalClasses[className] = true, internalClasses);
		}
	}

	function removeClass(className) {
		if (!(className in internalClasses) || internalClasses[className]) {
			$$invalidate(6, internalClasses[className] = false, internalClasses);
		}
	}

	function addNotchStyle(name, value) {
		if (notchStyles[name] != value) {
			if (value === '' || value == null) {
				delete notchStyles[name];
				$$invalidate(7, notchStyles);
			} else {
				$$invalidate(7, notchStyles[name] = value, notchStyles);
			}
		}
	}

	function removeNotchStyle(name) {
		if (name in notchStyles) {
			delete notchStyles[name];
			$$invalidate(7, notchStyles);
		}
	}

	function notch(notchWidth) {
		instance.notch(notchWidth);
	}

	function closeNotch() {
		instance.closeNotch();
	}

	function getElement() {
		return element;
	}

	function div2_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(5, element);
		});
	}

	const SMUI_floating_label_mount_handler = event => $$invalidate(4, floatingLabel = event.detail);
	const SMUI_floating_label_unmount_handler = () => $$invalidate(4, floatingLabel = undefined);

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(9, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(1, className = $$new_props.class);
		if ('notched' in $$new_props) $$invalidate(2, notched = $$new_props.notched);
		if ('noLabel' in $$new_props) $$invalidate(3, noLabel = $$new_props.noLabel);
		if ('$$scope' in $$new_props) $$invalidate(13, $$scope = $$new_props.$$scope);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty & /*floatingLabel*/ 16) {
			$: if (floatingLabel) {
				floatingLabel.addStyle('transition-duration', '0s');
				addClass('mdc-notched-outline--upgraded');

				requestAnimationFrame(() => {
					floatingLabel.removeStyle('transition-duration');
				});
			} else {
				removeClass('mdc-notched-outline--upgraded');
			}
		}
	};

	return [
		use,
		className,
		notched,
		noLabel,
		floatingLabel,
		element,
		internalClasses,
		notchStyles,
		forwardEvents,
		$$restProps,
		notch,
		closeNotch,
		getElement,
		$$scope,
		slots,
		div2_binding,
		SMUI_floating_label_mount_handler,
		SMUI_floating_label_unmount_handler
	];
}

class NotchedOutline extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance_1, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			use: 0,
			class: 1,
			notched: 2,
			noLabel: 3,
			notch: 10,
			closeNotch: 11,
			getElement: 12
		});
	}

	get notch() {
		return this.$$.ctx[10];
	}

	get closeNotch() {
		return this.$$.ctx[11];
	}

	get getElement() {
		return this.$$.ctx[12];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (NotchedOutline);



/***/ }),

/***/ "./node_modules/@smui/textfield/Input.svelte":
/*!***************************************************!*\
  !*** ./node_modules/@smui/textfield/Input.svelte ***!
  \***************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\textfield\Input.svelte generated by Svelte v3.48.0 */







function create_fragment(ctx) {
	let input;
	let input_class_value;
	let useActions_action;
	let forwardEvents_action;
	let mounted;
	let dispose;

	let input_levels = [
		{
			class: input_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[1]]: true,
				'mdc-text-field__input': true
			})
		},
		{ type: /*type*/ ctx[2] },
		{ placeholder: /*placeholder*/ ctx[3] },
		/*valueProp*/ ctx[4],
		/*internalAttrs*/ ctx[6],
		/*$$restProps*/ ctx[10]
	];

	let input_data = {};

	for (let i = 0; i < input_levels.length; i += 1) {
		input_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(input_data, input_levels[i]);
	}

	return {
		c() {
			input = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("input");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(input, input_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, input, anchor);
			if (input.autofocus) input.focus();
			/*input_binding*/ ctx[21](input);

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.useActions.call(null, input, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[7].call(null, input)),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(input, "change", /*change_handler*/ ctx[22]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(input, "input", /*input_handler*/ ctx[23]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(input, "change", /*changeHandler*/ ctx[9])
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(input, input_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(input_levels, [
				dirty & /*className*/ 2 && input_class_value !== (input_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
					[/*className*/ ctx[1]]: true,
					'mdc-text-field__input': true
				})) && { class: input_class_value },
				dirty & /*type*/ 4 && { type: /*type*/ ctx[2] },
				dirty & /*placeholder*/ 8 && { placeholder: /*placeholder*/ ctx[3] },
				dirty & /*valueProp*/ 16 && /*valueProp*/ ctx[4],
				dirty & /*internalAttrs*/ 64 && /*internalAttrs*/ ctx[6],
				dirty & /*$$restProps*/ 1024 && /*$$restProps*/ ctx[10]
			]));

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(input);
			/*input_binding*/ ctx[21](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function toNumber(value) {
	if (value === '') {
		const nan = new Number(Number.NaN);
		nan.length = 0;
		return nan;
	}

	return +value;
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = [
		"use","class","type","placeholder","value","files","dirty","invalid","updateInvalid","getAttr","addAttr","removeAttr","focus","getElement"
	];

	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	const forwardEvents = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let { type = 'text' } = $$props;
	let { placeholder = ' ' } = $$props;
	let { value = '' } = $$props;
	let { files = undefined } = $$props;
	let { dirty = false } = $$props;
	let { invalid = false } = $$props;
	let { updateInvalid = true } = $$props;
	let element;
	let internalAttrs = {};
	let valueProp = {};

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		if (updateInvalid) {
			$$invalidate(14, invalid = element.matches(':invalid'));
		}
	});

	function valueUpdater(e) {
		switch (type) {
			case 'number':
			case 'range':
				$$invalidate(11, value = toNumber(e.target.value));
				break;
			case 'file':
				$$invalidate(12, files = e.target.files);
			default:
				$$invalidate(11, value = e.target.value);
				break;
		}
	}

	function changeHandler(e) {
		$$invalidate(13, dirty = true);

		if (updateInvalid) {
			$$invalidate(14, invalid = element.matches(':invalid'));
		}
	}

	function getAttr(name) {
		return name in internalAttrs
		? internalAttrs[name]
		: getElement().getAttribute(name);
	}

	function addAttr(name, value) {
		if (internalAttrs[name] !== value) {
			$$invalidate(6, internalAttrs[name] = value, internalAttrs);
		}
	}

	function removeAttr(name) {
		if (!(name in internalAttrs) || internalAttrs[name] != null) {
			$$invalidate(6, internalAttrs[name] = undefined, internalAttrs);
		}
	}

	function focus() {
		getElement().focus();
	}

	function getElement() {
		return element;
	}

	function input_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(5, element);
		});
	}

	const change_handler = e => (type === 'file' || type === 'range') && valueUpdater(e);
	const input_handler = e => type !== 'file' && valueUpdater(e);

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(10, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(1, className = $$new_props.class);
		if ('type' in $$new_props) $$invalidate(2, type = $$new_props.type);
		if ('placeholder' in $$new_props) $$invalidate(3, placeholder = $$new_props.placeholder);
		if ('value' in $$new_props) $$invalidate(11, value = $$new_props.value);
		if ('files' in $$new_props) $$invalidate(12, files = $$new_props.files);
		if ('dirty' in $$new_props) $$invalidate(13, dirty = $$new_props.dirty);
		if ('invalid' in $$new_props) $$invalidate(14, invalid = $$new_props.invalid);
		if ('updateInvalid' in $$new_props) $$invalidate(15, updateInvalid = $$new_props.updateInvalid);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty & /*type, valueProp, value*/ 2068) {
			$: if (type === 'file') {
				delete valueProp.value;
				(($$invalidate(4, valueProp), $$invalidate(2, type)), $$invalidate(11, value));
			} else {
				$$invalidate(4, valueProp.value = value == null ? '' : value, valueProp);
			}
		}
	};

	return [
		use,
		className,
		type,
		placeholder,
		valueProp,
		element,
		internalAttrs,
		forwardEvents,
		valueUpdater,
		changeHandler,
		$$restProps,
		value,
		files,
		dirty,
		invalid,
		updateInvalid,
		getAttr,
		addAttr,
		removeAttr,
		focus,
		getElement,
		input_binding,
		change_handler,
		input_handler
	];
}

class Input extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			use: 0,
			class: 1,
			type: 2,
			placeholder: 3,
			value: 11,
			files: 12,
			dirty: 13,
			invalid: 14,
			updateInvalid: 15,
			getAttr: 16,
			addAttr: 17,
			removeAttr: 18,
			focus: 19,
			getElement: 20
		});
	}

	get getAttr() {
		return this.$$.ctx[16];
	}

	get addAttr() {
		return this.$$.ctx[17];
	}

	get removeAttr() {
		return this.$$.ctx[18];
	}

	get focus() {
		return this.$$.ctx[19];
	}

	get getElement() {
		return this.$$.ctx[20];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Input);



/***/ }),

/***/ "./node_modules/@smui/textfield/Textarea.svelte":
/*!******************************************************!*\
  !*** ./node_modules/@smui/textfield/Textarea.svelte ***!
  \******************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\textfield\Textarea.svelte generated by Svelte v3.48.0 */







function create_fragment(ctx) {
	let textarea;
	let textarea_class_value;
	let textarea_style_value;
	let useActions_action;
	let forwardEvents_action;
	let mounted;
	let dispose;

	let textarea_levels = [
		{
			class: textarea_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[2]]: true,
				'mdc-text-field__input': true
			})
		},
		{
			style: textarea_style_value = `${/*resizable*/ ctx[4] ? '' : 'resize: none; '}${/*style*/ ctx[3]}`
		},
		/*internalAttrs*/ ctx[6],
		/*$$restProps*/ ctx[9]
	];

	let textarea_data = {};

	for (let i = 0; i < textarea_levels.length; i += 1) {
		textarea_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(textarea_data, textarea_levels[i]);
	}

	return {
		c() {
			textarea = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("textarea");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(textarea, textarea_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, textarea, anchor);
			if (textarea.autofocus) textarea.focus();
			/*textarea_binding*/ ctx[18](textarea);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_input_value)(textarea, /*value*/ ctx[0]);

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.useActions.call(null, textarea, /*use*/ ctx[1])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[7].call(null, textarea)),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(textarea, "change", /*changeHandler*/ ctx[8]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(textarea, "input", /*textarea_input_handler*/ ctx[19])
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(textarea, textarea_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(textarea_levels, [
				dirty & /*className*/ 4 && textarea_class_value !== (textarea_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
					[/*className*/ ctx[2]]: true,
					'mdc-text-field__input': true
				})) && { class: textarea_class_value },
				dirty & /*resizable, style*/ 24 && textarea_style_value !== (textarea_style_value = `${/*resizable*/ ctx[4] ? '' : 'resize: none; '}${/*style*/ ctx[3]}`) && { style: textarea_style_value },
				dirty & /*internalAttrs*/ 64 && /*internalAttrs*/ ctx[6],
				dirty & /*$$restProps*/ 512 && /*$$restProps*/ ctx[9]
			]));

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 2) useActions_action.update.call(null, /*use*/ ctx[1]);

			if (dirty & /*value*/ 1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_input_value)(textarea, /*value*/ ctx[0]);
			}
		},
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(textarea);
			/*textarea_binding*/ ctx[18](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const omit_props_names = [
		"use","class","style","value","dirty","invalid","updateInvalid","resizable","getAttr","addAttr","removeAttr","focus","getElement"
	];

	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	const forwardEvents = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let { style = '' } = $$props;
	let { value = '' } = $$props;
	let { dirty = false } = $$props;
	let { invalid = false } = $$props;
	let { updateInvalid = true } = $$props;
	let { resizable = true } = $$props;
	let element;
	let internalAttrs = {};

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		if (updateInvalid) {
			$$invalidate(11, invalid = element.matches(':invalid'));
		}
	});

	function changeHandler() {
		$$invalidate(10, dirty = true);

		if (updateInvalid) {
			$$invalidate(11, invalid = element.matches(':invalid'));
		}
	}

	function getAttr(name) {
		return name in internalAttrs
		? internalAttrs[name]
		: getElement().getAttribute(name);
	}

	function addAttr(name, value) {
		if (internalAttrs[name] !== value) {
			$$invalidate(6, internalAttrs[name] = value, internalAttrs);
		}
	}

	function removeAttr(name) {
		if (!(name in internalAttrs) || internalAttrs[name] != null) {
			$$invalidate(6, internalAttrs[name] = undefined, internalAttrs);
		}
	}

	function focus() {
		getElement().focus();
	}

	function getElement() {
		return element;
	}

	function textarea_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(5, element);
		});
	}

	function textarea_input_handler() {
		value = this.value;
		$$invalidate(0, value);
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(9, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(1, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(2, className = $$new_props.class);
		if ('style' in $$new_props) $$invalidate(3, style = $$new_props.style);
		if ('value' in $$new_props) $$invalidate(0, value = $$new_props.value);
		if ('dirty' in $$new_props) $$invalidate(10, dirty = $$new_props.dirty);
		if ('invalid' in $$new_props) $$invalidate(11, invalid = $$new_props.invalid);
		if ('updateInvalid' in $$new_props) $$invalidate(12, updateInvalid = $$new_props.updateInvalid);
		if ('resizable' in $$new_props) $$invalidate(4, resizable = $$new_props.resizable);
	};

	return [
		value,
		use,
		className,
		style,
		resizable,
		element,
		internalAttrs,
		forwardEvents,
		changeHandler,
		$$restProps,
		dirty,
		invalid,
		updateInvalid,
		getAttr,
		addAttr,
		removeAttr,
		focus,
		getElement,
		textarea_binding,
		textarea_input_handler
	];
}

class Textarea extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			use: 1,
			class: 2,
			style: 3,
			value: 0,
			dirty: 10,
			invalid: 11,
			updateInvalid: 12,
			resizable: 4,
			getAttr: 13,
			addAttr: 14,
			removeAttr: 15,
			focus: 16,
			getElement: 17
		});
	}

	get getAttr() {
		return this.$$.ctx[13];
	}

	get addAttr() {
		return this.$$.ctx[14];
	}

	get removeAttr() {
		return this.$$.ctx[15];
	}

	get focus() {
		return this.$$.ctx[16];
	}

	get getElement() {
		return this.$$.ctx[17];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Textarea);



/***/ }),

/***/ "./node_modules/@smui/textfield/Textfield.svelte":
/*!*******************************************************!*\
  !*** ./node_modules/@smui/textfield/Textfield.svelte ***!
  \*******************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _material_textfield__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! @material/textfield */ "./node_modules/@material/textfield/foundation.js");
/* harmony import */ var _material_dom__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! @material/dom */ "./node_modules/@material/dom/events.js");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _smui_common_ContextFragment_svelte__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @smui/common/ContextFragment.svelte */ "./node_modules/@smui/common/ContextFragment.svelte");
/* harmony import */ var _smui_ripple__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @smui/ripple */ "./node_modules/@smui/ripple/index.js");
/* harmony import */ var _smui_floating_label_FloatingLabel_svelte__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @smui/floating-label/FloatingLabel.svelte */ "./node_modules/@smui/floating-label/FloatingLabel.svelte");
/* harmony import */ var _smui_line_ripple_LineRipple_svelte__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @smui/line-ripple/LineRipple.svelte */ "./node_modules/@smui/line-ripple/LineRipple.svelte");
/* harmony import */ var _smui_notched_outline_NotchedOutline_svelte__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @smui/notched-outline/NotchedOutline.svelte */ "./node_modules/@smui/notched-outline/NotchedOutline.svelte");
/* harmony import */ var _HelperLine_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./HelperLine.js */ "./node_modules/@smui/textfield/HelperLine.js");
/* harmony import */ var _Prefix_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./Prefix.js */ "./node_modules/@smui/textfield/Prefix.js");
/* harmony import */ var _Suffix_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./Suffix.js */ "./node_modules/@smui/textfield/Suffix.js");
/* harmony import */ var _Input_svelte__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./Input.svelte */ "./node_modules/@smui/textfield/Input.svelte");
/* harmony import */ var _Textarea_svelte__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./Textarea.svelte */ "./node_modules/@smui/textfield/Textarea.svelte");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\textfield\Textfield.svelte generated by Svelte v3.48.0 */



















const get_helper_slot_changes = dirty => ({});
const get_helper_slot_context = ctx => ({});
const get_ripple_slot_changes = dirty => ({});
const get_ripple_slot_context = ctx => ({});
const get_trailingIcon_slot_changes_1 = dirty => ({});
const get_trailingIcon_slot_context_1 = ctx => ({});
const get_leadingIcon_slot_changes_1 = dirty => ({});
const get_leadingIcon_slot_context_1 = ctx => ({});
const get_label_slot_changes_2 = dirty => ({});
const get_label_slot_context_2 = ctx => ({});
const get_trailingIcon_slot_changes = dirty => ({});
const get_trailingIcon_slot_context = ctx => ({});
const get_suffix_slot_changes = dirty => ({});
const get_suffix_slot_context = ctx => ({});
const get_prefix_slot_changes = dirty => ({});
const get_prefix_slot_context = ctx => ({});
const get_internalCounter_slot_changes = dirty => ({});
const get_internalCounter_slot_context = ctx => ({});
const get_leadingIcon_slot_changes = dirty => ({});
const get_leadingIcon_slot_context = ctx => ({});
const get_label_slot_changes_1 = dirty => ({});
const get_label_slot_context_1 = ctx => ({});
const get_label_slot_changes = dirty => ({});
const get_label_slot_context = ctx => ({});

// (164:0) {:else}
function create_else_block_1(ctx) {
	let div;
	let t0;
	let contextfragment0;
	let t1;
	let t2;
	let contextfragment1;
	let t3;
	let div_class_value;
	let div_style_value;
	let Ripple_action;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const label_slot_template = /*#slots*/ ctx[50].label;
	const label_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(label_slot_template, ctx, /*$$scope*/ ctx[89], get_label_slot_context_2);

	contextfragment0 = new _smui_common_ContextFragment_svelte__WEBPACK_IMPORTED_MODULE_3__["default"]({
			props: {
				key: "SMUI:textfield:icon:leading",
				value: true,
				$$slots: { default: [create_default_slot_9] },
				$$scope: { ctx }
			}
		});

	const default_slot_template = /*#slots*/ ctx[50].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[89], null);

	contextfragment1 = new _smui_common_ContextFragment_svelte__WEBPACK_IMPORTED_MODULE_3__["default"]({
			props: {
				key: "SMUI:textfield:icon:leading",
				value: false,
				$$slots: { default: [create_default_slot_8] },
				$$scope: { ctx }
			}
		});

	const ripple_slot_template = /*#slots*/ ctx[50].ripple;
	const ripple_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(ripple_slot_template, ctx, /*$$scope*/ ctx[89], get_ripple_slot_context);

	let div_levels = [
		{
			class: div_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[9]]: true,
				'mdc-text-field': true,
				'mdc-text-field--disabled': /*disabled*/ ctx[12],
				'mdc-text-field--textarea': /*textarea*/ ctx[14],
				'mdc-text-field--filled': /*variant*/ ctx[15] === 'filled',
				'mdc-text-field--outlined': /*variant*/ ctx[15] === 'outlined',
				'smui-text-field--standard': /*variant*/ ctx[15] === 'standard' && !/*textarea*/ ctx[14],
				'mdc-text-field--no-label': /*noLabel*/ ctx[16] || !/*$$slots*/ ctx[41].label,
				'mdc-text-field--with-leading-icon': /*$$slots*/ ctx[41].leadingIcon,
				'mdc-text-field--with-trailing-icon': /*$$slots*/ ctx[41].trailingIcon,
				'mdc-text-field--invalid': /*invalid*/ ctx[2] !== /*uninitializedValue*/ ctx[36] && /*invalid*/ ctx[2],
				.../*internalClasses*/ ctx[26]
			})
		},
		{
			style: div_style_value = Object.entries(/*internalStyles*/ ctx[27]).map(func_1).concat([/*style*/ ctx[10]]).join(' ')
		},
		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.exclude)(/*$$restProps*/ ctx[42], ['input$', 'label$', 'ripple$', 'outline$', 'helperLine$'])
	];

	let div_data = {};

	for (let i = 0; i < div_levels.length; i += 1) {
		div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(div_data, div_levels[i]);
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (label_slot) label_slot.c();
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(contextfragment0.$$.fragment);
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (default_slot) default_slot.c();
			t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(contextfragment1.$$.fragment);
			t3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (ripple_slot) ripple_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (label_slot) {
				label_slot.m(div, null);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(contextfragment0, div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t1);

			if (default_slot) {
				default_slot.m(div, null);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(contextfragment1, div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t3);

			if (ripple_slot) {
				ripple_slot.m(div, null);
			}

			/*div_binding*/ ctx[79](div);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(Ripple_action = _smui_ripple__WEBPACK_IMPORTED_MODULE_4__["default"].call(null, div, {
						ripple: /*ripple*/ ctx[11],
						unbounded: false,
						addClass: /*addClass*/ ctx[38],
						removeClass: /*removeClass*/ ctx[39],
						addStyle: /*addStyle*/ ctx[40]
					})),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.useActions.call(null, div, /*use*/ ctx[8])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[35].call(null, div)),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "SMUI:textfield:leading-icon:mount", /*SMUI_textfield_leading_icon_mount_handler_1*/ ctx[80]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "SMUI:textfield:leading-icon:unmount", /*SMUI_textfield_leading_icon_unmount_handler_1*/ ctx[81]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "SMUI:textfield:trailing-icon:mount", /*SMUI_textfield_trailing_icon_mount_handler_1*/ ctx[82]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "SMUI:textfield:trailing-icon:unmount", /*SMUI_textfield_trailing_icon_unmount_handler_1*/ ctx[83])
				];

				mounted = true;
			}
		},
		p(ctx, dirty) {
			if (label_slot) {
				if (label_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						label_slot,
						label_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(label_slot_template, /*$$scope*/ ctx[89], dirty, get_label_slot_changes_2),
						get_label_slot_context_2
					);
				}
			}

			const contextfragment0_changes = {};

			if (dirty[2] & /*$$scope*/ 134217728) {
				contextfragment0_changes.$$scope = { dirty, ctx };
			}

			contextfragment0.$set(contextfragment0_changes);

			if (default_slot) {
				if (default_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[89], dirty, null),
						null
					);
				}
			}

			const contextfragment1_changes = {};

			if (dirty[2] & /*$$scope*/ 134217728) {
				contextfragment1_changes.$$scope = { dirty, ctx };
			}

			contextfragment1.$set(contextfragment1_changes);

			if (ripple_slot) {
				if (ripple_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						ripple_slot,
						ripple_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(ripple_slot_template, /*$$scope*/ ctx[89], dirty, get_ripple_slot_changes),
						get_ripple_slot_context
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(div_levels, [
				(!current || dirty[0] & /*className, disabled, textarea, variant, noLabel, invalid, internalClasses*/ 67228164 | dirty[1] & /*$$slots*/ 1024 && div_class_value !== (div_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
					[/*className*/ ctx[9]]: true,
					'mdc-text-field': true,
					'mdc-text-field--disabled': /*disabled*/ ctx[12],
					'mdc-text-field--textarea': /*textarea*/ ctx[14],
					'mdc-text-field--filled': /*variant*/ ctx[15] === 'filled',
					'mdc-text-field--outlined': /*variant*/ ctx[15] === 'outlined',
					'smui-text-field--standard': /*variant*/ ctx[15] === 'standard' && !/*textarea*/ ctx[14],
					'mdc-text-field--no-label': /*noLabel*/ ctx[16] || !/*$$slots*/ ctx[41].label,
					'mdc-text-field--with-leading-icon': /*$$slots*/ ctx[41].leadingIcon,
					'mdc-text-field--with-trailing-icon': /*$$slots*/ ctx[41].trailingIcon,
					'mdc-text-field--invalid': /*invalid*/ ctx[2] !== /*uninitializedValue*/ ctx[36] && /*invalid*/ ctx[2],
					.../*internalClasses*/ ctx[26]
				}))) && { class: div_class_value },
				(!current || dirty[0] & /*internalStyles, style*/ 134218752 && div_style_value !== (div_style_value = Object.entries(/*internalStyles*/ ctx[27]).map(func_1).concat([/*style*/ ctx[10]]).join(' '))) && { style: div_style_value },
				dirty[1] & /*$$restProps*/ 2048 && (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.exclude)(/*$$restProps*/ ctx[42], ['input$', 'label$', 'ripple$', 'outline$', 'helperLine$'])
			]));

			if (Ripple_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(Ripple_action.update) && dirty[0] & /*ripple*/ 2048) Ripple_action.update.call(null, {
				ripple: /*ripple*/ ctx[11],
				unbounded: false,
				addClass: /*addClass*/ ctx[38],
				removeClass: /*removeClass*/ ctx[39],
				addStyle: /*addStyle*/ ctx[40]
			});

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty[0] & /*use*/ 256) useActions_action.update.call(null, /*use*/ ctx[8]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(contextfragment0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(contextfragment1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(ripple_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(contextfragment0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(contextfragment1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(ripple_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (label_slot) label_slot.d(detaching);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(contextfragment0);
			if (default_slot) default_slot.d(detaching);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(contextfragment1);
			if (ripple_slot) ripple_slot.d(detaching);
			/*div_binding*/ ctx[79](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

// (1:0) {#if valued}
function create_if_block_1(ctx) {
	let label_1;
	let t0;
	let t1;
	let contextfragment0;
	let t2;
	let t3;
	let current_block_type_index;
	let if_block2;
	let t4;
	let contextfragment1;
	let t5;
	let label_1_class_value;
	let label_1_style_value;
	let label_1_for_value;
	let Ripple_action;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	let if_block0 = !/*textarea*/ ctx[14] && /*variant*/ ctx[15] !== 'outlined' && create_if_block_8(ctx);
	let if_block1 = (/*textarea*/ ctx[14] || /*variant*/ ctx[15] === 'outlined') && create_if_block_6(ctx);

	contextfragment0 = new _smui_common_ContextFragment_svelte__WEBPACK_IMPORTED_MODULE_3__["default"]({
			props: {
				key: "SMUI:textfield:icon:leading",
				value: true,
				$$slots: { default: [create_default_slot_4] },
				$$scope: { ctx }
			}
		});

	const default_slot_template = /*#slots*/ ctx[50].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[89], null);
	const if_block_creators = [create_if_block_3, create_else_block];
	const if_blocks = [];

	function select_block_type_1(ctx, dirty) {
		if (/*textarea*/ ctx[14]) return 0;
		return 1;
	}

	current_block_type_index = select_block_type_1(ctx, [-1, -1, -1, -1]);
	if_block2 = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);

	contextfragment1 = new _smui_common_ContextFragment_svelte__WEBPACK_IMPORTED_MODULE_3__["default"]({
			props: {
				key: "SMUI:textfield:icon:leading",
				value: false,
				$$slots: { default: [create_default_slot_1] },
				$$scope: { ctx }
			}
		});

	let if_block3 = !/*textarea*/ ctx[14] && /*variant*/ ctx[15] !== 'outlined' && /*ripple*/ ctx[11] && create_if_block_2(ctx);

	let label_1_levels = [
		{
			class: label_1_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[9]]: true,
				'mdc-text-field': true,
				'mdc-text-field--disabled': /*disabled*/ ctx[12],
				'mdc-text-field--textarea': /*textarea*/ ctx[14],
				'mdc-text-field--filled': /*variant*/ ctx[15] === 'filled',
				'mdc-text-field--outlined': /*variant*/ ctx[15] === 'outlined',
				'smui-text-field--standard': /*variant*/ ctx[15] === 'standard' && !/*textarea*/ ctx[14],
				'mdc-text-field--no-label': /*noLabel*/ ctx[16] || /*label*/ ctx[17] == null && !/*$$slots*/ ctx[41].label,
				'mdc-text-field--label-floating': /*focused*/ ctx[29] || /*value*/ ctx[0] != null && /*value*/ ctx[0] !== '',
				'mdc-text-field--with-leading-icon': /*withLeadingIcon*/ ctx[22] === /*uninitializedValue*/ ctx[36]
				? /*$$slots*/ ctx[41].leadingIcon
				: /*withLeadingIcon*/ ctx[22],
				'mdc-text-field--with-trailing-icon': /*withTrailingIcon*/ ctx[23] === /*uninitializedValue*/ ctx[36]
				? /*$$slots*/ ctx[41].trailingIcon
				: /*withTrailingIcon*/ ctx[23],
				'mdc-text-field--with-internal-counter': /*textarea*/ ctx[14] && /*$$slots*/ ctx[41].internalCounter,
				'mdc-text-field--invalid': /*invalid*/ ctx[2] !== /*uninitializedValue*/ ctx[36] && /*invalid*/ ctx[2],
				.../*internalClasses*/ ctx[26]
			})
		},
		{
			style: label_1_style_value = Object.entries(/*internalStyles*/ ctx[27]).map(func).concat([/*style*/ ctx[10]]).join(' ')
		},
		{
			for: label_1_for_value = /* suppress a11y warning, since this is wrapped */ null
		},
		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.exclude)(/*$$restProps*/ ctx[42], ['input$', 'label$', 'ripple$', 'outline$', 'helperLine$'])
	];

	let label_1_data = {};

	for (let i = 0; i < label_1_levels.length; i += 1) {
		label_1_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(label_1_data, label_1_levels[i]);
	}

	return {
		c() {
			label_1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("label");
			if (if_block0) if_block0.c();
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block1) if_block1.c();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(contextfragment0.$$.fragment);
			t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (default_slot) default_slot.c();
			t3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if_block2.c();
			t4 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(contextfragment1.$$.fragment);
			t5 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block3) if_block3.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(label_1, label_1_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, label_1, anchor);
			if (if_block0) if_block0.m(label_1, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(label_1, t0);
			if (if_block1) if_block1.m(label_1, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(label_1, t1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(contextfragment0, label_1, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(label_1, t2);

			if (default_slot) {
				default_slot.m(label_1, null);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(label_1, t3);
			if_blocks[current_block_type_index].m(label_1, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(label_1, t4);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(contextfragment1, label_1, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(label_1, t5);
			if (if_block3) if_block3.m(label_1, null);
			/*label_1_binding*/ ctx[72](label_1);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(Ripple_action = _smui_ripple__WEBPACK_IMPORTED_MODULE_4__["default"].call(null, label_1, {
						ripple: !/*textarea*/ ctx[14] && /*variant*/ ctx[15] === 'filled',
						unbounded: false,
						addClass: /*addClass*/ ctx[38],
						removeClass: /*removeClass*/ ctx[39],
						addStyle: /*addStyle*/ ctx[40],
						eventTarget: /*inputElement*/ ctx[34],
						activeTarget: /*inputElement*/ ctx[34],
						initPromise: /*initPromise*/ ctx[37]
					})),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.useActions.call(null, label_1, /*use*/ ctx[8])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[35].call(null, label_1)),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(label_1, "SMUI:textfield:leading-icon:mount", /*SMUI_textfield_leading_icon_mount_handler*/ ctx[73]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(label_1, "SMUI:textfield:leading-icon:unmount", /*SMUI_textfield_leading_icon_unmount_handler*/ ctx[74]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(label_1, "SMUI:textfield:trailing-icon:mount", /*SMUI_textfield_trailing_icon_mount_handler*/ ctx[75]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(label_1, "SMUI:textfield:trailing-icon:unmount", /*SMUI_textfield_trailing_icon_unmount_handler*/ ctx[76]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(label_1, "SMUI:textfield:character-counter:mount", /*SMUI_textfield_character_counter_mount_handler*/ ctx[77]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(label_1, "SMUI:textfield:character-counter:unmount", /*SMUI_textfield_character_counter_unmount_handler*/ ctx[78])
				];

				mounted = true;
			}
		},
		p(ctx, dirty) {
			if (!/*textarea*/ ctx[14] && /*variant*/ ctx[15] !== 'outlined') {
				if (if_block0) {
					if_block0.p(ctx, dirty);

					if (dirty[0] & /*textarea, variant*/ 49152) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
					}
				} else {
					if_block0 = create_if_block_8(ctx);
					if_block0.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
					if_block0.m(label_1, t0);
				}
			} else if (if_block0) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0, 1, 1, () => {
					if_block0 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			if (/*textarea*/ ctx[14] || /*variant*/ ctx[15] === 'outlined') {
				if (if_block1) {
					if_block1.p(ctx, dirty);

					if (dirty[0] & /*textarea, variant*/ 49152) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					}
				} else {
					if_block1 = create_if_block_6(ctx);
					if_block1.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					if_block1.m(label_1, t1);
				}
			} else if (if_block1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1, 1, 1, () => {
					if_block1 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			const contextfragment0_changes = {};

			if (dirty[2] & /*$$scope*/ 134217728) {
				contextfragment0_changes.$$scope = { dirty, ctx };
			}

			contextfragment0.$set(contextfragment0_changes);

			if (default_slot) {
				if (default_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[89], dirty, null),
						null
					);
				}
			}

			let previous_block_index = current_block_type_index;
			current_block_type_index = select_block_type_1(ctx, dirty);

			if (current_block_type_index === previous_block_index) {
				if_blocks[current_block_type_index].p(ctx, dirty);
			} else {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
					if_blocks[previous_block_index] = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				if_block2 = if_blocks[current_block_type_index];

				if (!if_block2) {
					if_block2 = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
					if_block2.c();
				} else {
					if_block2.p(ctx, dirty);
				}

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block2, 1);
				if_block2.m(label_1, t4);
			}

			const contextfragment1_changes = {};

			if (dirty[2] & /*$$scope*/ 134217728) {
				contextfragment1_changes.$$scope = { dirty, ctx };
			}

			contextfragment1.$set(contextfragment1_changes);

			if (!/*textarea*/ ctx[14] && /*variant*/ ctx[15] !== 'outlined' && /*ripple*/ ctx[11]) {
				if (if_block3) {
					if_block3.p(ctx, dirty);

					if (dirty[0] & /*textarea, variant, ripple*/ 51200) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block3, 1);
					}
				} else {
					if_block3 = create_if_block_2(ctx);
					if_block3.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block3, 1);
					if_block3.m(label_1, null);
				}
			} else if (if_block3) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block3, 1, 1, () => {
					if_block3 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(label_1, label_1_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(label_1_levels, [
				(!current || dirty[0] & /*className, disabled, textarea, variant, noLabel, label, focused, value, withLeadingIcon, withTrailingIcon, invalid, internalClasses*/ 616813061 | dirty[1] & /*$$slots*/ 1024 && label_1_class_value !== (label_1_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
					[/*className*/ ctx[9]]: true,
					'mdc-text-field': true,
					'mdc-text-field--disabled': /*disabled*/ ctx[12],
					'mdc-text-field--textarea': /*textarea*/ ctx[14],
					'mdc-text-field--filled': /*variant*/ ctx[15] === 'filled',
					'mdc-text-field--outlined': /*variant*/ ctx[15] === 'outlined',
					'smui-text-field--standard': /*variant*/ ctx[15] === 'standard' && !/*textarea*/ ctx[14],
					'mdc-text-field--no-label': /*noLabel*/ ctx[16] || /*label*/ ctx[17] == null && !/*$$slots*/ ctx[41].label,
					'mdc-text-field--label-floating': /*focused*/ ctx[29] || /*value*/ ctx[0] != null && /*value*/ ctx[0] !== '',
					'mdc-text-field--with-leading-icon': /*withLeadingIcon*/ ctx[22] === /*uninitializedValue*/ ctx[36]
					? /*$$slots*/ ctx[41].leadingIcon
					: /*withLeadingIcon*/ ctx[22],
					'mdc-text-field--with-trailing-icon': /*withTrailingIcon*/ ctx[23] === /*uninitializedValue*/ ctx[36]
					? /*$$slots*/ ctx[41].trailingIcon
					: /*withTrailingIcon*/ ctx[23],
					'mdc-text-field--with-internal-counter': /*textarea*/ ctx[14] && /*$$slots*/ ctx[41].internalCounter,
					'mdc-text-field--invalid': /*invalid*/ ctx[2] !== /*uninitializedValue*/ ctx[36] && /*invalid*/ ctx[2],
					.../*internalClasses*/ ctx[26]
				}))) && { class: label_1_class_value },
				(!current || dirty[0] & /*internalStyles, style*/ 134218752 && label_1_style_value !== (label_1_style_value = Object.entries(/*internalStyles*/ ctx[27]).map(func).concat([/*style*/ ctx[10]]).join(' '))) && { style: label_1_style_value },
				{ for: label_1_for_value },
				dirty[1] & /*$$restProps*/ 2048 && (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.exclude)(/*$$restProps*/ ctx[42], ['input$', 'label$', 'ripple$', 'outline$', 'helperLine$'])
			]));

			if (Ripple_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(Ripple_action.update) && dirty[0] & /*textarea, variant*/ 49152 | dirty[1] & /*inputElement*/ 8) Ripple_action.update.call(null, {
				ripple: !/*textarea*/ ctx[14] && /*variant*/ ctx[15] === 'filled',
				unbounded: false,
				addClass: /*addClass*/ ctx[38],
				removeClass: /*removeClass*/ ctx[39],
				addStyle: /*addStyle*/ ctx[40],
				eventTarget: /*inputElement*/ ctx[34],
				activeTarget: /*inputElement*/ ctx[34],
				initPromise: /*initPromise*/ ctx[37]
			});

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty[0] & /*use*/ 256) useActions_action.update.call(null, /*use*/ ctx[8]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(contextfragment0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(contextfragment1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block3);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(contextfragment0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(contextfragment1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block3);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(label_1);
			if (if_block0) if_block0.d();
			if (if_block1) if_block1.d();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(contextfragment0);
			if (default_slot) default_slot.d(detaching);
			if_blocks[current_block_type_index].d();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(contextfragment1);
			if (if_block3) if_block3.d();
			/*label_1_binding*/ ctx[72](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

// (209:4) <ContextFragment key="SMUI:textfield:icon:leading" value={true}>
function create_default_slot_9(ctx) {
	let current;
	const leadingIcon_slot_template = /*#slots*/ ctx[50].leadingIcon;
	const leadingIcon_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(leadingIcon_slot_template, ctx, /*$$scope*/ ctx[89], get_leadingIcon_slot_context_1);

	return {
		c() {
			if (leadingIcon_slot) leadingIcon_slot.c();
		},
		m(target, anchor) {
			if (leadingIcon_slot) {
				leadingIcon_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (leadingIcon_slot) {
				if (leadingIcon_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						leadingIcon_slot,
						leadingIcon_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(leadingIcon_slot_template, /*$$scope*/ ctx[89], dirty, get_leadingIcon_slot_changes_1),
						get_leadingIcon_slot_context_1
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(leadingIcon_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(leadingIcon_slot, local);
			current = false;
		},
		d(detaching) {
			if (leadingIcon_slot) leadingIcon_slot.d(detaching);
		}
	};
}

// (213:4) <ContextFragment key="SMUI:textfield:icon:leading" value={false}>
function create_default_slot_8(ctx) {
	let current;
	const trailingIcon_slot_template = /*#slots*/ ctx[50].trailingIcon;
	const trailingIcon_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(trailingIcon_slot_template, ctx, /*$$scope*/ ctx[89], get_trailingIcon_slot_context_1);

	return {
		c() {
			if (trailingIcon_slot) trailingIcon_slot.c();
		},
		m(target, anchor) {
			if (trailingIcon_slot) {
				trailingIcon_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (trailingIcon_slot) {
				if (trailingIcon_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						trailingIcon_slot,
						trailingIcon_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(trailingIcon_slot_template, /*$$scope*/ ctx[89], dirty, get_trailingIcon_slot_changes_1),
						get_trailingIcon_slot_context_1
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(trailingIcon_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(trailingIcon_slot, local);
			current = false;
		},
		d(detaching) {
			if (trailingIcon_slot) trailingIcon_slot.d(detaching);
		}
	};
}

// (63:4) {#if !textarea && variant !== 'outlined'}
function create_if_block_8(ctx) {
	let t;
	let if_block1_anchor;
	let current;
	let if_block0 = /*variant*/ ctx[15] === 'filled' && create_if_block_10(ctx);
	let if_block1 = !/*noLabel*/ ctx[16] && (/*label*/ ctx[17] != null || /*$$slots*/ ctx[41].label) && create_if_block_9(ctx);

	return {
		c() {
			if (if_block0) if_block0.c();
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block1) if_block1.c();
			if_block1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (if_block0) if_block0.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
			if (if_block1) if_block1.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block1_anchor, anchor);
			current = true;
		},
		p(ctx, dirty) {
			if (/*variant*/ ctx[15] === 'filled') {
				if (if_block0) {
					
				} else {
					if_block0 = create_if_block_10(ctx);
					if_block0.c();
					if_block0.m(t.parentNode, t);
				}
			} else if (if_block0) {
				if_block0.d(1);
				if_block0 = null;
			}

			if (!/*noLabel*/ ctx[16] && (/*label*/ ctx[17] != null || /*$$slots*/ ctx[41].label)) {
				if (if_block1) {
					if_block1.p(ctx, dirty);

					if (dirty[0] & /*noLabel, label*/ 196608 | dirty[1] & /*$$slots*/ 1024) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					}
				} else {
					if_block1 = create_if_block_9(ctx);
					if_block1.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					if_block1.m(if_block1_anchor.parentNode, if_block1_anchor);
				}
			} else if (if_block1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1, 1, 1, () => {
					if_block1 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1);
			current = false;
		},
		d(detaching) {
			if (if_block0) if_block0.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			if (if_block1) if_block1.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block1_anchor);
		}
	};
}

// (64:6) {#if variant === 'filled'}
function create_if_block_10(ctx) {
	let span;

	return {
		c() {
			span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "mdc-text-field__ripple");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
		}
	};
}

// (67:6) {#if !noLabel && (label != null || $$slots.label)}
function create_if_block_9(ctx) {
	let floatinglabel;
	let current;

	const floatinglabel_spread_levels = [
		{
			floatAbove: /*focused*/ ctx[29] || /*value*/ ctx[0] != null && /*value*/ ctx[0] !== ''
		},
		{ required: /*required*/ ctx[13] },
		{ wrapped: true },
		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'label$')
	];

	let floatinglabel_props = {
		$$slots: { default: [create_default_slot_7] },
		$$scope: { ctx }
	};

	for (let i = 0; i < floatinglabel_spread_levels.length; i += 1) {
		floatinglabel_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(floatinglabel_props, floatinglabel_spread_levels[i]);
	}

	floatinglabel = new _smui_floating_label_FloatingLabel_svelte__WEBPACK_IMPORTED_MODULE_5__["default"]({ props: floatinglabel_props });
	/*floatinglabel_binding*/ ctx[51](floatinglabel);

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(floatinglabel.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(floatinglabel, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const floatinglabel_changes = (dirty[0] & /*focused, value, required*/ 536879105 | dirty[1] & /*$$restProps*/ 2048)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(floatinglabel_spread_levels, [
					dirty[0] & /*focused, value*/ 536870913 && {
						floatAbove: /*focused*/ ctx[29] || /*value*/ ctx[0] != null && /*value*/ ctx[0] !== ''
					},
					dirty[0] & /*required*/ 8192 && { required: /*required*/ ctx[13] },
					floatinglabel_spread_levels[2],
					dirty[1] & /*$$restProps*/ 2048 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'label$'))
				])
			: {};

			if (dirty[0] & /*label*/ 131072 | dirty[2] & /*$$scope*/ 134217728) {
				floatinglabel_changes.$$scope = { dirty, ctx };
			}

			floatinglabel.$set(floatinglabel_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(floatinglabel.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(floatinglabel.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			/*floatinglabel_binding*/ ctx[51](null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(floatinglabel, detaching);
		}
	};
}

// (68:8) <FloatingLabel           bind:this={floatingLabel}           floatAbove={focused || (value != null && value !== '')}           {required}           wrapped           {...prefixFilter($$restProps, 'label$')}           >
function create_default_slot_7(ctx) {
	let t_value = (/*label*/ ctx[17] == null ? '' : /*label*/ ctx[17]) + "";
	let t;
	let current;
	const label_slot_template = /*#slots*/ ctx[50].label;
	const label_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(label_slot_template, ctx, /*$$scope*/ ctx[89], get_label_slot_context);

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
			if (label_slot) label_slot.c();
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);

			if (label_slot) {
				label_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if ((!current || dirty[0] & /*label*/ 131072) && t_value !== (t_value = (/*label*/ ctx[17] == null ? '' : /*label*/ ctx[17]) + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);

			if (label_slot) {
				if (label_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						label_slot,
						label_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(label_slot_template, /*$$scope*/ ctx[89], dirty, get_label_slot_changes),
						get_label_slot_context
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			if (label_slot) label_slot.d(detaching);
		}
	};
}

// (78:4) {#if textarea || variant === 'outlined'}
function create_if_block_6(ctx) {
	let notchedoutline;
	let current;

	const notchedoutline_spread_levels = [
		{
			noLabel: /*noLabel*/ ctx[16] || /*label*/ ctx[17] == null && !/*$$slots*/ ctx[41].label
		},
		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'outline$')
	];

	let notchedoutline_props = {
		$$slots: { default: [create_default_slot_5] },
		$$scope: { ctx }
	};

	for (let i = 0; i < notchedoutline_spread_levels.length; i += 1) {
		notchedoutline_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(notchedoutline_props, notchedoutline_spread_levels[i]);
	}

	notchedoutline = new _smui_notched_outline_NotchedOutline_svelte__WEBPACK_IMPORTED_MODULE_7__["default"]({ props: notchedoutline_props });
	/*notchedoutline_binding*/ ctx[53](notchedoutline);

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(notchedoutline.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(notchedoutline, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const notchedoutline_changes = (dirty[0] & /*noLabel, label*/ 196608 | dirty[1] & /*$$slots, $$restProps*/ 3072)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(notchedoutline_spread_levels, [
					dirty[0] & /*noLabel, label*/ 196608 | dirty[1] & /*$$slots*/ 1024 && {
						noLabel: /*noLabel*/ ctx[16] || /*label*/ ctx[17] == null && !/*$$slots*/ ctx[41].label
					},
					dirty[1] & /*$$restProps*/ 2048 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'outline$'))
				])
			: {};

			if (dirty[0] & /*focused, value, required, floatingLabel, label, noLabel*/ 537075745 | dirty[1] & /*$$restProps, $$slots*/ 3072 | dirty[2] & /*$$scope*/ 134217728) {
				notchedoutline_changes.$$scope = { dirty, ctx };
			}

			notchedoutline.$set(notchedoutline_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(notchedoutline.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(notchedoutline.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			/*notchedoutline_binding*/ ctx[53](null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(notchedoutline, detaching);
		}
	};
}

// (84:8) {#if !noLabel && (label != null || $$slots.label)}
function create_if_block_7(ctx) {
	let floatinglabel;
	let current;

	const floatinglabel_spread_levels = [
		{
			floatAbove: /*focused*/ ctx[29] || /*value*/ ctx[0] != null && /*value*/ ctx[0] !== ''
		},
		{ required: /*required*/ ctx[13] },
		{ wrapped: true },
		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'label$')
	];

	let floatinglabel_props = {
		$$slots: { default: [create_default_slot_6] },
		$$scope: { ctx }
	};

	for (let i = 0; i < floatinglabel_spread_levels.length; i += 1) {
		floatinglabel_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(floatinglabel_props, floatinglabel_spread_levels[i]);
	}

	floatinglabel = new _smui_floating_label_FloatingLabel_svelte__WEBPACK_IMPORTED_MODULE_5__["default"]({ props: floatinglabel_props });
	/*floatinglabel_binding_1*/ ctx[52](floatinglabel);

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(floatinglabel.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(floatinglabel, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const floatinglabel_changes = (dirty[0] & /*focused, value, required*/ 536879105 | dirty[1] & /*$$restProps*/ 2048)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(floatinglabel_spread_levels, [
					dirty[0] & /*focused, value*/ 536870913 && {
						floatAbove: /*focused*/ ctx[29] || /*value*/ ctx[0] != null && /*value*/ ctx[0] !== ''
					},
					dirty[0] & /*required*/ 8192 && { required: /*required*/ ctx[13] },
					floatinglabel_spread_levels[2],
					dirty[1] & /*$$restProps*/ 2048 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'label$'))
				])
			: {};

			if (dirty[0] & /*label*/ 131072 | dirty[2] & /*$$scope*/ 134217728) {
				floatinglabel_changes.$$scope = { dirty, ctx };
			}

			floatinglabel.$set(floatinglabel_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(floatinglabel.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(floatinglabel.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			/*floatinglabel_binding_1*/ ctx[52](null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(floatinglabel, detaching);
		}
	};
}

// (85:10) <FloatingLabel             bind:this={floatingLabel}             floatAbove={focused || (value != null && value !== '')}             {required}             wrapped             {...prefixFilter($$restProps, 'label$')}             >
function create_default_slot_6(ctx) {
	let t_value = (/*label*/ ctx[17] == null ? '' : /*label*/ ctx[17]) + "";
	let t;
	let current;
	const label_slot_template = /*#slots*/ ctx[50].label;
	const label_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(label_slot_template, ctx, /*$$scope*/ ctx[89], get_label_slot_context_1);

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
			if (label_slot) label_slot.c();
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);

			if (label_slot) {
				label_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if ((!current || dirty[0] & /*label*/ 131072) && t_value !== (t_value = (/*label*/ ctx[17] == null ? '' : /*label*/ ctx[17]) + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);

			if (label_slot) {
				if (label_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						label_slot,
						label_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(label_slot_template, /*$$scope*/ ctx[89], dirty, get_label_slot_changes_1),
						get_label_slot_context_1
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			if (label_slot) label_slot.d(detaching);
		}
	};
}

// (79:6) <NotchedOutline         bind:this={notchedOutline}         noLabel={noLabel || (label == null && !$$slots.label)}         {...prefixFilter($$restProps, 'outline$')}       >
function create_default_slot_5(ctx) {
	let if_block_anchor;
	let current;
	let if_block = !/*noLabel*/ ctx[16] && (/*label*/ ctx[17] != null || /*$$slots*/ ctx[41].label) && create_if_block_7(ctx);

	return {
		c() {
			if (if_block) if_block.c();
			if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (if_block) if_block.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
			current = true;
		},
		p(ctx, dirty) {
			if (!/*noLabel*/ ctx[16] && (/*label*/ ctx[17] != null || /*$$slots*/ ctx[41].label)) {
				if (if_block) {
					if_block.p(ctx, dirty);

					if (dirty[0] & /*noLabel, label*/ 196608 | dirty[1] & /*$$slots*/ 1024) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
					}
				} else {
					if_block = create_if_block_7(ctx);
					if_block.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
					if_block.m(if_block_anchor.parentNode, if_block_anchor);
				}
			} else if (if_block) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block, 1, 1, () => {
					if_block = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
			current = false;
		},
		d(detaching) {
			if (if_block) if_block.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
		}
	};
}

// (96:4) <ContextFragment key="SMUI:textfield:icon:leading" value={true}>
function create_default_slot_4(ctx) {
	let current;
	const leadingIcon_slot_template = /*#slots*/ ctx[50].leadingIcon;
	const leadingIcon_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(leadingIcon_slot_template, ctx, /*$$scope*/ ctx[89], get_leadingIcon_slot_context);

	return {
		c() {
			if (leadingIcon_slot) leadingIcon_slot.c();
		},
		m(target, anchor) {
			if (leadingIcon_slot) {
				leadingIcon_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (leadingIcon_slot) {
				if (leadingIcon_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						leadingIcon_slot,
						leadingIcon_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(leadingIcon_slot_template, /*$$scope*/ ctx[89], dirty, get_leadingIcon_slot_changes),
						get_leadingIcon_slot_context
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(leadingIcon_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(leadingIcon_slot, local);
			current = false;
		},
		d(detaching) {
			if (leadingIcon_slot) leadingIcon_slot.d(detaching);
		}
	};
}

// (125:4) {:else}
function create_else_block(ctx) {
	let t0;
	let t1;
	let input_1;
	let updating_value;
	let updating_files;
	let updating_dirty;
	let updating_invalid;
	let t2;
	let t3;
	let current;
	const prefix_slot_template = /*#slots*/ ctx[50].prefix;
	const prefix_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(prefix_slot_template, ctx, /*$$scope*/ ctx[89], get_prefix_slot_context);
	let if_block0 = /*prefix*/ ctx[19] != null && create_if_block_5(ctx);

	const input_1_spread_levels = [
		{ type: /*type*/ ctx[18] },
		{ disabled: /*disabled*/ ctx[12] },
		{ required: /*required*/ ctx[13] },
		{ updateInvalid: /*updateInvalid*/ ctx[21] },
		{ "aria-controls": /*helperId*/ ctx[28] },
		{ "aria-describedby": /*helperId*/ ctx[28] },
		/*noLabel*/ ctx[16] && /*label*/ ctx[17] != null
		? { placeholder: /*label*/ ctx[17] }
		: {},
		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'input$')
	];

	function input_1_value_binding(value) {
		/*input_1_value_binding*/ ctx[63](value);
	}

	function input_1_files_binding(value) {
		/*input_1_files_binding*/ ctx[64](value);
	}

	function input_1_dirty_binding(value) {
		/*input_1_dirty_binding*/ ctx[65](value);
	}

	function input_1_invalid_binding(value) {
		/*input_1_invalid_binding*/ ctx[66](value);
	}

	let input_1_props = {};

	for (let i = 0; i < input_1_spread_levels.length; i += 1) {
		input_1_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(input_1_props, input_1_spread_levels[i]);
	}

	if (/*value*/ ctx[0] !== void 0) {
		input_1_props.value = /*value*/ ctx[0];
	}

	if (/*files*/ ctx[1] !== void 0) {
		input_1_props.files = /*files*/ ctx[1];
	}

	if (/*dirty*/ ctx[4] !== void 0) {
		input_1_props.dirty = /*dirty*/ ctx[4];
	}

	if (/*invalid*/ ctx[2] !== void 0) {
		input_1_props.invalid = /*invalid*/ ctx[2];
	}

	input_1 = new _Input_svelte__WEBPACK_IMPORTED_MODULE_11__["default"]({ props: input_1_props });
	/*input_1_binding*/ ctx[62](input_1);
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(input_1, 'value', input_1_value_binding));
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(input_1, 'files', input_1_files_binding));
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(input_1, 'dirty', input_1_dirty_binding));
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(input_1, 'invalid', input_1_invalid_binding));
	input_1.$on("blur", /*blur_handler_3*/ ctx[67]);
	input_1.$on("focus", /*focus_handler_3*/ ctx[68]);
	input_1.$on("blur", /*blur_handler_1*/ ctx[69]);
	input_1.$on("focus", /*focus_handler_1*/ ctx[70]);
	let if_block1 = /*suffix*/ ctx[20] != null && create_if_block_4(ctx);
	const suffix_slot_template = /*#slots*/ ctx[50].suffix;
	const suffix_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(suffix_slot_template, ctx, /*$$scope*/ ctx[89], get_suffix_slot_context);

	return {
		c() {
			if (prefix_slot) prefix_slot.c();
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block0) if_block0.c();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(input_1.$$.fragment);
			t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block1) if_block1.c();
			t3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (suffix_slot) suffix_slot.c();
		},
		m(target, anchor) {
			if (prefix_slot) {
				prefix_slot.m(target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t0, anchor);
			if (if_block0) if_block0.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t1, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(input_1, target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t2, anchor);
			if (if_block1) if_block1.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t3, anchor);

			if (suffix_slot) {
				suffix_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (prefix_slot) {
				if (prefix_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						prefix_slot,
						prefix_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(prefix_slot_template, /*$$scope*/ ctx[89], dirty, get_prefix_slot_changes),
						get_prefix_slot_context
					);
				}
			}

			if (/*prefix*/ ctx[19] != null) {
				if (if_block0) {
					if_block0.p(ctx, dirty);

					if (dirty[0] & /*prefix*/ 524288) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
					}
				} else {
					if_block0 = create_if_block_5(ctx);
					if_block0.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
					if_block0.m(t1.parentNode, t1);
				}
			} else if (if_block0) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0, 1, 1, () => {
					if_block0 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			const input_1_changes = (dirty[0] & /*type, disabled, required, updateInvalid, helperId, noLabel, label*/ 271003648 | dirty[1] & /*$$restProps*/ 2048)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(input_1_spread_levels, [
					dirty[0] & /*type*/ 262144 && { type: /*type*/ ctx[18] },
					dirty[0] & /*disabled*/ 4096 && { disabled: /*disabled*/ ctx[12] },
					dirty[0] & /*required*/ 8192 && { required: /*required*/ ctx[13] },
					dirty[0] & /*updateInvalid*/ 2097152 && { updateInvalid: /*updateInvalid*/ ctx[21] },
					dirty[0] & /*helperId*/ 268435456 && { "aria-controls": /*helperId*/ ctx[28] },
					dirty[0] & /*helperId*/ 268435456 && { "aria-describedby": /*helperId*/ ctx[28] },
					dirty[0] & /*noLabel, label*/ 196608 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*noLabel*/ ctx[16] && /*label*/ ctx[17] != null
					? { placeholder: /*label*/ ctx[17] }
					: {}),
					dirty[1] & /*$$restProps*/ 2048 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'input$'))
				])
			: {};

			if (!updating_value && dirty[0] & /*value*/ 1) {
				updating_value = true;
				input_1_changes.value = /*value*/ ctx[0];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value = false);
			}

			if (!updating_files && dirty[0] & /*files*/ 2) {
				updating_files = true;
				input_1_changes.files = /*files*/ ctx[1];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_files = false);
			}

			if (!updating_dirty && dirty[0] & /*dirty*/ 16) {
				updating_dirty = true;
				input_1_changes.dirty = /*dirty*/ ctx[4];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_dirty = false);
			}

			if (!updating_invalid && dirty[0] & /*invalid*/ 4) {
				updating_invalid = true;
				input_1_changes.invalid = /*invalid*/ ctx[2];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_invalid = false);
			}

			input_1.$set(input_1_changes);

			if (/*suffix*/ ctx[20] != null) {
				if (if_block1) {
					if_block1.p(ctx, dirty);

					if (dirty[0] & /*suffix*/ 1048576) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					}
				} else {
					if_block1 = create_if_block_4(ctx);
					if_block1.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					if_block1.m(t3.parentNode, t3);
				}
			} else if (if_block1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1, 1, 1, () => {
					if_block1 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			if (suffix_slot) {
				if (suffix_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						suffix_slot,
						suffix_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(suffix_slot_template, /*$$scope*/ ctx[89], dirty, get_suffix_slot_changes),
						get_suffix_slot_context
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(prefix_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(input_1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(suffix_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(prefix_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(input_1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(suffix_slot, local);
			current = false;
		},
		d(detaching) {
			if (prefix_slot) prefix_slot.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t0);
			if (if_block0) if_block0.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t1);
			/*input_1_binding*/ ctx[62](null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(input_1, detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t2);
			if (if_block1) if_block1.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t3);
			if (suffix_slot) suffix_slot.d(detaching);
		}
	};
}

// (100:4) {#if textarea}
function create_if_block_3(ctx) {
	let span;
	let textarea_1;
	let updating_value;
	let updating_dirty;
	let updating_invalid;
	let t;
	let span_class_value;
	let current;

	const textarea_1_spread_levels = [
		{ disabled: /*disabled*/ ctx[12] },
		{ required: /*required*/ ctx[13] },
		{ updateInvalid: /*updateInvalid*/ ctx[21] },
		{ "aria-controls": /*helperId*/ ctx[28] },
		{ "aria-describedby": /*helperId*/ ctx[28] },
		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'input$')
	];

	function textarea_1_value_binding(value) {
		/*textarea_1_value_binding*/ ctx[55](value);
	}

	function textarea_1_dirty_binding(value) {
		/*textarea_1_dirty_binding*/ ctx[56](value);
	}

	function textarea_1_invalid_binding(value) {
		/*textarea_1_invalid_binding*/ ctx[57](value);
	}

	let textarea_1_props = {};

	for (let i = 0; i < textarea_1_spread_levels.length; i += 1) {
		textarea_1_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(textarea_1_props, textarea_1_spread_levels[i]);
	}

	if (/*value*/ ctx[0] !== void 0) {
		textarea_1_props.value = /*value*/ ctx[0];
	}

	if (/*dirty*/ ctx[4] !== void 0) {
		textarea_1_props.dirty = /*dirty*/ ctx[4];
	}

	if (/*invalid*/ ctx[2] !== void 0) {
		textarea_1_props.invalid = /*invalid*/ ctx[2];
	}

	textarea_1 = new _Textarea_svelte__WEBPACK_IMPORTED_MODULE_12__["default"]({ props: textarea_1_props });
	/*textarea_1_binding*/ ctx[54](textarea_1);
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(textarea_1, 'value', textarea_1_value_binding));
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(textarea_1, 'dirty', textarea_1_dirty_binding));
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(textarea_1, 'invalid', textarea_1_invalid_binding));
	textarea_1.$on("blur", /*blur_handler_2*/ ctx[58]);
	textarea_1.$on("focus", /*focus_handler_2*/ ctx[59]);
	textarea_1.$on("blur", /*blur_handler*/ ctx[60]);
	textarea_1.$on("focus", /*focus_handler*/ ctx[61]);
	const internalCounter_slot_template = /*#slots*/ ctx[50].internalCounter;
	const internalCounter_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(internalCounter_slot_template, ctx, /*$$scope*/ ctx[89], get_internalCounter_slot_context);

	return {
		c() {
			span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(textarea_1.$$.fragment);
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (internalCounter_slot) internalCounter_slot.c();

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				'mdc-text-field__resizer': !('input$resizable' in /*$$restProps*/ ctx[42]) || /*$$restProps*/ ctx[42].input$resizable
			}));
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(textarea_1, span, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(span, t);

			if (internalCounter_slot) {
				internalCounter_slot.m(span, null);
			}

			current = true;
		},
		p(ctx, dirty) {
			const textarea_1_changes = (dirty[0] & /*disabled, required, updateInvalid, helperId*/ 270544896 | dirty[1] & /*$$restProps*/ 2048)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(textarea_1_spread_levels, [
					dirty[0] & /*disabled*/ 4096 && { disabled: /*disabled*/ ctx[12] },
					dirty[0] & /*required*/ 8192 && { required: /*required*/ ctx[13] },
					dirty[0] & /*updateInvalid*/ 2097152 && { updateInvalid: /*updateInvalid*/ ctx[21] },
					dirty[0] & /*helperId*/ 268435456 && { "aria-controls": /*helperId*/ ctx[28] },
					dirty[0] & /*helperId*/ 268435456 && { "aria-describedby": /*helperId*/ ctx[28] },
					dirty[1] & /*$$restProps*/ 2048 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'input$'))
				])
			: {};

			if (!updating_value && dirty[0] & /*value*/ 1) {
				updating_value = true;
				textarea_1_changes.value = /*value*/ ctx[0];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value = false);
			}

			if (!updating_dirty && dirty[0] & /*dirty*/ 16) {
				updating_dirty = true;
				textarea_1_changes.dirty = /*dirty*/ ctx[4];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_dirty = false);
			}

			if (!updating_invalid && dirty[0] & /*invalid*/ 4) {
				updating_invalid = true;
				textarea_1_changes.invalid = /*invalid*/ ctx[2];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_invalid = false);
			}

			textarea_1.$set(textarea_1_changes);

			if (internalCounter_slot) {
				if (internalCounter_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						internalCounter_slot,
						internalCounter_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(internalCounter_slot_template, /*$$scope*/ ctx[89], dirty, get_internalCounter_slot_changes),
						get_internalCounter_slot_context
					);
				}
			}

			if (!current || dirty[1] & /*$$restProps*/ 2048 && span_class_value !== (span_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				'mdc-text-field__resizer': !('input$resizable' in /*$$restProps*/ ctx[42]) || /*$$restProps*/ ctx[42].input$resizable
			}))) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", span_class_value);
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(textarea_1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(internalCounter_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(textarea_1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(internalCounter_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
			/*textarea_1_binding*/ ctx[54](null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(textarea_1);
			if (internalCounter_slot) internalCounter_slot.d(detaching);
		}
	};
}

// (127:6) {#if prefix != null}
function create_if_block_5(ctx) {
	let prefix_1;
	let current;

	prefix_1 = new _Prefix_js__WEBPACK_IMPORTED_MODULE_9__["default"]({
			props: {
				$$slots: { default: [create_default_slot_3] },
				$$scope: { ctx }
			}
		});

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(prefix_1.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(prefix_1, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const prefix_1_changes = {};

			if (dirty[0] & /*prefix*/ 524288 | dirty[2] & /*$$scope*/ 134217728) {
				prefix_1_changes.$$scope = { dirty, ctx };
			}

			prefix_1.$set(prefix_1_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(prefix_1.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(prefix_1.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(prefix_1, detaching);
		}
	};
}

// (128:8) <Prefix>
function create_default_slot_3(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(/*prefix*/ ctx[19]);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*prefix*/ 524288) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, /*prefix*/ ctx[19]);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (149:6) {#if suffix != null}
function create_if_block_4(ctx) {
	let suffix_1;
	let current;

	suffix_1 = new _Suffix_js__WEBPACK_IMPORTED_MODULE_10__["default"]({
			props: {
				$$slots: { default: [create_default_slot_2] },
				$$scope: { ctx }
			}
		});

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(suffix_1.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(suffix_1, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const suffix_1_changes = {};

			if (dirty[0] & /*suffix*/ 1048576 | dirty[2] & /*$$scope*/ 134217728) {
				suffix_1_changes.$$scope = { dirty, ctx };
			}

			suffix_1.$set(suffix_1_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(suffix_1.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(suffix_1.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(suffix_1, detaching);
		}
	};
}

// (150:8) <Suffix>
function create_default_slot_2(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(/*suffix*/ ctx[20]);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*suffix*/ 1048576) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, /*suffix*/ ctx[20]);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (154:4) <ContextFragment key="SMUI:textfield:icon:leading" value={false}>
function create_default_slot_1(ctx) {
	let current;
	const trailingIcon_slot_template = /*#slots*/ ctx[50].trailingIcon;
	const trailingIcon_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(trailingIcon_slot_template, ctx, /*$$scope*/ ctx[89], get_trailingIcon_slot_context);

	return {
		c() {
			if (trailingIcon_slot) trailingIcon_slot.c();
		},
		m(target, anchor) {
			if (trailingIcon_slot) {
				trailingIcon_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (trailingIcon_slot) {
				if (trailingIcon_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						trailingIcon_slot,
						trailingIcon_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(trailingIcon_slot_template, /*$$scope*/ ctx[89], dirty, get_trailingIcon_slot_changes),
						get_trailingIcon_slot_context
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(trailingIcon_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(trailingIcon_slot, local);
			current = false;
		},
		d(detaching) {
			if (trailingIcon_slot) trailingIcon_slot.d(detaching);
		}
	};
}

// (157:4) {#if !textarea && variant !== 'outlined' && ripple}
function create_if_block_2(ctx) {
	let lineripple;
	let current;
	const lineripple_spread_levels = [(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'ripple$')];
	let lineripple_props = {};

	for (let i = 0; i < lineripple_spread_levels.length; i += 1) {
		lineripple_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(lineripple_props, lineripple_spread_levels[i]);
	}

	lineripple = new _smui_line_ripple_LineRipple_svelte__WEBPACK_IMPORTED_MODULE_6__["default"]({ props: lineripple_props });
	/*lineripple_binding*/ ctx[71](lineripple);

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(lineripple.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(lineripple, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const lineripple_changes = (dirty[1] & /*$$restProps*/ 2048)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(lineripple_spread_levels, [(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'ripple$'))])
			: {};

			lineripple.$set(lineripple_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(lineripple.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(lineripple.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			/*lineripple_binding*/ ctx[71](null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(lineripple, detaching);
		}
	};
}

// (219:0) {#if $$slots.helper}
function create_if_block(ctx) {
	let helperline;
	let current;
	const helperline_spread_levels = [(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'helperLine$')];

	let helperline_props = {
		$$slots: { default: [create_default_slot] },
		$$scope: { ctx }
	};

	for (let i = 0; i < helperline_spread_levels.length; i += 1) {
		helperline_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(helperline_props, helperline_spread_levels[i]);
	}

	helperline = new _HelperLine_js__WEBPACK_IMPORTED_MODULE_8__["default"]({ props: helperline_props });
	helperline.$on("SMUI:textfield:helper-text:id", /*SMUI_textfield_helper_text_id_handler*/ ctx[84]);
	helperline.$on("SMUI:textfield:helper-text:mount", /*SMUI_textfield_helper_text_mount_handler*/ ctx[85]);
	helperline.$on("SMUI:textfield:helper-text:unmount", /*SMUI_textfield_helper_text_unmount_handler*/ ctx[86]);
	helperline.$on("SMUI:textfield:character-counter:mount", /*SMUI_textfield_character_counter_mount_handler_1*/ ctx[87]);
	helperline.$on("SMUI:textfield:character-counter:unmount", /*SMUI_textfield_character_counter_unmount_handler_1*/ ctx[88]);

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(helperline.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(helperline, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const helperline_changes = (dirty[1] & /*$$restProps*/ 2048)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(helperline_spread_levels, [(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.prefixFilter)(/*$$restProps*/ ctx[42], 'helperLine$'))])
			: {};

			if (dirty[2] & /*$$scope*/ 134217728) {
				helperline_changes.$$scope = { dirty, ctx };
			}

			helperline.$set(helperline_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(helperline.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(helperline.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(helperline, detaching);
		}
	};
}

// (220:2) <HelperLine     on:SMUI:textfield:helper-text:id={(event) => (helperId = event.detail)}     on:SMUI:textfield:helper-text:mount={(event) => (helperText = event.detail)}     on:SMUI:textfield:helper-text:unmount={() => {       helperId = undefined;       helperText = undefined;     }}     on:SMUI:textfield:character-counter:mount={(event) =>       (characterCounter = event.detail)}     on:SMUI:textfield:character-counter:unmount={() =>       (characterCounter = undefined)}     {...prefixFilter($$restProps, 'helperLine$')}     >
function create_default_slot(ctx) {
	let current;
	const helper_slot_template = /*#slots*/ ctx[50].helper;
	const helper_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(helper_slot_template, ctx, /*$$scope*/ ctx[89], get_helper_slot_context);

	return {
		c() {
			if (helper_slot) helper_slot.c();
		},
		m(target, anchor) {
			if (helper_slot) {
				helper_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (helper_slot) {
				if (helper_slot.p && (!current || dirty[2] & /*$$scope*/ 134217728)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						helper_slot,
						helper_slot_template,
						ctx,
						/*$$scope*/ ctx[89],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[89])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(helper_slot_template, /*$$scope*/ ctx[89], dirty, get_helper_slot_changes),
						get_helper_slot_context
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(helper_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(helper_slot, local);
			current = false;
		},
		d(detaching) {
			if (helper_slot) helper_slot.d(detaching);
		}
	};
}

function create_fragment(ctx) {
	let current_block_type_index;
	let if_block0;
	let t;
	let if_block1_anchor;
	let current;
	const if_block_creators = [create_if_block_1, create_else_block_1];
	const if_blocks = [];

	function select_block_type(ctx, dirty) {
		if (/*valued*/ ctx[24]) return 0;
		return 1;
	}

	current_block_type_index = select_block_type(ctx, [-1, -1, -1, -1]);
	if_block0 = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
	let if_block1 = /*$$slots*/ ctx[41].helper && create_if_block(ctx);

	return {
		c() {
			if_block0.c();
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block1) if_block1.c();
			if_block1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if_blocks[current_block_type_index].m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
			if (if_block1) if_block1.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block1_anchor, anchor);
			current = true;
		},
		p(ctx, dirty) {
			let previous_block_index = current_block_type_index;
			current_block_type_index = select_block_type(ctx, dirty);

			if (current_block_type_index === previous_block_index) {
				if_blocks[current_block_type_index].p(ctx, dirty);
			} else {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
					if_blocks[previous_block_index] = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				if_block0 = if_blocks[current_block_type_index];

				if (!if_block0) {
					if_block0 = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
					if_block0.c();
				} else {
					if_block0.p(ctx, dirty);
				}

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
				if_block0.m(t.parentNode, t);
			}

			if (/*$$slots*/ ctx[41].helper) {
				if (if_block1) {
					if_block1.p(ctx, dirty);

					if (dirty[1] & /*$$slots*/ 1024) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					}
				} else {
					if_block1 = create_if_block(ctx);
					if_block1.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					if_block1.m(if_block1_anchor.parentNode, if_block1_anchor);
				}
			} else if (if_block1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1, 1, 1, () => {
					if_block1 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1);
			current = false;
		},
		d(detaching) {
			if_blocks[current_block_type_index].d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			if (if_block1) if_block1.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block1_anchor);
		}
	};
}

const func = ([name, value]) => `${name}: ${value};`;
const func_1 = ([name, value]) => `${name}: ${value};`;

function instance_1($$self, $$props, $$invalidate) {
	let valued;
	let inputElement;

	const omit_props_names = [
		"use","class","style","ripple","disabled","required","textarea","variant","noLabel","label","type","value","files","dirty","invalid","prefix","suffix","updateInvalid","validateOnValueChange","useNativeValidation","withLeadingIcon","withTrailingIcon","input","floatingLabel","lineRipple","notchedOutline","focus","layout","getElement"
	];

	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	const $$slots = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_slots)(slots);
	const { applyPassive } = _material_dom__WEBPACK_IMPORTED_MODULE_13__;
	const forwardEvents = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());

	let uninitializedValue = () => {
		
	};

	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let { style = '' } = $$props;
	let { ripple = true } = $$props;
	let { disabled = false } = $$props;
	let { required = false } = $$props;
	let { textarea = false } = $$props;
	let { variant = textarea ? 'outlined' : 'standard' } = $$props;
	let { noLabel = false } = $$props;
	let { label = null } = $$props;
	let { type = 'text' } = $$props;
	let { value = uninitializedValue } = $$props;
	let { files = uninitializedValue } = $$props;
	let { dirty = false } = $$props;
	let { invalid = uninitializedValue } = $$props;
	let { prefix = null } = $$props;
	let { suffix = null } = $$props;
	let { updateInvalid = invalid === uninitializedValue } = $$props;
	let { validateOnValueChange = updateInvalid } = $$props;
	let { useNativeValidation = updateInvalid } = $$props;
	let { withLeadingIcon = uninitializedValue } = $$props;
	let { withTrailingIcon = uninitializedValue } = $$props;
	let { input = undefined } = $$props;
	let { floatingLabel = undefined } = $$props;
	let { lineRipple = undefined } = $$props;
	let { notchedOutline = undefined } = $$props;
	let element;
	let instance;
	let internalClasses = {};
	let internalStyles = {};
	let helperId;
	let focused = false;
	let addLayoutListener = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.getContext)('SMUI:addLayoutListener');
	let removeLayoutListener;
	let initPromiseResolve;
	let initPromise = new Promise(resolve => initPromiseResolve = resolve);

	// These are instances, not accessors.
	let leadingIcon;

	let trailingIcon;
	let helperText;
	let characterCounter;

	// React to changes of value from outside component.
	let previousValue = value;

	if (addLayoutListener) {
		removeLayoutListener = addLayoutListener(layout);
	}

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		$$invalidate(48, instance = new _material_textfield__WEBPACK_IMPORTED_MODULE_14__.MDCTextFieldFoundation({
				// getRootAdapterMethods_
				addClass,
				removeClass,
				hasClass,
				registerTextFieldInteractionHandler: (evtType, handler) => getElement().addEventListener(evtType, handler),
				deregisterTextFieldInteractionHandler: (evtType, handler) => getElement().removeEventListener(evtType, handler),
				registerValidationAttributeChangeHandler: handler => {
					const getAttributesList = mutationsList => {
						return mutationsList.map(mutation => mutation.attributeName).filter(attributeName => attributeName);
					};

					const observer = new MutationObserver(mutationsList => {
							if (useNativeValidation) {
								handler(getAttributesList(mutationsList));
							}
						});

					const config = { attributes: true };
					observer.observe(input.getElement(), config);
					return observer;
				},
				deregisterValidationAttributeChangeHandler: observer => {
					observer.disconnect();
				},
				// getInputAdapterMethods_
				getNativeInput: () => input.getElement(),
				setInputAttr: (name, value) => {
					input.addAttr(name, value);
				},
				removeInputAttr: name => {
					input.removeAttr(name);
				},
				isFocused: () => document.activeElement === input.getElement(),
				registerInputInteractionHandler: (evtType, handler) => {
					input.getElement().addEventListener(evtType, handler, applyPassive());
				},
				deregisterInputInteractionHandler: (evtType, handler) => {
					input.getElement().removeEventListener(evtType, handler, applyPassive());
				},
				// getLabelAdapterMethods_
				floatLabel: shouldFloat => floatingLabel && floatingLabel.float(shouldFloat),
				getLabelWidth: () => floatingLabel ? floatingLabel.getWidth() : 0,
				hasLabel: () => !!floatingLabel,
				shakeLabel: shouldShake => floatingLabel && floatingLabel.shake(shouldShake),
				setLabelRequired: isRequired => floatingLabel && floatingLabel.setRequired(isRequired),
				// getLineRippleAdapterMethods_
				activateLineRipple: () => lineRipple && lineRipple.activate(),
				deactivateLineRipple: () => lineRipple && lineRipple.deactivate(),
				setLineRippleTransformOrigin: normalizedX => lineRipple && lineRipple.setRippleCenter(normalizedX),
				// getOutlineAdapterMethods_
				closeOutline: () => notchedOutline && notchedOutline.closeNotch(),
				hasOutline: () => !!notchedOutline,
				notchOutline: labelWidth => notchedOutline && notchedOutline.notch(labelWidth)
			},
		{
				get helperText() {
					return helperText;
				},
				get characterCounter() {
					return characterCounter;
				},
				get leadingIcon() {
					return leadingIcon;
				},
				get trailingIcon() {
					return trailingIcon;
				}
			}));

		if (valued) {
			instance.init();
		} else {
			(0,svelte__WEBPACK_IMPORTED_MODULE_1__.tick)().then(() => {
				instance.init();
			});
		}

		initPromiseResolve();

		return () => {
			instance.destroy();
		};
	});

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onDestroy)(() => {
		if (removeLayoutListener) {
			removeLayoutListener();
		}
	});

	function hasClass(className) {
		return className in internalClasses
		? internalClasses[className]
		: getElement().classList.contains(className);
	}

	function addClass(className) {
		if (!internalClasses[className]) {
			$$invalidate(26, internalClasses[className] = true, internalClasses);
		}
	}

	function removeClass(className) {
		if (!(className in internalClasses) || internalClasses[className]) {
			$$invalidate(26, internalClasses[className] = false, internalClasses);
		}
	}

	function addStyle(name, value) {
		if (internalStyles[name] != value) {
			if (value === '' || value == null) {
				delete internalStyles[name];
				$$invalidate(27, internalStyles);
			} else {
				$$invalidate(27, internalStyles[name] = value, internalStyles);
			}
		}
	}

	function focus() {
		input.focus();
	}

	function layout() {
		if (instance) {
			const openNotch = instance.shouldFloat;
			instance.notchOutline(openNotch);
		}
	}

	function getElement() {
		return element;
	}

	function floatinglabel_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			floatingLabel = $$value;
			$$invalidate(5, floatingLabel);
		});
	}

	function floatinglabel_binding_1($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			floatingLabel = $$value;
			$$invalidate(5, floatingLabel);
		});
	}

	function notchedoutline_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			notchedOutline = $$value;
			$$invalidate(7, notchedOutline);
		});
	}

	function textarea_1_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			input = $$value;
			$$invalidate(3, input);
		});
	}

	function textarea_1_value_binding(value$1) {
		value = value$1;
		$$invalidate(0, value);
	}

	function textarea_1_dirty_binding(value) {
		dirty = value;
		$$invalidate(4, dirty);
	}

	function textarea_1_invalid_binding(value) {
		invalid = value;
		(($$invalidate(2, invalid), $$invalidate(48, instance)), $$invalidate(21, updateInvalid));
	}

	const blur_handler_2 = () => $$invalidate(29, focused = false);
	const focus_handler_2 = () => $$invalidate(29, focused = true);

	function blur_handler(event) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bubble.call(this, $$self, event);
	}

	function focus_handler(event) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bubble.call(this, $$self, event);
	}

	function input_1_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			input = $$value;
			$$invalidate(3, input);
		});
	}

	function input_1_value_binding(value$1) {
		value = value$1;
		$$invalidate(0, value);
	}

	function input_1_files_binding(value) {
		files = value;
		$$invalidate(1, files);
	}

	function input_1_dirty_binding(value) {
		dirty = value;
		$$invalidate(4, dirty);
	}

	function input_1_invalid_binding(value) {
		invalid = value;
		(($$invalidate(2, invalid), $$invalidate(48, instance)), $$invalidate(21, updateInvalid));
	}

	const blur_handler_3 = () => $$invalidate(29, focused = false);
	const focus_handler_3 = () => $$invalidate(29, focused = true);

	function blur_handler_1(event) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bubble.call(this, $$self, event);
	}

	function focus_handler_1(event) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bubble.call(this, $$self, event);
	}

	function lineripple_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			lineRipple = $$value;
			$$invalidate(6, lineRipple);
		});
	}

	function label_1_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(25, element);
		});
	}

	const SMUI_textfield_leading_icon_mount_handler = event => $$invalidate(30, leadingIcon = event.detail);
	const SMUI_textfield_leading_icon_unmount_handler = () => $$invalidate(30, leadingIcon = undefined);
	const SMUI_textfield_trailing_icon_mount_handler = event => $$invalidate(31, trailingIcon = event.detail);
	const SMUI_textfield_trailing_icon_unmount_handler = () => $$invalidate(31, trailingIcon = undefined);
	const SMUI_textfield_character_counter_mount_handler = event => $$invalidate(33, characterCounter = event.detail);
	const SMUI_textfield_character_counter_unmount_handler = () => $$invalidate(33, characterCounter = undefined);

	function div_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(25, element);
		});
	}

	const SMUI_textfield_leading_icon_mount_handler_1 = event => $$invalidate(30, leadingIcon = event.detail);
	const SMUI_textfield_leading_icon_unmount_handler_1 = () => $$invalidate(30, leadingIcon = undefined);
	const SMUI_textfield_trailing_icon_mount_handler_1 = event => $$invalidate(31, trailingIcon = event.detail);
	const SMUI_textfield_trailing_icon_unmount_handler_1 = () => $$invalidate(31, trailingIcon = undefined);
	const SMUI_textfield_helper_text_id_handler = event => $$invalidate(28, helperId = event.detail);
	const SMUI_textfield_helper_text_mount_handler = event => $$invalidate(32, helperText = event.detail);

	const SMUI_textfield_helper_text_unmount_handler = () => {
		$$invalidate(28, helperId = undefined);
		$$invalidate(32, helperText = undefined);
	};

	const SMUI_textfield_character_counter_mount_handler_1 = event => $$invalidate(33, characterCounter = event.detail);
	const SMUI_textfield_character_counter_unmount_handler_1 = () => $$invalidate(33, characterCounter = undefined);

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(42, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(8, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(9, className = $$new_props.class);
		if ('style' in $$new_props) $$invalidate(10, style = $$new_props.style);
		if ('ripple' in $$new_props) $$invalidate(11, ripple = $$new_props.ripple);
		if ('disabled' in $$new_props) $$invalidate(12, disabled = $$new_props.disabled);
		if ('required' in $$new_props) $$invalidate(13, required = $$new_props.required);
		if ('textarea' in $$new_props) $$invalidate(14, textarea = $$new_props.textarea);
		if ('variant' in $$new_props) $$invalidate(15, variant = $$new_props.variant);
		if ('noLabel' in $$new_props) $$invalidate(16, noLabel = $$new_props.noLabel);
		if ('label' in $$new_props) $$invalidate(17, label = $$new_props.label);
		if ('type' in $$new_props) $$invalidate(18, type = $$new_props.type);
		if ('value' in $$new_props) $$invalidate(0, value = $$new_props.value);
		if ('files' in $$new_props) $$invalidate(1, files = $$new_props.files);
		if ('dirty' in $$new_props) $$invalidate(4, dirty = $$new_props.dirty);
		if ('invalid' in $$new_props) $$invalidate(2, invalid = $$new_props.invalid);
		if ('prefix' in $$new_props) $$invalidate(19, prefix = $$new_props.prefix);
		if ('suffix' in $$new_props) $$invalidate(20, suffix = $$new_props.suffix);
		if ('updateInvalid' in $$new_props) $$invalidate(21, updateInvalid = $$new_props.updateInvalid);
		if ('validateOnValueChange' in $$new_props) $$invalidate(43, validateOnValueChange = $$new_props.validateOnValueChange);
		if ('useNativeValidation' in $$new_props) $$invalidate(44, useNativeValidation = $$new_props.useNativeValidation);
		if ('withLeadingIcon' in $$new_props) $$invalidate(22, withLeadingIcon = $$new_props.withLeadingIcon);
		if ('withTrailingIcon' in $$new_props) $$invalidate(23, withTrailingIcon = $$new_props.withTrailingIcon);
		if ('input' in $$new_props) $$invalidate(3, input = $$new_props.input);
		if ('floatingLabel' in $$new_props) $$invalidate(5, floatingLabel = $$new_props.floatingLabel);
		if ('lineRipple' in $$new_props) $$invalidate(6, lineRipple = $$new_props.lineRipple);
		if ('notchedOutline' in $$new_props) $$invalidate(7, notchedOutline = $$new_props.notchedOutline);
		if ('$$scope' in $$new_props) $$invalidate(89, $$scope = $$new_props.$$scope);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty[0] & /*value, files*/ 3) {
			$: $$invalidate(24, valued = value !== uninitializedValue || files !== uninitializedValue);
		}

		if ($$self.$$.dirty[0] & /*input*/ 8) {
			$: $$invalidate(34, inputElement = input && input.getElement());
		}

		if ($$self.$$.dirty[0] & /*invalid, updateInvalid*/ 2097156 | $$self.$$.dirty[1] & /*instance*/ 131072) {
			$: if (instance && instance.isValid() !== !invalid) {
				if (updateInvalid) {
					$$invalidate(2, invalid = !instance.isValid());
				} else {
					instance.setValid(!invalid);
				}
			}
		}

		if ($$self.$$.dirty[1] & /*instance, validateOnValueChange*/ 135168) {
			$: if (instance && instance.getValidateOnValueChange() !== validateOnValueChange) {
				instance.setValidateOnValueChange(validateOnValueChange === uninitializedValue
				? false
				: validateOnValueChange);
			}
		}

		if ($$self.$$.dirty[1] & /*instance, useNativeValidation*/ 139264) {
			$: if (instance) {
				instance.setUseNativeValidation(useNativeValidation);
			}
		}

		if ($$self.$$.dirty[0] & /*disabled*/ 4096 | $$self.$$.dirty[1] & /*instance*/ 131072) {
			$: if (instance) {
				instance.setDisabled(disabled);
			}
		}

		if ($$self.$$.dirty[0] & /*valued, value*/ 16777217 | $$self.$$.dirty[1] & /*instance, previousValue*/ 393216) {
			$: if (instance && valued && previousValue !== value) {
				$$invalidate(49, previousValue = value);

				// Check the data is flowing down.
				if (instance.getValue() !== value) {
					instance.setValue(value);
				}
			}
		}
	};

	return [
		value,
		files,
		invalid,
		input,
		dirty,
		floatingLabel,
		lineRipple,
		notchedOutline,
		use,
		className,
		style,
		ripple,
		disabled,
		required,
		textarea,
		variant,
		noLabel,
		label,
		type,
		prefix,
		suffix,
		updateInvalid,
		withLeadingIcon,
		withTrailingIcon,
		valued,
		element,
		internalClasses,
		internalStyles,
		helperId,
		focused,
		leadingIcon,
		trailingIcon,
		helperText,
		characterCounter,
		inputElement,
		forwardEvents,
		uninitializedValue,
		initPromise,
		addClass,
		removeClass,
		addStyle,
		$$slots,
		$$restProps,
		validateOnValueChange,
		useNativeValidation,
		focus,
		layout,
		getElement,
		instance,
		previousValue,
		slots,
		floatinglabel_binding,
		floatinglabel_binding_1,
		notchedoutline_binding,
		textarea_1_binding,
		textarea_1_value_binding,
		textarea_1_dirty_binding,
		textarea_1_invalid_binding,
		blur_handler_2,
		focus_handler_2,
		blur_handler,
		focus_handler,
		input_1_binding,
		input_1_value_binding,
		input_1_files_binding,
		input_1_dirty_binding,
		input_1_invalid_binding,
		blur_handler_3,
		focus_handler_3,
		blur_handler_1,
		focus_handler_1,
		lineripple_binding,
		label_1_binding,
		SMUI_textfield_leading_icon_mount_handler,
		SMUI_textfield_leading_icon_unmount_handler,
		SMUI_textfield_trailing_icon_mount_handler,
		SMUI_textfield_trailing_icon_unmount_handler,
		SMUI_textfield_character_counter_mount_handler,
		SMUI_textfield_character_counter_unmount_handler,
		div_binding,
		SMUI_textfield_leading_icon_mount_handler_1,
		SMUI_textfield_leading_icon_unmount_handler_1,
		SMUI_textfield_trailing_icon_mount_handler_1,
		SMUI_textfield_trailing_icon_unmount_handler_1,
		SMUI_textfield_helper_text_id_handler,
		SMUI_textfield_helper_text_mount_handler,
		SMUI_textfield_helper_text_unmount_handler,
		SMUI_textfield_character_counter_mount_handler_1,
		SMUI_textfield_character_counter_unmount_handler_1,
		$$scope
	];
}

class Textfield extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(
			this,
			options,
			instance_1,
			create_fragment,
			svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal,
			{
				use: 8,
				class: 9,
				style: 10,
				ripple: 11,
				disabled: 12,
				required: 13,
				textarea: 14,
				variant: 15,
				noLabel: 16,
				label: 17,
				type: 18,
				value: 0,
				files: 1,
				dirty: 4,
				invalid: 2,
				prefix: 19,
				suffix: 20,
				updateInvalid: 21,
				validateOnValueChange: 43,
				useNativeValidation: 44,
				withLeadingIcon: 22,
				withTrailingIcon: 23,
				input: 3,
				floatingLabel: 5,
				lineRipple: 6,
				notchedOutline: 7,
				focus: 45,
				layout: 46,
				getElement: 47
			},
			null,
			[-1, -1, -1, -1]
		);
	}

	get focus() {
		return this.$$.ctx[45];
	}

	get layout() {
		return this.$$.ctx[46];
	}

	get getElement() {
		return this.$$.ctx[47];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Textfield);



/***/ }),

/***/ "./node_modules/@smui/textfield/character-counter/CharacterCounter.svelte":
/*!********************************************************************************!*\
  !*** ./node_modules/@smui/textfield/character-counter/CharacterCounter.svelte ***!
  \********************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _material_textfield_character_counter__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @material/textfield/character-counter */ "./node_modules/@material/textfield/character-counter/foundation.js");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\@smui\textfield\character-counter\CharacterCounter.svelte generated by Svelte v3.48.0 */









function create_else_block(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(/*content*/ ctx[3]);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		p(ctx, dirty) {
			if (dirty & /*content*/ 8) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, /*content*/ ctx[3]);
		},
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (11:2) {#if content == null}
function create_if_block(ctx) {
	let current;
	const default_slot_template = /*#slots*/ ctx[8].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[7], null);

	return {
		c() {
			if (default_slot) default_slot.c();
		},
		m(target, anchor) {
			if (default_slot) {
				default_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 128)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[7],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[7])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[7], dirty, null),
						null
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (default_slot) default_slot.d(detaching);
		}
	};
}

function create_fragment(ctx) {
	let div;
	let current_block_type_index;
	let if_block;
	let div_class_value;
	let useActions_action;
	let forwardEvents_action;
	let current;
	let mounted;
	let dispose;
	const if_block_creators = [create_if_block, create_else_block];
	const if_blocks = [];

	function select_block_type(ctx, dirty) {
		if (/*content*/ ctx[3] == null) return 0;
		return 1;
	}

	current_block_type_index = select_block_type(ctx, -1);
	if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);

	let div_levels = [
		{
			class: div_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
				[/*className*/ ctx[1]]: true,
				'mdc-text-field-character-counter': true
			})
		},
		/*$$restProps*/ ctx[5]
	];

	let div_data = {};

	for (let i = 0; i < div_levels.length; i += 1) {
		div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(div_data, div_levels[i]);
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if_block.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			if_blocks[current_block_type_index].m(div, null);
			/*div_binding*/ ctx[9](div);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(useActions_action = _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.useActions.call(null, div, /*use*/ ctx[0])),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.action_destroyer)(forwardEvents_action = /*forwardEvents*/ ctx[4].call(null, div))
				];

				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			let previous_block_index = current_block_type_index;
			current_block_type_index = select_block_type(ctx, dirty);

			if (current_block_type_index === previous_block_index) {
				if_blocks[current_block_type_index].p(ctx, dirty);
			} else {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
					if_blocks[previous_block_index] = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				if_block = if_blocks[current_block_type_index];

				if (!if_block) {
					if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
					if_block.c();
				} else {
					if_block.p(ctx, dirty);
				}

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
				if_block.m(div, null);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(div_levels, [
				(!current || dirty & /*className*/ 2 && div_class_value !== (div_class_value = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.classMap)({
					[/*className*/ ctx[1]]: true,
					'mdc-text-field-character-counter': true
				}))) && { class: div_class_value },
				dirty & /*$$restProps*/ 32 && /*$$restProps*/ ctx[5]
			]));

			if (useActions_action && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(useActions_action.update) && dirty & /*use*/ 1) useActions_action.update.call(null, /*use*/ ctx[0]);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if_blocks[current_block_type_index].d();
			/*div_binding*/ ctx[9](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance_1($$self, $$props, $$invalidate) {
	const omit_props_names = ["use","class","getElement"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	const forwardEvents = (0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.forwardEventsBuilder)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_current_component)());
	let { use = [] } = $$props;
	let { class: className = '' } = $$props;
	let element;
	let instance;
	let content = null;

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		instance = new _material_textfield_character_counter__WEBPACK_IMPORTED_MODULE_3__.MDCTextFieldCharacterCounterFoundation({
				setContent: value => {
					$$invalidate(3, content = value);
				}
			});

		(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.dispatch)(getElement(), 'SMUI:textfield:character-counter:mount', instance);
		instance.init();

		return () => {
			(0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_2__.dispatch)(getElement(), 'SMUI:textfield:character-counter:unmount', instance);
			instance.destroy();
		};
	});

	function getElement() {
		return element;
	}

	function div_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			element = $$value;
			$$invalidate(2, element);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(5, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('use' in $$new_props) $$invalidate(0, use = $$new_props.use);
		if ('class' in $$new_props) $$invalidate(1, className = $$new_props.class);
		if ('$$scope' in $$new_props) $$invalidate(7, $$scope = $$new_props.$$scope);
	};

	return [
		use,
		className,
		element,
		content,
		forwardEvents,
		$$restProps,
		getElement,
		$$scope,
		slots,
		div_binding
	];
}

class CharacterCounter extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance_1, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { use: 0, class: 1, getElement: 6 });
	}

	get getElement() {
		return this.$$.ctx[6];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CharacterCounter);



/***/ }),

/***/ "./node_modules/svelte-select/src/ClearIcon.svelte":
/*!*********************************************************!*\
  !*** ./node_modules/svelte-select/src/ClearIcon.svelte ***!
  \*********************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\svelte-select\src\ClearIcon.svelte generated by Svelte v3.48.0 */


function create_fragment(ctx) {
	let svg;
	let path;

	return {
		c() {
			svg = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.svg_element)("svg");
			path = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.svg_element)("path");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(path, "fill", "currentColor");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(path, "d", "M34.923,37.251L24,26.328L13.077,37.251L9.436,33.61l10.923-10.923L9.436,11.765l3.641-3.641L24,19.047L34.923,8.124\n    l3.641,3.641L27.641,22.688L38.564,33.61L34.923,37.251z");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "width", "100%");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "height", "100%");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "viewBox", "-2 -2 50 50");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "focusable", "false");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "role", "presentation");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, svg, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(svg, path);
		},
		p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(svg);
		}
	};
}

class ClearIcon extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, null, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {});
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ClearIcon);



/***/ }),

/***/ "./node_modules/svelte-select/src/Item.svelte":
/*!****************************************************!*\
  !*** ./node_modules/svelte-select/src/Item.svelte ***!
  \****************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_select_src_Item_svelte_27_css_svelte_loader_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Item_svelte_27_css_D_www_sipro_spa_node_modules_svelte_select_src_Item_svelte__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-select/src/Item.svelte.27.css!=!svelte-loader?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Item.svelte.27.css!./node_modules/svelte-select/src/Item.svelte */ "./node_modules/svelte-select/src/Item.svelte.27.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Item.svelte.27.css!./node_modules/svelte-select/src/Item.svelte");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\svelte-select\src\Item.svelte generated by Svelte v3.48.0 */


function create_fragment(ctx) {
	let div;
	let raw_value = /*getOptionLabel*/ ctx[0](/*item*/ ctx[1], /*filterText*/ ctx[2]) + "";
	let div_class_value;

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", div_class_value = "item " + /*itemClasses*/ ctx[3] + " svelte-bdnybl");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			div.innerHTML = raw_value;
		},
		p(ctx, [dirty]) {
			if (dirty & /*getOptionLabel, item, filterText*/ 7 && raw_value !== (raw_value = /*getOptionLabel*/ ctx[0](/*item*/ ctx[1], /*filterText*/ ctx[2]) + "")) div.innerHTML = raw_value;;

			if (dirty & /*itemClasses*/ 8 && div_class_value !== (div_class_value = "item " + /*itemClasses*/ ctx[3] + " svelte-bdnybl")) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", div_class_value);
			}
		},
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let { isActive = false } = $$props;
	let { isFirst = false } = $$props;
	let { isHover = false } = $$props;
	let { getOptionLabel = undefined } = $$props;
	let { item = undefined } = $$props;
	let { filterText = '' } = $$props;
	let itemClasses = '';

	$$self.$$set = $$props => {
		if ('isActive' in $$props) $$invalidate(4, isActive = $$props.isActive);
		if ('isFirst' in $$props) $$invalidate(5, isFirst = $$props.isFirst);
		if ('isHover' in $$props) $$invalidate(6, isHover = $$props.isHover);
		if ('getOptionLabel' in $$props) $$invalidate(0, getOptionLabel = $$props.getOptionLabel);
		if ('item' in $$props) $$invalidate(1, item = $$props.item);
		if ('filterText' in $$props) $$invalidate(2, filterText = $$props.filterText);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty & /*isActive, isFirst, isHover, item*/ 114) {
			$: {
				const classes = [];

				if (isActive) {
					classes.push('active');
				}

				if (isFirst) {
					classes.push('first');
				}

				if (isHover) {
					classes.push('hover');
				}

				if (item.isGroupHeader) {
					classes.push('groupHeader');
				}

				if (item.isGroupItem) {
					classes.push('groupItem');
				}

				$$invalidate(3, itemClasses = classes.join(' '));
			}
		}
	};

	return [getOptionLabel, item, filterText, itemClasses, isActive, isFirst, isHover];
}

class Item extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			isActive: 4,
			isFirst: 5,
			isHover: 6,
			getOptionLabel: 0,
			item: 1,
			filterText: 2
		});
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Item);




/***/ }),

/***/ "./node_modules/svelte-select/src/List.svelte":
/*!****************************************************!*\
  !*** ./node_modules/svelte-select/src/List.svelte ***!
  \****************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _Item_svelte__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Item.svelte */ "./node_modules/svelte-select/src/Item.svelte");
/* harmony import */ var _VirtualList_svelte__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./VirtualList.svelte */ "./node_modules/svelte-select/src/VirtualList.svelte");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_select_src_List_svelte_28_css_svelte_loader_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_List_svelte_28_css_D_www_sipro_spa_node_modules_svelte_select_src_List_svelte__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./node_modules/svelte-select/src/List.svelte.28.css!=!svelte-loader?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/List.svelte.28.css!./node_modules/svelte-select/src/List.svelte */ "./node_modules/svelte-select/src/List.svelte.28.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/List.svelte.28.css!./node_modules/svelte-select/src/List.svelte");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\svelte-select\src\List.svelte generated by Svelte v3.48.0 */







function get_each_context(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[34] = list[i];
	child_ctx[36] = i;
	return child_ctx;
}

// (210:0) {#if isVirtualList}
function create_if_block_3(ctx) {
	let div;
	let virtuallist;
	let current;

	virtuallist = new _VirtualList_svelte__WEBPACK_IMPORTED_MODULE_3__["default"]({
			props: {
				items: /*items*/ ctx[4],
				itemHeight: /*itemHeight*/ ctx[7],
				$$slots: {
					default: [
						create_default_slot,
						({ item, i }) => ({ 34: item, 36: i }),
						({ item, i }) => [0, (item ? 8 : 0) | (i ? 32 : 0)]
					]
				},
				$$scope: { ctx }
			}
		});

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(virtuallist.$$.fragment);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "listContainer virtualList svelte-ux0sbr");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(virtuallist, div, null);
			/*div_binding*/ ctx[20](div);
			current = true;
		},
		p(ctx, dirty) {
			const virtuallist_changes = {};
			if (dirty[0] & /*items*/ 16) virtuallist_changes.items = /*items*/ ctx[4];
			if (dirty[0] & /*itemHeight*/ 128) virtuallist_changes.itemHeight = /*itemHeight*/ ctx[7];

			if (dirty[0] & /*Item, filterText, getOptionLabel, selectedValue, optionIdentifier, hoverItemIndex, items*/ 4918 | dirty[1] & /*$$scope, i, item*/ 104) {
				virtuallist_changes.$$scope = { dirty, ctx };
			}

			virtuallist.$set(virtuallist_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(virtuallist.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(virtuallist.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(virtuallist);
			/*div_binding*/ ctx[20](null);
		}
	};
}

// (213:2) <VirtualList {items} {itemHeight} let:item let:i>
function create_default_slot(ctx) {
	let div;
	let switch_instance;
	let current;
	let mounted;
	let dispose;
	var switch_value = /*Item*/ ctx[2];

	function switch_props(ctx) {
		return {
			props: {
				item: /*item*/ ctx[34],
				filterText: /*filterText*/ ctx[12],
				getOptionLabel: /*getOptionLabel*/ ctx[5],
				isFirst: isItemFirst(/*i*/ ctx[36]),
				isActive: isItemActive(/*item*/ ctx[34], /*selectedValue*/ ctx[8], /*optionIdentifier*/ ctx[9]),
				isHover: isItemHover(/*hoverItemIndex*/ ctx[1], /*item*/ ctx[34], /*i*/ ctx[36], /*items*/ ctx[4])
			}
		};
	}

	if (switch_value) {
		switch_instance = new switch_value(switch_props(ctx));
	}

	function mouseover_handler() {
		return /*mouseover_handler*/ ctx[18](/*i*/ ctx[36]);
	}

	function click_handler(...args) {
		return /*click_handler*/ ctx[19](/*item*/ ctx[34], /*i*/ ctx[36], ...args);
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "listItem");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (switch_instance) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, div, null);
			}

			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "mouseover", mouseover_handler),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "click", click_handler)
				];

				mounted = true;
			}
		},
		p(new_ctx, dirty) {
			ctx = new_ctx;
			const switch_instance_changes = {};
			if (dirty[1] & /*item*/ 8) switch_instance_changes.item = /*item*/ ctx[34];
			if (dirty[0] & /*filterText*/ 4096) switch_instance_changes.filterText = /*filterText*/ ctx[12];
			if (dirty[0] & /*getOptionLabel*/ 32) switch_instance_changes.getOptionLabel = /*getOptionLabel*/ ctx[5];
			if (dirty[1] & /*i*/ 32) switch_instance_changes.isFirst = isItemFirst(/*i*/ ctx[36]);
			if (dirty[0] & /*selectedValue, optionIdentifier*/ 768 | dirty[1] & /*item*/ 8) switch_instance_changes.isActive = isItemActive(/*item*/ ctx[34], /*selectedValue*/ ctx[8], /*optionIdentifier*/ ctx[9]);
			if (dirty[0] & /*hoverItemIndex, items*/ 18 | dirty[1] & /*item, i*/ 40) switch_instance_changes.isHover = isItemHover(/*hoverItemIndex*/ ctx[1], /*item*/ ctx[34], /*i*/ ctx[36], /*items*/ ctx[4]);

			if (switch_value !== (switch_value = /*Item*/ ctx[2])) {
				if (switch_instance) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
					const old_component = switch_instance;

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (switch_value) {
					switch_instance = new switch_value(switch_props(ctx));
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, div, null);
				} else {
					switch_instance = null;
				}
			} else if (switch_value) {
				switch_instance.$set(switch_instance_changes);
			}
		},
		i(local) {
			if (current) return;
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
			current = true;
		},
		o(local) {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

// (232:0) {#if !isVirtualList}
function create_if_block(ctx) {
	let div;
	let current;
	let each_value = /*items*/ ctx[4];
	let each_blocks = [];

	for (let i = 0; i < each_value.length; i += 1) {
		each_blocks[i] = create_each_block(get_each_context(ctx, each_value, i));
	}

	const out = i => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(each_blocks[i], 1, 1, () => {
		each_blocks[i] = null;
	});

	let each_1_else = null;

	if (!each_value.length) {
		each_1_else = create_else_block_1(ctx);
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].c();
			}

			if (each_1_else) {
				each_1_else.c();
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "listContainer svelte-ux0sbr");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].m(div, null);
			}

			if (each_1_else) {
				each_1_else.m(div, null);
			}

			/*div_binding_1*/ ctx[23](div);
			current = true;
		},
		p(ctx, dirty) {
			if (dirty[0] & /*getGroupHeaderLabel, items, handleHover, handleClick, Item, filterText, getOptionLabel, selectedValue, optionIdentifier, hoverItemIndex, noOptionsMessage, hideEmptyState*/ 32630) {
				each_value = /*items*/ ctx[4];
				let i;

				for (i = 0; i < each_value.length; i += 1) {
					const child_ctx = get_each_context(ctx, each_value, i);

					if (each_blocks[i]) {
						each_blocks[i].p(child_ctx, dirty);
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i], 1);
					} else {
						each_blocks[i] = create_each_block(child_ctx);
						each_blocks[i].c();
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i], 1);
						each_blocks[i].m(div, null);
					}
				}

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				for (i = each_value.length; i < each_blocks.length; i += 1) {
					out(i);
				}

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();

				if (!each_value.length && each_1_else) {
					each_1_else.p(ctx, dirty);
				} else if (!each_value.length) {
					each_1_else = create_else_block_1(ctx);
					each_1_else.c();
					each_1_else.m(div, null);
				} else if (each_1_else) {
					each_1_else.d(1);
					each_1_else = null;
				}
			}
		},
		i(local) {
			if (current) return;

			for (let i = 0; i < each_value.length; i += 1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i]);
			}

			current = true;
		},
		o(local) {
			each_blocks = each_blocks.filter(Boolean);

			for (let i = 0; i < each_blocks.length; i += 1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(each_blocks[i]);
			}

			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_each)(each_blocks, detaching);
			if (each_1_else) each_1_else.d();
			/*div_binding_1*/ ctx[23](null);
		}
	};
}

// (254:2) {:else}
function create_else_block_1(ctx) {
	let if_block_anchor;
	let if_block = !/*hideEmptyState*/ ctx[10] && create_if_block_2(ctx);

	return {
		c() {
			if (if_block) if_block.c();
			if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (if_block) if_block.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
		},
		p(ctx, dirty) {
			if (!/*hideEmptyState*/ ctx[10]) {
				if (if_block) {
					if_block.p(ctx, dirty);
				} else {
					if_block = create_if_block_2(ctx);
					if_block.c();
					if_block.m(if_block_anchor.parentNode, if_block_anchor);
				}
			} else if (if_block) {
				if_block.d(1);
				if_block = null;
			}
		},
		d(detaching) {
			if (if_block) if_block.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
		}
	};
}

// (255:4) {#if !hideEmptyState}
function create_if_block_2(ctx) {
	let div;
	let t;

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(/*noOptionsMessage*/ ctx[11]);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "empty svelte-ux0sbr");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*noOptionsMessage*/ 2048) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, /*noOptionsMessage*/ ctx[11]);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
		}
	};
}

// (237:4) { :else }
function create_else_block(ctx) {
	let div;
	let switch_instance;
	let t;
	let current;
	let mounted;
	let dispose;
	var switch_value = /*Item*/ ctx[2];

	function switch_props(ctx) {
		return {
			props: {
				item: /*item*/ ctx[34],
				filterText: /*filterText*/ ctx[12],
				getOptionLabel: /*getOptionLabel*/ ctx[5],
				isFirst: isItemFirst(/*i*/ ctx[36]),
				isActive: isItemActive(/*item*/ ctx[34], /*selectedValue*/ ctx[8], /*optionIdentifier*/ ctx[9]),
				isHover: isItemHover(/*hoverItemIndex*/ ctx[1], /*item*/ ctx[34], /*i*/ ctx[36], /*items*/ ctx[4])
			}
		};
	}

	if (switch_value) {
		switch_instance = new switch_value(switch_props(ctx));
	}

	function mouseover_handler_1() {
		return /*mouseover_handler_1*/ ctx[21](/*i*/ ctx[36]);
	}

	function click_handler_1(...args) {
		return /*click_handler_1*/ ctx[22](/*item*/ ctx[34], /*i*/ ctx[36], ...args);
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "listItem");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (switch_instance) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, div, null);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "mouseover", mouseover_handler_1),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "click", click_handler_1)
				];

				mounted = true;
			}
		},
		p(new_ctx, dirty) {
			ctx = new_ctx;
			const switch_instance_changes = {};
			if (dirty[0] & /*items*/ 16) switch_instance_changes.item = /*item*/ ctx[34];
			if (dirty[0] & /*filterText*/ 4096) switch_instance_changes.filterText = /*filterText*/ ctx[12];
			if (dirty[0] & /*getOptionLabel*/ 32) switch_instance_changes.getOptionLabel = /*getOptionLabel*/ ctx[5];
			if (dirty[0] & /*items, selectedValue, optionIdentifier*/ 784) switch_instance_changes.isActive = isItemActive(/*item*/ ctx[34], /*selectedValue*/ ctx[8], /*optionIdentifier*/ ctx[9]);
			if (dirty[0] & /*hoverItemIndex, items*/ 18) switch_instance_changes.isHover = isItemHover(/*hoverItemIndex*/ ctx[1], /*item*/ ctx[34], /*i*/ ctx[36], /*items*/ ctx[4]);

			if (switch_value !== (switch_value = /*Item*/ ctx[2])) {
				if (switch_instance) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
					const old_component = switch_instance;

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (switch_value) {
					switch_instance = new switch_value(switch_props(ctx));
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, div, t);
				} else {
					switch_instance = null;
				}
			} else if (switch_value) {
				switch_instance.$set(switch_instance_changes);
			}
		},
		i(local) {
			if (current) return;
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
			current = true;
		},
		o(local) {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

// (235:4) {#if item.isGroupHeader && !item.isSelectable}
function create_if_block_1(ctx) {
	let div;
	let t_value = /*getGroupHeaderLabel*/ ctx[6](/*item*/ ctx[34]) + "";
	let t;

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t_value);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "listGroupTitle svelte-ux0sbr");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*getGroupHeaderLabel, items*/ 80 && t_value !== (t_value = /*getGroupHeaderLabel*/ ctx[6](/*item*/ ctx[34]) + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, t_value);
		},
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
		}
	};
}

// (234:2) {#each items as item, i}
function create_each_block(ctx) {
	let current_block_type_index;
	let if_block;
	let if_block_anchor;
	let current;
	const if_block_creators = [create_if_block_1, create_else_block];
	const if_blocks = [];

	function select_block_type(ctx, dirty) {
		if (/*item*/ ctx[34].isGroupHeader && !/*item*/ ctx[34].isSelectable) return 0;
		return 1;
	}

	current_block_type_index = select_block_type(ctx, [-1, -1]);
	if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);

	return {
		c() {
			if_block.c();
			if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if_blocks[current_block_type_index].m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
			current = true;
		},
		p(ctx, dirty) {
			let previous_block_index = current_block_type_index;
			current_block_type_index = select_block_type(ctx, dirty);

			if (current_block_type_index === previous_block_index) {
				if_blocks[current_block_type_index].p(ctx, dirty);
			} else {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
					if_blocks[previous_block_index] = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				if_block = if_blocks[current_block_type_index];

				if (!if_block) {
					if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
					if_block.c();
				} else {
					if_block.p(ctx, dirty);
				}

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
				if_block.m(if_block_anchor.parentNode, if_block_anchor);
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
			current = false;
		},
		d(detaching) {
			if_blocks[current_block_type_index].d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
		}
	};
}

function create_fragment(ctx) {
	let t;
	let if_block1_anchor;
	let current;
	let mounted;
	let dispose;
	let if_block0 = /*isVirtualList*/ ctx[3] && create_if_block_3(ctx);
	let if_block1 = !/*isVirtualList*/ ctx[3] && create_if_block(ctx);

	return {
		c() {
			if (if_block0) if_block0.c();
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block1) if_block1.c();
			if_block1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (if_block0) if_block0.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
			if (if_block1) if_block1.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block1_anchor, anchor);
			current = true;

			if (!mounted) {
				dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(window, "keydown", /*handleKeyDown*/ ctx[15]);
				mounted = true;
			}
		},
		p(ctx, dirty) {
			if (/*isVirtualList*/ ctx[3]) {
				if (if_block0) {
					if_block0.p(ctx, dirty);

					if (dirty[0] & /*isVirtualList*/ 8) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
					}
				} else {
					if_block0 = create_if_block_3(ctx);
					if_block0.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
					if_block0.m(t.parentNode, t);
				}
			} else if (if_block0) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0, 1, 1, () => {
					if_block0 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			if (!/*isVirtualList*/ ctx[3]) {
				if (if_block1) {
					if_block1.p(ctx, dirty);

					if (dirty[0] & /*isVirtualList*/ 8) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					}
				} else {
					if_block1 = create_if_block(ctx);
					if_block1.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					if_block1.m(if_block1_anchor.parentNode, if_block1_anchor);
				}
			} else if (if_block1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1, 1, 1, () => {
					if_block1 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1);
			current = false;
		},
		d(detaching) {
			if (if_block0) if_block0.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			if (if_block1) if_block1.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block1_anchor);
			mounted = false;
			dispose();
		}
	};
}

function itemClasses(hoverItemIndex, item, itemIndex, items, selectedValue, optionIdentifier, isMulti) {
	return `${selectedValue && !isMulti && selectedValue[optionIdentifier] === item[optionIdentifier]
	? 'active '
	: ''}${hoverItemIndex === itemIndex || items.length === 1
	? 'hover'
	: ''}`;
}

function isItemActive(item, selectedValue, optionIdentifier) {
	return selectedValue && selectedValue[optionIdentifier] === item[optionIdentifier];
}

function isItemFirst(itemIndex) {
	return itemIndex === 0;
}

function isItemHover(hoverItemIndex, item, itemIndex, items) {
	return hoverItemIndex === itemIndex || items.length === 1;
}

function instance($$self, $$props, $$invalidate) {
	const dispatch = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.createEventDispatcher)();
	let { container = undefined } = $$props;
	let { Item = _Item_svelte__WEBPACK_IMPORTED_MODULE_2__["default"] } = $$props;
	let { isVirtualList = false } = $$props;
	let { items = [] } = $$props;

	let { getOptionLabel = (option, filterText) => {
		if (option) return option.isCreator
		? `Create \"${filterText}\"`
		: option.label;
	} } = $$props;

	let { getGroupHeaderLabel = option => {
		return option.label;
	} } = $$props;

	let { itemHeight = 40 } = $$props;
	let { hoverItemIndex = 0 } = $$props;
	let { selectedValue = undefined } = $$props;
	let { optionIdentifier = 'value' } = $$props;
	let { hideEmptyState = false } = $$props;
	let { noOptionsMessage = 'No options' } = $$props;
	let { isMulti = false } = $$props;
	let { activeItemIndex = 0 } = $$props;
	let { filterText = '' } = $$props;
	let isScrollingTimer = 0;
	let isScrolling = false;
	let prev_items;
	let prev_activeItemIndex;
	let prev_selectedValue;

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		if (items.length > 0 && !isMulti && selectedValue) {
			const _hoverItemIndex = items.findIndex(item => item[optionIdentifier] === selectedValue[optionIdentifier]);

			if (_hoverItemIndex) {
				$$invalidate(1, hoverItemIndex = _hoverItemIndex);
			}
		}

		scrollToActiveItem('active');

		container.addEventListener(
			'scroll',
			() => {
				clearTimeout(isScrollingTimer);

				isScrollingTimer = setTimeout(
					() => {
						isScrolling = false;
					},
					100
				);
			},
			false
		);
	});

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onDestroy)(() => {
		
	}); // clearTimeout(isScrollingTimer);

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.beforeUpdate)(() => {
		if (items !== prev_items && items.length > 0) {
			$$invalidate(1, hoverItemIndex = 0);
		}

		// if (prev_activeItemIndex && activeItemIndex > -1) {
		//   hoverItemIndex = activeItemIndex;
		//   scrollToActiveItem('active');
		// }
		// if (prev_selectedValue && selectedValue) {
		//   scrollToActiveItem('active');
		//   if (items && !isMulti) {
		//     const hoverItemIndex = items.findIndex((item) => item[optionIdentifier] === selectedValue[optionIdentifier]);
		//     if (hoverItemIndex) {
		//       hoverItemIndex = hoverItemIndex;
		//     }
		//   }
		// }
		prev_items = items;

		prev_activeItemIndex = activeItemIndex;
		prev_selectedValue = selectedValue;
	});

	function handleSelect(item) {
		if (item.isCreator) return;
		dispatch('itemSelected', item);
	}

	function handleHover(i) {
		if (isScrolling) return;
		$$invalidate(1, hoverItemIndex = i);
	}

	function handleClick(args) {
		const { item, i, event } = args;
		event.stopPropagation();
		if (selectedValue && !isMulti && selectedValue[optionIdentifier] === item[optionIdentifier]) return closeList();

		if (item.isCreator) {
			dispatch('itemCreated', filterText);
		} else {
			$$invalidate(16, activeItemIndex = i);
			$$invalidate(1, hoverItemIndex = i);
			handleSelect(item);
		}
	}

	function closeList() {
		dispatch('closeList');
	}

	async function updateHoverItem(increment) {
		if (isVirtualList) return;
		let isNonSelectableItem = true;

		while (isNonSelectableItem) {
			if (increment > 0 && hoverItemIndex === items.length - 1) {
				$$invalidate(1, hoverItemIndex = 0);
			} else if (increment < 0 && hoverItemIndex === 0) {
				$$invalidate(1, hoverItemIndex = items.length - 1);
			} else {
				$$invalidate(1, hoverItemIndex = hoverItemIndex + increment);
			}

			isNonSelectableItem = items[hoverItemIndex].isGroupHeader && !items[hoverItemIndex].isSelectable;
		}

		await (0,svelte__WEBPACK_IMPORTED_MODULE_1__.tick)();
		scrollToActiveItem('hover');
	}

	function handleKeyDown(e) {
		switch (e.key) {
			case 'ArrowDown':
				e.preventDefault();
				items.length && updateHoverItem(1);
				break;
			case 'ArrowUp':
				e.preventDefault();
				items.length && updateHoverItem(-1);
				break;
			case 'Enter':
				e.preventDefault();
				if (items.length === 0) break;
				const hoverItem = items[hoverItemIndex];
				if (selectedValue && !isMulti && selectedValue[optionIdentifier] === hoverItem[optionIdentifier]) {
					closeList();
					break;
				}
				if (hoverItem.isCreator) {
					dispatch('itemCreated', filterText);
				} else {
					$$invalidate(16, activeItemIndex = hoverItemIndex);
					handleSelect(items[hoverItemIndex]);
				}
				break;
			case 'Tab':
				e.preventDefault();
				if (items.length === 0) break;
				if (selectedValue && selectedValue[optionIdentifier] === items[hoverItemIndex][optionIdentifier]) return closeList();
				$$invalidate(16, activeItemIndex = hoverItemIndex);
				handleSelect(items[hoverItemIndex]);
				break;
		}
	}

	function scrollToActiveItem(className) {
		if (isVirtualList || !container) return;
		let offsetBounding;
		const focusedElemBounding = container.querySelector(`.listItem .${className}`);

		if (focusedElemBounding) {
			offsetBounding = container.getBoundingClientRect().bottom - focusedElemBounding.getBoundingClientRect().bottom;
		}

		$$invalidate(0, container.scrollTop -= offsetBounding, container);
	}

	;
	;
	const mouseover_handler = i => handleHover(i);
	const click_handler = (item, i, event) => handleClick({ item, i, event });

	function div_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			container = $$value;
			$$invalidate(0, container);
		});
	}

	const mouseover_handler_1 = i => handleHover(i);
	const click_handler_1 = (item, i, event) => handleClick({ item, i, event });

	function div_binding_1($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			container = $$value;
			$$invalidate(0, container);
		});
	}

	$$self.$$set = $$props => {
		if ('container' in $$props) $$invalidate(0, container = $$props.container);
		if ('Item' in $$props) $$invalidate(2, Item = $$props.Item);
		if ('isVirtualList' in $$props) $$invalidate(3, isVirtualList = $$props.isVirtualList);
		if ('items' in $$props) $$invalidate(4, items = $$props.items);
		if ('getOptionLabel' in $$props) $$invalidate(5, getOptionLabel = $$props.getOptionLabel);
		if ('getGroupHeaderLabel' in $$props) $$invalidate(6, getGroupHeaderLabel = $$props.getGroupHeaderLabel);
		if ('itemHeight' in $$props) $$invalidate(7, itemHeight = $$props.itemHeight);
		if ('hoverItemIndex' in $$props) $$invalidate(1, hoverItemIndex = $$props.hoverItemIndex);
		if ('selectedValue' in $$props) $$invalidate(8, selectedValue = $$props.selectedValue);
		if ('optionIdentifier' in $$props) $$invalidate(9, optionIdentifier = $$props.optionIdentifier);
		if ('hideEmptyState' in $$props) $$invalidate(10, hideEmptyState = $$props.hideEmptyState);
		if ('noOptionsMessage' in $$props) $$invalidate(11, noOptionsMessage = $$props.noOptionsMessage);
		if ('isMulti' in $$props) $$invalidate(17, isMulti = $$props.isMulti);
		if ('activeItemIndex' in $$props) $$invalidate(16, activeItemIndex = $$props.activeItemIndex);
		if ('filterText' in $$props) $$invalidate(12, filterText = $$props.filterText);
	};

	return [
		container,
		hoverItemIndex,
		Item,
		isVirtualList,
		items,
		getOptionLabel,
		getGroupHeaderLabel,
		itemHeight,
		selectedValue,
		optionIdentifier,
		hideEmptyState,
		noOptionsMessage,
		filterText,
		handleHover,
		handleClick,
		handleKeyDown,
		activeItemIndex,
		isMulti,
		mouseover_handler,
		click_handler,
		div_binding,
		mouseover_handler_1,
		click_handler_1,
		div_binding_1
	];
}

class List extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(
			this,
			options,
			instance,
			create_fragment,
			svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal,
			{
				container: 0,
				Item: 2,
				isVirtualList: 3,
				items: 4,
				getOptionLabel: 5,
				getGroupHeaderLabel: 6,
				itemHeight: 7,
				hoverItemIndex: 1,
				selectedValue: 8,
				optionIdentifier: 9,
				hideEmptyState: 10,
				noOptionsMessage: 11,
				isMulti: 17,
				activeItemIndex: 16,
				filterText: 12
			},
			null,
			[-1, -1]
		);
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (List);




/***/ }),

/***/ "./node_modules/svelte-select/src/MultiSelection.svelte":
/*!**************************************************************!*\
  !*** ./node_modules/svelte-select/src/MultiSelection.svelte ***!
  \**************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_select_src_MultiSelection_svelte_30_css_svelte_loader_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_MultiSelection_svelte_30_css_D_www_sipro_spa_node_modules_svelte_select_src_MultiSelection_svelte__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-select/src/MultiSelection.svelte.30.css!=!svelte-loader?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/MultiSelection.svelte.30.css!./node_modules/svelte-select/src/MultiSelection.svelte */ "./node_modules/svelte-select/src/MultiSelection.svelte.30.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/MultiSelection.svelte.30.css!./node_modules/svelte-select/src/MultiSelection.svelte");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\svelte-select\src\MultiSelection.svelte generated by Svelte v3.48.0 */




function get_each_context(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[9] = list[i];
	child_ctx[11] = i;
	return child_ctx;
}

// (23:2) {#if !isDisabled && !multiFullItemClearable}
function create_if_block(ctx) {
	let div;
	let mounted;
	let dispose;

	function click_handler(...args) {
		return /*click_handler*/ ctx[6](/*i*/ ctx[11], ...args);
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			div.innerHTML = `<svg width="100%" height="100%" viewBox="-2 -2 50 50" focusable="false" role="presentation" class="svelte-14r1jr2"><path d="M34.923,37.251L24,26.328L13.077,37.251L9.436,33.61l10.923-10.923L9.436,11.765l3.641-3.641L24,19.047L34.923,8.124 l3.641,3.641L27.641,22.688L38.564,33.61L34.923,37.251z"></path></svg>`;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "multiSelectItem_clear svelte-14r1jr2");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (!mounted) {
				dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "click", click_handler);
				mounted = true;
			}
		},
		p(new_ctx, dirty) {
			ctx = new_ctx;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			mounted = false;
			dispose();
		}
	};
}

// (18:0) {#each selectedValue as value, i}
function create_each_block(ctx) {
	let div1;
	let div0;
	let raw_value = /*getSelectionLabel*/ ctx[4](/*value*/ ctx[9]) + "";
	let t0;
	let t1;
	let div1_class_value;
	let mounted;
	let dispose;
	let if_block = !/*isDisabled*/ ctx[2] && !/*multiFullItemClearable*/ ctx[3] && create_if_block(ctx);

	function click_handler_1(...args) {
		return /*click_handler_1*/ ctx[7](/*i*/ ctx[11], ...args);
	}

	return {
		c() {
			div1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			div0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block) if_block.c();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div0, "class", "multiSelectItem_label svelte-14r1jr2");

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div1, "class", div1_class_value = "multiSelectItem " + (/*activeSelectedValue*/ ctx[1] === /*i*/ ctx[11]
			? 'active'
			: '') + " " + (/*isDisabled*/ ctx[2] ? 'disabled' : '') + " svelte-14r1jr2");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div1, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div1, div0);
			div0.innerHTML = raw_value;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div1, t0);
			if (if_block) if_block.m(div1, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div1, t1);

			if (!mounted) {
				dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div1, "click", click_handler_1);
				mounted = true;
			}
		},
		p(new_ctx, dirty) {
			ctx = new_ctx;
			if (dirty & /*getSelectionLabel, selectedValue*/ 17 && raw_value !== (raw_value = /*getSelectionLabel*/ ctx[4](/*value*/ ctx[9]) + "")) div0.innerHTML = raw_value;;

			if (!/*isDisabled*/ ctx[2] && !/*multiFullItemClearable*/ ctx[3]) {
				if (if_block) {
					if_block.p(ctx, dirty);
				} else {
					if_block = create_if_block(ctx);
					if_block.c();
					if_block.m(div1, t1);
				}
			} else if (if_block) {
				if_block.d(1);
				if_block = null;
			}

			if (dirty & /*activeSelectedValue, isDisabled*/ 6 && div1_class_value !== (div1_class_value = "multiSelectItem " + (/*activeSelectedValue*/ ctx[1] === /*i*/ ctx[11]
			? 'active'
			: '') + " " + (/*isDisabled*/ ctx[2] ? 'disabled' : '') + " svelte-14r1jr2")) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div1, "class", div1_class_value);
			}
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div1);
			if (if_block) if_block.d();
			mounted = false;
			dispose();
		}
	};
}

function create_fragment(ctx) {
	let each_1_anchor;
	let each_value = /*selectedValue*/ ctx[0];
	let each_blocks = [];

	for (let i = 0; i < each_value.length; i += 1) {
		each_blocks[i] = create_each_block(get_each_context(ctx, each_value, i));
	}

	return {
		c() {
			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].c();
			}

			each_1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].m(target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, each_1_anchor, anchor);
		},
		p(ctx, [dirty]) {
			if (dirty & /*activeSelectedValue, isDisabled, multiFullItemClearable, handleClear, getSelectionLabel, selectedValue*/ 63) {
				each_value = /*selectedValue*/ ctx[0];
				let i;

				for (i = 0; i < each_value.length; i += 1) {
					const child_ctx = get_each_context(ctx, each_value, i);

					if (each_blocks[i]) {
						each_blocks[i].p(child_ctx, dirty);
					} else {
						each_blocks[i] = create_each_block(child_ctx);
						each_blocks[i].c();
						each_blocks[i].m(each_1_anchor.parentNode, each_1_anchor);
					}
				}

				for (; i < each_blocks.length; i += 1) {
					each_blocks[i].d(1);
				}

				each_blocks.length = each_value.length;
			}
		},
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_each)(each_blocks, detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(each_1_anchor);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	const dispatch = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.createEventDispatcher)();
	let { selectedValue = [] } = $$props;
	let { activeSelectedValue = undefined } = $$props;
	let { isDisabled = false } = $$props;
	let { multiFullItemClearable = false } = $$props;
	let { getSelectionLabel = undefined } = $$props;

	function handleClear(i, event) {
		event.stopPropagation();
		dispatch('multiItemClear', { i });
	}

	const click_handler = (i, event) => handleClear(i, event);
	const click_handler_1 = (i, event) => multiFullItemClearable ? handleClear(i, event) : {};

	$$self.$$set = $$props => {
		if ('selectedValue' in $$props) $$invalidate(0, selectedValue = $$props.selectedValue);
		if ('activeSelectedValue' in $$props) $$invalidate(1, activeSelectedValue = $$props.activeSelectedValue);
		if ('isDisabled' in $$props) $$invalidate(2, isDisabled = $$props.isDisabled);
		if ('multiFullItemClearable' in $$props) $$invalidate(3, multiFullItemClearable = $$props.multiFullItemClearable);
		if ('getSelectionLabel' in $$props) $$invalidate(4, getSelectionLabel = $$props.getSelectionLabel);
	};

	return [
		selectedValue,
		activeSelectedValue,
		isDisabled,
		multiFullItemClearable,
		getSelectionLabel,
		handleClear,
		click_handler,
		click_handler_1
	];
}

class MultiSelection extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			selectedValue: 0,
			activeSelectedValue: 1,
			isDisabled: 2,
			multiFullItemClearable: 3,
			getSelectionLabel: 4
		});
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MultiSelection);




/***/ }),

/***/ "./node_modules/svelte-select/src/Select.svelte":
/*!******************************************************!*\
  !*** ./node_modules/svelte-select/src/Select.svelte ***!
  \******************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _List_svelte__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./List.svelte */ "./node_modules/svelte-select/src/List.svelte");
/* harmony import */ var _Item_svelte__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Item.svelte */ "./node_modules/svelte-select/src/Item.svelte");
/* harmony import */ var _Selection_svelte__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Selection.svelte */ "./node_modules/svelte-select/src/Selection.svelte");
/* harmony import */ var _MultiSelection_svelte__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./MultiSelection.svelte */ "./node_modules/svelte-select/src/MultiSelection.svelte");
/* harmony import */ var _utils_isOutOfViewport__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./utils/isOutOfViewport */ "./node_modules/svelte-select/src/utils/isOutOfViewport.js");
/* harmony import */ var _utils_debounce__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./utils/debounce */ "./node_modules/svelte-select/src/utils/debounce.js");
/* harmony import */ var _ClearIcon_svelte__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./ClearIcon.svelte */ "./node_modules/svelte-select/src/ClearIcon.svelte");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_select_src_Select_svelte_26_css_svelte_loader_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Select_svelte_26_css_D_www_sipro_spa_node_modules_svelte_select_src_Select_svelte__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./node_modules/svelte-select/src/Select.svelte.26.css!=!svelte-loader?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Select.svelte.26.css!./node_modules/svelte-select/src/Select.svelte */ "./node_modules/svelte-select/src/Select.svelte.26.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Select.svelte.26.css!./node_modules/svelte-select/src/Select.svelte");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\svelte-select\src\Select.svelte generated by Svelte v3.48.0 */












function create_if_block_7(ctx) {
	let switch_instance;
	let switch_instance_anchor;
	let current;
	const switch_instance_spread_levels = [/*iconProps*/ ctx[18]];
	var switch_value = /*Icon*/ ctx[17];

	function switch_props(ctx) {
		let switch_instance_props = {};

		for (let i = 0; i < switch_instance_spread_levels.length; i += 1) {
			switch_instance_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(switch_instance_props, switch_instance_spread_levels[i]);
		}

		return { props: switch_instance_props };
	}

	if (switch_value) {
		switch_instance = new switch_value(switch_props(ctx));
	}

	return {
		c() {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
			switch_instance_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (switch_instance) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, switch_instance_anchor, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const switch_instance_changes = (dirty[0] & /*iconProps*/ 262144)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(switch_instance_spread_levels, [(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*iconProps*/ ctx[18])])
			: {};

			if (switch_value !== (switch_value = /*Icon*/ ctx[17])) {
				if (switch_instance) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
					const old_component = switch_instance;

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (switch_value) {
					switch_instance = new switch_value(switch_props(ctx));
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, switch_instance_anchor.parentNode, switch_instance_anchor);
				} else {
					switch_instance = null;
				}
			} else if (switch_value) {
				switch_instance.$set(switch_instance_changes);
			}
		},
		i(local) {
			if (current) return;
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
			current = true;
		},
		o(local) {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(switch_instance_anchor);
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance, detaching);
		}
	};
}

// (831:2) {#if isMulti && selectedValue && selectedValue.length > 0}
function create_if_block_6(ctx) {
	let switch_instance;
	let switch_instance_anchor;
	let current;
	var switch_value = /*MultiSelection*/ ctx[7];

	function switch_props(ctx) {
		return {
			props: {
				selectedValue: /*selectedValue*/ ctx[0],
				getSelectionLabel: /*getSelectionLabel*/ ctx[13],
				activeSelectedValue: /*activeSelectedValue*/ ctx[25],
				isDisabled: /*isDisabled*/ ctx[10],
				multiFullItemClearable: /*multiFullItemClearable*/ ctx[9]
			}
		};
	}

	if (switch_value) {
		switch_instance = new switch_value(switch_props(ctx));
		switch_instance.$on("multiItemClear", /*handleMultiItemClear*/ ctx[29]);
		switch_instance.$on("focus", /*handleFocus*/ ctx[32]);
	}

	return {
		c() {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
			switch_instance_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (switch_instance) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, switch_instance_anchor, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const switch_instance_changes = {};
			if (dirty[0] & /*selectedValue*/ 1) switch_instance_changes.selectedValue = /*selectedValue*/ ctx[0];
			if (dirty[0] & /*getSelectionLabel*/ 8192) switch_instance_changes.getSelectionLabel = /*getSelectionLabel*/ ctx[13];
			if (dirty[0] & /*activeSelectedValue*/ 33554432) switch_instance_changes.activeSelectedValue = /*activeSelectedValue*/ ctx[25];
			if (dirty[0] & /*isDisabled*/ 1024) switch_instance_changes.isDisabled = /*isDisabled*/ ctx[10];
			if (dirty[0] & /*multiFullItemClearable*/ 512) switch_instance_changes.multiFullItemClearable = /*multiFullItemClearable*/ ctx[9];

			if (switch_value !== (switch_value = /*MultiSelection*/ ctx[7])) {
				if (switch_instance) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
					const old_component = switch_instance;

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (switch_value) {
					switch_instance = new switch_value(switch_props(ctx));
					switch_instance.$on("multiItemClear", /*handleMultiItemClear*/ ctx[29]);
					switch_instance.$on("focus", /*handleFocus*/ ctx[32]);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, switch_instance_anchor.parentNode, switch_instance_anchor);
				} else {
					switch_instance = null;
				}
			} else if (switch_value) {
				switch_instance.$set(switch_instance_changes);
			}
		},
		i(local) {
			if (current) return;
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
			current = true;
		},
		o(local) {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(switch_instance_anchor);
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance, detaching);
		}
	};
}

// (852:2) {:else}
function create_else_block_1(ctx) {
	let input_1;
	let mounted;
	let dispose;

	let input_1_levels = [
		/*_inputAttributes*/ ctx[26],
		{ placeholder: /*placeholderText*/ ctx[27] },
		{ style: /*inputStyles*/ ctx[15] }
	];

	let input_1_data = {};

	for (let i = 0; i < input_1_levels.length; i += 1) {
		input_1_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(input_1_data, input_1_levels[i]);
	}

	return {
		c() {
			input_1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("input");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(input_1, input_1_data);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(input_1, "svelte-17qb5ew", true);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, input_1, anchor);
			if (input_1.autofocus) input_1.focus();
			/*input_1_binding_1*/ ctx[63](input_1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_input_value)(input_1, /*filterText*/ ctx[1]);

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(input_1, "focus", /*handleFocus*/ ctx[32]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(input_1, "input", /*input_1_input_handler_1*/ ctx[64])
				];

				mounted = true;
			}
		},
		p(ctx, dirty) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(input_1, input_1_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(input_1_levels, [
				dirty[0] & /*_inputAttributes*/ 67108864 && /*_inputAttributes*/ ctx[26],
				dirty[0] & /*placeholderText*/ 134217728 && { placeholder: /*placeholderText*/ ctx[27] },
				dirty[0] & /*inputStyles*/ 32768 && { style: /*inputStyles*/ ctx[15] }
			]));

			if (dirty[0] & /*filterText*/ 2 && input_1.value !== /*filterText*/ ctx[1]) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_input_value)(input_1, /*filterText*/ ctx[1]);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(input_1, "svelte-17qb5ew", true);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(input_1);
			/*input_1_binding_1*/ ctx[63](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

// (843:2) {#if isDisabled}
function create_if_block_5(ctx) {
	let input_1;
	let mounted;
	let dispose;

	let input_1_levels = [
		/*_inputAttributes*/ ctx[26],
		{ placeholder: /*placeholderText*/ ctx[27] },
		{ style: /*inputStyles*/ ctx[15] },
		{ disabled: true }
	];

	let input_1_data = {};

	for (let i = 0; i < input_1_levels.length; i += 1) {
		input_1_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(input_1_data, input_1_levels[i]);
	}

	return {
		c() {
			input_1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("input");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(input_1, input_1_data);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(input_1, "svelte-17qb5ew", true);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, input_1, anchor);
			if (input_1.autofocus) input_1.focus();
			/*input_1_binding*/ ctx[61](input_1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_input_value)(input_1, /*filterText*/ ctx[1]);

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(input_1, "focus", /*handleFocus*/ ctx[32]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(input_1, "input", /*input_1_input_handler*/ ctx[62])
				];

				mounted = true;
			}
		},
		p(ctx, dirty) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(input_1, input_1_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(input_1_levels, [
				dirty[0] & /*_inputAttributes*/ 67108864 && /*_inputAttributes*/ ctx[26],
				dirty[0] & /*placeholderText*/ 134217728 && { placeholder: /*placeholderText*/ ctx[27] },
				dirty[0] & /*inputStyles*/ 32768 && { style: /*inputStyles*/ ctx[15] },
				{ disabled: true }
			]));

			if (dirty[0] & /*filterText*/ 2 && input_1.value !== /*filterText*/ ctx[1]) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_input_value)(input_1, /*filterText*/ ctx[1]);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(input_1, "svelte-17qb5ew", true);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(input_1);
			/*input_1_binding*/ ctx[61](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

// (862:2) {#if !isMulti && showSelectedItem}
function create_if_block_4(ctx) {
	let div;
	let switch_instance;
	let current;
	let mounted;
	let dispose;
	var switch_value = /*Selection*/ ctx[6];

	function switch_props(ctx) {
		return {
			props: {
				item: /*selectedValue*/ ctx[0],
				getSelectionLabel: /*getSelectionLabel*/ ctx[13]
			}
		};
	}

	if (switch_value) {
		switch_instance = new switch_value(switch_props(ctx));
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "selectedItem svelte-17qb5ew");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (switch_instance) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, div, null);
			}

			current = true;

			if (!mounted) {
				dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "focus", /*handleFocus*/ ctx[32]);
				mounted = true;
			}
		},
		p(ctx, dirty) {
			const switch_instance_changes = {};
			if (dirty[0] & /*selectedValue*/ 1) switch_instance_changes.item = /*selectedValue*/ ctx[0];
			if (dirty[0] & /*getSelectionLabel*/ 8192) switch_instance_changes.getSelectionLabel = /*getSelectionLabel*/ ctx[13];

			if (switch_value !== (switch_value = /*Selection*/ ctx[6])) {
				if (switch_instance) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
					const old_component = switch_instance;

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (switch_value) {
					switch_instance = new switch_value(switch_props(ctx));
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, div, null);
				} else {
					switch_instance = null;
				}
			} else if (switch_value) {
				switch_instance.$set(switch_instance_changes);
			}
		},
		i(local) {
			if (current) return;
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
			current = true;
		},
		o(local) {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance);
			mounted = false;
			dispose();
		}
	};
}

// (871:2) {#if showSelectedItem && isClearable && !isDisabled && !isWaiting}
function create_if_block_3(ctx) {
	let div;
	let switch_instance;
	let current;
	let mounted;
	let dispose;
	var switch_value = /*ClearIcon*/ ctx[23];

	function switch_props(ctx) {
		return {};
	}

	if (switch_value) {
		switch_instance = new switch_value(switch_props(ctx));
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "clearSelect svelte-17qb5ew");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (switch_instance) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, div, null);
			}

			current = true;

			if (!mounted) {
				dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "click", (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.prevent_default)(/*handleClear*/ ctx[24]));
				mounted = true;
			}
		},
		p(ctx, dirty) {
			if (switch_value !== (switch_value = /*ClearIcon*/ ctx[23])) {
				if (switch_instance) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
					const old_component = switch_instance;

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(old_component.$$.fragment, 1, 0, () => {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(old_component, 1);
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (switch_value) {
					switch_instance = new switch_value(switch_props(ctx));
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(switch_instance.$$.fragment);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, 1);
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(switch_instance, div, null);
				} else {
					switch_instance = null;
				}
			} else if (switch_value) {
				
			}
		},
		i(local) {
			if (current) return;
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(switch_instance.$$.fragment, local);
			current = true;
		},
		o(local) {
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(switch_instance.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (switch_instance) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(switch_instance);
			mounted = false;
			dispose();
		}
	};
}

// (877:2) {#if showIndicator || (showChevron && !selectedValue || (!isSearchable && !isDisabled && !isWaiting && ((showSelectedItem && !isClearable) || !showSelectedItem)))}
function create_if_block_1(ctx) {
	let div;

	function select_block_type_1(ctx, dirty) {
		if (/*indicatorSvg*/ ctx[22]) return create_if_block_2;
		return create_else_block;
	}

	let current_block_type = select_block_type_1(ctx, [-1, -1, -1]);
	let if_block = current_block_type(ctx);

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if_block.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "indicator svelte-17qb5ew");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			if_block.m(div, null);
		},
		p(ctx, dirty) {
			if (current_block_type === (current_block_type = select_block_type_1(ctx, dirty)) && if_block) {
				if_block.p(ctx, dirty);
			} else {
				if_block.d(1);
				if_block = current_block_type(ctx);

				if (if_block) {
					if_block.c();
					if_block.m(div, null);
				}
			}
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if_block.d();
		}
	};
}

// (881:6) {:else}
function create_else_block(ctx) {
	let svg;
	let path;

	return {
		c() {
			svg = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.svg_element)("svg");
			path = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.svg_element)("path");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(path, "d", "M4.516 7.548c0.436-0.446 1.043-0.481 1.576 0l3.908 3.747\n            3.908-3.747c0.533-0.481 1.141-0.446 1.574 0 0.436 0.445 0.408 1.197 0\n            1.615-0.406 0.418-4.695 4.502-4.695 4.502-0.217 0.223-0.502\n            0.335-0.787 0.335s-0.57-0.112-0.789-0.335c0\n            0-4.287-4.084-4.695-4.502s-0.436-1.17 0-1.615z");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "width", "100%");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "height", "100%");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "viewBox", "0 0 20 20");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "focusable", "false");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "class", "svelte-17qb5ew");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, svg, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(svg, path);
		},
		p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(svg);
		}
	};
}

// (879:6) {#if indicatorSvg}
function create_if_block_2(ctx) {
	let html_tag;
	let html_anchor;

	return {
		c() {
			html_tag = new svelte_internal__WEBPACK_IMPORTED_MODULE_0__.HtmlTag(false);
			html_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
			html_tag.a = html_anchor;
		},
		m(target, anchor) {
			html_tag.m(/*indicatorSvg*/ ctx[22], target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, html_anchor, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*indicatorSvg*/ 4194304) html_tag.p(/*indicatorSvg*/ ctx[22]);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(html_anchor);
			if (detaching) html_tag.d();
		}
	};
}

// (898:2) {#if isWaiting}
function create_if_block(ctx) {
	let div;

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			div.innerHTML = `<svg class="spinner_icon svelte-17qb5ew" viewBox="25 25 50 50"><circle class="spinner_path svelte-17qb5ew" cx="50" cy="50" r="20" fill="none" stroke="currentColor" stroke-width="5" stroke-miterlimit="10"></circle></svg>`;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "spinner svelte-17qb5ew");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
		}
	};
}

function create_fragment(ctx) {
	let div;
	let t0;
	let t1;
	let t2;
	let t3;
	let t4;
	let t5;
	let div_class_value;
	let current;
	let mounted;
	let dispose;
	let if_block0 = /*Icon*/ ctx[17] && create_if_block_7(ctx);
	let if_block1 = /*isMulti*/ ctx[8] && /*selectedValue*/ ctx[0] && /*selectedValue*/ ctx[0].length > 0 && create_if_block_6(ctx);

	function select_block_type(ctx, dirty) {
		if (/*isDisabled*/ ctx[10]) return create_if_block_5;
		return create_else_block_1;
	}

	let current_block_type = select_block_type(ctx, [-1, -1, -1]);
	let if_block2 = current_block_type(ctx);
	let if_block3 = !/*isMulti*/ ctx[8] && /*showSelectedItem*/ ctx[28] && create_if_block_4(ctx);
	let if_block4 = /*showSelectedItem*/ ctx[28] && /*isClearable*/ ctx[16] && !/*isDisabled*/ ctx[10] && !/*isWaiting*/ ctx[5] && create_if_block_3(ctx);
	let if_block5 = (/*showIndicator*/ ctx[20] || (/*showChevron*/ ctx[19] && !/*selectedValue*/ ctx[0] || !/*isSearchable*/ ctx[14] && !/*isDisabled*/ ctx[10] && !/*isWaiting*/ ctx[5] && (/*showSelectedItem*/ ctx[28] && !/*isClearable*/ ctx[16] || !/*showSelectedItem*/ ctx[28]))) && create_if_block_1(ctx);
	let if_block6 = /*isWaiting*/ ctx[5] && create_if_block(ctx);

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (if_block0) if_block0.c();
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block1) if_block1.c();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if_block2.c();
			t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block3) if_block3.c();
			t3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block4) if_block4.c();
			t4 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block5) if_block5.c();
			t5 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block6) if_block6.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", div_class_value = "selectContainer " + /*containerClasses*/ ctx[21] + " svelte-17qb5ew");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "style", /*containerStyles*/ ctx[12]);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(div, "hasError", /*hasError*/ ctx[11]);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(div, "multiSelect", /*isMulti*/ ctx[8]);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(div, "disabled", /*isDisabled*/ ctx[10]);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(div, "focused", /*isFocused*/ ctx[4]);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			if (if_block0) if_block0.m(div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t0);
			if (if_block1) if_block1.m(div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t1);
			if_block2.m(div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t2);
			if (if_block3) if_block3.m(div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t3);
			if (if_block4) if_block4.m(div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t4);
			if (if_block5) if_block5.m(div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t5);
			if (if_block6) if_block6.m(div, null);
			/*div_binding*/ ctx[65](div);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(window, "click", /*handleWindowClick*/ ctx[33]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(window, "keydown", /*handleKeyDown*/ ctx[31]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(window, "resize", /*getPosition*/ ctx[30]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(div, "click", /*handleClick*/ ctx[34])
				];

				mounted = true;
			}
		},
		p(ctx, dirty) {
			if (/*Icon*/ ctx[17]) {
				if (if_block0) {
					if_block0.p(ctx, dirty);

					if (dirty[0] & /*Icon*/ 131072) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
					}
				} else {
					if_block0 = create_if_block_7(ctx);
					if_block0.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
					if_block0.m(div, t0);
				}
			} else if (if_block0) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0, 1, 1, () => {
					if_block0 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			if (/*isMulti*/ ctx[8] && /*selectedValue*/ ctx[0] && /*selectedValue*/ ctx[0].length > 0) {
				if (if_block1) {
					if_block1.p(ctx, dirty);

					if (dirty[0] & /*isMulti, selectedValue*/ 257) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					}
				} else {
					if_block1 = create_if_block_6(ctx);
					if_block1.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					if_block1.m(div, t1);
				}
			} else if (if_block1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1, 1, 1, () => {
					if_block1 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			if (current_block_type === (current_block_type = select_block_type(ctx, dirty)) && if_block2) {
				if_block2.p(ctx, dirty);
			} else {
				if_block2.d(1);
				if_block2 = current_block_type(ctx);

				if (if_block2) {
					if_block2.c();
					if_block2.m(div, t2);
				}
			}

			if (!/*isMulti*/ ctx[8] && /*showSelectedItem*/ ctx[28]) {
				if (if_block3) {
					if_block3.p(ctx, dirty);

					if (dirty[0] & /*isMulti, showSelectedItem*/ 268435712) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block3, 1);
					}
				} else {
					if_block3 = create_if_block_4(ctx);
					if_block3.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block3, 1);
					if_block3.m(div, t3);
				}
			} else if (if_block3) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block3, 1, 1, () => {
					if_block3 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			if (/*showSelectedItem*/ ctx[28] && /*isClearable*/ ctx[16] && !/*isDisabled*/ ctx[10] && !/*isWaiting*/ ctx[5]) {
				if (if_block4) {
					if_block4.p(ctx, dirty);

					if (dirty[0] & /*showSelectedItem, isClearable, isDisabled, isWaiting*/ 268502048) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block4, 1);
					}
				} else {
					if_block4 = create_if_block_3(ctx);
					if_block4.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block4, 1);
					if_block4.m(div, t4);
				}
			} else if (if_block4) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block4, 1, 1, () => {
					if_block4 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			if (/*showIndicator*/ ctx[20] || (/*showChevron*/ ctx[19] && !/*selectedValue*/ ctx[0] || !/*isSearchable*/ ctx[14] && !/*isDisabled*/ ctx[10] && !/*isWaiting*/ ctx[5] && (/*showSelectedItem*/ ctx[28] && !/*isClearable*/ ctx[16] || !/*showSelectedItem*/ ctx[28]))) {
				if (if_block5) {
					if_block5.p(ctx, dirty);
				} else {
					if_block5 = create_if_block_1(ctx);
					if_block5.c();
					if_block5.m(div, t5);
				}
			} else if (if_block5) {
				if_block5.d(1);
				if_block5 = null;
			}

			if (/*isWaiting*/ ctx[5]) {
				if (if_block6) {
					
				} else {
					if_block6 = create_if_block(ctx);
					if_block6.c();
					if_block6.m(div, null);
				}
			} else if (if_block6) {
				if_block6.d(1);
				if_block6 = null;
			}

			if (!current || dirty[0] & /*containerClasses*/ 2097152 && div_class_value !== (div_class_value = "selectContainer " + /*containerClasses*/ ctx[21] + " svelte-17qb5ew")) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", div_class_value);
			}

			if (!current || dirty[0] & /*containerStyles*/ 4096) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "style", /*containerStyles*/ ctx[12]);
			}

			if (dirty[0] & /*containerClasses, hasError*/ 2099200) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(div, "hasError", /*hasError*/ ctx[11]);
			}

			if (dirty[0] & /*containerClasses, isMulti*/ 2097408) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(div, "multiSelect", /*isMulti*/ ctx[8]);
			}

			if (dirty[0] & /*containerClasses, isDisabled*/ 2098176) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(div, "disabled", /*isDisabled*/ ctx[10]);
			}

			if (dirty[0] & /*containerClasses, isFocused*/ 2097168) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.toggle_class)(div, "focused", /*isFocused*/ ctx[4]);
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block4);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block4);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (if_block0) if_block0.d();
			if (if_block1) if_block1.d();
			if_block2.d();
			if (if_block3) if_block3.d();
			if (if_block4) if_block4.d();
			if (if_block5) if_block5.d();
			if (if_block6) if_block6.d();
			/*div_binding*/ ctx[65](null);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let disabled;
	let showSelectedItem;
	let placeholderText;
	const dispatch = (0,svelte__WEBPACK_IMPORTED_MODULE_1__.createEventDispatcher)();
	let { container = undefined } = $$props;
	let { input = undefined } = $$props;
	let { Item = _Item_svelte__WEBPACK_IMPORTED_MODULE_3__["default"] } = $$props;
	let { Selection = _Selection_svelte__WEBPACK_IMPORTED_MODULE_4__["default"] } = $$props;
	let { MultiSelection = _MultiSelection_svelte__WEBPACK_IMPORTED_MODULE_5__["default"] } = $$props;
	let { isMulti = false } = $$props;
	let { multiFullItemClearable = false } = $$props;
	let { isDisabled = false } = $$props;
	let { isCreatable = false } = $$props;
	let { isFocused = false } = $$props;
	let { selectedValue = undefined } = $$props;
	let { filterText = "" } = $$props;
	let { placeholder = "Select..." } = $$props;
	let { items = [] } = $$props;
	let { itemFilter = (label, filterText, option) => label.toLowerCase().includes(filterText.toLowerCase()) } = $$props;
	let { groupBy = undefined } = $$props;
	let { groupFilter = groups => groups } = $$props;
	let { isGroupHeaderSelectable = false } = $$props;

	let { getGroupHeaderLabel = option => {
		return option.label;
	} } = $$props;

	let { getOptionLabel = (option, filterText) => {
		return option.isCreator
		? `Create \"${filterText}\"`
		: option.label;
	} } = $$props;

	let { optionIdentifier = "value" } = $$props;
	let { loadOptions = undefined } = $$props;
	let { hasError = false } = $$props;
	let { containerStyles = "" } = $$props;

	let { getSelectionLabel = option => {
		if (option) return option.label;
	} } = $$props;

	let { createGroupHeaderItem = groupValue => {
		return { value: groupValue, label: groupValue };
	} } = $$props;

	let { createItem = filterText => {
		return { value: filterText, label: filterText };
	} } = $$props;

	let { isSearchable = true } = $$props;
	let { inputStyles = "" } = $$props;
	let { isClearable = true } = $$props;
	let { isWaiting = false } = $$props;
	let { listPlacement = "auto" } = $$props;
	let { listOpen = false } = $$props;
	let { list = undefined } = $$props;
	let { isVirtualList = false } = $$props;
	let { loadOptionsInterval = 300 } = $$props;
	let { noOptionsMessage = "No options" } = $$props;
	let { hideEmptyState = false } = $$props;
	let { filteredItems = [] } = $$props;
	let { inputAttributes = {} } = $$props;
	let { listAutoWidth = true } = $$props;
	let { itemHeight = 40 } = $$props;
	let { Icon = undefined } = $$props;
	let { iconProps = {} } = $$props;
	let { showChevron = false } = $$props;
	let { showIndicator = false } = $$props;
	let { containerClasses = "" } = $$props;
	let { indicatorSvg = undefined } = $$props;
	let { ClearIcon = _ClearIcon_svelte__WEBPACK_IMPORTED_MODULE_8__["default"] } = $$props;
	let target;
	let activeSelectedValue;
	let _items = [];
	let originalItemsClone;
	let prev_selectedValue;
	let prev_listOpen;
	let prev_filterText;
	let prev_isFocused;
	let prev_filteredItems;

	async function resetFilter() {
		await (0,svelte__WEBPACK_IMPORTED_MODULE_1__.tick)();
		$$invalidate(1, filterText = "");
	}

	let getItemsHasInvoked = false;

	const getItems = (0,_utils_debounce__WEBPACK_IMPORTED_MODULE_7__["default"])(
		async () => {
			getItemsHasInvoked = true;
			$$invalidate(5, isWaiting = true);

			let res = await loadOptions(filterText).catch(err => {
				console.warn('svelte-select loadOptions error :>> ', err);
				dispatch("error", { type: 'loadOptions', details: err });
			});

			if (res && !res.cancelled) {
				if (res) {
					$$invalidate(35, items = [...res]);
					dispatch("loaded", { items });
				} else {
					$$invalidate(35, items = []);
				}

				$$invalidate(5, isWaiting = false);
				$$invalidate(4, isFocused = true);
				$$invalidate(37, listOpen = true);
			}
		},
		loadOptionsInterval
	);

	let _inputAttributes = {};

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.beforeUpdate)(() => {
		if (isMulti && selectedValue && selectedValue.length > 1) {
			checkSelectedValueForDuplicates();
		}

		if (!isMulti && selectedValue && prev_selectedValue !== selectedValue) {
			if (!prev_selectedValue || JSON.stringify(selectedValue[optionIdentifier]) !== JSON.stringify(prev_selectedValue[optionIdentifier])) {
				dispatch("select", selectedValue);
			}
		}

		if (isMulti && JSON.stringify(selectedValue) !== JSON.stringify(prev_selectedValue)) {
			if (checkSelectedValueForDuplicates()) {
				dispatch("select", selectedValue);
			}
		}

		if (container && listOpen !== prev_listOpen) {
			if (listOpen) {
				loadList();
			} else {
				removeList();
			}
		}

		if (filterText !== prev_filterText) {
			if (filterText.length > 0) {
				$$invalidate(4, isFocused = true);
				$$invalidate(37, listOpen = true);

				if (loadOptions) {
					getItems();
				} else {
					loadList();
					$$invalidate(37, listOpen = true);

					if (isMulti) {
						$$invalidate(25, activeSelectedValue = undefined);
					}
				}
			} else {
				setList([]);
			}

			if (list) {
				list.$set({ filterText });
			}
		}

		if (isFocused !== prev_isFocused) {
			if (isFocused || listOpen) {
				handleFocus();
			} else {
				resetFilter();
				if (input) input.blur();
			}
		}

		if (prev_filteredItems !== filteredItems) {
			let _filteredItems = [...filteredItems];

			if (isCreatable && filterText) {
				const itemToCreate = createItem(filterText);
				itemToCreate.isCreator = true;

				const existingItemWithFilterValue = _filteredItems.find(item => {
					return item[optionIdentifier] === itemToCreate[optionIdentifier];
				});

				let existingSelectionWithFilterValue;

				if (selectedValue) {
					if (isMulti) {
						existingSelectionWithFilterValue = selectedValue.find(selection => {
							return selection[optionIdentifier] === itemToCreate[optionIdentifier];
						});
					} else if (selectedValue[optionIdentifier] === itemToCreate[optionIdentifier]) {
						existingSelectionWithFilterValue = selectedValue;
					}
				}

				if (!existingItemWithFilterValue && !existingSelectionWithFilterValue) {
					_filteredItems = [..._filteredItems, itemToCreate];
				}
			}

			setList(_filteredItems);
		}

		prev_selectedValue = selectedValue;
		prev_listOpen = listOpen;
		prev_filterText = filterText;
		prev_isFocused = isFocused;
		prev_filteredItems = filteredItems;
	});

	function checkSelectedValueForDuplicates() {
		let noDuplicates = true;

		if (selectedValue) {
			const ids = [];
			const uniqueValues = [];

			selectedValue.forEach(val => {
				if (!ids.includes(val[optionIdentifier])) {
					ids.push(val[optionIdentifier]);
					uniqueValues.push(val);
				} else {
					noDuplicates = false;
				}
			});

			if (!noDuplicates) $$invalidate(0, selectedValue = uniqueValues);
		}

		return noDuplicates;
	}

	function findItem(selection) {
		let matchTo = selection
		? selection[optionIdentifier]
		: selectedValue[optionIdentifier];

		return items.find(item => item[optionIdentifier] === matchTo);
	}

	function updateSelectedValueDisplay(items) {
		if (!items || items.length === 0 || items.some(item => typeof item !== "object")) return;

		if (!selectedValue || (isMulti
		? selectedValue.some(selection => !selection || !selection[optionIdentifier])
		: !selectedValue[optionIdentifier])) return;

		if (Array.isArray(selectedValue)) {
			$$invalidate(0, selectedValue = selectedValue.map(selection => findItem(selection) || selection));
		} else {
			$$invalidate(0, selectedValue = findItem() || selectedValue);
		}
	}

	async function setList(items) {
		await (0,svelte__WEBPACK_IMPORTED_MODULE_1__.tick)();
		if (!listOpen) return;
		if (list) return list.$set({ items });
		if (loadOptions && getItemsHasInvoked && items.length > 0) loadList();
	}

	function handleMultiItemClear(event) {
		const { detail } = event;
		const itemToRemove = selectedValue[detail ? detail.i : selectedValue.length - 1];

		if (selectedValue.length === 1) {
			$$invalidate(0, selectedValue = undefined);
		} else {
			$$invalidate(0, selectedValue = selectedValue.filter(item => {
				return item !== itemToRemove;
			}));
		}

		dispatch("clear", itemToRemove);
		getPosition();
	}

	async function getPosition() {
		await (0,svelte__WEBPACK_IMPORTED_MODULE_1__.tick)();
		if (!target || !container) return;
		const { top, height, width } = container.getBoundingClientRect();
		target.style["min-width"] = `${width}px`;
		target.style.width = `${listAutoWidth ? "auto" : "100%"}`;
		target.style.left = "0";

		if (listPlacement === "top") {
			target.style.bottom = `${height + 5}px`;
		} else {
			target.style.top = `${height + 5}px`;
		}

		target = target;

		if (listPlacement === "auto" && (0,_utils_isOutOfViewport__WEBPACK_IMPORTED_MODULE_6__["default"])(target).bottom) {
			target.style.top = ``;
			target.style.bottom = `${height + 5}px`;
		}

		target.style.visibility = "";
	}

	function handleKeyDown(e) {
		if (!isFocused) return;

		switch (e.key) {
			case "ArrowDown":
				e.preventDefault();
				$$invalidate(37, listOpen = true);
				$$invalidate(25, activeSelectedValue = undefined);
				break;
			case "ArrowUp":
				e.preventDefault();
				$$invalidate(37, listOpen = true);
				$$invalidate(25, activeSelectedValue = undefined);
				break;
			case "Tab":
				if (!listOpen) $$invalidate(4, isFocused = false);
				break;
			case "Backspace":
				if (!isMulti || filterText.length > 0) return;
				if (isMulti && selectedValue && selectedValue.length > 0) {
					handleMultiItemClear(activeSelectedValue !== undefined
					? activeSelectedValue
					: selectedValue.length - 1);

					if (activeSelectedValue === 0 || activeSelectedValue === undefined) break;

					$$invalidate(25, activeSelectedValue = selectedValue.length > activeSelectedValue
					? activeSelectedValue - 1
					: undefined);
				}
				break;
			case "ArrowLeft":
				if (list) list.$set({ hoverItemIndex: -1 });
				if (!isMulti || filterText.length > 0) return;
				if (activeSelectedValue === undefined) {
					$$invalidate(25, activeSelectedValue = selectedValue.length - 1);
				} else if (selectedValue.length > activeSelectedValue && activeSelectedValue !== 0) {
					$$invalidate(25, activeSelectedValue -= 1);
				}
				break;
			case "ArrowRight":
				if (list) list.$set({ hoverItemIndex: -1 });
				if (!isMulti || filterText.length > 0 || activeSelectedValue === undefined) return;
				if (activeSelectedValue === selectedValue.length - 1) {
					$$invalidate(25, activeSelectedValue = undefined);
				} else if (activeSelectedValue < selectedValue.length - 1) {
					$$invalidate(25, activeSelectedValue += 1);
				}
				break;
		}
	}

	function handleFocus() {
		$$invalidate(4, isFocused = true);
		if (input) input.focus();
	}

	function removeList() {
		resetFilter();
		$$invalidate(25, activeSelectedValue = undefined);
		if (!list) return;
		list.$destroy();
		$$invalidate(36, list = undefined);
		if (!target) return;
		if (target.parentNode) target.parentNode.removeChild(target);
		target = undefined;
		$$invalidate(36, list);
		target = target;
	}

	function handleWindowClick(event) {
		if (!container) return;

		const eventTarget = event.path && event.path.length > 0
		? event.path[0]
		: event.target;

		if (container.contains(eventTarget)) return;
		$$invalidate(4, isFocused = false);
		$$invalidate(37, listOpen = false);
		$$invalidate(25, activeSelectedValue = undefined);
		if (input) input.blur();
	}

	function handleClick() {
		if (isDisabled) return;
		$$invalidate(4, isFocused = true);
		$$invalidate(37, listOpen = !listOpen);
	}

	function handleClear() {
		$$invalidate(0, selectedValue = undefined);
		$$invalidate(37, listOpen = false);
		dispatch("clear", selectedValue);
		handleFocus();
	}

	async function loadList() {
		await (0,svelte__WEBPACK_IMPORTED_MODULE_1__.tick)();
		if (target && list) return;

		const data = {
			Item,
			filterText,
			optionIdentifier,
			noOptionsMessage,
			hideEmptyState,
			isVirtualList,
			selectedValue,
			isMulti,
			getGroupHeaderLabel,
			items: filteredItems,
			itemHeight
		};

		if (getOptionLabel) {
			data.getOptionLabel = getOptionLabel;
		}

		target = document.createElement("div");

		Object.assign(target.style, {
			position: "absolute",
			"z-index": 2,
			visibility: "hidden"
		});

		$$invalidate(36, list);
		target = target;
		if (container) container.appendChild(target);
		$$invalidate(36, list = new _List_svelte__WEBPACK_IMPORTED_MODULE_2__["default"]({ target, props: data }));

		list.$on("itemSelected", event => {
			const { detail } = event;

			if (detail) {
				const item = Object.assign({}, detail);

				if (!item.isGroupHeader || item.isSelectable) {
					if (isMulti) {
						$$invalidate(0, selectedValue = selectedValue ? selectedValue.concat([item]) : [item]);
					} else {
						$$invalidate(0, selectedValue = item);
					}

					resetFilter();
					(($$invalidate(0, selectedValue), $$invalidate(48, optionIdentifier)), $$invalidate(8, isMulti));

					setTimeout(() => {
						$$invalidate(37, listOpen = false);
						$$invalidate(25, activeSelectedValue = undefined);
					});
				}
			}
		});

		list.$on("itemCreated", event => {
			const { detail } = event;

			if (isMulti) {
				$$invalidate(0, selectedValue = selectedValue || []);
				$$invalidate(0, selectedValue = [...selectedValue, createItem(detail)]);
			} else {
				$$invalidate(0, selectedValue = createItem(detail));
			}

			dispatch('itemCreated', detail);
			$$invalidate(1, filterText = "");
			$$invalidate(37, listOpen = false);
			$$invalidate(25, activeSelectedValue = undefined);
			resetFilter();
		});

		list.$on("closeList", () => {
			$$invalidate(37, listOpen = false);
		});

		($$invalidate(36, list), target = target);
		getPosition();
	}

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		if (isFocused) input.focus();
		if (listOpen) loadList();

		if (items && items.length > 0) {
			$$invalidate(60, originalItemsClone = JSON.stringify(items));
		}
	});

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onDestroy)(() => {
		removeList();
	});

	function input_1_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			input = $$value;
			$$invalidate(3, input);
		});
	}

	function input_1_input_handler() {
		filterText = this.value;
		$$invalidate(1, filterText);
	}

	function input_1_binding_1($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			input = $$value;
			$$invalidate(3, input);
		});
	}

	function input_1_input_handler_1() {
		filterText = this.value;
		$$invalidate(1, filterText);
	}

	function div_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			container = $$value;
			$$invalidate(2, container);
		});
	}

	$$self.$$set = $$props => {
		if ('container' in $$props) $$invalidate(2, container = $$props.container);
		if ('input' in $$props) $$invalidate(3, input = $$props.input);
		if ('Item' in $$props) $$invalidate(39, Item = $$props.Item);
		if ('Selection' in $$props) $$invalidate(6, Selection = $$props.Selection);
		if ('MultiSelection' in $$props) $$invalidate(7, MultiSelection = $$props.MultiSelection);
		if ('isMulti' in $$props) $$invalidate(8, isMulti = $$props.isMulti);
		if ('multiFullItemClearable' in $$props) $$invalidate(9, multiFullItemClearable = $$props.multiFullItemClearable);
		if ('isDisabled' in $$props) $$invalidate(10, isDisabled = $$props.isDisabled);
		if ('isCreatable' in $$props) $$invalidate(40, isCreatable = $$props.isCreatable);
		if ('isFocused' in $$props) $$invalidate(4, isFocused = $$props.isFocused);
		if ('selectedValue' in $$props) $$invalidate(0, selectedValue = $$props.selectedValue);
		if ('filterText' in $$props) $$invalidate(1, filterText = $$props.filterText);
		if ('placeholder' in $$props) $$invalidate(41, placeholder = $$props.placeholder);
		if ('items' in $$props) $$invalidate(35, items = $$props.items);
		if ('itemFilter' in $$props) $$invalidate(42, itemFilter = $$props.itemFilter);
		if ('groupBy' in $$props) $$invalidate(43, groupBy = $$props.groupBy);
		if ('groupFilter' in $$props) $$invalidate(44, groupFilter = $$props.groupFilter);
		if ('isGroupHeaderSelectable' in $$props) $$invalidate(45, isGroupHeaderSelectable = $$props.isGroupHeaderSelectable);
		if ('getGroupHeaderLabel' in $$props) $$invalidate(46, getGroupHeaderLabel = $$props.getGroupHeaderLabel);
		if ('getOptionLabel' in $$props) $$invalidate(47, getOptionLabel = $$props.getOptionLabel);
		if ('optionIdentifier' in $$props) $$invalidate(48, optionIdentifier = $$props.optionIdentifier);
		if ('loadOptions' in $$props) $$invalidate(49, loadOptions = $$props.loadOptions);
		if ('hasError' in $$props) $$invalidate(11, hasError = $$props.hasError);
		if ('containerStyles' in $$props) $$invalidate(12, containerStyles = $$props.containerStyles);
		if ('getSelectionLabel' in $$props) $$invalidate(13, getSelectionLabel = $$props.getSelectionLabel);
		if ('createGroupHeaderItem' in $$props) $$invalidate(50, createGroupHeaderItem = $$props.createGroupHeaderItem);
		if ('createItem' in $$props) $$invalidate(51, createItem = $$props.createItem);
		if ('isSearchable' in $$props) $$invalidate(14, isSearchable = $$props.isSearchable);
		if ('inputStyles' in $$props) $$invalidate(15, inputStyles = $$props.inputStyles);
		if ('isClearable' in $$props) $$invalidate(16, isClearable = $$props.isClearable);
		if ('isWaiting' in $$props) $$invalidate(5, isWaiting = $$props.isWaiting);
		if ('listPlacement' in $$props) $$invalidate(52, listPlacement = $$props.listPlacement);
		if ('listOpen' in $$props) $$invalidate(37, listOpen = $$props.listOpen);
		if ('list' in $$props) $$invalidate(36, list = $$props.list);
		if ('isVirtualList' in $$props) $$invalidate(53, isVirtualList = $$props.isVirtualList);
		if ('loadOptionsInterval' in $$props) $$invalidate(54, loadOptionsInterval = $$props.loadOptionsInterval);
		if ('noOptionsMessage' in $$props) $$invalidate(55, noOptionsMessage = $$props.noOptionsMessage);
		if ('hideEmptyState' in $$props) $$invalidate(56, hideEmptyState = $$props.hideEmptyState);
		if ('filteredItems' in $$props) $$invalidate(38, filteredItems = $$props.filteredItems);
		if ('inputAttributes' in $$props) $$invalidate(57, inputAttributes = $$props.inputAttributes);
		if ('listAutoWidth' in $$props) $$invalidate(58, listAutoWidth = $$props.listAutoWidth);
		if ('itemHeight' in $$props) $$invalidate(59, itemHeight = $$props.itemHeight);
		if ('Icon' in $$props) $$invalidate(17, Icon = $$props.Icon);
		if ('iconProps' in $$props) $$invalidate(18, iconProps = $$props.iconProps);
		if ('showChevron' in $$props) $$invalidate(19, showChevron = $$props.showChevron);
		if ('showIndicator' in $$props) $$invalidate(20, showIndicator = $$props.showIndicator);
		if ('containerClasses' in $$props) $$invalidate(21, containerClasses = $$props.containerClasses);
		if ('indicatorSvg' in $$props) $$invalidate(22, indicatorSvg = $$props.indicatorSvg);
		if ('ClearIcon' in $$props) $$invalidate(23, ClearIcon = $$props.ClearIcon);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty[0] & /*isDisabled*/ 1024) {
			$: disabled = isDisabled;
		}

		if ($$self.$$.dirty[1] & /*items*/ 16) {
			$: updateSelectedValueDisplay(items);
		}

		if ($$self.$$.dirty[0] & /*selectedValue, isMulti*/ 257 | $$self.$$.dirty[1] & /*optionIdentifier*/ 131072) {
			$: {
				if (typeof selectedValue === "string") {
					$$invalidate(0, selectedValue = {
						[optionIdentifier]: selectedValue,
						label: selectedValue
					});
				} else if (isMulti && Array.isArray(selectedValue) && selectedValue.length > 0) {
					$$invalidate(0, selectedValue = selectedValue.map(item => typeof item === "string"
					? { value: item, label: item }
					: item));
				}
			}
		}

		if ($$self.$$.dirty[1] & /*noOptionsMessage, list*/ 16777248) {
			$: {
				if (noOptionsMessage && list) list.$set({ noOptionsMessage });
			}
		}

		if ($$self.$$.dirty[0] & /*selectedValue, filterText*/ 3) {
			$: $$invalidate(28, showSelectedItem = selectedValue && filterText.length === 0);
		}

		if ($$self.$$.dirty[0] & /*selectedValue*/ 1 | $$self.$$.dirty[1] & /*placeholder*/ 1024) {
			$: $$invalidate(27, placeholderText = selectedValue ? "" : placeholder);
		}

		if ($$self.$$.dirty[0] & /*isSearchable*/ 16384 | $$self.$$.dirty[1] & /*inputAttributes*/ 67108864) {
			$: {
				$$invalidate(26, _inputAttributes = Object.assign(
					{
						autocomplete: "off",
						autocorrect: "off",
						spellcheck: false
					},
					inputAttributes
				));

				if (!isSearchable) {
					$$invalidate(26, _inputAttributes.readonly = true, _inputAttributes);
				}
			}
		}

		if ($$self.$$.dirty[0] & /*filterText, isMulti, selectedValue*/ 259 | $$self.$$.dirty[1] & /*items, loadOptions, originalItemsClone, optionIdentifier, itemFilter, getOptionLabel, groupBy, createGroupHeaderItem, isGroupHeaderSelectable, groupFilter*/ 537884688) {
			$: {
				let _filteredItems;
				let _items = items;

				if (items && items.length > 0 && typeof items[0] !== "object") {
					_items = items.map((item, index) => {
						return { index, value: item, label: item };
					});
				}

				if (loadOptions && filterText.length === 0 && originalItemsClone) {
					_filteredItems = JSON.parse(originalItemsClone);
					_items = JSON.parse(originalItemsClone);
				} else {
					_filteredItems = loadOptions
					? filterText.length === 0 ? [] : _items
					: _items.filter(item => {
							let keepItem = true;

							if (isMulti && selectedValue) {
								keepItem = !selectedValue.some(value => {
									return value[optionIdentifier] === item[optionIdentifier];
								});
							}

							if (!keepItem) return false;
							if (filterText.length < 1) return true;
							return itemFilter(getOptionLabel(item, filterText), filterText, item);
						});
				}

				if (groupBy) {
					const groupValues = [];
					const groups = {};

					_filteredItems.forEach(item => {
						const groupValue = groupBy(item);

						if (!groupValues.includes(groupValue)) {
							groupValues.push(groupValue);
							groups[groupValue] = [];

							if (groupValue) {
								groups[groupValue].push(Object.assign(createGroupHeaderItem(groupValue, item), {
									id: groupValue,
									isGroupHeader: true,
									isSelectable: isGroupHeaderSelectable
								}));
							}
						}

						groups[groupValue].push(Object.assign({ isGroupItem: !!groupValue }, item));
					});

					const sortedGroupedItems = [];

					groupFilter(groupValues).forEach(groupValue => {
						sortedGroupedItems.push(...groups[groupValue]);
					});

					$$invalidate(38, filteredItems = sortedGroupedItems);
				} else {
					$$invalidate(38, filteredItems = _filteredItems);
				}
			}
		}
	};

	return [
		selectedValue,
		filterText,
		container,
		input,
		isFocused,
		isWaiting,
		Selection,
		MultiSelection,
		isMulti,
		multiFullItemClearable,
		isDisabled,
		hasError,
		containerStyles,
		getSelectionLabel,
		isSearchable,
		inputStyles,
		isClearable,
		Icon,
		iconProps,
		showChevron,
		showIndicator,
		containerClasses,
		indicatorSvg,
		ClearIcon,
		handleClear,
		activeSelectedValue,
		_inputAttributes,
		placeholderText,
		showSelectedItem,
		handleMultiItemClear,
		getPosition,
		handleKeyDown,
		handleFocus,
		handleWindowClick,
		handleClick,
		items,
		list,
		listOpen,
		filteredItems,
		Item,
		isCreatable,
		placeholder,
		itemFilter,
		groupBy,
		groupFilter,
		isGroupHeaderSelectable,
		getGroupHeaderLabel,
		getOptionLabel,
		optionIdentifier,
		loadOptions,
		createGroupHeaderItem,
		createItem,
		listPlacement,
		isVirtualList,
		loadOptionsInterval,
		noOptionsMessage,
		hideEmptyState,
		inputAttributes,
		listAutoWidth,
		itemHeight,
		originalItemsClone,
		input_1_binding,
		input_1_input_handler,
		input_1_binding_1,
		input_1_input_handler_1,
		div_binding
	];
}

class Select extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(
			this,
			options,
			instance,
			create_fragment,
			svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal,
			{
				container: 2,
				input: 3,
				Item: 39,
				Selection: 6,
				MultiSelection: 7,
				isMulti: 8,
				multiFullItemClearable: 9,
				isDisabled: 10,
				isCreatable: 40,
				isFocused: 4,
				selectedValue: 0,
				filterText: 1,
				placeholder: 41,
				items: 35,
				itemFilter: 42,
				groupBy: 43,
				groupFilter: 44,
				isGroupHeaderSelectable: 45,
				getGroupHeaderLabel: 46,
				getOptionLabel: 47,
				optionIdentifier: 48,
				loadOptions: 49,
				hasError: 11,
				containerStyles: 12,
				getSelectionLabel: 13,
				createGroupHeaderItem: 50,
				createItem: 51,
				isSearchable: 14,
				inputStyles: 15,
				isClearable: 16,
				isWaiting: 5,
				listPlacement: 52,
				listOpen: 37,
				list: 36,
				isVirtualList: 53,
				loadOptionsInterval: 54,
				noOptionsMessage: 55,
				hideEmptyState: 56,
				filteredItems: 38,
				inputAttributes: 57,
				listAutoWidth: 58,
				itemHeight: 59,
				Icon: 17,
				iconProps: 18,
				showChevron: 19,
				showIndicator: 20,
				containerClasses: 21,
				indicatorSvg: 22,
				ClearIcon: 23,
				handleClear: 24
			},
			null,
			[-1, -1, -1]
		);
	}

	get handleClear() {
		return this.$$.ctx[24];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Select);




/***/ }),

/***/ "./node_modules/svelte-select/src/Selection.svelte":
/*!*********************************************************!*\
  !*** ./node_modules/svelte-select/src/Selection.svelte ***!
  \*********************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_select_src_Selection_svelte_29_css_svelte_loader_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_Selection_svelte_29_css_D_www_sipro_spa_node_modules_svelte_select_src_Selection_svelte__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-select/src/Selection.svelte.29.css!=!svelte-loader?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Selection.svelte.29.css!./node_modules/svelte-select/src/Selection.svelte */ "./node_modules/svelte-select/src/Selection.svelte.29.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/Selection.svelte.29.css!./node_modules/svelte-select/src/Selection.svelte");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\svelte-select\src\Selection.svelte generated by Svelte v3.48.0 */


function create_fragment(ctx) {
	let div;
	let raw_value = /*getSelectionLabel*/ ctx[0](/*item*/ ctx[1]) + "";

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "selection svelte-ch6bh7");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			div.innerHTML = raw_value;
		},
		p(ctx, [dirty]) {
			if (dirty & /*getSelectionLabel, item*/ 3 && raw_value !== (raw_value = /*getSelectionLabel*/ ctx[0](/*item*/ ctx[1]) + "")) div.innerHTML = raw_value;;
		},
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let { getSelectionLabel = undefined } = $$props;
	let { item = undefined } = $$props;

	$$self.$$set = $$props => {
		if ('getSelectionLabel' in $$props) $$invalidate(0, getSelectionLabel = $$props.getSelectionLabel);
		if ('item' in $$props) $$invalidate(1, item = $$props.item);
	};

	return [getSelectionLabel, item];
}

class Selection extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { getSelectionLabel: 0, item: 1 });
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Selection);




/***/ }),

/***/ "./node_modules/svelte-select/src/VirtualList.svelte":
/*!***********************************************************!*\
  !*** ./node_modules/svelte-select/src/VirtualList.svelte ***!
  \***********************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_select_src_VirtualList_svelte_55_css_svelte_loader_cssPath_D_www_sipro_spa_node_modules_svelte_select_src_VirtualList_svelte_55_css_D_www_sipro_spa_node_modules_svelte_select_src_VirtualList_svelte__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./node_modules/svelte-select/src/VirtualList.svelte.55.css!=!svelte-loader?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/VirtualList.svelte.55.css!./node_modules/svelte-select/src/VirtualList.svelte */ "./node_modules/svelte-select/src/VirtualList.svelte.55.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/node_modules/svelte-select/src/VirtualList.svelte.55.css!./node_modules/svelte-select/src/VirtualList.svelte");
/* module decorator */ module = __webpack_require__.hmd(module);
/* node_modules\svelte-select\src\VirtualList.svelte generated by Svelte v3.48.0 */




function get_each_context(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[23] = list[i];
	return child_ctx;
}

const get_default_slot_changes = dirty => ({
	item: dirty & /*visible*/ 32,
	i: dirty & /*visible*/ 32,
	hoverItemIndex: dirty & /*hoverItemIndex*/ 2
});

const get_default_slot_context = ctx => ({
	item: /*row*/ ctx[23].data,
	i: /*row*/ ctx[23].index,
	hoverItemIndex: /*hoverItemIndex*/ ctx[1]
});

// (160:57) Missing template
function fallback_block(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Missing template");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (158:2) {#each visible as row (row.index)}
function create_each_block(key_1, ctx) {
	let svelte_virtual_list_row;
	let t;
	let current;
	const default_slot_template = /*#slots*/ ctx[15].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[14], get_default_slot_context);
	const default_slot_or_fallback = default_slot || fallback_block(ctx);

	return {
		key: key_1,
		first: null,
		c() {
			svelte_virtual_list_row = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("svelte-virtual-list-row");
			if (default_slot_or_fallback) default_slot_or_fallback.c();
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_custom_element_data)(svelte_virtual_list_row, "class", "svelte-p6ehlv");
			this.first = svelte_virtual_list_row;
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, svelte_virtual_list_row, anchor);

			if (default_slot_or_fallback) {
				default_slot_or_fallback.m(svelte_virtual_list_row, null);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(svelte_virtual_list_row, t);
			current = true;
		},
		p(new_ctx, dirty) {
			ctx = new_ctx;

			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope, visible, hoverItemIndex*/ 16418)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[14],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[14])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[14], dirty, get_default_slot_changes),
						get_default_slot_context
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot_or_fallback, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot_or_fallback, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(svelte_virtual_list_row);
			if (default_slot_or_fallback) default_slot_or_fallback.d(detaching);
		}
	};
}

function create_fragment(ctx) {
	let svelte_virtual_list_viewport;
	let svelte_virtual_list_contents;
	let each_blocks = [];
	let each_1_lookup = new Map();
	let svelte_virtual_list_viewport_resize_listener;
	let current;
	let mounted;
	let dispose;
	let each_value = /*visible*/ ctx[5];
	const get_key = ctx => /*row*/ ctx[23].index;

	for (let i = 0; i < each_value.length; i += 1) {
		let child_ctx = get_each_context(ctx, each_value, i);
		let key = get_key(child_ctx);
		each_1_lookup.set(key, each_blocks[i] = create_each_block(key, child_ctx));
	}

	return {
		c() {
			svelte_virtual_list_viewport = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("svelte-virtual-list-viewport");
			svelte_virtual_list_contents = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("svelte-virtual-list-contents");

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].c();
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(svelte_virtual_list_contents, "padding-top", /*top*/ ctx[6] + "px");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(svelte_virtual_list_contents, "padding-bottom", /*bottom*/ ctx[7] + "px");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_custom_element_data)(svelte_virtual_list_contents, "class", "svelte-p6ehlv");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(svelte_virtual_list_viewport, "height", /*height*/ ctx[0]);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_custom_element_data)(svelte_virtual_list_viewport, "class", "svelte-p6ehlv");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_render_callback)(() => /*svelte_virtual_list_viewport_elementresize_handler*/ ctx[18].call(svelte_virtual_list_viewport));
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, svelte_virtual_list_viewport, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(svelte_virtual_list_viewport, svelte_virtual_list_contents);

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].m(svelte_virtual_list_contents, null);
			}

			/*svelte_virtual_list_contents_binding*/ ctx[16](svelte_virtual_list_contents);
			/*svelte_virtual_list_viewport_binding*/ ctx[17](svelte_virtual_list_viewport);
			svelte_virtual_list_viewport_resize_listener = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_resize_listener)(svelte_virtual_list_viewport, /*svelte_virtual_list_viewport_elementresize_handler*/ ctx[18].bind(svelte_virtual_list_viewport));
			current = true;

			if (!mounted) {
				dispose = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(svelte_virtual_list_viewport, "scroll", /*handle_scroll*/ ctx[8]);
				mounted = true;
			}
		},
		p(ctx, [dirty]) {
			if (dirty & /*$$scope, visible, hoverItemIndex*/ 16418) {
				each_value = /*visible*/ ctx[5];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();
				each_blocks = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_keyed_each)(each_blocks, dirty, get_key, 1, ctx, each_value, each_1_lookup, svelte_virtual_list_contents, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.outro_and_destroy_block, create_each_block, null, get_each_context);
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			if (!current || dirty & /*top*/ 64) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(svelte_virtual_list_contents, "padding-top", /*top*/ ctx[6] + "px");
			}

			if (!current || dirty & /*bottom*/ 128) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(svelte_virtual_list_contents, "padding-bottom", /*bottom*/ ctx[7] + "px");
			}

			if (!current || dirty & /*height*/ 1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(svelte_virtual_list_viewport, "height", /*height*/ ctx[0]);
			}
		},
		i(local) {
			if (current) return;

			for (let i = 0; i < each_value.length; i += 1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(each_blocks[i]);
			}

			current = true;
		},
		o(local) {
			for (let i = 0; i < each_blocks.length; i += 1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(each_blocks[i]);
			}

			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(svelte_virtual_list_viewport);

			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].d();
			}

			/*svelte_virtual_list_contents_binding*/ ctx[16](null);
			/*svelte_virtual_list_viewport_binding*/ ctx[17](null);
			svelte_virtual_list_viewport_resize_listener();
			mounted = false;
			dispose();
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let { $$slots: slots = {}, $$scope } = $$props;
	let { items = undefined } = $$props;
	let { height = '100%' } = $$props;
	let { itemHeight = 40 } = $$props;
	let { hoverItemIndex = 0 } = $$props;
	let { start = 0 } = $$props;
	let { end = 0 } = $$props;

	// local state
	let height_map = [];

	let rows;
	let viewport;
	let contents;
	let viewport_height = 0;
	let visible;
	let mounted;
	let top = 0;
	let bottom = 0;
	let average_height;

	async function refresh(items, viewport_height, itemHeight) {
		const { scrollTop } = viewport;
		await (0,svelte__WEBPACK_IMPORTED_MODULE_1__.tick)(); // wait until the DOM is up to date
		let content_height = top - scrollTop;
		let i = start;

		while (content_height < viewport_height && i < items.length) {
			let row = rows[i - start];

			if (!row) {
				$$invalidate(10, end = i + 1);
				await (0,svelte__WEBPACK_IMPORTED_MODULE_1__.tick)(); // render the newly visible row
				row = rows[i - start];
			}

			const row_height = height_map[i] = itemHeight || row.offsetHeight;
			content_height += row_height;
			i += 1;
		}

		$$invalidate(10, end = i);
		const remaining = items.length - end;
		average_height = (top + content_height) / end;
		$$invalidate(7, bottom = remaining * average_height);
		height_map.length = items.length;
		$$invalidate(3, viewport.scrollTop = 0, viewport);
	}

	async function handle_scroll() {
		const { scrollTop } = viewport;
		const old_start = start;

		for (let v = 0; v < rows.length; v += 1) {
			height_map[start + v] = itemHeight || rows[v].offsetHeight;
		}

		let i = 0;
		let y = 0;

		while (i < items.length) {
			const row_height = height_map[i] || average_height;

			if (y + row_height > scrollTop) {
				$$invalidate(9, start = i);
				$$invalidate(6, top = y);
				break;
			}

			y += row_height;
			i += 1;
		}

		while (i < items.length) {
			y += height_map[i] || average_height;
			i += 1;
			if (y > scrollTop + viewport_height) break;
		}

		$$invalidate(10, end = i);
		const remaining = items.length - end;
		average_height = y / end;
		while (i < items.length) height_map[i++] = average_height;
		$$invalidate(7, bottom = remaining * average_height);

		// prevent jumping if we scrolled up into unknown territory
		if (start < old_start) {
			await (0,svelte__WEBPACK_IMPORTED_MODULE_1__.tick)();
			let expected_height = 0;
			let actual_height = 0;

			for (let i = start; i < old_start; i += 1) {
				if (rows[i - start]) {
					expected_height += height_map[i];
					actual_height += itemHeight || rows[i - start].offsetHeight;
				}
			}

			const d = actual_height - expected_height;
			viewport.scrollTo(0, scrollTop + d);
		}
	} // TODO if we overestimated the space these
	// rows would occupy we may need to add some

	// more. maybe we can just call handle_scroll again?
	// trigger initial refresh
	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		rows = contents.getElementsByTagName('svelte-virtual-list-row');
		$$invalidate(13, mounted = true);
	});

	function svelte_virtual_list_contents_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			contents = $$value;
			$$invalidate(4, contents);
		});
	}

	function svelte_virtual_list_viewport_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			viewport = $$value;
			$$invalidate(3, viewport);
		});
	}

	function svelte_virtual_list_viewport_elementresize_handler() {
		viewport_height = this.offsetHeight;
		$$invalidate(2, viewport_height);
	}

	$$self.$$set = $$props => {
		if ('items' in $$props) $$invalidate(11, items = $$props.items);
		if ('height' in $$props) $$invalidate(0, height = $$props.height);
		if ('itemHeight' in $$props) $$invalidate(12, itemHeight = $$props.itemHeight);
		if ('hoverItemIndex' in $$props) $$invalidate(1, hoverItemIndex = $$props.hoverItemIndex);
		if ('start' in $$props) $$invalidate(9, start = $$props.start);
		if ('end' in $$props) $$invalidate(10, end = $$props.end);
		if ('$$scope' in $$props) $$invalidate(14, $$scope = $$props.$$scope);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty & /*items, start, end*/ 3584) {
			$: $$invalidate(5, visible = items.slice(start, end).map((data, i) => {
				return { index: i + start, data };
			}));
		}

		if ($$self.$$.dirty & /*mounted, items, viewport_height, itemHeight*/ 14340) {
			// whenever `items` changes, invalidate the current heightmap
			$: if (mounted) refresh(items, viewport_height, itemHeight);
		}
	};

	return [
		height,
		hoverItemIndex,
		viewport_height,
		viewport,
		contents,
		visible,
		top,
		bottom,
		handle_scroll,
		start,
		end,
		items,
		itemHeight,
		mounted,
		$$scope,
		slots,
		svelte_virtual_list_contents_binding,
		svelte_virtual_list_viewport_binding,
		svelte_virtual_list_viewport_elementresize_handler
	];
}

class VirtualList extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			items: 11,
			height: 0,
			itemHeight: 12,
			hoverItemIndex: 1,
			start: 9,
			end: 10
		});
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (VirtualList);




/***/ }),

/***/ "./resources/js/Pages/SemillerosInvestigacion/Form.svelte":
/*!****************************************************************!*\
  !*** ./resources/js/Pages/SemillerosInvestigacion/Form.svelte ***!
  \****************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _Shared_Label__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/Shared/Label */ "./resources/js/Shared/Label.svelte");
/* harmony import */ var _Shared_Input__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/Shared/Input */ "./resources/js/Shared/Input.svelte");
/* harmony import */ var _Shared_Textarea__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @/Shared/Textarea */ "./resources/js/Shared/Textarea.svelte");
/* harmony import */ var _Shared_SelectMulti__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @/Shared/SelectMulti */ "./resources/js/Shared/SelectMulti.svelte");
/* harmony import */ var _Shared_Select__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @/Shared/Select */ "./resources/js/Shared/Select.svelte");
/* harmony import */ var _Shared_InfoMessage__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @/Shared/InfoMessage */ "./resources/js/Shared/InfoMessage.svelte");
/* harmony import */ var _Shared_LoadingButton__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @/Shared/LoadingButton */ "./resources/js/Shared/LoadingButton.svelte");
/* harmony import */ var _Shared_Button__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @/Shared/Button */ "./resources/js/Shared/Button.svelte");
/* harmony import */ var _Shared_Dialog__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @/Shared/Dialog */ "./resources/js/Shared/Dialog.svelte");
/* harmony import */ var _Shared_Export2Word__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @/Shared/Export2Word */ "./resources/js/Shared/Export2Word.svelte");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Pages\SemillerosInvestigacion\Form.svelte generated by Svelte v3.48.0 */













function get_each_context(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[37] = list[i];
	return child_ctx;
}

function get_each_context_1(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[40] = list[i];
	return child_ctx;
}

function get_each_context_2(ctx, list, i) {
	const child_ctx = ctx.slice();
	child_ctx[43] = list[i];
	return child_ctx;
}

// (41:8) {#if semilleroInvestigacion}
function create_if_block_7(ctx) {
	let div;
	let label;
	let t;
	let input;
	let updating_value;
	let current;

	label = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: { labelFor: "nombre", value: "Código" }
		});

	function input_value_binding(value) {
		/*input_value_binding*/ ctx[15](value);
	}

	let input_props = {
		disabled: true,
		id: "codigo",
		type: "text",
		class: "mt-1",
		error: /*errors*/ ctx[2].codigo,
		required: true
	};

	if (/*semilleroInvestigacion*/ ctx[0].codigo !== void 0) {
		input_props.value = /*semilleroInvestigacion*/ ctx[0].codigo;
	}

	input = new _Shared_Input__WEBPACK_IMPORTED_MODULE_2__["default"]({ props: input_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(input, 'value', input_value_binding));

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label.$$.fragment);
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(input.$$.fragment);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "mt-4");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label, div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(input, div, null);
			current = true;
		},
		p(ctx, dirty) {
			const input_changes = {};
			if (dirty[0] & /*errors*/ 4) input_changes.error = /*errors*/ ctx[2].codigo;

			if (!updating_value && dirty[0] & /*semilleroInvestigacion*/ 1) {
				updating_value = true;
				input_changes.value = /*semilleroInvestigacion*/ ctx[0].codigo;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value = false);
			}

			input.$set(input_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(input.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(input.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(input);
		}
	};
}

// (130:144) 
function create_if_block_6(ctx) {
	let infomessage;
	let current;

	infomessage = new _Shared_InfoMessage__WEBPACK_IMPORTED_MODULE_6__["default"]({
			props: {
				alertMsg: true,
				class: "mt-10",
				$$slots: { default: [create_default_slot_6] },
				$$scope: { ctx }
			}
		});

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(infomessage.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(infomessage, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const infomessage_changes = {};

			if (dirty[1] & /*$$scope*/ 32768) {
				infomessage_changes.$$scope = { dirty, ctx };
			}

			infomessage.$set(infomessage_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(infomessage.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(infomessage.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(infomessage, detaching);
		}
	};
}

// (125:8) {#if ($form.linea_investigacion?.value && programasFormacion.length > 0) || programasFormacionSemilleroInvestigacion?.length > 0}
function create_if_block_5(ctx) {
	let div;
	let label;
	let t;
	let selectmulti;
	let updating_selectedValue;
	let current;

	label = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				class: "mb-4",
				labelFor: "programas_formacion",
				value: "Programa(s) de formación"
			}
		});

	function selectmulti_selectedValue_binding(value) {
		/*selectmulti_selectedValue_binding*/ ctx[31](value);
	}

	let selectmulti_props = {
		id: "programas_formacion",
		items: /*arrayProgramasFormacion*/ ctx[13],
		isMulti: true,
		error: /*errors*/ ctx[2].programas_formacion,
		placeholder: "Programa(s) de formación",
		required: true
	};

	if (/*$form*/ ctx[10].programas_formacion !== void 0) {
		selectmulti_props.selectedValue = /*$form*/ ctx[10].programas_formacion;
	}

	selectmulti = new _Shared_SelectMulti__WEBPACK_IMPORTED_MODULE_4__["default"]({ props: selectmulti_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(selectmulti, 'selectedValue', selectmulti_selectedValue_binding));

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label.$$.fragment);
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(selectmulti.$$.fragment);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "mt-4");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label, div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(selectmulti, div, null);
			current = true;
		},
		p(ctx, dirty) {
			const selectmulti_changes = {};
			if (dirty[0] & /*arrayProgramasFormacion*/ 8192) selectmulti_changes.items = /*arrayProgramasFormacion*/ ctx[13];
			if (dirty[0] & /*errors*/ 4) selectmulti_changes.error = /*errors*/ ctx[2].programas_formacion;

			if (!updating_selectedValue && dirty[0] & /*$form*/ 1024) {
				updating_selectedValue = true;
				selectmulti_changes.selectedValue = /*$form*/ ctx[10].programas_formacion;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_selectedValue = false);
			}

			selectmulti.$set(selectmulti_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(selectmulti.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(selectmulti.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(selectmulti);
		}
	};
}

// (131:12) <InfoMessage alertMsg={true} class="mt-10">
function create_default_slot_6(ctx) {
	let p;

	return {
		c() {
			p = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			p.textContent = "La línea de investigación seleccionada no tiene programas de formación asociados, por favor antes de crear/modificar semilleros de investigación debe actualizar las líneas de investigación.";
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(p, "class", "mt-3 py-4 text-justify");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p, anchor);
		},
		p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p);
		}
	};
}

// (138:8) {#if semilleroInvestigacion}
function create_if_block_4(ctx) {
	let small;
	let svg;
	let path;
	let t0;
	let t1_value = /*semilleroInvestigacion*/ ctx[0].updated_at + "";
	let t1;

	return {
		c() {
			small = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("small");
			svg = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.svg_element)("svg");
			path = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.svg_element)("path");
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t1_value);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(path, "stroke-linecap", "round");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(path, "stroke-linejoin", "round");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(path, "d", "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "xmlns", "http://www.w3.org/2000/svg");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "class", "h-6 w-6 mr-2");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "fill", "none");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "viewBox", "0 0 24 24");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "stroke", "currentColor");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "stroke-width", "2");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(small, "class", "flex items-center text-violet-700");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, small, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(small, svg);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(svg, path);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(small, t0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(small, t1);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*semilleroInvestigacion*/ 1 && t1_value !== (t1_value = /*semilleroInvestigacion*/ ctx[0].updated_at + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, t1_value);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(small);
		}
	};
}

// (147:8) <Button on:click={() => (dialogGuardar = true)} class="ml-auto" type="button">
function create_default_slot_5(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("¿Desea guardar la información?");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (152:4) 
function create_title_slot(ctx) {
	let div1;

	return {
		c() {
			div1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			div1.innerHTML = `<div class="m-auto relative text-violet-600"><figure><img src="/images/megaphone.png" alt="" class="m-auto w-20"/></figure></div>`;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div1, "slot", "title");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div1, anchor);
		},
		p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div1);
		}
	};
}

// (160:8) <InfoMessage>
function create_default_slot_4(ctx) {
	let t0;
	let strong0;
	let t2;
	let strong1;
	let t4;
	let strong2;
	let t6;
	let a;
	let t8;
	let strong3;
	let t10;

	return {
		c() {
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Se recomienda que antes de dar clic en el botón ");
			strong0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong0.textContent = "Guardar";
			t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(" descargue el borrador en archivo Word. De esta manera si ocurre un error al guardar puede recuperar la información registrada. Luego de descargar el borrador de clic en el botón ");
			strong1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong1.textContent = "Guardar";
			t4 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(". Revise que se muestra un mensaje en verde que dice '");
			strong2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong2.textContent = "El recurso se ha modificado correctamente";
			t6 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("'. Si después de unos segundos no se muestra el mensaje y al recargar el aplicativo observa que la información no se ha guardado por favor envie un correo a ");
			a = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("a");
			a.textContent = "sgpssipro@sena.edu.co";
			t8 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("\r\n            desde una cuenta ");
			strong3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong3.textContent = "@sena.edu.co";
			t10 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(" y describa detalladamente lo ocurrido (Importante adjuntar el borrador e indicar el código del semillero).");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(a, "href", "mailto:sgpssipro@sena.edu.co");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(a, "class", "underline");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t0, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, strong0, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t2, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, strong1, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t4, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, strong2, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t6, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, a, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t8, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, strong3, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t10, anchor);
		},
		p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t0);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(strong0);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t2);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(strong1);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t4);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(strong2);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t6);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(a);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t8);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(strong3);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t10);
		}
	};
}

// (159:4) 
function create_header_info_slot(ctx) {
	let div;
	let infomessage;
	let current;

	infomessage = new _Shared_InfoMessage__WEBPACK_IMPORTED_MODULE_6__["default"]({
			props: {
				$$slots: { default: [create_default_slot_4] },
				$$scope: { ctx }
			}
		});

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(infomessage.$$.fragment);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "slot", "header-info");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "ml-10 shadow-md");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(infomessage, div, null);
			current = true;
		},
		p(ctx, dirty) {
			const infomessage_changes = {};

			if (dirty[1] & /*$$scope*/ 32768) {
				infomessage_changes.$$scope = { dirty, ctx };
			}

			infomessage.$set(infomessage_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(infomessage.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(infomessage.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(infomessage);
		}
	};
}

// (229:16) {:else}
function create_else_block_3(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Sin información registrada");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (224:16) {#if $form.redes_conocimiento}
function create_if_block_3(ctx) {
	let each_1_anchor;
	let each_value_2 = /*$form*/ ctx[10].redes_conocimiento;
	let each_blocks = [];

	for (let i = 0; i < each_value_2.length; i += 1) {
		each_blocks[i] = create_each_block_2(get_each_context_2(ctx, each_value_2, i));
	}

	return {
		c() {
			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].c();
			}

			each_1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].m(target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, each_1_anchor, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*$form*/ 1024) {
				each_value_2 = /*$form*/ ctx[10].redes_conocimiento;
				let i;

				for (i = 0; i < each_value_2.length; i += 1) {
					const child_ctx = get_each_context_2(ctx, each_value_2, i);

					if (each_blocks[i]) {
						each_blocks[i].p(child_ctx, dirty);
					} else {
						each_blocks[i] = create_each_block_2(child_ctx);
						each_blocks[i].c();
						each_blocks[i].m(each_1_anchor.parentNode, each_1_anchor);
					}
				}

				for (; i < each_blocks.length; i += 1) {
					each_blocks[i].d(1);
				}

				each_blocks.length = each_value_2.length;
			}
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_each)(each_blocks, detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(each_1_anchor);
		}
	};
}

// (225:20) {#each $form.redes_conocimiento as redConocimiento}
function create_each_block_2(ctx) {
	let br;
	let t0;
	let t1_value = /*redConocimiento*/ ctx[43].label + "";
	let t1;

	return {
		c() {
			br = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("br");
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t1_value);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, br, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t0, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t1, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*$form*/ 1024 && t1_value !== (t1_value = /*redConocimiento*/ ctx[43].label + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, t1_value);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(br);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t0);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t1);
		}
	};
}

// (242:16) {:else}
function create_else_block_2(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Sin información registrada");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (237:16) {#if $form.programas_formacion}
function create_if_block_2(ctx) {
	let each_1_anchor;
	let each_value_1 = /*$form*/ ctx[10].programas_formacion;
	let each_blocks = [];

	for (let i = 0; i < each_value_1.length; i += 1) {
		each_blocks[i] = create_each_block_1(get_each_context_1(ctx, each_value_1, i));
	}

	return {
		c() {
			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].c();
			}

			each_1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].m(target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, each_1_anchor, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*$form*/ 1024) {
				each_value_1 = /*$form*/ ctx[10].programas_formacion;
				let i;

				for (i = 0; i < each_value_1.length; i += 1) {
					const child_ctx = get_each_context_1(ctx, each_value_1, i);

					if (each_blocks[i]) {
						each_blocks[i].p(child_ctx, dirty);
					} else {
						each_blocks[i] = create_each_block_1(child_ctx);
						each_blocks[i].c();
						each_blocks[i].m(each_1_anchor.parentNode, each_1_anchor);
					}
				}

				for (; i < each_blocks.length; i += 1) {
					each_blocks[i].d(1);
				}

				each_blocks.length = each_value_1.length;
			}
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_each)(each_blocks, detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(each_1_anchor);
		}
	};
}

// (238:20) {#each $form.programas_formacion as programaFormacion}
function create_each_block_1(ctx) {
	let br;
	let t0;
	let t1_value = /*programaFormacion*/ ctx[40].label + "";
	let t1;

	return {
		c() {
			br = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("br");
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t1_value);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, br, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t0, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t1, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*$form*/ 1024 && t1_value !== (t1_value = /*programaFormacion*/ ctx[40].label + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, t1_value);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(br);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t0);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t1);
		}
	};
}

// (255:16) {:else}
function create_else_block_1(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Sin información registrada");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (250:16) {#if $form.lineas_investigacion}
function create_if_block_1(ctx) {
	let each_1_anchor;
	let each_value = /*$form*/ ctx[10].lineas_investigacion;
	let each_blocks = [];

	for (let i = 0; i < each_value.length; i += 1) {
		each_blocks[i] = create_each_block(get_each_context(ctx, each_value, i));
	}

	return {
		c() {
			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].c();
			}

			each_1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			for (let i = 0; i < each_blocks.length; i += 1) {
				each_blocks[i].m(target, anchor);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, each_1_anchor, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*$form*/ 1024) {
				each_value = /*$form*/ ctx[10].lineas_investigacion;
				let i;

				for (i = 0; i < each_value.length; i += 1) {
					const child_ctx = get_each_context(ctx, each_value, i);

					if (each_blocks[i]) {
						each_blocks[i].p(child_ctx, dirty);
					} else {
						each_blocks[i] = create_each_block(child_ctx);
						each_blocks[i].c();
						each_blocks[i].m(each_1_anchor.parentNode, each_1_anchor);
					}
				}

				for (; i < each_blocks.length; i += 1) {
					each_blocks[i].d(1);
				}

				each_blocks.length = each_value.length;
			}
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_each)(each_blocks, detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(each_1_anchor);
		}
	};
}

// (251:20) {#each $form.lineas_investigacion as lineaInvestigacion}
function create_each_block(ctx) {
	let br;
	let t0;
	let t1_value = /*lineaInvestigacion*/ ctx[37].label + "";
	let t1;

	return {
		c() {
			br = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("br");
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t1_value);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, br, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t0, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t1, anchor);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*$form*/ 1024 && t1_value !== (t1_value = /*lineaInvestigacion*/ ctx[37].label + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, t1_value);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(br);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t0);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t1);
		}
	};
}

// (168:8) <Export2Word id="borrador" showButton={false} bind:this={exportComponent}>
function create_default_slot_3(ctx) {
	let h1;
	let t1;
	let p0;
	let strong0;
	let t3;

	let t4_value = (/*$form*/ ctx[10].nombre
	? /*$form*/ ctx[10].nombre
	: 'Sin información registrada') + "";

	let t4;
	let t5;
	let p1;
	let strong1;
	let t7;

	let t8_value = (/*$form*/ ctx[10].fecha_creacion_semillero
	? /*$form*/ ctx[10].fecha_creacion_semillero
	: 'Sin información registrada') + "";

	let t8;
	let t9;
	let p2;
	let strong2;
	let t11;

	let t12_value = (/*$form*/ ctx[10].linea_investigacion_id
	? /*$form*/ ctx[10].linea_investigacion_id?.label
	: 'Sin información registrada') + "";

	let t12;
	let t13;
	let p3;
	let strong3;
	let t15;

	let t16_value = (/*$form*/ ctx[10].nombre_lider_semillero
	? /*$form*/ ctx[10].nombre_lider_semillero
	: 'Sin información registrada') + "";

	let t16;
	let t17;
	let p4;
	let strong4;
	let t19;

	let t20_value = (/*$form*/ ctx[10].email_contacto
	? /*$form*/ ctx[10].email_contacto
	: 'Sin información registrada') + "";

	let t20;
	let t21;
	let p5;
	let strong5;
	let t23;

	let t24_value = (/*$form*/ ctx[10].reconocimientos_semillero_investigacion
	? /*$form*/ ctx[10].reconocimientos_semillero_investigacion
	: 'Sin información registrada') + "";

	let t24;
	let t25;
	let p6;
	let strong6;
	let t27;

	let t28_value = (/*$form*/ ctx[10].vision
	? /*$form*/ ctx[10].vision
	: 'Sin información registrada') + "";

	let t28;
	let t29;
	let p7;
	let strong7;
	let t31;

	let t32_value = (/*$form*/ ctx[10].mision
	? /*$form*/ ctx[10].mision
	: 'Sin información registrada') + "";

	let t32;
	let t33;
	let p8;
	let strong8;
	let t35;

	let t36_value = (/*$form*/ ctx[10].objetivo_general
	? /*$form*/ ctx[10].objetivo_general
	: 'Sin información registrada') + "";

	let t36;
	let t37;
	let p9;
	let strong9;
	let t39;

	let t40_value = (/*$form*/ ctx[10].objetivos_especificos
	? /*$form*/ ctx[10].objetivos_especificos
	: 'Sin información registrada') + "";

	let t40;
	let t41;
	let p10;
	let strong10;
	let t43;

	let t44_value = (/*$form*/ ctx[10].link_semillero
	? /*$form*/ ctx[10].link_semillero
	: 'Sin información registrada') + "";

	let t44;
	let t45;
	let p11;
	let strong11;
	let t47;
	let br0;
	let t48;

	let t49_value = (/*$form*/ ctx[10].es_semillero_tecnoacademia
	? /*$form*/ ctx[10].es_semillero_tecnoacademia?.label
	: 'Sin información registrada') + "";

	let t49;
	let t50;
	let p12;
	let strong12;
	let t52;
	let br1;
	let t53;
	let t54;
	let p13;
	let strong13;
	let t56;
	let br2;
	let t57;
	let t58;
	let p14;
	let strong14;
	let t60;
	let br3;
	let t61;

	function select_block_type_2(ctx, dirty) {
		if (/*$form*/ ctx[10].redes_conocimiento) return create_if_block_3;
		return create_else_block_3;
	}

	let current_block_type = select_block_type_2(ctx, [-1, -1]);
	let if_block0 = current_block_type(ctx);

	function select_block_type_3(ctx, dirty) {
		if (/*$form*/ ctx[10].programas_formacion) return create_if_block_2;
		return create_else_block_2;
	}

	let current_block_type_1 = select_block_type_3(ctx, [-1, -1]);
	let if_block1 = current_block_type_1(ctx);

	function select_block_type_4(ctx, dirty) {
		if (/*$form*/ ctx[10].lineas_investigacion) return create_if_block_1;
		return create_else_block_1;
	}

	let current_block_type_2 = select_block_type_4(ctx, [-1, -1]);
	let if_block2 = current_block_type_2(ctx);

	return {
		c() {
			h1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("h1");
			h1.textContent = "Información del semillero de investigación";
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong0.textContent = "Nombre del semillero:";
			t3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t4 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t4_value);
			t5 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong1.textContent = "Fecha creación del semillero:";
			t7 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t8 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t8_value);
			t9 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong2.textContent = "Línea de investigación principal:";
			t11 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t12 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t12_value);
			t13 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong3.textContent = "Nombre del líder del semillero:";
			t15 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t16 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t16_value);
			t17 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p4 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong4 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong4.textContent = "Correo electrónico de contacto:";
			t19 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t20 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t20_value);
			t21 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p5 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong5 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong5.textContent = "Reconocimientos semillero de investigación:";
			t23 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t24 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t24_value);
			t25 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p6 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong6 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong6.textContent = "Visión:";
			t27 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t28 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t28_value);
			t29 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p7 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong7 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong7.textContent = "Misión:";
			t31 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t32 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t32_value);
			t33 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p8 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong8 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong8.textContent = "Objetivo general:";
			t35 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t36 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t36_value);
			t37 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p9 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong9 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong9.textContent = "Objetivos específicos:";
			t39 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t40 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t40_value);
			t41 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p10 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong10 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong10.textContent = "Link del semillero:";
			t43 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t44 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t44_value);
			t45 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p11 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong11 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong11.textContent = "¿Es semillero de TecnoAcademia?";
			t47 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			br0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("br");
			t48 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			t49 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(t49_value);
			t50 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p12 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong12 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong12.textContent = "Red o redes de conocimiento afines al Semillero de Investigación:";
			t52 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			br1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("br");
			t53 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if_block0.c();
			t54 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p13 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong13 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong13.textContent = "Programas de formación:";
			t56 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			br2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("br");
			t57 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if_block1.c();
			t58 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			p14 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			strong14 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("strong");
			strong14.textContent = "Articulación con líneas de investigación:";
			t60 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			br3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("br");
			t61 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if_block2.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(h1, "class", "font-black text-center my-10");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p0, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p0, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p1, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p1, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p2, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p2, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p3, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p3, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p4, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p4, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p5, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p5, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p6, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p6, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p7, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p7, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p8, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p8, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p9, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p9, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p10, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p10, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p11, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p11, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p12, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p12, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p13, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p13, "margin-bottom", "4rem");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p14, "white-space", "pre-line");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(p14, "margin-bottom", "4rem");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, h1, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t1, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p0, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p0, strong0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p0, t3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p0, t4);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t5, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p1, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p1, strong1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p1, t7);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p1, t8);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t9, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p2, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p2, strong2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p2, t11);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p2, t12);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t13, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p3, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p3, strong3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p3, t15);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p3, t16);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t17, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p4, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p4, strong4);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p4, t19);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p4, t20);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t21, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p5, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p5, strong5);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p5, t23);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p5, t24);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t25, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p6, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p6, strong6);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p6, t27);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p6, t28);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t29, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p7, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p7, strong7);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p7, t31);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p7, t32);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t33, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p8, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p8, strong8);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p8, t35);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p8, t36);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t37, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p9, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p9, strong9);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p9, t39);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p9, t40);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t41, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p10, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p10, strong10);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p10, t43);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p10, t44);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t45, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p11, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p11, strong11);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p11, t47);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p11, br0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p11, t48);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p11, t49);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t50, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p12, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p12, strong12);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p12, t52);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p12, br1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p12, t53);
			if_block0.m(p12, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t54, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p13, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p13, strong13);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p13, t56);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p13, br2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p13, t57);
			if_block1.m(p13, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t58, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p14, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p14, strong14);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p14, t60);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p14, br3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p14, t61);
			if_block2.m(p14, null);
		},
		p(ctx, dirty) {
			if (dirty[0] & /*$form*/ 1024 && t4_value !== (t4_value = (/*$form*/ ctx[10].nombre
			? /*$form*/ ctx[10].nombre
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t4, t4_value);

			if (dirty[0] & /*$form*/ 1024 && t8_value !== (t8_value = (/*$form*/ ctx[10].fecha_creacion_semillero
			? /*$form*/ ctx[10].fecha_creacion_semillero
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t8, t8_value);

			if (dirty[0] & /*$form*/ 1024 && t12_value !== (t12_value = (/*$form*/ ctx[10].linea_investigacion_id
			? /*$form*/ ctx[10].linea_investigacion_id?.label
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t12, t12_value);

			if (dirty[0] & /*$form*/ 1024 && t16_value !== (t16_value = (/*$form*/ ctx[10].nombre_lider_semillero
			? /*$form*/ ctx[10].nombre_lider_semillero
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t16, t16_value);

			if (dirty[0] & /*$form*/ 1024 && t20_value !== (t20_value = (/*$form*/ ctx[10].email_contacto
			? /*$form*/ ctx[10].email_contacto
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t20, t20_value);

			if (dirty[0] & /*$form*/ 1024 && t24_value !== (t24_value = (/*$form*/ ctx[10].reconocimientos_semillero_investigacion
			? /*$form*/ ctx[10].reconocimientos_semillero_investigacion
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t24, t24_value);

			if (dirty[0] & /*$form*/ 1024 && t28_value !== (t28_value = (/*$form*/ ctx[10].vision
			? /*$form*/ ctx[10].vision
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t28, t28_value);

			if (dirty[0] & /*$form*/ 1024 && t32_value !== (t32_value = (/*$form*/ ctx[10].mision
			? /*$form*/ ctx[10].mision
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t32, t32_value);

			if (dirty[0] & /*$form*/ 1024 && t36_value !== (t36_value = (/*$form*/ ctx[10].objetivo_general
			? /*$form*/ ctx[10].objetivo_general
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t36, t36_value);

			if (dirty[0] & /*$form*/ 1024 && t40_value !== (t40_value = (/*$form*/ ctx[10].objetivos_especificos
			? /*$form*/ ctx[10].objetivos_especificos
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t40, t40_value);

			if (dirty[0] & /*$form*/ 1024 && t44_value !== (t44_value = (/*$form*/ ctx[10].link_semillero
			? /*$form*/ ctx[10].link_semillero
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t44, t44_value);

			if (dirty[0] & /*$form*/ 1024 && t49_value !== (t49_value = (/*$form*/ ctx[10].es_semillero_tecnoacademia
			? /*$form*/ ctx[10].es_semillero_tecnoacademia?.label
			: 'Sin información registrada') + "")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t49, t49_value);

			if (current_block_type === (current_block_type = select_block_type_2(ctx, dirty)) && if_block0) {
				if_block0.p(ctx, dirty);
			} else {
				if_block0.d(1);
				if_block0 = current_block_type(ctx);

				if (if_block0) {
					if_block0.c();
					if_block0.m(p12, null);
				}
			}

			if (current_block_type_1 === (current_block_type_1 = select_block_type_3(ctx, dirty)) && if_block1) {
				if_block1.p(ctx, dirty);
			} else {
				if_block1.d(1);
				if_block1 = current_block_type_1(ctx);

				if (if_block1) {
					if_block1.c();
					if_block1.m(p13, null);
				}
			}

			if (current_block_type_2 === (current_block_type_2 = select_block_type_4(ctx, dirty)) && if_block2) {
				if_block2.p(ctx, dirty);
			} else {
				if_block2.d(1);
				if_block2 = current_block_type_2(ctx);

				if (if_block2) {
					if_block2.c();
					if_block2.m(p14, null);
				}
			}
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(h1);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t1);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p0);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t5);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p1);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t9);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p2);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t13);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p3);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t17);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p4);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t21);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p5);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t25);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p6);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t29);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p7);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t33);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p8);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t37);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p9);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t41);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p10);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t45);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p11);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t50);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p12);
			if_block0.d();
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t54);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p13);
			if_block1.d();
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t58);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p14);
			if_block2.d();
		}
	};
}

// (167:4) 
function create_content_slot(ctx) {
	let div;
	let export2word;
	let current;

	let export2word_props = {
		id: "borrador",
		showButton: false,
		$$slots: { default: [create_default_slot_3] },
		$$scope: { ctx }
	};

	export2word = new _Shared_Export2Word__WEBPACK_IMPORTED_MODULE_10__["default"]({ props: export2word_props });
	/*export2word_binding*/ ctx[35](export2word);

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(export2word.$$.fragment);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "slot", "content");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(export2word, div, null);
			current = true;
		},
		p(ctx, dirty) {
			const export2word_changes = {};

			if (dirty[0] & /*$form*/ 1024 | dirty[1] & /*$$scope*/ 32768) {
				export2word_changes.$$scope = { dirty, ctx };
			}

			export2word.$set(export2word_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(export2word.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(export2word.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			/*export2word_binding*/ ctx[35](null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(export2word);
		}
	};
}

// (263:12) <Button on:click={() => (dialogGuardar = false)} variant={null}>
function create_default_slot_2(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Cancelar");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (264:12) <Button variant="raised" type="button" on:click={() => exportComponent.export2Word(semilleroInvestigacion.codigo)}>
function create_default_slot_1(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Descargar borrador en Word");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (267:12) {:else}
function create_else_block(ctx) {
	let span;

	return {
		c() {
			span = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("span");
			span.textContent = "El semillero no se puede modificar";
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(span, "class", "inline-block ml-1.5");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, span, anchor);
		},
		p: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(span);
		}
	};
}

// (265:12) {#if semilleroInvestigacion?.allowed.to_update || allowedToCreate}
function create_if_block(ctx) {
	let loadingbutton;
	let current;

	loadingbutton = new _Shared_LoadingButton__WEBPACK_IMPORTED_MODULE_7__["default"]({
			props: {
				loading: /*$form*/ ctx[10].processing,
				form: "semillero-investigacion-form",
				$$slots: { default: [create_default_slot] },
				$$scope: { ctx }
			}
		});

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(loadingbutton.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(loadingbutton, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const loadingbutton_changes = {};
			if (dirty[0] & /*$form*/ 1024) loadingbutton_changes.loading = /*$form*/ ctx[10].processing;

			if (dirty[1] & /*$$scope*/ 32768) {
				loadingbutton_changes.$$scope = { dirty, ctx };
			}

			loadingbutton.$set(loadingbutton_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(loadingbutton.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(loadingbutton.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(loadingbutton, detaching);
		}
	};
}

// (266:16) <LoadingButton loading={$form.processing} form="semillero-investigacion-form">
function create_default_slot(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Guardar");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

// (261:4) 
function create_actions_slot(ctx) {
	let div1;
	let div0;
	let button0;
	let t0;
	let button1;
	let t1;
	let current_block_type_index;
	let if_block;
	let current;

	button0 = new _Shared_Button__WEBPACK_IMPORTED_MODULE_8__["default"]({
			props: {
				variant: null,
				$$slots: { default: [create_default_slot_2] },
				$$scope: { ctx }
			}
		});

	button0.$on("click", /*click_handler_1*/ ctx[33]);

	button1 = new _Shared_Button__WEBPACK_IMPORTED_MODULE_8__["default"]({
			props: {
				variant: "raised",
				type: "button",
				$$slots: { default: [create_default_slot_1] },
				$$scope: { ctx }
			}
		});

	button1.$on("click", /*click_handler_2*/ ctx[34]);
	const if_block_creators = [create_if_block, create_else_block];
	const if_blocks = [];

	function select_block_type_1(ctx, dirty) {
		if (/*semilleroInvestigacion*/ ctx[0]?.allowed.to_update || /*allowedToCreate*/ ctx[9]) return 0;
		return 1;
	}

	current_block_type_index = select_block_type_1(ctx, [-1, -1]);
	if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);

	return {
		c() {
			div1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			div0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(button0.$$.fragment);
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(button1.$$.fragment);
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if_block.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div0, "class", "p-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div1, "slot", "actions");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div1, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div1, div0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(button0, div0, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div0, t0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(button1, div0, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div0, t1);
			if_blocks[current_block_type_index].m(div0, null);
			current = true;
		},
		p(ctx, dirty) {
			const button0_changes = {};

			if (dirty[1] & /*$$scope*/ 32768) {
				button0_changes.$$scope = { dirty, ctx };
			}

			button0.$set(button0_changes);
			const button1_changes = {};

			if (dirty[1] & /*$$scope*/ 32768) {
				button1_changes.$$scope = { dirty, ctx };
			}

			button1.$set(button1_changes);
			let previous_block_index = current_block_type_index;
			current_block_type_index = select_block_type_1(ctx, dirty);

			if (current_block_type_index === previous_block_index) {
				if_blocks[current_block_type_index].p(ctx, dirty);
			} else {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
					if_blocks[previous_block_index] = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				if_block = if_blocks[current_block_type_index];

				if (!if_block) {
					if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
					if_block.c();
				} else {
					if_block.p(ctx, dirty);
				}

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
				if_block.m(div0, null);
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(button0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(button1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(button0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(button1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(button0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(button1);
			if_blocks[current_block_type_index].d();
		}
	};
}

function create_fragment(ctx) {
	let form_1;
	let fieldset;
	let t0;
	let div0;
	let label0;
	let t1;
	let select0;
	let updating_selectedValue;
	let t2;
	let div1;
	let label1;
	let t3;
	let select1;
	let updating_selectedValue_1;
	let t4;
	let div2;
	let label2;
	let t5;
	let selectmulti0;
	let updating_selectedValue_2;
	let t6;
	let div3;
	let label3;
	let t7;
	let input0;
	let updating_value;
	let t8;
	let div4;
	let label4;
	let t9;
	let input1;
	let t10;
	let div5;
	let label5;
	let t11;
	let input2;
	let updating_value_1;
	let t12;
	let div6;
	let label6;
	let t13;
	let input3;
	let updating_value_2;
	let t14;
	let div7;
	let label7;
	let t15;
	let textarea0;
	let updating_value_3;
	let t16;
	let div8;
	let label8;
	let t17;
	let textarea1;
	let updating_value_4;
	let t18;
	let div9;
	let label9;
	let t19;
	let textarea2;
	let updating_value_5;
	let t20;
	let div10;
	let label10;
	let t21;
	let textarea3;
	let updating_value_6;
	let t22;
	let div11;
	let label11;
	let t23;
	let textarea4;
	let updating_value_7;
	let t24;
	let div12;
	let label12;
	let t25;
	let input4;
	let updating_value_8;
	let t26;
	let div13;
	let label13;
	let t27;
	let selectmulti1;
	let updating_selectedValue_3;
	let t28;
	let hr;
	let t29;
	let div14;
	let label14;
	let t30;
	let select2;
	let updating_selectedValue_4;
	let t31;
	let current_block_type_index;
	let if_block1;
	let t32;
	let div15;
	let t33;
	let button;
	let t34;
	let dialog;
	let updating_open;
	let current;
	let mounted;
	let dispose;
	let if_block0 = /*semilleroInvestigacion*/ ctx[0] && create_if_block_7(ctx);

	label0 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				class: "mb-4",
				labelFor: "es_semillero_tecnoacademia",
				value: "¿Es semillero de TecnoAcademia?"
			}
		});

	function select0_selectedValue_binding(value) {
		/*select0_selectedValue_binding*/ ctx[16](value);
	}

	let select0_props = {
		items: /*opcionesSiNo*/ ctx[4],
		id: "es_semillero_tecnoacademia",
		error: /*errors*/ ctx[2].es_semillero_tecnoacademia,
		autocomplete: "off",
		placeholder: "Seleccione una opción",
		required: true
	};

	if (/*$form*/ ctx[10].es_semillero_tecnoacademia !== void 0) {
		select0_props.selectedValue = /*$form*/ ctx[10].es_semillero_tecnoacademia;
	}

	select0 = new _Shared_Select__WEBPACK_IMPORTED_MODULE_5__["default"]({ props: select0_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(select0, 'selectedValue', select0_selectedValue_binding));

	label1 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				class: "mb-4",
				labelFor: "linea_investigacion_id",
				value: "Línea de investigación principal"
			}
		});

	function select1_selectedValue_binding(value) {
		/*select1_selectedValue_binding*/ ctx[17](value);
	}

	let select1_props = {
		id: "linea_investigacion_id",
		items: /*lineasInvestigacion*/ ctx[5],
		error: /*errors*/ ctx[2].linea_investigacion_id,
		autocomplete: "off",
		placeholder: "Seleccione una línea de investigación",
		required: true
	};

	if (/*$form*/ ctx[10].linea_investigacion_id !== void 0) {
		select1_props.selectedValue = /*$form*/ ctx[10].linea_investigacion_id;
	}

	select1 = new _Shared_Select__WEBPACK_IMPORTED_MODULE_5__["default"]({ props: select1_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(select1, 'selectedValue', select1_selectedValue_binding));

	label2 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				class: "mb-4",
				labelFor: "lineas_investigacion",
				value: "Articulación con líneas de investigación"
			}
		});

	function selectmulti0_selectedValue_binding(value) {
		/*selectmulti0_selectedValue_binding*/ ctx[18](value);
	}

	let selectmulti0_props = {
		id: "lineas_investigacion",
		items: /*lineasInvestigacion*/ ctx[5],
		isMulti: true,
		error: /*errors*/ ctx[2].lineas_investigacion,
		placeholder: "Buscar por el nombre de la línea de investigación",
		required: true
	};

	if (/*$form*/ ctx[10].lineas_investigacion !== void 0) {
		selectmulti0_props.selectedValue = /*$form*/ ctx[10].lineas_investigacion;
	}

	selectmulti0 = new _Shared_SelectMulti__WEBPACK_IMPORTED_MODULE_4__["default"]({ props: selectmulti0_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(selectmulti0, 'selectedValue', selectmulti0_selectedValue_binding));

	label3 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				labelFor: "nombre",
				value: "Nombre del semillero"
			}
		});

	function input0_value_binding(value) {
		/*input0_value_binding*/ ctx[19](value);
	}

	let input0_props = {
		id: "nombre",
		type: "text",
		class: "mt-1",
		error: /*errors*/ ctx[2].nombre,
		required: true
	};

	if (/*$form*/ ctx[10].nombre !== void 0) {
		input0_props.value = /*$form*/ ctx[10].nombre;
	}

	input0 = new _Shared_Input__WEBPACK_IMPORTED_MODULE_2__["default"]({ props: input0_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(input0, 'value', input0_value_binding));

	label4 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				labelFor: "fecha_creacion_semillero",
				value: "Fecha creación del semillero"
			}
		});

	label5 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				labelFor: "nombre_lider_semillero",
				value: "Nombre del líder del semillero"
			}
		});

	function input2_value_binding(value) {
		/*input2_value_binding*/ ctx[21](value);
	}

	let input2_props = {
		id: "nombre_lider_semillero",
		type: "text",
		class: "mt-1",
		error: /*errors*/ ctx[2].nombre_lider_semillero,
		required: true
	};

	if (/*$form*/ ctx[10].nombre_lider_semillero !== void 0) {
		input2_props.value = /*$form*/ ctx[10].nombre_lider_semillero;
	}

	input2 = new _Shared_Input__WEBPACK_IMPORTED_MODULE_2__["default"]({ props: input2_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(input2, 'value', input2_value_binding));

	label6 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				labelFor: "email_contacto",
				value: "Email de contacto"
			}
		});

	function input3_value_binding(value) {
		/*input3_value_binding*/ ctx[22](value);
	}

	let input3_props = {
		id: "email_contacto",
		type: "email",
		class: "mt-1",
		error: /*errors*/ ctx[2].email_contacto,
		required: true
	};

	if (/*$form*/ ctx[10].email_contacto !== void 0) {
		input3_props.value = /*$form*/ ctx[10].email_contacto;
	}

	input3 = new _Shared_Input__WEBPACK_IMPORTED_MODULE_2__["default"]({ props: input3_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(input3, 'value', input3_value_binding));

	label7 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				labelFor: "reconocimientos_semillero_investigacion",
				value: "Reconocimientos semillero de investigación"
			}
		});

	function textarea0_value_binding(value) {
		/*textarea0_value_binding*/ ctx[23](value);
	}

	let textarea0_props = {
		maxlength: "40000",
		id: "reconocimientos_semillero_investigacion",
		error: /*errors*/ ctx[2].reconocimientos_semillero_investigacion,
		required: true
	};

	if (/*$form*/ ctx[10].reconocimientos_semillero_investigacion !== void 0) {
		textarea0_props.value = /*$form*/ ctx[10].reconocimientos_semillero_investigacion;
	}

	textarea0 = new _Shared_Textarea__WEBPACK_IMPORTED_MODULE_3__["default"]({ props: textarea0_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(textarea0, 'value', textarea0_value_binding));

	label8 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				labelFor: "vision",
				value: "Visión"
			}
		});

	function textarea1_value_binding(value) {
		/*textarea1_value_binding*/ ctx[24](value);
	}

	let textarea1_props = {
		maxlength: "40000",
		id: "vision",
		error: /*errors*/ ctx[2].vision,
		required: true
	};

	if (/*$form*/ ctx[10].vision !== void 0) {
		textarea1_props.value = /*$form*/ ctx[10].vision;
	}

	textarea1 = new _Shared_Textarea__WEBPACK_IMPORTED_MODULE_3__["default"]({ props: textarea1_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(textarea1, 'value', textarea1_value_binding));

	label9 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				labelFor: "mision",
				value: "Misión"
			}
		});

	function textarea2_value_binding(value) {
		/*textarea2_value_binding*/ ctx[25](value);
	}

	let textarea2_props = {
		maxlength: "40000",
		id: "mision",
		error: /*errors*/ ctx[2].mision,
		required: true
	};

	if (/*$form*/ ctx[10].mision !== void 0) {
		textarea2_props.value = /*$form*/ ctx[10].mision;
	}

	textarea2 = new _Shared_Textarea__WEBPACK_IMPORTED_MODULE_3__["default"]({ props: textarea2_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(textarea2, 'value', textarea2_value_binding));

	label10 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				labelFor: "objetivo_general",
				value: "Objetivo general"
			}
		});

	function textarea3_value_binding(value) {
		/*textarea3_value_binding*/ ctx[26](value);
	}

	let textarea3_props = {
		maxlength: "40000",
		id: "objetivo_general",
		error: /*errors*/ ctx[2].objetivo_general,
		required: true
	};

	if (/*$form*/ ctx[10].objetivo_general !== void 0) {
		textarea3_props.value = /*$form*/ ctx[10].objetivo_general;
	}

	textarea3 = new _Shared_Textarea__WEBPACK_IMPORTED_MODULE_3__["default"]({ props: textarea3_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(textarea3, 'value', textarea3_value_binding));

	label11 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				labelFor: "objetivos_especificos",
				value: "Objetivos específicos "
			}
		});

	function textarea4_value_binding(value) {
		/*textarea4_value_binding*/ ctx[27](value);
	}

	let textarea4_props = {
		maxlength: "40000",
		id: "objetivos_especificos",
		error: /*errors*/ ctx[2].objetivos_especificos,
		required: true
	};

	if (/*$form*/ ctx[10].objetivos_especificos !== void 0) {
		textarea4_props.value = /*$form*/ ctx[10].objetivos_especificos;
	}

	textarea4 = new _Shared_Textarea__WEBPACK_IMPORTED_MODULE_3__["default"]({ props: textarea4_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(textarea4, 'value', textarea4_value_binding));

	label12 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				labelFor: "link_semillero",
				value: "Link del semillero"
			}
		});

	function input4_value_binding(value) {
		/*input4_value_binding*/ ctx[28](value);
	}

	let input4_props = {
		id: "link_semillero",
		type: "url",
		class: "mt-1",
		error: /*errors*/ ctx[2].link_semillero
	};

	if (/*$form*/ ctx[10].link_semillero !== void 0) {
		input4_props.value = /*$form*/ ctx[10].link_semillero;
	}

	input4 = new _Shared_Input__WEBPACK_IMPORTED_MODULE_2__["default"]({ props: input4_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(input4, 'value', input4_value_binding));

	label13 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: true,
				class: "mb-4",
				labelFor: "redes_conocimiento",
				value: "Red o redes de conocimiento afines al Semillero de Investigación"
			}
		});

	function selectmulti1_selectedValue_binding(value) {
		/*selectmulti1_selectedValue_binding*/ ctx[29](value);
	}

	let selectmulti1_props = {
		id: "redes_conocimiento",
		items: /*redesConocimiento*/ ctx[8],
		isMulti: true,
		error: /*errors*/ ctx[2].redes_conocimiento,
		placeholder: "Buscar redes de conocimiento",
		required: true
	};

	if (/*$form*/ ctx[10].redes_conocimiento !== void 0) {
		selectmulti1_props.selectedValue = /*$form*/ ctx[10].redes_conocimiento;
	}

	selectmulti1 = new _Shared_SelectMulti__WEBPACK_IMPORTED_MODULE_4__["default"]({ props: selectmulti1_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(selectmulti1, 'selectedValue', selectmulti1_selectedValue_binding));

	label14 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				required: /*$form*/ ctx[10].programas_formacion?.length > 0
				? undefined
				: 'required',
				class: "mb-4",
				labelFor: "linea_investigacion",
				value: "Seleccione una línea de investigación y posteriormente asocie los programas de formación"
			}
		});

	function select2_selectedValue_binding(value) {
		/*select2_selectedValue_binding*/ ctx[30](value);
	}

	let select2_props = {
		id: "linea_investigacion",
		items: /*lineasInvestigacion*/ ctx[5],
		error: /*errors*/ ctx[2].linea_investigacion,
		autocomplete: "off",
		placeholder: "Seleccione una línea de investigación",
		required: /*$form*/ ctx[10].programas_formacion?.length > 0
		? undefined
		: 'required'
	};

	if (/*$form*/ ctx[10].linea_investigacion !== void 0) {
		select2_props.selectedValue = /*$form*/ ctx[10].linea_investigacion;
	}

	select2 = new _Shared_Select__WEBPACK_IMPORTED_MODULE_5__["default"]({ props: select2_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(select2, 'selectedValue', select2_selectedValue_binding));
	const if_block_creators = [create_if_block_5, create_if_block_6];
	const if_blocks = [];

	function select_block_type(ctx, dirty) {
		if (/*$form*/ ctx[10].linea_investigacion?.value && /*programasFormacion*/ ctx[6].length > 0 || /*programasFormacionSemilleroInvestigacion*/ ctx[7]?.length > 0) return 0;
		if (/*$form*/ ctx[10].linea_investigacion?.value && /*programasFormacion*/ ctx[6].length == 0 || /*programasFormacionSemilleroInvestigacion*/ ctx[7]?.length == 0) return 1;
		return -1;
	}

	if (~(current_block_type_index = select_block_type(ctx, [-1, -1]))) {
		if_block1 = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
	}

	let if_block2 = /*semilleroInvestigacion*/ ctx[0] && create_if_block_4(ctx);

	button = new _Shared_Button__WEBPACK_IMPORTED_MODULE_8__["default"]({
			props: {
				class: "ml-auto",
				type: "button",
				$$slots: { default: [create_default_slot_5] },
				$$scope: { ctx }
			}
		});

	button.$on("click", /*click_handler*/ ctx[32]);

	function dialog_open_binding(value) {
		/*dialog_open_binding*/ ctx[36](value);
	}

	let dialog_props = {
		$$slots: {
			actions: [create_actions_slot],
			content: [create_content_slot],
			"header-info": [create_header_info_slot],
			title: [create_title_slot]
		},
		$$scope: { ctx }
	};

	if (/*dialogGuardar*/ ctx[11] !== void 0) {
		dialog_props.open = /*dialogGuardar*/ ctx[11];
	}

	dialog = new _Shared_Dialog__WEBPACK_IMPORTED_MODULE_9__["default"]({ props: dialog_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(dialog, 'open', dialog_open_binding));

	return {
		c() {
			form_1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("form");
			fieldset = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("fieldset");
			if (if_block0) if_block0.c();
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label0.$$.fragment);
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(select0.$$.fragment);
			t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label1.$$.fragment);
			t3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(select1.$$.fragment);
			t4 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label2.$$.fragment);
			t5 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(selectmulti0.$$.fragment);
			t6 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div3 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label3.$$.fragment);
			t7 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(input0.$$.fragment);
			t8 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div4 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label4.$$.fragment);
			t9 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			input1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("input");
			t10 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div5 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label5.$$.fragment);
			t11 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(input2.$$.fragment);
			t12 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div6 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label6.$$.fragment);
			t13 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(input3.$$.fragment);
			t14 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div7 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label7.$$.fragment);
			t15 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(textarea0.$$.fragment);
			t16 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div8 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label8.$$.fragment);
			t17 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(textarea1.$$.fragment);
			t18 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div9 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label9.$$.fragment);
			t19 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(textarea2.$$.fragment);
			t20 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div10 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label10.$$.fragment);
			t21 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(textarea3.$$.fragment);
			t22 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div11 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label11.$$.fragment);
			t23 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(textarea4.$$.fragment);
			t24 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div12 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label12.$$.fragment);
			t25 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(input4.$$.fragment);
			t26 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div13 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label13.$$.fragment);
			t27 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(selectmulti1.$$.fragment);
			t28 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			hr = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("hr");
			t29 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div14 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label14.$$.fragment);
			t30 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(select2.$$.fragment);
			t31 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block1) if_block1.c();
			t32 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div15 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (if_block2) if_block2.c();
			t33 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(button.$$.fragment);
			t34 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(dialog.$$.fragment);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div0, "class", "mt-8");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div1, "class", "mt-8");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div2, "class", "mt-8");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div3, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(input1, "id", "fecha_creacion_semillero");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(input1, "type", "date");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(input1, "class", "mt-1 p-2");
			input1.required = true;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div4, "class", "mt-4 ");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div5, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div6, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div7, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div8, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div9, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div10, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div11, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div12, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div13, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(hr, "class", "mt-10 mb-10");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div14, "class", "mt-4");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(fieldset, "class", "p-8");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div15, "class", "shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(form_1, "class", "bg-white rounded shadow");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(form_1, "id", "semillero-investigacion-form");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, form_1, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(form_1, fieldset);
			if (if_block0) if_block0.m(fieldset, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label0, div0, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div0, t1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(select0, div0, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label1, div1, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div1, t3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(select1, div1, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t4);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label2, div2, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div2, t5);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(selectmulti0, div2, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t6);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label3, div3, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div3, t7);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(input0, div3, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t8);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div4);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label4, div4, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div4, t9);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div4, input1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_input_value)(input1, /*$form*/ ctx[10].fecha_creacion_semillero);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t10);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div5);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label5, div5, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div5, t11);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(input2, div5, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t12);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div6);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label6, div6, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div6, t13);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(input3, div6, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t14);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div7);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label7, div7, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div7, t15);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(textarea0, div7, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t16);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div8);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label8, div8, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div8, t17);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(textarea1, div8, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t18);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div9);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label9, div9, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div9, t19);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(textarea2, div9, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t20);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div10);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label10, div10, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div10, t21);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(textarea3, div10, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t22);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div11);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label11, div11, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div11, t23);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(textarea4, div11, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t24);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div12);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label12, div12, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div12, t25);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(input4, div12, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t26);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div13);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label13, div13, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div13, t27);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(selectmulti1, div13, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t28);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, hr);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t29);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, div14);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label14, div14, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div14, t30);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(select2, div14, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(fieldset, t31);

			if (~current_block_type_index) {
				if_blocks[current_block_type_index].m(fieldset, null);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(form_1, t32);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(form_1, div15);
			if (if_block2) if_block2.m(div15, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div15, t33);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(button, div15, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t34, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(dialog, target, anchor);
			current = true;

			if (!mounted) {
				dispose = [
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(input1, "input", /*input1_input_handler*/ ctx[20]),
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(form_1, "submit", (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.prevent_default)(function () {
						if ((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.is_function)(/*submit*/ ctx[3])) /*submit*/ ctx[3].apply(this, arguments);
					}))
				];

				mounted = true;
			}
		},
		p(new_ctx, dirty) {
			ctx = new_ctx;

			if (/*semilleroInvestigacion*/ ctx[0]) {
				if (if_block0) {
					if_block0.p(ctx, dirty);

					if (dirty[0] & /*semilleroInvestigacion*/ 1) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
					}
				} else {
					if_block0 = create_if_block_7(ctx);
					if_block0.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
					if_block0.m(fieldset, t0);
				}
			} else if (if_block0) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0, 1, 1, () => {
					if_block0 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			const select0_changes = {};
			if (dirty[0] & /*opcionesSiNo*/ 16) select0_changes.items = /*opcionesSiNo*/ ctx[4];
			if (dirty[0] & /*errors*/ 4) select0_changes.error = /*errors*/ ctx[2].es_semillero_tecnoacademia;

			if (!updating_selectedValue && dirty[0] & /*$form*/ 1024) {
				updating_selectedValue = true;
				select0_changes.selectedValue = /*$form*/ ctx[10].es_semillero_tecnoacademia;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_selectedValue = false);
			}

			select0.$set(select0_changes);
			const select1_changes = {};
			if (dirty[0] & /*lineasInvestigacion*/ 32) select1_changes.items = /*lineasInvestigacion*/ ctx[5];
			if (dirty[0] & /*errors*/ 4) select1_changes.error = /*errors*/ ctx[2].linea_investigacion_id;

			if (!updating_selectedValue_1 && dirty[0] & /*$form*/ 1024) {
				updating_selectedValue_1 = true;
				select1_changes.selectedValue = /*$form*/ ctx[10].linea_investigacion_id;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_selectedValue_1 = false);
			}

			select1.$set(select1_changes);
			const selectmulti0_changes = {};
			if (dirty[0] & /*lineasInvestigacion*/ 32) selectmulti0_changes.items = /*lineasInvestigacion*/ ctx[5];
			if (dirty[0] & /*errors*/ 4) selectmulti0_changes.error = /*errors*/ ctx[2].lineas_investigacion;

			if (!updating_selectedValue_2 && dirty[0] & /*$form*/ 1024) {
				updating_selectedValue_2 = true;
				selectmulti0_changes.selectedValue = /*$form*/ ctx[10].lineas_investigacion;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_selectedValue_2 = false);
			}

			selectmulti0.$set(selectmulti0_changes);
			const input0_changes = {};
			if (dirty[0] & /*errors*/ 4) input0_changes.error = /*errors*/ ctx[2].nombre;

			if (!updating_value && dirty[0] & /*$form*/ 1024) {
				updating_value = true;
				input0_changes.value = /*$form*/ ctx[10].nombre;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value = false);
			}

			input0.$set(input0_changes);

			if (dirty[0] & /*$form*/ 1024) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_input_value)(input1, /*$form*/ ctx[10].fecha_creacion_semillero);
			}

			const input2_changes = {};
			if (dirty[0] & /*errors*/ 4) input2_changes.error = /*errors*/ ctx[2].nombre_lider_semillero;

			if (!updating_value_1 && dirty[0] & /*$form*/ 1024) {
				updating_value_1 = true;
				input2_changes.value = /*$form*/ ctx[10].nombre_lider_semillero;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value_1 = false);
			}

			input2.$set(input2_changes);
			const input3_changes = {};
			if (dirty[0] & /*errors*/ 4) input3_changes.error = /*errors*/ ctx[2].email_contacto;

			if (!updating_value_2 && dirty[0] & /*$form*/ 1024) {
				updating_value_2 = true;
				input3_changes.value = /*$form*/ ctx[10].email_contacto;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value_2 = false);
			}

			input3.$set(input3_changes);
			const textarea0_changes = {};
			if (dirty[0] & /*errors*/ 4) textarea0_changes.error = /*errors*/ ctx[2].reconocimientos_semillero_investigacion;

			if (!updating_value_3 && dirty[0] & /*$form*/ 1024) {
				updating_value_3 = true;
				textarea0_changes.value = /*$form*/ ctx[10].reconocimientos_semillero_investigacion;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value_3 = false);
			}

			textarea0.$set(textarea0_changes);
			const textarea1_changes = {};
			if (dirty[0] & /*errors*/ 4) textarea1_changes.error = /*errors*/ ctx[2].vision;

			if (!updating_value_4 && dirty[0] & /*$form*/ 1024) {
				updating_value_4 = true;
				textarea1_changes.value = /*$form*/ ctx[10].vision;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value_4 = false);
			}

			textarea1.$set(textarea1_changes);
			const textarea2_changes = {};
			if (dirty[0] & /*errors*/ 4) textarea2_changes.error = /*errors*/ ctx[2].mision;

			if (!updating_value_5 && dirty[0] & /*$form*/ 1024) {
				updating_value_5 = true;
				textarea2_changes.value = /*$form*/ ctx[10].mision;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value_5 = false);
			}

			textarea2.$set(textarea2_changes);
			const textarea3_changes = {};
			if (dirty[0] & /*errors*/ 4) textarea3_changes.error = /*errors*/ ctx[2].objetivo_general;

			if (!updating_value_6 && dirty[0] & /*$form*/ 1024) {
				updating_value_6 = true;
				textarea3_changes.value = /*$form*/ ctx[10].objetivo_general;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value_6 = false);
			}

			textarea3.$set(textarea3_changes);
			const textarea4_changes = {};
			if (dirty[0] & /*errors*/ 4) textarea4_changes.error = /*errors*/ ctx[2].objetivos_especificos;

			if (!updating_value_7 && dirty[0] & /*$form*/ 1024) {
				updating_value_7 = true;
				textarea4_changes.value = /*$form*/ ctx[10].objetivos_especificos;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value_7 = false);
			}

			textarea4.$set(textarea4_changes);
			const input4_changes = {};
			if (dirty[0] & /*errors*/ 4) input4_changes.error = /*errors*/ ctx[2].link_semillero;

			if (!updating_value_8 && dirty[0] & /*$form*/ 1024) {
				updating_value_8 = true;
				input4_changes.value = /*$form*/ ctx[10].link_semillero;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value_8 = false);
			}

			input4.$set(input4_changes);
			const selectmulti1_changes = {};
			if (dirty[0] & /*redesConocimiento*/ 256) selectmulti1_changes.items = /*redesConocimiento*/ ctx[8];
			if (dirty[0] & /*errors*/ 4) selectmulti1_changes.error = /*errors*/ ctx[2].redes_conocimiento;

			if (!updating_selectedValue_3 && dirty[0] & /*$form*/ 1024) {
				updating_selectedValue_3 = true;
				selectmulti1_changes.selectedValue = /*$form*/ ctx[10].redes_conocimiento;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_selectedValue_3 = false);
			}

			selectmulti1.$set(selectmulti1_changes);
			const label14_changes = {};

			if (dirty[0] & /*$form*/ 1024) label14_changes.required = /*$form*/ ctx[10].programas_formacion?.length > 0
			? undefined
			: 'required';

			label14.$set(label14_changes);
			const select2_changes = {};
			if (dirty[0] & /*lineasInvestigacion*/ 32) select2_changes.items = /*lineasInvestigacion*/ ctx[5];
			if (dirty[0] & /*errors*/ 4) select2_changes.error = /*errors*/ ctx[2].linea_investigacion;

			if (dirty[0] & /*$form*/ 1024) select2_changes.required = /*$form*/ ctx[10].programas_formacion?.length > 0
			? undefined
			: 'required';

			if (!updating_selectedValue_4 && dirty[0] & /*$form*/ 1024) {
				updating_selectedValue_4 = true;
				select2_changes.selectedValue = /*$form*/ ctx[10].linea_investigacion;
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_selectedValue_4 = false);
			}

			select2.$set(select2_changes);
			let previous_block_index = current_block_type_index;
			current_block_type_index = select_block_type(ctx, dirty);

			if (current_block_type_index === previous_block_index) {
				if (~current_block_type_index) {
					if_blocks[current_block_type_index].p(ctx, dirty);
				}
			} else {
				if (if_block1) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
						if_blocks[previous_block_index] = null;
					});

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				}

				if (~current_block_type_index) {
					if_block1 = if_blocks[current_block_type_index];

					if (!if_block1) {
						if_block1 = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
						if_block1.c();
					} else {
						if_block1.p(ctx, dirty);
					}

					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					if_block1.m(fieldset, null);
				} else {
					if_block1 = null;
				}
			}

			if (/*semilleroInvestigacion*/ ctx[0]) {
				if (if_block2) {
					if_block2.p(ctx, dirty);
				} else {
					if_block2 = create_if_block_4(ctx);
					if_block2.c();
					if_block2.m(div15, t33);
				}
			} else if (if_block2) {
				if_block2.d(1);
				if_block2 = null;
			}

			const button_changes = {};

			if (dirty[1] & /*$$scope*/ 32768) {
				button_changes.$$scope = { dirty, ctx };
			}

			button.$set(button_changes);
			const dialog_changes = {};

			if (dirty[0] & /*$form, semilleroInvestigacion, allowedToCreate, exportComponent, dialogGuardar*/ 7681 | dirty[1] & /*$$scope*/ 32768) {
				dialog_changes.$$scope = { dirty, ctx };
			}

			if (!updating_open && dirty[0] & /*dialogGuardar*/ 2048) {
				updating_open = true;
				dialog_changes.open = /*dialogGuardar*/ ctx[11];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_open = false);
			}

			dialog.$set(dialog_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(select0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(select1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label2.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(selectmulti0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label3.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(input0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label4.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label5.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(input2.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label6.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(input3.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label7.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(textarea0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label8.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(textarea1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label9.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(textarea2.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label10.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(textarea3.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label11.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(textarea4.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label12.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(input4.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label13.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(selectmulti1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label14.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(select2.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(button.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(dialog.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(select0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(select1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label2.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(selectmulti0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label3.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(input0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label4.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label5.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(input2.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label6.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(input3.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label7.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(textarea0.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label8.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(textarea1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label9.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(textarea2.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label10.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(textarea3.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label11.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(textarea4.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label12.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(input4.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label13.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(selectmulti1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label14.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(select2.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(button.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(dialog.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(form_1);
			if (if_block0) if_block0.d();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(select0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(select1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(selectmulti0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(input0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label4);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label5);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(input2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label6);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(input3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label7);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(textarea0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label8);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(textarea1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label9);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(textarea2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label10);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(textarea3);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label11);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(textarea4);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label12);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(input4);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label13);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(selectmulti1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label14);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(select2);

			if (~current_block_type_index) {
				if_blocks[current_block_type_index].d();
			}

			if (if_block2) if_block2.d();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(button);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t34);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(dialog, detaching);
			mounted = false;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.run_all)(dispose);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let $form,
		$$unsubscribe_form = svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		$$subscribe_form = () => ($$unsubscribe_form(), $$unsubscribe_form = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.subscribe)(form, $$value => $$invalidate(10, $form = $$value)), form);

	$$self.$$.on_destroy.push(() => $$unsubscribe_form());
	let { semilleroInvestigacion } = $$props;
	let { form } = $$props;
	$$subscribe_form();
	let { errors } = $$props;
	let { submit } = $$props;
	let { opcionesSiNo } = $$props;
	let { lineasInvestigacion } = $$props;
	let { programasFormacion } = $$props;
	let { programasFormacionSemilleroInvestigacion } = $$props;
	let { redesConocimiento } = $$props;
	let { allowedToCreate } = $$props;
	let dialogGuardar = false;
	let exportComponent;
	let oldLineaInvestigacionIdValue = null;
	let arrayProgramasFormacion = programasFormacion;

	function input_value_binding(value) {
		if ($$self.$$.not_equal(semilleroInvestigacion.codigo, value)) {
			semilleroInvestigacion.codigo = value;
			$$invalidate(0, semilleroInvestigacion);
		}
	}

	function select0_selectedValue_binding(value) {
		if ($$self.$$.not_equal($form.es_semillero_tecnoacademia, value)) {
			$form.es_semillero_tecnoacademia = value;
			form.set($form);
		}
	}

	function select1_selectedValue_binding(value) {
		if ($$self.$$.not_equal($form.linea_investigacion_id, value)) {
			$form.linea_investigacion_id = value;
			form.set($form);
		}
	}

	function selectmulti0_selectedValue_binding(value) {
		if ($$self.$$.not_equal($form.lineas_investigacion, value)) {
			$form.lineas_investigacion = value;
			form.set($form);
		}
	}

	function input0_value_binding(value) {
		if ($$self.$$.not_equal($form.nombre, value)) {
			$form.nombre = value;
			form.set($form);
		}
	}

	function input1_input_handler() {
		$form.fecha_creacion_semillero = this.value;
		form.set($form);
	}

	function input2_value_binding(value) {
		if ($$self.$$.not_equal($form.nombre_lider_semillero, value)) {
			$form.nombre_lider_semillero = value;
			form.set($form);
		}
	}

	function input3_value_binding(value) {
		if ($$self.$$.not_equal($form.email_contacto, value)) {
			$form.email_contacto = value;
			form.set($form);
		}
	}

	function textarea0_value_binding(value) {
		if ($$self.$$.not_equal($form.reconocimientos_semillero_investigacion, value)) {
			$form.reconocimientos_semillero_investigacion = value;
			form.set($form);
		}
	}

	function textarea1_value_binding(value) {
		if ($$self.$$.not_equal($form.vision, value)) {
			$form.vision = value;
			form.set($form);
		}
	}

	function textarea2_value_binding(value) {
		if ($$self.$$.not_equal($form.mision, value)) {
			$form.mision = value;
			form.set($form);
		}
	}

	function textarea3_value_binding(value) {
		if ($$self.$$.not_equal($form.objetivo_general, value)) {
			$form.objetivo_general = value;
			form.set($form);
		}
	}

	function textarea4_value_binding(value) {
		if ($$self.$$.not_equal($form.objetivos_especificos, value)) {
			$form.objetivos_especificos = value;
			form.set($form);
		}
	}

	function input4_value_binding(value) {
		if ($$self.$$.not_equal($form.link_semillero, value)) {
			$form.link_semillero = value;
			form.set($form);
		}
	}

	function selectmulti1_selectedValue_binding(value) {
		if ($$self.$$.not_equal($form.redes_conocimiento, value)) {
			$form.redes_conocimiento = value;
			form.set($form);
		}
	}

	function select2_selectedValue_binding(value) {
		if ($$self.$$.not_equal($form.linea_investigacion, value)) {
			$form.linea_investigacion = value;
			form.set($form);
		}
	}

	function selectmulti_selectedValue_binding(value) {
		if ($$self.$$.not_equal($form.programas_formacion, value)) {
			$form.programas_formacion = value;
			form.set($form);
		}
	}

	const click_handler = () => $$invalidate(11, dialogGuardar = true);
	const click_handler_1 = () => $$invalidate(11, dialogGuardar = false);
	const click_handler_2 = () => exportComponent.export2Word(semilleroInvestigacion.codigo);

	function export2word_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			exportComponent = $$value;
			$$invalidate(12, exportComponent);
		});
	}

	function dialog_open_binding(value) {
		dialogGuardar = value;
		$$invalidate(11, dialogGuardar);
	}

	$$self.$$set = $$props => {
		if ('semilleroInvestigacion' in $$props) $$invalidate(0, semilleroInvestigacion = $$props.semilleroInvestigacion);
		if ('form' in $$props) $$subscribe_form($$invalidate(1, form = $$props.form));
		if ('errors' in $$props) $$invalidate(2, errors = $$props.errors);
		if ('submit' in $$props) $$invalidate(3, submit = $$props.submit);
		if ('opcionesSiNo' in $$props) $$invalidate(4, opcionesSiNo = $$props.opcionesSiNo);
		if ('lineasInvestigacion' in $$props) $$invalidate(5, lineasInvestigacion = $$props.lineasInvestigacion);
		if ('programasFormacion' in $$props) $$invalidate(6, programasFormacion = $$props.programasFormacion);
		if ('programasFormacionSemilleroInvestigacion' in $$props) $$invalidate(7, programasFormacionSemilleroInvestigacion = $$props.programasFormacionSemilleroInvestigacion);
		if ('redesConocimiento' in $$props) $$invalidate(8, redesConocimiento = $$props.redesConocimiento);
		if ('allowedToCreate' in $$props) $$invalidate(9, allowedToCreate = $$props.allowedToCreate);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty[0] & /*$form, oldLineaInvestigacionIdValue, programasFormacion*/ 17472) {
			$: if ($form.linea_investigacion) {
				if (oldLineaInvestigacionIdValue != $form.linea_investigacion?.value) {
					$$invalidate(13, arrayProgramasFormacion = programasFormacion.filter(function (obj) {
						$$invalidate(14, oldLineaInvestigacionIdValue = $form.linea_investigacion?.value);
						return obj.linea_investigacion_id == $form.linea_investigacion?.value;
					}));
				}
			}
		}
	};

	return [
		semilleroInvestigacion,
		form,
		errors,
		submit,
		opcionesSiNo,
		lineasInvestigacion,
		programasFormacion,
		programasFormacionSemilleroInvestigacion,
		redesConocimiento,
		allowedToCreate,
		$form,
		dialogGuardar,
		exportComponent,
		arrayProgramasFormacion,
		oldLineaInvestigacionIdValue,
		input_value_binding,
		select0_selectedValue_binding,
		select1_selectedValue_binding,
		selectmulti0_selectedValue_binding,
		input0_value_binding,
		input1_input_handler,
		input2_value_binding,
		input3_value_binding,
		textarea0_value_binding,
		textarea1_value_binding,
		textarea2_value_binding,
		textarea3_value_binding,
		textarea4_value_binding,
		input4_value_binding,
		selectmulti1_selectedValue_binding,
		select2_selectedValue_binding,
		selectmulti_selectedValue_binding,
		click_handler,
		click_handler_1,
		click_handler_2,
		export2word_binding,
		dialog_open_binding
	];
}

class Form extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(
			this,
			options,
			instance,
			create_fragment,
			svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal,
			{
				semilleroInvestigacion: 0,
				form: 1,
				errors: 2,
				submit: 3,
				opcionesSiNo: 4,
				lineasInvestigacion: 5,
				programasFormacion: 6,
				programasFormacionSemilleroInvestigacion: 7,
				redesConocimiento: 8,
				allowedToCreate: 9
			},
			null,
			[-1, -1]
		);
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Form);



/***/ }),

/***/ "./resources/js/Shared/Button.svelte":
/*!*******************************************!*\
  !*** ./resources/js/Shared/Button.svelte ***!
  \*******************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _smui_button__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/button */ "./node_modules/@smui/button/index.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* harmony import */ var D_www_sipro_spa_resources_js_Shared_Button_svelte_14_css_svelte_loader_cssPath_D_www_sipro_spa_resources_js_Shared_Button_svelte_14_css_D_www_sipro_spa_resources_js_Shared_Button_svelte__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./resources/js/Shared/Button.svelte.14.css!=!svelte-loader?cssPath=D:/www/sipro-spa/resources/js/Shared/Button.svelte.14.css!./resources/js/Shared/Button.svelte */ "./resources/js/Shared/Button.svelte.14.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Button.svelte.14.css!./resources/js/Shared/Button.svelte");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\Button.svelte generated by Svelte v3.48.0 */




function create_default_slot_1(ctx) {
	let current;
	const default_slot_template = /*#slots*/ ctx[5].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[7], null);

	return {
		c() {
			if (default_slot) default_slot.c();
		},
		m(target, anchor) {
			if (default_slot) {
				default_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 128)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[7],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[7])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[7], dirty, null),
						null
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (default_slot) default_slot.d(detaching);
		}
	};
}

// (15:0) <Button {...props} {href} {variant} {style} height="100px" action={null} on:click>
function create_default_slot(ctx) {
	let label;
	let current;

	label = new _smui_button__WEBPACK_IMPORTED_MODULE_1__.Label({
			props: {
				class: /*labelClass*/ ctx[2],
				$$slots: { default: [create_default_slot_1] },
				$$scope: { ctx }
			}
		});

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const label_changes = {};
			if (dirty & /*labelClass*/ 4) label_changes.class = /*labelClass*/ ctx[2];

			if (dirty & /*$$scope*/ 128) {
				label_changes.$$scope = { dirty, ctx };
			}

			label.$set(label_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label, detaching);
		}
	};
}

function create_fragment(ctx) {
	let button;
	let current;

	const button_spread_levels = [
		/*props*/ ctx[4],
		{ href: /*href*/ ctx[1] },
		{ variant: /*variant*/ ctx[0] },
		{ style: /*style*/ ctx[3] },
		{ height: "100px" },
		{ action: null }
	];

	let button_props = {
		$$slots: { default: [create_default_slot] },
		$$scope: { ctx }
	};

	for (let i = 0; i < button_spread_levels.length; i += 1) {
		button_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(button_props, button_spread_levels[i]);
	}

	button = new _smui_button__WEBPACK_IMPORTED_MODULE_1__["default"]({ props: button_props });
	button.$on("click", /*click_handler*/ ctx[6]);

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(button.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(button, target, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			const button_changes = (dirty & /*props, href, variant, style*/ 27)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(button_spread_levels, [
					dirty & /*props*/ 16 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*props*/ ctx[4]),
					dirty & /*href*/ 2 && { href: /*href*/ ctx[1] },
					dirty & /*variant*/ 1 && { variant: /*variant*/ ctx[0] },
					dirty & /*style*/ 8 && { style: /*style*/ ctx[3] },
					button_spread_levels[4],
					button_spread_levels[5]
				])
			: {};

			if (dirty & /*$$scope, labelClass*/ 132) {
				button_changes.$$scope = { dirty, ctx };
			}

			button.$set(button_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(button.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(button.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(button, detaching);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let props;
	const omit_props_names = ["variant","href","labelClass","style"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { variant = 'raised' } = $$props;
	let { href } = $$props;
	let { labelClass } = $$props;
	let { style } = $$props;

	function click_handler(event) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bubble.call(this, $$self, event);
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(8, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('variant' in $$new_props) $$invalidate(0, variant = $$new_props.variant);
		if ('href' in $$new_props) $$invalidate(1, href = $$new_props.href);
		if ('labelClass' in $$new_props) $$invalidate(2, labelClass = $$new_props.labelClass);
		if ('style' in $$new_props) $$invalidate(3, style = $$new_props.style);
		if ('$$scope' in $$new_props) $$invalidate(7, $$scope = $$new_props.$$scope);
	};

	$$self.$$.update = () => {
		$: $$invalidate(4, props = {
			...$$restProps,
			class: `${$$restProps.class || ''}`
		});
	};

	return [variant, href, labelClass, style, props, slots, click_handler, $$scope];
}

class Button_1 extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			variant: 0,
			href: 1,
			labelClass: 2,
			style: 3
		});
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Button_1);




/***/ }),

/***/ "./resources/js/Shared/Dialog.svelte":
/*!*******************************************!*\
  !*** ./resources/js/Shared/Dialog.svelte ***!
  \*******************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _smui_dialog__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/dialog */ "./node_modules/@smui/dialog/index.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\Dialog.svelte generated by Svelte v3.48.0 */



const get_actions_slot_changes = dirty => ({});
const get_actions_slot_context = ctx => ({});
const get_content_slot_changes = dirty => ({});
const get_content_slot_context = ctx => ({});
const get_header_info_slot_changes = dirty => ({});
const get_header_info_slot_context = ctx => ({});
const get_title_slot_changes = dirty => ({});
const get_title_slot_context = ctx => ({});

// (16:4) <Title id="mandatory-title">
function create_default_slot_3(ctx) {
	let current;
	const title_slot_template = /*#slots*/ ctx[5].title;
	const title_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(title_slot_template, ctx, /*$$scope*/ ctx[7], get_title_slot_context);

	return {
		c() {
			if (title_slot) title_slot.c();
		},
		m(target, anchor) {
			if (title_slot) {
				title_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (title_slot) {
				if (title_slot.p && (!current || dirty & /*$$scope*/ 128)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						title_slot,
						title_slot_template,
						ctx,
						/*$$scope*/ ctx[7],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[7])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(title_slot_template, /*$$scope*/ ctx[7], dirty, get_title_slot_changes),
						get_title_slot_context
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(title_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(title_slot, local);
			current = false;
		},
		d(detaching) {
			if (title_slot) title_slot.d(detaching);
		}
	};
}

// (22:4) <Content id="mandatory-content" class="p-4">
function create_default_slot_2(ctx) {
	let current;
	const content_slot_template = /*#slots*/ ctx[5].content;
	const content_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(content_slot_template, ctx, /*$$scope*/ ctx[7], get_content_slot_context);

	return {
		c() {
			if (content_slot) content_slot.c();
		},
		m(target, anchor) {
			if (content_slot) {
				content_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (content_slot) {
				if (content_slot.p && (!current || dirty & /*$$scope*/ 128)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						content_slot,
						content_slot_template,
						ctx,
						/*$$scope*/ ctx[7],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[7])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(content_slot_template, /*$$scope*/ ctx[7], dirty, get_content_slot_changes),
						get_content_slot_context
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(content_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(content_slot, local);
			current = false;
		},
		d(detaching) {
			if (content_slot) content_slot.d(detaching);
		}
	};
}

// (25:4) <Actions>
function create_default_slot_1(ctx) {
	let current;
	const actions_slot_template = /*#slots*/ ctx[5].actions;
	const actions_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(actions_slot_template, ctx, /*$$scope*/ ctx[7], get_actions_slot_context);

	return {
		c() {
			if (actions_slot) actions_slot.c();
		},
		m(target, anchor) {
			if (actions_slot) {
				actions_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (actions_slot) {
				if (actions_slot.p && (!current || dirty & /*$$scope*/ 128)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						actions_slot,
						actions_slot_template,
						ctx,
						/*$$scope*/ ctx[7],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[7])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(actions_slot_template, /*$$scope*/ ctx[7], dirty, get_actions_slot_changes),
						get_actions_slot_context
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(actions_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(actions_slot, local);
			current = false;
		},
		d(detaching) {
			if (actions_slot) actions_slot.d(detaching);
		}
	};
}

// (15:0) <Dialog surface$style="width: {size}; max-width: calc(100vw - 32px); background-color: {hexBgColor}" bind:open scrimClickAction="" {...props} escapeKeyAction="" aria-labelledby="mandatory-title" aria-describedby="mandatory-content" id="{id}-dialog">
function create_default_slot(ctx) {
	let title;
	let t0;
	let div;
	let t1;
	let content;
	let t2;
	let actions;
	let current;

	title = new _smui_dialog__WEBPACK_IMPORTED_MODULE_1__.Title({
			props: {
				id: "mandatory-title",
				$$slots: { default: [create_default_slot_3] },
				$$scope: { ctx }
			}
		});

	const header_info_slot_template = /*#slots*/ ctx[5]["header-info"];
	const header_info_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(header_info_slot_template, ctx, /*$$scope*/ ctx[7], get_header_info_slot_context);

	content = new _smui_dialog__WEBPACK_IMPORTED_MODULE_1__.Content({
			props: {
				id: "mandatory-content",
				class: "p-4",
				$$slots: { default: [create_default_slot_2] },
				$$scope: { ctx }
			}
		});

	actions = new _smui_dialog__WEBPACK_IMPORTED_MODULE_1__.Actions({
			props: {
				$$slots: { default: [create_default_slot_1] },
				$$scope: { ctx }
			}
		});

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(title.$$.fragment);
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (header_info_slot) header_info_slot.c();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(content.$$.fragment);
			t2 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(actions.$$.fragment);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "p-4");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(title, target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t0, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (header_info_slot) {
				header_info_slot.m(div, null);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t1, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(content, target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t2, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(actions, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const title_changes = {};

			if (dirty & /*$$scope*/ 128) {
				title_changes.$$scope = { dirty, ctx };
			}

			title.$set(title_changes);

			if (header_info_slot) {
				if (header_info_slot.p && (!current || dirty & /*$$scope*/ 128)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						header_info_slot,
						header_info_slot_template,
						ctx,
						/*$$scope*/ ctx[7],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[7])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(header_info_slot_template, /*$$scope*/ ctx[7], dirty, get_header_info_slot_changes),
						get_header_info_slot_context
					);
				}
			}

			const content_changes = {};

			if (dirty & /*$$scope*/ 128) {
				content_changes.$$scope = { dirty, ctx };
			}

			content.$set(content_changes);
			const actions_changes = {};

			if (dirty & /*$$scope*/ 128) {
				actions_changes.$$scope = { dirty, ctx };
			}

			actions.$set(actions_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(title.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(header_info_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(content.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(actions.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(title.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(header_info_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(content.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(actions.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(title, detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t0);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (header_info_slot) header_info_slot.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t1);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(content, detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t2);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(actions, detaching);
		}
	};
}

function create_fragment(ctx) {
	let dialog;
	let updating_open;
	let current;

	const dialog_spread_levels = [
		{
			surface$style: "width: " + /*size*/ ctx[2] + "; max-width: calc(100vw - 32px); background-color: " + /*hexBgColor*/ ctx[3]
		},
		{ scrimClickAction: "" },
		/*props*/ ctx[4],
		{ escapeKeyAction: "" },
		{ "aria-labelledby": "mandatory-title" },
		{ "aria-describedby": "mandatory-content" },
		{ id: "" + (/*id*/ ctx[1] + "-dialog") }
	];

	function dialog_open_binding(value) {
		/*dialog_open_binding*/ ctx[6](value);
	}

	let dialog_props = {
		$$slots: { default: [create_default_slot] },
		$$scope: { ctx }
	};

	for (let i = 0; i < dialog_spread_levels.length; i += 1) {
		dialog_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(dialog_props, dialog_spread_levels[i]);
	}

	if (/*open*/ ctx[0] !== void 0) {
		dialog_props.open = /*open*/ ctx[0];
	}

	dialog = new _smui_dialog__WEBPACK_IMPORTED_MODULE_1__["default"]({ props: dialog_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(dialog, 'open', dialog_open_binding));

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(dialog.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(dialog, target, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			const dialog_changes = (dirty & /*size, hexBgColor, props, id*/ 30)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(dialog_spread_levels, [
					dirty & /*size, hexBgColor*/ 12 && {
						surface$style: "width: " + /*size*/ ctx[2] + "; max-width: calc(100vw - 32px); background-color: " + /*hexBgColor*/ ctx[3]
					},
					dialog_spread_levels[1],
					dirty & /*props*/ 16 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*props*/ ctx[4]),
					dialog_spread_levels[3],
					dialog_spread_levels[4],
					dialog_spread_levels[5],
					dirty & /*id*/ 2 && { id: "" + (/*id*/ ctx[1] + "-dialog") }
				])
			: {};

			if (dirty & /*$$scope*/ 128) {
				dialog_changes.$$scope = { dirty, ctx };
			}

			if (!updating_open && dirty & /*open*/ 1) {
				updating_open = true;
				dialog_changes.open = /*open*/ ctx[0];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_open = false);
			}

			dialog.$set(dialog_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(dialog.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(dialog.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(dialog, detaching);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let props;
	const omit_props_names = ["open","id","size","hexBgColor"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { open = false } = $$props;
	let { id } = $$props;
	let { size = '850px' } = $$props;
	let { hexBgColor = '#fff' } = $$props;

	function dialog_open_binding(value) {
		open = value;
		$$invalidate(0, open);
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(8, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('open' in $$new_props) $$invalidate(0, open = $$new_props.open);
		if ('id' in $$new_props) $$invalidate(1, id = $$new_props.id);
		if ('size' in $$new_props) $$invalidate(2, size = $$new_props.size);
		if ('hexBgColor' in $$new_props) $$invalidate(3, hexBgColor = $$new_props.hexBgColor);
		if ('$$scope' in $$new_props) $$invalidate(7, $$scope = $$new_props.$$scope);
	};

	$$self.$$.update = () => {
		$: $$invalidate(4, props = {
			...$$restProps,
			class: `${$$restProps.class || ''}`
		});
	};

	return [open, id, size, hexBgColor, props, slots, dialog_open_binding, $$scope];
}

class Dialog_1 extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { open: 0, id: 1, size: 2, hexBgColor: 3 });
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Dialog_1);



/***/ }),

/***/ "./resources/js/Shared/Export2Word.svelte":
/*!************************************************!*\
  !*** ./resources/js/Shared/Export2Word.svelte ***!
  \************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _Shared_Button__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/Shared/Button */ "./resources/js/Shared/Button.svelte");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\Export2Word.svelte generated by Svelte v3.48.0 */




function create_if_block(ctx) {
	let button;
	let current;

	button = new _Shared_Button__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				type: "button",
				variant: "raised",
				$$slots: { default: [create_default_slot] },
				$$scope: { ctx }
			}
		});

	button.$on("click", /*click_handler*/ ctx[4]);

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(button.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(button, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const button_changes = {};

			if (dirty & /*$$scope*/ 32) {
				button_changes.$$scope = { dirty, ctx };
			}

			button.$set(button_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(button.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(button.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(button, detaching);
		}
	};
}

// (50:4) <Button type="button" variant="raised" on:click={() => export2Word()}>
function create_default_slot(ctx) {
	let t;

	return {
		c() {
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("Descargar borrador en Word");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
		}
	};
}

function create_fragment(ctx) {
	let div;
	let t;
	let if_block_anchor;
	let current;
	const default_slot_template = /*#slots*/ ctx[3].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[5], null);
	let if_block = /*showButton*/ ctx[1] && create_if_block(ctx);

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if (default_slot) default_slot.c();
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block) if_block.c();
			if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "id", /*id*/ ctx[0]);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);

			if (default_slot) {
				default_slot.m(div, null);
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
			if (if_block) if_block.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 32)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[5],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[5])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[5], dirty, null),
						null
					);
				}
			}

			if (!current || dirty & /*id*/ 1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "id", /*id*/ ctx[0]);
			}

			if (/*showButton*/ ctx[1]) {
				if (if_block) {
					if_block.p(ctx, dirty);

					if (dirty & /*showButton*/ 2) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
					}
				} else {
					if_block = create_if_block(ctx);
					if_block.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
					if_block.m(if_block_anchor.parentNode, if_block_anchor);
				}
			} else if (if_block) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block, 1, 1, () => {
					if_block = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if (default_slot) default_slot.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			if (if_block) if_block.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let { $$slots: slots = {}, $$scope } = $$props;
	let { id } = $$props;
	let { showButton = true } = $$props;

	function export2Word(filename = '') {
		var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
		var postHtml = '</body></html>';
		var html = preHtml + document.getElementById(id).innerHTML + postHtml;
		var blob = new Blob(['\ufeff', html], { type: 'application/msword' });

		// Specify link url
		var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);

		// Specify file name
		filename = filename ? filename + '.doc' : 'document.doc';

		// Create download link element
		var downloadLink = document.createElement('a');

		document.body.appendChild(downloadLink);

		if (navigator.msSaveOrOpenBlob) {
			navigator.msSaveOrOpenBlob(blob, filename);
		} else {
			// Create a link to the file
			downloadLink.href = url;

			// Setting the file name
			downloadLink.download = filename;

			//triggering the function
			downloadLink.click();
		}

		document.body.removeChild(downloadLink);
	}

	const click_handler = () => export2Word();

	$$self.$$set = $$props => {
		if ('id' in $$props) $$invalidate(0, id = $$props.id);
		if ('showButton' in $$props) $$invalidate(1, showButton = $$props.showButton);
		if ('$$scope' in $$props) $$invalidate(5, $$scope = $$props.$$scope);
	};

	return [id, showButton, export2Word, slots, click_handler, $$scope];
}

class Export2Word extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { id: 0, showButton: 1, export2Word: 2 });
	}

	get export2Word() {
		return this.$$.ctx[2];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Export2Word);



/***/ }),

/***/ "./resources/js/Shared/InfoMessage.svelte":
/*!************************************************!*\
  !*** ./resources/js/Shared/InfoMessage.svelte ***!
  \************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\InfoMessage.svelte generated by Svelte v3.48.0 */


function create_else_block(ctx) {
	let img;
	let img_src_value;

	return {
		c() {
			img = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("img");
			if (!(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.src_url_equal)(img.src, img_src_value = "/images/alert.png")) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(img, "src", img_src_value);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(img, "alt", "");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(img, "class", "absolute right-1.5 w-11 top-[-1rem]");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, img, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(img);
		}
	};
}

// (12:4) {#if alertMsg == false}
function create_if_block_1(ctx) {
	let svg;
	let path;

	return {
		c() {
			svg = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.svg_element)("svg");
			path = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.svg_element)("path");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(path, "stroke-linecap", "round");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(path, "stroke-linejoin", "round");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(path, "stroke-width", "2");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(path, "d", "M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "xmlns", "http://www.w3.org/2000/svg");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "fill", "none");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "viewBox", "0 0 24 24");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "stroke", "currentColor");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(svg, "class", "w-5");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(svg, "transform", "translateX(-44px)");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_style)(svg, "position", "absolute");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, svg, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(svg, path);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(svg);
		}
	};
}

// (19:4) {#if message}
function create_if_block(ctx) {
	let p;

	return {
		c() {
			p = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(p, "class", "whitespace-pre-wrap");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, p, anchor);
			p.innerHTML = /*message*/ ctx[0];
		},
		p(ctx, dirty) {
			if (dirty & /*message*/ 1) p.innerHTML = /*message*/ ctx[0];;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(p);
		}
	};
}

function create_fragment(ctx) {
	let div;
	let t0;
	let t1;
	let current;

	function select_block_type(ctx, dirty) {
		if (/*alertMsg*/ ctx[1] == false) return create_if_block_1;
		return create_else_block;
	}

	let current_block_type = select_block_type(ctx, -1);
	let if_block0 = current_block_type(ctx);
	let if_block1 = /*message*/ ctx[0] && create_if_block(ctx);
	const default_slot_template = /*#slots*/ ctx[4].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[3], null);
	let div_levels = [/*props*/ ctx[2]];
	let div_data = {};

	for (let i = 0; i < div_levels.length; i += 1) {
		div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(div_data, div_levels[i]);
	}

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if_block0.c();
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block1) if_block1.c();
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (default_slot) default_slot.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			if_block0.m(div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t0);
			if (if_block1) if_block1.m(div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t1);

			if (default_slot) {
				default_slot.m(div, null);
			}

			current = true;
		},
		p(ctx, [dirty]) {
			if (current_block_type !== (current_block_type = select_block_type(ctx, dirty))) {
				if_block0.d(1);
				if_block0 = current_block_type(ctx);

				if (if_block0) {
					if_block0.c();
					if_block0.m(div, t0);
				}
			}

			if (/*message*/ ctx[0]) {
				if (if_block1) {
					if_block1.p(ctx, dirty);
				} else {
					if_block1 = create_if_block(ctx);
					if_block1.c();
					if_block1.m(div, t1);
				}
			} else if (if_block1) {
				if_block1.d(1);
				if_block1 = null;
			}

			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 8)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[3],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[3])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[3], dirty, null),
						null
					);
				}
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(div, div_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(div_levels, [dirty & /*props*/ 4 && /*props*/ ctx[2]]));
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if_block0.d();
			if (if_block1) if_block1.d();
			if (default_slot) default_slot.d(detaching);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let props;
	const omit_props_names = ["message","alertMsg"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { message } = $$props;
	let { alertMsg = false } = $$props;

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(5, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('message' in $$new_props) $$invalidate(0, message = $$new_props.message);
		if ('alertMsg' in $$new_props) $$invalidate(1, alertMsg = $$new_props.alertMsg);
		if ('$$scope' in $$new_props) $$invalidate(3, $$scope = $$new_props.$$scope);
	};

	$$self.$$.update = () => {
		$: $$invalidate(2, props = {
			...$$restProps,
			class: `relative ${alertMsg
			? 'bg-red-100 text-red-400'
			: 'bg-violet-100 text-violet-600'} py-1 px-2 ${$$restProps.class || ''}`
		});
	};

	return [message, alertMsg, props, $$scope, slots];
}

class InfoMessage extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { message: 0, alertMsg: 1 });
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (InfoMessage);



/***/ }),

/***/ "./resources/js/Shared/Input.svelte":
/*!******************************************!*\
  !*** ./resources/js/Shared/Input.svelte ***!
  \******************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _Shared_Label__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/Shared/Label */ "./resources/js/Shared/Label.svelte");
/* harmony import */ var _InputError__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./InputError */ "./resources/js/Shared/InputError.svelte");
/* harmony import */ var _smui_textfield__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @smui/textfield */ "./node_modules/@smui/textfield/index.js");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\Input.svelte generated by Svelte v3.48.0 */







function create_if_block(ctx) {
	let inputerror;
	let current;
	inputerror = new _InputError__WEBPACK_IMPORTED_MODULE_2__["default"]({ props: { message: /*error*/ ctx[3] } });

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(inputerror.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(inputerror, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const inputerror_changes = {};
			if (dirty & /*error*/ 8) inputerror_changes.message = /*error*/ ctx[3];
			inputerror.$set(inputerror_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(inputerror.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(inputerror.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(inputerror, detaching);
		}
	};
}

function create_fragment(ctx) {
	let div;
	let label_1;
	let t0;
	let textfield;
	let t1;
	let div_class_value;
	let current;

	label_1 = new _Shared_Label__WEBPACK_IMPORTED_MODULE_1__["default"]({
			props: {
				label: /*label*/ ctx[2],
				id: /*id*/ ctx[1]
			}
		});

	const textfield_spread_levels = [
		{ disabled: /*disabled*/ ctx[5] },
		{ variant: "outlined" },
		/*props*/ ctx[8],
		{ type: /*type*/ ctx[4] },
		{ value: /*value*/ ctx[0] },
		{ label: /*label*/ ctx[2] }
	];

	let textfield_props = {};

	for (let i = 0; i < textfield_spread_levels.length; i += 1) {
		textfield_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(textfield_props, textfield_spread_levels[i]);
	}

	textfield = new _smui_textfield__WEBPACK_IMPORTED_MODULE_3__["default"]({ props: textfield_props });
	/*textfield_binding*/ ctx[13](textfield);
	textfield.$on("input", /*update*/ ctx[9]);
	let if_block = /*error*/ ctx[3] && create_if_block(ctx);

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label_1.$$.fragment);
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(textfield.$$.fragment);
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block) if_block.c();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", div_class_value = /*$$restProps*/ ctx[10].class);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label_1, div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(textfield, div, null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, t1);
			if (if_block) if_block.m(div, null);
			/*div_binding*/ ctx[14](div);
			current = true;
		},
		p(ctx, [dirty]) {
			const label_1_changes = {};
			if (dirty & /*label*/ 4) label_1_changes.label = /*label*/ ctx[2];
			if (dirty & /*id*/ 2) label_1_changes.id = /*id*/ ctx[1];
			label_1.$set(label_1_changes);

			const textfield_changes = (dirty & /*disabled, props, type, value, label*/ 309)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(textfield_spread_levels, [
					dirty & /*disabled*/ 32 && { disabled: /*disabled*/ ctx[5] },
					textfield_spread_levels[1],
					dirty & /*props*/ 256 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*props*/ ctx[8]),
					dirty & /*type*/ 16 && { type: /*type*/ ctx[4] },
					dirty & /*value*/ 1 && { value: /*value*/ ctx[0] },
					dirty & /*label*/ 4 && { label: /*label*/ ctx[2] }
				])
			: {};

			textfield.$set(textfield_changes);

			if (/*error*/ ctx[3]) {
				if (if_block) {
					if_block.p(ctx, dirty);

					if (dirty & /*error*/ 8) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
					}
				} else {
					if_block = create_if_block(ctx);
					if_block.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block, 1);
					if_block.m(div, null);
				}
			} else if (if_block) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block, 1, 1, () => {
					if_block = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}

			if (!current || dirty & /*$$restProps*/ 1024 && div_class_value !== (div_class_value = /*$$restProps*/ ctx[10].class)) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", div_class_value);
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label_1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(textfield.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label_1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(textfield.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label_1);
			/*textfield_binding*/ ctx[13](null);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(textfield);
			if (if_block) if_block.d();
			/*div_binding*/ ctx[14](null);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let props;
	const omit_props_names = ["id","value","label","error","type","disabled","focus","select"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { id } = $$props;
	let { value } = $$props;
	let { label } = $$props;
	let { error } = $$props;
	let { type } = $$props;
	let { disabled } = $$props;
	let input;
	let container;
	const focus = () => input.focus();
	const select = () => input.select();

	function update(event) {
		$$invalidate(0, value = event.target.value);
	}

	(0,svelte__WEBPACK_IMPORTED_MODULE_4__.onMount)(() => {
		container.querySelector('input').setAttribute('id', id);
	});

	function textfield_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			input = $$value;
			$$invalidate(6, input);
		});
	}

	function div_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			container = $$value;
			$$invalidate(7, container);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(10, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('id' in $$new_props) $$invalidate(1, id = $$new_props.id);
		if ('value' in $$new_props) $$invalidate(0, value = $$new_props.value);
		if ('label' in $$new_props) $$invalidate(2, label = $$new_props.label);
		if ('error' in $$new_props) $$invalidate(3, error = $$new_props.error);
		if ('type' in $$new_props) $$invalidate(4, type = $$new_props.type);
		if ('disabled' in $$new_props) $$invalidate(5, disabled = $$new_props.disabled);
	};

	$$self.$$.update = () => {
		$: $$invalidate(8, props = {
			...$$restProps,
			class: 'w-full block bg-white'
		});
	};

	return [
		value,
		id,
		label,
		error,
		type,
		disabled,
		input,
		container,
		props,
		update,
		$$restProps,
		focus,
		select,
		textfield_binding,
		div_binding
	];
}

class Input extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			id: 1,
			value: 0,
			label: 2,
			error: 3,
			type: 4,
			disabled: 5,
			focus: 11,
			select: 12
		});
	}

	get focus() {
		return this.$$.ctx[11];
	}

	get select() {
		return this.$$.ctx[12];
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Input);



/***/ }),

/***/ "./resources/js/Shared/InputError.svelte":
/*!***********************************************!*\
  !*** ./resources/js/Shared/InputError.svelte ***!
  \***********************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\InputError.svelte generated by Svelte v3.48.0 */


function create_if_block(ctx) {
	let div;
	let p;
	let t;

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			p = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("p");
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(/*message*/ ctx[0]);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "text-red-400 bg-red-100 py-1 px-2");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(div, p);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(p, t);
		},
		p(ctx, dirty) {
			if (dirty & /*message*/ 1) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t, /*message*/ ctx[0]);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
		}
	};
}

function create_fragment(ctx) {
	let if_block_anchor;
	let if_block = /*message*/ ctx[0] && create_if_block(ctx);

	return {
		c() {
			if (if_block) if_block.c();
			if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (if_block) if_block.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
		},
		p(ctx, [dirty]) {
			if (/*message*/ ctx[0]) {
				if (if_block) {
					if_block.p(ctx, dirty);
				} else {
					if_block = create_if_block(ctx);
					if_block.c();
					if_block.m(if_block_anchor.parentNode, if_block_anchor);
				}
			} else if (if_block) {
				if_block.d(1);
				if_block = null;
			}
		},
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (if_block) if_block.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let { message } = $$props;

	$$self.$$set = $$props => {
		if ('message' in $$props) $$invalidate(0, message = $$props.message);
	};

	return [message];
}

class InputError extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { message: 0 });
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (InputError);



/***/ }),

/***/ "./resources/js/Shared/Label.svelte":
/*!******************************************!*\
  !*** ./resources/js/Shared/Label.svelte ***!
  \******************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\Label.svelte generated by Svelte v3.48.0 */


function create_if_block(ctx) {
	let label;
	let html_tag;
	let t;
	let if_block = /*required*/ ctx[2] && create_if_block_1(ctx);
	let label_levels = [/*props*/ ctx[3], { for: /*labelFor*/ ctx[0] }];
	let label_data = {};

	for (let i = 0; i < label_levels.length; i += 1) {
		label_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(label_data, label_levels[i]);
	}

	return {
		c() {
			label = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("label");
			html_tag = new svelte_internal__WEBPACK_IMPORTED_MODULE_0__.HtmlTag(false);
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block) if_block.c();
			html_tag.a = t;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(label, label_data);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, label, anchor);
			html_tag.m(/*value*/ ctx[1], label);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.append)(label, t);
			if (if_block) if_block.m(label, null);
		},
		p(ctx, dirty) {
			if (dirty & /*value*/ 2) html_tag.p(/*value*/ ctx[1]);

			if (/*required*/ ctx[2]) {
				if (if_block) {
					
				} else {
					if_block = create_if_block_1(ctx);
					if_block.c();
					if_block.m(label, null);
				}
			} else if (if_block) {
				if_block.d(1);
				if_block = null;
			}

			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_attributes)(label, label_data = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(label_levels, [
				dirty & /*props*/ 8 && /*props*/ ctx[3],
				dirty & /*labelFor*/ 1 && { for: /*labelFor*/ ctx[0] }
			]));
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(label);
			if (if_block) if_block.d();
		}
	};
}

// (15:8) {#if required}
function create_if_block_1(ctx) {
	let small;

	return {
		c() {
			small = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("small");
			small.textContent = "* Campo obligatorio";
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(small, "class", "label-required text-red-400");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, small, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(small);
		}
	};
}

function create_fragment(ctx) {
	let if_block_anchor;
	let if_block = /*value*/ ctx[1] && create_if_block(ctx);

	return {
		c() {
			if (if_block) if_block.c();
			if_block_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			if (if_block) if_block.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block_anchor, anchor);
		},
		p(ctx, [dirty]) {
			if (/*value*/ ctx[1]) {
				if (if_block) {
					if_block.p(ctx, dirty);
				} else {
					if_block = create_if_block(ctx);
					if_block.c();
					if_block.m(if_block_anchor.parentNode, if_block_anchor);
				}
			} else if (if_block) {
				if_block.d(1);
				if_block = null;
			}
		},
		i: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		o: svelte_internal__WEBPACK_IMPORTED_MODULE_0__.noop,
		d(detaching) {
			if (if_block) if_block.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block_anchor);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let props;
	const omit_props_names = ["labelFor","value","required"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { labelFor } = $$props;
	let { value } = $$props;
	let { required } = $$props;

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(4, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('labelFor' in $$new_props) $$invalidate(0, labelFor = $$new_props.labelFor);
		if ('value' in $$new_props) $$invalidate(1, value = $$new_props.value);
		if ('required' in $$new_props) $$invalidate(2, required = $$new_props.required);
	};

	$$self.$$.update = () => {
		$: $$invalidate(3, props = {
			...$$restProps,
			class: `block font-medium text-sm text-gray-700 ${$$restProps.class || ''}`
		});
	};

	return [labelFor, value, required, props];
}

class Label extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { labelFor: 0, value: 1, required: 2 });
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Label);



/***/ }),

/***/ "./resources/js/Shared/LoadingButton.svelte":
/*!**************************************************!*\
  !*** ./resources/js/Shared/LoadingButton.svelte ***!
  \**************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var _smui_button__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/button */ "./node_modules/@smui/button/index.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* harmony import */ var D_www_sipro_spa_resources_js_Shared_LoadingButton_svelte_12_css_svelte_loader_cssPath_D_www_sipro_spa_resources_js_Shared_LoadingButton_svelte_12_css_D_www_sipro_spa_resources_js_Shared_LoadingButton_svelte__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./resources/js/Shared/LoadingButton.svelte.12.css!=!svelte-loader?cssPath=D:/www/sipro-spa/resources/js/Shared/LoadingButton.svelte.12.css!./resources/js/Shared/LoadingButton.svelte */ "./resources/js/Shared/LoadingButton.svelte.12.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/LoadingButton.svelte.12.css!./resources/js/Shared/LoadingButton.svelte");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\LoadingButton.svelte generated by Svelte v3.48.0 */




function create_if_block(ctx) {
	let div;

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.attr)(div, "class", "btn-spinner mr-2");
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
		}
	};
}

// (21:4) <Label>
function create_default_slot_1(ctx) {
	let current;
	const default_slot_template = /*#slots*/ ctx[4].default;
	const default_slot = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_slot)(default_slot_template, ctx, /*$$scope*/ ctx[5], null);

	return {
		c() {
			if (default_slot) default_slot.c();
		},
		m(target, anchor) {
			if (default_slot) {
				default_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope*/ 32)) {
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.update_slot_base)(
						default_slot,
						default_slot_template,
						ctx,
						/*$$scope*/ ctx[5],
						!current
						? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_all_dirty_from_scope)(/*$$scope*/ ctx[5])
						: (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_slot_changes)(default_slot_template, /*$$scope*/ ctx[5], dirty, null),
						null
					);
				}
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(default_slot, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (default_slot) default_slot.d(detaching);
		}
	};
}

// (16:0) <Button {...props} disabled={loading} action={null} {variant}>
function create_default_slot(ctx) {
	let t;
	let label;
	let current;
	let if_block = /*loading*/ ctx[0] && /*disabled*/ ctx[2] && create_if_block(ctx);

	label = new _smui_button__WEBPACK_IMPORTED_MODULE_1__.Label({
			props: {
				$$slots: { default: [create_default_slot_1] },
				$$scope: { ctx }
			}
		});

	return {
		c() {
			if (if_block) if_block.c();
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(label.$$.fragment);
		},
		m(target, anchor) {
			if (if_block) if_block.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(label, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			if (/*loading*/ ctx[0] && /*disabled*/ ctx[2]) {
				if (if_block) {
					
				} else {
					if_block = create_if_block(ctx);
					if_block.c();
					if_block.m(t.parentNode, t);
				}
			} else if (if_block) {
				if_block.d(1);
				if_block = null;
			}

			const label_changes = {};

			if (dirty & /*$$scope*/ 32) {
				label_changes.$$scope = { dirty, ctx };
			}

			label.$set(label_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(label.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(label.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			if (if_block) if_block.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(label, detaching);
		}
	};
}

function create_fragment(ctx) {
	let button;
	let current;

	const button_spread_levels = [
		/*props*/ ctx[3],
		{ disabled: /*loading*/ ctx[0] },
		{ action: null },
		{ variant: /*variant*/ ctx[1] }
	];

	let button_props = {
		$$slots: { default: [create_default_slot] },
		$$scope: { ctx }
	};

	for (let i = 0; i < button_spread_levels.length; i += 1) {
		button_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(button_props, button_spread_levels[i]);
	}

	button = new _smui_button__WEBPACK_IMPORTED_MODULE_1__["default"]({ props: button_props });

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(button.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(button, target, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			const button_changes = (dirty & /*props, loading, variant*/ 11)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(button_spread_levels, [
					dirty & /*props*/ 8 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*props*/ ctx[3]),
					dirty & /*loading*/ 1 && { disabled: /*loading*/ ctx[0] },
					button_spread_levels[2],
					dirty & /*variant*/ 2 && { variant: /*variant*/ ctx[1] }
				])
			: {};

			if (dirty & /*$$scope, loading, disabled*/ 37) {
				button_changes.$$scope = { dirty, ctx };
			}

			button.$set(button_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(button.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(button.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(button, detaching);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let props;
	const omit_props_names = ["loading","variant","disabled"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { loading = false } = $$props;
	let { variant = 'raised' } = $$props;
	let { disabled = true } = $$props;

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(6, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('loading' in $$new_props) $$invalidate(0, loading = $$new_props.loading);
		if ('variant' in $$new_props) $$invalidate(1, variant = $$new_props.variant);
		if ('disabled' in $$new_props) $$invalidate(2, disabled = $$new_props.disabled);
		if ('$$scope' in $$new_props) $$invalidate(5, $$scope = $$new_props.$$scope);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty & /*disabled*/ 4) {
			$: disabled
			? $$invalidate(0, loading = false)
			: $$invalidate(0, loading = true);
		}

		$: $$invalidate(3, props = {
			...$$restProps,
			class: `inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 ${$$restProps.class || ''}`
		});
	};

	return [loading, variant, disabled, props, slots, $$scope];
}

class LoadingButton extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();
		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, { loading: 0, variant: 1, disabled: 2 });
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (LoadingButton);




/***/ }),

/***/ "./resources/js/Shared/Select.svelte":
/*!*******************************************!*\
  !*** ./resources/js/Shared/Select.svelte ***!
  \*******************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var _Shared_InputError__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/Shared/InputError */ "./resources/js/Shared/InputError.svelte");
/* harmony import */ var svelte_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! svelte-i18n */ "./node_modules/svelte-i18n/dist/runtime.esm.js");
/* harmony import */ var svelte_select__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! svelte-select */ "./node_modules/svelte-select/src/Select.svelte");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* harmony import */ var D_www_sipro_spa_resources_js_Shared_Select_svelte_13_css_svelte_loader_cssPath_D_www_sipro_spa_resources_js_Shared_Select_svelte_13_css_D_www_sipro_spa_resources_js_Shared_Select_svelte__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./resources/js/Shared/Select.svelte.13.css!=!svelte-loader?cssPath=D:/www/sipro-spa/resources/js/Shared/Select.svelte.13.css!./resources/js/Shared/Select.svelte */ "./resources/js/Shared/Select.svelte.13.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/Select.svelte.13.css!./resources/js/Shared/Select.svelte");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\Select.svelte generated by Svelte v3.48.0 */







function create_fragment(ctx) {
	let select_1;
	let t;
	let inputerror;
	let current;

	select_1 = new svelte_select__WEBPACK_IMPORTED_MODULE_4__["default"]({
			props: {
				isDisabled: /*disabled*/ ctx[11],
				selectedValue: (/*selectedValue*/ ctx[0]?.value)
				? /*selectedValue*/ ctx[0]
				: null,
				inputAttributes: { id: /*id*/ ctx[2] },
				placeholder: /*required*/ ctx[4]
				? /*placeholder*/ ctx[5] + ' *'
				: /*placeholder*/ ctx[5],
				containerClasses: "items " + /*classes*/ ctx[1],
				listPlacement: "bottom",
				items: /*items*/ ctx[7],
				autocomplete: /*autocomplete*/ ctx[6],
				isMulti: /*isMulti*/ ctx[8],
				isSearchable: /*isSearchable*/ ctx[10],
				groupBy: /*groupBy*/ ctx[9],
				noOptionsMessage: "No hay ítems, por favor revise los filtros"
			}
		});

	select_1.$on("select", /*select_handler*/ ctx[13]);
	select_1.$on("clear", /*clear_handler*/ ctx[14]);
	inputerror = new _Shared_InputError__WEBPACK_IMPORTED_MODULE_2__["default"]({ props: { message: /*error*/ ctx[3] } });

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(select_1.$$.fragment);
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(inputerror.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(select_1, target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(inputerror, target, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			const select_1_changes = {};
			if (dirty & /*disabled*/ 2048) select_1_changes.isDisabled = /*disabled*/ ctx[11];

			if (dirty & /*selectedValue*/ 1) select_1_changes.selectedValue = (/*selectedValue*/ ctx[0]?.value)
			? /*selectedValue*/ ctx[0]
			: null;

			if (dirty & /*id*/ 4) select_1_changes.inputAttributes = { id: /*id*/ ctx[2] };

			if (dirty & /*required, placeholder*/ 48) select_1_changes.placeholder = /*required*/ ctx[4]
			? /*placeholder*/ ctx[5] + ' *'
			: /*placeholder*/ ctx[5];

			if (dirty & /*classes*/ 2) select_1_changes.containerClasses = "items " + /*classes*/ ctx[1];
			if (dirty & /*items*/ 128) select_1_changes.items = /*items*/ ctx[7];
			if (dirty & /*autocomplete*/ 64) select_1_changes.autocomplete = /*autocomplete*/ ctx[6];
			if (dirty & /*isMulti*/ 256) select_1_changes.isMulti = /*isMulti*/ ctx[8];
			if (dirty & /*isSearchable*/ 1024) select_1_changes.isSearchable = /*isSearchable*/ ctx[10];
			if (dirty & /*groupBy*/ 512) select_1_changes.groupBy = /*groupBy*/ ctx[9];
			select_1.$set(select_1_changes);
			const inputerror_changes = {};
			if (dirty & /*error*/ 8) inputerror_changes.message = /*error*/ ctx[3];
			inputerror.$set(inputerror_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(select_1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(inputerror.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(select_1.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(inputerror.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(select_1, detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(inputerror, detaching);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let $_;
	(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, svelte_i18n__WEBPACK_IMPORTED_MODULE_3__._, $$value => $$invalidate(16, $_ = $$value));
	let { classes = '' } = $$props;
	let { id = '' } = $$props;
	let { error } = $$props;
	let { required } = $$props;
	let { placeholder } = $$props;
	let { autocomplete } = $$props;
	let { items = [] } = $$props;
	let { selectedValue } = $$props;
	let { isMulti = false } = $$props;
	let { groupBy } = $$props;
	let { isSearchable = true } = $$props;
	let { disabled = false } = $$props;
	let select = null;

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		select = document.getElementById(id);
	});

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.afterUpdate)(() => {
		if (required && select != null) {
			selectedValue?.value != undefined
			? select.setCustomValidity('')
			: select.setCustomValidity($_('Please fill out this field.'));
		}
	});

	function handleSelect(event) {
		$$invalidate(0, selectedValue = event.detail);
	}

	const select_handler = e => handleSelect(e);
	const clear_handler = () => $$invalidate(0, selectedValue = null);

	$$self.$$set = $$props => {
		if ('classes' in $$props) $$invalidate(1, classes = $$props.classes);
		if ('id' in $$props) $$invalidate(2, id = $$props.id);
		if ('error' in $$props) $$invalidate(3, error = $$props.error);
		if ('required' in $$props) $$invalidate(4, required = $$props.required);
		if ('placeholder' in $$props) $$invalidate(5, placeholder = $$props.placeholder);
		if ('autocomplete' in $$props) $$invalidate(6, autocomplete = $$props.autocomplete);
		if ('items' in $$props) $$invalidate(7, items = $$props.items);
		if ('selectedValue' in $$props) $$invalidate(0, selectedValue = $$props.selectedValue);
		if ('isMulti' in $$props) $$invalidate(8, isMulti = $$props.isMulti);
		if ('groupBy' in $$props) $$invalidate(9, groupBy = $$props.groupBy);
		if ('isSearchable' in $$props) $$invalidate(10, isSearchable = $$props.isSearchable);
		if ('disabled' in $$props) $$invalidate(11, disabled = $$props.disabled);
	};

	return [
		selectedValue,
		classes,
		id,
		error,
		required,
		placeholder,
		autocomplete,
		items,
		isMulti,
		groupBy,
		isSearchable,
		disabled,
		handleSelect,
		select_handler,
		clear_handler
	];
}

class Select_1 extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			classes: 1,
			id: 2,
			error: 3,
			required: 4,
			placeholder: 5,
			autocomplete: 6,
			items: 7,
			selectedValue: 0,
			isMulti: 8,
			groupBy: 9,
			isSearchable: 10,
			disabled: 11
		});
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Select_1);




/***/ }),

/***/ "./resources/js/Shared/SelectMulti.svelte":
/*!************************************************!*\
  !*** ./resources/js/Shared/SelectMulti.svelte ***!
  \************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var svelte_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! svelte-i18n */ "./node_modules/svelte-i18n/dist/runtime.esm.js");
/* harmony import */ var svelte_select__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! svelte-select */ "./node_modules/svelte-select/src/Select.svelte");
/* harmony import */ var _Shared_InputError__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @/Shared/InputError */ "./resources/js/Shared/InputError.svelte");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* harmony import */ var D_www_sipro_spa_resources_js_Shared_SelectMulti_svelte_17_css_svelte_loader_cssPath_D_www_sipro_spa_resources_js_Shared_SelectMulti_svelte_17_css_D_www_sipro_spa_resources_js_Shared_SelectMulti_svelte__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./resources/js/Shared/SelectMulti.svelte.17.css!=!svelte-loader?cssPath=D:/www/sipro-spa/resources/js/Shared/SelectMulti.svelte.17.css!./resources/js/Shared/SelectMulti.svelte */ "./resources/js/Shared/SelectMulti.svelte.17.css!=!./node_modules/svelte-loader/index.js?cssPath=D:/www/sipro-spa/resources/js/Shared/SelectMulti.svelte.17.css!./resources/js/Shared/SelectMulti.svelte");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\SelectMulti.svelte generated by Svelte v3.48.0 */







function create_fragment(ctx) {
	let selectmulti;
	let updating_selectedValue;
	let t;
	let inputerror;
	let current;

	function selectmulti_selectedValue_binding(value) {
		/*selectmulti_selectedValue_binding*/ ctx[11](value);
	}

	let selectmulti_props = {
		isDisabled: /*disabled*/ ctx[7],
		inputAttributes: { id: /*id*/ ctx[1] },
		items: /*items*/ ctx[3],
		containerClasses: "items " + /*classes*/ ctx[2],
		isMulti: true,
		groupBy: /*groupBy*/ ctx[8],
		placeholder: /*required*/ ctx[6]
		? /*placeholder*/ ctx[4] + ' *'
		: /*placeholder*/ ctx[4],
		autocomplete: "off"
	};

	if (/*selectedValue*/ ctx[0] !== void 0) {
		selectmulti_props.selectedValue = /*selectedValue*/ ctx[0];
	}

	selectmulti = new svelte_select__WEBPACK_IMPORTED_MODULE_3__["default"]({ props: selectmulti_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(selectmulti, 'selectedValue', selectmulti_selectedValue_binding));
	inputerror = new _Shared_InputError__WEBPACK_IMPORTED_MODULE_4__["default"]({ props: { message: /*error*/ ctx[5] } });

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(selectmulti.$$.fragment);
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(inputerror.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(selectmulti, target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(inputerror, target, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			const selectmulti_changes = {};
			if (dirty & /*disabled*/ 128) selectmulti_changes.isDisabled = /*disabled*/ ctx[7];
			if (dirty & /*id*/ 2) selectmulti_changes.inputAttributes = { id: /*id*/ ctx[1] };
			if (dirty & /*items*/ 8) selectmulti_changes.items = /*items*/ ctx[3];
			if (dirty & /*classes*/ 4) selectmulti_changes.containerClasses = "items " + /*classes*/ ctx[2];

			if (dirty & /*required, placeholder*/ 80) selectmulti_changes.placeholder = /*required*/ ctx[6]
			? /*placeholder*/ ctx[4] + ' *'
			: /*placeholder*/ ctx[4];

			if (!updating_selectedValue && dirty & /*selectedValue*/ 1) {
				updating_selectedValue = true;
				selectmulti_changes.selectedValue = /*selectedValue*/ ctx[0];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_selectedValue = false);
			}

			selectmulti.$set(selectmulti_changes);
			const inputerror_changes = {};
			if (dirty & /*error*/ 32) inputerror_changes.message = /*error*/ ctx[5];
			inputerror.$set(inputerror_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(selectmulti.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(inputerror.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(selectmulti.$$.fragment, local);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(inputerror.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(selectmulti, detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(inputerror, detaching);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let $_;
	(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.component_subscribe)($$self, svelte_i18n__WEBPACK_IMPORTED_MODULE_2__._, $$value => $$invalidate(10, $_ = $$value));
	let { id = '' } = $$props;
	let { classes = '' } = $$props;
	let { selectedValue } = $$props;
	let { items } = $$props;
	let { placeholder } = $$props;
	let { error } = $$props;
	let { required } = $$props;
	let { disabled = false } = $$props;
	const groupBy = item => item.group;
	let select = null;

	(0,svelte__WEBPACK_IMPORTED_MODULE_1__.onMount)(() => {
		$$invalidate(9, select = document.getElementById(id));
	});

	function selectmulti_selectedValue_binding(value) {
		selectedValue = value;
		$$invalidate(0, selectedValue);
	}

	$$self.$$set = $$props => {
		if ('id' in $$props) $$invalidate(1, id = $$props.id);
		if ('classes' in $$props) $$invalidate(2, classes = $$props.classes);
		if ('selectedValue' in $$props) $$invalidate(0, selectedValue = $$props.selectedValue);
		if ('items' in $$props) $$invalidate(3, items = $$props.items);
		if ('placeholder' in $$props) $$invalidate(4, placeholder = $$props.placeholder);
		if ('error' in $$props) $$invalidate(5, error = $$props.error);
		if ('required' in $$props) $$invalidate(6, required = $$props.required);
		if ('disabled' in $$props) $$invalidate(7, disabled = $$props.disabled);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty & /*selectedValue*/ 1) {
			$: selectedValue;
		}

		if ($$self.$$.dirty & /*required, select, selectedValue, $_*/ 1601) {
			$: if (required && select != null) {
				selectedValue != undefined
				? select.setCustomValidity('')
				: select.setCustomValidity($_('Please fill out this field.'));
			}
		}
	};

	return [
		selectedValue,
		id,
		classes,
		items,
		placeholder,
		error,
		required,
		disabled,
		groupBy,
		select,
		$_,
		selectmulti_selectedValue_binding
	];
}

class SelectMulti_1 extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			id: 1,
			classes: 2,
			selectedValue: 0,
			items: 3,
			placeholder: 4,
			error: 5,
			required: 6,
			disabled: 7
		});
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SelectMulti_1);




/***/ }),

/***/ "./resources/js/Shared/Textarea.svelte":
/*!*********************************************!*\
  !*** ./resources/js/Shared/Textarea.svelte ***!
  \*********************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");
/* harmony import */ var fit_textarea__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! fit-textarea */ "./node_modules/fit-textarea/index.js");
/* harmony import */ var _Shared_InputError__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/Shared/InputError */ "./resources/js/Shared/InputError.svelte");
/* harmony import */ var _smui_textfield__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @smui/textfield */ "./node_modules/@smui/textfield/index.js");
/* harmony import */ var _smui_textfield_character_counter__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @smui/textfield/character-counter */ "./node_modules/@smui/textfield/character-counter/index.js");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_loader_lib_hot_api_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./node_modules/svelte-loader/lib/hot-api.js */ "./node_modules/svelte-loader/lib/hot-api.js");
/* harmony import */ var D_www_sipro_spa_node_modules_svelte_hmr_runtime_proxy_adapter_dom_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js */ "./node_modules/svelte-hmr/runtime/proxy-adapter-dom.js");
/* module decorator */ module = __webpack_require__.hmd(module);
/* resources\js\Shared\Textarea.svelte generated by Svelte v3.48.0 */








function create_else_block(ctx) {
	let textarea;
	let updating_value;
	let current;

	const textarea_spread_levels = [
		{ disabled: /*disabled*/ ctx[3] },
		{ textarea: true },
		{ input$maxlength: /*maxlength*/ ctx[5] },
		{ label: /*label*/ ctx[2] },
		/*props*/ ctx[7]
	];

	function textarea_value_binding_1(value) {
		/*textarea_value_binding_1*/ ctx[11](value);
	}

	let textarea_props = {
		$$slots: {
			internalCounter: [create_internalCounter_slot]
		},
		$$scope: { ctx }
	};

	for (let i = 0; i < textarea_spread_levels.length; i += 1) {
		textarea_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(textarea_props, textarea_spread_levels[i]);
	}

	if (/*value*/ ctx[0] !== void 0) {
		textarea_props.value = /*value*/ ctx[0];
	}

	textarea = new _smui_textfield__WEBPACK_IMPORTED_MODULE_3__["default"]({ props: textarea_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(textarea, 'value', textarea_value_binding_1));
	textarea.$on("input", /*input_handler_1*/ ctx[12]);

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(textarea.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(textarea, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const textarea_changes = (dirty & /*disabled, maxlength, label, props*/ 172)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(textarea_spread_levels, [
					dirty & /*disabled*/ 8 && { disabled: /*disabled*/ ctx[3] },
					textarea_spread_levels[1],
					dirty & /*maxlength*/ 32 && { input$maxlength: /*maxlength*/ ctx[5] },
					dirty & /*label*/ 4 && { label: /*label*/ ctx[2] },
					dirty & /*props*/ 128 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*props*/ ctx[7])
				])
			: {};

			if (dirty & /*$$scope, maxlength*/ 32800) {
				textarea_changes.$$scope = { dirty, ctx };
			}

			if (!updating_value && dirty & /*value*/ 1) {
				updating_value = true;
				textarea_changes.value = /*value*/ ctx[0];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value = false);
			}

			textarea.$set(textarea_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(textarea.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(textarea.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(textarea, detaching);
		}
	};
}

// (30:4) {#if sinContador == true}
function create_if_block_1(ctx) {
	let textarea;
	let updating_value;
	let current;

	const textarea_spread_levels = [
		{ disabled: /*disabled*/ ctx[3] },
		{ textarea: true },
		{ label: /*label*/ ctx[2] },
		/*props*/ ctx[7]
	];

	function textarea_value_binding(value) {
		/*textarea_value_binding*/ ctx[9](value);
	}

	let textarea_props = {};

	for (let i = 0; i < textarea_spread_levels.length; i += 1) {
		textarea_props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)(textarea_props, textarea_spread_levels[i]);
	}

	if (/*value*/ ctx[0] !== void 0) {
		textarea_props.value = /*value*/ ctx[0];
	}

	textarea = new _smui_textfield__WEBPACK_IMPORTED_MODULE_3__["default"]({ props: textarea_props });
	svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks.push(() => (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bind)(textarea, 'value', textarea_value_binding));
	textarea.$on("input", /*input_handler*/ ctx[10]);

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(textarea.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(textarea, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const textarea_changes = (dirty & /*disabled, label, props*/ 140)
			? (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_update)(textarea_spread_levels, [
					dirty & /*disabled*/ 8 && { disabled: /*disabled*/ ctx[3] },
					textarea_spread_levels[1],
					dirty & /*label*/ 4 && { label: /*label*/ ctx[2] },
					dirty & /*props*/ 128 && (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.get_spread_object)(/*props*/ ctx[7])
				])
			: {};

			if (!updating_value && dirty & /*value*/ 1) {
				updating_value = true;
				textarea_changes.value = /*value*/ ctx[0];
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.add_flush_callback)(() => updating_value = false);
			}

			textarea.$set(textarea_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(textarea.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(textarea.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(textarea, detaching);
		}
	};
}

// (34:12) <CharacterCounter slot="internalCounter">
function create_default_slot(ctx) {
	let t0;
	let t1;

	return {
		c() {
			t0 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)("0 / ");
			t1 = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.text)(/*maxlength*/ ctx[5]);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t0, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t1, anchor);
		},
		p(ctx, dirty) {
			if (dirty & /*maxlength*/ 32) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.set_data)(t1, /*maxlength*/ ctx[5]);
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t0);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t1);
		}
	};
}

// (34:12) 
function create_internalCounter_slot(ctx) {
	let charactercounter;
	let current;

	charactercounter = new _smui_textfield_character_counter__WEBPACK_IMPORTED_MODULE_4__["default"]({
			props: {
				slot: "internalCounter",
				$$slots: { default: [create_default_slot] },
				$$scope: { ctx }
			}
		});

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(charactercounter.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(charactercounter, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const charactercounter_changes = {};

			if (dirty & /*$$scope, maxlength*/ 32800) {
				charactercounter_changes.$$scope = { dirty, ctx };
			}

			charactercounter.$set(charactercounter_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(charactercounter.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(charactercounter.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(charactercounter, detaching);
		}
	};
}

// (39:0) {#if error}
function create_if_block(ctx) {
	let inputerror;
	let current;
	inputerror = new _Shared_InputError__WEBPACK_IMPORTED_MODULE_2__["default"]({ props: { message: /*error*/ ctx[1] } });

	return {
		c() {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.create_component)(inputerror.$$.fragment);
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.mount_component)(inputerror, target, anchor);
			current = true;
		},
		p(ctx, dirty) {
			const inputerror_changes = {};
			if (dirty & /*error*/ 2) inputerror_changes.message = /*error*/ ctx[1];
			inputerror.$set(inputerror_changes);
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(inputerror.$$.fragment, local);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(inputerror.$$.fragment, local);
			current = false;
		},
		d(detaching) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.destroy_component)(inputerror, detaching);
		}
	};
}

function create_fragment(ctx) {
	let div;
	let current_block_type_index;
	let if_block0;
	let t;
	let if_block1_anchor;
	let current;
	const if_block_creators = [create_if_block_1, create_else_block];
	const if_blocks = [];

	function select_block_type(ctx, dirty) {
		if (/*sinContador*/ ctx[4] == true) return 0;
		return 1;
	}

	current_block_type_index = select_block_type(ctx, -1);
	if_block0 = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
	let if_block1 = /*error*/ ctx[1] && create_if_block(ctx);

	return {
		c() {
			div = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.element)("div");
			if_block0.c();
			t = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.space)();
			if (if_block1) if_block1.c();
			if_block1_anchor = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.empty)();
		},
		m(target, anchor) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, div, anchor);
			if_blocks[current_block_type_index].m(div, null);
			/*div_binding*/ ctx[13](div);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, t, anchor);
			if (if_block1) if_block1.m(target, anchor);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.insert)(target, if_block1_anchor, anchor);
			current = true;
		},
		p(ctx, [dirty]) {
			let previous_block_index = current_block_type_index;
			current_block_type_index = select_block_type(ctx, dirty);

			if (current_block_type_index === previous_block_index) {
				if_blocks[current_block_type_index].p(ctx, dirty);
			} else {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_blocks[previous_block_index], 1, 1, () => {
					if_blocks[previous_block_index] = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
				if_block0 = if_blocks[current_block_type_index];

				if (!if_block0) {
					if_block0 = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
					if_block0.c();
				} else {
					if_block0.p(ctx, dirty);
				}

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0, 1);
				if_block0.m(div, null);
			}

			if (/*error*/ ctx[1]) {
				if (if_block1) {
					if_block1.p(ctx, dirty);

					if (dirty & /*error*/ 2) {
						(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					}
				} else {
					if_block1 = create_if_block(ctx);
					if_block1.c();
					(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1, 1);
					if_block1.m(if_block1_anchor.parentNode, if_block1_anchor);
				}
			} else if (if_block1) {
				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.group_outros)();

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1, 1, 1, () => {
					if_block1 = null;
				});

				(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.check_outros)();
			}
		},
		i(local) {
			if (current) return;
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_in)(if_block1);
			current = true;
		},
		o(local) {
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block0);
			(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.transition_out)(if_block1);
			current = false;
		},
		d(detaching) {
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(div);
			if_blocks[current_block_type_index].d();
			/*div_binding*/ ctx[13](null);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(t);
			if (if_block1) if_block1.d(detaching);
			if (detaching) (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.detach)(if_block1_anchor);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let props;
	const omit_props_names = ["error","id","value","label","disabled","sinContador","maxlength"];
	let $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names);
	let { error } = $$props;
	let { id } = $$props;
	let { value } = $$props;
	let { label } = $$props;
	let { disabled } = $$props;
	let { sinContador = false } = $$props;
	let { maxlength = 2000 } = $$props;
	let container;

	(0,svelte__WEBPACK_IMPORTED_MODULE_5__.onMount)(() => {
		fit_textarea__WEBPACK_IMPORTED_MODULE_1__["default"].watch(container.querySelector('textarea'));
		container.querySelector('textarea').setAttribute('id', id);
	});

	function textarea_value_binding(value$1) {
		value = value$1;
		$$invalidate(0, value);
	}

	function input_handler(event) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bubble.call(this, $$self, event);
	}

	function textarea_value_binding_1(value$1) {
		value = value$1;
		$$invalidate(0, value);
	}

	function input_handler_1(event) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bubble.call(this, $$self, event);
	}

	function div_binding($$value) {
		svelte_internal__WEBPACK_IMPORTED_MODULE_0__.binding_callbacks[$$value ? 'unshift' : 'push'](() => {
			container = $$value;
			$$invalidate(6, container);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)((0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.assign)({}, $$props), (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.exclude_internal_props)($$new_props));
		$$invalidate(14, $$restProps = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.compute_rest_props)($$props, omit_props_names));
		if ('error' in $$new_props) $$invalidate(1, error = $$new_props.error);
		if ('id' in $$new_props) $$invalidate(8, id = $$new_props.id);
		if ('value' in $$new_props) $$invalidate(0, value = $$new_props.value);
		if ('label' in $$new_props) $$invalidate(2, label = $$new_props.label);
		if ('disabled' in $$new_props) $$invalidate(3, disabled = $$new_props.disabled);
		if ('sinContador' in $$new_props) $$invalidate(4, sinContador = $$new_props.sinContador);
		if ('maxlength' in $$new_props) $$invalidate(5, maxlength = $$new_props.maxlength);
	};

	$$self.$$.update = () => {
		$: $$invalidate(7, props = {
			...$$restProps,
			class: `w-full block bg-white ${$$restProps.class || ''}`
		});
	};

	return [
		value,
		error,
		label,
		disabled,
		sinContador,
		maxlength,
		container,
		props,
		id,
		textarea_value_binding,
		input_handler,
		textarea_value_binding_1,
		input_handler_1,
		div_binding
	];
}

class Textarea_1 extends svelte_internal__WEBPACK_IMPORTED_MODULE_0__.SvelteComponent {
	constructor(options) {
		super();

		(0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.init)(this, options, instance, create_fragment, svelte_internal__WEBPACK_IMPORTED_MODULE_0__.safe_not_equal, {
			error: 1,
			id: 8,
			value: 0,
			label: 2,
			disabled: 3,
			sinContador: 4,
			maxlength: 5
		});
	}
}

if (module && module.hot) {}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Textarea_1);



/***/ }),

/***/ "./node_modules/svelte-select/src/utils/debounce.js":
/*!**********************************************************!*\
  !*** ./node_modules/svelte-select/src/utils/debounce.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ debounce)
/* harmony export */ });
function debounce(func, wait, immediate) {
  let timeout;

  return function executedFunction() {
    let context = this;
    let args = arguments;

    let later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };

    let callNow = immediate && !timeout;

    clearTimeout(timeout);

    timeout = setTimeout(later, wait);

    if (callNow) func.apply(context, args);
  };
};


/***/ }),

/***/ "./node_modules/svelte-select/src/utils/isOutOfViewport.js":
/*!*****************************************************************!*\
  !*** ./node_modules/svelte-select/src/utils/isOutOfViewport.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* export default binding */ __WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(elem) {
  const bounding = elem.getBoundingClientRect();
  const out = {};

  out.top = bounding.top < 0;
  out.left = bounding.left < 0;
  out.bottom = bounding.bottom > (window.innerHeight || document.documentElement.clientHeight);
  out.right = bounding.right > (window.innerWidth || document.documentElement.clientWidth);
  out.any = out.top || out.left || out.bottom || out.right;

  return out;
};


/***/ }),

/***/ "./node_modules/@smui/button/GroupItem.js":
/*!************************************************!*\
  !*** ./node_modules/@smui/button/GroupItem.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ GroupItem)
/* harmony export */ });
function GroupItem(
  node,
  {
    addClass = (className) => node.classList.add(className),
    removeClass = (className) => node.classList.remove(className),
  } = {}
) {
  addClass('smui-button__group-item');

  return {
    destroy() {
      removeClass('smui-button__group-item');
    },
  };
}


/***/ }),

/***/ "./node_modules/@smui/button/index.js":
/*!********************************************!*\
  !*** ./node_modules/@smui/button/index.js ***!
  \********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Group": () => (/* reexport safe */ _Group_svelte__WEBPACK_IMPORTED_MODULE_1__["default"]),
/* harmony export */   "GroupItem": () => (/* reexport safe */ _GroupItem_js__WEBPACK_IMPORTED_MODULE_2__["default"]),
/* harmony export */   "Icon": () => (/* reexport safe */ _smui_common_CommonIcon_svelte__WEBPACK_IMPORTED_MODULE_4__["default"]),
/* harmony export */   "Label": () => (/* reexport safe */ _smui_common_CommonLabel_svelte__WEBPACK_IMPORTED_MODULE_3__["default"]),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Button_svelte__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Button.svelte */ "./node_modules/@smui/button/Button.svelte");
/* harmony import */ var _Group_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Group.svelte */ "./node_modules/@smui/button/Group.svelte");
/* harmony import */ var _GroupItem_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./GroupItem.js */ "./node_modules/@smui/button/GroupItem.js");
/* harmony import */ var _smui_common_CommonLabel_svelte__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @smui/common/CommonLabel.svelte */ "./node_modules/@smui/common/CommonLabel.svelte");
/* harmony import */ var _smui_common_CommonIcon_svelte__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @smui/common/CommonIcon.svelte */ "./node_modules/@smui/common/CommonIcon.svelte");







/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_Button_svelte__WEBPACK_IMPORTED_MODULE_0__["default"]);




/***/ }),

/***/ "./node_modules/@smui/common/classAdderBuilder.js":
/*!********************************************************!*\
  !*** ./node_modules/@smui/common/classAdderBuilder.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "classAdderBuilder": () => (/* binding */ classAdderBuilder)
/* harmony export */ });
/* harmony import */ var _ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ClassAdder.svelte */ "./node_modules/@smui/common/ClassAdder.svelte");


const defaults = { ..._ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__.internals };

function classAdderBuilder(props) {
  function Component(...args) {
    Object.assign(_ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__.internals, defaults, props);
    return new _ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__["default"](...args);
  }

  Component.prototype = _ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__["default"];

  // SSR support
  if (_ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__["default"].$$render) {
    Component.$$render = (...args) =>
      Object.assign(_ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__.internals, defaults, props) && _ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__["default"].$$render(...args);
  }
  if (_ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__["default"].render) {
    Component.render = (...args) =>
      Object.assign(_ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__.internals, defaults, props) && _ClassAdder_svelte__WEBPACK_IMPORTED_MODULE_0__["default"].render(...args);
  }

  return Component;
}


/***/ }),

/***/ "./node_modules/@smui/common/classMap.js":
/*!***********************************************!*\
  !*** ./node_modules/@smui/common/classMap.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "classMap": () => (/* binding */ classMap)
/* harmony export */ });
function classMap(classObj) {
  return Object.entries(classObj)
    .filter(([name, value]) => name !== '' && value)
    .map(([name]) => name)
    .join(' ');
}


/***/ }),

/***/ "./node_modules/@smui/common/dispatch.js":
/*!***********************************************!*\
  !*** ./node_modules/@smui/common/dispatch.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "dispatch": () => (/* binding */ dispatch)
/* harmony export */ });
function dispatch(
  element,
  eventType,
  detail = {},
  eventInit = { bubbles: true }
) {
  if (typeof Event !== 'undefined' && element) {
    const event = new Event(eventType, eventInit);
    event.detail = detail;
    const el = 'getElement' in element ? element.getElement() : element;
    el.dispatchEvent(event);
    return event;
  }
}


/***/ }),

/***/ "./node_modules/@smui/common/exclude.js":
/*!**********************************************!*\
  !*** ./node_modules/@smui/common/exclude.js ***!
  \**********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "exclude": () => (/* binding */ exclude)
/* harmony export */ });
function exclude(obj, keys) {
  let names = Object.getOwnPropertyNames(obj);
  const newObj = {};

  for (let i = 0; i < names.length; i++) {
    const name = names[i];
    const cashIndex = name.indexOf('$');
    if (
      cashIndex !== -1 &&
      keys.indexOf(name.substring(0, cashIndex + 1)) !== -1
    ) {
      continue;
    }
    if (keys.indexOf(name) !== -1) {
      continue;
    }
    newObj[name] = obj[name];
  }

  return newObj;
}


/***/ }),

/***/ "./node_modules/@smui/common/forwardEventsBuilder.js":
/*!***********************************************************!*\
  !*** ./node_modules/@smui/common/forwardEventsBuilder.js ***!
  \***********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "forwardEventsBuilder": () => (/* binding */ forwardEventsBuilder)
/* harmony export */ });
/* harmony import */ var svelte_internal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte/internal */ "./node_modules/svelte/internal/index.mjs");


// Match modifiers on DOM events.
const oldModifierRegex = /^[a-z]+(?::(?:preventDefault|stopPropagation|passive|nonpassive|capture|once|self))+$/;
// Match modifiers on other events.
const newModifierRegex = /^[^$]+(?:\$(?:preventDefault|stopPropagation|passive|nonpassive|capture|once|self))+$/;

function forwardEventsBuilder(component) {
  // This is our pseudo $on function. It is defined on component mount.
  let $on;
  // This is a list of events bound before mount.
  let events = [];
  // This is the original component $on function.
  const componentOn = component.$on;

  // And we override the $on function to forward all bound events.
  component.$on = (fullEventType, callback) => {
    let eventType = fullEventType;
    let destructor = () => {};
    if ($on) {
      // The event was bound programmatically.
      destructor = $on(eventType, callback);
    } else {
      // The event was bound before mount by Svelte.
      events.push([eventType, callback]);
    }
    const oldModifierMatch = eventType.match(oldModifierRegex);
    const newModifierMatch = eventType.match(newModifierRegex);
    const modifierMatch = oldModifierMatch || newModifierMatch;

    if (oldModifierMatch && console) {
      console.warn(
        'Event modifiers in SMUI now use "$" instead of ":", so that all events can be bound with modifiers. Please update your event binding: ',
        eventType
      );
    }

    if (modifierMatch) {
      // Remove modifiers from the real event.
      const parts = eventType.split(oldModifierMatch ? ':' : '$');
      eventType = parts[0];
    }

    // Call the original $on function.
    const componentDestructor = componentOn.call(
      component,
      eventType,
      callback
    );

    return (...args) => {
      destructor();
      return componentDestructor(...args);
    };
  };

  function forward(e) {
    // Internally bubble the event up from Svelte components.
    (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.bubble)(component, e);
  }

  return (node) => {
    const destructors = [];
    const forwardDestructors = {};

    // This function is responsible for forwarding all bound events.
    $on = (fullEventType, callback) => {
      let eventType = fullEventType;
      let handler = callback;
      // DOM addEventListener options argument.
      let options = false;
      const oldModifierMatch = eventType.match(oldModifierRegex);
      const newModifierMatch = eventType.match(newModifierRegex);
      const modifierMatch = oldModifierMatch || newModifierMatch;
      if (modifierMatch) {
        // Parse the event modifiers.
        // Supported modifiers:
        // - preventDefault
        // - stopPropagation
        // - passive
        // - nonpassive
        // - capture
        // - once
        const parts = eventType.split(oldModifierMatch ? ':' : '$');
        eventType = parts[0];
        options = Object.fromEntries(parts.slice(1).map((mod) => [mod, true]));
        if (options.nonpassive) {
          options.passive = false;
          delete options.nonpassive;
        }
        if (options.preventDefault) {
          handler = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.prevent_default)(handler);
          delete options.preventDefault;
        }
        if (options.stopPropagation) {
          handler = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.stop_propagation)(handler);
          delete options.stopPropagation;
        }
      }

      // Listen for the event directly, with the given options.
      const off = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(node, eventType, handler, options);
      const destructor = () => {
        off();
        const idx = destructors.indexOf(destructor);
        if (idx > -1) {
          destructors.splice(idx, 1);
        }
      };

      destructors.push(destructor);

      // Forward the event from Svelte.
      if (!eventType in forwardDestructors) {
        forwardDestructors[eventType] = (0,svelte_internal__WEBPACK_IMPORTED_MODULE_0__.listen)(node, eventType, forward);
      }

      return destructor;
    };

    for (let i = 0; i < events.length; i++) {
      // Listen to all the events added before mount.
      $on(events[i][0], events[i][1]);
    }

    return {
      destroy: () => {
        // Remove all event listeners.
        for (let i = 0; i < destructors.length; i++) {
          destructors[i]();
        }

        // Remove all event forwarders.
        for (let entry of Object.entries(forwardDestructors)) {
          entry[1]();
        }
      },
    };
  };
}


/***/ }),

/***/ "./node_modules/@smui/common/internal.js":
/*!***********************************************!*\
  !*** ./node_modules/@smui/common/internal.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "classAdderBuilder": () => (/* reexport safe */ _classAdderBuilder_js__WEBPACK_IMPORTED_MODULE_0__.classAdderBuilder),
/* harmony export */   "classMap": () => (/* reexport safe */ _classMap_js__WEBPACK_IMPORTED_MODULE_1__.classMap),
/* harmony export */   "dispatch": () => (/* reexport safe */ _dispatch_js__WEBPACK_IMPORTED_MODULE_2__.dispatch),
/* harmony export */   "exclude": () => (/* reexport safe */ _exclude_js__WEBPACK_IMPORTED_MODULE_3__.exclude),
/* harmony export */   "forwardEventsBuilder": () => (/* reexport safe */ _forwardEventsBuilder_js__WEBPACK_IMPORTED_MODULE_4__.forwardEventsBuilder),
/* harmony export */   "prefixFilter": () => (/* reexport safe */ _prefixFilter_js__WEBPACK_IMPORTED_MODULE_5__.prefixFilter),
/* harmony export */   "useActions": () => (/* reexport safe */ _useActions_js__WEBPACK_IMPORTED_MODULE_6__.useActions)
/* harmony export */ });
/* harmony import */ var _classAdderBuilder_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./classAdderBuilder.js */ "./node_modules/@smui/common/classAdderBuilder.js");
/* harmony import */ var _classMap_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./classMap.js */ "./node_modules/@smui/common/classMap.js");
/* harmony import */ var _dispatch_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./dispatch.js */ "./node_modules/@smui/common/dispatch.js");
/* harmony import */ var _exclude_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./exclude.js */ "./node_modules/@smui/common/exclude.js");
/* harmony import */ var _forwardEventsBuilder_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./forwardEventsBuilder.js */ "./node_modules/@smui/common/forwardEventsBuilder.js");
/* harmony import */ var _prefixFilter_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./prefixFilter.js */ "./node_modules/@smui/common/prefixFilter.js");
/* harmony import */ var _useActions_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./useActions.js */ "./node_modules/@smui/common/useActions.js");









/***/ }),

/***/ "./node_modules/@smui/common/prefixFilter.js":
/*!***************************************************!*\
  !*** ./node_modules/@smui/common/prefixFilter.js ***!
  \***************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "prefixFilter": () => (/* binding */ prefixFilter)
/* harmony export */ });
function prefixFilter(obj, prefix) {
  let names = Object.getOwnPropertyNames(obj);
  const newObj = {};

  for (let i = 0; i < names.length; i++) {
    const name = names[i];
    if (name.substring(0, prefix.length) === prefix) {
      newObj[name.substring(prefix.length)] = obj[name];
    }
  }

  return newObj;
}


/***/ }),

/***/ "./node_modules/@smui/common/useActions.js":
/*!*************************************************!*\
  !*** ./node_modules/@smui/common/useActions.js ***!
  \*************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "useActions": () => (/* binding */ useActions)
/* harmony export */ });
function useActions(node, actions) {
  let objects = [];

  if (actions) {
    for (let i = 0; i < actions.length; i++) {
      const isArray = Array.isArray(actions[i]);
      const action = isArray ? actions[i][0] : actions[i];
      if (isArray && actions[i].length > 1) {
        objects.push(action(node, actions[i][1]));
      } else {
        objects.push(action(node));
      }
    }
  }

  return {
    update(actions) {
      if (((actions && actions.length) || 0) != objects.length) {
        throw new Error('You must not change the length of an actions array.');
      }

      if (actions) {
        for (let i = 0; i < actions.length; i++) {
          if (objects[i] && 'update' in objects[i]) {
            const isArray = Array.isArray(actions[i]);
            if (isArray && actions[i].length > 1) {
              objects[i].update(actions[i][1]);
            } else {
              objects[i].update();
            }
          }
        }
      }
    },

    destroy() {
      for (let i = 0; i < objects.length; i++) {
        if (objects[i] && 'destroy' in objects[i]) {
          objects[i].destroy();
        }
      }
    },
  };
}


/***/ }),

/***/ "./node_modules/@smui/dialog/Actions.js":
/*!**********************************************!*\
  !*** ./node_modules/@smui/dialog/Actions.js ***!
  \**********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _smui_common_Div_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/common/Div.svelte */ "./node_modules/@smui/common/Div.svelte");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__.classAdderBuilder)({
  class: 'mdc-dialog__actions',
  component: _smui_common_Div_svelte__WEBPACK_IMPORTED_MODULE_1__["default"],
  classMap: {
    'smui-dialog__actions--reversed': 'SMUI:dialog:actions:reversed',
  },
  contexts: {
    'SMUI:button:context': 'dialog:action',
  },
}));


/***/ }),

/***/ "./node_modules/@smui/dialog/Content.js":
/*!**********************************************!*\
  !*** ./node_modules/@smui/dialog/Content.js ***!
  \**********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _smui_common_Div_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/common/Div.svelte */ "./node_modules/@smui/common/Div.svelte");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__.classAdderBuilder)({
  class: 'mdc-dialog__content',
  component: _smui_common_Div_svelte__WEBPACK_IMPORTED_MODULE_1__["default"],
}));


/***/ }),

/***/ "./node_modules/@smui/dialog/Header.js":
/*!*********************************************!*\
  !*** ./node_modules/@smui/dialog/Header.js ***!
  \*********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _smui_common_Div_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/common/Div.svelte */ "./node_modules/@smui/common/Div.svelte");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__.classAdderBuilder)({
  class: 'mdc-dialog__header',
  component: _smui_common_Div_svelte__WEBPACK_IMPORTED_MODULE_1__["default"],
  contexts: {
    'SMUI:icon-button:context': 'dialog:header',
  },
}));


/***/ }),

/***/ "./node_modules/@smui/dialog/InitialFocus.js":
/*!***************************************************!*\
  !*** ./node_modules/@smui/dialog/InitialFocus.js ***!
  \***************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ InitialFocus)
/* harmony export */ });
function InitialFocus(node) {
  node.setAttribute('data-mdc-dialog-initial-focus', '');

  return {
    destroy() {
      node.removeAttribute('data-mdc-dialog-initial-focus');
    },
  };
}


/***/ }),

/***/ "./node_modules/@smui/dialog/Title.js":
/*!********************************************!*\
  !*** ./node_modules/@smui/dialog/Title.js ***!
  \********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _smui_common_H2_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/common/H2.svelte */ "./node_modules/@smui/common/H2.svelte");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__.classAdderBuilder)({
  class: 'mdc-dialog__title',
  component: _smui_common_H2_svelte__WEBPACK_IMPORTED_MODULE_1__["default"],
}));


/***/ }),

/***/ "./node_modules/@smui/dialog/index.js":
/*!********************************************!*\
  !*** ./node_modules/@smui/dialog/index.js ***!
  \********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Actions": () => (/* reexport safe */ _Actions_js__WEBPACK_IMPORTED_MODULE_4__["default"]),
/* harmony export */   "Content": () => (/* reexport safe */ _Content_js__WEBPACK_IMPORTED_MODULE_3__["default"]),
/* harmony export */   "Header": () => (/* reexport safe */ _Header_js__WEBPACK_IMPORTED_MODULE_1__["default"]),
/* harmony export */   "InitialFocus": () => (/* reexport safe */ _InitialFocus_js__WEBPACK_IMPORTED_MODULE_5__["default"]),
/* harmony export */   "Title": () => (/* reexport safe */ _Title_js__WEBPACK_IMPORTED_MODULE_2__["default"]),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Dialog_svelte__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Dialog.svelte */ "./node_modules/@smui/dialog/Dialog.svelte");
/* harmony import */ var _Header_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Header.js */ "./node_modules/@smui/dialog/Header.js");
/* harmony import */ var _Title_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Title.js */ "./node_modules/@smui/dialog/Title.js");
/* harmony import */ var _Content_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Content.js */ "./node_modules/@smui/dialog/Content.js");
/* harmony import */ var _Actions_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Actions.js */ "./node_modules/@smui/dialog/Actions.js");
/* harmony import */ var _InitialFocus_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./InitialFocus.js */ "./node_modules/@smui/dialog/InitialFocus.js");








/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_Dialog_svelte__WEBPACK_IMPORTED_MODULE_0__["default"]);




/***/ }),

/***/ "./node_modules/@smui/ripple/Ripple.js":
/*!*********************************************!*\
  !*** ./node_modules/@smui/ripple/Ripple.js ***!
  \*********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Ripple)
/* harmony export */ });
/* harmony import */ var _material_ripple__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @material/ripple */ "./node_modules/@material/ripple/foundation.js");
/* harmony import */ var _material_ripple__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @material/ripple */ "./node_modules/@material/ripple/util.js");
/* harmony import */ var _material_dom__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @material/dom */ "./node_modules/@material/dom/events.js");
/* harmony import */ var _material_dom__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @material/dom */ "./node_modules/@material/dom/ponyfill.js");
/* harmony import */ var svelte__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! svelte */ "./node_modules/svelte/index.mjs");



const { applyPassive } = _material_dom__WEBPACK_IMPORTED_MODULE_1__;
const { matches } = _material_dom__WEBPACK_IMPORTED_MODULE_2__;

function Ripple(
  node,
  {
    ripple = true,
    surface = false,
    unbounded = false,
    disabled = false,
    color = null,
    active = null,
    eventTarget = null,
    activeTarget = null,
    addClass = (className) => node.classList.add(className),
    removeClass = (className) => node.classList.remove(className),
    addStyle = (name, value) => node.style.setProperty(name, value),
    initPromise = Promise.resolve(),
  } = {}
) {
  let instance;
  let addLayoutListener = (0,svelte__WEBPACK_IMPORTED_MODULE_0__.getContext)('SMUI:addLayoutListener');
  let removeLayoutListener;
  let oldActive = active;
  let oldEventTarget = eventTarget;
  let oldActiveTarget = activeTarget;

  function handleProps() {
    if (surface) {
      addClass('mdc-ripple-surface');
      if (color === 'primary') {
        addClass('smui-ripple-surface--primary');
        removeClass('smui-ripple-surface--secondary');
      } else if (color === 'secondary') {
        removeClass('smui-ripple-surface--primary');
        addClass('smui-ripple-surface--secondary');
      } else {
        removeClass('smui-ripple-surface--primary');
        removeClass('smui-ripple-surface--secondary');
      }
    }

    // Handle activation first.
    if (instance && oldActive !== active) {
      oldActive = active;
      if (active) {
        instance.activate();
      } else if (active === false) {
        instance.deactivate();
      }
    }

    // Then create/destroy an instance.
    if (ripple && !instance) {
      instance = new _material_ripple__WEBPACK_IMPORTED_MODULE_3__.MDCRippleFoundation({
        addClass,
        browserSupportsCssVars: () => _material_ripple__WEBPACK_IMPORTED_MODULE_4__.supportsCssVariables(window),
        computeBoundingRect: () => node.getBoundingClientRect(),
        containsEventTarget: (target) => node.contains(target),
        deregisterDocumentInteractionHandler: (evtType, handler) =>
          document.documentElement.removeEventListener(
            evtType,
            handler,
            applyPassive()
          ),
        deregisterInteractionHandler: (evtType, handler) =>
          (eventTarget || node).removeEventListener(
            evtType,
            handler,
            applyPassive()
          ),
        deregisterResizeHandler: (handler) =>
          window.removeEventListener('resize', handler),
        getWindowPageOffset: () => ({
          x: window.pageXOffset,
          y: window.pageYOffset,
        }),
        isSurfaceActive: () =>
          active == null ? matches(activeTarget || node, ':active') : active,
        isSurfaceDisabled: () => !!disabled,
        isUnbounded: () => !!unbounded,
        registerDocumentInteractionHandler: (evtType, handler) =>
          document.documentElement.addEventListener(
            evtType,
            handler,
            applyPassive()
          ),
        registerInteractionHandler: (evtType, handler) =>
          (eventTarget || node).addEventListener(
            evtType,
            handler,
            applyPassive()
          ),
        registerResizeHandler: (handler) =>
          window.addEventListener('resize', handler),
        removeClass,
        updateCssVariable: addStyle,
      });

      initPromise.then(() => {
        instance.init();
        instance.setUnbounded(unbounded);
      });
    } else if (instance && !ripple) {
      initPromise.then(() => {
        instance.destroy();
        instance = null;
      });
    }

    // Now handle event/active targets
    if (
      instance &&
      (oldEventTarget !== eventTarget || oldActiveTarget !== activeTarget)
    ) {
      oldEventTarget = eventTarget;
      oldActiveTarget = activeTarget;

      instance.destroy();
      requestAnimationFrame(() => {
        if (instance) {
          instance.init();
          instance.setUnbounded(unbounded);
        }
      });
    }

    if (!ripple && unbounded) {
      addClass('mdc-ripple-upgraded--unbounded');
    }
  }

  handleProps();

  if (addLayoutListener) {
    removeLayoutListener = addLayoutListener(layout);
  }

  function layout() {
    if (instance) {
      instance.layout();
    }
  }

  return {
    update(props) {
      ({
        ripple,
        surface,
        unbounded,
        disabled,
        color,
        active,
        eventTarget,
        activeTarget,
        addClass,
        removeClass,
        addStyle,
        initPromise,
      } = {
        ripple: true,
        surface: false,
        unbounded: false,
        disabled: false,
        color: null,
        active: null,
        eventTarget: null,
        activeTarget: null,
        addClass: (className) => node.classList.add(className),
        removeClass: (className) => node.classList.remove(className),
        addStyle: (name, value) => node.style.setProperty(name, value),
        initPromise: Promise.resolve(),
        ...props,
      });
      handleProps();
    },

    destroy() {
      if (instance) {
        instance.destroy();
        instance = null;
        removeClass('mdc-ripple-surface');
        removeClass('smui-ripple-surface--primary');
        removeClass('smui-ripple-surface--secondary');
      }

      if (removeLayoutListener) {
        removeLayoutListener();
      }
    },
  };
}


/***/ }),

/***/ "./node_modules/@smui/ripple/index.js":
/*!********************************************!*\
  !*** ./node_modules/@smui/ripple/index.js ***!
  \********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Ripple_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Ripple.js */ "./node_modules/@smui/ripple/Ripple.js");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_Ripple_js__WEBPACK_IMPORTED_MODULE_0__["default"]);


/***/ }),

/***/ "./node_modules/@smui/textfield/HelperLine.js":
/*!****************************************************!*\
  !*** ./node_modules/@smui/textfield/HelperLine.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _smui_common_Div_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/common/Div.svelte */ "./node_modules/@smui/common/Div.svelte");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__.classAdderBuilder)({
  class: 'mdc-text-field-helper-line',
  component: _smui_common_Div_svelte__WEBPACK_IMPORTED_MODULE_1__["default"],
}));


/***/ }),

/***/ "./node_modules/@smui/textfield/Prefix.js":
/*!************************************************!*\
  !*** ./node_modules/@smui/textfield/Prefix.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _smui_common_Span_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/common/Span.svelte */ "./node_modules/@smui/common/Span.svelte");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__.classAdderBuilder)({
  class: 'mdc-text-field__affix mdc-text-field__affix--prefix',
  component: _smui_common_Span_svelte__WEBPACK_IMPORTED_MODULE_1__["default"],
}));


/***/ }),

/***/ "./node_modules/@smui/textfield/Suffix.js":
/*!************************************************!*\
  !*** ./node_modules/@smui/textfield/Suffix.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @smui/common/internal.js */ "./node_modules/@smui/common/internal.js");
/* harmony import */ var _smui_common_Span_svelte__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @smui/common/Span.svelte */ "./node_modules/@smui/common/Span.svelte");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_smui_common_internal_js__WEBPACK_IMPORTED_MODULE_0__.classAdderBuilder)({
  class: 'mdc-text-field__affix mdc-text-field__affix--suffix',
  component: _smui_common_Span_svelte__WEBPACK_IMPORTED_MODULE_1__["default"],
}));


/***/ }),

/***/ "./node_modules/@smui/textfield/character-counter/index.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@smui/textfield/character-counter/index.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _CharacterCounter_svelte__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CharacterCounter.svelte */ "./node_modules/@smui/textfield/character-counter/CharacterCounter.svelte");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_CharacterCounter_svelte__WEBPACK_IMPORTED_MODULE_0__["default"]);


/***/ }),

/***/ "./node_modules/@smui/textfield/index.js":
/*!***********************************************!*\
  !*** ./node_modules/@smui/textfield/index.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "HelperLine": () => (/* reexport safe */ _HelperLine_js__WEBPACK_IMPORTED_MODULE_3__["default"]),
/* harmony export */   "Input": () => (/* reexport safe */ _Input_svelte__WEBPACK_IMPORTED_MODULE_4__["default"]),
/* harmony export */   "Prefix": () => (/* reexport safe */ _Prefix_js__WEBPACK_IMPORTED_MODULE_1__["default"]),
/* harmony export */   "Suffix": () => (/* reexport safe */ _Suffix_js__WEBPACK_IMPORTED_MODULE_2__["default"]),
/* harmony export */   "Textarea": () => (/* reexport safe */ _Textarea_svelte__WEBPACK_IMPORTED_MODULE_5__["default"]),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Textfield_svelte__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Textfield.svelte */ "./node_modules/@smui/textfield/Textfield.svelte");
/* harmony import */ var _Prefix_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Prefix.js */ "./node_modules/@smui/textfield/Prefix.js");
/* harmony import */ var _Suffix_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Suffix.js */ "./node_modules/@smui/textfield/Suffix.js");
/* harmony import */ var _HelperLine_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./HelperLine.js */ "./node_modules/@smui/textfield/HelperLine.js");
/* harmony import */ var _Input_svelte__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./Input.svelte */ "./node_modules/@smui/textfield/Input.svelte");
/* harmony import */ var _Textarea_svelte__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./Textarea.svelte */ "./node_modules/@smui/textfield/Textarea.svelte");








/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_Textfield_svelte__WEBPACK_IMPORTED_MODULE_0__["default"]);




/***/ }),

/***/ "./node_modules/fit-textarea/index.js":
/*!********************************************!*\
  !*** ./node_modules/fit-textarea/index.js ***!
  \********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const mirrors = new WeakMap();
const mirrorDefaults = {
    height: '',
    position: 'fixed',
    top: '-500px',
    left: '-500px'
};
function getFieldData(source) {
    const cached = mirrors.get(source);
    if (cached) {
        return cached;
    }
    const sourceStyle = getComputedStyle(source);
    const mirror = source.cloneNode();
    for (const property of sourceStyle) {
        mirror.style.setProperty(property, sourceStyle.getPropertyValue(property));
    }
    Object.assign(mirror.style, mirrorDefaults);
    const data = {
        mirror,
        additionalHeight: parseFloat(sourceStyle.borderTopWidth) +
            parseFloat(sourceStyle.borderBottomWidth)
    };
    // Cache it and automatically de-cache it when something relevant happens
    mirrors.set(source, data);
    const regenerateMirror = () => {
        mirrors.delete(source);
    };
    const settings = {
        once: true,
        passive: true
    };
    window.addEventListener('resize', regenerateMirror, settings);
    window.addEventListener('load', regenerateMirror, settings);
    return data;
}
function fitTextarea(textarea) {
    const { mirror, additionalHeight } = getFieldData(textarea);
    // Match content and reset height
    mirror.value = textarea.value;
    document.body.append(mirror);
    const desiredHeight = String(mirror.scrollHeight + additionalHeight) + 'px';
    if (textarea.style.minHeight !== desiredHeight) {
        textarea.style.minHeight = desiredHeight;
    }
    mirror.remove();
}
function listener(event) {
    fitTextarea(event.target);
}
function watchAndFit(elements) {
    if (typeof elements === 'string') {
        elements = document.querySelectorAll(elements);
    }
    else if (elements instanceof HTMLTextAreaElement) {
        elements = [elements];
    }
    for (const element of elements) {
        element.addEventListener('input', listener);
        if (element.form) {
            element.form.addEventListener('reset', listener);
        }
        fitTextarea(element);
    }
}
fitTextarea.watch = watchAndFit;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (fitTextarea);


/***/ })

}]);