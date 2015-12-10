    var adminObject = {
        showInputField : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $(this).children(':nth-child(1)').css('display','block').focus();
                $(this).children(':nth-child(2)').hide();
            });
        },
        
        hideInputField : function(thisIdentity) {
            "use strict";
            $(document).on('blur', thisIdentity, function(e) {
                e.preventDefault();
                $(thisIdentity).hide();
                $(this).next().show();
            });
            $(document).on('keypress', thisIdentity, function(e) {
                if(e.which == 13) {
                    var thisValue = $(thisIdentity).val();
                    var dataId = $(thisIdentity).attr('id');
                    if(thisValue == '') {
                        $(thisIdentity).val($('[data-id="#' + dataId + '"]').text());
                        
                    } else {
                        var positionId = dataId.split('-')[1];
                        var url = $('#categories').data('name');
                        $.post(url, {id: positionId, name: thisValue}, function(data) {
                            if (data && data.success) {
                                $.post($('#categories').data('reload'), function(data){
                                    $('#categories').html(data);
                                }, 'html'); 
                                $.post($('#position_team').data('reload'), function(data){
                                    $('#position_team').html(data);
                                }, 'html');
                            } else {
                                 
                                $(thisIdentity).val($('[data-id="#' + dataId + '"]').text());
                            }
                            
                        },'json');
                    }
                    $(thisIdentity).hide();
                    $(this).next().show();
                }
            }); 
        },
        
        addPositionBtn : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#positionNameField').val() != '' && $('#EXCOSelect').val() != '' & $('#projectSelect').val() != '') {
                   var url = $(this).data('url');
                   $.post(url, {name: $('#positionNameField').val(), exco: $('#EXCOSelect').val(), project: $('#projectSelect').val()}, function(data) {
                        if (data && data.success) {
                            //console.log('data');
                            $.post($('#categories').data('reload'), function(data){
                                $('#categories').html(data);
                            }, 'html'); 
                            $.post($('#position_team').data('reload'), function(data){
                                $('#position_team').html(data);
                            }, 'html');

                        } 
                   }, 'json');
                } 
            });
        },
        
        changeCategory : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if(!$(this).hasClass('disabled')) {
                    var id = $(this).closest('tr').find('td:first').find('input').attr('id').split('-')[1];
                    var value = $(this).text();
                    var type = $(this).data('type');
                    var url = $('#categories').data('type');
                    $.post(url, {id: id, value: value, type: type}, function(data) {
                        console.log(data);
                        if (data && data.success) {
                            $.post($('#categories').data('reload'), function(data){
                                $('#categories').html(data);
                            }, 'html'); 
                            $.post($('#position_team').data('reload'), function(data){
                                $('#position_team').html(data);
                            }, 'html');
  
                        }                        
                    },'json');
                }
                
            });
        },
        
        removePosition : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if(!$(this).hasClass('disabled')) {
                    if($('#categoriesTable').find('.prompt').length > 0) {
                        $('#categoriesTable').find('.prompt').parent('tr').remove();
                    }
                    var thisRow = $(this).closest('tr');
                    var id = $(thisRow).find('td:first').find('input').attr('id').split('-')[1];
                    var actionPrompt = '<tr>' + adminObject.addPrompt(id) + '</tr>';
                    $(thisRow).after(actionPrompt);
                }
            });
        },
        
        addPrompt : function(id) {
            var thisTemp = '<td colspan="4" class="br_td prompt">';
            thisTemp += '<div class="fl_r">';
            thisTemp += '<a href="#" class="actualRemove" data-id="'+id+'">Yes</a> ';
            thisTemp += '<a href="#" class="removePrompt">No</a>';
            thisTemp += '</div>';
            thisTemp += '<span class="warn"> Are you sure you wish to remove this position?!</span> ';
            thisTemp += '</td>';
            return thisTemp;
        },
        
        removePrompt : function (thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $(this).closest('tr').remove(); 
            });
        },
        
        actualRemove : function (thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var url = $('#categories').data('remove');
                var id = $(this).data('id');
                $.post(url, {id: id}, function(data) {
                     if (data && data.success) {
                        $.post($('#categories').data('reload'), function(data){
                            $('#categories').html(data);
                        }, 'html'); 
                        $.post($('#position_team').data('reload'), function(data){
                            $('#position_team').html(data);
                        }, 'html');

                        
                    } 
                }, 'json');
            });
        },
        
        changePositionInTeam : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if(!$(this).hasClass('disabled')) {
                    var idArray = $(this).closest('td').attr('id').split('-');
                    var positionId = idArray[0];
                    var teamId = idArray[1];
                    var value = $(this).text();
                    var url = $('#position_team').data('team');
                    $.post(url, {position_id: positionId, team_id: teamId, value: value}, function(data) {
                        console.log(data);
                        if (data && data.success) { 
                            $.post($('#position_team').data('reload'), function(data){
                                $('#position_team').html(data);
                            }, 'html');
  
                        }                        
                    },'json');
                }
                
            });
        }
        
        
        //changeOrder : function (thisIdentity) {
//            $(thisIdentity).livequery(function() {
//                $(thisIdentity).tableDnD({
//                    onDrop : function(thisTable, thisRow) {
//                        var thisArray = $.tableDnD.serialize();
//                        console.log(thisArray);
//                        var thisURL = $('#order').data('order');
//                        $.post(thisURL, {data: thisArray, type: $(thisIdentity).data('type')}, function(data) {
//                            if(data && !data.error) {
//                                $.post($('#categories').data('reload'), function(data){
//                                    $('#categories').html(data);
//                                }, 'html'); 
//                                $.post($('#order').data('reload'), function(data){
//                                    $('#order').html(data);
//                                }, 'html');
//
//                            }
//                            
//                        }, 'json');
//                    } 
//                });
//            });
//            
//            
//        }
        

    };
    $(function() {
        "use strict";
        adminObject.showInputField('.showInputField');
        adminObject.hideInputField('.hideInputField:visible');
        adminObject.addPositionBtn('#addPositionBtn');
        adminObject.changeCategory('.changeCategory');
        adminObject.removePosition('.removePosition');
        adminObject.removePrompt('.removePrompt');
        adminObject.actualRemove('.actualRemove');
        adminObject.changePositionInTeam('.changePositionInTeam');
        //adminObject.changeOrder('#orderEXCO');
//        adminObject.changeOrder('#orderProject');
    });
    
    