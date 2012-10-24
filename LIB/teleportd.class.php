<?php
/**
 * Copyright Teleportd Ltd
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so, subject to the
 * following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
 * NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
class Teleportd
{
	public $user_key;
	public $srv = 'api.v2.teleportd.com';
	public $port = 80;

	public function __construct($user_key, $srv = null, $port = null)
	{
		$this->user_key = $user_key;

		if($srv!==null) {
			$this->srv = $srv;
		}

		if($port!==null) {
			$this->port = $port;
		}
	}

	/**
	 * @param array $args associative array of arguments (str, loc, period)
	 *
	 * Example:
	 *
	 * array(
	 * 		'str' => 'paris',
	 * 		'loc' => array(42.3, 2.5, 4, 4)
	 * )
	 */
	public function search($args)
	{
		return $this->__execute('search', $args)->hits;
	}

	/**
	 * @param string $sha the sha hash of the image to get
	 */
	public function get($sha)
	{
		return $this->__execute('get', array('sha' => $sha))->hit;
	}


	/**
	 * Executes a GET HTTP request to the server with the given method and arguments
	 * @param string $method the name of the method to call
	 * @param array $args an array of arguments
	 * @throws TeleportdException if connection failed or an error occured
	 * @return mixed
	 */
	protected function __execute($method, $args)
	{
		$data = false;
		$path = '/' . $method . '?user_key=' . $this->user_key . '&' . http_build_query($args);

		$fp = @fsockopen($this->srv, $this->port, $errno, $errstr, 1);

		if($fp) {
			stream_set_timeout($fp, 1);
			$out = "GET " . $path . " HTTP/1.0\r\n";
			$out .= "Host: " . $this->srv . "\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Pragma: no-cache\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "\r\n";

			if(fwrite($fp, $out)) {
				$hdrs = "";
				$body = "";
				$switch = false;
				while(!feof($fp)) {
					$data = true;
					$line = fgets($fp, 128);
					if(!$switch) {
						if($line == "\r\n") {
							$switch = true;
						} else {
							$hdrs .= $line;
						}
					} else {
						$body .= $line;
					}
				}
				fclose($fp);
			}
		}

		if(!$data) {
			throw new TeleportdException('Connection failed', 1);
		} else {
			$json = json_decode(mb_convert_encoding($body, 'UTF-8'));

			if($json) {
				if($json->ok) {
					return $json;
				} else {
					throw new TeleportdException($json->error, 3);
				}
			} else {
				throw new TeleportdException('Parsing failed', 2);
			}
		}
	}
}

class TeleportdException extends Exception {}