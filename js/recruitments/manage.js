    var adminObject = {
               
        
        
        getPosition : function (thisIdentity) {
            $(document).on('change', thisIdentity, function(e) {
                var thisRow = $(this).closest('tr');

                var type = $(thisRow).find('.selectProject').find('option:selected').data('type');
                
                $(thisRow).find('.selectPosition').empty();
                $(thisRow).find('.selectPosition').append('<option class="positionOptionBlank"></option>');
                $(thisRow).find('.selectTeam').empty();
                //$(thisRow).find('.selectTeam').append('<option class="teamOptionBlank"></option>');
                $(thisRow).find('.selectTeam').attr('disabled', 'disabled');
                
                if(typeof(type) == 'undefined' || type == '') {
                    $(thisRow).find('.selectPosition').attr('disabled', 'disabled');
                } else {
                    $.post($('#commonLinks').data('get_position'), {type: type}, function(data) {
                        if(data && data.success) {
                            $.each(data.positions, function(k, v) {
                                $(thisRow).find('.selectPosition').append('<option value="'+v['id']+'" >'+v['name']+'</option>'); 
                            });
                            $(thisRow).find('.selectPosition').removeAttr('disabled');
                        }
                    },'json');  
                }
                
                
                
                
            });
        }, 
        
        getTeam : function (thisIdentity) {
            
            $(document).on('change', thisIdentity, function(e) {
                var thisRow = $(this).closest('tr');
                
                var type = $(thisRow).find('.selectProject').find('option:selected').data('type');
                var position = $(this).val();                
                $(thisRow).find('.selectTeam').empty();
                
                if(typeof(position) == 'undefined' || position == '') {
                    $(thisRow).find('.selectTeam').attr('disabled', 'disabled');
                } else {
                    $.post($('#commonLinks').data('get_team'), {project_type_id: type, position_id: position}, function(data) {
                    
                        $.each(data, function(k, v) {
                            $(thisRow).find('.selectTeam').append('<option value="'+v['id']+'" >'+v['name']+'</option>'); 
                        });
                        $(thisRow).find('.selectTeam').removeAttr('disabled');
                    
                    },'json');
                }

                
                
                
                
            });
        },
        
        addPosition : function(thisIdentity) {
            $(thisIdentity).on('click', function(e) {
                e.preventDefault(); 
                
                if($('.selectProject').val() !== '' && $('.selectPosition').val() !== '' && $('.selectTeam').val() !== '' && $('.position_deadline').val() !== '') {
                    //console.log('valid');
                    var params = $('.addPositionForm').serializeArray();
                    var list = [$('.recruitmentList'), $('.currentApplications')];
                    common.insertRecord(params, 'recruitment', list);
                }
                
            
            });
        }


        
        
        
        

    };
    $(document).ready(function() {
        "use strict";
        

        adminObject.getPosition('.selectProject');
        adminObject.getTeam('.selectPosition');
        adminObject.addPosition('.addPositionToRecruitment');
        
        
    });
    
    
    $(function() {
        
        $('.ui-datepicker-trigger').on('click', function(e) {
            $('.ui-state-hover').removeClass('ui-state-hover');    
        });
            
        $(".datepicker" ).livequery(function() {

            $('.datepicker').datepicker({
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
                    var thisObject = $(this);
                    
                    $(this).data('datepicker').inline = true;   
                    if($(thisObject).hasClass('common_deadline')) {
                        var val = $('.common_deadline').val();

                        if(val !== '' || typeof(val) !== 'undefined' || val.length !== 0) {
                            $('.position_deadline').val(val);
                        } 
                    }
                                              
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

        

        
    });