    var adminObject = {
        
        /* ADD MEMBER PAGE */
        
        checkNameExists : function(thisIdentity) {
            $(thisIdentity).change(function(e) {
                
                var url = $(this).data('url'); 
                var name = $(this).val();
                $.post(url, {name: name }, function(data) {
                    var result = data.result;
                    if(result > 0) {
                        if(result == 1) {
                            $('#nameMessage').append('This member may be added aleady (' + result + ' profile with this name found).')
                        } else {
                            $('#nameMessage').append('This member may be added aleady (' + result + ' profiles with this name found).')
                        }
                    } else {
                        $('#nameMessage').empty();
                    }
                }, 'json'); 
            });
        },
        
        /* EDIT MEMBER PAGE*/
        

        
        /* LIST MEMBER PAGE */
        
        clearFields : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $('input:text').val('');
                $('option').attr('selected', false);
                $('#projectYear').width('58px');
                $('#projectYear option').each(function() {
                    var currentText = $(this).text();
                    if(currentText != '') {
                        var newText = currentText.substr(0,4);
                    }
                    $(this).text(newText);
                });
            });
        },
        
        clearSearch : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $('input:text').val('');
                $('option').attr('selected', false);
                $('#projectYear').width('58px');
                $('#projectYear option').each(function() {
                    var currentText = $(this).text();
                    if(currentText != '') {
                        var newText = currentText.substr(0,4);
                    }
                    $(this).text(newText);
                });
                $('#searchResult').remove();
            });
        },
        
        addEXCOEndYear : function(thisIdentity) {
            $(document).on('change', thisIdentity, function(e) {
                var projectId = $(this).val();
                if(projectId == '5') {
                    $('#projectYear').width('100px');
                    $('#projectYear option').each(function() {
                        var currentText = $(this).text();
                        if(currentText != '') {
                            var nextYear = parseInt(currentText) + 1;
                            var newText = currentText + ' - ' + nextYear;
                        }
                        $(this).text(newText);
                    });
                } else if (projectId != '5') {
                    if($('#projectYear').width() > 58) {
                        $('#projectYear').width('58px');
                        $('#projectYear option').each(function() {
                            var currentText = $(this).text();
                            if(currentText != '') {
                                var newText = currentText.substr(0,4);
                            }
                            $(this).text(newText);
                        });
                    }
                    
                }
            });  
        },

        loadResult : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault(); 
                var criteria = $('#search').serializeArray();
                var thisUrl = $('#search').data("url");
                $.post(thisUrl, criteria, function(data) {
                    var dataReturn = data;
                    $('#result').html(data);
                }, 'html');
                console.log(criteria);
            });
        },    
        
        clickAddRowConfirm : function (thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var rowCount = $('#member_list tr').length;
                if(rowCount > 1) {
                    var thisObj = $(this);
                    var thisParent = thisObj.closest('tr');
                    var thisId = thisParent.attr('id').split('-')[1];
                    var thisSpan = thisObj.data('span');
                    var thisURL = thisObj.data('url');
                    var thisName = thisParent.find('td:eq(0)').text();
                    if ($('#clickRemove-' + thisId).length === 0) {
                        thisParent.before(adminObject.clickRemoveRowTemplate(thisId, thisSpan, thisURL, thisName));
                    } 
                }
            });
        },
        
        clickRemoveRowTemplate : function(id, span, url, name) {
            "use strict";
            var thisTemp = '<tr id="clickRemove-' + id + '">';
            if(span > 1) {
                thisTemp += '<td colspan="' + span + '" class="br_td prompt">';
            }
            thisTemp += '<div class="fl_r">';
            thisTemp += '<a href="#" data-url="' + url;
            thisTemp += '" class="clickRemoveRow mrr5 link_btn">Yes</a>';
            thisTemp += '<a href="#" class="clickRemoveRowConfirm link_btn">No</a>';
            thisTemp += '</div>';
            thisTemp += '<span class="warn"><span style="font-weight:bold;color:#900;">CONFIRMATION</span>: Are you sure you wish to remove ' + name + '?!</span> ';
            //thisTemp += 'This action cannot be reversed!</span>';
            thisTemp += '</td>';
            thisTemp += '</tr>';
            return thisTemp;
        },
        
        clickRemoveRowConfirm : function (thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $(this).closest('tr').remove(); 
            });
        },
        
        clickRemoveRow : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisObj = $(this);
                var thisId = thisObj.closest('tr').attr('id').split('-')[1];
                var thisURL = thisObj.data('url');
                $.getJSON(thisURL, function(data) {
                    if (data && !data.error) {
                        if (!adminObject.isEmpty(data.replace)) {
                            $.each(data.replace, function(k, v) {
                                $(k).html(v); 
                            });
                        } else {
                            $('#row-' + thisId).remove();
                            thisObj.closest('tr').remove(); //remove confirmation message
                        }
                    } 
                }); 
            });
        },
        
        getAllEmail : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#dialog').css('display') == 'none' || $('.ui-dialog').css('display') == 'none') {
                    $.each($('.email'), function() {
                        $('#dialog').append($(this).text()+', ');
                    });
                    
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
        }
        

    };
    $(function() {
        "use strict";
        
        /* ADD MEMBER */
            if($('#high_school').length) {
                $("#high_school").autocomplete({
                    source: function(request, response) {
                        var schoolUrl = $('#high_school').data('url');
    				    $.post(schoolUrl , { high_school: request.term }, 
                        function(data) {
                            response($.map(data,function(item) {
                                return {
                                    value: item.name,
                                    label: item.name,
                                    name: item.name
                                }
                            }));
                        },'json');
                    },
                    focus: function(event, ui) {
    					// prevent autocomplete from updating the textbox
    					event.preventDefault();
    				}
                }).data("ui-autocomplete")._renderItem = function( ul, item ) {
                    return $( "<li></li>" )
                        .data( "item.autocomplete", item )
                        .append( '<span style="margin-left:6px;margin-right:6px;">'+ item.name + '</span>' ) 
                        .appendTo( ul );
                };
            }
            
            if($('#uni').length) {
                $("#uni").autocomplete({
                    source: function(request, response) {
                        var schoolUrl = $('#uni').data('url');
    				    $.post(schoolUrl , { uni: request.term }, 
                        function(data) {
                            response($.map(data,function(item) {
                                return {
                                    value: item.name,
                                    label: item.name,
                                    name: item.name
                                }
                            }));
                        },'json');
                    },
                    focus: function(event, ui) {
    					// prevent autocomplete from updating the textbox
    					event.preventDefault();
    				}
                }).data("ui-autocomplete")._renderItem = function( ul, item ) {
                    return $( "<li></li>" )
                        .data( "item.autocomplete", item )
                        .append( '<span style="margin-left:6px;margin-right:6px;">'+ item.name + '</span>' ) 
                        .appendTo( ul );
                };
            }
        adminObject.checkNameExists('#checkNameExists');
        
        /* EDIT MEMBER */
        
        /* LIST MEMBER */
        adminObject.clearFields('#clearFields');
        adminObject.clearSearch('#clearSearch');
        adminObject.addEXCOEndYear('#projectName');
        adminObject.loadResult('#loadResult');
        
        adminObject.clickRemoveRowConfirm('.clickRemoveRowConfirm');
        adminObject.clickAddRowConfirm('.clickAddRowConfirm');
        adminObject.clickRemoveRow('.clickRemoveRow');
        adminObject.getAllEmail('#getAllEmail');
        

    });
    
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
    
