    var adminObject = {        
        addTeamBtn : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#teamNameField').val() != '' && $('#EXCOSelect').val() != '' & $('#projectSelect').val() != '') {
                    var params = {name: $('#teamNameField').val(), exco: $('#EXCOSelect').val(), project: $('#projectSelect').val()};
                    common.insertRecord(params, 'team');
                }
            });
        },
        
        clickYesNo : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if(!$(this).hasClass('disabled')) {
                    var id = $(this).closest('tr').data('id');
                    var value = $(this).data('value');
                    var type = $(this).data('type');
                    var url = $('#team_categories').data('type');
                    $.post(url, {id: id, value: value, type: type}, function(data) {
                        if (data && data.success) {
                            common.reloadAll()  
                        }
                    },'json');
                }
                
            });
        },      
        

    };
    $(function() {
        "use strict";

        adminObject.addTeamBtn('#addTeamBtn');
        adminObject.clickYesNo('.clickYesNo');

        
    });

    