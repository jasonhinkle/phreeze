<?
/*
phpApplication v1.0 by nathan@0x00.org
http://0x00.org/php/phpApplication/
*/

	
	class phpApplication {
		var $name=NULL;
		var $fp=NULL;
		var $locked=FALSE;
		var $storage_dir;
		var $umask;
		function phpApplication($appname, $storage_dir = '/tmp/', $umask = 0077) {
			$this->name=$appname;

			$this->storage_dir = $storage_dir;
			$this->umask = $umask;

			if (!$this->_openfile()) {
				$this->_error("Error opening storage file", E_USER_WARNING);
			}
		}

		function set($var, $value=NULL) {
			if (!$this->fp) return FALSE;
			$ilocked=$this->_trylock();
			if ($ilocked===NULL) return FALSE;
			$vars=$this->_unserializeread();
			if (!is_array($vars)) $vars=array();
			$setvars=array();
			if (!is_array($var)) {
				$setvars[$var]=$value;
			} else {
				$setvars=$var;
			}
			
			foreach ($setvars as $k=>$v) {
				$vars[$k]=$v;
			}
			
			// crappy workaround for removing value
			if ($value == NULL)
			{
				//echo "<p>BEFORE:</p>" . join(array_keys($vars),"<br>");
				unset($vars[$var]);
				//echo "<p>AFTER:</p>" . join(array_keys($vars),"<br>");
				//die();
			}

			$this->_serializewrite($vars);
			if ($ilocked) {
				$this->unlock();
			}
		}
		
		function reset() {
			if (!$this->fp) return FALSE;
			$ilocked=$this->_trylock();
			if ($ilocked===NULL) return FALSE;
			$vars=array();

			$this->_serializewrite($vars);
			if ($ilocked) {
				$this->unlock();
			}
		}
		
		function get($var) {
			$vars=$this->getall();
			if ($vars===FALSE) return FALSE;
			if (!is_array($var)) {
				if (array_key_exists($var,$vars)) {
					$ret=$vars[$var];
				} else {
					$ret = null;
				}
			} else {
				$ret=array();
				foreach ($var as $v) {
					$ret[$v]=$vars[$v];
				}
			}
			return $ret;
		}

		function getall() {
			if (!$this->fp) return FALSE;
			$ilocked=$this->_trylock();
			if ($ilocked===NULL) return FALSE;
			$vars=$this->_unserializeread();
			if ($ilocked) {
				$this->unlock();
			}
			return $vars;
		}


		function shared_lock() {
			if (!$this->fp) return FALSE;
			$ret=flock($this->fp, LOCK_SH);
			if ($ret) $this->locked=TRUE;
			return $ret;
		}
		
		function lock() {
			if (!$this->fp) return FALSE;
			$ret=flock($this->fp, LOCK_EX);
			if ($ret) $this->locked=TRUE;
			return $ret;
		}
		
		function unlock() {
			if (!$this->fp) return FALSE;
			$ret=flock($this->fp, LOCK_UN);
			$this->locked=FALSE;
			return $ret;
		}
		
		function _trylock() {
			if (!$this->fp) return NULL;
			/* if already locked the user wants to handle locking */
			if ($this->locked) return FALSE;
			if ($this->lock()) return TRUE;
			return NULL;
		}
		
		function _serializewrite($vars) {
			$this->_write(serialize($vars));
		}
			
		function _unserializeread() {
			$data=$this->_read();
			return unserialize($data);
		}
		
		function _read() {
			rewind($this->fp);
			$buff="";
			while (($l=fread($this->fp, 1024))) {
				$buff.=$l;
			}
			return $buff;
		}
		
		function _write($data) {
			ftruncate($this->fp, 0);
			rewind($this->fp);
			fwrite($this->fp, $data, strlen($data));
			fflush($this->fp);
		}
		
		function _openfile() {
			//print "FILE: " . $this->_file();
			$pumask=umask($this->umask);
			$this->fp=fopen($this->_file(), "a+");
			return $this->fp;
		}
		
		function _closefile() {
			return fclose($this->fp);
		}
		
		function _error($err, $type) {
			trigger_error($err, $type);
		}

		function _file() {
			return $this->storage_dir . "/phpApplication-" . $this->name;
		}
	}
?>