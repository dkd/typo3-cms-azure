<?php
namespace TYPO3\CMS\Install\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Basic Service to check and create install tool files
 */
class EnableFileService {

	/**
	 * @constant Relative path to ENABLE_INSTALL_TOOL file
	 */
	const INSTALL_TOOL_ENABLE_FILE_PATH = 'typo3conf/ENABLE_INSTALL_TOOL';

	/**
	 * @constant Relative path to  FIRST_INSTALL file
	 */
	const FIRST_INSTALL_FILE_PATH = 'FIRST_INSTALL';

	/**
	 * @constant Maximum age of ENABLE_INSTALL_TOOL file before it gets removed (in seconds)
	 */
	const INSTALL_TOOL_ENABLE_FILE_LIFETIME = 3600;

	/**
	 * @return bool
	 */
	static public function isFirstInstallAllowed() {
		// if (!is_dir(PATH_typo3conf) && is_file(self::getFirstInstallFilePath())) {
        // azure is_file() shipping typo3conf so we cannot check if typo3conf does not exists
        if (is_file(self::getFirstInstallFilePath())) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Creates the INSTALL_TOOL_ENABLE file
	 *
	 * @return boolean
	 */
	static public function createInstallToolEnableFile() {
		$installEnableFilePath = self::getInstallToolEnableFilePath();
		if (!is_file($installEnableFilePath)) {
			$result = touch($installEnableFilePath);
		} else {
			$result = TRUE;
			self::extendInstallToolEnableFileLifetime();
		}
		\TYPO3\CMS\Core\Utility\GeneralUtility::fixPermissions($installEnableFilePath);
		return $result;
	}

	/**
	 * Removes the INSTALL_TOOL_ENABLE file
	 *
	 * @return boolean
	 */
	static public function removeInstallToolEnableFile() {
		return unlink(self::getInstallToolEnableFilePath());
	}

	/**
	 * Removes the FIRST_INSTALL file
	 *
	 * @return boolean
	 */
	static public function removeFirstInstallFile() {
		return unlink(self::getFirstInstallFilePath());
	}

	/**
	 * Checks if the install tool file exists
	 *
	 * @return bool
	 */
	static public function installToolEnableFileExists() {
		return @is_file(self::getInstallToolEnableFilePath());
	}

	/**
	 * Checks if the install tool file exists
	 *
	 * @return boolean
	 */
	static public function checkInstallToolEnableFile() {
		if (!self::installToolEnableFileExists()) {
			return FALSE;
		}
		if (!self::isInstallToolEnableFilePermanent()) {
			if (self::installToolEnableFileLifetimeExpired()) {
				self::removeInstallToolEnableFile();
				return FALSE;
			}
			self::extendInstallToolEnableFileLifetime();
		}
		return TRUE;
	}

	/**
	 * Checks if the install tool file should be kept
	 *
	 * @return bool
	 */
	static public function isInstallToolEnableFilePermanent() {
		if (self::installToolEnableFileExists()) {
			$content = @file_get_contents(self::getInstallToolEnableFilePath());
			if (strpos($content, 'KEEP_FILE') !== FALSE) {
				return TRUE;
			}
		}
		return FALSE;
	}


	/**
	 * Checks if the lifetime of the install tool file is expired
	 *
	 * @return bool
	 */
	static public function installToolEnableFileLifetimeExpired() {
		if (time() - @filemtime(self::getInstallToolEnableFilePath()) > self::INSTALL_TOOL_ENABLE_FILE_LIFETIME) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Updates the last modification of the ENABLE_INSTALL_TOOL file
	 *
	 * @return void
	 */
	static protected function extendInstallToolEnableFileLifetime() {
		$enableFile = self::getInstallToolEnableFilePath();
		// Extend the age of the ENABLE_INSTALL_TOOL file by one hour
		if (is_file($enableFile)) {
			$couldTouch = @touch($enableFile);
			if (!$couldTouch) {
				// If we can't remove the creation method will call us again.
				if (self::removeInstallToolEnableFile()) {
					self::createInstallToolEnableFile();
				}
			}
		}
	}

	/**
	 * Returns the path to the INSTALL_TOOL_ENABLE file
	 *
	 * @return string
	 */
	static protected function getInstallToolEnableFilePath() {
		return PATH_site . self::INSTALL_TOOL_ENABLE_FILE_PATH;
	}

	/**
	 * Returns the path to the FIRST_INSTALL file
	 *
	 * @return string
	 */
	static protected function getFirstInstallFilePath() {
		return PATH_site . self::FIRST_INSTALL_FILE_PATH;
	}
}
