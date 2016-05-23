    var adminObject = {
        
        addPositionBtn : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#positionNameField').val() != '' && $('#EXCOSelect').val() != '' & $('#projectSelect').val() != '') {
                    var params = {name: $('#positionNameField').val(), exco: $('#EXCOSelect').val(), project: $('#projectSelect').val()};
                    common.insertRecord(params, 'position');
                } 
            });
        },
        
        changeCategory : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if(!$(this).hasClass('disabled')) {
                    var id = $(this).closest('tr').data('id');
                    var value = $(this).data('value');
                    var type = $(this).data('type');
                    var url = $('#position_categories').data('type');
                    $.post(url, {id: id, value: value, type: type}, function(data) {
                        console.log(data);
                        if (data && data.success) {
                            common.reloadAll();
                        }                        
                    },'json');
                }
                
            });
        },
        
        changePositionInTeam : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if(!$(this).hasClass('disabled')) {
                    var idArray = $(this).closest('td').attr('id').split('-');
                    var position_id = idArray[0];
                    var team_id = idArray[1];
                    var value = $(this).data('value');
                    var params = {position_id: position_id, team_id: team_id};
                    if(value == 1) {
                        common.removeRecord('','position_team',params);
                    } 
                    if(value == 0) {
                        common.insertRecord(params,'position_team');
                    }                   
                }
                
            });
        }
        

    };
    $(function() {
        "use strict";
        adminObject.addPositionBtn('#addPositionBtn');
        adminObject.changeCategory('.changeCategory');
        
        
        adminObject.changePositionInTeam('.changePositionInTeam');
        
    });
    
    