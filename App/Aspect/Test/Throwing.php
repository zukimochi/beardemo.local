<?php
/**
 * App
 *
 * @category   BEAR
 * @package    bear.demo
 * @subpackage Aspect
 * @author     $Author:$ <username@example.com>
 * @license    @license@ http://@license_url@
 * @version    Release: @package_version@ $Id:$
 * @link       http://@link_url@
 */

/**
 * Advice
 *
 * @category   BEAR
 * @package    bear.demo
 * @subpackage Aspect
 * @author     $Author:$ <username@example.com>
 * @license    @license@ http://@license_url@
 * @version    Release: @package_version@ $Id:$
 * @link       http://@link_url@
 */
class App_Aspect_Test_Throwing implements BEAR_Aspect_Throwing_Interface
{

    /**
     * Throwingアドバイス
     *
     * Afterアドバイスの一種でエラーまたは例外の場合に呼ばれます。
     *
     * @param array                 $result
     * @param BEAR_Aspect_JoinPoint $joinPoint
     *
     * @return array
     */
    public function throwing($result, BEAR_Aspect_JoinPoint $joinPoint)
    {
        $result = array('is_error' => true);
        return $result;
    }
}