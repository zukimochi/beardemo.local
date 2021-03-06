<?php
/**
 * App
 *
 * @category   BEAR
 * @package    bear.demo
 * @subpackage Form
 * @author     $Author:$ <username@example.com>
 * @license    @license@ http://@license_url@
 * @version    Release: @package_version@ $Id:$
 * @link       http://@link_url@
 */

/**
 * Multi form
 *
 * 確認画面つきフォーム
 *
 * 表示、確認、修正の３つの状態のある確認画面つきフォームです。
 *
 * @category   BEAR
 * @package    bear.demo
 * @subpackage Form
 * @author     $Author:$ <username@example.com>
 * @license    @license@ http://@license_url@
 * @version    Release: @package_version@ $Id:$
 * @link       http://@link_url@
 */
class App_Form_Preview extends BEAR_Base
{

    /**
     * テンプレート
     *
     * @var string
     */
    private static $_elementTemplateFreeze = "\n\t\t<li><label class=\"element\"><!-- BEGIN required --><span class=\"required\">*</span><!-- END required -->{label}</label><div class=\"element<!-- BEGIN error -->_error<!-- END error -->\">{element}<!-- BEGIN error --><span class=\"form-element-error\" alt=\"!\"><img src=\"/image/warning.gif\"><!-- END error --></div></li>";

    /**
     * フォーム
     *
     * @var array
     */
    private $_form = array('formName' => 'form');

    /**
     * アトリビュート
     *
     * @var array
     */
    private $_attr = array('name' => 'size="30" maxlength="30"',
        'email' => 'size="30" maxlength="30"', 'body' => 'rows="8" cols="40"'
    );

    /**
     * セレクトボックスセパレータ （フリーズしたときに変更
     *
     * @var mixed
     */
    private $_separator = array('&nbsp;', '<br />');

    /*
     * デフォルト
     *
     * @var mixed
     */
    private $_defaults = null;

    /**
     * Inject（最初の表示）
     *
     * @return void
     */
    public function onInject()
    {
    }

    /**
     * Inject（最初の表示）
     *
     * @return void
     */
    public function onInjectMobile()
    {
        $this->_attr = array(
            'name' => 'size="12" maxlength="30"',
            'email' => 'size="12" maxlength="30"',
            'body' => 'rows="8" cols="20"'
        );
    }

    /**
     * Inject　- フリーズ
     *
     * @return void
     */
    private function _injectPreview()
    {
        $this->_form = array('formName' => 'form', 'callback' => array(__CLASS__, 'onRenderFreeze'));
        $this->_separator = '&nbsp;';
    }


    /**
     * Inject - 修正
     *
     * @return void
     */
    private function _injectModify()
    {
        $this->_defaults = $_POST;
    }

    /**
     * フォームの状態をセット
     *
     * フォームの状態を通常、確認（フリーズ）、修正にセットします
     *
     * @param string $formMode
     *
     * @return void
     */
    private function _injectFormMode($formMode)
    {
        switch ($formMode) {
            case 'default':
                break;
            case 'preview':
                $this->_separator = '&nbsp;';
                $this->_injectPreview();
                break;
            case 'modify':
                $this->_defaults = $_POST;
                $this->_injectModify();
                break;
            default:
                break;
        }
    }

    /**
     * build form
     *
     * @param string $formMode
     *
     * @return void
     */
    public function build($formMode)
    {
        $this->_injectFormMode($formMode);
        $this->_form = BEAR::factory('BEAR_Form', $this->_form);
        // デフォルト
        if ($this->_defaults) {
            $this->_form->setDefaults($this->_defaults);
        }
        // ヘッダー
        $this->_form->addElement('header', 'main', 'Preview Form');
        // フィールド
        $this->_form->addElement('text', 'name', '名前', $this->_attr['name']);
        $this->_form->addElement('text', 'email', 'メールアドレス', $this->_attr['email']);
        $this->_form->addElement('textarea', 'body', '本文', $this->_attr['body']);
        // Creates a checkboxes group using an array of separators
        $checkbox[] = HTML_QuickForm::createElement('bcheckbox', 'travel', null, '旅行');
        $checkbox[] = HTML_QuickForm::createElement('bcheckbox', 'photo', null, '写真');
        $checkbox[] = HTML_QuickForm::createElement('bcheckbox', 'music', null, '音楽');
        $checkbox[] = HTML_QuickForm::createElement('bcheckbox', 'movie', null, '映画');
        $this->_form->addGroup(
            $checkbox, 'hobby', array('趣味:', '最低２つ入力してください'),
            $this->_separator
        );
        // ラジオボタン
        $radio[] = HTML_QuickForm::createElement('bradio', null, null, 'Yes', 'y');
        $radio[] = HTML_QuickForm::createElement('bradio', null, null, 'No', 'n');
        $this->_form->addGroup($radio, 'yesorno', 'Yes/No:');
        // フィルタと検証ルール
        $this->_form->applyFilter('__ALL__', 'trim');
        $this->_form->applyFilter('__ALL__', 'strip_tags');
        $this->_form->addRule('name', '名前を入力してください', 'required', null);
        $this->_form->addRule('email', 'emailを入力してください', 'required', null);
        $this->_form->addRule('email', 'emailの形式で入力してください', 'email', null);
        //         グループルール
        $this->_form->addGroupRule('hobby', '趣味を最低２つ入力してください', 'required', null, 2);
    }

    /**
     * 最初の画面のボタン
     *
     * @return void
     */
    public function buildConfirmButton()
    {
        $this->_form->addElement('submit', '_freeze', '確認...', '');
    }

    /**
     * 確認画面のボタン
     *
     * @return void
     */
    public function buildSendButton()
    {
        $buttons = array();
        $buttons[] = $this->_form->createElement('submit', '_action', '送信', '');
        $buttons[] = $this->_form->createElement('submit', '_modify', '修正', '');
        $this->_form->addGroup($buttons);
        $this->_form->freeze();
    }


    /**
     * カスタムテンプレート
     *
     * @param HTML_QuickForm_Renderer_Tableless $render
     *
     * @return void
     */
    public static function onRenderFreeze(HTML_QuickForm_Renderer_Tableless $render)
    {
        $render->setElementTemplate(self::$_elementTemplateFreeze);
    }
}