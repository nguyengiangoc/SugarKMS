    $(document).ready(function() {
        $(".tabs").livequery(function() {
            $(".tabs").tabs();
        });  
    });
    
    
    var adminObject = {
               
        showInvolvement : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisRow = $(this).closest('tr');
                if(!$(thisRow).hasClass('currentRow')) {
                    var memberList = $(this).closest('.reloadSection');
                    memberList.find('.currentRow').removeClass('currentRow');
                    $(thisRow).addClass('currentRow');     
                    var involvementSection = memberList.closest('td').next('td').find('.reloadSection');        
                    var involvementId = $(thisRow).data('id');      
                    adminObject.reloadInvolvement(involvementSection, involvementId);
                }
                
            });
        },
        
        reloadInvolvement : function(involvement, id) {
            common.loadSection('involvement_details',{id: id}, involvement)
            
        },        
        
        changeInvolvementDetails : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisForm = $(this).closest('form');
                console.log(thisForm);
                var selectStart = $(thisForm).find('.selectStart');
                console.log(selectStart);
                var selectEnd = $(thisForm).find('.selectEnd');
                
                var error = [];
                
                var monthstart = parseInt($(selectStart).val().split('_')[0]);
                var yearstart = parseInt($(selectStart).val().split('_')[1]);
                var monthend = parseInt($(selectEnd).val().split('_')[0]);
                var yearend = parseInt($(selectEnd).val().split('_')[1]);
                if(yearstart > yearend || ((yearstart == yearend) && (monthstart > monthend))) { 
                    error.push('earlier');
                    //errorMessage += '- The start date must be earlier than the end date.' 
                }
                
                if(error.length == 0) {
                    var params = $(thisForm).serializeArray();
                    
                    params.push({name:'month_start',value:monthstart},{name:'year_start',value:yearstart},{name:'month_end',value:monthend},{name:'year_end',value:yearend});
                    console.log(params);
                    var involvementDetails = $(thisForm).closest('.reloadSection');
                    var memberList = $(involvementDetails).closest('table').find('div[data-plugin="project_manage_member"]');
                    var reloadSection = [memberList, involvementDetails];
                    var id = $(memberList).find('.currentRow').data('id');
                    //console.log(id);
                    common.updateRecord(id, params, 'involvement', reloadSection);
                    
                }
                
                
            });
        },
        
        closeInvolvementDetails : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault(); 
                var reloadSection = $(this).closest('.reloadSection');
                var sectionDetails = $(reloadSection).find('.sectionParams');
                sectionDetails.remove();
                $(reloadSection).closest('td').prev('td').find('.currentRow').removeClass('currentRow');
                common.reloadSection(reloadSection);
            });
        },
        
        addMember : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault(); 
                $('#addMemberMessageRow').empty().hide();
                var error = [];
                var errorMessage = '<span class="warn"><span class="notice">NOTICE</span>:<br />';
                if($('#autocomplete').val() == '') {
                    error.push('name');
                    errorMessage += '- The name field is empty.</br>'; 
                }
                if($('#selectPosition').val() == '') {
                    error.push('position');
                    errorMessage += '- The position field is empty.</br>'; 
                }
                if($('#selectTeam').val() == '') {
                    error.push('team');
                    errorMessage += '- The team field is empty.</br>'; 
                }
                errorMessage += '</span>';
                if(error.length !== 0) {
                    //$('#addMemberMessageRow').append(errorMessage);
//                    $('#addMemberMessageRow').show();
                } else {
                    var params = $('#addMemberForm').serializeArray();

                    var url = $('#commonLinks').data('add');
                    if($('#verified').val() == 'true' && $('#member_id') !== '') {
                        common.insertRecord(params,'involvement');                       
                    }
                }
                
            
            });
        },
        
        addApplication : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault(); 
                var thisForm = $(this).closest('form');
                var position = $(thisForm).find('.selectPosition').val();
                var team = $(thisForm).find('.selectTeam').val();
                var datepicker = $(thisForm).find('.datepicker').val();
                
                if(position != '' && team != '' && datepicker != '') {
                    var params = $(thisForm).serializeArray();
                    var reloadSection = $(this).closest('.ui-tabs-panel').find('.reloadSection');
                    common.insertRecord(params, 'application', reloadSection);
                }
                
            
            });
        },
        

        removeMember: function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var involvementDetails = $(this).closest('.reloadSection');
                var memberList = $(involvementDetails).closest('table').find('div[data-plugin="project_manage_member"]');
                var reloadSection = [memberList, involvementDetails];
                var id = $(memberList).find('.currentRow').data('id');

                common.removeRecord(id, 'involvement', '', reloadSection);
            });
        },
        
        getTeam: function(thisIdentity) {
            
            $(document).on('change', thisIdentity, function(e) {
                var selectTeam = $(this).closest('table').find('.selectTeam');
                //console.log(selectTeam);
                $(selectTeam).removeAttr('disabled');
                $(selectTeam).empty();

                var position = $(this).val();
                if(typeof(position) == 'undefined' || position == '') {
                    $(selectTeam).attr('disabled', 'disabled');
                }
                var type = $('#project_type_id').val();
                var url = $('#links').data('get_team');
                
                $.post(url, {position_id: position, project_type_id: type}, function(data) {

                    $.each(data, function(index, team) {
                        
                        $(selectTeam).append('<option value="' + team['id'] + '" class="teamOption">' + team['name'] + '</option>');
                    });
                    
                }, 'json');
            });
        }

        

        
        

    };
    $(function() {
        "use strict";
        
        
        /* EDIT PROJECT */
        adminObject.addMember('#addMemberBtn');
        adminObject.addApplication('#addApplicationBtn');

        adminObject.removeMember('.removeMemberBtn');

        adminObject.closeInvolvementDetails('.closeInvolvementDetails');
        adminObject.showInvolvement('.showInvolvement');
        adminObject.getTeam('.selectPosition');
        adminObject.changeInvolvementDetails('.changeInvolvementDetailsBtn');
    
    });
    
    $(function() {
        
        $(".datepicker" ).on('click', function(e) {
            $('.ui-state-hover').removeClass('ui-state-hover');
        }).livequery(function() {
            $(".datepicker" ).datepicker({
                showOn: "button",
                buttonImage: "/sugarkms/images/cal_icon.png",
                buttonImageOnly: true,
                dateFormat: "dd-mm-yy",
                minDate: 0,
                beforeShow: function (textbox, instance) {
                    instance.dpDiv.css({
                            marginTop: (-textbox.offsetHeight) + 'px',
                            marginLeft: parseInt(textbox.offsetWidth) + 30 + 'px'
                    });
                    $('.ui-state-hover').removeClass('ui-state-hover');
                },
                onSelect: function() {
                    $(this).data('datepicker').inline = true;     
                                              
                },
                onClose: function() {
                    $(this).data('datepicker').inline = false;
                }
            }).keyup(function(e) {
                if(e.keyCode == 8 || e.keyCode == 46) {
                    $.datepicker._clearDate(this);
                    $('.ui-state-hover').removeClass('ui-state-hover');
                    $('.ui-datepicker-current-day').removeClass('ui-datepicker-current-day');
                }
            });
        });
        
        
        var selectPosition = $('#autocomplete').closest('table').find('.selectPosition');
        var selectTeam = $('#autocomplete').closest('table').find('.selectTeam');
        var nameUrl = $('#autocomplete').data('url');
        if($('#autocomplete').length) {
            $("#autocomplete").livequery(function() {
                $("#autocomplete").autocomplete({
                    source: function(request, response) {
    				    var projectId = $('#project_id').val();
    				    $.post(nameUrl , { name: request.term, projectId: projectId }, 
                        function(data) {
                            if(!data.length) {
                                var result = [{label: 'No profile found for this member. Click to add.', value: 'no'}];
                                response(result);
                            } else {
                                response($.map(data,function(item) {
                                        return {
                                            name: item.name,
                                            value: item.name,
                                            id: item.id,
                                            email: item.email,
                                            in_project: item.in_project
                                        }
                                    
                                }));
                            }
                            
                        },'json');
                    }, //end of source
                    select: function (e, ui) {
                        if(ui.item.value == "no") {
                            return false;                           
                        } else {
                            if(ui.item.in_project == "1") {
                                e.preventDefault();
                            } else {
                                //thanh vien do co trong co so du lieu
                                $('#checkIcon').css('visibility','visible'); //hien ra dau check xanh la cay
                                $('#member_id').val(ui.item.id); //gan vao hidden field member Id
                                $('#verified').val('true'); //xac nhan la thanh vien co trong csdl
                                $(selectPosition).removeAttr('disabled');
                            }
                            
                        }
                    },
                    focus: function(event, ui) {
    					// prevent autocomplete from updating the textbox
    					event.preventDefault();
    				},
                    response: function(event, ui) {
                        if($('#verified').val() == 'true') {
                            $('#checkIcon').css('visibility','hidden');
                            $('#verified').val('');
                            $('#addMemberForm #member_id').removeAttr('value');
                            $(selectPosition).children('option').each(function () {
                                $(this).removeAttr('selected');
                            }); 
                            $(selectPosition).find('.positionOptionBlank').attr('selected', 'selected');
                            $(selectPosition).attr('disabled','disabled');
                            $(selectTeam).empty();
                            $(selectTeam).attr('disabled', 'disabled');
                            //if(!$(selectPosition).find('.positionOptionBlank').length) {
    //                            $(selectPosition).prepend('<option val="" class="positionOptionBlank" selected="selected"></option>');
    //                        }
    //                        if(!$(selectTeam).find('.teamOptionBlank').length) {
    //                            $('#autocomplete').siblings('.selectTeam').prepend('<option val="" class="teamOptionBlank" selected="selected"></option>');
    //                        }
                        }
                    }
                }).data("ui-autocomplete")._renderItem = function( ul, item ) {
                    if(item.value == "no") {
                        return $( "<li></li>" )
                            .data( "item.autocomplete", item )
                            .append( '<span style="margin-left:6px;margin-right:6px;"><a href="'+$('#autocomplete').data('add') + '" style="text-decoration:none;" target="_blank">' + item.label + '</a></span>' ) 
                            .appendTo( ul );
                    } else {
                        if(item.in_project == "0") {
                            if(item.email == '') {
                                return $( "<li></li>" )
                                .data( "item.autocomplete", item )
                                .append( '<span style="margin-left:6px;margin-right:6px;">'+ item.name + '</span>' ) 
                                .appendTo( ul );
                            } else {
                                return $( "<li></li>" )
                                .data( "item.autocomplete", item )
                                .append( '<span style="margin-left:6px;margin-right:6px;">'+ item.name + ', ' + item.email + '</span>' ) 
                                .appendTo( ul );
                            }
                            
                            
                            
                        } else {
                            return $( "<li></li>" )
                            .data( "item.autocomplete", item )
                            .append( '<span style="margin-left:6px;margin-right:6px;">'+ item.name + ', ' + item.email + ' <strong>(added)</strong>' + "</span>" ) 
                            .appendTo( ul );
                        }
                        
                    }
                    
                };
            });
            
        }
            
            
            
            
            
            
            
			
            
            
            
            
            
            

              
    });   
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
				   