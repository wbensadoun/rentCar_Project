/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import 'bootstrap';
const $=require('jquery');
require('@fortawesome/fontawesome-free');

$(function (){
  $('#load-more').on("click",function (){
          const advertRoute = $("#advert-route").val();
          let offset = $(".public-advert").length + 1;

          let url =  advertRoute.replace(/(__OFFSET__)/g,offset);

      $.ajax({
          url: url,
          type: "get",
          dataType : "json",
          error: function(request, error){
              console.log(error);
              },
          success: function(data){
              if(data === ""){
                  $("#load-more").attr('disabled','disabled');
              }else{
                  $('#advert-list').append(data);
              }


          }
      })

  })

})
