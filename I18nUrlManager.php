<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace pheme\i18n;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UrlManager;

/**
 * @author Aris Karageorgos <aris@phe.me>
 */
class I18nUrlManager extends UrlManager
{

    public static $currentLanguage;
    /**
     * @var array Supported languages
     */
    public $languages;

    /**
     * @var array Language aliases
     */
    public $aliases = [];

    /**
     * @var bool Whether to display the source app language in the URL
     */
    public $displaySourceLanguage = false;

    /**
     * @var string Parameter used to set the language
     */
    public $languageParam = 'lang';
    
    /**
     * @var bool Whether to rewrite the baseUrl in th URL
     */
    public $rewriteBaseUrl = false;

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
            $locale = ArrayHelper::getValue($this->aliases, $language, $language);
            if (in_array($language, $this->languages)) {
                $request->setPathInfo(substr_replace($pathInfo, '', 0, (strlen($language) + 1)));
                Yii::$app->language = $locale;
                static::$currentLanguage = $language;
            }
        } else {
            $params = $request->getQueryParams();
            $route = isset($params[$this->routeParam]) ? $params[$this->routeParam] : '';
            if (is_array($route)) {
                $route = '';
            }
            $language = explode('/', $route)[0];
            $locale = ArrayHelper::getValue($this->aliases, $language, $language);
            if (in_array($language, $this->languages)) {
                $route = substr_replace($route, '', 0, (strlen($language) + 1));
                $params[$this->routeParam] = $route;
                $request->setQueryParams($params);
                Yii::$app->language = $locale;
                static::$currentLanguage = $language;
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
        $params = (array)$params;

        $lang = ArrayHelper::remove($params, $this->languageParam, static::$currentLanguage);
        $sourceLanguage = Yii::$app->sourceLanguage;
        $locale = ($lang) ? ArrayHelper::getValue($this->aliases, $lang, $lang) : $sourceLanguage;

        if (($lang !== $sourceLanguage && $locale !== $sourceLanguage) || $this->displaySourceLanguage) {
            if ($this->rewriteBaseUrl) {
                return rtrim(Yii::$app->getHomeUrl(), '/').DIRECTORY_SEPARATOR
                    .$lang.DIRECTORY_SEPARATOR
                    .ltrim(parent::createUrl($params), Yii::$app->getHomeUrl().'/');
            }
            return '/' . $lang . '/' . ltrim(parent::createUrl($params), '/');
        }

        return parent::createUrl($params);
    }
}
