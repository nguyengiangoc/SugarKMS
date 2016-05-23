    var adminObject = {
               
        reloadPageDetails : function() {
            var id = $('.currentRow').data('id');
            common.loadSection('page_details',{id: id})
            
        },
                
        changeAccessMode: function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var id = $('.currentRow').data('id');
                var thisObj = $(this);
                var everyone = $(this).data('everyone');
                var url = $('#links').data('access_mode');
                $.post(url, {id: id, everyone: everyone}, function(data){
                    if (data && data.success) {
                        adminObject.reloadPageDetails();
                    } 
                }, 'json');
                
            });
        },
        
        toggleAccessCriteria: function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if(!$(this).hasClass('currentRow')) {
                    
                    $('.currentRow').removeClass('currentRow');
                    
                    $(this).addClass('currentRow');                    
                    adminObject.reloadPageDetails();
                }
                
            });
        },
        
        getPosition : function (thisIdentity) {
            $(document).on('change', thisIdentity, function(e) {
                var thisRow = $(this).closest('tr');
                
                if($(thisRow).find('#typeBlank').length > 0) {
                    $(thisRow).find('#typeBlank').remove();
                }
                var type = $(this).val();
                
                $(thisRow).find('.position').empty();
                $(thisRow).find('.position').append('<option id="positionBlank"></option>');
                $(thisRow).find('.team').empty();
                $(thisRow).find('.team').append('<option id="teamBlank"></option>');
                $(thisRow).find('.team').attr('disabled', 'disabled');
                $.post($('#links').data('get_position'), {type: type}, function(data) {
                    if(data && data.success) {
                        $.each(data.positions, function(k, v) {
                            $(thisRow).find('.position').append('<option value="'+v['id']+'">'+v['name']+'</option>'); 
                        });
                        $(thisRow).find('.position').removeAttr('disabled');
                    }
                },'json');
                
                
                
            });
        }, 
        
        getTeam : function (thisIdentity) {
            $(document).on('change', thisIdentity, function(e) {
                var thisRow = $(this).closest('tr');
                $(thisRow).find('#positionBlank').remove(); 

                var position = $(this).val();
                
                $(thisRow).find('.team').empty();
                var val = $(thisRow).find('.exco_project').val();
                if(val == 1) {
                    var type = 5;
                } else {
                    var type = 6
                }
                $.post($('#links').data('get_team'), {project_type_id: type, position_id: position}, function(data) {
                    
                        $.each(data, function(k, v) {
                            $(thisRow).find('.team').append('<option value="'+v['id']+'">'+v['name']+'</option>'); 
                        });
                        $(thisRow).find('.team').removeAttr('disabled');
                    
                },'json');
                
                
                
            });
        }, 
        
        addInvolvementCriteria : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisRow = $(this).closest('tr');
                var exco = $(thisRow).find('.exco_project').val();
                var currency = $(thisRow).find('.currency').val();
                var position = $(thisRow).find('.position').val();
                var team = $(thisRow).find('.team').val();
                if(exco != '' && currency != '' && position != '' && team !='') {
                    var page_id = $('.currentRow').data('id');
                    var params = {page_id: page_id, exco_project: exco, currency: currency, position_id: position, team_id: team, by_involvement: 1};
                    common.insertRecord(params, 'page_criteria');
                    //adminObject.reloadPageDetails();
                }
            });
        },
        
        addMemberOtherCriteria : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisRow = $(this).closest('tr');
                var other = $(thisRow).find('.other').val();
                if(other != '') {
                    var page_id = $('.currentRow').data('id');
                    var params = {by_involvement: 0, other: other, page_id: page_id};
                    common.insertRecord(params, 'page_criteria');
                    adminObject.reloadPageDetails();
                        
                    
                }
            });
        },
        
        addParamsBtn : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#paramField').val() != '') {
                    var id = $(this).closest('.reloadSection').find('.sectionParams').data('params').split('=')[1] ;    
                    var params = {page_id: id, param: $('#paramField').val() , required_value: $('#requiredField').val()};
                    common.insertRecord(params, 'page_params');
                } 
            });
        },
        
        
        getFolderContent: function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function() {
                var value = $(this).val();
                var thisObj = $(this);
                var selectFile = thisObj.closest('form').find('.selectFile');
                selectFile.empty();
                if(value != '') {
                    var url = $(this).data('url');
                    
                    var type = $(this).data('type');
                    $.post(url, { folder: value, type: type}, function(data) {
                        if(typeof(data) != 'undefined' && data != '') {
                            var parentForm = thisObj.closest('form');
                            
                            selectFile.removeAttr('disabled');
                            
                            console.log(data);
                            $.each(data, function(k, v) {
                                if(v != '.' && v != '..') {
                                    selectFile.append('<option>'+v+'</option>');
                                }
                            });
                        } 
                    }, 'json');
                } else {
                    selectFile.attr('disabled','disabled');
                }
            });
        },
        
        changeJSDirectory: function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var parentForm = $(this).closest('form');
                var folder = $(parentForm.find('.selectFolder')).val();
                var ds = $(this).data('ds');
                var file = $(parentForm.find('.selectFile')).val();
                common.updateRecord($('.currentRow').data('id'), {js_file_directory: folder+ds+file}, 'page');
                
            });
        },
        
        changePHPDirectory: function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var parentForm = $(this).closest('form');
                var folder = $(parentForm.find('.selectFolder')).val();
                var ds = $(this).data('ds');
                var file = $(parentForm.find('.selectFile')).val();
                common.updateRecord($('.currentRow').data('id'), {php_file_directory: folder+ds+file}, 'page');
                
            });
        },
        
        changeGroupBtn : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var value = $('.chooseGroup').val();
                var object = $(this).closest('table').data('object');
                common.updateRecord($('.currentRow').data('id'), {group_id: value}, 'page');
            });
        },


        
        
        
        

    };
    $(document).ready(function() {
        "use strict";
        
        adminObject.toggleAccessCriteria('.fl_l .clickable');
        adminObject.changeAccessMode('.changeAccessMode');
        adminObject.getPosition('.exco_project');
        adminObject.getTeam('.position');
        
        adminObject.addParamsBtn('#addParamBtn');
        
        adminObject.addInvolvementCriteria('.addInvolvementCriteria');
        adminObject.addMemberOtherCriteria('#addMemberOtherCriteria');

        adminObject.getFolderContent('.selectFolder');
        adminObject.changeJSDirectory('#changeJSDirectory');
        adminObject.changePHPDirectory('#changePHPDirectory');
        
        adminObject.changeGroupBtn('#changeGroupBtn');
        
    });

                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
				   