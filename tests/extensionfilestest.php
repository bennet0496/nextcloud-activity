<?php
/**
 * ownCloud
 *
 * @author Joas Schilling
 * @copyright 2015 Joas Schilling nickvergessen@owncloud.com
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Activity\Tests;

use OCA\Activity\Data;
use OCA\Activity\Extension\Files;

class ExtensionFilesTest extends TestCase {
	/** @var \OCA\Activity\Extension\Files */
	protected $filesExtension;

	protected function setUp() {
		parent::setUp();
		$this->filesExtension = new Files(\OCP\Util::getL10N('activity', 'en'));
	}

	public function testGetNotificationTypes() {
		$this->assertFalse($this->filesExtension->getNotificationTypes('en'));
	}

	public function testFilterNotificationTypes() {
		$this->assertFalse($this->filesExtension->filterNotificationTypes('', ''));
	}

	public function testGetDefaultTypes() {
		$this->assertFalse($this->filesExtension->getDefaultTypes(''));
	}

	public function dataTranslate() {
		return [
			['AnotherApp', '', false],
			['files', 'AnotherApp', false],
			['files', 'created_self', true],
			['files', 'created_by', true],
			['files', 'created_public', true],
			['files', 'changed_self', true],
			['files', 'changed_by', true],
			['files', 'deleted_self', true],
			['files', 'deleted_by', true],
			['files', 'restored_self', true],
			['files', 'restored_by', true],
			['files', 'shared_user_self', true],
			['files', 'shared_group_self', true],
			['files', 'shared_with_by', true],
			['files', 'shared_link_self', true],
		];
	}

	/**
	 * @dataProvider dataTranslate
	 *
	 * @param string $app
	 * @param string $text
	 * @param bool $expected
	 */
	public function testTranslate($app, $text, $expected) {
		$return = $this->filesExtension->translate($app, $text, ['param1', 'param2'], false, false, 'en');
		$this->assertReturnIsNotFalse($return, $expected);
	}

	public function dataGetSpecialParameterList() {
		return [
			['AnotherApp', 'created_self', false],
			['files', 'AnotherApp', false],
			['files', 'created_self', ['file', 'username']],
			['files', 'created_by', ['file', 'username']],
			['files', 'created_public', ['file', 'username']],
			['files', 'changed_self', ['file', 'username']],
			['files', 'changed_by', ['file', 'username']],
			['files', 'deleted_self', ['file', 'username']],
			['files', 'deleted_by', ['file', 'username']],
			['files', 'restored_self', ['file', 'username']],
			['files', 'restored_by', ['file', 'username']],
			['files', 'shared_user_self', ['file', 'username']],
			['files', 'shared_group_self', ['file']],
			['files', 'shared_with_by', ['file', 'username']],
			['files', 'shared_link_self', ['file', 'username']],
		];
	}

	/**
	 * @dataProvider dataGetSpecialParameterList
	 *
	 * @param string $app
	 * @param string $subject
	 * @param mixed $expected
	 */
	public function testGetSpecialParameterList($app, $subject, $expected) {
		$this->assertEquals($expected, $this->filesExtension->getSpecialParameterList($app, $subject));
	}

	public function dataGetTypeIcon() {
		return [
			[Data::TYPE_SHARED, true],
			[Data::TYPE_SHARE_CREATED, true],
			[Data::TYPE_SHARE_CHANGED, true],
			[Data::TYPE_SHARE_DELETED, true],
			[Data::TYPE_SHARE_RESTORED, false],
			['AnotherApp', false],
		];
	}

	/**
	 * @dataProvider dataGetTypeIcon
	 *
	 * @param string $type
	 * @param bool $expected
	 */
	public function testGetTypeIcon($type, $expected) {
		$this->assertReturnIsNotFalse($this->filesExtension->getTypeIcon($type), $expected);
	}


	public function dataGetGroupParameter() {
		return [
			['AnotherApp', 'created_self', false],
			['files', 'AnotherApp', false],
			['files', 'created_self', 0],
			['files', 'created_by', 0],
			['files', 'created_public', false],
			['files', 'changed_self', 0],
			['files', 'changed_by', 0],
			['files', 'deleted_self', 0],
			['files', 'deleted_by', 0],
			['files', 'restored_self', 0],
			['files', 'restored_by', 0],
			['files', 'shared_user_self', false],
			['files', 'shared_group_self', false],
			['files', 'shared_with_by', false],
			['files', 'shared_link_self', false],
		];
	}

	/**
	 * @dataProvider dataGetGroupParameter
	 *
	 * @param string $app
	 * @param string $subject
	 * @param mixed $expected
	 */
	public function testGetGroupParameter($app, $subject, $expected) {
		$this->assertSame($expected, $this->filesExtension->getGroupParameter(['app' => $app, 'subject' => $subject]));
	}

	public function testGetNavigation() {
		$this->assertFalse($this->filesExtension->getNavigation(''));
	}

	public function testIsFilterValid() {
		$this->assertFalse($this->filesExtension->isFilterValid(''));
	}

	public function testGetQueryForFilter() {
		$this->assertFalse($this->filesExtension->getQueryForFilter(''));
	}

	/**
	 * @param mixed $return
	 * @param bool $expected
	 */
	protected function assertReturnIsNotFalse($return, $expected) {
		if ($expected) {
			$this->assertNotFalse($return);
		} else {
			$this->assertFalse($return);
		}
	}
}
