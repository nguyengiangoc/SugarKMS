    var adminObject = {
        
        /* ADD MEMBER PAGE */
        
        checkEmailExists : function(thisIdentity) {
            $(thisIdentity).change(function(e) {
                
                var url = $(this).data('url'); 
                var email = $(this).val();
                if(email == '') {
                    $('#emailMessage').empty();
                } else {
                    $.post(url, {email: email }, function(data) {
                        var result = data.result;
                        if(result) {
                            $('#emailMessage').append('This email is already recorded in another profile.')
                        } else {
                            $('#emailMessage').empty();
                        }
                    }, 'json');
                }
                 
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
        }
        

    };
    
    $(document).ready(function() {
        adminObject.checkEmailExists('.checkEmailExists');
        adminObject.getSchoolList('#high_school');
        adminObject.getSchoolList('#uni');
    });
    
 
    
