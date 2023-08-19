<?php

namespace App\Services\Language;

class LanguageHelper
{
    public static function getName($alias): ?string
    {
        $name = collect(file(app_path('Services/Language/aliases.php')))->get($alias);
        if ($name === null) {
            // TODO: Email Webmaster missing alias
            // TODO: Notify User that this lab couldn't be formatted, but still give them the row
            return null;
        }

        return $name;
    }
}
