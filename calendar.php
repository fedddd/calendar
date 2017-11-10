<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Задание Федоренко иван</title>

        <link href="css/style.css" rel="stylesheet">
        <script src="js/jquery.js"></script>

        <script src="js/script.js"></script>

    </head>


    <body>

        <div class="header"></div>
        <div class="content">
            <div class="calendar">



                <p>
                    <button onclick="prevMonth();return false"> &ltrif;</button>
                    <span id="month"><?= $run->thisMonth() ?></span>
                    <span id="year"><?= date('Y')?></span>
                    <button onclick="nextMonth();return false"> &rtrif;</button>
                </p>       

                <table> 
                   

                    
                    <?for($tr=0, $block=0;$tr<6;$tr++):?>
                    <tr>  
                        <?for($td=0;$td<7;$td++):?>
                                <td id="block_<?= $block ?>">
                                    <p class="day_text"> </p>
                                    <p class="event_text"> </p>
                                    <p class="name_text"></p>
                                    <a href="#"></a>

                                </td>
                             <? $block++ ?>
                         <? endfor; ?>  
                    </tr>
                    <? endfor; ?>  

                </table>    
                
         
                


                <div class="popup">
                    <form id="form" method="POST" onsubmit="return false;" >
                        <h3></h3>
                        <p></p>

                        <input id="name" type="text" name="name" placeholder="Имена участников">
                        <textarea id="event" name="event" placeholder="Описание"></textarea>
                        <input id="file" type="file" name="day_file">
                        <button onclick="addEvent();">Готово</button>
                        <button onclick="delEvent();">Удалить</button>

                    </form>
                </div>





            </div>




        </div>



    </body>
</html>