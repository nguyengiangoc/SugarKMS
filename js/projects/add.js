    
    var adminObject = {
        
        /* ADD PROJECT PAGE */
        
        changeProjectType: function(thisIdentity) {
            $(document).on('change',thisIdentity, function(e) {
                if($('#projectTypeBlank').length != 0) {
                    $('#projectTypeBlank').remove();
                }
                $('#projectTypeMessage').html('');
                var url = $(thisIdentity).data('url');
                var typeId = $(thisIdentity).val();
                $('.projectYearOption').remove();
                if($('#projectYearBlank').length == 0) {
                    $('#project_year').append('<option val="" id="projectYearBlank"></option>');
                }
                
                $.post(url, {id: typeId }, function(data) {
                    
                    if(data.length == 0) {
                        $('#project_year').attr('disabled', '');
                        $('#projectTypeMessage').html('All projects of this type have already been added.');                                    
                    } else {
                        $('#project_year').removeAttr('disabled');
                        $.each(data, function(index, value) {
                            if(typeId == 5) {
                                var nextYear = parseInt(value['value']) + 1;
                                $('#project_year').append('<option value="'+value['value']+'" class="projectYearOption">'+value['label']+' - '+nextYear+'</option>');
                            } else {
                                $('#project_year').append('<option value="'+value['value']+'" class="projectYearOption">'+value['label']+'</option>');
                            }
                            
                        });
                    }
                }, 'json'); 
            });
        },
        
        addProjectBtn: function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                if($('#project_year').is(':disabled')) {
                    e.preventDefault();
                }
            });
        }     
        
    };
    $(function() {
        "use strict";
        
        /* ADD PROJECT */
        adminObject.changeProjectType('#changeProjectType');
        adminObject.addProjectBtn('#addProjectBtn');
        
    });
    
    
       
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
				   