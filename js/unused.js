activeToggleAdd : function(thisIdentity) {
            $(document).on('change', thisIdentity, function(e) {
                var thisObj = $(this);
                var thisRow = thisObj.closest('tr');
                var thisActive = $(thisRow).find('td:eq(4)');
                var thisValue = $(thisObj).val();
                
                if(thisValue < ((new Date).getFullYear())) {
                    var result = '<select style="width:50px;border:1px solid #aaa;" disabled="disabled">';
                    result += '<option>No</option></select>';
                    result += '<input type="hidden" name="active[]" value="no" />';
                    
                } else {
                    var result = '<select name="active[]" style="width:50px;border:1px solid #aaa;">';
                    result += '<option value="yes">Yes</option>';
                    result += '<option value-"no">No</option>';
                    result += '</select>';
                }

                $(thisActive).html(result);
            });
        },
        
        addInvInAdd : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
               e.preventDefault();
                var table = $('#involvments');
                var lastRow = $('.involvements tr:last');
                var lastRowId = lastRow.data('id');
                var newRowId = lastRowId + 1;
                var newRow = lastRow.clone();
                newRow.attr('id', 'row' + newRowId);
                newRow.attr('data-id', newRowId);
                newRow.find('td:eq(4) a').attr('data-id', newRowId);
                newRow.insertAfter(lastRow);
            });
        },
                      
        removeInvInAdd : function (thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var rowCount = $('.involvements tr').length;
                if(rowCount > 2) {
                    var thisObj = $(this);
                    var thisRow = $(thisObj).closest('tr');
                    thisRow.remove();
                }
                 
            });
        },
        
        
        
        
        
        
        
        
        
        
        
        
        
        
                addInvInEdit : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
               e.preventDefault();
               var thisURL = $(this).data('url');
                $.getJSON(thisURL, function(data) {
                    if(!data['error']) {
                        var lastRow = $('#involvements tr:last');
                        var newRowId = data.id;
                        var newRow = lastRow.clone();
                        newRow.find('input').attr('value', newRowId);
                        newRow.attr('id', 'row-' + newRowId);
                        newRow.attr('data-id', newRowId);
                        newRow.find('option:selected').removeAttr('selected');
                        newRow.find('td:eq(0) select option:eq(0)').attr('selected', 'selected');
                        newRow.find('td:eq(1) select option:eq(0)').attr('selected', 'selected');
                        newRow.find('td:eq(2) select option:eq(0)').attr('selected', 'selected');
                        newRow.find('td:eq(3) select option:eq(0)').attr('selected', 'selected');
                        newRow.insertAfter(lastRow);
                    }
                });
                
            });
        },
        
        confirmRemoveInv : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var rowCount = $('#involvements tr').length;
                if(rowCount > 2) {
                    var thisObj = $(this);
                    var thisParent = thisObj.closest('tr');
                    var thisId = thisParent.attr('id').split('-')[1];
                    var thisURL = thisObj.data('url')+ thisId;
                    if ($('#clickRemove-' + thisId).length === 0) {
                        thisParent.before(adminObject.removeInvTemplate(thisId, thisURL));
                    } 
                }
            });
        },
        
        removeInvTemplate : function(id, url) {
            "use strict";
            var thisTemp = '<tr id="clickRemove-' + id + '">';
            thisTemp += '<td colspan="6" class="br_td prompt">';
            thisTemp += '<div class="fl_r">';
            thisTemp += '<a href="#" data-url="' + url;
            thisTemp += '" class="removeInvInEdit mrr5">Yes</a>';
            thisTemp += '<a href="#" class="clickRemoveRowConfirm">No</a>';
            thisTemp += '</div>';
            thisTemp += '<span class="warn"><span style="font-weight:bold;color:#900;">CONFIRMATION</span>: Are you sure you wish to remove this record of involvement?</span>';
            thisTemp += '</td>';
            thisTemp += '</tr>';
            return thisTemp;
        },
        
        removeInvInEdit : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisObj = $(this);
                var thisId = thisObj.closest('tr').attr('id').split('-')[1];
                var thisURL = thisObj.data('url');
                $.getJSON(thisURL, function(data) {
                    if (data && !data.error) {
                        $('#row-' + thisId).remove();
                        thisObj.closest('tr').remove(); //remove confirmation message
                        
                    } 
                }); 
            });
        },
        
        activeToggleEdit : function(thisIdentity) {
            $(document).on('change', thisIdentity, function(e) {
                var thisObj = $(this);
                var thisId = $(thisObj).attr('id').split('_')[2];
                var thisActive = $('#active_' + thisId);
                var thisValue = $(thisObj).val();
                if(thisValue < ((new Date).getFullYear())) {
                    var inactive = '<select style="width:50px;border:1px solid #aaa;" disabled="disabled">';
                    inactive += '<option>No</option></select>';
                    inactive += '<input type="hidden" name="active[]" value="no" />';
                    $(thisActive).html(inactive);
                } else {
                    var active = '<select name="active[]" style="width:50px;border:1px solid #aaa;">';
                    active += '<option value="yes">Yes</option>';
                    active += '<option value-"no">No</option>';
                    active += '</select>';
                    $(thisActive).html(active);
                }
            });
        },
        
        adminObject.activeToggleEdit('.activeToggleEdit');
        
        adminObject.addInvInEdit('.addInvInEdit');
        adminObject.confirmRemoveInv('.confirmRemoveInv')
        adminObject.removeInvInEdit('.removeInvInEdit');
        
        adminObject.activeToggleAdd('.activeToggleAdd');
        
        adminObject.addInvInAdd('.addInvInAdd');
        adminObject.removeInvInAdd('.removeInvInAdd');