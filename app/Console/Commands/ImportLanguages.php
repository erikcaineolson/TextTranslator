<?php

namespace App\Console\Commands;

use App\Language;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Console\Command;

class ImportLanguages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'languages:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the currently supported languages from Google Translate.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $translate = new TranslateClient([
            'key'     => getenv('GOOGLE_CLOUD_API_KEY'),
            'keyFile' => getenv('GOOGLE_APPLICATION_CREDENTIALS'),
        ]);

        $languageJson = $translate->localizedLanguages();

        $languages = json_decode(json_encode($languageJson));

        // if the language already exists in the system, just update it
        foreach ($languages as $language) {
            Language::updateOrCreate([
                'code'    => $language->code,
                'moniker' => $language->name,
            ]);
        }

        return 0;
    }
}
