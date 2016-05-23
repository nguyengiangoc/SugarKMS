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
//                console.log($('#name').val());
//                console.log($('#wave').val());
//                console.log($('#month_start').val());
//                console.log($('#month_end').val());
//                console.log($('#same_start_end').val());
//                console.log($('#first_time').val());   
                
                if( $('#name').val() != '' 
                    && $('#wave').val() != '' 
                    && $('#month_start').val() != '' 
                    && $('#month_end').val() != '' 
                    && (($('#same_start_end').val() == 1 && parseInt($('#month_start').val()) < parseInt($('#month_end').val())) || ($('#same_start_end').val() == 0 && $('#write_two_years').val() != '' ))
                    && $('#first_time').val() != '') {
                    
//                    console.log('valid');
                    var params = $('#addTypeForm').serializeArray();
                    common.insertRecord(params, 'project_type');                      
                } 
            });
        },
        
        changeWriteTwoYears : function(thisIdentity) {
            "use strict";
            $(document).on('change', thisIdentity, function(e) {
                if($(this).val() == 1) {
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
        
        
        actualRemove : function (thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').prev('tr').data('id');
                common.removeRecord(id, 'project_type');
                       
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

        adminObject.actualRemove('.actualRemove')
    });


    