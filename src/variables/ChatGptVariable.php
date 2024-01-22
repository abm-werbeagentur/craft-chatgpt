<?php
namespace abmat\chatgpt\variables;

use Craft;
use abmat\chatgpt\ChatGptPlugin;

class ChatGptVariable {

	public function getPrompts(bool $enabled = false): array {
		return ChatGptPlugin::getInstance()->promptService->getPrompts($enabled);
	}

	public function getLanguages(): array {
		$currentSiteLang = "";

		foreach(Craft::$app->sites->getAllSites() as $site) {
			if($site->handle == Craft::$app->request->getQueryParam("site")) {
				$currentSiteLang = $site->language;
			}
		}
		if(!$currentSiteLang) {
			$currentSiteLang = Craft::$app->sites->getAllSites()[0]->language;
		}

		$languages[$currentSiteLang] = [
			"language"=> $currentSiteLang,
			"text"=> Craft::t('abm-chatgpt', "Translate to {language}", [
				"language" => $currentSiteLang
			])
		];

		foreach(Craft::$app->sites->getAllSites() as $site) {
			if(!array_key_exists($site->language,$languages)) {
				$languages[$site->language] = [
					"language"=> $site->language,
					"text"=> Craft::t('abm-chatgpt', "Translate to {language}", [
						"language" => $site->language
					])
				];
			}
		}
		return $languages;
	}
}