    var adminObject = {
        
        /* LIST MEMBER PAGE */
        
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
        
        getSchoolList : function(thisIdentity) {
            $(thisIdentity).autocomplete({
                source: function(request, response) {
                    var schoolUrl = $(thisIdentity).data('url');
                    var highSchool = $(thisIdentity).data('high_school');
				    $.post(schoolUrl , { term: request.term, high_school: highSchool }, 
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
			         //prevent autocomplete from updating the textbox
					event.preventDefault();
				}
            }).data("ui-autocomplete")._renderItem = function( ul, item ) {
                return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( '<span style="margin-left:6px;margin-right:6px;">'+ item.name + '</span>' ) 
                    .appendTo( ul );
            };
        },
        
        addEXCOEndYear : function(thisIdentity) {
            $(document).on('change', thisIdentity, function(e) {
                var projectId = $(this).val();
                if(projectId == '5') {
                    //$('#projectYear').width('100px');
                    $('#projectYear option').each(function() {
                        var currentText = $(this).text();
                        if(currentText != '') {
                            var nextYear = parseInt(currentText) + 1;
                            var newText = currentText + ' - ' + nextYear;
                        }
                        $(this).text(newText);
                    });
                } else if (projectId != '5') {
                    $('#projectYear option').each(function() {
                            var currentText = $(this).text();
                            if(currentText != '') {
                                var newText = currentText.substr(0,4);
                            }
                            $(this).text(newText);
                        });
                    //if($('#projectYear').width() > 58) {
//                        //$('#projectYear').width('58px');
//                        
//                    }
                    
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
                //console.log(criteria);
            });
        },    
        
        actualRemove : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisObj = $(this);
                var id = thisObj.closest('tr').prev('tr').data('id');
                common.removeRecord(id, 'member');
                $('#row-' + id).remove();
                thisObj.closest('tr').prev('tr').remove();
                thisObj.closest('tr').remove(); //remove confirmation message
            });
        },
        
        getAllEmail : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#dialog').css('display') == 'none' || $('.ui-dialog').css('display') == 'none') {
                    $.each($('.email:not(:has(span))'), function() {
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
        
        
        /* LIST MEMBER */
        
        adminObject.clearSearch('#clearSearch');
        
        adminObject.getSchoolList('#high_school');
        adminObject.getSchoolList('#uni');
        
        adminObject.addEXCOEndYear('#projectName');
        adminObject.loadResult('#loadResult');
        
        adminObject.actualRemove('.actualRemove');
        
        adminObject.getAllEmail('#getAllEmail');
        

    });

