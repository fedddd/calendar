<?php

//подключаем модель
include_once './config/db.php';
include_once 'model.php';
include_once 'controller.php';


/*
 * Контроллер для работы с Ajax запросами.
 */

class AjaxController extends controller {
    
    
  //запускает методы 
  public function runEvent (){
      
     
      if( isset($_POST['month']) && !empty($_POST['month'])&& isset($_POST['year']) && !empty($_POST['year']) && $_POST['method']=='select' ){ 
          $month = stripslashes(trim(htmlspecialchars($_POST['month'],ENT_QUOTES)));
          $year = stripslashes(trim(htmlspecialchars($_POST['year'],ENT_QUOTES)));
          
          $this->get_all_events($month, $year);
             
      }
     if( isset($_POST['name']) && !empty($_POST['name']) && 
         isset($_POST['event']) && !empty($_POST['event']) && 
         isset($_POST['block_id']) && !empty($_POST['block_id']) &&
         isset($_POST['month']) && !empty($_POST['month']) && 
         isset($_POST['year']) && !empty($_POST['year']) &&
         ($_POST['method']=='insert' || $_POST['method']=='update') ){ 
         
        
          $this->add_day();   
      } 
       
     if( isset($_POST['block_id']) && !empty($_POST['block_id']) &&
         isset($_POST['month']) && !empty($_POST['month']) && 
         isset($_POST['year']) && !empty($_POST['year']) &&
         $_POST['method']=='del' ){ 
         
        
          $this->del_day();   
      } 
      
      
  }
  
  
  //получает и передает массив событий res за указанный месяц и год
  
 public function get_all_events($month,$year) {
        
        
       
        //получаем данные из модели
        $model = new model;
        $data = $model->getEvents($month, $year);
        
       if(is_array($data)){
        foreach ($data as $value) {
            $day = $value['day'];
               
            $res[$day]['name'] =  $value['name'];
            $res[$day]['event'] = $value['event'];
            $res[$day]['file'] =  $value['file'];
        }
       }
        //день недели для первого числа месяца
         $w = date("w",mktime( 0,0,0,$month,0,$year))-1;
         if($w<0){
           $w=6;  
         }
         
         
        $res['w'] = $w;
        
        // массив день для блоков
        $block_day = $this->block_day($month, $year);
        $res[block_day] = $block_day;
        
        echo json_encode($res);
       
       
       
    } 
  
  /*
 * Сохраняет файл
 */
   public function add_file() {
   $uploadfile= './uploads/'. $_FILES['day_file']['name'];
      if (move_uploaded_file($_FILES['day_file']['tmp_name'], $uploadfile)){
         $answer = $_FILES['day_file']['name'];
      }else{
         $answer = ""; 
      }  
   return $answer;

   }
  
    
/*
 * обрабатывает Ajax на добавление события
 */

    public function add_day() {

        // Фильтруем полученные данные
	$data['name'] = stripslashes(trim(htmlspecialchars($_POST['name'],ENT_QUOTES)));
        $data['event'] = stripslashes(trim(htmlspecialchars($_POST['event'],ENT_QUOTES)));
        $block = stripslashes(trim(htmlspecialchars($_POST['block_id'],ENT_QUOTES))); 
        $month = stripslashes(trim(htmlspecialchars($_POST['month'],ENT_QUOTES)));
        $year =  stripslashes(trim(htmlspecialchars($_POST['year'],ENT_QUOTES)));       
        
        $data['file'] = $_FILES['day_file']['tmp_name'];
        
        
      //добавляем файл если он прешел  
      if( !empty($_FILES)){
      $data['file'] =  $this->add_file();  
      }     
       
 
     echo json_encode($data);
        $name = $data['name'];
        $event = $data['event'];
        $file = $_FILES['day_file']['name'];
        $day = $this->block_day($month,$year);
        
        $model = new model;
        if ($_POST['method']=='insert'){
        
           $model->insertEvents($name,$event, $day[$block], $month, $year,$file);
        }else{
           $model->updateEvents($name,$event, $day[$block], $month, $year, $file);  
        }
        
     
    }
    
    public function del_day() {

        // Фильтруем полученные данные
	
        $block = stripslashes(trim(htmlspecialchars($_POST['block_id'],ENT_QUOTES))); 
        $month = stripslashes(trim(htmlspecialchars($_POST['month'],ENT_QUOTES)));
        $year =  stripslashes(trim(htmlspecialchars($_POST['year'],ENT_QUOTES)));       
                
     echo json_encode($data);
       
        
        $day = $this->block_day($month,$year);
        
        $model = new model;
        $model->delEvents($day[$block], $month, $year);
     
    }
}
    
    
    



$ajax = new AjaxController;
$ajax->runEvent();


    