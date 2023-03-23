<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Fr\T3faq\Domain\Model\Dto\Factory;

use Fr\T3faq\Domain\Model\Dto\DemandInterface;

abstract class AbstractDemandFromSettings implements DemandFromSettingsFactoryInterface
{
    protected function decorate(array $settings, DemandInterface $demand): void
    {
        if (!empty($settings) && !empty(static::PROPERTY_DECORATOR_MAP)) {
            foreach (static::PROPERTY_DECORATOR_MAP as $decoratorMap) {
                if (class_exists($decoratorMap['decorator'])) {

                    $decorator = new $decoratorMap['decorator'](
                        $demand,
                        $this->resolveSettingValueMap($decoratorMap['settings'], $settings)
                    );
                    $demand = $decorator->decorate();
                }
            }
        }
    }

    protected function resolveSettingValueMap(array $settingsMap, array $settings): array
    {
        $resolvedSettings = [];
        foreach ($settingsMap as $key => $value) {
            if (isset($settings[$value])) {
                $resolvedSettings[$key] = $settings[$value];
            } else {
                $resolvedSettings[$key] = null;
            }
        }
        return $resolvedSettings;
    }
}
