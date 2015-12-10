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
                    console.log(data); 
                    if(data.length == 0) {
                        $('#project_year').attr('disabled', '');
                        $('#projectTypeMessage').html('All projects of this type have already been added.');                                    
                    } else {
                        $('#project_year').removeAttr('disabled');
                        $.each(data, function(index, value) {
                            if(typeId == 5) {
                                var nextYear = parseInt(value['value']) + 1;
                                $('#project_year').append('<option val="'+value['value']+'" class="projectYearOption">'+value['label']+' - '+nextYear+'</option>');
                            } else {
                                $('#project_year').append('<option val="'+value['value']+'" class="projectYearOption">'+value['label']+'</option>');
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
        },
        
        
        /* EDIT PROJECT PAGE */
        
        addMemberBtn : function(thisIdentity) {
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
                if($('#selectStart').val() == '') {
                    error.push('start');
                    errorMessage += '- The start date field is empty.</br>'; 
                }
                if($('#selectEnd').val() == '') {
                    error.push('end');
                    errorMessage += '- The end date field is empty.</br>'; 
                }
                var monthstart = parseInt($('#selectStart').val().split('_')[0]);
                var yearstart = parseInt($('#selectStart').val().split('_')[1]);
                var monthend = parseInt($('#selectEnd').val().split('_')[0]);
                var yearend = parseInt($('#selectEnd').val().split('_')[1]);
                if(yearstart > yearend || ((yearstart == yearend) && (monthstart > monthend))) { 
                    error.push('earlier');
                    errorMessage += '- The start date must be earlier than the end date.' 
                }
                errorMessage += '</span>';
                if(error.length !== 0) {
                    $('#addMemberMessageRow').append(errorMessage);
                    $('#addMemberMessageRow').show();
                } else {
                    var memberInfo = $('#addMemberForm').serializeArray();
                    
                    memberInfo.push({name:'month_start',value:monthstart},{name:'year_start',value:yearstart},{name:'month_end',value:monthend},{name:'year_end',value:yearend});
                    console.log(memberInfo);
                    var addMemberUrl = $('#addMemberForm').data('url');
                    if($('#verified').val() == 'true' && $('#memberId') !== '') {
                        $.post(addMemberUrl, memberInfo, function(data) {
                            //console.log(data);
                            if(data) {
                                var successMessage = '<span class="success"><span class="bold">NOTICE</span>: <span class="bold">' + $('#autocomplete').val() + '</span> has been added to this project.</span>';
                                $('#addMemberMessageRow').append(successMessage);
                                $('#addMemberMessageRow').slideDown('slow').delay(1500).slideUp();
                                if(!$('#positionOptionBlank').length) {
                                    $('#selectPosition').prepend('<option val="" id="positionOptionBlank" selected="selected"></option>');
                                }
                                if(!$('#teamOptionBlank').length) {
                                    $('#selectTeam').prepend('<option val="" id="teamOptionBlank" selected="selected"></option>');
                                }
                                if(!$('#startOptionBlank').length) {
                                    $('#selectStart').prepend('<option val="" id="startOptionBlank" selected="selected"></option>');
                                }
                                if(!$('#endOptionBlank').length) {
                                    $('#selectEnd').prepend('<option val="" id="endOptionBlank" selected="selected"></option>');
                                }
                                $('#selectPosition, #selectTeam, #selectStart, #selectEnd').attr('disabled', 'disabled');
                                $('#checkIcon').hide();
                                $('#autocomplete').val('');
                                var reload = $('#autocomplete').data('reload');
                                $.post(reload, function(data) {
                                    $('#list').html(data);
                                }, 'html');
                            }
                            
                        });
                    }
                }
                
            
            });
        },
        
        changePosition : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#member_list').children('.prompt').length > 0) {
                    $('#member_list').children('.prompt').parent('tr').remove();
                }
                var thisRow = this.closest('tr');
                var name = $(thisRow).find('td:first').text().trim();
                var split = name.split(' ');
                var length = split.length - 1;
                var shortName = split[split.length - 1];
                
                if($(this).data('exco') == 'yes') {
                    var project = 'EXCO';
                } else {
                    var project = 'project';
                }
                var actionPrompt = '<tr>' + adminObject.addPrompt(shortName, project) + '</tr>';
                $(thisRow).after(actionPrompt);
            });
        },
        
        addPrompt : function(shortName, project) {
            var actionPrompt = '<td class="br_td prompt" colspan="4"><strong>PROBLEM</strong>: What is the problem with this involvement?<a href="#" class="clearPrompt fl_r">Close</a><br />';
                actionPrompt += '<table class="problem_action">';
                    actionPrompt += '<tr><th>Problem</th><th>Action</th>';
                    //actionPrompt += '<tr>';
//                        actionPrompt += '<td><strong>' + shortName + '</strong> moved to another team or another position.</td>';
//                        actionPrompt += '<td><a href="#" class="newPosition"><strong>Update</strong></a> ' + shortName + '\'s new position and end the current one.</td>'
//                    actionPrompt += '</tr>';
                    actionPrompt += '<tr>';
                        actionPrompt += '<td><strong>' + shortName + '</strong> did serve in this ' + project + ', but the involvement time is incorrect.</td>';
                        actionPrompt += '<td><a href="#" class="changeTime" data-name="' + shortName + '" ><strong>Change</strong></a> this position\'s involvement time.</td>'
                    actionPrompt += '</tr>';
                    actionPrompt += '<tr>';
                        actionPrompt += '<td><strong>' + shortName + '</strong> did serve in this ' + project + ', but the position is incorrect.</td>';
                        actionPrompt += '<td><a href="#" class="removePosition"><strong>Remove</strong></a> this involvement, then add <strong>' + shortName + '</strong> in a different position.</td>'
                    actionPrompt += '</tr>';
                    actionPrompt += '<tr>';
                        actionPrompt += '<td><strong>' + shortName + '</strong> withdrew from this ' + project + ' along the way.</td>';
                        actionPrompt += '<td><a href="#" class="changeTime" data-name="' + shortName + '" ><strong>Change</strong></a> this position\'s involvement time.</td>'
                    actionPrompt += '</tr>';
                    actionPrompt += '<tr>';
                        actionPrompt += '<td><strong>' + shortName + '</strong> has never served in this position in this ' + project + '.</td>';
                        actionPrompt += '<td><a href="#" class="removePosition"><strong>Remove</strong></a> this involvement.</td>'
                    actionPrompt += '</tr>';
                    
                actionPrompt += '</table>';
                actionPrompt += '</td>';
                
            //var actionPrompt = '<td class="br_td prompt" colspan="4"><strong>ACTION PROMPT</strong>: What would you like to do with <strong>';
//                actionPrompt += shortName;
//                actionPrompt += '</strong>\'s position?<br />';
//                actionPrompt += '- <a href="#" class="changeTime" data-name="' + shortName + '" ><strong>Change</strong></a> this position\'s involvement time. <br />';
//                
//                actionPrompt += '- <a href="#" class="newPosition">Update</a> with a new position, because <strong>' + shortName + '</strong> was promoted, demoted, or moved to another team <strong>during</strong> this project. <br />';
//                actionPrompt += '- <a href="#" class="withdrawPosition">End</a> this position, because <strong>' + shortName + '</strong> withdrew from this ' + project + '.<br />';
//                actionPrompt += '- <a href="#" class="removePosition">Remove</a> this position, because <strong>' + shortName + '</strong> has never served in this position in this ' + project + ' (non-existent involvement). <br />';
//                actionPrompt += '- <a href="#" class="clearPrompt">Nothing, never mind.</a>'
//                actionPrompt += '</td>';
            return actionPrompt;
        },
        
        clearPrompt : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $(this).closest('.prompt').parent('tr').remove();
            });
        },
        
        backToPrompt : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var changeButton = $(this).closest('tr').prev('tr').find('.changePosition');
                if($(changeButton).data('exco') == 'yes') {
                    var project = 'EXCO';
                } else {
                    var project = 'project';
                }
                var name = $(this).closest('tr').prev('tr').find('td:first').text().trim();
                var split = name.split(' ');
                var length = split.length - 1;
                var shortName = split[split.length - 1];
                var actionPrompt = adminObject.addPrompt(shortName, project);
                $(this).closest('tr').html(actionPrompt);
            });
        },
        
        changeTime : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisCell = $(this).closest('.prompt');
                var content = '<strong>ACTION</strong> - Change involvement time: ';
                
                var changeButton = $(thisCell).closest('tr').prev('tr').find('.changePosition');
                
                var monthstart = $(changeButton).data('monthstart');
                var yearstart = $(changeButton).data('yearstart');
                var monthend = $(changeButton).data('monthend');
                var yearend = $(changeButton).data('yearend');
                
                var monthstartm = $(changeButton).data('monthstartm');
                var yearstartm = $(changeButton).data('yearstartm');
                var monthendm = $(changeButton).data('monthendm');
                var yearendm = $(changeButton).data('yearendm');
                
                var invid = $(changeButton).data('invid');
                var invurl = $(changeButton).data('invchange');
                
                var name = $(this).data('name');
                
                content += '<strong>' + name + '</strong> served in this position ';
                
                content += '<strong>from</strong> <select name="start" id="start" class="selectMonthYear">';
                
                if (yearstart != yearend) {
                    for(i=parseInt(monthstart);i<13;i++) {
                        content += '<option value="' + i+ '_' + yearstart + '" ';
                        if((i + '_' + yearstart) == (monthstartm + '_' + yearstartm)) {
                            content += ' selected="selected"';
                        }
                        content +='>' + i + '/' + yearstart + '</option>';
                    }
                    for(i=1;i<parseInt(monthend)+1;i++) {
                        content += '<option value="' + i+ '_' + yearend + '" ';
                        if((i + '_' + yearend) == (monthstartm + '_' + yearstartm)) {
                            content += ' selected="selected"';
                        }
                        content += '>' + i + '/' + yearend + '</option>';
                    }
                } else {
                    for(i=parseInt(monthstart);i<parseInt(monthend)+1;i++) {
                        content += '<option value="' + i+ '_' + yearstart + '" ';
                        if((i + '_' + yearstart) == (monthstartm + '_' + yearstartm)) {
                            content += ' selected="selected"';
                        }
                        content += '>' + i + '/' + yearstart + '</option>';
                    }
                }
                content += '</select>&nbsp;';
                
                content += '&nbsp;<strong>to</strong> <select name="end" id="end" class="selectMonthYear">';
                
                if (yearstart != yearend) {
                    for(i=parseInt(monthstart);i<13;i++) {
                        content += '<option value="' + i+ '_' + yearstart + '" ';
                        if((i + '_' + yearstart) == (monthendm + '_' + yearendm)) {
                            content += ' selected="selected"';
                        }
                        content += '>' + i + '/' + yearstart + '</option>';
                    }
                    for(i=1;i<parseInt(monthend)+1;i++) {
                        content += '<option value="' + i+ '_' + yearend + '" ';
                        if((i + '_' + yearend) == (monthendm + '_' + yearendm)) {
                            content += ' selected="selected"';
                        }
                        content += '>' + i + '/' + yearend + '</option>';
                    }
                } else {
                    for(i=parseInt(monthstart);i<parseInt(monthend)+1;i++) {
                        content += '<option value="' + i+ '_' + yearstart + '" ';
                        if((i + '_' + yearstart) == (monthendm + '_' + yearendm)) {
                            content += ' selected="selected"';
                        }
                        content += '>' + i + '/' + yearstart + '</option>';
                    }
                }
                content += '</select><br />';
                content += '<div class="warn" style="display:inline-block;"></div>'
                content += '<div class="fl_r"><a  class="saveTime" href="#" data-invurl="' + invurl + '" data-invid="' + invid + '">Save</a> &nbsp; <a class="backToPrompt" href="#">Back</a> &nbsp; <a class="clearPrompt" href="#">Cancel</a></div><br />'
                $(thisCell).html(content);
            });
        },
        
        saveTime : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var monthstart = $('#start').val().split('_')[0];
                var yearstart = $('#start').val().split('_')[1];
                var monthend = $('#end').val().split('_')[0];
                var yearend = $('#end').val().split('_')[1];
                var url = $(this).data('invurl');
                var invid = $(this).data('invid');
                var error = false;
                if(parseInt(yearstart) > parseInt(yearend)) { error = true; }
                if((parseInt(yearstart) == parseInt(yearend)) && (parseInt(monthstart) > parseInt(monthend))) { error = true; }
                if(error) { 
                    $('.prompt .warn').html('<span class="notice">NOTICE</span>: The start date must be earlier than the end date.');
                } else {
                    $.post(url , { id: invid, month_start: monthstart, year_start: yearstart, month_end: monthend, year_end: yearend }, function(data) {
                        if(data.success) {
                            var urlReload = $('.changePosition').data('projectreload');
                            //console.log(urlReload);
                            $.post(urlReload, function(data) {
                                $('#list').html(data);
                            }, 'html');
                            //window.location.reload();
                        } else {
                            $('.prompt .warn').html('<span class="notice">NOTICE</span>: The involvement time cannot be changed at the moment.')
                        }
                    },'json');
                }
            });
        },
        
        removePosition: function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisCell = $(this).closest('.prompt');
                var changeButton = $(thisCell).closest('tr').prev('tr').find('.changePosition');
                var invid = $(changeButton).data('invid');
                var invRemove = $(changeButton).data('invremove');
                var content = '<span class="warn"><span class="bold">ACTION</span> - Remove this position: Are you sure? This action <span class="bold">CANNOT</span> be reversed.</span>';
                content += '<div class="fl_r"><a  class="removeYes" href="#" data-remove="' + invRemove + '" data-invid="' + invid + '">Yes</a> &nbsp; <a class="backToPrompt" href="#">Back</a> &nbsp; <a class="clearPrompt" href="#">Cancel</a></div><br />'
                $(thisCell).html(content);
            });
        },
        
        removeYes: function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var invid = $(this).data('invid');
                var removeUrl = $(this).data('remove');
                $.post(removeUrl, {id: invid} , function(data) {
                    if (data && !data.error) {
                        var reload = $('#autocomplete').data('reload');
                        $.post(reload, function(data) {
                            $('#list').html(data);
                        }, 'html');
                    } 
                });
            });
        },
        
        getAllEmail : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#dialog').css('display') == 'none' || $('.ui-dialog').css('display') == 'none') {
                    var thisObj = $(thisIdentity);
                    if($('#volunteers').length > 0) {
                        $('#dialog').append('<strong>Organizers</strong><br />');
                        $.each($('#organizers .email'), function() {
                            if(!$(this).closest('tr').hasClass('withdrawn')) {
                                $('#dialog').append($(this).text()+', ');
                            }
                            
                        });
                        $('#dialog').append('<br /><br />');
                        $('#dialog').append('<strong>Volunteers</strong><br />');
                        $.each($('#volunteers .email'), function() {
                            if(!$(this).closest('tr').hasClass('withdrawn')) {
                                $('#dialog').append($(this).text()+', ');
                            }
                            
                        });
                    } else {
                        $.each($('.email'), function() {
                            if(!$(this).closest('tr').hasClass('withdrawn')) {
                                $('#dialog').append($(this).text()+', ');
                            }
                            
                        });
                    }
                    
                    $('#dialog').dialog({
                            autoOpen: false, 
                            title: 'All emails', 
                            draggble: true, 
                            resizable: false,
                            close: function() {
                                $('#dialog').html('');
                            }
                        }).dialog('open');
                }
                
            });
        },
        
        getTeamEmail : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#dialog').css('display') == 'none' || $('.ui-dialog').css('display') == 'none') {
                    var thisObj = $(this);
                    $.each($(thisObj).closest('tr').nextUntil('.teamRow'), function() {
                        if(!$(this).hasClass('withdrawn')) {
                            var text = $(this).find('td:eq(3)').text();
                            $('#dialog').append(text+', ');
                        }
                        
                    });                
                    $('#dialog').dialog({
                            autoOpen: false, 
                            title: 'Team emails', 
                            draggble: true, 
                            resizable: false,
                            close: function() {
                                $('#dialog').html('');
                            }
                        }).dialog('open');
                }
                
            });
        }
        
        

    };
    $(function() {
        "use strict";
        
        /* ADD PROJECT */
        adminObject.changeProjectType('#changeProjectType');
        adminObject.addProjectBtn('#addProjectBtn');
        
        
        /* EDIT PROJECT */
        adminObject.addMemberBtn('#addMemberBtn');
        
        adminObject.changePosition('.changePosition');
        adminObject.clearPrompt('.clearPrompt');
        adminObject.backToPrompt('.backToPrompt');
        adminObject.changeTime('.changeTime');
        adminObject.saveTime('.saveTime');
        adminObject.removePosition('.removePosition');
        adminObject.removeYes('.removeYes');
        
        /* VIEW PROJECT*/
        adminObject.getAllEmail('#getAllEmail');
        adminObject.getTeamEmail('.getTeamEmail');
    });
    
    $(document).ready(function() {
        $('#volunteers').show();
    })
    
    $(function() {
            var nameUrl = $('#autocomplete').data('url');
            if($('#autocomplete').length) {
                $("#autocomplete").autocomplete({
                    source: function(request, response) {
    				    var projectId = $('#projectId').val();
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
                                $('#memberId').val(ui.item.id); //gan vao hidden field member Id
                                $('#verified').val('true'); //xac nhan la thanh vien co trong csdl
                                $('#selectPosition, #selectStart, #selectEnd').removeAttr('disabled');
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
                            $('#selectPosition, #selectTeam, #selectStart, #selectEnd').attr('disabled','disabled');
                            if(!$('#positionOptionBlank').length) {
                                $('#selectPosition').prepend('<option val="" id="positionOptionBlank" selected="selected"></option>');
                            }
                            if(!$('#teamOptionBlank').length) {
                                $('#selectTeam').prepend('<option val="" id="teamOptionBlank" selected="selected"></option>');
                            }
                            if(!$('#startOptionBlank').length) {
                                $('#selectStart').prepend('<option val="" id="startOptionBlank" selected="selected"></option>');
                            }
                            if(!$('#endOptionBlank').length) {
                                $('#selectEnd').prepend('<option val="" id="endOptionBlank" selected="selected"></option>');
                            }
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
            }
            
            
            
            
            
            
            
			
            
            
            
            
            
            var positionPrevious;
            $('#selectPosition').
                focus(function(e) {
                    positionPrevious = $(this).val();
                }).
                change(function() {
                    if(positionPrevious == '') {
                        $('#positionOptionBlank').remove();
                        $('#teamOptionBlank').remove();
                        $('#selectTeam').removeAttr('disabled');
                    }
                    $('.teamOption').remove();
                    var id = $(this).val();
                    var type = $('#project_type_id').val();
                    var url = $(this).data('url');
                    
                    $.post(url, {position_id: id, project_type_id: type}, function(data) {
                        
                        console.log(data);
                        //var teams = JSON.parse(data);
                        $.each(data, function(index, team) {
                            
                            $('#selectTeam').append('<option value="' + team['id'] + '" class="teamOption">' + team['name'] + '</option>');
                        });
                        
                    }, 'json');
                });
            var startPrevious;
            $('#selectStart').
                focus(function(e) {
                    startPrevious = $(this).val();
                }).
                change(function() {
                    if(startPrevious == '') {
                        $('#startOptionBlank').remove();
                    }
                });
            var endPrevious;
            $('#selectEnd').
                focus(function(e) {
                    endPrevious = $(this).val();
                }).
                change(function() {
                    if(endPrevious == '') {
                        $('#endOptionBlank').remove();
                    }
                });
            
            //var typePrevious;
//            $('#changeProjectType').
//                focus(function(e) {
//                    typePrevious = $(this).val();
//                }).
//                change(function() {
//                    if(typePrevious == '') {
//                        $('#projectTypeBlank').remove();
//                    } 
//                });
            
            var projectYearPrevious;
            $('#project_year').
                focus(function(e) {
                    projectYearPrevious = $(this).val();
                }).
                change(function() {
                    if(projectYearPrevious == '') {
                        $('#projectYearBlank').remove();
                    } 
                });
            
            var clicked = false, clickY, clickX;
            $('.dragDiv').on({
                'mousemove': function(e) {
                    clicked && $(this).scrollLeft($(this).scrollLeft() + (clickX - e.pageX));
                    //updateScrollPos(e);
                },
                'mousedown': function(e) {
                    clicked = true;
                    clickY = e.pageY;
                    clickX = e.pageX;
                },
                'mouseup': function() {
                    clicked = false;
                    $('html').css('cursor', 'auto');
                }
            });
            $("#tabs").tabs();   
    });   
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
				   