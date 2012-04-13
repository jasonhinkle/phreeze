<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: modifier.lcfirst.php 25202 2010-02-14 18:16:23Z changi67 $

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     lcfirst
 * Purpose:  lowercase the initial character in a string
 * -------------------------------------------------------------
 */
function smarty_modifier_lcfirst( $s ) { return strtolower( $s{0} ). substr( $s, 1 ); }