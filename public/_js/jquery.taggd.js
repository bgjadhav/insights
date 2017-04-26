/*
 * jQuery Taggd
 * A helpful plugin that helps you adding 'tags' on images.
 *
 * Copyright (C) 2014 Tim Severien
 * License: MIT
 */

(function($) {
    var settings = {
        'align': {
            'x': 'center',
            'y': 'center'
        },

        'handlers': {},

        'offset': {
            'left': 0,
            'top': 0
        }
    };

    var methods = {
        'init': function(opt) {
            var wrapper = $('<div class="taggd-wrapper" />');
            var $this = this;

            $this.wrap(wrapper);
            
            $this._data = [];

            $this.settings = {};
            $.extend(true, $this.settings, settings);
            $.extend(true, $this.settings, opt);

            $(window).resize(function() {
                methods.draw.call($this);
            });
			
			$(window).resize();

            return this;
        },

        'items': function(items) {
        	var $this = this;
            $this._data = items;
            
            var $wrapper = $this.parent('.taggd-wrapper');
	        $wrapper.find('.taggd-item').remove();

            $this.css({ 'height': 'auto', 'width': 'auto' });

            var height = this.height;
            var width = this.width;

            $.each(items, function(i, v) {
                var item = $('<span class="taggd-item" style="position: absolute;" />');

                if(v.x > 1 && v.x % 1 === 0 && v.y > 1 && v.y % 1 === 0) {
                    v.x = v.x / width;
                    v.y = v.y / height;
                }

                item.attr('data-x', v.x);
                item.attr('data-y', v.y);
                item.attr('data-toggle', 'modal');
                if(v.modal_id != '') item.attr('data-target', '#' + v.modal_id);
                item.attr('data-user_id', v.user_id);

                // orientation
                var orientation = '';
                if(v.facing == 'up' || v.facing == 'down') orientation = 'h';
                else orientation = 'v';
                // Second floor needs scaled down tables
                if(v.floor == '1' && v.machine != 'arcade') item.addClass('small_desk_' + orientation);
                // Fifth floor also needs scaled ddown tables
                if(v.floor == '5' && v.machine != 'arcade') item.addClass('small_desk_' + orientation);

                if(v.machine != 'arcade') {
                    item.addClass(v.machine + '_' + v.facing);
                } else {
                    item.addClass(v.machine);
                } 

                $wrapper.append(item);

                if(typeof v.text === 'string' && v.text.length > 0 && v.modal_id != '') {
                    var hover = $('<span class="taggd-item-hover" />').html(
                        '<div style="margin: auto; width: 100px;">' +
                        '<img class="circular-mini" src="' + v.avatar + '" /></div>' +
                        '<div style="text-align:center; font-weight: bold;">' + v.text + '</div>'
                    );

                    hover.attr('data-x', v.x);
                    hover.attr('data-y', v.y);

                    $wrapper.append(hover);
                }

                if(typeof $this.settings.handlers === 'object') {
                    for(var h in $this.settings.handlers) {
                        var f = $this.settings.handlers[h];

                        if(typeof f === 'string') {
                            switch(f) {
                                case 'show':
                                    item.on(h, methods.show);
                                    break;
                                case 'hide':
                                    item.on(h, methods.hide);
                                    break;
                                case 'toggle':
                                    item.on(h, methods.toggle);
                                    break;
                            }
                        } else if(typeof f === 'function') {
                            item.on(h, v, $this.settings.handlers[h]);
                        }
                    }
                }
            });

            $this.removeAttr('style');
            methods.draw.call($this);
        },
        
        'data':function(){
            return this._data;
        },

        'show': function() {
            var $this = $(this);
            $this.addClass('active');
            $this.next().addClass('show');
        },

        'hide': function() {
            var $this = $(this);
            $this.removeClass('active');
            $this.next().removeClass('show');
        },

        'toggle': function() {
            var hover = $(this).next();

            if(hover.hasClass('show')) methods.hide.call(this);
            else methods.show.call(this);
        },
		'clear':function(){
            var $this = this;
            var $wrapper = $this.parent('.taggd-wrapper');
            $wrapper.find('.taggd-item').remove();
            $wrapper.find('.taggd-item-hover').remove();
            $this.unwrap($('<div class="taggd-wrapper" />'));
        },
        'draw': function() {
            var $this = this;
            var $parent = $this.parent('.taggd-wrapper');
			
			if($(window).width() > 850) {
				$parent.removeAttr('style').css({
					'width': $this.width() -250
				});
			} else {
				$parent.removeAttr('style').css({
					'width': $this.width()
				});
			}

            $parent.find('span').each(function(i, e) {
                var $el = $(e);

                var left = $el.attr('data-x') * $this.width();
                var top = $el.attr('data-y') * $this.height();

                if($el.hasClass('taggd-item')) {
                    $el.css({
                        'left': left - $el.outerWidth(true) / 2,
                        'top': top - $el.outerHeight(true) / 2
                    });
                } else if($el.hasClass('taggd-item-hover')) {
                    if($this.settings.align.x === 'center') {
                        left -= $el.outerWidth(true) / 2;
                    } else if($this.settings.align.x === 'right') {
                        left -= $el.outerWidth(true)
                    }

                    if($this.settings.align.y === 'center') {
                        top -= $el.outerHeight(true) / 2;
                    } else if($this.settings.align.y === 'bottom') {
                        top -= $el.outerHeight(true)
                    }

                    if ($el.prev().hasClass('small_desk_h')) {
                            $el.css({
                                'left': left - 150,
                                'top': top + 60
                            });
                        }
                        else {
                            $el.css({
                                'left': left + $this.settings.offset.left,
                                'top': top + $this.settings.offset.top
                            });
                        }
                }
            });
        }
    };

    $.fn.taggd = function(opt) {
        if(methods[opt]) {
            return methods[opt].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof opt === 'object' || !opt) {
            return methods.init.apply(this, arguments);
        }

        return this;
    };
})(jQuery);
