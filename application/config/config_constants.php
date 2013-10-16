<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// LOGIN/DATA/SESSION/COOKIE/URLS

define('NAME_PROJECT', 'Propous');

define('DEFAULT_LANGUAGE', $default_lang_short);

// define('REQUEST_URL_DOMAIN', 'http://192.168.5.3/propous');
// define('REQUEST_URL_API', '/api');
// define('BASE_URL', '/propous');

define('REQUEST_URL_DOMAIN', 'http://10.29.0.139/localtests/propous');
define('REQUEST_URL_API', '/api');
define('BASE_URL', '/localtests/propous');

define('COOKIE_DAYS_EXPIRATION', 365);
define('TIME_MILISECONDS_ALERTS_HIDE', 4500);

define('SHOW_DETAILED_ERROR_INFO', true);

// CAPTCHAS

define('USE_CAPTHAS_IN_FORMS', "true");
define('CAPTCHA_SECONDS_EXPIRATION_TIME', 365);
define('CAPTCHA_IMAGE_WIDTH', 200);
define('CAPTCHA_IMAGE_HEIGHT', 60);
define('CAPTCHA_FONT_SIZE', 22);

// VALIDATION

define('VALIDATE_ID_MIN_VALUE', 1);
define('VALIDATE_NUMBER_MIN_VALUE', 1);

// CATEGORIES

define('MAX_CATEGORIES_IN_PROPOSAL', 5);

// SOCIALITIES

define('MAX_SOCIALITIES_IN_USER', 5);

// PHOTOS

define('PHOTO_NAME_MULTIPLIER', 10000);
define('PHOTO_MAX_SIZE', '1200');
define('PHOTO_MAX_WIDTH', '1624');
define('PHOTO_MAX_HEIGHT', '1624');
define('PHOTO_OVERWRITE', true);
define('PHOTO_ALLOWED_TYPES', 'gif|jpg|png|jpeg');

define('PHOTOS_IN_CARROUSEL', 5);

define('DEFAULT_NO_PHOTO_NAME', 'no_photo.png');

define('MAIN_PHOTO_WIDTH', 200);
define('MAIN_PHOTO_HEIGHT', 266);

// PREMIUM

define('PREMIUM_PREMIUM_DIAMOND_DURATION', 30);
define('PREMIUM_PREMIUM_DIAMOND_PROPOSALS', 200);

define('PREMIUM_PREMIUM_GOLD_DURATION', 7);
define('PREMIUM_PREMIUM_GOLD_PROPOSALS', 100);

define('PREMIUM_PREMIUM_SILVER_DURATION', 7);
define('PREMIUM_PREMIUM_SILVER_PROPOSALS', 20);

define('PREMIUM_PREMIUM_DURATION', 7);
define('PREMIUM_PREMIUM_PROPOSALS', 10);

define('PREMIUM_FREE_PROPOSALS', 3);

// PROPOSAL

define('PROPOSAL_MAX_TO_GET_IN_LISTS', 2000);

define('PROPOSAL_NAME_MIN_SIZE', 3);
define('PROPOSAL_NAME_MAX_SIZE', 200);
define('PROPOSAL_NAME_MAX_SIZE_WRAP', 100);

define('PROPOSAL_SECONDS_TO_MAKE_EDITABLE', 86400); // 24h
define('PROPOSAL_SECONDS_TIME_TO_MAKE_FREE_PROPOSALS', 86400);

// USER

define('USER_NAME_MIN_SIZE', 3);
define('USER_NAME_MAX_SIZE', 150);
define('USER_PASSWORD_MIN_SIZE', 3);
define('USER_PASSWORD_MAX_SIZE', 40);

define('USER_NATIONALITY_MAX_SIZE', 100);
define('USER_DWELLING_MAX_SIZE', 100);
define('USER_CAR_MAX_SIZE', 100);
define('USER_SEXUALITY_MAX_SIZE', 100);
define('USER_CHILDREN_MAX_SIZE', 100);
define('USER_PARTNER_MAX_SIZE', 100);
define('USER_OCCUPATION_MAX_SIZE', 250);
define('USER_PHONE_MAX_SIZE', 100);

define('USER_CONTACT_MESSAGE_MIN_SIZE', 3);

// LANGUAGES

define('LANGUAGES_WITH_DATE_YEAR_TO_DAY', "['en']");
define('LANGUAGES_WITH_DATE_DAY_TO_YEAR', "['es']");

// EMAIL

define('EMAIL_ADDRESS', 'info@propous.com');
define('EMAIL_ADDRESS_INFO', 'Info Propous');

// DATATABLES

define('DATATABLES_SHOW_LENGTH', "[25, 50, 100, 200]");
define('DATATABLES_DEFAULT_LENGHT_MENU',25);
define('DATATABLES_SHOW_FILTER', "false");
define('DATATABLES_SHOW_SORT', "false");
define('DATATABLES_SHOW_INFO', "false");
define('DATATABLES_AUTO_WIDTH', "false");
define('DATATABLES_SAVE_COOKIE_STATE', "true");
define('DATATABLES_PAGINATION_TYPE', "full_numbers");

// MESSAGES

define('TIME_SECONDS_LEAVE_WRITING_STATUS',5);
define('TIME_SECONDS_ACTUALIZE_MESSAGES_INFO', 8);

define('DEFAULT_NUMBER_USERS_MESSAGED_SHOWED', 20);
define('DEFAULT_NUMBER_MESSAGES_SHOWED', 100);

/* End of file config_constants.php */
/* Location: ./application/config/config_constants.php */