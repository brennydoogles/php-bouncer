<?php

	require_once("BouncerAccessResponse.class.php");
	/**
	 * Created with JetBrains PhpStorm.
	 * User: Brendon Dugan <wishingforayer@gmail.com>
	 * Date: 7/4/12
	 * Time: 4:08 PM
	 *
	 */
	class BouncerRole{
		private $name;
		private $pages;
		private $overrides;

		/**
		 * @param string $name
		 * @param array  $pages
		 * @param array  $overrides
		 */
		public function __construct($name = "", $pages = null, $overrides = null){
			if(!empty($name) && is_array($pages)){
				$this->name      = $name;
				$this->pages     = $pages;
				$this->overrides = (is_array($overrides)) ? $overrides : array();
			}
			else{
				$this->name      = "";
				$this->pages     = array();
				$this->overrides = array();
			}
		}

		/**
		 * @param string $name
		 */
		public function setName($name){
			$this->name = $name;
		}

		/**
		 * @return string
		 */
		public function getName(){
			return $this->name;
		}

		/**
		 * @param array $overrides
		 */
		public function setOverrides($overrides){
			if(is_array($overrides)){
				$this->overrides = $overrides;
			}
		}

		/**
		 * @return array
		 */
		public function getOverrides(){
			return $this->overrides;
		}

		/**
		 * @param $pages
		 */
		public function setPages($pages){
			if(is_array($pages)){
				$this->pages = $pages;
			}
		}

		/**
		 * @return array
		 */
		public function getPages(){
			return $this->pages;
		}

		/**
		 * @param string $url
		 *
		 * @return BouncerAccessResponse
		 */
		public function verifyAccess($url){
			$isOverridden = array_key_exists($url, $this->overrides);
			$response     = new BouncerAccessResponse();
			$response->setIsAccessible(false); // We set this to false by default, and change it if we need to.
			if(!$isOverridden && in_array($url, $this->pages)){
				$response->setIsAccessible(true);
			}
			$response->setIsOverridden($isOverridden);
			return $response;
		}

		/**
		 * @param string $url
		 *
		 * @return string|bool
		 */
		public function getOverridingPage($url){
			if(array_key_exists($url, $this->overrides)){
				return $this->overrides[$url];
			}
			return false;
		}
	}
