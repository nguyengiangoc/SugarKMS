    var adminObject = {
        //showInputField : function(thisIdentity) {
//            "use strict";
//            $(document).on('click', thisIdentity, function(e) {
//                e.preventDefault();
//                $(this).children(':nth-child(1)').css('display','block').focus();
//                $(this).children(':nth-child(2)').hide();
//            });
//        },
//        
//        hideInputField : function(thisIdentity) {
//            "use strict";
//            $(document).on('blur', thisIdentity, function(e) {
//                e.preventDefault();
//                $(thisIdentity).hide();
//                $(this).next().show();
//            });
//            $(document).on('keypress', thisIdentity, function(e) {
//                if(e.which == 13) {
//                    var thisValue = $(thisIdentity).val();
//                    var dataId = $(thisIdentity).attr('id');
//                    if(thisValue == '') {
//                        $(thisIdentity).val($('[data-id="#' + dataId + '"]').text());
//                        
//                    } else {
//                        var teamId = dataId.split('-')[1];
//                        var url = $('#project_type').data('name');
//                        $.post(url, {id: teamId, name: thisValue}, function(data) {
//                            if (data && data.success) {
//                                $.post($('#project_type').data('reload'), function(data){
//                                    $('#project_type').html(data);
//                                }, 'html'); 
//                            } else {
//                                 
//                                $(thisIdentity).val($('[data-id="#' + dataId + '"]').text());
//                            }
//                            
//                        },'json');
//                    }
//                    $(thisIdentity).hide();
//                    $(this).next().show();
//                }
//            }); 
//        },
        
        //showSelect : function(thisIdentity) {
//            "use strict";
//            $(document).on('click', thisIdentity, function(e) {
//                e.preventDefault();
//                $(this).children(':nth-child(1)').css('display','block').focus();
//                $(this).children(':nth-child(2)').hide();
//            });
//        },
//        
//        hideSelect : function(thisIdentity) {
//            "use strict";
//            $(document).on('blur', thisIdentity, function(e) {
//                var thisObj = $(this);
//                e.preventDefault();
//                var spanVal = $(thisObj).next().text();
//                $(thisObj).children().each(function() {
//                    $(this).removeAttr('selected');                    
//                    if($(this).val() == spanVal) {
//                        $(this).attr('selected','selected');
//                    }
//                });
//                $(thisIdentity).hide();
//                $(this).next().show();
//            });
//        },
        
        addProjectTypeBtn : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                console.log($('#wave').val());
                if($('#name').val() != '' 
                && $('#wave').val() != '' 
                && $('#month_start').val() != '' 
                && $('#month_end').val() != '' 
                && (($('#same_start_end').val() == 'Yes' && $('#write_two_years').val() != '' && parseInt($('#month_start').val()) < parseInt($('#month_end').val())) || ($('#same_start_end').val() == 'No'))
                && $('#first_time').val() != '') {
                    
                    console.log('valid');
                    var url = $(this).data('url');
                    var params = $(addTypeForm).serializeArray();
                    $.post(url, params, function(data) {
                        if (data && data.success) {
                            $.post($('#project_types').data('reload'), function(data){
                                $('#project_types').html(data);
                            }, 'html'); 
                        } 
                   }, 'json');
                } 
            });
        },
        
        changeWriteTwoYears : function(thisIdentity) {
            "use strict";
            $(document).on('change', thisIdentity, function(e) {
                if($(this).val() == 'Yes') {
                    $('#writeTwo').removeAttr('disabled');
                } else {
                    $('#writeTwo').attr('disabled', 'disabled');
                    $('#writeTwo').val('');
                }
            });
        },
        
        //clickYesNo : function(thisIdentity) {
//            "use strict";
//            $(document).on('click', thisIdentity, function(e) {
//                e.preventDefault();
//                if(!$(this).hasClass('disabled')) {
//                    var id = $(this).closest('tr').find('td:first').find('input').attr('id').split('-')[1];
//                    var value = $(this).text();
//                    var type = $(this).data('type');
//                    var url = $('#categories').data('type');
//                    $.post(url, {id: id, value: value, type: type}, function(data) {
//                        console.log(data);
//                        if (data && data.success) {
//                            $.post($('#categories').data('reload'), function(data){
//                                $('#categories').html(data);
//                            }, 'html'); 
//                            $.post($('#order').data('reload'), function(data){
//                                $('#order').html(data);
//                            }, 'html');
//                            //location.reload();    
//                        }
//                    },'json');
//                }
//                
//            });
//        },
        
        removeType : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if(!$(this).hasClass('disabled')) {
                    if($('#categoriesTable').find('.prompt').length > 0) {
                        $('#categoriesTable').find('.prompt').parent('tr').remove();
                    }
                    var thisRow = $(this).closest('tr');
                    var id = $(this).data('id');
                    var actionPrompt = '<tr>' + adminObject.addPrompt(id) + '</tr>';
                    $(thisRow).after(actionPrompt);
                }
            });
        },
        
        addPrompt : function(id) {
            var thisTemp = '<td colspan="8" class="br_td prompt">';
            thisTemp += '<div class="fl_r">';
            thisTemp += '<a href="#" class="actualRemove" data-id="'+id+'">Yes</a> ';
            thisTemp += '<a href="#" class="removePrompt">No</a>';
            thisTemp += '</div>';
            thisTemp += '<span class="warn"><span class="bold">CONFIRMATION:</span> Are you sure you wish to remove this project type?!</span> ';
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
                var url = $('#project_types').data('remove');
                var id = $(this).data('id');
                $.post(url, {id: id}, function(data) {
                     if (data && data.success) {
                        $.post($('#project_types').data('reload'), function(data){
                            $('#project_types').html(data);
                        }, 'html'); 
                        
                    } 
                }, 'json');
            });
        }
        

        

    };
    $(function() {
        "use strict";
//        adminObject.showInputField('.showInputField');
//        adminObject.hideInputField('.hideInputField:visible');
//        adminObject.showSelect('.showSelect');
//        adminObject.hideSelect('.hideSelect');
        adminObject.addProjectTypeBtn('#addProjectTypeBtn');
//        adminObject.clickYesNo('.clickYesNo');
        adminObject.changeWriteTwoYears('#sameYear');
        adminObject.removeType('.removeType');
        adminObject.removePrompt('.removePrompt');
        adminObject.actualRemove('.actualRemove')
    });


    