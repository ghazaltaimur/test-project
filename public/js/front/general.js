$(document).ready(function(){
  $(".featureTabsHead li").click(function(e){
    e.preventDefault();

    $('.featureTabsHead li').each(function(){
      $(this).removeClass('active');
    });
    $('.featureTabsContent').addClass('hidden');

    $id = $(this).attr('id');
    $(this).addClass('active');
    $('.'+$id).removeClass('hidden');
  });
});