<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace pheme\i18n;

use yii\web\UrlManager;
use Yii;

/**
 * @author Aris Karageorgos <aris@phe.me>
 */
class I18nUrlManager extends UrlManager
{

    /**
     * @var array Supported languages
     */
    public $languages;

    /**
     * @var bool Whether to display the source app language in the URL
     */
    public $displaySourceLanguage = false;

    /**
     * @var string Parameter used to set the language
     */
    public $languageParam = 'lang';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (is_callable($this->languages)) {
            $this->languages = call_user_func($this->languages);
        }
        if (empty($this->languages)) {
            $this->languages = [Yii::$app->language];
        }
        parent::init();
    }

    /**
     * Parses the URL and sets the language accordingly
     * @param \yii\web\Request $request
     * @return array|bool
     */
    public function parseRequest($request)
    {
        if ($this->enablePrettyUrl) {
            $pathInfo = $request->getPathInfo();
            $language = explode('/', $pathInfo)[0];
            if (in_array($language, $this->languages)) {
                $request->setPathInfo(substr_replace($pathInfo, '', 0, (strlen($language) + 1)));
                Yii::$app->language = $language;
            }
        } else {
            $params = $request->getQueryParams();
            $route = isset($params[$this->routeParam]) ? $params[$this->routeParam] : '';
            if (is_array($route)) {
                $route = '';
            }
            $language = explode('/', $route)[0];
            if (in_array($language, $this->languages)) {
                $route = substr_replace($route, '', 0, (strlen($language) + 1));
                $params[$this->routeParam] = $route;
                $request->setQueryParams($params);
                Yii::$app->language = $language;
            }
        }
        return parent::parseRequest($request);
    }

    /**
     * Adds language functionality to URL creation
     * @param array|string $params
     * @return string
     */
    public function createUrl($params)
    {
        if (array_key_exists($this->languageParam, $params)) {
            $lang = $params[$this->languageParam];
            if (($lang !== Yii::$app->sourceLanguage || $this->displaySourceLanguage) && !empty($lang)) {
                $params[0] = $lang . '/' . ltrim($params[0], '/');
            }
            unset($params[$this->languageParam]);
        } else {
            if (Yii::$app->language !== Yii::$app->sourceLanguage || $this->displaySourceLanguage) {
                $params[0] = Yii::$app->language . '/' . ltrim($params[0], '/');
            }
        }
        return parent::createUrl($params);
    }

}
