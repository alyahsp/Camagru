<?php
	require_once('database.php');

	try{
		$dbh = new PDO('mysql:host=localhost', $DB_USER, $DB_PASSWORD);
		$dbh->query('CREATE DATABASE IF NOT EXISTS CamagruDB;');
		$dbh->query('USE CamagruDB;');
		$dbh->query("CREATE TABLE IF NOT EXISTS User
		(
			UserID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
			Login VARCHAR(32) NOT NULL,
			Password VARCHAR(128) NOT NULL,
			Email VARCHAR(280) NOT NULL,
			Active BOOLEAN DEFAULT FALSE
		);");

		$dbh->query("CREATE TABLE IF NOT EXISTS Photo
		(
			PhotoID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
			UserID INT NOT NULL,
			PicURL VARCHAR(512) NOT NULL,
			Likes INT,
			FOREIGN KEY (UserID) REFERENCES User(UserID) ON DELETE CASCADE
		);");

		$dbh->query("CREATE TABLE IF NOT EXISTS Comment
		(
			CommentID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
			UserID INT NOT NULL,
			PhotoID INT NOT NULL,
			Content VARCHAR(512) NOT NULL,
			FOREIGN KEY (UserID) REFERENCES User(UserID) ON DELETE CASCADE,
			FOREIGN KEY (PhotoID) REFERENCES Photo(PhotoID) ON DELETE CASCADE
		);");

		$dbh->query("CREATE TABLE IF NOT EXISTS Heart
		(
			HeartID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
			UserID INT NOT NULL,
			PhotoID INT NOT NULL,
			Hearted BOOLEAN DEFAULT FALSE,
			FOREIGN KEY (UserID) REFERENCES User(UserID) ON DELETE CASCADE,
			FOREIGN KEY (PhotoID) REFERENCES Photo(PhotoID) ON DELETE CASCADE
		);");
	}
	catch (PDOException $e){
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
?>
