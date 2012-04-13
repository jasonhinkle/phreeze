<?php
/** @package    verysimple::Encryption::GPG */

/** require supporting files */
require_once("Cipher.php");

/**
 * @package    verysimple::Encryption::GPG
 */
class GPG_AES
{
	static function encrypt($block, $ctx)
	{
		$RCON = GPG_Cipher::$RCON;
		$S = GPG_Cipher::$S;
		
		$T1 = GPG_Cipher::$T1;
		$T2 = GPG_Cipher::$T2;
		$T3 = GPG_Cipher::$T3;
		$T4 = GPG_Cipher::$T4;
		
		$r = 0;
		$t0 = 0;
		$t1 = 0;
		$t2 = 0;
		$t3 = 0;
		
		$b = GPG_Utility::pack_octets($block);
		$rounds = $ctx->rounds;
		$b0 = $b[0];
		$b1 = $b[1];
		$b2 = $b[2];
		$b3 = $b[3];
		
		for($r = 0; $r < $rounds - 1; $r++) {
			$t0 = $b0 ^ $ctx->rk[$r][0];
			$t1 = $b1 ^ $ctx->rk[$r][1];
			$t2 = $b2 ^ $ctx->rk[$r][2];
			$t3 = $b3 ^ $ctx->rk[$r][3];
			
			$b0 = $T1[$t0 & 255] ^ $T2[($t1 >> 8) & 255] ^ $T3[($t2 >> 16) & 255] ^ $T4[GPG_Utility::zshift($t3, 24)];
			$b1 = $T1[$t1 & 255] ^ $T2[($t2 >> 8) & 255] ^ $T3[($t3 >> 16) & 255] ^ $T4[GPG_Utility::zshift($t0, 24)];
			$b2 = $T1[$t2 & 255] ^ $T2[($t3 >> 8) & 255] ^ $T3[($t0 >> 16) & 255] ^ $T4[GPG_Utility::zshift($t1, 24)];
			$b3 = $T1[$t3 & 255] ^ $T2[($t0 >> 8) & 255] ^ $T3[($t1 >> 16) & 255] ^ $T4[GPG_Utility::zshift($t2, 24)];
		}
		
		$r = $rounds - 1;
		
		$t0 = $b0 ^ $ctx->rk[$r][0];
		$t1 = $b1 ^ $ctx->rk[$r][1];
		$t2 = $b2 ^ $ctx->rk[$r][2];
		$t3 = $b3 ^ $ctx->rk[$r][3];
		
		$b[0] = GPG_Cipher::F1($t0, $t1, $t2, $t3) ^ $ctx->rk[$rounds][0];
		$b[1] = GPG_Cipher::F1($t1, $t2, $t3, $t0) ^ $ctx->rk[$rounds][1];
		$b[2] = GPG_Cipher::F1($t2, $t3, $t0, $t1) ^ $ctx->rk[$rounds][2];
		$b[3] = GPG_Cipher::F1($t3, $t0, $t1, $t2) ^ $ctx->rk[$rounds][3];
		
		return GPG_Utility::unpack_octets($b);
	}
}

?>