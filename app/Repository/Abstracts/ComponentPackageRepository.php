<?php

namespace App\Repository\Abstracts;

abstract class ComponentPackageRepository extends ModelRepository
{
    /**
     * @param bool $accommodation Should accommodation be included
     * @param bool $activities Should activities be included
     * @param bool $flights Should flights be included
     * @param bool $transport Should transport be included
     * @param bool $extras Should merchandise/extras be included
     * @param array $filter Filter for component types
     * @return InventoryTourRepository[]
     */
    public abstract function getComponents(bool $accommodation = true, bool $activities = true, bool $flights = true, bool $transport = true, bool $extras = true, array $filter = ['Included', 'Add-on', 'Upgrade']): array;
}
