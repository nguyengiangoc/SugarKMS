    var adminObject = {
        
        addPageGroupBtn : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#pageGroupName').val() != '' && $('#pageGroupcPage').val() != '') {
                    var params = $('#addPageGroupForm').serializeArray();
                    var object = $(this).closest('table').data('object');
                    common.insertRecord(params, object);
                } 
            });
        },
        
        addPageBtn : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#pageName').val() != '' && $('#pageURLAction').val() != '') {
                    var params = $('#addPageForm').serializeArray();
                    var object = $(this).closest('table').data('object');
                    common.insertRecord(params, object);
                } 
            });
        },
        
        showPagesInGroup : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisRow = $(this).closest('tr');
                if(!$(thisRow).hasClass('currentRow')) {
                    $('.currentRow').removeClass('currentRow');
                    $(thisRow).addClass('currentRow');                    
                    adminObject.reloadPagesInGroups();
                }
                
            });
        },
        
        reloadPagesInGroups : function() {
            var id = $('.currentRow').data('id');
            common.loadSection('pages_in_group',{id: id})
            
        },
        
        changeGroup : function(thisIdentity) {
            "use strict";
            $(document).on('change', thisIdentity, function(e) {
                e.preventDefault();
                var value = $(this).closest('td').find('.chooseGroup').val();
                console.log(value);
                var object = $(this).closest('table').data('object');
                var id = $(this).closest('tr').data('id');
                common.updateRecord(id, {group_id: value}, 'page');
            });
        },
              

    };
    $(document).ready(function() {
        "use strict";
        
        adminObject.addPageGroupBtn('#addPageGroupBtn');
        adminObject.addPageBtn('#addPageBtn');
        
        adminObject.showPagesInGroup('.showPagesInGroup');
//        adminObject.addPage('.addActionPage');

        adminObject.changeGroup('.chooseGroup');

        


    });

                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
				   