<?php
namespace abmat\chatgpt\models;

use Craft;
use craft\base\Chippable;
use craft\base\Colorable;
use craft\base\CpEditable;
use craft\base\Iconic;
use craft\base\Model;
use craft\helpers\Cp;
use craft\helpers\App;
use craft\helpers\ArrayHelper;
use craft\helpers\Html;
use craft\models\FieldLayout;

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
					'usages'  => $this->getUsageList($field),
					'type'   => $this->_getClass($field)
				];
			}
		}

		return $fields;
	}

	private function getUsageList($field) {
		$layouts = \Craft::$app->getFields()->findFieldUsages($field);
		if (empty($layouts)) {
			return Html::tag('i', Craft::t('app', 'No usages'));
		}

		/** @var FieldLayout[][] $layoutsByType */
		$layoutsByType = ArrayHelper::index($layouts,
			fn(FieldLayout $layout) => $layout->uid,
			[fn(FieldLayout $layout) => $layout->type ?? '__UNKNOWN__'],
		);
		/** @var FieldLayout[] $unknownLayouts */
		$unknownLayouts = ArrayHelper::remove($layoutsByType, '__UNKNOWN__');
		/** @var FieldLayout[] $layoutsWithProviders */
		$layoutsWithProviders = [];

		// re-fetch as many of these as we can from the element types,
		// so they have a chance to supply the layout providers
		foreach ($layoutsByType as $type => &$typeLayouts) {
			/** @var string|ElementInterface $type */
			/** @phpstan-ignore-next-line */
			foreach ($type::fieldLayouts(null) as $layout) {
				if (isset($typeLayouts[$layout->uid]) && $layout->provider instanceof Chippable) {
					$layoutsWithProviders[] = $layout;
					unset($typeLayouts[$layout->uid]);
				}
			}
		}
		unset($typeLayouts);

		$labels = [];
		$items = array_map(function(FieldLayout $layout) use (&$labels) {
			/** @var FieldLayoutProviderInterface&Chippable $provider */
			$provider = $layout->provider;
			$label = $labels[] = $provider->getUiLabel();
			$url = $provider instanceof CpEditable ? $provider->getCpEditUrl() : null;
			$icon = $provider instanceof Iconic ? $provider->getIcon() : null;

			$labelHtml = Html::beginTag('span', [
				'class' => ['flex', 'flex-nowrap', 'gap-s'],
			]);
			if ($icon) {
				$labelHtml .= Html::tag('div', Cp::iconSvg($icon), [
					'class' => array_filter([
						'cp-icon',
						'small',
						$provider instanceof Colorable ? $provider->getColor()?->value : null,
					]),
				]);
			}
			$labelHtml .= Html::tag('span', Html::encode($label)) .
				Html::endTag('span');

			return $url ? Html::a($labelHtml, $url) : $labelHtml;
		}, $layoutsWithProviders);

		// sort by label
		array_multisort($labels, SORT_ASC, $items);

		return Html::ul($items, [
			'encode' => false,
		]);
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