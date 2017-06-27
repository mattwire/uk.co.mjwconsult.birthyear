/*-------------------------------------------------------+
 | Birth Year Custom Field                                |
 | Copyright (C) 2017 SYSTOPIA                            |
 | Copyright (C) 2017 MJW Consulting                      |
 +--------------------------------------------------------+
 | This program is released as free software under the    |
 | Affero GPL license. You can redistribute it and/or     |
 | modify it under the terms of this license which you    |
 | can read by viewing the included agpl.txt or online    |
 | at www.gnu.org/licenses/agpl.html. Removal of this     |
 | copyright header is strictly prohibited without        |
 | written permission from the original author(s).        |
 +--------------------------------------------------------*/

var birthyear_extended_demographics = "#custom-set-content-EXTENDED_DEMOGRAPHICS";

/**
 * Make birth year group and demographics mutually data-dependent
 */
function birthday_data_dependencies() {
    // add demographic -> extended demographic dependency
    var current_value = cj("#crm-demographic-content").attr('data-dependent-fields');
    var fields = eval(current_value);
    if (fields) {
        if (fields.indexOf(birthyear_extended_demographics) == -1) {
            fields.push(birthyear_extended_demographics);
            cj("#crm-demographic-content").attr('data-dependent-fields', JSON.stringify(fields));
        }
    }

    // add extended demographic -> demographic dependency
    var current_value = cj(birthyear_extended_demographics).attr('data-dependent-fields');
    var fields = [];
    if (current_value != undefined) {
        fields = eval(current_value);
    }
    if (fields) {
        if (fields.indexOf("#crm-demographic-content") == -1) {
            fields.push("#crm-demographic-content");
            cj(birthyear_extended_demographics).attr('data-dependent-fields', JSON.stringify(fields));
        }
    }
}

cj(document).ready(function () {
    // inject birthday dependencies
    birthday_data_dependencies();

    // inject data dependency after reload
    cj(document).bind("ajaxComplete", birthday_data_dependencies);
});
