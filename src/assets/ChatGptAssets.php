<?php
namespace abmat\chatgpt\assets;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class ChatGptAssets extends AssetBundle
{
	public static function getSourcePath() {
    	return __DIR__ . '/dist';
    }

	public function init() {

		/* define asset bundle and files */
		$this->sourcePath = self::getSourcePath();
		$this->depends = [CpAsset::class];
		$this->js = ['abmat-chatgpt.js'];
		$this->css = ['abmat-chatgpt.css'];
		parent::init();
	}
}