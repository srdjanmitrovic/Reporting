<?php

try{
	$pdo = new PDO('mysql:host=127.0.0.1;dbname=production', 'root', '');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = $pdo->prepare('SELECT * FROM production.transactions LIMIT 1;');
	$query->execute();
	$transaction = $query->fetch();
	$query = $pdo->prepare('SELECT * FROM production.transactions WHERE id > :transaction LIMIT 5;');
	$query->execute(array(':transaction' => $transaction));
	$nextTransaction = $query->fetchAll();
	$lastTransactionFromPreviousSelect = end($nextTransaction)['id'];
}catch(PDOException $e){
	echo $e->getMessage();
}