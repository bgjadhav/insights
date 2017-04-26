/**
 * PublisherManager.js
 * @created 22/01/2016
 * @author Fraser Reid <freid@mediamath.com>
 */
define(['Alert',
        'Chosen',
        'Request',
        'Table',
        'Handlebars',
        'Source',
        'Fooltip',
        'Text!Templates/publisher/publisher.handlebars',
        'Text!Templates/publisher/form.handlebars' ],
    function( alert,
              chosen,
              request,
              table,
              handlebars,
              source,
              fooltip,
              publisher,
              form ){

    return {
        ajax : {
            add_edit_publisher : 'publisher_database/add_edit_publisher',
            publisher : 'publisher_database/publishers'
        },

        div : {
            add : '#add',
            add_edit_publisher : '#add-edit-publisher',
            cancel : '#cancel',
            edit : '#edit',
            form : '#publisher-nav',
            input : {
                id : '[name="ID"]',
                partner_id : '[name="PARTNER_ID"]',
                publisher : '[name="PUBLISHER"]',
                sync : '[name="sync"]'
            },
            manage : '#add-edit-publisher',
            publisher : '.publisher',
            publisher_select : '.publisher select',
            publisher_logo : '.publisher .logo',
            publisher_name : '.publisher .name',
            publisher_nav : '#publisher-nav',
            save : '#save',
            sync : '.sync'
        },

        template: {},

        image : {},

        /**
         * Add Edit Publisher
         */
        add_edit_publisher : function( publisher_id, partner_id, name, sync ){
            var self = this;

            request.open(self.ajax.add_edit_publisher,
                $.post(self.ajax.add_edit_publisher, {
                    publisher_id : publisher_id,
                    partner_id : partner_id,
                    name : name,
                    sync : sync
                },function(data) {
                    if( data['status'] == true ) {
                        if (publisher_id) {
                            $(self.div.publisher_select).find('[data-publisher-id="' + data['publisher_id'] + '"]').text(name);
                            $(self.div.publisher_select).find('[data-publisher-id="' + data['publisher_id'] + '"]').val(name);
                            $(self.div.publisher_select).find('[data-publisher-id="' + data['publisher_id'] + '"]').attr('data-sync', sync);
                            alert.set( 'success', 'Publisher updated' );
                        }else{
                            $(self.div.publisher_select).append('<option data-publisher-id="' + data['publisher_id'] + '" data-partner-id="' + data['partner_id'] + '" data-sync="' + data['sync'] + '" style="cursor: pointer;" value="' + name + '">' + name + '</option>');
                            alert.set( 'success', 'Publisher added' );
                        }

                        $(self.div.publisher_select).trigger("chosen:updated");

                    }else{
                        alert.set( 'error', data['error'] );
                    }
                    request.close(self.ajax.add_edit_publisher);
                })
            )
        },

        /**
         * Events
         */
        events : function(){
            var self = this;

            //Fooltip - events
            fooltip.events();

            //add
            $(self.div.form).on('click', self.div.add, function(e){
                e.preventDefault();
                self.toggle_button_active(self.div.add);

                $(self.div.publisher_select).val('');
                $(self.div.publisher_select).trigger("chosen:updated");
                $(self.div.publisher_logo).fadeOut(500, function () {
                    $(self.div.publisher_name).fadeOut(500, function () {
                        $(self.div.publisher_nav).find('.row:first-child').animate({height: 0});
                    });
                });

                $(self.div.add_edit_publisher + ' ul li').fadeIn();
                $(self.div.add_edit_publisher).find(self.div.sync).hide();
                $(self.div.add_edit_publisher).find(self.div.input.id).val('');
                $(self.div.add_edit_publisher).find(self.div.input.partner_id).val('');
                $(self.div.add_edit_publisher).find(self.div.input.publisher).fadeIn().val('');
                $(self.div.add_edit_publisher).find(self.div.sync + ' [value="0"]').attr('selected', 'selected');
                $(self.div.add_edit_publisher).find(self.div.save).text('Add');
            });

            //cancel add/edit
            $(self.div.manage).on('click', self.div.cancel, function(e){
                e.preventDefault();
                self.reset_form();
            });

            //edit
            $(self.div.form).on('click', self.div.edit, function(e){
                e.preventDefault();
                //Does not actually need to do anything as edit is do from the select
            });

            //save
            $(self.div.manage).on('click', self.div.save, function(e){
                e.preventDefault();
                var publisher_id = $(self.div.add_edit_publisher).find(self.div.input.id).val();
                var partner_id = $(self.div.add_edit_publisher).find(self.div.input.partner_id).val();
                var name = $(self.div.add_edit_publisher).find(self.div.input.publisher).val();
                var sync = $(self.div.add_edit_publisher).find(self.div.input.sync).val();
                self.add_edit_publisher(publisher_id, partner_id, name, sync);
            });

            //select publisher
            $(self.div.publisher).on('change', 'select', function(){
                var selected = $(self.div.publisher_select).find('option:selected');

                self.toggle_button_active(self.div.edit);

                $(self.div.add_edit_publisher + ' ul li').fadeIn();
                $(self.div.add_edit_publisher).find(self.div.input.id).val(selected.data('publisher-id'));
                $(self.div.add_edit_publisher).find(self.div.input.partner_id).val(selected.data('partner-id'));
                $(self.div.add_edit_publisher).find(self.div.input.publisher).val(selected.val());
                $(self.div.add_edit_publisher).find(self.div.save).text('Edit');

                if (selected.data('partner-id')) {
                    $(self.div.add_edit_publisher).find(self.div.sync + ' [value="' + selected.data('sync') + '"]').attr('selected', 'selected');
                    $(self.div.add_edit_publisher).find(self.div.sync).show();
                } else {
                    $(self.div.add_edit_publisher).find(self.div.sync + ' [value="0"]').attr('selected', 'selected');
                    $(self.div.add_edit_publisher).find(self.div.sync).hide();
                }

                //Toggle Image
                self.toggle_image();
            });
        },

        /**
         * Publisher Manage Page
         */
        generate_publisher_manager : function(){
            var self = this;
            var state = $.Deferred();
            var html = '';

            self.getPublishers().done(function (data) {
                html = source.html(self.template.publisher({
                        save: false,
                        edit: true,
                        add: true,
                        actions: false,
                        options: data
                    })
                );

                html += source.html(self.template.form());

                state.resolve(html);

                //Chosen drop down
                $(self.div.publisher_select).chosen({allow_single_deselect: true});
            });
            return $.when(state).done().promise();
        },

        /**
         * Init
         * @returns {*}
         */
        init : function(){
            var self = this;

            //Templates
            self.template['publisher'] = handlebars.compile(publisher);
            self.template['form'] = handlebars.compile(form);

            //Swag Helper
            Swag.registerHelpers(handlebars);

            //Initialize fooltips
            fooltip.init(self.div.add_edit_publisher);

            return self.generate_publisher_manager();
        },

        /**
         * Get Publishers
         * @internal return an array of publishers
         * @returns {*}
         */
        getPublishers : function(){
            var self = this;
            var state = $.Deferred();
            request.open(self.ajax.publisher,
                $.getJSON(self.ajax.publisher, function (data) {

                    //Reset image array for new list
                    self.image = {};

                    //Store images for later
                    $.each(data, function (index, value) {
                        if (value !== undefined) {
                            self.image[index] = value['logo'];
                        }
                    });

                    state.resolve(data);

                    $(self.div.publisher).find('select').chosen('trigger:updated');

                    request.close(self.ajax.publisher);
                })
            );
            return $.when(state).done().promise();
        },

        /**
         * Toggle Button Active
         * @param div
         */
        toggle_button_active : function(div){
            $(div).parent().parent().find('.blue').removeClass('blue');
            $(div).addClass('blue');
        },

        /**
         * Toggle Image
         */
        toggle_image : function(){
            var self = this;
            var selected = $(self.div.publisher_select).find('option:selected');

            $(self.div.publisher_name).fadeOut();
            $(self.div.publisher_logo).fadeOut();

            if( selected.text() != "" ) {
                var img = new Image();
                img.onload = function () {
                    $(self.div.publisher_nav).find('.row:first-child').animate({height: 150}, function () {
                        $(self.div.publisher_logo).attr("src", 'https://open.mediamath.com/_img/partners/logos/' + self.image[parseInt(selected.index()) - 1]).fadeIn();
                    });
                };

                img.onerror = function () {
                    $(self.div.publisher_nav).find('.row:first-child').animate({height: 150}, function () {
                        $(self.div.publisher_name).text(selected.text()).fadeIn();
                    });
                };

                img.src = 'https://open.mediamath.com/_img/partners/logos/' + self.image[parseInt(selected.index()) - 1];
            }else{
                $(self.div.publisher_nav).find('.row:first-child').animate({height: 0});
            }
        },

        /**
         * Reset Form
         */
        reset_form : function(){
            var self = this;
            self.toggle_button_active(self.div.edit);

            $(self.div.publisher_select).val('');
            $(self.div.publisher_select).trigger("chosen:updated");
            $(self.div.publisher_logo).fadeOut( 500, function(){
                $(self.div.publisher_name).fadeOut( 500, function(){
                    self.toggle_image();
                });
            });

            $(self.div.add_edit_publisher + ' ul li').fadeOut();
        }

    };
});