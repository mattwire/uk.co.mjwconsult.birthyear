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

//var birth_year_field = "#custom_BIRTH_YEAR_FIELD_1";
var birth_year_field = "input[id^=custom_BIRTH_YEAR_FIELD_]";
cj(birth_year_field).change(function() {
    var value = cj(birth_year_field).val();
    var current_year = new Date().getFullYear();
    var current_year_short = current_year % 100;

    if (value != undefined && value.length > 0) {
        var int_value = parseInt(value);

        if (int_value < 100) {
            // two-digit values will get adjusted
            if (int_value > current_year_short) {
                int_value = 1900 + int_value;
            } else {
                int_value = 2000 + int_value;
            }
        }

        // every value not between 1900 and 2100 is rejected
        if (int_value < 1900 || int_value > current_year) {
            int_value = 0;
        }

        // re-assign value to field
        if (int_value) {
            cj(birth_year_field).val(int_value);
        } else {
            cj(birth_year_field).val('');
        }
    }
});
