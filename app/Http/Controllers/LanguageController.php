<?php

namespace App\Http\Controllers;

use Exception;
use App\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    // define what should process before save
    public function beforeSave($request)
    {
        $valid_locale = true;

        if ($request->filled('id')) {
            $request->request->remove('locale');
        } else {
            $valid_locale = false;

            if ($request->filled('locale')) {
                $locale = trim($request->get('locale'));

                if (strlen($locale) == 2) {
                    $valid_locale = true;
                }
            }

            if (!$valid_locale) {
                throw new Exception(__('Please enter two-letter ISO 639-1 code'));
            }
        }
    }

    // define what should process before delete
    public function beforeDelete($id)
    {
        $language = Language::select('id', 'name', 'locale')
            ->where('id', $id)
            ->first();

        if ($language) {
            if ($language->locale == 'en') {
                throw new Exception(__('Cannot delete system default locale: en'));
            }
        } else {
            throw new Exception(__('No such language found'));
        }
    }
}
