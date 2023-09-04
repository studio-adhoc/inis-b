
# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.0.28] - 2023-09-04

### Fixed

- Fixed Headline Margins
- Fixed Block Editor Text Sizes

## [1.0.27] - 2023-08-21

### Fixed

- Added Global Styles Dependency for theme stylesheet
- Added Border Colors to Group Box Style

## [1.0.26] - 2023-08-14

### Changed

- Moved WP Global Styles from Theme CSS to custom PHP file

## [1.0.25] - 2023-08-10

### Added

- Disable Options Pages in ACF

## [1.0.24] - 2023-07-12

### Added

- Plugable Function for inis_b_get_complete_content
- Post List Parameter for Content Display

## [1.0.23] - 2023-05-04

### Fixed

- Fixed CSS typo
- Fixed align wide for block editor

## [1.0.22] - 2023-04-17

### Added

- Disable CPTs und Taxomies in ACF

### Fixed

- Adjust width class in Block Editor

## [1.0.21] - 2023-03-30

### Added

- Added parameter for empty text to post-list shortcode

### Changed

- Removed Openverse Tab from Inserter

## [1.0.20] - 2023-01-18

### Added

- Automatic Thickbox support for images linked to the media file
- TECs Sans Serif Font for the block editor

### Fixed

- Made second parameter in function "get_cpt_category" optional
- Adjust padding for Buttons if last-child in column

## [1.0.19] - 2022-11-04

### Fixed

- Missing Global Styles for WP 6.1: Paragraph Fontsizes

## [1.0.18] - 2022-11-03

### Fixed

- Missing Global Styles for WP 6.1: Buttons und Galleries
- Button CSS for Block Editor Admin

## [1.0.17] - 2022-10-31

### Fixed

- Fixed TEC Add to calendar dropdown display again after translation
- Fixed Legacy Widgets Preview iframe height

## [1.0.16] - 2022-07-04

### Changed

- Adjust Content Display in List Views when Post Type is not public

### Fixed

- Fixed TEC Add to calendar dropdown display

## [1.0.15] - 2022-05-25

### Fixed

- Fixed Block Editor Columns Gap

## [1.0.14] - 2022-05-25

### Fixed

- Fixed Block Editor Columns Gap

## [1.0.13] - 2022-05-25

### Fixed

- Fixed Block Editor Columns Gap
- Fixed thickbox js for refactored block galleries

## [1.0.12] - 2022-02-04

### Added

- Added style for Borlabs Cookies

### Changed

- Removed dependence on Cookie Notice Plugin

### Fixed

- Fixed thickbox js for refactored block galleries

## [1.0.11] - 2022-01-27

### Changed

- Removed WP 5.9 Inline Global Stylesheet
- Removed Full Site Editing Blocks

## [1.0.10] - 2021-11-01

### Changed

- Removed wp_link_pages from content.php

### Fixed

- Fixed CSS for OL-lists
- Fixed CSS for Project Lists
- Fixed CSS for Block Editor Columns

## [1.0.9] - 2021-08-10

### Added

- Add open graph customizer inputs for image and description for post archive

### Changed

- Changed title in index.php to the_archive_title for filtering
- Replace "allowed_block_types" filter with "allowed_block_types_all"

### Fixed

- Fixed Fontsize and Lineheight within the block editor
- Fixed PHP Notice on new Widget Block Editor Page

## [1.0.8] - 2021-07-22

### Added

- Added Body Class for Widget Block iFrame

### Changed

- Adjust Content Display in List Views

### Fixed

- Adjust Block Editor Styles to WP 5.8

## [1.0.7] - 2021-06-07

### Added

- Add open graph customizer inputs for image and description for tribe events post archive

### Changed

- Pluggable function for open graph tag output

### Fixed

- Display Selection when Background is white

## [1.0.6] - 2021-04-26

### Added

- Added styling for embed figcaptions

### Changed

- Changed internal redirect to homepage if website is not active

### Fixed

- Adjust paddings for Cookie Box
- Adjust styling for em/italic and links in the block editor

## [1.0.5] - 2021-03-05

### Added

- Tag support for post-list shortcode
- Add action 'inis_b_before_archive_post_header' in content.php
- Add action 'inis_b_after_archive_post_header' in content.php
- Add action 'inis_b_before_archive_post_content' in content.php
- Add action 'inis_b_after_archive_post_content' in content.php
- Add action 'inis_b_before_single_post_header' in content-single.php
- Add action 'inis_b_after_single_post_header' in content-single.php
- Add action 'inis_b_before_single_post_content' in content-single.php
- Add action 'inis_b_after_single_post_content' in content-single.php
- Add action 'inis_b_after_single_post' in single.php

### Changed

- Changed action 'inis_b_after_single_post_content' to 'inis_b_before_single_post' in single.php
- Consistent use of the_archive_title in archives

### Fixed

- Adjust URL for No JS Stylesheet
- Adjust custom color styles for Tribe Events Calendar
- Adjust UL styling in OL
- Adjust OL styling in UL
- Adjust jQuery for jQuery 3

## [1.0.4] - 2021-01-28

### Changed

- Disable YouTube Videos if Cookie Notice is not accepted

### Fixed

- Adjust Seperator Block Background Color
- Fixed Padding Display for Columns

## [1.0.3] - 2021-01-07

### Added

- Get CPT Taxonomies by Post IDs
- Add action 'inis_b_before_single_post_content' in single.php
- Custom CSS Classes for Sharer Buttons

### Fixed

- Adjust Top Link CSS
- Fixed Admin CSS URL

## [1.0.2] - 2020-12-15

### Added

- Post Thumbnail Captions

### Fixed

- Fixed missing comment display
- Adjust Tribe Events Calendar Button custom color styles

## [1.0.1] - 2020-10-06

### Added

- Show Members CTP in Customizer if ACF Plugin is activated

### Fixed

- Adjust custom color styles for Events Calendar Plugin
- Adjust custom colors for Block Editor
- Adjust padding under embed iframe wrapper

## [1.0] - 2020-08-18

Initial release
