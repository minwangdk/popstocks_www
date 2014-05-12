<?php
session_start();
if(isset($_SESSION['loginID']))
   echo "Your USERID is " . $_SESSION['loginID'] . "</br> 
   You are logged in as ". $_SESSION['loginName'] . "</br>";
?>
<?php

/*** mysql server info ***/
$db_hostname = 'localhost';
$db_username = 'omnipwn';
$db_password = 'godaddyIllus22';
$db_name = 'popcorn';

//get data from form page
$buy = $_GET['buy'];
$sell = $_GET['sell'];
$id = $_SESSION['loginID'];
$username = $_SESSION['loginName'];
$popid = $buy + $sell;
$buyorsell = $buy - $sell;


try {
		
    $dbh = new PDO("mysql:host=$db_hostname;dbname=$db_name;charset=utf8", $db_username, $db_password);
    /*** echo a message saying we have connected ***/
    echo 'Connected to database<br />';
    //errormode on
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//Select Pop stock price to use for buying and selling order
	$getprice = $dbh->prepare("
	SELECT stockprice, popname 
	FROM pops 
	WHERE id = '$popid' ");
	$result = $getprice->execute();
	$priceresult = $getprice->fetchAll();
	$price = $priceresult[0]['stockprice'];
	$popname = $priceresult[0]['popname']; 
	if ($result == 1){
		echo 'Price of ' .$popname. ' was ' .$price. '</br>';
	}
	//Check eligibility of purchase/sale
	if ($buyorsell == 1){
		$checkpopcorn = $dbh->prepare("
		SELECT popcorn
		FROM users
		WHERE id = '$id' ");
		$result = $checkpopcorn->execute();
		$popcornresult = $checkpopcorn->fetchAll();
		$popcorn = $popcornresult[0]['popcorn'];
		if ($result == 1){
			echo 'Your popcorn: ' .$popcorn. '</br>';
		}
	}
	if ($buyorsell == -1){
		$checkstocks = $dbh->prepare("
		SELECT quantity
		FROM ownership
		WHERE user = '$id' && pop = '$popid' ");
		$result = $checkstocks->execute();
		$checkstocksresult = $checkstocks->fetchAll();
		$stocks = $checkstocksresult[0]['quantity'];
		if ($result == 1){
			echo 'You currently have ' .$stocks. ' shares of ' .$popname. '!</br>';
		}
	}
	$eligible = FALSE;
	if (($buyorsell == 1) && ($popcorn >= $price)){
		$eligible = TRUE;
	}
	if (($buyorsell == -1) && ($stocks > 0)){
		$eligible = TRUE;
	}
	echo $username. ' is eligible ' .var_export($eligible,true). '</br>';
	
	//Insert buy/sell order into  DB
	//Change stockprice
	if ($eligible){
		$changeprice = $dbh->prepare("
		UPDATE pops
		SET stockprice = stockprice + 1 * '$buyorsell'
		WHERE id = '$popid' ");
		$result = $changeprice -> execute();
		if ($result == 1){
			echo $popname . '\'s stockprice has changed to ' .($price + $buyorsell) .'</br>';
			
			//Remove or add popcorn from/to user
			$paypopcorn = $dbh->prepare("
			UPDATE users 
			SET popcorn = popcorn - '$buyorsell' * '$price'  
			WHERE id = '$id' ");
			$result = $paypopcorn->execute();	
			if ($result == 1){
				if ($buyorsell == 1){
					$earnspend = ' spent ';
				} else if ($buyorsell == -1){
					$earnspend = ' earned ';
				}
				echo $username .$earnspend .$price .' popcorn</br>';
				
				//Give popstock to user
				$givestock = $dbh->prepare("
				INSERT INTO ownership (user, pop, quantity) 
				VALUES ('$id', '$popid', '$buyorsell') 
				ON DUPLICATE KEY UPDATE quantity = quantity + '$buyorsell'");
				$result = $givestock->execute();
				
				if ($result == 1){
					if ($buyorsell == 1){
						echo 'Ownership updated, you invested in ' .$popname. '!</br>';
					} else if ($buyorsell == -1){
						echo 'Ownership updated, you sold ' .$popname. '!</br>';
					}
						
				}
			}
		}
	//Error msg
	} else if ($buyorsell == 1) {
		echo 'You dont have enough popcorn to buy this stock';
	} else if ($buyorsell == -1) {
		echo 'You dont have any shares in this popstock to sell';
	}
	
	?>
	<pre><? var_dump($priceresult); ?></pre>
	<?
	


    /*** close the database connection ***/
    $dbh = null;
}
    
catch(PDOException $e)
{
echo $e->getMessage();
}
	











?>