<?php

/**
 * SelectFilterByDropdown : filter a dropdown question type by another dropdown
 *
 * @author Denis Chenu <denis@sondages.pro>
 * @copyright 2016 Advantages <https://www.advantages.fr/>
 * @copyright 2016 Denis Chenu <http://www.sondages.pro>

 * @license GPL v3
 * @version 0.1.0
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
class SelectFilterByDropdown extends PluginBase
{
    protected $storage = 'DbStorage';

    protected static $name = 'SelectFilterByDropdown';
    protected static $description = 'Allow to filter a dropdown question type by another dropdown';


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
            $filterBy = $aAttributes['SelectFilterByDropdown'];
            if (empty($filterBy) && substr($aAttributes['cssclass'], 0, 6) === "filter") {
                $filterBy = substr($aAttributes['cssclass'], 6);
            }
            if (empty($filterBy)) {
                return;
            }
            $oQuestion = Question::model()->find(
                "title = :title and sid = :sid and type = :type",
                [':title' => $filterBy, ':sid' => $oEvent->get('surveyId'), ':type' => '!']
            );
            if (empty($oQuestion)) {
                $this->log("Unable to find question " . $filterBy . " in survey " . $oEvent->get('surveyId') . " for question " . $oEvent->get('qid'), \CLogger::LEVEL_WARNING);
                tracevar("Unable to find question " . $filterBy . " in survey " . $oEvent->get('surveyId') . " for question " . $oEvent->get('qid'));
                return;
            }
            $this->registerSelectFilterPackage();
            $script = "selectFilter_selectFilterByCode(" . $oEvent->get('qid') .", " . $oQuestion->qid .");\n";
            Yii::app()->clientScript->registerScript("SelectFilterByDropdown{$oEvent->get('qid')}", $script, CClientScript::POS_END);
        }
    }

    /**
     * The attribute, use readonly for 3.X version
     */
    public function newQuestionAttributes()
    {
        $selectAttribute = [
            'SelectFilterByDropdown' => [
                'name' => 'SelectFilterByDropdown',
                'types' => '!',
                'category' => 'Display',
                'sortorder' => 500, /* at end */
                'inputtype' => 'text',
                'default' => '',
                'expression' => 0,/* As static */
                'help' => $this->gT("Put the code of another dropdown question in same page."),
                'caption' => $this->gT('Filter by'),
            ]
        ];
        $this->getEvent()->append('questionAttributes', $selectAttribute);
    }

    /**
    * Function to register the packahe if noit exist
    */
    private function registerSelectFilterPackage()
    {
        if (!Yii::app()->clientScript->hasPackage('selectfilterbydropdown')) {
            Yii::setPathOfAlias('selectfilterbydropdown', dirname(__FILE__));
            Yii::app()->clientScript->addPackage('selectfilterbydropdown', array(
                'basePath'    => 'selectfilterbydropdown.assets',
                'js'          => [
                    'selectfilter.js'
                ],
                'depends'     => ['jquery']
            ));
        }
        App()->getClientScript()->registerPackage('selectfilterbydropdown');
    }
}
