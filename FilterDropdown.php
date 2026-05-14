<?php

/**
 * FilterDropdown : Filter a dropdown question by a value or expression against the response code or label
 *
 * @author Adam Zammit <adam.zammit@acspri.org.au>
 * @author Denis Chenu <denis@sondages.pro>
 * @copyright 2016 Advantages <https://www.advantages.fr/>
 * @copyright 2016 Denis Chenu <http://www.sondages.pro>
 * @copyright 2026 ACSPRI <https://www.acspri.org.au>

 * @license GPL v3
 * @version 1.0.0
 *
 * Based on: SelectFilterByDropdown: https://gitlab.com/SondagesPro/QuestionSettingsType/SelectFilterByDropdown
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
class FilterDropdown extends PluginBase
{
    protected $storage = 'DbStorage';

    protected static $name = 'FilterDropdown';
    protected static $description = 'Filter a dropdown question by a value or expression against the response code or label';


    public function init()
    {
        $this->subscribe('newQuestionAttributes');
        $this->subscribe('beforeQuestionRender');
    }

    public function beforeQuestionRender()
    {
        $oEvent = $this->getEvent();
        $sType = $oEvent->get('type');
        if ($sType == "!") {
            $aAttributes = QuestionAttribute::model()->getQuestionAttributes($oEvent->get('qid'));
            $filterByCode = LimeExpressionManager::ProcessString($aAttributes['FilterDropdownByCode'], null, array(), 3, 1, false, false, true);
            $filterByLabel = LimeExpressionManager::ProcessString($aAttributes['FilterDropdownByLabel'], null, array(), 3, 1, false, false, true);
            $answerstring = $oEvent->get('answers'); //answers in HTML
            $doc = new DOMDocument();
            $doc->loadHTML($answerstring);
            $options =  $doc->getElementsByTagName('option');
            $count = 0;
            $remove = [];
            foreach ($options as $option) {
                $count++;
                $label = trim($option->nodeValue);
                $code = trim($option->getAttribute('value'));
                if (!empty($code)) { //dont remove no answer
                    if (!((!empty($filterByCode) && str_starts_with($code,$filterByCode)) ||
                            (!empty($filterByLabel) && str_starts_with($label,$filterByLabel)))) {
                        $remove[] = $option;
                    }
                }
            }
            if ((count($remove) - 1) < $count) { //if will remove all - dont do it
                foreach ($remove as $item) {
                    $item->parentNode->removeChild($item);
                }
            }
            $oEvent->set('answers',$doc->saveHTML());
        }

    }


    /**
     * Add new question attributes
     */
    public function newQuestionAttributes()
    {
        $selectAttribute = [
            'FilterDropdownByCode' => [
                'name' => 'FilterDropdownByCode',
                'types' => '!',
                'category' => 'Display',
                'sortorder' => 500, /* at end */
                'inputtype' => 'text',
                'default' => '',
                'expression' => 1,
                'help' => $this->gT("Dropdown responses will be filtered by codes starting with what appears in this field. You can use expressions. If referring to a previous question it MUST be on a previous page"),
                'caption' => $this->gT('Filter by code starting with'),
            ],
            'FilterDropdownByLabel' => [
                'name' => 'FilterDropdownByLabel',
                'types' => '!',
                'category' => 'Display',
                'sortorder' => 500, /* at end */
                'inputtype' => 'text',
                'default' => '',
                'expression' => 1,
                'help' => $this->gT("Dropdown responses will be filtered by labels starting with what appears in this field. You can use expressions. If referring to a prevvious question it MUST be on a previous page"),
                'caption' => $this->gT('Filter by label starting with'),
            ]
 
        ];
        $this->getEvent()->append('questionAttributes', $selectAttribute);
    }

}
