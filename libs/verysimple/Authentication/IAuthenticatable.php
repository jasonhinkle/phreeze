<?php
/** @package    verysimple::Authentication */

/**
 * Classes implementing IAuthenticatable can be used with Authenticator for checking permissions
 * @package    verysimple::Authentication
 * @author     VerySimple Inc.
 * @copyright  1997-2007 VerySimple, Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version    1.0
 */
interface IAuthenticatable
{
	public function IsAnonymous();
	public function IsAuthorized($permission);
	public function Login($username,$password);
}

?>