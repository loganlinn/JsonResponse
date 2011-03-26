<?php
/**
 * JsonResponse CakePHP Component
 * 
 * Handles organizing a standard JSON response for AJAX 
 * requests modeled after JSend (http://labs.omniti.com/labs/jsend)
 * 
 * Responses have the following fields
 *      - status [string]
 *      - data [array]
 *      - message [string] (optional)
 *
 * @author loganlinn
 */
class JsonResponseComponent extends Object{
	/*
	 * Constants
	 */
	const STATUS_SUCCESS = "success";
	const STATUS_FAIL = "fail";
	const STATUS_ERROR = "error";
	
	/**
	 * Store response properties in a variable so unwanted class variables aren't encoded to JS
	 */
	public $response = null;
	
	/**
	 * Store reference to the associated controller
	 */
	private $controller = null;
	
	/**
	 * Variable to set the response object as
	 */
	public $viewVariable = 'json';
	public $viewName = "/json_response/default";
	public $layoutName = "raw";
	
	/**
	 * Initializes the component
	 *
	 * @param AppController $controller 
	 * @return void
	 */
	public function initialize(AppController &$controller, $options=array()){
		$this->controller = $controller;
		/**
		 * Create the response object. Use generic Object class
		 */
		$this->response = new Object();
		$this->response->status = self::STATUS_SUCCESS;
		$this->response->data = null;
	}

	/**
	 * Finalizes the response by passing the response to the View, and setting the render
	 *
	 * @return void
	 */
	public function finish(){
		if($this->controller){
			$this->controller->set($this->viewVariable, $this->response);
			$this->controller->render($this->viewName, $this->layoutName);
		}
	}
	
	/**
	 * Called when the response should indicate a failure.
	 * Sets the status to fail
	 * If $key is array, sets the fail data to the $key
	 * If $key is string, store a message for the $key and $value
	 *
	 * @param string $key 
	 * @param string $value 
	 * @param string $overwrite indicates if when writing to the key, whether the key should be overwritten, or values combined together
	 * @return void
	 */
	public function fail($key, $value=null, $overwrite=true){
		$this->response->status = self::STATUS_FAIL;
		if(is_array($key)){
			$this->response->data = $data;
		}else{
			if($this->response->data == null){
				$this->response->data = array($key=>$value);
			}else if(isset($this->response->data[$key])){ 	// check if we already have a value for that key
				if(is_array($this->response->data[$key])){	// append if its already an array
					$this->response->data[$key][] = $value;
				}else if(!$overwrite){ 				// create an array if we aren't overwriting
					$this->response->data[$key] = array($this->response->data[$key], $value);
				}else{	// overwrite the value
					$this->response->data[$key] = $value;
				}
			}else{
				$this->response->data[$key] = $value;
			}
		}
	}
	
	/**
	 * Set's the response's status to success
	 *
	 * @return void
	 */
	public function success(){
		$this->response->status = self::STATUS_SUCCESS;
	}
	
	/**
	 * Called when the response should indicate an error
	 * Sets the status to error
	 * Sets the response's message
	 *
	 * @param string $message 
	 * @return void
	 */
	public function error($message=null){
		$this->response->status = self::STATUS_ERROR;
		$this->response->message = $message;
	}
	
	/**
	 * Provides the response's status
	 *
	 * @return string $status
	 */
	public function getStatus(){
		return $this->response->status;
	}
	
	/**
	 * Adds an item to the response's data array
	 *
	 * @param object $dataItem 
	 * @return void
	 */
	public function addData($dataItem){
		if($this->response->data == null){
			$this->response->data = array();
		}
		$this->response->data[] = $dataItem;
	}
	
	/**
	 * Sets the response's data array 
	 *
	 * @param array $data 
	 * @return void
	 */
	public function setData($data){
		$this->response->data = $data;
	}
	
	/**
	 * Sets the response message
	 *
	 * @param string $message 
	 * @return void
	 */
	public function setMessage($message){
		$this->response->message = $message;
	}
}
?>