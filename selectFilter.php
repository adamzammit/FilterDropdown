<?php
/**
 * selectFilter : filter a dropdown question type by another dropdown
 *
 * @author Denis Chenu <denis@sondages.pro>
 * @copyright 2016 Advantages <https://www.advantages.fr/>
 * @copyright 2016 Denis Chenu <http://www.sondages.pro>

 * @license GPL v3
 * @version 0.0.1
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
class selectFilter  extends \ls\pluginmanager\PluginBase {
    protected $storage = 'DbStorage';

    static protected $name = 'selectFilter';
    static protected $description = 'Allow to filter a dropdown question type by another dropdown';


    public function init()
    {
        $this->subscribe('beforeQuestionRender');
    }

    public function beforeQuestionRender()
    {
        $oEvent=$this->getEvent();
        $sType=$oEvent->get('type');
        if($sType=="L")
        {

        }
    }
}
