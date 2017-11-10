<?php
/*
 * Получить все события за указанный месяц и год
 * 
 * @param integer $month-месяц integer $year-год
 * @return array массив событий
 */

class model extends db {

    
    
// выбирает все поля за указанный месяц  и год
    public function getEvents($month, $year) {



        $mysqli = new mysqli($this->host, $this->user, $this->base_parol, $this->base);
		$mysqli->set_charset('utf8');

        $sql = "SELECT *
              FROM events
              WHERE month=$month AND year=$year";

        $select = $mysqli->query($sql);

        while ($row = mysqli_fetch_assoc($select)) {
            $rs[] = $row;
        }


        return $rs;
    }
    
    
    

    //вставляет новое событие
    public function insertEvents($name, $event, $day, $month, $year, $file) {

        $mysqli = new mysqli($this->host, $this->user, $this->base_parol, $this->base);
		$mysqli->set_charset('utf8');

        $sql = "INSERT INTO
               events (name, event,day,month,year,file)
               VALUES('$name','$event','$day','$month','$year','$file')";
            

        $mysqli->query($sql);

        
    }
    
    //вставляет новое событие
    public function updateEvents($name, $event, $day, $month, $year) {

        $mysqli = new mysqli($this->host, $this->user, $this->base_parol, $this->base);
		$mysqli->set_charset('utf8');

        $sql = "UPDATE events
                SET  name = '$name',event ='$event'
                WHERE day = '$day' and month = '$month' and  year = '$year'";
            

        $mysqli->query($sql);

        
    }
    
    
    public function delEvents($day, $month, $year){
            $mysqli = new mysqli($this->host, $this->user, $this->base_parol, $this->base);
			$mysqli->set_charset('utf8');
    $sql =   "DELETE
              FROM events
              WHERE day=$day AND month=$month AND year=$year";
    
     $mysqli->query($sql);
    
    }

}
