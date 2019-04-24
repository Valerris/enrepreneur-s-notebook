<?php

    class Data{
        private $_mysqli;
        
        public function __construct($mysqli = NULL){
            $this->_mysqli = $mysqli;
        }
        
        public function createData(){
            $server_results['status'] = 'success';
            
		if(!isset($_POST['table'])){
			$server_results['status'] = 'error';
			$server_results['message'] = 'Error: No table-verb specified!';
		} 
		else{
			if($_POST['table'] === 'zakazi'){
				if(isset($_POST['surname'])){
					$surname = $_POST['surname'];

					if(empty($surname)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No surname';
					}
				}
				if(isset($_POST['name'])){
					$name = $_POST['name'];

					if(empty($name)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No name';
					}
				}
				if(isset($_POST['patronimyc'])){
					$patronimyc = $_POST['patronimyc'];

					if(empty($patronimyc)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No patronimyc';
					}
				}
				if(isset($_POST['tel'])){
					$tel = $_POST['tel'];

					if(empty($tel)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No tel';
					}
				}
				if(isset($_POST['datebeg'])){
					$datebeg = $_POST['datebeg'];

					if(empty($datebeg)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No datebeg';
					}
				}
				if(isset($_POST['datend'])){
					$datend = $_POST['datend'];

					if(empty($datend)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No datend';
					}
				}
				if(isset($_POST['price'])){
					$price = $_POST['price'];

					if(empty($price)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No price';
					}
				}
				if(isset($_POST['done'])){
					$done = "Выполнен";
				}
				else{
					$done = "Не выполнен";
				}
				$details = $_POST['details'];

				$sql = "INSERT INTO orders (order_id, surname, name, patronimyc, tel, datebeg, datend, done, price, details)
					VALUES(NULL, '$surname', '$name', '$patronimyc', '$tel', '$datebeg', '$datend', '$done', '$price', '$details')";
			}
			
            if($_POST['table'] === 'zakupki'){
				if(isset($_POST['item'])){
					$item = $_POST['item'];
					
					if(empty($item)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No item';
					}
				}
				if(isset($_POST['vendor'])){
					$vendor = $_POST['vendor'];
					
					if(empty($vendor)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No vendor';
					}
				}
				if(isset($_POST['cost'])){
					$cost = $_POST['cost'];
					
					if(empty($cost)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No cost';
					}
				}
				if(isset($_POST['date'])){
					$date = $_POST['date'];
					
					if(empty($date)){
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: No date';
					}
				}
				
				$sql = "INSERT INTO purchases (purchase_id, order_id, Item, vendor, cost, date) VALUES(NULL, (SELECT MAX(order_id) FROM orders), '$item', '$vendor', '$cost', '$date')";
			}
			
            if($server_results['status'] === 'success'){                
                
                $result = mysqli_query($this->_mysqli, $sql);
                
                if($this->_mysqli->errno === 0) {
                    $server_results['message'] = 'Добавлено!';
                } else {
                    $server_results['status'] = 'error';
                    $server_results['message'] = 'MySQLi error #: ' .
                    $this->_mysqli->errno . ': ' . $this->_mysqli->error;
                }
            }
            $JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
		}
            return $JSON_data;
        }
        
        public function readAllData(){
            
            $sql = "SELECT * FROM orders AS o INNER JOIN purchases AS p ON p.order_id = o.order_id";        
            $result = mysqli_query($this->_mysqli, $sql);
            
			$id = 1;
			
            $text = '<table border="1"><caption>Таблица Заказов</caption><tr><th>id</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Телефон</th><th>Дата начала</th><th>Дата окончания</th><th>Статус</th><th>Цена</th><th>Описание</th><th>Что купил</th><th>Продавец</th><th>Стоимость</th><th>Дата</th><th>Доход</th></tr>';
			
			$income = 0;
			$incomeAll = 0;
            while($row = mysqli_fetch_assoc($result)) {
				
				$income = $row['price'] - $row['cost'];
                $text .= '<tr><td>' . $id++ . '</td><td>' . $row['surname'] . '</td><td>' . $row['name'] . '</td><td>' . $row['patronimyc'] . '</td><td>' . $row['tel'] . '</td><td>' . $row['datebeg'] . '</td><td>' . $row['datend'] . '</td><td>' . $row['done'] . '</td><td>' . $row['price'] . '</td><td>' . $row['details'] . '</td><td>' . $row['Item'] . '</td><td>' . $row['vendor'] . '</td><td>' . $row['cost'] . '</td><td>' . $row['date'] . '</td><td>' . $income . ' y. e. ' . '</td></tr>';
                
				$incomeAll += $income;				
            }
            
            $text .= '<tr><td>Итого: </td><td>' . $incomeAll . ' y. e. ' . '</td></tr></table><br><a href="../index.html">Вернуться</a>' . $row['item'];
            unset($id);
			unset($income);
			unset($incomeAll);
            echo $text;
        }
    }

?>