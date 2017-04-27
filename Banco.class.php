<?php
class Banco{
	private static $server   = "localhost";
	private static $user     = "wmaco071_pagsegu";
	private static $pass     = "catya067290";
	private static $db       = "wmaco071_pagseguro";
	private static $cn       = NULL;

	//private static $user     = "root";
	//private static $pass     = "123";
	//private static $db       = "pagseguro_teste";
	//private static $cn       = NULL;

	public function __construct(){
		self::$cn = mysqli_connect(self::$server, self::$user, self::$pass);
		mysqli_select_db(self::$cn, self::$db);
	}

	public function query($sql){
		mysqli_query(self::$cn, $sql);
	}

}