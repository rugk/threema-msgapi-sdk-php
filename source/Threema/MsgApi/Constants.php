<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\MsgApi;

final class Constants {
	const PUBLIC_KEY_PREFIX = 'public:';
	const PRIVATE_KEY_PREFIX = 'private:';
	const DEFAULT_PINNED_KEY = 'sha256//PI1YNwkAgVLVmnydc84An+4reEMvoXcYCEgFP0WEF2Y=;sha256//8SLubAXo6MrrGziVya6HjCS/Cuc7eqtzw1v6AfIW57c=';

	/**
	 * create instance disabled
	 */
	private function __construct() {}

	/**
	 * clone instance disabled
	 */
	private function __clone() {}
}
