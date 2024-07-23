
$(document).ready(function(){
    $('.sidenav').sidenav();
  });
  $(document).ready(function(){
    $('select').formSelect();
  });

  $(document).ready(function(){
    $('.collapsible').collapsible();
  });
  
  $(document).ready(function(){
    $('.fixed-action-btn').floatingActionButton();
  });
  $(document).ready(function(){
    $('.tooltipped').tooltip();
  });
  $(document).ready(function(){
    $('.materialboxed').materialbox();
  });
  $(document).ready(function(){
    $('.datepicker').datepicker({format: "yyyy-mm-dd ", autoClose: true,});
 });
 $('.dropdown-trigger').dropdown({
  coverTrigger: false,
  constrainWidth: false,
});
// $('.dropdown-trigger').dropdown();
 
 $(document).ready(function(){
  $('.modal').modal();

});

// document.addEventListener('keydown', event => {
//   if(event.keyCode == 112){
//     event.preventDefault();
//     window.open('/dashboard', "_self");
//     return false;
//   }
//   if(event.keyCode == 113){
//     event.preventDefault();
//     $('#search').focus();
//     $('#search').val('');
//     return false;
//   }
//   if(event.keyCode == 117){
//     event.preventDefault();
//     window.open('/addpayment', "_self");
//     return false;
//   }
//   if(event.keyCode == 118){
//     event.preventDefault();
//     window.open('/detailedreport', "_self");
//     return false;
//   }
//   if(event.keyCode == 119){
//     event.preventDefault();
//     window.open('/statement', "_self");
//     return false;
//   }
//   if(event.keyCode == 120){
//     event.preventDefault();
//     window.open('/createorder', "_self");
//     return false;
//   }
// });


//   autoplay()   
// function autoplay() {
//     $('.carousel').carousel('next');
//     setTimeout(autoplay, 4500);
// }

carousel()
  function carousel(){
    var caroItem = $('.mp-caro-item');
    var next = 0;
    for (let i = 0; i < caroItem.length; i++) {
      if(!caroItem[i].classList.contains('hide')){
        var next = i + 1;
        if(next > caroItem.length-1){
          var next = 0;
        }
      } 
    }
    $('.mp-caro-item').addClass('hide');
    caroItem[next].classList.remove('hide');
    setTimeout(carousel, 5000);
  }
  function next(){
    var caroItem = $('.mp-caro-item');
    var next = 0;
    for (let i = 0; i < caroItem.length; i++) {
      if(!caroItem[i].classList.contains('hide')){
        var next = i + 1;
        if(next > caroItem.length-1){
          var next = 0;
        }
      }
   
    }
    $('.mp-caro-item').addClass('hide');
    caroItem[next].classList.remove('hide');
  }
  function prev(i){
    var caroItem = $('.mp-caro-item');
    var next = 0;
    for (let i = 0; i < caroItem.length; i++) {
      if(!caroItem[i].classList.contains('hide')){
        var next = i - 1;
        if(next == -1){
        var next = caroItem.length-1;
        }
      }
    }
    $('.mp-caro-item').addClass('hide');
    caroItem[next].classList.remove('hide');
  }

  