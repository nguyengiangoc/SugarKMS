    var common = {
        
        // GET //
        getRecords: function(object, params) {
            var url = $('#commonLinks').data('get');
            var result = '';
            $.post(url, {object: object, params: params} , function(data) {
                console.log(data.result);
                result = data.result;
                            
            }, 'json').done(function(data) {
                console.log(data.result);
                return result;
            });
        },
        
        
        // LOAD //
        
        loadSection: function(plugin, params, thisSection) {
 
            if(typeof(thisSection) == 'undefined') {
                var thisSection = $("div[data-plugin='"+plugin+"']");
            } 
            
            if(typeof(params) == 'undefined') {
                params = '';
            }
                        
            var url = $('#commonLinks').data('reload_section');

            $.post(url, {plugin: plugin, params: params} , function(data) {
                
                $(thisSection).html(data);
            }, 'html');     
                   
        },
        
        
        // RELOAD //
        reloadSection: function(thisSection, params) {
            
            var plugin = $(thisSection).data('plugin');
            
            if(typeof(params) == 'undefined' || params == '') {
                if($(thisSection).find('.sectionParams').length > 0) {
                    var params_string = $(thisSection).find('.sectionParams').data('params');
                    var params_array = params_string.split('&');
                    var params = {};
                    $.each(params_array, function(index, element) {
                        var key = element.split('=')[0];
                        var value = element.split('=')[1];
                        params[key] = value; 
                    });
                } else {
                    var params = {};
                }
            } 
            
            
            //var thisObj = $(this);  
//            
//            
//            
//            if(typeof(thisObj) == 'undefined') {
//                var thisSection = $("div[data-plugin='"+plugin+"']");
//            } else {
//                var thisSection = thisObj;
//            }
//            if(typeof(params) == 'undefined') {
//                params = '';
//            }
            
            var currentRow = $(thisSection).find('.currentRow');
            if(currentRow.length > 0) {
                var currentId = currentRow.data('id');
            }
            
//            var subSection = $(thisSection).find('.reloadSection');
//            if(subSection.length > 0 ) {
//                var subPlugin = $(subSection).data('plugin');
//                var subParams =  $(subSection).find('.sectionParams').data('params');
//            }
            
            var url = $('#commonLinks').data('reload_section');
            //console.log($(thisSection).data('plugin')+'  '+$(thisSection).find('.sectionParams').data('params'));
            $.post(url, {plugin: plugin, params: params} , function(data) {
                
                $(thisSection).html(data);
            }, 'html').done(function() {
                
                if(typeof(currentId) != 'undefined') {
                    $.each(thisSection.find('tr'), function() {
                        if($(this).data('id') == currentId) {
                            $(this).addClass('currentRow');
                        } 
                    });
                }
                
                //if(typeof(subPlugin) != 'undefined' ) {
//                    var subSection = $(thisSection).find("div[data-plugin='"+subPlugin+"']");
//                    var params = {};
//                    console.log('  ');
//                    console.log(subParams);
//                    if(typeof(subParams) != 'undefined' && subParams != '' && subParams != null) {
//                        var params_array = subParams.split('&');
//                        $.each(params_array, function(index, element) {
//                            var key = element.split('=')[0];
//                            var value = element.split('=')[1];
//                            params[key] = value; 
//                        });
//                    }
//                    
//                    $.post(url, {plugin: subPlugin, params: params} , function(data) {
//                        $(subSection).html(data);
//                    });
//                    
//                    
//                }
                
            });     
                   
        },
        
        reloadAll: function(reloadSection) {
            if(typeof(reloadSection) == 'undefined') {
                
                $.each($('.reloadSection'), function() {
                    
                    //console.log($(this));
                    common.reloadSection($(this));                   
                    
                    
                }); 
            } else {
                $.each(reloadSection, function(key, section) {          
                    common.reloadSection($(section));
                    
//                        var plugin = $(section).data('plugin');
//                        var params_string = $(section).find('.sectionParams').data('params');
//                        //console.log(params_string);
//                        var params_array = params_string.split('&');
//                        var params = {};
//                        $.each(params_array, function(index, element) {
//                            var key = element.split('=')[0];
//                            var value = element.split('=')[1];
//                            params[key] = value; 
//                        });
//                        common.reloadSection(plugin,params,section);
                    
                    
                }); 
            }
        },
        
        
        // REMOVE //
        removeRecord: function(id, object, params, reloadSection) {
            var url = $('#commonLinks').data('remove');
            $.post(url, {id: id, object: object, params: params} , function(data) {

            }, 'json').done(function() {
                common.reloadAll(reloadSection);
            });
        },
        
        confirmRemove: function() {
            "use strict";
            $(document).on('click', '.confirmRemove', function(e) {
                e.preventDefault();
                if(!$(this).hasClass('disabled')) {
                    var thisRow = $(this).closest('tr');                  
                    thisRow.siblings('tr.prompt').remove();
                    thisRow.after(common.confirmRemoveTemplate());                    
                }                    
                
            });
        },
        
        confirmRemoveTemplate: function() {
            "use strict";
            var thisTemp = '<tr class="prompt">';
            thisTemp += '<td colspan="10" class="br_btd ">';
            thisTemp += '<span class="warn"><span style="font-weight:bold;color:#900;">CONFIRMATION</span>: Are you sure?!</span> ';
            thisTemp += '<div class="fl_r">';
            thisTemp += '<a href="#" class="actualRemove">Yes</a> ';
            thisTemp += '<a href="#" class="removeConfirmRow">No</a>';
            thisTemp += '</div>';
            thisTemp += '</td>';
            thisTemp += '</tr>';
            return thisTemp;
        },
        
        removeConfirmRow : function (thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $(this).closest('tr').remove(); 
            });
        },            
        
        actualRemove : function () {
            "use strict";
            $(document).on('click', '.actualRemove', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').prev('tr').data('id');
                var object = $(this).closest('table').data('object');
                common.removeRecord(id, object);
                               
            });
        },   
        
        // UPDATE, CHANGE // 
        
        updateRecord: function(id, params, object, reloadSection) {
            var url = $('#commonLinks').data('change_field');
            $.post(url, {id: id, params: params, object: object} , function(data) {
                if(data.success) {
                    common.reloadAll(reloadSection);
                }
            }, 'json').done(function() {
                
                //if(typeof(reloadSection) == 'undefined') {
//                    
//                } else {
//                    $.each(reloadSection, function(key, section) {
//                        

//                    }) 
//                }
                
            });
        },
        
        changeOrder: function() {
            $('.changeOrder').livequery(function() {
                $('.changeOrder').tableDnD({
                    onDrop : function(thisTable, thisRow) {
                        var params = $.tableDnD.serialize();
                        var object = $(thisTable).closest('table').data('object');
                        var url = $('#commonLinks').data('change_order');
                        $.post(url, {params: params, object: object} , function(data) {
                        
                        }, 'json').done(function() {
                            common.reloadAll();
                        });
                    } 
                });
            });
        },
        
        toggleYesNo: function() {
            "use strict";
            $(document).on('click', '.toggleYesNo', function(e) {
                e.preventDefault();
                var url = $('#commonLinks').data('toggle_yes_no');
                var id = $(this).closest('tr').data('id');
                if(typeof(id) == 'undefined') {
                    var id = $(this).closest('table').data('id');
                }
                var field = $(this).data('field');
                var object = $(this).closest('table').data('object');
                var value = $(this).data('value');                            
                $.post(url, {id: id, object: object, field: field, value: value} , function(data) {
                    
                }, 'json').done(function() {
                    common.reloadAll();
                });
                               
            });
            
        },
        
        // ADD //
        
        insertRecord: function(params, object, reloadSection) {
            var url = $('#commonLinks').data('add');
            $.post(url, {params: params, object: object} , function(data) {
                if(data.success) {
                    common.reloadAll(reloadSection);
                }
            }, 'json');

        },
        
        
        // CLICK TO MODIFY FIELD //
        
        
        showInputField : function() {
            "use strict";
            $(document).on('click', '.showInputField', function(e) {
                e.preventDefault();
                if($(this).find('input').length == 0) {
                    var thisSpan = $(this).find('span');
                    if(thisSpan.length == 0) {
                        var text = $(this).text();
                        $(this).html('<span>'+text+'</span>');
                        thisSpan = $(this).find('span');
                    }
                    var defaultValue = thisSpan.text().trim();
                    thisSpan.empty();
                    $(this).append('<input type="text" value="'+defaultValue+'" data-default="'+defaultValue+'" class="clickAppearInputField" />');
                    $(this).find('input').focus();
                } 
                
            });
        },
        
        hideInputField : function() {
            "use strict";
            $(document).on('blur', '.clickAppearInputField', function(e) {
                e.preventDefault();
                var thisSpan = $(this).prev('span');
                thisSpan.text($(this).data('default'));
                $(this).remove();
            });
            $(document).on('keypress', '.clickAppearInputField', function(e) {
                if(e.which == 13) {
                    e.preventDefault();
                    var value = $(this).val();
                    if(value == '') {
                        var thisSpan = $(this).prev('span');
                        thisSpan.text($(this).data('default'));
                        $(this).remove();
                    } else {
                        var field = $(this).closest('td').data('field');
                        if(typeof(field) != 'undefined') {
                            var id = $(this).closest('tr').data('id');
                            var params = {};
                            params[field] = value;
                            var object = $(this).closest('table').data('object');
                            common.updateRecord(id, params, object);
                        }
                        
                    }
                }
            }); 
        },
        
//        linkRow : function() {
//            "use strict";
//            $(document).on('click', '.linkRow', function(e) {
//                e.preventDefault();
//                
//                var id = $(this).data('id');
//                var object = $(this).closest('table').data('object');
//                var url = $('#commonLinks').data('link_row');
//                $.post(url, {id: id, object: object}, function(data) {
//                    if(data.success) {
//                        window.open(data.url);
//                    }
//                }, 'json'); 
//                
//            });
//        }
    };
    
    $(function() {
        "use strict";
        
        common.removeConfirmRow('.removeConfirmRow');
        common.actualRemove();
        common.showInputField();
        common.hideInputField();
        common.toggleYesNo();
        common.confirmRemove();
        //common.linkRow();
        $(document).ready(function() {
            $(".tabs").livequery(function() {
                $(".tabs").tabs();
            });  
        });
    });        

    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
				   