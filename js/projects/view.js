    var adminObject = {
        
        
        getAllEmail : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#dialog').css('display') == 'none' || $('.ui-dialog').css('display') == 'none') {
                    var thisObj = $(thisIdentity);
                    if($('#volunteers').length > 0) {
                        $('#dialog').append('<strong>Organizers</strong><br />');
                        $.each($('#organizers .email:not(:has(span))'), function() {
                            if(!$(this).closest('tr').hasClass('withdrawn')) {
                                $('#dialog').append($(this).text()+', ');
                            }
                            
                        });
                        $('#dialog').append('<br /><br />');
                        $('#dialog').append('<strong>Volunteers</strong><br />');
                        $.each($('#volunteers .email:not(:has(span))'), function() {
                            if(!$(this).closest('tr').hasClass('withdrawn')) {
                                $('#dialog').append($(this).text()+', ');
                            }
                            
                        });
                    } else {
                        $.each($('.email:not(:has(span))'), function() {
                            if(!$(this).closest('tr').hasClass('withdrawn')) {
                                $('#dialog').append($(this).text()+', ');
                            }
                            
                        });
                    }
                    
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
        },
        
        getTeamEmail : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                if($('#dialog').css('display') == 'none' || $('.ui-dialog').css('display') == 'none') {
                    var thisObj = $(this);
                    $.each($(thisObj).closest('tr').nextUntil('.groupRow'), function() {
                        if(!$(this).hasClass('withdrawn')) {
                            var text = $(this).find('td.email:not(:has(span))').text();
                            $('#dialog').append(text+', ');
                        }
                        
                    });                
                    $('#dialog').dialog({
                            autoOpen: false, 
                            title: 'Team emails', 
                            draggble: true, 
                            resizable: false,
                            close: function() {
                                $('#dialog').html('');
                            }
                        }).dialog('open');
                }
                
            });
        },
        
        scrollOnKeyPress: function(thisIdentity) {
            $(thisIdentity).on('keypress', function(e) {
                if(e.keyCode == 37) {
                    $('.dragDiv').scrollLeft($('.dragDiv').scrollLeft() - 50);
                }
                if(e.keyCode == 39) {
                    $('.dragDiv').scrollLeft($('.dragDiv').scrollLeft() + 50);
                }
                
            })
        },
        
        scrollOnDrag: function(thisIdentity) {
            var clicked = false, clickY, clickX;
            $(thisIdentity).on({
                'mousedown': function(e) {
                    clicked = true;
                    clickY = e.pageY;
                    clickX = e.pageX;
                },
                'mousemove': function(e) {
                    clicked && $(this).scrollLeft($(this).scrollLeft() + (clickX - e.pageX));
                    //updateScrollPos(e);
                },
                'mouseup': function() {
                    clicked = false;
                    $('html').css('cursor', 'auto');
                }
            });
        }
        
        

    };
    $(document).ready(function() {
        "use strict";
        
        /* VIEW PROJECT*/
        adminObject.getAllEmail('#getAllEmail');
        adminObject.getTeamEmail('.getTeamEmail');
        adminObject.scrollOnKeyPress(document);
        adminObject.scrollOnDrag('.dragDiv');
        
        $("#tabs").livequery(function() {
            $("#tabs").tabs();
        });
        
        if($('.volunteers_view').length > 0) {
            $.post($('.volunteers_view').data('url'), function(data) {
                $('.volunteers_view').html(data);
            }, 'html');
        }
    });

                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
				   