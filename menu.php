<?php  
	namespace app;
	
	class Menu 
	{
  		public $db;
  		public $main;
  		public function __construct($db=null) 
  		{
    		$this->db = $db ? $db : getConnection();
    		$q = 'SELECT id,name,parent_id,FileName FROM head_nav_menu';
    		$this->main = $this->db->query($q);
  		}
  	
  		public function displayMenu() {
    	$items = array();
    	foreach ($this->main as $row) {
      	$path = $row['parent_id'];
      	$link = self::link($row['name'],$path==='NULL'?null:$path);
      	
      	if ($row['sublinks'] > 0) $link .= $this->subMenu($row['id']);
     	$items[] = $link;
    	}
    
    	return '<nav>'.self::lists(array_filter($items)).'</nav>';
  		}
  		private function subMenu($id) {
    	$q = 'SELECT name,parent_id FROM submenu WHERE menu_id = ';
    	$sub = array();
    	foreach ($this->db->query($q.$id) as $row) {
      	$sub[] = self::link($row['name'],$row['linkpath']);
    	}
    	return self::lists(array_filter($sub));
  		}
  		static function link($content, $href) {
    	return '<a '.($href?'href="'.$href.'"':'').'>' .$content.'</a>';
  		}
 		static function lists(array $items) {
    	if (!$items) return null;
    	return '<ul><li>'.implode('</li><li>',$items).'</li></ul>';
  		}
	}


?>