###*
 * @module       jQuery RD Instafeed
 * @author       Rafael Shayvolodyan(raffa)
 * @version      1.0.1
###
(($, document, window) ->

  ###*
   * Creates a RD Instafeed.
   * @class RD Instafeed.
   * @public
   * @param {HTMLElement} element - The element to create the RD Instafeed for.
   * @param {Object} [options] - The options
  ###
  class RDInstafeed

    ###*
     * Default options for RD Instafeed.
     * @public
    ###
    Defaults:
      accessToken: '',
      clientId: '',
      get: 'user',
      tagName: 'awesome',
      userId: '499522078',
      locatioId: '',
      sortBy: 'most-recent',
      useHttp: false,
      showLog: 'false',
      dateFormat:
        seconds: 'less than a minute ago',
        minute: 'about a minute ago',
        minutes: ' minutes ago',
        hour: 'about an hour ago',
        hours: ' hours ago',
        day: '1 day ago',
        days: '%b/%d/%Y'

    constructor: (element, options) ->
      @options = $.extend(true, {}, @Defaults, options)
      @$element = $(element)
      @element = element
      @$items = @$element.find('[data-instafeed-item]')
      @unique = @.genKey()
      @nextUrl = ''
      @initialize()

    ###*
     * Initializes the RD Instafeed.
     * @protected
    ###
    initialize: () ->
      if typeof @options.clientId isnt 'string' && typeof @options.accessToken isnt 'string'
        throw new Error "Missing clientId or accessToken."

      if @options.before? and typeof @options.before is 'function'
        @options.before.call(this)
      imageArr = null

      @.fetchData(@, @.buildUrl(), imageArr)
      return

    ###*
    * Get Data from Instagram
    * @protected
    * @param {String} url - Instagram url.
    * @param {Array} imageArr - Instagram image array (for pagination).
   ###
    fetchData: (ctx, url, imageArr) ->
      get = if ctx.element.getAttribute('data-instafeed-get') then ctx.element.getAttribute('data-instafeed-get') else ctx.options.get

      $.ajax({
        type: "GET",
        dataType: "jsonp",
        cache: false,
        url: url
        success: (response) ->
          if imageArr?
            images = imageArr
            images.push.apply(images, response.data)
          else
            images = response.data


          if response.pagination?
            ctx.nextUrl = response.pagination.next_url

          if get isnt 'profile'
            parsedLimit = parseInt(ctx.$items.length, 10)
            if images.length > parsedLimit
              images = ctx.sorting(ctx, images)
              images = images.slice(0, parsedLimit)
              ctx.validate(ctx, response)
              showLog = if ctx.element.getAttribute('data-instafeed-showlog') then ctx.element.getAttribute('data-instafeed-showlog') else ctx.options.showLog
              console.log images if showLog is 'true'
              ctx.loopData(images)

            else if ctx.nextUrl?
              ctx.fetchData(ctx, response.pagination.next_url, images)
          else
            ctx.validate(ctx, response)
            showLog = if ctx.element.getAttribute('data-instafeed-showlog') then ctx.element.getAttribute('data-instafeed-showlog') else ctx.options.showLog
            console.log images if showLog is 'true'
            ctx.loopData(images)
      });

      return

    ###*
    * Validate json object.
    * @protected
    * @param {JSON Object} response - response from instagram.
    ###
    validate: (ctx, response) ->
      if typeof response isnt 'object'
        if ctx.options.error? and typeof ctx.options.error is 'function'
          ctx.options.error.call(this, 'Invalid JSON data')
          return false
        else
          throw new Error 'Invalid JSON response'

      if response.meta.code isnt 200
        if ctx.options.error? and typeof ctx.options.error is 'function'
          ctx.options.error.call(this, response.meta.error_message)
          return false
        else
          throw new Error "Error from Instagram: #{response.meta.error_message}"

      if response.data.length is 0
        if ctx.options.error? and typeof ctx.options.error is 'function'
          ctx.options.error.call(this, 'No images were returned from Instagram')
          return false
        else
          throw new Error 'No images were returned from Instagram'

      return

    ###*
    * Sorting Instagram images
    * @protected
    * @param {Array} response - Instagram images.
    ###
    sorting: (ctx, response) ->
      get = if ctx.element.getAttribute('data-instafeed-get') then ctx.element.getAttribute('data-instafeed-get') else ctx.options.get
      if get isnt 'profile'
        sortBy = if ctx.element.getAttribute('data-instafeed-sort') then ctx.element.getAttribute('data-instafeed-sort') else ctx.options.sortBy
        if sortBy isnt 'none'
          if sortBy is 'random'
            sortSettings = ['', 'random']
          else
            sortSettings = sortBy.split('-')

          reverse = if sortSettings[0] is 'least' then true else false
          switch sortSettings[1]
            when 'random'
              response.sort () ->
                return 0.5 - Math.random()

            when 'recent'
              response = ctx.sortBy(response, 'created_time', reverse)

            when 'liked'
              response = ctx.sortBy(response, 'likes.count', reverse)

            when 'commented'
              response = ctx.sortBy(response, 'comments.count', reverse)

            else throw new Error "Invalid option for sortBy: '#{sortBy}'."

      return response

    ###*
    * Loop html elements for attribute parsing
    * @protected
    * @param {Array} data - Instagram images.
    ###
    loopData: (data) ->
      ctx = @

      if ctx.options.filter? and typeof ctx.options.filter is 'function'
        data = ctxfilter(data, ctx.options.filter)

      if Array.isArray(data)
        i = 0

        while i < data.length
          data[i]['tags_full'] = ctx.arrToString(data[i]['tags'])
          indexes = {
            comments: -1,
            likes: -1,
            locations: -1
          }

          ctx.$items.eq(i).find('*').each(->
            if ctx.checkAttribute(@, 'data-instafeed-comment')
              indexes.comments++
              return
            else if ctx.checkAttribute(@, 'data-instafeed-like')
              indexes.likes++
              return
            else if ctx.checkAttribute(@, 'data-instafeed-location')
              indexes.locations++
              return

            if ctx.checkAttribute(@, 'data-comments-data')
              ctx.parseAttributes(@, data[i], indexes.comments)
            else if ctx.checkAttribute(@, 'data-likes-data')
              ctx.parseAttributes(@, data[i], indexes.likes)
            else if ctx.checkAttribute(@, 'data-locations-data')
              ctx.parseAttributes(@, data[i], indexes.locations)
            else
              ctx.parseAttributes(@, data[i], 0)
            return
          )
          i++
      else
        data['link'] = 'https://www.instagram.com/' + data.username
        ctx.$element.find('*').not('[data-instafeed-item], [data-instafeed-item] *, [data-instafeed-get]').each(->
          ctx.parseAttributes(@, data, 0)
        )

      if ctx.options.after? and typeof ctx.options.after is 'function'
        ctx.options.after.call(this)

      true

    ###*
    * Checks for the presence of an element attribute
    * @protected
    * @param {DOM Element Object} el - HTML element.
    * @param {String} attribute - attribute name.
    ###
    checkAttribute: (el, attribute) ->
      for attr in el.attributes
        if attr.name.indexOf(attribute) > -1
          return true
      false

    ###*
    * Parse element attributes and replace it
    * @protected
    * @param {DOM Element Object} el - HTML element.
    * @param {JSON Object} json - attribute name.
    * @param {Integer} index - index for likes, comments, locations.
    ###
    parseAttributes: (el, json, index) ->
      dataArr = el.attributes;
      for dataEl of dataArr

        if dataArr[dataEl]? and typeof dataArr[dataEl] is 'object' and dataArr[dataEl].name.indexOf('data-') != -1 and dataArr[dataEl].name.indexOf('data-instafeed-') == -1
          valueIndex = dataArr[dataEl].name.substring(5)
          value = null

          if valueIndex.indexOf('-') != -1
            valueArr = valueIndex.split('-')
            value = json

            for tmp in valueArr
              if tmp is 'data' && value[tmp]? && value[tmp][index]?
                value = value[tmp][index]
              else
                value = value[tmp]


          if typeof dataArr[dataEl].value is 'string'
            attributes = dataArr[dataEl].value.split(/\s?,\s?/i);

            if value?
              temp = value
            else if json[valueIndex]?
              temp = json[valueIndex]


            if valueIndex.indexOf('created_time') isnt -1
              date = temp

            if  temp? and attributes? and (typeof temp is 'string' || typeof temp is 'number')
              for attr in attributes
                if valueIndex.indexOf('created_time') isnt -1
                  if attr is 'datetime'
                    temp = @.dating(date, true)
                  else
                    temp = @.dating(date, false)

                if attr.toLowerCase() is 'text'
                  el.innerHTML = temp
                else
                  if valueIndex is 'type' && temp isnt 'image'
                    el.setAttribute(attr, 'iframe')
                  else
                    c = attr.charAt(0)
                    if !(c >= '0' && c <= '9')
                      el.setAttribute(attr, temp)
      return

    ###*
    * Convert Array to String
    * @protected
    * @param {Array} arr.
    ###
    arrToString: (arr) ->
      arr.join(' ')

    ###*
    * Formatting Date
    * @protected
    * @param {Number} time - Instagram date.
    * @param {Boolean} datetime - if true, formatting for datetime attribute.
    ###
    dating: (time, datetime) ->
      date = new Date(time * 1000)
      current = new Date()
      delta = parseInt((current.getTime() - date.getTime()) / 1000)
      delta += (current.getTimezoneOffset() * 60)

      months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
                'November', 'December']
      _date =
        '%d': date.getDate(),
        '%m': date.getMonth() + 1
        '%b': months[date.getMonth()].substring(0, 3)
        '%B': months[date.getMonth()]
        '%y': String(date.getFullYear()).slice -2
        '%Y': date.getFullYear()

      if datetime
        datef = '%Y-%m-%d'
      else
        datef = if element.getAttribute('data-instafeed-date-format') then element.getAttribute('data-instafeed-date-format') else @options.dateFormat.days

      if (delta < 60)
        return @options.dateFormat.seconds
      else if(delta < 120)
        return @options.dateFormat.minute
      else if(delta < (60 * 60))
        return (parseInt(delta / 60)).toString() + @options.dateFormat.minutes
      else if(delta < (120 * 60))
        return @options.dateFormat.hour
      else if(delta < (24 * 60 * 60))
        return 'about ' + (parseInt(delta / 3600)).toString() + @options.dateFormat.hours
      else if(delta < (48 * 60 * 60))
        return @options.dateFormat.day
      else
        formats = datef.match /%[dmbByY]/g
        datef = datef.replace format, _date[format] for format in formats
        return datef

    ###*
    * Sort Data
    * @protected
    * @param {data} time - data for sorting.
    * @param {Object} property
    * @param {Boolean} reverse
    ###
    sortBy: (data, property, reverse) ->

      sorter = (a, b) ->
        valueA = @getObjectProperty a, property
        valueB = @getObjectProperty b, property
        if reverse
          if valueA > valueB then return 1 else return -1

        if valueA < valueB then return 1 else return -1

      data.sort(sorter.bind(this))
      data

    ###*
    * Access an object property by string
    * @protected
    * @param {object} Object.
    * @param {String} property
    ###
    getObjectProperty: (object, property) ->
      property = property.replace /\[(\w+)\]/g, '.$1'

      pieces = property.split '.'

      while pieces.length
        piece = pieces.shift()

        if object? and piece of object
          object = object[piece]
        else
          return null

      object

    ###*
    * Build Url for getting data via function fetchData
    * @protected
    ###
    buildUrl: ->
      base = "https://api.instagram.com/v1"

      get = if @.element.getAttribute('data-instafeed-get') then @.element.getAttribute('data-instafeed-get') else @options.get

      switch get
        when "popular" then endpoint = "media/popular"

        when "tagged"
          tagName = if @.element.getAttribute('data-instafeed-tagname') then @.element.getAttribute('data-instafeed-tagname') else @options.tagName

          unless tagName
            throw new Error "No tag name specified. Use the 'tagName' option."


          endpoint = "tags/#{tagName}/media/recent"

        when "location"

          location = if @.element.getAttribute('data-instafeed-location') then @.element.getAttribute('data-instafeed-location') else @options.location
          unless location
            throw new Error "No location specified. Use the 'locationId' option."


          endpoint = "locations/#{location}/media/recent"

        when "user"

          user = if @.element.getAttribute('data-instafeed-user') then @.element.getAttribute('data-instafeed-user') else @options.userId
          unless user
            throw new Error "No user specified. Use the 'userId' option."

          endpoint = "users/#{user}/media/recent"
        when "profile"

          user = if @.element.getAttribute('data-instafeed-user') then @.element.getAttribute('data-instafeed-user') else @options.userId
          unless user
            throw new Error "No user specified. Use the 'userId' option."

          endpoint = "users/#{user}"

        else throw new Error "Invalid option for get: '#{@options.get}'."


      url = "#{base}/#{endpoint}"

      access = if @.element.getAttribute('data-instafeed-accesstoken') then @.element.getAttribute('data-instafeed-accesstoken') else @options.accessToken
      clientId = if @.element.getAttribute('data-instafeed-clientid') then @.element.getAttribute('data-instafeed-clientid') else @options.clientId

      if !!access
        url += "?access_token=#{access}"
      else
        url += "?client_id=#{clientId}"


      if @$items.length and get isnt "profile"
        url += "&count=#{@$items.length}"

      url += "&callback=instafeedCache#{@unique}.parse"
      url

    ###*
    * Generate a unique key
    * @protected
    ###
    genKey: ->
      S4 = ->
        (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1)
      "#{S4()}#{S4()}#{S4()}#{S4()}"

    ###*
    * Filter out images
    * @protected
    ###
    filter: (images, filter) ->
      filteredImages = []
      for image in images
        do (image) ->
          filteredImages.push(image) if filter(image)
      filteredImages


  ###*
   * The jQuery Plugin for the RD Instafeed
   * @public
  ###
  $.fn.extend RDInstafeed: (options) ->
    @each ->
      $this = $(this)
      if !$this.data('RDInstafeed')
        $this.data 'RDInstafeed', new RDInstafeed(this, options)

  window.RDInstafeed = RDInstafeed) window.jQuery, document, window


###*
 * The Plugin AMD export
 * @public
###
if module?
  module.exports = window.RDInstafeed
else if typeof define is 'function' && define.amd
  define(["jquery"], () ->
    'use strict'
    return window.RDInstafeed
  )