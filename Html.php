<?php
class Html {
	static public $instance;
	
	private $applicationName	= 'G1337 gallery';
	private $pageName					= '';
	private $css							= array();
	private $js								= array();
	private $layer						= 'front';
	private $frontend					= true;
	private $jsHeader					= '';
	
	public function setLayer($layer) {
		$this->layer = $layer=='front' ? $layer : 'account';
		return $this;
	}
	
	public function addJSHeader($code) {
		$this->jsHeader.=$code."\n";
	}
	
	static public function getInstance() {
		if(null==self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function __construct() {
	}
	
	public function addCSS($cssFile, $media='all') {
		$this->css[]=array('media'=>$media, 'href'=>$cssFile);
		return $this;
	}
	public function addJS($file) {
		$this->js[]=$file;
		return $this;
	}
	
	public function setApplicationName($applicationName) {
		$this->applicationName = $applicationName;
		return $this;
	}
	public function setPageName($pageName) {
		$this->pageName = $pageName;
		return $this;
	}
	public function getPageName() {
		return $this->pageName;
	}
	
	public function start() {
		ob_start(array(&$this, 'render'));
	}
	
	public function render($content) {
		$return = '';
		$return.= '<!DOCTYPE html>'."\n";
		$return.=	'<html dir="ltr" lang="en-EN">'."\n";
		$return.=	'<head>'."\n";
		$return.=	'<meta charset="UTF-8" />'."\n";
		$return.=	'<title>'.($this->pageName!=='' ? $this->pageName.' | ' : '').$this->applicationName.'</title>'."\n";
		$return.=	'<link rel="profile" href="http://gmpg.org/xfn/11" />'."\n";

		// CSS files
		array_unique($this->css);
		foreach($this->css as $css) {
			$return.= sprintf('<link rel="stylesheet" type="text/css" media="%s" href="%s" />'."\n", $css['media'], $css['href']);
		}
		
		// Javascript files
		array_unique($this->js);
		foreach($this->js as $file) {
			$return.='<script type="text/javascript" src="'.$file.'"></script>'."\n";
		}
		
		$return.=	'<body>'."\n";
		if($this->layer=='account') {
			$return .='<table id="wrap" cellpadding="0" cellspacing="0"><tr><td id="header" colspan="3">';
			$return .='<h1><a href="'.ROOT_URL.'">'.Config::getInstance()->get('application_name', 'G1337').'</a></h1>';
			$return .='</td></tr><tr><td id="account-wrapper">';
		}
		$return.=$content;
		if($this->layer=='account') {
			$return .='</td></tr></table>';
		}
		$return.= '</body>'."\n";
		$return.= '</html>';
		return $return;
	}
}
