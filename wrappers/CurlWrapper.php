<?php
/*
 * 2011 LutherSloan
 */

/**
 * Curl Wrapper for p11
 *
 * @package    p11
 * @subpackage wrappers
 * @author     LutherSloan
 * @version    2011.04
 */
class CurlWrapper
{
	
	/**
	 * Curl handler
	 * @var resource
	 */
	private $ch;
	
	private $timeout = 60;
	
	/**
	 * init.
	 * @param string $url
	 */
	public function init($url)
	{
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_URL, $url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->ch, CURLOPT_VERBOSE, 0);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$this->exec();
	}
	
	private function exec()
	{
		$this->result = curl_exec($this->ch);
		$this->httpStatus = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);		
		curl_close ($this->ch);
	}

	
	public function initFtp($url, $file, $username, $password)
	{
 		$fp = fopen($file, 'r');
 		$basename = basename($file);
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_URL, 'ftp://'.$username.':'.$password.'@'.$url.'/'.$basename);
		curl_setopt($this->ch, CURLOPT_UPLOAD, 1);
		curl_setopt($this->ch, CURLOPT_INFILE, $fp);
	 	curl_setopt($this->ch, CURLOPT_INFILESIZE, filesize($file));
		$this->exec();
	}
	
	/**
	 * Get result
	 * 
	 * @param string $url
	 * 
	 * @return string
	 */
	public function getResult($url)
	{
		$this->init($url);
		if ($this->httpStatus != 200) {
			throw new Exception('Error: '.$this->httpStatus);
		}
		return $this->result;
	}
	
	/**
	 * Set Timeout
	 * @param integer $timeout
	 */
	public function setTimeout($timeout)
	{
		$this->timeout = $timeout;
	}
	
	/**
	 * Timeout
	 * @return integer
	 */
	public function getTimeout()
	{
		return $this->timeout;
	}
	
	/**
	 * get Http Status
	 * 
	 * @return integer
	 */
	public function getHttpStatus()
	{
		return $this->httpStatus;
	}
	
	/**
	 * Close
	 */
	public function close()
	{
		curl_close($this->ch);
	}
}