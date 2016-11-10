<?php
/**
 * @author Threema GmbH
 * @copyright Copyright (c) 2015-2016 Threema GmbH
 */


namespace Threema\MsgApi;

final class Constants {
	const PUBLIC_KEY_PREFIX = 'public:';
	const PRIVATE_KEY_PREFIX = 'private:';
	const DEFAULT_PINNED_KEY = 'sha256//8SLubAXo6MrrGziVya6HjCS/Cuc7eqtzw1v6AfIW57c=;sha256//8kTK9HP1KHIP0sn6T2AFH3Bq+qq3wn2i/OJSMjewpFw=';

	/**
	 * create instance disabled
	 */
	private function __construct() {}

	/**
	 * clone instance disabled
	 */
	private function __clone() {}
}
