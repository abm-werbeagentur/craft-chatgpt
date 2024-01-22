<?php
namespace abmat\chatgpt\services;

use Craft;

use abmat\chatgpt\ChatGptPlugin;
use abmat\chatgpt\models\PromptModel;
use abmat\chatgpt\records\PromptRecord;

class PromptProcessor {

	public function process($data){

		$prompt = $data['prompt']??'';
		$query = $data['query']??'';
		$lang = $data['lang']??'';

		if(!$prompt || !$query){
			throw new \Exception(Craft::t('abm-chatgpt', "Missing required parameters"));
		}

		if($systemPrompt = $this->_processSystemPrompt($prompt,$query,$lang) != false) {
			return $systemPrompt;
		}

		$promptRecord = PromptRecord::findOne(['id'=>(int)$prompt]);
		if(!$promptRecord){
			throw new \Exception(Craft::t('abm-chatgpt', "Prompt not found"));
		}

		$prompt = preg_replace('/(\[\[text\]\])/', $query, $promptRecord->template);
		$prompt = "Using the locale $lang\n\n$prompt";

		$temperature = $promptRecord->temperature;

		return [
			'processed'=>ChatGptPlugin::getInstance()->request->send($prompt, 30000, $temperature),
			'replaceText'=>$promptRecord->replaceText
		];
	}

	private function _processSystemPrompt($prompt,$query,$lang) {

		if($prompt == '_translate_'){
			$prompt="Translate to {$lang}: {$query}";
			return [
				'processed'=>ChatGptPlugin::getInstance()->request->send($prompt, 30000, 0.7, true),
				'replaceText'=>1
			];
		}
		return false;
	}
}