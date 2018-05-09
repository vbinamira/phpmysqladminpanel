<?php 
	/**
	 * 
	 */
	class Accounting
	{
		private $file;
		function __construct($value)
		{
			$this->file = $value;
		}

		public function parseFile()
		{
			$data = [];
			$filecontainer = fopen($this->file,"r");
			$this->numarray = fgetcsv($filecontainer);
		}

		public function getTotal()
		{
			for ($i=0; $i < count($numarray); $i++) { 
				$total = $numarray[$i];
				$max += intval($total);
			}
			return $max;
		}


		public function getAverage($numarray) 
		{
			$numlength = count($number);
			for ($i=0; $i < $numlength; $i++) { 
				$singlenums = $numlength[$i];
				$total += $singlenums;
			}
			$average = $total / $numlength;
			return $average;
		}

		public function convert($value)
		{
			// USE API HERE
		}
	}
 ?>