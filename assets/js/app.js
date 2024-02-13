// Jquery - jQuery is a popular and feature-rich JavaScript library. Learn more: https://jquery.com/
window.jQuery = window.$ = require('jquery');

// Bootstrap - The most popular framework uses as the foundation. Learn more: http://getbootstrap.com
window.bootstrap = require('bootstrap');

// Popper.js - Tooltip & Popover Positioning Engine used by Bootstrap. Learn more: https://popper.js.org
window.Popper = require('@popperjs/core');

// Wnumb - Number & Money formatting. Learn more: https://refreshless.com/wnumb/
window.wNumb = require('wnumb');

// Moment - Parse, validate, manipulate, and display dates and times in JavaScript. Learn more: https://momentjs.com/
window.moment = require('moment');

// ES6-Shim - ECMAScript 6 compatibility shims for legacy JS engines.  Learn more: https://github.com/paulmillr/es6-shim
require("es6-shim/es6-shim.min");

// Apexcharts - mBdern charting library that helps developers to create beautiful and interactive visualizations for web pages: https://apexcharts.com/
window.ApexCharts = require('apexcharts/dist/apexcharts.min');

// FormValidation - Best premium validation library for JavaScript. Zero dependencies. Learn more: https://formvalidation.io/
window.FormValidation = require('./craft/vendors/plugins/formvalidation/dist/js/FormValidation.full.min.js');
window.FormValidation.plugins.Bootstrap5 = require('./craft/vendors/plugins/formvalidation/dist/amd/plugins/Bootstrap5.js').default;

// Date Range Picker - A JavaScript component for choosing date ranges, dates and times: https://www.daterangepicker.com/
require('bootstrap-daterangepicker/daterangepicker.js');

// Bootstrap Maxlength - This plugin integrates by default with Twitter bootstrap using badges to display the maximum length of the field where the user is inserting text: https://github.com/mimo84/bootstrap-maxlength
require('bootstrap-maxlength/src/bootstrap-maxlength.js');

// Bootstrap Multiselectsplitter - Transforms a <select> containing one or more <optgroup> in two chained <select>: https://github.com/poolerMF/bootstrap-multiselectsplitter/
require('bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min');

// Select2 - Select2 is a jQuery based replacement for select boxes: https://select2.org/
require('select2/dist/js/select2.full.min');
require('./craft/vendors/plugins/select2.init.js');

// Tempus Dominus is the successor to the very popular Eonasdan/bootstrap-datetimepicker. The plugin provide a robust date and time picker designed to integrate into your Bootstrap project.
window.tempusDominus = require('@eonasdan/tempus-dominus/dist/js/tempus-dominus.min');
require('@eonasdan/tempus-dominus/dist/plugins/customDateFormat');

// Flatpickr - is a lightweight and powerful datetime picker.
require('flatpickr/dist/flatpickr');
require('flatpickr/dist/l10n/ar');

// Inputmask - is a javascript library which creates an input mask: https://github.com/RobinHerbots/Inputmask
require('inputmask/dist/inputmask.js');
require('inputmask/dist/bindings/inputmask.binding.js');

// noUiSlider - is a lightweight range slider with multi-touch support and a ton of features. It supports non-linear ranges, requires no external dependencies: https://refreshless.com/nouislider/
window.noUiSlider = require('nouislider/dist/nouislider.min');

// The autosize - function accepts a single textarea element, or an array or array-like object (such as a NodeList or jQuery collection) of textarea elements: https://www.jacklmoore.com/autosize/
window.autosize = require('autosize/dist/autosize.min');

// Clipboard - Copy text to the clipboard shouldn't be hard. It shouldn't require dozens of steps to configure or hundreds of KBs to load: https://clipboardjs.com/
window.ClipboardJS = require('clipboard/dist/clipboard.min');

// DropzoneJS -  is an open source library that provides drag'n'drop file uploads with image previews: https://www.dropzonejs.com/
window.Dropzone = require('dropzone/dist/dropzone-min').default;

