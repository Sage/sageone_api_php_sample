<?php

class SageoneSigner {
	private $request_method;
	private $url;
	private $request_body_params;
	private $nonce;
	private $secret;
	private $token;
	private $sageone_guid;

	/**
	* @param string $request_method The request method
	* @param string $url The url of the request
	* @param string $request_body_params A JSON string representing the post data
	* @param string $nonce The nonce
	* @param string $secret Your application's signing_secret
	* @param string $token Your access_token obtained during authentication
	* @param string $sageone_guid Your resource_owner_id obtained during authentication
	* @example new SageoneSigner("get", $request_url, array(), $nonce, $signing_secret, $access_token)
	*/
	public function __construct($request_method, $url, $request_body_params, $nonce, $secret, $token, $sageone_guid) {
		$this->request_method = $request_method;
		$this->url = $url;
		$this->request_body_params = $request_body_params;
		$this->nonce = $nonce;
		$this->secret = $secret;
		$this->token = $token;
		$this->sageone_guid = $sageone_guid;
	}

	/* generate the HMAC-SHA1 signature */
	public function signature() {
		return base64_encode(hash_hmac('sha1', $this->signatureBaseString() ,$this->signingKey(), true));
	}

	/* Return the http request method in upper case */
	private function requestMethod() {
		return strtoupper($this->request_method);
	}

	/* Return the base URL */
	private function baseUrl() {
		$base_array = array();
		$base_array[] = $this->parsedUrl()['scheme'];
		$base_array[] = "://";
		$base_array[] = $this->parsedUrl()['host'];
		if (array_key_exists('port', $this->parsedUrl())) {
			$base_array[] = ":{$this->parsedUrl()['port']}";
		}
		$base_array[] = $this->parsedUrl()['path'];

		return join("", $base_array);
	}

	/* Build the parameter string */
	private function parameterString() {
		$sortedParams = $this->sortParams();
		$count = 0;
		$parameter_string = "";
		$last = sizeof($sortedParams) - 1;

		foreach ($sortedParams as $key => $value) {
			if ($count == $last) {
				$parameter_string .= $key."=".$value;
			} else {
				$parameter_string .= $key."=".$value."&";
			}

			$count++;
		}

		unset($count);
		unset($last);
		unset($sortedParams);

		return $parameter_string;
	}

	/* Build the signature base string */
	private function signatureBaseString() {
		$str = $this->requestMethod();
		$str .= "&";
		$str .= rawurlencode($this->baseUrl());
		$str .= "&";
		$str .= rawurlencode($this->parameterString());
		$str .= "&";
		$str .= rawurlencode($this->nonce);
		$str .= "&";
		$str .= rawurlencode($this->sageone_guid);

		return $str;
	}

	/* Build the signing key */
	private function signingKey() {
		$key = rawurlencode($this->secret);
		$key .= "&";
		$key .= rawurlencode($this->token);

		return $key;
	}

	/* get the query params */
	private function queryParams() {
		if (array_key_exists('query', $this->parsedUrl())) {
			parse_str($this->parsedUrl()['query'], $output);
			return $output;
		} else {
			return array();
		}
	}

	/* base64 encode the request body */
	private function encodedBody() {
		$body = array();
		$encoded = base64_encode($this->request_body_params);
		$body["body"] = $encoded;
		return $body;
	}

	/* Encode keys and values then return the sorted array of params */
	private function sortParams() {
		$merged = array_merge($this->queryParams(),$this->encodedBody());
		$sorted = array();

		foreach ($merged as $key => $value) {
			$sorted[rawurlencode($key)] = rawurlencode($value);
		}

		ksort($sorted);
		unset($key);
		unset($value);
		unset($merged);
		return $sorted;
	}

	/* parse the url into an array */
	private function parsedUrl() {
	  return parse_url($this->url);
	}
}

?>
