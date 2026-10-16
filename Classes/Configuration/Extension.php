<?php

declare(strict_types=1);

namespace Cpsit\T3faq\Configuration;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Cpsit\T3faq\Domain\Model\Question;

/**
 * Extension
 *
 * provides configuration for the extension t3faq
 */
final class Extension
{
    public const NAME = 'T3faq';
    public const KEY = 't3faq';
    public const VENDOR_NAME = 'Fr';

    public const TABLES_ALLOWED_ON_STANDARD_PAGES = [
        Question::TABLE_NAME,
    ];
}
