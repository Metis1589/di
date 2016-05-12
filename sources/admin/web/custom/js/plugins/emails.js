(function ($) {
    $.widget('custom.emails', {

        _create: function () {
            var widget = this;
            
            widget.defaultEmail = widget._getDefaultEmail();
            
            var addEmailEl = $(widget.element).find('.new_restaurant_contact_email'); 
            var delEmailEl = $(widget.element).find('.restaurant_contact_email'); 
            widget._bindDelete(delEmailEl);
            
            var cnt = $(widget.element).find('.restaurant_contact_email').length;
            if(cnt<2){
                $('.restaurant_contact_email_delete').hide();
            }
            
            addEmailEl.unbind('click');
            addEmailEl.bind('click', widget._createEmail());
            
        },
        
        _bindDelete: function(email) {
            var widget = this;
            $(email).find('.restaurant_contact_email_delete').unbind('click');
            $(email).find('.restaurant_contact_email_delete').bind('click', widget._deleteEmail());
        },
        
        _createEmail: function() {
            var widget = this;
            return function(e) {
                 e.preventDefault();
                 var emailCount = parseInt($(widget.element).find('.restaurant_contact_email:last').attr('data-count'));
                 var newEmailContent = widget.defaultEmail.replace(/0/g, emailCount + 1);
                 var newEmail = $('<div/>', { 
                     'class':'restaurant_contact_email',
                     'data-count': emailCount+1
                 }).html(newEmailContent);
                 newEmail.find('input[id$="id"]').remove();
  
                var lastEmail = $(widget.element).find('.restaurant_contact_email:last'); 
                if (lastEmail.lenght == 1){
                    lastEmail.after(newEmail);
                }
                else{
                    $(widget.element).prepend(newEmail);
                }
                
                $(widget.element).find('.restaurant_contact_email:last').after(newEmail);
                
                $('.restaurant_contact_email_delete').show();
                widget._bindDelete(newEmail);
                 
                $("#w0").yiiActiveForm('add', {
                    "id":"billing_email_"+ (emailCount + 1) + "_email",
                    "name":"Billing[emails]["+ (emailCount + 1) + "][email]",
                    "container":".billing_email_"+ (emailCount + 1) + "_email",
                    "input":"#billing_email_"+ (emailCount + 1) + "_email",
                    "message":".billing_email_"+ (emailCount + 1) + "_email .help-block",
                    "validateOnType":true,
                    "validate":function (attribute, value, messages, deferred) {
                        yii.validation.required(value, messages, { "message":$(".email-error-source").val()});
                        yii.validation.email(value, messages, {
                            "pattern":/^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/,
                            "fullPattern":/^[^@]*<[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/,
                            "allowName":false,
                            "message":$(".email-format-error-source").val(),
                            "enableIDN":false,
                            "skipOnEmpty":1
                        });
                    }
                });
                 
            }
        },
        
        _deleteEmail: function() {
            var widget = this;
             return function(e) {
                var cnt = $(e.target).closest('.restaurant-contact-emails').find('.restaurant_contact_email').length;
                if(cnt==2){
                    $('.restaurant_contact_email_delete').hide();
                }
                if(cnt>1){
                    var id = $(e.target).parents('.restaurant_contact_email').attr('data-id');
                    if (id != '0' && id != undefined) {
                        var deletedPhones = $(widget.element).find('.deleted_restaurant_contact_emails');
                        var deletedIds = deletedPhones.val();
                        deletedPhones.val(deletedIds + ',' + id);
                    }
                    $(e.target).parents('.restaurant_contact_email').remove();    
                }
             }
        },
        
        _getDefaultEmail: function() {
             var widget = this;
             var defaultEmail = $(widget.element).find('.restaurant_contact_email:first').clone();
             defaultEmail.find('input[id$="email"]').val(''); 
             defaultEmail.find('input[id$="email"]').attr('value', '');
             return defaultEmail.html();
        },
        
        _beforeChange: function() {
            return function() {
                $('#wait-spinner').show();
            }
        },
        
        _changedSuccefully: function() {
            return function(result) {
                window.location.reload();
            }
        },
        
        _onError: function() {
            return function(result) {
                $('#wait-spinner').hide();
                alert('Error occcured.')
            }
        },
            
        destroy: function () {
            this._super('_destroy');
        },

    });
} (jQuery));


$(document).ready(function () {
    $('.restaurant-contact-emails').emails();
});