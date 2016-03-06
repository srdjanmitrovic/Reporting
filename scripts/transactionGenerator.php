<?php 

try{
$pdo = new PDO('mysql:host=127.0.0.1;dbname=production', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$transaction = 200911008;
	while($i < 1){
		$query = $pdo->prepare('SELECT * FROM production.transaction_spool WHERE id > :transaction LIMIT 1;');
		$query->execute(array(':transaction' => $transaction));
		$nextTransaction = $query->fetch();
		sleep(1);
		$query = $pdo->prepare('INSERT INTO production.transactions (SELECT * FROM production.transaction_spool WHERE id> :nextTransaction LIMIT 1);');
		$db_data = $query->execute(array(':nextTransaction' => $nextTransaction['id']));
		$transaction = $nextTransaction['id'];
		echo $nextTransaction['id']."\n";
	}
}catch(PDOException $e){
	echo $e->getMessage();
}