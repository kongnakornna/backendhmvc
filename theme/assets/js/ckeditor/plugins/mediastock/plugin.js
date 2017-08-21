﻿/**
* oEmbed Plugin plugin
* Licensed under the MIT license
* jQuery Embed Plugin: http://code.google.com/p/jquery-oembed/ (MIT License)
* Plugin for: http://ckeditor.com/license (GPL/LGPL/MPL: http://ckeditor.com/license)
*/

(function() {
        CKEDITOR.plugins.add('mediastock', {
        
            icons: 'mediastock',
            hidpi: true,
            requires: 'widget,dialog',
            lang: 'de,en,fr,nl,pl,pt-br,ru,tr', // %REMOVE_LINE_CORE%
            version: 1.17,
            init: function(editor) { 
              loadjQueryLibaries();
              
              CKEDITOR.tools.extend(CKEDITOR.editor.prototype, {
                    oEmbed: function(url, maxWidth, maxHeight, responsiveResize) {

                        if (url.length < 1 || url.indexOf('http') < 0) {
                            alert(editor.lang.oembed.invalidUrl);
                            return false;
                        }

                        function mediastock() {
                            if (maxWidth == null || maxWidth == 'undefined') {
                                maxWidth = null;
                            }

                            if (maxHeight == null || maxHeight == 'undefined') {
                                maxHeight = null;
                            }

                            if (responsiveResize == null || responsiveResize == 'undefined') {
                                responsiveResize = false;
                            }

                            embedCode(url, editor, false, maxWidth, maxHeight, responsiveResize);
                        }

                        if (typeof(jQuery.fn.oembed) === 'undefined') {
                            CKEDITOR.scriptLoader.load(CKEDITOR.getUrl(CKEDITOR.plugins.getPath('mediastock') + 'libs/jquery.mediastock.min.js'), function() {
                                mediastock();
                            });
                        } else {
                            mediastock();
                        }

                        return true;
                    }
                });
                
                
                editor.widgets.add('mediastock', {
                  draggable: false,
                    mask: true,
                    dialog: 'mediastock',
                    allowedContent: {
                        div: {
                            styles: 'text-align,float',
                            attributes: '*',
                            classes: editor.config.oembed_WrapperClass != null ? editor.config.oembed_WrapperClass : "embeddedContent"
                        },
                        'div(embeddedContent,oembed-provider-*) iframe': {
                            attributes: '*'
                        },
                        'div(embeddedContent,oembed-provider-*) blockquote': {
                            attributes: '*'
                        },
                        'div(embeddedContent,oembed-provider-*) script': {
                            attributes: '*'
                        }
                    },
                    template:
                        '<div class="' + (editor.config.oembed_WrapperClass != null ? editor.config.oembed_WrapperClass : "embeddedContent") + '">' +
                            '</div>',
                    upcast: function(element) {
                        return element.name == 'div' && element.hasClass(editor.config.oembed_WrapperClass != null ? editor.config.oembed_WrapperClass : "embeddedContent");
                    },
                    init: function() {
                        var data = {
                            mediastock: this.element.data('mediastock') || '',
                            resizeType: this.element.data('resizeType') || 'noresize',
                            maxWidth: this.element.data('maxWidth') || 560,
                            maxHeight: this.element.data('maxHeight') || 315,
                            align: this.element.data('align') || 'none',
                            oembed_provider: this.element.data('oembed_provider') || ''
                        };

                        this.setData(data);
                        this.element.addClass('oembed-provider-' + data.oembed_provider);

                        this.on('dialog', function(evt) {
                            evt.data.widget = this;
                        }, this);
                    }
                });

                
                
              CKEDITOR.dialog.add('mediastock', function(editor) {
                  return {
                      title: editor.lang.mediastock.title,
                      minWidth: CKEDITOR.env.ie && CKEDITOR.env.quirks ? 568 : 550,
                      minHeight: 155,
                      onShow: function() {
                          var data = {
                              mediastock: this.widget.element.data('mediastock') || '',
                              resizeType: this.widget.element.data('resizeType') || 'noresize',
                              maxWidth: this.widget.element.data('maxWidth'),
                              maxHeight: this.widget.element.data('maxHeight'),
                              align: this.widget.element.data('align') || 'none'
                          };

                          this.widget.setData(data);

                          this.getContentElement('general', 'resizeType').setValue(data.resizeType);

                          this.getContentElement('general', 'align').setValue(data.align);

                          var resizetype = this.getContentElement('general', 'resizeType').getValue(),
                              maxSizeBox = this.getContentElement('general', 'maxSizeBox').getElement(),
                              sizeBox = this.getContentElement('general', 'sizeBox').getElement();

                          if (resizetype == 'noresize') {
                              maxSizeBox.hide();
                              sizeBox.hide();
                          } else if (resizetype == "custom") {
                              maxSizeBox.hide();

                              sizeBox.show();
                          } else {
                              maxSizeBox.show();

                              sizeBox.hide();
                          }
                      },

                      onOk: function() {
                      },
                      contents: [
                          {
                              label: editor.lang.common.generalTab,
                              id: 'general',
                              elements: [
                                  {
                                      type: 'html',
                                      id: 'oembedHeader',
                                      html: '<div style="white-space:normal;width:500px;padding-bottom:10px">' + editor.lang.oembed.pasteUrl + '</div>'
                                  }, {
                                      type: 'text',
                                      id: 'embedCode',
                                      focus: function() {
                                          this.getElement().focus();
                                      },
                                      label: editor.lang.oembed.url,
                                      title: editor.lang.oembed.pasteUrl,
                                      setup: function(widget) {
                                          if (widget.data.oembed) {
                                              this.setValue(widget.data.oembed);
                                          }
                                      },
                                      commit: function(widget) {
                                          var dialog = CKEDITOR.dialog.getCurrent(),
                                              inputCode = dialog.getValueOf('general', 'embedCode').replace(/\s/g, ""),
                                              resizeType = dialog.getContentElement('general', 'resizeType').
                                                  getValue(),
                                              align = dialog.getContentElement('general', 'align').
                                                  getValue(),
                                              maxWidth = null,
                                              maxHeight = null,
                                              responsiveResize = false,
                                              editorInstance = dialog.getParentEditor();

                                          if (inputCode.length < 1 || inputCode.indexOf('http') < 0) {
                                              alert(editor.lang.oembed.invalidUrl);
                                              return false;
                                          }

                                          if (resizeType == "noresize") {
                                              responsiveResize = false;
                                              maxWidth = null;
                                              maxHeight = null;
                                          } else if (resizeType == "responsive") {
                                              maxWidth = dialog.getContentElement('general', 'maxWidth').
                                                  getInputElement().
                                                  getValue();
                                              maxHeight = dialog.getContentElement('general', 'maxHeight').
                                                  getInputElement().
                                                  getValue();

                                              responsiveResize = true;
                                          } else if (resizeType == "custom") {
                                              maxWidth = dialog.getContentElement('general', 'width').
                                                  getInputElement().
                                                  getValue();
                                              maxHeight = dialog.getContentElement('general', 'height').
                                                  getInputElement().
                                                  getValue();

                                              responsiveResize = false;
                                          }

                                          embedCode(inputCode, editorInstance, maxWidth, maxHeight, responsiveResize, resizeType, align, widget);

                                          widget.setData('oembed', inputCode);
                                          widget.setData('resizeType', resizeType);
                                          widget.setData('align', align);
                                          widget.setData('maxWidth', maxWidth);
                                          widget.setData('maxHeight', maxHeight);
                                      }
                                  }, {
                                      type: 'hbox',
                                      widths: ['50%', '50%'],
                                      children: [
                                          {
                                              id: 'resizeType',
                                              type: 'select',
                                              label: editor.lang.oembed.resizeType,
                                              'default': 'noresize',
                                              setup: function(widget) {
                                                  if (widget.data.resizeType) {
                                                      this.setValue(widget.data.resizeType);
                                                  }
                                              },
                                              items: [
                                                  [editor.lang.oembed.noresize, 'noresize'],
                                                  [editor.lang.oembed.responsive, 'responsive'],
                                                  [editor.lang.oembed.custom, 'custom']
                                              ],
                                              onChange: resizeTypeChanged
                                          }, {
                                              type: 'hbox',
                                              id: 'maxSizeBox',
                                              widths: ['120px', '120px'],
                                              style: 'float:left;position:absolute;left:58%;width:200px',
                                              children: [
                                                  {
                                                      type: 'text',
                                                      width: '100px',
                                                      id: 'maxWidth',
                                                      'default': editor.config.oembed_maxWidth != null ? editor.config.oembed_maxWidth : '560',
                                                      label: editor.lang.oembed.maxWidth,
                                                      title: editor.lang.oembed.maxWidthTitle,
                                                      setup: function(widget) {
                                                          if (widget.data.maxWidth) {
                                                              this.setValue(widget.data.maxWidth);
                                                          }
                                                      }
                                                  }, {
                                                      type: 'text',
                                                      id: 'maxHeight',
                                                      width: '120px',
                                                      'default': editor.config.oembed_maxHeight != null ? editor.config.oembed_maxHeight : '315',
                                                      label: editor.lang.oembed.maxHeight,
                                                      title: editor.lang.oembed.maxHeightTitle,
                                                      setup: function(widget) {
                                                          if (widget.data.maxHeight) {
                                                              this.setValue(widget.data.maxHeight);
                                                          }
                                                      }
                                                  }
                                              ]
                                          }, {
                                              type: 'hbox',
                                              id: 'sizeBox',
                                              widths: ['120px', '120px'],
                                              style: 'float:left;position:absolute;left:58%;width:200px',
                                              children: [
                                                  {
                                                      type: 'text',
                                                      id: 'width',
                                                      width: '100px',
                                                      'default': editor.config.oembed_maxWidth != null ? editor.config.oembed_maxWidth : '560',
                                                      label: editor.lang.oembed.width,
                                                      title: editor.lang.oembed.widthTitle,
                                                      setup: function(widget) {
                                                          if (widget.data.maxWidth) {
                                                              this.setValue(widget.data.maxWidth);
                                                          }
                                                      }
                                                  }, {
                                                      type: 'text',
                                                      id: 'height',
                                                      width: '120px',
                                                      'default': editor.config.oembed_maxHeight != null ? editor.config.oembed_maxHeight : '315',
                                                      label: editor.lang.oembed.height,
                                                      title: editor.lang.oembed.heightTitle,
                                                      setup: function(widget) {
                                                          if (widget.data.maxHeight) {
                                                              this.setValue(widget.data.maxHeight);
                                                          }
                                                      }
                                                  }
                                              ]
                                          }
                                      ]
                                  }, {
                                      type: 'hbox',
                                      id: 'alignment',
                                      children: [
                                          {
                                              id: 'align',
                                              type: 'radio',
                                              items: [
                                                  [editor.lang.oembed.none, 'none'],
                                                  [editor.lang.common.alignLeft, 'left'],
                                                  [editor.lang.common.alignCenter, 'center'],
                                                  [editor.lang.common.alignRight, 'right']
                                              ],
                                              label: editor.lang.common.align,
                                              setup: function(widget) {
                                                  this.setValue(widget.data.align);
                                              }
                                          }
                                      ]
                                  }
                              ]
                          }
                      ]
                  };
              });
                
                
                
                ////////////////////////
                
              editor.ui.addButton('mediastock', {
                label: editor.lang.oembed.button,
                command: 'mediastock',
                toolbar: 'insert,10',
                icon: this.path + "icons/" + (CKEDITOR.env.hidpi ? "hidpi/" : "") + "mediastock.png"
              });
              

                
              function loadjQueryLibaries() {
                  if (typeof(jQuery) === 'undefined') {
                      CKEDITOR.scriptLoader.load('//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', function() {
                          jQuery.noConflict();
                          if (typeof(jQuery.fn.oembed) === 'undefined') {
                              CKEDITOR.scriptLoader.load(
                                  CKEDITOR.getUrl(CKEDITOR.plugins.getPath('oembed') + 'libs/jquery.oembed.min.js')
                              );
                          }
                      });

                  } else if (typeof(jQuery.fn.oembed) === 'undefined') {
                      CKEDITOR.scriptLoader.load(CKEDITOR.getUrl(CKEDITOR.plugins.getPath('oembed') + 'libs/jquery.oembed.min.js'));
                  }
              }
            }
        });
    }
)();