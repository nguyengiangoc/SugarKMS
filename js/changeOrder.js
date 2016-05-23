    var changeOrder = {
        
        changeOrder: function() {
            $('.changeOrder').livequery(function() {
                $('.changeOrder').tableDnD({
                    onDrop : function(thisTable, thisRow) {
                        var params = $.tableDnD.serialize();
                        var object = $(thisTable).closest('table').data('object');
                        var url = $('#commonLinks').data('change_order');
                        var reloadSection = $(thisTable).closest('.reloadSection');
                        
                        $.post(url, {params: params, object: object} , function(data) {
                        
                        }, 'json').done(function() {
                            common.reloadSection(reloadSection);
                        });
                    } 
                });
            });
        }
    };
    
    $(function() {
        "use strict";
        
        changeOrder.changeOrder();

    });        

    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
				   