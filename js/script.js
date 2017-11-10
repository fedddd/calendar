$(document).ready(function() {
getMonthEvent();
    
 /*
 *функция для изменения стелей в выбранной ячейке td при клике
 */   
$('tr').on('click','td:not(.grey)', function() {
//изменяем класс выбранного td
	$('.selected.event').attr('class', 'event');
	$('.selected').attr('class', '');
	
	var lastclass = $(this).attr('class');	
	if (lastclass == "event"){
		$(this).attr('class', 'selected event');
	}else{
		$(this).attr('class', 'selected');

	}
        
        //переносим данные из ячейки в popup
        var name = $(".selected p.event_text" ).text();
        var day = $(".selected p.day_text" ).text();
        var month = $('#month').text();
         month = getMonth(month);
         month = transformMonth(month);
         
        $('.popup h3').html(name);
        $('.popup p').html(day + " " + month);
        
        //скрываем возможность загрузки нового файла если он уже загружен
       if( $('.selected a').text() == '' ){
         $('#file').attr('style','display:block') 
       }else{
         $('#file').attr('style','display:none')  
       }
       
        
	
    //задаем координаты отступов для popup
	//вытаскиваем из id порядковый номер
       $(".popup").css("display","block"); 
        
	var current_id = $(this).attr('id');	
	current_id = current_id.substr(6);
	current_id = parseInt(current_id);
	
	
	//исходя из высоты td
	var top = $('td').css('width');
	top = top.substr(0,top.length-2 );
	top = parseInt(top);
        top=top+10;
	top =top*Math.floor(current_id/7);
	
	//исходя из длинны td
	var left = $('td').css('height');
	left = left.substr(0,left.length-2 );
	left = parseInt(left);
        left=left+10;
	left =left*(current_id%7)+left+ 20;
	
	
	
	var left;
	$('.popup').css('top', top + 'px');
	$('.popup').css('left',left +'px');
	

});
});







/* 
 * Функция получает события за выбранный месяц
 * 
 */
function getMonthEvent(){
    console.log("js - getMonthEvent()");
    var textmonth = $('#month').text();
    var month = getMonth(textmonth);
    var year = $('#year').text();
    

    
    
    
    $.ajax({
       type: 'POST',
       async: true,
       url: "ajax.php",
       data: 'month=' + month + '&year=' + year +'&method=select' ,
       dataType: 'json',
       success: function(data){
          
          var id;
          var w = data['w'];//порядковый номер дня недели для первого дня месяца
         
         
         //очищаем события в таблице
          $('p.name_text').html(''); 
          $('p.event_text').html('');
          $('td a').html('');
          $('td').attr('class', '');
          
          for(id=0; id<42; id++){
              
    
           //вставляем дни
           $('#block_' + id + ' p.day_text').html(data['block_day'][id]); 
          
          //присваиваем класс .grey для дней не выбранного месяца     
          if(((data['block_day'][id]<20) && (id>28)) || (data['block_day'][id]>id))  {
              $('#block_' + id).attr('class', 'grey');
          }
          
          
          
          //вставляем события если они есть в массиве    
           if(data[id]) {          
           $('#block_' + w + ' p.name_text').html(data[id]['name']); 
           $('#block_' + w + ' p.event_text').html(data[id]['event']);
           $('#block_' + w + ' a').html(data[id]['file']);
           $('#block_' + w + ' a').attr('href','uploads/' + data[id]['file']);
           $('#block_' + w).attr('class', 'event');
           }
           w++;
        }
       }   
    });
}










/* 
 * Функция Отправки события для добавления
 * 
 */
function addEvent(){
    

    console.log("js - addEvent()");
    
    var textmonth = $('#month').text();
    var month = getMonth(textmonth);
    var year = $('#year').text();
    
    var name = $("#name").val();
    var event = $("#event").val();
    
    var block_id = $('.selected').attr('id');
        block_id = block_id.substr(6);
	block_id = parseInt(block_id);
    
    var method = 'insert'; 
        
    if ($(".selected p.event_text" ).text() !== ''){
        var method = 'update'; 
    }
    
    
    
    
    
   //формируем объект FormData 
   

    file = new FormData(document.forms.form);
    file.append('method', method);
    file.append('month', month);
    file.append('year', year);
    file.append('block_id', block_id);
    

    
    
    $.ajax({
       type: 'POST',
       async: true,
       url: "ajax.php",
       data: file,
       processData: false,
       contentType: false,
       dataType: 'json',
       success: function(data){
           console.log(data['name']);
           console.log(data['file']);
           
           $(".popup").css("display","none");
           $('.selected p.event_text').html(data['event']);
           $('.selected p.name_text').html(data['name']);
           $('.selected a').html(data['file']);
           $('.selected a').attr('href','/uploads/' + data['file']);
           $('.selected').attr('class', 'event');
           $('#file').val("");
           $('#event').val("");
           $('#name').val("");
       }   
    });

    }


/* 
 * Функция Отправки события для удаления
 * 
 */
function delEvent(){
    console.log("js - delEvent()");
    
    var textmonth = $('#month').text();
    var month = getMonth(textmonth);
    var year = $('#year').text();
    
  
    
    var block_id = $('.selected').attr('id');
        block_id = block_id.substr(6);
	block_id = parseInt(block_id);
    
    
    
    
    $.ajax({
       type: 'POST',
       async: true,
       url: "ajax.php",
       data: 'block_id=' + block_id + '&month=' + month + '&year=' + year +'&method=del' ,
       dataType: 'json',
       success: function(data){
           $(".popup").css("display","none");
           $('.selected p.event_text').html("");
           $('.selected p.name_text').html("");
           $('.selected a').html("");
           $('.selected').attr('class', '.selected');
           
       }   
    });
}










//преобразует месяц в его порядковый номер
function getMonth(textmonth){
  var month; 
  var Mass = ["","Январь", "Февраль", "Март", "Апрель", "Май", "Июнь",
           "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
  var i;  
  for(i=0;i<13;i++){
     if (Mass[i] == textmonth){
         month = i;
     }
  }   

  return  month;
}

//преобразует порядковый номер месяца в название
function getTextMonth(month){
  var textmonth;
  month = parseInt(month);
  var Mass = ["","Январь", "Февраль", "Март", "Апрель", "Май", "Июнь",
           "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
  textmonth = Mass[month];
  
  return  textmonth;
}


//Спряжение месяца
function transformMonth(month){
    
  var Mass = ["","Января", "Февраля", "Марта", "Апреля", "Мая", "Июня",
           "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"];
   var transform_month = Mass[month];
  
  return  transform_month;
}


//вызывает следующий месяц

function nextMonth(){
 console.log("js - nextMonth()");
    $(".popup").css("display","none");
    var textmonth = $('#month').text();
    var month = getMonth(textmonth);
    var year = $('#year').text();
    year = parseInt(year);
   
    month++
    if(month>12){
       month=1; 
    year++;
    }
    
   month = getTextMonth(month);

    $('#month').html(month);
    $('#year').html(year);
    getMonthEvent();
    
    
    
}


//вызывает предыдущий месяц

function prevMonth(){
 console.log("js - prevMonth())");
    $(".popup").css("display","none");
    var textmonth = $('#month').text();
    var month = getMonth(textmonth);
    var year = $('#year').text();
    year = parseInt(year);
     
    
    month--;
    if(month<1){
       month=12; 
    year--;
    }
    month = getTextMonth(month);
    $('#month').html(month);
    $('#year').html(year);
    getMonthEvent();
   
}