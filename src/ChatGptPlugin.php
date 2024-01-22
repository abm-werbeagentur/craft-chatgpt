<?php
namespace abmat\chatgpt;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\base\Field;
use craft\elements\Entry;
use craft\events\DefineFieldHtmlEvent;
use craft\events\DefineHtmlEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\TemplateEvent;
use craft\helpers\StringHelper;
use craft\helpers\UrlHelper;
use craft\web\Application;
use craft\web\UrlManager;
use craft\web\View;
use craft\web\twig\variables\CraftVariable;

use abmat\chatgpt\assets\ChatGptAssets;
use abmat\chatgpt\models\SettingsModel;
use abmat\chatgpt\services\Prompt;
use abmat\chatgpt\services\PromptProcessor;
use abmat\chatgpt\services\Request;
use abmat\chatgpt\variables\ChatGptVariable;

use yii\base\Event;

/**
 * ChatGPT Prompts and translations for plain text fields.
 * @method static Plugin getInstance()
 *
 * @author ABM
 * @since 1.0
 */
class ChatGptPlugin extends \craft\base\Plugin {

	/**
     * @inheritdoc
     */
	public string $schemaVersion = '1.0.0';

    /**
     * @inheritdoc
     */
	public bool $hasCpSection = true;

    /**
     * @inheritdoc
     */
    public bool $hasCpSettings = true;

	public function init() {
		parent::init();

		$this->_setComponents();

		Craft::$app->on(Application::EVENT_INIT, function() {
			if (Craft::$app->getUser()->getIdentity()) {
				$this->_setRoutes();
				$this->_setEvents();
			}
		});
	}

	protected function _setComponents(){
		$this->setComponents([
			'promptService' => Prompt::class,
			'request' => Request::class,
			'promptProcessor' => PromptProcessor::class,
		]);
	}

	protected function _setRoutes() {
		// Register CP routes
		Event::on(
			UrlManager::class,
			UrlManager::EVENT_REGISTER_CP_URL_RULES,
			function (RegisterUrlRulesEvent $event) {

				$event->rules['abm-chatgpt/settings/general'] = 'abm-chatgpt/settings/general';
				$event->rules['abm-chatgpt/settings/fields'] = 'abm-chatgpt/settings/fields';

				$event->rules['abm-chatgpt/prompts'] = 'abm-chatgpt/prompts/index';
				$event->rules['abm-chatgpt/prompts/add'] = 'abm-chatgpt/prompts/create';
				$event->rules['abm-chatgpt/prompts/edit/<id:\d+>'] = 'abm-chatgpt/prompts/edit';
				$event->rules['abm-chatgpt/prompts/delete/<id:\d+>'] = 'abm-chatgpt/prompts/remove';
			}
		);
	}

	protected function _setEvents(){
		
		/**
		 * Warn user in case there are no selected fields.
		 */
		Event::on(
			ChatGptPlugin::class,
			ChatGptPlugin::EVENT_AFTER_SAVE_SETTINGS,
			function (Event $event) {

				/** @var SettingsModel $settings */
				$settings = ChatGptPlugin::getInstance()->getSettings();

				if (!in_array(true, $settings->enabledFields, false)){
					Craft::$app->getSession()->setError(Craft::t('abm-chatgpt', 'Fields are not selected in settings. Please select fields in plugin settings under \'Fields\' tab.'));
				}

				if ($settings->apiToken === ''){
					Craft::$app->getSession()->setError(Craft::t('abm-chatgpt', 'API Access Token required.'));
				}
			}
		);

		if (Craft::$app->getRequest()->getIsCpRequest()) {
			Event::on(
				CraftVariable::class,
				CraftVariable::EVENT_INIT,
				function (Event $event) {
					$variable = $event->sender;
					$variable->set('abmChatGpt', ChatGptVariable::class);
				}
			);
	
			/**
			 * Attach button to selected fields.
			 */
			Event::on(
				Field::class,
				Field::EVENT_DEFINE_INPUT_HTML,
				static function (DefineFieldHtmlEvent $event) {
					/** @var SettingsModel $settings */
					$settings = ChatGptPlugin::getInstance()->getSettings();
	
					if (
						array_key_exists($event->sender->uid, $settings->enabledFields)
						&& $settings->enabledFields[$event->sender->uid]
						&& $settings->apiToken
					){
						$event->html .= Craft::$app->view->renderTemplate('abm-chatgpt/_fields.twig',
							[ 'event' => $event, 'hash' => StringHelper::UUID()] );
					}
				}
			);

			// Load JS before page template is rendered
			Event::on(
				View::class,
				View::EVENT_BEFORE_RENDER_PAGE_TEMPLATE,
				function (TemplateEvent $event) {
					/** @var SettingsModel $settings */
					$settings = ChatGptPlugin::getInstance()->getSettings();

					if($settings->apiToken) {
						// Get view
						$view = Craft::$app->getView();

						$view->registerAssetBundle(ChatGptAssets::class);

						// Load additional JS
						$js = Craft::$app->view->renderTemplate('abm-chatgpt/_js_vars.twig');
						$js .= Craft::$app->view->renderTemplate('abm-chatgpt/_scripts.twig');
						if ($js) {
							$view->registerJs($js, View::POS_END);
						}

						if($settings->titleFieldEnabled()) {
							// Load JS for title field
							$js = Craft::$app->view->renderTemplate('abm-chatgpt/_title_field_script.twig',[
								'hash' => StringHelper::UUID()
							]);
							if ($js) {
								$view->registerJs($js, View::POS_END);
							}
						}
					}
				}
			);
		}
	}

	protected function createSettingsModel(): SettingsModel {
		/* plugin settings model */
		return new SettingsModel();
	}

	/**
	 * @return string|null
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @throws \yii\base\Exception
	 */
	protected function settingsHtml(): ?string
	{
		return \Craft::$app->getView()->renderTemplate(
			'abm-chatgpt/settings',
			[ 'settings' => $this->getSettings() ]
		);
	}

	/**
	 * @return string
	 */
	public function getPluginName(): string
	{
		return $this->name;
	}


	/**
	 * @return array|null
	 */
	public function getCpNavItem(): ?array
	{
		$nav = parent::getCpNavItem();

		$nav['label'] = \Craft::t('abm-chatgpt', 'Chat GPT');
		$nav['url'] = 'abm-chatgpt';

		if (Craft::$app->getUser()->getIsAdmin()) {
			$nav['subnav']['abm-chatgpt-prompts'] = [
				'label' => Craft::t('abm-chatgpt', 'Prompts Templates'),
				'url' => 'abm-chatgpt/prompts',
			];
			$nav['subnav']['abm-chatgpt-settings'] = [
				'label' => Craft::t('abm-chatgpt', 'Settings'),
				'url' => 'abm-chatgpt/settings/general',
			];
		}

		return $nav;
	}

	/**
	 * @return mixed
	 */
	public function getSettingsResponse(): mixed
	{
		return Craft::$app->controller->redirect(UrlHelper::cpUrl('abm-chatgpt/settings/general'));
	}
}