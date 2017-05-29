/**
* @module       jQuery RD Instafeed
* @author       Rafael Shayvolodyan(raffa)
* @version      1.2.1
*/

(function() {
  (function($, document, window) {

    /**
     * Creates a RD Instafeed.
     * @class RD Instafeed.
     * @public
     * @param {HTMLElement} element - The element to create the RD Instafeed for.
     * @param {Object} [options] - The options
     */
    var RDInstafeed;
    RDInstafeed = (function() {

      /**
       * Default options for RD Instafeed.
       * @public
       */
      RDInstafeed.prototype.Defaults = {
        accessToken: '',
        clientId: '',
        get: 'user',
        tagName: 'awesome',
        userId: '',
        locationId: '',
        sortBy: 'most-recent',
        useHttp: false,
        showLog: 'false',
        dateFormat: {
          seconds: 'less than a minute ago',
          minute: 'about a minute ago',
          minutes: ' minutes ago',
          hour: 'about an hour ago',
          hours: ' hours ago',
          day: '1 day ago',
          days: '%b/%d/%Y'
        }
      };

      function RDInstafeed(element, options) {
        this.options = $.extend(true, {}, this.Defaults, options);
        this.$element = $(element);
        this.element = element;
        this.$items = this.$element.find('[data-instafeed-item]');
        this.unique = this.genKey();
        this.nextUrl = '';
        this.initialize();
      }


      /**
       * Initializes the RD Instafeed.
       * @protected
       */

      RDInstafeed.prototype.initialize = function() {
        var imageArr;
        if (typeof this.options.clientId !== 'string' && typeof this.options.accessToken !== 'string') {
          throw new Error("Missing clientId or accessToken.");
        }
        if ((this.options.before != null) && typeof this.options.before === 'function') {
          this.options.before.call(this);
        }
        imageArr = null;
        this.fetchData(this, this.buildUrl(), imageArr);
      };


      /**
       * Get Data from Instagram
       * @protected
       * @param {String} url - Instagram url.
       * @param {Array} imageArr - Instagram image array (for pagination).
       */

      RDInstafeed.prototype.fetchData = function(ctx, url, imageArr) {
        var get;
        console.log(url);
        get = ctx.element.getAttribute('data-instafeed-get') ? ctx.element.getAttribute('data-instafeed-get') : ctx.options.get;
        $.ajax({
          type: "GET",
          dataType: "jsonp",
          cache: false,
          url: url,
          success: function(response) {
            var images, imagesRequested, showLog;
            if (imageArr != null) {
              images = imageArr;
              images.push.apply(images, response.data);
            } else {
              images = response.data;
            }

            if (response.pagination != null) {
              ctx.nextUrl = response.pagination.next_url;
            }

            console.log(response);

            if (get !== 'profile') {

              imagesRequested = parseInt(ctx.$items.length, 10);

              if (images.length >= imagesRequested) {
                images = ctx.sorting(ctx, images);
                images = images.slice(0, imagesRequested);
                ctx.validate(ctx, response);
                showLog = ctx.element.getAttribute('data-instafeed-showlog') ? ctx.element.getAttribute('data-instafeed-showlog') : ctx.options.showLog;
                if (showLog === 'false') {
                  console.log(images);
                }

                return ctx.loopData(images);
              } else if (ctx.nextUrl != null) {
                return ctx.fetchData(ctx, response.pagination.next_url, images);
              } else {

                if ( images.length <  imagesRequested) {
                  while( images.length != imagesRequested ){
                    for ( var qew in images ) {
                      if ( images.length <  imagesRequested) {
                        images.push( images[qew] );
                      } else {
                        break;
                      }
                    }
                  }
                }

                images = ctx.sorting(ctx, images);
                ctx.validate(ctx, response);
                return ctx.loopData(images);
              }
            } else {
              ctx.validate(ctx, response);
              showLog = ctx.element.getAttribute('data-instafeed-showlog') ? ctx.element.getAttribute('data-instafeed-showlog') : ctx.options.showLog;
              if (showLog === 'false') {
                console.log(images);
              }
              return ctx.loopData(images);
            }
          }
        });
      };


      /**
       * Validate json object.
       * @protected
       * @param {JSON Object} response - response from instagram.
       */

      RDInstafeed.prototype.validate = function(ctx, response) {
        if (typeof response !== 'object') {
          if ((ctx.options.error != null) && typeof ctx.options.error === 'function') {
            ctx.options.error.call(this, 'Invalid JSON data');
            return false;
          } else {
            throw new Error('Invalid JSON response');
          }
        }
        if (response.meta.code !== 200) {
          if ((ctx.options.error != null) && typeof ctx.options.error === 'function') {
            ctx.options.error.call(this, response.meta.error_message);
            return false;
          } else {
            throw new Error("Error from Instagram: " + response.meta.error_message);
          }
        }
        if (response.data.length === 0) {
          if ((ctx.options.error != null) && typeof ctx.options.error === 'function') {
            ctx.options.error.call(this, 'No images were returned from Instagram');
            return false;
          } else {
            throw new Error('No images were returned from Instagram');
          }
        }
      };


      /**
       * Sorting Instagram images
       * @protected
       * @param {Array} response - Instagram images.
       */

      RDInstafeed.prototype.sorting = function(ctx, response) {
        var get, reverse, sortBy, sortSettings;
        get = ctx.element.getAttribute('data-instafeed-get') ? ctx.element.getAttribute('data-instafeed-get') : ctx.options.get;
        if (get !== 'profile') {
          sortBy = ctx.element.getAttribute('data-instafeed-sort') ? ctx.element.getAttribute('data-instafeed-sort') : ctx.options.sortBy;
          if (sortBy !== 'none') {
            if (sortBy === 'random') {
              sortSettings = ['', 'random'];
            } else {
              sortSettings = sortBy.split('-');
            }
            reverse = sortSettings[0] === 'least' ? true : false;
            switch (sortSettings[1]) {
              case 'random':
                response.sort(function() {
                  return 0.5 - Math.random();
                });
                break;
              case 'recent':
                response = ctx.sortBy(response, 'created_time', reverse);
                break;
              case 'liked':
                response = ctx.sortBy(response, 'likes.count', reverse);
                break;
              case 'commented':
                response = ctx.sortBy(response, 'comments.count', reverse);
                break;
              default:
                throw new Error("Invalid option for sortBy: '" + sortBy + "'.");
            }
          }
        }
        return response;
      };


      /**
       * Loop html elements for attribute parsing
       * @protected
       * @param {Array} data - Instagram images.
       */

      RDInstafeed.prototype.loopData = function(data) {
        var ctx, i, indexes;
        ctx = this;
        if ((ctx.options.filter != null) && typeof ctx.options.filter === 'function') {
          data = ctxfilter(data, ctx.options.filter);
        }
        if (Array.isArray(data)) {
          i = 0;
          while (i < data.length) {
            data[i]['tags_full'] = ctx.arrToString(data[i]['tags']);
            indexes = {
              comments: -1,
              likes: -1,
              locations: -1
            };
            ctx.$items.eq(i).find('*').each(function() {
              if (ctx.checkAttribute(this, 'data-instafeed-comment')) {
                indexes.comments++;
                return;
              } else if (ctx.checkAttribute(this, 'data-instafeed-like')) {
                indexes.likes++;
                return;
              } else if (ctx.checkAttribute(this, 'data-instafeed-location')) {
                indexes.locations++;
                return;
              }

              if (ctx.checkAttribute(this, 'data-comments-data')) {
              } else if (ctx.checkAttribute(this, 'data-likes-data')) {
                ctx.parseAttributes(this, data[i], indexes.likes);
              } else if (ctx.checkAttribute(this, 'data-locations-data')) {
                ctx.parseAttributes(this, data[i], indexes.locations);
              } else {
                ctx.parseAttributes(this, data[i], 0);
              }
            });
            i++;
          }
        } else {
          data['link'] = 'https://www.instagram.com/' + data.username;
          ctx.$element.find('*').not('[data-instafeed-item], [data-instafeed-item] *, [data-instafeed-get]').each(function() {
            return ctx.parseAttributes(this, data, 0);
          });
        }
        if ((ctx.options.after != null) && typeof ctx.options.after === 'function') {
          ctx.options.after.call(this);
        }
        return true;
      };


      /**
       * Checks for the presence of an element attribute
       * @protected
       * @param {DOM Element Object} el - HTML element.
       * @param {String} attribute - attribute name.
       */

      RDInstafeed.prototype.checkAttribute = function(el, attribute) {
        var attr, j, len, ref;
        ref = el.attributes;
        for (j = 0, len = ref.length; j < len; j++) {
          attr = ref[j];
          if (attr.name.indexOf(attribute) > -1) {
            return true;
          }
        }
        return false;
      };


      /**
       * Parse element attributes and replace it
       * @protected
       * @param {DOM Element Object} el - HTML element.
       * @param {JSON Object} json - attribute name.
       * @param {Integer} index - index for likes, comments, locations.
       */

      RDInstafeed.prototype.parseAttributes = function(el, json, index) {
        var attr, attributes, c, dataArr, dataEl, date, j, k, len, len1, temp, tmp, value, valueArr, valueIndex;
        dataArr = el.attributes;
        for (dataEl in dataArr) {
          if ((dataArr[dataEl] != null) && typeof dataArr[dataEl] === 'object' && dataArr[dataEl].name.indexOf('data-') !== -1 && dataArr[dataEl].name.indexOf('data-instafeed-') === -1) {
            valueIndex = dataArr[dataEl].name.substring(5);
            value = null;
            if (valueIndex.indexOf('-') !== -1) {
              valueArr = valueIndex.split('-');
              value = json;
              for (j = 0, len = valueArr.length; j < len; j++) {
                tmp = valueArr[j];
                if (tmp === 'data' && (value[tmp] != null) && (value[tmp][index] != null)) {
                  value = value[tmp][index];
                } else if (value[tmp] != null) {
                  value = value[tmp];
                } else {
                  continue;
                }
              }
            }
            if (typeof dataArr[dataEl].value === 'string') {
              attributes = dataArr[dataEl].value.split(/\s?,\s?/i);
              if (value != null) {
                temp = value;
              } else if (json[valueIndex] != null) {
                temp = json[valueIndex];
              }
              if (valueIndex.indexOf('created_time') !== -1) {
                date = temp;
              }
              if ((temp != null) && (attributes != null) && (typeof temp === 'string' || typeof temp === 'number')) {
                for (k = 0, len1 = attributes.length; k < len1; k++) {
                  attr = attributes[k];
                  if (valueIndex.indexOf('created_time') !== -1) {
                    if (attr === 'datetime') {
                      temp = this.dating(date, true);
                    } else {
                      temp = this.dating(date, false);
                    }
                  }
                  if (attr.toLowerCase() === 'text') {
                    el.innerHTML = temp;
                  } else {
                    if (valueIndex === 'type' && temp !== 'image') {
                      el.setAttribute(attr, 'iframe');
                    } else {
                      c = attr.charAt(0);
                      if (!(c >= '0' && c <= '9')) {
                        el.setAttribute(attr, temp);
                      }
                    }
                  }
                }
              }
            }
          }
        }
      };


      /**
       * Convert Array to String
       * @protected
       * @param {Array} arr.
       */

      RDInstafeed.prototype.arrToString = function(arr) {
        return arr.join(' ');
      };


      /**
       * Formatting Date
       * @protected
       * @param {Number} time - Instagram date.
       * @param {Boolean} datetime - if true, formatting for datetime attribute.
       */

      RDInstafeed.prototype.dating = function(time, datetime) {
        var _date, current, date, datef, delta, format, formats, j, len, months;
        date = new Date(time * 1000);
        current = new Date();
        delta = parseInt((current.getTime() - date.getTime()) / 1000);
        delta += current.getTimezoneOffset() * 60;
        months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        _date = {
          '%d': date.getDate(),
          '%m': date.getMonth() + 1,
          '%b': months[date.getMonth()].substring(0, 3),
          '%B': months[date.getMonth()],
          '%y': String(date.getFullYear()).slice(-2),
          '%Y': date.getFullYear()
        };
        if (datetime) {
          datef = '%Y-%m-%d';
        } else {
          datef = this.element.getAttribute('data-instafeed-date-format') ? this.element.getAttribute('data-instafeed-date-format') : this.options.dateFormat.days;
        }
        if (delta < 60) {
          return this.options.dateFormat.seconds;
        } else if (delta < 120) {
          return this.options.dateFormat.minute;
        } else if (delta < (60 * 60)) {
          return (parseInt(delta / 60)).toString() + this.options.dateFormat.minutes;
        } else if (delta < (120 * 60)) {
          return this.options.dateFormat.hour;
        } else if (delta < (24 * 60 * 60)) {
          return 'about ' + (parseInt(delta / 3600)).toString() + this.options.dateFormat.hours;
        } else if (delta < (48 * 60 * 60)) {
          return this.options.dateFormat.day;
        } else {
          formats = datef.match(/%[dmbByY]/g);
          for (j = 0, len = formats.length; j < len; j++) {
            format = formats[j];
            datef = datef.replace(format, _date[format]);
          }
          return datef;
        }
      };


      /**
       * Sort Data
       * @protected
       * @param {data} time - data for sorting.
       * @param {Object} property
       * @param {Boolean} reverse
       */

      RDInstafeed.prototype.sortBy = function(data, property, reverse) {
        var sorter;
        sorter = function(a, b) {
          var valueA, valueB;
          valueA = this.getObjectProperty(a, property);
          valueB = this.getObjectProperty(b, property);
          if (reverse) {
            if (valueA > valueB) {
              return 1;
            } else {
              return -1;
            }
          }
          if (valueA < valueB) {
            return 1;
          } else {
            return -1;
          }
        };
        data.sort(sorter.bind(this));
        return data;
      };


      /**
       * Access an object property by string
       * @protected
       * @param {object} Object.
       * @param {String} property
       */

      RDInstafeed.prototype.getObjectProperty = function(object, property) {
        var piece, pieces;
        property = property.replace(/\[(\w+)\]/g, '.$1');
        pieces = property.split('.');
        while (pieces.length) {
          piece = pieces.shift();
          if ((object != null) && piece in object) {
            object = object[piece];
          } else {
            return null;
          }
        }
        return object;
      };


      /**
       * Build Url for getting data via function fetchData
       * @protected
       */

      RDInstafeed.prototype.buildUrl = function() {
        var access, base, clientId, endpoint, get, location, tagName, url, user;
        base = "https://api.instagram.com/v1";
        get = this.element.getAttribute('data-instafeed-get') ? this.element.getAttribute('data-instafeed-get') : this.options.get;
        switch (get) {
          case "tagged":
            tagName = this.element.getAttribute('data-instafeed-tagname') ? this.element.getAttribute('data-instafeed-tagname') : this.options.tagName;
            if (!tagName) {
              throw new Error("No tag name specified. Use the 'tagName' option.");
            }
            endpoint = "tags/" + tagName + "/media/recent";
            break;
          case "location":
            location = this.element.getAttribute('data-instafeed-location') ? this.element.getAttribute('data-instafeed-location') : this.options.location;
            if (!location) {
              throw new Error("No location specified. Use the 'locationId' option.");
            }
            endpoint = "locations/" + location + "/media/recent";
            break;
          case "user":
            user = this.element.getAttribute('data-instafeed-user') ? this.element.getAttribute('data-instafeed-user') : this.options.userId;
            if (!user) {
              throw new Error("No user specified. Use the 'userId' option.");
            }
            endpoint = "users/" + user + "/media/recent";
            break;
          case "profile":
            user = this.element.getAttribute('data-instafeed-user') ? this.element.getAttribute('data-instafeed-user') : this.options.userId;
            if (!user) {
              throw new Error("No user specified. Use the 'userId' option.");
            }
            endpoint = "users/" + user;
            break;
          default:
            throw new Error("Invalid option for get: '" + this.options.get + "'.");
        }
        url = base + "/" + endpoint;
        access = this.element.getAttribute('data-instafeed-accesstoken') ? this.element.getAttribute('data-instafeed-accesstoken') : this.options.accessToken;
        clientId = this.element.getAttribute('data-instafeed-clientid') ? this.element.getAttribute('data-instafeed-clientid') : this.options.clientId;

        if (!!access) {
          url += "?access_token=" + access;
        } else {
          url += "?client_id=" + clientId;
        }
        if (this.$items.length && get !== "profile") {
          url += "&count=" + this.$items.length;
        }
        url += "&callback=instafeedCache" + this.unique + ".parse";
        return url;
      };


      /**
       * Generate a unique key
       * @protected
       */

      RDInstafeed.prototype.genKey = function() {
        var S4;
        S4 = function() {
          return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        };
        return "" + (S4()) + (S4()) + (S4()) + (S4());
      };


      /**
       * Filter out images
       * @protected
       */

      RDInstafeed.prototype.filter = function(images, filter) {
        var filteredImages, fn, image, j, len;
        filteredImages = [];
        fn = function(image) {
          if (filter(image)) {
            return filteredImages.push(image);
          }
        };
        for (j = 0, len = images.length; j < len; j++) {
          image = images[j];
          fn(image);
        }
        return filteredImages;
      };

      return RDInstafeed;

    })();

    /**
     * The jQuery Plugin for the RD Instafeed
     * @public
     */
    $.fn.extend({
      RDInstafeed: function(options) {
        return this.each(function() {
          var $this;
          $this = $(this);
          if (!$this.data('RDInstafeed')) {
            return $this.data('RDInstafeed', new RDInstafeed(this, options));
          }
        });
      }
    });
    return window.RDInstafeed = RDInstafeed;
  })(window.jQuery, document, window);


  /**
   * The Plugin AMD export
   * @public
   */

  if (typeof module !== "undefined" && module !== null) {
    module.exports = window.RDInstafeed;
  } else if (typeof define === 'function' && define.amd) {
    define(["jquery"], function() {
      'use strict';
      return window.RDInstafeed;
    });
  }

}).call(this);