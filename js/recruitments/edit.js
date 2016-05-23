    var adminObject = {
               
        
        
        changeRecruitmentSettings : function () {
            $(document).on('click', '.changeRecruitmentSettings', function(e) {
                e.preventDefault();
                var params = $('#changeSettingsForm').serializeArray();
                var thisSection = $(this).closest('.reloadSection');
                var id = $('.sectionParams').data('params').split('=')[1];
                //console.log(thisSection);
                common.updateRecord(id, params, 'recruitment', thisSection);
                
                
                
                
            });
        },    
        
        changeRecruitmentJD : function () {
            $(document).on('click', '.changeRecruitmentJD', function(e) {
                e.preventDefault();
                var params = $('#changeJDForm').serializeArray();
                var thisSection = [$(this).closest('.reloadSection'), $('.settingsSection')];
                var id = $('.sectionParams').data('params').split('=')[1];
                //console.log(thisSection);
                common.updateRecord(id, params, 'recruitment', thisSection);
                
                
                
                
            });
        },
        
        toggleChoice : function(thisIdentity) {
            $(document).on('change', thisIdentity, function(e) {
                var value = $(this).val();
                var thisForm = $(this).closest('form');
                if(value == 'dropdown' || value == 'radio' || value == 'checkbox') {
                    $(thisForm).find('.withChoice').show();
                    $(thisForm).find('.withLimit').hide();
                } else {
                    $(thisForm).find('.withChoice').hide();
                    $(thisForm).find('.withLimit').show();
                }
            });
            
            
        },

        
        addChoice : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('.choiceList tr').length == 2) {
                    $('.choiceList .removeChoice, .choiceList .confirmRemove').removeClass('disabled');
                }
                var newRow = '<tr><td class="borderRight">+</td><td><input type="text" style="width:100%;" /></td><td style="width:5px;"><span href="#" class="removeChoice clickable"><strong>X</strong></span></td></tr>';
                $(this).closest('table').find('.choiceList').append(newRow);
            });
            
        },

        
        removeChoice : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('.choiceList tr').length > 2) {
                    $(this).closest('tr').remove();                    
                } 
                
                if($('.choiceList tr').length == 2) {
                    $('.choiceList .removeChoice, .choiceList .confirmRemove').addClass('disabled');
                } 
                
            });           
            
        },
        
        removeChoiceEdit : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $(this).closest('tr').find('input').addClass('removeChoice');
                $(this).closest('tr').hide();
                
                
            });           
            
        },
        
        addQuestion : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var label = $('textarea[name="label"]').val();
                var error = [];
                if(label == '') {
                    error.push('label');
                } 
                var type = $('select[name="type"]').val();
                if(type == 'dropdown' || type == 'radio' || type == 'checkbox') {
                    var choices = [];
                    $('.choiceList input').each(function() {
                        if($(this).val() == '') {
                            error.push('choice');   
                        } else {
                            choices.push($(this).val());
                        }
                    });
                    
                }
                
                if(error.length == 0) {
                    var params = $('.addQuestionForm').serializeArray();
                    if(type == 'dropdown' || type == 'radio' || type == 'checkbox') {
                        params.push({name: 'choices', value: choices});
                    }
                    
                    var id = $('#pageParams').data('id');
                    
                    params.push({name : 'recruitment_id', value: id });
                    
                    var questionList = [$('.questionListSection'), $('.settingsSection')]; 
                    common.insertRecord(params, 'question', questionList);
                    //console.log(params);
                }
                
            });
        },
        
        editQuestion : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                
                $('.currentRow').removeClass('currentRow');
                $(this).closest('tr').addClass('currentRow');
                
                var id = $(this).closest('tr').data('id');
                
                var section = $('.editQuestionSection');
                
                common.reloadSection(section, {question_id: id});
                
            });
        },
        
        closeQuestion : function(thisIdentity) {
            
            
            $(document).on('click', thisIdentity, function(e) {
                                
                e.preventDefault();
                
                
                
                var id = $('input[name="recruitment_id"]').val();

                var section = $('.editQuestionSection');
                
                common.reloadSection(section, {recruitment_id: id});
                
                $('.questionListSection .currentRow').removeClass('currentRow');
                
            });
            
            
            
        },
        
        saveQuestionChanges : function(thisIdentity) {
            
            $(document).on('click', thisIdentity, function(e) {
                                
                e.preventDefault();               
                                
                var thisForm = $('.editQuestionForm');
                
                var type = $(thisForm).find('.questionType').val();
                
                var error = [];
                
                
                // CHECK QUESTION LABEL //
                var label = $('textarea[name="label"]').val();
                
                if(label == '') {
                    error.push('label');
                }
                
                if(type == 'dropdown' || type == 'radio' || type == 'checkbox') {
                    
                    var newChoices = [];
                    var existingChoices = [];
                    
                    $(thisForm).find('.choiceList input').each(function() {
                        if($(this).val() == '') {
                            error.push('choice');   
                        } else {
                            if($(this).hasClass('existingChoice')) {
                                var value = $(this).val();
                                var id = $(this).closest('tr').data('id');
                                existingChoices.push({id: id, label: value});
                            } else {
                                newChoices.push($(this).val());
                            }
                            
                        }
                    });
                    
                }
                
                if(error.length == 0) {
                    var params = $('.editQuestionForm').serializeArray();
                    if(type == 'dropdown' || type == 'radio' || type == 'checkbox') {
                        params.push({name: 'new_choices', value: newChoices}, {name: 'existing_choices', value: existingChoices});
                    }
                    
                    var id = $('.currentRow').data('id');
                    
                    var section = [$('.questionListSection'), $('.editQuestionSection') ];
                    
                    
                    console.log(params);
                    
                    common.updateRecord(id, params, 'question', section);
                }
                
                
                
                
            });
            
        }

    };
    $(document).ready(function() {
        "use strict";
        
        adminObject.changeRecruitmentSettings();
        adminObject.changeRecruitmentJD();
        
        adminObject.toggleChoice('.questionType');
        adminObject.addChoice('.addChoice');
        adminObject.removeChoice('.removeChoice');
        adminObject.addQuestion('.addQuestion');
        
        adminObject.editQuestion('.editQuestion');
        adminObject.closeQuestion('.closeQuestion');
        
        adminObject.saveQuestionChanges('.editQuestionBtn');
        
        //adminObject.toggleChoiceEdit(); 
        
        
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