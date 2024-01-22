<?php
namespace abmat\chatgpt\controllers;

use Craft;
use abmat\chatgpt\ChatGptPlugin;

class PromptRequestController extends \craft\web\Controller{

	public function actionProcess() {
		$this->requirePostRequest();
		$data = $this->request->post();

		try{
			$result = ChatGptPlugin::getInstance()->promptProcessor->process($data);
			return $this->asJson(['res'=>true, 'result'=>$result]);
		}catch (\Throwable $e){
			return $this->asJson(['res'=>false, 'msg'=>$e->getMessage()]);
		}
	}
}