<?php
return [
	// Plugin
	'Fields are not selected in settings. Please select fields in plugin settings under \'Fields\' tab.' => 'Fields are not selected in settings. Please select fields in plugin settings under \'Fields\' tab.',
	'API Access Token required.' => 'API Access Token required.',

	// CP - Navigation
	'Chat GPT' => 'Chat GPT',
	'Prompts Templates' => 'Prompts',
	'Settings' => 'Settings',

	// Prompts
	'New Prompt' => 'New Prompt',
	'Prompt Template' => 'Prompt Template',
	'Temperature' => 'Temperature',
	'Enabled' => 'Enabled',
	'No prompts exist yet.' => 'No prompts exist yet.',
	'temperatureDescription' => 'What sampling temperature to use, between 0 and 2. Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic',
	'Number of words to generate' => 'Number of words to generate',
	'Approx. fixed number of words' => 'Approx. fixed number of words',
	'Number of words' => 'Number of words',
	'numberOfWordsDescription' => 'Choose this option if you want to generate a fixed number of words, regardless of how long the selected text is. This is helpful for certain types of prompts, like generating a paragraph on a certain topic for example.',
	'Relative to length of text' => 'Relative to length of text',
	'Multiplier'=>'Multiplier',
	'multiplierDescription' => 'Choose this option if you want to calculate the length of the generated words relative to the length of words selected. 1x = same length as select text, 2x means two times, etc. Summarization is a good candidate to use this option for.',
	'Replace the text' => 'Replace the text',
	'promptReplaceText' => 'The text will be automatically sent to ChatGPT and the response will replace the text. When not selected, the response from ChatGPT will be appended. You can modify this behavior for each prompt separately by selecting this option.',
	'promptTemplateInstructions' => 'Use \[\[text\]\] as a placeholder to insert the content of the text field at the position.',

	// Settings
	'General config {configvar} is set to false.' => 'General config {configvar} is set to false.',
	'To set any settings, you have to enable this config' => 'To set any settings, you have to enable this config',

	// Settings - Navgation
	'General' => 'General',
	'Fields' => 'Fields',

	// Settings - General
	'OpenAI Preferred Model' => 'OpenAI Preferred Model',
	'modelDescription' => 'Some models are more capable than others. For more information, see https://platform.openai.com/docs/models',
	'API: Access Token' => 'API: Access Token',
	'Access token for the ChatGPT API' => 'Access token for the ChatGPT API',

	// Settings - Fields
	'fieldsDescriptionText' => 'In this section, you have the ability to choose which fields in your entries will show the dropdown menu.',
	'Label' => 'Label',
	'Handle' => 'Handle',
	'Field type' => 'Field type',
	'Group'=> 'Group',
	'Show Prompts Menu' => 'Show Prompts Menu',
	'No fields exist yet.' => 'No fields exist yet.',
	'Matrix Fields' => 'Matrix Fields',
	'Block' => 'Block',
];