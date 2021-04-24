<?php

namespace App\Http\Controllers;

use File;
use Exception;
use App\Translation;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    use CommonController;

    // define common variables
    public $module_config;

    public function __construct()
    {
        $this->module_config = [
            'parent_foreign_map' => [
                'oc_language' => [
                    'foreign_key' => 'locale',
                    'local_key' => 'locale',
                    'fetch_field' => 'oc_language.name as language'
                ]
            ],
        ];
    }

    // define what should process before save
    public function beforeSave($request)
    {
        if ($request->filled('locale') && $request->filled('from') && $request->filled('to')) {
            $locale = trim($request->get('locale'));
            $from = trim($request->get('from'));
            $to = trim($request->get('to'));

            $transData = $this->openTranslationFile($locale);

            if ($request->filled('id')) {
                $existing = Translation::select('id', 'from')
                    ->where('id', trim($request->get('id')))
                    ->first();

                if (isset($transData[$existing->from]) || isset($transData[$from])) {
                    if ($from != $existing->from) {
                        unset($transData[$existing->from]);
                    }
                }
            }

            $transData[$from] = $to;
            $saved = $this->saveTranslationFile($locale, $transData);

            if (!$saved) {
                throw new Exception(__('Cannot save translation file. Please try again'));
            }
        } else {
            throw new Exception(__('Please provide Language, From & To'));
        }
    }

    // put all functions to be performed after delete
    public function afterDelete($data)
    {
        $table = $this->getModuleTable('Translation');
        $from = $data[$table]['from'];
        $locale = $data[$table]['locale'];

        $transData = $this->openTranslationFile($locale);

        if ($transData) {
            if (isset($transData[$from])) {
                unset($transData[$from]);

                if ($transData && count($transData)) {
                    $saved = $this->saveTranslationFile($locale, $transData);
                } else {
                    try {
                        $saved = File::delete(resource_path('lang/' . $locale . '.json'));
                    } catch (Exception $e) {
                        $saved = false;
                    }
                }

                if (!$saved) {
                    $msg = __('Cannot delete translation from file. Please delete') . ' ' . $from . ' ' . __('record manually from translation file');
                    throw new Exception($msg);
                }
            }
        }
    }

    public function openTranslationFile($locale)
    {
        $jsonString = [];

        if (File::exists(resource_path('lang/' . $locale . '.json'))) {
            $jsonString = File::get(resource_path('lang/' . $locale . '.json'));
            $jsonString = json_decode($jsonString, true);
        }

        return $jsonString;
    }

    public function saveTranslationFile($locale, $data)
    {
        ksort($data);
        $jsonData = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        try {
            $saved = File::put(resource_path('lang/' . $locale . '.json'), $jsonData);
        } catch(Exception $e) {
            $saved = false;
        }

        return $saved;
    }
}
