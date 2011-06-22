<?php
/**
 * Examples for the JsonResponse Component
 * @author loganlinn
 */
class ExamplesController extends AppController
{
	public $name = "Examples";
	public $uses = array();
	public $components = array("JsonResponse");
	public $helpers = array("Javascript");
	
	function data_example(){
		$this->JsonResponse->addData(array("name" => "foo"));
		$this->JsonResponse->addData(array("name" => "bar"));
		$this->JsonResponse->finish();
		return();
	}
	
	function error_example($name=null){
		if($name == null){
			$this->JsonResponse->error("Everyone has a name!");
		}else{
			$this->JsonResponse->setData(array("name"=>$name));
		}
		$this->JsonResponse->finish();
		return;
	}
	
	function fail_example(){
		if(empty($this->data['title'])){
			$this->JsonResponse->fail("title", "A title is required");
		}
		$this->JsonResponse->finish();
	}
	
	function validation_example(){
		App::import("Model", "Page");
		$this->Page = new Page();
		$this->Page->set($this->data);
		if(!$this->Page->validates()){
			foreach($this->Page->invalidFields() as $invalid_field => $message){
				$this->JsonResponse->fail($invalid_field, $message);
			}
			$this->JsonResponse->finish();
			return;
		}
		if($this->Page->save()){
			// return the data with the assigned id
			$this->data["Page"]["id"] = $this->Page->id;
			$this->JsonResponse->setData($this->data);
		}else{
			$this->error("An error occurred while saving.");
		}
		$this->JsonResponse->finish();
	}
}

?>