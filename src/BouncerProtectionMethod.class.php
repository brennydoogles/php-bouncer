<?php
	/**
	 * Created with JetBrains PhpStorm.
	 * User: Brendon Dugan <wishingforayer@gmail.com>
	 * Date: 7/28/12
	 * Time: 7:41 AM
	 *
	 */
	final class BouncerProtectionMethod{
		// Enums are awesome, and not at all supported by PHP, which is a shame because there is no
		// better way to provide a list of allowed values to developers who are developing against
		// your code. This class emulates some of the functionality of Enums.
		//
		// Usage: BouncerProtectionMethod::Session or BouncerProtectionMethod::Get
		const Session = 0;
		const Get     = 1;
		const None    = -1;

		private function __construct(){

		}
	}
