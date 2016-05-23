    var adminObject = {       
        addAbbrForm : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $('.addAbbr').remove();
                var id = $(this).closest('tr').data('id');
                //var thisTemp = '<tr><td class=" prompt"></td><td class=" prompt" style="vertical-align:center;">';
//                thisTemp += '<input type="text" style="width:100px;" />&nbsp;';
//                thisTemp += '</td>';
//                thisTemp += '<td class=" prompt"><a href="#" class="removePrompt">Close</a></td></tr>';
                var thisTemp = '<div class="addAbbr" style="margin-top:3px;">';
                thisTemp += '<input type="text" style="width:80%;" class="addAbbrInput" data-id="'+id+'" />&nbsp;';
                thisTemp += '<a href="#" class="removeAbbrForm">Close</a></div>';
                $(this).after(thisTemp);
                $('.addAbbrInput').focus();
            }); 
        },
        
        removeAbbrForm : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $('.addAbbr').remove();
            }); 
        },
        
        addAbbrInput : function(thisIdentity) {
            "use strict";
            $(document).on('keypress', thisIdentity, function(e) {
                if(e.which == 13) {
                    var thisValue = $(this).val();
                    var id = $(this).data('id');
                    if(thisValue != '') {
                        var params = {school_id: id, abbr: thisValue, high_school: 0};
                        common.insertRecord(params, 'school_abbr');
                        
                    }
                }
            }); 
        },
        
        removeAbbr : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                common.removeRecord(id, 'school_abbr');                      
            });
        }

        

    };
    $(function() {
        "use strict";
        adminObject.addAbbrForm('.addAbbrForm');
        adminObject.removeAbbrForm('.removeAbbrForm');
        adminObject.addAbbrInput('.addAbbrInput');
        adminObject.removeAbbr('.removeAbbr');
    });


    