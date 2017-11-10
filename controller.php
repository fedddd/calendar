<?php
/*
* Контроллер
*/
class Controller {
 

 
    

    /*
     * param месяц, год
     * return Текущий месяц
     */
    public function thisMonth(){
     $month = date("n");
     $textMonth = array("","Январь", "Февраль", "Март", "Апрель", "Май", "Июнь",
           "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
       return $textMonth[$month];
     }
    
    /*
     * param месяц, год
     * return массив key  номер блока таблицы value - дата 
     */

    public function block_day($month, $year) {

        //порядковый номер дня недели для первого числа заданного месяца
        $w =date("w",mktime( 0,0,0,$month,0,$year)) - 1;
        if($w<0){
            $w=6;
        }
        
        
        //день первой ячейки
        $fistday = date("U",mktime( 0,0,0,$month,0,$year)) - ($w * 86400);
        
        
        
        for ($block = 0; $block<42; $block++){
        //день для текущей ячейки
        $curentday = $fistday + 86400 * $block;

        //выводим в формате дней
        $block_day[] = date("j", $curentday);
        }
        
        
        return $block_day;
    }

     

}

 





