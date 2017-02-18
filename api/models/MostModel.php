<?php

require_once '../source/lib/BaseTemplate.php';
require_once '../source/lib/Database.php';

/**
 * MostModel will return Top 5 Watched Videos information information from the DB 
 */
class MostModel extends BaseTemplate
{
	/**
	 * Connect with Database and establish a DB Handler to use
	 */
	final public function __construct(){
		//lets start by connecting to the db
		$conn = new Database;
		$this->_dbh = $conn->connect();
		if(!$this->_dbh){
			$db_response = $conn->getResponse();
			$this->setError($db_response['reason']);
			$this->_dbh = null;
		};
		$conn = null;
	}
	final public function getWatched(){
		if($this->_response['status']){
			$rslt = $this->getResults("SELECT id, sortorder FROM most WHERE type = 'mwv' ORDER BY sortorder asc");

			if($rslt['status'] && !empty($rslt['result'])){
				$list = [];
				$rows = $rslt['result'];
				for($i=0;$i<count($rows);$i++){
					$list[] = [
						'videoId' => $rows[$i]['id'],
						'order' => $rows[$i]['sortorder'],
						'href' => self::URL.'/video/'.$rows[$i]['id']
					];
				}
				//format data so that it outputs the way we want it
				$this->_response['data']['result'] = $list;
				$rows=null;
				$list=null;
			//the sql was good but returned no results
			} elseif ($rslt['status'] && empty($rslt['result'])){
				$this->setError('Invalid Country Code');
			} else {
				$this->setError($rslt['reason']);
			}
			$rslt = null;
		}
	}
}