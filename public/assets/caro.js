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