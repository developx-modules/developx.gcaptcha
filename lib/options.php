<?

namespace Developx\Gcaptcha;

/**
 * Class Options
 */
class Options
{
    protected static $_instance;
    const MODULE_NAME = 'developx.gcaptcha';
    const CACHE_TIME = 36000000;
    const CAPTCHA_ACTION = 'developxCaptcha';

    /** @var array */
    public $arOptions = [
        'CAPTCHA_ACTIVE' => [
            'TYPE' => 'checkbox',
            'DEFAULT' => 'N'
        ],
        'CAPTCHA_KEY' => [
            'TYPE' => 'text',
            'SIZE' => 50
        ],
        'CAPTCHA_SECRET' => [
            'TYPE' => 'text',
            'SIZE' => 50
        ],
        'CAPTCHA_SENS' => [
            'TYPE' => 'select',
            'DEFAULT' => 0.5,
            'VALUES' => [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9]
        ],
        'CAPTCHA_FAILS_LOG' => [
            'TYPE' => 'checkbox',
            'DEFAULT' => 'Y'
        ],
        'INCLUDE_JQUERY' => [
            'TYPE' => 'checkbox',
            'DEFAULT' => 'Y'
        ],
        'CAPTCHA_SUCCESS_COUNT' => [
            'TYPE' => 'info',
            'DEFAULT' => 0
        ],
        'CAPTCHA_FAIL_COUNT' => [
            'TYPE' => 'info',
            'DEFAULT' => 0
        ]
    ];

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $obCache = new \CPHPCache();
        if ($obCache->InitCache(self::CACHE_TIME, $this->getCachePatch(), '/')) {
            $this->arOptions = $obCache->GetVars();
        } elseif ($obCache->StartDataCache()) {
            foreach ($this->arOptions as $code => $prop) {
                $this->arOptions[$code]['VALUE'] = \COption::GetOptionString(self::MODULE_NAME, $code);
                if (empty($this->arOptions[$code]['VALUE']) && isset($prop['DEFAULT'])) {
                    $this->arOptions[$code]['VALUE'] = $prop['DEFAULT'];
                }
            }
            $obCache->EndDataCache($this->arOptions);
        }
    }

    /**
     * @return string
     */
    private function getCachePatch()
    {
        return 'cache' . self::MODULE_NAME . 'options';
    }

    public function clearCache()
    {
        $cache = new \CPHPCache();
        $cache->Clean($this->getCachePatch(), '/');
    }

    public function setOption($code, $value)
    {
        if ($this->arOptions[$code]['TYPE'] == 'checkbox' && empty($value)) {
            $value = 'N';
        }
        \COption::SetOptionString(self::MODULE_NAME, $code, $value);
    }

    /**
     * @return array
     **/
    public function getOptions()
    {
        $arOptions = [];
        foreach ($this->arOptions as $code => $option) {
            $arOptions[$code] = $option['VALUE'];
        }
        return $arOptions;
    }

    /**
     * @return boolean
     **/
    public function checkCaptchaActive()
    {
        $options = $this->getOptions();
        if (
            $options['CAPTCHA_ACTIVE'] == 'Y' &&
            !empty($options['CAPTCHA_KEY']) &&
            !empty($options['CAPTCHA_SECRET']) &&
            !empty($options['CAPTCHA_SENS'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $code
     * @param string $title
     * @return string
     */
    public function showHtmlOption($code, $title)
    {
        $params = $this->arOptions[$code];
        switch ($params['TYPE']) {
            case 'checkbox':
                $option = '<input type="checkbox" 
                    name="' . $code . '"
                    value="Y" ' . ($params['VALUE'] == "Y" ? "checked" : "") . '>';
                break;
            case 'text':
                $option = '<input type="text" 
                    size="' . $params['SIZE'] . '" 
                    maxlength="255" 
                    value="' . $params['VALUE'] . '" 
                    name="' . $code . '"> ';
                break;
            case 'select':
                $option = '<select name="' . $code . '">';
                foreach ($params['VALUES'] as $value) {
                    $option .= '<option ' . ($value == $params['VALUE'] ? "selected" : "") . ' value="' . $value . '">' . $value . '</option>';
                }
                $option .= '</select>';
                break;
            case 'info':
                $option = '<span>' . $params['VALUE'] . '</span>';
                break;
        }
        $result = '<tr>
                    <td width="50%">' . $title . '</td>
                    <td width="50%">' . $option . '</td>
                </tr>';
        return $result;
    }

    /**
     * @return string
     */
    public function getCaptchaAction()
    {
        return self::CAPTCHA_ACTION;
    }
}