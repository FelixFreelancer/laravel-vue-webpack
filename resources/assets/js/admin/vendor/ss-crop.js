;(function ($, window, document, undefined) {

    "use strict";

    var pluginName = "ssCrop",
        defaults = {
            croppie: {
                viewport: {
                    width: 200,
                    height: 200,
                }
            },
            result: {
                size: {
                    width: 200,
                }
            }
        };

    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this._croppie;
        this.reset;
        this.file;
        this.preview;
        this.init();
    }

    $.extend(Plugin.prototype, {

        init: function () {
            var _this = this;
            _this.bindUIActions();

        },
        bindUIActions() {
            var _this = this;

            _this.reset = $(this.element).parents('.ss-crop').find('.ss-crop__reset');
            _this.submit = $(this.element).parents('.ss-crop').find('.ss-crop__submit');
            _this.file = $(this.element);

            _this.file.on('change', function () {
                _this.fileSelected();
            });

            _this.reset.on('click', function () {
                _this.resetCrop();
            });
        },
        fileSelected() {
            var _this = this;
            if (_this.file.val()) {
                _this.getBase64(_this.file[0], function (base64Data) {
                    _this.createPreviewBlock(base64Data);
                });
            }
            else {
                _this.resetCrop();
                console.log('No Image Selected!');
            }
        },
        getBase64(input, callback) {
            var _this = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    callback(e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        },
        createPreviewBlock(base64Data) {
            var _this = this;
            var previewBlockId = _this.file.attr('id') + '-preview';
            if ($('#' + previewBlockId).length < 0) {
                $('#' + fileId).next('<div class="ss-crop__preview"><div id="' + previewBlockId + '"></div></div>');
            }
            _this.preview = $('#' + previewBlockId);
            _this.doCrop(base64Data);
        },
        doCrop(base64Data) {
            var _this = this;
            if (!_this._croppie) {
                _this.settings.croppie.update = function () {
                    _this.generate($.debounce(1000, function (data) {
                        $('#' + _this.file.attr('id') + '_hidden').val(data);
                    }));
                };
                _this._croppie = _this.preview.croppie(_this.settings.croppie);
            }
            _this._croppie.croppie('bind', {
                url: base64Data,
            });
            _this.reset.show().addClass('visible');
            _this.submit.show().addClass('visible');
        },
        resetCrop() {
            var _this = this;
            _this.reset.hide().removeClass('visible');
            _this.submit.hide().removeClass('visible');
            _this.file.val('');
            _this.destroyCroppe();
        },
        destroyCroppe() {
            var _this = this;
            if (_this._croppie) {
                _this._croppie.croppie('destroy');
                _this._croppie = '';
            }
        },
        generate(callback) {
            var _this = this;
            if (_this._croppie != undefined && _this._croppie.croppie != undefined) {
                _this._croppie.croppie('result', _this.settings.result).then(function (data) {
                    callback(data);
                });
            } else {
                callback('');
            }
        }
    });

    $.fn[pluginName] = function (options) {
        var args = arguments;
        if (options === undefined || typeof options === 'object') {
            return this.each(function () {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
                }
            });
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
            var returns;
            this.each(function () {
                var instance = $.data(this, 'plugin_' + pluginName);
                if (instance instanceof Plugin && typeof instance[options] === 'function') {

                    returns = instance[options].apply(instance, Array.prototype.slice.call(args, 1));
                }
                if (options === 'destroy') {
                    $.data(this, 'plugin_' + pluginName, null);
                }
            });
            return returns !== undefined ? returns : null;
        }
    };


})(jQuery, window, document);
