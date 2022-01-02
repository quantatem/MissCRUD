<?php
class Connection
{
	public static function make($config) 
	{
		try {
			//return new PDO('mysql:host=127.0.0.1;dbname=mytodo', 'root', 'password');
			return new PDO(
				$config['connection'] . ';dbname=' . $config['name'] . ';charset=utf8mb4',
				$config['username'],
				$config['password'],
				$config['options']
			);
		} catch(PDOException $e) {
			die($e->getMessage());
		}
	}
}