// Quill - is a free, open source WYSIWYG editor built for the modern web. Completely customize it for any need with its modular architecture and expressive API: https://quilljs.com/
window.Quill = require('quill/dist/quill.js');

// Tagify - Transforms an input field or a textarea into a Tags component, in an easy, customizable way, with great performance and small code footprint, exploded with features: https://github.com/yairEO/tagify
require('@yaireo/tagify/dist/tagify.polyfills.min.js');
window.Tagify = require('@yaireo/tagify/dist/tagify.min');

// Toastr - is a Javascript library for non-blocking notifications. jQuery is required. The goal is to create a simple core library that can be customized and extended: https://github.com/CodeSeven/toastr
window.toastr = require('./craft/vendors/plugins/toastr/build/toastr.min.js');

// ES6 Promise Polyfill - This is a polyfill of the ES6 Promise: https://github.com/lahmatiy/es6-promise-polyfill
require('es6-promise-polyfill/promise.min');

// Sweetalert2 - a beautiful, responsive, customizable and accessible (WAI-ARIA) replacement for JavaScript's popup boxes: https://sweetalert2.github.io/
window.Swal = window.swal = require('sweetalert2/dist/sweetalert2.min');
require('./craft/vendors/plugins/sweetalert2.init.js');

// CountUp.js - is a dependency-free, lightweight JavaScript class that can be used to quickly create animations that display numerical data in a more interesting way.
window.countUp = require('countup.js/dist/countUp.min');

// Chart.js - Simple yet flexible JavaScript charting for designers & developers
window.Chart = require('chart.js');

// Tiny slider - for all purposes, inspired by Owl Carousel.
window.tns = require('tiny-slider/src/tiny-slider').tns;

// A lightweight script to animate scrolling to anchor links
window.SmoothScroll = require('smooth-scroll/dist/smooth-scroll.js');

// Datatable
require('datatables.net')
require('datatables.net-bs5')
require('datatables.net-fixedheader')
require('datatables.net-fixedheader-bs5')
require('datatables.net-fixedcolumns')
require('datatables.net-fixedcolumns-bs5')
require('datatables.net-responsive')
require('datatables.net-responsive-bs5')
require('./craft/vendors/plugins/datatables.init')

// Sortable
window.Sortable = require('sortablejs').Sortable

// Error stack parser
require('error-stack-parser')

window.KTApp = require('./craft/components/app.js');
window.KTUtil = require('./craft/components/util.js');
window.KTDrawer = require('./craft/components/drawer.js');
window.KTEventHandler = require('./craft/components/event-handler.js');
window.KTBlockUI = require('./craft/components/blockui.js');
window.KTCookie = require('./craft/components/cookie.js');
window.KTDialer = require('./craft/components/dialer.js');
window.KTFeedback = require('./craft/components/feedback.js');
window.KTImageInput = require('./craft/components/image-input.js');
window.KTMenu = require('./craft/components/menu.js');
window.KTPasswordMeter = require('./craft/components/password-meter.js');
window.KTScroll = require('./craft/components/scroll.js');
window.KTScrolltop = require('./craft/components/scrolltop.js');
window.KTSearch = require('./craft/components/search.js');
window.KTStepper = require('./craft/components/stepper.js');
window.KTSticky = require('./craft/components/sticky.js');
window.KTSwapper = require('./craft/components/swapper.js');
window.KTToggle = require('./craft/components/toggle.js');
window.KTComponents = require('./craft/components/_init.js');

// Layout base js
window.KTLayoutAside = require('./craft/layout/aside.js');
window.KTLayoutHeader = require('./craft/layout/header.js');
window.KTLayoutSearch = require('./craft/layout/search.js');
window.KTThemeMode = require('./craft/layout/theme-mode.js');
window.KTThemeModeUser = require('./craft/layout/theme-mode-user.js');

import '../scss/app.scss'
import './sidebar.js'