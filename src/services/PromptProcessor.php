<?php
namespace abmat\chatgpt\services;

use Craft;

use abmat\chatgpt\ChatGptPlugin;
use abmat\chatgpt\models\PromptModel;
use abmat\chatgpt\records\PromptRecord;

class PromptProcessor {

	public function process($data) {

		$prompt = $data['prompt']??'';
		$query = $data['query']??'';
		$lang = $data['lang']??'';

		if(!$prompt || !$query){
			throw new \Exception(Craft::t('abm-chatgpt', "Missing required parameters"));
		}

		if(($systemPrompt = $this->_processSystemPrompt($prompt,$query,$lang)) != false) {
			return $systemPrompt;
		}

		$promptRecord = PromptRecord::findOne(['id'=>(int)$prompt]);
		if(!$promptRecord){
			throw new \Exception(Craft::t('abm-chatgpt', "Prompt not found"));
		}

		$prompt = preg_replace('/(\[\[text\]\])/', $query, $promptRecord->template);
		$prompt = "Using the locale $lang\n\n$prompt";

		$temperature = $promptRecord->temperature;

		$maxTokens = round($promptRecord->wordsNumber * 1.33);
		if ($promptRecord->wordsType == 2) {

			$maxTokens = round($this->_countWords($query) * $promptRecord->wordsMultiplier * 1.33);
		}

		return [
			'processed'=>ChatGptPlugin::getInstance()->request->send($query, $prompt, $maxTokens, $temperature),
			'replaceText'=>$promptRecord->replaceText
		];
	}

	private function _countWords($str){
		return count(preg_split('~[^\p{L}\p{N}\']+~u', $str));
	}

	private function _processSystemPrompt($prompt,$query,$lang) {

		if($prompt == '_translate_'){
			$language = locale_get_display_name($lang, 'en');
			$prompt="Keep html notations and translate following text to {$language}: {$query}";
			return [
				'processed'=>ChatGptPlugin::getInstance()->request->send($query, $prompt, 30000, 0.7, true),
				'replaceText'=>1
			];
		}
		return false;
	}
}