
$.fn.carouselMan = function(options){
  return this.each(function(){


    var obj = $(this);
    
    obj.o = options;
    
    obj.curPage = 0;
    obj.total = obj.find(obj.o.itemDom).length;
    obj.size = obj.o.itemWidth;
    obj.parent = obj.parent();

    obj.addClass('carouselman');
    obj.speed = 500;
    if (obj.o.speed!='' && obj.o.speed!=undefined){
      obj.speed = obj.o.speed;
    }

    obj.easing = 'swing';
    if (obj.o.easing!='' && obj.o.easing!=undefined){
      obj.easing = obj.o.easing;
    }
    obj.classPrefix = 'carouselman';
    if (obj.o.classPrefix!=undefined){
      obj.classPrefix = obj.o.classPrefix;
    } 
    obj.pageStart = 0;
    if (obj.o.pageStart!=undefined && obj.o.pageStart!=''){
      obj.pageStart = obj.o.pageStart;
    }
    obj.itemsPerPage = 4;
    if (obj.o.itemsPerPage!=undefined && obj.o.itemsPerPage!=''){
      obj.itemsPerPage = obj.o.itemsPerPage;
    }

    obj.wrap("<div class='"+obj.classPrefix+"-wrapper'/>");
    obj.before("<div class='"+obj.classPrefix+"-shadow "+obj.classPrefix+"-leftshadow'/>");
    obj.before("<div class='"+obj.classPrefix+"-shadow "+obj.classPrefix+"-rightshadow'/>");
    obj.after("<div class='"+obj.classPrefix+"-pager'/>");
    obj.after("<div class='"+obj.classPrefix+"-prev' id='prev'/>");
    obj.after("<div class='"+obj.classPrefix+"-next' id='next'/>");
    


    if (obj.size==undefined){
      obj.size = $(this).find(obj.o.itemDom).outerWidth(true);
    }
    $(this).css('width',(obj.size*obj.total)+"px");

    //generate pages and their offsets
    obj.numPages = Math.floor(obj.total/obj.itemsPerPage);
    obj.pageOffsets = new Array();
    obj.lastpage = '';


    //set width of parent
    if (obj.numPages!=(obj.total/obj.itemsPerPage)){
      obj.lastpage = (obj.size*obj.total)-$(obj).parent().width();
    }

    for (i=0; i<obj.numPages; i++){
      if (i!=0){
        offsetit = obj.o.pageOffset;
      }else{
        offsetit = 0;
      }

      if (i==(obj.numPages-1) && obj.lastpage==''){
        obj.pageOffsets[i] = '-'+((obj.size*obj.total)-$(obj).parent().width())+'px';
      }else{
        obj.pageOffsets[i] = '-'+(((i*obj.itemsPerPage)*obj.size)-offsetit)+'px';
      }
    }

    if (obj.lastpage!=undefined && obj.lastpage!=''){
      obj.pageOffsets[obj.pageOffsets.length] = '-'+obj.lastpage+'px';
    }

    if (obj.pageStart==0){
      $(obj).parent().find("."+obj.classPrefix+"-leftshadow").hide();
      $(obj).parent().find("."+obj.classPrefix+"-prev").hide();
      
    }else if (obj.pageStart==obj.numPages){
      $(obj).parent().find("."+obj.classPrefix+"-rightshadow").hide();
      $(obj).parent().find("."+obj.classPrefix+"-next").hide();
    }

    //if total items less than show on page, hide all the shadows/arrows
    if (obj.total <= obj.itemsPerPage){
      $(obj).parent().find('.'+obj.classPrefix+'-leftshadow').hide();
      $(obj).parent().find('.'+obj.classPrefix+'-prev').hide();
      $(obj).parent().find("."+obj.classPrefix+"-rightshadow").hide();
      $(obj).parent().find("."+obj.classPrefix+"-next").hide();
      $(obj).parent().find('.'+obj.classPrefix+'-pager').hide();
    }

    //create pagination
    for(var key in obj.pageOffsets){
       $(obj).parent().find("."+obj.classPrefix+"-pager").append("<a href=''>&bull;</a>");
    }
    obj.css('left',obj.pageOffsets[obj.pageStart]);
    obj.curPage = obj.pageStart;

    $(obj).parent().find('.'+obj.classPrefix+'-pager a:eq('+obj.pageStart+')').addClass(obj.classPrefix+'-active');
    
    $(this).data('cMan',obj);

    $(this).parent().find("."+obj.classPrefix+"-next").on('click',function(){
      obj = $(this).parent().find('.carouselman').data('cMan');


      obj.carouselMan.next(obj);
      return false;
    });

    $(this).parent().find("."+obj.classPrefix+"-prev").on('click',function(){
      obj = $(this).parent().find('.carouselman').data('cMan');
      $.fn.carouselMan.prev(obj);
      return false;
    });

   $(this).parent().find("."+obj.classPrefix+"-pager a").on('click',function(){
      obj = $(this).parent().parent().find('.carouselman').data('cMan');
      $.fn.carouselMan.goto($(this).index(),obj);
      return false;
    })
   
    //goto page
    $.fn.carouselMan.goto = function(id,obj){

      if (obj.pageOffsets[id]!=undefined){
        $(obj).stop().animate({
          left: obj.pageOffsets[id]
        },obj.speed,obj.easing);
        obj.curPage = id;
      }

      $.fn.carouselMan.setActive(id,obj);

      if (id==obj.pageOffsets.length-1){
        $(obj).parent().find("."+obj.classPrefix+"-next").fadeOut();
        $(obj).parent().find("."+obj.classPrefix+"-rightshadow").fadeOut();
      }else{
        $(obj).parent().find("."+obj.classPrefix+"-next").fadeIn();
        $(obj).parent().find("."+obj.classPrefix+"-rightshadow").fadeIn();
      }

      if (id==0){
        $(obj).parent().find("."+obj.classPrefix+"-prev").fadeOut();
        $(obj).parent().find("."+obj.classPrefix+"-leftshadow").fadeOut();
      }else{
        $(obj).parent().find("."+obj.classPrefix+"-prev").fadeIn();
        $(obj).parent().find("."+obj.classPrefix+"-leftshadow").fadeIn();
      }
    }

    $.fn.carouselMan.setActive = function(id,obj){
      $(obj).parent().find("."+obj.classPrefix+"-pager a."+obj.classPrefix+"-active").removeClass(obj.classPrefix+'-active');
      $(obj).parent().find("."+obj.classPrefix+"-pager a:eq("+id+")").addClass(obj.classPrefix+'-active');
    }

    //next
    $.fn.carouselMan.next = function(obj){
      this.goto(obj.curPage+1,obj);
    };
   
    //prev
    $.fn.carouselMan.prev = function(obj){
      $.fn.carouselMan.goto(obj.curPage-1,obj);
    }
    //attach the object to the dom
  });
}