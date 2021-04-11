<?php

/**
 * ------------------------------------------------------------------------------------------------
 * HidePrefix, a MediaWiki extension for hiding prefix in links and page titles.
 * Copyright (C) 2012 Van de Bugger.
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License along with this
 * program.  If not, see <https://www.gnu.org/licenses/>.
 * ------------------------------------------------------------------------------------------------
 */

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;

class HidePrefix {

	/**
	 * Hide prefix in links.
	 *
	 * @param LinkRenderer $linkRenderer
	 * @param LinkTarget $target
	 * @param string &$text
	 * @param array &$extraAttribs
	 * @param string &$query
	 * @param string &$ret
	 * @return bool
	 */
	public static function onHtmlPageLinkRendererBegin(
		LinkRenderer $linkRenderer,
		LinkTarget $target,
		&$text,
		&$extraAttribs,
		&$query,
		&$ret
	) {
		if ( isset( $text ) ) {
			// Hmm... Sometimes `$text' is not a string but an object of class `Message'...
			if ( is_string( $text ) ) {
				$title = Title::newFromText( $text );
				$targetTitle = Title::newFromLinkTarget( $target );
				if ( $title !== null && $targetTitle && $title->getPrefixedText() == $targetTitle->getPrefixedText() ) {
					$text = $target->getText();
				}
			}
		} else {
			$text = $target->getText();
		}
		return true;
	}

	/**
	 * Hide prefix in page title.
	 *
	 * @param OutputPage &$out
	 * @param Skin &$sk
	 */
	public static function onBeforePageDisplay( &$out, &$sk ) {
		if ( !$out->isArticle() ) {
			return;
		}

		$title = $out->getTitle();
		if ( !$title instanceof Title ) {
			return;
		}

		if ( $out->getPageTitle() == $title->getPrefixedText() ) {
			$out->setPageTitle( $title->getText() );
		}
	}
}
