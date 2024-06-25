<?php
    require_once '../scripts/connect.php';

    $servicesid = $_GET['id'];
    $dateadd = $_GET['dataadd'];
    $status = 1;

    $check_query = "SELECT * FROM application WHERE dateservices = '$dateadd'";
    $check_result = mysqli_query($link, $check_query);

    if(empty($_GET_['clientname'] || $_GET['telephon']))
        echo 'Не все поля заполнены!';
    else{
        $clientname = $_GET['clientname'];
        $telephon = $_GET['telephon'];
        if (!preg_match('/\+7(\d\d\d)\d\d\d\d\d\d\d$/', $telephon) || !preg_match("/^[a-zA-Zа-яёА-ЯЁ]+$/u",$clientname)) {
            echo 'Данные введены не корректно!';
        } else {
            if (mysqli_num_rows($check_result) > 0) {
                echo "Ошибка: Запись с такой датой уже существует.";
            }  else {
                    // Проверка на прошедшую дату
                    $inputDate = new DateTime($date); // Предполагаем, что $date содержит введенную дату 
                    $today = new DateTime(); // Текущая дата
                
                    if ($inputDate < $today) {
                    echo "Ошибка: Введена дата из прошлого.";
                    } else {
                    // Создание клиента
                    $createclient = "INSERT INTO client (nameclient, telephon) VALUES ('$clientname', '$telephon')";
            
                    if (mysqli_query($link, $createclient)) {
                        $clientid = mysqli_insert_id($link);
            
                        // Создание заявки
                        $createappli = "INSERT INTO application (idclient, idservices, dateservices, status) VALUES ('$clientid', '$servicesid', '$dateadd', '$status')";
            
                        if (mysqli_query($link, $createappli)) {
                            echo "Заявка успешно создана!";
                        } else {
                            echo "Ошибка при создании заявки: " . mysqli_error($link);
                        }
                    } else {
                        echo "Ошибка при создании клиента: " . mysqli_error($link);
                    }
                } 
            }
        }
    }
?>