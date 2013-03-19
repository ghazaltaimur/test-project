$(document).ready(function(){

    $('.delAction').click(function(){
        var verify = confirm('Are you sure you want to delete?');
        if(verify) {
            //window.location.href = $(this).attr('href');
            window.location.href = $(this).attr('data-link');
        }
        return false;    
    });
    
});

$(document).ready(function() {  
    $(".toggle").click(function() {  
        $(".filters").toggle();     
    });  
});