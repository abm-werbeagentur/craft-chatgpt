<?php
namespace abmat\chatgpt\records;

/**
 * @property int $id
 * @property string $label
 * @property string $template
 * @property bool $active
 *
 * @property-read \yii\db\ActiveQueryInterface $element
 */
class PromptRecord extends \craft\db\ActiveRecord
{
	/**
	 * @return string
	 */
	public static function tableName()
	{
		return '{{%abm_chatgpt_prompt}}';
	}
}