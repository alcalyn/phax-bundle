
var help = {
    
    init: function()
    {
        console.log('Phax help controller has been loaded.');
    },
    
    defaultReaction: function(r)
    {
        console.log(r.phax_metadata.message);
    },
    
    testReaction: function(r)
    {
        console.log(r);
    },
    
    pingReaction: function(r)
    {
        console.log(r.phax_metadata.message);
    }
    
};