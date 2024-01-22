<?php
namespace abmat\chatgpt\services;

use craft\helpers\StringHelper;

use abmat\chatgpt\models\PromptModel;
use abmat\chatgpt\records\PromptRecord;

class Prompt extends \craft\base\Component {

	public function save(PromptModel $promptModel): bool{
		if($promptModel->id == 0){
			$promptRecord = new PromptRecord();
		}else{
			$promptRecord = PromptRecord::findOne($promptModel->id);
		}
		$promptRecord->dateCreated = $promptModel->getDateCreated();
		$promptRecord->dateUpdated = $promptModel->getDateUpdated();
		$promptRecord->uid = StringHelper::UUID();
		$promptRecord->label = $promptModel->label;
		$promptRecord->template = $promptModel->template;
		$promptRecord->active = $promptModel->active;
		$promptRecord->replaceText = $promptModel->replaceText;
		$promptRecord->temperature = $promptModel->temperature;

		return $promptRecord->save();
	}

	public function remove(int $id): int{
		$promptRecord = PromptRecord::findOne($id);
		if(!$promptRecord){
			return 0;
		}
		return PromptRecord::deleteAll(['id'=>$id]);
	}

	public function getPrompts(bool $enabled = false): array
	{

		$prompts = PromptRecord::find();

		if ($enabled) {
			$prompts =  $prompts->where(['active' => true]);
		}

		$prompts =  $prompts->all();

		return $prompts;
	}
}