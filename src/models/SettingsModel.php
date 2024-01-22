<?php
namespace abmat\chatgpt\models;

use Craft;
use craft\base\Model;
use craft\helpers\App;

class SettingsModel extends Model
{

	/**
	 * @var string
	 */
	public string $apiToken = '';

	/**
	 * @var string
	 */
	public string $preferredModel = 'gpt-3.5-turbo';

	/**
	 * @var double
	 */
	public float $temperature = 0.7;

	/**
	 * @var array
	 */
	public array $enabledFields = [];

	/**
	 * @var bool
	 */
	public bool $usePageLang = true;

	private array $_supportedFieldTypes = [
		'craft\fields\PlainText',
		'craft\redactor\Field',
		'craft\ckeditor\Field'
	];

	/**
	 * @return string
	 */
	public function getApiKey(): string
	{
		return App::parseEnv($this->apiToken);
	}

	public function titleFieldEnabled(){
		return isset($this->enabledFields['title']) && $this->enabledFields['title'];
	}

	public function getRegularFieldsList(): array {
		$fields = [];

		foreach ( \Craft::$app->getFields()->getAllFields() as $field ) {

			if ( in_array( ( new \ReflectionClass( $field ) )->getName(), $this->_supportedFieldTypes ) ) {
				$fields[] = [
					'id'     => $field->id,
					'uid'    => $field->uid,
					'handle' => $field->handle,
					'name'   => $field->name,
					'group'  => $field->getGroup()->name,
					'type'   => $this->_getClass( $field )
				];
			}

		}

		return $fields;
	}

	public function getMatrixFieldsList(): array {
		$matrixFields = [];
		foreach ( \Craft::$app->getFields()->getFieldsByType( 'craft\fields\Matrix' ) as $matrixField ) {
			$matrixFields [ $matrixField->handle ]['name']   = $matrixField->name;
			$matrixFields [ $matrixField->handle ]['fields'] = [];
			foreach ( \Craft::$app->getMatrix()->getBlockTypesByFieldId( $matrixField->id ) as $block ) {
				foreach ( \Craft::$app->getFields()->getAllFields( "matrixBlockType:" . $block->uid ) as $blockField ) {
					if ( in_array( ( new \ReflectionClass( $blockField ) )->getName(), $this->_supportedFieldTypes ) ) {
						$matrixFields [ $matrixField->handle ]['fields'][] = [
							'id'     => $blockField->id,
							'uid'    => $blockField->uid,
							'handle' => $blockField->handle,
							'name'   => $blockField->name,
							'group'  => $block->name,
							'type'   => $this->_getClass( $blockField )
						];
					}
				}
			}
		}

		return $matrixFields;
	}

	protected function _getClass( $object ): string {
		return str_replace( [
			'craft\fields\\',
			'craft\redactor\Field',
			'craft\ckeditor\Field'
		],
			[
				'',
				'Redactor',
				'CKEditor'
			], ( new \ReflectionClass( $object ) )->getName() );
	}
	
	public function getLanguages (){
		return [
			'en' => Craft::t('abm-chatgpt','English') . ' (English)',
			'de' => Craft::t('abm-chatgpt','German') . ' (Deutsch)',
			'fr' => Craft::t('abm-chatgpt','French') . ' (Français)',
			'es' => Craft::t('abm-chatgpt','Spanish') . ' (Español)',
			'it' => Craft::t('abm-chatgpt','Italian') . ' (Italiano)',
			'pt' => Craft::t('abm-chatgpt','Portuguese') . ' (Português)',
			'nl' => Craft::t('abm-chatgpt','Dutch') . ' (Nederlands)',
			'pl' => Craft::t('abm-chatgpt','Polish') . ' (Polski)',
			'ru' => Craft::t('abm-chatgpt','Russian') . ' (Русский)',
			'ja' => Craft::t('abm-chatgpt','Japanese') . ' (日本語)',
			'zh' => Craft::t('abm-chatgpt','Chinese') . ' (中文)',
			'br' => Craft::t('abm-chatgpt','Brazilian Portuguese') . ' (Português Brasileiro)',
			'tr' => Craft::t('abm-chatgpt','Turkish') . ' (Türkçe)',
			'ar' => Craft::t('abm-chatgpt','Arabic') . ' (العربية)',
			'ko' => Craft::t('abm-chatgpt','Korean') . ' (한국어)',
			'hi' => Craft::t('abm-chatgpt','Hindi') . ' (हिन्दी)',
			'id' => Craft::t('abm-chatgpt','Indonesian') . ' (Bahasa Indonesia)',
			'sv' => Craft::t('abm-chatgpt','Swedish') . ' (Svenska)',
			'da' => Craft::t('abm-chatgpt','Danish') . ' (Dansk)',
			'fi' => Craft::t('abm-chatgpt','Finnish') . ' (Suomi)',
			'no' => Craft::t('abm-chatgpt','Norwegian') . ' (Norsk)',
			'ro' => Craft::t('abm-chatgpt','Romanian') . ' (Română)',
			'ka' => Craft::t('abm-chatgpt','Georgian') . ' (ქართული)',
			'vi' => Craft::t('abm-chatgpt','Vietnamese') . ' (Tiếng Việt)',
			'hu' => Craft::t('abm-chatgpt','Hungarian') . ' (Magyar)',
			'bg' => Craft::t('abm-chatgpt','Bulgarian') . ' (Български)',
			'el' => Craft::t('abm-chatgpt','Greek') . ' (Ελληνικά)',
			'fa' => Craft::t('abm-chatgpt','Persian') . ' (فارسی)',
			'sk' => Craft::t('abm-chatgpt','Slovak') . ' (Slovenčina)',
			'cs' => Craft::t('abm-chatgpt','Czech') . ' (Čeština)',
			'lt' => Craft::t('abm-chatgpt','Lithuanian') . ' (Lietuvių)'
		];
	}
}