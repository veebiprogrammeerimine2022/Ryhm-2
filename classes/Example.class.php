<?php
	class Example {
		//muutujad, ehk omadused (properties)
		private $secret_value;
		public $public_value = 7;
		private $received_value;
		
		//konstruktor, funktsioon, mis käivitub klassi kasutuselevõtul
		function __construct($value){
			$this->secret_value = mt_rand(1, 10);
			echo "Klass alustab! Salajane arv on " .$this->secret_value ."<br>";
			$this->received_value = $value;
			$this->multiply();
		}
		
		//destruktor, funktsioon, mis käivitub, kui klass lõpetab
		function __destruct(){
			echo "Ongi kõik! <br>";
		}
		
		
		//funktsioonid ehk meetodid (methods)
		private function multiply(){
			$result = $this->secret_value * $this->received_value;
			echo "Korrutis: " .$result ."<br>";
		}
		
		public function add(){
			echo "Salajane arv + avalik arv = " .$this->secret_value + $this->public_value ."<br>";
		}
	}//class lõppeb