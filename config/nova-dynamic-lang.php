<?php

return [

	/**
	 * We will create a file in your language files to save your fields.
	 * So we need a file name.
	 *
	 * Note : It will be used as a 'frontend' even if you set the file name to null by default
	 */
	'file_name' => 'frontend',

	/**
	 * If you want to use your languages then set path here.
	 * By default available languages will be used.
	 */
	'default_languages_path' => null,

	/**
	 * To save flag file to storage disk
	 */
	'disk' => 'public',

	/**
	 * You may define defaults units to save language file
	 */
	'sections' => [

		/**
		 * Default store
		 *
		 * Supported: "array", "json", "database"
		 */
		'default' => 'array',

		/**
		 * You may choose from existing stores
		 */
		'stores' => [

			/**
			 * Define your fields quickly with simple rules
			 *
			 * RULES :
			 *
			 * First Index => Field Label  | For HTML input label
			 * Second Index => Field Value  | For other lang
			 * Third Index =>  Field Short Code | For to invoke in your app. Example : __('frontend.title')
			 *
			 * Example :
			 *
			 * fields => [
			 *    ["Title","Title","title"],
			 *    ["Description","Description","description"]
			 * ]
			 */
			'array' => [
				'driver' => 'array',
				'fields' => [],
			],

			/**
			 * Define your fields with json file if your json file is exists.
			 *
			 * RULES :
			 *
			 * "fields" key is required.
			 *
			 * First Index => Field Label  | For HTML input label
			 * Second Index => Field Value  | For other lang
			 * Third Index =>  Field Short Code | For to invoke in your app. Example : __('frontend.title')
			 *
			 * Example :
			 *
			 *  {
			 *      "fields":[
			 *          ["Title","Title","title"],
			 *          ["Description","Description","description"]
			 *      ]
			 *  }
			 */
			'json' => [
				'driver' => 'json',
				'path' => null,
			],

			/**
			 * Define your fields with database.
			 *
			 * You may create a resource with your specific model if you want.
			 * Only should be instance of LanguageSection model.
			 *
			 * Note : You shouldn't change the table columns in migration class if you will this case.
			 */
			'database' => [
				'driver' => 'database',
				'model' => \Ysfkaya\NovaDynamicLang\Models\LanguageSection::class,
			],
		],
	],
];