<?php
/*-----------------------------------------------------------------------------------*/
/* Removes Cookie Consent Checkbox if Cookies are not accepted
/*-----------------------------------------------------------------------------------*/
function custom_cookies_field($field) {
  if (function_exists('cn_cookies_accepted') && !cn_cookies_accepted()) {
    $field = '';
  }
  return $field;
}
add_filter('comment_form_field_cookies','custom_cookies_field');

/*-----------------------------------------------------------------------------------*/
/* Add Custom GDPR Checkbox to Comment Form
/*-----------------------------------------------------------------------------------*/
function addField($submitField = '') {
  $policy_link = '';
  if (get_option('wp_page_for_privacy_policy')) {
    $policy_link = ' <a href="' . get_permalink(get_option('wp_page_for_privacy_policy')) . '">' . __('Privacy Policy','inis-b') . '</a>';
  }
  $field = '<p class="comment-gdpr-checkbox"><label><input type="checkbox" name="comment_gdpr" id="comment_gdpr" value="1" />' . __('By using this form you agree with the storage and handling of your data by this website.','inis-b') . $policy_link . '<abbr class="required" title="required">*</abbr></label></p>';
  return $field . $submitField;
}
add_filter('comment_form_submit_field','addField', 999);

/*-----------------------------------------------------------------------------------*/
/* Checks if GDPR Checkbox is checked
/*-----------------------------------------------------------------------------------*/
function checkPost() {
  if (!isset($_POST['comment_gdpr'])) {
    wp_die(
      '<p>' . __('<strong>ERROR</strong>: Please click our privacy policy checkbox.', 'inis-b') . '</p>',
      __('Comment Submission Failure'),
      array('back_link' => true)
    );
  }
}
add_action('pre_comment_on_post','checkPost');

/*-----------------------------------------------------------------------------------*/
/* Adds GDPR Date to Meta Field
/*-----------------------------------------------------------------------------------*/
function addAcceptedDateToCommentMeta($commentId = 0) {
  if (isset($_POST['comment_gdpr']) && !empty($commentId)) {
    add_comment_meta($commentId, '_comment_gdpr', time());
  }
}
add_action('comment_post','addAcceptedDateToCommentMeta');

/*-----------------------------------------------------------------------------------*/
/* Add Custom GDPR Column in Admin Area
/*-----------------------------------------------------------------------------------*/
function displayAcceptedDateColumnInCommentOverview($columns = array()) {
  $columns['comment_gdpr'] = __('GDPR Accepted On', 'inis-b');
  return $columns;
}
add_filter('manage_edit-comments_columns','displayAcceptedDateColumnInCommentOverview');

/*-----------------------------------------------------------------------------------*/
/* Inserts GDPR Meta Field Value in Column Field
/*-----------------------------------------------------------------------------------*/
function displayAcceptedDateInCommentOverview($column = '', $commentId = 0) {
  if ($column === 'comment_gdpr') {
    $date = get_comment_meta($commentId, '_comment_gdpr', true);
    date_default_timezone_set(get_option('timezone_string'));
    $value = (!empty($date)) ? date(get_option('date_format') . ' ' . get_option('time_format'), $date) : __('Not accepted.', 'inis-b');
    echo $value;
  }
  return $column;
}
add_action('manage_comments_custom_column','displayAcceptedDateInCommentOverview', 10, 2);
