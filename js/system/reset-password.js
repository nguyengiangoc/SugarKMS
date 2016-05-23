    $(function() {
            var nameUrl = $('#autocomplete').data('url');
            if($('#autocomplete').length) {
                $("#autocomplete").autocomplete({
                    source: function(request, response) {
    				    var projectId = $('#projectId').val();
    				    $.post(nameUrl , { name: request.term, projectId: projectId }, 
                        function(data) {
                            if(!data.length) {
                                var result = [{label: 'No profile found for this member.', value: 'no'}];
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
                                $('#new, #retype').removeAttr('disabled');
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
                            $('#new, #retype').attr('disabled','disabled');
                        }
                    }
                }).data("ui-autocomplete")._renderItem = function( ul, item ) {
                    if(item.value == "no") {
                        return $( "<li></li>" )
                            .data( "item.autocomplete", item )
                            .append( '<span style="margin-left:6px;margin-right:6px;"><a href="'+$('#autocomplete').data('add') + '" style="text-decoration:none;" target="_blank">' + item.label + '</a></span>' ) 
                            .appendTo( ul );
                    } else {
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
                   }
                    
                };
            }
            
           
                       
    }); 