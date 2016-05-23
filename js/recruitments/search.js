    var adminObject = {
        
        /* LIST MEMBER PAGE */
        
        clearSearch : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $('option').attr('selected', false);
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
        }
        
        
        

    };
    $(function() {
        "use strict";
        
        
        /* LIST MEMBER */
        
        adminObject.clearSearch('#clearSearch');
        
        adminObject.addEXCOEndYear('#projectName');
        adminObject.loadResult('#loadResult');
        
        

    });

