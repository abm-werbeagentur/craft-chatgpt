<?php
namespace abmat\chatgpt\controllers;

use abmat\chatgpt\ChatGptPlugin;
use abmat\chatgpt\models\PromptModel;
use abmat\chatgpt\records\PromptRecord;

class PromptsController extends \craft\web\Controller {
	public function actionIndex() {
		return $this->renderTemplate( 'abm-chatgpt/prompts/_index', [
			'prompts' => PromptRecord::find()->all()
		] );
	}

	public function actionCreate() {
		return $this->renderTemplate( 'abm-chatgpt/prompts/_edit', [
			'prompt' => new PromptModel()
		] );
	}

	public function actionEdit($id) {
		$prompt = PromptRecord::findOne( $id );
		if(!$prompt){
			throw new \yii\web\NotFoundHttpException("Prompt not found");
		}
		return $this->renderTemplate( 'abm-chatgpt/prompts/_edit', [
			'prompt' => $prompt
		]);
	}

	public function actionSave() {
		$request = \Craft::$app->getRequest();

		$promptModel = new PromptModel();
		$promptModel->id = $request->getParam( 'id' )??0;
		$promptModel->label = $request->getRequiredParam( 'label' );
		$promptModel->template = $request->getRequiredParam( 'template' );
		$promptModel->active = $request->getRequiredParam( 'active' );
		$promptModel->replaceText = $request->getRequiredParam( 'replaceText' );
		$promptModel->temperature = $request->getRequiredParam( 'temperature' );

		if ( ! $promptModel->validate() ) {
			return $this->renderTemplate( 'abm-chatgpt/prompts/_edit', [
				'prompt' => $promptModel
			] );
		}


		if ( ChatGptPlugin::getInstance()->promptService->save($promptModel)) {
			\Craft::$app->getSession()->setNotice(\Craft::t('abm-chatgpt', "Prompt updated"));
			return $this->redirect( 'abm-chatgpt/prompts' );
		}

		return $this->renderTemplate( 'abm-chatgpt/prompts/_edit', [
			'prompt' => $promptModel
		] );
	}

	public function actionRemove(int $id){
		if(ChatGptPlugin::getInstance()->promptService->remove($id)){
			\Craft::$app->getSession()->setNotice(\Craft::t('abm-chatgpt', "Prompt removed"));
		}
		return $this->redirect( 'abm-chatgpt/prompts' );
	}
}