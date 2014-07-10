<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace pheme\i18n\widgets;

use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * @author Aris Karageorgos <aris@phe.me>
 */
class LanguageSwitcher extends ButtonDropdown
{
    /**
     * @var string drop down button label
     */
    public $label = 'Language';

    /**
     * Renders the language drop down if there are currently more than one languages in the app.
     * If you pass an associative array of language names along with their code to the URL manager
     * those language names will be displayed in the drop down instead of their codes.
     */
    public function run()
    {
        $languages = isset(Yii::$app->getUrlManager()->languages) ? Yii::$app->getUrlManager()->languages : [];
        if (count($languages) > 1) {
            $items = [];
            $currentUrl = preg_replace('/' . Yii::$app->language . '\//', '', Yii::$app->getRequest()->getUrl(), 1);
            $isAssociative = ArrayHelper::isAssociative($languages);
            foreach ($languages as $language => $code) {
                $url = $code . $currentUrl;
                if ($isAssociative) {
                    $item = ['label' => $language, 'url' => $url];
                } else {
                    $item = ['label' => $code, 'url' => $url];
                }
                if ($code === Yii::$app->language) {
                    $item['options']['class'] = 'disabled';
                }
                $items[] = $item;
            }
            $this->dropdown['items'] = $items;
            parent::run();
        }
    }
}
