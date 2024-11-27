<?php
return [
	// Plugin
	'Fields are not selected in settings. Please select fields in plugin settings under \'Fields\' tab.' => 'Die Felder sind in den Einstellungen nicht ausgewählt. Bitte wählen Sie die Felder in den Plugin-Einstellungen auf der Registerkarte \'Felder\' aus.',
	'API Access Token required.' => 'API-Zugangs-Token erforderlich.',

	// CP - Navigation
	'Chat GPT' => 'Chat GPT',
	'Prompts Templates' => 'Prompts',
	'Settings' => 'Einstellungen',

	// Prompts
	'New Prompt' => 'Neuer Prompt',
	'Prompt Template' => 'Prompt Vorlage',
	'Temperature' => 'Temperature',
	'Enabled' => 'Aktiviert',
	'No prompts exist yet.' => 'Es gibt noch keine Prompt.',
	'temperatureDescription' => 'Welche Sampling-Temperatur soll verwendet werden, zwischen 0 und 2. Höhere Werte wie 0,8 machen die Ausgabe zufälliger, während niedrigere Werte wie 0,2 sie gezielter und deterministischer machen',
	'Number of words to generate' => 'Anzahl der zu erzeugenden Wörter',
	'Approx. fixed number of words' => 'Ungefähr festgelegte Anzahl von Wörtern',
	'Number of words' => 'Anzahl der Wörter',
	'numberOfWordsDescription' => 'Wählen Sie diese Option, wenn Sie eine feste Anzahl von Wörtern erzeugen möchten, unabhängig davon, wie lang der ausgewählte Text ist. Dies ist für bestimmte Arten von Aufforderungen hilfreich, z. B. für die Erstellung eines Absatzes zu einem bestimmten Thema.',
	'Relative to length of text' => 'Relativ zur Länge des Textes',
	'Multiplier'=>'Multiplikator',
	'multiplierDescription' => 'Wählen Sie diese Option, wenn Sie die Länge der generierten Wörter im Verhältnis zur Länge der ausgewählten Wörter berechnen möchten. 1x = gleiche Länge wie der markierte Text, 2x bedeutet das Doppelte, usw. Die Zusammenfassung ist ein guter Kandidat für die Verwendung dieser Option.',
	'Replace the text' => 'Ersetzen Sie den Text',
	'promptReplaceText' => 'Der Text wird automatisch an ChatGPT gesendet und die Antwort wird den Text ersetzen. Wenn diese Option nicht ausgewählt ist, wird die Antwort von ChatGPT angehängt. Sie können dieses Verhalten für jeden Prompt separat ändern, indem Sie diese Option auswählen.',
	'promptTemplateInstructions' => 'Verwenden Sie \[\[text\]\] als Platzhalter, um den Inhalt des Textfeldes an die Stelle einzufügen.',

	// Settings
	'General config {configvar} is set to false.' => 'Allgemeine Konfiguration {configvar} ist auf false gesetzt.',
	'To set any settings, you have to enable this config' => 'Um Einstellungen vornehmen zu können, müssen Sie diese Konfiguration aktivieren',

	// Settings - Navgation
	'General' => 'Allgemein',
	'Fields' => 'Felder',

	// Settings - General
	'OpenAI Preferred Model' => 'OpenAI bevorzugtes Modell',
	'modelDescription' => 'Einige Modelle sind leistungsstärker als andere. Weitere Informationen finden Sie unter https://platform.openai.com/docs/models',
	'API: Access Token' => 'API: Zugangs-Token',
	'Access token for the ChatGPT API' => 'Zugriffstoken für die ChatGPT API',

	// Settings - Fields
	'fieldsDescriptionText' => 'In diesem Abschnitt können Sie auswählen, welche Felder in Ihren Einträgen das Dropdown-Menü anzeigen sollen.',
	'Label' => 'Bezeichnung',
	'Handle' => 'Handle',
	'Field type' => 'Feldtyp',
	'Group'=> 'Gruppe',
	'Show Prompts Menu' => 'Prompt Menü anzeigen',
	'No fields exist yet.' => 'Es gibt noch keine Felder.',
	'Matrix Fields' => 'Matrix Felder',
	'Block' => 'Block',
];