<?php
namespace abmat\chatgpt\controllers;

use Craft;

use abmat\chatgpt\ChatGptPlugin;

use yii\web\Response;

class SettingsController extends \craft\web\Controller {
	/**
	 * @return Response
	 */
	public function actionGeneral(): Response {
		$settings = ChatGptPlugin::getInstance()->getSettings();

		return $this->renderTemplate( 'abm-chatgpt/settings/_general', [
			'settings' => $settings,
		] );
	}

	/**
	 * @return Response
	 */
	public function actionFields(): Response {
		$settings = ChatGptPlugin::getInstance()->getSettings();

		return $this->renderTemplate( 'abm-chatgpt/settings/_fields', [
			'settings'     => $settings,
			'fields'       => $settings->getRegularFieldsList(),
			'matrixFields' => $settings->getMatrixFieldsList()
		] );
	}
}