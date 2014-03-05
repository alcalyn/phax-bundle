

var phax = {
    
    controllers: [],
    
    action: function(controller, action, params)
    {
        data = params ? params : {} ;
        
        data['phax_metadata'] = {
            controller: controller,
            action: action ? action : 'default'
        };
        
        /*
         * Debug data : displays controller and action in request call
         */
        var debug_data = '?_'+controller+'_'+action;
        
        jQuery.post(
            phaxConfig.www_script+debug_data,
            data,
            function(r) {
                phax.reaction(controller, action, r);
            }
        )
        .fail(function(r) {
            phaxError.reactionFatalError(controller, action, r.responseText);
        });
    },
    
    reaction: function(controller, action, r)
    {
        action = action ? action : 'default' ;
        
        if (r.phax_metadata.has_error) {
            phaxError.reactionError(controller, action, r.phax_metadata.errors);
            return;
        }
        
        if (r.phax_metadata.trigger_js_reaction) {
            if (!phax.controller_loaded(controller)) {
                phax.load_controller(controller);
            }
            
            phaxCore.callControllerAction(controller, action, r);
        }
    },
    
    controller_loaded: function(controller)
    {
        return phax.controllers[controller] ? true : false ;
    },
    
    load_controller: function(controller)
    {
        if (!phax.controller_loaded(controller)) {
            if (window[controller]) {
                phax.controllers[controller] = window[controller];
                phaxCore.hasFunction(controller, 'init') && phaxCore.call(controller, 'init');
            } else {
                phaxError.controllerNotFound(controller);
            }
        }
    }
    
};



var phaxCore = {
    
    hasFunction: function(controller, fonction)
    {
        return (phax.controllers[controller] && phax.controllers[controller][fonction]);
    },
    
    call: function(controller, fonction, arg)
    {
        return phax.controllers[controller][fonction](arg);
    },
    
    callControllerAction: function(controller, action, r)
    {
        var fonction = action ? action+'Reaction' : 'defaultReaction';
        
        if (phaxCore.hasFunction(controller, fonction)) {
            return phaxCore.call(controller, fonction, r);
        } else {
            phaxError.reactionUndefined(controller, fonction);
        }
    }
};

var phaxError = {

    reactionError: function(controller, action, errors)
    {
        console.log('Phax reaction error in '+controller+'::'+action);
        console.log(errors);
        alert(errors[0]);
    },
    
    reactionFatalError: function(controller, action, r)
    {
        console.log('Phax reaction error (JSON parse error) in '+controller+'::'+action+', r = '+r);
    },
    
    reactionUndefined: function(controller, action)
    {
        console.log('Phax reaction undefined : '+controller+'::'+action);
    },
    
    controllerNotFound: function(controller_name)
    {
        console.log('phax Error : controller not found : "'+controller_name+'"');
    }
    
};


